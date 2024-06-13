<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
session_start();
$id_user = $_SESSION['idUser'];
$id = $_GET['v'];
$hoy = date('Y-m-d H:i:s');
// consultas
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
// 
$ventas2 = mysqli_query($conexion, "SELECT d.*, p.*, c.fecha FROM detalle_compra d INNER JOIN producto p ON d.id_producto = p.codproducto INNER JOIN compras c ON d.id_compra = c.id WHERE d.id_compra = $id ORDER BY p.descripcion ASC");
$datosVenta = mysqli_fetch_assoc($ventas2);
// fpdf
$pdf = new FPDF($orientation = 'L', $unit = 'mm');
$pdf->SetTitle(utf8_decode("Recepción técnica"));
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);
$textypos = 5;
$pdf->setY(12);
$pdf->setX(10);
// Agregamos los datos de la empresa
$pdf->Cell(5, $textypos, utf8_decode($datos['nombre']));
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(35);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Proveedor:");
$pdf->SetFont('Arial', '', 10);
$pdf->setY(35);
$pdf->setX(30);
$pdf->Cell(5, $textypos, utf8_decode($datosVenta['proveedor']));
// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(30);
$pdf->setX(75);
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(35);
$pdf->setX(115);
$pdf->Cell(5, $textypos, "Factura No: ");
$pdf->SetFont('Arial', '', 10);
$pdf->setY(35);
$pdf->setX(137);
$pdf->Cell(5, $textypos, utf8_decode($datosVenta['num_fac_compra']));
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(115);
$pdf->Cell(5, $textypos, "Consecutivo No: ");
$pdf->SetFont('Arial', '', 10);
$pdf->setY(40);
$pdf->setX(145);
$pdf->Cell(5, $textypos, utf8_decode($datosVenta['id']));
// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(35);
$pdf->setX(205);
$pdf->Cell(5, $textypos, 'Fecha: ');
$pdf->SetFont('Arial', '', 10);
$pdf->setY(35);
$pdf->setX(219);
$pdf->Cell(5, $textypos, utf8_decode($datosVenta['fecha']));
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(205);
/// Apartir de aqui empezamos con la tabla de productos
$pdf->setY(60);
$pdf->setX(135);
$pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$header = array("CodBarras", "Nombre", "Laboratorío", "Cant", "Lote", "Invima", "Vencimiento");
// Column widths
$w = array(35, 60, 55, 15, 30, 50, 30);
// Header
for ($i = 0; $i < count($header); $i++)
    $pdf->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C');
$pdf->Ln();
// Data
$total = 0;
foreach ($ventas2 as $row) {
    $descrip = mb_convert_case($row['descripcion'], MB_CASE_LOWER, "UTF-8");
    $lab = mb_convert_case($row['laboratorio'], MB_CASE_LOWER, "UTF-8");
    $pdf->Cell($w[0], 6, $row['codigo'], 1);
    $pdf->Cell($w[1], 6, substr($descrip, 0, 30), 1);
    $pdf->Cell($w[2], 6, substr($lab, 0, 31), 1);
    $pdf->Cell($w[3], 6, $row['cantidad'], 1);
    $pdf->Cell($w[4], 6, $row['lote_c'], 1);
    $pdf->Cell($w[5], 6, $row['invima'], 1);
    $pdf->Cell($w[6], 6, $row['vencimiento_fac'], 1);

    $pdf->Ln();
}

$pdf->setY(100);
$pdf->setX(435);
$pdf->Ln();

$pdf->SetFont('Arial', '');
$pdf->Cell(200, 17, utf8_decode("------------------------------------------------"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(78, 17, utf8_decode("------------------------------------------------"), 0, 1, 'L');

$pdf->SetFont('Arial', '',);
$pdf->Cell(200, 1, utf8_decode("Firma Usuario"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(78, 1, utf8_decode("Firma Revisor"), 0, 1, 'L');

$pdf->output();
