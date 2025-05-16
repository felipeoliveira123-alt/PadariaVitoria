<?php
session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_completo'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha']; // Criptografa a senha
    $dataNascimento = $_POST['data_nascimento'];
    $nomeMae = $_POST['primeiro_nome_mae'];
    $situacao = 'HABILITADO';

    // Verificar se o e-mail já está cadastrado
    $verifica = $conexao->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $verifica->bind_param("s", $usuario);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        echo "<script>alert('Este usuário já está cadastrado.'); window.history.back();</script>";
        $verifica->close();
        exit;
    }
    $verifica->close();

    // Inserir novo usuário
    $sql = "INSERT INTO usuarios 
            (nome_completo, usuario, senha, data_nascimento, data_registro, primeiro_nome_mae, data_alteracao, situacao)
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?, CURRENT_TIMESTAMP, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $usuario, $senha, $dataNascimento, $nomeMae, $situacao);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>