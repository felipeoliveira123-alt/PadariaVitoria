<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Recuperação de Senha</title>

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
            <h3 class="card-title text-center mb-4">Recuperação de Senha</h3>

            <form action="/PadariaVitoria/app/views/recuperacao_senha/verificar_perguntas.php" method="POST">
              <div class="mb-3">
                <label for="nome_completo" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
              </div>

              <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
              </div>

              <div class="mb-3">
                <label for="nome_mae" class="form-label">Primeiro Nome da Mãe</label>
                <input type="text" class="form-control" id="nome_mae" name="nome_mae" required>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Verificar</button>
              </div>
            </form>

            <p class="text-center mt-3">Lembrou sua senha? <a href="/PadariaVitoria/index.php">Voltar ao login</a></p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php
  if (isset($_GET['email']) && isset($_GET['senha'])) {
      $email = htmlspecialchars($_GET['email']);
      $senha = htmlspecialchars($_GET['senha']);
      echo "<script>
          window.onload = function() {
              alert('Usuário encontrado:\\nE-mail: $email\\nSenha (MD5): $senha');
          };
      </script>";
  } elseif (isset($_GET['erro'])) {
      echo "<script>
          window.onload = function() {
              alert('Dados incorretos. Nenhum usuário encontrado.');
          };
      </script>";
  }
  ?>

</body>
</html>