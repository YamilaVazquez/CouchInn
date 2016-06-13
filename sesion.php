<?php
/*Clase para el manejo de sesiones. Autenticacion, autorizacion y cierre de sesion */
/*Se deberia colocar a esta altura: session_start() ya que es la primera instruccion  a ejecutar*/
session_start();
require_once 'funciones.php'; /*ya incluye conexion.php*/
final class Sesion {
	/*
		Validacion de usuario. Si pasa inicia la sesion, caso contrario lanza una Excepcion.
		Parametros:
			$usermail:	string 
			$pass:	string
	*/
	public static function iniciarSesion($usermail, $pass){
		//Se chequea el envio vacio de alguno de los campos en caso de forzar el envío.
		if(campoMail($usermail) && campoObligatorio($pass)){
			//Se verifica la existencia de la combinacion usuario/password
			$query = "SELECT * FROM usuarios WHERE email='".$usermail."' and pass='".$pass."'";
			$link = conectar();
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_array ($result);
			mysqli_free_result($result);
			desconectar($link);
			if($row){
				session_start();
				$_SESSION['estado']=true;
				$_SESSION['usuario']=$row['nombre'];
				$_SESSION['id']=$row['iduser'];
				$_SESSION['admin']=$row['admin'];
				$_SESSION['vip']=$row['premium'];/*VER COMO ACTUALIZAR CUANDO SE CONVIERTE EN VIP EL USUARIO DURANTE LA SESION*/
			}
			else{
				throw new Exception('Error. e-Mail y contraseña inválidos');
			}
		}
		else{
				throw new Exception('Debe completar todos los campos');
		}
	}
	
	public static function cerrarSesion(){
		session_unset();
		session_destroy();		
	}
	/*Revisar (valores de $_SESSION['admin'] y $_SESSION['admin'] por la forma de almacenar bool en MySQL)*. ¿Session::EstaLogueado?, idem para nombreUsuario*/
	public static function esAdmin(){
		if ($_SESSION['admin']) {
			return true;
		} else {
			return false;
		}
	}
	public static function esPremium(){
		if ($_SESSION['vip']) {
			return true;
		} else {
			return false;
		}
	}
	public static function convertirVIP(){
		if (!Sesion::esPremium()) {
			$_SESSION['vip']=1;
		}
	}
	public static function estaLogueado(){
		if(isset($_SESSION['estado']) && ($_SESSION['estado'])){
			return true;
		}
		else{
			return false;
		}
	}
/*Parametros: $adminonly: boolean (false por default) Para indicar si el acceso es solo para administradores. RE-IMPLEMENTAR sin tanto condicional*/	
	public static function estaAutorizado($adminonly = false){
		if(Sesion::estaLogueado()){
			if (!$adminonly) {
				return true;
			} else {
				if (Sesion::esAdmin()) {
					return true;
				} else {
					throw new Exception('No tiene los permisos necesarios para acceder');
				}
			}
		}
		else{
			throw new Exception('No esta autorizado para acceder. Inicie sesión o contáctese con el administrador');
		}
	}

	//Retorna el nombre del usuario
	public static function nombreUsuario(){
		return $_SESSION['usuario'];
	}
		
	public static function idUsuario(){
		return $_SESSION['id'];
	}
}