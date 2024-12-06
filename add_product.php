<?php
include 'db.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access denied.');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare("INSERT INTO products (description, quantity) VALUES (?, ?)");
    $stmt->execute([$description, $quantity]);
    echo "Produto adicionado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Adicionar Produtos</h2>
        <form method="POST" action="add_product.php">
            <div class="mb-3">
                <label for="description" class="form-label">Descrição dos Produtos</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Produto</button>
            <a href="products.php" class="btn btn-secondary">Volta ao Produtos</a>
        </form>
    </div>
</body>
</html>