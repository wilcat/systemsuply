<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Check if user needs to change password
        if ($user['requires_password_change']) {
            header('Location: change_password.php');
            exit;
        } else {
            header('Location: dashboard.php');
            exit;
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <div class="container body vh-100 d-flex block justify-content-center align-items-center; display: center;" style="background-color: rgb(6,57,112);">
    <div class="container vh-100 d-flex block justify-content-center align-items-center; display: center;">
    <div class="img-fluid w-100 block justify-content-center align-items-center; display: center">
        <img src="./img/favicon.png" alt="Logo" class="img-fluid w-50" style="max-width: 400px; width: 100%; height: auto; padding: 10px; display: center">
        <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
    <h2 >Login</h2>
    <form method="POST" action="login.php">
        <div class="mb-3">
        <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
        <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <p>Não tem conta? <a href="register.php">Registre aqui!</a></p>
</body>
</html>
