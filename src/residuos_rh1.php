<?php
require "../conexion.php";
$hoy = date('Y-m-d');
session_start();
include_once "includes/header.php";
?>


<div class="card">
    <div class="card-header">
        Residuos RH1
    </div>
    <div class="card-body">
        <a id="download_xlsx" class="btn btn-success text-white">Exportar a excel</a>
        <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Agregar</button>
        <div class="table-responsive">
            <table class="table table-hover" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>fecha</th>
                        <th>biodegradables</th>
                        <th>reciclables</th>
                        <th>inertes</th>
                        <th>ordinarios</th>
                        <th>biosanitarios</th>
                        <th>anatomopatologicos</th>
                        <th>cortopunzantes</th>
                        <th>deanimales</th>
                        <th>fuentes_abiertas</th>
                        <th>fuentes_cerradas</th>
                        <th>farmacos</th>
                        <th>citotoxicos</th>
                        <th>metales_pesados</th>
                        <th>reactivos</th>
                        <th>contenedores_presurizados</th>
                        <th>aceites_usados</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../conexion.php";

                    $query = mysqli_query($conexion, "SELECT * FROM residuos");
                    $result = mysqli_num_rows($query);
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?php echo $data['fecha']; ?></td>
                                <td><?php echo $data['biodegradables']; ?></td>
                                <td><?php echo $data['reciclables']; ?></td>
                                <td><?php echo $data['inertes']; ?></td>
                                <td><?php echo $data['ordinarios']; ?></td>
                                <td><?php echo $data['biosanitarios']; ?></td>
                                <td><?php echo $data['anatomopatologicos']; ?></td>
                                <td><?php echo $data['cortopunzantes']; ?></td>
                                <td><?php echo $data['deanimales']; ?></td>
                                <td><?php echo $data['fuentes_abiertas']; ?></td>
                                <td><?php echo $data['fuentes_cerradas']; ?></td>
                                <td><?php echo $data['farmacos']; ?></td>
                                <td><?php echo $data['citotoxicos']; ?></td>
                                <td><?php echo $data['metales_pesados']; ?></td>
                                <td><?php echo $data['reactivos']; ?></td>
                                <td><?php echo $data['contenedores_presurizados']; ?></td>
                                <td><?php echo $data['aceites_usados']; ?></td>
                                <td>
                                    <form action="eliminar_residuos.php?id=<?php echo $data['id_resi']; ?>" method="post" class="confirmar d-inline">
                                        <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                    </form>
                                    <a class="btn btn-info" target="_blank" href="pdf/residuos.php?v=<?php echo $data['id_resi']; ?>">
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Residuos RH1</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body position-relative">
                <div class="row">
                    <div class="col-md-4 border p-2">
                        <input type="date" id="fecha_residuos" class="form-control" value="<?php echo $hoy; ?>">
                        <div class="border mt-2 p-2">
                            <h6 class="mt-4 bg-dark text-white p-1">Residuos no peligrosos</h6>
                            <div class="form-group">
                                <label for="">Biodegradables</label>
                                <input type="number" id="Biodegradables" class="form-control ml-3" value="0.0">
                            </div>
                            <div class="form-group">
                                <label for="">Reciclables</label>
                                <input type="number" id="Reciclables" class="form-control ml-3" value="0.0">
                            </div>
                            <div class="form-group">
                                <label for="">Inertes</label>
                                <input type="number" id="Inertes" class="form-control ml-3" value="0.0">
                            </div>
                            <div class="form-group">
                                <label for="">Ordinarios</label>
                                <input type="number" id="Ordinarios" class="form-control ml-3" value="0.0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 border p-2">
                        <h6 class="mt-4 bg-dark text-white p-1">Residuos peligrosos</h6>
                        <div class="row">
                            <div class="col-md-6 p-2">
                                <div class="border p-2">
                                    <h6 class="mt-4 bg-dark text-white p-1">Infecciosos o de riesgo biologico</h6>
                                    <div class="form-group">
                                        <label for="">Biosanitarios</label>
                                        <input type="number" id="Biosanitarios" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Anatomopatologicos</label>
                                        <input type="number" id="Anatomopatologicos" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Cortopunzantes</label>
                                        <input type="number" id="Cortopunzantes" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">DeAnimales</label>
                                        <input type="number" id="DeAnimales" class="form-control ml-3" value="0.0">
                                    </div>
                                </div>
                                <div class="border p-2 mt-2">
                                    <h6 class="mt-4 bg-dark text-white p-1">Radioactivos</h6>
                                    <div class="form-group">
                                        <label for="">Fuentes abiertas</label>
                                        <input type="number" id="Fuentes_abiertas" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Fuentes cerradas</label>
                                        <input type="number" id="Fuentes_cerradas" class="form-control ml-3" value="0.0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 p-2">
                                <div class="border p-2">
                                    <h6 class="mt-4 bg-dark text-white p-1">Quimicos</h6>
                                    <div class="form-group">
                                        <label for="">Farmacos</label>
                                        <input type="number" id="Farmacos" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Citotoxicos</label>
                                        <input type="number" id="Citotoxicos" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Metales pesados</label>
                                        <input type="number" id="Metales_pesados" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Reactivos</label>
                                        <input type="number" id="Reactivos" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Contenedores presurizados</label>
                                        <input type="number" id="Contenedores_presurizados" class="form-control ml-3" value="0.0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Aceites usados</label>
                                        <input type="number" id="Aceites_usados" class="form-control ml-3" value="0.0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Guardar" onclick="guardarResiduos()" class="btn btn-primary" id="btnAccion">
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>