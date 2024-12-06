<?php
include 'db.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access denied.');

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);
header('Location: users.php');
?>
