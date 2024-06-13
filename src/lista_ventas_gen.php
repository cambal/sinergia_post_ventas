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
$query = mysqli_query($conexion, "SELECT * FROM detalle_venta d INNER JOIN producto p ON d.id_producto = codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND d.estado = 0");
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header">
        Historial ventas
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tbl_historial_ventas">
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
                        <!-- <th>Devolución</th> -->
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
                        }
                    }
                    ?>
                </tbody>
                <tfoot class="sticky-top">
                    <tr>
                        <td>total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>$ <?php echo number_format($totalSumado); ?></td>
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

<script>
    $(document).ready(function() {
        $('#tbl_historial_ventas').DataTable({
            "pageLength": 5,
            "processing": true,
            "serverSide": true,
            "paging": true,
            "order": [],

            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
            },
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'ajax': {
                'url': 'datatable_historial_ventas.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>