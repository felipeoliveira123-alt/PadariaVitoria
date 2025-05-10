<?php
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController {
    private $authModel;

    public function __construct($conexao) {
        $this->authModel = new AuthModel($conexao);
    }

    public function login($email, $senha) {
        $usuario = $this->authModel->autenticar($email, $senha);
        
        if ($usuario) {
            $_SESSION['usuario'] = $email;
            $_SESSION['carrinho'] = [];
            return [
                'status' => 'success',
                'redirect' => 'vendas.php'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'E-mail ou senha incorretos!'
        ];
    }

    public function logout() {
        session_destroy();
        return [
            'status' => 'success',
            'redirect' => 'index.php'
        ];
    }
}