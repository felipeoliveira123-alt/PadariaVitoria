CREATE DATABASE IF NOT EXISTS panify;
USE panify;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(200),
    senha VARCHAR(40),
    data_registro TIMESTAMP,
	data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    situacao ENUM('HABILITADO', 'DESABILITADO') DEFAULT 'HABILITADO'
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    codigo_barras VARCHAR(50) NOT NULL UNIQUE,
    categoria VARCHAR(100),
    data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_alteracao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE produto_lotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    numero_lote VARCHAR(50),
    quantidade INT NOT NULL DEFAULT 0,
    validade DATE NOT NULL,
    data_entrada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    INDEX idx_produto_validade (produto_id, validade)
);

INSERT INTO usuarios (nome, email, senha, data_registro, data_alteracao, situacao)
VALUES ('Felipe Novais', 'felipenovais638@gmail.com', md5('senha123'), current_timestamp(), current_timestamp(), 'HABILITADO'),
('Felipe Schneider', 'felipe@schneider.com', md5('senha123'), current_timestamp(), current_timestamp(), 'HABILITADO');

INSERT INTO produtos (nome, descricao, preco, codigo_barras, categoria, data_cadastro, data_alteracao)
VALUES 
('Coca-Cola 350ml', 'Refrigerante de cola em lata de 350ml', 3.50, '7894900010015', 'Bebidas', current_timestamp(), current_timestamp()),
('Arroz Tio João 1kg', 'Arroz branco tipo 1 pacote de 1kg', 7.80, '7896006740019', 'Alimentos', current_timestamp(), current_timestamp()),
('Feijão Carioca Kicaldo 1kg', 'Feijão carioca pacote de 1kg', 6.50, '7896007810018', 'Alimentos', current_timestamp(), current_timestamp()),
('Óleo de Soja Soya 900ml', 'Óleo de soja refinado garrafa de 900ml', 8.20, '7896036098021', 'Alimentos', current_timestamp(), current_timestamp()),
('Sabonete Dove 90g', 'Sabonete hidratante 90g', 2.50, '7891150024905', 'Higiene', current_timestamp(), current_timestamp()),
('Creme Dental Colgate Total 90g', 'Creme dental proteção total 12 horas', 4.30, '7891024130225', 'Higiene', current_timestamp(), current_timestamp()),
('Detergente Ypê Neutro 500ml', 'Detergente líquido neutro 500ml', 2.00, '7896098900115', 'Limpeza', current_timestamp(), current_timestamp()),
('Leite Integral Parmalat 1L', 'Leite integral longa vida 1 litro', 4.50, '7896102500013', 'Bebidas', current_timestamp(), current_timestamp()),
('Cerveja Skol Lata 350ml', 'Cerveja Pilsen lata de 350ml', 2.80, '7891991010841', 'Bebidas', current_timestamp(), current_timestamp()),
('Chocolate Lacta Ao Leite 90g', 'Barra de chocolate ao leite 90g', 5.00, '7891008100103', 'Alimentos', current_timestamp(), current_timestamp());

INSERT INTO produto_lotes (produto_id, numero_lote, quantidade, validade)
VALUES 
(1, 'COCA2024001', 50, '2024-12-31'),
(1, 'COCA2024002', 50, '2025-01-31'),
(2, 'ARROZ2024001', 30, '2024-12-31'),
(2, 'ARROZ2024002', 20, '2025-06-30'),
(3, 'FEIJAO2024001', 40, '2024-12-31'),
(3, 'FEIJAO2024002', 40, '2025-06-30');

CREATE OR REPLACE VIEW vw_produto_estoque AS
SELECT 
    p.id,
    p.nome,
    p.codigo_barras,
    COALESCE(SUM(pl.quantidade), 0) as estoque_total,
    COUNT(DISTINCT pl.id) as total_lotes,
    MIN(pl.validade) as proxima_validade
FROM produtos p
LEFT JOIN produto_lotes pl ON p.id = pl.produto_id AND pl.ativo = TRUE
WHERE p.ativo = TRUE
GROUP BY p.id, p.nome, p.codigo_barras;