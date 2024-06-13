<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

$hoy = date('Y-m-d');

$query = mysqli_query($conexion, "SELECT * FROM cierre_caja");

include_once "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        Historial Cierres de caja
    </div>
    <div class="card-body">
        <a id="download_xlsx_cierre_caja" class="btn btn-success text-white">Exportar a excel</a>
        <a data-toggle="modal" data-target="#cierre_caja_modal" class="btn btn-info text-white">Cerrar caja</a>
        <div class="table-responsive">
            <table class="table table-hover" id="tbl_cierre_caja">
                <thead class="thead-dark">
                    <tr>
                        <th>ID cierre</th>
                        <th>Vendedor</th>
                        <th>efectivo</th>
                        <th>nequi</th>
                        <th>daviplata</th>
                        <th>tarjeta</th>
                        <th>tengo</th>
                        <th>sobrante</th>
                        <th>Gastos</th>
                        <th>Total</th>
                        <th>fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

<script>
    $(document).ready(function() {
        $('#tbl_cierre_caja').DataTable({
            "pageLength": 5,
            "lengthMenu": [[1000, 500, 100, 50, 25], [1000, 500, 100, 50, 25]],
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
                'url': 'datatable_cierre_caja.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>