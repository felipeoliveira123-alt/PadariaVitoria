-- Atualização do schema para permitir itens avulsos
ALTER TABLE venda_itens DROP FOREIGN KEY venda_itens_ibfk_2;
ALTER TABLE venda_itens MODIFY COLUMN produto_id INT NULL;
ALTER TABLE venda_itens ADD COLUMN is_avulso BOOLEAN DEFAULT FALSE;
ALTER TABLE venda_itens ADD COLUMN nome_item VARCHAR(255) NULL;
ALTER TABLE venda_itens ADD CONSTRAINT venda_itens_ibfk_2 FOREIGN KEY (produto_id) REFERENCES produtos (id) ON DELETE RESTRICT ON UPDATE RESTRICT;
