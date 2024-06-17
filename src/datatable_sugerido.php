<?php
include "conexion.php";

$output = array();

$sql = "SELECT SUM(l.existencia) AS existencia, p.codproducto, p.codigo, p.descripcion, p.existencia_minima, p.id_lab FROM lotes l INNER JOIN producto p ON l.id_producto = p.codproducto WHERE p.delete = 0 AND existencia < p.existencia_minima - 1 GROUP BY p.codigo, p.descripcion, l.lote";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " AND p.codigo LIKE '%" . $search_value . "%'";
    $sql .= " OR p.descripcion LIKE '%" . $search_value . "%'";
}
if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY p.descripcion desc";
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
    $id_lab = $row['id_lab'];
    $id_prod = $row['codproducto'];
    $laboratorio = mysqli_query($con, "SELECT * FROM laboratorios WHERE id = $id_lab");
    $assocLab = mysqli_fetch_assoc($laboratorio);

    $stock = mysqli_query($con, "SELECT SUM(existencia) AS existencia FROM lotes WHERE id_producto = $id_prod");
    $resultadoStock = mysqli_fetch_assoc($stock);
    $existencia = 0;
    if (!empty($resultadoStock['existencia'])) {
        $existencia = $resultadoStock['existencia'];
    }
    if ($existencia < $row['existencia_minima']) {
        $sub_array = array();
        $sub_array[] = $row['codigo'];
        $sub_array[] = substr($row['descripcion'], 0, 50);
        $sub_array[] = $assocLab['laboratorio'];
        $sub_array[] = $existencia;
        $sub_array[] = $row['existencia_minima'];
        $sub_array[] = $row['existencia_minima'] - $existencia;
        $data[] = $sub_array;
    }
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' => 0,
    'data' => $data,
);
echo json_encode($output);
