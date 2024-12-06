<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only administrators can access this page.";
    exit;
}

// Handle role update or delete requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user_id'])) {
        // Delete user
        $delete_id = $_POST['delete_user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        echo "User deleted successfully.";
    } elseif (isset($_POST['modify_user_id']) && isset($_POST['new_role'])) {
        // Update user role
        $modify_id = $_POST['modify_user_id'];
        $new_role = $_POST['new_role'];
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $modify_id]);
        echo "User role updated successfully.";
    }
}

// Fetch users list from the database
$stmt = $pdo->prepare("SELECT id, username, role FROM users ORDER BY id ASC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Usuarios</h2>
        
        <?php if (count($users) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <form method="POST" style="display: inline-block;">
                                    <!-- Modify Role -->
                                    <select name="new_role" class="form-select form-select-sm d-inline w-auto" required>
                                        <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                                        <option value="viewer" <?php if ($user['role'] === 'viewer') echo 'selected'; ?>>Viewer</option>
                                        <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                    <input type="hidden" name="modify_user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-warning">Update Role</button>
                                </form>
                                
                                <!-- Delete User -->
                                <?php if ($user['role'] !== 'admin') { ?>
                                    <form method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No users found.</p>
        <?php } ?>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Dashboard</a>
    </div>
</body>
</html>
