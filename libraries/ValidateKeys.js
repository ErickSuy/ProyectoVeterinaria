/**
 * Created by emsaban on 8/08/14.
 */

function funTeclaMatch(boolTeclaAlt, boolTeclaControl, numTeclaPulsada) {
    boolEstado = true;
    if (boolTeclaAlt && !event.altKey) boolEstado = false;
    else if (boolTeclaControl && !event.ctrlKey) boolEstado = false;
    else if (event.keyCode != numTeclaPulsada) boolEstado = false;
    return boolEstado;
};

function funDesactivar(boolTeclaAlt, boolTeclaControl, numTeclaPulsada) {
    if (funTeclaMatch(boolTeclaAlt, boolTeclaControl, numTeclaPulsada)) {
        event.returnValue = false;
        return true;
    }
    return false;
}

function funConfirmar(boolTeclaAlt, boolTeclaControl, numTeclaPulsada, strPregunta) {
    boolEstado = funTeclaMatch(boolTeclaAlt, boolTeclaControl, numTeclaPulsada);
    if (boolEstado)
        if (!confirm(strPregunta)) {
            event.returnValue = false;
            boolEstado = false;
        }
    return boolEstado;
}

function funDoFocus(boolTeclaAlt, boolTeclaControl, numTeclaPulsada, control, boolAutorizacion) {
    boolEstado = false;
    if (boolAutorizacion) {
        boolEstado = funTeclaMatch(boolTeclaAlt, boolTeclaControl, numTeclaPulsada);
        if (boolEstado) {
            control.focus();
            event.returnValue = false;
        }
    }
    return !boolEstado;
};

function funDoSubmit(boolTeclaAlt, boolTeclaControl, numTeclaPulsada, formulario, boolAutorizacion) {
    boolEstado = false;
    if (boolAutorizacion) {
        boolEstado = funTeclaMatch(boolTeclaAlt, boolTeclaControl, numTeclaPulsada);
        if (boolEstado) {
            formulario.submit();
            event.returnValue = false;
        }
    }
    return !boolEstado;
};

function funDoLink(boolTeclaAlt, boolTeclaControl, numTeclaPulsada, strDireccion, numMarco, boolAutorizacion) {
    boolEstado = funTeclaMatch(boolTeclaAlt, boolTeclaControl, numTeclaPulsada);
    if (boolEstado) {
        if (boolAutorizacion) {
            if (numMarco < 0) parent.location = strDireccion;
            else parent.frames[numMarco].location = strDireccion;
            event.returnValue = false;
        }
    }
    return !boolEstado;
};