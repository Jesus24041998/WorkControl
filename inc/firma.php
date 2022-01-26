<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/conexion.php';
/**
 * ../firma.php
 * Clase para subir y recoger firmas
 * @author Jesús Rodríguez Malagón
 */
class Firma
{
    /**
     * Metodo que muestra la firma si existe, en caso negativo muestra una imagen por defecto
     * @param $tipo,$token
     * @return $respuesta
     */

    function mostrarFirma($tipo, $token)
    {
        //Se abre conexión
        $con = new ConexionBD();
        $conex = $con->conexion();
        if ($tipo === 'usuarioEmpresa') {
            mysqli_select_db($conex, "id15103239_workcontrol");
            $result1 = mysqli_query($conex, "SELECT firma FROM usuarioEmpresa WHERE token = '$token'");
        }
        if ($tipo === 'trabajadorEmpresa') {
            mysqli_select_db($conex, "id15103239_workcontrol");
            $result1 = mysqli_query($conex, "SELECT firma FROM trabajadorEmpresa WHERE token = '$token'");
        }
        $respuesta = "";
        //Recibe una firma de la base de datos
        $row = mysqli_fetch_row($result1);
        if ($row[0] !== '') {
            $respuesta = $row[0];
        } else {
            $respuesta = '/imagenes/image-off.png';
        }

        mysqli_free_result($result1);
        mysqli_close($conex);
        return $respuesta;
    }
}
