<?php
require_once 'header.php';
require_once 'funciones.php';
require_once 'sesion.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
/*CALIFICACION DEL HUESPED AL COUCH - acceso desde hueshistorial.php*/
if (!empty($_POST)) {

	$whitelist = array('calif', 'comment', 'idreserva'); //field name que se aceptaran
	$errors = array();
	$fields = array();
	if (strlen($_POST['comment']) < 10) { /*validar por un minimo de caracteres mas grande*/
		$errors[] = '<div class="cartelAtencion">Debe escribir un comentario justificando su calificación. Mínimo 10 caracteres</div>';
	}
	if (!is_numeric($_POST['calif'])) {
		$errors[] = '<div class="cartelAtencion">La calificación debe ser un número entre 1 y 10</div>';
	}
	if (empty($_POST['idreserva'])) {
		$errors[] = '<div class="cartelError">Ocurrió un error. Intente nuevamente</div>';
	}

	foreach ($whitelist as $key) {
		$fields[$key] = $_POST[$key];
	}
	
	foreach ($fields as $field => $data) {
		if (empty($data)) {
			$errors[] = '<div class="cartelAtencion">Debe completar todos los campos</div>';
		}
	}
	if (empty($errors)) {
		$iduser = sesion::IdUsuario();
		$conn = conectar();
		$queryreserva = "SELECT * FROM reservas WHERE (estado = 'concretada') AND (idreserva = ".$fields['idreserva'].")";
		$resultres = mysqli_query($conn, $queryreserva);
		if (mysqli_num_rows($resultres) > 0) {
			/*se podria comprobar si el usuario logueado coincide con el de la reserva a calificar para mas seguridad*/
			$query = "INSERT INTO califhuesped (idreserva, puntajehues, commenthues) VALUES ('".$fields['idreserva']."', '".$fields['calif']."', '".$fields['comment']."')";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$msj = "¡Gracias por calificar este hospedaje!";
				header('Location: hueshistorial.php?mensaje='.urlencode($msj).'&tipo=Info');
			}else {
				$msj = "Error. Por favor vuelva a intentarlo más tarde";
				header('Location: hueshistorial.php?mensaje='.urlencode($msj).'&tipo=Error');
			}
		} else {/*no esta concretada o el id no coincide*/
			$msj = "Error. No tiene permisos para calificar este hospedaje";
			header('Location: hueshistorial.php?mensaje='.urlencode($msj).'&tipo=Error');
		}
	}
	
	if (!empty($errors)) {
		$length=count($errors);
		for ($i=0;$i<$length;$i++)
		echo $errors[$i];
	}
}

if (!empty($_GET['idres'])) {
?>
			<form name="calificarCouch" onsubmit="return validar();" method="POST">
			<fieldset>
				<legend>Califique su estadía</legend>
				<label for="calificacion">* Calificación:</label>
				<?php
					if (!empty($fields['calif'])) {
						$calif = $fields['calif'];
					}else{
						$calif = -1;
					}
					$select = '<select name="calif" title="Eliga el puntaje">';
					for ($i = 1; $i <= 10; $i++) {
						$select =$select.'<option value="'.$i.'" ';
						$select.=($i == $calif) ? 'selected' : '';
						$select.=' >'.$i.'</option>';
					}
					$select =$select.'</select>';
					echo $select;
				?>
				<label for="comment">* Comentario:</label>
				<textarea rows="10" cols="40" name="comment" id="comment" maxlength="200"><?php echo isset($fields['comment']) ? ($fields['comment']) : '' ?></textarea><br/>
				<br/>
				<input type="hidden" name="idreserva" value="<?php echo $_GET['idres']; ?>"/>
				<label> (*) Campo obligatorio </label>
				<div>
					<input type="submit" value="Enviar" class="submitbtn">
					<a href="hueshistorial.php"><input type="button" value="Cancelar" class="submitbtn"></a>
				</div>
			</fieldset>
		</form>
<?php
}else {
	echo '<div id="cartel" class="cartelError">Error. No se recibió ningun hospedaje para calificar</div>';
}
require_once 'footer.php';
?>