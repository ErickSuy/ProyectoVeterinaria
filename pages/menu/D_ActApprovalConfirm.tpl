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
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
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
                                <!-- I: CONTENIDO PRINCIPAL -->
                                <div id="treepane" tabindex="100">
                                    <!-- INCLUDESCRIPT BLOCK : isessioninfo -->
                                    <div class="demo tree tree-default" id="thetree" tabindex="100">
                                        <div id="ffframe-large">
                                            <div id="ffheader">
                                                <span class="page_title">Carga de notas de exámenes finales</span>

                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>

                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2"></div>
                                                    </div>
                                                    <div id="ff_content">
                                                        <div id="sitebody">
                                                            <br>
                                                            <hr>
                                                            <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span>
                                                            </div>
                                                            <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span>
                                                            </div>
                                                            <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span>

                                                                <div style="display:inline; margin-left:170px;"></div>
                                                            </div>
                                                            <div class="siterow"><br/>

                                                                <div class="siterow-center"><span>APROBAR NOTAS DE NOTAS DE EXAMEN FINAL</span>
                                                                </div>
                                                                <br/></div>
                                                            <div class="siterow"><span class="page_label">Del curso: </span><span
                                                                        class="underline_label">{vCurso}
                                                                    - {vNombre}</span><span
                                                                        class="page_label">de la carrera: </span><span
                                                                        class="underline_label">{vCarrera}</span>
                                                            </div>
                                                            <div class="siterow"><span
                                                                        class="page_label">Correspondientes a: </span><span
                                                                        class="underline_label">{vPeriodo}</span><span
                                                                        class="page_label">de: </span><span
                                                                        class="underline_label">{vAnio}</span><span
                                                                        class="page_time_label"> Fecha: {vFecha}
                                                                    &nbsp;&nbsp;Hora:{vHora} </span></div>
                                                            <hr>
                                                            <div id="dynheader" class="restrict_right">
                                                            </div>
                                                            <div id="dynbody" class="restrict_right ff">
                                                                <div id="notesrow2">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <hr>
                                                                <div id="siteactions" class="siterow restrict_right">
                                                                    <input type="submit" name="aprobaracta"
                                                                           style="width: auto !important;" class="nbtn gbtn btn_midi"
                                                                           value="Aprobar Acta"
                                                                           title="Aprueba la Informaci&oacute;n ingresada"/>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <br>
                                                        </div>
                                                        <div align="center">
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