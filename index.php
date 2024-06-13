<?php
session_start();
if (!empty($_SESSION['active'])) {
  header('location: src/index.php');
} else {
  if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
      $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Ingrese usuario y contraseña
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
      require_once "conexion.php";
      $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
      $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
      $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$clave'");
      mysqli_close($conexion);
      $resultado = mysqli_num_rows($query);
      if ($resultado > 0) {
        $dato = mysqli_fetch_array($query);
        $_SESSION['active'] = true;
        $_SESSION['idUser'] = $dato['idusuario'];
        $_SESSION['nombre'] = $dato['nombre'];
        $_SESSION['user'] = $dato['usuario'];
        header('Location: src/');
      } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Contraseña incorrecta
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        session_destroy();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <!-- <link rel="icon" type="image/png" href="assets/img/favicon.png"> -->
  <title>
Sinergia post ventas
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
        <div class="container-fluid pe-0">
          <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="pages/dashboard.html">
          Sinergia post ventas
          </a>
          <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
    </div>
  </div>
</div>
<section>
  <div class="page-header min-vh-75">
    <div class="container">
      <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
          <div class="card card-plain mt-8">
            <div class="card-header pb-0 text-left bg-transparent">
              <h3 class="font-weight-bolder text-info text-gradient">Bienvenido</h3>
              <p class="mb-0">Ingresa tu correo y contraseña</p>
              <p class="mb-0"> <?php echo (isset($alert)) ? $alert : ''; ?></p>
            </div>
            <div class="card-body">
              <form role="form" action="" method="post">
                <label>Usuario</label>
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Ingrese correo" aria-label="Email" name="usuario" id="exampleInputEmail1" aria-describedby="email-addon">
                </div>
                <label>Contraseña</label>
                <div class="mb-3">
                  <input type="password" class="form-control" id="exampleInputPassword1" name="clave" placeholder="Ingrese contraseña" aria-label="Password" aria-describedby="password-addon">
                </div>
                <!-- <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                  <label class="form-check-label" for="rememberMe">Remember me</label>
                </div> -->
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Iniciar sesión</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('assets/img/curved-images/curved6.jpg')"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<footer class="footer py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
        <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
          <span class="text-lg fab fa-dribbble"></span>
        </a>
        <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
          <span class="text-lg fab fa-twitter"></span>
        </a>
        <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
          <span class="text-lg fab fa-instagram"></span>
        </a>
        <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
          <span class="text-lg fab fa-pinterest"></span>
        </a>
        <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
          <span class="text-lg fab fa-github"></span>
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-8 mx-auto text-center mt-1">
        <p class="mb-0 text-secondary">
          Copyright © <script>
            document.write(new Date().getFullYear())
          </script> by Camilo Ballesteros
        </p>
      </div>
    </div>
  </div>
</footer>
</body>

</html>