<?php
require_once 'header.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
/*
	Genera el código HTML interno de un <select>
		$query:			consulta select a la BD
		$campoId:		nombre de la clave primaria
		$campoVisible:	nombre del campo a mostrar	
		$idDefault:		(Opcional) si se deseea definir una opcion cm default (seleccionada)	
*/
function generarOptionsDeSelect($query,$campoId,$campoVisible,$idDefault=-1){
		$conexion = conectar();
		$resultados= mysqli_query($conexion, $query);
		$html='';
		
		if($resultados){
			while($resultado = mysqli_fetch_array($resultados)){
				$html.= '<option value="'.$resultado[$campoId].'" ';
				$html.=($resultado[$campoId] == $idDefault) ? 'selected' : '';
				$html.=' >'.$resultado[$campoVisible].'</option>';
			}
			mysqli_free_result($resultados);
		}
		desconectar($conexion);
		return $html;
}

function subirImagen () {
	if(isset($_FILES['imagen'])) {
		if(is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			if (filesize($_FILES['imagen']['tmp_name']) < 2096128) {
					if(getimagesize($_FILES['imagen']['tmp_name'])) {
						$directory = "imgcouch/"; //Directorio donde salvar la imagen
						$filename = time().basename($_FILES['imagen']['name']);
						$TargetPath=$directory.$filename; //nombre para la img (o subdirectorio? ver xq no se cuaando o como cambia el tmp_name con este)
						if(move_uploaded_file($_FILES['imagen']['tmp_name'], $TargetPath)){
							return $filename;
						} else {
							return 2;
						}
					} else {
						echo "File is not an image.";
						return 4;
					}
			} else {
				return 3;
				// el tamaño es mayor del permitido
			}
		} else {
			//echo 'Error al subir la imagen al servidor. Por favor intente de nuevo';
			return 2;
		}
	} else {
		return 1;
		// echo 'no se seleccionó ninguna imagen,quedará la default';
	}
}

$whitelist = array('titulo', 'descripcion', 'tipo', 'capacidad', 'provincia', 'ciudad'); //field name que se aceptaran
$errors = array();
$fields = array();

if (!empty($_POST)) {
	//validar campo a campo
	
	if (!campoObligatorio($_POST['titulo'])) {
		//si no es valido
		$errors[] = '<div class="cartelAtencion">Debe ingresar un titulo</div>';
	} 
	if (!campoObligatorio($_POST['descripcion'])) {
		//si no es valido
		$errors[] = '<div class="cartelAtencion">Debe ingresar una descripción</div>';
	}
	
	if (empty($_POST['tipo'])) {
		$errors[] = '<div class="cartelAtencion">Seleccione un tipo de couch de la lista</div>';
	}
	
	if (empty($_POST['capacidad'])) {
		$errors[] = '<div class="cartelAtencion">La capacidad debe ser un valor entre 1 y 10</div>';
	}
	
	if (empty($_POST['provincia'])) {
		$errors[] = '<div class="cartelAtencion">Seleccione una provincia de la lista</div>';
	}
	
	if (empty($_POST['ciudad'])) {
		$errors[] = '<div class="cartelAtencion">Ingrese una localidad</div>';
	}
	
	if (!empty($_FILES['imagen']['name'])) {
		$val = subirImagen();
		if ( $val == 1) {
			$filename = "default_couch.png";
		} else	if ( $val == 2) {
			$errors[] = '<div class="cartelAtencion">Error al subir la imagen al servidor. Por favor intente de nuevo</div>';
		} else if($val == 3) {
			$errors[] = '<div class="cartelAtencion">El tamaño de la imagen supera los 2mb permitidos</div>';
		} else if($val == 4) {
			$errors[] = '<div class="cartelAtencion">El archivo seleccionado no es una imagen</div>';
		} else {$filename = $val;}
	}else {
		$filename = "default_couch.png";
	}
	
	foreach ($whitelist as $key) {
		$fields[$key] = $_POST[$key];
	}
	
	foreach ($fields as $field => $data) {
		if (empty($data)) {
			$errors[] = '<div class="cartelAtencion">Debe completar el campo '.$field.'</div>';
		}
	}
	//chequear si todo ok y procesar los datos
	if (empty($errors)) {
		$userid = Sesion::idUsuario();
		$query1 = "INSERT INTO couchs (iduser, idtipo, cod_loc, titulo, nombrecouch, capacidad, path) VALUES ('$userid', '".$fields['tipo']."', '".$fields['ciudad']."', '".$fields['titulo']."', '".$fields['descripcion']."', '".$fields['capacidad']."', '$filename')";
		$link = conectar();
		$result = mysqli_query($link, $query1);
		desconectar($link);
		if ($result) {
			$msj = "Su couch fue ingresado correctamente al sistema.";
			header('Location: miscouchs.php?mensaje='.urlencode($msj).'&tipo=Correcto');/*cambiar y redirigir a VerDetalle*/
		}else {
			echo '<div class="cartelError">Error. Por favor vuelva a intentarlo más tarde</div>';
		}
	}
}
if (!empty($errors)) {
	/*print_r(array_values($errors));*/
	$length=count($errors);
	for ($i=0;$i<$length;$i++)
	echo $errors[$i];
}

?>
<script type="text/javascript">
function showCities(codprov,loc) {
	if (codprov == "") {
			document.getElementById("ciudad").innerHTML = "";
			return;
	} else {
		var xmlhttp = new XMLHttpRequest();
		//The onreadystatechange event is triggered every time the readyState changes.
		xmlhttp.onreadystatechange = function() {
			//4 request finished and response is ready. 200 ok
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("ciudad").innerHTML = xmlhttp.responseText;//The responseText property returns the response as a string
			}
		};
		xmlhttp.open("GET", "selectciudades.php?id="+codprov+"&selectedloc="+loc, true);
		xmlhttp.send();
	}
}
</script>

<form name="formularioCrear" enctype="multipart/form-data" onsubmit="return validar();" method="POST" action='couchnew.php'>
	<fieldset>
		<legend>Publicar nuevo Couch</legend>
		<br/>
		<label for="descrip">* Título:</label>
		<input type="text" name="titulo" value="<?php echo isset($fields['titulo']) ? ($fields['titulo']) : '' ?>" placeholder="Escriba un titulo"/>
		<label for="tipo">* Tipo de Couch:</label>
		<select name="tipo" title="Seleccione el tipo de couch">
		<?php 
			if (isset($fields['tipo'])) {$tiposelect = $fields['tipo'];}else{$tiposelect = -1;}
			echo '<option selected="selected" value="">Eliga el tipo de couch</option>';
			echo generarOptionsDeSelect('SELECT * FROM tipocouch WHERE bajalogica = 0','idtipo','nombretipo', $tiposelect);
		?>
		</select><br/>
		<label for="capacidad">* Capacidad:</label>
		<?php
			if (!empty($fields['capacidad'])) {
				$capac = $fields['capacidad'];
			}else{
				$capac = -1;
			}
			$select = '<select name="capacidad" title="Eliga la capacidad">';
			for ($i = 1; $i <= 10; $i++) {
				$select =$select.'<option value="'.$i.'" ';
				$select.=($i == $capac) ? 'selected' : '';
				$select.=' >'.$i.'</option>';
			}
			$select =$select.'</select>';
			echo $select;
		?>
		<label for="provincia">* Localidad:</label>
		<?php
			if (isset($fields['ciudad'])) {
				$locselect = $fields['ciudad'];
			}else{
				$locselect = -1;
			}
		?>
		<select name="provincia" title="Seleccione una provincia" onchange="showCities(this.value,<?php echo $locselect;?>)">
		<?php
			if (isset($fields['provincia'])) {$provselect = $fields['provincia'];}else{$provselect = -1;}
			echo '<option value="">Elige la provincia</option>';
			echo generarOptionsDeSelect('SELECT * FROM provincias','cod_prov','nombreprov', $provselect);
		?>
		</select><br/>
		<!--funcion con AJAX para obtener las localidades segun la provincia seleccionada-->
		<div id="ciudad">
			<select name="ciudad" title="Seleccione una ciudad">
		<?php
			if (isset($fields['ciudad'])) {
				$ciuselect = $fields['ciudad'];
				echo generarOptionsDeSelect('SELECT * FROM localidades','cod_loc','nombreloc', $ciuselect);
			}else{
			echo '<option value="">Elige la ciudad</option>';
			}
			
			?>
			</select>
		</div>
		<label for="descrip">* Descripcion:</label>
		<textarea rows="10" cols="40" name="descripcion" id="descrip" maxlength="500"><?php echo isset($fields['descripcion']) ? ($fields['descripcion']) : '' ?></textarea><br/>
		<br/>
		<input type="hidden" name="MAX_FILE_SIZE" value="2096128"/>
		<label>Imagen:</label>
		<input type="file" accept="image/*" name="imagen"/>
		<br/>
		<input type="submit" value="Crear" title="Cree un nuevo couch" class="submitbtn"/>
		<a href="index.php"><input type="button" value="Cancelar" title="cancelar operación" class="submitbtn"/></a>
		<label> (*) Campo obligatorio</label>
	</fieldset>
</form>
<?php
include 'footer.php';
?>