<?php
$erroLogin = isset($_GET['erro']) && $_GET['erro'] == 1;
$usuarioSalvo = isset($_COOKIE['usuario_salvo']) ? $_COOKIE['usuario_salvo'] : '';
$senhaSalva = isset($_COOKIE['senha_salva']) ? $_COOKIE['senha_salva'] : '';
$lembrarMarcado = ($usuarioSalvo && $senhaSalva) ? 'checked' : '';

// Pegar mensagem de erro, se existir
$mensagemErro = isset($erro) && !empty($erro) ? $erro : '';
$mostrarErro = !empty($mensagemErro) || $erroLogin;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padaria Vitória - Login</title>

    <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/estilo.css">
    <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png?v=1">
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <form class="p-4 p-md-5 border rounded-3 bg-light shadow-sm formulariologin" action="/PadariaVitoria/app/views/login.php" method="POST">
            <div class="text-center mb-4">
                <img src="/PadariaVitoria/app/public/images/Logotipo.png" class="img-fluid imagemLogotipoPadaria" alt="Logo da Padaria">
            </div>

            <h2>Login</h2>
            
            <?php if ($mostrarErro): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($mensagemErro ?: "Usuário ou senha incorretos") ?>
            </div>
            <?php endif; ?>

            <div class="form-floating mb-3">

                <input type="usuario" class="form-control" id="usuario" name="usuario" placeholder="Digite seu Usuário" required value="<?= htmlspecialchars($usuarioSalvo) ?>">

                <label for="usuario">Usuário</label>

            </div>

            <div class="form-floating mb-3">

                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required value="<?= htmlspecialchars($senhaSalva) ?>">

                <label for="senha">Senha</label>

            </div>

            <div class="checkbox mb-3">

                <label><input type="checkbox" name="lembrar_login" <?= $lembrarMarcado ?>> Lembrar login</label>
                
            </div>

            <button class="btn btn-success" type="submit">Entrar</button>
            <a href="/PadariaVitoria/app/views/cadastro_usuario/cadastro.php" class="btn btn-primary">Cadastre-se</a>

            <div id="esqueceuSenha" class="mt-3" style="<?= $mostrarErro ? 'display:block;' : 'display:none;' ?>">
                <a href="/PadariaVitoria/app/views/recuperacao_senha/Recuperacao.php">Esqueceu sua senha?</a>
            </div>

        </form>
    </div>

    <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
