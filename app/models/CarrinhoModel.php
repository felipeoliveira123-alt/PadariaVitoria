<?php
class CarrinhoModel {
    public function adicionarProduto($produto) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $_SESSION['carrinho'][] = $produto;
    }

    public function getItens() {
        return $_SESSION['carrinho'] ?? [];
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->getItens() as $item) {
            $total += $item['price'] ?? $item['preco'];
        }
        return $total;
    }

    public function limpar() {
        $_SESSION['carrinho'] = [];
    }
}