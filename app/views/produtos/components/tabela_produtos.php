<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Código de Barras</th>
            <th>Categoria</th>
            <th>Estoque Total</th>
            <th>Próxima Validade</th>
            <th class="text-center">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($produtos['response'])): ?>
            <?php foreach ($produtos['response'] as $produto): ?>
                <tr data-id="<?= htmlspecialchars($produto['id']) ?>">
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao'] ?? '') ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($produto['codigo_barras']) ?></td>
                    <td><?= htmlspecialchars($produto['categoria'] ?? '') ?></td>
                    <td><?= htmlspecialchars($produto['estoque_total']) ?></td>
                    <td><?= $produto['proxima_validade'] ? date('d/m/Y', strtotime($produto['proxima_validade'])) : '-' ?></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary edit-product">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-info manage-batches" title="Gerenciar Lotes">
                                <i class="bi bi-box-seam"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-product">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">Nenhum produto encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>