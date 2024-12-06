<?php
include 'db.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access denied.');

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);
header('Location: products.php');
?>
