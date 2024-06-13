<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
session_start();
$id_user = $_SESSION['idUser'];
$hoy = date('Y-m-d H:i:s');
$pdf = new FPDF('P', 'mm', array(80, 500));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
// 
$cajero = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_user");
$datosCajero = mysqli_fetch_assoc($cajero);
// 
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id AND d.estado = 1");
// 
$ventas2 = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$datosVenta = mysqli_fetch_assoc($ventas2);
// 
$pdf->Cell(60, 0, utf8_decode($datos['nombre']), 10, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 10, utf8_decode($datos['direccion']), 0, 1, 'C');
$pdf->Cell(70, 5, 'Tel: ' . $datos['telefono'], 0, 1, 'C');
$pdf->Cell(70, 5, 'Nit: ' . $datos['nit'], 0, 1, 'C');
$pdf->Cell(70, 5, $hoy, 0, 1, 'C');
$pdf->Cell(70, 5, 'Factura de venta: ' . $datosVenta['id_venta'], 0, 1, 'C');
$pdf->Cell(70, 5, 'Cajero: ' . $datosCajero['nombre'], 0, 1, 'C');
$pdf->Cell(70, 5, utf8_decode($datos['email']), 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(23, 32, 42);
if ($datosC['cedulaNit'] != '12345') {
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(23, 32, 42);
    $pdf->Cell(70, 5, "- - - - - - - - - - - - - Datos del cliente - - - - - - - - - - - - -", 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(25, 5, utf8_decode('NIT'), 0, 0, 'L');
    $pdf->Cell(25, 5, utf8_decode('Nombre'), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode('Teléfono'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(25, 5, substr(utf8_decode($datosC['cedulaNit']), 0, 25), 0, 0, 'L');
    $pdf->Cell(25, 5, substr(utf8_decode($datosC['nombre']), 0, 18), 0, 0, 'L');
    $pdf->Cell(30, 5, utf8_decode($datosC['telefono']), 0, 1, 'L');
    $pdf->Ln(3);
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23, 32, 42);
$pdf->Cell(70, 5, "- - - - - - - - - - - - Detalle de Producto - - - - - - - - - - - -", 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(36, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(15, 5, 'Cant.', 0, 0, 'L');
$pdf->Cell(20, 5, 'Valor.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 8);
$total = 0.00;
$desc = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(36, 5, substr(strtolower($row['descripcion']), 0, 25), 0, 0, 'L');
    $pdf->Cell(15, 5, $row['cantidad'] . ' x' . $row['cant_unidad'] . ' uni', 0, 0, 'L');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $desc = $desc + $row['descuento'];
    $pdf->Cell(20, 5, '$ ' . number_format($row['total']), 0, 1, 'L');
}
$pdf->Ln();
// 
$pdf->Cell(70, 5, "- - - - - - - - - - - - - - - - - Total - - - - - - - - - - - - - - - - -", 0, 1, 'C');
if ($desc != 0) {
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, utf8_decode("Total: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 7, '$ ' . number_format($total + $desc), 0, 1, 'L');
    // 
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, utf8_decode("Descuento: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 7, '$ ' . number_format($desc), 0, 1, 'L');
}
// 
$pdf->SetFont('Arial', 'B',);
$pdf->Cell(50, 7, utf8_decode("Total: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($total), 0, 1, 'L');
// 
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 10, "- - Gracias por tu compra - -", 0, 1, 'C');
$pdf->Output("factura_venta.pdf", "I");
