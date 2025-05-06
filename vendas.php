<?php
session_start();
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/app/controllers/CarrinhoController.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$controller = new CarrinhoController($conexao);
$mensagem = "";
$erro = "";

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['codigo_barras'])) {
            $controller->adicionarProduto($_POST['codigo_barras']);
            $mensagem = "Produto adicionado com sucesso!";
        }

        if (isset($_POST['finalizar'])) {
            $controller->finalizarCompra();
            $mensagem = "Compra finalizada com sucesso!";
        }
    }
    
    $data = $controller->index();
    $itens = $data['itens'];
    $total = $data['total'];
    
} catch (Exception $e) {
    $erro = "Erro ao processar a operação: " . $e->getMessage();
}

require_once __DIR__ . '/app/views/carrinho/index.php';
?>