<?php
require_once 'sesion.php';
try{
  Sesion::estaAutorizado();
}
	catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if (isset($_GET) && !empty($_GET['id'])) {
	$resid = $_GET['id'];
	$conn = conectar();
	$query = "SELECT * FROM reservas WHERE idreserva = '$resid'";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		
		$row = mysqli_fetch_array($result);
		$fechaini = $row['fechaini'];
		echo $fechaini;

		$fechafin = $row['fechafin'];
		echo $fechafin;
		$couchid = $row['idcouch'];
		$query2 ="SELECT * 
		FROM reservas r 
		WHERE (idcouch = '$couchid') AND 
		((DATE('$fechaini') BETWEEN r.fechaini  AND r.fechafin) OR 
		(DATE('$fechafin') BETWEEN r.fechaini AND r.fechafin)) AND 
		(estado = 'aceptada') AND 
		(idreserva <> '$resid')";
		$result2 =mysqli_query($conn, $query2);
		if (mysqli_num_rows($result2) > 0) { /*si es mayor a cero, es que hay conflicto*/
			$msj = "Error. La reserva '$resid' se superpone con una reserva ya aceptada.";
			//header('Location: pendientes2.php?mensaje='.urlencode($msj).'&tipo=Error');
		} else {
			echo "no hay conflictos con otras reservas<br/>";
			$query = "UPDATE reservas SET estado = 'aceptada' WHERE idreserva = '".$resid."'";
			$result3 = mysqli_query($conn, $query);
			$query = "UPDATE reservas r SET estado = 'rechazada' WHERE (idcouch = '$couchid') AND 
		((DATE(r.fechaini) BETWEEN '$fechaini'  AND '$fechafin') OR 
		(DATE(r.fechafin) BETWEEN '$fechaini' AND '$fechafin')) AND 
		(estado = 'pendiente') AND (idreserva <> '$resid')";
			$result = mysqli_query($conn, $query);
			$msj = "La reserva '$resid' fue aceptada. Se rechazaron las reservas pendientes que se superponian con esta";
			header('Location: pendientes2.php?mensaje='.urlencode($msj).'&tipo=Correcto');
			/*cambiarla a ceptada.
			rechazar las pendientes que se superppongan
			enviar datos de contacto*/
		}
	} else {
		$msj = "Error. La reserva no existe";
		header('Location: pendientes2.php?mensaje='.urlencode($msj).'&tipo=Error');
	}
	desconectar($conn);
} else {
	//header('Location: pendientes2.php');
}
/*todas las reservas en determinado couch entre determinadas fechas
"UPDATE tipocouch SET nombretipo ='".$nombre ."' WHERE idtipo = '".$_POST['id']."'"
$query2 ="SELECT * 
		FROM reservas 
		WHERE (idcouch = '$couchid') AND 
		((fechaini BETWEEN CAST( '$fechaini' AS DATE ) AND CAST( '$fechafin' AS DATE )) OR 
		(fechafin BETWEEN CAST( '$fechaini' AS DATE ) AND CAST( '$fechafin' AS DATE )))";*/
?>