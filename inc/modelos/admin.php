<?php
/**
 * ../admin.php
 * Archivo de enlace entre AJAX y la Base de datos
 */
 
$accion= $_POST['action'];
if($accion==="registrarT")
{
$empresa = $_POST['empresa'];
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
if($accion==="registrarE")
{
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
echo json_encode($accion);
}
if($accion==="subirCalendario")
{
$date = $_POST['date'];
$entrada= $_POST['entrada'];
$salida= $_POST['salida'];
}
if($accion==="cogerCalendario")
{
$date = $_POST['date'];
$token = $_POST['token'];
}
if($accion==="Login")
{
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
}
switch ($accion) {
    case 'registrarT':
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
        $funtion = new FunctionBD();
        $usuariost = $funtion->registrarUsuarioTrabajo($nombre, $apellido1, $apellido2, $contrasena, $telefono, $correo, $dni, $empresa, $token);
        echo json_encode($usuariost);
        ?>
            <script type="text/javascript">
            alert("Datos registrados con exito");
            </script>
        <?php
        break;
    case 'registrarE':
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
        $funtion = new FunctionBD();
        $usuariose = $funtion->registrarUsuarioEmpresa($nombre, $apellido1, $apellido2, $contrasena, $telefono, $correo, $dni, $idempresa, $nombreempresa, $token);
        echo json_encode($usuariose);
        ?>
            <script type="text/javascript">
            alert("Datos registrados con exito");
            </script>
        <?php
        break;
    case 'Login':
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
        $funtion = new FunctionBD();
        $login = $funtion->logear($correo, $contrasena);
        echo json_encode($login);
        json_encode("");
        break;
    case 'subirCalendario': {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
        $funtion = new FunctionBD();
        $subircalendario = $funtion->agregarTiempo($date, $entrada, $salida);
        echo json_encode($subircalendario);
        }
    case 'cogerCalendario': {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
        $funtion = new FunctionBD();
        $cogerCalendario = $funtion->recogerTiempo($token,$date);
        $a=json_encode($cogerCalendario);
        if($cogerCalendario!="")
        {
        echo $a;
        }
        }
}
?>