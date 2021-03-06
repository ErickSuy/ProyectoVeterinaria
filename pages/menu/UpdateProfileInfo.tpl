﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
                    if($('#ff_calle').val() == ''){
                        isValid = false;
                        $('#msg_calle').html('*Este dato es requerido').show();
                    } else {
                        $('#msg_calle').html('').hide();
                    }

                    if($('#ff_numcasa').val() == ''){
                        isValid = false;
                        $('#msg_numcasa').html('*Este dato es requerido').show();
                    } else {
                        $('#msg_numcasa').html('').hide();
                    }

                    $('#msg_residenciadepto').html('').hide();
                }

                if($('#ff_pasaporte').val() != '') {
                    if($('#ff_fechapasaporte').datebox('getValue') == '') {
                        isValid = false;
                        $('#msg_fechapasaporte').html('*Este dato es requerido').show();
                    } else  {
                        $('#msg_fechapasaporte').html('').hide();
                    }

                    if($('#pais').val() == 0) {
                        isValid = false;
                        $('#msg_pais').html('*Este dato es requerido').show();
                    } else  {
                        $('#msg_pais').html('').hide();
                    }
                }

                return isValid;
            }

            function validateStep3(){
                var isValid = true;
                var emailp = $('#ff_correop').val();
                var emaila = $('#ff_correoa').val();


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

                if(emaila && emaila.length >0 && !($('#ff_correoa').validatebox('isValid'))){
                    isValid = false;
                    $('#msg_correoa').html('*Dato inválido').show();
                }else{
                    $('#msg_correoa').html('').hide();
                }

                var celp = $('#ff_celp').numberbox('getValue');
                var cela = $('#ff_cela').numberbox('getValue');
                var telp = $('#ff_telp').numberbox('getValue');
                var tela = $('#ff_tela').numberbox('getValue');
                var telpa = $('#ff_telpadre').numberbox('getValue');
                var telma = $('#ff_telmadre').numberbox('getValue');
                var telem = $('#ff_telemer').numberbox('getValue');


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

                if(cela.length >0 && cela.length != 8) {
                    isValid = false;
                    $('#msg_cela').html('*Dato inválido').show();
                } else {
                    $('#msg_cela').html('').hide();
                }
                if(tela.length>0 && tela.length != 8) {
                    isValid = false;
                    $('#msg_tela').html('*Dato inválido').show();
                } else {
                    $('#msg_tela').html('').hide();
                }
                if(telpa.length>0  && telpa.length != 8) {
                    isValid = false;
                    $('#msg_telpadre').html('*Dato inválido').show();
                } else {
                    $('#msg_telpadre').html('').hide();
                }
                if(telma.length>0 && telma.length != 8) {
                    isValid = false;
                    $('#msg_telmadre').html('*Dato inválido').show();
                } else {
                    $('#msg_telmadre').html('').hide();
                }
                if(telem.length>0 && telem.length != 8) {
                    isValid = false;
                    $('#msg_telemer').html('*Dato inválido').show();
                } else {
                    $('#msg_telemer').html('').hide();
                }

                var nombrep = $('#ff_nombrepadre').val();
                var nombrem = $('#ff_nombremadre').val();

                if(nombrep.length == 0) {
                    isValid = false;
                    $('#msg_nombrepadre').html('*Dato obligatorio').show();
                } else {
                    $('#msg_nombrepadre').html('').hide();
                }

                if(nombrem.length == 0) {
                    isValid = false;
                    $('#msg_nombremadre').html('*Dato obligatorio').show();
                } else {
                    $('#msg_nombremadre').html('').hide();
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

            var estadocivil = $("#estadocivil option:selected").html();
            var estadocivil_int;
            if(estadocivil=='SOLTERO'){
                estadocivil_int = 1;
            } else if(estadocivil=='CASADO') {
                estadocivil_int = 2;
            } else if(estadocivil=='SOLTERA') {
                estadocivil_int = 3;
            } else {
                estadocivil_int = 4;
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
            var avenida = argsForm.ff_calle.value;
            var num_casa = argsForm.ff_numcasa.value;
            var apartamento = argsForm.ff_aptoosimilar.value;
            var zona = argsForm.ff_zona.value;
            var colonia = argsForm.ff_colonia.value;

            var domicilio_depto;
            var domicilio_munic = argsForm.residenciamunic[argsForm.residenciamunic.selectedIndex].value;

            domicilio_depto = municipios[domicilio_munic][0];
            domicilio_munic = municipios[domicilio_munic][1];

            var nacionalidad = argsForm.nacionalidad[argsForm.nacionalidad.selectedIndex].value;
            var pasaporte = argsForm.ff_pasaporte.value;

            var pasaporte_fecha =  $('#ff_fechapasaporte').datebox('getValue');
            if(pasaporte_fecha.length>0) {
                pasaporte_fecha = pasaporte_fecha.split('/');
                pasaporte_fecha = pasaporte_fecha[2] + '-' + pasaporte_fecha[1] + '-' + pasaporte_fecha[0];
            }

            var pasaporte_pais = argsForm.pais[argsForm.pais.selectedIndex].value;

            // Formación
            var titulo_bach = argsForm.ff_carrerabr.value;
            var titulo_institucion = argsForm.ff_establecmientobr.value;

            //Contacto
            var correo_prin = argsForm.ff_correop.value;
            var correo_alte = argsForm.ff_correoa.value;
            var celular_prin = $('#ff_celp').numberbox('getValue');
            var celular_alte = $('#ff_cela').numberbox('getValue');
            var telefono_prin = $('#ff_telp').numberbox('getValue');
            var telefono_alte = $('#ff_tela').numberbox('getValue');
            var telefono_papa = $('#ff_telpadre').numberbox('getValue');
            var telefono_mama = $('#ff_telmadre').numberbox('getValue');
            var telefono_emer = $('#ff_telemer').numberbox('getValue');
            var nombre_mama = argsForm.ff_nombremadre.value;
            var nombre_papa = argsForm.ff_nombrepadre.value;
            var nombre_emer = argsForm.ff_nombreemer.value;

            var cadenaparametros='genero='+genero+
                    '&estadocivil='+estadocivil+
                    '&fecha_nacimiento='+fecha_nacimiento+
                    '&cedula='+cedula+
                    '&cedula_depto='+cedula_depto+
                    '&cedula_munic='+cedula_munic+
                    '&dpi='+dpi+
                    '&avenida='+avenida+
                    '&num_casa='+num_casa+
                    '&apartamento='+apartamento+
                    '&zona='+zona+
                    '&colonia='+colonia+
                    '&domicilio_depto='+domicilio_depto+
                    '&domicilio_munic='+domicilio_munic+
                    '&nacionalidad='+nacionalidad+
                    '&pasaporte='+pasaporte+
                    '&pasaporte_fecha='+pasaporte_fecha+
                    '&pasaporte_pais='+pasaporte_pais+
                    '&titulo_bach='+titulo_bach+
                    '&titulo_institucion='+titulo_institucion+
                    '&correo_prin='+correo_prin+
                    '&correo_alte='+correo_alte+
                    '&telefono_prin='+telefono_prin+
                    '&telefono_alte='+telefono_alte+
                    '&telefono_papa='+telefono_papa+
                    '&telefono_mama='+telefono_mama+
                    '&telefono_emer='+telefono_emer+
                    '&nombre_mama='+nombre_mama+
                    '&nombre_papa='+nombre_papa+
                    '&nombre_emer='+nombre_emer+
                    '&celular_prin='+celular_prin+
                    '&celular_alte='+celular_alte+
                    '&genero_int='+genero_int+
                    '&estadocivil_int='+estadocivil_int;
            console.log(cadenaparametros);
            document.location.href = "SaveUpdatedProfileInfo.php?"+cadenaparametros;
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

        function imprimirformulario(argsForm)
        {
            var i;
            var r=0;
            var trabaja='No', cambiocarrera='No', trasladofacultad='No', trasladouniversidad='No', solicitoequivalencias='No';
            if(argsForm.radiobutton[1].checked){
                trabaja='Si';
            }else{
                argsForm.lugartrabajo.value="";
                argsForm.cargo.value="";
                argsForm.direcciontrabajo.value="";
            }
            if(argsForm.radiobutton1[1].checked){
                cambiocarrera='Si';
            }else{
                argsForm.carreraorigen.value="";
            }
            if(argsForm.radiobutton2[1].checked){
                trasladofacultad='Si';
            }else{
                argsForm.facultadorigen.value="";
            }
            if(argsForm.radiobutton3[1].checked){
                trasladouniversidad='Si';
            }else{
                argsForm.universidadorigen.value="";
            }
            if(argsForm.radiobutton4[1].checked){
                solicitoequivalencias='Si';
            }else{
                solicitoequivalencias='No';
            }

            if(argsForm.radiobutton[1].checked && (argsForm.lugartrabajo.value == '' || argsForm.cargo.value == '' || argsForm.direcciontrabajo.value == '')){
                r=1;
            }
            if(argsForm.radiobutton1[1].checked && (argsForm.carreraorigen.value == '')){
                r=1;
            }
            if(argsForm.radiobutton2[1].checked && (argsForm.facultadorigen.value == '')){
                r=1;
            }
            if(argsForm.radiobutton3[1].checked && (argsForm.universidadorigen.value == '')){
                r=1;
            }
            if(argsForm.cedula.value != ''&& argsForm.lugarnacimiento.value != ''&& argsForm.titulo.value != ''&& argsForm.establecimiento.value != ''&& argsForm.nombrepadre.value != ''&& argsForm.nombremadre.value != ''&& argsForm.anioingreso.value != ''&& r==0)
            {
                if(cedulaCorrecta(argsForm)==1){
                    var altura=screen.height;
                    var ancho =screen.width-150;
                    var propiedades="top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes";
                    propiedades=propiedades+",height="+altura;
                    propiedades=propiedades+",width="+ancho;


                    var cadenaparametros='cedula='+transformarCadena(argsForm.cedula.value)+
                            '&extendidaen='+transformarCadena(argsForm.extendidaenmunic[argsForm.extendidaenmunic.selectedIndex].label)+ ", "+
                            transformarCadena(argsForm.extendidaendepto[argsForm.extendidaendepto.selectedIndex].id)+
                            '&lugarnac='+transformarCadena(argsForm.lugarnacimiento.value)+
                            '&titulo='+transformarCadena(argsForm.titulo.value)+
                            '&establecimiento='+transformarCadena(argsForm.establecimiento.value)+
                            '&lugartrabajo='+transformarCadena(argsForm.lugartrabajo.value)+
                            '&cargo='+transformarCadena(argsForm.cargo.value)+
                            '&direcciontrabajo='+transformarCadena(argsForm.direcciontrabajo.value)+
                            '&nombrepadre='+transformarCadena(argsForm.nombrepadre.value)+
                            '&nombremadre='+transformarCadena(argsForm.nombremadre.value)+
                            '&carreraorigen='+transformarCadena(argsForm.carreraorigen.value)+
                            '&facultadorigen='+transformarCadena(argsForm.facultadorigen.value)+
                            '&universidadorigen='+transformarCadena(argsForm.universidadorigen.value)+
                            '&mesingreso='+transformarCadena(argsForm.select2[argsForm.select2.selectedIndex].value)+
                            '&anioingreso='+transformarCadena(argsForm.anioingreso.value)+
                            '&trabaja='+trabaja+
                            '&cambiocarrera='+cambiocarrera+
                            '&trasladofacultad='+trasladofacultad+
                            '&trasladouniversidad='+trasladouniversidad+
                            '&solicitoequivalencias='+solicitoequivalencias;
                    Ventana=document.open('vistaprevia.php?'+cadenaparametros,'InformacionGraduandos',propiedades);
                    GuardarDatos(argsForm);
                }else{
                    alert("Cédula Incorrecta. El número de orden no corresponde con el departamento o formato de cédula equivocado");
                }
            }else{
                alert("Debe llenar todos los campos marcados como obligatorios");
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
                forma.ff_calle.value = '';
                forma.ff_numcasa.value = '';
                forma.ff_aptoosimilar.value = '';
                forma.ff_zona.value = '';
                forma.ff_colonia.value = '';
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

        function rellenarLugarnac(forma){
            forma.lugarnacimiento.value="";
            forma.lugarnacimiento.value=forma.extendidaenmunic[forma.extendidaenmunic.selectedIndex].label + ", " + forma.extendidaendepto[forma.extendidaendepto.selectedIndex].id;
            forma.lugarnacimiento.selected=true;
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

        function validarPaisYfechaPasaporte(forma) {
            var pasaporte = forma.ff_pasaporte.value;

            if(pasaporte.length==0) {
                forma.pais[0].selected = true;
                forma.pais.disabled = true;
                $('#ff_fechapasaporte').datebox('setValue', '');
                $('#ff_fechapasaporte').datebox('disable');
            } else {
                forma.pais.disabled = false;
                $('#ff_fechapasaporte').datebox('enable');
            }
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
                                                                    <img width="{anchoImg}" height="{altoImg}" src="../../fotos/{aFoto}.jpg" >
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
                                                                                    <small>Información académica</small>
                                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#step-4">
                                                                <label class="stepNumber">4.</label>
                                                                                <span class="stepDesc">
                                                                                    <small>Información de contacto</small>
                                                                                </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div id="step-1">
                                                        <h2 class="StepTitle">Información general del estudiante</h2>
                                                        <!-- step content -->
                                                        <div class="ff_pane" style="display: block; padding: 13px;">
                                                            <table cellspacing="0" class="fffields">
                                                                <tbody>
                                                                <tr id="titlerow">
                                                                    <td class="page_col1">Grupo</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_grupo" type="text" spellcheck="false" disabled="true" value="{aGrupo}" class="readOnlyText">
                                                                    </td>
                                                                </tr>
                                                                <tr id="last2" class="displaynone" style="display: table-row;">
                                                                    <td class="page_col1">Carrera</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_carrera" type="text" spellcheck="false" disabled="true" value="{aCarrera}" class="readOnlyText">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Carnet</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_carnet" type="text" spellcheck="false" disabled="true" value="{aCarnet}" class="readOnlyText">
                                                                    </td>
                                                                </tr>
                                                                <tr id="first">
                                                                    <td class="page_col1">Nombre</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_nombre" type="text" spellcheck="false" disabled="true" value="{aNombre}" class="readOnlyText">
                                                                    </td>
                                                                </tr>
                                                                <tr id="last">
                                                                    <td class="page_col1">Apellido</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_apellido" type="text" spellcheck="false" disabled="true" value="{aApellido}" class="readOnlyText">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Género</td>
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
                                                                    <td class="page_col1">Estado civil</td>
                                                                    <td class="page_col2">
                                                                        <select name="estadocivil" id="estadocivil">
                                                                            <option value="0"></option>
                                                                            <option value="1">SOLTERO</option>
                                                                            <option value="2">CASADO</option>
                                                                            <option value="3">SOLTERA</option>
                                                                            <option value="4">CASADA</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <span id="msg_estadocivil" class="msg-danger-txt"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Fecha de nacimiento</td>
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
                                                                        <input id="ff_cedula" name="ff_cedula" style="text-transform: uppercase !important;" placeholder="Formato: X-XX XXXXXXX; Ej.: A-01 0000000, P-17 1111111" onchange="javascript:limpiarExtendidoEn(document.informacionestudiante);" type="text" maxlength="13" spellcheck="false" value="{aCedula}">
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
                                                                    <td class="page_col1">CUI/Pasaporte</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_dpi" name="ff_dpi" type="text" spellcheck="false" disabled="true" value="{aDpi}" class="readOnlyText">
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
                                                        <h2 class="StepTitle">Información de residencia del estudiante</h2>
                                                        <!-- step content -->
                                                        <div class="ff_pane" style="display: block; padding: 13px;">
                                                            <table cellspacing="0" class="fffields">
                                                                <tbody>
                                                                <tr>
                                                                    <td class="page_col1">Número o nombre de calle o avenida</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_calle" name="ff_calle" type="text" spellcheck="false" value="{aNumeroOCalleOAvenida}">
                                                                    </td>
                                                                    <td>
                                                                        <span id="msg_calle" class="msg-danger-txt"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Número (casa)</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_numcasa" name="ff_numcasa" type="text" spellcheck="false" value="{aNumeroCasa}">
                                                                    </td>
                                                                    <td>
                                                                        <span id="msg_numcasa" class="msg-danger-txt"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Apartamento o similar</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_aptoosimilar" name="ff_aptoosimilar" type="text" spellcheck="false" value="{aAptoOSimilar}">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Zona</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_zona" name="ff_zona" type="text" spellcheck="false" value="{aZona}">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Colonia, barrio, aldea u otro</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <input id="ff_colonia" name="ff_colonia" type="text" spellcheck="false" value="{aColonia}">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Departamento</td>
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
                                                                    <td class="page_col1">Municipio</td>
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
                                                                <tr>
                                                                    <td class="page_col1">Número de pasaporte</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_pasaporte" name="ff_pasaporte" type="text" onchange="javascript:validarPaisYfechaPasaporte(document.informacionestudiante);" spellcheck="false"  value="{aPasaporte}">
                                                                    </td>
                                                                    <td>
                                                                        <span id="msg_pasaporte" class="msg-danger-txt"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Fecha de emisión del pasaporte</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_fechapasaporte" name="ff_fechapasaporte" style="width: inherit;" data-options="formatter:myformatter,parser:myparser" class="easyui-datebox" size="15" editable="false" value="{aFechaPasaporte}"/>
                                                                    </td>
                                                                    <td>
                                                                        <span id="msg_fechapasaporte" class="msg-danger-txt"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Pais emisión pasaporte</td>
                                                                    <td class="page_col2">
                                                                        <select name="pais" id="pais" />
                                                                        <option value="0" id="SIN PAIS"></option>
                                                                        <!-- START BLOCK : LLENAR_SELECT_PAIS-->
                                                                        <option value="{indice_pais}" id="{nombre_pais}">{nombre_pais}</option>
                                                                        <!-- END BLOCK : LLENAR_SELECT_PAIS -->
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <span id="msg_pais" class="msg-danger-txt"></span>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="step-3">
                                                        <h2 class="StepTitle">Información de diversificado del estudiante</h2>
                                                        <!-- step content -->
                                                        <div class="ff_pane" style="display: block; padding: 120px;">
                                                            <table cellspacing="0" class="fffields">
                                                                <tbody>
                                                                <tr>
                                                                    <td class="page_col1">Nombre de la carrera</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_carrerabr"  name="ff_carrerabr" type="text" spellcheck="false" value="{aCarreraBr}">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="page_col1">Nombre del establecimiento</td>
                                                                    <td class="page_col2">
                                                                        <input id="ff_establecmientobr" name="ff_establecmientobr" type="text" spellcheck="false" value="{aEstablecimiento}">
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="step-4">
                                                        <h2 class="StepTitle">Información de contacto del estudiante</h2>
                                                        <!-- step content -->
                                                        <div class="ff_pane" style="display: block; padding: 10px;">
                                                            <div>
                                                                <table cellspacing="0" class="fffields">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td class="page_col1">Correo electrónico principal</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_correop" name="ff_correop"  class="easyui-validatebox" value="{aCorreoP}" data-options="required:true,validType:'email'">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_correop" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Correo electrónico alterno</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_correoa" name="ff_correoa"  class="easyui-validatebox" value="{aCorreoA}" data-options="required:false,validType:'email'">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_correoa" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de teléfono principal</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_telp" name="ff_telp" type="text" class="easyui-numberbox" value="{aTelP}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_telp" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de teléfono alterno</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_tela" name="ff_tela" type="text" class="easyui-numberbox" value="{aTelA}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_tela" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de celular principal</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_celp" name="ff_celp" type="text" class="easyui-numberbox" value="{aCelP}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_celp" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de celular alterno</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_cela" name="ff_cela" type="text" class="easyui-numberbox" value="{aCelA}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_cela" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Nombre del padre</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_nombrepadre" name="ff_nombrepadre" type="text" spellcheck="false" value="{aNombrePadre}">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_nombrepadre" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de teléfono/celular del padre</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_telpadre" name="ff_telpadre" type="text" class="easyui-numberbox" value="{aTelPadre}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_telpadre" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Nombre de la madre</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_nombremadre" name="ff_nombremadre" type="text" spellcheck="false" value="{aNombreMadre}">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_nombremadre" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de teléfono/celular de la madre</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_telmadre" name="ff_telmadre" type="text" class="easyui-numberbox" value="{aNombreMadre}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_telmadre" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Nombre de contacto de emergencia</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_nombreemer" name="ff_nombreemer" type="text" spellcheck="false" value="{aNombreEmer}">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_nombreemer" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Número de teléfono/celular de emergencia</td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_telemer" name="ff_telemer" type="text" class="easyui-numberbox" value="{aTelEmer}" data-options="precision:0,groupSeparator:'',decimalSeparator:''">
                                                                        </td>
                                                                        <td>
                                                                            <span id="msg_telemer" class="msg-danger-txt"></span>
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
    document.informacionestudiante.estadocivil[{aSelEstadoCivil}].selected = true;
    document.informacionestudiante.extendidaendepto[{extdepto}].selected = true;
    document.informacionestudiante.extendidaenmunic[{extmunic}].selected = true;
    document.informacionestudiante.residenciadepto[{resdepto}].selected = true;
    document.informacionestudiante.residenciamunic[{resmunic}].selected = true;
    document.informacionestudiante.pais[{paisselect}].selected = true;
    document.informacionestudiante.pais.disabled = true;
    <!-- END BLOCK : INIT -->
</script>

<!-- PIE -->
<!-- INCLUDESCRIPT BLOCK : ifooter -->

</body>
</html>