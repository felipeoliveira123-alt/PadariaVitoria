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
                <?php if (!empty($itens)): ?>
                    <?php foreach ($itens as $item): ?>
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