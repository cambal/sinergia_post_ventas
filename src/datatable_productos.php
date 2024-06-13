<?php
include "conexion.php";

$output = array();

$sql = "SELECT * FROM producto p INNER JOIN laboratorios l ON p.id_lab = l.id WHERE `delete` = 0";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " AND p.codigo LIKE '%" . $search_value . "%'";
    $sql .= " OR p.descripcion LIKE '%" . $search_value . "%'";
    $sql .= " AND p.delete = 0";
}
if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY p.codigo desc";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();

while ($row = mysqli_fetch_assoc($query)) {
    $id_prod = $row['codproducto'];
    $stock = mysqli_query($con, "SELECT SUM(existencia) AS existencia FROM lotes WHERE id_producto = $id_prod");
    $resultadoStock = mysqli_fetch_assoc($stock);
    $existencia = 0;
if(!empty($resultadoStock['existencia'])){
    $existencia = $resultadoStock['existencia'];
}
    $sub_array = array();
    $sub_array[] = $row['codigo'];
    $sub_array[] = $row['descripcion'];
    $sub_array[] = $row['laboratorio'];
    $sub_array[] = '$' . number_format($row['precio_menudeo']);
    $sub_array[] = '$' . number_format($row['precio_blister']);
    $sub_array[] = '$' . number_format($row['precio_global']);
    $sub_array[] = '$' . number_format($row['precio_compra']);
    $sub_array[] = $existencia;
    $sub_array[] = ' <button type="button" onclick="editarProducto(' . $row['codproducto'] . ')" data-toggle="modal" data-target="#exampleModal" class="btn btn-info"><i class="fas fa-edit"></i>
    </button>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
