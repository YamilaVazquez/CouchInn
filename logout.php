<?php
	include_once 'sesion.php';
	Sesion::cerrarSesion();
	header('Location: /index.php?mensaje='.urlencode('Has cerrado la sesión correctamente!.').'&tipo=Correcto');
?>
