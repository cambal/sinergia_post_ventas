<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "compras";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>
<style>
    #contenedor video {
        max-width: 100%;
        width: 100%;
    }

    #contenedor {
        max-width: 100%;
        position: relative;
    }

    canvas {
        max-width: 100%;
    }

    canvas.drawingBuffer {
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <h4 class="text-center">Datos producto</h4>
        </div>
        <div class="card">
            <!-- <input type="text" name="codigo" id="codigo"> -->
            <div class="card-body">
                <form action="" id="form_producto_lote">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="entrada_admin">Buscar por Cod Barras o Nombre</label>
                                <input id="entrada_admin" class="form-control" type="text" name="entrada_admin" placeholder="Ingresa el código o nombre">
                                <input id="id_compra" type="hidden" name="id_compra">
                            </div>
                            <div class="form-group">
                                <label for="codigo">Cod Barras</label>
                                <input id="codigo_compra" class="form-control form_control_disabled" placeholder="Ingrese cod barras" type="text" name="codigo_compra" disabled required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_compra">Producto</label>
                                <input id="descripcion_compra" class="form-control form_control_disabled" placeholder="Ingrese nombre" type="text" name="descripcion_compra" disabled required>
                            </div>
                            <div class="form-group">
                                <label for="invima_compra">Invima</label>
                                <input id="invima_compra" placeholder="Ingrese invima" class="form-control form_control_disabled" type="text" name="invima_compra" disabled>
                            </div>
                            <div class="form-group">
                                <label for="laboratorio_compra">Laboratorío</label>
                                <input id="laboratorio_compra" class="form-control" type="text" name="laboratorio_compra" placeholder="Ingrese laboratorío" disabled>
                                <select id="laboratorio_compra_select" class="form-control" name="laboratorio_compra_select" required>
                                    <option value="" disabled selected hidden>Seleccione Laboratorío</option>
                                    <?php
                                    $query_lab = mysqli_query($conexion, "SELECT * FROM laboratorios ORDER BY laboratorio ASC");
                                    while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                                        <option class="p-3" value="<?php echo $datos['id'] ?>"><?php echo $datos['laboratorio'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="existencia_minima_compra">stock minimo</label>
                                <input id="existencia_minima_compra" placeholder="Ingrese invima" class="form-control form_control_disabled" type="text" name="existencia_minima_compra" disabled required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="precio_compra_compra">precio compra global <span class="text-danger">(*)</span></label>
                                    <input id="precio_compra_compra" class="form-control separadorMiles" type="text" name="precio_compra_compra" placeholder="Ingrese precio compra" required>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cant_menudeo" class=" text-dark font-weight-bold">Cant Menudeo</label>
                                            <input type="number" placeholder="Ingrese cant unidad" class="form-control" name="cant_menudeo" id="cant_menudeo" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cant_blister" class=" text-dark font-weight-bold">Cant Blister</label>
                                            <input type="number" placeholder="Ingrese cant blister" class="form-control" name="cant_blister" id="cant_blister" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cant_global" class=" text-dark font-weight-bold">Cant global</label>
                                            <input type="number" placeholder="Ingrese cant global" class="form-control" name="cant_global" id="cant_global" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="precio_menudeo">$ venta menudeo</label>
                                            <input id="precio_menudeo" class="form-control separadorMiles" type="text" name="precio_menudeo" placeholder="Ingrese precio menudeo" required disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="precio_blister">$ venta blister</label>
                                            <input id="precio_blister" class="form-control separadorMiles" type="text" name="precio_blister" placeholder="Ingrese precio blister" required disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="precio_venta_compra"> $ venta global</label>
                                            <input id="precio_venta_compra" class="form-control separadorMiles" type="text" name="precio_venta_compra" placeholder="Ingrese precio global" required disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center">
                                    <div class="form-group form_hide mb-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="menudeo_menudeo" checked="">
                                            <label class="form-check-label d-flex align-items-center" for="menudeo_menudeo" style="height: 1.8rem;">
                                                <span class="pb-1">
                                                    Menudeo
                                                    <strong class="form-group presentacion_compra2" style="margin-left: 3px;">
                                                    </strong>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form_hide">
                                        <label for="cantidad_compra">Cantidad <span class="text-danger">(*)</span></label>
                                        <input id="cantidad_compra" class="form-control" type="number" name="cantidad_compra" onkeyup="calcularPrecioCompra(event)" placeholder="Ingrese cantidad" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form_hide">
                                        <label for="total_compra">.</label>
                                        <input id="total_compra" class="form-control" type="text" name="total_compra" required disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form_hide">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="lote_compra">Lote <span class="text-danger">(*)</span></label>
                                            <input id="lote_compra" class="form-control inputCompra" type="text" name="lote_compra" placeholder="Ingrese lote" required>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <label for="vencimiento_compra">Vencimiento <span class="text-danger">(*)</span></label>
                                        <input id="vencimiento_compra" class="form-control" type="date" name="vencimiento_compra" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="btn_guardar" onclick="entradaAdmin(event)">Agregar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive p-0">
                <!-- table -->
                <table class="table table-hover align-items-center mb-0" id="tbl_entrada">
                    <thead>
                        <tr>
                            <th>Cod Barras</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <!-- <th>Cantidad unit</th> -->
                            <th>Usuario</th>
                            <th>Fecha</th>
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
        $('#tbl_entrada').DataTable({
            "pageLength": 5,
            "lengthMenu": [
                [500, 100, 50, 25, 10, 5],
                [500, 100, 50, 25, 10, 5]
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
                'url': 'datatable_entrada_admin.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>