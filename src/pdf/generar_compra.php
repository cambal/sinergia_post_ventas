<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
session_start();
$id_user = $_SESSION['idUser'];
$hoy = date('Y-m-d H:i:s');
$pdf = new FPDF('P', 'mm', array(80, 6500));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Compras");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
// 
$cajero = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_user");
$datosCajero = mysqli_fetch_assoc($cajero);
// 
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_compra d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_compra = $id");
// 
$ventas2 = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_compra d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_compra = $id ORDER BY p.descripcion ASC");
$datosVenta = mysqli_fetch_assoc($ventas2);
// 
$pdf->Cell(60, 10, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($datos['direccion']), 0, 1, 'C');
$pdf->Cell(70, 5, 'Tel: ' . $datos['telefono'], 0, 1, 'C');
$pdf->Cell(70, 5, 'Nit: ' . $datos['nit'], 0, 1, 'C');
$pdf->Cell(70, 5, $hoy, 0, 1, 'C');
// $pdf->Cell(70, 5, 'Factura de compra: ' . $datosVenta['id_compra'], 0, 1, 'C');
$pdf->Cell(70, 5, 'Cajero: ' . $datosCajero['nombre'], 0, 1, 'C');
$pdf->Cell(70, 5, utf8_decode($datos['email']), 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(23, 32, 42);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23, 32, 42);
$pdf->Cell(70, 5, "- - - - - - - - - - - - Detalle de Producto - - - - - - - - - - - -", 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(10, 5, 'Cant.', 0, 0, 'L');
$pdf->Cell(20, 5, 'Valor.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 8);
$total = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {
    $descrip = mb_convert_case($row['descripcion'], MB_CASE_LOWER, "UTF-8");
    $pdf->Cell(40, 5, substr($descrip, 0, 26), 0, 0, 'L');
    $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'L');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $pdf->Cell(20, 5, '$ ' . number_format($row['precio_compra'] * $row['cantidad']), 0, 1, 'L');
}
$pdf->Ln();
// 
$pdf->Cell(70, 5, "- - - - - - - - - - - - - - - - - Total - - - - - - - - - - - - - - - - -", 0, 1, 'C');
// 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Total: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($total), 0, 1, 'L');
// 
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 10, "- - Factura de compra - -", 0, 1, 'C');
$pdf->Output("factura_compra.pdf", "I");
