<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas - Padaria Vitória</title>
    <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/bootstrap-5.3.5-dist/css/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Relatório de Vendas</h2>
            <div>
                <a href="vendas.php" class="btn btn-outline-primary me-2">Voltar ao Carrinho</a>
                <a href="produtos.php" class="btn btn-outline-secondary me-2">Gerenciar Produtos</a>
                <a href="logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>

        <?php if (isset($erro) && $erro): ?>
            <div class="alert alert-danger alert-dismissible"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (isset($mensagem) && $mensagem): ?>
            <div class="alert alert-primary alert-dismissible"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <?php if ($detalheVenda): ?>
            <!-- Exibir detalhes de uma venda específica -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalhes da Venda #<?= htmlspecialchars($detalheVenda['id']) ?></h5>
                    <a href="relatorios.php" class="btn btn-sm btn-outline-light">Voltar</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($detalheVenda['data_venda'])) ?></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Valor Total:</strong> R$ <?= number_format($detalheVenda['valor_total'], 2, ',', '.') ?></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Vendedor:</strong> <?= htmlspecialchars($detalheVenda['usuario_nome']) ?></p>
                        </div>
                    </div>

                    <h6>Itens da Venda</h6>
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-end">Preço (R$)</th>
                                <th class="text-end">Subtotal (R$)</th>
                            </tr>
                        </thead>
                        <tbody>                            <?php foreach ($detalheVenda['itens'] as $item): ?>
                                <tr<?= isset($item['is_avulso']) && $item['is_avulso'] == 1 ? ' class="table-light"' : '' ?>>
                                    <td>
                                        <?= htmlspecialchars($item['produto_nome']) ?>
                                        <?php if (isset($item['is_avulso']) && $item['is_avulso'] == 1): ?>
                                            <span class="badge bg-info">Item avulso</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= htmlspecialchars($item['quantidade']) ?></td>
                                    <td class="text-end"><?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-end"><?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-end">R$ <?= number_format($detalheVenda['valor_total'], 2, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>        <?php else: ?>
            <!-- Filtros para o relatório de vendas -->
            <?php require_once __DIR__ . '/components/filtros_relatorios.php'; ?>

            <!-- Lista de vendas realizadas -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Lista de Vendas</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($vendas)): ?>

                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">Número da venda</th>
                                    <th class="text-center">Itens vendidos</th>
                                    <th class="text-center">Valor (R$)</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vendas as $venda): ?>
                                    <tr>                                        <td class="text-center">#<?= htmlspecialchars($venda['venda_id']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($venda['total_itens']) ?></td>
                                        <td class="text-center"><?= number_format($venda['valor_total'], 2, ',', '.') ?></td>
                                        <td class="text-center"><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($venda['vendedor']) ?></td>
                                        <td class="text-center">
                                            <a href="relatorios.php?venda_id=<?= $venda['venda_id'] ?>" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="4" class="text-end">Valor Total:</th>
                                    <th class="text-end">
                                        R$ <?= number_format(array_sum(array_column($vendas, 'valor_total')), 2, ',', '.') ?>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Paginação (rodapé) -->
                        <?php require_once __DIR__ . '/components/paginacao.php'; ?>
                    <?php else: ?>
                        <div class="alert alert-info">Nenhuma venda registrada até o momento ou nenhuma venda corresponde aos filtros aplicados.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>