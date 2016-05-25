<?php
include 'header.php';
?>
		<form action="login.php">
			<fieldset>
				<legend>Iniciar Sesión</legend>
				<br/>
				<label for="correo"> E-mail:</label>
				<input type="email" id="correo" placeholder="nombre@mail.com" required="">
				<label for="contraseña"> Contraseña:</label>
				<input type="password" placeholder="Ingrese su contraseña" name="contraseña1" id ="contraseña1" required="">
				<input type="submit" value="Aceptar" class="submitbtn" onclick="enviar()">
				<input type="submit" value="Cancelar" class="submitbtn">
				<label for="recupero">Recuperar contraseña</label>
			</fieldset>
  	</form>	
<?php
include 'footer.php';
?>