<?php
include_once 'header.php';
include_once 'funciones.php';
if(isset($_GET['mensaje'])){
  echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
if (isset($_GET['id'])) {//verifica si apreto si
      $conexion=conectar(); 
      $query = "UPDATE reservas SET estado='rechazada'  WHERE idreserva = " .$_GET['id'];
      $result= mysqli_query($conexion, $query);
      $msj= "Su reserva ha sido rechazada";
      header('Location: pendientes2.php?mensaje='.urlencode($msj).'&tipo=Correcto');
}

?>



<form id="formulario" action="rechazarReservaCouch.php" method="post"> 
      <fieldset> 
      <legend><b>Deseas rechazar reservas que estan pendientes en tu couch???</b></legend> 
      <br>
      <legend><b>Estas seguro???</b></legend> 
      <a href= "confirmarRechazo.php"><input type="submit" value="SI" class="submitbtn"></a>
      <a href= "rechazarReservaCouch.php"><input type="submit" value="NO" class="submitbtn"> </a>
      </fieldset> 
</form>