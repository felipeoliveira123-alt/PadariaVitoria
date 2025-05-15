<?php
// Extrair os dados de paginação
$paginaAtual = $paginacao['pagina_atual'] ?? 1;
$totalPaginas = $paginacao['total_paginas'] ?? 1;
$totalRegistros = $paginacao['total_registros'] ?? 0;

// Preparar URL para paginação mantendo os filtros
$urlParams = $_GET;
unset($urlParams['pagina']); // Remover parâmetro de página para reconstruí-lo
$queryString = http_build_query($urlParams);
$urlBase = 'relatorios.php?' . ($queryString ? $queryString . '&' : '');

// Determinar páginas para exibir (manter 5 páginas visíveis)
$paginaInicial = max(1, $paginaAtual - 2);
$paginaFinal = min($totalPaginas, $paginaInicial + 4);

// Ajustar página inicial se estamos no final
if ($paginaFinal - $paginaInicial < 4) {
    $paginaInicial = max(1, $paginaFinal - 4);
}
?>

<?php if ($totalRegistros > 0): ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="mb-0">Mostrando <?= ($paginaAtual - 1) * $paginacao['itens_por_pagina'] + 1 ?> 
           a <?= min($paginaAtual * $paginacao['itens_por_pagina'], $totalRegistros) ?> 
           de <?= $totalRegistros ?> registros</p>
    </div>
    
    <?php if ($totalPaginas > 1): ?>
    <nav aria-label="Navegação de páginas">
        <ul class="pagination mb-0">
            <!-- Botão Anterior -->
            <li class="page-item <?= $paginaAtual <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $paginaAtual > 1 ? $urlBase . 'pagina=' . ($paginaAtual - 1) : '#' ?>" 
                   aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            
            <!-- Primeira página (se não estamos nas primeiras páginas) -->
            <?php if ($paginaInicial > 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $urlBase . 'pagina=1' ?>">1</a>
            </li>
            <?php if ($paginaInicial > 2): ?>
            <li class="page-item disabled">
                <a class="page-link" href="#">...</a>
            </li>
            <?php endif; ?>
            <?php endif; ?>
            
            <!-- Numeração de páginas -->
            <?php for ($i = $paginaInicial; $i <= $paginaFinal; $i++): ?>
            <li class="page-item <?= $i == $paginaAtual ? 'active' : '' ?>">
                <a class="page-link" href="<?= $urlBase . 'pagina=' . $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            
            <!-- Última página (se não estamos nas últimas páginas) -->
            <?php if ($paginaFinal < $totalPaginas): ?>
            <?php if ($paginaFinal < $totalPaginas - 1): ?>
            <li class="page-item disabled">
                <a class="page-link" href="#">...</a>
            </li>
            <?php endif; ?>
            <li class="page-item">
                <a class="page-link" href="<?= $urlBase . 'pagina=' . $totalPaginas ?>"><?= $totalPaginas ?></a>
            </li>
            <?php endif; ?>
            
            <!-- Botão Próximo -->
            <li class="page-item <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $paginaAtual < $totalPaginas ? $urlBase . 'pagina=' . ($paginaAtual + 1) : '#' ?>" 
                   aria-label="Próximo">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>
<?php endif; ?>
