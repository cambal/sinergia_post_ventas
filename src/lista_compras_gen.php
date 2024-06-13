<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "historial_compras";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
$query = mysqli_query($conexion, "SELECT * FROM compras c INNER JOIN usuario u ON c.id_usuario = u.idusuario ORDER BY c.fecha DESC");
include_once "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        Historial compras
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th># Factura</th>
                        <th>Proveedor</th>
                        <th>Usuario</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Factura</th>
                        <th>Recepcion tecnica</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalSumado = 0;
                    while ($row = mysqli_fetch_assoc($query)) {
                        $totalSumado += $row['total'];
                    ?>
                        <tr>
                            <td><?php echo $row['num_fac_compra']; ?></td>
                            <td><?php echo $row['proveedor']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>$<?php echo number_format($row['total']); ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td>
                                <a href="pdf/generar_compra.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                            </td>
                            <td>
                                <a href="pdf/generar_recepcion_tecnica.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-info"><i class="fas fa-file-pdf"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>total</td>
                        <td>$<?php echo number_format($totalSumado); ?></td>
                        
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