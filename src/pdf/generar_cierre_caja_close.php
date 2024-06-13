<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
session_start();
$id_user = $_SESSION['idUser'];
$hoy = date('Y-m-d H:i:s');
$pdf = new FPDF('P', 'mm', array(80, 900));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Cierre caja");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$efectivo = $_GET['efec'];
$nequi = $_GET['nequi'];
$daviplata = $_GET['daviplata'];
$tarjeta = $_GET['tarjeta'];
$efec_fis = $_GET['efec_fis'];
$cuanto_pagaste = $_GET['cuanto_pagaste'];
$creando = $_GET['creando'];
if ($creando == 'si') {
    $fecha = date('Y-m-d H:i:s');
} else {
    $fecha = $_GET['fecha'];
}
$sobr = $_GET['sobr'];
$obs = $_GET['obs'];
$id = $_GET['v'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
// 
$cajero = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_user");
$datosCajero = mysqli_fetch_assoc($cajero);
// 
// $cierre_caja = mysqli_query($conexion, "SELECT * FROM cierre_caja");
// $datosCierre_caja = mysqli_fetch_assoc($cierre_caja);
//  
$pdf->Cell(60, 10, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->Cell(70, 10, utf8_decode('Cierre Caja'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($datos['direccion']), 0, 1, 'C');
$pdf->Cell(70, 5, 'Tel: ' . $datos['telefono'], 0, 1, 'C');
$pdf->Cell(70, 5, 'Nit: ' . $datos['nit'], 0, 1, 'C');
$pdf->Cell(70, 5, $fecha, 0, 1, 'C');
// $pdf->Cell(70, 5, 'Factura de venta: ' . $datosVenta['id_venta'], 0, 1, 'C');
$pdf->Cell(70, 5, 'Cajero: ' . $datosCajero['nombre'], 0, 1, 'C');
$pdf->Cell(70, 5, utf8_decode($datos['email']), 0, 1, 'C');
$pdf->Ln();
$pdf->Cell(70, 5, "- - - - - - - - - - - - - - Metodo pago - - - - - - - - - - - - - -", 0, 1, 'C');
// 
if($efectivo != 0){
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Efectivo: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($efectivo), 0, 1, 'L');
}
// 
if($nequi != 0){
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Nequi: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($nequi), 0, 1, 'L');
}
// 
if($daviplata != 0){
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Daviplata: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($daviplata), 0, 1, 'L');
}
// 
if($tarjeta != 0){
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Tarjeta: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($tarjeta), 0, 1, 'L');
}
// 
$pdf->Cell(70, 5, "- - - - - - - - - - - - - - - - - Total - - - - - - - - - - - - - - - - -", 0, 1, 'C');
// 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Total: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, '$ ' . number_format($efectivo + $nequi + $daviplata + $tarjeta), 0, 1, 'L');
// 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 10, utf8_decode("Total actual: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 10, '$ ' . number_format($efec_fis), 0, 1, 'L');
// 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Sobrante: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, number_format($sobr), 0, 1, 'L');
// 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 7, utf8_decode("Gastos: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 7, number_format($cuanto_pagaste), 0, 1, 'L');
// 
$pdf->Cell(70, 5, "- - - - - - - - - - - - - - Observaciones - - - - - - - - - - - - - -", 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(70, 5, utf8_decode($obs), 0, 'L');
// fin
$pdf->Output("factura_venta.pdf", "I");
session_destroy();