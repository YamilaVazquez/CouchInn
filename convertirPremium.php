 <?php
 include_once 'header.php';
include_once 'funciones.php';
try{
  Sesion::estaAutorizado();
}
catch(Exception $e){
  header('Location: loginform.php?mensaje='.urlencode($e->getMessage()).'&tipo=Error');
}
    
    if (isset($_POST['Aceptar'])) { //pregunto si apretó aceptar

        if(validaringreso()){ //pregunto si se ingresó la tarjeta
            $tarjetaNum= $_POST['cardnumber'];
            $nombre= $_POST['namedueno'];
            $apellido= $_POST['apedueno'];
            $cod= $_POST['idnumber'];
            $fvto= $_POST['fechavto'];
            if(is_numeric($tarjetaNum)){ //tendría que ver que sea sólo numerico.
                if (strlen($tarjetaNum) == 16){ // validar que la cantidad de numeros sea menor a 17
                    if (campoLetras($nombre)){ //valido que nombre string
                        if (campoLetras($apellido)) { //valido que ape string
                            if(is_numeric($cod)){ //valido codigo solo numeros
                                if(strlen($cod) == 3){ //valido que cod 3 dig
                                    if(validarFechavto($fvto)){
                                        $conexion=conectar();

                                        if (esPremium($conexion)){
                                                $msj= "Error. El usuario ya es premium";
                                                header('Location: index.php?mensaje='.urlencode($msj).'&tipo=Atencion');
                                           }
                                           else{
                                                $usuarioid = Sesion::idUsuario();
                                                $query = "UPDATE usuarios SET premium = 1 WHERE iduser =".$usuarioid;
                                                $fila = mysqli_query($conexion, $query);    //ver comillas
                                                Sesion::convertirVIP();
                                                $msj= "¡Felicitaciones!. Usted ya es premium";
                                                header('Location: index.php?mensaje='.urlencode($msj).'&tipo=Correcto');
                                           } 

                                    }
                                    else{   
                                        
                                        $msj= "La tarjeta está vencida o la fecha de vencimiento ingresada es inválida";
                                        header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
                                    }
                                }
                                else{
                                    $msj= "El código de verificación es incorrecto";
                                        header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
                                }
                            }
                            else{
                                $msj= "El código de verificación debe ser numérico";
                                        header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
                            }
                        }
                        else{
                            $msj= "El apellido no puede contener números y/o símbolos";
                            header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
                        }
                    }
                    else{
                            $msj= "El nombre no puede contener números y/o símbolos";
                            header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
                    }
                   /*if (esPremium($conexion)){
                        echo "Error, el usuario ya es premium";
                        //header('Location: http://localhost/couchinnxampp/');
                   }
                   else{
                        $usuarioid = Sesion::idUsuario();
                        $query = "UPDATE usuarios SET premium = 1 WHERE idtipo =".$usuarioid;
                        $fila = mysqli_query($conexion, $query);    //ver comillas
                        echo "¡$query    //header('Location: http://localhost/couchinnxampp/')"; 
                   } */    
                }
                else{
                    $msj= "La cantidad de dígitos ingresados de la tarjeta es incorrecta";
                            header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');   
                } 
            }      
            else{
                $msj= "Los dígitos de la tarjeta deben ser numéricos";
                header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');            }
        }
        else{
            $msj= "No se ingresó el número de tarjeta";
            header('Location: premiumform.php?mensaje='.urlencode($msj).'&tipo=Atencion');
        }
    }
    

    function validaringreso(){
        if (isset($_POST['cardnumber'])) {
            if(isset($_POST['namedueno']) && (isset($_POST['apedueno']))){
                if (isset($_POST['idnumber'])) {
                    if (isset($_POST['fechavto'])) {
                        return true;
                    }
                    else
                        echo "Error, falta ingresar fecha de vencimiento de tarjeta";
                        return false;
                }
                else{
                    echo "Error, falta ingresar el código de verificación";
                    return false;
                }
            }
            else{
                echo "Error, falta ingresar el nombre y apellido";
                return false;
            }
        }
        else{
            echo "Error, falta ingresar el número de la tarjeta";
            return false;
        }   
    }  

    function esPremium($conexion){
        $usuarioid = Sesion::idUsuario();
        $consulta= "select * from usuarios where (iduser = ".$usuarioid.") and (premium = 1)"; //ver si 1 es premium o no.
        $resultado= mysqli_query($conexion,$consulta);
        $fila = mysqli_fetch_array($resultado);
        if($fila) {
            return true;
        }
        else{
            return false;
        }
    }

    function validarFechavto($campo){
    $campo  = explode('-', $campo); //formato mm-aaaa [1]año [0]mes
    if (count($campo) == 2) {
        if (checkdate($campo[0], 01, $campo[1])) { /*checkdate ( int $MES, int $DIA ,int $AÑO ).Retorna True si es valida*/
            $actual=date_create(null);
            date_time_set($actual,0,0,0);
            $fecha=date_create($campo[1].'-'.$campo[0].'-'."01");
            if($fecha > $actual){
                return true; // fecha valida.
            }
            else{
                return false;
            }
        } else {
            return false; // fecha invalida
        }
    } else {
        return false;// no corresponde con el formato esperado.
    }
}       
          
include 'footer.php';
desconectar($conexion);   
    
  ?>