<?php
include "conexion.php";

$rango_seleccionado = '+90 day';
if (!empty($_POST['rango_seleccionado'])) {
    $rango_seleccionado = htmlspecialchars($_POST['rango_seleccionado']);;
}
$output = array();

$sql = "SELECT * FROM lotes";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

// if (isset($_POST['search']['value'])) {
//     $search_value = $_POST['search']['value'];
//     $sql .= " AND p.codigo LIKE '%" . $search_value . "%'";
//     $sql .= " OR p.descripcion LIKE '%" . $search_value . "%'";
//     // $sql .= " GROUP BY codigo";
// }
// if (isset($_POST['order'])) {
//     $column_name = $_POST['order'][0]['column'];
//     $order = $_POST['order'][0]['dir'];
//     $sql .= " ORDER BY " . $column_name . " " . $order . "";
// } else {
//     $sql .= " ORDER BY codproducto desc";
// }

// if ($_POST['length'] != -1) {
//     $start = $_POST['start'];
//     $length = $_POST['length'];
//     $sql .= " LIMIT  " . $start . ", " . $length;
// }

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();

while ($row = mysqli_fetch_assoc($query)) {
    $id_producto = $row['id_producto'];
    $sqlP = "SELECT * FROM producto WHERE codproducto = $id_producto";
    $queryP = mysqli_query($con, $sqlP);
    $arrayData = mysqli_fetch_assoc($queryP);

    $hoy = date('Y-m-d');
    $vencimiento = $row['vencimiento'];
    $rango = strtotime($rango_seleccionado, strtotime($hoy));
    $rango = date('Y-m-d', $rango);
    if ($vencimiento <= $rango) {
    $sub_array = array();
    $sub_array[] = $arrayData['codigo'];
    $sub_array[] = $row['lote'];
    $sub_array[] = substr($arrayData['descripcion'], 0, 50);
    $sub_array[] = $row['vencimiento'];
    $data[] = $sub_array;
    }
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo json_encode($output);
