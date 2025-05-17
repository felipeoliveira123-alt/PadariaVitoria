<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Padaria Vitória</title>
    <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png">
</head>
<body class="bg-light">    <!-- Toast para notificações são gerenciadas pelo componente toast_messages.php -->

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Carrinho de Compras</h2>
            <div>
                <a href="produtos.php" class="btn btn-outline-primary me-2">Gerenciar Produtos</a>
                <a href="relatorios.php" class="btn btn-outline-info me-2">
                    <i class="bi bi-graph-up"></i> Relatório de Vendas
                </a>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#productModal">
                    <i class="bi bi-plus-circle"></i> Cadastrar Produto
                </button>
                <a href="logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Adicionar Produto por Código</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <div class="col-md-8">
                                <input type="text" name="codigo_barras" class="form-control"
                                    placeholder="Digite o código de barras" required
                                    pattern="[0-9]+" title="Digite apenas números">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Adicionar Produto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Adicionar Item Avulso</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="item_avulso" value="1">
                              <div class="col-md-12">
                                <label for="nome_item" class="form-label">Nome do item</label>
                                <input type="text" id="nome_item" name="nome_item" class="form-control mb-2"
                                    placeholder="Ex: Pão francês" value="Item avulso" maxlength="100">
                            </div>
                              <div class="col-md-6">
                                <label for="preco_item" class="form-label">Preço</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" id="preco_item" name="preco_item" class="form-control preco-input"
                                        placeholder="0,00" required>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="quantidade_item" class="form-label">Qtd</label>
                                <input type="number" id="quantidade_item" name="quantidade_item" class="form-control"
                                    placeholder="Qtd" value="1" min="1" max="999" required>
                            </div>
                            
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once __DIR__ . '/components/tabela_carrinho.php'; ?>

        <div class="text-end mt-4">
            <form method="POST" class="d-inline-block me-2">
                <button type="submit" name="cancelar_venda" class="btn btn-danger"
                    <?= empty($itens) ? 'disabled' : '' ?>>
                    <i class="bi bi-x-circle"></i> Cancelar Venda
                </button>
            </form>
            <form method="POST" class="d-inline-block">
                <button type="submit" name="finalizar" class="btn btn-success"
                    <?= empty($itens) ? 'disabled' : '' ?>>
                    Finalizar Compra
                </button>
            </form>
        </div>
    </div>

    <!-- Incluir o modal de cadastro de produtos -->
    <?php include_once __DIR__ . '/../produtos/components/modal_produto.php'; ?>    <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <!-- Incluir o script de produtos existente -->
    <script src="/PadariaVitoria/app/public/js/produtos.js"></script>
    <!-- Incluir o script de formatação de preço -->
    <script src="/PadariaVitoria/app/public/js/formato-preco.js"></script>
    
    <!-- Incluir o componente de toast para mensagens PHP -->
    <?php include_once __DIR__ . '/../components/toast_messages.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração específica para o contexto do carrinho
            const productModal = document.getElementById('productModal');
            if (productModal) {
                productModal.addEventListener('show.bs.modal', function() {
                    document.getElementById('productForm').reset();
                    document.getElementById('productModalTitle').textContent = 'Novo Produto';
                    
                    // Garantir que os campos de lote estejam visíveis
                    document.querySelectorAll('#productForm hr, #productForm h6, #productForm div.mb-3:nth-last-child(-n+3)').forEach(el => {
                        el.style.display = 'block';
                    });
                });
            }


            // Capturar o envio do formulário de adição ao carrinho
            const cartForm = document.querySelector('form.row.g-3.mb-4');
            if (cartForm) {
                cartForm.addEventListener('submit', function(event) {
                    // Não vamos prevenir o envio do formulário, apenas registrar que ele foi enviado
                    // para mostrar o toast após o recarregamento da página
                    localStorage.setItem('addedToCart', 'true');
                });
            }            // Verificar se um produto foi adicionado ao carrinho após o carregamento da página
            if (localStorage.getItem('addedToCart') === 'true') {
                localStorage.removeItem('addedToCart');
            }
            
            // Não duplicamos o toast aqui, pois já está sendo gerenciado pelo componente toast_messages.php
            // que mostra notificações no canto superior direito

            // Manipular a remoção de itens do carrinho
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const row = this.closest('tr');
                    const itemId = row.dataset.id;
                    
                    console.log('Removing item with ID:', itemId);
                    
                    if (itemId) {
                        // Criar um formulário para enviar a solicitação de remoção
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.location.href;
                        form.style.display = 'none';
                        
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'remove_item';
                        input.value = itemId;
                        
                        form.appendChild(input);
                        document.body.appendChild(form);
                        
                        form.submit();
                    } else {
                        console.error('ID do item não encontrado');
                        showToast('Não foi possível remover o item. ID do produto não encontrado.', 'danger');
                    }
                });
            });
        });
    </script>
</body>
</html>