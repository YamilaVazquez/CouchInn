<?php
include_once 'header.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
$usuarioid=Sesion::idUsuario();
if (isset($_POST) and (!empty($_POST))) {
    if (campoLetras($_POST['nombre'])){
      if (campoLetras($_POST['apellido'])){
        if (campoMail($_POST['email'])){
          if(campoAlfanumerico($_POST['pass'])){
            if (($_POST['telefono']>6) && is_numeric($_POST['telefono'])){
              $conexion= conectar();
              $consulta= "UPDATE usuarios set nombre='".$_POST['nombre']."',apellido='".$_POST['apellido']."',telefono='".$_POST['telefono']."',pass='".$_POST['pass']."',email='".$_POST['email']."' where iduser = ".$usuarioid;
              
              $resultado= mysqli_query($conexion,$consulta);//hago la consulta
              echo '<div class="cartelCorrecto">Modificación exitosa</div>';
            }else{
                   echo '<div class="cartelAtencion">En teléfono ingresado no es válido, debe ser numérico y más de 6 dígitos</div>';
            }
          }else{
                 echo '<div class="cartelAtencion">Ingrese un password</div>';
          }    
        }else{
           echo '<div class="cartelAtencion">El mail ingresado no es válido</div>';
        }

      }else{
            echo '<div class="cartelAtencion">Su apellido debe contener solo letras</div>';
      }      
    }else{
       echo '<div class="cartelAtencion">Su nombre debe contener solo letras</div>';
    }
}

$link = mysqli_connect('localhost','root','','couchinn') or trigger_error("Error de conexión", E_USER_ERROR);
 $consulta= 'select * from usuarios where iduser = '.$usuarioid;
 $resultado= mysqli_query($link,$consulta);//hago la consulta
 $fila = mysqli_fetch_array($resultado);//saco una fila en particular



echo '<form action="modificarPerfil.php" method="post">
      <fieldset>
        <legend>Modificar Perfil</legend>
        <br/>
        <label for="nombre">  Nombre:</label>    
        <input type=text id="nombre" name="nombre" placeholder="Ingresa tu nombre" value='.$fila['nombre'].' required="">
        <label for="apellido">  Apellido:</label>
        <input type=text id="apellido" name="apellido" placeholder="Escribe tu apellido" value='.$fila['apellido'].' required="">
        <label for="Telefono">  Telefono:</label>
        <input type=text id="telefono" name="telefono" placeholder="Escribe tu telefono" value='.$fila['telefono'].' >
        <label for="correo">  E-mail:</label>
        <input type=text id="email" name="email" placeholder="nombre@mail.com" value='.$fila['email'].' >
        <label for="password">  contraseña:</label>
        <input type=text id="pass" name="pass" placeholder="" value='.$fila['pass'].' >
        <input type="submit" value="Aceptar" class="submitbtn">
        <a href= "index.php" ><input type="button" value="Cancelar" class="submitbtn"> </a>
      </fieldset> 

</form>' ;

?>