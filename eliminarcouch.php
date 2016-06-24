<?php
require_once "header.php";
include_once 'funciones.php';

if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}

 	

if (isset($_GET) && (!empty($_GET['id']))){
	$conexion= conectar();
	$couchid= $_GET['id'];
	$query= "SELECT * FROM reservas WHERE ( idcouch = '$couchid') AND ( estado = 'pendiente' )"; 
	$result = mysqli_query($conexion, $query);
	
	if (mysqli_num_rows($result) > 0) { //si hay reservas pendientes
		while ($row = mysqli_fetch_array($result)){ //recorro pendientes y las rechazo
			$query = mysqli_query($conexion, "UPDATE reservas SET estado = 'rechazada' WHERE idreserva = '".$row['idreserva']."'"); //ver si está bien
		}
		$query= "SELECT * FROM reservas WHERE ( idcouch = '$couchid') AND ( estado = 'aceptada' )";
		$fila= mysqli_query($conexion, $query);
		if (mysqli_num_rows($fila) > 0){ //Si hay reservas aceptadas
			$resultado = mysqli_query($conexion, "UPDATE couchs SET visibilidad = 0 WHERE idcouch = ".$couchid); //despublico ese couch
				$msj = "El couch ha sido despublicado, usted tiene reservas aceptadas";
				header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Info');
		}
		else{
			$query= "SELECT * FROM reservas WHERE ( idcouch = '$couchid') AND ( estado = 'concretada' )";
			$result = mysqli_query($conexion, $query);
			if (mysqli_num_rows($result) >  0) { //si hay reservas concretadas
				$resultado = mysqli_query($conexion, "UPDATE couchs SET bajacouch = 1 WHERE idcouch = ".$couchid); //baja lógica
				$msj = "El couch se eliminó correctamente (baja)";
				header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Correcto');
			}
			else{
				$resultado = mysqli_query($conexion, "DELETE FROM couchs WHERE idcouch = ".$couchid); //baja física
				$msj = "El couch se eliminó correctamente (fisica)";
				header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Correcto');
			}
		}
	}
	else{
			$query= "SELECT * FROM reservas WHERE ( idcouch = '$couchid') AND ( estado = 'aceptada' )";
		$fila= mysqli_query($conexion, $query);
		if (mysqli_num_rows($fila) > 0){ //Si hay reservas aceptadas
			$resultado = mysqli_query($conexion, "UPDATE couchs SET visibilidad = 0 WHERE idcouch = ".$couchid); //despublico ese couch
				$msj = "El couch ha sido despublicado, usted tiene reservas aceptadas";
				header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Info');
		}
		else{
			$query= "SELECT * FROM reservas WHERE ( idcouch = '$couchid') AND ( estado = 'concretada' )";
			$result = mysqli_query($conexion, $query);
			if (mysqli_num_rows($result) >  0) { //si hay reservas concretadas
				$resultado = mysqli_query($conexion, "UPDATE couchs SET bajacouch = 1 WHERE idcouch = ".$couchid); //baja lógica
				$msj = "El couch se eliminó correctamente (baja)";
				header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Correcto');
			}
			else{
				$resultado = mysqli_query($conexion, "DELETE FROM couchs WHERE idcouch = ".$couchid); //baja física
				$msj = "El couch se eliminó correctamente (fisica)";
				header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Correcto');
			}
		}
	}
}

//si hay reservas pendientes
	//recorrer esas reservas y rechazar
	//si hay reservas aceptadas
		//despublicar y mostrar mensaje de despublicación
	//else
		//si hay reservas concretadas
			//baja logica y mostrar mensaje de eliminación
		//else
			//borrar fisicamente y mensaje de eliminación.

desconectar($conexion);
include 'footer.php';
?>