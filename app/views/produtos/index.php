<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Produtos - Padaria Vitória</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png">
    <link rel="stylesheet" href="/PadariaVitoria/app/public/css/produtos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciamento de Produtos</h2>
            <div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#productModal">
                    <i class="bi bi-plus-circle"></i> Novo Produto
                </button>
                <a href="vendas.php" class="btn btn-outline-primary">Voltar ao Carrinho</a>
            </div>
        </div>

        <!-- Componente de filtros -->
        <?php include_once __DIR__ . '/components/filtros_produtos.php'; ?>

        <!-- Modals -->
        <?php
        include_once __DIR__ . '/components/modal_produto.php';
        include_once __DIR__ . '/components/modal_lote.php';
        ?>

        <!-- Tabela de Produtos -->
        <?php include_once __DIR__ . '/components/tabela_produtos.php'; ?>

        <!-- Paginação -->
        <?php include_once __DIR__ . '/components/paginacao.php'; ?>
    </div>    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/PadariaVitoria/app/public/js/produtos.js"></script>
    <!-- Incluir o script de formatação de preço -->
    <script src="/PadariaVitoria/app/public/js/formato-preco.js"></script>

    <!-- Incluir o componente de toast para mensagens PHP -->
    <?php include_once __DIR__ . '/../components/toast_messages.php'; ?>
</body>

</html>