<?php
session_start();

class AuthModel {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function autenticar($usuario, $senha) {
        $stmt = $this->conexao->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $usuarioDB = $resultado->fetch_assoc();

            if (password_verify($senha, $usuarioDB['senha'])) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['carrinho'] = [];

                // Lembrar login
                if (isset($_POST['lembrar_login'])) {
                    setcookie('usuario_salvo', $usuario, time() + (86400 * 30), "/");
                    setcookie('senha_salva', $senha, time() + (86400 * 30), "/");
                } else {
                    setcookie('usuario_salvo', '', time() - 3600, "/");
                    setcookie('senha_salva', '', time() - 3600, "/");
                }

                return ['status' => 'success', 'redirect' => 'app/views/menu/Menu.php'];
            }
        }

        return ['status' => 'error', 'message' => 'Usuário ou senha inválidos.'];
    }
}