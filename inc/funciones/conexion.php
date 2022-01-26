<?php
class ConexionBD
{
    /**
     * Description: Constructor de la clase
     * Conecta automáticamente con la base de datos
     */
    function __construct()
    {
    }
    /**
     * Description: Destructor de la clase
     * Cierra la conexión automáticamente con la base de datos
     */
    function __destruct()
    {
    }

    function conexion()
    {
        $host="localhost";   
$user="root";
$password="";
$db="workcontrol";
$conn = new mysqli($host,$user,$password,$db);
        if ($conn->connect_error) {
            echo $conn->connect_error;
        }

        $conn->set_charset('utf8');

        return $conn;
    }
}
?>