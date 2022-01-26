<?php

/**
 * ../ajax.php
 * Archivo de enlace entre AJAX y la Base de datos
 */

include_once $_SERVER['DOCUMENT_ROOT'] .'/inc/funciones.php';
$accion = $_POST['action'];
if ($accion === "Login") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
}
if ($accion === "registrarE") {
    $nombreempresa = $_POST['nombreempresa'];
    $token = $_POST['token'];
    $nombre = $_POST['nombre'];
    $apellido2 = $_POST['apellido2'];
    $apellido1 = $_POST['apellido1'];
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $dni = $_POST['dni'];
    $idempresa = $_POST['idempresa'];
    
}
if ($accion === "registrarT") {
    $empresa = $_POST['idempresa'];
    $token = $_POST['token'];
    $nombre = $_POST['nombre'];
    $apellido2 = $_POST['apellido2'];
    $apellido1 = $_POST['apellido1'];
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $dni = $_POST['dni'];
    $idempresa = $_POST['idempresa'];
    
}
if ($accion === "cerrarSesion") {
    $tipousuario = $_POST['tipoUsuario'];
    $token = $_POST['token'];
    
}
if ($accion === "subirCalendario") {
    $date = $_POST['date'];
    $entrada = $_POST['entrada'];
    $salida = $_POST['salida'];
    
}
if ($accion === "cogerCalendario") {
    $date = $_POST['date'];
    $token = $_POST['token'];
    $dni =  $_POST['dni'];
    $tipousuario =  $_POST['tipo'];
    
}
if ($accion === "recogerRespuestaImagen") {
    $file = $_FILES["file"];
}
switch ($accion) {
    case 'Login':
        $user = new Usuario();
        $usuario = $user->loguear($correo,$contrasena);
        echo json_encode($usuario);
        break;
    case 'registrarE':
        $user = new Usuario();
        $usuario = $user->registrarEmpresario($nombre,$apellido1,$apellido2,$contrasena,$telefono,$correo,$dni,$idempresa,$nombreempresa,$token);
        echo json_encode($usuario);
        break;
    case 'registrarT':
        $user = new Usuario();
        $usuario = $user->registrarTrabajador($nombre,$apellido1,$apellido2,$contrasena,$telefono,$correo,$dni,$idempresa,$empresa,$token);
        echo json_encode($usuario);
        break;
    case 'subirCalendario':
        $cal = new Calendario();
        $calendario = $cal->subirCalendario($date,$entrada,$salida);
        echo json_encode($calendario);
        break;
    case 'cogerCalendario':
        $cal = new Calendario();
        $calendario = $cal->cogerCalendario($date,$token,$dni,$tipousuario);
        echo json_encode($calendario);
        break;
    case 'cerrarSesion':
        $fun = new Funciones();
        $function = $fun->cerrarSesion($tipousuario,$token);
        echo json_encode($function);
        break;
    case 'recogerRespuestaImagen':
        $fun = new Funciones();
        $function = $fun->recogerRespuestaImagen($file);
        echo json_encode($function);
        break;
}
