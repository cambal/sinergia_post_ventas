<?php
# Este es el simple encabezado HTML
# Incluimos la conexión

// $contraseña = "";
// $usuario = "root";
// $nombre_base_de_datos = "v_farmacia";
// try {
//     $base_de_datos = new PDO('mysql:host=localhost;dbname=' . $nombre_base_de_datos, $usuario, $contraseña);
//     $base_de_datos->query("set names utf8;");
//     $base_de_datos->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
//     $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $base_de_datos->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
// } catch (Exception $e) {
//     echo "Ocurrió algo con la base de datos: " . $e->getMessage();
// }

include_once "../conexion.php";

# Cuántos productos mostrar por página
$productosPorPagina = 10;
// Por defecto es la página 1; pero si está presente en la URL, tomamos esa
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}
# El límite es el número de productos por página
$limit = $productosPorPagina;
# El offset es saltar X productos que viene dado por multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * $productosPorPagina;
# Necesitamos el conteo para saber cuántas páginas vamos a mostrar
$sentencia = $conexion->query("SELECT count(*) AS conteo FROM producto");
$conteo = $sentencia->fetch_object()->conteo;
# Para obtener las páginas dividimos el conteo entre los productos por página, y redondeamos hacia arriba
$paginas = ceil($conteo / $productosPorPagina);

# Ahora obtenemos los productos usando ya el OFFSET y el LIMIT
$sentencia = mysqli_query($conexion, "SELECT * FROM producto LIMIT $limit OFFSET $offset");
// $sentencia->execute([$limit, $offset]);
$productos = mysqli_fetch_assoc($sentencia);
# Y más abajo los dibujamos...
?>

<div class="col-xs-12">
    <h1>Productos</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <!-- <th>Código</th>
                <th>Descripción</th>
                <th>Precio de compra</th>
                <th>Precio de venta</th>
                <th>Existencia</th> -->
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($data = mysqli_fetch_assoc($sentencia)) { ?>
                <tr>
                    <td><?php echo $data['descripcion'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <nav>
        <div class="row">
            <div class="col-xs-12 col-sm-6">

                <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> productos disponibles</p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p>Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
            </div>
        </div>
        <ul class="pagination">
            <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
            <?php if ($pagina > 1) { ?>
                <li>
                    <a href="./listar.php?pagina=<?php echo $pagina - 1 ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
            <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                <li class="<?php if ($x == $pagina) echo "active" ?>">
                    <a href="./listar.php?pagina=<?php echo $x ?>">
                        <?php echo $x ?></a>
                </li>
            <?php } ?>
            <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
            <?php if ($pagina < $paginas) { ?>
                <li>
                    <a href="./listar.php?pagina=<?php echo $pagina + 1 ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>
<?php include_once "pie.php" ?>