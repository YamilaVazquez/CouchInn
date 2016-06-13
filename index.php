<?php
include 'header.php';
include_once 'conexion.php';
?>
			    <!--<div class="busqueda">
					<form id="searchid" name="searchForm" method="get" action="index.php" onSubmit="return validarFormulario();">
						Localidad:<select name="ciudad" title="Seleccione una ciudad por la cual filtrar"><option value="opc">Opc1</option></select>
						Capacidad:<input type="text" name="capacidad" placeholder="1" maxlength="2";/>
						<input type="submit" name="lugar" value= "Buscar"/>
					</form>
				</div>  Fin DIV busqueda-->

				<h2>Últimos couchs dados de alta</h2>
				<br/>
<?php
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
function imageForCouch($id) {
	$link = conectar();
	$query = "SELECT path, MIN(idimg) FROM imgcouchs i INNER JOIN couchs c ON (i.idcouch = c.idcouch) INNER JOIN usuarios u ON (u.iduser = c.iduser) WHERE (premium = 1 AND i.idcouch = '".$id."') GROUP BY i.idcouch";
	$result = mysqli_query($link, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$path_for_image = "./imgcouch".$row['path'];
		return $path_for_image;
	}else {
		$path_for_image = "./default_couch.png";
		return $path_for_image;
	}
}

$page_size = 6; /*registros por pagina*/

/*if (!empty($_GET)) { agregado mio*/
if (isset($_GET['page']) && !empty($_GET)) {
	$page = $_GET['page'];
}
if (!empty($page)) { /*ver por valores 0 para page*/
	$start = ($page-1)*$page_size;
} else {
	$page = 1;
	$start = 0;
}
$link = conectar();
$query = "SELECT * FROM couchs WHERE visibilidad = 1";
$result = mysqli_query($link, $query);
$total_rows = mysqli_num_rows($result);
$total_pages = ceil($total_rows / $page_size);

$query = "SELECT idcouch, nombrecouch, capacidad, nombreloc, nombreprov, nombretipo FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) WHERE visibilidad = 1 ORDER BY idcouch DESC LIMIT ".$start.",".$page_size;
/*queda v aceptar criterios para filtrado*/
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
/*if ($result) {*/
	while ( $row = mysqli_fetch_array($result) ) {
	/*REVISAR! Los DIV contenedores se deforman en altura por las diferentes longitudes del texto*/
				$imgpath = imageForCouch($row['idcouch']);
				echo '<div class="tablecontainer">
						<table class="listado">
							<tr>
								<td><img src="'.$imgpath.'" alt="Foto de couch"/></td>
							</tr>
							<tr>
								<td>Localidad: '.$row['nombreloc'].',<br/>'.$row['nombreprov'].'</td>
							</tr>
							<tr>
								<td>Capacidad: '.$row['capacidad'].'</td>
							</tr>
							<tr>
								<td>Categoria: '.$row['nombretipo'].'</td>
							</tr>
							<tr>
								<td>'.$row['nombrecouch'].'</td>
							</tr>
							<tr>
								<td ><a href="detallecouch.php?id='.$row['idcouch'].'" title="Obtenga más detalles del couch"> +Info</a></td> 
							</tr>
						</table>
					</div>';
	}
	mysqli_free_result($result);
	desconectar($link);
}
else{
	echo '<p>No hay couchs disponibles para mostrar.</p>';
}
if ( $total_pages > 1 ) {
	echo '<br/>ir a página ';
	for ($i = 1; $i <= $total_pages; $i++) { /*cambiar. si hay demasiadas paginas quedara muy largo. Debería poner "..." si hay mas de x cant*/
		if ($page == $i) {
			echo $page.' ';
		} else {
			echo "<a href='index.php?page=".$i."'>".$i." </a>";
		}
	}
}
/*echo '<br/>pagina '.$page.' de '.$total_pages.' con un total de '.$total_rows.' resultados<br/>';*/

include 'footer.php';
?>