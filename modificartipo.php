<?php
include 'header.php';
include_once 'funciones.php';
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
$conexion= conectar(); 			// hago conexion

if (isset($_POST['aceptar'])) {		//pregunto si se oprimió el boton aceptar (name)
	$nombre = $_POST['tipoCouch'];	//tengo una var nombre que es el name del tipoCouch que recibo.
	if (validarCampos($nombre)){ // valido que esten llenos los campos
		if(nombre_valido($nombre)){ //valido que el campo sea solo letras
			if(!existeEnBD($conexion)) {
				$msj = "Error. No se encuentra el tipo de couch a modificar";
				header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Error');
			}
			else{
					$fila = mysqli_query($conexion, "UPDATE tipocouch SET nombretipo ='".$nombre ."' WHERE idtipo = '".$_POST['id']."'"); //Habilitar
					$msj = "Modificación exitosa del tipo de couch";
					header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Correcto');
					
			}		
		}
		else{
				$msj = "El nombre del tipo de couch no debe contener números ni caracteres";
				header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Atencion');
			}
	}
	else{
		$msj = "Falta completar el campo";
		header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Atencion');
	}	
}	
	
function validarCampos($campo){
	if(isset($campo) && !empty($campo)){	//pregunto si hay un campo tipo cuch y si no es vacio
		return true;
	}
	else{
		return false;
	}
}

function nombre_valido ($valor)
	{
	    $reg = "/^([a-z ñáéíóú]{2,60})$/i";
	    if(preg_match($reg, $valor)) return true;
	        else return false;
	}



function existeEnBD($conexion){
	$sql = "select * from tipocouch where idtipo = '".$_POST['id']."'";
	$result = mysqli_query($conexion, $sql);
	$fila= mysqli_fetch_array($result);
	
	if($fila){
		return true;
	}
	else{
		return false;
	}
}

if (isset($_GET) && (!empty($_GET['id'])) && (!empty($_GET['name']))){
	echo '<form action="modificartipo.php" method= "POST">
		<fieldset>
			<legend> Modificar tipo Couch</legend>
			<br/>
			<label for="tipoCouch"> Nombre nuevo:</label>
			<input type="text" name="tipoCouch" id="tipoCouch" placeholder="'.$_GET['name'].'" required="">
			<input type="submit" value="Aceptar" class="submitbtn" name="aceptar" onclick="enviar()">
			<a href= "pruebavertipos.php" ><input type="button" value="Cancelar" class="submitbtn"> </a>
			<input type="hidden" name="id" value="'.$_GET['id'].'">
		</fieldset>
	</form>';	

}


include 'footer.php';

desconectar($conexion);
?>