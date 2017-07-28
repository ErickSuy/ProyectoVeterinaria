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
        function actualizarSelect(anio) {
            var i;
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
                                                <span class="page_title">Consulta de inscripción</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                            <!-- START BLOCK : b_selecthistorial -->
                                                            <form name="busqueda" method="post" action="Enrollment.php">
                                                                <span>Especifique el año para la búsqueda de información...</span>
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
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td rowspan="2" style="vertical-align: middle;">
                                                                            <input id="Buscar" name="Buscar" type="submit" value="Buscar inscripción" class="nbtn rbtn btn_midi btn_exp_h okbutton"/>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </form>
                                                            <!-- END BLOCK : b_selecthistorial -->
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
                                                                                <!-- START BLOCK : b_contenido -->
                                                                                <hr>
                                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                                <div class="siterow"><br/><div class="siterow-center"><span>INFORMACIÓN DE INSCRIPCIÓN EN RYE</span></div><br/></div>
                                                                                <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aEstudiante}</span><span class="page_label">Carrera: </span><span class="underline_label">{aCarrera}</span></div>

                                                                                <!-- START BLOCK : b_detalle -->
                                                                                <hr>
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft1'">
                                                                                            <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                                                <thead>
                                                                                                <tr class="tableheader">
                                                                                                    <th width="10%" align=left>AÑO</th>
                                                                                                    <th width="45%" align=left>FECHA INSCRIPCIÓN</th>
                                                                                                    <th width="45%" align=left>OBSERVACIÓN</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td width="10%">{vCiclo}</td>
                                                                                                    <td align=left width="45%">{vFechaIns}</td>
                                                                                                    <td align=left width="45%">{vObser}</td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <div id="ft1" class="panel-footer" style="padding:5px;">
                                                                                            <table><tr><thalign="right"><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- END BLOCK : b_detalle -->

                                                                                <!-- START BLOCK : b_sindatos -->
                                                                                <hr>
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">No encontró información para el ciclo {aAnioConsulta}.</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- END BLOCK : b_sindatos -->

                                                                                <!-- END BLOCK : b_contenido -->

                                                                                <!-- START BLOCK : b_sinhistorial -->
                                                                                <hr>
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">No tiene ningúnb registro de inscripción registrado.</textarea>
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
                                                                                    <h4><i class='fa fa-warning'></i> TOMAR NOTA:</h4>
                                                                                    <ul>
                                                                                        <li>Esta es la boleta de inscripción, no es necesario imprimirla ni sellarla. Si necesita alguna constancia de inscripción ó la información<br>
                                                                                            no es correcta, favor dirigirse al departamento de Registro y Estadística.
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

<script language='JavaScript'>
    actualizarSelect({anio});
</script>
</body>
</html>