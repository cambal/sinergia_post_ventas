<?php
// session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$hoy = date('Y-m-d');
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
$query = mysqli_query($conexion, "SELECT v.id, v.metodo_pago, v.id_usuario, v.id_cliente, v.fecha, d.id_detalle_venta, d.id_producto, d.id_venta, d.cantidad, d.cant_unidad, d.descuento, d.precio, d.total, d.estado, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy' AND d.estado = 0 AND d.cierre_caja = 0");

$queryEfectivo = mysqli_query($conexion, "SELECT v.id, v.metodo_pago, v.id_usuario, v.id_cliente, v.fecha, d.id_detalle_venta, d.id_producto, d.id_venta, d.cantidad, d.cant_unidad, d.descuento, d.precio, d.total, d.estado, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy' AND d.estado = 0 AND metodo_pago = 'efectivo' AND d.cierre_caja = 0");

$queryNequi = mysqli_query($conexion, "SELECT v.id, v.metodo_pago, v.id_usuario, v.id_cliente, v.fecha, d.id_detalle_venta, d.id_producto, d.id_venta, d.cantidad, d.cant_unidad, d.descuento, d.precio, d.total, d.estado, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy' AND d.estado = 0 AND metodo_pago = 'nequi' AND d.cierre_caja = 0");

$queryDaviplata = mysqli_query($conexion, "SELECT v.id, v.metodo_pago, v.id_usuario, v.id_cliente, v.fecha, d.id_detalle_venta, d.id_producto, d.id_venta, d.cantidad, d.cant_unidad, d.descuento, d.precio, d.total, d.estado, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy' AND d.estado = 0 AND metodo_pago = 'daviplata' AND d.cierre_caja = 0");

$queryTarjeta = mysqli_query($conexion, "SELECT v.id, v.metodo_pago, v.id_usuario, v.id_cliente, v.fecha, d.id_detalle_venta, d.id_producto, d.id_venta, d.cantidad, d.cant_unidad, d.descuento, d.precio, d.total, d.estado, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy' AND d.estado = 0 AND metodo_pago = 'tarjeta' AND d.cierre_caja = 0");

if ($query) {
    $totalSumadoEfectivo = 0;
    $totalSumadoNequi = 0;
    $totalSumadoDaviplata = 0;
    $totalSumadoTarjeta = 0;
    $totalSumado = 0;
    while ($row = mysqli_fetch_assoc($query)) {
        $totalSumado += $row['total'];
    }
    while ($row = mysqli_fetch_assoc($queryEfectivo)) {
        $totalSumadoEfectivo += $row['total'];
    }
    while ($row = mysqli_fetch_assoc($queryNequi)) {
        $totalSumadoNequi += $row['total'];
    }
    while ($row = mysqli_fetch_assoc($queryDaviplata)) {
        $totalSumadoDaviplata += $row['total'];
    }
    while ($row = mysqli_fetch_assoc($queryTarjeta)) {
        $totalSumadoTarjeta += $row['total'];
    }

    // $totalSumadoEfectivo / 2;
    // $totalSumadoNequi / 2;
    // $totalSumadoDaviplata / 2;
    // $totalSumadoTarjeta / 2;
    // while ($row = mysqli_fetch_assoc($query)) {
    //     $metodo_pago = $row['metodo_pago'];
    //     if ($metodo_pago == 'daviplata') {
    //         $totalSumadoDaviplata += $row['total'];
    //     } else if ($metodo_pago == 'tarjeta') {
    //         $totalSumadoTarjeta += $row['total'];
    //     }

    //     $totalSumado = $totalSumadoEfectivo + $totalSumadoNequi + $totalSumadoDaviplata + $totalSumadoTarjeta;
    // }
}
?>


<div class="modal fade" id="cierre_caja_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cierre de caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="efect">Efectivo</label>
                            <input class="form-control" type="text" value="$ <?php echo number_format($totalSumadoEfectivo); ?>" required disabled>
                            <input id="efect" class="form-control" type="hidden" name="efect" value="<?php echo $totalSumadoEfectivo; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nequi">Nequi</label>
                            <input class="form-control" type="text" value="$ <?php echo number_format($totalSumadoNequi); ?>" required disabled>
                            <input id="nequi" class="form-control" type="hidden" name="nequi" value="<?php echo $totalSumadoNequi; ?>">
                        </div>
                        <div class="form-group">
                            <label for="daviplata">Daviplata</label>
                            <input class="form-control" type="text" value="$ <?php echo number_format($totalSumadoDaviplata); ?>" required disabled>
                            <input id="daviplata" class="form-control" type="hidden" name="daviplata" value="<?php echo $totalSumadoDaviplata; ?>">
                        </div>
                        <div class="form-group">
                            <label for="tarjeta">Tarjeta</label>
                            <input class="form-control" type="text" value="$ <?php echo number_format($totalSumadoTarjeta); ?>" required disabled>
                            <input id="tarjeta" class="form-control" type="hidden" name="tarjeta" value="<?php echo $totalSumadoTarjeta; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total">Total sumado</label>
                            <input class="form-control" type="text" value="$<?php echo number_format($totalSumado); ?>" required disabled>
                            <input id="total" class="form-control" type="hidden" name="total" value="<?php echo $totalSumado; ?>">
                        </div>
                        <div class="form-group">
                            <label for="devol">Devoluciones</label>
                            <input id="devol" class="form-control" type="text" name="devol" value="0" required disabled>
                        </div>
                        <div class="form-group">
                            <label for="efectivoFisico">¿Cuanto tienes? <span class="text-danger">(*)</span></label>
                            <input id="efectivoFisico" class="form-control separadorMiles" type="text" name="efectivoFisico" placeholder="ingrese valor" onkeyup="calcularPrecioCierreCaja(event)" required>
                        </div>
                        <div class="form-group">
                            <label for="cuanto_pagaste">¿Cuanto gastaste? <span class="text-danger">(*)</span></label>
                            <input id="cuanto_pagaste" class="form-control separadorMiles" type="text" name="cuanto_pagaste" placeholder="ingrese valor" required>
                        </div>
                        <div class="form-group">
                            <label for="resultSobrante">Sobrante / faltante</label>
                            <input id="resultSobrante" class="form-control" type="text" name="resultSobrante" placeholder="ingrese valor" required disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="observacion">Observaciones</label>
                    <textarea class="form-control" id="observacion" name="observacion" placeholder="ingrese observación" rows="5"></textarea>
                    <!-- <small>Maximo 436 caracteres</small> -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-block" type="button" onclick="registrarCierreCaja()">Guardar</button>
            </div>
        </div>
    </div>