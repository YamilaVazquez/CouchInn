<?php
include 'header.php';
?>
<form name="formularioCrear" enctype="multipart/form-data" onsubmit="return validar();" method="post" action='couchnew.php'>
	<fieldset>
		<legend>Publicar nuevo Couch</legend>
		<br/>
		<label for="descrip">* Descripcion:</label>
		<textarea rows="10" cols="40" name="descripcion" id="descrip" maxlength="500"></textarea><br/>
		Capacidad: <input type="text" name="capacidad" maxlength="2" value="" placeholder="1"/><br/>
		Localidad:<select name="ciudad" title="Seleccione una ciudad por la cual filtrar">
						<option value="opc">Ciudad1</option>
						<option value="opc2">Ciudad2</option>
				</select><br/>
<!--</select> php>echo generarSelect('tabla','idcampo','nombre_campo_a_mostrar?');?>-->
		<input type="hidden" name="MAX_FILE_SIZE" value="2096128"/>
		<label>Imagen:</label>
		<input type="file" accept="image/*" name="imagen"/>
		<br/>
		<input type="submit" value="Crear" title="Cree un nuevo couch" class="submitbtn"/>
		<input type="reset" value="Cancelar" title="cancelar carga de formulario" class="submitbtn"/>	
	</fieldset>
</form>
<?php
include 'footer.php';
?>