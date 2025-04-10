CREATE DATABASE IF NOT EXISTS padaria;
USE padaria;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(200),
    senha VARCHAR(40), /*Essa senha sera convertido em modo de criptográfia MD5*/
    data_registro TIMESTAMP,
	data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    situacao ENUM('HABILITADO', 'DESABILITADO') DEFAULT 'HABILITADO'
);

INSERT INTO usuarios (nome, email, senha, data_registro, data_alteracao, situacao)
VALUES ('Felipe Novais', 'felipenovais638@gmail.com', md5('senha123'), current_timestamp(), current_timestamp(), 'HABILITADO');

SELECT * FROM usuarios;


