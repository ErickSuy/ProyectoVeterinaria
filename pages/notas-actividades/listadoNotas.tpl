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


<script language="javascript">

function Aprobar() {
    valor = confirm("Al aprobar las notas de las actividades acá mostradas, ya no podrá realizar ninguna modificación.\n" + "¿Seguro que desea aprobar la información?");
    if (valor) {
        document.aprobarcurso.submit();
    }
    return false;
}

</script>

<script>
// desde el script de arriba es codigo de MIGUEL
function OcultarTodos()
{
 var elementos;
<!-- START BLOCK : DatoOcultar --> 
 elementos= document.getElementsByName("{carnet}");
      for (k = 0; k < elementos.length; k++)
	    elementos[k].style.display = "none";
<!-- END BLOCK : DatoOcultar --> 		
}

function VerOcultar(id)
{
 //  alert ("VerOcultar "+id);
//   var targetId, srcElement, targetElement;
   var elementos = document.getElementsByName(id);
//   document.getElementsByName
   var estaba=elementos[0].style.display;
//   OcultarTodos();
//alert (estaba);
   if (estaba == "none") 
   {
      for (k = 0; k < elementos.length; k++)
	    elementos[k].style.display = "";
   } 
   else 
   {
      for (k = 0; k < elementos.length; k++)
	    elementos[k].style.display = "none";
	  
    }
} // de la funcion VerOcultar

function imprimirReporte(){
    var altura=screen.height;
    var ancho =screen.width-150;
    var propiedades="top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes";
    propiedades=propiedades+",height="+altura;
    propiedades=propiedades+",width="+ancho;

    Ventana=document.open('../.././fw/controller/manager/NA_FinalReportPDF.php?{aParametros}','ZonasCurso',propiedades);
}


function generarExcel(){
    $('#dgTablaDatos tbody tr').each(function(){
        var row = table.row( $(this) ); 
        if ( row.child.isShown() ) {
            row.child.hide();
            $(this).removeClass('shown');
        }
    });
    tableToExcel('dgTablaDatos', 'Zonas');
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
                                                <span class="page_title">Carga de notas de actividades</span>

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

                                                            <!-- START BLOCK : listado -->
                                                            <div id="sitebody">
                                                                <br>
                                                                <hr>
                                                                <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                <div class="siterow"><br/><div class="siterow-center"><span>REPORTE DE NOTAS DE ACTIVIDADES</span></div><br/></div>
                                                                <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                                                <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span><span class="page_time_label"> Fecha: {vFecha}&nbsp;&nbsp;Hora:{vHora} </span></div>
                                                                <hr>
                                                                <div id="dynheader" class="restrict_right"></div>
                                                                <div class="ff_pane" style="display: block;">
                                                                    <table class='stripe row-border order-column  compact' id="dgTablaDatos" name="dgTablaDatos" align='left'  cellspacing="0" width="95.5%">
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
                                                                            <td align="center"><span class="note-font-style"> <font color="{color}">{txtTotalZona}&nbsp;</font> </span></td>
                                                                            
                                                                            <td hidden="true">{aDetalle}</td>
                                                                            <!-- END BLOCK : totalmagistral -->
                                                                        </tr>

                                                                        <!-- START BLOCK : detalleestudiante -->

                                                                            <!-- START BLOCK : detallemagistral -->

                                                                            <!-- END BLOCK : detallemagistral -->

                                                                        <!-- END BLOCK : detalleestudiante -->

                                                                        <!-- END BLOCK : filaestudiante -->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div>
                                                                    <hr>
                                                                    <!-- END BLOCK : botones -->
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <form name="aprobarcurso" id="aprobarcurso" action="listadoNotas.php?opcion=0" onsubmit="return Aprobar();">
                                                                                    <table border="0" align="left">
                                                                                        <tr>
                                                                                            <td>
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
                                                                            <td>
                                                                                <input type="button" style="width: auto !important;" class="nbtn gbtn btn_midi" onClick="generarExcel()" value="Exportar a Excel">
                                                                            </td>
                                                                            <td>
                                                                                <input type="button" style="width: auto !important;" class="nbtn gbtn btn_midi" onClick="imprimirReporte()" value="Generar Impresión">
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                    <br/>
                                                                    {InitTabla}
                                                                    <div align="justify">
                                                                        <p class="note-font-style">                                                                      
                                                                                  {notaInasistencia}
                                                                         </p>
                                                                    </div> 
                                                                    
                                                                    
                                                                    <script>
                                                                        function format ( d ) {
                                                                            return d[4];
                                                                        }
                                                                        $('#dgTablaDatos tbody').on('click', 'td.details-control', function () {
                                                                            var tr = $(this).closest('tr');
                                                                            var row = table.row( tr );

                                                                            if ( row.child.isShown() ) {
                                                                                row.child.hide();
                                                                                tr.removeClass('shown');
                                                                            }
                                                                            else {
                                                                                row.child( format(row.data()) ).show();
                                                                                tr.addClass('shown');
                                                                            }
                                                                        } );
                                                                    </script>
                                                                    <!-- END BLOCK : botones -->
                                                                </div>
                                                                <br>
                                                                <br>
                                                            </div>
                                                            <!-- END BLOCK : listado -->

                                                            <!-- START BLOCK : mensaje -->
                                                            <center>
                                                                {mensaje}
                                                            </center>
                                                            <!-- END BLOCK : mensaje -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    {RegresarActividades}
                                                    <!--<input type="submit" name="Regresar" id="Regresar" value="Regresar a listado de actividades" class="nbtn rbtn btn_midi btn_exp_h okbutton" onclick="location.href='{dirActividades}'"/>-->
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