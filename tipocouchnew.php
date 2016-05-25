<?php
include 'header.php';
?>
	<form action="newcategory.php">
		<fieldset>
			<legend> Crear tipo Couch</legend>
			<br/>
			<label for="tipoCouch"> Nombre:</label>
			<input type="text" name="tipoCouch" id="tipoCouch" placeholder="Ingrese nuevo tipo de couch" required="">
			<input type="submit" value="Aceptar" class="submitbtn" name="aceptar" onclick="enviar()">
			<input type="submit" value="Cancelar" class="submitbtn">
		</fieldset>
	</form>	
<?php
include 'footer.php';
?>