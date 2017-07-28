<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- INCLUDESCRIPT BLOCK : ihead -->

<link href="../../libraries/js/wizard/styles/smart_wizard.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../libraries/js/wizard/jquery.smartWizard.js"></script>
<script type="text/javascript">
$(function () {
    $('#ff_fechapasaporte').datebox({
        onSelect: function(date){
            if($('#ff_pasaporte').val() == '') {
                $('#ff_fechapasaporte').datebox('setValue', '');
                $.messager.alert('Datos faltantes', 'Debe ingresar antes el número de pasaporte.', 'warning');
            }
        }
    });
});

$(document).ready(function(){
    // Smart Wizard
    $('#wizard').smartWizard({
        transitionEffect: 'slideleft',
        onLeaveStep:leaveAStepCallback,
        onFinish:onFinishCallback
    });


    function leaveAStepCallback(obj, context){
        //alert("Leaving step " + context.fromStep + " to go to step " + context.toStep);
        return validateSteps(context.fromStep);
    }

    function onFinishCallback(objs, context){
        if(validateAllSteps()){
            GuardarDatos(document.informacionestudiante);
        }
    }

    function validateSteps(stepnumber){
        var isStepValid = true;
        if(stepnumber == 1){
            if(validateStep1() == false ){
                isStepValid = false;
                $('#wizard').smartWizard('showMessage','Corrija los errores indicados antes de continuar.');
                $('#wizard').smartWizard('setError',"{stepnum:stepnumber,iserror:true}");
            }else{
                $('#wizard').smartWizard('hideMessage');
                $('#wizard').smartWizard('setError',"{stepnum:stepnumber,iserror:false}");
            }
        } else if(stepnumber == 2) {
            if(validateStep2() == false ){
                isStepValid = false;
                $('#wizard').smartWizard('showMessage','Corrija los errores indicados antes de continuar.');
                $('#wizard').smartWizard('setError',"{stepnum:stepnumber,iserror:true}");
            }else{
                $('#wizard').smartWizard('hideMessage');
                $('#wizard').smartWizard('setError',"{stepnum:stepnumber,iserror:false}");
            }
        }
        return isStepValid;
    }

    function validateStep1(){
        var isValid = true;

        if($('#genero').val() == 0) {
            isValid = false;
            $('#msg_genero').html('*Seleccione una opción.').show();
        } else {
            $('#msg_genero').html('').hide();
        }

        if($('#estadocivil').val() == 0) {
            isValid = false;
            $('#msg_estadocivil').html('*Seleccione una opción.').show();
        } else {
            $('#msg_estadocivil').html('').hide();
        }


        if($('#ff_cedula').val()!= ''&& ($('#ff_cedula').val()).length>0 && $('#extendidaendepto').val()==0) {
            isValid = false;
            $('#msg_extendidaen').html('*Ingrese departamento y municipio.').show();
        } else{
            $('#msg_extendidaen').html('').hide();
        }
        return isValid;
    }

    function validateStep2(){
        var isValid = true;

        if($('#residenciadepto').val() == 0) {
            isValid = false;
            $('#msg_residenciadepto').html('*Este dato es requerido').show();
        } else {
            if($('#ff_direccion').val() == '') {
                isValid = false;
                $('#msg_direccion').html('*Este dato es requerido').show();
            } else {
                $('#msg_direccion').html('').hide();
            }
            $('#msg_residenciadepto').html('').hide();
        }

        return isValid;
    }

    function validateStep3(){
        var isValid = true;
        var emailp = $('#ff_correop').val();

        if(emailp && emailp.length > 0){
            if(!($('#ff_correop').validatebox('isValid'))){
                isValid = false;
                $('#msg_correop').html('*Dato inválido').show();
            }else{
                $('#msg_correop').html('').hide();
            }
        }else{
            isValid = false;
            $('#msg_correop').html('*Dato obligatorio').show();
        }

        var celp = $('#ff_celp').numberbox('getValue');
        var telp = $('#ff_telp').numberbox('getValue');

        if(celp.length == 0 && telp.length == 0) {
            isValid = false;
            $('#msg_celp').html('*Dato obligatorio').show();
            $('#msg_telp').html('*Dato obligatorio').show();
        } else {
            if(celp.length != 8) {
                isValid = false;
                $('#msg_celp').html('*Dato inválido').show();
            } else {
                $('#msg_celp').html('').hide();
            }

            if(telp.length != 8) {
                isValid = false;
                $('#msg_telp').html('*Dato inválido').show();
            } else {
                $('#msg_telp').html('').hide();
            }
        }

        return isValid;
    }

    function validateAllSteps(){
        return validateStep3();
    }
});

function myformatter(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?('0'+d):d) + '/' + (m<10?('0'+m):m) + '/' + y;
}
function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('/'));
    var y = parseInt(ss[2],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[0],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}

function GuardarDatos(argsForm)
{
    // General
    var genero = $("#genero option:selected").html();
    var genero_int;

    if(genero=='MASCULINO'){
        genero_int = 1;
    } else  {
        genero_int = 2;
    }

    var fecha_nacimiento = $('#ff_fechanacimiento').datebox('getValue').split('/');
    fecha_nacimiento = fecha_nacimiento[2] + '-' + fecha_nacimiento[1] + '-'  + fecha_nacimiento[0];

    var cedula = argsForm.ff_cedula.value;

    var cedula_depto;
    var cedula_munic = argsForm.extendidaenmunic[argsForm.extendidaenmunic.selectedIndex].value;

    cedula_depto = municipios[cedula_munic][0];
    cedula_munic = municipios[cedula_munic][1];

    var dpi = argsForm.ff_dpi.value;

    // Residencia
    var direccion = argsForm.ff_direccion.value;;

    var domicilio_depto;
    var domicilio_munic = argsForm.residenciamunic[argsForm.residenciamunic.selectedIndex].value;

    domicilio_depto = municipios[domicilio_munic][0];
    domicilio_munic = municipios[domicilio_munic][1];

    var nacionalidad = argsForm.nacionalidad[argsForm.nacionalidad.selectedIndex].value;

    //Contacto
    var correo_prin = argsForm.ff_correop.value;
    var celular_prin = $('#ff_celp').numberbox('getValue');
    var telefono_prin = $('#ff_telp').numberbox('getValue');

    var cadenaparametros='genero='+genero+
            '&fecha_nacimiento='+fecha_nacimiento+
            '&cedula='+cedula+
            '&cedula_depto='+cedula_depto+
            '&cedula_munic='+cedula_munic+
            '&dpi='+dpi+
            '&direccion='+direccion+
            '&domicilio_depto='+domicilio_depto+
            '&domicilio_munic='+domicilio_munic+
            '&nacionalidad='+nacionalidad+
            '&correo_prin='+correo_prin+
            '&telefono_prin='+telefono_prin+
            '&celular_prin='+celular_prin+
            '&genero_int='+genero_int;
    console.log(cadenaparametros);
    document.location.href = "D_SaveUpdatedProfileInfo.php?"+cadenaparametros;
}
</script>

<script language="javascript">
departamentos=new Array();
municipios=new Array();

<!-- START BLOCK : VECTOR_DEPARTAMENTO -->
departamentos[{index}]=new Array();
departamentos[{index}][0] = "{cod_depto}";
departamentos[{index}][1] = "{nom_depto}";
<!-- END BLOCK : VECTOR_DEPARTAMENTO -->

municipios[0]=new Array();
municipios[0][0] = "0";
municipios[0][1] = "0";
municipios[0][2] = "0";
<!-- START BLOCK : VECTOR_MUNICIPIO -->
municipios[{indice}]=new Array();
municipios[{indice}][0] = "{depto}";
municipios[{indice}][1] = "{munic}";
municipios[{indice}][2] = "{nom_munic}";
<!-- END BLOCK : VECTOR_MUNICIPIO -->

function cedulaCorrecta(forma){
    var cedula = forma.ff_cedula.value;
    if (!cedula.match("[A-Za-z]-[0-9][0-9] [0-9]*")){
        return 0;
    }
    cedula = cedula.substring(0,4);
    cedula = cedula.toUpperCase();
    var indice_seleccionado = forma.extendidaendepto.selectedIndex;

    switch(indice_seleccionado){
        case 1:
            if(cedula=="A-01"){return 1;}
            else{return 0;}
            break;
        case 2:
            if(cedula=="D-04"){ return 1;}
            else{return 0;}
                break;
        case 3:
            if(cedula=="B-02"){ return 1;}
            else{return 0;}
                break;
        case 4:
            if(cedula=="C-03"){ return 1;}
            else{return 0;}
                break;
        case 5:
            if(cedula=="E-05"){ return 1;}
            else{return 0;}
                break;
        case 6:
            if(cedula=="F-06"){ return 1;}
            else{return 0;}
                break;
        case 7:
            if(cedula=="G-07"){ return 1;}
            else{return 0;}
                break;
        case 8:
            if(cedula=="H-08"){ return 1;}
            else{return 0;}
                break;
        case 9:
            if(cedula=="I-09"){ return 1;}
            else{return 0;}
                break;
        case 10:
            if(cedula=="J-10"){ return 1;}
            else{return 0;}
                break;
        case 11:
            if(cedula=="K-11"){ return 1;}
            else{return 0;}
                break;
        case 12:
            if(cedula=="L-12"){ return 1;}
            else{return 0;}
                break;
        case 13:
            if(cedula=="M-13"){ return 1;}
            else{return 0;}
                break;
        case 14:
            if(cedula=="N-14"){ return 1;}
            else{return 0;}
                break;
        case 15:
            if(cedula=="Ñ-15"){ return 1;}
            else{return 0;}
                break;
        case 16:
            if(cedula=="O-16"){ return 1;}
            else{return 0;}
                break;
        case 17:
            if(cedula=="P-17"){ return 1;}
            else{return 0;}
                break;
        case 18:
            if(cedula=="Q-18"){ return 1;}
            else{return 0;}
                break;
        case 19:
            if(cedula=="R-19"){ return 1;}
            else{return 0;}
                break;
        case 20:
            if(cedula=="S-20"){ return 1;}
            else{return 0;}
                break;
        case 21:
            if(cedula=="T-21"){ return 1;}
            else{return 0;}
                break;
        case 22:
            if(cedula=="U-22"){ return 1;}
            else{return 0;}
                    }
    }

    function listarSeleccionado(forma){
        if(forma.extendidaendepto.value != 0  && !cedulaCorrecta(forma)) {
            $.messager.alert('Cédula Incorrecta', 'El número de orden no corresponde con el departamento o formato de cédula equivocado', 'warning');
            forma.extendidaendepto[0].style.display = "block";
            forma.extendidaendepto[0].selected = true;

            forma.extendidaenmunic[0].style.display = "block";
            forma.extendidaenmunic[0].selected = true;
            forma.extendidaenmunic.disabled = true;
            return void(0);
        } else {
            if(forma.extendidaendepto.value == 0) {
                forma.extendidaenmunic[0].style.display = "block";
                forma.extendidaenmunic[0].selected = true;
                forma.extendidaenmunic.disabled = true;
                return void(0);
            }
        }

        forma.extendidaenmunic.disabled = false;

        var dpt=forma.extendidaendepto.value;
        var seleccionado=false;
        for(var i=0; i<=forma.extendidaenmunic.length; i++){
            if(forma.extendidaenmunic[i].id==dpt){
                forma.extendidaenmunic[i].style.display = "block";
                if(!seleccionado){
                    forma.extendidaenmunic[i].selected = true;
                    seleccionado=true;
                }
            }else{
                forma.extendidaenmunic[i].style.display = "none";
            }
        }
    }

    function listarResidenciaDptoSeleccionado(forma){
        if(forma.residenciadepto.value == 0) {
            forma.residenciamunic[0].style.display = "block";
            forma.residenciamunic[0].selected = true;
            forma.residenciamunic.disabled = true;
            return void(0);
        }

        forma.residenciamunic.disabled = false;

        var dpt=forma.residenciadepto.value;
        var seleccionado=false;
        for(var i=0; i<=forma.residenciamunic.length; i++){
            if(forma.residenciamunic[i].id==dpt){
                forma.residenciamunic[i].style.display = "block";
                if(!seleccionado){
                    forma.residenciamunic[i].selected = true;
                    seleccionado=true;
                }
            }else{
                forma.residenciamunic[i].style.display = "none";
            }
        }
    }


    function transformarCadena(cadena){
        //cadena = cadena.toUpperCase();
        cadena = cadena.replace("Á","Aacute");cadena = cadena.replace("á","aacute");
        cadena = cadena.replace("É","Eacute");cadena = cadena.replace("é","eacute");
        cadena = cadena.replace("Í","Iacute");cadena = cadena.replace("í","iacute");
        cadena = cadena.replace("Ó","Oacute");cadena = cadena.replace("ó","oacute");
        cadena = cadena.replace("Ú","Uacute");cadena = cadena.replace("ú","uacute");
        cadena = cadena.replace("Ñ","Ntilde");cadena = cadena.replace("ñ","ntilde");
        return cadena;
    }

    function limpiarExtendidoEn(forma) {
        forma.extendidaendepto[0].style.display = "block";
        forma.extendidaendepto[0].selected = true;

        forma.extendidaenmunic[0].style.display = "block";
        forma.extendidaenmunic[0].selected = true;
        forma.extendidaenmunic.disabled = true;
    }

</script>
</head>
<body>
<!-- ENCABEZADO -->
<!-- INCLUDESCRIPT BLOCK : iheader -->

<!-- CONTENIDO -->
<div class="bga">
<div id="content">
<table width="100%">
<tbody>
<tr>
<td id="menucol" valign="top">
    <div id="actions" class="actions column">
        <!-- INCLUDESCRIPT BLOCK : imenu -->
    </div>
</td>
<td valign="top" id="vaultcol">
<div id="usercoldiv" class="usercolvisible" name="usercol"></div>
<table id="tabstable">
<tbody>
<tr>
    <td colspan="2" id="tdtabrow"></td>
</tr>
<tr id="treerow">
<td valign="top" colspan="2">
<div id="treepane" tabindex="100">
<!-- INCLUDESCRIPT BLOCK : isessioninfo -->
<div class="demo tree tree-default" id="thetree" tabindex="100">
<div id="ffframe-large">
<div id="ffheader">
    <span id="pff_title" class="page_title">Modificar información personal</span>
    <div id="fffonticon">
        <i class="fa fa-sun-o fa-spin fa-lg"></i>
    </div>
</div>
<div id="ffbody">
<div id="page_content" class="page_content">
<div class="ffpad fftop">
    <div class="clear"></div>
    <div id="headerrow2">
        <table width="100%" align="center" border="0"
               cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2" align="center">
                    <!--
                    <img width="{anchoImg}" height="{altoImg}" src="../../fotos/{aFoto}.jpg" >
                    -->
                </td>
            </tr>
        </table>
    </div>
</div>
<br>
<div id="ff_content">
<form name="informacionestudiante" id="informacionestudiante" action="" method=POST onSubmit="">
<div class="ff_pane" style="display: block;">
<div class='easyui-tabs' style='width:auto;height:auto'>
<div title='Actualización de información personal' style='padding:10px'>
<div id="wizard" class="swMain">
<ul class="anchor">
    <li>
        <a href="#step-1">
            <label class="stepNumber">1.</label>
                                                                                <span class="stepDesc">
                                                                                    <small>Información general</small>
                                                                                </span>
        </a>
    </li>
    <li>
        <a href="#step-2">
            <label class="stepNumber">2.</label>
                                                                                <span class="stepDesc">
                                                                                    <small>Información de residencia</small>
                                                                                </span>
        </a>
    </li>
    <li>
        <a href="#step-3">
            <label class="stepNumber">3.</label>
                                                                                <span class="stepDesc">
                                                                                    <small>Información de contacto</small>
                                                                                </span>
        </a>
    </li>
</ul>
<div id="step-1">
    <h2 class="StepTitle">Información general del docente</h2>
    <!-- step content -->
    <div class="ff_pane" style="display: block; padding: 13px;">
        <table cellspacing="0" class="fffields">
            <tbody>
            <tr id="titlerow">
                <td class="page_col1">Grupo</td>
                <td class="page_col2" colspan="2">
                    <input id="ff_grupo" type="text" spellcheck="false" disabled="true" value="{aGrupo}" style="width: 380px !important;" class="readOnlyText">
                </td>
            </tr>
            <tr>
                <td class="page_col1">Reg. Personal</td>
                <td class="page_col2" colspan="2">
                    <input id="ff_personal" type="text" spellcheck="false" disabled="true" value="{aPersonal}" style="width: 380px !important;" class="readOnlyText">
                </td>
            </tr>
            <tr id="first">
                <td class="page_col1">Titularidad</td>
                <td class="page_col2" colspan="2">
                    <input id="ff_titularidad" type="text" spellcheck="false" disabled="true" value="{aTitularidad}" style="width: 380px !important;" class="readOnlyText">
                </td>
            </tr>
            <tr id="first">
                <td class="page_col1">Nombre</td>
                <td class="page_col2" colspan="2">
                    <input id="ff_nombre" type="text" spellcheck="false" disabled="true" value="{aNombre}" style="width: 380px !important;" class="readOnlyText">
                </td>
            </tr>
            <tr id="last">
                <td class="page_col1">Apellido</td>
                <td class="page_col2" colspan="2">
                    <input id="ff_apellido" type="text" spellcheck="false" disabled="true" value="{aApellido}" style="width: 380px !important;" class="readOnlyText">
                </td>
            </tr>
            <tr>
                <td class="page_col1">Género (*)</td>
                <td class="page_col2">
                    <select name="genero" id="genero">
                        <option value="0"></option>
                        <option value="1">MASCULINO</option>
                        <option value="2">FEMENINO</option>
                    </select>
                </td>
                <td>
                    <span id="msg_genero" class="msg-danger-txt"></span>
                </td>
            </tr>
            <tr>
                <td class="page_col1">Fecha de nacimiento (*)</td>
                <td class="page_col2">
                    <input id="ff_fechanacimiento" name="ff_fechanacimiento" data-options="formatter:myformatter,parser:myparser" style="width: inherit;" class="easyui-datebox" size="15" editable="false" value="{aFechaNacimiento}"/>
                </td>
                <td>
                    <span id="msg_fechanac"></span>
                </td>
            </tr>
            <tr>
                <td class="page_col1">Cédula de vecindad</td>
                <td class="page_col2">
                    <input id="ff_cedula" name="ff_cedula" style="text-transform: uppercase !important; width: 380px !important;" placeholder="Formato: X-XX XXXXXXX; Ej.: A-01 0000000, P-17 1111111" onchange="javascript:limpiarExtendidoEn(document.informacionestudiante);" type="text" maxlength="13" spellcheck="false" value="{aCedula}">
                </td>
                <td>
                    <span id="msg_cedula"></span>
                </td>
            </tr>
            <tr>
                <td class="page_col1">Extendida en</td>
                <td class="page_col2">
                    <table align="left" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <select name="extendidaendepto" id="extendidaendepto"  onchange="javascript:listarSeleccionado(document.informacionestudiante);"/>
                                <option value="0" id="SIN DEPARTAMENTO"></option>
                                <!-- START BLOCK : LLENAR_SELECT_DEPTO -->
                                <option value="{indice_depto}" id="{nombre_depto}">{nombre_depto}</option>
                                <!-- END BLOCK : LLENAR_SELECT_DEPTO -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="extendidaenmunic" id="extendidaenmunic" />
                                <option value="0" id="SIN MUNICIPIO"></option>
                                <!-- START BLOCK : LLENAR_SELECT_MUNIC -->
                                <option value="{i_munic}" id="{indice_munic}" label="{nombre_munic}" >{nombre_munic}</option>
                                <!-- END BLOCK : LLENAR_SELECT_MUNIC -->
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <span id="msg_extendidaen"  class="msg-danger-txt"></span>
                </td>
            </tr>
            <tr>
                <td class="page_col1">DPI</td>
                <td class="page_col2">
                    <input id="ff_dpi" name="ff_dpi" type="text" spellcheck="false" style="width: 380px !important;"  value="{aDpi}">
                </td>
                <td>
                    <span id="msg_dpi"></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="step-2">
    <h2 class="StepTitle">Información de residencia</h2>
    <!-- step content -->
    <div class="ff_pane" style="display: block; padding: 13px;">
        <table cellspacing="0" class="fffields">
            <tbody>
            <tr>
                <td class="page_col1">Departamento (*)</td>
                <td class="page_col2">
                    <select id="residenciadepto" name="residenciadepto" onchange="javascript:listarResidenciaDptoSeleccionado(document.informacionestudiante);"/>
                    <option value="0" id="SIN DEPARTAMENTO"></option>
                    <!-- START BLOCK : LLENAR_SELECT_DEPTO2 -->
                    <option value="{indice_depto2}" id="{nombre_depto2}">{nombre_depto2}</option>
                    <!-- END BLOCK : LLENAR_SELECT_DEPTO2 -->
                    </select>
                </td>
                <td>
                    <span id="msg_residenciadepto" class="msg-danger-txt"></span>
                </td>
            </tr>
            <tr>
                <td class="page_col1">Municipio (*)</td>
                <td class="page_col2" colspan="2">
                    <select name="residenciamunic" id="residenciamunic" />
                    <option value="0" id="SIN MUNICIPIO"></option>
                    <!-- START BLOCK : LLENAR_SELECT_MUNIC2 -->
                    <option value="{i_munic2}" id="{indice_munic2}" label="{nombre_munic2}" >{nombre_munic2}</option>
                    <!-- END BLOCK : LLENAR_SELECT_MUNIC2 -->
                    </select>
                </td>
            </tr>
            <tr>
                <td class="page_col1">Dirección (*)</td>
                <td class="page_col2">
                    <input id="ff_direccion" name="ff_direccion" type="text" spellcheck="false" style="width: 380px !important;" value="{aDireccion}">
                </td>
                <td>
                    <span id="msg_direccion" class="msg-danger-txt"></span>
                </td>
            </tr>
            <tr>
                <td class="page_col1">Nacionalidad</td>
                <td class="page_col2">
                    <select name="nacionalidad" id="nacionalidad" />
                    <!-- START BLOCK : LLENAR_SELECT_NAC -->
                    <option value="{indice_nac}" id="{nombre_nac}" {aSelected}>{nombre_nac}</option>
                    <!-- END BLOCK : LLENAR_SELECT_NAC -->
                    </select>
                </td>
                <td>
                    <span id="msg_nacionalidad" class="msg-danger-txt"></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="step-3">
    <h2 class="StepTitle">Información de contacto</h2>
    <!-- step content -->
    <div class="ff_pane" style="display: block; padding: 10px;">
        <div>
            <table cellspacing="0" class="fffields">
                <tbody>
                <tr>
                    <td class="page_col1">Correo electrónico (*)</td>
                    <td class="page_col2">
                        <input id="ff_correop" name="ff_correop"  class="easyui-validatebox" value="{aCorreoP}" style="width: 380px !important;" data-options="required:true,validType:'email'">
                    </td>
                    <td>
                        <span id="msg_correop" class="msg-danger-txt"></span>
                    </td>
                </tr>
                <tr>
                    <td class="page_col1">Número de teléfono (*)</td>
                    <td class="page_col2">
                        <input id="ff_telp" name="ff_telp" type="text" class="easyui-numberbox" value="{aTelP}" style="width: 380px !important;" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                    </td>
                    <td>
                        <span id="msg_telp" class="msg-danger-txt"></span>
                    </td>
                </tr>
                <tr>
                    <td class="page_col1">Número de celular (*)</td>
                    <td class="page_col2">
                        <input id="ff_celp" name="ff_celp" type="text" class="easyui-numberbox" value="{aCelP}" style="width: 380px !important;" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                    </td>
                    <td>
                        <span id="msg_celp" class="msg-danger-txt"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
</div>
<div class="clear"></div>
</div>
</div>
</div>
<div class="contenido-pie"></div>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</div>

<script language="javascript">
    <!-- START BLOCK : INIT -->
    document.informacionestudiante.extendidaenmunic.disabled = true;
    document.informacionestudiante.residenciamunic.disabled = true;
    document.informacionestudiante.genero[{aSelGenero}].selected = true;
    document.informacionestudiante.extendidaendepto[{extdepto}].selected = true;
    document.informacionestudiante.extendidaenmunic[{extmunic}].selected = true;
    document.informacionestudiante.residenciadepto[{resdepto}].selected = true;
    document.informacionestudiante.residenciamunic[{resmunic}].selected = true;
    <!-- END BLOCK : INIT -->
</script>

<!-- PIE -->
<!-- INCLUDESCRIPT BLOCK : ifooter -->

</body>
</html>