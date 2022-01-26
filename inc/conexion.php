<?php
/**
 * class ConexionBD 
 * @author Jesús Rodríguez Malagón
 */
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
    /**
    * Funcion conexion que contiene los datos de la base de datos y conecta
    * @return $conn
    */
    function conexion()
    {


        $host="localhost";
        $user="root";
        $password="";
        //$host="localhost";   
        //$user="id15103239_workcontroladmin";
        //$password="%%mHXABo6yFL@OzMMk3";
        $conn = mysqli_connect($host,$user,$password);
        if ($conn->connect_error) {
        echo $conn->connect_error;
        }

        $conn->set_charset('utf8');

        return $conn;
    }
}
?>