<?php
require('includes/fpdf.php');
include 'db.php';
session_start();

// Check if the user is logged in and has access to generate reports
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only administrators can access this page.";
    exit;
}

// Fetch inventory data
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id ASC");
$stmt->execute();
$products = $stmt->fetchAll();

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Title
$pdf->Cell(0, 10, 'Inventory Report', 1, 1, 'C');
$pdf->Ln(10);

// Table headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'Product ID', 1);
$pdf->Cell(80, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Ln();

// Table content
$pdf->SetFont('Arial', '', 10);
foreach ($products as $product) {
    $pdf->Cell(30, 10, $product['id'], 1);
    $pdf->Cell(80, 10, $product['description'], 1);
    $pdf->Cell(30, 10, $product['quantity'], 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output('I', 'inventory_report.pdf');
?>
