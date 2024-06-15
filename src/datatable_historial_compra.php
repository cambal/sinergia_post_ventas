<?php
include "conexion.php";

$output = array();

$sql = "SELECT * FROM compras c INNER JOIN usuario u ON c.id_usuario = u.idusuario";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE c.fecha like '%" . $search_value . "%'";
    $sql .= " OR c.num_fac_compra like '%" . $search_value . "%'";
    $sql .= " OR c.proveedor like '%" . $search_value . "%'";
    $sql .= " OR u.nombre like '%" . $search_value . "%'";
    // $sql .= " AND d.id_usuario = $id";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY c.fecha desc";
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
    $sub_array[] =  $row['num_fac_compra'];
    $sub_array[] =  $row['proveedor'];
    $sub_array[] =  $row['nombre'];
    $sub_array[] = '$' . number_format($row['total']);
    $sub_array[] =  $row['fecha'];
    $sub_array[] =  '<a href="pdf/generar_compra.php?v=' . $row["id"] . '" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>';
    $sub_array[] =  '<a href="pdf/generar_recepcion_tecnica.php?v=' . $row["id"] . '" target="_blank" class="btn btn-info"><i class="fas fa-file-pdf"></i></a>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
