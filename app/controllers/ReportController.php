<?php
require_once __DIR__ . '/../models/VendaModel.php';

class ReportController {
    private $vendaModel;

    public function __construct($conexao) {
        $this->vendaModel = new VendaModel($conexao);
    }

    public function vendasReport() {
        $vendas = $this->vendaModel->listarVendas();
        return [
            'vendas' => $vendas
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