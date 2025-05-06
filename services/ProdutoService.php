<?php
require_once __DIR__ . '/../config/conexao.php';

class ProdutoService {
    private $conexao;

    public function __construct() {
        global $conexao;
        $this->conexao = $conexao;
    }

    public function buscarTodos() {
        $result = $this->conexao->query("SELECT id, nome as name, preco as price, estoque as stockQuantity FROM produtos WHERE ativo = 1");
        $produtos = [];
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $row;
        }
        return ['response' => $produtos];
    }

    public function buscarPorId($id) {
        $stmt = $this->conexao->prepare("SELECT id, nome as name, preco as price, estoque as stockQuantity FROM produtos WHERE id = ? AND ativo = 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function buscarPorCodigoBarras($codigoBarras) {
        $stmt = $this->conexao->prepare("SELECT id, nome as name, preco as price, estoque as stockQuantity FROM produtos WHERE codigo_barras = ? AND ativo = 1");
        $stmt->bind_param("s", $codigoBarras);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function atualizar($id, array $dados) {
        $stmt = $this->conexao->prepare("UPDATE produtos SET nome = ?, preco = ?, estoque = ? WHERE id = ?");
        $stmt->bind_param("sdii", $dados['name'], $dados['price'], $dados['stockQuantity'], $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao atualizar produto: " . $stmt->error);
        }
        
        return [
            'status' => 'success',
            'message' => 'Produto atualizado com sucesso',
            'data' => $dados
        ];
    }
}
?>