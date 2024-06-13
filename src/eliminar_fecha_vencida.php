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
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $hoy = date('Y-m-d');
    $vencimientoActual = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");
    $vencimientoNuevo = mysqli_fetch_assoc($vencimientoActual);
    $codproducto = $vencimientoNuevo['codproducto'];
    // esta mal este lote no es es el q se vencio
    $lote = $vencimientoNuevo['lote'];
    $cadena = $vencimientoNuevo['vencimiento'];
    // fecha
    $dividir = explode(",", $cadena);
    // lote
    $dividirlote = explode(",", $lote);
    $key = array_search(min($dividir), $dividir);

    $fechaAQuitar = min($dividir);
    if (count($dividir) > 1) {
        unset($dividir[$key]);
        unset($dividirlote[$key]);
    } else {
        if (min($dividir) < $hoy) {
            unset($dividir[0]);
            unset($dividirlote[0]);
        }
    }
    $arrayVencimiento = implode(",", $dividir);
    $arrayLote = implode(",", $dividirlote);
    $query_delete = mysqli_query($conexion, "UPDATE producto SET lote = '$arrayLote', vencimiento = '$arrayVencimiento' WHERE codproducto = $id");

    $insertar = mysqli_query($conexion, "INSERT INTO `vencidos`(`id`, `id_producto`, `lote`, `fecha`) VALUES (null,$codproducto,'$lote','$fechaAQuitar');");

    header("Location: vencidos_pendientes.php");
}
