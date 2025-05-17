<?php
session_start();
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../controllers/ReportController.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: app/views/login.php");
    exit;
}

$controller = new ReportController($conexao);
$mensagem = "";
$erro = "";
$detalheVenda = null;

try {
    // Exibir detalhes de uma venda específica
    if (isset($_GET['venda_id'])) {
        $venda_id = $_GET['venda_id'];
        $resultado = $controller->detalheVenda($venda_id);
        $detalheVenda = $resultado['venda'];
    }
    
    // Lista de vendas para o relatório com paginação e filtros
    $resultado = $controller->vendasReport();
    $vendas = $resultado['vendas'];
    $paginacao = $resultado['paginacao'];
    $filtros = $resultado['filtros'];

} catch (Exception $e) {
    $erro = "Erro ao processar a operação: " . $e->getMessage();
}

require_once __DIR__ . '/../views/relatorios/RelatorioVendas.php';
?>