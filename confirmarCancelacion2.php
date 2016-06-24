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
if (isset($_GET['id'])) {
      $conexion=conectar();
      $query = "UPDATE reservas SET estado='cancelada'  WHERE idreserva = ".$_GET['id'];
      $result= mysqli_query($conexion, $query);
      $msj= "La reserva ha sido cancelada";
      header('Location: hostreservasaceptadas.php?mensaje='.urlencode($msj).'&tipo=Correcto'); 
    
	
}

?>

<form id="formulario" action="cancelarReservasPendientes.php" method="post"> 
      <fieldset> 
      <legend><b>Deseas cancelar reservas que estan aceptadas en tu couch???</b></legend> 
      <br>
      <legend><b>Estas seguro???</b></legend> 
      <input type="submit" value="SI" class="submitbtn">
      <a href= "cancelarReservasAceptadas.php" ><input type="submit" value="NO" class="submitbtn"> </a>
      </fieldset> 
</form>