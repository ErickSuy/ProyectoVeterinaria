<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
                                                                <td colspan="2" align="center">
                                                                    <!--
                                                                    <iframe width="{anchoImg}" height="{altoImg}"
                                                                            src="../../fw/view/ProfilePicture.php"
                                                                            frameborder="0" scrolling="no"></iframe>
                                                                            -->
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <br>
                                                <div id="ff_content">
                                                    <div class="ff_pane" style="display: block;">
                                                        <div class='easyui-tabs' style='width:auto;height:auto'>
                                                            <div title='General' style='padding:10px'>
                                                                <div class="ff_pane" style="display: block; padding: 13px;">
                                                                    <table cellspacing="0" class="fffields">
                                                                        <tbody>
                                                                        <tr id="titlerow">
                                                                            <td class="page_col1">Grupo</td>
                                                                            <td class="page_col2" colspan="2">
                                                                                <input id="ff_grupo" type="text" spellcheck="false" disabled="true" value="{aGrupo}" style="width: 380px !important;" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Reg. Personal</td>
                                                                            <td class="page_col2" colspan="2">
                                                                                <input id="ff_personal" type="text" spellcheck="false" disabled="true" value="{aPersonal}" style="width: 380px !important;" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="first">
                                                                            <td class="page_col1">Titularidad</td>
                                                                            <td class="page_col2" colspan="2">
                                                                                <input id="ff_titularidad" type="text" spellcheck="false" disabled="true" value="{aTitularidad}" style="width: 380px !important;" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="first">
                                                                            <td class="page_col1">Nombre</td>
                                                                            <td class="page_col2" colspan="2">
                                                                                <input id="ff_nombre" type="text" spellcheck="false" disabled="true" value="{aNombre}" style="width: 380px !important;" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="last">
                                                                            <td class="page_col1">Apellido</td>
                                                                            <td class="page_col2" colspan="2">
                                                                                <input id="ff_apellido" type="text" spellcheck="false" disabled="true" value="{aApellido}" style="width: 380px !important;" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Género</td>
                                                                            <td class="page_col2">
                                                                                <imput id="ff_genero" name="ff_genero" type="text" disabled="true" value="{aGenero}" style="width: 380px !important;" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Fecha de nacimiento</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_fechanacimiento" name="ff_fechanacimiento" type="text" style="text-transform: uppercase !important; width: 380px !important;" size="15" disabled="true" value="{aFechaNacimiento}" class="readOnlyText"/>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Cédula de vecindad</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_cedula" name="ff_cedula" style="text-transform: uppercase !important; width: 380px !important;" disabled="true" type="text"  value="{aCedula}" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Extendida en</td>
                                                                            <td class="page_col2">
                                                                                <table align="left" border="0" cellpadding="0" cellspacing="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input id="ff_extdepto" name="ff_extdepto" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aExtDepto}" class="readOnlyText">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input id="ff_extmunicipio" name="ff_extmunicipio" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aExtMunicipio}" class="readOnlyText">
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">DPI</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_dpi" name="ff_dpi" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aDpi}" class="readOnlyText">
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
                                                                            <td class="page_col1">Dirección</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_direccion" type="text" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aDereccion}" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Municipio</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_municipio" type="text" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aMunicipio}" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Departamento</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_departamento" type="text" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aDepartamento}" class="readOnlyText">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="page_col1">Nacionalidad</td>
                                                                            <td class="page_col2">
                                                                                <input id="ff_nacionalidad" type="text" style="text-transform: uppercase !important; width: 380px !important;" type="text" disabled="true" value="{aNacionalidad}" class="readOnlyText">
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
                                                                                <td class="page_col1">Correo electrónico</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_correop" name="ff_correop"  class="easyui-validatebox" value="{aCorreoP}" style="width: 380px !important;" disabled="true" class="readOnlyText">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Número de teléfono</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_telp" name="ff_telp" type="text" class="easyui-numberbox" value="{aTelP}" style="width: 380px !important;" disabled="true" class="readOnlyText">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Número de celular</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_celp" name="ff_celp" type="text" class="easyui-numberbox" value="{aCelP}" style="width: 380px !important;" disabled="true" class="readOnlyText">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
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