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
$ventas2 = mysqli_query($conexion, "SELECT * FROM residuos WHERE id_resi = $id");
$datosVenta = mysqli_fetch_assoc($ventas2);

// fpdf
$pdf = new FPDF($orientation = 'L', $unit = 'mm');
$pdf->SetTitle(utf8_decode("Residuos RH1"));
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$textypos = 5;
$pdf->setY(35);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Formulario RH1:");
// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(30);
$pdf->setX(75);
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(35);
$pdf->setX(115);
$pdf->Cell(5, $textypos, "Mes: ");
$pdf->SetFont('Arial', '', 10);
$pdf->setY(35);
$pdf->setX(137);

$fecha = date("Y-m-d", strtotime($datosVenta['fecha']));
$fechaSegundos = strtotime($fecha);
$dia = date("j", $fechaSegundos);
$mes = date("n", $fechaSegundos);
$año =  date("Y", $fechaSegundos);

$pdf->Cell(5, $textypos, utf8_decode($mes));
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(115);
$pdf->Cell(5, $textypos, utf8_decode("Año: "));
$pdf->SetFont('Arial', '', 10);
$pdf->setY(40);
$pdf->setX(137);
$pdf->Cell(5, $textypos, utf8_decode($año));
// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(35);
$pdf->setX(205);
$pdf->Cell(5, $textypos, 'Fecha: ');
$pdf->SetFont('Arial', '', 10);
$pdf->setY(35);
$pdf->setX(219);
$pdf->Cell(5, $textypos, utf8_decode($datosVenta['fecha']));
$pdf->setY(40);
$pdf->setX(205);
/// Apartir de aqui empezamos con la tabla de productos
$pdf->SetFont('Arial', '', 6);
$pdf->setY(60);
$pdf->setX(135);
$pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$header = array("", "Infecciosos o de riesgo biologico", "Quimicos", "Reactivos");
//// Array de Cabecera
$header2 = array("Residuos no peligrosos", "Residuos peligrosos");
//// Array de Cabecera
$header3 = array("biodegradables", "reciclables", "inertes", "ordinarios", "biosanitarios", "anatomopa", "cortopunzantes", "deanimales", "farmacos", "citotoxicos", "metales pesa", "reactivos", "conte presu", "aceites usados", "fuen abiertas", "fuen cerradas",);
// Column widths
$w = array(68, 68, 108, 36);
$w2 = array(68, 212);
$w3 = array(17, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18);
$w4 = array(17, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18);
// Header1
for ($i = 0; $i < count($header2); $i++)
    $pdf->Cell($w2[$i], 7, utf8_decode($header2[$i]), 1, 0, 'C');
$pdf->Ln();
// Header2
for ($i = 0; $i < count($header); $i++)
    $pdf->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C');
$pdf->Ln();
// header3
for ($i = 0; $i < count($header3); $i++)
    $pdf->Cell($w3[$i], 7, utf8_decode($header3[$i]), 1, 0, 'C');
$pdf->Ln();
// Data
$total = 0;
foreach ($ventas2 as $row) {
    // $date = new Datetime($row['fecha']);
    // $fecha = strftime(utf8_decode("%d de %B de %Y"), $date->getTimestamp());
    // $pdf->Cell($w4[0], 6, $fecha, 1);
    $pdf->Cell($w4[0], 6, $row['biodegradables'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[1], 6, $row['reciclables'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[2], 6, $row['inertes'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[3], 6, $row['ordinarios'] . ' kg', 1, 0, 'C');

    $pdf->Cell($w4[4], 6, $row['biosanitarios'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[5], 6, $row['anatomopatologicos'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[6], 6, $row['cortopunzantes'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[7], 6, $row['deanimales'] . ' kg', 1, 0, 'C');

    $pdf->Cell($w4[8], 6, $row['farmacos'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[9], 6, $row['citotoxicos'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[10], 6, $row['metales_pesados'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[11], 6, $row['reactivos'] . ' kg', 1, 0, 'C');
    $pdf->Cell($w4[12], 6, $row['contenedores_presurizados'], 1, 0, 'C');
    $pdf->Cell($w4[13], 6, $row['aceites_usados'] . ' kg', 1, 0, 'C');

    $pdf->Cell($w4[14], 6, $row['fuentes_abiertas'], 1, 0, 'C');
    $pdf->Cell($w4[15], 6, $row['fuentes_cerradas'], 1, 0, 'C');

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
