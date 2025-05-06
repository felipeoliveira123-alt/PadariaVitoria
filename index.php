<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: vendas.php");
    exit;
}

require_once __DIR__ . '/app/views/auth/login.php';
?>