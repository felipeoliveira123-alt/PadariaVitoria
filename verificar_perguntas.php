<?php
session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCompleto = trim($_POST['nome_completo']);
    $dataNascimento = $_POST['data_nascimento'];
    $nomeMae = trim($_POST['nome_mae']);

    $stmt = $conexao->prepare("SELECT usuario, senha FROM usuarios 
        WHERE nome_completo = ? AND data_nascimento = ? AND primeiro_nome_mae = ?");
    $stmt->bind_param("sss", $nomeCompleto, $dataNascimento, $nomeMae);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $dados = $resultado->fetch_assoc();
        $usuarioEmail = $dados['usuario'];
        $senhaOriginal = $dados['senha']; // Assume que a senha está salva em texto plano

        // Exibe o popup na mesma página
        echo "<script>
            window.onload = function() {
                const texto = 'Usuário: $usuarioEmail\\nSenha: $senhaOriginal';
                navigator.clipboard.writeText(texto).then(function() {
                    alert('Informações copiadas para a área de transferência:\\n' + texto);
                    window.location.href = 'Recuperacao.php';
                });
            };
        </script>";
    } else {
        echo "<script>
            window.onload = function() {
                alert('Dados incorretos. Nenhum usuário encontrado.');
                window.location.href = 'Recuperacao.php';
            };
        </script>";
    }
}
?>

