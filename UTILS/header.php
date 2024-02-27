<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}
include "functions.php";
include "../../DATOS/conexion/conexion.php";
// datos Empresa
$dni = '';
$nombre_empresa = '';
$razonSocial = '';
$emailEmpresa = '';
$telEmpresa = '';
$dirEmpresa = '';
$igv = '';

$query_empresa = mysqli_query($conexion, "SELECT * FROM configuracion");
$row_empresa = mysqli_num_rows($query_empresa);
if ($row_empresa > 0) {
	if ($infoEmpresa = mysqli_fetch_assoc($query_empresa)) {
		$dni = $infoEmpresa['dni'];
		$nombre_empresa = $infoEmpresa['nombre'];
		$razonSocial = $infoEmpresa['razon_social'];
		$telEmpresa = $infoEmpresa['telefono'];
		$emailEmpresa = $infoEmpresa['email'];
		$dirEmpresa = $infoEmpresa['direccion'];
		$igv = $infoEmpresa['igv'];
	}
}
$query_data = mysqli_query($conexion, "CALL data();");
$result_data = mysqli_num_rows($query_data);
if ($result_data > 0) {
	$data = mysqli_fetch_assoc($query_data);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../../PRESENTACION/assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="../../PRESENTACION/assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>
		Sistema de Venta
	</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<!-- CSS Files -->
	<link href="../../PRESENTACION/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../../PRESENTACION/assets/css/now-ui-dashboard.min.css" rel="stylesheet" />
</head>

<body class=""  data-color="blue" style="background: rgb(69,58,255);background: linear-gradient(90deg, rgba(69,58,255,1) 0%, rgba(109,109,240,1) 35%, rgba(0,212,255,1) 100%);">
	<div class="wrapper" >
		<div class="sidebar" data-color="blue" style="background: linear-gradient(90deg, rgba(69,58,255,1) 0%, rgba(109,109,240,1) 35%, rgba(0,212,255,1) 100%);color:white;">

			<div class="logo">
			<a href="../../PRESENTACION/views/index.php" class="simple-text logo-normal" style="font-size:24px;">
			<center><div style="overflow: hidden; height: 140px;">
                    <img src="../../PRESENTACION/img/logowhite.png" class="" style="width: 100%; height: 100%; object-fit: cover;margin-top:0%;">
                </div></center>
				</a>
			</div>
			<div class="sidebar-wrapper" id="sidebar-wrapper">
				<ul class="nav" style="font-size:24px;">
					<li class="">
						<a href="../../PRESENTACION/views/nueva_venta.php">
							<i class="now-ui-icons design_app"></i>
							<p>Ventas</p>
						</a>
					</li>
					<li>
						<a href="../../PRESENTACION/views/lista_productos.php">
							<i class="now-ui-icons education_atom"></i>
							<p>Productos</p>
						</a>
					</li>
					<li>
						<a href="../../PRESENTACION/views/lista_cliente.php">
							<i class="now-ui-icons location_map-big"></i>
							<p>Clientes</p>
						</a>
					</li>
					<li>
						<a href="../../PRESENTACION/views/lista_usuarios.php">
							<i class="now-ui-icons ui-1_bell-53"></i>
							<p>Usuarios</p>
						</a>
					</li>
					<li>
						<a href="../../PRESENTACION/views/lista_proveedor.php">
							<i class="now-ui-icons design_bullet-list-67"></i>
							<p>Proveedor</p>
						</a>
					</li>

				</ul>
			</div>
		</div>
		<div class="main-panel" id="main-panel">
			<!-- Navbar -->
			<nav class="navbar navbar-expand-lg navbar-dark navbar-absolute" style="background: rgb(218,255,58);
background: linear-gradient(287deg, rgba(218,255,58,1) 0%, rgba(0,212,255,1) 30%, rgba(0,87,233,1) 100%);">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<div class="navbar-toggle">
							<button type="button" class="navbar-toggler">
								<span class="navbar-toggler-bar bar1"></span>
								<span class="navbar-toggler-bar bar2"></span>
								<span class="navbar-toggler-bar bar3"></span>
							</button>
						</div>
						<a class="navbar-brand" href="#"><?php echo fechaPeru(); ?></a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navigation">
						<ul class="navbar-nav">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="now-ui-icons location_world"></i>
									<p>
										<span class="d-lg-none d-md-block">Some Actions</span>
									</p>
								</a>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="#"><?php echo $_SESSION['user']; ?></a>
									<?php if ($_SESSION['rol'] == 1) {
										$rol = "Administrador";
									} else {
										$rol = "Vendedor";
									} ?>
									<a class="dropdown-item" href="#"><?php echo $rol; ?></a>
									<a class="dropdown-item" href="../../PRESENTACION/views/salir.php">Salir</a>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#pablo">
									<i class="now-ui-icons users_single-02"></i>
									<p>
										<span class="d-lg-none d-md-block">Cuenta</span>
									</p>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<!-- End Navbar -->