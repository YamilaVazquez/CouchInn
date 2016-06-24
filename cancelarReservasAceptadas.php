<?php
   include_once 'header.php';
   include_once 'funciones.php';
   try{
      Sesion::estaAutorizado();
   }
   catch(Exception $e){
      header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
   }
   if(isset($_GET['idreserva'])&&!empty($_GET['idreserva'])){//tenemos el id del couch actual
      
           $reservaid = Sesion::idreserva();
           $consulta= "select * from reservas where estado='aceptada'  ";
           $resultado= mysqli_query($link,$consulta); 
           
           if($resultado){
                 
                echo '<table class="tablaVer">
                   <tr>
                        <td>Numero</td>
                        <td>Titulo</td>
                        <td>usuario</td>
                        <td>Fecha inicio</td>
                        <td>Fecha fin</td>
                   </tr>';
                while ($row = mysqli_fetch_array($resultado)){
                   echo '<tr>
                            <td>'.$row['idreserva'].'</td>
                            <td>'.$row['titulo'].'</td>
                            <td>'.$row['iduser'].'</td>
                            <td>'.$row['fechaini'].'</td>
                            <td>'.$row['fechafin'].'</td>
                            <td><a href="aceptarreserva.php?id='.$row['idreserva'].'" onclick="return confirm(\'¿Esta seguro que desea aceptar la reserva numero '.$row['idreserva'].'? \')" title="Aceptar reserva"> Aceptar</a></td>
                            <td><a href="confirmarCancelacion2.php?id='.$row['idreserva'].'" onclick="return confirm(\'¿Esta seguro que desea cancelar la reserva numero '.$row['idreserva'].'? \')" title="Cancelar reserva"> Cancelar</a></td>
                        </tr>';
                }
                echo '</table>';  

            }
            else{
                echo "No tienes reservas aceptadas";
            }


   }

?>