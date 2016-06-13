<?php
include_once 'header.php';
include_once 'funciones.php'; //agrego esto para validar mail

$conexion= conectar();

if (isset($_POST['Aceptar'])) {
	$correo= $_POST['correo'];

	if(isset($_POST['correo']) && !empty($_POST['correo'])){	//pregunto si hay un campo correo y si no es vacio
		/*$email_to = $_POST['correo'];		//Ver!!
		$email_subject = "Recuperar contraseña. Couchinn";
		$email_from= "noreply@couchinn.com";*/
		if (campoMail($correo)){ //pregunto si está bien el correo
			if (existeEnBD($conexion)){
				/*$email_message = "Esta es su contraseña:\n\n";*/
				$result = mysqli_query($conexion, "UPDATE usuarios SET pass = '1234' WHERE email = '".$_POST['correo']."'"); 
				$fila= mysqli_fetch_array($result);
				/*$email_message .= "Contraseña: " . $fila['pass'] . "\n"; //ver si al correo le faltan comillas.
				$headers = 'From: '.$email_from."\r\n".
				'Reply-To: '.$email_from."\r\n" .
				'X-Mailer: PHP/' . phpversion();
				@mail($email_to, $email_subject, $email_message, $headers);*/

				$msj = "¡Se ha enviado la contraseña vía e-mail!";
				header('Location: loginform.php?mensaje='.urlencode($msj).'&tipo=Correcto');				
			}
			else{
				$msj = "Error. El mail ingresado no está registrado";
				header('Location: passrecoveryform.php?mensaje='.urlencode($msj).'&tipo=Error');	
			}
		}
		else{
			$msj= "El campo mail es inválido. Vuelva a ingresarlo";
			header('Location: passrecoveryform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
		}		
	}
	else{
		$msj = "Falta completar el campo mail";
		header('Location: passrecoveryform.php?mensaje='.urlencode($msj).'&tipo=Atencion');

	}
}

function existeEnBD($conexion){
	$result = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email ='" .$_POST['correo']."'"); 
	$fila= mysqli_fetch_array($result);
	
	if ($fila){
		return true;
	}
	else{
		return false;
	}
}

include 'footer.php';
desconectar ($conexion);

?>