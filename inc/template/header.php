<!--
- ../inc/template/header.php    
- El encabezadp de página para todos los html de la web.
-->
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/funciones/funciones.php";
?>

<head>

<script src="js/jquery-3.5.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


    <script src="js/calendario.js" async="async"></script>
    <script src="js/formulario.js"></script>
    <link rel="stylesheet" href="css/alertify.css">
    <script src="js/alertify.min.js"></script>
    <script type="text/javascript"></script>
    <link rel="stylesheet" href="css/style.css">
   

    <title>WorkControl-<?php

                        $funcion = new FunctionBD();
                        $pagina = $funcion->obtenerPaginaActual();
                        $file = fopen("pagina.txt", "w") or die("Se produjo un error al crear el archivo");
                        fwrite($file, $pagina) or die("Se produjo un error al escribir");
                        fclose($file);
                        var_export(str_replace("'", "", $pagina));
                        ?></title>
</head>

<body>
    <?php
    if ($pagina == 'login' || $pagina == 'index') {
    } else {
        echo '<div class="container">
        <div class="row mt-3">
            <div class="col">
                <div class="form-group row">
                    <div class="col-12 col-md-3 mb-3">
                        <a id="flecha" title="" href="login.php"><img src="../inc/modelos/imagenes/back-arrow.png" width="20" height="20" alt="haciaatras" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    }
    ?>