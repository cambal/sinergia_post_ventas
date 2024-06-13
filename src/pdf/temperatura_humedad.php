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
$ventas2 = mysqli_query($conexion, "SELECT * FROM temperatura_humedad WHERE id_temp = $id ORDER BY fecha ASC");
$datosVenta = mysqli_fetch_assoc($ventas2);

// fpdf
$pdf = new FPDF($orientation = 'L', $unit = 'mm');
$pdf->SetTitle(utf8_decode("Temperatura y humedad"));
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$textypos = 5;
$pdf->setY(35);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Control temperatura y humedad relativa");
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
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(205);
/// Apartir de aqui empezamos con la tabla de productos
$pdf->setY(60);
$pdf->setX(135);
$pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$header = array("Temperatura", "Humedad", "Hora", "Fecha");
// Column widths
$w = array(65, 70, 65, 75);
// Header
for ($i = 0; $i < count($header); $i++)
    $pdf->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C');
$pdf->Ln();
// Data
$total = 0;
foreach ($ventas2 as $row) {

    if ($row['hora'] == '9') {
        $hour = '9am';
    } else if ($row['hora'] == '1') {
        $hour = '1pm';
    } else if ($row['hora'] == '5') {
        $hour = '5pm';
    }

    $pdf->Cell($w[0], 6, utf8_decode($row['temperatura'] . '°C'), 1);
    $pdf->Cell($w[1], 6, $row['humedad'] . '%', 1);
    $pdf->Cell($w[2], 6, $hour, 1);
    $pdf->Cell($w[3], 6, $row['fecha'], 1);

    $pdf->Ln();
}

$pdf->setY(100);
$pdf->setX(435);
$pdf->Ln();

$pdf->output();
