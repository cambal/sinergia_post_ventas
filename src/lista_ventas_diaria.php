<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "venta_diaria";
$hoy = date('Y-m-d');
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
$query = mysqli_query($conexion, "SELECT v.id, v.metodo_pago, v.id_usuario, v.id_cliente, v.fecha, d.id_detalle_venta, d.id_producto, d.id_venta, d.cantidad, d.cant_unidad, d.descuento, d.precio, d.total, d.estado, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy' AND d.estado = 0 AND d.cierre_caja = 0");
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header">
        Historial ventas
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th># factura</th>
                        <th># Producto</th>
                        <th>Cantidad</th>
                        <th>valor unit</th>
                        <th>Total</th>
                        <th>Metodo pago</th>
                        <th>Fecha venta</th>
                        <th>Factura</th>
                        <th>Fac Electrónica</th>
                        <th>Devolución</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($query) {
                        $totalSumadoCompra = 0;
                        $totalSumado = 0;
                        while ($row = mysqli_fetch_assoc($query)) {
                            $totalSumado += $row['total'];
                    ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo substr($row['descripcion'], 0, 22); ?></td>
                                <td><?php echo number_format($row['cantidad']); ?></td>
                                <td>$<?php echo number_format($row['precio']); ?></td>
                                <td>$<?php echo number_format($row['precio'] * $row['cantidad']); ?></td>
                                <td><?php echo $row['metodo_pago']; ?></td>
                                <td><?php echo $row['fecha']; ?></td>
                                <td>
                                    <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                </td>
                                <td>
                                    <a href="pdf/factura_electronica.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                </td>
                                <td>
                                    <button onclick="devolucion_venta(<?php echo $row['id_detalle_venta']; ?>, <?php echo $row['cant_unidad']; ?>)" class="btn btn-info">Devolución</button>
                                </td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
                <tfoot class="sticky-top">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>total</td>
                        <td>$ <?php echo number_format($totalSumado); ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>