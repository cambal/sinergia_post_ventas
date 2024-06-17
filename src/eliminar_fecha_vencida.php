<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];
    $lote = $_GET['lote'];
    $hoy = date('Y-m-d');

    $consulta = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_producto AND lote = '$lote'");

    $array = mysqli_fetch_assoc($consulta);
    $existencia = $array['existencia'];
    $vencimiento = $array['vencimiento'];

    $eliminar = mysqli_query($conexion, "DELETE FROM lotes WHERE id_producto = $id_producto AND lote = '$lote'");

    if ($eliminar) {
        $insertar = mysqli_query($conexion, "INSERT INTO `vencidos`(`id`, `id_producto`, `lote`, `cantidad`, `vencimiento`, `fecha`) VALUES (null,$id_producto,'$lote', $existencia, $vencimiento, '$hoy');");
    }
    header("Location: vencidos_pendientes.php");
}
