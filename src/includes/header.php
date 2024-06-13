<?php
if (empty($_SESSION['active'])) {
    header('Location: ../');
}
$id_user = $_SESSION['idUser'];
$usuario = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_user");
$resUser = mysqli_fetch_assoc($usuario);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <!-- <link rel="icon" type="image/png" href="../assets/img/favicon.png"> -->
    <title>
        Sinergia Post Ventas
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- index css -->
    <link rel="stylesheet" href="../assets/css/index.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />

    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="wrapper ">
        <!-- sidebar -->
        <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
            <div class="sidenav-header">
                <div class=" p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"><i class="fas fa-times mr-2 fa-2x"></i></div>
                <a class="navbar-brand m-0" href="index.php">
                    <span class="ms-1 font-weight-bold">SINERGIA POST VENTAS</span>
                </a>
            </div>
            <hr class="horizontal dark mt-0">
            <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php" id="tab-index">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-home mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Ventas</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-ventas" href="ventas.php">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-cash-register mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Nueva venta</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lista_ventas_diaria.php" id="tab-lista_ventas_diaria">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Análisis de ventas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lista_ventas_gen.php" id="tab-lista_ventas_gen">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Historial Ventas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cierres_caja.php" id="tab-cierres_caja">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-cash-register mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Cierres caja</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Compras</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="compras.php" id="tab-compras">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-shopping-cart mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Nueva Compra</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lista_compras_gen.php" id="tab-lista_compras_gen">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Historial Compras</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sugerido.php" id="tab-sugerido">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-shopping-cart mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Sugerido</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Devoluciones</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lista_devoluciones.php" id="tab-devoluciones">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Historial devoluciones</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Salidas / entradas</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="entrada_admin.php" id="tab-entrada_admin">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fab fa-product-hunt mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Entrada admin</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salida_admin.php" id="tab-salida_admin">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fab fa-product-hunt mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Salida admin</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administración</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php" id="tab-productos">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fab fa-product-hunt mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Productos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos_utilidad.php" id="tab-productos_utilidad">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fab fa-product-hunt mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Productos Existentes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tipo.php" id="tab-tipo">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fas fa-tags mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Tipos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="presentacion.php" id="tab-presentacion">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fas fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Presentación</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laboratorio.php" id="tab-laboratorio">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-flask mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Laboratorios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proveedores.php" id="tab-proveedores">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fa fa-building mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Proveedores</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Vencimientos</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proximos_vencer.php" id="tab-proximos_vencer">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-minus-circle mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Proximos a vencer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vencidos_pendientes.php" id="tab-vencidos_pendientes">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-minus-square mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Vencidos Pendientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vencidos.php" id="tab-vencidos">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-minus-square mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Historial Vencidos</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">SGC</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="temperatura_humedad.php" id="tab-temperatura_humedad">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fas fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Temp y humedad</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="limpieza_desinfeccion.php" id="tab-limpieza_desinfeccion">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fas fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Limp y desinfección</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="residuos_rh1.php" id="tab-residuos_rh1">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fas fa-list mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Residuos RH1</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">CONFIGURACIÓN</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientes.php" id="tab-clientes">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class=" fas fa-users mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Clientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios.php" id="tab-usuarios">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config.php" id="tab-config">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-cogs mr-2 fa-2x"></i>
                            </div>
                            <span class="nav-link-text ms-1">Datos Empresa</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- End Navbar -->
        <main id="main-content" class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
                <div class="container-fluid py-1 px-3">
                    <nav class="d-flex" aria-label="breadcrumb">
                        <!-- <a id="btn_sidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a> -->
                        <p class="font-weight-bolder mb-0"><?php echo $resUser['nombre']; ?></p>
                    </nav>
                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                            <!-- <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Type here...">
                            </div> -->
                        </div>
                        <ul class="navbar-nav  justify-content-end">
                            <li class="nav-item d-flex align-items-center">
                                <!-- <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                                    <i class="fa fa-user me-sm-1"></i>
                                    <span class="d-sm-inline d-none">Sign In</span>
                                </a> -->
                            </li>
                            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item px-3 d-flex align-items-center">
                                <a id="btn_sidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item px-3 d-flex align-items-center">
                                <!-- <a href="javascript:;" class="nav-link text-body p-0">
                                    <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                                </a> -->
                                <a class="fixed-plugin-button text-dark nav-link px-3 py-2">
                                    <i class="fa fa-cog py-2"> </i>
                                </a>
                            </li>
                            <li class="nav-item dropdown pe-2 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bell cursor-pointer"></i>
                                </a>
                                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md" data-toggle="modal" data-target="#exampleModalCenter" id="navbarDropdownProfile">
                                            <div class="d-flex py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1 ml-3">
                                                        <span class="font-weight-bold">Cuenta</span>
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" data-toggle="modal" data-target="#cierre_caja_modal">
                                            <div class="d-flex py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        Cerrar caja
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="salir.php">
                                            <div class="d-flex py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        Cerrar sesión
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- nav lateral derecho -->
            <div class="fixed-plugin">
                <!-- <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
                    <i class="fa fa-cog py-2"> </i>
                </a> -->
                <div class="card shadow-lg ">
                    <div class="card-header pb-0 pt-3 ">
                        <div class="float-end mt-4">
                            <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
                        <!-- End Toggle Button -->
                    </div>
                    <div class="card-body pt-sm-3 pt-0">
                        <!-- Sidebar Backgrounds -->
                        <div>
                            <h6 class="mb-0">Sidebar Colors</h6>
                        </div>
                        <a href="javascript:void(0)" class="switch-trigger background-color">
                            <div class="badge-colors my-2 text-start">
                                <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                                <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                                <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                                <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                                <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                                <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                            </div>
                        </a>
                        <!-- Sidenav Type -->
                        <div class="mt-3">
                            <h6 class="mb-0">Sidenav Type</h6>
                            <p class="text-sm">Choose between 2 different sidenav types.</p>
                        </div>
                        <div class="d-flex">
                            <button class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
                            <button class="btn bg-gradient-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
                        </div>
                        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
                        <!-- Navbar Fixed -->
                        <div class="mt-3">
                            <h6 class="mb-0">Navbar Fixed</h6>
                        </div>
                        <div class="form-check form-switch ps-0">
                            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                        </div>
                        <hr class="horizontal dark my-sm-4">
                    </div>
                </div>
            </div>


            <body class="g-sidenav-show  bg-gray-100">
                <div class="container-preloader-dot-loading" id="preload">
                    <div class="preloader-dot-loading">
                        <div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
                    </div>
                </div>
                <!-- contenedor -->
                <div class="container-fluid py-4">