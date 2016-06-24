<?php
require_once "header.php";

try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
echo "<h2>Mis Couchs</h2>";
$conn = conectar();
$userid = Sesion::idUsuario();
$query = "SELECT idcouch, titulo, nombrecouch, capacidad, nombreloc, nombreprov, nombretipo FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) WHERE (iduser = '$userid') AND (visibilidad = 1) AND (bajacouch = 0)";
$result = mysqli_query($conn, $query);
if ($result) {
	echo '<h3>Couch Publicados</h3>';
	echo '<table class="tablaVer">
		<tr>
			<td>Nombre</td>
			<td>Localidad</td>
			<td>Tipo</td>
		</tr>';
	while ($row = mysqli_fetch_array($result)){
		echo '<tr>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['nombreloc'].', '.$row['nombreprov'].'</td>
				<td>'.$row['nombretipo'].'</td>
				<td><a href="couchedit.php?id='.$row['idcouch'].'" title="Edite este couch"> Editar</a></td>
				<td><a href="despublicarcouch.php?id='.$row['idcouch'].'" onclick="return confirm(\'¿Esta seguro que desea despublicar '.$row['titulo'].'? Esta acción inhabilitará la realización de nuevas reservas sobre este couch\')" title="Ocultar este couch"> Despublicar</a></td>
				<td><a href="eliminarcouch.php?id='.$row['idcouch'].'" onclick="return confirm(\'¿Esta seguro que desea eliminar '.$row['titulo'].'? \')" title="Elimine este couch"> Eliminar</a></td>
			</tr>';
	}
	echo '</table><br/>';
}else {echo "<p><strong>No tiene couchs publicados</strong></p>";}
mysqli_free_result($result);
$query2 = "SELECT idcouch, titulo, nombrecouch, capacidad, nombreloc, nombreprov, nombretipo FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) WHERE (iduser = '$userid') AND (visibilidad = 0) AND (bajacouch = 0 )";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
	echo '<h3>Couch No publicados</h3>';
	echo '<table class="tablaVer">
		<tr>
			<td>Nombre</td>
			<td>Localidad</td>
			<td>Tipo</td>
		</tr>';
	while ($row = mysqli_fetch_array($result2)){
		echo '<tr>
				<td>'.$row['titulo'].'</td>
				<td>'.$row['nombreloc'].', '.$row['nombreprov'].'</td>
				<td>'.$row['nombretipo'].'</td>
				<td><a href="couchedit.php?id='.$row['idcouch'].'" title="Edite este couch"> Editar</a></td>
				<td><a href="publicarcouch.php?id='.$row['idcouch'].'" onClick="return confirm(\'¿Esta seguro que desea publicar '.$row['titulo'].'? Esta acción habilitará la realización de nuevas reservas\')" title="Publicar este couch"> Publicar</a></td>
				<td><a href="eliminarcouch.php?id='.$row['idcouch'].'" onclick="return confirm(\'¿Esta seguro que desea eliminar '.$row['titulo'].'? \')" title="Elimine este couch"> Eliminar</a></td>
			</tr>';
	}
	echo '</table>';
}else {echo "<p><strong>No tiene couchs publicados</strong></p>";}
mysqli_free_result($result2);
desconectar($conn);
include 'footer.php';
?>