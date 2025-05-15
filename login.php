<?php
session_start();
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

if (isset($_SESSION['usuario'])) {
    header("Location: vendas.php");
    exit;
}

$controller = new AuthController($conexao);
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->login($_POST['usuario'], $_POST['senha']);
    
    if ($resultado['status'] === 'success') {
        header("Location: " . $resultado['redirect']);
        exit;
    } else {
        $erro = $resultado['message'];
    }
}

require_once __DIR__ . '/app/views/auth/login.php';