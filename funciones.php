<?php
/*FUNCIONES VARIAS DE VALIDACIÓN del lado del servidor, en su mayoria basadas en las de JS*/
include_once 'conexion.php';

function campoMail($campo){
	if (filter_var($campo, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}

function campoURL($campo){
	// if (filter_var($campo, FILTER_VALIDATE_URL)) {
	if ((strlen($campo) > 3) && (preg_match('/^(http:\/\/)?(www.)?[0-9a-zA-ZñÑ-]+.(com|net|tur|org)(.[a-zA-Z]{2})?\/?$/', $campo))) {
		return true;
	} else {
		return false;
	}
}
// longitud minima > 3
function campoObligatorio($campo){
	return (strlen($campo) > 3);
}
//Solo letras y espacios
function campoLetras($campo){
	if ((strlen($campo) > 3) && (preg_match('/^[a-zA-ZÑñ ]+$/', $campo))) {
		return true;
	} else {
		return false;
	}
}
//valores alfanumericos (letras, numeros y espacios)
function campoAlfanumerico($campo){
	if ((strlen($campo) > 3) && (preg_match('/^[a-zA-ZÑñ0-9 ]+$/', $campo))) {
		return true;
	} else {
		return false;
	}
}
//solo letras y/o numeros -sin espacios-
function campoUsuario($campo){
	//return ((campo.value.length > 4)  && (campo.value.match(/^[a-zA-ZÑñ0-9 ]+$/g)) );
	if ((strlen($campo) > 3) && (preg_match('/^[a-zA-ZÑñ0-9]+$/', $campo))) {
		return true;
	} else {
		return false;
	}
}
//valida una fecha. No basada en la ver de JS
function validarFecha($campo){
	$campo  = explode('-', $campo); //se recibe en formato dd/mm/aaaa [0]dia [1]mes[2]año
	if (count($campo) == 3) {
		if (checkdate($campo[1], $campo[0], $campo[2])) { //checkdate ( int $MES, int $DIA ,int $AÑO )
			$actual=date_create(null);
			date_time_set($actual,0,0,0);
			$fecha=date_create($campo[2].'-'.$campo[1].'-'. $campo[0]);
			if($fecha >= $actual){
				return true; // fecha valida.
				
			}
			else{
				return false;
			}
			
		} else {
			return false; // fecha invalida
		}
	} else {
		return false;// no corresponde con el formato esperado.
	}
}
/*valida la imagen subida. Devuelve su tipo y contenido listo para insertar en la bd como un array en caso de ser valida*/
function validarImagen () {
	if(isset($_FILES['imagen'])) {
		if(is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			// return 0;
			if (filesize($_FILES['imagen']['tmp_name']) < 2096128) {
				    if(substr($_FILES['imagen']['type'],0,5) == 'image') {/*Debería validarse el tipo de otra forma, como con getimagesize o finfo_open()*/
						$contimg = array();
						$contimg['contenido'] = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
						$contimg['tipo'] = substr($_FILES['imagen']['type'],6);
						return $contimg;
					} else {
						//no es una imagen
						return 4;
					}
			} else {
				return 3;
				// el tamaño es mayor del permitido
			}
		} else {
			//echo 'Error al subir la imagen al servidor. Por favor intente de nuevo';
			return 1;
		}
	} else {
		return 2;
		// echo 'no se seleccionó ninguna imagen';
	}
}