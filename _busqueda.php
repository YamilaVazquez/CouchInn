<?php
include_once 'header.php';
include_once 'conexion.php';
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
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
};
function validar(){
	var fin = document.getElementById('fecha2');
	var ini = document.getElementById('fecha1');
	if (validarFecha(ini)) {
		if (validarFecha(fin)) {
			if (validarPeriodo(ini, fin)) {
				return true;
			} else {return false;}
		} else {return false;}
	} else {return false;}
};
</script>

Filtrar:

	<form name="filtrar" onsubmit="return validar()" id="filtrar">
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
			<input type="date" name="filtrofechaini" id="fecha1" placeholder="Fecha inicio">
			<input type="date" name="filtrofechafin" id="fecha2" placeholder="Fecha fin">
		</div>
		<input type="submit" value="filtrar" id="filtrarbtn" />
		<a href="#" rel="nofollow" onclick="toggleAdvancedSearch()">Opciones avanzadas</a>
	</form>
<br/>

<?php 

$consultaSQL ="SELECT * FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN usuarios u ON (c.iduser = u.iduser) WHERE visibilidad = 1"; /*la consulta genérica*/
$string= "";
if (! empty($_GET)) { /*según el criterio de filtrado recibido, se concatena a la consulta la condición correspondiente*/
	/*if (! empty($_GET['filtrofechaini']) && ! empty($_GET['filtrofechafin'])) {
		$consultaAcep = "SELECT * FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch) WHERE ((fechaini BETWEEN CAST(".$_GET['filtrofechaini']." AS DATE ) AND CAST(".$_GET['filtrofechafin']."AS DATE )) 
			OR
			(fechafin BETWEEN CAST( ".$_GET['filtrofechaini']." AS DATE ) AND CAST( ".$_GET['filtrofechafin']." AS DATE )) 
			OR 
			(estado = 'aceptada'))";
		$consultaSQL= "$consultaSQL EXCEPT $consultaAcep"; //cambie la consulta sql para poder agregar la otra.	
		$string.= "fecha inicio: ".$_GET['filtrofechaini']." fecha fin: ".$_GET['filtrofechafin']." </br>"; 
	}*/
	
	if(! empty($_GET['filtrocategoria'])) { //por cada filtro esto
		$consultaSQL.=' AND (c.idtipo = '.$_GET['filtrocategoria'].') AND ( t.bajalogica = 0)'; //voy concatenando where.
		$conexion = conectar();

		$result= mysqli_query($conexion, "SELECT * FROM tipocouch WHERE idtipo =".$_GET['filtrocategoria']." AND (bajalogica = 0)");
		$row = mysqli_fetch_array ($result);
		$string.= "Categoría: ".$row['nombretipo']."</br>";
	}
	if (! empty($_GET['filtrotitulo'])) {
		$consultaSQL.=" AND (c.titulo  LIKE '%".$_GET['filtrotitulo']."%')"; //%sofa_%
		$string.= "Título:".$_GET['filtrotitulo']."</br>";
	}
	if (! empty($_GET['filtrocapac'])) {
		$consultaSQL.=' AND (c.capacidad= ' .$_GET['filtrocapac'].')';
		$string.= "Capacidad:".$_GET['filtrocapac']."</br>";
	}
	if (! empty($_GET['filtroprov']) && ! empty($_GET['ciudad'])) {
		$consultaSQL.=' AND (c.cod_loc=' .$_GET['ciudad'].')';
		
		$conexion = conectar();

		$result= mysqli_query($conexion, "SELECT * FROM provincias p INNER JOIN localidades l WHERE p.cod_prov =".$_GET['filtroprov']." AND cod_loc=".$_GET['ciudad']);
		$row = mysqli_fetch_array ($result);
		$string.= "Localidad:".$row['nombreloc'].", ".$row['nombreprov']."</br>";
	}
	
}
$conexion = conectar();
$result= mysqli_query($conexion, $consultaSQL);
if (!empty($_GET['filtrofechaini']) && !empty($_GET['filtrofechafin'])) {
	/*$fecha_actual = date("Y-m-d");
	$fechaini=$_GET['filtrofechaini'];
	$fechafin=$_GET['filtrofechafin'];
	if ($fechaini > $fecha_actual) { //si fini > factual BIEN
		if ($fechaini > $fechafin) {	//si fini > ffin MAL
			$msj= "Error. Fecha de inicio menor a la fecha final";
          	header('Location:index.php&mensaje='.urlencode($msj).'&tipo=Atencion');
		}
	}
	else{
		$msj= "Error. Fecha de inicio menor a la fecha actual";
       	echo $msj;
        header('Location:index.php&mensaje='.urlencode($msj).'&tipo=Atencion');	
	}*/		


	$string.= "fecha inicio: ".$_GET['filtrofechaini']." fecha fin: ".$_GET['filtrofechafin']." </br>"; 
}

echo "<h4>Filtrado por </br> $string</h4>";

if (mysqli_num_rows($result)> 0){

	while ($row = mysqli_fetch_array ($result)) {
		$conexion = conectar();
		if (!empty($_GET['filtrofechaini']) && !empty($_GET['filtrofechafin'])) {
			$fechaini=$_GET['filtrofechaini'];
			$fechafin=$_GET['filtrofechafin'];
			$couchid=$row['idcouch'];
			$fecha_actual = date("Y-m-d");
			
				
			$consultafecha= "SELECT count(*) AS cant FROM reservas r WHERE ((idcouch = '$couchid') AND 
			((DATE('$fechaini') BETWEEN r.fechaini  AND r.fechafin) OR 
			(DATE('$fechafin') BETWEEN r.fechaini AND r.fechafin)) AND
			(r.estado = 'aceptada'))";
				$resultado = mysqli_query($conexion, $consultafecha);
				$raw= mysqli_fetch_array($resultado);
				if ($raw['cant'] == '0') {
					mostrar($row);
				}	
					
		}
		else{
			mostrar($row);
		}
			
	}
}



function mostrar($row){
	if ($row['premium']){
			$imgpath = $row['path'];
		}
		else{
			$imgpath = "default_couch.png";
	}

	echo '<div class="tablecontainer">
				<table class="listado">
					<tr>
						<td><img src="imgcouch/'.$imgpath.'" alt="Foto de couch"/></td>
					</tr>
					<tr>
						<td>'.$row['titulo'].'</td>
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
//ver filtro fecha. Todas menos las que están aceptadas en esa fecha, poner primero en la concatenación de sql.
//Mostrar los filtros
//dejar guardados los filtros o sacarlos
// ver si mostrar el index o ahi

include 'footer.php';
?>