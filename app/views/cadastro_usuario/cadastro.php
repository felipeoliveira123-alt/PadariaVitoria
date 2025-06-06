<!DOCTYPE html>
<html lang="pt-br">
<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">

  <title>Cadastro</title>

  <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/estilo.css">
  <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png?v=1">

</head>

<body>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <div class="card shadow rounded-4">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Cadastro</h3>

            <form action="/PadariaVitoria/index.php?rota=cadastro_usuario" method="POST">
              <div class="mb-3">
                <label for="nome" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
              </div>

              <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
              </div>

              <div class="mb-3">
                <label for="usuario" class="form-label">Usuário</label>
                <input type="usuario" class="form-control" id="usuario" name="usuario" required>
              </div>

              <div class="mb-3">
                <label for="senha" class="form-label">Primeiro nome da mãe</label>
                <input type="text" class="form-control" id="primeiro_nome_mae" name="primeiro_nome_mae" required>
              </div>

              <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
              </div>

              <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
              </div>
            </form>

            <p class="text-center mt-3">Já tem uma conta? <a href="/PadariaVitoria/index.php?rota=login">Entrar</a></p>
          </div>
        </div>

      </div>
    </div>
  </div>

</body>
</html>