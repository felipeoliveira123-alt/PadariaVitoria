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
                    <td><?= htmlspecialchars($produto['categoria'] ?? '') ?></td>                    <td><?= htmlspecialchars($produto['estoque_total']) ?></td>
                    <td>
                        <?php if ($produto['proxima_validade']): 
                            $validade = strtotime($produto['proxima_validade']);
                            $hoje = time();
                            $diasRestantes = ceil(($validade - $hoje) / (60 * 60 * 24));
                            $classeValidade = '';
                            $iconeValidade = '';
                            
                            if ($diasRestantes <= 0) {
                                $classeValidade = 'text-white bg-danger';
                                $iconeValidade = '<i class="bi bi-x-circle-fill"></i> ';
                            } elseif ($diasRestantes <= 7) {
                                $classeValidade = 'text-dark bg-warning';
                                $iconeValidade = '<i class="bi bi-exclamation-triangle-fill"></i> ';
                            } elseif ($diasRestantes <= 30) {
                                $classeValidade = 'text-dark bg-info';
                                $iconeValidade = '<i class="bi bi-info-circle-fill"></i> ';
                            }
                        ?>
                            <span class="badge <?= $classeValidade ?>">
                                <?= $iconeValidade ?><?= date('d/m/Y', $validade) ?>
                                <?php if ($diasRestantes <= 30): ?>
                                    <span class="ms-1">(<?= $diasRestantes ?> dias)</span>
                                <?php endif; ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
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
            <?php endforeach; ?>        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">
                    <?php if (!empty($produtos['filtros']['validade_min']) || !empty($produtos['filtros']['validade_max'])): ?>
                        Nenhum produto encontrado com os filtros de validade aplicados.
                    <?php else: ?>
                        Nenhum produto encontrado.
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>