<?php
/**
 * ../registrarempresario.php
 * Archivo php usado para el formulario de el registro del Empresario
 */
include $_SERVER['DOCUMENT_ROOT']."/inc/template/header.php";
?>

<div class="container">
	<div class="row mt-3">
		<div class="col">
			<form class='formulario' name="registroempresario" method="post" action="registrarE" enctype="multipart/form-data">
			    
			    <div class="form-group row">

                    <div class="col-12 col-md-3 mb-3">
                        <h5 id="titulo" style>Registro empresario</h5>
                    </div>
                </div>

				<div class="form-group row">
					<div class="col-12 col-md-3 mb-3">
						<label for="nombre">Nombre</label><label class="requerido">(*)</label>
						<input type="text" required="required" class="form-control" placeholder="Nombre" name="nombre" id="nombre">
					</div>
					<div class="col-12 col-md-3 mb-3">
						<label for="apellido1">Primer apellido</label><label class="requerido">(*)</label>
						<input type="text" required="required" class="form-control" placeholder="Primer apellido" name="apellido1" id="apellido1">
					</div>
					<div class="col-12 col-md-3 mb-3">
						<label for="apellido2">Segundo apellido</label>
						<input type="text" class="form-control" placeholder="Segundo apellido" name="apellido2" id="apellido2">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-12 col-md-3 mb-3">
						<label for="pass">Contraseña</label><label class="requerido">(*)</label>
						<input type="password" required="required" class="form-control" placeholder="Contraseña" name="contrasena" id="contrasena">
					</div>
					<div class="col-12 col-md-3 mb-3">
						<label for="dni">Teléfono</label><label class="requerido">(*)</label>
						<input type="text" required="required" class="form-control" placeholder="Telefono" name="telefono" id="telefono">
					</div>
					<div class="col-12 col-md-3 mb-3">
						<label for="correo">Correo</label><label class="requerido">(*)</label>
						<input type="text" required="required" class="form-control" placeholder="usuario@gmail.com" name="correo" id="correo">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-12 col-md-3 mb-3">
						<label for="dni">DNI del particular</label><label class="requerido">(*)</label>
						<input type="text" required="required" class="form-control" placeholder="DNI" name="dni" id="dni">
					</div>
					<div class="col-12 col-md-3 mb-3">
						<label for="nif-cif">Identificador fiscal de la empresa</label>
						<label class="requerido">(*)</label>
						<select id="cifnif">
							<option id="nif" value="nif" selected>NIF</option>
							<option id="cif" value="cif">CIF</option>
						</select>
						<input type="text" required="required" class="form-control" placeholder="NIF o CIF" name="nifocif" id="nifocif">
					</div>
					<div class="col-12 col-md-3 mb-3">
						<label for="enombreempresa">Nombre de la empresa</label><label class="requerido">(*)</label>
						<input type="text" required="required" class="form-control" placeholder="Nombre de la empresa" name="nombreempresa" id="nombreempresa">
					</div>
				</div>
			
				<div class="form-group row">
					<div class="col-12 text-center">
						<label class="form-check-label">
							<br>
							<input type="checkbox" required="required" name="terminos" id="terminos" class="form-check-input mr-2 mb-3">Acepto Términos y Condiciones</label>
						<br>
						<div class="row justify-content-center">
							<div class="col-12 col-sm-9 col-md-4">
							<input type="submit" id="sub" class="btn btn-primary btn-block"onclick="eventListeners();"></label>
							</div>
							<div class="col-12 col-sm-9 col-md-4">
								<input type="reset" id="reset" class="btn btn-primary btn-block"></label>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-12 col-sm-9 col-md-4">
								<label id="validiti">
							</div>
						</div>
					</div>
				</div>
			</form>

			<?php
			include $_SERVER['DOCUMENT_ROOT']."/inc/template/footer.php";
			?>