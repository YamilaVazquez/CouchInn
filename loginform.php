<?php
include 'header.php';
if (Sesion::estaLogueado()){
	header('Location: index.php');
}
//Si se recibe algun mensaje se genera el cartel: se recibe mensaje=texto y tipo=[Correcto|Error|Info|Atencion]
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
?>

		<form name="formlogin" method="post" action="login.php">
			<fieldset>
				<legend>Iniciar Sesión</legend>
				<br/>
				<label for="correo"> E-mail:</label>
				<input type="email" name="mail" id="correo" placeholder="nombre@mail.com" required="">
				<label for="clave"> Contraseña:</label>
				<input type="password" name="pass" placeholder="Ingrese su contraseña" id ="clave" required="">
				<?php
					echo '<input type="hidden" name="location" value=""';
						if(isset($_GET['location'])) {
							echo htmlspecialchars($_GET['location']);
						}
					echo '" />';
				?>
				<input type="submit" value="Aceptar" class="submitbtn" onclick="enviar()">
				<a href="index.php"><input type="button" value="Cancelar" class="submitbtn"></a>
			</fieldset>
			<a href="passrecoveryform.php">Recuperar contraseña</a>
  	</form>	
<?php
include 'footer.php';
?>