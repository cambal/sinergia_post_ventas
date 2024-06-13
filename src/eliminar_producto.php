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
    // $query_delete = mysqli_query($conexion, "SET FOREIGN_KEY_CHECKS=0;
    // DELETE FROM `producto` WHERE `codproducto` = $id LIMIT 1;
    // SET FOREIGN_KEY_CHECKS=1;");
    $query_delete = mysqli_query($conexion, "UPDATE `producto` SET `existencia` = 0,`lote` = '',`vencimiento` = '',`delete`= 1 WHERE codproducto = $id");
    mysqli_close($conexion);
    header("Location: productos.php");
}
