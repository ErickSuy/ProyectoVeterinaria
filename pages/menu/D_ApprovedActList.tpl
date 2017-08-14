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

        function imprimirActa() {
            var altura=screen.height;
            var ancho =screen.width-150;
            var propiedades="top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes";
            propiedades=propiedades+",height="+altura;
            propiedades=propiedades+",width="+ancho;
            Ventana=document.open('../.././fw/controller/manager/COAC_CourseActPDF.php?{aParametros}','NotasCurso',propiedades);
        }
    </script>
        
    <script language="javascript">

    function Aprobar() {
        valor = confirm("Al aprobar las notas de las actividades acá mostradas, ya no podrá realizar ninguna modificación.\n" + "¿Seguro que desea aprobar la información?");
        if (valor) {
            document.aprobarcurso.submit();
        }
        return false;
    }
    
    function AsignarEstudiantes() {
        valor = confirm("Desea agregar las actividades ya creadas a los estudiantes asignados recientemente?");
        if (valor) {
            document.asignarEstudiante.submit();
        }
        return false;
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

                                                                <div class="siterow-center"><span>NOTAS DE ACTA DE ASIGNATURA</span>
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
                                                                    <table class="RAsig-table" align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                        <thead>
                                                                        <tr>
                                                                            <th width='5%'>No.</th>
                                                                            <th width='5%'>CARNET</th>
                                                                            <th width='50%'>NOMBRE</th>
                                                                            <th width='10%'>&nbsp;<!--Laboratorio--></th>
                                                                            <th width='10%' align="center">ZONA</th>
                                                                            <th width='10%' align="center">EXAMEN <br>FINAL</th>
                                                                            <th width='10%' align="center">NOTA <br>FINAL</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- START BLOCK : LISTADO -->
                                                                        <tr>
                                                                            <td> {Numero}</td>
                                                                            <td> {Carne}</td>
                                                                            <td> {Apellido}, {Nombre}
                                                                                  {Congelando}</td>
                                                                            <td> &nbsp;<!--{Laboratorio}--></td>
                                                                            <td> {Zona}</td>
                                                                            <td> {Examen}</td>
                                                                            <td><span class="note-font-style">{NotaFinal}</span></td>
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
                                                                          
                                                                        </td>
                                                                        <td>
                                                                            <!-- START BLOCK : ACTACURSO -->
                                                                            <input type="button" style="width: auto !important;" class="nbtn gbtn btn_midi" onClick="imprimirActa()" value="Generar Acta">
                                                                            <!-- END BLOCK : ACTACURSO -->
                                                                        </td>
                                                                        <td>
                                                                            <input type="button" style="width: auto !important;" class="nbtn gbtn btn_midi" onClick="imprimirReporte()" value="Generar Impresión">
                                                                        </td>
                                                                         <td>
                                                                                <form name="asignarEstudiante" id="asignarEstudiante" action="D_ApprovedActList.php?" onsubmit="return AsignarEstudiantes();">
                                                                                    <table border="0" align="left">
                                                                                        <tr>
                                                                                            <td>
                                                                                                <input name="opcion" type="hidden" value="2" />
                                                                                                <input name="txtCurso" type="hidden" value="{txtCurso}" />
                                                                                                <input name="txtCarrera" type="hidden" value="{txtCarrera}" />
                                                                                                <input name="txtPeriodo" type="hidden" value="{txtPeriodo}" />
                                                                                                <input name="txtAnio" type="hidden" value="{txtAnio}" />
                                                                                                <label>
                                                                                                    <input type="submit" style="width: auto !important;" class="nbtn grbtn btn_midi" name="button" id="button" value="Asignar actividades Estudiantes" >
                                                                                                </label>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </form>
                                                                            </td>
                                                                    </tr>
                                                                </table>
                                                               
                                                                <center>
                                                                    {mensajeROOT}
                                                                </center>
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <table class='RAsig-table' cellpadding="0" cellspacing="0" width="95%" align="center">
                                                                <thead>
                                                                <tr>
                                                                    <th colspan="3"><div align="center" >RESUMEN DE NOTAS</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>Aprobados&nbsp;</td>
                                                                    <td><span class="note-font-style">{Aprobados}&nbsp;</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Reprobados&nbsp;</td>
                                                                    <td><span class="note-font-style">{Perdidos}&nbsp;</span></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                                
                                                        </div>
                                                                <!-- listado de zonas finales, si existiese el caso de que el catedratico no aprobo las notas dentro del periodo -->
                                                        <!-- START BLOCK : listadoZonas -->
                                                        
                                                            <div id="sitebody">
                                                                <div class="siterow"><br/><div class="siterow-center"><span>LISTADO DE ZONAS "Sin Aprobar"</span></div><br/></div>                                                                
                                                                <br>
                                                                <hr>
                                                                <div id="dynheader" class="restrict_right"></div>
                                                                <div class="ff_pane" style="display: block;">
                                                                    <table class='RAsig-table' id="dgTablaDatos" name="dgTablaDatos" align='left'  cellspacing="0" width="95.5%">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>CARNET</th>
                                                                            <th>NOMBRE</th>
                                                                            <th ><div align="center">TOTAL ZONA</div></th>
                                                                            <th hidden="true">D</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- START BLOCK : filaestudiante -->
                                                                        <tr>
                                                                            <td>{txtContador}&nbsp; </td>
                                                                            <td>{txtLinkCarnet}&nbsp; </td>
                                                                            <td>{txtNombre}&nbsp;</td>
                                                                            <!-- START BLOCK : totalmagistral -->                                                                            
                                                                            <td align="center"><span class="note-font-style"> <font color="{color}">{txtTotalZona}&nbsp;</font></td>
                                                                            <!-- END BLOCK : totalmagistral -->                                                                            
                                                                        </tr>
                                                                        <!-- END BLOCK : filaestudiante -->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div>
                                                                    <hr>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <form name="aprobarcurso" id="aprobarcurso" action="D_ApprovedActList.php?" onsubmit="return Aprobar();">
                                                                                    <table border="0" align="left">
                                                                                        <tr>
                                                                                            <td>
                                                                                                <input name="opcion" type="hidden" value="1" />
                                                                                                <input name="txtCurso" type="hidden" value="{txtCurso}" />
                                                                                                <input name="txtLaSeccion" type="hidden" value="{txtLaSeccion}" />
                                                                                                <input name="txtCarrera" type="hidden" value="{txtCarrera}" />
                                                                                                <input name="txtPeriodo" type="hidden" value="{txtPeriodo}" />
                                                                                                <input name="txtAnio" type="hidden" value="{txtAnio}" />
                                                                                                <input name="txtRegPer" type="hidden" value="{txtRegPer}" />
                                                                                                <label>
                                                                                                    <input type="{tipoaprobar}" style="width: auto !important;" class="nbtn grbtn btn_midi" name="button" id="button" value="Aprobar Notas de Actividades" >
                                                                                                </label>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </form>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                    </table>
                                                                                                
                                                                    <br/>                        
                                                                </div>
                                                                <br>
                                                                <br>
                                                            </div>
                                                            <!-- START BLOCK : mensaje -->
                                                            <center>
                                                                {mensaje}
                                                            </center>
                                                            <!-- END BLOCK : mensaje -->
                                                        <!-- END BLOCK : listadoZonas -->
                                                                <!-- fin de listado de zonas finales-->
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <input type="submit" name="Regresar" id="Regresar" title="COAC_ActCourseList.php?anio={vAnio}&periodo={periodo}"
                                                           value="Regresar a listado de cursos"
                                                           class="nbtn rbtn btn_midi btn_exp_h okbutton"
                                                           onclick='javascript:redireccionar("COAC_ActCourseList.php?anio={vAnio}&periodo={periodo}");'/>
                                                    <!-- D_CourseList -->
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