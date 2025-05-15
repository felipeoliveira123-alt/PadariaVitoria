<?php
class AuthModel {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function autenticar($email, $senha) {
        $stmt = $this->conexao->prepare("SELECT * FROM usuarios WHERE usuario = ? AND senha = ?");
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return false;
    }
}