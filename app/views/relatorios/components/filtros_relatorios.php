<?php
// Extrair filtros e valores atuais
$filtroDataInicio = $filtros['data_inicio'] ?? '';
$filtroDataFim = $filtros['data_fim'] ?? '';
$filtroVendedor = $filtros['vendedor'] ?? '';
$filtroValorMin = $filtros['valor_min'] ?? '';
$filtroValorMax = $filtros['valor_max'] ?? '';

// Valor atual para itens por página
$itensPorPagina = $paginacao['itens_por_pagina'] ?? 10;
?>

<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Filtros do Relatório</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="relatorios.php" class="row g-3">
            <!-- Filtro por data de início -->
            <div class="col-md-3">
                <label for="data_inicio" class="form-label">Data inicial</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                       value="<?= htmlspecialchars($filtroDataInicio) ?>">
            </div>
            
            <!-- Filtro por data de fim -->
            <div class="col-md-3">
                <label for="data_fim" class="form-label">Data final</label>
                <input type="date" class="form-control" id="data_fim" name="data_fim" 
                       value="<?= htmlspecialchars($filtroDataFim) ?>">
            </div>
            
            <!-- Filtro por vendedor -->
            <div class="col-md-3">
                <label for="vendedor" class="form-label">Vendedor</label>
                <input type="text" class="form-control" id="vendedor" name="vendedor" 
                       value="<?= htmlspecialchars($filtroVendedor) ?>" placeholder="Nome do vendedor">
            </div>
            
            <!-- Filtro por valor mínimo -->
            <div class="col-md-3">
                <label for="valor_min" class="form-label">Valor mínimo (R$)</label>
                <input type="number" class="form-control" id="valor_min" name="valor_min" 
                       value="<?= htmlspecialchars($filtroValorMin) ?>" min="0" step="0.01" placeholder="Mínimo">
            </div>
            
            <!-- Filtro por valor máximo -->
            <div class="col-md-3">
                <label for="valor_max" class="form-label">Valor máximo (R$)</label>
                <input type="number" class="form-control" id="valor_max" name="valor_max" 
                       value="<?= htmlspecialchars($filtroValorMax) ?>" min="0" step="0.01" placeholder="Máximo">
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
                <a href="relatorios.php" class="btn btn-outline-secondary ms-1">
                    <i class="bi bi-x-circle"></i> Limpar filtros
                </a>
            </div>
        </form>
    </div>
</div>
