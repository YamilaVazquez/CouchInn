<?php
include 'conexion.php';
include 'header.php';
$conexion= conectar(); 			// hago conexion


if (isset($_GET) && (!empty($_GET['id']))){
	if (usabilidad($conexion)) { 	//si se está usando, deshabilitar
		$fila = mysqli_query($conexion, "UPDATE tipocouch SET bajalogica = 1 WHERE idtipo ='" .$_GET['id']."'"); 
		$msj = "Eliminación exitosa. Ya se eliminó el tipo de couch seleccionado";
		header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Correcto');
	}	
	else{
		$fila = mysqli_query($conexion, "DELETE FROM tipocouch WHERE idtipo ='" .$_GET['id']."'"); 
		$msj = "Eliminación exitosa. Ya se eliminó el tipo de couch seleccionado";
		header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Correcto');
	}
}
else{
	$msj = "El campo no puede estar vacío. Vuelva a intentarlo";
	header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Atencion');
}


function usabilidad($conexion){
	$result = mysqli_query($conexion, "SELECT * FROM couchs WHERE idtipo ='".$_GET['id']."'");
	$fila= mysqli_fetch_array($result);
	if ($fila){
		return true;
	}
	else{
		return false;
	}
	/*if (mysqli_num_rows($fila) > 0) {
			return true;
	}
	else{
		return false;
	}*/
}
	


include 'footer.php';
desconectar($conexion);
?>