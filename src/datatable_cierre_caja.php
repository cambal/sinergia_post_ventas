<?php
include "conexion.php";

$output = array();

$sql = "SELECT * FROM cierre_caja c INNER JOIN usuario u ON c.id_usuario = u.idusuario";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE id like '%" . $search_value . "%'";
    $sql .= " OR nombre like '%" . $search_value . "%'";
    $sql .= " OR fecha_cierre like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY id desc";
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
    $sub_array[] = $row['nombre'];
    $sub_array[] = '$' . number_format($row['efectivo']);
    $sub_array[] = '$' . number_format($row['nequi']);
    $sub_array[] = '$' . number_format($row['daviplata']);
    $sub_array[] = '$' . number_format($row['tarjeta']);
    $sub_array[] = '$' . number_format($row['efectivo_actual']);
    $sub_array[] = '$' . number_format($row['sobrante']);
    $sub_array[] = '$' . number_format($row['cuanto_pagaste']);
    $sub_array[] = '$' . number_format($row['efectivo_actual'] - $row['cuanto_pagaste']);
    $sub_array[] = $row['fecha_cierre'];
    $sub_array[] = '<a href="pdf/generar_cierre_caja.php?v=' . $row['id'] . '&efec=' . $row['efectivo'] . '&nequi=' . $row['nequi'] . '&daviplata=' . $row['daviplata'] . '&tarjeta=' . $row['tarjeta'] . '&efec_fis=' . $row['efectivo_actual'] . '&fecha=' . $row['fecha_cierre'] . '&sobr=' . $row['sobrante'] . '&cuanto_pagaste=' . $row['cuanto_pagaste'] . '&obs=' . $row['observacion'] . '&creando=no"   class="btn btn-info" target="_blank"><i class="fas fa-file-pdf"></i></a>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
