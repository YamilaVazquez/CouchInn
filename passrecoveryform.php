<?php
include 'header.php';
if(isset($_GET['mensaje'])){
  echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
?>
	<form action="passrecovery.php" method="post">
        <fieldset>
          <legend>Recuperar contraseña</legend>
          <br/>
          <label> Ingrese su mail y se le enviará su contraseña</label>
          <br/>
	       <label for="correo"> E-mail:</label>
          <input type="email" name="correo" id="correo" placeholder="nombre@mail.com" required="">
          <input type="submit" value="Aceptar" name="Aceptar" class="submitbtn" onclick="enviar()">
          <a href="http://localhost/couchinnxampp/"> <input type="button" value="Cancelar" class="submitbtn"> </a>
        </fieldset>
  	</form>	
<?php
include 'footer.php';
?>