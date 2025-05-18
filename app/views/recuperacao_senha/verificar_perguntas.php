<?php
session_start();

// Caminho ajustado corretamente para conexão
require_once __DIR__ . '/../../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCompleto = trim($_POST['nome_completo'] ?? '');
    $dataNascimento = $_POST['data_nascimento'] ?? '';
    $nomeMae = trim($_POST['nome_mae'] ?? '');

    if ($nomeCompleto && $dataNascimento && $nomeMae) {
        $stmt = $conexao->prepare("SELECT usuario, senha FROM usuarios 
            WHERE nome_completo = ? AND data_nascimento = ? AND primeiro_nome_mae = ?");
        $stmt->bind_param("sss", $nomeCompleto, $dataNascimento, $nomeMae);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $usuarioEmail = $dados['usuario'];
            $senhaOriginal = $dados['senha']; // Apenas se a senha estiver em texto plano

            echo "<script>
                window.onload = function() {
                    const texto = 'Usuário: {$usuarioEmail}\\nSenha: {$senhaOriginal}';
                    navigator.clipboard.writeText(texto).then(function() {
                        alert('Informações copiadas para a área de transferência:\\n' + texto);
                        window.location.href = '/PadariaVitoria/app/views/recuperacao_senha/Recuperacao.php';
                    });
                };
            </script>";
        } else {
            echo "<script>
                window.onload = function() {
                    alert('Dados incorretos. Nenhum usuário encontrado.');
                    window.location.href = '/PadariaVitoria/app/views/recuperacao_senha/Recuperacao.php';
                };
            </script>";
        }
    } else {
        echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.location.href = '/PadariaVitoria/app/views/recuperacao_senha/Recuperacao.php';
        </script>";
    }
}
?>
