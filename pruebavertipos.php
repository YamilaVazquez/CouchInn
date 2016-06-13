<?php
include 'conexion.php';
include 'header.php';
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
	$conexion= conectar();
	
	echo '<form > 
		<fieldset>
			<legend> Tipos de couchs</legend>
			<br/>
			<a width=100px, href= "tipocouchnew.php"> Agregar nuevo tipo <br/></a> <br/>';
			$resultado = mysqli_query($conexion, "SELECT * FROM tipocouch WHERE bajalogica = 0");
			if ($resultado){
				while ($fila = mysqli_fetch_array($resultado)) {		
					echo '
					<table width="300">
					<tr>
					<td width= 100px>'.$fila['nombretipo'].' </td>
					<td width= 100px> <a href= "eliminartipo.php?id='.$fila['idtipo'].'" onclick="return confirm(\'¿Esta seguro que desea eliminar '.$fila['nombretipo'].'? \')" title="Borre este tipo de couch">Eliminar</a></td>
					<td width= 100px> <a href= "modificartipo.php?id='.$fila['idtipo'].'&name='.$fila['nombretipo'].'">Modificar</a> </td>
		
					</tr>
				  
					</table>';
				}	
			}
			else{
				echo 'No hay tipos de couchs';
			}	
		echo '</fieldset>
	</form>';

include 'footer.php';
desconectar($conexion);
?>