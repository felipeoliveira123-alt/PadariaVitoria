<?php
session_start();
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../controllers/CarrinhoController.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /PadariaVitoria/app/views/login.php");
    exit;
}

$controller = new CarrinhoController($conexao);
$mensagem = "";
$erro = "";
$produtosSemEstoque = [];

try {    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['codigo_barras'])) {
            try {
                $resultado = $controller->adicionarProduto($_POST['codigo_barras']);
                $mensagem = "Produto adicionado ao carrinho.";
                // Verificar se o produto adicionado tem aviso de estoque
                if (isset($resultado['data']['estoque_aviso']) && $resultado['data']['estoque_aviso']) {
                    $mensagem .= " Atenção: Este produto está com estoque insuficiente ou zerado.";
                }
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
          if (isset($_POST['item_avulso'])) {
            $nome = $_POST['nome_item'] ? $_POST['nome_item'] : 'Item avulso';
            $preco = $_POST['preco_item'];
            $quantidade = $_POST['quantidade_item'] ?? 1;
            
            try {
                // O controller já trata a conversão de vírgula para ponto
                $resultado = $controller->adicionarItemAvulso($nome, $preco, $quantidade);
                $mensagem = "Item avulso adicionado ao carrinho.";
            } catch (Exception $e) {
                $erro = "Erro ao adicionar item avulso: " . $e->getMessage();
            }
        }

        if (isset($_POST['remove_item'])) {
            $controller->removerItem($_POST['remove_item']);
            $mensagem = "Item removido do carrinho.";
        }

        if (isset($_POST['finalizar'])) {
            $resultado = $controller->finalizarCompra();
            $mensagem = $resultado['message'];
            
            if (isset($resultado['produtos_sem_estoque'])) {
                $produtosSemEstoque = $resultado['produtos_sem_estoque'];
            }
        }
        
        if (isset($_POST['cancelar_venda'])) {
            $resultado = $controller->cancelarVenda();
            $mensagem = $resultado['message'];
        }
    }
    
    $data = $controller->index();
    $itens = $data['itens'];

    $total = $data['total'];

} catch (Exception $e) {
    // Garante que o carrinho seja carregado mesmo após erro
    $data = $controller->index();
    $itens = $data['itens'];
    $total = $data['total'];
    $erro = "Erro ao processar a operação: " . $e->getMessage();
}

require_once __DIR__ . '/../views/carrinho/index.php';
?>