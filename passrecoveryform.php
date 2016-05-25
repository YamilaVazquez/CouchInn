<?php
include 'header.php';
?>
	<form action="passrecovery.php">
        <fieldset>
          <legend>Recuperar contraseña</legend>
          <br/>
          <label> Ingrese su mail y se le enviará su contraseña</label>
          <br/>
	       <label for="correo"> E-mail:</label>
          <input type="email" id="correo" placeholder="nombre@mail.com" required="">
          <input type="submit" value="Aceptar" class="submitbtn" onclick="enviar()">
          <input type="submit" value="Cancelar" class="submitbtn" oncancel="">
        </fieldset>
  	</form>	
<?php
include 'footer.php';
?>