<?php
require_once "../conexion.php";
session_start();
// busquedas input
if (isset($_GET['q'])) {
    $datos = array();
    $nombre = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM cliente WHERE nombre LIKE '%$nombre%' OR cedulaNit LIKE '%$nombre%' LIMIT 10");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['idcliente'];
        $data['cedulaNit'] = $row['cedulaNit'];
        $data['label'] = $row['cedulaNit'] . ' - ' . $row['nombre'];
        $data['nombre'] = $row['nombre'];
        $data['direccion'] = $row['direccion'];
        $data['telefono'] = $row['telefono'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['pro'])) {
    $datos = array();
    $nombre = $_GET['pro'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT *, SUM(existencia) AS existencia FROM lotes l INNER JOIN producto p ON l.id_producto = p.codproducto WHERE (p.codigo LIKE '%" . $nombre . "%' OR p.codigo_hijo LIKE '%" . $nombre . "%' OR p.descripcion LIKE '%" . $nombre . "%') AND p.delete = 0 AND l.existencia > 0 GROUP BY p.codigo ORDER BY p.descripcion ASC LIMIT 10");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $id_lab = $row['id_lab'];
        $id_tipo = $row['id_tipo'];

        $laboratorio = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $id_lab LIMIT 10");
        $assocLab = mysqli_fetch_assoc($laboratorio);

        $tipos = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $id_tipo LIMIT 10");
        $assocTipo = mysqli_fetch_assoc($tipos);

        if ($row['precio_menudeo'] > 0) {
            $data['label'] = $row['codigo'] . ' -- ' . substr($row['descripcion'], 0, 30) . ' -- ' . substr($assocLab['laboratorio'], 0, 18) . ' -- ' . '$' . number_format($row['precio_menudeo']);
        } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] > 0) {
            $data['label'] = $row['codigo'] . ' -- ' . substr($row['descripcion'], 0, 30) . ' -- ' . substr($assocLab['laboratorio'], 0, 18) . ' -- ' . '$' . number_format($row['precio_blister']);
        } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] == 0) {
            $data['label'] = $row['codigo'] . ' -- ' . substr($row['descripcion'], 0, 30) . ' -- ' . substr($assocLab['laboratorio'], 0, 18) . ' -- ' . '$' . number_format($row['precio_global']);
        }
        $data['value'] = $row['descripcion'];
        $data['cant_menudeo'] = $row['cant_menudeo'];
        $data['cant_blister'] = $row['cant_blister'];
        $data['cant_global'] = $row['cant_global'];
        $data['precio_menudeo'] = $row['precio_menudeo'];
        $data['precio_blister'] = $row['precio_blister'];
        $data['precio_venta'] = $row['precio_global'];
        $data['precio_compra'] = $row['precio_compra'];
        $data['existencia'] = $row['existencia'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
    // mostrar producto
} else if (isset($_GET['compra'])) {
    $datos = array();
    $nombre = $_GET['compra'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT * FROM lotes l INNER JOIN producto p WHERE (p.codigo LIKE '%" . $nombre . "%' OR p.codigo_hijo LIKE '%" . $nombre . "%' OR p.descripcion LIKE '%" . $nombre . "%') AND p.delete = 0 GROUP BY p.codigo ORDER BY p.descripcion ASC LIMIT 10");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $id_lab = $row['id_lab'];
        $id_tipo = $row['id_tipo'];
        $laboratorio = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $id_lab LIMIT 10");
        $assocLab = mysqli_fetch_assoc($laboratorio);

        $tipos = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $id_tipo LIMIT 10");
        $assocTipo = mysqli_fetch_assoc($tipos);

        if ($row['precio_menudeo'] > 0) {
            $data['label'] = $row['codigo'] . ' -- ' . substr($row['descripcion'], 0, 30) . ' -- ' . substr($assocLab['laboratorio'], 0, 18) . ' -- ' . '$' . number_format($row['precio_menudeo']);
        } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] > 0) {
            $data['label'] = $row['codigo'] . ' -- ' . substr($row['descripcion'], 0, 30) . ' -- ' . substr($assocLab['laboratorio'], 0, 18) . ' -- ' . '$' . number_format($row['precio_blister']);
        } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] == 0) {
            $data['label'] = $row['codigo'] . ' -- ' . substr($row['descripcion'], 0, 30) . ' -- ' . substr($assocLab['laboratorio'], 0, 18) . ' -- ' . '$' . number_format($row['precio_global']);
        }
        // $data['label'] = $row['codigo'] . ' - ' . $row['descripcion'];
        $data['codigo'] = $row['codigo'];
        $data['precio_menudeo'] = $row['precio_menudeo'];
        $data['cant_menudeo'] = $row['cant_menudeo'];
        $data['precio_blister'] = $row['precio_blister'];
        $data['cant_blister'] = $row['cant_blister'];
        $data['cant_global'] = $row['cant_global'];
        $data['precio'] = $row['precio_global'];
        $data['codigo_hijo'] = $row['codigo_hijo'];
        $data['descripcion'] = $row['descripcion'];
        $data['precio_compra'] = $row['precio_compra'];
        $data['invima'] = $row['invima'];
        $data['existencia'] = $row['existencia'];
        $data['existencia_minima'] = $row['existencia_minima'];
        $data['laboratorio'] = $assocLab['laboratorio'];
        $data['tipo'] = $assocTipo['tipo'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
    // mostrar producto
} else if (isset($_GET['traerLote'])) {
    $datos = array();
    $id_prod = $_GET['id_prod'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_prod GROUP BY vencimiento ASC");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['id'];
        $data['label'] = $row['vencimiento'] . ' -- (' . $row['existencia'] . ' unidades) -- ' . $row['lote'];
        $data['lote'] = $row['lote'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
    // mostrar producto
} else if (isset($_GET['lote_compra'])) {
    $datos = array();
    $nombre = $_GET['lote_compra'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT * FROM lotes WHERE (lote LIKE '%" . $nombre . "%') GROUP BY lote ASC LIMIT 10");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['label'] = $row['lote'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
    die();
}
// 
else if (isset($_GET['detalle'])) {
    // variables
    $id = $_SESSION['idUser'];
    $datos = array();
    // consulta detalles venta
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_temp d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id");
    // while de detalles venta
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['codigo'] = $row['codigo'];
        $data['lote'] = $row['lote'];
        $data['descripcion'] = $row['descripcion'];
        $data['cant_global'] = $row['cant_global'];
        $data['cant_unidad'] = $row['cant_unidad'];
        $data['tipo_venta'] = $row['tipo_venta'];
        $data['cantidad'] = $row['cantidad'];
        $data['descuento'] = $row['descuento'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = $row['total'];
        array_push($datos, $data);
    }
    // return array
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_detalle_compra'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_temp_compra WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['devolucion'])) {
    $id_detalle = $_GET['id'];
    $cantidad = $_GET['cantidad'];
    // consulta
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_venta WHERE id_detalle_venta = $id_detalle");
    // fetch
    $datosConsulta = mysqli_fetch_assoc($consulta);
    // variable
    $id_venta = $datosConsulta['id_venta'];
    $id_producto = $datosConsulta['id_producto'];
    $lote = $datosConsulta['lote'];
    $vencimiento = $datosConsulta['vencimiento'];
    $multiplicacion = ($datosConsulta['cantidad'] * $datosConsulta['cant_unidad']);
    // eliminar
    $query = mysqli_query($conexion, "UPDATE detalle_venta SET estado = 1 WHERE id_detalle_venta = $id_detalle");
    $msg = "llego1";
    // validacion
    if ($query) {
        $query2 = mysqli_query($conexion, "UPDATE ventas SET estado = 1 WHERE id = $id_venta");
        // trayendo stock actual
        $msg = "llego";

        $stockActual = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_producto AND lote = '$lote'");
        $stockNuevo = mysqli_num_rows($stockActual);
        $stockNuevoArray = mysqli_fetch_assoc($stockActual);
        if ($stockNuevo > 0) {
            $stockTotal = ($stockNuevoArray['existencia'] + $cantidad);
            $actualizarCantLote = mysqli_query($conexion, "UPDATE lotes SET existencia = $stockTotal WHERE id_producto = $id_producto AND lote = '$lote'");
        } else {
            $insertar = mysqli_query($conexion, "INSERT INTO lotes(id_producto, lote, vencimiento, existencia) VALUES ($id_producto, '$lote', '$vencimiento', $multiplicacion)");
            $msg = "ok";
        }
    } else {
        $msg = "Error";
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['consultarVenta'])) {
    // variables
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];

    echo json_encode($total);
    die();
} else if (isset($_GET['procesarVenta'])) {
    // variables
    $id_cliente = $_GET['id'];
    $id_user = $_SESSION['idUser'];
    $metodo_pago = $_GET['metodo_pago'];
    $hoy = date('Y-m-d');
    // consuktando  detalle_temp
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    // insertando en tabla ventas
    $insertar = mysqli_query($conexion, "INSERT INTO ventas(id_cliente, total, metodo_pago, id_usuario, fecha) VALUES ($id_cliente, '$total', '$metodo_pago', $id_user, '$hoy')");
    if ($insertar) {
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM ventas");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];
        $consultaDetalle = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_usuario = $id_user");

        $array = array();
        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_producto2 = $row['id_producto'];
            $lote = $row['lote'];
            $vencimiento = $row['vencimiento'];
            $cantidad = $row['cantidad'];
            $cant_unidad = $row['cant_unidad'];
            $desc = $row['descuento'];
            $precio = $row['precio_venta'];
            $total = $row['total'];
            // consultando producto seleccionado
            $multi = $cant_unidad *  $cantidad;
            // consulta lote con menor fecha de vencimiento
            $consultLote = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_producto2 AND lote = '$lote'");
            $resultconsultLote = mysqli_fetch_assoc($consultLote);
            // 
            $multi = $cant_unidad *  $cantidad;
            // $stockTotal3 = $resultconsultLote['existencia'] - $multi;
            // restando existencia
            $existenciaLote = ($resultconsultLote['existencia'] - $multi);
            // si la existencia del lote esta en 0 se elimina si aun tiene unidades se actualiza
            if ($existenciaLote <= 0) {
                $query_delete = mysqli_query($conexion, "DELETE FROM lotes WHERE id_producto = $id_producto2 AND lote = '$lote'");
            } else {
                $actualizarLote = mysqli_query($conexion, "UPDATE lotes SET existencia = $existenciaLote WHERE id_producto = $id_producto2 AND lote = '$lote'");
            }
            // insertandop detalle venta
            $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_venta (id_producto, lote, vencimiento, id_venta, cantidad, cant_unidad, precio, descuento, total, estado, cierre_caja) VALUES ($id_producto2, '$lote', '$vencimiento', $ultimoId, $cantidad, $cant_unidad, '$precio', '$desc', '$total', 0, 0)");
            // validando que todo se halla insertado cone exito
            if ($insertarDet) {
                // eliminando de la tabla temporal
                $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id_usuario = $id_user");
                $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
            }
        }
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_POST['guardarPoducto'])) {
    // variables
    $codigo = $_POST['codigo'];
    $codigo_hijo_compra = $_POST['codigo_hijo_compra'];
    $descripcion_compra = $_POST['descripcion_compra'];
    $invima_compra = $_POST['invima_compra'];
    $existencia_minima_compra = $_POST['existencia_minima_compra'];
    $tipo_compra = $_POST['tipo_compra'];
    $laboratorio_compra = $_POST['laboratorio_compra'];
    $precio_menudeo = $_POST['precio_menudeo'];
    $cant_menudeo = $_POST['cant_menudeo'];
    $precio_blister = $_POST['precio_blister'];
    $cant_blister = $_POST['cant_blister'];
    $cant_global = $_POST['cant_global'];
    $precio_compra_compra = $_POST['precio_compra_compra'];
    $precio_venta_compra = $_POST['precio_venta_compra'];
    // consulta 
    $consultaGuardarProducto = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = $codigo");
    $resultGuardarProducto = mysqli_fetch_row($consultaGuardarProducto);
    // validando si el producto existe
    if ($resultGuardarProducto > 1) {
        $msn = "El código de barras ya se encuentra en uso";
        echo json_encode($msn);
        die();
    } else {
        $insertarCierreCaja = mysqli_query($conexion, "INSERT INTO producto(`codproducto`, `codigo`, `codigo_hijo`, `descripcion`, `cant_menudeo`, `cant_blister`, `cant_global`, `precio_menudeo`, `precio_blister`, `precio_global`, `precio_compra`, `iva`, `invima`, `existencia_minima`, `id_lab`, `id_tipo`, `delete`) values (null,'$codigo','$codigo_hijo_compra', '$descripcion_compra', '$cant_menudeo', '$cant_blister', '$cant_global', '$precio_menudeo', '$precio_blister', '$precio_venta_compra', '$precio_compra_compra',  0,'$invima_compra', '$existencia_minima_compra', $laboratorio_compra, $tipo_compra, 0)");
        $msn = "producto guardado correctamente";
        echo json_encode($msn);
        die();
    }
} else if (isset($_POST['actualizar_cantidad_venta'])) {
    $id = $_POST['id'];
    $id_user = $_SESSION['idUser'];
    $id_detalle_venta = $_POST['id_detalle_venta'];
    $loteSeleccionado = $_POST['lote'];
    $nueva_cantidad_venta = $_POST['nueva_cantidad_venta'];
    $nueva_cant_unidad_venta = $_POST['nueva_cant_unidad_venta'];
    $sub_total = $_POST['sub_total'];
    // operacion
    $multiplicacion = ($nueva_cantidad_venta * $nueva_cant_unidad_venta);
    // consultar si hay la existenmcia sufuciente
    $consultaCantidadLote = mysqli_query($conexion, "SELECT * FROM lotes WHERE lote = '$loteSeleccionado' AND existencia >= $multiplicacion");
    $resultConsultaCantidadLote = mysqli_num_rows($consultaCantidadLote);
    if ($resultConsultaCantidadLote > 0) {
        $consultaProducto = mysqli_query($conexion, "UPDATE `detalle_temp` SET `cantidad` = '$nueva_cantidad_venta', `total` = $sub_total WHERE id = $id_detalle_venta");
        if ($consultaProducto) {
            $msg2 = array('mensaje' => 'exitoso');
        } else {
            $msg2 = array('mensaje' => 'Hubo un error intenta de nuevo');
        }
    } else {
        $msg2 = array("mensaje" => 'No hay unidades suficientes en ese lote');
    }
    echo json_encode($msg2);
    die();
} else if (isset($_POST['actualizar_cantidad_compra'])) {
    $id_detalle_compra = $_POST['id_detalle_compra'];
    $nueva_cantidad = $_POST['nueva_cantidad'];
    $nuevo_total = $_POST['nuevo_total'];

    $consultaProducto = mysqli_query($conexion, "UPDATE `detalle_temp_compra` SET `cantidad`='$nueva_cantidad', `total`= $nuevo_total WHERE id = $id_detalle_compra");
    // $resultNumRows = mysqli_num_rows($consultaProducto);
    if ($consultaProducto) {
        $msg2 = array('mensaje' => 'exitoso');
    } else {
        $msg2 = array('mensaje' => 'error');
    }
    echo json_encode($msg2);
    die();
} else if (isset($_POST['actualizar_total_compra'])) {
    $id_detalle_compra = $_POST['id_detalle_compra'];
    $nuevo_total = $_POST['nuevo_total'];

    $consultaProducto = mysqli_query($conexion, "UPDATE `detalle_temp_compra` SET `total`= $nuevo_total WHERE id = $id_detalle_compra");
    if ($consultaProducto) {
        $msg2 = array('mensaje' => 'exitoso');
    } else {
        $msg2 = array('mensaje' => 'error');
    }
    echo json_encode($msg2);
    die();
} else if (isset($_POST['actualizar_compra'])) {
    $id_detalle_compra = $_POST['id_detalle_compra'];
    $nueva_cantidad = $_POST['nueva_cantidad'];
    $nuevo_total = $_POST['nuevo_total'];
    $nuevo_precio_venta = $_POST['nuevo_precio_venta'];
    $nuevo_precio_compra = $_POST['nuevo_precio_compra'];
    $nuevo_precio_blister = $_POST['nuevo_precio_blister'];
    $nuevo_precio_menudeo = $_POST['nuevo_precio_menudeo'];
    $nuevo_lote = $_POST['nuevo_lote'];
    $nuevo_vencimiento = $_POST['nuevo_vencimiento'];

    $consultaProducto = mysqli_query($conexion, "UPDATE `detalle_temp_compra` SET `cantidad`='$nueva_cantidad',`precio_menudeo_c`=$nuevo_precio_menudeo,`precio_blister_c`=$nuevo_precio_blister,`precio_c`=$nuevo_precio_compra,`precio_venta`=$nuevo_precio_venta,`total`= $nuevo_total,`vencimiento_compra`='$nuevo_vencimiento',`lote_compra`='$nuevo_lote' WHERE id = $id_detalle_compra");
    if ($consultaProducto) {
        $msg2 = array('mensaje' => 'exitoso');
    } else {
        $msg2 = array('mensaje' => 'error');
    }
    echo json_encode($msg2);
    die();
} else if (isset($_POST['guardarPoductoBtn'])) {
    // variables
    $codigo2 = $_POST['codigo'];
    $codigo_hijo2 = $_POST['codigo_hijo'];
    $descripcion_compra2 = $_POST['descripcion_compra'];
    $invima_compra2 = $_POST['invima_compra'];
    $existencia_minima_compra2 = $_POST['existencia_minima_compra'];
    $iva2 = $_POST['iva'];
    $tipo_compra2 = $_POST['tipo_compra'];
    $laboratorio_compra2 = $_POST['laboratorio_compra'];
    $precio_menudeo2 = $_POST['precio_menudeo'];
    $cant_menudeo2 = $_POST['cant_menudeo'];
    $precio_blister2 = $_POST['precio_blister'];
    $cant_blister2 = $_POST['cant_blister'];
    $cant_global2 = $_POST['cant_global'];
    $precio_compra_compra2 = $_POST['precio_compra_compra'];
    $precio_venta_compra2 = $_POST['precio_venta_compra'];
    // consulta 
    $consultaGuardarProducto = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo2'");
    $resultGuardarProducto = mysqli_num_rows($consultaGuardarProducto);
    // validando si el producto existe
    if ($resultGuardarProducto > 0) {
        $insertarCierreCaja = mysqli_query($conexion, "UPDATE `producto` SET `codigo`='$codigo2',`codigo_hijo`='$codigo_hijo2',`descripcion`='$descripcion_compra2',`cant_menudeo`='$cant_menudeo2',`cant_blister`='$cant_blister2',`cant_global`='$cant_global2',`precio_menudeo`='$precio_menudeo2',`precio_blister`='$precio_blister2',`precio_global`='$precio_venta_compra2',`precio_compra`='$precio_compra_compra2',`iva`='$iva2',`invima`='$invima_compra2',`existencia_minima`='$existencia_minima_compra2',`id_lab`='$laboratorio_compra2',`id_tipo`='$tipo_compra2',`delete`= 0 WHERE  codigo = '$codigo2'");
        $msn = "producto actualizado correctamente";
        echo json_encode($msn);
        die();
    } else {
        $insertarCierreCaja = mysqli_query($conexion, "INSERT INTO producto(`codigo`, `codigo_hijo`, `descripcion`, `cant_menudeo`, `cant_blister`, `cant_global`, `precio_menudeo`, `precio_blister`, `precio_global`, `precio_compra`, `iva`, `invima`, `existencia_minima`, `id_lab`, `id_tipo`, `delete`) values ('$codigo2','$codigo_hijo2', '$descripcion_compra2', '$cant_menudeo2', '$cant_blister2', '$cant_global2', '$precio_menudeo2', '$precio_blister2', '$precio_venta_compra2', '$precio_compra_compra2', $iva2,'$invima_compra2', '$existencia_minima_compra2', $laboratorio_compra2, $tipo_compra2,0)");
        $msn = "producto guardado correctamente";
        echo json_encode($msn); 
        die();
    }
} else if (isset($_POST['procesarCompra'])) {
    // variables
    $id_user = $_SESSION['idUser'];
    $proveedor_compra = $_POST['proveedor_compra'];
    $num_fac_compra = $_POST['num_fac_compra'];
    $total_fac_compra = $_POST['total_fac_compra'];
    $hoy2 = date('Y-m-d H:i:s');
    // consulta  detalles compra
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp_compra WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    // insertar compras
    $insertar = mysqli_query($conexion, "INSERT INTO compras(total, id_usuario,proveedor,num_fac_compra,fecha) VALUES ('$total', $id_user, '$proveedor_compra', '$num_fac_compra', '$hoy2')");
    // validando si se inserto la compra correctamente
    if ($insertar) {
        // id max
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM compras");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];
        // consulta a detalle compra
        $consultaDetalle = mysqli_query($conexion, "SELECT * FROM detalle_temp_compra WHERE id_usuario = $id_user");
        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_producto3 = $row['id_producto'];
            $cant_unit = $row['cant_unit'];
            $cantidad3 = $row['cantidad'];
            $precio_menudeo_c = $row['precio_menudeo_c'];
            $precio_blister_c = $row['precio_blister_c'];
            $precio_compra = $row['precio_c'];
            $precio = $row['precio_venta'];
            $vencimiento_compra = $row['vencimiento_compra'];
            $laboratorio_compra = $row['laboratorio'];
            $lote_compra = $row['lote_compra'];
            $total = $row['total'];
            // insertar data tabla detalle compra
            $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_compra (id_producto, id_compra, cantidad, cant_unit, precio_menudeo_d, precio_blister_d, precio_compra, precio, total, proveedor, laboratorio,num_fac_compra,total_fac_compra, vencimiento_fac, lote_c) VALUES ($id_producto3, $ultimoId, '$cantidad3', $cant_unit, '$precio_menudeo_c', '$precio_blister_c', '$precio_compra', '$precio', '$total', '$proveedor_compra', '$laboratorio_compra', '$num_fac_compra', '$total_fac_compra', '$vencimiento_compra', '$lote_compra')");
            // consulta lote
            $consultLote = mysqli_query($conexion, "SELECT * FROM `lotes` WHERE lote = '$lote_compra'");
            $resultconsultLote = mysqli_fetch_assoc($consultLote);
            if ($resultconsultLote > 0) {
                $totalExistencia = $resultconsultLote['existencia'] + $cant_unit;
                // actualizar lote
                $actualizarLote = mysqli_query($conexion, "UPDATE lotes SET id_producto = '$id_producto3', lote = '$lote_compra', vencimiento = '$vencimiento_compra', existencia = '$totalExistencia' WHERE lote = '$lote_compra'");
            } else {
                $totalExistencia = $cant_unit;
                // insertar lote
                $insertarLote = mysqli_query($conexion, "INSERT INTO lotes (id_producto, lote, vencimiento,existencia) VALUES ('$id_producto3', '$lote_compra', '$vencimiento_compra', '$totalExistencia')");
            }
            // producto
            $stock2 = mysqli_query($conexion, "UPDATE producto SET precio_menudeo = '$precio_menudeo_c',precio_blister = '$precio_blister_c', precio_global = '$precio', precio_compra = '$precio_compra' WHERE codproducto = $id_producto3");
        }
        if ($insertarDet) {
            // elimina data de tabla detalles temporal
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temp_compra WHERE id_usuario = $id_user");
            $msg = array('id_compra' => $ultimoId);
            echo json_encode($msg);
            die();
        }
    } else {
        $msg = array('mensaje' => 'error');
        echo json_encode($msg);
        die();
    }
} else if (isset($_POST['registrarCierreCaja'])) {
    $devoluciones = $_POST['devoluciones'];
    $efectivo_total = $_POST['efectivo'];
    $nequi = $_POST['nequi'];
    $daviplata = $_POST['daviplata'];
    $tarjeta = $_POST['tarjeta'];
    $efectivo_actual = $_POST['efectivo_fisico'];
    $cuanto_pagaste = $_POST['cuanto_pagaste'];
    $efectivo = $_POST['efectivo'];
    $resultSobrante = $_POST['resultSobrante'];
    $observacion = $_POST['observacion'];
    $hoy = date('Y-m-d H:i:s');
    $hoy3 = date('Y-m-d');
    $id_user = $_SESSION['idUser'];

    $sum = $efectivo_total + $nequi + $daviplata + $tarjeta;
    // select a detalle venta
    $query5 = mysqli_query($conexion, "SELECT * FROM detalle_venta d INNER JOIN ventas v WHERE d.id_venta = v.id AND v.fecha = '$hoy3' AND d.estado = 0 AND cierre_caja = 0");

    while ($row = mysqli_fetch_assoc($query5)) {
        $id_ven = $row['id_venta'];
        $updateDetalleVenta = mysqli_query($conexion, "UPDATE detalle_venta SET cierre_caja = 1 WHERE id_venta = $id_ven");
    }

    // insert tabla
    $insertarCierreCaja = mysqli_query($conexion, "INSERT INTO `cierre_caja`(`id`, `id_usuario`, `devoluciones`, `total`, `efectivo`, `nequi`, `daviplata`, `tarjeta`, `efectivo_actual`, `sobrante`, `cuanto_pagaste`, `observacion`, `fecha_cierre`) VALUES (null, '$id_user', '$devoluciones', '$sum', '$efectivo', '$nequi', '$daviplata', '$tarjeta', '$efectivo_actual', '$resultSobrante', '$cuanto_pagaste', '$observacion', '$hoy')");
    // select
    $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM ventas");
    $resultId = mysqli_fetch_assoc($id_maximo);
    $ultimoId = $resultId['total'];
    if ($insertarCierreCaja) {
        $msg = array('id_compra' => $ultimoId);
    } else {
        $msg = array('id_compra' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['descuento'])) {
    $id = $_GET['id'];
    $desc = $_GET['desc'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    if ($desc == 0) {
        $total_desc = $desc;
        $total = $result['total'] + $result['descuento'];
    } else {
        $total_desc = $desc + $result['descuento'];
        $total = $result['total'] - $desc;
    }
    $insertar = mysqli_query($conexion, "UPDATE detalle_temp SET descuento = $total_desc, total = '$total'  WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'descontado');
    } else {
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['editarCliente'])) {
    $idcliente = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarUsuario'])) {
    $idusuario = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $idusuario");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarProducto'])) {
    $id = $_GET['id'];
    // productos
    $sql = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");
    $data = mysqli_fetch_array($sql);
    // lotes
    $lot = mysqli_query($conexion, "SELECT SUM(existencia) AS existencia FROM lotes WHERE id_producto = $id GROUP BY id_producto");
    $row = mysqli_num_rows($lot);
    $rowArray = mysqli_fetch_assoc($lot);
    if ($row > 0) {
        array_push($data, $rowArray['existencia']);
    }
    //  return data
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarTipo'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarPresent'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM presentacion WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarLab'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarProvedor'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_POST['regDetalleCompra'])) {
    // 
    $id = $_POST['id'];
    // por unidades
    $cant = $_POST['cant'];
    // seleccionado por usuario
    $cantSeleccionado = $_POST['cantSeleccionado'];
    $precio_menudeo = $_POST['precio_menudeo'];
    $precio_blister = $_POST['precio_blister'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $vencimiento_compra = $_POST['vencimiento_compra'];
    $laboratorio_compra = $_POST['laboratorio_compra'];
    $lote_compra = $_POST['lote_compra'];
    $id_user = $_SESSION['idUser'];
    // echo $precio_compra;
    // echo $cant;
    $total = $precio_compra * $cantSeleccionado;
    // cambiando formato de tipo date
    // $proveedor_compra = date('Y-m-d', strtotime($prov));
    // consulta
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp_compra WHERE id_producto = $id AND id_usuario = $id_user AND lote_compra = '$lote_compra'");
    // 
    $datos = mysqli_fetch_assoc($verificar);
    // 
    $result = mysqli_num_rows($verificar);
    // verificar si e producto ya ha sido agregado
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + $cant;
        $total_precio = ($cantidad * $precio_compra);
        // 
        $verificarVencimiento = mysqli_query($conexion, "SELECT * FROM detalle_temp_compra d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE id_producto = $id AND id_usuario = $id_user AND d.vencimiento_compra != $vencimiento_compra");
        // 
        $resVen = mysqli_num_rows($verificarVencimiento);
        if ($resVen > 0) {
            while ($row = mysqli_fetch_assoc($verificarVencimiento)) {
                $ArrayVenc = $row['vencimiento_compra'];
                $cant_global = $row['cant_global'];
                $array = ($ArrayVenc);
            }
        }
        $ArrayVenc = $vencimiento_compra;

        // update
        $query11 = mysqli_query($conexion, "UPDATE `detalle_temp_compra` SET `cantidad`='$cantidad',`precio_c`='$precio_compra',`precio_venta`='$precio_venta',`total`='$total_precio',`vencimiento_compra`='$ArrayVenc',`laboratorio`='$laboratorio_compra',`lote_compra`='$lote_compra' WHERE id_producto = $id AND id_usuario = $id_user AND lote_compra = $lote_compra");
        if ($query11) {
            $msg = "Producto actualizado";
        } else {
            $msg = "Error al actualizar producto";
        }
    } else {
        // insertar compra
        $query22 = mysqli_query($conexion, "INSERT INTO detalle_temp_compra(id_usuario, id_producto, cantidad, cant_unit, precio_menudeo_c, precio_blister_c ,precio_c, precio_venta, total, vencimiento_compra, laboratorio,lote_compra) VALUES ($id_user, $id, $cantSeleccionado, $cant, '$precio_menudeo', '$precio_blister','$precio_compra','$precio_venta', '$total', '$vencimiento_compra', '$laboratorio_compra', '$lote_compra')");
        if ($query22) {
            $msg = "Producto agregado";
        } else {
            $msg = "Error al agregar producto";
        }
    }
    echo json_encode($msg);
    die();
} else if (isset($_POST['regDetalle'])) {
    // variables
    $id = $_POST['id'];
    $tipo_venta = $_POST['tipo_venta'];
    $precio = $_POST['precio'];
    $cant = $_POST['cant'];
    $cant_unidad = $_POST['cant_unidad'];
    $loteSeleccionado = $_POST['lote'];
    $id_user = $_SESSION['idUser'];
    // operaciones
    $cant_por_cantipo = ($cant * $cant_unidad);
    $total = ($precio * $cant);
    // consultar si ya esta el producto o no adjuntado en la tabla
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = $id AND id_usuario = $id_user AND lote = '$loteSeleccionado'");
    $result = mysqli_num_rows($verificar);
    // validando si encontro o no algun registro
    if ($result > 0) {
        $consultaMultiplicada = mysqli_query($conexion, "SELECT sum(cantidad*cant_unidad) AS Total, cantidad FROM detalle_temp WHERE id_producto = $id AND id_usuario = $id_user AND lote = '$loteSeleccionado' GROUP BY  id_producto");
        // sumando cantidad que ya hay almacenada con la cantidad recien ingresada
        $datos = mysqli_fetch_assoc($consultaMultiplicada);
        $cantidad = ($datos["Total"] + $cant_unidad);
        $cant_nueva = ($datos["cantidad"] + $cant);
        $total_nuevo = $cant_nueva * $precio;
        //  consultando existencia de lote
        $consultaCantidadLote = mysqli_query($conexion, "SELECT * FROM lotes WHERE lote = '$loteSeleccionado' AND existencia >= $cantidad");
        $resultConsultaCantidadLote = mysqli_num_rows($consultaCantidadLote);
        // validando si hay o no unidades en ese lote
        if ($resultConsultaCantidadLote > 0) {
            // actualizando venta
            $query = mysqli_query($conexion, "UPDATE `detalle_temp` SET `cant_unidad` = $cantidad, `cantidad` = '$cant_nueva', `total` = '$total_nuevo' WHERE id_producto = $id AND lote = '$loteSeleccionado'");
            if ($query) {
                $msg = "actualizado";
            } else {
                $msg = "Error al ingresar";
            }
        } else {
            $msg = "No hay las unidades suficientes en ese lote";
        }
    } else {
        $consultaCantidadLote = mysqli_query($conexion, "SELECT * FROM lotes WHERE lote = '$loteSeleccionado' AND existencia >= $cant_por_cantipo");
        $resultConsultaCantidadLote = mysqli_num_rows($consultaCantidadLote);
        $resultConsultaCantidadLoteArray = mysqli_fetch_assoc($consultaCantidadLote);
<<<<<<< HEAD

=======
>>>>>>> 895b97baf3db6036e761cb584e920e1c4b259d22
        if ($resultConsultaCantidadLote > 0) {
            $vencimiento = $resultConsultaCantidadLoteArray['vencimiento'];
            $query = mysqli_query($conexion, "INSERT INTO detalle_temp(id_usuario, id_producto, lote, vencimiento,cantidad,cant_unidad,tipo_venta,precio_venta, total) VALUES ($id_user, $id, '$loteSeleccionado', '$vencimiento', $cant, '$cant_unidad','$tipo_venta','$precio', '$total')");
            if ($query) {
                $msg = "registrado";
            } else {
                $msg = "Error al ingresar";
            }
        } else {
            $msg = "No hay las unidades suficientes en ese lote";
        }
    }
    echo json_encode($msg);
    die();
} else if (isset($_POST['cambio'])) {
    if (empty($_POST['actual']) || empty($_POST['nueva'])) {
        $msg = 'Los campos estan vacios';
    } else {
        $id = $_SESSION['idUser'];
        $actual = md5($_POST['actual']);
        $nueva = md5($_POST['nueva']);
        $consulta = mysqli_query($conexion, "SELECT * FROM usuario WHERE clave = '$actual' AND idusuario = $id");
        $result = mysqli_num_rows($consulta);
        if ($result == 1) {
            $query = mysqli_query($conexion, "UPDATE usuario SET clave = '$nueva' WHERE idusuario = $id");
            if ($query) {
                $msg = 'ok';
            } else {
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
    }
    echo $msg;
    die();
} else if (isset($_POST['permisos_sidebar'])) {
    $id_user = $_SESSION['idUser'];
    $permiso = "compras";
    $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
    $existe = mysqli_fetch_all($sql);
    if (empty($existe) && $id_user != 1) {
        $msg = "no";
    } else {
        $msg = "si";
    }
    echo $msg;
    die();
} else if (isset($_POST['temperatura_humedad'])) {
    // $id_user = $_SESSION['idUser'];
    $temperatura = $_POST['temperatura'];
    $humedad = $_POST['humedad'];
    $hora = $_POST['hora'];
    $fecha_tomada = $_POST['fecha_tomada'];

    $consult = mysqli_query($conexion, "SELECT * FROM temperatura_humedad WHERE fecha = '$fecha_tomada' AND hora = '$hora'");

    $result = mysqli_num_rows($consult);

    if ($result > 0) {
        $msg = "ya existe";
    } else {
        $sql = mysqli_query($conexion, "INSERT INTO `temperatura_humedad`(`temperatura`, `humedad`, `hora`, `fecha`) VALUES ('$temperatura','$humedad','$hora', '$fecha_tomada')");
        if ($sql) {
            $msg = 'ok';
        } else {
            $msg = "error";
        }
    }
    echo $msg;
    die();
} else if (isset($_POST['limpieza_desinfeccion'])) {
    // $id_user = $_SESSION['idUser'];
    $area_aseo = $_POST['area_aseo'];
    $solucion_sanizante = $_POST['solucion_sanizante'];
    $usuario_limpieza = $_POST['usuario_limpieza'];
    $fecha_limpieza = $_POST['fecha_limpieza'];
    $sql = mysqli_query($conexion, "INSERT INTO `limpieza_desinfeccion`(`area_aseo`, `solucion_sanizante`, `id_usuario`, `fecha`) VALUES ('$area_aseo','$solucion_sanizante','$usuario_limpieza', '$fecha_limpieza')");
    if ($sql) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
    die();
} else if (isset($_POST['residuos'])) {
    // $id_user = $_SESSION['idUser'];
    $fecha_residuos = $_POST['fecha_residuos'];
    $Biodegradables = $_POST['Biodegradables'];
    $Reciclables = $_POST['Reciclables'];
    $Inertes = $_POST['Inertes'];
    $Ordinarios = $_POST['Ordinarios'];
    $Biosanitarios = $_POST['Biosanitarios'];
    $Anatomopatologicos = $_POST['Anatomopatologicos'];
    $Cortopunzantes = $_POST['Cortopunzantes'];
    $DeAnimales = $_POST['DeAnimales'];
    $Fuentes_abiertas = $_POST['Fuentes_abiertas'];
    $Fuentes_cerradas = $_POST['Fuentes_cerradas'];
    $Farmacos = $_POST['Farmacos'];
    $Citotoxicos = $_POST['Citotoxicos'];
    $Metales_pesados = $_POST['Metales_pesados'];
    $Reactivos = $_POST['Reactivos'];
    $Contenedores_presurizados = $_POST['Contenedores_presurizados'];
    $Aceites_usados = $_POST['Aceites_usados'];

    $sql = mysqli_query($conexion, "INSERT INTO `residuos` (`fecha`, `biodegradables`, `reciclables`, `inertes`, `ordinarios`, `biosanitarios`, `anatomopatologicos`, `cortopunzantes`, `deanimales`, `fuentes_abiertas`, `fuentes_cerradas`, `farmacos`, `citotoxicos`, `metales_pesados`, `reactivos`, `contenedores_presurizados`, `aceites_usados`) VALUES ('$fecha_residuos','$Biodegradables','$Reciclables','$Inertes','$Ordinarios','$Biosanitarios','$Anatomopatologicos','$Cortopunzantes','$DeAnimales','$Fuentes_abiertas','$Fuentes_cerradas','$Farmacos','$Citotoxicos','$Metales_pesados','$Reactivos','$Contenedores_presurizados','$Aceites_usados')");

    if ($sql) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
    die();
} else if (isset($_POST['salidaAdmin'])) {
    // variables
    $id_user = $_SESSION['idUser'];
    $id_producto = $_POST['id'];
    $cantidad = $_POST['cantidad'];
    $lote = $_POST['lote'];
    $cant_unidad = $_POST['cant_unidad'];
    $hoy = date('Y-m-d H:i:s');
    $multi = $cant_unidad *  $cantidad;
    // consultando producto
    $consultLote = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_producto AND lote = '$lote' AND  existencia >= $cant_unidad");
    $result = mysqli_num_rows($consultLote);
    $resultconsultLote = mysqli_fetch_assoc($consultLote);
    // validando si existe y hay unidades en el producto
    if ($result > 0) {
        // restando existencia
        $existenciaLote = ($resultconsultLote['existencia'] - $multi);
        // si la existencia del lote esta en 0 se elimina si aun tiene unidades se actualiza
        if ($existenciaLote <= 0) {
            $query_delete = mysqli_query($conexion, "DELETE FROM lotes WHERE lote = '$lote'");
        } else {
            $actualizarLote = mysqli_query($conexion, "UPDATE lotes SET existencia = $existenciaLote WHERE lote = '$lote'");
        }
        // insertando detalle venta
        $sqlPost = mysqli_query($conexion, "INSERT INTO `salida_admin`(`id_salida`, `id_producto`, `id_usuario`, `cantidad`, `cant_unit`, `fecha`) VALUES (null,'$id_producto','$id_user','$cantidad', '$cant_unidad', '$hoy')");
        // validando que todo se halla insertado cone exito
        if ($sqlPost) {
            $msg = "ok";
        }
    } else {
        $msg = "no hay unidades";
    }
    echo $msg;
    die();
} else if (isset($_POST['entradaAdmin2'])) {
    // variables
    $id_user = $_SESSION['idUser'];
    $id_producto = $_POST['id'];
    $cantidad = $_POST['cantidad'];
    $lote = $_POST['lote'];
    $vencimiento = $_POST['vencimiento'];
    $cant_unidad = $_POST['cant_unidad'];
    $hoy = date('Y-m-d H:i:s');
    $multi = $cant_unidad *  $cantidad;
    // consultando producto
    $consultLote = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_producto AND lote = '$lote'");
    $result = mysqli_num_rows($consultLote);
    $resultconsultLote = mysqli_fetch_assoc($consultLote);
    if ($result > 0) {
        $totalExistencia = $resultconsultLote['existencia'] + $cant_unit;
        // actualizar lote
        $actualizarLote = mysqli_query($conexion, "UPDATE lotes SET existencia = $totalExistencia, lote = '$lote' WHERE lote = '$lote' AND id_producto = $id_producto");
    } else {
        $totalExistencia = $cant_unit;
        // insertar lote
        $insertarLote = mysqli_query($conexion, "INSERT INTO lotes (id_producto, lote, vencimiento, existencia) VALUES ('$id_producto3', '$lote', '$vencimiento', $totalExistencia)");
    }
    // insertando detalle venta
    $sqlPost = mysqli_query($conexion, "INSERT INTO `entrada_admin`(`id_entrada`, `id_producto`, `id_usuario`, `cantidad`, `cant_unit`, `fecha`) VALUES (null,'$id_producto','$id_user','$cantidad', '$cant_unidad', '$hoy')");
    // validando que todo se halla insertado cone exito
    if ($sqlPost) {
        $msg = "ok";
    }
    // return
    echo $msg;
    die();
} else if (isset($_POST['entradaAdmin'])) {
    // variables
    $id_user = $_SESSION['idUser'];
    $hoy = date('Y-m-d H:i:s');
    $id_p = $_POST['id_producto'];
    $lote_p = $_POST['lote'];
    $vencimiento_p = $_POST['vencimiento'];
    $cantidad_p = $_POST['cantidad'];
    $cantidad_unidad_p = $_POST['cantidad_unidad'];
    $multiplicacion = ($cantidad_p * $cantidad_unidad_p);
    // 
    $consultalote = mysqli_query($conexion, "SELECT * FROM lotes WHERE id_producto = $id_p AND lote = '$lote_p'");
    $resultadolote = mysqli_num_rows($consultalote);
    $resultadoloteArray = mysqli_fetch_assoc($consultalote);
    if ($resultadolote > 0) {
        $existenciaActual = $resultadoloteArray['existencia'];
        $existenciaNueva = ($existenciaActual + $cantidad_unidad_p);
        $actualizar = mysqli_query($conexion, "UPDATE lotes SET vencimiento = '$vencimiento_p', existencia = $existenciaNueva WHERE id_producto = $id_p AND lote == '$lote_p'");
        if ($actualizar) {
            $msgF = array('mensaje' => 'exitoso');
        } else {
            $msgF = array('mensaje' => 'error');
        }
    } else {
        // insertandop detalle venta
        $insertar = mysqli_query($conexion, "INSERT INTO lotes (`id_producto`, `lote`, `vencimiento`, `existencia`) VALUES ($id_p, '$lote_p', '$vencimiento_p', $cantidad_unidad_p)");
        // insertando detalle venta
        $sqlPost = mysqli_query($conexion, "INSERT INTO `entrada_admin`(`id_entrada`, `id_producto`, `id_usuario`, `cantidad`, `cant_unit`, `fecha`) VALUES (null,'$id_p','$id_user','$cantidad_unidad_p', '$cantidad_unidad_p', '$hoy')");
        // 
        if ($insertar) {
            $msgF = array('mensaje' => 'exitoso');
        } else {
            $msgF = array('mensaje' => 'error');
        }
    }
    echo json_encode($msgF);
    die();
}
 