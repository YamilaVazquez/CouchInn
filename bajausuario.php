<?php
require_once "header.php";
include_once 'funciones.php';

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
<h2>Baja del Sistema</h2>
<p>Si da de baja su cuenta de usuario no podrá volver a iniciar sesión en el sistema ni realizar o aceptar nuevas reservas. Tenga en cuenta que si posee reservas en curso no podrá darse de baja hasta que finalicen las mismas</p>
	<form method="POST" onsubmit="return confirm('¿Está seguro que desea eliminar su cuenta de usuario?')">
		<legend>Elimine su cuenta de usuario</legend>
		<input type="hidden" name="id" value="<?php echo Sesion::idUsuario()?>"/>
		<label for="pass"> * Contraseña:</label> 
		<input type="password" name="pass" placeholder="Confirme su contraseña" required="" />
		<input type="submit" value="Eliminar cuenta" name="eliminar" title="Elimine de forma permanente su cuenta de usuario"/>
		<a href="index.php"><input type="button" value="Cancelar" class="submitbtn"> </a>
		<label> (*) Campo obligatorio</label>
	</form>
<?php

$conn = conectar();

function validarpass($iduser, $pass) {
	$conn = conectar();
	$query = "SELECT * FROM usuarios WHERE iduser='$iduser' and pass='$pass'";
	$result = mysqli_query($conn, $query);
	desconectar ($conn);
	$row = mysqli_fetch_array ($result);
	mysqli_free_result($result);
	if($row) {
		return true;
	} else {
		return false;
	}
}

function bajacouchs($userid) {
	$conn = conectar();
	$bajac= mysqli_query($conn, "UPDATE couchs SET bajacouch = '1', visibilidad = '0' WHERE iduser = ".$userid); /*baja lógica*/
	desconectar ($conn);
}

function cancelarresaceptadas($userid) {
	$conn = conectar();
	// cancelar aceptadas como anfitrion
	$aceptanf = "SELECT r.idreserva FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (c.iduser =".$userid.") AND (estado = 'aceptada')";
	$result = mysqli_query($conn, $aceptanf);
	while ($row = mysqli_fetch_array($result)) {
		$upt = "UPDATE reservas SET estado = 'cancelada' WHERE idreserva = " .$row['idreserva'];
		$resultupt = mysqli_query($conn, $upt);
	}
	// cancelar aceptadas como huesped
	$acepthues = "UPDATE reservas SET estado='cancelada'  WHERE iduser = ".$userid;
	$resultupt2 = mysqli_query($conn, $acepthues);
	desconectar ($conn);
}

function rechazarpendientes($userid) {
	$conn = conectar();
	//rechazar pendientes como anf
	      $query = "SELECT r.idreserva FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (c.iduser =".$userid.") AND (estado = 'pendiente')";
		  $result = mysqli_query($conn, $query);
		  while ($row = mysqli_fetch_array($result)) {
			$upt = "UPDATE reservas SET estado = 'rechazada' WHERE idreserva = " .$row['idreserva'];
			$resultupt = mysqli_query($conn, $upt);
		  }
		  mysqli_free_result($result);
	//cancelar pendientes como huesp
	      $upt2 = "UPDATE reservas SET estado='cancelada'  WHERE iduser = ".$userid;
		  $resultupt2 = mysqli_query($conn, $upt2);
		  desconectar ($conn);
}
//reservas concretadas como anfitrion sin finalizar. retorna True si hay alguna
function concretadaAnfitrion ($fechaactual, $userid) {
	$conn = conectar();
	$query = "SELECT r.idreserva, c.idcouch, r.iduser, fechafin FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch)  WHERE (c.iduser =".$userid.") AND (estado = 'concretada') AND (fechafin >= '$fechaactual')";
	$result = mysqli_query($conn, $query);
	desconectar ($conn);
	if (mysqli_num_rows($result) > 0) {
		return true;
	} else {
		return false;
	}
}
//reservas concretadas como huesped sin finalizar. retorna True si hay alguna
function concretadaHuesped ($fechaactual, $userid) {
	$conn = conectar();
	$query= "SELECT * FROM reservas WHERE ( iduser = '$userid') AND ( estado = 'concretada' ) AND (fechafin >= '$fechaactual')";
	$result = mysqli_query($conn, $query);
	desconectar ($conn);
	if (mysqli_num_rows($result) > 0) {
		return true;
	} else {
		return false;
	}
}

if (!empty($_POST) && !empty($_POST['pass'])) {
	$conn = conectar();
	if ((Sesion::idUsuario() == $_POST['id']) && (validarpass($_POST['id'], $_POST['pass']))) {
		$userid = $_POST['id'];
		if(!Sesion::esAdmin()) {
			$fechaactual = date("Y-m-d");
			if (!concretadaAnfitrion($fechaactual, $userid) && !concretadaHuesped($fechaactual, $userid)) {
				rechazarpendientes($userid);
				cancelarresaceptadas($userid);
				bajacouchs($userid);
				$querybajauser="UPDATE usuarios SET email = '', pass = '', premium = '0' WHERE iduser = ".$userid;
				$resultbaja = mysqli_query($conn, $querybajauser);
				desconectar ($conn);
				Sesion::cerrarSesion();
				header('Location: index.php?mensaje='.urlencode('Se ha dado de baja del sistema. Para volver a ingresar deberá registrarse').'&tipo=Atencion');
			} else {
				echo '<div id="cartel" class="cartelError">Tiene hospedajes en curso. Debe esperar a que terminen para darse la baja del sistema</div>';
			}
		} else {
			echo '<div id="cartel" class="cartelError">Los usuarios administradores no pueden darse de baja</div>';
		}
	} else {echo '<div id="cartel" class="cartelAtencion">El password ingresado no es válido</div>';}
}

/*
si post!empty
	si pass! empty y pass = user en bd
		si no es admin
			si NO tiene reservas concretadas sin finalizar
				se dan de baja todos los couchs -logica- (y borrar imgs, idealmente)
				Se cancelan reservas aceptadas (como anf y hues)
				se rechazan todas las pendientes
				se realiza la baja -logica- del usuario
			else tiene res concr sin finalizar
				error. debe esperar a que finalicen
		else es admin
			error. no puede darse de baja del sistema
	else pass erroneo o vacio
		error. ingrese password

		
Baja de Usuario
Solicitar ingreso de password para confirmar la operación.
Si la confirmación es correcta y el usuario no posee reservas concretadas sin finalizar, se da la baja en el sistema del usuario 

con sus couchs ,se cancelan sus reservas y se cierra su sesión
Si la confirmación es correcta y el usuario posee reservas concretadas sin finalizar, Se le informa que no puede darse la baja 

hasta finalizadas las mismas (¿Cancelar las demas y dar de baja/despublicar sus couchs o no?)
Si el pass es erróneo, se informa el error.
Si un administrador intenta darse de baja, se niega la operación y se informa el error.*/

include 'footer.php';
?>