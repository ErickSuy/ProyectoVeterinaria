<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="es">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }

        function bloquearymostrar(boton) {
            if (boton.disabled != true) {
                boton.disabled = true;
            }
            var o_idiv = document.getElementById('barra');
            o_idiv.style.display = 'block';
        }

        function mostrarBarraProceso(forma) {
            var o_idiv = document.getElementById('barraProceso');
            o_idiv.style.display = 'block';
        }

        function actualizarSelect(periodo, anio) {
            var i;
            var longitud;

            longitud = document.busqueda.periodo.length;
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
                                                <form name="busqueda" method="post" action="{aNivel}">
                                                    <div id="page_content" class="page_content">
                                                        <div class="ffpad fftop">
                                                            <div class="clear"></div>
                                                            <div id="headerrow2">
                                                                Especifique el Registro Académico del estudiante:
                                                             </div>
                                                        </div>
                                                        <br>
                                                        <div id="ff_content">
                                                            <div class="ff_pane" style="display: block;">
                                                                <table cellspacing="0" class="fffields">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td colspan="2" align="center">
                                                                            <div id="barra" class="Estilo1" style="display:none">
                                                                                <img name='imgBarra' src='../../resources/images/espera.gif' width='375' height='14'><br>
                                                                                <label>Espere mientras se procesa la información ...</label>
                                                                            </div>

                                                                            <div id="barraProceso" style="font-family:Arial, Helvetica, sans-serif; color:#003366; display:none">
                                                                                <br><br>
                                                                                <img name="espera" src="../../resources/images/espera.gif" width="360" heigth="12"><br>
                                                                                Espere un momento, la operación podría tomar algunos minutos . . .
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">
                                                                            <span class="formfilltranslation"><span id="_docwrite_formfill14">Registro Académico:</span></span>
                                                                        </td>
                                                                        <td class="page_col2">
                                                                            <input id="ff_cui" name="ff_cui" type="text" spellcheck="false" maxlength="9"  value="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">
                                                                            <span class="formfilltranslation"><span id="_docwrite_formfill14">Carrera:</span></span>
                                                                        </td>
                                                                        <td class="page_col2">
                                                                            <select id="career" name="career"  editable="false" panelheight="auto"
                                                                                    required="true">
                                                                                <option></option>
                                                                                <option value="2">02 Medicina Veterinaria</option>
                                                                                <option value="3">03 Zootecnia</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="buttons">
                                                        <input id="Buscar" name="Buscar" type="submit" value="Buscar certificados" class="nbtn rbtn btn_midi btn_exp_h okbutton"/>
                                                    </div>
                                                    <div class="clear"></div>
                                                </form>
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

<script language='JavaScript'>
    actualizarSelect({aPeriodo},{aAnio});
</script>
</body>
</html>

