<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos_existentes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

$hoy = date('Y-m-d');

$query = mysqli_query($conexion, "SELECT * FROM producto p INNER JOIN laboratorios l ON p.id_lab = l.id WHERE `delete` = 0");


include_once "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        Historial Productos Existentes
    </div>
    <div class="card-body">
        <a id="download_xlsx_productos_utilidad" class="btn btn-success text-white">Exportar a excel</a>
        <div class="table-responsive">
            <table class="table table-hover" id="tbl_productos_existentes">
                <thead class="thead-dark">
                    <tr>
                        <th>Cod Barras</th>
                        <th>Producto</th>
                        <th>Laboratorio</th>
                        <th>existencia</th>
                        <th>$ compra global</th>
                        <th>$ venta global</th>
                        <th>Ganancia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalSumadoCompra = 0;
                    $totalSumado = 0;
                    while ($row = mysqli_fetch_assoc($query)) {
                        $id_prod = $row['codproducto'];
                        $stock = mysqli_query($conexion, "SELECT SUM(existencia) AS existencia FROM lotes WHERE id_producto = $id_prod");
                        $resultadoStock = mysqli_fetch_assoc($stock);
                        $existencia = 0;
                        if (!empty($resultadoStock['existencia'])) {
                            $existencia = $resultadoStock['existencia'];
                        }
                        if ($existencia != 0) {
                            if ($row['precio_menudeo'] > 0 && $row['precio_blister'] == 0) {
                                // MENUDEO
                                $resultado = $row['precio_menudeo'] * $existencia;
                                // 
                                $toC = $row['precio_compra'] / $row['cant_global'] * $existencia;
                                $toV = $resultado / $row['cant_global'] * $existencia;

                                $sub_array[] = '$' . number_format($toC);
                                $sub_array[] = '$' . number_format($toV);
                            } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] > 0) {
                                // BLISTER
                                $toC = $row['precio_compra'] / $row['cant_global'] * $existencia;
                                $toV = $existencia / $row['cant_blister'] * $row['precio_blister'];
                            } else if ($row['precio_menudeo'] > 0 && $row['precio_blister'] > 0) {
                                // TODO
                                $toC = $row['precio_compra'] / $row['cant_global'] * $existencia;
                                $toV = $row['precio_menudeo'] * $existencia;
                            } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] == 0) {
                                // SOLO TOTAL
                                $toC = $row['precio_compra'] * $existencia;
                                $toV = $row['precio_global'] * $existencia;
                            }
                            $totalSumadoCompra += $toC;
                            $totalSumado += $toV;
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>total</td>
                        <td>$<?php echo number_format($totalSumadoCompra); ?></td>
                        <td>$<?php echo number_format($totalSumado); ?></td>
                        <td>$<?php echo number_format($totalSumado - $totalSumadoCompra); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
<script>
    $(document).ready(function() {
        $('#tbl_productos_existentes').DataTable({
            "pageLength": 5,
            "lengthMenu": [
                [1000, 500, 100, 50, 25],
                [1000, 500, 100, 50, 25]
            ],
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
                'url': 'datatable_productos_existentes.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>