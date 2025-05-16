<!DOCTYPE html>

<html lang="pt-br">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Menu - Padaria</title>

  <script src="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>

  <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js" rel="stylesheet">
  <link rel="stylesheet" href="/PadariaVitoria/app/public/css/bootstrap-5.3.5-dist/css/estilo.css">
  <link rel="icon" type="image/png" href="/PadariaVitoria/app/public/images/Logotipo.png?v=1">

  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    /* Carrossel em tela cheia */
    .carousel-item {
      height: 100vh;
      position: relative;
    }

    .carousel-item img {
      object-fit: cover;
      width: 100%;
      height: 100%;
      filter: brightness(50%); /* efeito cinza escuro */
    }

    /* Texto sobre a imagem */
    .carousel-caption {
      bottom: 50%;
      transform: translateY(50%);
    }

    .carousel-caption h1, .carousel-caption p {
      color: white;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
    }

    /* Barra de navegação */
    .navbar {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      z-index: 10;
    }
  </style>
</head>
<body>

  <!-- Navbar com dropdown -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
  <a class="navbar-brand d-flex align-items-center" href="#">
    <img src="/PadariaVitoria/app/public/images/Logotipo.png" alt="Logo Padaria" width="40" height="40" class="me-2">
    <span class="fw-bold text-white">Padaria Vitória</span>
  </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav" aria-controls="menuNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Menu
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
              <li><a class="dropdown-item" href="../controle_ponto/ControlePonto.html">Controle de Ponto</a></li>

              <li><a class="dropdown-item" href="../relatorios.php">Relatório Financeiro</a></li>

              <!-- Removido: Controle de Estoque -->

              <li>
                  <a class="dropdown-item bg-light border border-primary rounded text-primary fw-semibold" href="../vendas.php">
                  Carrinho de Compras
                </a>
              </li>

              <li><a class="dropdown-item" href="../produtos.php">Controle de Estoque</a></li>

              <li><hr class="dropdown-divider"></li>

              <li><a class="dropdown-item text-danger" href="../logout.php">Sair</a></li>
            </ul>

      </div>
    </div>
  </nav>

  <!-- Carrossel full screen com fade automático -->
  <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="/PadariaVitoria/app/public/images/paes.jpg" class="d-block w-100" alt="Pães frescos">
        <div class="carousel-caption text-center">
          <h1>Bem-vindo à Padaria Vitória</h1>
          <p>Os melhores pães da cidade</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="/PadariaVitoria/app/public/images/biscoitos.jpg" class="d-block w-100" alt="Biscoitos artesanais">
        <div class="carousel-caption text-center">
          <h1>Biscoitos artesanais</h1>
          <p>Feitos com carinho e ingredientes naturais</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="/PadariaVitoria/app/public/images/cafe.jpg" class="d-block w-100" alt="Café fresco">
        <div class="carousel-caption text-center">
          <h1>Café fresquinho</h1>
          <p>Perfeito para acompanhar seu pãozinho</p>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Próximo</span>
    </button>
  </div>

</body>

</html>
