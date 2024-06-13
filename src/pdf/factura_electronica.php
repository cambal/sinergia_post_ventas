<?php
require_once '../../conexion.php';
include "fpdf/fpdf.php";

$hoy = date('Y-m-d H:i:s');
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
// 
$ventas2 = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion, p.codigo FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id AND estado = 0");
$datosVenta = mysqli_fetch_assoc($ventas2);
// 
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
// 
$pdf = new FPDF($orientation = 'P', $unit = 'mm');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);
$textypos = 5;
// Agregamos los datos de la empresa
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(10);
$pdf->setX(10);
# Logo de la empresa formato png #
// $pdf->Image('../../assets/img/favicon.png', 5, 5, 35, 35, 'PNG');

// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(10);
$pdf->setX(60);
$pdf->Cell(5, $textypos, utf8_decode($datos['nombre']));
$pdf->SetFont('Arial', '', 10);
$pdf->setY(15);
$pdf->setX(60);
$pdf->Cell(5, $textypos, utf8_decode($datos['nit']));
$pdf->SetFont('Arial', '', 10);
$pdf->setY(20);
$pdf->setX(60);
$pdf->Cell(5, $textypos, utf8_decode($datos['direccion']));
$pdf->setY(25);
$pdf->setX(60);
$pdf->Cell(5, $textypos, $datos['telefono']);
$pdf->setY(30);
$pdf->setX(60);
$pdf->Cell(5, $textypos, $datos['email']);

// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10,);
$pdf->setY(10);
$pdf->setX(135);
$pdf->Cell(5, $textypos, "FACTURA DE VENTA");
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(15);
$pdf->setX(140);
$pdf->Cell(5, $textypos, utf8_decode("ELECTRÓNICA"));
$pdf->SetFont('Arial', '', 10);
$pdf->setY(20);
$pdf->setX(150);
$pdf->Cell(5, $textypos, $datosVenta['id_venta']);

/// Apartir de aqui empezamos con la tabla de productos
$pdf->setY(40);
$pdf->setX(135);
$pdf->Ln();
// 
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(23, 32, 42);
if ($datosC['cedulaNit'] != '12345') {
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(23, 32, 42);
    $pdf->Cell(180, 10, "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - Datos del cliente - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ", 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(63, 5, utf8_decode('NIT'), 0, 0, 'L');
    $pdf->Cell(63, 5, utf8_decode('Nombre'), 0, 0, 'L');
    $pdf->Cell(63, 5, utf8_decode('Teléfono'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(63, 5, substr(utf8_decode($datosC['cedulaNit']), 0, 25), 0, 0, 'L');
    $pdf->Cell(63, 5, substr(utf8_decode($datosC['nombre']), 0, 18), 0, 0, 'L');
    $pdf->Cell(63, 5, utf8_decode($datosC['telefono']), 0, 1, 'L');
    $pdf->Ln(3);
}
$pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$pdf->Cell(180, 15, "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - Datos de producto - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ", 0, 1, 'C');
$header = array("Producto", utf8_decode("Descripción"), "Cant", "Precio", "Importe");
// Column widths
$w = array(35, 70, 25, 30, 30);
// Header
for ($i = 0; $i < count($header); $i++)
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
$pdf->Ln();
// Data
$total = 0;
foreach ($ventas2 as $row) {
    $descrip = mb_convert_case($row['descripcion'], MB_CASE_LOWER, "UTF-8");
    $pdf->Cell($w[0], 6, $row['codigo'], 1);
    $pdf->Cell($w[1], 6, substr($descrip, 0, 38), 1);
    $pdf->Cell($w[2], 6, $row['cantidad'], 1);
    $pdf->Cell($w[3], 6, '$ ' . number_format($row['precio']), 1);
    $pdf->Cell($w[4], 6, '$ ' . number_format($row['precio']), 1);

    $pdf->Ln();
}
$pdf->setX(235);
$pdf->Ln();


$pdf->output();
