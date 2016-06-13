<?php
require_once 'sesion.php';
include 'header.php';
try{
	Sesion::estaAutorizado(true);
}
catch(Exception $e){
	header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
$conexion= conectar();
	//onsubmit  para validaciones
	echo '<form action="TipoCouchCrear.php" method="post" onsubmit="";> 
		<fieldset>
			<legend> Crear tipo Couch</legend>
			<br/>
			<label for="tipoCouch"> Nombre:</label>
			<input type="text" name="tipoCouch" id="tipoCouch" placeholder="Ingrese nuevo tipo de couch" required="">
			<input type="submit" value="Aceptar" class="submitbtn" name="aceptar">
			<a href= "pruebavertipos.php" ><input type="button" value="Cancelar" class="submitbtn"> </a>
		</fieldset>
	</form>	';

include 'footer.php';
desconectar($conexion);
?>