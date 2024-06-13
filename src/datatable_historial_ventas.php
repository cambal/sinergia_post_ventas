<?php
include "conexion.php";

$output = array();

$sql = "SELECT * FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto INNER JOIN ventas v WHERE d.id_venta = v.id AND d.estado = 0";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " AND v.id like '%" . $search_value . "%'";
    $sql .= " AND p.descripcion like '%" . $search_value . "%'";
    $sql .= " OR v.fecha like '%" . $search_value . "%'";
    $sql .= " AND d.id_venta = v.id AND d.estado = 0";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY v.id desc";
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
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['descripcion'];
    $sub_array[] = $row['cantidad'];
    $sub_array[] = '$' . number_format($row['total']);
    $sub_array[] = '$' . number_format($row['total'] * $row['cantidad']);
    $sub_array[] = $row['metodo_pago'];
    $sub_array[] = $row['fecha'];
    $sub_array[] = '<a href="pdf/generar.php?cl=' . $row["id_cliente"] . '&v=' . $row["id"] . '" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>';
    $sub_array[] = ' <a href="pdf/factura_electronica.php?cl=' . $row["id_cliente"] . '&v=' . $row["id"] . '" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
