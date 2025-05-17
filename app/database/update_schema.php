<?php
// Script para atualizar a estrutura do banco de dados
require_once __DIR__ . '/../config/conexao.php';

$sql = "
-- Atualização do schema para permitir itens avulsos
ALTER TABLE venda_itens DROP FOREIGN KEY venda_itens_ibfk_2;
ALTER TABLE venda_itens MODIFY COLUMN produto_id INT NULL;
ALTER TABLE venda_itens ADD COLUMN is_avulso BOOLEAN DEFAULT FALSE;
ALTER TABLE venda_itens ADD COLUMN nome_item VARCHAR(255) NULL;
ALTER TABLE venda_itens ADD CONSTRAINT venda_itens_ibfk_2 FOREIGN KEY (produto_id) REFERENCES produtos (id) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- Atualizar a view de relatório de vendas para incluir itens avulsos
DROP VIEW IF EXISTS vw_relatorio_vendas;

CREATE OR REPLACE VIEW vw_relatorio_vendas AS
SELECT 
    v.id as venda_id,
    v.data_venda,
    v.valor_total,
    u.nome_completo as vendedor,
    COUNT(vi.id) as total_itens,
    GROUP_CONCAT(
        CASE 
            WHEN vi.is_avulso = 1 THEN vi.nome_item
            ELSE p.nome
        END
        SEPARATOR ', '
    ) as produtos
FROM vendas v
JOIN usuarios u ON v.usuario_id = u.id
JOIN venda_itens vi ON v.id = vi.venda_id
LEFT JOIN produtos p ON vi.produto_id = p.id
GROUP BY v.id, v.data_venda, v.valor_total, u.nome_completo
ORDER BY v.data_venda DESC;
";

// Executar as queries
$conexao->multi_query($sql);

// Limpar resultados
while ($conexao->more_results() && $conexao->next_result()) {
    if ($resultado = $conexao->store_result()) {
        $resultado->free();
    }
}

if ($conexao->error) {
    echo "Erro ao atualizar o banco de dados: " . $conexao->error;
} else {
    echo "Banco de dados atualizado com sucesso!";
}
