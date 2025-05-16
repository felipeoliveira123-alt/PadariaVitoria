<?php
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController {
    private $authModel;

    public function __construct($conexao) {
        $this->authModel = new AuthModel($conexao);
    }

    public function login($usuario, $senha) {
        $usuario = $this->authModel->autenticar($usuario, $senha);
        
        if ($usuario) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['carrinho'] = [];
            return [
                'status' => 'success',
                'redirect' => '/PadariaVitoria/app/views/menu/Menu.php'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Usuário ou senha incorretos!'
        ];
    }

    public function logout() {
        session_destroy();
        return [
            'status' => 'success',
            'redirect' => '/PadariaVitoria/index.php?rota=login'
        ];
    }
}