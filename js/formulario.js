eventListeners();
var clickado = false;
var todoAcabado = false;


function rand() {
    return Math.random().toString(36).substr(2); // remove `0.`
}

function token() {
    return rand() + rand(); // hace el random mas largo
}
/**
Evento que se ejecuta nada mas comenzar y que mantiene el login abierto si la sesión del usuario no ha caducado
*/
function eventListeners() {
    $(document).ready(function () {
        if (document.title !== "WorkControl-'index'") {
            document.addEventListener('change', validarRegistro);
            document.addEventListener('submit', validarRegistro);
            document.querySelector("#sub").addEventListener('click', comprobarClick);
        }
    });
}

function comprobarClick() {
    if (todoAcabado) {
        clickado = true;
    }
}
function Ajax(accion, array) {
    $(document).ready(function () {
        /** 
        No implementado por fallos.
        Firebase
                var actionCodeSettings = {
                    // URL you want to redirect back to. The domain (www.example.com) for this
                    // URL must be whitelisted in the Firebase Console.
                    url: 'login.php',
                    // This must be true.
                    handleCodeInApp: true,
                    iOS: {
                        bundleId: 'com.example.ios'
                    },
                    android: {
                        packageName: 'com.example.android',
                        installApp: true,
                        minimumVersion: '12'
                    },
                    dynamicLinkDomain: ''
                };
        */
        // datos que se envian al servidor
        var datos = new FormData();
        if (accion === 'Login') {
            datos.append('correo', array[1]);
            datos.append('contrasena', array[0]);
            datos.append('action', accion);
        }
        if (accion === 'registrarE') {

            datos.append('correo', array[5]);
            datos.append('contrasena', array[0]);
            datos.append('nombre', array[7]);
            datos.append('apellido1', array[2]);
            datos.append('apellido2', array[3]);
            datos.append('telefono', array[4]);
            datos.append('dni', array[1]);
            datos.append('idempresa', array[8]);
            datos.append('nombreempresa', array[6]);
            datos.append('action', accion);
            datos.append('token', token());


        }
        if (accion === 'registrarT') {


            datos.append('correo', array[5]);
            datos.append('contrasena', array[0]);
            datos.append('nombre', array[7]);
            datos.append('apellido1', array[2]);
            datos.append('apellido2', array[3]);
            datos.append('telefono', array[4]);
            datos.append('dni', array[1]);
            datos.append('empresa', array[6]);
            datos.append('action', accion);
            datos.append('token', token());


        }
        // crear el llamado a ajax
        var xhttp = new XMLHttpRequest();

        // abrir la conexión.
        xhttp.open('POST', '../inc/modelos/admin.php', true);

        // retorno de datos
        xhttp.onload = function () {
            if (this.status === 200) {

                var respuesta = xhttp.responseText;
                // Si la respuesta es correcta
                if (respuesta.respuesta === 'login') {
                    alertify
                        .alert("El usuario inicio sesion correctamente.", function () {
                            window.open("../index.php", "_self");


                        });
                }
                if (respuesta.respuesta === 'correcto') {
                    // si es un nuevo usuario 
                    if (respuesta.tipo === 'usuarioe' || respuesta.tipo === 'usuariot') {
                        alertify
                            .alert("El usuario se creó correctamente." +
                                "Volviendo al login", function () {
                                    window.open("../login.php", "_self");
                                });

                        /**
    
                            firebase.auth().createUserWithEmailAndPassword(respuesta.correo, respuesta.contrasena).catch(function (error) {
                                // Handle Errors here.
                                var errorCode = error.code;
                                var errorMessage = error.message;
                                // ...
                            });
                            
    
                             Firebase
                            firebase.auth().sendSignInLinkToEmail(respuesta.correo, actionCodeSettings)
                                .then(function () {
                                    // The link was successfully sent. Inform the user.
                                    // Save the email locally so you don't need to ask the user for it again
                                    // if they open the link on the same device.
                                    window.localStorage.setItem('emailForSignIn', email);
                                    alertify
                                        .alert("El usuario se creó correctamente.Verifíque su correo para continuar." +
                                            "Volviendo al login", function () {
                                                window.location.href = "login.php";
                                            });
                                })
                                .catch(function (error) {
                                    alertify
                                        .alert("No se ha podido enviar una autentificación al correo " + correo +
                                            "Volviendo al login", function () {
                                                window.location.href = "login.php";
                                            });
                                });
    
                            */

                    }

                }
                if (respuesta.respuesta === 'nouser') {
                    alertify
                        .alert("No existe el usuario", function () {

                        });
                }
                if (respuesta.respuesta === 'contrasenaerror') {
                    alertify
                        .alert("Contraseña incorrecta", function () {

                        });
                }


                if (respuesta.respuesta === 'registrado') {
                    alertify
                        .alert("El usuario ya esta registrado", function () {

                        });
                }
                if (respuesta.respuesta === 'error') {
                    alertify
                        .alert("Algo a salido mal", function () {

                        });
                }


                /** 
                var firebaseConfig = {
                    apiKey: "api-key",
                    authDomain: "project-id.firebaseapp.com",
                    databaseURL: "https://project-id.firebaseio.com",
                    projectId: "project-id",
                    storageBucket: "project-id.appspot.com",
                    messagingSenderId: "sender-id",
                    appID: "app-id",

                };
                firebase.initializeApp(firebaseConfig);
                */
            }
        }
        // Enviar la petición
        xhttp.send(datos);

    });
}
function validarRegistro(e) {
    try {
        e.preventDefault();
        // crear el llamado a ajax
        var xhr = new XMLHttpRequest();

        // abrir la conexión.
        xhr.open('GET', '../pagina.txt', true);

        xhr.onload = function () {
            if (this.status === 200) {
                let responseObj = xhr.response;
                // Si la respuesta no es el login
                if (responseObj === 'login') {
                    // ¡Se hace la revisión!
                    var contra = new RegExp('([a-zA-Z0-9*_-]){8,}', "");;
                    var corr = new RegExp('[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+', "");

                    let checkcontrasena = document.getElementById("contrasena");
                    let checkcorreo = document.getElementById("correo");
                    var contrasena = document.getElementById("contrasena").value;
                    var correotexto = document.getElementById("correo").value;


                    if (corr.test(correotexto)) {
                        checkcorreo.setCustomValidity("");

                    } else {
                        checkcorreo.setCustomValidity("Correo invalido")
                    }

                    if (checkcorreo.checkValidity()) {
                        todoAcabado = true;
                    }

                    if (clickado) {
                        clickado = false;
                        // Is there an email link sign-in?
                        /** 
                        if (ui.isPendingRedirect()) {
                            ui.start('#firebaseui-auth-container', uiConfig);
                            alertify
                                .alert("Verifique su correo", function () {

                                });
                        }
                        else {
                            */
                        let arraylog = [contrasena, correotexto];
                        Ajax('Login', arraylog);
                        //}
                    }




                }
                if (responseObj === 'registrartrabajador') {
                    // ¡Se hace la revisión!
                    var nombre = new RegExp('[A-Z][a-z]{2,}', "");
                    var cif = new RegExp("[a-zA-Z]{1}[0-9]{7}[a-zA-Z0-9]{1}", "");
                    var contra = new RegExp('([a-zA-Z0-9*_-]){8,}', "");
                    var dni = new RegExp("([0-9]{8})([A-HJ-NP-TV-Z])", "");
                    var telf = new RegExp("(|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}", "");
                    var apell = new RegExp('[A-Z][a-z]{2,}', "");
                    var corr = new RegExp('[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+', "");

                    var checkempresa = document.getElementById("sempresa");
                    var textEmpresa = checkempresa.options[checkempresa.selectedIndex].innerText;
                    let checkcorreo = document.getElementById("correo");
                    let checkcontrasena = document.getElementById("contrasena");
                    let checkdni = document.getElementById("dni");
                    let checkusuario = document.getElementById("nombre");
                    //let checkfirma = document.getElementById("firma");
                    var checkapellido1 = document.getElementById("apellido1");
                    var checkapellido2 = document.getElementById("apellido2");
                    var checktelefono = document.getElementById("telefono");
                    var name = document.getElementById("nombre").value;
                    var contrasena = document.getElementById("contrasena").value;
                    var DNI = document.getElementById("dni").value;
                    //var firm = document.getElementById("firma").value;
                    var empresa = document.getElementById("sempresa").value;
                    var apellido1 = document.getElementById("apellido1").value;
                    var apellido2 = document.getElementById("apellido2").value;
                    var telefono = document.getElementById("telefono").value;
                    var correotexto = document.getElementById("correo").value;
                    var action = document.getElementById("formulario");
                    var checkbox = document.getElementById("terminos").checked;


                    if (empresa !== "") {
                        checkempresa.setCustomValidity("");
                    } else {
                        checkempresa.setCustomValidity("Actualmente no hay una empresa registrada")
                    }


                    if (corr.test(correotexto)) {
                        checkcorreo.setCustomValidity("");
                    } else {
                        checkcorreo.setCustomValidity("Correo invalido")
                    }

                    if (nombre.test(name)) {

                        checkusuario.setCustomValidity("");
                    } else {

                        checkusuario.setCustomValidity('Solo letras , 3 dígitos mínimo, primera letra mayuscula');

                    }
                    if (contra.test(contra)) {

                        checkcontrasena.setCustomValidity("");
                    } else {
                        checkcontrasena.setCustomValidity("La contraseña puede integrar mayusculas ,caracteres especiales (* _ -) y mínimo 8 dígitos");
                    }
                    if (dni.test(DNI)) {

                        checkdni.setCustomValidity("");
                    } else {
                        checkdni.setCustomValidity('Formato del NIF/DNI 00000000T');

                    }
                    /** 
                                        if (checkfirma.value == '') {
                                            checkfirma.setCustomValidity('Agrege una firma');
                                        } else {
                                            checkfirma.setCustomValidity("");
                                        }
                    */
                    if (telf.test(telefono)) {

                        checktelefono.setCustomValidity("");
                    } else {
                        checktelefono.setCustomValidity('El teléfono español 600000000 o 700000000');

                    }
                    if (apell.test(apellido1)) {

                        checkapellido1.setCustomValidity("");
                    } else {
                        checkapellido1.setCustomValidity('El apellido no puede contener menos de 3 digitos y la primera letra debe ir en mayuscula');

                    }
                    if (empresa !== "" && checkbox && checkcorreo.checkValidity() && checkusuario.checkValidity() && checkdni.checkValidity() && checkcontrasena.checkValidity() && checkapellido1.checkValidity() && checktelefono.checkValidity()) {
                        todoAcabado = true;
                    }
                    if (clickado) {
                        clickado = false;
                        let arraytrab = [contrasena, DNI, apellido1, apellido2, telefono, correotexto, textEmpresa, name];
                        Ajax('registrarT', arraytrab);
                    }




                }
                if (responseObj === 'registrarempresario') {
                    // ¡Se hace la revisión!
                    var nombre = new RegExp('[A-Z][a-z]{2,}', "");
                    var cif = new RegExp("[a-zA-Z]{1}[0-9]{7}[a-zA-Z0-9]{1}", "");
                    var contra = new RegExp('([a-zA-Z0-9*_-]){8,}', "");
                    var dni = new RegExp("([0-9]{8})([A-HJ-NP-TV-Z])", "");
                    var telf = new RegExp("(|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}", "");
                    var corr = new RegExp('[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+', "");
                    var apell = new RegExp('[A-Z][a-z]{2,}', "");

                    var nombreempresa = document.getElementById("nombreempresa").value;
                    let checkcontrasena = document.getElementById("contrasena");
                    let checkdni = document.getElementById("dni");
                    let checkcorreo = document.getElementById("correo");
                    let checknifciftext = document.getElementById("nifocif");
                    let checkusuario = document.getElementById("nombre");
                    //let checkfirma = document.getElementById("firma");
                    var checkapellido1 = document.getElementById("apellido1");
                    var checkapellido2 = document.getElementById("apellido2");
                    var checktelefono = document.getElementById("telefono");
                    var name = document.getElementById("nombre").value;
                    var contrasena = document.getElementById("contrasena").value;
                    var DNI = document.getElementById("dni").value;
                    //var firm = document.getElementById("firma").value;
                    var apellido1 = document.getElementById("apellido1").value;
                    var apellido2 = document.getElementById("apellido2").value;
                    var telefono = document.getElementById("telefono").value;
                    var nifciftext = document.getElementById("nifocif").value;
                    var correotexto = document.getElementById("correo").value;
                    var action = document.getElementById("formulario");
                    var checkbox = document.getElementById("terminos").checked;

                    if (corr.test(correotexto)) {
                        checkcorreo.setCustomValidity("");
                    } else {
                        checkcorreo.setCustomValidity("Correo invalido")
                    }

                    if (nombre.test(name)) {

                        checkusuario.setCustomValidity("");
                    } else {

                        checkusuario.setCustomValidity('Solo letras , 3 dígitos mínimo, primera letra mayuscula');

                    }
                    switch (registroempresario.cifnif.value) {
                        case 'nif':
                            if (dni.test(nifciftext)) {

                                checknifciftext.setCustomValidity("");
                            } else {
                                checknifciftext.setCustomValidity('Formato del NIF 00000000T');

                            }
                            break;

                        case 'cif':
                            if (cif.test(nifciftext)) {

                                checknifciftext.setCustomValidity("");
                            } else {
                                checknifciftext.setCustomValidity('Formato del CIF A00000000');
                            }
                            break;
                    }
                    if (contra.test(contra)) {

                        checkcontrasena.setCustomValidity("");
                    } else {
                        checkcontrasena.setCustomValidity("La contraseña puede integrar mayusculas ,caracteres especiales (* _ -) y mínimo 8 dígitos");
                    }
                    if (dni.test(DNI)) {

                        checkdni.setCustomValidity("");
                    } else {
                        checkdni.setCustomValidity('Formato del NIF/DNI 00000000T');

                    }
                    /** 
                    if (checkfirma.value == '') {
                        checkfirma.setCustomValidity('Agrege una firma');
                    } else {
                        checkfirma.setCustomValidity("");
                    }
                    */
                    if (telf.test(telefono)) {

                        checktelefono.setCustomValidity("");
                    } else {
                        checktelefono.setCustomValidity('El teléfono español 600000000 o 700000000');

                    }
                    if (apell.test(apellido1)) {

                        checkapellido1.setCustomValidity("");
                    } else {
                        checkapellido1.setCustomValidity('El apellido no puede contener menos de 3 digitos y la primera letra debe ir en mayuscula');

                    }

                    if (checkbox && checkcorreo.checkValidity() && checkusuario.checkValidity() && checkdni.checkValidity() && checknifciftext.checkValidity() && checkcontrasena.checkValidity() && checktelefono.checkValidity()) {
                        todoAcabado = true;
                    }
                    if (clickado) {
                        clickado = false;
                        let arrayemp = [contrasena, DNI, apellido1, apellido2, telefono, correotexto, nombreempresa, name, nifciftext];
                        Ajax('registrarE', arrayemp);
                    }



                }
            }
        }
        xhr.send(null);

    }
    catch (err) {
        console.log(err);
    }
}