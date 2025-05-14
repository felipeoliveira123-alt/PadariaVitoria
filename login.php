<?php
session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Prepared statement para evitar SQL injection
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ? AND senha = ?");
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['carrinho'] = [];

        // Se o checkbox "lembrar_login" estiver marcado
        if (isset($_POST['lembrar_login'])) {
            setcookie('usuario_salvo', $usuario, time() + (86400 * 30), "/"); // 30 dias
            setcookie('senha_salva', $senha, time() + (86400 * 30), "/");
        } else {
            // Remove cookies se não marcado
            setcookie('usuario_salvo', '', time() - 3600, "/");
            setcookie('senha_salva', '', time() - 3600, "/");
        }

        header("Location: Menu.php");
        exit;
    } else {
        header("Location: index.php?erro=1");
        exit;
    }
}
?>
