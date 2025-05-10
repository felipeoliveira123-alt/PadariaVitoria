<?php
session_start();
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

$controller = new AuthController($conexao);
$resultado = $controller->logout();

header("Location: " . $resultado['redirect']);
exit;
?>