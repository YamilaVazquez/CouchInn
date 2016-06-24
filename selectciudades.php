<?php
include_once 'conexion.php';
if (!empty($_GET['id'])) {
	$codprov = intval($_GET['id']);
	$query = "SELECT * FROM localidades WHERE cod_prov = '$codprov'";
	$conn = conectar();
	$result = mysqli_query($conn, $query);
	/*if ($result) {*/
		$select='<select name="ciudad" title="Seleccione una ciudad">
				<option value=""> Elige una ciudad </option>';
		if (!empty($_GET['selectedloc'])) {
			$idDefault = $_GET['selectedloc'];
		}else{
			$idDefault = -1;
		}
		while ($row = mysqli_fetch_array($result)) {
			$select =$select.'<option value="'.$row['cod_loc'].'" ';
			$select.=($row['cod_loc'] == $idDefault) ? 'selected' : '';
			$select.=' >'.$row['nombreloc'].'</option>';
		}
		$select =$select.'</select>';
	/*}*/
	desconectar($conn);
	echo $select;
}else {
	echo '<select name="ciudad" title="Seleccione una ciudad">
		<option selected value=""> Elige una ciudad </option>
		</select>';
}
?>