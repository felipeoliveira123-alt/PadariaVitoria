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
