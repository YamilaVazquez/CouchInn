<?php
require_once 'sesion.php';
include 'header.php';
try{
	Sesion::estaAutorizado(true);
}
catch(Exception $e){
	header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
	$conexion= conectar();
	$resultado = mysqli_query($conexion, "SELECT * FROM tipocouch WHERE bajalogica = 0");	
	if ($resultado){
		while ($fila = mysqli_fetch_array($resultado)) {		
			echo '<tr>
				<td>'.$fila['nombretipo'].'</td>
				<td><a href= "eliminartipo.php?id='.$fila['idtipo'].'>Editar</a> </td>
				<td><a href= "eliminartipo.php?id='.$fila['idtipo'].'>Editar</a> </td>
				<td><a href= "eliminartipo.php?id='.$fila['idtipo'].'>Eliminar</a> </td>
			</tr><br/>';
		}	
	}
	else{
		echo "No hay tipos de couchs";
	}	
	echo '<a href= "tipocouchnew.php"> Agregar</a>';

	

include 'footer.php';
desconectar($conexion);
?>