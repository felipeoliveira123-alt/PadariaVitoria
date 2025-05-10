<?php
// Extrair filtros e valores atuais
$filtroNome = $produtos['filtros']['nome'] ?? '';
$filtroCategoria = $produtos['filtros']['categoria'] ?? '';
$filtroEstoqueMin = $produtos['filtros']['estoque_min'] ?? '';
$filtroEstoqueMax = $produtos['filtros']['estoque_max'] ?? '';
$categorias = $produtos['categorias'] ?? [];

// Valor atual para itens por página
$itensPorPagina = $produtos['paginacao']['itens_por_pagina'] ?? 10;
?>

<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Filtros</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="produtos.php" class="row g-3">
            <!-- Filtro por nome -->
            <div class="col-md-6">
                <label for="filtro_nome" class="form-label">Nome do produto</label>
                <input type="text" class="form-control" id="filtro_nome" name="filtro_nome" 
                       value="<?= htmlspecialchars($filtroNome) ?>" placeholder="Digite o nome do produto">
            </div>
            
            <!-- Filtro por categoria -->
            <div class="col-md-6">
                <label for="filtro_categoria" class="form-label">Categoria</label>
                <select class="form-select" id="filtro_categoria" name="filtro_categoria">
                    <option value="">Todas as categorias</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['categoria']) ?>" 
                            <?= $filtroCategoria === $cat['categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Filtro por estoque mínimo -->
            <div class="col-md-3">
                <label for="filtro_estoque_min" class="form-label">Estoque mínimo</label>
                <input type="number" class="form-control" id="filtro_estoque_min" name="filtro_estoque_min" 
                       value="<?= htmlspecialchars($filtroEstoqueMin) ?>" min="0" placeholder="Mínimo">
            </div>
            
            <!-- Filtro por estoque máximo -->
            <div class="col-md-3">
                <label for="filtro_estoque_max" class="form-label">Estoque máximo</label>
                <input type="number" class="form-control" id="filtro_estoque_max" name="filtro_estoque_max" 
                       value="<?= htmlspecialchars($filtroEstoqueMax) ?>" min="0" placeholder="Máximo">
            </div>
            
            <!-- Itens por página -->
            <div class="col-md-2">
                <label for="itens_por_pagina" class="form-label">Itens por página</label>
                <select class="form-select" id="itens_por_pagina" name="itens_por_pagina">
                    <?php foreach ([5, 10, 20, 50] as $opcao): ?>
                        <option value="<?= $opcao ?>" <?= $itensPorPagina == $opcao ? 'selected' : '' ?>>
                            <?= $opcao ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Botões de ação -->
            <div class="col-md-4">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="produtos.php" class="btn btn-outline-secondary ms-1">
                    <i class="bi bi-x-circle"></i> Limpar filtros
                </a>
            </div>
        </form>
    </div>
</div>