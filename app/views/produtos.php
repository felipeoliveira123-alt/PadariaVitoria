<?php
session_start();
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../controllers/ProdutoController.php';

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
            exit;        } else {
            // Obter parâmetros de filtro
            $filtros = [
                'nome' => $_GET['filtro_nome'] ?? '',
                'categoria' => $_GET['filtro_categoria'] ?? '',
                'estoque_min' => $_GET['filtro_estoque_min'] ?? null,
                'estoque_max' => $_GET['filtro_estoque_max'] ?? null,
                'validade_min' => $_GET['filtro_validade_min'] ?? null,
                'validade_max' => $_GET['filtro_validade_max'] ?? null
            ];
            
            // Parâmetros de paginação
            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $itensPorPagina = isset($_GET['itens_por_pagina']) ? (int)$_GET['itens_por_pagina'] : 10;
            
            // Limitar itens por página entre 5 e 50
            $itensPorPagina = max(5, min(50, $itensPorPagina));
            
            $produtos = $controller->index($filtros, $pagina, $itensPorPagina);
        }    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dadosJson = file_get_contents('php://input');
        $dados = json_decode($dadosJson, true);
        
        // Verifica se o preço contém vírgula e converte para o formato correto
        if (isset($dados['preco']) && is_string($dados['preco'])) {
            if (strpos($dados['preco'], ',') !== false) {
                $dados['preco'] = str_replace('.', '', $dados['preco']); // Remove pontos de milhar
                $dados['preco'] = str_replace(',', '.', $dados['preco']); // Substitui vírgula por ponto
            }
            
            $dados['preco'] = (float) $dados['preco'];
        }
        
        header('Content-Type: application/json');
        if (isset($dados['tipo']) && $dados['tipo'] === 'lote') {
            echo json_encode($controller->storeLote($dados));
        } else {
            echo json_encode($controller->store($dados));
        }
        exit;    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (isset($_GET['id'])) {
            $dadosJson = file_get_contents('php://input');
            $dados = json_decode($dadosJson, true);
            
            // Verifica se o preço contém vírgula e converte para o formato correto
            if (isset($dados['preco']) && is_string($dados['preco'])) {
                if (strpos($dados['preco'], ',') !== false) {
                    $dados['preco'] = str_replace('.', '', $dados['preco']); // Remove pontos de milhar
                    $dados['preco'] = str_replace(',', '.', $dados['preco']); // Substitui vírgula por ponto
                }
                
                $dados['preco'] = (float) $dados['preco'];
            }
            
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

require_once __DIR__ . '/../views/produtos/index.php';