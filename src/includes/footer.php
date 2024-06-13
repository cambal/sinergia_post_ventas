<footer class="footer pt-3  ">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    made <i class="fa fa-heart"></i> by
                    <a href="https://camibal.github.io/" class="font-weight-bold" target="_blank">Camilo Ballesteros</a>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About
                            Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                    </li>
                </ul> -->
            </div>
        </div>
    </div>
</footer>
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cambiar contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frmPass">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="actual"> Contraseña Actual</label>
                        <input id="actual" class="form-control" type="password" name="actual" placeholder="Contraseña actual" required>
                    </div>
                    <div class="form-group">
                        <label for="nueva"> Contraseña Nueva</label>
                        <input id="nueva" class="form-control" type="password" name="nueva" placeholder="Contraseña nueva" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-block" type="button" onclick="btnCambiar(event)">Cambiar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</body>
</main>

<?php include "./includes/cierre_caja.php"; ?>

<script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/material-dashboard.js" type="text/javascript"></script>
<script src="../assets/js/bootstrap-notify.js"></script>
<script src="../assets/js/arrive.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>
<script src="../assets/js/jquery-ui/jquery-ui.min.js"></script>
<script src="../assets/js/chart.min.js"></script>
<script src="../assets/js/funciones.js"></script>
<script src="../assets/js/lector.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/excellentexport@3.4.3/dist/excellentexport.min.js"></script>

<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>

<script>
    /// Url actual
    let url = window.location.href;
    /// Elementos de li
    const tabs = ["index", "ventas", "lista_ventas_diaria", "lista_ventas_gen", "cierres_caja", "compras", "lista_compras_gen", "sugerido", "devoluciones", "productos", "productos_utilidad", "tipo", "presentacion", "laboratorio", "proveedores", "vencidos_pendientes", "vencidos", "proximos_vencer", "clientes", "usuarios", "config", "temperatura_humedad", "limpieza_desinfeccion", "residuos_rh1", "salida_admin", "entrada_admin"];
    tabs.forEach(e => {
        /// Agregar .php y ver si lo contiene en la url
        if (url.indexOf(e + ".php") !== -1) {
            /// Agregar tab- para hacer que coincida la Id
            // alert("tab-" + e)
            setActive("tab-" + e);
        }

    });

    /// Funcion que asigna la clase active
    function setActive(id) {
        // alert(id)
        document.querySelector(".nav-link").classList.remove("class", "active");
        document.getElementById(id).classList.add("active");
    }
</script>
<script>
    // let sidenav_main = document.getElementById("sidenav-main");
    // sidenav_main.classList.add("d-none");
    $(document).ready(function() {
        if (localStorage.getItem("sidenav")) {
            // abierto
            setTimeout(function() {
                $("#main-content").addClass("m-0");
            }, 250);
            $("#sidenav-main").fadeOut(250);
        } else {
            // cerrado
            $("#main-content").removeClass("m-0");
            setTimeout(function() {
                $("#sidenav-main").fadeIn("slow");
            }, 250);
        }
    });
    $("#btn_sidenav").click(function() {
        if ($("#sidenav-main").css("display") !== "none") {
            // abierto
            setTimeout(function() {
                $("#main-content").addClass("m-0");
            }, 250);
            $("#sidenav-main").fadeOut(250);
            localStorage.setItem("sidenav", true);
        } else {
            // cerrado
            $("#main-content").removeClass("m-0");
            setTimeout(function() {
                $("#sidenav-main").fadeIn("slow");
            }, 250);
            localStorage.removeItem("sidenav");
        }
    });
</script>

</body>

</html>