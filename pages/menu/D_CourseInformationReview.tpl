<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="es">
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

    <script>
        $(function () {
            $('#dghorario').datagrid({
                pageSize: 20,
                collapsible: true,
                sortOrder: 'asc',
                url: 'D_CourseInformationReview.php?ajax=1&curso={aCurso}&seccion={aSeccion}&carrera={aCarrera}&index={aIndex}&msgnota={aMsgNota}',
                pagination: false
            });
        });
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
                                                <span class="page_title">Carga de notas</span>

                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>

                                            </div>
                                            <!-- INCLUDESCRIPT BLOCK : icontenido -->
                                        </div>
                                    </div>
                                    <div class="contenido-pie"></div>
                                </div>
                                <!-- F: CONTENIDO PRINCIPAL -->
                                <script type="text/javascript">
                                    function cellStyler(value,row,index){
                                        var fontColor;
                                        switch (row.type) {
                                            case "CM":
                                                fontColor = "#3d3d3d";
                                                break;
                                            case "LB":
                                                fontColor = "#0000FF";
                                                break;
                                            case "PR":
                                                fontColor = "#008000";
                                                break;
                                            case "TT":
                                                fontColor = "#FF00CC";
                                                break;
                                        }

                                        if (index % 2 == 0){
                                            return 'background: #E0E0E0;color: ' + fontColor + ';text-transform: capitalize;font-weight: lighter;';
                                        } else {
                                            return 'background: none repeat scroll 0% 0% rgba(255, 255, 255, 0.1);color: ' + fontColor + ';text-transform: capitalize;font-weight: lighter;';
                                        }
                                    }

                                </script>
                                <script>
                                    $(function () {
                                        $('#lbCargaManual').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para ir a la carga de notas manual</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#lbCargaxArchivo').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para ir a la carga de notas por archivo</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#lbDescargaArchivo').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para descargar archivo de asignados</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                     function getToolTipCss(element) {
                                         $(element).tooltip('tip').css({
                                             backgroundColor: '#666',
                                             color:'#848484',
                                             marginLeft:'8px',
                                             padding: '4px',
                                             border: '1px solid #a0a0a0',
                                             fontWeight:'bold',
                                             opacity: '.9',
                                             lineHeight:'14px',
                                             position: 'absolute',
                                             MozBorderRadius: '4px',
                                             borderRadius: '4px',
                                             MozBoxShadow: '0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset',
                                             WebkitBoxShadow: '0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset',
                                             boxShadow: '0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset',
                                             textShadow: '0 1px 0 rgba(255,255,255,.4)'

                                         });
                                     }
                                </script>
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


