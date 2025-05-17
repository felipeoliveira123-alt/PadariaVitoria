<?php
require_once __DIR__ . '/../models/CarrinhoModel.php';
require_once __DIR__ . '/../models/ProdutoModel.php';
require_once __DIR__ . '/../models/VendaModel.php';

class CarrinhoController {
    private $carrinhoModel;
    private $produtoModel;
    private $vendaModel;
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
        $this->carrinhoModel = new CarrinhoModel();
        $this->produtoModel = new ProdutoModel($conexao);
        $this->vendaModel = new VendaModel($conexao);
    }

    public function index() {
        return [
            'itens' => $this->carrinhoModel->getItens(),
            'total' => $this->carrinhoModel->getTotal()
        ];
    }

    public function adicionarProduto($codigoBarras) {
        $produto = $this->produtoModel->buscarPorCodigoBarras($codigoBarras);
        
        if (!$produto) {
            throw new Exception("Produto não encontrado!");
        }
        
        // Remover restrição de estoque - permitir adicionar produtos mesmo sem estoque
        // Apenas adicionar um aviso se o estoque for insuficiente
        if ($produto['estoque_total'] <= 0) {
            $produto['estoque_aviso'] = true; // Sinalizador para exibir aviso ao usuário
        }

        $this->carrinhoModel->adicionarProduto($produto);
        return [
            'status' => 'success',
            'message' => 'Produto adicionado ao carrinho',
            'data' => $produto
        ];
    }    public function removerItem($id) {
        $removido = $this->carrinhoModel->removerItem($id);
        
        if (!$removido) {
            throw new Exception("Não foi possível remover o item.");
        }
        
        return [
            'status' => 'success',
            'message' => 'Item removido do carrinho'
        ];
    }
    
    public function adicionarItemAvulso($nome, $preco, $quantidade = 1) {
        if (!is_numeric($preco) || $preco <= 0) {
            throw new Exception("O preço deve ser um valor numérico positivo.");
        }
        
        if (!is_numeric($quantidade) || $quantidade <= 0) {
            throw new Exception("A quantidade deve ser um valor numérico positivo.");
        }
        
        $item = $this->carrinhoModel->adicionarItemAvulso($nome, $preco, $quantidade);
        
        return [
            'status' => 'success',
            'message' => 'Item avulso adicionado ao carrinho',
            'data' => $item
        ];
    }

    public function finalizarCompra() {
        $itens = $this->carrinhoModel->getItens();
        $total = $this->carrinhoModel->getTotal();
        
        if (empty($itens)) {
            throw new Exception("O carrinho está vazio!");
        }
        
        // Remover a verificação de estoque - permitir venda mesmo com estoque insuficiente
        // Apenas preparar avisos para exibir ao usuário
        $produtosSemEstoque = [];
        foreach ($itens as &$item) {
            $produtoId = $item['id'];
            $quantidade = $item['quantidade'] ?? 1;
            
            // Buscar informações atualizadas do produto
            $produto = $this->produtoModel->buscarPorId($produtoId);
            
            if ($produto && $produto['estoque_total'] < $quantidade) {
                $produtosSemEstoque[] = $produto['nome'];
                $item['estoque_aviso'] = true;
            }
        }
        
        try {
            // Registrar a venda no banco de dados
            $dadosVenda = [
                'valor_total' => $total,
                'usuario_id' => $_SESSION['usuario']['id'] ?? 1 // Usar ID padrão se não estiver definido
            ];
            
            $vendaId = $this->vendaModel->registrarVenda($dadosVenda, $itens);
            
            if (!$vendaId) {
                throw new Exception("Erro ao registrar a venda no sistema.");
            }
            
            // Limpar o carrinho apenas após o registro bem-sucedido
            $this->carrinhoModel->limpar();
            
            $mensagem = 'Compra finalizada com sucesso!';
            
            return [
                'status' => 'success',
                'message' => $mensagem,
                'venda_id' => $vendaId,
                'produtos_sem_estoque' => $produtosSemEstoque
            ];
            
        } catch (Exception $e) {
            throw new Exception("Erro ao finalizar compra: " . $e->getMessage());
        }
    }

    public function cancelarVenda() {
        $this->carrinhoModel->limpar();
        
        return [
            'status' => 'success',
            'message' => 'Venda cancelada com sucesso! O carrinho foi esvaziado.'
        ];
    }
}