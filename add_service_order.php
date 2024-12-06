<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $technician_name = $_POST['technician_name'];
    $materials = implode(', ', $_POST['materials']); // Join multiple materials as a single string

    // Insert service order into the database
    $stmt = $pdo->prepare("INSERT INTO service_orders (technician_name, materials) VALUES (?, ?)");
    $stmt->execute([$technician_name, $materials]);

    echo "Service order created successfully!";
    header("Location: service_orders.php");
    exit;
}

// Fetch available materials for selection
$materials_stmt = $pdo->prepare("SELECT id, description FROM products WHERE quantity > 0");
$materials_stmt->execute();
$materials = $materials_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Service Order</h2>
        <form method="POST" action="add_service_order.php">
            <div class="mb-3">
                <label for="technician_name" class="form-label">Technician Name</label>
                <input type="text" class="form-control" id="technician_name" name="technician_name" required>
            </div>
            <div class="mb-3">
                <label for="materials" class="form-label">Materials Used</label>
                <select class="form-control" id="materials" name="materials[]" multiple required>
                    <?php foreach ($materials as $material) { ?>
                        <option value="<?php echo htmlspecialchars($material['description']); ?>">
                            <?php echo htmlspecialchars($material['description']); ?>
                        </option>
                    <?php } ?>
                </select>
                <small class="form-text text-muted">Hold Ctrl (or Command on Mac) to select multiple materials.</small>
            </div>
            <button type="submit" class="btn btn-primary">Add Service Order</button>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
