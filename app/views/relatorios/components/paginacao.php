<?php
// Extrair dados de paginação
$paginaAtual = $paginacao['pagina_atual'] ?? 1;
$totalPaginas = $paginacao['total_paginas'] ?? 1;
$itensPorPagina = $paginacao['itens_por_pagina'] ?? 10;
$totalRegistros = $paginacao['total_registros'] ?? 0;

// Extrair filtros atuais para manter nos links de paginação
$filtrosRelatorio = $filtros ?? [];

// Função para gerar URLs de paginação mantendo os filtros
function gerarUrlPaginacao($pagina, $filtros, $itensPorPagina) {
    $params = ['pagina' => $pagina, 'itens_por_pagina' => $itensPorPagina];
    
    if (!empty($filtros['data_inicio'])) {
        $params['data_inicio'] = $filtros['data_inicio'];
    }
    
    if (!empty($filtros['data_fim'])) {
        $params['data_fim'] = $filtros['data_fim'];
    }
    
    if (!empty($filtros['vendedor'])) {
        $params['vendedor'] = $filtros['vendedor'];
    }
    
    if (isset($filtros['valor_min']) && $filtros['valor_min'] !== '') {
        $params['valor_min'] = $filtros['valor_min'];
    }
    
    if (isset($filtros['valor_max']) && $filtros['valor_max'] !== '') {
        $params['valor_max'] = $filtros['valor_max'];
    }
    
    return 'relatorios.php?' . http_build_query($params);
}

// Mostrar informações de paginação apenas se houver mais de uma página
if ($totalPaginas > 0):
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="mb-0 text-muted">
            Exibindo 
            <strong><?= ($paginaAtual - 1) * $itensPorPagina + 1 ?></strong>
             - 
            <strong><?= min($paginaAtual * $itensPorPagina, $totalRegistros) ?></strong>
             de 
            <strong><?= $totalRegistros ?></strong> 
            vendas
        </p>
    </div>
    
    <nav aria-label="Navegação de páginas">
        <ul class="pagination mb-0">
            <!-- Botão Anterior -->
            <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= gerarUrlPaginacao($paginaAtual - 1, $filtrosRelatorio, $itensPorPagina) ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo; Anterior</span>
                </a>
            </li>
            
            <!-- Números de página -->
            <?php
            // Definir o range de páginas a serem exibidas
            $inicio = max(1, $paginaAtual - 2);
            $fim = min($totalPaginas, $paginaAtual + 2);
            
            // Mostrar primeira página se estiver muito longe
            if ($inicio > 2) {
                echo '<li class="page-item"><a class="page-link" href="' . gerarUrlPaginacao(1, $filtrosRelatorio, $itensPorPagina) . '">1</a></li>';
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
            }
            
            // Mostrar páginas no range
            for ($i = $inicio; $i <= $fim; $i++) {
                echo '<li class="page-item ' . ($i == $paginaAtual ? 'active' : '') . '">';
                echo '<a class="page-link" href="' . gerarUrlPaginacao($i, $filtrosRelatorio, $itensPorPagina) . '">' . $i . '</a>';
                echo '</li>';
            }
            
            // Mostrar última página se estiver muito longe
            if ($fim < $totalPaginas - 1) {
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                echo '<li class="page-item"><a class="page-link" href="' . gerarUrlPaginacao($totalPaginas, $filtrosRelatorio, $itensPorPagina) . '">' . $totalPaginas . '</a></li>';
            }
            ?>
            
            <!-- Botão Próxima -->
            <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= gerarUrlPaginacao($paginaAtual + 1, $filtrosRelatorio, $itensPorPagina) ?>" aria-label="Próxima">
                    <span aria-hidden="true">Próxima &raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>
