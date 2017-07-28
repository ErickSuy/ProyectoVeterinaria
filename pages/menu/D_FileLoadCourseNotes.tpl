<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }
        function redireccionar(nuevadireccion)
        {
            location.href=nuevadireccion;
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
                                                        <form id="frmPorArchivo" enctype='multipart/form-data' name="frmPorArchivo" method=POST onSubmit="document.frmPorArchivo.btnEnviarArchivo.value=1; BloquearBoton(frmPorArchivo.btnSubmit,0); ">
                                                            <div id="sitebody">
                                                                <br>
                                                                <hr>
                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                <div class="siterow"><br/><div class="siterow-center"><span>CARGA DE NOTAS POR ARCHIVO: {tituloActividad}</span></div><br/></div>
                                                                <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                                                <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span><span class="page_time_label"> Fecha: {vFecha}&nbsp;&nbsp;Hora:{vHora} </span></div>
                                                                <hr>
                                                                <div id="dynheader" class="restrict_right"></div>
                                                                <div class="ff_pane" style="display: block;">
                                                                    <table class="fffields"  cellspacing="0" >
                                                                        <tbody>
                                                                        <tr>
                                                                            <td class="page_col1">Archivo</td>
                                                                            <td class="page_col2">
                                                                                <input type='file' name='txtArchivo' size='75'>
                                                                                <input type='hidden' name='MAX_FILE_SIZE' value='{maxtamanio}'>
                                                                                <input type='submit' name='btnSubmit' value='Cargar archivo de notas' style="width: auto !important;" class="nbtn gbtn btn_midi" >
                                                                                <input type=hidden name='btnEnviarArchivo'>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Nota</td>
                                                                            <td class="page_col2">
                                                                                <div align="justify">
                                                                                    <p class="note-font-style">
                                                                                        El archivo debe contener el listado de carnets asignados a la carrera del curso y en la <br/>
                                                                                        siguiente columna la nota del examen final. La nota debe estar especificada en puntos netos 30 puntos y 20 puntos, respectivamente.<br/>
                                                                                    </p>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">
                                                                                <label>Nombre</label>
                                                                            </td>
                                                                            <td class="page_col2">
                                                                                <input type="text" size="75" disabled="true" value="{Nombre}"/>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">
                                                                                <label>Tama&ntilde;o</label>
                                                                            </td>
                                                                            <td class="page_col2">
                                                                                <input type="text" size="75" disabled="true" value="{Tamanio}"/>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">
                                                                                <label>Tipo</label>
                                                                            </td>
                                                                            <td class="page_col2"">
                                                                                <input type="text" size="75" disabled="true" value="{Tipo}"/>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div>
                                                                    <hr>
                                                                </div>
                                                                <br>
                                                                <br>
                                                            </div>
                                                        </form>
                                                        &nbsp;{Msg}
                                                        <!-- INCLUDESCRIPT BLOCK : imensajenotas -->
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <input type="submit" name="Regresar" id="Regresar"
                                                           value="Regresar a listado de cursos"
                                                           class="nbtn rbtn btn_midi btn_exp_h okbutton"
                                                           onclick='javascript:redireccionar("D_CourseList.php?anio={vAnio}&periodo={periodo}");'/>
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