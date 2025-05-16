<?php
session_start();

// Inclui o arquivo de conexão com o banco
require_once __DIR__ . '/../../config/conexao.php';

// Inclui o controller de autenticação
require_once __DIR__ . '/../controllers/AuthController.php';

// Se o usuário já estiver logado, redireciona para a página de vendas
if (isset($_SESSION['usuario'])) {
    header("Location: app/views/vendas.php");
    exit;
}

$controller = new AuthController($conexao);
$erro = "";

// Se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tenta fazer o login com os dados enviados
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $resultado = $controller->login($usuario, $senha);

    if ($resultado['status'] === 'success') {
        header("Location: " . $resultado['redirect']);
        exit;
    } else {
        $erro = $resultado['message'];
    }
}

// Exibe a view de login
require_once __DIR__ . '/app/views/auth/login.php';
