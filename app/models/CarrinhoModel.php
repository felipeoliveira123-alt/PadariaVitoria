<?php
class CarrinhoModel {
    public function adicionarProduto($produto) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $_SESSION['carrinho'][] = $produto;
    }
    
    public function adicionarItemAvulso($nome, $preco, $quantidade = 1) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        // Cria um ID único para o item avulso
        $id = 'avulso_' . time() . '_' . mt_rand(100, 999);
        
        $item = [
            'id' => $id,
            'nome' => $nome ? $nome : 'Item avulso',
            'preco' => (float)$preco,
            'quantidade' => (int)$quantidade,
            'tipo' => 'avulso'
        ];
        
        $_SESSION['carrinho'][] = $item;
        return $item;
    }

    public function getItens() {
        return $_SESSION['carrinho'] ?? [];
    }    public function getTotal() {
        $total = 0;
        foreach ($this->getItens() as $item) {
            $preco = $item['price'] ?? $item['preco'];
            $quantidade = $item['quantidade'] ?? 1;
            $total += $preco * $quantidade;
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