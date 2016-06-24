<?php
require_once 'conexion.php';
require_once 'funciones.php';
require_once 'sesion.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}

if (isset($_GET) && (!empty($_GET['id']))){
	$conexion= conectar();
	$query = "UPDATE couchs SET visibilidad = 1 WHERE idcouch = ".$_GET['id'];
	$resultado = mysqli_query($conexion, $query); //publico el couch
	$msj = "El couch ha sido publicado";
	header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Info');
}
desconectar($conexion);
?>