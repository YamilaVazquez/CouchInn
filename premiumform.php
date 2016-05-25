<?php
include 'header.php';
?>
	<form action="premium.php">
		<fieldset>
			<legend> Convertirse a usuario premium</legend>
			<br/>
			<label> Para convertirse a usuario premium debe abonar por única vez la suma de $10 (diez pesos). El pago es unicamente mediante tarjeta de crédito</label>
			<br/>
			<label for="tarjeta"> * Tarjeta de Crédito:</label>
			<input type="text" name="tarjeta" placeholder="Ingrese su tarjeta de crédito" required="">
			<input type="text" id="cardnumber" name="cardnumber" placeholder="Ingrese el número de su tarjeta de crédito" required="">
			<input type="submit" value="Aceptar" class="submitbtn" onclick="enviar()">
			<input type="submit" value="Cancelar" class="submitbtn">
			<label> (*) Campo obligatorio</label>
		</fieldset>
	</form>	
<?php
include 'footer.php';
?>