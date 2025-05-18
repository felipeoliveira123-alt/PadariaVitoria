<?php
// Extrair filtros e valores atuais
$filtroNome = $produtos['filtros']['nome'] ?? '';
$filtroCategoria = $produtos['filtros']['categoria'] ?? '';
$filtroEstoqueMin = $produtos['filtros']['estoque_min'] ?? '';
$filtroEstoqueMax = $produtos['filtros']['estoque_max'] ?? '';
$filtroValidadeMin = $produtos['filtros']['validade_min'] ?? '';
$filtroValidadeMax = $produtos['filtros']['validade_max'] ?? '';
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
              <!-- Filtro por validade mínima -->
            <div class="col-md-3">
                <label for="filtro_validade_min" class="form-label">Validade de</label>
                <input type="date" class="form-control" id="filtro_validade_min" name="filtro_validade_min" 
                       value="<?= htmlspecialchars($filtroValidadeMin) ?>">
            </div>
            
            <!-- Filtro por validade máxima -->
            <div class="col-md-3">
                <label for="filtro_validade_max" class="form-label">Validade até</label>
                <input type="date" class="form-control" id="filtro_validade_max" name="filtro_validade_max" 
                       value="<?= htmlspecialchars($filtroValidadeMax) ?>">
            </div>
              <!-- Filtros rápidos de validade -->
            <div class="col-md-4">
                <label class="form-label">Filtros rápidos de validade</label>
                <div class="d-flex gap-2 filtros-rapidos">
                    <button type="button" class="btn btn-sm btn-outline-warning" id="filtrar-vencendo-7-dias">
                        <i class="bi bi-exclamation-triangle"></i> Vencendo em 7 dias
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="filtrar-vencendo-30-dias">
                        <i class="bi bi-exclamation-circle"></i> Vencendo em 30 dias
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="limpar-filtros-validade">
                        <i class="bi bi-x-circle"></i> Limpar datas
                    </button>
                </div>
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