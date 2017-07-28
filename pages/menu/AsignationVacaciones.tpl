<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    
    <link href="../../libraries/js/wizard/styles/smart_wizard.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../../libraries/js/wizard/jquery.smartWizard3.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            // Smart Wizard
            $('#wizard').smartWizard({
                transitionEffect: 'slideleft',
                onLeaveStep:leaveAStepCallback,
                onFinish:onFinishCallback
            });
            function leaveAStepCallback(obj, context){                
                return validarPeriodo();
            }
            function onFinishCallback(objs, context){
				$('#wizard').smartWizard('showMessage','Para generar boleta u otra gestion, utilizar las opciones al lado derecho de los cursos.<br/>\
														<i class=\'fa fa-check-circle fa-lg\'></i> Generar orden de pago.<br/>\
														<i class=\'fa fa-times-circle fa-lg\'></i> Remover curso seleccionado.<br/>\
														<i class=\'fa fa-trash fa-lg\'></i> Eliminar orden generada.<br/>\
														<i class=\'fa fa-print fa-lg\'></i> Imprimir orden generada.<br/>\
														<i class=\'fa fa-info-circle fa-lg\'></i> Informacion de curso.');
                //document.location.href = "../../pages/estudiantes/estudiantes.htm";
                //isStepValid = false;
            }
        });
        
        //Pasando información completa de los cursos disponibles
        var infoCursos = new Array();
        <!-- START BLOCK : INFOCURSOS -->
        infoCursos[{aPosicion}] = new Array();
        infoCursos[{aPosicion}][0] = {aIdCourse};
        infoCursos[{aPosicion}][1] = "{aName}";
        infoCursos[{aPosicion}][2] = "{aSeccion}";
        infoCursos[{aPosicion}][3] = "{aEdificio}";
        infoCursos[{aPosicion}][4] = "{aSalon}";
        infoCursos[{aPosicion}][5] = "{aInicio}";
        infoCursos[{aPosicion}][6] = "{aFinal}";
        infoCursos[{aPosicion}][7] = "{aLun}";
        infoCursos[{aPosicion}][8] = "{aMar}";
        infoCursos[{aPosicion}][9] = "{aMie}";
        infoCursos[{aPosicion}][10] = "{aJue}";
        infoCursos[{aPosicion}][11] = "{aVie}";
        infoCursos[{aPosicion}][12] = "{aSab}";
        infoCursos[{aPosicion}][13] = "{aDom}";
        infoCursos[{aPosicion}][14] = {aPrice}; //Precio del curso
        infoCursos[{aPosicion}][15] = {aInicioMin}; //Hora de inicio en minutos, contando desde 00:00
        infoCursos[{aPosicion}][16] = {aFinalMin}; //Hora de final en minutos, contando desde 00:00
        <!-- END BLOCK : INFOCURSOS -->
        var NumDisp={NumDisp}; //Cantidad de cursos disponibles para asignar
        var tiempoPermitido={tmpPermitido}; //Minutos permitidos para asignarse
        
        function getInfoCurso(codigo){
            var info = new Array();
            for(var i=0; i<NumDisp;i++){
                if(infoCursos[i][0]==codigo){
                    info[0]=infoCursos[i][0];
                    info[1]=infoCursos[i][1];
                    info[2]=infoCursos[i][2];
                    info[3]=infoCursos[i][3];
                    info[4]=infoCursos[i][4];
                    info[5]=infoCursos[i][5];
                    info[6]=infoCursos[i][6];
                    info[7]=infoCursos[i][7];
                    info[8]=infoCursos[i][8];
                    info[9]=infoCursos[i][9];
                    info[10]=infoCursos[i][10];
                    info[11]=infoCursos[i][11];
                    info[12]=infoCursos[i][12];
                    info[13]=infoCursos[i][13];
                    info[14]=infoCursos[i][14];
                    info[15]=infoCursos[i][15];
                    info[16]=infoCursos[i][16];
                    break;
                }
            }
            return info;
        }
        
        function generarOrden(codigoCurso){
            if(verificarTraslape(codigoCurso)){
                //$.messager.alert('INFORMACION', "ORDEN GENERADA :"+codigoCurso);
                document.location.href = "saveAsignationVacaciones.php?codigo="+codigoCurso;
                isStepValid = false;
            }else{
                $.messager.alert('INFORMACION', "Existe traslpe de horario con el curso: "+codigoCurso);
            }
        }
        
        function eliminarOrden(codigoOrden){
            $.messager.confirm('CONFIRMAR',"Esta acción eliminirá y dejará sin validez la orden de pago No.: "+ codigoOrden+" \n ¿Continuar?", function(r){
                if (r){
                    document.location.href = "deleteAsignationVacaciones.php?noOrden="+codigoOrden;
                    isStepValid = false;
                }
            });
        }
        function imprimirOrden(codigoOrden){
            document.location.href = "printAsignationVacaciones.php?noOrden="+codigoOrden;
            isStepValid = false;
        }
        function infoOrden(codigoOrden){
            $.messager.alert('INFORMACION', "La orden No. "+codigoOrden+" ha sido pagada, para verificar la asignación dirigirse a la sección de cursos asignados", 'info');
        }
        function validarTiempo(idCourse){
            var respuesta= false;
            var tmpTiempo;
            var info= new Array();
            
            info=getInfoCurso(idCourse);
            tmpTiempo=(info[16]-info[15]);
            
            var tabla = document.getElementById('seleccionados');
            for (var i = 0, row; row = tabla.rows[i]; i++) {
                info = new Array();
                for (var j = 0, col; col = row.cells[j]; j++) {
                    if(j==0 && col!=null){ //en la posicion 0 esta el codigo
                        info=getInfoCurso(col.firstChild.nodeValue);
                        tmpTiempo+=(info[16]-info[15]);
                    }
                }  
            } 
            
            if(tmpTiempo<=tiempoPermitido)
                respuesta=true;
            
            return respuesta;
        }
        function verificarTraslape(idCourse){
            var respuesta= false;
            var dia=0;
            var control=0;
            var comparaciones=0;
            var infoNvo= new Array();
            var infoSelec;
            
            infoNvo=getInfoCurso(idCourse);// Curso que se va a adherir
            
            var tabla = document.getElementById('seleccionados');
            for (var i = 0, row; row = tabla.rows[i]; i++) { //Itera las filas de la tabla
                dia=0;
                infoSelec = new Array();
                if(row.cells[0]!=null && row.cells[0].firstChild.nodeValue!=idCourse){ //Posición 0 se encuentra el codigo del curso
                    infoSelec=getInfoCurso(row.cells[0].firstChild.nodeValue);
                    for(var j=7; j<14;j++){ //corresponde a la posicion de los dias en el vector de información (7 es lunes...)  
                        if(infoNvo[j]=="X" && infoSelec[j]=="X"){
                            if((infoNvo[15]<infoNvo[16] && infoSelec[15]<infoSelec[16]) && ((infoNvo[15]<infoSelec[15] && infoNvo[16]<=infoSelec[15])||(infoNvo[15]>=infoSelec[16] && infoNvo[16]>infoSelec[16]))){
                                dia++;
                            }
                        }else{
                            dia++;
                        }      
                    }
                }else{
                    dia=7
                }
                comparaciones++;
                if(dia==7){
                    control++;
                }
            } 
            
            if(control==comparaciones){
                respuesta=true;
            }
                
            return respuesta;
        }
        function validarPeriodo()
        {
            var isActive = {isActive};
            if(isActive==1)
                return true;
            else
            {
                $('#wizard').smartWizard('showMessage','El sistema no esta activo actualmente.');
                $('#wizard').smartWizard('setError',"{stepnum:stepnumber,iserror:true}");
                return false;
            }
        }
        function selectedCourses() //Verifica que haya cursos seleccionados
        {
            var tabla = document.getElementById('seleccionados');
            var dat = '';
            for (var i = 0, row; row = tabla.rows[i]; i++) {
                for (var j = 0, col; col = row.cells[j]; j++) {
                    if(j==0 && col!=null){ //en la posicion 0 esta el codigo
                        dat += col.firstChild.nodeValue;
                    }
                }  
            }
            return dat;
        }
        function selectAvailables()//Verifica que haya almenos un curso que no este pagado.
        {
            var activar=false;
            
            var tabla = document.getElementById('seleccionados');
            var dat = '';
            for (var i = 0, row; row = tabla.rows[i]; i++) {
                for (var j = 0, col; col = row.cells[j]; j++) {
                    if(j==2 && col!=null){ //en la posicion 0 esta el codigo
                        if(col.firstChild.nodeValue!=="PAGADO")
                            activar=true;
                    }
                }  
            }
            return activar;
        }
                
        function agregarCurso() //Escribe el curso seleccionado hacia la tabla de selecciones
        {
            var objeto = document.getElementById('cursos');
            var indice=objeto.selectedIndex;
            var cod = objeto.value;
            var curso = objeto.options[indice].id;
            var info = new Array();
            info=getInfoCurso(cod);
            
            if(objeto.value>0) //Evita que el mensaje se vaya a la tabla de seleccionados
            {                
                if(validarTiempo(cod)){
                document.getElementById('secciones').options.length = 0; //Borra las secciones del curso en el combobox
                $("#cursos option[value='"+cod+"']").remove();

                $("#asignaciones").append(   '<tr id="'+cod+'">\n\
                                                <th style="padding-top: 5px; padding-bottom: 5px;">'+info[0]+'</th>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[1]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[5]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[6]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[7]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[8]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[9]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[10]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[11]+'</td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">NINGUNO</td>\n\
                                                <th style="padding-top: 5px; padding-bottom: 5px;"width="5%" align="left">\n\
                                                    <div style=\'height:inherit;width: auto;\' id=\'divIconoQuitar\'>\n\
                                                        <a class=\'btnQuitarCurso\' href=\'javascript:void(0);\' onclick="javascript:regresarCurso('+cod+',\''+curso+'\');" title=\'Clic para quitar curso de la lista\'>\n\
                                                            <i class=\'fa fa-times-circle fa-lg\'></i>\n\
                                                        </a>\n\
                                                    </div>\n\
                                                </th>\n\
                                                <th style="padding-top: 5px; padding-bottom: 5px;"width="5%" align="left">\n\
                                                    <div style=\'height:inherit;width: auto;\' id=\'divIconoGenerar\'>\n\
                                                        <a class=\'btnGeneraOrden\' href=\'javascript:void(0);\' onclick="javascript:generarOrden('+cod+');" title=\'Clic para generar orden de pago del curso\'>\n\
                                                            <i class=\'fa fa-check-circle fa-lg\'></i>\n\
                                                        </a>\n\
                                                    </div>\n\
                                                </th>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;"></td>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;"></td>\n\
                                            </tr>');
                }else{
                    $('#wizard').smartWizard('showMessage','Está excediendo la cantidad de horas permitidas');
                    $('#wizard').smartWizard('setError',"{stepnum:stepnumber,iserror:true}");
                }
            }
            
        }
        function regresarCurso(codigoCurso,nombreCurso) //Vueleve a agregar el curso retirado al listado
        {
            $("#asignaciones tr[id='"+codigoCurso+"']").remove();
            $("#cursos").append('<option id="'+nombreCurso+'" value="'+codigoCurso+'" onclick="javascript:escribirSeccion();">'+codigoCurso+' - '+nombreCurso+'</option>');
        }
        
        function escribirSeccion()
        {
            //$("#secciones").append('<option id="'+nombreCurso+'" value="'+codigoCurso+'">'+codigoCurso+' - '+nombreCurso+'</option>');
            //var objeto = document.getElementById('secciones');
            //if(!objeto.value > 0)
            var objeto = document.getElementById('cursos');    
            document.getElementById('secciones').options.length = 0;

            if(objeto.value>0)
                $("#secciones").append('<option id="A" value="1">A</option>');
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
                                            <span id="pff_title" class="page_title">Asignación de Cursos de Vacaciones</span>
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
                                                    <div title='Asignación de Cursos de Vacaciones' style='padding:10px'>
                                                    <div id="wizard" class="swMain">
                                                    <ul class="anchor">
                                                        <li>
                                                            <a href="#step-1">
                                                                <label class="stepNumber">1.</label>
                                                                                <span class="stepDesc">
                                                                                    <small>Selección de cursos</small>
                                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#step-2">
                                                                <label class="stepNumber">2.</label>
                                                                                <span class="stepDesc">
                                                                                    <small>Generación de boleta</small>
                                                                                </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div id="step-1">
                                                        <div class="ff_pane" style="display: block; padding: 120px;">
                                                            <table  width="100%" align="center" class="fffields">
                                                                <tbody>
                                                                <!-- START BLOCK : MENSAJES -->
                                                                <tr class="{aClass}">
                                                                    <td class="page_col1" align="center">
                                                                        <i class="{aIcono}"></i>
                                                                    </td>
                                                                    <td class="page_col2">
                                                                        {aMensaje}
                                                                    </td>
                                                                </tr>
                                                                <!-- END BLOCK : MENSAJES -->
                                                                <!-- START BLOCK : MENSAJES2 -->
                                                                <tr class="{aClass2}">
                                                                    <td class="page_col1" align="center">
                                                                        <i class="{aIcono2}"></i>
                                                                    </td>
                                                                    <td class="page_col2">
                                                                        {aMensaje2}
                                                                    </td>
                                                                </tr>
                                                                <!-- END BLOCK : MENSAJES2 -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="step-2">
                                                        <h2 class="StepTitle">Listado de cursos</h2>
                                                        <!-- step content -->
                                                        <div class="ff_pane" style="display: block; padding: 13px;">
                                                            <table cellspacing="0" class="fffields">
                                                                <tbody>
                                                                <tr id="titlerow">
                                                                    <td class="page_col1">Cursos</td>
                                                                    <td class="page_col2" colspan="2">
                                                                        <select id="cursos" onchange="escribirSeccion();">
                                                                            <option id="curso" value="0" selected>Seleccione curso</option>
                                                                            <!-- START BLOCK : cursosDisponibles -->
                                                                            <option id="{aNombreCurso}" value="{aCurso}">{aCurso} - {aNombreCurso}</option>
                                                                            <!-- END BLOCK : cursosDisponibles -->
                                                                        </select>
                                                                    </td>
                                                                    <tr id="titlerow" >
                                                                        <td class="page_col1">Seccion</td>
                                                                        <td class="page_col2" colspan="2">
                                                                            <select id="secciones">
                                                                                <!-- START BLOCK : secciones -->
                                                                                <option id="{aSeccion}" value="{aIdSeccion}" >{aSeccion} - {aIdSeccion}</option>
                                                                                <!-- END BLOCK : secciones -->
                                                                            </select>
                                                                    </tr>
                                                                    <tr id="titlerow" >
                                                                        <td class="page_col1"></td>
                                                                        <td>
                                                                            <input type="button" id="boton" value="Agregar Curso" onclick="javascript:agregarCurso();"/>
                                                                        </td>
                                                                    </tr>
                                                                </tr>
                                                                </tbody>
                                                                <tbody>
                                                                <tr id="cursosSeleccionados">
                                                                    <td class="page_col1">Asignaciones</td>
                                                                    <td class="page_col2" colspan="10">
                                                                        <table id="asignaciones" class='reporte-cursos RAsig-table' width="100%" border="1" cellpadding="0" cellspacing="0">
                                                                            <thead>
                                                                            <tr>
                                                                                <th width="4%"align="left">CÓD.</th>
                                                                                <th width="43%"align="left">NOMBRE</th>
                                                                                <th width="7%"align="center">INICIO</th>
                                                                                <th width="7%"align="left">FINAL</th>
                                                                                <th width="2%"align="left">L</th>
                                                                                <th width="2%"align="left">M</th>
                                                                                <th width="2%"align="left">M</th>
                                                                                <th width="2%"align="left">J</th>
                                                                                <th width="2%"align="left">V</th>
                                                                                <th width="5%"align="left">ORDEN</th>
                                                                                <th width="6%"align="left">QUITAR-</th>
                                                                                <th width="6%"align="left">GENERAR-</th>
                                                                                <th width="6%"align="left">ELIMINAR-</th>
                                                                                <th width="6%"align="left">IMPRIMIR</th>
                                                                                <th hidden="true" align="center">INDICE</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="seleccionados" class="{aClaseFila}">
                                                                                <!-- START BLOCK : GENERADOS -->
                                                                                <tr id="{aCurso}">
                                                                                    <th style="padding-top: 5px; padding-bottom: 5px;">{aCurso}</th>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;">{aNombreCurso}</td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;">{aInicio}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aFinal}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aL}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aM}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aMi}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aJ}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aV}</td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;">{aOrden}</td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;"></td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;"></td>
                                                                                    <th style="padding-top: 5px; padding-bottom: 5px;"width="5%" align="left">
                                                                                        <div style='height:inherit;width: auto;' id='divIconoEliminar{aCurso}'>
                                                                                            <a class='btnGeneraOrden' href='javascript:void(0);' onclick="javascript:eliminarOrden('{aOrden}');" title='Clic para eliminar orden de pago del curso'>
                                                                                                <i class='fa fa-trash fa-lg'></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </th>
                                                                                    <th style="padding-top: 5px; padding-bottom: 5px;"width="5%" align="left">
                                                                                        <div style='height:inherit;width: auto;' id='divIconoImprimir{aCurso}'>
                                                                                            <a class='btnImprimirOrden' href='javascript:void(0);' onclick="javascript:imprimirOrden('{aOrden}');" title='Clic para imprimir la orden de pago del curso'>
                                                                                                <i class='fa fa-print fa-lg'></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </th>
                                                                                </tr>
                                                                                <!-- END BLOCK : GENERADOS -->
                                                                                <!-- START BLOCK : PAGADOS -->
                                                                                <tr id="{aCurso}">
                                                                                    <th style="padding-top: 5px; padding-bottom: 5px;">{aCurso}</th>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;">{aNombreCurso}</td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;">{aInicio}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aFinal}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aL}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aM}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aMi}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aJ}</td>
                                                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{aV}</td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;">{aOrden}</td>
                                                                                    <th style="padding-top: 5px; padding-bottom: 5px;"width="5%" align="left">
                                                                                        <div style='height:inherit;width: auto;' id='divIconoInfo{aCurso}'>
                                                                                            <a class='btnGeneraOrden' href='javascript:void(0);' onclick="javascript:infoOrden('{aOrden}');" title='Clic para información de la orden'>
                                                                                                <i class='fa fa-info-circle fa-lg'></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </th>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;"></td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;"></td>
                                                                                    <td style="padding-top: 5px; padding-bottom: 5px;"></td>
                                                                                </tr>
                                                                                <!-- END BLOCK : PAGADOS -->
                                                                            </tbody>
                                                                        </table>
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

<!-- PIE -->
<!-- INCLUDESCRIPT BLOCK : ifooter -->

</body>
</html>