CREATE DATABASE tarefas_db;
USE tarefas_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    idade INT DEFAULT NULL
);

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(100) NOT NULL,
    setor VARCHAR(100) NOT NULL,
    prioridade ENUM('Baixa', 'Média', 'Alta') NOT NULL,
    data_cadastro DATE NOT NULL,
    status_tarefa ENUM('Fazer', 'Fazendo', 'Pronto') NOT NULL,
    usuario_responsavel INT NOT NULL,
    FOREIGN KEY (usuario_responsavel) REFERENCES usuarios(id)
);

INSERT INTO usuarios (nome, email, senha, idade) VALUES
('João Medeiros', 'joao@medeiros', '123456', 20);

INSERT INTO tarefas (descricao, setor, prioridade, data_cadastro, status_tarefa, usuario_responsavel) VALUES
('Guardar os refrigerantes', 'Bebidas', 'Média', '2025-09-10', 'Fazer', 1);