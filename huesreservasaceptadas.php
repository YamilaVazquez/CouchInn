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

echo "<h2>Mis reservaciones aceptadas</h2>";/*RESERVAS REALIZADAS -POR- EL USUARIO, NO HACIA LOS COUCHS DEL USUARIO*/
$conn = conectar();
$userid = Sesion::idUsuario();
$query = "SELECT r.idreserva, c.idcouch, c.iduser, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch) WHERE (r.iduser =".$userid.") AND (estado = 'aceptada')";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result)>0){
	echo '<table class="tablaVer">
		<tr>
			<td><strong>Couch</strong></td>
			<td><strong>Fecha inicio</strong></td>
			<td><strong>Fecha fin</strong></td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){
		$queryuser = "SELECT email, nombre, apellido, telefono FROM usuarios WHERE iduser = ".$row['iduser'];
		$resultuser = mysqli_query($conn, $queryuser);
		if (mysqli_num_rows($resultuser)> 0){
			$usuario = mysqli_fetch_array($resultuser);
		}
		echo '<tr>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['fechaini'].'</td>
				<td>'.$row['fechafin'].'</td>
			</tr>
			<tr>';
			if (isset($usuario)) {
				echo '<td>Anfitrión: '.$usuario['nombre'].' '.$usuario['apellido'].'</td>
					<td>eMail: '.$usuario['email'].'</td>
					<td>Teléfono: '.$usuario['telefono'].'</td>';
			}else {
				echo '<td>Datos del anfitrión no disponibles</td>';
			}
		echo '</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>No han aceptado ninguna de sus reservaciones</strong></p>";}

//mysqli_free_result($result);

include 'footer.php';
desconectar($conn);
?>	