<?php
	include_once 'conexion.php'; //voy a incluir el arvhivo conexion
	include_once 'header.php';
	include_once 'funciones.php';
	$conexion= conectar();
	

	if (isset($_POST['aceptar'])) {		//pregunto si se oprimió el boton aceptar (name)
		$nombre = $_POST['tipoCouch'];	//tengo una var nombre que es el name del tipoCouch que recibo.
		if (validarCampos()){

			if(!existeEnBD($conexion)) {

				$fila = mysqli_query($conexion, "insert into tipocouch (nombretipo) values ('".$nombre."')"); 
				$msj = "Ingreso exitoso. Ya se encuentra ingresado el nuevo tipo de couch";
				header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Correcto');
			}
			else{
				if (!estahabilitado($conexion)){
					$fila = mysqli_query($conexion, "UPDATE tipocouch SET bajalogica = 0 WHERE nombretipo = '" .$nombre."'"); //
					$msj= "Ingreso exitoso. Ya se encuentra el nuevo tipo de couch";	
					header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Correcto');
				}else
				{
					$msj= "Error. El tipo de couch ingresado ya se encuentra";
					header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Error');
				}
			}
		}
		else{
				$msj= "El couch no puede contener campo numerico ni caracteres";
				header('Location: pruebavertipos.php?mensaje='.urlencode($msj).'&tipo=Atencion');

		}
	}	
	
	
	function validarCampos(){
		if(isset($_POST['tipoCouch']) && !empty($_POST['tipoCouch'])){	//pregunto si hay un campo tipo cuch y si no es vacio
			/*if( campoLetras($_POST['tipoCouch']))*/
			if(nombre_valido($_POST['tipoCouch'])){
				return true; 
			}
			else{
				return false;
			}	
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
		$sql = "select * from tipocouch where nombretipo = '".$_POST['tipoCouch']."'";
		$result = mysqli_query($conexion, $sql);
		

		$fila= mysqli_fetch_array($result);
		
		if($fila){
			return true;
		}
		else{
			return false;
		}
	}

	function estahabilitado($conexion){
		$sql = "select * from tipocouch where (nombretipo = '".$_POST['tipoCouch']. "') and (bajalogica = 0)";
		echo $sql;
		$result = mysqli_query($conexion, $sql); //ver
		$fila= mysqli_fetch_array($result);
		if($fila){
			return true;
		}
		else{
			return false;
		}
		
	}
	include 'footer.php';
	desconectar($conexion);
?>