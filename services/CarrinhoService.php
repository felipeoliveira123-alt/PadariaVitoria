<?php
class CarrinhoService {
    /**
     * Adiciona um produto ao carrinho
     * @param array $produto
     * @return void
     */
    public function adicionarProduto($produto) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $_SESSION['carrinho'][] = $produto;
    }

    /**
     * Retorna todos os itens do carrinho
     * @return array
     */
    public function getItens() {
        return $_SESSION['carrinho'] ?? [];
    }

    /**
     * Calcula o total do carrinho
     * @return float
     */
    public function getTotal() {
        $total = 0;
        foreach ($this->getItens() as $item) {
            $total += $item['price'] ?? $item['preco'];
        }
        return $total;
    }

    /**
     * Limpa o carrinho
     * @return void
     */
    public function limpar() {
        $_SESSION['carrinho'] = [];
    }
}
?>