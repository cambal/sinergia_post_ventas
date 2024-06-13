<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header('Location: permisos.php');
}
if (!empty($_POST)) {
  $alert = "";
  $id = $_POST['id'];
  $codigo = $_POST['codigo1'];
  $codigo_hijo = $_POST['codigo_hijo'];
  $producto = $_POST['producto'];
  $precio = $_POST['precio'];
  $precio_menudeo = $_POST['precio_menudeo'];
  $cant_menudeo = $_POST['cant_menudeo'];
  $precio_blister = $_POST['precio_blister'];
  $cant_blister = $_POST['cant_blister'];
  $cant_global = $_POST['cant_global'];
  $precio_compra = $_POST['precio_compra'];
  $invima = $_POST['invima'];
  $cantidad = $_POST['cantidad'];
  $existencia_minima = $_POST['existencia_minima'];
  $tipo = $_POST['tipo'];
  $laboratorio = $_POST['laboratorio'];
  $iva = $_POST['iva'];

  // validando campos vacios
  if (empty($codigo) || empty($producto) || empty($tipo) || empty($laboratorio)  || empty($precio_compra) || empty($existencia_minima) || empty($precio) || $precio <  0) {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
  } else {
    // 
    if (empty($id)) {
      $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
      $result = mysqli_fetch_array($query);
      if ($result > 0) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El codigo ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
      } else {
        $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,codigo_hijo,descripcion,cant_menudeo,cant_blister,cant_global, precio_menudeo,precio_blister,precio_global,precio_compra,iva,invima,existencia_minima,id_lab,id_tipo, vencimiento) values ('$codigo','$codigo_hijo', '$producto', '$cant_menudeo', '$cant_blister','$cant_global','$precio_menudeo','$precio_blister', '$precio', '$precio_compra', '$iva', '$invima', '$existencia_minima', $laboratorio, $tipo, '$vencimiento')");
        if ($query_insert) {
          $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
          $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
        }
      }
    } else {
      $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', codigo_hijo = '$codigo_hijo', descripcion = '$producto', cant_menudeo = '$cant_menudeo',cant_blister='$cant_blister',cant_global=$cant_global,precio_menudeo='$precio_menudeo',precio_blister='$precio_blister', precio_global= $precio, precio_compra=$precio_compra, iva=$iva, invima='$invima', existencia_minima = $existencia_minima, id_tipo = $tipo, id_lab = $laboratorio, vencimiento = '$vencimiento' WHERE codproducto = $id");
      if ($query_update) {
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
      } else {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
      }
    }
  }
}
include_once "includes/header.php";
?>

<body class="g-sidenav-show  bg-gray-100">
  <!-- contenedor -->
  <div class="container-fluid py-4">
    <?php echo isset($alert) ? $alert : ''; ?>
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h6>Productos</h6>
          </div>
          <!-- Modal -->
          <div class="modal fade bd-example-modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Producto</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="" method="post" autocomplete="off" id="formulario">
                  <div class="modal-body">
                    <div class="row">
                      <!-- col -->
                      <div class="col-md-4">
                        <!--  -->
                        <div class="form-group">
                          <label for="codigo1" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras <span class="text-danger">(*)</span></label>
                          <input type="text" placeholder="Ingrese código de barras" name="codigo1" id="codigo1" class="form-control" required>
                          <input type="hidden" id="id" name="id" required>
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="codigo_hijo" class="text-dark font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras hijo</label>
                          <input type="text" placeholder="Ingrese código de barras hijo" name="codigo_hijo" id="codigo_hijo" class="form-control">
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="producto" class=" text-dark font-weight-bold">Producto <span class="text-danger">(*)</span></label>
                          <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control" required>
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="invima" class=" text-dark font-weight-bold">Invima</label>
                          <input type="text" placeholder="Ingrese invima" class="form-control" name="invima" id="invima">
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="existencia_minima" class="d-flex text-dark font-weight-bold">
                            Stock Minimo <span class="text-danger">(*)</span>
                          </label>
                          <input type="number" placeholder="Ingrese cantidad" class="form-control" name="existencia_minima" id="existencia_minima" required>
                        </div>
                      </div>
                      <!-- col -->
                      <div class="col-md-5">
                        <!--  -->
                        <div class="form-group">
                          <label for="iva" class=" text-dark font-weight-bold">Iva</label>
                          <input type="number" placeholder="Ingrese precio unidad" class="form-control" name="iva" id="iva" value="0">
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="tipo">Tipo <span class="text-danger">(*)</span></label>
                          <select id="tipo" class="form-control" name="tipo" required>
                            <?php
                            $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos ORDER BY tipo ASC");
                            while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                              <option value="<?php echo $datos['id'] ?>"><?php echo $datos['tipo'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="laboratorio">Laboratorio <span class="text-danger">(*)</span></label>
                          <select id="laboratorio" class="form-control" name="laboratorio" required>
                            <?php
                            $query_lab = mysqli_query($conexion, "SELECT * FROM laboratorios ORDER BY laboratorio ASC");
                            while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                              <option class="p-3" value="<?php echo $datos['id'] ?>"><?php echo $datos['laboratorio'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <!--  -->
                        <div class="form-group mb-0">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="menudeo" checked="">
                                <label class="form-check-label d-flex align-items-center" for="menudeo" style="height: 1.8rem;">
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
                                <input class="form-check-input" type="checkbox" id="blister">
                                <label class="form-check-label d-flex align-items-center" for="blister" style="height: 1.8rem;">
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
                        <!--  -->
                        <div class="form-group mb-0">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="cant_menudeo" class=" text-dark font-weight-bold">Cant Menudeo</label>
                                <input type="number" placeholder="Ingrese precio unidad" class="form-control" name="cant_menudeo" id="cant_menudeo" disabled>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="cant_blister" class=" text-dark font-weight-bold">Cant Blister</label>
                                <input type="number" placeholder="Ingrese precio blister" class="form-control" name="cant_blister" id="cant_blister" disabled>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="cant_global" class=" text-dark font-weight-bold">Cant Global <span class="text-danger">(*)</span></label>
                                <input type="number" placeholder="Ingrese precio blister" class="form-control" name="cant_global" id="cant_global">
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--  -->
                        <div class="form-group mb-0">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="precio_menudeo" class=" text-dark font-weight-bold">Precio Menudeo</label>
                                <input type="text" placeholder="Ingrese precio unidad" class="form-control separadorMiles" name="precio_menudeo" id="precio_menudeo" disabled>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="precio_blister" class=" text-dark font-weight-bold">Precio Blister</label>
                                <input type="text" placeholder="Ingrese precio blister" class="form-control separadorMiles" name="precio_blister" id="precio_blister" disabled>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- col -->
                      <div class="col-md-3">
                        <!--  -->
                        <div class="form-group">
                          <label for="precio_compra" class=" text-dark font-weight-bold">Precio compra global <span class="text-danger">(*)</span></label>
                          <input type="text" placeholder="Ingrese precio compra" class="form-control separadorMiles" name="precio_compra" id="precio_compra" data-type="currency" required>
                          <!-- <small><div id="precio_compra_format"></div></small> -->
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="precio" class="d-flex text-dark font-weight-bold">
                            Precio Venta Global <span class="text-danger">(*)</span>
                          </label>
                          <input type="text" placeholder="Ingrese precio venta" class="form-control separadorMiles" name="precio" id="precio" required>
                        </div>
                        <!--  -->
                        <div class="form-group">
                          <label for="cantidad" class="d-flex text-dark font-weight-bold">Cant stock actual <span class="text-danger">(*)</span>
                          </label>
                          <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad" disabled>
                        </div>
                        <!--  -->
                        <!-- <div class="form-group">
                          <label for="lote" class=" text-dark font-weight-bold">Lote <span class="text-danger">(*)</span></label>
                          <input type="text" placeholder="Ingrese lote" class="form-control" name="lote" id="lote" required>
                        </div> -->
                        <!--  -->
                        <!-- <div class="form-group">
                          <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si" disabled>
                          <label for="vencimiento">Vencimiento <span class="text-danger">(*)</span></label>
                          <div id="listas">
                          </div>
                        </div> -->
                        <!--  -->
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="form-group pr-5">
                      <input type="button" value="Limpiar" onclick="limpiar()" class="btn btn-info" id="btnNuevo">
                      <input type="button" onclick="guardarProductoBtn()" value="Registrar" class="btn btn-primary" id="btnAccion">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
            <div class="row px-5 justify-content-end mb-4">
              <div class="col-md-3 d-flex  justify-content-end">
                <button data-toggle="modal" data-target="#exampleModal" onclick="limpiar()" class="btn btn-primary" id="openModal">Agregar nuevo</button>
              </div>
            </div>
            <div class="table-responsive p-0">
              <!-- table -->
              <table class="table table-hover align-items-center mb-0" id="tbl_productos">
                <thead>
                  <tr>
                    <th>Cod Barras</th>
                    <th>Producto</th>
                    <th>Lab</th>
                    <th>$ Venta menudeo</th>
                    <th>$ Venta blister</th>
                    <th>$ Venta</th>
                    <th>$ Compra</th>
                    <th>Stock</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once "includes/footer.php"; ?>
  </div>
  <script>
    $(document).ready(function() {
      $('#tbl_productos').DataTable({
        "pageLength": 5,
        "lengthMenu": [
          [1000, 500, 100, 50, 25],
          [1000, 500, 100, 50, 25]
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
          'url': 'datatable_productos.php',
          'type': 'post',
        },
        "columnDefs": [{
          'target': [5],
          'orderable': true,
        }]
      });
    });
  </script>
</body>

</html>