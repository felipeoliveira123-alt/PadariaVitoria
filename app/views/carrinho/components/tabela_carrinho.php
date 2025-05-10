<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th class="text-end">Preço (R$)</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($itens)): ?>
                    <?php foreach ($itens as $key => $item): ?>
                        <?php 
                        // Ensure the item has an ID
                        $item_id = $item['id'] ?? null;
                        if (!$item_id && isset($item['produto_id'])) {
                            $item_id = $item['produto_id'];
                        }
                        if (!$item_id) {
                            // If no ID is found, use the array index as a fallback
                            $item_id = $key;
                        }
                        ?>
                        <tr data-id="<?= htmlspecialchars($item_id) ?>">
                            <td><?= htmlspecialchars($item['name'] ?? $item['nome']) ?></td>
                            <td class="text-end">
                                <?= number_format($item['price'] ?? $item['preco'], 2, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger remove-item">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Nenhum produto no carrinho.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="table-secondary">
                <tr>
                    <th>Total</th>
                    <th></th>
                    <th class="text-end">R$ <?= number_format(isset($total) ? $total : 0, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>