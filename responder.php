<?php
require_once 'header.php';
require_once 'funciones.php';
require_once 'sesion.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if (isset($_POST['nombre']) and (!empty($_POST['nombre']))) {
    
  $conexion= conectar(); 
  $evaluacion = "UPDATE comentarios SET respuesta ='" .$_POST['nombre']. "' WHERE idcomment = ".$_POST['id'];
  $result = mysqli_query($conexion, $evaluacion);
  if ($result){
    $msj = "Su respuesta ya fue publicada.";
   header('Location:preguntaDueno.php?&mensaje='.urlencode($msj).'&tipo=Correcto');
  }
  else{
    $msj = "ERROR, no se publicó la respuesta";
    header('Location:preguntaDueno.php?&mensaje='.urlencode($msj).'&tipo=Error');
  }
}

//desconectar($conexion);
         

 if (isset($_GET['id']) and (!empty($_GET['id']))) {
?>
    	 <form name="formulario" method="post"> 
          RESPUESTA: <input type="text" required="" name="nombre" value="">
          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>
          <input type="submit" value="Enviar" class="submitbtn" /> 
          <a href="preguntaDueno.php"><input type="button" value="Cancelar" class="submitbtn"/></a>
        </form> ;
<?php
}
?>
     