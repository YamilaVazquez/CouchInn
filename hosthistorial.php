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

echo "<h2>Huéspedes que visitaron mis couchs</h2>";/*de RESERVAS REALIZADAS a LOS COUCHS DEL USUARIO*/
$conn = conectar();
$userid = Sesion::idUsuario();
$query = "SELECT r.idreserva, c.idcouch, r.iduser, fechaini, fechafin, titulo FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (c.iduser =".$userid.") AND (estado = 'concretada')";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result)> 0){
	echo '<table class="tablaVer">
		<tr>
			<td><strong>Couch<strong></td>
			<td><strong>Huésped<strong></td>
			<td><strong>Fecha inicio<strong></td>
			<td><strong>Fecha fin<strong></td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){/*HABILITAR CALIF*/
		$con = conectar();
		$queryuser = "SELECT email, nombre, apellido, telefono FROM usuarios WHERE iduser = ".$row['iduser'];
		$resultuser = mysqli_query($con, $queryuser);
		if (mysqli_num_rows($resultuser)> 0){
			$usuario = mysqli_fetch_array($resultuser);
		}
		echo '<tr>
				<td>'.$row['titulo'].'</td>';
				if (isset($usuario)) {
					echo '<td>'.$usuario['nombre'].' '.$usuario['apellido'].'</td>';
				}else {
					echo '<td>No disponible</td>';
				}
				echo '<td>'.$row['fechaini'].'</td>
				<td>'.$row['fechafin'].'</td>';
				$querycalif = "SELECT count(*) AS cant FROM califhost WHERE idreserva = ".$row['idreserva'];
				$resultcalif = mysqli_query($con, $querycalif);
				$rowcalif = mysqli_fetch_array($resultcalif);
				if ($rowcalif['cant'] == 0) {
					echo '<td><a href="calificarhuesped.php?idres='.$row['idreserva'].'" title="Califique al huésped">Calificar</a></td>';
				}
			echo '</tr>';

	}
	echo '</table>';
}else {echo "<p><strong>Aún no has recibido a ningún huésped</strong></p>";}
/*			if ($raw['count(*)'] == 0) {
				mostrar($row);
			}	*/
//mysqli_free_result($result);

include 'footer.php';
desconectar($conn);
?>