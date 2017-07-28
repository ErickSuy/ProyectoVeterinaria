<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
            window.open('../salirse.php');
        }
    </script>

    <script language="javascript">
        function redireccionar(nuevadireccion) {
            location.href = nuevadireccion;
        }

        function TrimLeft(str) {
            var resultStr = "";
            var i = len = 0;
            if (str + "" == "undefined" || str == null)
                return "";
            str += "";

            if (str.length == 0)
                resultStr = "";
            else {
                len = str.length;
                while ((i <= len) && (str.charAt(i) == " "))
                    i++;
                resultStr = str.substring(i, len);
            }
            return resultStr;
        }

        function TrimRight(str) {
            var resultStr = "";
            var i = 0;
            if (str + "" == "undefined" || str == null)
                return "";
            str += "";
            if (str.length == 0)
                resultStr = "";
            else {
                i = str.length - 1;
                while ((i >= 0) && (str.charAt(i) == " "))
                    i--;
                resultStr = str.substring(0, i + 1);
            }

            return resultStr;
        }

        function Trim(str) {
            var resultStr = "";
            resultStr = TrimLeft(str);
            resultStr = TrimRight(resultStr);
            return resultStr;
        }


        function ValidaArchivoNotas() {

            if (Trim(document.ArchivoNotas.userfile.value) == "") {
                alert("::.. Debe ingresar nombre de archivo a procesar ..::");
                return false;
            }
//espera();
            document.ArchivoNotas.submit();
            return true;
        }


        function ValidaArchivo() {

            if (Trim(document.ManejaArchivo.userfile.value) == "") {
                alert("::.. Debe ingresar nombre de archivo a procesar ..::");
                return false;
            }
//espera();
            document.ManejaArchivo.submit();
            return true;
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
                                        <div id="fframe-reporte">
                                            <div id="ffheader">
                                                <span class="page_title">Carga de notas de actividades</span>

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
                                                        <div class="ff_pane" style="display: block;">
                                                            <!-- START BLOCK : cargaarchivo -->
                                                            <form id="ManejaArchivo" name="ManejaArchivo" method="post" action="manejoarchivoactividades.php?opcion=2" enctype="multipart/form-data">
                                                                <div id="sitebody">
                                                                    <br>
                                                                    <hr>
                                                                    <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                    <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                    <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                    <div class="siterow"><br/><div class="siterow-center"><span>CARGA DE ARCHIVO DE SECCION</span></div><br/></div>
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
                                                                                    <input name="userfile" type="file" size="55">
                                                                                    <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                                                                                    <input type="button" value="     Asignar secciones    " onclick="ValidaArchivo();"/>
                                                                                    <label>
                                                                                        <input type="hidden" name="txtCurso" id="txtCurso" value="{txtCurso}"/>
                                                                                        <input type="hidden" name="txtCarrera" id="txtCarrera" value="{txtCarrera}"/>
                                                                                        <input type="hidden" name="txtSeccion" id="txtSeccion" value="{txtSeccion}"/>
                                                                                        <input type="hidden" name="txtPeriodo" id="txtPeriodo" value="{txtPeriodo}"/>
                                                                                        <input type="hidden" name="txtAnio" id="txtAnio" value="{txtAnio}"/>
                                                                                        <input type="hidden" name="txtTipoActividad" id="txtTipoActividad" value="{txtTipoActividad}"/>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Nota</td>
                                                                                <td class="page_col2">
                                                                                    <div align="justify">Debe de seleccionar el archivo que contiene el listado de
                                                                                        carnets de los estudiantes asignados a laboratorio que usted imparte no
                                                                                        importando la sección a la que esten asignados oficialmente.
                                                                                    </div>
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
                                                            <!-- END BLOCK : cargaarchivo -->

                                                            <!-- START BLOCK : carganotas -->
                                                            <form id="ArchivoNotas" name="ArchivoNotas" method="post" action="manejoarchivoactividades.php?opcion=21" enctype="multipart/form-data">
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
                                                                                    <input name="userfile" type="file" size="55">
                                                                                    <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                                                                                    <input style="width: auto !important;" class="nbtn gbtn btn_midi" type="button" value="Cargar Notas" onclick="ValidaArchivoNotas();"/>
                                                                                    <label>
                                                                                        <input type="hidden" name="txtCurso" id="txtCurso" value="{txtCurso}"/>
                                                                                        <input type="hidden" name="txtSeccion" id="txtSeccion" value="{txtSeccion}"/>
                                                                                        <input type="hidden" name="txtCarrera" id="txtCarrera" value="{txtCarrera}"/>
                                                                                        <input type="hidden" name="txtPeriodo" id="txtPeriodo" value="{txtPeriodo}"/>
                                                                                        <input type="hidden" name="txtAnio" id="txtAnio" value="{txtAnio}"/>
                                                                                        <input type="hidden" name="txtTipoActividad" id="txtTipoActividad" value="{txtTipoActividad}"/>
                                                                                        <input name="txtIdActividad" type="hidden" id="txtIdActividad" value="{txtIdActividad}" size="5"/>
                                                                                        <input name="txtEsSuperActividad" type="hidden" id="txtEsSuperActividad" value="{txtEsSuperActividad}" size="5"/>
                                                                                        <input name="txtPonderacionActividad" type="hidden" id="txtPonderacionActividad" value="{txtPonderacionActividad}" size="5"/>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Nota</td>
                                                                                <td class="page_col2">
                                                                                    <div align="justify">
                                                                                        <p class="note-font-style">
                                                                                            * El archivo debe contener el listado de carnets asignados a la carrera del curso <br/>
                                                                                            * En la siguiente columna la nota de la actividad<br/>
                                                                                            * Para esta actividad la nota debe estar entre el rango de 0 y {txtPonderacionActividad} puntos<br/>
                                                                                            * La nota de la actividad es sobre puntos netos con hasta dos decimales (Ej.: 15.01 | 4.9 | 7 | 8.1)
                                                                                        </p>
                                                                                    </div>
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
                                                            <!-- END BLOCK : carganotas -->

                                                            <!-- START BLOCK : mensaje -->
                                                            <div class='alert alert-{aTipoMensaje}'>
                                                                <h4><i class='fa fa-info-circle fa-lg'></i> {aEncabezadoMensaje}:</h4>{mensaje}
                                                            </div>
                                                            <!-- END BLOCK : mensaje -->

                                                            <!-- START BLOCK : erroresManejables -->
                                                            <p>
                                                            <center>
                                                                <font color="red" style="font-size: 13px; font-weight: bold;">
                                                                    {mensaje1}
                                                                </font>
                                                            </center>
                                                            <div align="center">
                                                                <table class="RAsig-table" align='center' width='80%' cellspacing='0' cellpadding='0' border='0'>
                                                                   <thead>
                                                                   <tr>
                                                                       <th align="center"> Carné</th>
                                                                       <th align="center"> Nota Existente<br> Procesada por Otro Encargado(1)</th>
                                                                       <th align="center"> Nota Existente<br> Procesada por Usted(2)</th>
                                                                       <th align="center"> Nota Nueva<br> Procesada por Usted(3)</th>
                                                                       <th align="center"> Nota Nueva<br> No Procesada<br> Menor a la Existente(4)</th>
                                                                   </tr>
                                                                   </thead>
                                                                    <tbody>
                                                                    <!-- START BLOCK : errorManejable -->
                                                                    <tr>
                                                                        <td align="center"> {elCarnet} </td>
                                                                        <td align="center"> {laNota1_1} </td>
                                                                        <td align="center"> {laNota1_2} </td>
                                                                        <td align="center"> {laNota1_3} </td>
                                                                        <td align="center"> {laNota1_4} </td>
                                                                    </tr>
                                                                    <!-- END BLOCK : errorManejable -->
                                                                    <tr>
                                                                        <td align="center" colspan='5'> &nbsp; </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan='5'>
                                                                            (1) Son las notas ya existentes en la base de datos, procesadas en otra secci&oacute;n
                                                                            de laboratorio,<br>
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mayores a las notas enviadas por usted
                                                                            en esta oportunidad.<br>
                                                                            (2) Son las notas ya existentes en la base de datos, procesadas por usted en una
                                                                            ocasi&oacute;n anterior,<br>
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mayores a las notas enviadas en esta
                                                                            oportunidad.<br>
                                                                            (3) Son las notas enviadas por usted en esta oportunidad, y que son mayores a
                                                                            las notas ya existentes<br>
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;en la base de datos, procesadas en una
                                                                            ocasi&oacute;n anterior.<br>
                                                                            (4) Son las notas enviadas por usted en esta oportunidad, y que son menores a
                                                                            las notas ya existentes<br>
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;en la base de datos, procesadas en una
                                                                            ocasi&oacute;n anterior.<br>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            </p>
                                                            <!-- END BLOCK : erroresManejables -->
                                                            <!-- START BLOCK : erroresManejables1 -->
                                                            <p>
                                                            <div class='alert alert-{aTipoMensaje}'>
                                                                <h4><i class='fa fa-info-circle fa-lg'></i> {aEncabezadoMensaje}:</h4>{mensaje2}
                                                            </div>
                                                            <br/>
                                                            <div align="center">
                                                                <font style="font-size: 13px; font-weight: bold;">
                                                                    <table class="RAsig-table" align='center' width='80%' cellspacing='0' cellpadding='0' border='0'>
                                                                        <thead>
                                                                        <tr>
                                                                            <th width="60%" align="center">CARNET</th>
                                                                            <th width="40%" align="right">NOTA INVÁLIDA</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- START BLOCK : errorManejable1 -->
                                                                        <tr>
                                                                            <td width="60%" align="center">{elCarnet1}
                                                                            </td>
                                                                            <td width="40%" align="right">{laNota2}
                                                                            </td>
                                                                        </tr>
                                                                        <!-- END BLOCK : errorManejable1 -->
                                                                        </tbody>
                                                                    </table>
                                                                </font>
                                                            </div>
                                                            </p>
                                                            <!-- END BLOCK : erroresManejables1 -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <input type="submit" name="Regresar" id="Regresar" value="Regresar a listado de actividades" class="nbtn rbtn btn_midi btn_exp_h okbutton" onclick='javascript:redireccionar("../notas-actividades/creaactividad.php?opcion=9&curso={AtxtCurso}&index={AtxtIndex}&carrera={AtxtCarrera}&seccion={AtxtSeccion}");'/>
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
