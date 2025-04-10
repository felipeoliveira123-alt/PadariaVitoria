<?php
session_start();
include 'config/conexao.php';

// Verifica se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$mensagem = "";
$erro = "";
$total = 0;

// Adiciona produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['codigo_barras'])) {
        $codigo = $_POST['codigo_barras'];
        $sql = "SELECT nome, preco FROM produtos WHERE codigo_barras = '$codigo'";
        $res = $conexao->query($sql);

        if ($res && $res->num_rows > 0) {
            $produto = $res->fetch_assoc();
            $_SESSION['carrinho'][] = $produto;
        } else {
            $erro = "Produto não encontrado!";
        }
    }

    // Finaliza a compra
    if (isset($_POST['finalizar'])) {
        $_SESSION['carrinho'] = [];
        $mensagem = "Compra finalizada com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/Logotipo.png">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Carrinho de Compras</h2>
        <a href="logout.php" class="btn btn-outline-danger">Sair</a>
    </div>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <?php if ($mensagem): ?>
        <div class="alert alert-success"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-8">
            <input type="text" name="codigo_barras" class="form-control" placeholder="Digite o código de barras" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Adicionar Produto</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Preço (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION['carrinho'])): ?>
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
                    </tr>
                    <?php $total += $item['preco']; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">Nenhum produto no carrinho.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot class="table-secondary">
            <tr>
                <th>Total</th>
                <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <form method="POST" class="text-end">
        <button type="submit" name="finalizar" class="btn btn-success">Finalizar Compra</button>
    </form>
</div>

</body>
</html>
