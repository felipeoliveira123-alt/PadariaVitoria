<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização da Padaria Vitória</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Atualização do Sistema</h3>
                    </div>
                    <div class="card-body">
                        <h4>Atualização do Sistema da Padaria Vitória</h4>
                        <p class="mb-4">É necessário atualizar o banco de dados para incluir o suporte a itens avulsos no carrinho.</p>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Esta atualização modificará a estrutura do banco de dados. Certifique-se de fazer um backup antes de prosseguir.
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="app/database/update_schema.php" class="btn btn-primary">
                                Executar Atualização
                            </a>
                            <a href="app/views/vendas.php" class="btn btn-outline-secondary">
                                Pular atualização (não recomendado)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
