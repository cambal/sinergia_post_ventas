<?php
require "../conexion.php";
$hoy = date('Y-m-d');
session_start();
include_once "includes/header.php";
?>


<div class="card">
    <div class="card-header">
        Temperatura y humedad
    </div>
    <div class="card-body">
        <a id="download_xlsx" class="btn btn-success text-white">Exportar a excel</a>
        <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Agregar</button>
        <div class="table-responsive">
            <table class="table table-hover" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>temperatura</th>
                        <th>humedad</th>
                        <th>hora</th>
                        <th>fecha</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../conexion.php";

                    $query = mysqli_query($conexion, "SELECT * FROM temperatura_humedad ORDER BY fecha ASC");
                    $result = mysqli_num_rows($query);
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {
                            if ($data['hora'] == '9') {
                                $hour = '9am';
                            } else if ($data['hora'] == '1') {
                                $hour = '1pm';
                            } else if ($data['hora'] == '5') {
                                $hour = '5pm';
                            }
                    ?>
                            <tr>
                                <td><?php echo $data['temperatura']; ?>°C</td>
                                <td><?php echo $data['humedad']; ?>%</td>
                                <td><?php echo $hour; ?></td>
                                <td><?php echo $data['fecha']; ?></td>
                                <td>
                                    <form action="eliminar_temperatura_humedad.php?id=<?php echo $data['id_temp']; ?>" method="post" class="confirmar d-inline">
                                        <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                    </form>
                                    <a href="./pdf/temperatura_humedad.php?v=<?php echo $data['id_temp']; ?>" class="btn btn-info" target="_blank"><i class="fa fa-info"></i> </a>
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
                <h5 class="modal-title" id="exampleModalLabel">Temperatura y humedad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body position-relative">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <input type="date" id="fecha_tomada" class="form-control" value="<?php echo $hoy; ?>" onchange="FechaSel()">
                    </div>
                </div>
                <div class="row mt-5">
                    <!-- col -->
                    <div class="col-md-4">
                        <div class="temp">
                            <h6 class="text-center">Registro 9 am</h6>
                            <h6 class="text-left">Temperatura</h6>
                            <form class="form-temp">
                                <div id="numero-value">
                                    0°C
                                </div>
                                <input name="numero" type="range" min="-50" max="50" list="marcas" value="0" step="2" class="w-100" id="temp_9am" onmousemove="actualizar();" />
                                <div class="row justify-content-center">
                                    <div class="col-1 d-flex justify-content-center">-50</div>
                                    <div class="col-1 d-flex justify-content-center">-40</div>
                                    <div class="col-1 d-flex justify-content-center">-30</div>
                                    <div class="col-1 d-flex justify-content-center">-20</div>
                                    <div class="col-1 d-flex justify-content-center">-10</div>
                                    <div class="col-1 d-flex justify-content-center">0</div>
                                    <div class="col-1 d-flex justify-content-center">10</div>
                                    <div class="col-1 d-flex justify-content-center">20</div>
                                    <div class="col-1 d-flex justify-content-center">30</div>
                                    <div class="col-1 d-flex justify-content-center">40</div>
                                    <div class="col-1 d-flex justify-content-center">50</div>
                                </div>
                            </form>
                        </div>
                        <div class="temp">
                            <h6 class="text-left mt-3">Humedad</h6>
                            <form class="form-temp">
                                <div id="numero-value-hum">
                                    0%
                                </div>
                                <input name="numero_humedad" type="range" min="0" max="100" list="marcas" value="20" step="2" class="w-100" id="humedad_9am" onmousemove="actualizar();" />
                                <div class="row justify-content-center">
                                    <div class="col-1 d-flex justify-content-center">0</div>
                                    <div class="col-1 d-flex justify-content-center">10</div>
                                    <div class="col-1 d-flex justify-content-center">20</div>
                                    <div class="col-1 d-flex justify-content-center">30</div>
                                    <div class="col-1 d-flex justify-content-center">40</div>
                                    <div class="col-1 d-flex justify-content-center">50</div>
                                    <div class="col-1 d-flex justify-content-center">60</div>
                                    <div class="col-1 d-flex justify-content-center">70</div>
                                    <div class="col-1 d-flex justify-content-center">80</div>
                                    <div class="col-1 d-flex justify-content-center">90</div>
                                    <div class="col-1 d-flex justify-content-center">100</div>
                                </div>
                            </form>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="btn_9am" onclick="guardarTemperaturaHumedad('9')">Guardar 9 am</button>
                    </div>
                    <!-- col -->
                    <div class="col-md-4">
                        <div class="temp">
                            <h6 class="text-center">Registro 1 pm</h6>
                            <h6 class="text-left">Temperatura</h6>
                            <form class="form-temp">
                                <div id="numero-value-2">
                                    0°C
                                </div>
                                <input name="numero2" type="range" min="-50" max="50" list="marcas" value="0" step="2" class="w-100" id="temp_1pm" onmousemove="actualizar();" />
                                <div class="row justify-content-center">
                                    <div class="col-1 d-flex justify-content-center">-50</div>
                                    <div class="col-1 d-flex justify-content-center">-40</div>
                                    <div class="col-1 d-flex justify-content-center">-30</div>
                                    <div class="col-1 d-flex justify-content-center">-20</div>
                                    <div class="col-1 d-flex justify-content-center">-10</div>
                                    <div class="col-1 d-flex justify-content-center">0</div>
                                    <div class="col-1 d-flex justify-content-center">10</div>
                                    <div class="col-1 d-flex justify-content-center">20</div>
                                    <div class="col-1 d-flex justify-content-center">30</div>
                                    <div class="col-1 d-flex justify-content-center">40</div>
                                    <div class="col-1 d-flex justify-content-center">50</div>
                                </div>
                            </form>
                        </div>
                        <div class="temp">
                            <h6 class="text-left mt-3">Humedad</h6>
                            <form class="form-temp">
                                <div id="numero-value-hum-2">
                                    0%
                                </div>
                                <input name="numero_humedad_2" type="range" min="0" max="100" list="marcas" value="20" step="2" class="w-100" id="humedad_1pm" onmousemove="actualizar();" />
                                <div class="row justify-content-center">
                                    <div class="col-1 d-flex justify-content-center">0</div>
                                    <div class="col-1 d-flex justify-content-center">10</div>
                                    <div class="col-1 d-flex justify-content-center">20</div>
                                    <div class="col-1 d-flex justify-content-center">30</div>
                                    <div class="col-1 d-flex justify-content-center">40</div>
                                    <div class="col-1 d-flex justify-content-center">50</div>
                                    <div class="col-1 d-flex justify-content-center">60</div>
                                    <div class="col-1 d-flex justify-content-center">70</div>
                                    <div class="col-1 d-flex justify-content-center">80</div>
                                    <div class="col-1 d-flex justify-content-center">90</div>
                                    <div class="col-1 d-flex justify-content-center">100</div>
                                </div>
                            </form>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="btn_1pm" onclick="guardarTemperaturaHumedad('1')">Guardar 1 pm</button>
                    </div>
                    <!-- col -->
                    <div class="col-md-4">
                        <div class="temp">
                            <h6 class="text-center">Registro 5 pm</h6>
                            <h6 class="text-left">Temperatura</h6>
                            <form class="form-temp">
                                <div id="numero-value-3">
                                    0°C
                                </div>
                                <input name="numero3" type="range" min="-50" max="50" list="marcas" value="0" step="2" class="w-100" id="temp_5pm" onmousemove="actualizar();" />
                                <div class="row justify-content-center">
                                    <div class="col-1 d-flex justify-content-center">-50</div>
                                    <div class="col-1 d-flex justify-content-center">-40</div>
                                    <div class="col-1 d-flex justify-content-center">-30</div>
                                    <div class="col-1 d-flex justify-content-center">-20</div>
                                    <div class="col-1 d-flex justify-content-center">-10</div>
                                    <div class="col-1 d-flex justify-content-center">0</div>
                                    <div class="col-1 d-flex justify-content-center">10</div>
                                    <div class="col-1 d-flex justify-content-center">20</div>
                                    <div class="col-1 d-flex justify-content-center">30</div>
                                    <div class="col-1 d-flex justify-content-center">40</div>
                                    <div class="col-1 d-flex justify-content-center">50</div>
                                </div>
                            </form>
                        </div>
                        <div class="temp">
                            <h6 class="text-left mt-3">Humedad</h6>
                            <form class="form-temp">
                                <div id="numero-value-hum-3">
                                    0%
                                </div>
                                <input name="numero_humedad_3" type="range" min="0" max="100" list="marcas" value="20" step="2" class="w-100" id="humedad_5pm" onmousemove="actualizar();" />
                                <div class="row justify-content-center">
                                    <div class="col-1 d-flex justify-content-center">0</div>
                                    <div class="col-1 d-flex justify-content-center">10</div>
                                    <div class="col-1 d-flex justify-content-center">20</div>
                                    <div class="col-1 d-flex justify-content-center">30</div>
                                    <div class="col-1 d-flex justify-content-center">40</div>
                                    <div class="col-1 d-flex justify-content-center">50</div>
                                    <div class="col-1 d-flex justify-content-center">60</div>
                                    <div class="col-1 d-flex justify-content-center">70</div>
                                    <div class="col-1 d-flex justify-content-center">80</div>
                                    <div class="col-1 d-flex justify-content-center">90</div>
                                    <div class="col-1 d-flex justify-content-center">100</div>
                                </div>
                            </form>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="btn_5pm" onclick="guardarTemperaturaHumedad('5')">Guardar 5 pm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function actualizar() {
        // rango
        let rango = document.getElementsByName('numero')[0];
        let rango2 = document.getElementsByName('numero2')[0];
        let rango3 = document.getElementsByName('numero3')[0];
        let rango_humedad = document.getElementsByName('numero_humedad')[0];
        let rango_humedad_2 = document.getElementsByName('numero_humedad_2')[0];
        let rango_humedad_3 = document.getElementsByName('numero_humedad_3')[0];
        // valor
        let valor = rango.value;
        let valor2 = rango2.value;
        let valor3 = rango3.value;
        let valor_hum = rango_humedad.value;
        let valor_hum_2 = rango_humedad_2.value;
        let valor_hum_3 = rango_humedad_3.value;
        // campo
        let campo = document.getElementById('numero-value');
        let campo2 = document.getElementById('numero-value-2');
        let campo3 = document.getElementById('numero-value-3');
        let campo_hum = document.getElementById('numero-value-hum');
        let campo_hum_2 = document.getElementById('numero-value-hum-2');
        let campo_hum_3 = document.getElementById('numero-value-hum-3');
        // inner html
        campo.innerHTML = valor + '°C';
        campo2.innerHTML = valor2 + '°C';
        campo3.innerHTML = valor3 + '°C';
        campo_hum.innerHTML = valor_hum + '%';
        campo_hum_2.innerHTML = valor_hum_2 + '°%';
        campo_hum_3.innerHTML = valor_hum_3 + '%';
    }
</script>
<?php include_once "includes/footer.php"; ?>

<script>
    window.addEventListener("load", function() {
        initBtn();
    });

    function initBtn() {
        actualizar();
        let fecha = new Date();
        let horas = fecha.getHours();
        if (horas < 9) {
            $("#btn_9am").prop("disabled", true);
        } else if (horas < 13) {
            $("#btn_1pm").prop("disabled", true);
            $("#btn_5pm").prop("disabled", true);
        } else if (horas < 17) {
            $("#btn_5pm").prop("disabled", true);
        }
    }

    function FechaSel() {
        let fecha_tomada = $("#fecha_tomada").val();
        let fecha = new Date();
        console.log(fecha.getDay());
        let año = fecha.getFullYear();
        let mes = fecha.getMonth() + 1;
        let dia = fecha.getDate();
        let horas = fecha.getHours();
        let format = '' + año + '-' + mes + '-' + dia + '';
        if (format < fecha_tomada) {
            $("#btn_9am").prop("disabled", true);
            $("#btn_1pm").prop("disabled", true);
            $("#btn_5pm").prop("disabled", true);
        } else if (format != fecha_tomada) {
            $("#btn_9am").prop("disabled", false);
            $("#btn_1pm").prop("disabled", false);
            $("#btn_5pm").prop("disabled", false);
        } else if (format == fecha_tomada) {
            initBtn();
        }
    }
</script>