<?php
	/*CAMBIAR DATOS DE CONEXIÓN SEGÚN COMO CONFIGURARON MYSQL*/
function conectar($bd='couchinn') {
	$link = mysqli_connect('localhost', 'dbuser', 'dbpass', $bd) or trigger_error("Error de conexión", E_USER_ERROR);
	return $link;
}

function desconectar($conexion){
	mysqli_close($conexion);
}
?>