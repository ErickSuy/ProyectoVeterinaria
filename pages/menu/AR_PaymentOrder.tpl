<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?xml version="1.0" encoding="utf-8"?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />


    <!-- INCLUDESCRIPT BLOCK : bloqueImpresionOrden  -->
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <meta http-equiv="Expires" CONTENT="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Cache-Control" content="must-revalidate">
    <meta http-equiv="Cache-Control" content="post-check=0">
    <meta http-equiv="Cache-Control" content="pre-check=0">



    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
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
                                                                        <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{aPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{aAnio}</span></div>
                                                                        <hr>
                                                                        <div id="dynheader" class="restrict_right"></div>
                                                                        <div id="dynbody" class="restrict_right">
                                                                            <div id="notesrow2">
                                                                                <div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:'#ft'">
                                                                                    <table class="RAsig-table" id="CursosAsignar" name="CursosAsignar" align='center' width='100%' cellspacing='0' cellpadding='0' border='0'>
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>No</font></th>
                                                                                            <th>CURSO</th>
                                                                                            <th>NOMBRE</th>
                                                                                            <th>GRUPO</th>
                                                                                            <th>Carrera</th>
                                                                                            <th>OBS.</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <!-- START BLOCK : lisAsigCurso -->
                                                                                        <tr>
                                                                                            <td>{vNum}</td>
                                                                                            <td>{vCurso}</td>
                                                                                            <td>{vNomCurso}</td>
                                                                                            <td>{vSeccion}</td>
                                                                                            <td>{vCarrera}</td>
                                                                                            <td>{vObserv}</td>
                                                                                        </tr>
                                                                                        <!-- END BLOCK : lisAsigCurso -->
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
                                                                        </div>
                                                                        <br>
                                                                        <br>

                                                                        <table class='RAsig-table' cellpadding="0" cellspacing="0" width="95%" align="center">
                                                                            <thead>
                                                                            <tr>
                                                                                <th colspan="5"><div align="center">LISTADO DE ÓRDENES DE PAGO {vperiodo}</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>N&uacute;mero Orden de pago</th>
                                                                                <th>Fecha de Orden</th>
                                                                                <th>Monto Orden</th>
                                                                                <th>Estado de la Orden</th>
                                                                                <th>&nbsp;</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <!-- START BLOCK : lista_ordenes -->
                                                                            <tr>
                                                                                <td><div align="center">{vorden}</div></td>
                                                                                <td><div align="center">{vfechaorden}</div></td>
                                                                                <td><div align="center">{vmontoorden}</div></td>
                                                                                <td><div align="center">{vmensajeEstado}</div></td>
                                                                                <td><div align="center">{vlinkImprimir}</div></td>
                                                                            </tr>
                                                                            <!-- END BLOCK : lista_ordenes -->
                                                                            </tbody>
                                                                        </table>
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