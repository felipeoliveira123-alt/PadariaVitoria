<?php
session_start();
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/app/controllers/ReportController.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$controller = new ReportController($conexao);
$mensagem = "";
$erro = "";
$detalheVenda = null;

try {
    // Exibir detalhes de uma venda específica
    if (isset($_GET['venda_id'])) {
        $resultado = $controller->detalheVenda($_GET['venda_id']);
        $detalheVenda = $resultado['venda'];
    }
    
    // Lista de vendas para o relatório
    $resultado = $controller->vendasReport();
    $vendas = $resultado['vendas'];
    
} catch (Exception $e) {
    $erro = "Erro ao processar a operação: " . $e->getMessage();
}

require_once __DIR__ . '/app/views/relatorios/index.php';
?>