<?php
require_once __DIR__ . '/../models/ProdutoModel.php';
require_once __DIR__ . '/../models/LoteModel.php';

class ProdutoController {
    private $produtoModel;
    private $loteModel;

    public function __construct($conexao) {
        $this->produtoModel = new ProdutoModel($conexao);
        $this->loteModel = new LoteModel($conexao);
    }

    public function index($filtros = [], $pagina = 1, $itensPorPagina = 10) {
        // Obter categorias para o filtro
        $categorias = $this->produtoModel->listarCategorias();
        
        // Listar produtos com filtros e paginação
        $resultado = $this->produtoModel->listarTodos($filtros, $pagina, $itensPorPagina);
        
        return [
            'response' => $resultado['produtos'],
            'categorias' => $categorias,
            'paginacao' => $resultado['paginacao'],
            'filtros' => $filtros
        ];
    }

    public function show($id, $incluirLotes = false) {
        if ($incluirLotes) {
            return ['response' => $this->loteModel->listarPorProduto($id)];
        }
        return $this->produtoModel->buscarPorId($id);
    }

    public function store($dados) {
        try {
            $produtoId = $this->produtoModel->criar($dados);
            
            if ($produtoId && isset($dados['lote_inicial'])) {
                $dadosLote = $dados['lote_inicial'];
                $dadosLote['produto_id'] = $produtoId;
                $this->loteModel->criar($dadosLote);
            }
            
            return [
                'status' => 'success',
                'message' => 'Produto criado com sucesso',
                'data' => array_merge($dados, ['id' => $produtoId])
            ];
        } catch (Exception $e) {
            throw new Exception("Erro ao criar produto: " . $e->getMessage());
        }
    }

    public function update($id, $dados) {
        try {
            if ($this->produtoModel->atualizar($id, $dados)) {
                return [
                    'status' => 'success',
                    'message' => 'Produto atualizado com sucesso',
                    'data' => $dados
                ];
            }
            throw new Exception("Erro ao atualizar produto");
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    public function destroy($id) {
        try {
            if ($this->produtoModel->deletar($id)) {
                return [
                    'status' => 'success',
                    'message' => 'Produto e seus lotes removidos com sucesso'
                ];
            }
            throw new Exception("Erro ao remover produto");
        } catch (Exception $e) {
            throw new Exception("Erro ao remover produto: " . $e->getMessage());
        }
    }

    public function storeLote($dados) {
        try {
            $loteId = $this->loteModel->criar($dados);
            if ($loteId) {
                return [
                    'status' => 'success',
                    'message' => 'Lote criado com sucesso',
                    'data' => array_merge($dados, ['id' => $loteId])
                ];
            }
            throw new Exception("Erro ao criar lote");
        } catch (Exception $e) {
            throw new Exception("Erro ao criar lote: " . $e->getMessage());
        }
    }

    public function destroyLote($produtoId, $loteId) {
        try {
            if ($this->loteModel->deletar($loteId, $produtoId)) {
                return [
                    'status' => 'success',
                    'message' => 'Lote removido com sucesso'
                ];
            }
            throw new Exception("Erro ao remover lote");
        } catch (Exception $e) {
            throw new Exception("Erro ao remover lote: " . $e->getMessage());
        }
    }
}