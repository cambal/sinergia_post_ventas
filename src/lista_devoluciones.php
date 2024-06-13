<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas_generales";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
$query = mysqli_query($conexion, "SELECT * FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND d.estado = 1 GROUP BY v.id");
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header">
        Historial devoluciones
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tbl">
                <thead class="thead-dark">
                <tr>
                        <th># factura</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($query) {
                        $totalSumadoCompra = 0;
                        $totalSumado = 0;
                        while ($row = mysqli_fetch_assoc($query)) {
                            $fecha = new DateTime($row['fecha']);
                            $fechaFormat = $fecha->format('Y-m-d');
                            $totalSumado += $row['total'];
                    ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>$<?php echo number_format($row['total']); ?></td>
                                <td><?php echo $row['fecha']; ?></td>
                                <td>
                                    <a href="pdf/devoluciones.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                </td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
                <tfoot class="sticky-top">
                    <tr>
                        <td>total</td>
                        <td>$ <?php echo number_format($totalSumado); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>