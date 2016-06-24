<?php
require_once 'conexion.php';
require_once 'header.php';

try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}

echo "<h2>Reservas aceptadas</h2>";/*RESERVAS REALIZADAS a LOS COUCHS DEL USUARIO*/
$conn = conectar();
$userid = Sesion::idUsuario();
$query = "SELECT r.idreserva, c.idcouch, r.iduser, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch) WHERE (c.iduser =".$userid.") AND (estado = 'aceptada')";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result)> 0){
	echo '<table class="tablaVer">
		<tr>
			<td><strong>Numero</strong></td>
			<td><strong>Couch</strong></td>
			<td><strong>Fecha inicio</strong></td>
			<td><strong>Fecha fin</strong></td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){
		$con = conectar();
		$queryuser = "SELECT email, nombre, apellido, telefono FROM usuarios WHERE iduser = ".$row['iduser'];
		$resultuser = mysqli_query($con, $queryuser);
		if (mysqli_num_rows($resultuser)> 0){
			$usuario = mysqli_fetch_array($resultuser);
		}/*else{
			$usuario = array();
			$usuario[nombre] = "";
		}*/
		echo '<tr>
				<td>'.$row['idreserva'].'</td>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['fechaini'].'</td>
				<td>'.$row['fechafin'].'</td>
				<td><a href="confirmarCancelacion2.php?id='.$row['idreserva'].'" onclick="return confirm(\'¿Esta seguro que desea cancelar la reserva numero '.$row['idreserva'].'? \')" title="Cancelar reserva">Cancelar</a></td>
			</tr>
			<tr>';
				if (isset($usuario)) {
					echo '<td>Huésped: '.$usuario['nombre'].' '.$usuario['apellido'].'</td>
						<td>eMail: '.$usuario['email'].'</td>
						<td colspan="3">Teléfono: '.$usuario['telefono'].'</td>';
				}else {
					echo '<td>Datos del huésped no disponibles</td>';
				}
		echo '</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>No has aceptado ninguna reserva</strong></p>";}

mysqli_free_result($result);
desconectar($conn);
include 'footer.php';
?>