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
            			$consulta= "SELECT * FROM pagos p INNER JOIN usuarios u ON (p.iduser = u.iduser) WHERE (fechapago BETWEEN CAST( '$fecha1' AS DATE ) AND CAST( '$fecha2' AS DATE ))"; 
            			$result= mysqli_query($conexion, $consulta);
            			$cant=0;
            			if (mysqli_num_rows($result)> 0){
							echo '<table class="tablaVer" align="center">
								<tr>
									<td colspan="2" align="center"><b>Usuario</b></td>
									<td align="center"><b>Fecha de pago</b></td>
								</tr>';
							while ($row = mysqli_fetch_array($result)){
								$cant= $cant + 1;
								echo '<tr>
										<td>'.$row['nombre'].'</td>
										<td>'.$row['apellido'].'</td>
										<td>'.$row['fechapago'].'</td>
									</tr>';
							}
							$total= $cant * 10;
							echo '<tr>
									<td colspan="2" align="center"><b>Total premium</b></td>
									<td>$ '.$total.'</td>
                  <td colspan="2" align="center"><b>Total usuarios</b></td>
                  <td>'.$cant.'</td>
								</tr>
									
							</table>';
						}else {echo "<p><strong>No se reportan usuarios premium en ese período</strong></p>";}
                	}
                	else{
                		$msj = "ERROR, la fecha de inicio ingresada es mayor que la fecha de fin";
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
<h2>Reporte de usuarios premium</h2>
<form id="formulario" action="reportePremium.php" method="post"> 
            <fieldset> 
              <legend>Reporte de usuarios:</legend> 
              <label>* fecha de inicio</label> 
              <input id="inicio" name="inicio" type="date" placeholder="dd-mm-aaaa" required="" /> 
              <label>* fecha de fin</label> 
              <input id="fin" name="fin" type="date" placeholder="dd-mm-aaaa" required="" /> 
              </br>
              <input type="hidden" name="id" value="'.$_GET['id'].'">
              <a href= "reportePremium.php"><input type="submit" value="Aceptar" class="submitbtn"></a>
              <a href= "index.php"><input type="button" value="Cancelar" class="submitbtn"></a>
              <label> (*) Campo obligatorio</label> 
            </fieldset>
</form>';


<?php
include 'footer.php';
desconectar($conexion);
?>