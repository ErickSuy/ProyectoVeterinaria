<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

        function redireccionar(nuevadireccion)
        {
            location.href=nuevadireccion;
        }

        function muestraespera() {
            document.Bloque.espera.heigth = "12";
            document.Bloque.espera.width = "360";
            document.Bloque.leyenda.heigth = "360";
            document.Bloque.leyenda.width = "360"
        }

        function Aprobar() {
            valor = confirm("Recoger el acta del curso en Control Académico en la fecha estipulada para firmarla y así oficializar la información.\n" + "¿Seguro que desea aprobar la información?\n Recuerde que al aprobar la información ya no podrá realizar ninguna modificación.");
            if (valor) {
                document.Bloque.aprobaracta.disabled = true;
//    muestraespera();
                return true;
            }
            return false;
        }
    </script>

    <script>
        function imprimirReporte(){
            var altura=screen.height;
            var ancho =screen.width-150;
            var propiedades="top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes";
            propiedades=propiedades+",height="+altura;
            propiedades=propiedades+",width="+ancho;
            Ventana=document.open('../.././fw/controller/manager/D_ApproveActPDF.php?{aParametros}','NotasCurso',propiedades);
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
                                                <span class="page_title">Carga de notas de exámenes finales</span>

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
                                                            <form name='Bloque' action="D_ActApprovalConfirm.php" method="post"
                                                                  onSubmit="return Aprobar();">
                                                                <div id="sitebody">
                                                                    <br>
                                                                    <hr>
                                                                    <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span>
                                                                    </div>
                                                                    <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span>
                                                                    </div>
                                                                    <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span>

                                                                        <div style="display:inline; margin-left:170px;"></div>
                                                                    </div>
                                                                    <div class="siterow"><br/>

                                                                        <div class="siterow-center"><span>REPORTE DE NOTAS DE EXAMEN FINAL</span>
                                                                        </div>
                                                                        <br/></div>
                                                                    <div class="siterow"><span class="page_label">Del curso: </span><span
                                                                                class="underline_label">{vCurso}
                                                                            - {vNombre}</span><span
                                                                                class="page_label">de la carrera: </span><span
                                                                                class="underline_label">{vCarrera}</span>
                                                                    </div>
                                                                    <div class="siterow"><span
                                                                                class="page_label">Correspondientes a: </span><span
                                                                                class="underline_label">{vPeriodo}</span><span
                                                                                class="page_label">de: </span><span
                                                                                class="underline_label">{vAnio}</span><span
                                                                                class="page_time_label"> Fecha: {vFecha}
                                                                            &nbsp;&nbsp;Hora:{vHora} </span></div>
                                                                    <hr>
                                                                    <div id="dynheader" class="restrict_right">
                                                                    </div>
                                                                    <div id="dynbody" class="restrict_right ff">
                                                                        <div id="notesrow2">
                                                                            <table class='RAsig-table' align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                                <thead>
                                                                                <tr>
                                                                                    <th width="5%">No.</th>
                                                                                    <th width="5%" align="center">CARNET</th>
                                                                                    <th width="40%">NOMBRE</th>
                                                                                    <th width="10%" align="center">&nbsp;<!--Laboratorio--></th>
                                                                                    <th width="10%" align="center">ZONA</th>
                                                                                    <th width="15%" align="center">EXAMEN FINAL</th>
                                                                                    <th width="15%" align="center">NOTA FINAL</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <!-- START BLOCK : LISTADO -->
                                                                                <tr>
                                                                                    <td>{Numero}.</td>
                                                                                    <td>{Carne}</td>
                                                                                    <td>{Apellido}, {Nombre}
                                                                                         {Congelando}</td>
                                                                                    <td>&nbsp;<!--{Laboratorio}--></td>
                                                                                    <td width="10%" align="center">{Zona}</td>
                                                                                    <td width="15%" align="center">{Examen}</td>
                                                                                    <td width="15%" align="center"><span class="note-font-style"> {NotaFinal}</span></td>
                                                                                </tr>
                                                                                <!-- END BLOCK :   LISTADO -->
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <hr>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <table border="0" align="left">
                                                                                        <tr>
                                                                                            <td>
                                                                                                <input type="submit" style="width: auto !important;" class="nbtn grbtn btn_midi" name="aprobaracta" id="aprobaracta" value="Aprobar Notas Finales" title="Aprueba la Informaci&oacute;n ingresada" >
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="button" style="width: auto !important;" class="nbtn gbtn btn_midi" onClick="imprimirReporte()" value="Generar Impresión">
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                </div>
                                                                <div class="alert alert-warning">
                                                                    <h4><i class="fa fa-warning"></i> IMPORTANTE</h4>
                                                                    No olvide pasar a recoger el <b>Acta Impresa</b> del curso en la fecha estipulada</b> a <b>Control Académico</b>, para firmarla<br/>
                                                                    y así oficializar la información que se ingresó en el Sistema de Carga de Notas en línea.
                                                                </div>
                                                                <div align="center">
                                                                    <!-- INCLUDESCRIPT BLOCK : imensajenotas -->
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <input type="submit" name="Regresar" id="Regresar"
                                                           value="Regresar a listado de cursos"
                                                           class="nbtn rbtn btn_midi btn_exp_h okbutton"
                                                           onclick='javascript:redireccionar("D_CourseList.php?anio={vAnio}&periodo={periodo}");'/>
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