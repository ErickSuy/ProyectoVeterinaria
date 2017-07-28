<!DOCTYPE html PUBLIC "-//W3C//DthXHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Expires" CONTENT="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Cache-Control" content="must-revalidate">
    <meta http-equiv="Cache-Control" content="post-check=0">
    <meta http-equiv="Cache-Control" content="pre-check=0">
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script language='javascript'>
        function actualizarSelect(periodo, anio) {
            var i;
            var longitud = document.busqueda.periodo.length;
            for (i = 0; i < longitud; i++) {
                if (document.busqueda.periodo.options[i].value == periodo) {
                    document.busqueda.periodo.options[i].selected = true;
                    break;
                }
            }
            longitud = document.busqueda.anio.length;
            for (i = 0; i < longitud; i++) {
                if (document.busqueda.anio.options[i].value == anio) {
                    document.busqueda.anio.options[i].selected = true;
                    break;
                }
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
                                                <span class="page_title">Reporte de conteo de asignaciones</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                        </div>
                                                    </div>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:auto'>
                                                                <div title='Resultado' style='padding:0px; margin: 0px;'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <div class="easyui-panel" style="width:inherit;height:auto;">
                                                                            <div id="sitebody">
                                                                                <br>
                                                                                <hr>
                                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                                <div class="siterow"><br/><div class="siterow-center"><span>CONTEO DE ASIGNACIÓN DE CURSOS EN {aCiclo}</span></div><br/></div>
                                                                                <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aEstudiante}</span><span class="page_label">Carrera: </span><span class="underline_label">{aCarrera}</span></div>
                                                                                <hr>

                                                                                <!-- START BLOCK : b_conteogeneral -->
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft1'">
                                                                                            <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th>CURSO</th>
                                                                                                    <th>NOMBRE</th>
                                                                                                    <th>SEMESTRE</th>
                                                                                                    <th>VACACIONES</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <!-- START BLOCK : detalleconteogeneral -->
                                                                                                <tr>
                                                                                                    <td>{vCod}</td>
                                                                                                    <td>{vCurso}</td>
                                                                                                    <td>{vNumSem}</td>
                                                                                                    <td>{vNumVac}</td>
                                                                                                </tr>
                                                                                                <!-- END BLOCK : detalleconteogeneral -->
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <div id="ft1" class="panel-footer" style="padding:5px;">
                                                                                            <table><tr><thalign="right"><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- END BLOCK : b_conteogeneral -->

                                                                                <!-- START BLOCK : b_conteociclo -->
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft2'">
                                                                                            <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th>CURSO</th>
                                                                                                    <th>NOMBRE</th>
                                                                                                    <th>CONTEO</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <!-- START BLOCK : detalleconteociclo -->
                                                                                                <tr>
                                                                                                    <td>{vCod}</td>
                                                                                                    <td>{vCurso}</td>
                                                                                                    <td>{vNumVeces}</td>
                                                                                                </tr>
                                                                                                <!-- END BLOCK : detalleconteociclo -->
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <div id="ft2" class="panel-footer" style="padding:5px;">
                                                                                            <table><tr><thalign="right"><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- END BLOCK : b_conteociclo -->

                                                                                <!-- START BLOCK : b_sinhistorial -->
                                                                                <hr>
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">No tiene registro de asignación de cursos que generé conteo.</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- END BLOCK : b_sinhistorial -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="menu_acc" class="easyui-accordion" style="width:100%;">
                                                                <div title="Importante" iconCls="fa fa-question-circle fa-lg" style="overflow:auto;padding:10px;">
                                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                            <td>
                                                                                <div class='alert alert-warning'>
                                                                                    <h4><i class='fa fa-warning'></i> PLAN DE REPITENCIAS</h4>
                                                                                    <ul>
                                                                                        <li><font style="font-size: 9px;">NORMATIVO DE EVALUACIÓN Y PROMOCIÓN DEL ESTUDIANTE DE LA FMVZ (fragmentos)</li>
                                                                                        <li><font style="font-size: 9px;">CAPÍTULO III:</li>
                                                                                        <li><font style="font-size: 9px;">
                                                                                                <p>
                                                                                                    Artículo 5: Se le otorga al estudiante hasta tres oportunidades para asignarse y cursar una misma asignatura. Cada una de ellas con dos oportunidades de recuperación.<br>
                                                                                                    Ningún estudiante podrá cursar más de tres veces una asignatura, con excepción de los casos contemplados en el Artículo 29 del Reglamento General de Evaluación y<br>
                                                                                                    Promoción del Estudiante de la Universidad de San Carlos de Guatemala.
                                                                                                </p>
                                                                                        </li>
                                                                                    </ul>
                                                                                    <br>
                                                                                    <ul>
                                                                                        <li>
                                                                                            <font style="font-size: 9px;">NORMATIVO DE EVALUACIÓN Y PROMOCIÓN DEL ESTUDIANTE DE LA USAC (fragmentos)</li>
                                                                                        <li>
                                                                                            <font style="font-size: 9px;">TÍTULO III; CAPÍTULO I:</li>
                                                                                        <li>
                                                                                            <font style="font-size: 9px;">
                                                                                                Artículo 24: Asignación: Se otorga al estudiante hasta tres oportunidades para asignarse y cursar una misma asignatura. Cada una de ellas con dos oportunidades para<br>
                                                                                                exámnes de recuperación. Ningún estudiante podrá cursar más de tres veces una asignatura, con excepción de los casos contemplados en el Artículo 29.
                                                                                        </li>
                                                                                        <li><font style="font-size: 9px;">
                                                                                                Artículo 25: Escuela de Vacaciones: Las escuelas de vacaciones o cualquier otra modalidad para que el estudiante regular pueda solventar o adelantar cursos que el pensum<br>
                                                                                                de estudios de la unidad académica tenga instituido, no podrá ser mayor a tres oportunidades por curso asignado en el ciclo lectivo correspondiente. La misma no se incluye<br>
                                                                                                dentro del Artículo 24.
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons"></div>
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