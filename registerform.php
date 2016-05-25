<?php
include 'header.php';
?>
		<form action="register.php">
			<fieldset>
				<legend>Información personal</legend>
				<br/>
				<label for="nombre"> * Nombre:</label>		
				<input type="text" id="nombre" placeholder="Escribe tu nombre" required="">
				<label for="apellido"> * Apellido:</label>
				<input type="text" id="apellido" placeholder="Escribe tu apellido" required="">
				<label for="fecha"> * Fecha de nacimineto:</label>        <!-- Validar que la fecha de nac sea > a 18 años-->
				<input type="date" id="fecha" placeholder="dd/mm/aaaa" required="">
				<label for="correo"> * E-mail:</label>
				<input type="email" id="correo" placeholder="nombre@mail.com" required="">
				<label for="descripcion"> Descripción personal:</label>
				<input type="text" id="descripcion" placeholder="Describase en unas palabras">
				<label for="contraseña"> * Contraseña:</label>
				<input type="password" placeholder="Ingrese su contraseña" name="contraseña" id ="contraseña" required="">
				<label> (*) Campo obligatorio </label>
				<div>
					<input type="submit" value="Aceptar" class="submitbtn" onclick="enviar()">
					<input type="submit" value="Cancelar" class="submitbtn" onclick="index2.html">
				</div>
			</fieldset>
		</form>

		<script type="text/javascript" src="validaciones.js"></script>
<?php
include 'footer.php';
?>