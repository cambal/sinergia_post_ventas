<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_venta";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <h4 class="text-center">Seleccionar Producto</h4>
        </div>
        <div class="card">
            <form class="card-body" id="form_ventas">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="producto">Cod Bar o Nombre</label>
                            <input id="producto" class="form-control" type="text" name="producto" placeholder="Ingresa el código o nombre" required>
                            <input id="id" type="hidden" name="id">
                        </div>
                        <div class="form-group">
                            <label for="lote_venta">Lote</label>
                            <select id="lote_venta" class="form-control" name="lote_venta" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="existencia_venta" class="d-flex">
                                Stock actual
                            </label>
                            <input id="existencia_venta" class="form-control" type="text" name="existencia_venta" placeholder="" disabled>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="cant_menudeo_venta">Cant menudeo</label>
                                    <input id="cant_menudeo_venta" class="form-control" type="text" name="cant_menudeo_venta" placeholder="" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="cant_blister_venta">Cant blister</label>
                                    <input id="cant_blister_venta" class="form-control" type="text" name="cant_blister_venta" placeholder="" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="cant_global_venta">Cant global</label>
                                    <input id="cant_global_venta" class="form-control" type="text" name="cant_global_venta" placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="precio_menudeo">Precio menudeo</label>
                                    <input id="precio_menudeo" class="form-control" type="text" name="precio_menudeo" placeholder="" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="precio_blister">Precio blister</label>
                                    <input id="precio_blister" class="form-control" type="text" name="precio_blister" placeholder="" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="precio_venta">Precio global</label>
                                    <input id="precio_venta" class="form-control" type="text" name="precio_venta" placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="radio" id="menudeo_venta" checked="" name="selected_venta">
                                        <label class="form-check-label d-flex align-items-center" for="menudeo_venta" style="height: 1.8rem;">
                                            <span class="pb-1">
                                                Menudeo
                                                <strong class="form-group presentacion_compra2" style="margin-left: 3px;">
                                                </strong>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="radio" id="blister_venta" name="selected_venta">
                                        <label class="form-check-label d-flex align-items-center" for="blister_venta" style="height: 1.8rem;">
                                            <span class="pb-1">
                                                Blister
                                                <strong class="form-group presentacion_compra2" style="margin-left: 3px;">
                                                </strong>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="radio" id="global_venta" name="selected_venta">
                                        <label class="form-check-label d-flex align-items-center" for="global_venta" style="height: 1.8rem;">
                                            <span class="pb-1">
                                                Global
                                                <strong class="form-group presentacion_compra2" style="margin-left: 3px;">
                                                </strong>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="cantidad" class="d-flex">Cantidad venta
                                    </label>
                                    <input id="cantidad" class="form-control" type="text" name="cantidad" placeholder="Ingrese cantidad" onkeyup="calcularPrecio(event)" autocomplete="off" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sub_total">Sub Total</label>
                                    <input id="sub_total" class="form-control" type="text" name="sub_total" placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="button" onclick="salidaAdmin()">Confirmar salida</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="row justify-content-end">
            <div class="col-md-12 d-flex  justify-content-end">
                <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Generar Venta</a>
            </div>
        </div>
        <div class="table-responsive p-0">
            <!-- table -->
            <table class="table table-hover align-items-center mb-0" id="tbl">
                <thead>
                    <tr>
                        <th>Cod Barras</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Cantidad unit</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../conexion.php";
                    $query = mysqli_query($conexion, "SELECT * FROM salida_admin s INNER JOIN producto p ON s.id_producto = p.codproducto INNER JOIN usuario u ON s.id_usuario = u.idusuario");
                    $result = mysqli_num_rows($query);
                    while ($data = mysqli_fetch_assoc($query)) {
                        // $divCompra = $data['precio_compra'];
                        // $divGanancia = $data['precio_global'] - $divCompra;
                    ?>
                        <tr>
                            <td><?php echo $data['codigo']; ?></td>
                            <td><?php echo substr($data['descripcion'], 0, 30); ?></td>
                            <td><?php echo substr($data['cantidad'], 0, 23); ?></td>
                            <td><?php echo substr($data['cant_unit'], 0, 23); ?></td>
                            <td><?php echo substr($data['nombre'], 0, 23); ?></td>
                            <td><?php echo substr($data['fecha'], 0, 23); ?></td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>