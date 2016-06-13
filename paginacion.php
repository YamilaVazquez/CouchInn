<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="estiloprincipal.css">
</head>
<body>
<h1>Couch prueba paginacion</h1>
<?php

include_once 'conexion.php';
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
$page_size = 2; /*registros por pagina*/

if (!empty($_GET)) { /*agregado mio*/
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

echo 'pagina '.$page.' de '.$total_pages.' con un total de '.$total_rows.' resultados<br/>';

/*$query = "SELECT * FROM couchs WHERE visibilidad = 1 LIMIT ".$start.",".$page_size;*/
$query = "SELECT idcouch, nombrecouch, capacidad, nombreloc, nombreprov, nombretipo FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) WHERE visibilidad = 1 ORDER BY idcouch DESC LIMIT ".$start.",".$page_size;
/*queda ver si es premium el dueño, buscar la foto y mostrarla en vez de la default, y aceptar criterios para filtrado*/
$result = mysqli_query($link, $query);

while ( $row = mysqli_fetch_array($result) ) {
			$imgpath = imageForCouch($row['idcouch']);
			echo '<div class="tablecontainer">
				<table class="listado">
					<tr>
						<td><img src="'.$imgpath.'" alt="Foto de couch"/></td>
					</tr>
					<tr>
						<td>Localidad: '.$row['nombreloc'].','.$row['nombreprov'].'</td>
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
						<td ><a href="couchdetail.php?id='.$row['idcouch'].'" title="Obtenga más detalles del couch"> +Info</a></td>
					</tr>
				</table>
				</div>';
}
mysqli_free_result($result);
mysqli_close($link);

if ( $total_pages > 1 ) {
	echo '<br/>ir a página ';
	for ($i = 1; $i <= $total_pages; $i++) {
		if ($page == $i) {
			echo $page.' ';
		} else {
			echo "<a href='paginacion.php?page=".$i."'>".$i." </a>";
		}
	}
}
?>
</body>
</html>