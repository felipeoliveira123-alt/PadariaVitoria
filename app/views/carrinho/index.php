<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Padaria Vitória</title>
    <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Carrinho de Compras</h2>
            <div>
                <a href="produtos.php" class="btn btn-outline-primary me-2">Gerenciar Produtos</a>
                <a href="logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>

        <?php if (isset($erro) && $erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (isset($mensagem) && $mensagem): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-8">
                <input type="text" name="codigo_barras" class="form-control"
                    placeholder="Digite o código de barras" required
                    pattern="[0-9]+" title="Digite apenas números">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Adicionar Produto</button>
            </div>
        </form>

        <?php include_once __DIR__ . '/components/tabela_carrinho.php'; ?>

        <div class="text-end mt-4">
            <form method="POST" class="d-inline-block">
                <button type="submit" name="finalizar" class="btn btn-success"
                    <?= empty($itens) ? 'disabled' : '' ?>>
                    Finalizar Compra
                </button>
            </form>
        </div>
    </div>

    <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>