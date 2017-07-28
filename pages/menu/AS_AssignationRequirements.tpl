<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script type="text/javascript">
        function Cancelar() {
            window.location = 'ViewProfileInfo.php';
        }

        function Siguiente() {
            window.location = 'AS_AssignationSelect.php?paso=1';
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
                                                                <div title='1. Requisitos generales'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <div class="easyui-panel" style="width:inherit;height:auto;">
                                                                            <br>
                                                                            <table  width="100%" align="center" class="fffields">
                                                                                <tbody>
                                                                                <!-- START BLOCK : MENSAJES -->
                                                                                <tr class="{aClass}">
                                                                                    <td class="page_col1" align="center">
                                                                                        <i class="{aIcono}"></i>
                                                                                    </td>
                                                                                    <td class="page_col2">
                                                                                        {aMensaje}
                                                                                    </td>
                                                                                </tr>
                                                                                <!-- END BLOCK : MENSAJES -->
                                                                                </tbody>
                                                                            </table>
                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td align="center">
                                                                                        <table border="0" align="center" cellspacing="0" cellpadding="0">
                                                                                            <tr>
                                                                                                <td width="66">
                                                                                                    <img src="../../resources/images/1_on.gif" width="34" height="34" border="1" class="step-img">
                                                                                                </td>
                                                                                                <td width="66">
                                                                                                    <img src="../../resources/images/2_off.gif" width="34" height="34" border="1" class="step-img">
                                                                                                </td>
                                                                                                <td width="66">
                                                                                                    <img src="../../resources/images/3_off.gif" width="34" height="34" border="1" class="step-img">
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
                                                                            <div id="menu_acc" class="easyui-accordion" style="width:100%;">
                                                                                <div title="Importante" iconCls="fa fa-question-circle fa-lg" style="overflow:auto;padding:10px;">
                                                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class='alert alert-warning'>
                                                                                                    <h4><i class='fa fa-warning'></i> TOMAR EN CUENTA</h4>
                                                                                                    <ul>
                                                                                                        <li> Solamente se permiten 3 ingresos al sistema de
                                                                                                            asignación de cursos por Internet.
                                                                                                        </li>
                                                                                                        <li> Para continuar con el proceso de asignación de cursos,
                                                                                                            clic en <strong>"Siguiente"</strong>.
                                                                                                        </li>
                                                                                                        <li> Si no cumple alguno de los requisitos, clic en <strong>"Cancelar"</strong>,
                                                                                                            solvente su situación y vuelva a intentarlo.
                                                                                                        </li>
                                                                                                        <li> Si en el último paso se le muestran cursos sin asignar,
                                                                                                            acuda al departamento de Informática para que se los
                                                                                                            pueda asignar.
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    {aButton}
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