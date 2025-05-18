-- Criação das tabelas para vendas
CREATE TABLE IF NOT EXISTS vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_venda TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10,2) NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

drop table vendas;

CREATE TABLE IF NOT EXISTS venda_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venda_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    lote_id INT,
    FOREIGN KEY (venda_id) REFERENCES vendas(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (lote_id) REFERENCES produto_lotes(id)
);

-- View para relatório financeiro
CREATE OR REPLACE VIEW vw_relatorio_vendas AS
SELECT 
    v.id as venda_id,
    v.data_venda,
    v.valor_total,
    u.nome_completo as vendedor,
    COUNT(vi.id) as total_itens,
    GROUP_CONCAT(p.nome SEPARATOR ', ') as produtos
FROM vendas v
JOIN usuarios u ON v.usuario_id = u.id
JOIN venda_itens vi ON v.id = vi.venda_id
JOIN produtos p ON vi.produto_id = p.id
GROUP BY v.id, v.data_venda, v.valor_total, u.nome_completo
ORDER BY v.data_venda DESC;

-- drop view vw_relatorio_vendas