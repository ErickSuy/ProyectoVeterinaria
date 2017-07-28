<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    
    <script type="text/javascript">

        function redireccionar()
        {
            document.location.href = "/pages/includes/AR_PrintPdf.php";
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
                                        <div id="ffframe">
                                            <div id="ffheader">
                                                <span id="pff_title" class="page_title">Orden de Pago</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <!-- START BLOCK : BOLETA -->
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <br>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:auto'>
                                                                <div title='General' style='padding:10px'>
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
                                                                            <tr id="titlerow">
                                                                                <td class="page_col1">Carnet</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_grupo" type="text" spellcheck="false" disabled="true" value="{aCarnet}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="last2" class="displaynone" style="display: table-row;">
                                                                                <td class="page_col1">Orden de Pago</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_carrera" type="text" spellcheck="false" disabled="true" value="{aOrden}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Monto (Q.)</td>
                                                                                <td class="page_col2">
                                                                                    <input id="ff_carnet" type="text" spellcheck="false" disabled="true" value="{aMonto}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="titlerow" >
                                                                                <td class="page_col1"></td>
                                                                                <td>
                                                                                    <input type="button" id="boton" value="Imprimir Boleta" onclick="javascript:redireccionar();"/>
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
                                                <div class="clear"></div>
                                            </div>
                                            <!-- END BLOCK : BOLETA -->
                                            <!-- START BLOCK : MENSAJES -->
                                            <table  width="100%" align="center" class="fffields">
                                                <tbody>
                                                
                                                <tr class="{aClass}">
                                                    <td class="page_col1" align="center">
                                                        <i class="{aIcono}"></i>
                                                    </td>
                                                    <td class="page_col2">
                                                        {aMensaje}
                                                    </td>
                                                </tr>
                                                
                                                </tbody>
                                            </table>
                                            <!-- END BLOCK : MENSAJES -->
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