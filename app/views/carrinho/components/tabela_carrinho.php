<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th class="text-end">Preço</th>
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
                        ?>                <tr data-id="<?= htmlspecialchars($item_id) ?>" class="<?= isset($item['tipo']) && $item['tipo'] == 'avulso' ? 'table-light' : '' ?>">
                            <td>
                                <?= htmlspecialchars($item['name'] ?? $item['nome']) ?>
                                <?php if (isset($item['tipo']) && $item['tipo'] == 'avulso'): ?>
                                    <span class="badge bg-info">Item avulso</span>
                                <?php endif; ?>
                                <?php if (isset($item['quantidade']) && $item['quantidade'] > 1): ?>
                                    <small class="text-muted ms-2">
                                        (<?= $item['quantidade'] ?> unid.)
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                R$ <?= number_format($item['price'] ?? $item['preco'], 2, ',', '.') ?>
                                <?php if (isset($item['quantidade']) && $item['quantidade'] > 1): ?>
                                    <br>
                                    <small class="text-muted">
                                        Total: R$ <?= number_format(($item['price'] ?? $item['preco']) * $item['quantidade'], 2, ',', '.') ?>
                                    </small>
                                <?php endif; ?>
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