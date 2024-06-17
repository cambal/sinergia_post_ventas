<?php
require "../conexion.php";
session_start();

include_once "includes/header.php";
?>

<!-- Content Row -->
<div class="row">
    <div class="card">
        <div class="card-header">
            Historial Vencidos
        </div>
        <div class="card-body">
            <a id="download_xlsx" class="btn btn-success text-white">Exportar a excel</a>
            <div class="table-responsive">
                <table class="table table-danger table-hover" id="tbl_vencidos">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod Barras</th>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Vencimiento</th>
                            <th>Eliminación</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>

<script>
    $(document).ready(function() {
        $('#tbl_vencidos').DataTable({
            "pageLength": 5,
            "lengthMenu": [
                [1000, 500, 100, 50, 25, 10, 5],
                [1000, 500, 100, 50, 25, 10, 5]
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
                'url': 'datatable_vencidos.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>