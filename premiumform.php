<?php
include 'header.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
?>
	<form action="convertirPremium.php" method="POST">
		<fieldset>
			<legend> Convertirse a usuario premium</legend>
			<br/>
			<label> Para convertirse a usuario premium debe abonar por única vez la suma de $10 (diez pesos). El pago es unicamente mediante tarjeta de crédito</label>
			<br/>
			
			<label for="cardnumer"> * Número de tarjeta:</label>  
			<input type="text" id="cardnumber" name="cardnumber" placeholder="Ingrese el número de su tarjeta de crédito" required="">
			<label for="namedueno"> * Nombre del titular:</label>  
			<input type="text" id="namedueno" name="namedueno" placeholder="Ingrese el nombre del titular" required="">
			<label for="apedueno"> * Apellido del titular:</label>  
			<input type="text" id="apedueno" name="apedueno" placeholder="Ingrese el apellido del titular" required="">
			<label for="idnumber"> * Código de verificación:</label>  
			<input type="text" id="idnumber" name="idnumber" placeholder="Ingrese el código de verificación de tarjeta" required="">
			<label for="fechavto"> * Fecha de vencimiento de tarjeta:</label>        
			<input type="text" name="fechavto" id="fechavto" placeholder="mm-aaaa" required="">
			<label for="tarjeta"> * Tarjeta de Crédito:</label>
			<select name="tarjeta">    
			<option selected value="0"> Elige una opción </option>
			<option value="Visa">Visa</option>    
			<option value="American Express">American Express</option>    
			<option value="MasterCard">MasterCard</option>    
			<option value="Naranja">Naranja</option>    
			<option value="Cabal">Cabal</option> 
			</select>
			<br/>
			<input type="submit" value="Aceptar" name="Aceptar" class="submitbtn" onclick="enviar()">
			<a href="http://localhost/couchinnxampp/"><input type="button" value="Cancelar" class="submitbtn"> </a>
			<label> (*) Campo obligatorio</label>
		</fieldset>
	</form>	
<?php
include 'footer.php';
?>