<?php
session_start();
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/app/controllers/ProdutoController.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$controller = new ProdutoController($conexao);
$mensagemErro = "";
$mensagemSucesso = "";
$produtos = [];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id']) && isset($_GET['lotes'])) {
            header('Content-Type: application/json');
            echo json_encode($controller->show($_GET['id'], true));
            exit;
        } elseif (isset($_GET['id'])) {
            header('Content-Type: application/json');
            echo json_encode($controller->show($_GET['id']));
            exit;
        } else {
            $produtos = $controller->index();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dadosJson = file_get_contents('php://input');
        $dados = json_decode($dadosJson, true);
        
        header('Content-Type: application/json');
        if (isset($dados['tipo']) && $dados['tipo'] === 'lote') {
            echo json_encode($controller->storeLote($dados));
        } else {
            echo json_encode($controller->store($dados));
        }
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (isset($_GET['id'])) {
            $dadosJson = file_get_contents('php://input');
            $dados = json_decode($dadosJson, true);
            
            header('Content-Type: application/json');
            echo json_encode($controller->update($_GET['id'], $dados));
            exit;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (isset($_GET['id']) && isset($_GET['lote_id'])) {
            header('Content-Type: application/json');
            echo json_encode($controller->destroyLote($_GET['id'], $_GET['lote_id']));
            exit;
        } elseif (isset($_GET['id'])) {
            header('Content-Type: application/json');
            echo json_encode($controller->destroy($_GET['id']));
            exit;
        }
    }
} catch (Exception $e) {
    $mensagemErro = $e->getMessage();
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => $mensagemErro]);
        exit;
    }
}

require_once __DIR__ . '/app/views/produtos/index.php';