<?php
//script para loguearse
	include_once 'sesion.php';

	try{
		Sesion::iniciarSesion($_POST['mail'],$_POST['pass']);
		
		/* $_POST['location'] (pasada desde loginform.php) trae la URL desde donde se accedió al formulario de inicio de sesión. Si esta en blanco se ignora, sino se guarda su valor*/
		$redirect = 'index.php';
		if($_POST['location'] != '') {
			$redirect = '/'.$_POST['location'];
			/*habria que verificar que sea una URL del dominio*/
		}
		header('Location: '.$redirect);
	}
	catch (Exception $e){
		header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
	}
?>