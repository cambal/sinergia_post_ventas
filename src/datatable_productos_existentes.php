<?php
include "conexion.php";

$output = array();

$sql = "SELECT *, SUM(l.existencia) AS existencia FROM lotes l INNER JOIN producto p ON l.id_producto = p.codproducto GROUP BY p.codigo, p.descripcion, l.lote";

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
    $id_lab = $row['id_lab'];
    $existencia = 0;
    $laboratorio = mysqli_query($con, "SELECT * FROM laboratorios WHERE id = $id_lab");
    $assocLab = mysqli_fetch_assoc($laboratorio);

    if (!empty($row['existencia'])) {
        $existencia = $row['existencia'];
    }

    $sub_array = array();
    $sub_array[] = $row['codigo'];
    $sub_array[] = $row['descripcion'];
    $sub_array[] = $assocLab['laboratorio'];
    $sub_array[] = $row['existencia'];

    if ($row['precio_menudeo'] > 0 && $row['precio_blister'] == 0) {
        // MENUDEO
        $resultado = $row['precio_menudeo'] * $existencia;
        // 
        $toC = $row['precio_compra'] / $row['cant_global'] * $existencia;
        $toV = $resultado / $row['cant_global'] * $existencia;

        $sub_array[] = '$' . number_format($toC);
        $sub_array[] = '$' . number_format($toV);
    } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] > 0) {
        // BLISTER
        $toC = $row['precio_compra'] / $row['cant_global'] * $existencia;
        $toV = $existencia / $row['cant_blister'] * $row['precio_blister'];

        $sub_array[] = '$' . number_format($toC);
        $sub_array[] = '$' . number_format($toV);
    } else if ($row['precio_menudeo'] > 0 && $row['precio_blister'] > 0) {
        // TODO
        $toC = $row['precio_compra'] / $row['cant_global'] * $existencia;
        $toV = $row['precio_menudeo'] * $existencia;

        $sub_array[] = '$' . number_format($toC);
        $sub_array[] = '$' . number_format($toV);
    } else if ($row['precio_menudeo'] == 0 && $row['precio_blister'] == 0) {
        // SOLO TOTAL
        $toC = $row['precio_compra'] * $existencia;
        $toV = $row['precio_global'] * $existencia;

        $sub_array[] = '$' . number_format($toC);
        $sub_array[] = '$' . number_format($toV);
    }
    $sub_array[] = '$' . number_format($toV - $toC);
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
