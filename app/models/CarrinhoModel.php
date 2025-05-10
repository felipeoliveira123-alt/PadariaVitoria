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

    public function removerItem($id) {
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $key => $item) {
                // Check for ID in multiple possible places
                $item_id = $item['id'] ?? null;
                if (!$item_id && isset($item['produto_id'])) {
                    $item_id = $item['produto_id'];
                }
                
                // Also handle numeric string comparison properly
                if (($item_id == $id) || ($key == $id && is_numeric($id))) {
                    unset($_SESSION['carrinho'][$key]);
                    // Reindex the array
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                    return true;
                }
            }
        }
        return false;
    }
}