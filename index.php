<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: /PadariaVitoria/app/views/vendas.php");
    exit;
}

//Parte do código que redireciona para a parte de cadastro do usuário
$rota = $_GET['rota'] ?? '';

if ($rota === 'cadastro_usuario') {
    require_once 'app/views/cadastro_usuario/CodigoCadastro.php';
}

require_once __DIR__ . '/app/views/auth/login.php';
?>