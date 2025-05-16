<?php
class LoteModel {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function listarPorProduto($produtoId) {
        $stmt = $this->conexao->prepare("
            SELECT pl.*, p.nome as produto_nome 
            FROM produto_lotes pl
            JOIN produtos p ON p.id = pl.produto_id
            WHERE pl.produto_id = ? AND pl.ativo = 1
            ORDER BY pl.validade ASC");
        $stmt->bind_param("i", $produtoId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function criar($dados) {
        $stmt = $this->conexao->prepare("INSERT INTO produto_lotes (produto_id, numero_lote, quantidade, validade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", 
            $dados['produto_id'],
            $dados['numero_lote'],
            $dados['quantidade'],
            $dados['validade']
        );
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    public function atualizar($id, $produtoId, $dados) {
        $stmt = $this->conexao->prepare("UPDATE produto_lotes SET numero_lote = ?, quantidade = ?, validade = ? WHERE id = ? AND produto_id = ?");
        $stmt->bind_param("sisii", 
            $dados['numero_lote'],
            $dados['quantidade'],
            $dados['validade'],
            $id,
            $produtoId
        );
        
        return $stmt->execute();
    }

    public function deletar($id, $produtoId) {
        $stmt = $this->conexao->prepare("UPDATE produto_lotes SET ativo = 0 WHERE id = ? AND produto_id = ?");
        $stmt->bind_param("ii", $id, $produtoId);
        return $stmt->execute();
    }
}