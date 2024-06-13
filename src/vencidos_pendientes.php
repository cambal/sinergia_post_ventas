<?php
require "../conexion.php";


$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas WHERE fecha > CURDATE()");
$total['ventas'] = mysqli_num_rows($ventas);
session_start();
include_once "includes/header.php";
?>


<!-- Content Row -->
<div class="row">
    <div class="card">
        <div class="card-header">
            Vencidos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-danger table-hover" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod Barras</th>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Vencimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";
                        $hoy = date('Y-m-d');
                        $query = mysqli_query($conexion, "SELECT * FROM lotes l INNER JOIN producto p ON l.id_producto = p.codproducto");
                        // $result = mysqli_num_rows($query);
                        while ($data = mysqli_fetch_assoc($query)) {
                            $dividir = $data['vencimiento'];
                            $lote = $data['lote'];
                            if ($dividir <= $hoy) {
                        ?>
                            <tr>
                                <td><?php echo $data['codigo']; ?></td>
                                <td><?php echo $data['descripcion']; ?></td>
                                <td><?php echo $data['lote']; ?></td>
                                <td><?php echo $dividir; ?></td>
                                <td>
                                    <form action="eliminar_fecha_vencida.php?id=<?php echo $data['codproducto']; ?>" method="post" class="d-inline">
                                        <button class="btn btn-danger" type="submit">
                                            <i class='fas fa-trash-alt'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>