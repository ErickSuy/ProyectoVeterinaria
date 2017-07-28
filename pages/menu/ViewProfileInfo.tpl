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
                                        <div id="ffframe">
                                            <div id="ffheader">
                                                <span id="pff_title" class="page_title">Información personal</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                            <table width="100%" align="center" border="0"
                                                                   cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td colspan="2" align="center"><img width="{anchoImg}" height="{altoImg}" src="../../fotos/{aFoto}.jpg" ></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:auto'>
                                                                <div title='General' style='padding:10px'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
                                                                            <tr id="titlerow">
                                                                                <td class="page_col1">Grupo</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_grupo" type="text" spellcheck="false" disabled="true" value="{aGrupo}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="last2" class="displaynone" style="display: table-row;">
                                                                                <td class="page_col1">Carrera</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_carrera" type="text" spellcheck="false" disabled="true" value="{aCarrera}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Carnet</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_carnet" type="text" spellcheck="false" disabled="true" value="{aCarnet}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="first">
                                                                                <td class="page_col1">Nombre</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_nombre" type="text" spellcheck="false" disabled="true" value="{aNombre}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="last">
                                                                                <td class="page_col1">Apellido</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_apellido" type="text" spellcheck="false" disabled="true" value="{aApellido}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Género</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_genero" type="text" spellcheck="false" disabled="true" value="{aGenero}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Fecha de Nacimiento</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_fechanacimiento" type="text" pellcheck="false" disabled="true" value="{aFechaNacimiento}">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div title='Formación' style='padding:10px'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td class="page_col1">Carrera</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_carrerabr" type="text" spellcheck="false" disabled="true" value="{aCarreraBr}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Establecimiento</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_establecmientobr" type="text" spellcheck="false" disabled="true" value="{aEstablecimiento}">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div title='Dirección' style='padding:10px'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td class="page_col1">Domicilio</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_domicilio" type="text" spellcheck="false" disabled="true" value="{aDomicilio}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Lugar de Nacimiento</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_lugarnac" type="text" spellcheck="false" disabled="true" value="{aLugarNacimiento}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Nacionalidad</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_nacionalidad" type="text" spellcheck="false" disabled="true" value="{aNacionalidad}">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div title='Contacto' style='padding:10px'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <div>
                                                                            <br>
                                                                            <table cellspacing="0" class="fffields">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="page_col1">Correo electrónico principal</td>
                                                                                    <td class="page_col2">
                                                                                        <input id="ff_correop" type="text" spellcheck="false" disabled="true" value="{aCorreoP}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="page_col1">Correo electrónico alterno</td>
                                                                                    <td class="page_col2">
                                                                                        <input id="ff_correoa" type="text" spellcheck="false" disabled="true" value="{aCorreoA}">
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <br><br><br>
                                                                            <div id="phones">
                                                                                <div class="floatleft">
                                                                                    <div class="ff_title">Número de teléfono domiciliar principal</div>
                                                                                    <div class="ff_border">
                                                                                        <br>
                                                                                    <span class="displaynone" style="display: inline;">
                                                                                        <input id="ff_telprincipal" type="text" spellcheck="false" class="ff_int" disabled="disabled" value="{aTelP}">
                                                                                    </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="floatright">
                                                                                    <div class="ff_title">Número de teléfono personal principal</div>
                                                                                    <div class="ff_border">
                                                                                        <br>
                                                                                    <span class="displaynone" style="display: inline;">
                                                                                        <input id="ff_celprincipal" type="text" spellcheck="false" class="ff_int" disabled="disabled" value="{aCelP}">
                                                                                    </span>
                                                                                    </div>
                                                                                </div>
                                                                                <br><br>
                                                                                <div class="clear" id="spacer"></div>
                                                                                <div class="floatleft">
                                                                                    <div class="ff_title">Número de teléfono domiciliar alterno</div>
                                                                                    <div class="ff_border">
                                                                                        <br>
                                                                                    <span class="displaynone" style="display: inline;">
                                                                                        <input id="ff_telalterno" spellcheck="false" type="text" class="ff_int" disabled="disabled" value="{aTelA}">
                                                                                    </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="floatright">
                                                                                    <div class="ff_title">Número de teléfono personal alterno</div>
                                                                                    <div class="ff_border">
                                                                                        <br>
                                                                                    <span class="displaynone" style="display: inline;">
                                                                                        <input id="ff_celalterno" spellcheck="false" type="text" class="ff_int" disabled="disabled" value="{aCelA}">
                                                                                    </span>
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