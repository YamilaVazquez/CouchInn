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


if (isset($_POST['id']) && !empty($_POST['id'])){
   $userid = Sesion::idUsuario();
   $couchid= $_POST['id'];
  
   if (isset($_POST['inicio'])){
          
    if (isset($_POST['fin'])){
            $fecha1=$_POST['inicio'];
            $fecha2=$_POST['fin'];
            if (validarFecha($fecha1)){
              if (validarFecha($fecha2)){
                if ($fecha1<$fecha2){
                  $conexion=conectar(); 
                  $consulta= "select * from reservas where ((idcouch= '$couchid') and estado='aceptada' and ((fechaini BETWEEN CAST( '$fecha1' AS DATE ) AND CAST( '$fecha2' AS DATE )) AND (fechafin BETWEEN CAST( '$fecha1' AS DATE ) AND CAST( '$fecha2' AS DATE )))"; 
                  $resultado= mysqli_query($conexion,$consulta);
                  $row=mysqli_num_rows($resultado);
                  if ($row > 0) {
                    $msj = "Lo siento. El couch se encuentra reservado";
                    header('Location:realizarReserva.php?mensaje='.urlencode($msj).'&tipo=Correcto');
                  }
                  else{
                    
                    $consulta1="INSERT INTO reservas (idcouch, iduser, fechaini, fechafin, estado) VALUES ('$couchid', '$userid', '$fecha1' , '$fecha2' , 'pendiente')";
                    $resultado= mysqli_query($conexion,$consulta1);
                    $msj = "¡Reserva exitosa! se encuentra en estado pendiente esperando que el dueño la acepte";
                    header('Location:pendientes.php?mensaje='.urlencode($msj).'&tipo=Correcto');


                  }

                }
                else{
                  $msj = "ERROR, la fecha de inicio ingresada es mayor que la fecha de fin ingresada";
                  header('Location:realizarReserva.php?id='.$_POST['id'].'&mensaje='.urlencode($msj).'&tipo=Error');
                }
             
              }
              else{
                
                $msj = "fecha no fue ingresada correctamente.Ingresar con el siguiente formato:dd-mm-aaaa";
                header('Location:realizarReserva.php?id='.$_POST['id'].'&mensaje='.urlencode($msj).'&tipo=Error');

              }
            }else{
              $msj = "fecha no fue ingresada correctamente.Ingresar con el siguiente formato:dd-mm-aaaa";
              header('Location:realizarReserva.php?id='.$_POST['id'].'&mensaje='.urlencode($msj).'&tipo=Error');
            }

            
          }
          else{
            $msj = "Error, fecha de fin de la reserva no fue ingresada";
           header('Location:realizarReserva.php?id='.$_POST['id'].'&mensaje='.urlencode($msj).'&tipo=Error');
          }
        }
        else{
          $msj= "Error.Fecha de inicio de la reserva no fue ingresada";
          header('Location:realizarReserva.php?id='.$_POST['id'].'&mensaje='.urlencode($msj).'&tipo=Error');
        }
   
 }

if (isset($_GET) && (!empty($_GET['id']))){

      echo '<form id="formulario" action="realizarReserva.php" method="post"> 
            <fieldset> 
              <legend>Ingrese las fechas:</legend> 
              <label> fecha de inicio</label> 
              <input id="inicio" name="inicio" type="date" placeholder="dd-mm-aaaa" /> 
              <label>fecha de fin</label> 
              <input id="fin" name="fin" type="date" placeholder="dd-mm-aaaa" /> 
              </br>
              <input type="hidden" name="id" value="'.$_GET['id'].'">
              <a href= "realizarReserva.php?id='.$_GET['id'].'"><input type="submit" value="Aceptar" class="submitbtn"></a>
              <a href= "pendientes.php"><input type="button" value="Cancelar" class="submitbtn"></a>
            </fieldset>
      </form>';

}

include 'footer.php';

//desconectar($conexion);
         
?>






