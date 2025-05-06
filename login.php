<?php
session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $_SESSION['usuario'] = $email;
        $_SESSION['carrinho'] = [];
        header("Location: vendas.php");
        exit;
    } else {
        echo "E-mail ou senha incorretos!";
    }
}
?>