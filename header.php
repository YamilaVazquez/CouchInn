<?php
include_once 'funciones.php';
include_once 'sesion.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="estiloprincipal.css">
	<link rel="stylesheet" type="text/css" href="menu.css">
	<link rel="stylesheet" type="text/css" href="forms.css">
	<title>CouchInn</title>
</head>
<body>
	<div id="contenido">
		<header id="encabezado">
			<ul id="navsesion">
<?php
			if (Sesion::estaLogueado()){
				echo '<li style="color:white"><i>Bienvenido '.Sesion::nombreUsuario().'</i></li>
				<li> | </li>
				<li><a href="logout.php">Cerrar Sesion</a></li>';
			} else {
				echo '<li><a href="loginform.php">Iniciar sesión</a></li>
				<li> | </li>
				<li><a href="register.php">Registrarse</a></li>';
			}
?>
			</ul>
			<a href="index.php"><img src="logo.png" alt="logo de couch inn"/></a>
			<nav id="menu">
				<ul>
<?php
				if (Sesion::estaLogueado()){
					if(!Sesion::esPremium()){echo '<li><a href="premium.php">* PREMIUM *</a></li><li> | </li>';}
					echo '<li><a href="#">MI PERFIL</a>
						<ul id="submenu">
							<li><a href="modificarPerfil.php">Modificar</a></li>
							<li><a href="micalificacion.php"> Mi calificación</a></li>
							<li><a href="#">Preguntas</a></li>';
							if (Sesion::esAdmin()) {
								echo'<li><a href="pruebavertipos.php">Tipo Couch</a></li>
								<li><a href="#">Reportes</a></li>';
							}
					echo'</ul>
					</li>
					<li> | </li>
					<li><a href="#">COUCHS</a>
						<ul id="submenu">
							<li><a href="miscouchs.php">*Mis Couchs</a></li>
							<li><a href="couchnew.php">*Nuevo</a></li>
							<li><a href="pendientes2.php">*Reservas pendientes</a></li>
							<li><a href="hostreservasaceptadas.php">*Reservas aceptadas</a></li>
							<li><a href="hosthistorial.php">*Historial de reservas</a></li>
						</ul>
					</li>
					<li> | </li>
					<li><a href="#">RESERVAS</a>
						<ul id="submenu">
							<li><a href="pendientes.php">Pendientes</a></li>
							<li><a href="huesreservasaceptadas.php">Aceptadas</a></li>
							<li><a href="hueshistorial.php">Historial</a></li>
						</ul>
					</li>
					<li> | </li>';
				}
?>
					<li><a href="faq.php">AYUDA</a></li>
				</ul>
			</nav>
		</header>
		<div id="container">
			<div id="content">