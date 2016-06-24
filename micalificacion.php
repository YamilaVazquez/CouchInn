<?php
include_once 'header.php';
include_once 'funciones.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
$usuarioid=Sesion::idUsuario(); 
$link = conectar();
/*calificaciones*/
		echo '<h4>Mis puntajes cómo huésped</h4>';
		$querycal = "SELECT * FROM 	califhost ch INNER JOIN reservas r ON (ch.idreserva = r.idreserva) WHERE iduser = ".$usuarioid;
		$resultcal = mysqli_query($link, $querycal);
		if (mysqli_num_rows($resultcal) > 0) {
			echo '<table class="tablaVer" align="center">
					<tr>
					<td><strong>Puntaje</strong></td>
					<td><strong>Comentario</strong></td>
					</tr>';
			while ($rowcal = mysqli_fetch_array($resultcal)) {
				echo '<tr>
						<td>'.$rowcal['puntajehost'].'</td>
						<td>'.$rowcal['commenthost'].'</td>
					</tr>';
			}
			echo '</table>';
		} else {
			echo 'Aún no te han calificado';
		}
include 'footer.php';
?>