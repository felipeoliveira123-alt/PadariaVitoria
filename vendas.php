<?php
session_start();
require_once __DIR__ . '/services/ProdutoService.php';
require_once __DIR__ . '/services/CarrinhoService.php';

// Verifica se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$produtoService = new ProdutoService();
$carrinhoService = new CarrinhoService();
$mensagem = "";
$erro = "";

// Processa as ações do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['codigo_barras'])) {
            $produto = $produtoService->buscarPorCodigoBarras($_POST['codigo_barras']);

            if ($produto) {
                $carrinhoService->adicionarProduto($produto);
            } else {
                $erro = "Produto não encontrado!";
            }
        }

        // Finaliza a compra
        if (isset($_POST['finalizar'])) {
            $carrinhoService->limpar();
            $mensagem = "Compra finalizada com sucesso!";
        }
    } catch (Exception $e) {
        $erro = "Erro ao processar a operação: " . $e->getMessage();
    }
}

$total = $carrinhoService->getTotal();
$itensCarrinho = $carrinhoService->getItens();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Carrinho - Padaria Vitória</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/Logotipo.png">
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

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($mensagem): ?>
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

        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Produto</th>
                            <th class="text-end">Preço (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($itensCarrinho)): ?>
                            <?php foreach ($itensCarrinho as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name'] ?? $item['nome']) ?></td>
                                    <td class="text-end">
                                        <?= number_format($item['price'] ?? $item['preco'], 2, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhum produto no carrinho.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th>Total</th>
                            <th class="text-end">R$ <?= number_format($total, 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="text-end mt-4">
            <form method="POST" class="d-inline-block"></form>
            <button type="submit" name="finalizar" class="btn btn-success"
                <?= empty($itensCarrinho) ? 'disabled' : '' ?>>
                Finalizar Compra
            </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>