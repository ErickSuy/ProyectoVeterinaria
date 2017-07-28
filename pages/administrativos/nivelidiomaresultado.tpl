<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="es">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }

        function validaNivelIdioma() {
            var nivel = document.frmIngresarCertificado.txtNivelIdioma.value;
            
            // todas las validaciones
            if(!/[0-9]+/.test(nivel)){
                document.getElementById("msg_nivelidioma").textContent = "* Solo se permiten valores numericos";
                document.frmIngresarCertificado.txtNivelIdioma.select();
                document.frmIngresarCertificado.txtNivelIdioma.focus();
                return false;
            }    
        
            if(nivel <= 0 || nivel > 20){
                document.getElementById("msg_nivelidioma").textContent = "* Nivel de idioma no permitido";
                document.frmIngresarCertificado.txtNivelIdioma.select();
                document.frmIngresarCertificado.txtNivelIdioma.focus();
                return false;
            }
            
            // si no hay error enviar el formulario
            document.frmIngresarCertificado.submit();
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
                                        <div id="ffframe">
                                            <div id="ffheader">
                                                <span class="page_title">Registro de certificado de idioma</span>
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
                                                            
                                                            <!-- START BLOCK : b_ingresocertificado -->
                                                            <form onsubmit="return false;" id="frmIngresarCertificado" name="frmIngresarCertificado" method="post" action="nivelidiomaresultado.php?opcion=2">
                                                                <div id="sitebody">
                                                                    <br>
                                                                    <hr>
                                                                    <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                    <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                    <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                    <div class="siterow"><br/><div class="siterow-center"><span>CERTIFICADOS DE INGLÉS REGISTRADOS</span></div><br/></div>
                                                                    <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aEstudiante}</span></div>
                                                                    <div class="siterow"><span class="page_label">Carrera: </span><span class="underline_label">{aCarrera}</span></div>

                                                                    <hr>
                                                                    <div id="dynheader" class="restrict_right"></div>
                                                                    <div id="dynbody" class="restrict_right ff">
                                                                        <div id="notesrow2">
                                                                            <div class="ff_pane" style="display: block;">
                                                                                <table cellspacing='0' class="fffields">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class="page_col1">Nivel de idioma</td>
                                                                                            <td class="page_col2">
                                                                                                <input name="txtNivelIdioma" type="text" id="txtNivelIdioma" size="10" maxlength="5" />
                                                                                            </td>
                                                                                            <td>
                                                                                                <span id="msg_nivelidioma" class="msg-danger-txt"></span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <hr>
                                                                    <div>
                                                                        <div id="siteactions" class="siterow restrict_right">
                                                                            <a name="btnGuardar" id="btnGuardar" href="javascript:void(0);" onclick="validaNivelIdioma();" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;">
                                                                                <i class="fa fa-database fa-lg"></i>
                                                                                <span>&nbsp;&nbsp;Grabar Nivel</span>
                                                                            </a>
                                                                        </div>
                                                                        <br>
                                                                        <br>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <!-- END BLOCK : b_ingresocertificado -->
                                                            
                                                            <!-- START BLOCK : b_listacertificados -->
                                                            <div id="sitebody">
                                                                <br>
                                                                <hr>
                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                <div class="siterow"><br/><div class="siterow-center"><span>CERTIFICADOS DE INGLÉS REGISTRADOS</span></div><br/></div>
                                                                <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aEstudiante}</span></div>
                                                                <div class="siterow"><span class="page_label">Carrera: </span><span class="underline_label">{aCarrera}</span></div>
                                                                
                                                                <hr>
                                                                <!-- START BLOCK : b_detalle -->
                                                                <div id="dynheader" class="restrict_right"></div>
                                                                <div id="dynbody" class="restrict_right">
                                                                    <div id="notesrow2">
                                                                        <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft1'">
                                                                            <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                                <thead>
                                                                                <tr class="tableheader">
                                                                                    <th width="10%" align=left>No.</th>
                                                                                    <th width="75%" align=left>NIVEL APROBADO</th>
                                                                                    <th width="15%" align=left>FECHA INGRESO</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <!-- START BLOCK : b_itemdetalle -->
                                                                                <tr>
                                                                                    <td width="10%">{vNo}</td>
                                                                                    <td align=left width="75%">{vNivel}</td>
                                                                                    <td align=left width="15%">{vFecha}</td>
                                                                                </tr>
                                                                                <!-- END BLOCK : b_itemdetalle -->
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div id="ft1" class="panel-footer" style="padding:5px;">
                                                                            <table><tr><td align="right"><span class="page_time_label"> Fecha: {aFecha}&nbsp;&nbsp;Hora: {aHora} </span></td></tr></table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- END BLOCK : b_detalle -->

                                                                <!-- START BLOCK : b_sindatos -->
                                                                <div id="dynheader" class="restrict_right"></div>
                                                                <div id="dynbody" class="restrict_right">
                                                                    <div id="notesrow2">
                                                                        <textarea disabled="disabled" cols="60" rows="10" spellcheck="false" autocomplete="off" style="height:100px; font-family:monospace; width:607px; max-width:607px; margin-left:12px;">No encontró información para el estudiante.</textarea>
                                                                    </div>
                                                                </div>
                                                                <!-- END BLOCK : b_sindatos -->
                                                                
                                                                <hr>
                                                                <div>
                                                                    <div id="siteactions" class="siterow restrict_right">
                                                                        <a name="btnAgregarCerti" id="btnAgregarCerti" href="nivelidiomaresultado.php?opcion=1" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;">
                                                                            <i class="fa fa-plus fa-lg"></i>
                                                                            <span>&nbsp;&nbsp;Agregar certificado</span>
                                                                        </a>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                </div>
                                                                
                                                            </div>
                                                            <!-- END BLOCK : b_listacertificados -->
                                                            
                                                            <!-- START BLOCK : b_error -->
                                                            <div id="sitebody">
                                                                <div class="alert-danger">
                                                                    <div><i class="fa fa-info-circle fa-lg"></i>  Error de operación</div>
                                                                    {mensajeError}
                                                                </div>
                                                            </div>
                                                            <!-- END BLOCK : b_error -->
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <a href="../menu/COAC_nivelidioma.php">
                                                        <input id="btnNuevaBusqueda" name="btnNuevaBusqueda" type="button" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Realizar nueva búsqueda">
                                                    </a>
                                                </div>
                                                <div class="clear"></div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="contenido-pie"></div>
                                </div>
                                <!-- F: CONTENIDO PRINCIPAL -->
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

