<?php
include 'header.php';
include_once 'funciones.php';
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
$conexion= conectar();
echo "<h2> Detalle de couch </h2>";

if (isset($_GET) && (!empty($_GET['id']))) { //pregunto si se oprimió y no es vacío el id CREO
	$query = "SELECT idcouch,titulo, nombrecouch, c.iduser, capacidad, path, nombreloc, nombreprov, nombretipo, nombre, apellido  FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) INNER JOIN usuarios u ON (c.iduser = u.iduser) WHERE idcouch =" .$_GET['id'];	//ver el order by si agregar
	
	$result = mysqli_query($conexion, $query);
	
		
	if (mysqli_num_rows($result) > 0) { 
		$row = mysqli_fetch_array($result);
		echo '
				<table style="width:70%" align="center" padding: 5px>
				  
				  <tr>
				    <td align="center"><b>Titulo:</b> </td>
				    <td>'.$row['titulo'].'</td>
				  </tr>
				  <tr>
				    <td align="center"><b>Categoría:</b></td>
				    <td>'.$row['nombretipo'].'</td>
				  </tr>
				  <tr>
				    <td align="center"><b>Descripción:</b></td>
				    <td>'.$row['nombrecouch'].'</td> 
				  </tr>
				  <tr>
				    <td align="center"><b>Capacidad:</b></td>
				    <td>'.$row['capacidad'].' </td>
				  </tr>
				  <tr>
				    <td align="center"><b>Provincia:</b></td>
				    <td>'.$row['nombreprov'].' </td>
				  </tr>
				  <tr>
				     <td align="center"><b>Localidad:</b> </td>
				     <td>'.$row['nombreloc'].'</td>
				  </tr>
				  <tr>
				     <td align="center"><b>Anfitrión:</b> </td>
				     <td>'.$row['nombre'].'</td>
				  </tr>
				  </br>
				  <tr>
					<td colspan="3" align="center" valign="middle"><img src="imgcouch/'.$row['path'].'" alt="Foto de couch" style="width:330px;height:250px" border= 2px/></td>
				  </tr>';
 				if (Sesion::estaLogueado() && $row['iduser'] <> sesion::IdUsuario()){
					echo '<tr>
				 			<td colspan="2" align="center" valign="middle" ></br><a href= "realizarReserva.php?id='.$row['idcouch'].'"><input type="button" value="Reservar" class="reservarbtn"> </a> </td>
				  		 </tr>	';
				}
				echo'</table>';	
		/*CALIFICACIONES*/
		echo '<h4>Calificaciones</h4>';
		$querycal = "SELECT * FROM 	califhuesped ch INNER JOIN reservas r ON (ch.idreserva = r.idreserva) WHERE idcouch = ".$row['idcouch'];
		$resultcal = mysqli_query($conexion, $querycal);
		if (mysqli_num_rows($resultcal) > 0) {
			echo '<table class="tablaVer" align="center">
					<tr>
					<td><strong>Puntaje</strong></td>
					<td><strong>Comentario</strong></td>
					</tr>';
			while ($rowcal = mysqli_fetch_array($resultcal)) {
				echo '<tr>
						<td>'.$rowcal['puntajehues'].'</td>
						<td>'.$rowcal['commenthues'].'</td>
					</tr>';
			}
			echo '</table>';
		} else {
			echo 'Este Couch aún no tiene calificaciones';
		}
		/*PREGUNTAS*/
		echo '<h4>Preguntas</h4>';
		$querypreg = "SELECT * FROM comentarios co WHERE idcouch = ".$row['idcouch'];
		$resultpreg = mysqli_query($conexion, $querypreg);
		if (mysqli_num_rows($resultpreg) > 0) { /*-------TERMINARRRRRRRRR----*/
			echo '<table class="tablaVer" align="center">
					<tr>
					<td><strong>Pregunta</strong></td>
					</tr>';
		} else {
			echo 'Este Couch aún no tiene Preguntas';
		}
	}	
}
include 'footer.php';

desconectar($conexion);
?>