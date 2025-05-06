<?php
class ProdutoModel {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function listarTodos() {
        $query = "
            SELECT p.*, COALESCE(SUM(pl.quantidade), 0) as estoque_total,
                   MIN(pl.validade) as proxima_validade
            FROM produtos p
            LEFT JOIN produto_lotes pl ON p.id = pl.produto_id AND pl.ativo = 1
            WHERE p.ativo = 1
            GROUP BY p.id";
        
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