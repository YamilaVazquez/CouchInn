<?php
	require_once "header.php";
	require_once "funciones.php";
$whitelist = array('name', 'apellido', 'fecha', 'pass', 'mail', 'nacion', 'phone'); //field name que se aceptaran
$errors = array();
$fields = array();

if (!empty($_POST)) {
	//validar campo a campo
	if (!campoMail($_POST['mail'])) {
		//si no es valido
		$errors[] = '<div class="cartelAtencion">El mail no es válido</div>';
	}else {
		//busco en bd a ver si el mail existe
		$link = conectar();
		$query = "SELECT * FROM usuarios WHERE email = '".$_POST['mail']."'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		desconectar($link);
		if ($row) {
			//esta en la BD y no se puede usar
			$errors[] = '<div class="cartelAtencion">El mail ya se encuentra en uso</div>';
		}
	}
	
	if (!campoLetras($_POST['name'])) {
		$errors[] = '<div class="cartelAtencion">Su nombre debe tener al menos 2 caracteres, los cuales no pueden ser números o símbolos</div>';
	}
	
	
	if (!campoLetras($_POST['apellido'])) {
		$errors[] = '<div class="cartelAtencion">Su apellido debe tener al menos 2 caracteres, los cuales no pueden ser números o símbolos</div>';
	}
	
	
	if (!validarFechaNacimiento($_POST['fecha'])) {
		$errors[] = '<div class="cartelAtencion">Fecha de nacimiento inválida. La fecha es incorrecta o usted es menor de 18 años</div>';
	}
	
	
	if (!campoObligatorio($_POST['pass'])) {
		$errors[] = '<div class="cartelAtencion">Contraseña inválida. Debe tener más de 3 caracteres</div>';
	}
	
	if(!campoLetras($_POST['nacion'])){
		$errors[] = '<div class="cartelAtencion">Su nacionalidad debe tener al menos 2 caracteres, los cuales no pueden ser números o símbolos</div>';
	}
	
	if (!campoObligatorio($_POST['phone'])) {
		$errors[] = '<div class="cartelAtencion">El teléfono ingresado no es válido</div>';
	}
	
	
	foreach ($whitelist as $key) {
		$fields[$key] = $_POST[$key];
	}
	
	foreach ($fields as $field => $data) {
		if (empty($data)) {
			$errors[] = '<div class="cartelAtencion">Debe completar su '.$field.'</div>';
		}
	}
	//chequear si todo ok y procesar los datos
	if (empty($errors)) {
		$arregloFecha = explode('-',$fields['fecha']); //formato dd/mm/aaaa [0]dia [1]mes[2]año (Chrome envia en este formato los input date)
		/*$fecha = $arregloFecha[2].'-'.$arregloFecha[1].'-'.$arregloFecha[0] ; AAAA-MM-DD formato para tipo Date en BD*/
		$fecha = $_POST['fecha'];
		$query1 = "INSERT INTO usuarios (email, pass, nombre, apellido, fechanac, nacionalidad, telefono) VALUES ('".$fields['mail']."', '".$fields['pass']."', '".$fields['name']."', '".$fields['apellido']."', '".$fecha."', '".$fields['nacion']."', '".$fields['phone']."')";
		$link = conectar();
		$result = mysqli_query($link, $query1);
		desconectar($link);
		if ($result) {
			$msj = "Registro exitoso. Ya puede iniciar sesión en el sistema con su e-mail y password";
			header('Location: loginform.php?mensaje='.urlencode($msj).'&tipo=Info');
		}else {
			echo '<div class="cartelError">Error. Por favor vuelva a intentarlo más tarde</div>';
		}
	}
}
if (!empty($errors)) {
	/*print_r(array_values($errors));*/
	$length=count($errors);
	for ($i=0;$i<$length;$i++)
	echo $errors[$i];
}
?>
<form name="nuevoUsuario" action="register.php" onsubmit="return validar();" method="post"">
	<fieldset>
		<legend>Información personal</legend>
		<br/>
		<label for="nombre"> * Nombre:</label>		
		<input type="text" name="name" id="nombre" placeholder="Escribe tu nombre" value="<?php echo isset($fields['name']) ? ($fields['name']) : '' ?>" required="">
		<label for="apellido"> * Apellido:</label>
		<input type="text" name="apellido" id="apellido" placeholder="Escribe tu apellido" value="<?php echo isset($fields['apellido']) ? ($fields['apellido']) : '' ?>" required="">
		<label for="fecha"> * Fecha de nacimiento:</label>
		<input type="date" name="fecha" id="fecha" placeholder="dd-mm-aaaa" value="<?php echo isset($fields['fecha']) ? ($fields['fecha']) : '' ?>" required="">
		<label for="nacionalidad"> *Nacionalidad:</label>
		<input type="text" name="nacion" id="nacionalidad" placeholder="argentina" value="<?php echo isset($fields['nacion']) ? ($fields['nacion']) : '' ?>" required="">
		<label for="tel"> *Teléfono:</label>
		<input type="text" name="phone" id="tel" placeholder="221 15 1234567" value="<?php echo isset($fields['phone']) ? ($fields['phone']) : '' ?>" required="">
		<label for="correo"> * E-mail:</label>
		<input type="email" name="mail" id="correo" placeholder="nombre@mail.com" value="<?php echo isset($fields['mail']) ? ($fields['mail']) : '' ?>" required="">
		<label for="clave"> * Contraseña:</label>
		<input type="password" placeholder="Ingrese su contraseña" name="pass" id ="clave" value="<?php echo isset($fields['pass']) ? ($fields['pass']) : '' ?>" required="">
		<label> (*) Campo obligatorio </label>
		<div>
			<input type="submit" value="Enviar" class="submitbtn">
			<input type="button" value="Cancelar" class="submitbtn" onclick="window.location='index.php';">
		</div>
	</fieldset>
</form>
<script type="text/javascript" src="validaciones.js"></script>
<script>
function validar() {
	var formulario= document.nuevoUsuario;
	if(campoMail(formulario.mail)) {
		if(campoObligatorio(formulario.pass)) {
			if(campoObligatorio(formulario.name)) {
				if(campoObligatorio(formulario.apellido)) {
					if(campoObligatorio(formulario.nacion)) {
						if(campoObligatorio(formulario.phone)) {
							return true;
						}
						else {
							alert('Ingrese un telefono valido.');
							return false;
						}
					}
					else{
						alert('Ingrese una nacionalidad valida.');
						return false;
					}
				}
				else{
					alert('Complete su apellido');
					return false;
				}
			}
			else{
				alert('Complete su nombre.');
				return false;
			}
		}
		else{
			alert('Ingrese una contraseña valida.');
			return false;
		}
	}
	else{
		alert('Ingrese un eMail válido.');
		return false;
	}
};
</script>