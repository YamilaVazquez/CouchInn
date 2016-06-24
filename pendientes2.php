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

echo "<h2>Reservas en estado pendiente</h2>";
$conn = conectar();
$userid = Sesion::idUsuario();
/*RESERVAS REALIZADAS a LOS COUCHS DEL USUARIO*/
$query = "SELECT r.idreserva, c.idcouch, r.iduser, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (c.iduser =".$userid.") AND (estado = 'pendiente')";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result)> 0){
	echo '<table class="tablaVer">
		<tr>
			<td>Numero</td>
			<td>Titulo</td>
			<td>usuario</td>
			<td>Fecha inicio</td>
			<td>Fecha fin</td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){
		echo '<tr>
				<td>'.$row['idreserva'].'</td>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['iduser'].'</td>
				<td>'.$row['fechaini'].'</td>
				<td>'.$row['fechafin'].'</td>
				<td><a href="aceptarreserva.php?id='.$row['idreserva'].'" onclick="return confirm(\'¿Esta seguro que desea aceptar la reserva numero '.$row['idreserva'].'? \')" title="Aceptar reserva"> Aceptar</a></td>
				<td><a href="confirmarRechazo.php?id='.$row['idreserva'].'" onclick="return confirm(\'¿Esta seguro que desea rechazar la reserva numero '.$row['idreserva'].'? \')" title="Rechazar reserva"> Rechazar</a></td>
			</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>No tiene reservas pendientes</strong></p>";}

//mysqli_free_result($result);

include 'footer.php';
desconectar($conn);
?>	