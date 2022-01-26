<?php

/**
 * ../funciones.php
 * Clase que contiene funciones php del main menu del proyecto
 * @author Jesús Rodríguez Malagón
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/conexion.php';
class Funciones
{
    /**
     * Metodo que muestra los usuarios de una empresa
     * @param $empresa
     * @return $respuesta
     */
    function mostrarTrabajadores($empresa)
    {
        $con = new ConexionBD();
        $conex = $con->conexion();
        $respuesta = "";
        $sql = "SELECT dni,nombre from id15103239_workcontrol.trabajadorEmpresa where empresa = '$empresa'";
        $resultset = mysqli_query($conex, $sql) or die("database error:" . mysqli_error($conex));
        if (mysqli_num_rows($resultset) === 0) {
            $respuesta = '<option value="0">Sin usuarios</option>';
        }
        while ($rows = mysqli_fetch_row($resultset)) {
            $respuesta .= '<option value="' . $rows[0] . '">' . $rows[1] . '-' . $rows[0] . '</option>';
        }
        mysqli_close($conex);
        mysqli_free_result($resultset);
        return $respuesta;
    }

    function cerrarSesion($tipousuario, $token)
    {
        $con = new ConexionBD();
        $conex = $con->conexion();
        $respuesta = "";
        mysqli_select_db($conex, "id15103239_workcontrol");
        $result4 = "UPDATE $tipousuario SET logueado = 'false' WHERE token = '$token'";
        if ($conex->query($result4)) {
            $respuesta = array(
                'respuesta' => 'actualizado'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'fallo'
            );
        }
        mysqli_close($conex);
        return $respuesta;
    }
    function recogerRespuestaImagen($file)
    {

        $con = new ConexionBD();
        $conex = $con->conexion();
        $respuesta = "";
        $tipoUsuario = $_COOKIE['tipoUsuario'];
        $token = $_COOKIE['token'];
        mysqli_select_db($conex, "id15103239_workcontrol");
        $tipoimagen = $file['type'];
        $tipoimagen = str_replace('image/', '.', $tipoimagen);
        $tipo = $_COOKIE['tipoUsuario'];
        $nombre = $file['name'];
        if (!empty($file['name'])) {

            if (!empty($file['type'])) {
                if ((($file['type'] == "image/png") || ($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg"))) {
                    $directorio = '/imagenes/firmas';
                    $nombre = 'firma-' . $token;
                    $tmp_file = $file['tmp_name'];
                    $ruta = $directorio . '/' . $nombre . $tipoimagen;
                    // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
                    $respuesta = move_uploaded_file($tmp_file, $ruta);

                    if ($respuesta === true) {

                        if ($tipo === 'usuarioEmpresa') {
                            //actualizamos imagen en la base de datos
                            mysqli_select_db($conex, "id15103239_workcontrol");
                            $sql = mysqli_query($conex, "UPDATE usuarioEmpresa SET firma = '$ruta' WHERE token = '$token'");
                        }
                        if ($tipo === 'trabajadorEmpresa') {
                            //actualizamos imagen en la base de datos
                            mysqli_select_db($conex, "id15103239_workcontrol");
                            $sql = mysqli_query($conex, "UPDATE trabajadorEmpresa SET firma = '$ruta' WHERE token = '$token'");
                        }
                    }
                }
            }
        }
        $sql = mysqli_query($conex, "SELECT firma FROM $tipoUsuario WHERE token = '$token'");
        $row = mysqli_fetch_row($sql);
        if ($row[0] !== '') {
            $respuesta = array(
                'respuesta' => 'confirma'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'sinfirma'
            );
        }
        mysqli_free_result($sql);
        mysqli_close($conex);
        return $respuesta;
    }
    function mostrarUsuarioLogueado()
    {
        $con = new ConexionBD();
        $conex = $con->conexion();
        mysqli_select_db($conex, "id15103239_workcontrol");
        $result1 = mysqli_query($conex, "SELECT id FROM usuarioEmpresa WHERE logueado = 'true'");
        $result2 = mysqli_query($conex, "SELECT id FROM trabajadorEmpresa WHERE logueado = 'true'");
        $respuesta = false;
        if (mysqli_num_rows($result1) === 0 && mysqli_num_rows($result2) === 0) {
            $respuesta = false;
        } else {
            $respuesta = true;
        }
        // Cierro la conexión
        mysqli_free_result($result1);
        mysqli_free_result($result2);
        mysqli_close($conex);
        return $respuesta;
    }
    function numerodeUsuarios()
    {
        $con = new ConexionBD();
        $conex = $con->conexion();
        mysqli_select_db($conex, "id15103239_workcontrol");
        $result1 = mysqli_query($conex, "SELECT id FROM trabajadorEmpresa");
        $result2 = mysqli_query($conex, "SELECT id FROM usuarioEmpresa");
        $numero1 = mysqli_num_rows($result1);
        $numero2 = mysqli_num_rows($result2);

        if ($numero1 === 0 && $numero2 === 0) {
            $respuesta = false;
        } else {
            $respuesta = true;
        }

        // Cierro la conexión
        mysqli_free_result($result1);
        mysqli_free_result($result2);
        mysqli_close($conex);
        return $respuesta;
    }
}
