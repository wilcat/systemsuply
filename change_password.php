<?php
include './includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE users SET password = ?, requires_password_change = 0 WHERE id = ?");
    $stmt->execute([$new_password, $user_id]);

    echo "Password changed successfully!";
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Mudar a Senha</title>
</head>
<body>
    <h2>Mudar senha</h2>
    <form method="POST" action="change_password.php">
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Mudar senha</button>
    </form>
</body>
</html>
