
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Expires" CONTENT="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Cache-Control" content="must-revalidate">
    <meta http-equiv="Cache-Control" content="post-check=0">
    <meta http-equiv="Cache-Control" content="pre-check=0">
    <<!-- INCLUDESCRIPT BLOCK : ihead -->
    <link rel="stylesheet" type="text/css" href="../../libraries/js/DataTables-1.10.6/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/js/DataTables-1.10.6/extensions/FixedColumns/css/dataTables.fixedColumns.css">
    <script type="text/javascript" charset="utf8" src="../../libraries/js/DataTables-1.10.6/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="../../libraries/js/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" src="../../libraries/js/exporter.js"></script>

    <style>
        td.details-control {
            background: url('../../libraries/js/DataTables-1.10.6/media/images/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('../../libraries/js/DataTables-1.10.6/media/images/details_close.png') no-repeat center center;
        }
    </style>
    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }
    </script>

    <style type="text/css">
        <!--
        .Estilo2 {
            font-size: 9px;
            background-color: #CCCCCC;
        }
        .Estilo1 {
        }
        .Estilo3 {
            font-size: 12px;
            font-weight: normal;
        }
        -->
    </style>
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
                                                <span class="page_title">Pago de Examén de Recuperación</span>

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
                                                            <form action="" method="post">
                                                                <div class="easyui-panel" style="width:inherit;height:auto;">
                                                                    <div id="sitebody">
                                                                        <br><hr>
                                                                        <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                        <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                        <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                        <div class="siterow"><br/><div class="siterow-center"><span>PAGO DE EXÁMEN DE RECUPERACIÓN</span></div><br/></div>
                                                                        <!-- START BLOCK : b_asignados -->
                                                                        <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{aAnio}</span></div>
                                                                        <hr>
                                                                        <div id="dynheader" class="restrict_right"></div>
                                                                        <div id="dynbody" class="restrict_right">
                                                                            <div id="notesrow2">
                                                                                <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft'">
                                                                                    <table class="RAsig-table" id="CursosAsignar" name="CursosAsignar" align='center' width='100%' cellspacing='0' cellpadding='0' border='0'>
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>&nbsp;</th>
                                                                                            <th>CURSO</th>
                                                                                            <th>OBSERVACIÓN</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <!-- START BLOCK : despCurso -->
                                                                                        <tr>
                                                                                            <td><input name="{curso}" TYPE=CHECKBOX VALUE="{valor}" {habilita}>
                                                                                                <input name='{numcurso}' id='{numcurso}' type='hidden' value="{curso}">
                                                                                                <input name="totalcur" id="theValue" type="hidden" value="{totalcursos}">
                                                                                                <input name='{marcacurso}' id='{marcacurso}' type='hidden' value="{marca}">
                                                                                            </td>
                                                                                            <td>{nombre_curso}</td>
                                                                                            <td>{observacion}</td>
                                                                                        </tr>
                                                                                        <!-- END BLOCK : despCurso -->
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="ft" class="panel-footer" style="padding:5px;">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <hr>
                                                                            <br/>
                                                                            <div id="siteactions" class="siterow restrict_right">
                                                                                <input type="submit" name="btnContinuar" id="btnContinuar" value="{vSubmit}"  style="width: auto !important;" class="nbtn gbtn btn_midi"/>
                                                                                <input type="hidden" name="hContinuar" id="hContinuar"  size="5"/>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <br>

                                                                        <!-- END BLOCK : b_asignados -->

                                                                        <!-- START BLOCK : erroresManejables1 -->
                                                                        <div class='alert alert-{aTipoMensaje}'>
                                                                            <h4><i class='fa fa-info-circle fa-lg'></i> {aEncabezadoMensaje}:</h4>{mensaje}
                                                                        </div>
                                                                        <!-- START BLOCK : erroresManejables1 -->

                                                                        <!-- START BLOCK : b_sinasignacion -->
                                                                        <hr>
                                                                        <div id="dynheader" class="restrict_right"></div>
                                                                        <div id="dynbody" class="restrict_right">
                                                                            <div id="notesrow2">
                                                                                <textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">{aMensaje}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <!-- END BLOCK : b_sinasignacion -->
                                                                    </div>
                                                                </div>
                                                            </form>
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
<script>
</script>
</div>
</body>
</html>