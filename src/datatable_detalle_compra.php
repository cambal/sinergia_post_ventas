<?php
include "conexion.php";

// $id = $_SESSION['idUser'];

$output = array();

$sql = "SELECT d.precio_menudeo_c, d.precio_blister_c, d.lote_compra, d.vencimiento_compra, d.total, d.id, d.cantidad, d.precio_venta, d.precio_c, p.codproducto, p.descripcion, p.codigo, p.cant_global FROM detalle_temp_compra d INNER JOIN producto p ON d.id_producto = p.codproducto";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE p.descripcion like '%" . $search_value . "%'";
    $sql .= " OR p.codigo like '%" . $search_value . "%'";
    // $sql .= " AND d.id_usuario = $id";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY d.id desc";
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
    $sub_array[] =  $row['codigo'];
    $sub_array[] = $row['descripcion'];
    $sub_array[] = $row['lote_compra'];
    $sub_array[] = $row['vencimiento_compra'];
    $sub_array[] = '$' . number_format($row['precio_menudeo_c']);
    $sub_array[] = '$' . number_format($row['precio_blister_c']);
    $sub_array[] = '<input class="form-control nuevo_precio_compra' . $count . '" placeholder="Desc" type="hidden" value="' . $row["precio_c"] . '" id="nuevo_precio_compra"disabled><input type="text" class="form-control" value="' . '$' . number_format($row["precio_c"]) . '"disabled>';
    $sub_array[] = '$' . number_format($row['precio_venta']);
    // 
    $sub_array[] = '<input type="number" class="form-control nueva_cantidad' . $count . '" value="' . $row['cantidad'] . '" /> <button class="btn btn-info p-2 update" type="button" onclick="actualizarCantidad(' . $row['id'] . ', ' . $count . ')">
                <i class="fas fa-redo-alt"></i>
                </button>';
    // 
    $sub_array[] = '<input type="text" class="form-control total_total nuevo_total' . $count . '" id="total_' . $count . '" value="' . $row["total"] . '" /><br> <button class="btn btn-info p-2 update" type="button" onclick="actualizarTotal(' . $row['id'] . ', ' . $count . ')">
                <i class="fas fa-redo-alt"></i>
                </button>';
    $sub_array[] = '$' . number_format($row["total"]);
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
