<?php
/**
 * ../login.php
 * Archivo principal que coordina el login de todo el html , desde aqui podemos acceder a la zona de registro o iniciar sesión, por defecto sera la página que reciva a nuevos usuarios
 */
include $_SERVER['DOCUMENT_ROOT']."/inc/template/header.php";
?>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <form class="formulario" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <div class="col-12 col-md-3 mb-3">
                        <h5 id="titulo" style>Iniciar sesión</h5>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-9 mb-4">
                            <label for="nombrel">Correo</label>
                            <input type="text" class="form-control" placeholder="Nombre"  name="correologin" id="correo">
                        </div>
                        <div class="col-12 col-md-9 mb-4">
                            <label for="contrasenal">Contraseña</label>
                            <input type="password" class="form-control" placeholder="Contraseña" name="contrasenalogin" id="contrasena">
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-9 col-md-4 mb-3">
                        <button class="btn btn-primary btn-block" id="sub" type="submit">Enviar</button>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 col-sm-9 col-md-4 mb-3">
                        <div class="bd-example">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#gridSystemModal">
                                ¿No tiene cuenta?, registrese
                            </button>
                        </div>
                    </div>
                </div>
                <div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="gridModalLabel">Seleccione como quiere registrarse</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid bd-example-row">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6 mb-6">
                                            <a href="registrarempresario.php">Registrar Empresario</a>
                                        </div>
                                        <div class="col-12 col-md-6 mb-6">
                                            <a href="registrartrabajador.php">Registrar Trabajador</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/template/footer.php";
?>