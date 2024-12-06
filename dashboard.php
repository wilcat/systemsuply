<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Bemvindo ao Estoque Trend Connect</h1>
        <div class="mt-3">
            <a href="products.php" class="btn btn-primary">Produtos</a>
            <a href="users.php" class="btn btn-primary">Usuários</a>
            <a href="inventory_report.php" class="btn btn-primary">Relatório Estoque</a>
            <a href="add_service_order.php" class="btn btn-primary">Ordem de Seviço</a>
            <?php if ($role === 'admin'): ?>
                <a href="access_report.php" class="btn btn-primary">Relatório de Acessos</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
