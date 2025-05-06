<?php
require_once __DIR__ . '/../models/CarrinhoModel.php';
require_once __DIR__ . '/../models/ProdutoModel.php';

class CarrinhoController {
    private $carrinhoModel;
    private $produtoModel;

    public function __construct($conexao) {
        $this->carrinhoModel = new CarrinhoModel();
        $this->produtoModel = new ProdutoModel($conexao);
    }

    public function index() {
        return [
            'itens' => $this->carrinhoModel->getItens(),
            'total' => $this->carrinhoModel->getTotal()
        ];
    }

    public function adicionarProduto($codigoBarras) {
        $produto = $this->produtoModel->buscarPorCodigoBarras($codigoBarras);
        
        if (!$produto) {
            throw new Exception("Produto não encontrado!");
        }

        $this->carrinhoModel->adicionarProduto($produto);
        return [
            'status' => 'success',
            'message' => 'Produto adicionado ao carrinho',
            'data' => $produto
        ];
    }

    public function finalizarCompra() {
        $this->carrinhoModel->limpar();
        return [
            'status' => 'success',
            'message' => 'Compra finalizada com sucesso'
        ];
    }
}