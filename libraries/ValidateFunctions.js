/**
 * Created by emsaban on 8/08/14.
 */
function AbrePagOlvidaPin(paramForma) {
    ValidateIdStudent(paramForma);
    altura = screen.height - 70;
    ancho = screen.width - 340;
    propiedades = "top=20,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=no";
    propiedades = propiedades + ",height=" + altura;
    propiedades = propiedades + ",width=" + ancho;
    VentanaEnviaPin = document.open("./datosusuario/enviapin.php?txtUser=" + paramForma.txtUser.value + "&txtGrupo=" + paramForma.txtGrupo.value, "EnviaPin", propiedades);
}

function AbrePagObtenerPin(paramForma) {
    ValidateIdStudent(paramForma);
    altura = screen.height - 70;
    ancho = screen.width - 340;
    propiedades = "top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=no";
    propiedades = propiedades + ",height=" + altura;
    propiedades = propiedades + ",width=" + ancho;
    VentanaGeneraPin = document.open("./datosusuario/generapin.php?txtUser=" + paramForma.txtUser.value + "&txtGrupo=" + paramForma.txtGrupo.value, "GeneraPin", propiedades);
}

function AbrePagObtenerPinVac(paramForma) {
    altura = screen.height - 70;
    ancho = screen.width - 340;
    propiedades = "top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=no";
    propiedades = propiedades + ",height=" + altura;
    propiedades = propiedades + ",width=" + ancho;
    VentanaGeneraPinVac = document.open("generapin_vac.php?txtUser=" + paramForma.txtUser3.value + "&txtGrupo=" + paramForma.txtGrupo3.value, "GeneraPin", propiedades);
}

// funcion para habilitar el select de carreras
function verCarreras(form) {
    form.id_career_log.disabled = true;
    if (valideIdUser(form))
        if (validatePassLength(form)) {
            form.btnAcces.disabled = false;
        }
    if (form.id_group_log.value == 0) {
        form.btnAcces.disabled = true;
    }
    else if (form.id_group_log.value == 3) {
        form.id_career_log.disabled = false;
        form.id_career_log.focus();
    }
}
// fin de la funcion verCarreras

// funcion para deshabilitar el grupo cuando cambio en usuario y clave
function validateChange(form) {
    form.id_group_log.value = 0;
    form.btnAcces.disabled = true;
    form.id_career_log.value = 0;
    form.id_career_log.disabled = true;
}

/***************************************************************
 Esta función elimina todos los espacios en blanco de izquierda
 y derecha del campo txtUser del formulario.
 ***************************************************************/
function depura(form) {
    strCarne = new String();
    strCarne = form.txtUser.value;
    j = 0;
    for (i = 0; strCarne.charCodeAt(i) == 32; i++) j++;
    strCarne = strCarne.substr(j, strCarne.length);
    j = strCarne.length;
    for (i = strCarne.length; strCarne.charCodeAt(i - 1) == 32; i--) j--;
    strCarne = strCarne.substr(0, j);
    form.txtUser.value = strCarne;
}

/***************************************************************
 Esta función deshabilita el botón submit btnLogin del formulario
 y luego verifica el tipo de datos del carnet y del pin, y si son
 correctos, habilita el botón, de lo contrario, este queda
 deshabilitado.  Debe invocarse al abandonar el campo txtUser o
 el campo txtPass del formulario para verificar que los datos
 insertados son correctos
 ***************************************************************/
function validateInputs(form) {
    form.btnAcces.disabled = true;
    if (valideIdUser(form))
        if (validatePassLength(form)) {
            form.id_group_log.focus();
        }
}

/**************************************************************
 Esta función verifica el tipo de datos del campo txtUser que
 corresponde al carnet.  Primero llama a depura para eliminar
 espacios en blanco del mismo, luego verifica que sea solamente
 de contenido numérico y si es así autocompleta el campo con
 ceros o con uno nueve dependiendo del caso.  Retorna True si
 todos los datos son numéricos y tiene longitud mayor a 0.
 **************************************************************/
function valideIdUser(form) {
    var intNumeric = 0;
    strCarne = new String;
    strYear = new String;
    dateHoy = new Date;

    form.id_user_log.value = (form.id_user_log.value).trim();

    strCarne = form.id_user_log.value;

    if ((strCarne.length < 1) || (strCarne.length > 9)) return false;
    for (i = 0; i < strCarne.length; i++)
        if ((strCarne.charCodeAt(i) > 57) || (strCarne.charCodeAt(i) < 48))   return false;
    return true;
}

/*******************************************************************
 Esta función verifica el campo txtPass del formulario.  si el campo
 tiene longitudo de cuatro retorna true, de lo contrario retorna
 false.
 *******************************************************************/
function validatePassLength(form) {
    strPwd = new String;
    strPwd = form.id_password_log.value;
    if ((strPwd.length < 4) || (strPwd.length > 16)) {
        return false;
    }
    else {
        return true;
    }
}

// funcion para completar el carne o el registro de personal correctamente
function ValidateIdStudent(form) {
    if (valideIdUser(form))
        if (form.id_group_log.value == 3) {  // si el grupo es estudiantes
            strCarne = new String;
            strCarne = form.id_user_log.value;
            strYear = new String;
            var intNumeric = 0;
            switch (strCarne.length) {
                // esto es necesario si son carnes con inicio de 98 y 99
                case 7:
                    intNumeric = strCarne;
                    if (intNumeric > 9799999) {
                        strCarne = "19" + strCarne;
                        form.id_user_log.value = strCarne;
                    }
                    else {
                        strCarne = "00" + strCarne;
                        form.id_user_log.value = strCarne;
                    }
                    break;
                // esto es necesario si son carnes con todo el anio completo pero menores a 1998
                case 9:
                    strYear = strCarne.charAt(0) + strCarne.charAt(1) + strCarne.charAt(2) + strCarne.charAt(3);
                    if (strYear < 1998) {
                        form.id_user_log.value = "00" + strCarne.substr(2, strCarne.length - 2);
                    }
                    break;
                //esto es necesario para autocompletar para carnes de longitud distinta
                default:
                    for (; (strCarne.length < 9);) {
                        strCarne = "0" + strCarne;
                    }
                    form.id_user_log.value = strCarne;
            }
        }
        else {
            strRegPer = new String;
            strRegPer = form.id_user_log.value;
            //esto es necesario para autocompletar porque son registros de personal
            for (; (strRegPer.length < 9);) {
                strRegPer = "0" + strRegPer;
            }
            form.id_user_log.value = strRegPer;
        }
} // end de la function Completar