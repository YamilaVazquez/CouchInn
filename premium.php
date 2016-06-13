<?php
include_once 'header.php';

try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
if (Sesion::esPremium()) {
	$msj= "Error. El usuario ya es premium";
    header('Location: index.php?mensaje='.urlencode($msj).'&tipo=Atencion');
}

$whitelist = array('cardnumber', 'namedueno', 'apedueno', 'idnumber', 'fechavto', 'tarjeta'); //field name que se aceptaran
$errors = array();
$fields = array();

if (!empty($_POST)) {

    function validarFechavto($campo){
		$campo  = explode('-', $campo); //formato mm-aaaa [1]año [0]mes
		if (count($campo) == 2) {
			if (checkdate($campo[0], 01, $campo[1])) { /*checkdate ( int $MES, int $DIA ,int $AÑO ).Retorna True si es valida*/
				$actual=date_create(null);
				date_time_set($actual,0,0,0);
				$fecha=date_create($campo[1].'-'.$campo[0].'-'."01");
				if($fecha > $actual){
					return true; // fecha valida.
				}
				else{
					return false;
				}
			} else {
				return false; // fecha invalida
			}
		} else {
			return false;// no corresponde con el formato esperado.
		}
	}

	if (!is_numeric($_POST['cardnumber'])) {
		$errors[] = '<div class="cartelAtencion">Los dígitos de la tarjeta deben ser numéricos</div>';
	}
	
	if (!(strlen($_POST['cardnumber']) == 16)) {
		$errors[] = '<div class="cartelAtencion">La cantidad de dígitos ingresados de la tarjeta es incorrecta</div>';
	}
	
	if (!campoLetras($_POST['namedueno'])) {
		$errors[] = '<div class="cartelAtencion">El nombre no puede contener números y/o símbolos</div>';
	}
	
	if (!campoLetras($_POST['apedueno'])) {
		$errors[] = '<div class="cartelAtencion">El apellido no puede contener números y/o símbolos</div>';
	}
	
	if (!is_numeric($_POST['idnumber'])) {
		$errors[] = '<div class="cartelAtencion">El código de verificación debe ser numérico</div>';
	}
	
	if (!(strlen($_POST['idnumber']) == 3)) {
		$errors[] = '<div class="cartelAtencion">El código de verificación es incorrecto</div>';
	}
	
	if (!validarFechavto($_POST['fechavto'])) {
		$errors[] = '<div class="cartelAtencion">La tarjeta está vencida o la fecha de vencimiento ingresada es inválida</div>';
	}
	
	/*if ($_POST['tarjeta'] == 0) {*/
	/*if (is_numeric($_POST['tarjeta'])) {
		$errors[] = '<div class="cartelAtencion">Seleccione una tarjeta de la lista</div>';
	}*/
	
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
		$usuarioid = Sesion::idUsuario();
		$query = "UPDATE usuarios SET premium = 1 WHERE iduser =".$usuarioid;
		$conexion = conectar();
		$fila = mysqli_query($conexion, $query);    //ver comillas
		Sesion::convertirVIP();
		$msj= "¡Felicitaciones!. Usted ya es premium";
		header('Location: index.php?mensaje='.urlencode($msj).'&tipo=Correcto');
	}
	
}
if (!empty($errors)) {
	/*print_r(array_values($errors));*/
	$length=count($errors);
	for ($i=0;$i<$length;$i++)
	echo $errors[$i];
}
/*    function validaringreso(){
        if (isset($_POST['cardnumber'])) {
            if(isset($_POST['namedueno']) && (isset($_POST['apedueno']))){
                if (isset($_POST['idnumber'])) {
                    if (isset($_POST['fechavto'])) {
                        return true;
                    }
                    else
                        echo "Error, falta ingresar fecha de vencimiento de tarjeta";
                        return false;
                }
                else{
                    echo "Error, falta ingresar el código de verificación";
                    return false;
                }
            }
            else{
                echo "Error, falta ingresar el nombre y apellido";
                return false;
            }
        }
        else{
            echo "Error, falta ingresar el número de la tarjeta";
            return false;
        }   
    }  */

?>
	<form action="premium.php" method="POST">
		<fieldset>
			<legend> Convertirse a usuario premium</legend>
			<br/>
			<label> Para convertirse a usuario premium debe abonar por única vez la suma de $10 (diez pesos). El pago es unicamente mediante tarjeta de crédito</label>
			<br/>
			
			<label for="cardnumer"> * Número de tarjeta:</label>  
			<input type="text" id="cardnumber" name="cardnumber" placeholder="Ingrese el número de su tarjeta de crédito" value="<?php echo isset($fields['cardnumber']) ? ($fields['cardnumber']) : '' ?>" required="">
			<label for="namedueno"> * Nombre del titular:</label>  
			<input type="text" id="namedueno" name="namedueno" placeholder="Ingrese el nombre del titular" value="<?php echo isset($fields['namedueno']) ? ($fields['namedueno']) : '' ?>" required="">
			<label for="apedueno"> * Apellido del titular:</label>  
			<input type="text" id="apedueno" name="apedueno" placeholder="Ingrese el apellido del titular" value="<?php echo isset($fields['apedueno']) ? ($fields['apedueno']) : '' ?>" required="">
			<label for="idnumber"> * Código de verificación:</label>  
			<input type="text" id="idnumber" name="idnumber" placeholder="Ingrese el código de verificación de tarjeta" value="<?php echo isset($fields['idnumber']) ? ($fields['idnumber']) : '' ?>" required="">
			<label for="fechavto"> * Fecha de vencimiento de tarjeta:</label>        
			<input type="text" name="fechavto" id="fechavto" placeholder="mm-aaaa" value="<?php echo isset($fields['fechavto']) ? ($fields['fechavto']) : '' ?>" required="">
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
			<a href="index.php"><input type="button" value="Cancelar" class="submitbtn"> </a>
			<label> (*) Campo obligatorio</label>
		</fieldset>
	</form>	
<?php
include 'footer.php';
?>