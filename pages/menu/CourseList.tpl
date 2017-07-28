<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- INCLUDESCRIPT BLOCK : ihead -->
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
                                                <span class="page_title">Catálogo de cursos aprobados</span>
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
                                                                <div title='Cátalogo' style='padding:0px; margin: 0px;'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <div class="easyui-panel" style="width:inherit;height:auto;">
                                                                            <div id="sitebody">
                                                                                <br><hr>
                                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                                <div class="siterow"><br/><div class="siterow-center"><span>LISTADO DE CURSOS APROBADOS</span></div><br/></div>
                                                                                <div class="siterow"><span class="page_label">Correspondientes al estudiante: </span><span class="underline_label">{aEstudiante}</span><span class="page_label">Carrera: </span><span class="underline_label">{aCarrera}</span></div>
                                                                                <!-- <span class="page_label">Promedio general: </span><span class="underline_label">{aPromedio}</span> -->
                                                                                <div class="siterow"><span class="page_label">Pensum: </span><span class="underline_label">{aPensum}</span></div>
                                                                                <div class="siterow"><span class="page_label">Total de cursos aprobados: </span><span class="underline_label">{aTotalCursos}</span></div>
                                                                                <hr>
<!-- START BLOCK: b_aprobados -->
                                                                                <div id="dynheader" class="restrict_right"></div>
                                                                                <div id="dynbody" class="restrict_right">
                                                                                    <div id="notesrow2">
                                                                                        <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft'">
                                                                                            <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0' border='0'>
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th width="4%">No.</th>
                                                                                                    <th width="6%">CURSO</th>
                                                                                                    <th width="60%">NOMBRE</th>
                                                                                                    <th width="8%" align="left">CRÉDITOS</th>
                                                                                                    <th width="17%" align="left">FECHA DE APROBACIÓN</th>
                                                                                                    <th width="5%" align="left">NOTA</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <!-- START BLOCK : detalleaprobados -->
                                                                                                <tr>
                                                                                                    <td>{aNum}.</td>
                                                                                                    <td>{aCurso}</td>
                                                                                                    <td>{aNombreCurso}</td>
                                                                                                    <td>{aCreditos}</td>
                                                                                                    <td>{aFechaAprobacion}</td>
                                                                                                    <td>{aNota}</td>
                                                                                                </tr>
                                                                                                <!-- END BLOCK : detalleaprobados -->
                                                                                                </tbody>
                                                                                            </table>
                                                                                            <!-- START BLOCK : b_cierre -->
                                                                                            <br>
                                                                                            <div class="siterow"><span class="page_label">INFORMACIÓN POSTERIOR AL CIERRE</span></div>
                                                                                            <table class="RAsig-table" width='100%' cellspacing="0" cellpadding="0" align="center">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th width="25">No.</th>
                                                                                                    <th width="27">CURSO</th>
                                                                                                    <th width="250">NOMBRE</th>
                                                                                                    <th width="50" align="center">NOTA</th>
                                                                                                    <th width="75" align="center">APROBACIÓN</th>
                                                                                                    <th width="250">OBSERVAIÓN</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <!-- START BLOCK : b_detalleaprobadoscierre -->
                                                                                                <tr>
                                                                                                    <td>{aNum}.</td>
                                                                                                    <td>{aCurso}</td>
                                                                                                    <td>{aNombreCurso}</td>
                                                                                                    <td>{aNota}</td>
                                                                                                    <td>{aFechaAprobacion}</td>
                                                                                                    <td>{aObservacion}</td>
                                                                                                </tr>
                                                                                                <!-- END BLOCK : b_detalleaprobadoscierre -->
                                                                                                </tbody>
                                                                                            </table>
                                                                                            <!-- END BLOCK : b_cierre -->
                                                                                        </div>
                                                                                        <div id="ft" class="panel-footer" style="padding:5px;">
                                                                                            <table><tr><td align="right"><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
<!-- END BLOCK: b_aprobados -->

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

</body>
</html>