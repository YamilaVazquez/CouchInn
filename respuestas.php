<?php
require_once 'header.php';
require_once 'conexion.php';
require_once 'funciones.php';
require_once 'sesion.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}

if(isset($_GET['mensaje'])){
	echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
	
   $userid = Sesion::idUsuario();
   $conexion= conectar(); 
   $evaluacion = "SELECT * FROM comentarios WHERE iduser=" .$userid. " AND respuesta IS NOT NULL";
   $result = mysqli_query($conexion, $evaluacion);
   if (mysqli_num_rows($result) > 0) {
      echo '<h3>MIS PREGUNTAS RESPONDIDAS</h3>';
      echo '<table class="tablaVer">
            <tr>
               
               <td><strong>Mi pregunta</strong></td>
               <td><strong>Respuesta</strong></td>
            </tr>';
      while ($row = mysqli_fetch_array($result)){
        echo '<tr>
                 
                 <td>'.$row['pregunta'].'</td>
                 <td>'.$row['respuesta'].'</td> 
              </tr>';
      } 
      echo '</table>';
    }else {
        echo "No hay preguntas por responder";
    } 



include 'footer.php';
desconectar($conexion);
?>	

