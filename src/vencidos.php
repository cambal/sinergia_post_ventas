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
                <table class="table table-danger table-hover" id="tbl">
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
                        <?php
                        $query = mysqli_query($conexion, "SELECT * FROM vencidos v INNER JOIN producto p ON v.id_producto = p.codproducto ORDER BY fecha ASC");
                        // AND p.vencimiento < '$hoy+90'
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {

                        ?>
                                <tr>
                                    <td><?php echo $data['codigo']; ?></td>
                                    <td><?php echo $data['descripcion']; ?></td>
                                    <td><?php echo $data['lote']; ?></td>
                                    <td><?php echo $data['cantidad']; ?></td>
                                    <td><?php echo $data['vencimiento']; ?></td>
                                    <td><?php echo $data['fecha']; ?></td>
                                </tr>
                        <?php

                            }
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>