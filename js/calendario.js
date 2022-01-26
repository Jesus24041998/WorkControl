/**
 * ../js/calendario.js
 * Archivo javascript que se encarga de los funcionamientos principales de el calendario , asi como de el formulario de horas agregado al calendario.
 */
$(document).ready(function () {
    if (document.title === "WorkControl-'index'") {
        //Asignamos alguna información importante para proceder con nuestro calendario
        let nombremeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        let tiempoactual = new Date();
        var tiempo = tiempoactual.getTime();
        let diaactual = tiempoactual.getDate();
        let numerodelmes = tiempoactual.getMonth();
        let anioactual = tiempoactual.getFullYear();

        var fecha = document.querySelector("#fecha");
        var mes = document.querySelector("#mes");
        var anio = document.querySelector("#anio");

        var yearactual = anioactual;
        var mesnumber = numerodelmes;
        mes.textContent = nombremeses[numerodelmes];
        anio.textContent = anioactual.toString();

        document.querySelector("#anterior").addEventListener('click', mesAnterior);
        document.querySelector("#siguiente").addEventListener('click', mesSiguiente);
        agregarNuevaFecha();
        //Abrimos el menu del trabajador / el empresario solo puede ver si hay datos , no manipularlos.
        document.addEventListener('submit', subirCalendario);
        /**
         * Esta funcion se encarga de de los cambios básicos en un calendario cuando disminuimos en 1 el mes.
         */
        function mesAnterior() {
            $("#calendario_marcado").attr("id", "");
            if (mesnumber !== 0) {
                mesnumber--;
            } else {
                mesnumber = 11;
                yearactual--;
            }

            agregarNuevaFecha();

        }
        /**
        * Esta función se encarga de de los cambios básicos en un calendario cuando aumentamos en 1 el mes.
        */
        function mesSiguiente() {
            $("#calendario_marcado").attr("id", "");
            if (mesnumber !== 11) {
                mesnumber++;
            } else {
                mesnumber = 0;
                yearactual++;
            }

            agregarNuevaFecha();
        }

        /**
         * Esta función reforma el calendario para que los datos nuevos aparezcan.
         */
        function agregarNuevaFecha() {
            tiempoactual.setFullYear(yearactual, mesnumber, diaactual);
            mes.textContent = nombremeses[mesnumber];
            anio.textContent = yearactual.toString();
            fecha.textContent = '';
            escribirMes(mesnumber);
        }
        /**
         * Esta funcion se encarga de autocompletar el calendario con los meses y dias del mes dentro de nuestro html.
         * parametros(int mes)
         */
        function escribirMes(mes) {
            for (let i = diaEmpezar(); i > 0; i--) {
                fecha.innerHTML += ` <button class='calendario_dias' id='calendario_otrosdias' disabled value =${obtenerDiasTotales(mes - 1) - (i - 1)}>
            ${obtenerDiasTotales(mes - 1) - (i - 1)}
        </div>`;
            }
            var numbermes = mes + 1;
            for (let i = 1; i <= obtenerDiasTotales(mes); i++) {
                //El dia actual
                if (i === diaactual && mes === numerodelmes && yearactual === anioactual) {
                    if (i >= 10) {
                        if (mes < 9) {
                            var elemento = `${yearactual}-${"0" + numbermes}-${i}`;
                        }
                        else {
                            var elemento = `${yearactual}-${numbermes}-${i}`;
                        }
                    }
                    else {
                        if (mes < 9) {
                            var elemento = `${yearactual}-${"0" + numbermes}-${"0" + i}`;
                        }
                        else {
                            var elemento = `${yearactual}-${numbermes}-${"0" + i}`;
                        }
                    }
                    fecha.innerHTML += ` <button class='calendario_dias' id='calendario_marcado' value ='${elemento}'>${i}</div>`;
                    recogerDatos(elemento);
                } else {
                    if (i >= 10) {
                        if (mes < 9) {
                            var elemento = `${yearactual}-${"0" + numbermes}-${i}`;
                        }
                        else {
                            var elemento = `${yearactual}-${numbermes}-${i}`;
                        }
                    }
                    else {
                        if (mes < 9) {
                            var elemento = `${yearactual}-${"0" + numbermes}-${"0" + i}`;
                        }
                        else {
                            var elemento = `${yearactual}-${numbermes}-${"0" + i}`;
                        }
                    }
                    fecha.innerHTML += ` <button class='calendario_dias' value ='${elemento}'>${i}</div>`;

                }
            }
            $('.calendario_dias').click(function () {
                elegirDia($(this));
            });


        }
        /**
         * Esta función se encarga de marcarnos desde que dia se empieza a contar el mes.
         */
        function diaEmpezar() {
            let empieza = new Date(anioactual, numerodelmes, 1);
            return ((empieza.getDay() - 1) === -1) ? 6 : empieza.getDay() - 1;
        }

        /**
         * Esta función se encarga de recoger todos los dias del mes y controla si el año es bisiesto etc.
         * parametros(int mes)
         */
        function obtenerDiasTotales(mes) {
            var devolver;
            if (mes === -1) {
                mes = 11;
            }
            if (mes == 0 || mes == 2 || mes == 4 || mes == 6 || mes == 7 || mes == 9 || mes == 11) {
                devolver = 31;

            } else if (mes == 3 || mes == 5 || mes == 8 || mes == 10) {
                devolver = 30;

            } else {

                devolver = esBisiesto() ? 29 : 28;
            }
            return devolver;
        }

        function esBisiesto() {
            return ((anioactual % 100 !== 0) && (anioactual % 4 === 0) || (anioactual % 400 === 0));
        }

        function loadWorker() {
            var checktrabajadores = document.querySelector("#trabajadores");
            textTrabajadores = checktrabajadores.options[checktrabajadores.selectedIndex].value;
        }



        //Arreglar
        function elegirDia(boton) {
            var cookie = readCookie("tipoUsuario", document);


            $("#calendario_marcado").attr("id", "");
            if (numerodelmes == mesnumber || boton) {
                boton.attr("id", "calendario_marcado");
            }
            if (cookie === "Trabajo") {
                $(".infocalendario #textoentrada").prop('disabled', false);
                $(".infocalendario #textosalida").prop('disabled', false);
                $(".infocalendario #botonsubmit").prop('disabled', false);
            }
            recogerDatos(boton.val());
        }
        function readCookie(name, document) {

            return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + name.replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;

        }
        //Metodo que se encarga de recoger el dia seleccionado en el calendario desde la base de datos para saber si este tiene alguna firma en ese dia
        function recogerDatos(elemento) {
            $(".infocalendario #textodeshabilitado").val(`${elemento}`);
            //document.getElementById('textodeshabilitado').innerHTML = "'" + $(this).val(); + "'";
            var cookie = readCookie("tipoUsuario", document);
            var cookie2 = readCookie("token", document);
            if (cookie === "Empresa") {
                loadWorker();
            }
            else {
                $(".infocalendario #textoentrada").prop('disabled', false);
                $(".infocalendario #textosalida").prop('disabled', false);
                $(".infocalendario #botonsubmit").prop('disabled', false);
                textTrabajadores = cookie2;
            }
            var datos = new FormData();
            datos.append('date', elemento);
            datos.append('token', textTrabajadores);
            datos.append('action', "cogerCalendario");
            // crear el llamado a ajax
            var xhr = new XMLHttpRequest();

            // abrir la conexión.
            xhr.open('POST', 'inc/modelos/admin.php', true);

            // retorno de datos
            xhr.onreadystatechange = function (aEvt) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var respuesta = JSON.parse(xhr.responseText);
                        if (respuesta.r1 === 'recogido') {
                            $('#textoentrada').val( respuesta.entrada );
                            $('#textosalida').val( respuesta.salida );
                            respuesta="";
                        }
                        else {
                            alertify
                                .alert("La fecha " + elemento + " no contempla un registro de firma", function () { });
                                $('#textoentrada').val('');
                            $('#textosalida').val('');
                            respuesta="";
                                
                        }
                    }
                }
            };
            xhr.send(datos);
        }
        function subirCalendario(e) {
            e.preventDefault()
            var fecha = document.getElementById("textodeshabilitado").value;
            var entrada = document.getElementById("textoentrada").value;
            var salida = document.getElementById("textosalida").value;
            var datos2 = new FormData();
            datos2.append('date', fecha);
            datos2.append('entrada', entrada);
            datos2.append('salida', salida);
            datos2.append('action', "subirCalendario");
            // crear el llamado a ajax
            var xhr2 = new XMLHttpRequest();
            if (entrada != "" || salida != "") {
                // abrir la conexión.
                xhr2.open('POST', 'inc/modelos/admin.php', true);
                // retorno de datos
                xhr2.onreadystatechange = function (aEvt) {
                    if (xhr2.readyState == 4) {
                        if (xhr2.status == 200) {
                            var respuesta2 = JSON.parse(xhr2.responseText);
                            if (respuesta2.respuesta === 'insertado') {
                                alertify
                                    .alert("Insertado con exito", function () {
                                        respuesta2="";
                                    });
                            }
                            if (respuesta2.respuesta === 'actualizado') {
                                alertify
                                    .alert("Actualizado con exito", function () {
                                        respuesta2="";
                                    });
                            }
                        }
                    }
                };
            }
            xhr2.send(datos2);
        }
    }
});
