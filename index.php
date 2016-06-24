<?php
include 'header.php';
include_once 'conexion.php';
?>
			    <?php

					function generarHTMLInternoSelect($tabla,$campoId,$campoVisible,$idDefault=-1){
							$conexion = conectar();
							$resultados= mysqli_query($conexion, 'select * from '.$tabla);
							$html='';
							
							if($resultados){
								while($resultado = mysqli_fetch_array($resultados)){
									$html.= '<option value="'.$resultado[$campoId].'" ';
									$html.=($resultado[$campoId] == $idDefault) ? 'selected' : '';
									$html.=' >'.$resultado[$campoVisible].'</option>';
								}
								mysqli_free_result($resultados);
							}
							
							desconectar($conexion);
							return $html;
					}

					?>
					<script type="text/javascript">
					function showCities(codprov,loc) {
						if (codprov == "") {
								document.getElementById("ciudad").innerHTML = "";
								return;
						} else {
							var xmlhttp = new XMLHttpRequest();
							//The onreadystatechange event is triggered every time the readyState changes.
							xmlhttp.onreadystatechange = function() {
								//4 request finished and response is ready. 200 ok
								if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
									document.getElementById("ciudad").innerHTML = xmlhttp.responseText;//The responseText property returns the response as a string
								}
							};
							xmlhttp.open("GET", "selectciudades.php?id="+codprov+"&selectedloc="+loc, true);
							xmlhttp.send();
						}
					};
					function toggleAdvancedSearch() {
						var elem = document.getElementById('advancedsearch');
						if (elem.style.display === 'none') {
							elem.style.display = 'block';
						} else {
							elem.style.display = 'none';
						}
					}
					</script>

							<form name="filtrar" action="_busqueda.php" id="filtrar">
							<input type="text" name="filtrotitulo" placeholder="Seleccione título de couch">
							
							<div id="advancedsearch" style="display:none">
								<select name="filtrocategoria" title="Seleccione un tipo por el cual filtrar">
									<?php
										if(isset($_GET['filtrocategoria'])){//Si se recibe un criterio de filtrado, se lo define como "selected"
											echo '<option value="">Todas las categorias</option>';
											echo generarHTMLInternoSelect('tipocouch','idtipo','nombretipo',$_GET['filtrocategoria']); /*esta funcion simplemente crea el Select con los datos de la BD*/
										}
										else{
											echo '<option selected="selected" value="">Todas las categorias</option>';
											echo generarHTMLInternoSelect('tipocouch','idtipo','nombretipo');
										}
									?>
								</select>
								<select name="filtrocapac" title="Seleccione la capacidad por la cual filtrar" >
									<option selected value="0"> Seleccione capacidad </option>
									<option value="1">1</option>    
									<option value="2">2</option>    
									<option value="3">3</option>    
									<option value="4">4</option>    
									<option value="5">5</option> 
									<option value="6">6</option>    
									<option value="7">7</option>    
									<option value="8">8</option>    
									<option value="9">9</option>    
									<option value="10">10</option> 

								 </select> 
								<select name="filtroprov" title="Seleccione la provincia por la cual filtrar" onchange="showCities(this.value, -1)">
									<?php
										if(isset($_GET['filtroprov'])){//Si se recibe un criterio de filtrado, se lo define como "selected"
											echo '<option value="">Todas las provincias</option>';
											echo generarHTMLInternoSelect('provincias','cod_prov','nombreprov',$_GET['filtroprov']); /*esta funcion simplemente crea el Select con los datos de la BD*/
										}
										else{
											echo '<option selected="selected" value="">Provincia</option>';
											echo generarHTMLInternoSelect('provincias','cod_prov','nombreprov');
										}
									?>
								</select>
								<div id="ciudad">
								<select name="ciudad" title="Seleccione la localidad por la cual filtrar"></select>
								</div>
								<input type="date" id="fecha1" name="filtrofechaini" placeholder="Fecha inicio">
								<input type="date" id="fecha2" name="filtrofechafin" placeholder="Fecha fin">
								
							</div>
							<input type="submit" value="filtrar" id="filtrarbtn" />
							<a href="#" rel="nofollow" onclick="toggleAdvancedSearch()">
							Opciones avanzadas</a>
							
						</form>
					<br/>

				<h2>Últimos couchs dados de alta</h2>
				<br/>
<?php
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
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

$query = "SELECT idcouch, titulo, path, nombrecouch, capacidad, nombreloc, nombreprov, nombretipo, premium FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) INNER JOIN usuarios u ON (u.iduser = c.iduser) WHERE visibilidad = 1 ORDER BY idcouch DESC LIMIT ".$start.",".$page_size;
/*queda v aceptar criterios para filtrado*/
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
/*if ($result) {*/
	while ( $row = mysqli_fetch_array($result) ) {
	/*REVISAR! Los DIV contenedores se deforman en altura por las diferentes longitudes del texto*/
				if ($row['premium']){
					$imgpath = $row['path'];
				}
				else{
					$imgpath = "default_couch.png";
				}

				echo '<div class="tablecontainer">
						<table class="listado">
							<tr>
								<td>'.$row['titulo'].'</td>
							</tr>	
							<tr>
								<td><img src="imgcouch/'.$imgpath.'" alt="Foto de couch"/></td>
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