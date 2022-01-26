<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/conexion.php';
/**
 * ../calendario.php
 * Clase referida a todo lo relacionado con el calendario
 * @author Jesús Rodríguez Malagón
 */
class Calendario
{
    function subirCalendario($date, $entrada, $salida)
    {
        $con = new ConexionBD();
        $conex = $con->conexion();
        $respuesta = "";
        if (strlen($entrada) === 5) {
            $entrada = $entrada . ":00";
            $entrada = date($entrada);
        } else {
            $entrada = date($entrada);
        }
        if (strlen($salida) === 5) {
            $salida = $salida . ":00";
            $salida = date($salida);
        } else {
            $salida = date($salida);
        }
        $token = $_COOKIE['token'];
        mysqli_select_db($conex, "id15103239_workcontrol");
        $sql = mysqli_query($conex, "SELECT count(fecha) as cuenta from calendario WHERE fecha = '$date' AND token = '$token'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_row($sql);
            if ($row[0] != 0) {
                mysqli_select_db($conex, "id15103239_workcontrol");
                $result4 = "UPDATE calendario SET horaentrada = '$entrada' , horasalida = '$salida' WHERE calendario.fecha = '$date' AND token = '$token'";
                $conex->query($result4);
                $respuesta = array(
                    'respuesta' => 'actualizado'
                );
            } else {
                mysqli_select_db($conex, "id15103239_workcontrol");
                $sql = mysqli_query($conex, "INSERT INTO calendario (token,fecha,horaentrada,horasalida) VALUES ('$token', '$date', '$entrada', '$salida')");
                $respuesta = array(
                    'respuesta' => 'insertado'
                );
            }
        }
        mysqli_close($conex);
        return $respuesta;
    }

    function cogerCalendario($date, $token, $dni, $tipousuario)
        {
            $con = new ConexionBD();
            $conex = $con->conexion();
            $respuesta = "";
            mysqli_select_db($conex, "id15103239_workcontrol");
            if ($conex->connect_error) {
                die("Connection failed: " . $conex->connect_error);
            }
            if ($tipousuario === 'trabajadorEmpresa') {
                $sql = mysqli_query($conex, "SELECT horaentrada,horasalida FROM calendario WHERE token = '$token' AND fecha='$date'");
            }
            if ($tipousuario === 'usuarioEmpresa') {
                
                if($dni !== false)
                {
                    $sql2 = mysqli_query($conex, "SELECT token FROM trabajadorEmpresa WHERE dni = '$dni'");
                    if(mysqli_num_rows($sql2) > 0)
                    {
                    $row = mysqli_fetch_row($sql2);
                    $sql = mysqli_query($conex, "SELECT horaentrada,horasalida FROM calendario WHERE token = '$row[0]' AND fecha='$date'");
                    }
                    else{
                        $sql = mysqli_query($conex, "SELECT horaentrada,horasalida FROM calendario WHERE token = '0'");  
                    }
                }
            }
            if (mysqli_num_rows($sql) > 0) {
               $row = mysqli_fetch_row($sql);
                $respuesta = array(
                        'r1' => 'recogido',
                        'entrada' => $row[0],
                        'salida' => $row[1]
                    );
            } else {
                $respuesta = array(
                    'r1' => 'vacio'
                );
            }
            mysqli_close($conex);
            mysqli_free_result($sql);
            return $respuesta;
        }
}
?>