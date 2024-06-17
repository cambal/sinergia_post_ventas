<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "sugerido";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

$hoy = date('Y-m-d');

// $query = mysqli_query($conexion, "SELECT * FROM producto WHERE existencia < existencia_minima - 1");


include_once "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        Sugerido
    </div>
    <div class="card-body">
        <a id="download_xlsx_sugerido" class="btn btn-success text-white">Exportar a excel</a>
        <div class="table-responsive">
            <table class="table table-hover tbl_sugerido" id="tbl_sugerido">
                <thead class="thead-dark">
                    <tr>
                        <th>Cod Barras</th>
                        <th>Producto</th>
                        <th>Laboratorío</th>
                        <th>stock</th>
                        <th>stock minimo</th>
                        <th>Faltante</th>
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
        $('#tbl_sugerido').DataTable({
            "pageLength": 5,
            "lengthMenu": [[1000, 500, 100, 50, 25, 10, 5], [1000, 500, 100, 50, 25, 10, 5]],
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
                'url': 'datatable_sugerido.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>