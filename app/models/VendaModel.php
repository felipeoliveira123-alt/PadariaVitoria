<?php
class VendaModel {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    /**
     * Registra uma nova venda e seus itens no banco de dados
     * @param array $dadosVenda - dados da venda (valor_total, usuario_id)
     * @param array $itens - itens do carrinho
     * @return int|false - ID da venda criada ou false em caso de erro
     */    public function registrarVenda($dadosVenda, $itens) {
        $this->conexao->begin_transaction();
        
        try {
            // Inserir a venda
            $stmt = $this->conexao->prepare("INSERT INTO vendas (valor_total, usuario_id) VALUES (?, ?)");
            $stmt->bind_param("di", $dadosVenda['valor_total'], $dadosVenda['usuario_id']);
            
            if (!$stmt->execute()) {
                throw new Exception("Erro ao registrar a venda: " . $stmt->error);
            }
            
            $vendaId = $stmt->insert_id;
            
            // Inserir os itens da venda e atualizar o estoque
            foreach ($itens as $item) {
                $precoUnitario = $item['price'] ?? $item['preco'];
                $quantidade = $item['quantidade'] ?? 1; // Assume 1 se não especificado
                $subtotal = $precoUnitario * $quantidade;
                
                // Verificar se é um item avulso
                $isAvulso = isset($item['tipo']) && $item['tipo'] === 'avulso';
                
                if ($isAvulso) {
                    // Para itens avulsos, produto_id será NULL e is_avulso será TRUE
                    $stmtItem = $this->conexao->prepare("
                        INSERT INTO venda_itens (venda_id, produto_id, quantidade, preco_unitario, subtotal, is_avulso, nome_item) 
                        VALUES (?, NULL, ?, ?, ?, TRUE, ?)
                    ");
                    $nomeItem = $item['nome'] ?? 'Item avulso';
                    $stmtItem->bind_param("iddds", $vendaId, $quantidade, $precoUnitario, $subtotal, $nomeItem);
                } else {
                    // Para produtos normais, preencher produto_id e is_avulso será FALSE
                    $produtoId = $item['id'];
                    $stmtItem = $this->conexao->prepare("
                        INSERT INTO venda_itens (venda_id, produto_id, quantidade, preco_unitario, subtotal, is_avulso) 
                        VALUES (?, ?, ?, ?, ?, FALSE)
                    ");
                    $stmtItem->bind_param("iiddd", $vendaId, $produtoId, $quantidade, $precoUnitario, $subtotal);
                    
                    // Atualizar o estoque apenas para produtos regulares
                    if (!$this->atualizarEstoque($produtoId, $quantidade)) {
                        throw new Exception("Erro ao atualizar o estoque do produto ID $produtoId");
                    }
                }
                
                if (!$stmtItem->execute()) {
                    throw new Exception("Erro ao registrar item da venda: " . $stmtItem->error);
                }
            }
            
            $this->conexao->commit();
            return $vendaId;
            
        } catch (Exception $e) {
            $this->conexao->rollback();
            throw $e;
        }
    }
    
    /**
     * Atualiza o estoque de um produto, deduzindo a quantidade vendida
     * @param int $produtoId - ID do produto
     * @param int $quantidade - quantidade vendida
     * @return bool - true se sucesso, false se erro
     */
    private function atualizarEstoque($produtoId, $quantidade) {
        // Buscar os lotes do produto com validade mais próxima (FIFO)
        $stmt = $this->conexao->prepare("
            SELECT id, quantidade 
            FROM produto_lotes 
            WHERE produto_id = ? AND ativo = 1 AND quantidade > 0
            ORDER BY validade ASC
        ");
        $stmt->bind_param("i", $produtoId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $quantidadeRestante = $quantidade;
        
        while ($quantidadeRestante > 0 && $lote = $result->fetch_assoc()) {
            $loteId = $lote['id'];
            $loteQuantidade = $lote['quantidade'];
            
            $quantidadeARemover = min($quantidadeRestante, $loteQuantidade);
            $novaQuantidade = $loteQuantidade - $quantidadeARemover;
            
            // Atualizar a quantidade do lote
            $stmtUpdate = $this->conexao->prepare("
                UPDATE produto_lotes 
                SET quantidade = ? 
                WHERE id = ?
            ");
            $stmtUpdate->bind_param("ii", $novaQuantidade, $loteId);
            
            if (!$stmtUpdate->execute()) {
                return false;
            }
            
            $quantidadeRestante -= $quantidadeARemover;
        }
        
        // Se ainda houver quantidade restante, criar ou atualizar um lote com valor negativo
        if ($quantidadeRestante > 0) {
            // Buscar o lote mais recente para este produto
            $stmt = $this->conexao->prepare("
                SELECT id 
                FROM produto_lotes 
                WHERE produto_id = ? AND ativo = 1
                ORDER BY validade DESC
                LIMIT 1
            ");
            $stmt->bind_param("i", $produtoId);
            $stmt->execute();
            $loteResult = $stmt->get_result();
            
            if ($lote = $loteResult->fetch_assoc()) {
                // Atualizar o lote mais recente com valor negativo
                $loteId = $lote['id'];
                $stmtUpdate = $this->conexao->prepare("
                    UPDATE produto_lotes 
                    SET quantidade = quantidade - ?
                    WHERE id = ?
                ");
                $stmtUpdate->bind_param("ii", $quantidadeRestante, $loteId);
                
                if (!$stmtUpdate->execute()) {
                    return false;
                }
            } else {
                // Se não houver lotes, criar um novo lote com quantidade negativa
                // Usar data atual + 1 ano como validade padrão para um novo lote
                $dataValidade = date('Y-m-d', strtotime('+1 year'));
                $quantidadeNegativa = -$quantidadeRestante;
                $numeroLote = 'VENDA-' . date('YmdHis');
                
                $stmtInsert = $this->conexao->prepare("
                    INSERT INTO produto_lotes (produto_id, numero_lote, quantidade, validade)
                    VALUES (?, ?, ?, ?)
                ");
                $stmtInsert->bind_param("isis", $produtoId, $numeroLote, $quantidadeNegativa, $dataValidade);
                
                if (!$stmtInsert->execute()) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Converte data do formato brasileiro (DD/MM/AAAA) para o formato MySQL (AAAA-MM-DD)
     * @param string $data Data no formato DD/MM/AAAA
     * @return string Data no formato AAAA-MM-DD ou string vazia se inválida
     */
    private function converterDataParaMySQL($data) {
        if (empty($data)) return '';

        // Verifica se está no formato esperado
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $data)) {
            $partes = explode('/', $data);
            return $partes[2] . '-' . $partes[1] . '-' . $partes[0]; // AAAA-MM-DD
        }

        // Se já estiver no formato MySQL (AAAA-MM-DD), retorna como está
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
            return $data;
        }

        return '';
    }

    /**
     * Lista todas as vendas realizadas com suporte a filtros e paginação
     * @param array $filtros - filtros aplicados (data_inicio, data_fim, vendedor, valor_min, valor_max)
     * @param int $pagina - página atual
     * @param int $itensPorPagina - quantidade de itens por página
     * @return array - lista de vendas e dados de paginação
     */
    public function listarVendas($filtros = [], $pagina = 1, $itensPorPagina = 10) {
        // Construir a consulta base
        $query = "SELECT * FROM vw_relatorio_vendas WHERE 1=1";
        $countQuery = "SELECT COUNT(*) as total FROM vw_relatorio_vendas WHERE 1=1";
        $params = [];
        $types = "";

        // Aplicar filtros
        if (!empty($filtros['data_inicio'])) {
            $query .= " AND DATE(data_venda) >= ?";
            $countQuery .= " AND DATE(data_venda) >= ?";
            $params[] = $filtros['data_inicio'];
            $types .= "s";
        }

        if (!empty($filtros['data_fim'])) {
            $query .= " AND DATE(data_venda) <= ?";
            $countQuery .= " AND DATE(data_venda) <= ?";
            $params[] = $filtros['data_fim'];
            $types .= "s";
        }

        if (!empty($filtros['vendedor'])) {
            $query .= " AND vendedor LIKE ?";
            $countQuery .= " AND vendedor LIKE ?";
            $params[] = "%" . $filtros['vendedor'] . "%";
            $types .= "s";
        }

        if (!empty($filtros['valor_min'])) {
            $query .= " AND valor_total >= ?";
            $countQuery .= " AND valor_total >= ?";
            $params[] = $filtros['valor_min'];
            $types .= "d";
        }

        if (!empty($filtros['valor_max'])) {
            $query .= " AND valor_total <= ?";
            $countQuery .= " AND valor_total <= ?";
            $params[] = $filtros['valor_max'];
            $types .= "d";
        }

        // Ordenação
        $query .= " ORDER BY data_venda DESC";

        // Paginação
        $offset = ($pagina - 1) * $itensPorPagina;
        $query .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $itensPorPagina;
        $types .= "ii";

        // Executar consulta para obter o número total de registros
        $stmtCount = $this->conexao->prepare($countQuery);
        if (!empty($types) && !empty($params)) {
            $countParams = array_slice($params, 0, -2); // Remover os parâmetros de LIMIT
            $countTypes = substr($types, 0, -2); // Remover os tipos de LIMIT

            if (!empty($countTypes)) {
                $stmtCount->bind_param($countTypes, ...$countParams);
            }
        }

        $stmtCount->execute();
        $totalRegistros = $stmtCount->get_result()->fetch_assoc()['total'];
        $totalPaginas = ceil($totalRegistros / $itensPorPagina);

        // Executar consulta principal
        $stmt = $this->conexao->prepare($query);
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $vendas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
          return [
            'vendas' => $vendas,
            'paginacao' => [
                'pagina_atual' => $pagina,
                'itens_por_pagina' => $itensPorPagina,
                'total_registros' => $totalRegistros,
                'total_paginas' => $totalPaginas
            ]
        ];
    }
      /**
     * Busca uma venda pelo ID com seus itens
     * @param int $id - ID da venda
     * @return array - dados da venda e seus itens
     */    public function buscarVendaPorId($id) {
        // Como o id recebido vem da view vw_relatorio_vendas, ele é o campo venda_id da view
        // que na verdade é o id da tabela vendas. Então podemos usar diretamente:
        $stmt = $this->conexao->prepare("
            SELECT v.*, u.nome_completo as usuario_nome
            FROM vendas v
            JOIN usuarios u ON v.usuario_id = u.id
            WHERE v.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $venda = $stmt->get_result()->fetch_assoc();
        
        if (!$venda) {
            return []; // Retorna array vazio em vez de null
        }
          // Buscar os itens da venda, incluindo itens avulsos
        $stmt = $this->conexao->prepare("
            SELECT 
                vi.*,
                CASE 
                    WHEN vi.is_avulso = 1 THEN vi.nome_item
                    ELSE p.nome
                END as produto_nome
            FROM venda_itens vi
            LEFT JOIN produtos p ON vi.produto_id = p.id
            WHERE vi.venda_id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $venda['itens'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return $venda;
    }
}