<?php
session_start();
require "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "proximos_vencer";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>


<!-- Content Row -->
<div class="row">
    <div class="card">
        <div class="card-header">
            Proximos a vencer
        </div>
        <!-- <form class="form-group" action="datatable_proximos_vencer.php" method="post">
            <label for="rango_seleccionado">Rango de fecha</label>
            <select id="rango_seleccionado" class="form-control" name="rango_seleccionado">
                <option value="+30 day">1 mes</option>
                <option value="+60 day">2 meses</option>
                <option value="+90 day">3 meses</option>
                <option value="+180 day">6 meses</option>
            </select>
        </form> -->
        <div class="card-body">
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table table-danger table-hover" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cod Barras</th>
                                <th>Lote</th>
                                <th>Producto</th>
                                <th>Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";
                            $hoy = date('Y-m-d');
                            $query = mysqli_query($conexion, "SELECT * FROM lotes l INNER JOIN producto p ON l.id_producto = p.codproducto ORDER BY p.descripcion ASC");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $dividir = $data['vencimiento'];
                                    $dateMasDay = strtotime('+90 day', strtotime($hoy));
                                    $dateMasDay = date('Y-m-d', $dateMasDay);
                                    if ($dividir <= $dateMasDay) {

                            ?>
                                        <tr>
                                            <td><?php echo $data['codigo']; ?></td>
                                            <td><?php echo $data['lote']; ?></td>
                                            <td><?php echo $data['descripcion']; ?></td>
                                            <td><?php echo $dividir; ?></td>
                                        </tr>
                            <?php
                                    }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "includes/footer.php"; ?>

    <!-- <script>
        $(document).ready(function() {
            $('#tbl_proximos_vencer').DataTable({
                "pageLength": 5,
                "lengthMenu": [
                    [1000, 500, 100, 50, 1],
                    [1000, 500, 100, 50, 1]
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
                    'url': 'datatable_proximos_vencer.php',
                    'type': 'post',
                },
                "columnDefs": [{
                    'target': [5],
                    'orderable': true,
                }]
            });
        });
    </script> -->