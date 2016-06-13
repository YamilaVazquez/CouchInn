<?php
	/*CAMBIAR DATOS DE CONEXIÓN SEGÚN COMO CONFIGURARON MYSQL*/
function conectar($bd='couchinn') {
	$link = mysqli_connect('localhost', 'root', '', $bd) or trigger_error("Error de conexión", E_USER_ERROR);
	mysqli_query($link,"SET NAMES 'utf8'");// esta funcion te coloca los asentos automaticamente en la bd
	mysqli_query($link,"SET CHARACTER SET utf8");
	mysqli_query($link,"SET COLLATION_CONNECTION = 'utf8_unicode_ci'"); 
	return $link;
}

function desconectar($conexion){
	mysqli_close($conexion);
}
?>