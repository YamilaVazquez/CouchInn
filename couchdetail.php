<?php
include 'header.php';
include_once 'conexion.php';

$link = conectar();

if(isset($_GET['id'])){
	$id=$_GET['id'];
	$consultaSQL = "SELECT * FROM couchs where idcouch = $id";	
		
	$result = mysqli_query($link, $consultaSQL);
		
		if ($result) {

			$row = mysqli_fetch_array ($result);
			/*$id = $row['idcouch'];*/
			echo '<div class="tablecontainer">
					<table class="listado">
						<tr>
							<td><img src="./default_couch.png" alt="Foto de couch"/></td>
						</tr>
						<tr>
							<td>'.$row['cod_loc'].'</td>
							<td>'.$row['capacidad'].'</td>
						</tr>
						<tr>
							<td>'.$row['nombrecouch'].'</td>
						</tr>
					</table>
				</div>';

			mysqli_free_result($result);		
		}
		else{
			echo '<div class="cartelError">El couch no está disponible</div>';
		}

	mysqli_close($link);
}
else{
	echo '<div class="cartelError">No se seleccionó ningun couch para ver.</div>';
}
/*CAMBIAR: Debe volver a la pagina de dónde vino, no al index*/
echo '<br/><br/>
		<a href="index.php" title="Regrese al listado de couchs">Volver</a>';

include 'footer.php';
?>
