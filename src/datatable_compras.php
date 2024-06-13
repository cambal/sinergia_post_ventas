<?php
include "conexion.php";
session_start();
$output = array();
$id = $_SESSION['idUser'];
$sql = "SELECT d.*, p.codproducto, p.descripcion, p.codigo, p.cant_global FROM detalle_temp_compra d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " AND p.descripcion like '%" . $search_value . "%'";
    $sql .= " AND d.id_usuario = $id";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY codproducto desc";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();
$count = 0;
while ($row = mysqli_fetch_assoc($query)) {
    $count = $count + 1;
    $sub_array = array();
    $sub_array[] = $row['codigo'];
    $sub_array[] = $row['descripcion'];
    // 
    $sub_array[] = ' <input class="form-control nuevo_lote${count}" placeholder="Desc" type="text" value="' . $row['lote_compra'] . '" name="nuevo_lote" id="nuevo_lote" disabled>';
    // 
    $sub_array[] = ' <input class="form-control nuevo_vencimiento'.$count.'" placeholder="Desc" type="date" value="'.$row['vencimiento_compra'].'" id="nuevo_vencimiento"disabled>';
    // precio menudeo
    $sub_array[] = ' <input class="form-control nuevo_precio_menudeo'.$count.'" placeholder="Desc" type="text" value="'.$row['precio_menudeo_c'].'" id="nuevo_precio_menudeo"disabled>';
    // precio blister
    $sub_array[] = ' <input class="form-control nuevo_precio_blister'.$count.'" placeholder="Desc" type="text" value="' . number_format($row['precio_blister_c']) . '" id="nuevo_precio_blister"disabled>';
    // precio compra
    $sub_array[] = '<input class="form-control nuevo_precio_compra' . $count . '" placeholder="Desc" type="text" value="' . number_format($row['precio_c']) . '" id="nuevo_precio_compra"disabled>';
    // precio venta
    $sub_array[] = ' <input class="form-control nuevo_precio_venta' . $count . '" placeholder="Desc" type="text" value="' . number_format($row['precio_venta']) . '" id="nuevo_precio_venta"disabled>)';
    // cantidad
    $sub_array[] = '<input class="form-control nueva_cantidad' . $count . '" placeholder="Desc" type="number" value="' . $row['cantidad'] . '" id="nueva_cantidad">        
    <button class="btn btn-info p-2 update" type="button" onclick="actualizarCantidad(' . $row['id'] . ', ' . $count . ')">
    <i class="fas fa-redo-alt"></i>
    </button>';
    // total
    $sub_array[] = '<input class="form-control total nuevo_total'.$count.' separadorMiles" placeholder="Desc" type="text" value="' . $row['total'] . '" id="nuevo_total" data-guardar="' . $row['total'] . '">
    <button class="btn btn-info p-2 update" type="button" onclick="actualizarTotal(' . $row['id'] . ', ' . $count . ')">
    <i class="fas fa-redo-alt"></i>
    </button>';
    // boton eliminar
    $sub_array[] = '<button class="btn btn-danger" type="button" onclick="deleteDetalleCompra(' . $row['id'] . ')">
    <i class="fas fa-trash-alt"></i></button>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
