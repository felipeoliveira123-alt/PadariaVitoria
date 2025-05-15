<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/estilo.css">
    <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png">
    <title>Login - Padaria Vitória</title>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <form class="p-4 p-md-5 border rounded-3 bg-light shadow-sm formulariologin" action="login.php" method="POST">
            <div class="text-center mb-4">
                <img src="/PadariaVitoria/app/public/images/Logotipo.png" class="img-fluid imagemLogotipoPadaria" alt="Logo da Padaria">
            </div>

            <div class="col-9 col-md-10">
                <h2>Login</h2>
            </div>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <div class="form-floating">
                <input type="usuario" class="form-control" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
                <label for="usuario">Usuário</label>
            </div>
            <br>

            <div class="form-floating">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua Senha" required>
                <label for="senha">Senha</label>
            </div>
            <br>

            <div class="checkbox">
                <label>
                    <input type="checkbox" value="Lembrar de mim" name="lembrar_login"> Lembrar login
                </label>
            </div>
            <br>

            <button class="btn btn-success" type="submit">Entrar</button>
            <a href="cadastro.html" class="btn btn-primary">Cadastre-se</a>
        </form>
    </div>

    <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/popper.min.js"></script>
    <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>
</body>