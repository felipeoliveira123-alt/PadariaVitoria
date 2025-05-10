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
     */
    public function registrarVenda($dadosVenda, $itens) {
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
                $produtoId = $item['id'];
                $precoUnitario = $item['price'] ?? $item['preco'];
                $quantidade = $item['quantidade'] ?? 1; // Assume 1 se não especificado
                $subtotal = $precoUnitario * $quantidade;
                
                // Inserir o item da venda
                $stmtItem = $this->conexao->prepare("
                    INSERT INTO venda_itens (venda_id, produto_id, quantidade, preco_unitario, subtotal) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmtItem->bind_param("iiddd", $vendaId, $produtoId, $quantidade, $precoUnitario, $subtotal);
                
                if (!$stmtItem->execute()) {
                    throw new Exception("Erro ao registrar item da venda: " . $stmtItem->error);
                }
                
                // Atualizar o estoque - usando FIFO (primeiro a expirar, primeiro a sair)
                if (!$this->atualizarEstoque($produtoId, $quantidade)) {
                    throw new Exception("Erro ao atualizar o estoque do produto ID $produtoId");
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
     * Lista todas as vendas realizadas
     * @return array - lista de vendas
     */
    public function listarVendas() {
        $query = "SELECT * FROM vw_relatorio_vendas ORDER BY data_venda DESC";
        $result = $this->conexao->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Busca uma venda pelo ID com seus itens
     * @param int $id - ID da venda
     * @return array - dados da venda e seus itens
     */
    public function buscarVendaPorId($id) {
        // Buscar a venda
        $stmt = $this->conexao->prepare("
            SELECT v.*, u.nome as usuario_nome
            FROM vendas v
            JOIN usuarios u ON v.usuario_id = u.id
            WHERE v.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $venda = $stmt->get_result()->fetch_assoc();
        
        if (!$venda) {
            return null;
        }
        
        // Buscar os itens da venda
        $stmt = $this->conexao->prepare("
            SELECT vi.*, p.nome as produto_nome
            FROM venda_itens vi
            JOIN produtos p ON vi.produto_id = p.id
            WHERE vi.venda_id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $venda['itens'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return $venda;
    }
}