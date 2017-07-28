<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
                                                <span class="page_title">Consulta de cursos asignados</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                            <form name="busqueda" method="post" action="SearchAssignedCourse.php">
                                                                <span>Especifique el año y período para la búsqueda de información...</span>
                                                                <!-- Selects para periodo y año -->
                                                                <table width="100%" align="center" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <table cellspacing="0" class="fffields">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="page_col1">
                                                                                        <label>Año:</label>
                                                                                    </td>
                                                                                    <td class="page_col2">
                                                                                        <select id="anio" name="anio">
                                                                                            <!-- START BLOCK : selectAnio -->
                                                                                            <option value="{anio_select}">{anio_select}</option>
                                                                                            <!-- END BLOCK : selectAnio -->
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="page_col1">
                                                                                        <label>Período:</label>
                                                                                    </td>
                                                                                    <td class="page_col2">
                                                                                        <!-- INCLUDESCRIPT BLOCK : iselectciclo -->
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td rowspan="2" style="vertical-align: middle;">
                                                                            <input id="Buscar" name="Buscar" type="submit" value="Buscar asignación de cursos" class="nbtn rbtn btn_midi btn_exp_h okbutton"/>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:auto;'>
                                                                <div title='Cátalogo' style='padding:0px; margin: 0px;'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <div class="easyui-panel" style="width:inherit;height:auto;">
                                                                            <div id="sitebody">
                                                                                <br><hr>
                                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                                <div class="siterow"><br/><div class="siterow-center"><span>BOLETA DE CURSOS ASIGNADOS</span></div><br/></div>
                                                                                <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{aAnio}</span></div>
                                                                                <div class="siterow"><span class="page_label">Estudiante: </span><span class="underline_label">{aEstudiante}</span><span class="page_label">Carrera: </span><span class="underline_label">{aCarrera}</span></div>

                                                                                <!-- START BLOCK : b_asignados -->

                                                                                <div class="siterow"><span class="page_label">Fecha de asignación:</span><span class="underline_label">{aFechaAsignacion}</span><span class="page_label">Transacción No.: </span><span class="transaction_label">{aTransaccion}</span></div>
                                                                                <hr>
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft'">
                                                                                            <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0' border='0'>
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th width="4%">CÓD.</th>
                                                                                                    <th width="50%">NOMBRE</th>
                                                                                                    <th width="5%">GRUPO</th>
                                                                                                    <td width="8%">EDIFICIO</th>
                                                                                                    <th width="6%">SALÓN</th>
                                                                                                    <th width="7%">INICIO</th>
                                                                                                    <th width="7%">FINAL</th>
                                                                                                    <th width="2%">L</th>
                                                                                                    <th width="2%">M</th>
                                                                                                    <th width="2%">M</th>
                                                                                                    <th width="2%">J</th>
                                                                                                    <th width="2%">V</th>
                                                                                                    <th width="2%">S</th>
                                                                                                    <th width="2%">D</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <!-- START BLOCK : b_detalleasignacion -->
                                                                                                <tr class="{aClaseFila}">
                                                                                                    <td><font color="{aFont}">{aCurso}</font></td>
                                                                                                    <td><font color="{aFont}">{aNombreCurso}</font></td>
                                                                                                    <td><font color="{aFont}">{aSeccion}</font></td>
                                                                                                    <td><font color="{aFont}">{aEdificio}</font></td>
                                                                                                    <td><font color="{aFont}">{aSalon}</font></td>
                                                                                                    <td><font color="{aFont}">{aInicio}</font></td>
                                                                                                    <td><font color="{aFont}">{aFinal}</font></td>
                                                                                                    <td><font color="{aFont}">{aL}</font></td>
                                                                                                    <td><font color="{aFont}">{aM}</font></td>
                                                                                                    <td><font color="{aFont}">{aMi}</font></td>
                                                                                                    <td><font color="{aFont}">{aJ}</font></td>
                                                                                                    <td><font color="{aFont}">{aV}</font></td>
                                                                                                    <td><font color="{aFont}">{aS}</font></td>
                                                                                                    <td><font color="{aFont}">{aD}</font></td>
                                                                                                </tr>
                                                                                                <!-- END BLOCK : b_detalleasignacion -->
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <div id="ft" class="panel-footer" style="padding:5px;">
                                                                                            <table><tr><td>[<font color=#3d3d3d>Clase Magistral</font>]</td><td>|</td><td><font color=#0000FF>[Laboratorio]</font></td><td>|<font color=#008000>[Práctica]</font></td><td>|<font color=#FF00CC>[Tutoria]</font></td><td><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <table width="100%" cellspacing="0" cellpadding="0" align="center">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td align="center">
                                                                                            <strong>
                                                                                                <p>
                                                                                                    * Se informa que una vez finalizado el periodo de
                                                                                                    asignaciones, se realiza una </br>
                                                                                                    auditoria general sobre las asignaciones y cualquier
                                                                                                    asignación fuera de </br>
                                                                                                    regla se anulará automáticamente.
                                                                                                </p>
                                                                                            </strong>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>

                                                                                <!-- END BLOCK : b_asignados -->

                                                                                <!-- START BLOCK : b_sinasignacion -->
                                                                                <hr>
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">No tiene cursos asignados o no existe información para el período y año especificados.</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- END BLOCK : b_sinasignacion -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
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

<script language="JavaScript">
    actualizarSelect({periodo}, {anio});
</script>

</body>
</html>