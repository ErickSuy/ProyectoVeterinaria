/**
 * Created by emsaban on 8/08/14.
 */

var message = "Esta operación no esta disponible.";

///////////////////////////////////
function clickIE4() {
    if (event.button == 2) {
        $.messager.alert("","Función no disponible",'info');
        return false;
    }
}

function clickNS4(e) {
    if (document.layers || document.getElementById && !document.all) {
        if (e.which == 2 || e.which == 3) {
            $.messager.alert("","Función no disponible",'info');
            return false;
        }
    }
}

if (document.layers) {
    document.captureEvents(Event.MOUSEDOWN);
    document.onmousedown = clickNS4;
}
else if (document.all && !document.getElementById) {
    document.onmousedown = clickIE4;
}

document.oncontextmenu = new Function("alert(message);return false")

function BloquearBoton(boton, imagen) {
    if (boton.disabled != true) {
        boton.disabled = true;
    }
    VerImagen(imagen);
}

var browser = '';

if (browser == '') {
    if (navigator.appName.indexOf('Microsoft') != -1)
        browser = 'IE';
    else if (navigator.appName.indexOf('Netscape') != -1)
        browser = 'Netscape';
    else browser = 'IE';
}