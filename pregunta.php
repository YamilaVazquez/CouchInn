<?php
include_once 'header.php';
include_once 'funciones.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if (isset($_POST['nombre']) and (!empty($_POST['nombre']))) {
   $pregunta=$_POST['nombre'];
   $userid = Sesion::idUsuario();
   $idcouch = $_POST['couch'];
   $conexion= conectar(); 
   $evaluacion = "INSERT INTO comentarios (iduser,idcouch, pregunta) VALUES ('$userid' ,'$idcouch' ,'$pregunta')";
   $result = mysqli_query($conexion, $evaluacion);
   if ($result) {
     $msj = "Gracias por ingresar tu pregunta";
     header('Location: detallecouch.php?mensaje='.urlencode($msj).'&tipo=Correcto&id='.$idcouch);
   }else {
     $msj = "Error. Por favor vuelva a intentarlo.";
     header('Location: detallecouch.php?mensaje='.urlencode($msj).'&tipo=Error&id='.$idcouch);
   } 

}

include 'footer.php';
?>

    
    










