<?php
require "../conexion.php";
$hoy = date('Y-m-d');
session_start();
include_once "includes/header.php";
?>


<div class="card">
    <div class="card-header">
        Limpieza y desinfección
    </div>
    <div class="card-body">
        <a id="download_xlsx" class="btn btn-success text-white">Exportar a excel</a>
        <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Agregar</button>
        <div class="table-responsive">
            <table class="table table-hover" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>Area aseo</th>
                        <th>Solución sanizante</th>
                        <th>Usuario</th>
                        <th>fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../conexion.php";

                    $query = mysqli_query($conexion, "SELECT * FROM limpieza_desinfeccion l INNER JOIN usuario u ON l.id_usuario = u.idusuario");
                    $result = mysqli_num_rows($query);
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?php echo $data['area_aseo']; ?></td>
                                <td><?php echo $data['solucion_sanizante']; ?></td>
                                <td><?php echo $data['nombre']; ?></td>
                                <td><?php echo $data['fecha']; ?></td>
                                <td>
                                    <form action="eliminar_limpieza_desinfeccion.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                                        <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                    </form>
                                    <a class="btn btn-info" target="_blank" href="pdf/limpieza_desinfeccion.php?v=<?php echo $data['id']; ?>">
                                        <i class='fas fa-info'></i>
                                    </a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>

            </table>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Limpieza y desinfección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body position-relative">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <input type="date" id="fecha_limpieza" class="form-control" value="<?php echo $hoy; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="area_aseo">Area aseo</label>
                    <select id="area_aseo" class="form-control" name="area_aseo" required>
                        <option value="Estantes">Estantes</option>
                        <option value="Vitrinas">Vitrinas</option>
                        <option value="Paredes">Paredes</option>
                        <option value="Pisos">Pisos</option>
                        <option value="Banos">Baños</option>
                        <option value="Inyectologia">Inyectología</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="solucion_sanizante">Solución sanizante</label>
                    <select id="solucion_sanizante" class="form-control" name="solucion_sanizante" required>
                        <option value="Hipoclorito">Hipoclorito de sodio</option>
                        <option value="Detergentes">Detergentes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="usuario_limpieza">Usuario</label>
                    <select id="usuario_limpieza" class="form-control" name="usuario_limpieza" required>
                        <?php
                        $query_lab = mysqli_query($conexion, "SELECT * FROM usuario ORDER BY nombre ASC");
                        while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                            <option class="p-3" value="<?php echo $datos['idusuario'] ?>"><?php echo $datos['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" value="Limpiar" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                <input type="submit" value="Guardar" onclick="guardarLimpezaDesinfeccion()" class="btn btn-primary" id="btnAccion">
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>