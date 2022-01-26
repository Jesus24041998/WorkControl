<?php
/**
 * ../index.php
 * Página principal de la web , aquí tenemos los datos basicos de el usuario con el que nos hayamos registrado y también su horas de entrada y salida
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/template/header.php";
?>
<script>
try
{
    // Cuando leamos toda la página comprobamos si la Cookie login sigue activa o no , si no es así volvemos al login
    $(document).ready(function() {
    //Leo el php
    
        <?php
        if ($_COOKIE['login'] == 'true') {
        }
        else
        {
             ?>
            window.location.href="login.php";
            <?php
        }
        ?>
    });
}
catch (e)
{
}
</script>
<script>
    // Cuando leamos toda la página abrimos conexión con la base de datos y agregamos todos los trabajadores detro de la empresa en particular a las opciones de un select o 
    // lista desplegable , esto solo se muestra en el caso de que el usuario logeado sea el empresario y no el propio usuario dentro de la empresa.
    $(document).ready(function() {
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/conexion.php";

        $con = new ConexionBD();
        $conex = $con->conexion();
        $empresa = $_COOKIE['empresa'];
        $sql = "SELECT nombre,correo,token from trabajadorempresa where empresa='$empresa'";
        $resultset = mysqli_query($conex, $sql) or die("database error:" . mysqli_error($conex));
        while ($rows = mysqli_fetch_assoc($resultset)) {
        ?>
            $('#trabajadores').append(new Option('<?php echo $rows['nombre'] . ' | ' . $rows['correo'] ?>', '<?php echo $rows['token'] ?>', false, false));
        <?php
        }
        // Cierro la conexión
        $conex->close();
        ?>
    });
</script>
<?php

// Hacemos una segunda comprobación , esta vez miramos si la sesión esta activa ó si tenemos mas de 1 cookie , en ese caso lanzamos una alerta de bienvenida.
// En el caso negativo , volvemos al login.
 
if (count($_COOKIE) > 1 || session_status() == 2) {
?>
    <script>
        alertify
            .alert("Bienvenido, " + "<?php echo $_COOKIE['nombre'] . " " . $_COOKIE['apellido'] ?>", function() {});
    </script>

<?php
} else {
?>
    <script>
        window.open("../login.php", "_self");
    </script>
<?php
}
?>
<!--
- Estructura html del index.
-->
<div class="container">
    <div class="form-group row">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">
                <h3>Bienvenido <?php echo $_COOKIE['nombre'] . " " . $_COOKIE['apellido'] ?></h3>
            </a>
            <div class="col-12 col-md-6 mb-6 dropdown position-relative" id="dd">
                <button type="button" data-boundary="dd" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="../inc/modelos/imagenes/menu.png" width="20" height="20" alt="menu" data-toggle="collapse" />
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Inicio</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" data-toggle="modal" data-target="#modalFirma">Firma</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" onclick="cerrarSes();" id="cerrarSesion">Cerrar sesión</a>
                </div>
            </div>
        </nav>
    </div>
</div>

<script>
    // Funcion cerrarSes , se encarga de confirmar un cierre de sesión del usuario.
    // Cuando pulsamos el boton con id cerrarSesion , este nos pregunta si queremos cerrar sesión, en caso afirmativo , elimina las cookies recarga la página 
    // para que los cambios hagan efecto y nos movemos al lógin.
    function cerrarSes() {
        alertify.confirm("¿Está seguro de que quiere cerrar sesión?",
            function() {
                alertify
                    .alert("Se ha cerrado la sesión", function() {

                        var expiry = new Date();
                        expiry.setTime(expiry.getTime() - 3600);
                        document.cookie = "login=false";
                        document.cookie = 'nombre' + "=; expires=" + expiry.toGMTString() + "; path=/"
                        document.cookie = 'apellido' + "=; expires=" + expiry.toGMTString() + "; path=/"
                        document.cookie = 'tipoUsuario' + "=; expires=" + expiry.toGMTString() + "; path=/"
                        document.cookie = 'token' + "=; expires=" + expiry.toGMTString() + "; path=/"
                        location.reload();
                        window.open("../login.php", "_self");
                    });

            });
    }
</script>
<!-- 
- Estructura bootstrap que se nos muestra al pulsar la etiqueta con un data-target como modalFirma
- Esto abre un modal para la insercción de nuestra firma , en caso de que no hayamos agregado nuestra firma , se nos pedirá más adelante si queremos rellenar el horario.
-->
<div id="modalFirma" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gridModalLabel">Seleccione una firma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid row">
                    <div class="row justify-content-center">
                        <form id="formularioImagen" method="post" enctype="multipart/form-data">
                            <div class="form-group row">
                                <div class="col-12 col-md-6 mb-6">
                                    <input type="file" required name="imagen" id="imagen">
                                </div>
                            </div>
                            <div class="container-fluid row">
                                <div class="col-12 col-sm-9 col-md-6">
                                    <input type="submit" name="subir" class="btn btn-danger submitBtn" value="Subir Imagen">
                                    <script>
                                        $(document).ready(function(e) {
                                            $("#formularioImagen").on('submit', function(e) {
                                                var imagen = document.getElementById("imagen");
                                                var files = imagen.files;
                                                e.preventDefault();
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/inc/funciones/subirImagen.php',
                                                    data: new FormData(this),
                                                    contentType: false,
                                                    cache: false,
                                                    processData: false,
                                                    beforeSend: function() {
                                                        $('.submitBtn').attr("disabled", "disabled");
                                                        $('#formularioImagen').css("opacity", ".5");
                                                    },
                                                    success: function(msg) {
                                                        if (msg == 'ok') {
                                                            $('#formularioImagen')[0].reset();
                                                            alertify
                                                                .alert("Imagen subida correctamente", function() {});

                                                        }
                                                        if (msg == 'err') {
                                                            alertify
                                                                .alert("La imagen no se ha subido correctamente", function() {});
                                                        }
                                                        $('#fupForm').css("opacity", "");
                                                        $(".submitBtn").removeAttr("disabled");
                                                    }
                                                });
                                                //file type validation
                                                $("#file").change(function() {
                                                    var file = this.files[0];
                                                    var imagefile = file.type;
                                                    var match = ["image/jpeg", "image/png", "image/jpg"];
                                                    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                                                        alert('Selecciona un archivo valido');
                                                        $("#file").val('');
                                                        return false;
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 text-center">
                                    <p style="font-size:15px">Esta es su firma</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 col-sm-9 col-md-4">
                                    <img src="<?php
                                                require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
                                                $funtion = new FunctionBD();
                                                $buscarimagen = $funtion->recogerFirma();
                                                if ($buscarimagen != "") {
                                                    echo $buscarimagen;
                                                } else {
                                                    echo '../inc/modelos/imagenes/image-off.png';
                                                }; ?>" width="20" height="20" alt="firma" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 text-center">
                                    <p style="color:red;font-size:10px">Solo se aceptan imagenes en formato jpg,jpeg o png y un tamaño máximo de 8000 kilobytes</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h4>Administración</h4>
            <hr>

            <?php
            /**
             * Esta parte depende de que usuario seamos , por tanto si somos el usuario Empresa, se nos muestra , en caso contrario , no hay necesidad de mostrarlo.
             */
            $empresa = $_COOKIE['empresa'];
            if ($_COOKIE['tipoUsuario'] == 'Empresa') {
                echo "
        <div class ='form-group row'>
            <div class ='col-12 col-md-3 mb-3'> 
             <p>Usuarios de la empresa $empresa</p>
            </div> 
        
            <div class ='col-12 col-md-3 mb-3'>
                <select id='trabajadores'></select>
            </div>
        </div>
        ";
            }
            ?>
        </div>
    </div>
    <!--
    - Parte del calendario
    -->
    <div class="row mt-3">
        <div class="col">
            <div class="calendario">
                <div class="calendario__info">
                    <div class="calendario__anterior" id="anterior">&#9664;</div>
                    <div class="calendario__mes" id="mes"></div>
                    <div class="calendario__anio" id="anio"></div>
                    <div class="calendario__siguiente" id="siguiente">&#9654;</div>
                </div>

                <div class="calendario__semana">
                    <div class="calendario__dia">Lunes</div>
                    <div class="calendario__dia">Martes</div>
                    <div class="calendario__dia">Miercoles</div>
                    <div class="calendario__dia">Jueves</div>
                    <div class="calendario__dia">Viernes</div>
                    <div class="calendario__dia">Sabado</div>
                    <div class="calendario__dia">Domingo</div>
                </div>

                <div class="calendario__fecha" id="fecha"></div>
            </div>
        </div>
    </div>
    <!--
    - Parte del formulario de horas.
    -->
    <div class="row mt-3">
        <div class="col">
            <div class="infocalendario">
                <form>
                    <div class="container">
                        <div class="row justify-content-center" id="row1">
                            <div class="col-6 col-sm-1">
                                <p>Fecha:</p>
                            </div>
                            <div class="col-6 col-sm-1">
                                <input id="textodeshabilitado" type="date" disabled>
                            </div>
                        </div>
                        <div class="row justify-content-center" id="row1">
                            <div class="col-4 col-sm-2">
                                <p>Horario de entrada:</p>
                            </div>
                            <div class="col-4 col-sm-1">
                                <input id="textoentrada" type="time" disabled>
                            </div>
                        </div>
                        <div class="row justify-content-center" id="row1">
                            <div class="col-4 col-sm-2">
                                <p>Horario de salida:</p>
                            </div>
                            <div class="col-4 col-sm-1">
                                <input id="textosalida" type="time" disabled>
                            </div>
                        </div>

                        <div class="row justify-content-center" id="row1">
                            <div class="col-4 text-center">
                                <button class="btn-block" type="submit"id ="botonsubmit" disabled>Agregar horario</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/template/footer.php";
?>