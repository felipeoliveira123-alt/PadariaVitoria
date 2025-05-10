<?php
class ProdutoModel {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    /**
     * Lista todos os produtos com opções de filtro e paginação
     * 
     * @param array $filtros - Array associativo com os campos de filtro 
     *                        (nome, categoria, estoque_min, estoque_max)
     * @param int $pagina - Número da página atual (começando em 1)
     * @param int $itensPorPagina - Quantidade de itens por página
     * @return array - Array com os produtos e informações de paginação
     */
    public function listarTodos($filtros = [], $pagina = 1, $itensPorPagina = 10) {
        // Construir a query base
        $query = "
            SELECT p.*, COALESCE(SUM(pl.quantidade), 0) as estoque_total,
                   MIN(pl.validade) as proxima_validade
            FROM produtos p
            LEFT JOIN produto_lotes pl ON p.id = pl.produto_id AND pl.ativo = 1
            WHERE p.ativo = 1";
        
        $params = [];
        $types = "";
        
        // Adicionar filtros à query
        if (!empty($filtros['nome'])) {
            $query .= " AND p.nome LIKE ?";
            $params[] = "%" . $filtros['nome'] . "%";
            $types .= "s";
        }
        
        if (!empty($filtros['categoria'])) {
            $query .= " AND p.categoria = ?";
            $params[] = $filtros['categoria'];
            $types .= "s";
        }
        
        // Finalizar a query para contagem total
        $queryCount = $query . " GROUP BY p.id";
        
        // Adicionar filtros de estoque após GROUP BY
        if (isset($filtros['estoque_min']) && is_numeric($filtros['estoque_min'])) {
            $queryCount .= " HAVING estoque_total >= ?";
            $params[] = $filtros['estoque_min'];
            $types .= "d";
        }
        
        if (isset($filtros['estoque_max']) && is_numeric($filtros['estoque_max'])) {
            if (strpos($queryCount, "HAVING") !== false) {
                $queryCount .= " AND estoque_total <= ?";
            } else {
                $queryCount .= " HAVING estoque_total <= ?";
            }
            $params[] = $filtros['estoque_max'];
            $types .= "d";
        }
        
        // Contar o total de registros para paginação
        $stmt = $this->conexao->prepare($queryCount);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $totalResults = $stmt->get_result();
        $totalRegistros = $totalResults->num_rows;
        
        // Calcular número de páginas
        $totalPaginas = ceil($totalRegistros / $itensPorPagina);
        
        // Validar página atual
        $pagina = max(1, min($pagina, $totalPaginas));
        $offset = ($pagina - 1) * $itensPorPagina;
        
        // Finalizar query para resultados paginados
        $query = $queryCount . " ORDER BY p.nome LIMIT ?, ?";
        
        // Adicionar parâmetros de paginação
        $params[] = $offset;
        $types .= "i";
        $params[] = $itensPorPagina;
        $types .= "i";
        
        // Executar consulta paginada
        $stmt = $this->conexao->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);
        
        // Retornar resultados com dados de paginação
        return [
            'produtos' => $produtos,
            'paginacao' => [
                'pagina_atual' => $pagina,
                'itens_por_pagina' => $itensPorPagina,
                'total_registros' => $totalRegistros,
                'total_paginas' => $totalPaginas
            ]
        ];
    }

    // Buscar todas as categorias distintas para o filtro
    public function listarCategorias() {
        $query = "SELECT DISTINCT categoria FROM produtos WHERE ativo = 1 AND categoria IS NOT NULL AND categoria != '' ORDER BY categoria";
        $result = $this->conexao->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function buscarPorId($id) {
        $stmt = $this->conexao->prepare("
            SELECT p.*, COALESCE(SUM(pl.quantidade), 0) as estoque_total
            FROM produtos p
            LEFT JOIN produto_lotes pl ON p.id = pl.produto_id AND pl.ativo = 1
            WHERE p.id = ? AND p.ativo = 1
            GROUP BY p.id");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarPorCodigoBarras($codigoBarras) {
        $stmt = $this->conexao->prepare("
            SELECT p.*, COALESCE(SUM(pl.quantidade), 0) as estoque_total
            FROM produtos p
            LEFT JOIN produto_lotes pl ON p.id = pl.produto_id AND pl.ativo = 1
            WHERE p.codigo_barras = ? AND p.ativo = 1
            GROUP BY p.id");
        $stmt->bind_param("s", $codigoBarras);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function criar($dados) {
        $stmt = $this->conexao->prepare("INSERT INTO produtos (nome, descricao, preco, codigo_barras, categoria) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", 
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['codigo_barras'],
            $dados['categoria']
        );
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    public function atualizar($id, $dados) {
        $stmt = $this->conexao->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, codigo_barras = ?, categoria = ? WHERE id = ?");
        $stmt->bind_param("ssdssi", 
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['codigo_barras'],
            $dados['categoria'],
            $id
        );
        
        return $stmt->execute();
    }

    public function deletar($id) {
        $this->conexao->begin_transaction();
        try {
            $stmtLotes = $this->conexao->prepare("UPDATE produto_lotes SET ativo = 0 WHERE produto_id = ?");
            $stmtLotes->bind_param("i", $id);
            $stmtLotes->execute();
            
            $stmtProduto = $this->conexao->prepare("UPDATE produtos SET ativo = 0 WHERE id = ?");
            $stmtProduto->bind_param("i", $id);
            
            if ($stmtProduto->execute()) {
                $this->conexao->commit();
                return true;
            }
            
            $this->conexao->rollback();
            return false;
        } catch (Exception $e) {
            $this->conexao->rollback();
            throw $e;
        }
    }
}