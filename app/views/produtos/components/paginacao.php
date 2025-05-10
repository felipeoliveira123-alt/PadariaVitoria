<?php
// Extrair dados de paginação
$paginaAtual = $produtos['paginacao']['pagina_atual'] ?? 1;
$totalPaginas = $produtos['paginacao']['total_paginas'] ?? 1;
$itensPorPagina = $produtos['paginacao']['itens_por_pagina'] ?? 10;
$totalRegistros = $produtos['paginacao']['total_registros'] ?? 0;

// Extrair filtros atuais para manter nos links de paginação
$filtros = $produtos['filtros'] ?? [];

// Função para gerar URLs de paginação mantendo os filtros
function gerarUrlPaginacao($pagina, $filtros, $itensPorPagina) {
    $params = ['pagina' => $pagina, 'itens_por_pagina' => $itensPorPagina];
    
    if (!empty($filtros['nome'])) {
        $params['filtro_nome'] = $filtros['nome'];
    }
    
    if (!empty($filtros['categoria'])) {
        $params['filtro_categoria'] = $filtros['categoria'];
    }
    
    if (isset($filtros['estoque_min']) && $filtros['estoque_min'] !== '') {
        $params['filtro_estoque_min'] = $filtros['estoque_min'];
    }
    
    if (isset($filtros['estoque_max']) && $filtros['estoque_max'] !== '') {
        $params['filtro_estoque_max'] = $filtros['estoque_max'];
    }
    
    return 'produtos.php?' . http_build_query($params);
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
            produtos
        </p>
    </div>
    
    <nav aria-label="Navegação de páginas">
        <ul class="pagination mb-0">
            <!-- Botão Anterior -->
            <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= gerarUrlPaginacao($paginaAtual - 1, $filtros, $itensPorPagina) ?>" aria-label="Anterior">
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
                echo '<li class="page-item"><a class="page-link" href="' . gerarUrlPaginacao(1, $filtros, $itensPorPagina) . '">1</a></li>';
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
            }
            
            // Mostrar páginas no range
            for ($i = $inicio; $i <= $fim; $i++) {
                echo '<li class="page-item ' . ($i == $paginaAtual ? 'active' : '') . '">';
                echo '<a class="page-link" href="' . gerarUrlPaginacao($i, $filtros, $itensPorPagina) . '">' . $i . '</a>';
                echo '</li>';
            }
            
            // Mostrar última página se estiver muito longe
            if ($fim < $totalPaginas - 1) {
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                echo '<li class="page-item"><a class="page-link" href="' . gerarUrlPaginacao($totalPaginas, $filtros, $itensPorPagina) . '">' . $totalPaginas . '</a></li>';
            }
            ?>
            
            <!-- Botão Próxima -->
            <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= gerarUrlPaginacao($paginaAtual + 1, $filtros, $itensPorPagina) ?>" aria-label="Próxima">
                    <span aria-hidden="true">Próxima &raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>