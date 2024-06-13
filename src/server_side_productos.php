<?php
require 'datatable_productos.php';
$table_data->get('producto', 'codproducto', array("codproducto", 'codigo', 'descripcion', 'id_lab', 'precio_menudeo', 'precio_blister', 'precio_global', 'precio_compra', 'existencia'));
