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
            <h4 class="text-center">Datos factura</h4>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="proveedor_compra">Proveedor</label>
                                <select id="proveedor_compra" class="form-control" name="proveedor_compra" required>
                                    <?php
                                    $query_tipo = mysqli_query($conexion, "SELECT * FROM proveedores ORDER BY proveedor ASC");
                                    while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                                        <option value="<?php echo $datos['proveedor'] ?>"><?php echo $datos['proveedor'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="num_fac_compra">Número factura</label>
                                <input type="text" name="num_fac_compra" id="num_fac_compra" class="form-control" placeholder="Ingrese número factura" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_fac_compra">Total factura</label>
                                <input type="text" name="total_fac_compra" id="total_fac_compra" class="form-control separadorMiles" placeholder="Ingrese total factura" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-12 d-flex justify-content-end">
                            <a href="#" class="btn btn-primary" id="btn_generar_compra"><i class="fas fa-save"></i> Generar Compra</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="form-group">
            <h4 class="text-center">Datos producto</h4>
        </div>
        <div class="card">
            <!-- <input type="text" name="codigo" id="codigo"> -->
            <div class="card-body">
                <form action="" id="form_compras">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="producto_compra">Buscar por Cod Barras o Nombre</label>
                                <input id="producto_compra" class="form-control" type="text" name="producto_compra" placeholder="Ingresa el código o nombre">
                                <input id="id_compra" type="hidden" name="id_compra">
                            </div>
                            <div class="form-group">
                                <label for="codigo">Cod Barras <span class="text-danger">(*)</span></label>
                                <input id="codigo_compra" class="form-control form_control_disabled" placeholder="Ingrese cod barras" type="text" name="codigo_compra" disabled required>
                            </div>
                            <div class="form-group form_hide_2">
                                <label for="codigo_hijo_compra">Cod Barras hijo</label>
                                <input id="codigo_hijo_compra" class="form-control form_control_disabled" placeholder="Ingrese cod barras hijo" type="text" name="codigo_hijo_compra" disabled required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_compra">Producto <span class="text-danger">(*)</span></label>
                                <input id="descripcion_compra" class="form-control form_control_disabled" placeholder="Ingrese nombre" type="text" name="descripcion_compra" disabled required>
                            </div>
                            <div class="form-group">
                                <label for="invima_compra">Invima</label>
                                <input id="invima_compra" placeholder="Ingrese invima" class="form-control form_control_disabled" type="text" name="invima_compra" disabled>
                            </div>
                            <div class="form-group form_hide_2">
                                <label for="tipo_compra">Tipo <span class="text-danger">(*)</span></label>
                                <input id="tipo_compra" placeholder="Ingrese tipo" class="form-control tipo_compra" type="text" name="tipo_compra" disabled>
                                <select id="tipo_compra_select" class="form-control tipo_compra" name="tipo_compra_select" required>
                                    <option value="" disabled selected hidden>Seleccione tipo</option>
                                    <?php
                                    $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos ORDER BY tipo ASC");
                                    while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                                        <option value="<?php echo $datos['id'] ?>"><?php echo $datos['tipo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="laboratorio_compra">Laboratorío <span class="text-danger">(*)</span></label>
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
                                <label for="existencia_minima_compra">stock minimo <span class="text-danger">(*)</span></label>
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
                            <!--  -->
                            <div class="form-group form_hide_2 mb-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="menudeo_compra">
                                            <label class="form-check-label d-flex align-items-center" for="menudeo_compra" style="height: 1.8rem;">
                                                <span class="pb-1">
                                                    Menudeo
                                                    <strong class="form-group presentacion_compra2" style="margin-left: 3px;">
                                                    </strong>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="blister_compra">
                                            <label class="form-check-label d-flex align-items-center" for="blister_compra" style="height: 1.8rem;">
                                                <span class="pb-1">
                                                    Blister
                                                    <strong class="form-group presentacion_compra2" style="margin-left: 3px;">
                                                    </strong>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
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
                                            <label for="cant_global" class=" text-dark font-weight-bold">Cant global <span class="text-danger">(*)</span></label>
                                            <input type="number" placeholder="Ingrese cant global" class="form-control" name="cant_global" id="cant_global" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- <div class="form-group">
                                            <label for="precio_venta_compra">%</label>
                                            <input type="number" class="form-control" onKeyPress="onKeyPressBlockChars(event,this.value);" onKeyUp="calculaPorcentajesMenudeo()" id="porcentaje_menudeo" placeholder="Ingrese % menudeo" disabled>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="precio_menudeo">$ venta menudeo</label>
                                            <input id="precio_menudeo" class="form-control separadorMiles" type="text" name="precio_menudeo" placeholder="Ingrese precio menudeo" required disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- <div class="form-group">
                                            <label>%</label>
                                            <input type="number" class="form-control" onKeyPress="onKeyPressBlockChars(event,this.value);" onKeyUp="calculaPorcentajesBlister()" id="porcentaje_blister" placeholder="Ingrese % blister" disabled>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="precio_blister">$ venta blister</label>
                                            <input id="precio_blister" class="form-control separadorMiles" type="text" name="precio_blister" placeholder="Ingrese precio blister" required disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- <div class="form-group">
                                            <label for="precio_venta_compra">%</label>
                                            <input type="number" class="form-control" onKeyPress="onKeyPressBlockChars(event,this.value);" onKeyUp="calculaPorcentajes()" id="porcentaje" placeholder="Ingrese % global" disabled>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="precio_venta_compra"> $ venta global <span class="text-danger">(*)</span></label>
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
                                            <label for="lote_compra">Lote</label>
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
                                <button class="btn btn-primary" type="button" id="btn_agregar" onclick="guardarProducto()">Guardar</button>
                                <button class="btn btn-primary" type="button" id="btn_guardar" onclick="agregarCompraLista(event)">Agregar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tblDetalleCompra">
            <thead class="thead-dark">
                <tr>
                    <th>Cod Barras</th>
                    <th>Producto</th>
                    <th>Lote</th>
                    <th>Venci</th>
                    <th>$ Menudeo</th>
                    <th>$ Blister</th>
                    <th>$ Compra Global</th>
                    <th>$ Venta Global</th>
                    <th>Cant</th>
                    <th>$ Total</th>
                    <th>$ Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td><input type="text" id="total_pagar" style="color: #fff; font-weight: bold; background: #212529; border:none;" disabled></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
<?php include_once "includes/footer.php"; ?>

<script>
    $(document).ready(function() {
        let table = $('#tblDetalleCompra').DataTable({
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
                var total = 0;
                $('#tblDetalleCompra').DataTable().rows().data().each(function(el, index) {
                    console.log(el[9].split("<br>")[0]);
                    total += el[10];
                });
                // 1
                let division = total.split(",");
                let suma = division.reduce((a, b) => a + b, 0);
                // 2
                let division2 = suma.split("$");
                let suma2 = division2.reduce((a, b) => parseInt(a + b), 0);
                let arreglo = [];
                for (let index = 0; index < division2.length; index++) {
                    const element = division2[index];
                    arreglo.push(parseInt(element));
                }
                // 3
                let suma3 = arreglo.reduce((a, b) => parseInt(a + b), 0);
                $("#total_pagar").val(formatterPeso.format(suma3));
                $(nRow).attr('id', aData[0]);
            },
            'ajax': {
                'url': 'datatable_detalle_compra.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': true,
            }]
        });
    });
</script>