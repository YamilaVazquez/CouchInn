<?php
include 'header2.php';
include_once 'conexion.php';
?>
			    <div class="busqueda">
					<form id="searchid" name="searchForm"> <!-- method="post" action="algo.php" onSubmit="return validarFormulario();" -->
						Localidad:<select name="ciudad" title="Seleccione una ciudad por la cual filtrar"><option value="opc">Opc1</option></select>
						Capacidad:<input type="text" placeholder="1" maxlength="2";/>
						<input type="submit" name="lugar" value= "Buscar"/>
					</form>
				</div> <!-- Fin DIV busqueda-->
				<h2>Últimos couchs dados de alta</h2>
				<a href="#">+Ver todos</a>
				<br/>
<?php
	$link = conectar();
	$consultaSQL = "SELECT * FROM couchs WHERE visibilidad = 1";
	$result = mysqli_query($link, $consultaSQL);

	if ($result) {
		while ($row = mysqli_fetch_array ($result)) {
			echo '<div class="tablecontainer">
				<table class="listado">
					<tr>
						<td><img src="./default_couch.png" alt="Foto de couch"/></td>
					</tr>
					<tr>
						<td>Localidad: '.$row['cod_loc'].'</td>
					</tr>
					<tr>
						<td>Capacidad: '.$row['capacidad'].'</td>
					</tr>
					<tr>
						<td>Categoria: '.$row['idtipo'].'</td>
					</tr>
					<tr>
						<td>'.$row['nombrecouch'].'</td>
					</tr>
					<tr>
						<td ><a href="couchdetail.php?id='.$row['idcouch'].'" title="Obtenga más detalles del couch"> +Info</a></td>
					</tr>
				</table>
				</div>';
		}
			mysqli_free_result($result);		
	} else {
	echo '<p>No hay couchs disponibles por el momento.</p>';
}
?>
<!-- Ejemplo de como se veria el listado. A borrar ya que se generara dinamicamente -->
				<div class="tablecontainer">
					<table class="listado">
						<tr>
							<td><img src="./default_couch.png" alt="Foto de couch"/></td>
						</tr>
						<tr>
							<td>Localidad</td>
						</tr>
						<tr>
							<td>Capacidad - TipoCouch</td>
						</tr>
						<tr>
							<td>DESCRIPCION Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do...</td>
						</tr>
						<tr>
							<td><a href="#" title="Obtenga más detalles del couch">+info</a></td>
						</tr>
					</table>
				</div>
<!-- fin ejemplo -->
<?php
include 'footer.php';
?>