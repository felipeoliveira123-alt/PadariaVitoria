<!DOCTYPE html>
<html lang="pt-BR">

<head lang="pt-BR" >

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">

    <link href="css/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-5.3.5-dist/css/estilo.css">
    <link rel="icon" type="image/png" href="images/Logotipo.png">

    <title>Padaria Vitória</title>
            
    </head>
    
    <body>

        <div class="container d-flex justify-content-center align-items-center min-vh-100">

            <form class="p-4 p-md-5 border rounded-3 bg-light shadow-sm formulariologin" action="login.php" method="POST">

                <div class="text-center mb-4">

                 <img src="images/Logotipo.png" class="img-fluid imagemLogotipoPadaria" alt="Logo da Padaria">

                </div>


                <div class="col-9 col-md-10">

                    <h2>Login</h2>

                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email">
                    <label for=inputemail>E-mail</label>
                </div>
                <br>

                <div class="form-floating">
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua Senha">
                    <label for=inputPassword>Senha</label>
                </div>
                <br>

                <div class="checkbox">

                    <label>

                        <input type="checkbox" value="Lembrar de mim"> Lembrar login
                    </label>

                </div>
                    
                <br>

                <button class="btn btn-success" type="submit">Entrar</button>

            </form>

        </div>

    </body>

    <script src="css/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="css/bootstrap-5.3.5-dist/js/popper.min.js"></script>

    <script src="css/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>

</html>