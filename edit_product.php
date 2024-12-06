<?php
include 'db.php';
session_start();

// Check if the user is logged in and if they are an administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only administrators can access this page.";
    exit;
}

// Check if a product ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "No product ID specified.";
    exit;
}

$product_id = $_GET['id'];

// Fetch the current details of the product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit;
}

// Update product details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Update product in the database
    $stmt = $pdo->prepare("UPDATE products SET quantity = ?, description = ? WHERE id = ?");
    $stmt->execute([$quantity, $description, $product_id]);

    echo "Product updated successfully.";
    header("Location: products.php"); // Redirect to products list
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
