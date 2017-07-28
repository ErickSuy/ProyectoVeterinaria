/**
 * Created by EdwinMac-donall on 27/08/14.
 */

/////  Código para mostrar post-it con problemas de asignación //////////

//ns4 = document.layers
//ie4 = document.all
//nn6 = document.getElementById && !document.all

function hideObject(num) {
    str = "obser" + num;
    document.getElementById(str).style.visibility = "hidden";
}

// Show/Hide functions for pointer objects

function showObject(num) {
    str = "observacion" + num;
    document.getElementById(str).style.visibility = "visible";
}

////// Código para manejo de cursos /////////

var totalRegistros = 0;
var totalCursos = 0;
var infoPensum = null;


function dataCurso(cod, nom, sec, obs, ind, cre, ret, req) {
    this.curso = cod;
    this.nombre = nom;
    this.seccion = sec;
    this.cursoError = obs;
    this.indice = ind;
    this.creditos = cre;
    this.retra = ret;
    this.prerrequisito = req;
}

function valSelect() {
    var selval1 = document.form1.cursos;
    var selval2 = document.form1.secciones;
    if ((selval1.options[selval1.selectedIndex].value != 0) && (selval2.options[selval2.selectedIndex].value != 0)) {
        var val1 = selval1.value;
        var val2 = selval2.value;
        //agregar curso seleccionado a la lista visual
        num = addEvent(infoPensum[val1][9],infoPensum[val1][8], infoPensum[val1][7], infoPensum[val1][0], infoPensum[val1][1], val2, '', infoPensum[val1][4], infoPensum[val1][3], '', '', infoPensum[val1][6], 0);
        /**
         * Modificado 14-04-2012
         * Se agrega el parametro '0', para diferenciar la llamada a la funcion de cuando se viene del paso 1 de proceso de asignacion, que cuando
         * se esta en el paso 2, agregando nuevos cursos al listado de cursos a asignarse
         */
        enviarInfojs(1, 0, num, infoPensum[val1][7], infoPensum[val1][0], infoPensum[val1][1], val2, '', infoPensum[val1][4], infoPensum[val1][3], 0,infoPensum[val1][5], infoPensum[val1][6], 0, 0,infoPensum[val1][8],infoPensum[val1][9]);
    } else {
        alert5("Indispensable", "Debe seleccionar curso y sección");
    }
}

function addEvent(labSec, lab, index, cod, nom, sec, obs, ind, cre, ret, val, req, marca, marcaAsig) {
    if (cod != "") {
        var numi = document.getElementById('theValue');
        var num = (document.getElementById("theValue").value - 1) + 2;
        numi.value = num;

        divCurso = "<div style='height:inherit;width: 41px;' id='divCurso" + num + "'>" + cod + "<input name='curso" + num + "' id='curso" + num + "' type='hidden' value='" + cod + "'></div>";
        divNombreCurso = "<div style='height:inherit;width: 611px;' id='divNombreCurso" + num + "'>" + nom + "<input name='nombre" + num + "' id='nombre" + num + "' type='hidden'  value='" + nom + "'></div>";
        divGrupo = "<div style='height:inherit;width: 58px;' id='divGrupo" + num + "'>" + sec + "<input name='grupo" + num + "' type='hidden' value='" + sec + "'></div>";

        divGrupoLab = '';
        selectOpcion = '';
        //alert1('titulo','SEC: ' + sec + ' :: LSEC: ' + labSec);
        if (lab == 1 && sec == '-') { // Curso en seccion unica y lab se parte
            selectGrupoLab = "";
            switch (labSec) {
                case 'A':
                    selectOpcion = "<input id=\"grupolab" + num + "\" class=\"easyui-combobox\" data-options=\"valueField: 'id',textField: 'text',data: [{text: 'A',id: 'A','selected':true},{text: 'B',id: 'B'}]\" />" + "<script>$('#grupolab" + num + "').combobox({required:true,width:83,panelHeight:'auto',editable: false});</script>";
                    break;
                case 'B':
                    selectOpcion = "<input id=\"grupolab" + num + "\" class=\"easyui-combobox\" data-options=\"valueField: 'id',textField: 'text',data: [{text: 'A',id: 'A'},{text: 'B',id: 'B','selected':true}]\" />" + "<script>$('#grupolab" + num + "').combobox({required:true,width:83,panelHeight:'auto',editable: false});$('#grupolab" + num + "').combobox('setValue', 'B');</script>";
                    break;
                default :
                    selectOpcion = "<input id=\"grupolab" + num + "\" class=\"easyui-combobox\" data-options=\"valueField: 'id',textField: 'text',data: [{text: 'A',id: 'A'},{text: 'B',id: 'B'}]\" />" + "<script>$('#grupolab" + num + "').combobox({required:true,width:83,panelHeight:'auto',editable: false});</script>";
            }
        }else if(lab == 1 && sec == '_') { // Curso y lab no tiene una seccion definida
            selectOpcion = sec + "<input name='grupolab" + num + "' type='hidden' value='" + sec + "' id='grupolab" + num + "'>";
        } else if(lab == 0 && sec == '-') { // Curso no tiene lab
            selectOpcion = sec + "<input name='grupolab" + num + "' type='hidden' value='" + sec + "' id='grupolab" + num + "'>";
        } else if(lab == 1 && sec != '-') { // Curso y lab se parten en la misma seccion
            selectOpcion = sec + "<input name='grupolab" + num + "' type='hidden' value='" + sec + "' id='grupolab" + num + "'>";
        } else { // Curso sin labarotorio
            selectOpcion = '-';
        }
        divGrupoLab = "<div style='height:inherit;width: 83px;' id='divGrupoLab" + num + "'>" + selectOpcion + "</div>";

        // El div que se muestra al colorse sobre el icono de observaciones del curso
        //divObservaciones = "<div id='observacion" + num + "' style='position:absolute;z-index:1;visibility:hidden;' class='observation'><table cellpadding=0 width='100%' cellspacing=0><tr><th><table width='98%' border='0' cellpadding='0' cellspacing='0'><tr><td align='left' valign='middle' width='10%'><img src='../../resources/images/alert.png' width='36' height='36' border='0'></td><td align='left' width='90%'><div>OBSERVACIONES DEL CURSO</div></td></tr></table></th></tr><tr><td><hr/></td></tr><tr><td>" + obs + "</td></tr></table></div>";
        divObservaciones = "<div id='observacion" + num + "' class=\"easyui-dialog\" title=\"" + cod + " :: " + nom + "\" style=\"width:80%;height:auto !important;max-width:800px;padding:10px\" data-options=\" iconCls:'fa fa-info-circle', onResize:function(){$(this).dialog('center');}\"><table cellpadding=0 width='100%' cellspacing=0><tr><th><table width='98%' border='0' cellpadding='0' cellspacing='0'><tr><td align='left' valign='middle' width='10%'><img src='../../resources/images/alert.png' width='36' height='36' border='0'></td><td align='left' width='90%'><div>OBSERVACIONES DEL CURSO</div></td></tr></table></th></tr><tr><td><hr/></td></tr><tr><td><div style=\"background:#cfcfcf;border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;padding:10px;\">" + obs + "</div></td></tr></table></div><script>$('#observacion" + num + "').dialog({modal: true,closed:true});</script>";

        if (obs != "") {
            //strimg = "quest.gif";
            strdo = "<a href='javascript:void(0);' title='Clic para ver los problemas de asignación del curso'><i class='fa fa-info-circle fa-lg' onclick=\"$('#observacion" + num + "').dialog('open')\"></i></a>";
            //strdo = "alt='El curso tiene problemas para asignarse' onmouseover='javascript:showObject(" + num + ");' onmouseout='javascript:hideObject(" + num + ");'";
        }
        else {
            //strimg = "ok.gif";
            strdo = "";
        }

        divIconoObservacion = "<div style='height:inherit;width: auto;' id='divObservacionIcono" + num + "'>" + strdo + "</div>" + divObservaciones ;

        divIconoEliminar = '';
        if (marca != 1) {
            divIconoEliminar = "<div style='height:inherit;width: auto;' id='divIconoEliminar" + num + "'><a class='btnQuitarCurso' href='javascript:void(0);' title='Clic para eliminar de la lista'><i class='fa fa-times-circle fa-lg'></i></a></div>";
        }
        else {
            divIconoEliminar = "<div style='height:inherit;width: 18px;' id='divIconoEliminar" + num + "'><input type='image' name='eliminar' src='../../resources/images/punto1.gif' alt='Asignado en Control Academico' disabled='disabled' align='top' width='18' height='18' vspace='3'></div>";
        }

        divIndice = "<div id='divIndice" + num + "'><input type='hidden' name='indice" + num + "' value='" + ind + "' id='indice" + num + "'></div>";
        divCreditos = "<div id='divCreditos" + num + "'><input type='hidden' name='creditos" + num + "' value='" + cre + "' id='indice" + num + "'></div>";
        divPrerrequisitos = "<div id='divPrerrequisitos" + num + "'><input type='hidden' name='prerrequisito" + num + "' value='" + req + "' id='indice" + num + "'></div>";
        divMarca = "<div id='divMarca" + num + "'><input type='hidden' name='marca" + num + "' value='" + marca + "' id='indice" + num + "'</div>";
        divMarcaAsig = "<div id='divMarcaAsig" + num + "'><input type='hidden' name='marcaAsig" + num + "' value='" + marcaAsig + "' id='indice" + num + "'></div>";
        divIndex = "<div id='divIndex" + num + "'><input type='hidden' name='index" + num + "' value='" + index + "' id='indice" + num + "'></div>";
        divNumero = "<div id='divIndex" + num + "'><input type='hidden' name='numero" + num + "' value='" + num + "' id='numero" + num + "'></div>";

        $("#tblListaCursos tbody").append(
            "<tr class='datagrid-row'>" +
            "<td>" + divCurso + "</td>" +
            "<td>" + divNombreCurso + "</td>" +
            "<td>" + divGrupo + "</td>" +
            "<td>" + divGrupoLab + "</td>" +
            "<td align='center'>" + divIconoObservacion + "</td>" +
            "<td align='center'>" + divIconoEliminar + "</td>" +
            "<td hidden='true'>" + divIndice + "</td>" +
            "<td hidden='true'>" + divCreditos + "</td>" +
            "<td hidden='true'>" + divPrerrequisitos + "</td>" +
            "<td hidden='true'>" + divMarca + "</td>" +
            "<td hidden='true'>" + divMarcaAsig + "</td>" +
            "<td hidden='true'>" + divIndex + "</td>" +
            "<td hidden='true'>" + divNumero + "</td>" +
            "</tr>");

        $(".btnQuitarCurso").bind("click", quitarCursoDeListado);

        //eliminar curso seleccionado
        delOptions(ind, cod, nom);
    }
    return num;
}

function quitarCursoDeListado() {
    var j;
    var numi = document.getElementById('theValue'); // LLeva el contador de cursos en el listado de cursos a asignarse
    //El nivel de parents depende del contenido de la columna
    var parenT = $(this).parent().parent().parent(); // DIV >> TD >> TR
    var tdNumero = parenT.children("td:nth-child(13)"); // Columna NUMERO

    // Se quitan todas las filas de la lista de cursos asignarse, para poder renombrar los id's
    $("#tblListaCursos tbody tr").each(function (index) {
        var indice;
        $(this).children("td").each(function (index2) {
            switch (index2) {
                case 6:
                    console.log('BORAR FILA');
                    indice = $(this).children().children("input[type=hidden]").val();
                    infoPensum[indice][5] = 1;
                    borrarSelectCursos(document.form1.cursos);
                    //reestablecer curso eliminado
                    addOptionCursos();
                    break;
            }
        })
        $(this).remove(); // Se elimina la fila
    });

    // Se quita el registro del curso eliminado del array que maneja los cursos del listado de cursos a asignarse
    console.log('La fila que se elimina es: ' + tdNumero.children().children("input[type=hidden]").val());
    quitarInfojs(tdNumero.children().children("input[type=hidden]").val());
    // Se reinicia el contador de cursos a asignarse
    numi.value = 0;

    var p = infojsCurso.length;
    var matrizcur = new Array();
    matrizcur = darInfojs();

    // Se vuelve a generar la tabla de cursos a asignarse con los nuevos id's
    for (j = 0; j < p; j++) {
        if (matrizcur[j] != null) {
            console.log('Total: ' + p + ' Indice: ' + j);
            addEvent(matrizcur[j][13],matrizcur[j][12], matrizcur[j][11], matrizcur[j][0], matrizcur[j][1], matrizcur[j][2], matrizcur[j][3], matrizcur[j][4], matrizcur[j][5], matrizcur[j][6], matrizcur[j][7], matrizcur[j][8], matrizcur[j][9], matrizcur[j][10]);
        }
    }
}

function removeEvent(divNum, num) {
    var d = document.getElementById('myDiv');
    var olddiv = document.getElementById(divNum);
    var val = document.getElementById("indice" + num).value;

    infoPensum[val][5] = 1;
    //borrar combo de los cursos
    borrarSelectCursos(document.form1.cursos);
    //reestablecer curso eliminado
    addOptionCursos();

    d.removeChild(olddiv);
}

function quitarCurso(num) {
    var i, j, k;
    var numi = document.getElementById('theValue'); // LLeva el contador de cursos en el listado de cursos a asignarse
    var totalCursos = document.getElementById("theValue").value;
    for (i = 0; i < totalCursos; i++) {
        k = i + 1;
        nombreIdDiv = "my" + k + "Div";
        removeEvent(nombreIdDiv, k);

    }
    quitarInfojs(num);
    numi.value = 0;

    var p = infojsCurso.length;
    var matrizcur = new Array();
    matrizcur = darInfojs();
    for (j = 0; j < p; j++) {
        if (matrizcur[j] != null) {
            addEvent(matrizcur[j][13],matrizcur[j][12], matrizcur[j][11], matrizcur[j][0], matrizcur[j][1], matrizcur[j][2], matrizcur[j][3], matrizcur[j][4], matrizcur[j][5], matrizcur[j][6], matrizcur[j][7], matrizcur[j][8], matrizcur[j][9], matrizcur[j][10]);
        }
    }
}


function addOptions(chosen) {
    var indice = "indice" + chosen;
    var val = document.getElementById(indice).value;

    infoPensum[val][5] = 1;

    borrarSelectCursos(document.form1.cursos);

    addOptionCursos();

}

/**
 * Quita del Select de cursos, el que se agrega a la lista de cursos a asignar
 * @param idx
 * @param chosen
 * @param desc
 */
function delOptions(idx, chosen, desc) {
    var selbox = document.form1.cursos;
    var selsec = document.form1.secciones;

    // Marcar el curso en el vector de Javascript
    infoPensum[idx][5] = 0;

    if (selbox.options[0].value != " ") {
        nomatch = new Array();
        for (n = 0; n < selbox.length; n++) {
            if (selbox.options[n].value != idx) {
                nomatch[nomatch.length] = new Array(selbox.options[n].value, selbox.options[n].text);
            }
        }
        selbox.options.length = 0;
        selsec.options.length = 0;
        if (nomatch.length == 0) {
            selsec.options[0] = new Option("Seleccione la sección", "0", "defauldSelected");
            selbox.options[0] = new Option("Seleccione el curso a asignar", "0", "defauldSelected");
        } else {
            for (n = 0; n < nomatch.length; n++) {
                selbox.options[n] = new Option(nomatch[n][1], nomatch[n][0]);
            }
        }
    }
}

/**
 * Llena el Select de cursos
 */
function addOptionCursos() {
    var selbox = document.form1.cursos;
    var laOpcion = new Option('Seleccione el curso a asignar', 0, true);
    selbox.options[0] = laOpcion;

    nomatch = new Array();
    for (v = 1; v <= totalCursos; v++) {
        if (infoPensum[v][5] == 1) {
            nomatch[nomatch.length] = new Array(infoPensum[v][0] + " - " + infoPensum[v][1] + " " + infoPensum[v][2], infoPensum[v][4]);
        }
    }

    for (n = 1; n <= nomatch.length; n++) {
        selbox.options[n] = new Option(nomatch[n - 1][0], nomatch[n - 1][1]);
    }

    selbox.options[0].selected = true;
}

function delRadioChecked() {
    var frm = document.forms[2];
    for (i = 0; i < frm.length; i++) {
        // buscar elementos tipo "RadioButtons"
        if (frm.elements[i].type == "radio") {
            // quita el cheque si el control es RadioButton
            frm.elements[i].checked = false;
        }
    }
}

function enviarFormulario() {
    document.forma1.submit();
}

function borrarSelectSecciones(forma) {
    while (forma.secciones.length) {
        for (i = 0; i < forma.secciones.length; i++) {
            forma.secciones.options[i] = null;
        }
    }
}

function borrarSelectCursos(selcur) {
    while (selcur.length) {
        for (i = 0; i < selcur.length; i++) {
            selcur.options[i] = null;
        }
    }
}

function buscarSecciones(forma) {
    if (infoHorario) {
        borrarSelectSecciones(forma);
        elCurso = forma.cursos[forma.cursos.selectedIndex].value;

        var posicion = 0;
        var contador = 0;
        for (i = 0; i < totalRegistros; i++) {
            if (elCurso == infoHorario[i][0]) {
                if (posicion == 0) posicion = i;
                var laOpcion = new Option(infoHorario[i][1], infoHorario[i][1]);
                forma.secciones.options[contador] = laOpcion;
                contador++;
            }
            else {
                if (posicion) break;
            }
        }
        forma.secciones.options[0].selected = true;
    }
}

function VerSecciones(forma) {
    if (infoHorario) {
        borrarSelectSecciones(forma);
        indice = forma.cursos[forma.cursos.selectedIndex].value;
        elCurso = infoPensum[indice][0];
        elIndex = infoPensum[indice][7];
        var laOpcion = new Option('Seleccione la sección', 0, true);
        forma.secciones.options[0] = laOpcion;
        var posicion = 0;
        var contador = 1;
        for (i = 1; i <= totalRegistros; i++) {
            if (elCurso == infoHorario[i][0] && elIndex == infoHorario[i][3]) {
                if (posicion == 0) posicion = i;
                var laOpcion = new Option(infoHorario[i][1], infoHorario[i][1]);
                forma.secciones.options[contador] = laOpcion;
                contador++;
            }
            else {
                if (posicion) break;
            }
        }
        forma.secciones.options[0].selected = true;
    }
}

function ocultarBarraProceso(forma) {
    forma.espera.heigth = 0;
    forma.espera.width = 0;
}

function verificaCodigoCurso(forma) {
    var faltante = 4 - forma.codigocurso.value.length;
    for (i = 0; i < faltante; i++)
        forma.codigocurso.value = "0" + forma.codigocurso.value;
}

function alert1($title, $msg) {
    $.messager.alert($title, $msg);
}
function alert2($title, $msg) {
    $.messager.alert($title, $msg, 'error');
}
function alert3($title, $msg) {
    $.messager.alert($title, $msg, 'info');
}
function alert4($title, $msg) {
    $.messager.alert($title, $msg, 'question');
}
function alert5($title, $msg) {
    $.messager.alert($title, $msg, 'warning');
}

function asignarCursos() {
    var numCursos = document.getElementById("theValue").value;
    if (numCursos > 0) {
        var i = 1;
        var submit = true;

        // Validación para los combos de los grupos de los laboratorios
        for(i=1;i<=numCursos;i++) {
            if(document.getElementById('grupolab' + i) != null && document.getElementById('grupolab' + i).type != 'hidden') {
                if($("#grupolab" + i).combobox('getValue')=='' || $("#grupolab" + i).combobox('getValue')==null) {
                    submit = false;
                    break;
                }
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'grupolab' + i;
                input.value = $("#grupolab" + i).combobox('getValue');

                var divLabGroup = document.getElementById('divGrupoLab' + i);
                divLabGroup.appendChild(input);
            }
        }

        if(submit) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'procesar_asignacion';
            input.value = '';

            form2.appendChild(input);
            document.form2.submit();
        }
    } else {
        alert2("Error", "Debe agregar cursos a la asignación");
    }
}

