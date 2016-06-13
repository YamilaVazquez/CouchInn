<?php
include 'header.php';
include_once 'funciones.php';
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
$conexion= conectar();

if (isset($_GET) && (!empty($_GET['id']))) { //pregunto si se oprimió y no es vacío el id CREO
	$query = "SELECT idcouch, nombrecouch, capacidad, nombreloc, nombreprov, nombretipo, nombre, apellido  FROM tipocouch t INNER JOIN couchs c ON (t.idtipo = c.idtipo) INNER JOIN localidades l  ON(c.cod_loc = l.cod_loc) INNER JOIN provincias p ON(l.cod_prov = p.cod_prov) INNER JOIN usuarios u ON (c.iduser = u.iduser) WHERE idcouch =" .$_GET['id'];	//ver el order by si agregar
	
	$result = mysqli_query($conexion, $query);
	
	$consulta= "SELECT path FROM imgcouchs i INNER JOIN couchs c ON (i.idcouch = c.idcouch) WHERE (i.idcouch =".$_GET['id'] .")";
	$resultado = mysqli_query($conexion, $consulta);
	if (mysqli_num_rows($resultado) > 0) {
		$fila = mysqli_fetch_array($resultado);
		$path_for_image = "./imgcouch".$fila['path'];
	}
	else{
		$path_for_image = "./default_couch.png";
	}	
	
	if (mysqli_num_rows($result) > 0) { 
		$row = mysqli_fetch_array($result);
		echo '<form> 
			<fieldset>
				<legend> Detalle de couch</legend>
				<br/>
				<table style="width:100%"  padding: 5px>
				  
				  <tr>
				    <td><b>Titulo:</b> </td>
				    <td>Casa en el lago</td>
				  </tr>
				  <tr>
				    <td><b>Categoría:</b></td>
				    <td>'.$row['nombretipo'].'</td>
				  </tr>
				  <tr>
				    <td><b>Descripción:</b></td>
				    <td>'.$row['nombrecouch'].'</td> 
				  </tr>
				  <tr>
				    <td><b>Capacidad:</b></td>
				    <td>'.$row['capacidad'].' </td>
				  </tr>
				  <tr>
				    <td><b>Provincia:</b></td>
				    <td>'.$row['nombreprov'].' </td>
				  </tr>
				  <tr>
				     <td><b>Localidad:</b> </td>
				     <td>'.$row['nombreloc'].'</td>
				  </tr>
				  <tr>
				     <td><b>Anfitrión:</b> </td>
				     <td>'.$row['nombre'].'</td>
				  </tr>
				  </br>
				  <tr>
					<td colspan="3" align="center" valign="middle"><img src='.$path_for_image.' alt="Foto de couch" style="width:330px;height:250px" border= 2px/></td>
				  </tr>';
 				if (Sesion::estaLogueado()){
					echo '<tr>
				 			<td colspan="2" align="center" valign="middle"></br><a href= #><input type="button" value="Reservar" class="submitbtn"> </a> </td>
				  		 </tr>	';
				}
				echo'</table>
			</fieldset>
			</form>';	
		}	
}
include 'footer.php';

desconectar($conexion);
?>

