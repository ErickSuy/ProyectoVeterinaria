<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script type="text/javascript" src="../../libraries/AssignationFunctions.js"></script>
    <script language="javascript">
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
                                                <span id="pff_title"
                                                      class="page_title">Asignación de cursos de semestre</span>

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
                                                    <br>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:auto'>
                                                                <div title='3. Resultado de la asignación'>
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
                                                                                                    <th width="8%">EDIFICIO</th>
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
                                                                                                <!-- START BLOCK : iasignacion -->
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
                                                                                                <!-- END BLOCK : iasignacion -->

                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <div id="ft" class="panel-footer" style="padding:5px;">
                                                                                            <table><tr><td>[<font color=#3d3d3d>Clase Magistral</font>]</td><td>|</td><td><font color=#0000FF>[Laboratorio]</font></td><td>|<font color=#008000>[Práctica]</font></td><td>|<font color=#FF00CC>[Tutoria]</font></td><td><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <table width="100%" cellspacing="0"
                                                                                   cellpadding="0">
                                                                                <tr>
                                                                                    <td align="center">
                                                                                        <table border="0" align="center"
                                                                                               cellspacing="0"
                                                                                               cellpadding="0">
                                                                                            <tr>
                                                                                                <td width="66"><img
                                                                                                            src="../../resources/images/1_off.gif"
                                                                                                            width="34"
                                                                                                            height="34"
                                                                                                            border="1"
                                                                                                            class="step-img">
                                                                                                </td>
                                                                                                <td width="66"><img
                                                                                                            src="../../resources/images/2_off.gif"
                                                                                                            width="34"
                                                                                                            height="34"
                                                                                                            border="1"
                                                                                                            class="step-img">
                                                                                                </td>
                                                                                                <td width="66"><img
                                                                                                            src="../../resources/images/3_on.gif"
                                                                                                            width="34"
                                                                                                            height="34"
                                                                                                            border="1"
                                                                                                            class="step-img">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>&nbsp;</td>
                                                                                                <td>&nbsp;</td>
                                                                                                <td>&nbsp;</td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>

                                                                            <div id="menu_acc" class="easyui-accordion"
                                                                                 style="width:100%;">
                                                                                <div title="Información importante"
                                                                                     iconCls="fa fa-question-circle fa-lg"
                                                                                     style="overflow:auto;padding:10px;">
                                                                                    <div class='alert alert-warning'>
                                                                                        <h4>
                                                                                            <i class='fa fa-warning'></i>
                                                                                            TOMAR NOTA</h4>
                                                                                        <ul>
                                                                                            <li> Los CURSOS y LABORATORIOS se muestran con el horario oficial publicado.</li>
                                                                                            <li> Asegurese de anotar el <strong>N&Uacute;MERO DE TRANSACCI&Oacute;N</strong> ya que es su
                                                                                                constancia de que realiz&oacute; el proceso de asignaci&oacute;n.
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
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