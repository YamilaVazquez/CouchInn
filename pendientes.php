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

echo "<h2>Mis reservaciones en espera</h2>";
$conn = conectar();
$userid = Sesion::idUsuario();
/*RESERVAS REALIZADAS -POR- EL USUARIO, NO HACIA LOS COUCHS DEL USUARIO*/
$query = "SELECT r.idreserva, c.idcouch, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (r.iduser =".$userid.") AND (estado = 'pendiente')";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result)>0){
	echo '<table class="tablaVer">
		<tr>
			<td>Titulo</td>
			<td>Fecha inicio</td>
			<td>Fecha fin</td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){
		echo '<tr>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['fechaini'].'</td>
				<td>'.$row['fechafin'].'</td>
				<td><a href="confirmarCancelacion.php?id='.$row['idreserva'].'" title="cancelar reservacion" onclick="return confirm(\'¿Esta seguro que desea cancelar? \')"> cancelar</a></td>
			</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>No tiene reservas pendientes</strong></p>";}

//mysqli_free_result($result);
//RESERVAS QUE LE FUERON RECHAZADAS
$query2 = "SELECT r.idreserva, c.idcouch, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (r.iduser =".$userid.") AND (estado = 'rechazada')";
$result2 = mysqli_query($conn, $query2);
if (mysqli_num_rows($result2)>0){
	echo "<h2>Reservaciones rechazadas</h2>";
	echo '<table class="tablaVer">
		<tr>
			<td>Titulo</td>
			<td>Fecha inicio</td>
			<td>Fecha fin</td>
		</tr>';
	while ($row2 = mysqli_fetch_array($result2)){
		echo '<tr>
				<td>'.$row2['titulo'].'</td>
				<td>'.$row2['fechaini'].'</td>
				<td>'.$row2['fechafin'].'</td>
			</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>No tiene reservas rechazadas</strong></p>";}

include 'footer.php';
desconectar($conn);
?>