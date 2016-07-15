<?php
include 'header.php';
include_once 'funciones.php';
if(isset($_GET['mensaje'])){
  echo '<div id="cartel" class="cartel'.$_GET['tipo'].'">'.$_GET['mensaje'].'</div>';
}
$conexion= conectar();

if (!empty($_POST)) {
  if (isset($_POST['inicio'])){
        if (isset($_POST['fin'])){
          $fecha1=$_POST['inicio'];
            $fecha2=$_POST['fin'];
            if (validarFecha($fecha1)){
              if (validarFecha($fecha2)){
                if ($fecha1<$fecha2){
                  $consulta= "SELECT * FROM reservas r INNER JOIN couchs c ON (r.idcouch = c.idcouch) WHERE ((fechaini BETWEEN CAST( '$fecha1' AS DATE ) AND CAST( '$fecha2' AS DATE )) AND (fechafin BETWEEN CAST( '$fecha1' AS DATE ) AND CAST( '$fecha2' AS DATE ))) AND ( estado = 'concretada')"; /*AND o OR*/
                  $result= mysqli_query($conexion, $consulta);
                  $num= 0;
                  if (mysqli_num_rows($result)> 0){
              echo '<table class="tablaVer" align="center">
                <tr>
                  <td><b>Reserva</b></td>
                  <td align="center"><b>Couch</b></td>
                  <td><b>Fecha de Inicio</b></td>
                  <td><b>Fecha de Fin</b></td>
                </tr>';
              while ($row = mysqli_fetch_array($result)){
                $num= $num + 1;
                echo '<tr align="center">
                    <td>'.$num.'</td>
                    <td>'.$row['titulo'].'</td>
                    <td>'.$row['fechaini'].'</td>
                    <td>'.$row['fechafin'].'</td>
                  </tr>';
              }
              echo '</table>';
            }else {echo "<p><strong>No se reportan reservas concretadas en ese período</strong></p>";}
                  }
                  else{
                    $msj = "ERROR, la fecha de inicio ingresada es mayor que la fecha de fin ingresada";
                      header('Location:reportePremium.php?&mensaje='.urlencode($msj).'&tipo=Error');
                  }
                }
                else{
                  $msj = "La fecha no fue ingresada correctamente.Ingresar con el siguiente formato:dd-mm-aaaa";
                  header('Location:reportePremium.php?&mensaje='.urlencode($msj).'&tipo=Error');
                }
            }
            else{
              $msj = "La fecha no fue ingresada correctamente.Ingresar con el siguiente formato:dd-mm-aaaa";
                header('Location:reportePremium.php?&mensaje='.urlencode($msj).'&tipo=Error');
            }       
      }
      else{
        $msj = "Error, fecha de fin no fue ingresada";
           header('Location: reportePremium.php?&mensaje='.urlencode($msj).'&tipo=Error');
      }
    }
    else{
      $msj = "Error, fecha de inicio no fue ingresada";
        header('Location:reportePremium.php?&mensaje='.urlencode($msj).'&tipo=Error');
    }   
}


?>

<form id="formulario" action="reporteReservas.php" method="post"> 
            <fieldset> 
              <legend>Reporte de reservas:</legend> 
              <label>* fecha de inicio</label> 
              <input id="inicio" name="inicio" type="date" placeholder="dd-mm-aaaa" required="" /> 
              <label>* fecha de fin</label> 
              <input id="fin" name="fin" type="date" placeholder="dd-mm-aaaa" required="" /> 
              </br>
              <input type="hidden" name="id" value="'.$_GET['id'].'">
              <a href= "reporteReservas.php"><input type="submit" value="Aceptar" class="submitbtn"></a>
              <a href= "index.php"><input type="button" value="Cancelar" class="submitbtn"></a>
              <label> (*) Campo obligatorio</label> 
            </fieldset>
</form>';


<?php
include 'footer.php';
desconectar($conexion);
?>