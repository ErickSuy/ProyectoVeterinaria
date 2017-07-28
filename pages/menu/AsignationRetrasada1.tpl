<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    
    <link href="../../libraries/js/wizard/styles/smart_wizard.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../../libraries/js/wizard/jquery.smartWizard2.js"></script>
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
        //*****************************INFORMACION DE LOS CURSOS DISPONIBLES*********************
        var infoCursos = new Array();
        <!-- START BLOCK : INFOCURSOS -->
        infoCursos[{aPosicion}] = new Array();
        infoCursos[{aPosicion}][0] = {aIdCourse};
        infoCursos[{aPosicion}][1] = "{aName}";
        <!-- END BLOCK : INFOCURSOS -->
        var NumDisp={NumDisp}; //Cantidad de cursos disponibles para asignar
        
        //*****************TERMINA PASO DE INFORMACIÓN DE LOS CURSSOS DISPONIBLES****************
        
        function getInfoCurso(codigo){
            var info = new Array();
            for(var i=0; i<NumDisp;i++){
                if(infoCursos[i][0]==codigo){
                    info[0]=infoCursos[i][0];
                    info[1]=infoCursos[i][1];
                    break;
                }
            }
            return info;
        }
        function generarOrden(codigoCurso){
            document.location.href = "saveAsignationRetrasada1.php?codigo="+codigoCurso;
            isStepValid = false;
            /*if(verificarTraslape(codigoCurso)){
                //$.messager.alert('INFORMACION', "ORDEN GENERADA :"+codigoCurso);
                document.location.href = "saveAsignationRetrasada1.php?codigo="+codigoCurso;
                isStepValid = false;
            }else{
                $.messager.alert('INFORMACION', "Existe traslpe de horario con el curso: "+codigoCurso);
            }*/
        }
        
        function eliminarOrden(codigoOrden){
            $.messager.confirm('CONFIRMAR',"Esta acción eliminirá y dejará sin validez la orden de pago No.: "+ codigoOrden+" \n ¿Continuar?", function(r){
                if (r){
                    document.location.href = "deleteAsignationR1.php?noOrden="+codigoOrden;
                    isStepValid = false;
                }
            });
        }
        function imprimirOrden(codigoOrden){
            //document.location.href = "printAsignationR1.php?noOrden="+codigoOrden;
            //isStepValid = false;
            var win = window.open("printAsignationR1.php?noOrden="+codigoOrden, '_blank');
            win.focus();
        }
        function infoOrden(codigoOrden){
            $.messager.alert('INFORMACION', "La orden No. "+codigoOrden+" ha sido pagada, para verificar la asignación dirigirse a la sección de cursos asignados", 'info');
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
                $("#cursos option[value='"+cod+"']").remove();

                $("#asignaciones").append(   '<tr id="'+cod+'">\n\
                                                <th style="padding-top: 5px; padding-bottom: 5px;">'+info[0]+'</th>\n\
                                                <td style="padding-top: 5px; padding-bottom: 5px;">'+info[1]+'</td>\n\
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
            }
            
        }
        function regresarCurso(codigoCurso,nombreCurso) //Vueleve a agregar el curso retirado al listado
        {
            $("#asignaciones tr[id='"+codigoCurso+"']").remove();
            $("#cursos").append('<option id="'+nombreCurso+'" value="'+codigoCurso+'">'+codigoCurso+' - '+nombreCurso+'</option>');
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
                                            <span id="pff_title" class="page_title">Asignación de Primera Retrasada</span>
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
                                                    <div title='Asignacion de primera retrasada' style='padding:10px'>
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
                                                                            <select id="cursos">
                                                                                <!-- START BLOCK : cursosDisponibles -->
                                                                                <option id="{aNombreCurso}" value="{aCurso}" >{aCurso} - {aNombreCurso}</option>
                                                                                <!-- END BLOCK : cursosDisponibles -->
                                                                            </select>
                                                                        </td>
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
                                                                                    <th width="65%"align="left">NOMBRE</th>
                                                                                    <th width="7%"align="left">ORDEN</th>
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