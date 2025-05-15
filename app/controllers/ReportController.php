<?php
require_once __DIR__ . '/../models/VendaModel.php';

class ReportController {
    private $vendaModel;

    public function __construct($conexao) {
        $this->vendaModel = new VendaModel($conexao);
    }    public function vendasReport() {
        // Obter os parâmetros de filtro da URL
        $filtros = [
            'data_inicio' => isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '',
            'data_fim' => isset($_GET['data_fim']) ? $_GET['data_fim'] : '',
            'vendedor' => isset($_GET['vendedor']) ? $_GET['vendedor'] : '',
            'valor_min' => isset($_GET['valor_min']) ? (float)$_GET['valor_min'] : '',
            'valor_max' => isset($_GET['valor_max']) ? (float)$_GET['valor_max'] : ''
        ];
        
        // Obter os parâmetros de paginação
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $itensPorPagina = isset($_GET['itens_por_pagina']) ? (int)$_GET['itens_por_pagina'] : 10;
        
        // Validar e corrigir valores de paginação
        $pagina = max(1, $pagina);
        $itensPorPagina = in_array($itensPorPagina, [5, 10, 20, 50]) ? $itensPorPagina : 10;
        
        // Obter os dados com paginação e filtros
        $resultado = $this->vendaModel->listarVendas($filtros, $pagina, $itensPorPagina);
        
        return [
            'vendas' => $resultado['vendas'],
            'paginacao' => $resultado['paginacao'],
            'filtros' => $filtros
        ];
    }

    public function detalheVenda($id) {
        $venda = $this->vendaModel->buscarVendaPorId($id);
        if (!$venda) {
            throw new Exception("Venda não encontrada");
        }
        
        return [
            'venda' => $venda
        ];
    }
}