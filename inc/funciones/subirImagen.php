<?php
/**
 * ../inc/funciones/subirImagen.php
 * Archivo especial que a traves de un formulario nos agrega una url dentro de la web para recoger imagenes
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/conexion.php";

$con = new ConexionBD();
$conex = $con->conexion();




if (!empty($_FILES['imagen']['name'])) {

    $nombre_img = $_FILES['imagen']['name'];

    if (!empty($_FILES["imagen"]["type"])) {
        if ((($_FILES["imagen"]["type"] == "image/png") || ($_FILES["imagen"]["type"] == "image/jpg") || ($_FILES["imagen"]["type"] == "image/jpeg"))) {
            $directorio = "../inc/modelos/imagenes";
            // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
            $respuesta = move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio ."/".$nombre_img);
            if ($respuesta == 1) {
                $resp = $directorio . $nombre_img;
                session_start();
                $token = $_SESSION['token'];
                $tipo = $_SESSION['tipoUsuario'];
                if ($tipo === 'Empresa') {
                    //actualizamos imagen en la base de datos
                    $sql  = $conex->query("UPDATE usuarioempresa SET imagen = '$resp' WHERE token = '$token'");
                }
                if ($tipo === 'Trabajo') {
                    //actualizamos imagen en la base de datos
                    $sql = $conex->query("UPDATE trabajadorempresa SET imagen = '$resp' WHERE token = '$token'");
                }
                // Condicional para verificar la subida del fichero
                echo $sql ? 'ok' : 'err';
            }
        }
    }
}
