<?php
require('./includes/fpdf.php');
include('db.php'); // Include your DB connection file

class PDF extends FPDF {
    // Header
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Inventory Report', 0, 1, 'C');
        $this->Ln(5);
    }

    // Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Retrieve filter values
$startDate = $_POST['start_date'] ?? '';
$endDate = $_POST['end_date'] ?? '';
$productCategory = $_POST['product_category'] ?? '';

// SQL query with optional filters
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
if ($startDate) {
    $query .= " AND date_added >= ?";
    $params[] = $startDate;
}
if ($endDate) {
    $query .= " AND date_added <= ?";
    $params[] = $endDate;
}
if ($productCategory) {
    $query .= " AND category = ?";
    $params[] = $productCategory;
}

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll();

// Initialize PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Table header
$pdf->Cell(40, 10, 'Product ID', 1);
$pdf->Cell(60, 10, 'Product Name', 1);
$pdf->Cell(30, 10, 'Category', 1);
$pdf->Cell(20, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Date Added', 1);
$pdf->Ln();

// Populate table rows
foreach ($results as $row) {
    $pdf->Cell(40, 10, $row['product_id'], 1);
    $pdf->Cell(60, 10, $row['product_name'], 1);
    $pdf->Cell(30, 10, $row['category'], 1);
    $pdf->Cell(20, 10, $row['quantity'], 1);
    $pdf->Cell(30, 10, $row['date_added'], 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('I', 'Inventory_Report.pdf');
?>
