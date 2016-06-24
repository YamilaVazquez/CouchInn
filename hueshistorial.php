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

echo "<h2>Historial de couchs visitados</h2>";/*de RESERVAS REALIZADAS -POR- EL USUARIO, NO HACIA LOS COUCHS DEL USUARIO*/
$conn = conectar();
$userid = Sesion::idUsuario();
$query = "SELECT r.idreserva, c.idcouch, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch) WHERE (r.iduser =".$userid.") AND (estado = 'concretada')";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result)>0){
	echo '<table class="tablaVer">
		<tr>
			<td>Couch</td>
			<td>Fecha inicio</td>
			<td>Fecha fin</td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){ /*HABILITAR CALIF solo si no la realizo > $_GET ?idres=idreserva*/
		echo '<tr>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['fechaini'].'</td>
				<td>'.$row['fechafin'].'</td>';
				$querycalif = "SELECT count(*) AS cant FROM califhuesped WHERE idreserva = ".$row['idreserva'];
				$resultcalif = mysqli_query($conn, $querycalif);
				$rowcalif = mysqli_fetch_array($resultcalif);
				if ($rowcalif['cant'] == 0) {
					echo '<td><a href="calificarcouch.php?idres='.$row['idreserva'].'" title="Califique su estadía">Calificar</a></td>';
				}
		echo	'</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>Aún no has realizado visitas</strong></p>";}

//mysqli_free_result($result);

include 'footer.php';
desconectar($conn);
?>	