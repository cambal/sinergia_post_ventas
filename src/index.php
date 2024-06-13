<?php
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$productos_stock = mysqli_query($conexion, "SELECT * FROM lotes l INNER JOIN producto p ON l.id_producto = p.codproducto WHERE l.existencia > 0 GROUP BY p.codigo, p.descripcion");
$total['productos_stock'] = mysqli_num_rows($productos_stock);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas WHERE fecha > CURDATE()");
$total['ventas'] = mysqli_num_rows($ventas);
$ventas_diaria = mysqli_query($conexion, "SELECT * FROM ventas");
$total['ventas_diaria'] = mysqli_num_rows($ventas_diaria);
session_start();
include_once "includes/header.php";
?>

<!-- Content Row -->
<div class="row justify-content-center">
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <!-- <div class="card-icon">
                    <i class="fas fa-user fa-2x"></i>
                </div> -->
                <a href="usuarios.php" class="card-category text-warning font-weight-bold">
                    Usuarios
                </a>
                <h3 class="card-title"><?php echo $total['usuarios']; ?></h3>
            </div>
            <div class="card-footer bg-warning text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <!-- <div class="card-icon">
                    <i class="fas fa-users fa-2x"></i>
                </div> -->
                <a href="clientes.php" class="card-category text-success font-weight-bold">
                    Clientes
                </a>
                <h3 class="card-title"><?php echo $total['clientes']; ?></h3>
            </div>
            <div class="card-footer bg-secondary text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <!-- <div class="card-icon">
                    <i class="fab fa-product-hunt fa-2x"></i>
                </div> -->
                <a href="productos.php" class="card-category text-danger font-weight-bold">
                    Productos
                </a>
                <h3 class="card-title"><?php echo $total['productos']; ?></h3>
            </div>
            <div class="card-footer bg-primary">
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <!-- <div class="card-icon">
                    <i class="fab fa-product-hunt fa-2x"></i>
                </div> -->
                <a href="productos_utilidad.php" class="card-category text-danger font-weight-bold">
                    Con stock
                </a>
                <h3 class="card-title"><?php echo $total['productos_stock']; ?></h3>
            </div>
            <div class="card-footer bg-primary">
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <!-- <div class="card-icon">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div> -->
                <a href="lista_ventas_diaria.php" class="card-category text-info font-weight-bold">
                    Ventas Hoy
                </a>
                <h3 class="card-title"><?php echo $total['ventas']; ?></h3>
            </div>
            <div class="card-footer bg-danger text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <!-- <div class="card-icon">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div> -->
                <a href="ventas.php" class="card-category text-info font-weight-bold">
                    Ventas
                </a>
                <h3 class="card-title"><?php echo $total['ventas_diaria']; ?></h3>
            </div>
            <div class="card-footer bg-danger text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos con stock mínimo</h3>
            </div>
            <div class="card-body">
                <canvas id="stockMinimo"></canvas>
            </div>
        </div> -->
    </div>
    <div class="col-lg-6">
        <!-- <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos más vendidos</h3>
            </div>
            <div class="card-body">
                <canvas id="ProductosVendidos"></canvas>
            </div>
        </div> -->
    </div>
</div>

<?php include_once "includes/footer.php"; ?>