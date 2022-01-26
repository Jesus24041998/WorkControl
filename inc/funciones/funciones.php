<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/conexion.php";


class FunctionBD
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
	function obtenerPaginaActual()
	{
		$archivo = basename($_SERVER['PHP_SELF']);
		$pagina = str_replace(".php", "", $archivo);
		setcookie('token', false, time() + (86400 * 5), "/");

		return trim($pagina);
	}

	[HttpPost]
	function subir_fichero($token, $tamano, $tipo, $temporalname, $error, $lugar)
	{
		// Conecto con la base de datos

		$con = new ConexionBD();
		$conex = $con->conexion();
		$respuesta = true;
		// Compruebo la conexión
		if ($conex->connect_error) {
			die("Connection failed: " . $conex->connect_error);
		}

		if ($error > 0) {
			$respuesta = false;
		} else {

			if ($lugar === 'registrarempresario') {
				// Verificamos si el tipo de archivo es un tipo de imagen permitido.
				// y que el tamaño del archivo no exceda los 16MB
				$permitidos = array("image/jpeg");
				$limite_kb = 16384;

				if (in_array($tipo, $permitidos) && $tamano <= $limite_kb * 1024) {

					// Leemos el contenido del archivo temporal en binario
					$data = file_get_contents($temporalname);
					$funtion = new FunctionBD();
					// Escapamos los caracteres para que se puedan almacenar en la base de datos correctamente.
					$data = addslashes($data);
					// Insertamos en la base de datos.
					$sql1 = "INSERT INTO imagenesusuario (imagen) VALUES ('$data')";
					$conex->query($sql1);
				} else {
					$respuesta = false;
				}
			} else {
				// Verificamos si el tipo de archivo es un tipo de imagen permitido.
				// y que el tamaño del archivo no exceda los 16MB
				$permitidos = array("image/jpeg");
				$limite_kb = 16384;

				if (in_array($tipo, $permitidos) && $tamano <= $limite_kb * 1024) {

					// Leemos el contenido del archivo temporal en binario
					$data = file_get_contents($temporalname);
					$funtion = new FunctionBD();
					// Escapamos los caracteres para que se puedan almacenar en la base de datos correctamente.
					$data = addslashes($data);
					// Insertamos en la base de datos.
					$sql1 = "INSERT INTO imagenes (imagen,token_usuario) VALUES ('$data','$token')";
					$conex->query($sql1);
				} else {
					$respuesta = false;
				}
			}
		}
		// Cierro la conexión
		$conex->close();
		return $respuesta;
	}
	function registrarUsuarioEmpresa($nombre, $apellido1, $apellido2, $contrasena, $telefono, $correo, $dni, $identificadorempresa, $nombreempresa, $token)
	{
		$con = new ConexionBD();
		$conex = $con->conexion();
		$correo_usuario = '';
		$correo_usuario2 = '';
		$contrasenacifrada = password_hash($contrasena, PASSWORD_DEFAULT);
		try {
			$stmt = $conex->prepare("SELECT correo FROM trabajadorEmpresa WHERE correo = '$correo'");
			$stmt2 = $conex->prepare("SELECT correo FROM usuarioEmpresa WHERE correo = '$correo'");
			if ($stmt) {
				$stmt->execute();
				// Loguear el usuario
				$stmt->bind_result($correo_usuario);
				$stmt->fetch();
				$stmt->close();
			}
			if ($stmt2) {
				$stmt2->execute();
				// Loguear el usuario
				$stmt2->bind_result($correo_usuario2);
				$stmt2->fetch();
				$stmt2->close();
			}
			if ($correo_usuario !== $correo && $correo_usuario2 !== $correo) {
				// Realizar la consulta a la base de datos
				$stmt3 = $conex->prepare("INSERT INTO usuarioempresa (nombre, apellido1, apellido2, contrasena, telefono,correo,dni,idempresa,token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
				$stmt4 = $conex->prepare("INSERT INTO empresa (nombre,identificadordelaempresa,jefe) VALUES (?, ?,?)");
				$stmt4->bind_param('sss', $nombreempresa, $identificadorempresa, $correo);
				$stmt4->execute();
				$stmt3->bind_param('sssssssss', $nombre, $apellido1, $apellido2, $contrasenacifrada, $telefono, $correo, $dni, $identificadorempresa, $token);
				$stmt3->execute();

				if ($stmt3->affected_rows > 0 && $stmt4->affected_rows > 0) {
					$respuesta = array(
						'respuesta' => 'correcto',
						'tipo' => 'usuariot',
						'correo' => "$correo",
						'contrasena' => "$contrasena"
					);
				} else {
					$respuesta = array(
						'respuesta' => 'error'
					);
				}
				$stmt3->close();
				$stmt4->close();
			} else {
				$respuesta = array(
					'respuesta' => 'registrado'
				);
			}
		} catch (Exception $e) {
			// En caso de un error, tomar la exepcion
			$respuesta = array(
				'respuesta' => 'error'
			);
		}
		$conex->close();
		return $respuesta;
	}
	function registrarUsuarioTrabajo($nombre, $apellido1, $apellido2, $contrasena, $telefono, $correo, $dni, $empresa, $token)
	{
		$con = new ConexionBD();
		$conex = $con->conexion();
		$correo_usuario = '';
		$correo_usuario2 = '';
		$contrasenacifrada = password_hash($contrasena, PASSWORD_DEFAULT);
		try {
			$stmt = $conex->prepare("SELECT correo FROM trabajadorEmpresa WHERE correo = '$correo'");
			$stmt2 = $conex->prepare("SELECT correo FROM usuarioEmpresa WHERE correo = '$correo'");
			if ($stmt) {
				$stmt->execute();
				// Loguear el usuario
				$stmt->bind_result($correo_usuario);
				$stmt->fetch();
				$stmt->close();
			}
			if ($stmt2) {
				$stmt2->execute();
				// Loguear el usuario
				$stmt2->bind_result($correo_usuario2);
				$stmt2->fetch();
				$stmt2->close();
			}
			if ($correo_usuario !== $correo && $correo_usuario2 !== $correo) {
				// Realizar la consulta a la base de datos
				$stmt3 = $conex->prepare("INSERT INTO trabajadorempresa (nombre, apellido1, apellido2, contrasena, telefono,correo,dni,empresa,token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ");

				$stmt3->bind_param('sssssssss', $nombre, $apellido1, $apellido2, $contrasenacifrada, $telefono, $correo, $dni, $empresa, $token);
				$stmt3->execute();

				if ($stmt3->affected_rows > 0) {
					$respuesta = array(
						'respuesta' => 'correcto',
						'tipo' => 'usuarioe',
						'correo' => "$correo",
						'contrasena' => "$contrasena"
					);
				} else {
					$respuesta = array(
						'respuesta' => 'error'
					);
				}
				$stmt3->close();
			} else {
				$respuesta = array(
					'respuesta' => 'registrado'
				);
			}
		} catch (Exception $e) {
			// En caso de un error, tomar la exepcion
			$respuesta = array(
				'respuesta' => 'error'
			);
		}

		$conex->close();
		return $respuesta;
	}
	function eliminarUsuario($nombre, $contrasena, $tipousuario)
	{
		$con = new ConexionBD();
		$conex = $con->conexion();


		if ($tipousuario == 'empresa') {
			$select = sprintf("SELECT contrasena FROM usuarioEmpresa WHERE nombre='$nombre'");
			$resultado = mysqli_query($conex, $select);
			$fila = $resultado->fetch_row();

			$contrasenadescifrada = password_verify($contrasena, $fila[0]);
			if ($contrasenadescifrada) {
				$fila = $resultado->fetch_row();
				$sql = "DELETE from usuarioEmpresa WHERE nombre = '$nombre' AND contrasena = '$fila[0]'";
			}
		} else {
			$select = sprintf("SELECT contrasena FROM trabajadorEmpresa WHERE nombre='$nombre'");
			$resultado = mysqli_query($conex, $select);
			$fila = $resultado->fetch_row();

			$contrasenadescifrada = password_verify($contrasena, $fila[0]);
			if ($contrasenadescifrada) {
				$fila = $resultado->fetch_row();
				$sql = "DELETE from trabajadorEmpresa WHERE nombre = '$nombre' AND contrasena = '$fila[0]'";
			}
		}
		// Cierro la conexión
		$conex->close();
	}
	function obtenerUsuario($nombre, $contrasena, $tipousuario)
	{

		$con = new ConexionBD();
		$conex = $con->conexion();


		if ($tipousuario == 'empresa') {
			$sql = sprintf("SELECT * FROM usuarioEmpresa where nombre = '$nombre' AND contrasena = '$contrasena'");
			$datos = array();

			if ($resultado = mysqli_query($conex, $sql)) {
				// Obtener el array de objetos
				while ($fila = $resultado->fetch_row()) {
					$datos[] = array(
						'nombre' => $fila[0], 'contrasena' => $fila[1],
						'token' => $fila[2]
					);
				}
				// Liberar el conjunto de resultados
				$resultado->close();
			}
		} else {
			$sql = sprintf("SELECT * FROM trabajadorEmpresa where nombre = '$nombre' AND contrasena = '$contrasena'");
			$datos = array();

			if ($resultado = mysqli_query($conex, $sql)) {
				// Obtener el array de objetos
				while ($fila = $resultado->fetch_row()) {
					$datos[] = array(
						'usuario' => $fila[0], 'contrasena' => $fila[1],
						'token' => $fila[2]
					);
				}
				// Liberar el conjunto de resultados
				$resultado->close();
			}
		}
		// Cierro la conexión
		$conex->close();

		return $datos;
	}
	function logear($correo, $contrasena)
	{
		$con = new ConexionBD();
		$conex = $con->conexion();
		$id_usuario = '';
		$nombre_usuario = '';
		$apellido_usuario = '';
		$contrasena_usuario = '';
		$correo_usuario = '';
		$empresa = '';
		$idempresa = '';
		$token = '';
		$stmt = $conex->prepare("SELECT id,nombre,apellido1,contrasena,correo,empresa,token FROM trabajadorEmpresa WHERE correo = '$correo'");
		$stmt->execute();
		// Loguear el usuario
		$stmt->bind_result($id_usuario, $nombre_usuario, $apellido_usuario, $contrasena_usuario, $correo_usuario, $empresa, $token);
		$stmt->fetch();
		$stmt->close();
		if ($correo_usuario !== "") {
			// El usuario existe, verificar el password
			if (password_verify(trim($contrasena), trim($contrasena_usuario))) {
				session_start();
				$_SESSION['nombre'] = $nombre_usuario;
				$_SESSION['apellido'] = $apellido_usuario;
				$_SESSION['correo'] = $correo_usuario;
				$_SESSION['id'] = $id_usuario;
				$_SESSION['login'] = true;
				$_SESSION['token'] = $token;
				$_SESSION['tipoUsuario'] = 'Trabajo';
				$_SESSION['tiempo'] = time();
				setcookie('login', 'true', time() + (86400 * 5), "/");
				setcookie('nombre', $nombre_usuario, time() + (86400 * 5), "/");
				setcookie('apellido', $apellido_usuario, time() + (86400 * 5), "/");
				setcookie('tipoUsuario', 'Trabajo', time() + (86400 * 5), "/");
				setcookie('token', $token, time() + (86400 * 5), "/");
				setcookie('empresa', $empresa, time() + (86400 * 5), "/");
				// Login correcto
				$respuesta = array(
					'respuesta' => 'login'
				);
			} else {
				// Login incorrecto, enviar error
				$respuesta = array(
					'respuesta' => 'contrasenaerror'
				);
			}
		} else {
			$stmt = $conex->prepare("SELECT id,nombre,apellido1,contrasena,correo,idempresa,token FROM usuarioEmpresa WHERE correo = '$correo'");
			$stmt->execute();
			// Loguear el usuario
			$stmt->bind_result($id_usuario, $nombre_usuario, $apellido_usuario, $contrasena_usuario, $correo_usuario, $idempresa, $token);
			$stmt->fetch();
			$stmt->close();
			if ($correo_usuario) {
				// El usuario existe, verificar el password
				if (password_verify(trim($contrasena), trim($contrasena_usuario))) {

					$stmt2 = $conex->prepare("SELECT nombre FROM empresa WHERE identificadordelaempresa = '$idempresa'");
					$stmt2->execute();
					// Loguear el usuario
					$stmt2->bind_result($empresa);
					$stmt2->fetch();
					$stmt2->close();
					session_start();
					$_SESSION['nombre'] = $nombre_usuario;
					$_SESSION['apellido'] = $apellido_usuario;
					$_SESSION['correo'] = $correo_usuario;
					$_SESSION['id'] = $id_usuario;
					$_SESSION['login'] = true;
					$_SESSION['token'] = $token;
					$_SESSION['tipoUsuario'] = 'Empresa';
					setcookie('login', 'true', time() + (86400 * 5), "/");
					setcookie('nombre', $nombre_usuario, time() + (86400 * 5), "/");
					setcookie('apellido', $apellido_usuario, time() + (86400 * 5), "/");
					setcookie('tipoUsuario', 'Empresa', time() + (86400 * 5), "/");
					setcookie('token', $token, time() + (86400 * 5), "/");
					setcookie('empresa', $empresa, time() + (86400 * 5), "/");

					// Login correcto
					$respuesta = array(
						'respuesta' => 'login',
					);
				} else {
					// Login incorrecto, enviar error
					$respuesta = array(
						'respuesta' => 'contrasenaerror'
					);
				}
			} else {
				$respuesta = array(
					'respuesta' => 'nouser'
				);
			}
		}
		$conex->close();
		return $respuesta;
	}
	function recogerFirma()
	{
		$con = new ConexionBD();
		$conex = $con->conexion();
		$urlImagen = '';
		$tipo = $_COOKIE['tipoUsuario'];
		$token = $_COOKIE['token'];
		if ($tipo === 'Empresa') {
			$stmt = $conex->prepare("SELECT imagen FROM usuarioempresa WHERE token = '$token'");
		}
		if ($tipo === 'Trabajo') {
			$stmt = $conex->prepare("SELECT imagen FROM trabajadorempresa WHERE token = '$token'");
		}
		$stmt->execute();
		$stmt->bind_result($urlImagen);
		$stmt->fetch();
		$stmt->close();
		$conex->close();
		return $urlImagen;
	}
	function recogerTiempo($token, $date)
	{
		$con = new ConexionBD();
		$conex = $con->conexion();
		$entrada = '';
		$salida = '';
		$array = "";
		$respuesta = "";
		if ($conex->connect_error) {
			die("Connection failed: " . $conex->connect_error);
		}

		$sql = "SELECT horaentrada,horasalida FROM calendario WHERE token = '$token' AND fecha='$date'";
		$result = $conex->query($sql);
		if ($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()) {
				$respuesta = array(
					'r1' => 'recogido',
					'entrada' => $row["horaentrada"],
					'salida' => $row["horasalida"]
				);
			}
		}
		$conex->close();
		return $respuesta;
	}
	function agregarTiempo($date, $inicio, $final)
	{
		//Actualizar
		$con = new ConexionBD();
		$respuesta = "";

		if(strlen($inicio)===5)
		{
			$inicio=$inicio.":00";
			$inicio=date($inicio);
		}
		else{
			$inicio=date($inicio);	
		}
		if(strlen($final)===5)
		{
			$final=$final.":00";
			$final=date($final);
		}
		else{
			$final=date($final);
		}
		$conex = $con->conexion();
		$token = $_COOKIE['token'];
		$sql = "SELECT count(fecha) as cuenta from calendario WHERE fecha = '$date'";
		
		$result = $conex->query($sql);
		if ($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()) {
				if($row['cuenta']>0)
				{
					$sql="UPDATE calendario SET horaentrada = '$inicio' , horasalida = '$final' WHERE calendario.fecha = '$date'";
					$link="";
					if($conex->query($sql)===True)
					{
						$respuesta = array(
							'respuesta' => 'actualizado'
						);
					}
					else{
						throw new Exception ('Ocurrio un error al actualizar '.$conex->error);
					}
				}
				else{
					$sql="INSERT INTO calendario (token,fecha,horaentrada,horasalida) VALUES ('$token', '$date', '$inicio', '$final')";
					$link="";
					if($conex->query($sql)===True)
					{
						$respuesta = array(
							'respuesta' => 'insertado'
						);
					}
					else{
						throw new Exception ('Ocurrio un error al insertar '.$conex->error);
					}
				}
			}
		}
		$conex->close();
		return $respuesta;
	}
}
