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
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <!--<link href="../coac/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../coac/assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>-->
   <!-- <link rel="stylesheet" href="../../resources/css/estiloTabs.css">-->
    
    <!-- START BLOCK : jquery1_12 -->
    <!-- este codigo se agrega unicamente con las tabs que trabajan bajo esta version, 
    para evitar conflictos de version se agrega unicamente cuando se requieren. 
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- END BLOCK : jquery1_12 -->
    
    <script type="text/javascript" src="../../libraries/easyui/extensions/datagrid-filter.js"></script>
    <script type="text/javascript" src="../../libraries/easyui/extensions/datagrid-editable.js"></script>
    <script type="text/javascript" src="../../libraries/easyui/extensions/datagrid-cellediting.js"></script>
    <script type="text/javascript" src="archivosJavascript/crearActividad.js"></script>
  <!--  <script>
  $( function() {
    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  } );
  </script>-->
  
    
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
                        <!-- START BLOCK : menuCalendario -->
                        <table class='RAsig-table' id="tablaCalendario" cellpadding="0" cellspacing="0" width="95%" align="center">
                            <thead>
                                <tr>
                                    <th colspan="3"><div align="center" >ACTIVIDADES CALENDARIO</div></th>
                                </tr>
                                <tr>
                                    <td align="center">Actividad</td>
                                    <td align="center">Inicio</td>
                                    <td align="center">Fin</td>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- START BLOCK : actividadCalendario -->
                                <tr>
                                    <td>{nombreActividad}</td>
                                    <td {colspan}>{fechaInicio}</td>
                                    <td>{fechaFin}</td>
                                </tr>
                                <!-- END BLOCK : actividadCalendario -->
                            </tbody>
                        </table>
                        <!-- END BLOCK : menuCalendario -->
                        
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
                                                    <div id="sitebody">
                                            <br>
                                            <hr>
                                            <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                            <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                            <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                            <div class="siterow"><br/><div class="siterow-center"><span>{tituloSeccion}</span></div><br/></div>
                                            <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                            <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span><span class="page_time_label"> Fecha: {vFecha}&nbsp;&nbsp;Hora:{vHora} </span></div>
                                            <hr>
                                            <div id="dynheader" class="restrict_right"></div>
                                            <div id="dynbody" class="restrict_right ff">
                                                <!-- START BLOCK : alertZona -->
                                                <div class="{tipo}">
                                                    <p class="text-center">{mensaje}  <strong>{mensajeStrong}</strong></p>
                                                </div>
                                                <!-- END BLOCK : alertZona -->    
                                                

<!-- ACORDION TEMPORAL - MUNU -->
                                            <div id="menuActividadesTmp" class="easyui-accordion" >
                                                    
                                                    <!-- START BLOCK : docenciaTmp -->
                                                    <div title="{nombreDocencia} <span class='notificacion' align='right'>{noActividadesDocencia}</span>" iconCls="{iconoDocencia}" style="overflow:auto;padding:10px;" >

                                                        <table class="reporte-cursos" width="100%" cellspacing="0" cellpaddingw="0"  align="center">
                                                            <thead>
                                                                <tr><td align="Center">Actividad</td><td align="Center">Ponderacion</td><td align="Center">Fecha</td><td align="center">Accion</td></tr>
                                                            </thead>
                                                            <tbody>

                                                                <!-- START BLOCK : actividadTmp -->
                                                                <tr>                                                                      
                                                                    <td align="left"><span>{nombreActividad}</span></td>
                                                                    <td align="center"><span>{PonderacionActividad}</span></td>
                                                                    <td align="center"><span>{FechaActividad}</span></td>
                                                                    <td align="right">
                                                                        <a href="#" class="easyui-menubutton" data-options="menu:'#mm{noActividad}'"><i class="fa fa-pencil"></i></a>
                                                                        <div id="mm{noActividad}" style="width:150px;">
                                                                            <div iconCls="fa fa-pencil-square-o" title="Editar de notas" onclick="window.location.href ='{refEditarActividad}'">Editar Actividad</div>
                                                                            <div iconCls="fa fa-spinner" title="Cargar Notas" onclick="window.location.href ='{refCargarActividad}'">Cargar Notas</div>
                                                                            <div iconCls="fa fa-eraser" title="Borrar Actividad" text="{redireccionarPag}" value="{refBorrarActividad}" href="javascript:void(0)" onclick="borrarActividad(this);">Borrar Actividad</div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <!-- END BLOCK : actividadTmp -->                                                                    

                                                            </tbody>
                                                        </table>
                                                                        <br>
                                                                        <br>
                                                        <div align='right'>
                                                            <a id="nuevaactividad" href="{vermasDocencia}" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-plus fa-lg"></i><span>&nbsp;&nbsp;Ver Mas</span></a>                                                            
                                                        </div>

                                                    </div>
                                                    <!-- END BLOCK : docenciaTmp -->
<!-- ACORDION DOCENCIA - MENU FIN --> 
                                                </div>

<!-- DETALLE DE DOCENCIA - TABS -->                                                        
                                               <!-- START BLOCK : tabDetalle-->
                                                <div id="tabDetalle" class='easyui-tabs' data-options="headerWidth:200" style='width:100%;height:auto;'>
                                                    <!-- START BLOCK : nuevaTab-->
                                                    <div title='{tituloTab}' style="padding: 10px;">
                                                        <div align="center">
                                                        <table width="51%"  style="border-collapse: separate; border-spacing: 10px;" >
                                                            <tr>
                                                                <td width="17%" align="center"><div style="width: 30px; height: 30px;  border-radius: 25px; border-style: solid; border-width: medium; background: rgba(65, 244, 110,.5);"></div></td>
                                                                <td width="17%" align="center"><div style="width: 30px; height: 30px;  border-radius: 25px; border-style: solid; border-width: medium; background: rgba(220, 244, 66,.5);"></div></td>
                                                                <td width="17%" align="center"><div style="width: 30px; height: 30px;  border-radius: 25px; border-style: solid; border-width: medium; background: rgba(255,0,0,0.5);"></div></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="17%" align="center"><b>Excelente</b></td>
                                                                <td width="17%" align="center"><b>Bueno</b></td>
                                                                <td width="17%" align="center"><b>Malo</b></td>
                                                            </tr>
                                                        </table>
                                                            
                                                       
                                                        <!-- START BLOCK : tablaContenidoTab -->
                                                        <table class='Rasig-table' cellpadding="0" cellspacing="0" width="100%" align="center">
                                                            <thead>                                                            
                                                            <!-- START BLOCK : tituloColumna -->
                                                            <td align="center"><b>{textotituloColumna}</b></td>
                                                            <!-- END BLOCK : tituloColumna -->
                                                            </thead>
                                                            <tbody>
                                                                <!-- START BLOCK : NuevaFila -->
                                                                <tr>
                                                                    <!-- START BLOCK : NuevaColumna -->
                                                                    <td style="background: {color};" {align}>{contenidoColumna}</td>
                                                                    <!-- START BLOCK : NuevaColumna -->
                                                                    <!-- START BLOCK : menuFila -->
                                                                    <td align="right">
                                                                                <a href="#" class="easyui-menubutton" data-options="menu:'#mm{noActividad}'"><i class="fa fa-pencil"></i></a>
                                                                                <div id="mm{noActividad}" style="width:150px;">
                                                                                    <div iconCls="fa fa-pencil-square-o" title="Editar de notas" onclick="window.location.href ='{refEditarActividad}'">Editar Actividad</div>
                                                                                    <div iconCls="fa fa-spinner" title="Cargar Notas" onclick="window.location.href ='{refCargarActividad}'">Cargar Notas</div>
                                                                                    <div iconCls="fa fa-eraser" title="Borrar Actividad" text="{redireccionarPag}" value="{refBorrarActividad}" href="javascript:void(0)" onclick="borrarActividad(this);">Borrar Actividad</div>
                                                                                </div>
                                                                    </td>
                                                                    <!-- END BLOCK : menuFila -->
                                                                </tr>
                                                                <!-- END BLOCK : NuevaFila -->                                                            
                                                            </tbody>
                                                        </table>
                                                        <!-- END BLOCK : tablaContenidoTab --> 
                                                         </div>
                                                                                <br>
                                                                                <br>
                                                    {cargarNotas}
                                                    </div>
                                                    <!-- END BLOCK :  nuevaTab-->
                                                </div>
                                                <!--parrafo nota -->
                                                <div align="justify">
                                                    <p class="note-font-style">
                                                         &nbsp; &nbsp;* EXCELENTE [67..100]%<br>
                                                         &nbsp; &nbsp;* BUENO [34..66]%<br>
                                                         &nbsp; &nbsp;* MALO [0..33]%<br>
                                                         &nbsp; &nbsp; &nbsp; &nbsp;-- Los rangos mostrados son un procentaje obtenido del 100% divido en 3 partes iguales<br>
                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;las tres partes hacen (excelente, bueno, malo), para hacer referencia a:<BR>
                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; > Las notas obtenidas en la actividad <BR> 
                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; > Estudiantes que entregaron sus actividades para ser calificadas.
                                                    </p>
                                                </div>
                                                <!-- END BLOCK : tabDetalle -->
<!-- fin DETALLE DE DOCENCIA - TABS -->

<!-- LISTADO ESTUDIANTES CURSO -->
                                                <!-- START BLOCK : IngresoNotas -->
                                               <table id='dgcursos' class='easyui-datagrid' aling='center' style="width:100%; height:auto;padding:0px;margin:0px;" 
                                                       data-options="toolbar: '#tboton'">
                                                    <thead frozen="true">
                                                        <tr>                                                            
                                                            <!-- START BLOCK : frozen -->
                                                            <th data-options="field:'{idFrozen}',width:100,sortable:'true'">{nombreFrozen}</th>
                                                            <!-- END BLOCK : frozen -->
                                                        </tr>
                                                    </thead>
                                                    <thead>
                                                        <tr>                                                            
                                                            <!-- START BLOCK : nofrozen -->                                                            
                                                            {rowNoFrozen}
                                                            <!-- END BLOCK : nofrozen -->                                                           
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <div id="tboton" style="height:auto">
                                                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="getCambios()"><i class="fa fa-floppy-o fa-fw"></i>Guardar</a>                                                
                                                </div>
                                                <div align="justify">
                                                    <p class="note-font-style">
                                                        * NOTA:<br> 
                                                         &nbsp; &nbsp; &nbsp; - La nota de las  actividades es sobre puntos netos con hasta dos decimales (Ej.: 15.01 | 4.9 | 7 | 8.1)<br>
                                                         &nbsp; &nbsp; &nbsp; - Las casillas permiten el ingreso de numeros negativos <br>
                                                         &nbsp; &nbsp; &nbsp; - Cuando un estudiante comete una falta en la actividad, se debe poner un numero negativo entre [-1...-2]
                                                               
                                                    </p>
                                                </div>           
                                                <!-- END BLOCK : IngresoNotas -->        
<!-- LISTADO ESTUDIANTES CURSO FIN -->

<!-- CREAR ACTIVIDAD -->
                                                <!-- START BLOCK : crearEditarActividad -->
                                                <div id="dynbody" class="restrict_right ff">
                                                    <div id="notesrow2">
                                                        <div class="ff_pane" style="display: block;">
                                                            
                                                <div id="printTest">{error}</div>
                                                <div id="printTest2"></div>
                                                            <form id="IngresaActividad" name="IngresaActividad" method="post">
                                                            <table cellspacing="0" class="fffields">
                                                                <tbody>                       
                                                                    <tr>
                                                                        <input name="txtIdActividad" type="hidden" id="txtIdActividad" value="{txtIdActividad}" size="10" />
                                                                        <td class="page_col1">Nombre de la actividad</td>
                                                                        <td class="page_col2">
                                                                            <input name="txtNombreActividad" type="text" id="txtNombreActividad" value="{txtNombreActividad}" placeholder="Máximo 20 carácteres" maxlength="20" />
                                                                            <span id="msg_nombre" class="msg-danger-txt"></span>
                                                                        </td>                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Tipo de actividad</tI></td>
                                                                        <td class="page_col2">
                                                                            <!-- START BLOCK : tipoactividad -->
                                                                            <select name="txtTipoActividad" id="txtTipoActividad" onChange="validaPerteneceA()">
                                                                                <!-- START BLOCK : opciontipoactividad -->
                                                                                <option value="{valoropciontipoactividad}" {txtSeleccionado}>{nombreopciontipoactividad}</option>
                                                                                <!-- START BLOCK : opciontipoactividad -->
                                                                            </select>
                                                                            <!-- END BLOCK : tipoactividad -->
                                                                            <span id="msg_tipo" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Fecha de actividad</tI></td>
                                                                        <td class="page_col2">
                                                                            <input id="txtFechaRealizar" name="txtFechaRealizar" data-options="formatter:myformatter,parser:myparser" style="width: inherit;" class="easyui-datebox" size="15" editable="false" value="{txtFechaRealizar}"/>
                                                                            <span id="msg_fecha" class="msg-danger-txt"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Docencia directa</tI></td>
                                                                        <td class="page_col2">
                                                                            <input name="txtPerteneceA" type="radio"  value="1"  {SeleccionaRadioClaseMagistral}  {HabilitadoClaseMagistral} />
                                                                            Teoria<br />
                                                                            <input type="radio" name="txtPerteneceA"  value="2" {SeleccionaRadioLaboratorio} {HabilitadoLaboratorio}/>
                                                                            Práctica<br />
                                                                            <span id="msg_pertenece" class="msg-danger-txt"></span>
                                                                        </td>                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Ponderación</td>
                                                                        <td class="page_col2">
                                                                            <input name="txtPonderacion" type="text" id="txtPonderacion" value="{txtPonderacion}"  placeholder="Decimal con coma"   
                                                                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||  
                                                                                   event.charCode == 44 ||event.charCode == 46 || event.charCode == 0 "/>
                                                                            <span id="msg_ponderacion" class="msg-danger-txt"></span>
                                                                        </td>
                                                                        <input name="ponderacionAnterior" type="hidden" id="ponderacionAnterior" value="{txtPonderacionAnterior}" />
                                                                    </tr>
                                                                    <tr>
                                                                        <td><div align="right"></div></td>
                                                                        <td><label>
                                                                                <input type="hidden" name="MAX_FILE_SIZE" value="9000000">
                                                                                <input name="txtArchivoEnunciado" type="hidden" id="txtArchivoEnunciado" size="40"  />
                                                                            </label></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                                                       
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>                                              
                                                <!-- END BLOCK : crearEditarActividad -->
<!-- CREAR ACTIVIDAD FIN-->
                                            </div><!-- fin div dynbody-->
                                            <div>
                                                <hr>
                                                <div id="siteactions" class="siterow restrict_right">
                                                    <!-- START BLOCK : enlaces -->
                                                    {nuevaactividad}
                                                    {cargarNotas}
                                                    {listarcursoaprobado}
                                                    {cargarnotasactividad}
                                                    {grabarActividad}
                                                    {listadoNotas}
                                                    <!-- END BLOCK : enlaces -->
                                                </div>
                                            </div> 
                                            <br>
                                            <br>
<!-- RESUMEN ACTIVIDADES -->
                                            <!-- START BLOCK : resumenactividades -->
                                            <table class='RAsig-table' cellpadding="0" cellspacing="0" width="95%" align="center">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3"><div align="center" >RESUMEN DE ACTIVIDADES</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Docencia Directa</td>
                                                        <td>Numero&nbsp;de actividades</td>
                                                        <td>Total Ponderaci&oacute;n&nbsp;</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>TEORIA&nbsp;</td>
                                                        <td>{NumeroActividadesClaseMagistral}&nbsp;</td>
                                                        <td>{PonderacionClaseMagistral}&nbsp;</td>
                                                    </tr>

                                                    <tr>
                                                        <td>PRACTICA&nbsp;</td>
                                                        <td>{NumeroActividadesLaboratorio}&nbsp;</td>
                                                        <td>{PonderacionLaboratorio}&nbsp;</td>
                                                    </tr>

                                                    <tr>
                                                        <td><div align="center"><label>TOTAL</label></td>
                                                        <td><label>{TotalNumero}</label></td>
                                                        <td><label>{TotalPonderacion}</label></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!-- END BLOCK : resumenactividades -->
<!-- FIN DE RESUMEN
                                            
                                        </div> <!-- sitebody fin-->
                                        <br>
                                        <div id="mensajeAlert"></div>
                                        <!-- START BLOCK : mensaje -->
                                        {mensaje}
                                        <!-- END BLOCK : mensaje -->
                                                </div>
                                                </div>
                                            </div> <!-- fin div page-content-->
                                                <div id="buttons">
                                                    {RegresarActividades}
                                                    <a href="../menu/D_CourseList.php">
                                                        <input id="Listado" name="Listado" type="button" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de cursos">
                                                    </a>
                                                </div>
                                            <div class="clear"></div>
                                        </div>
                                        </div>
                                                                                                                                        
                                        </div>
                                    <div class="contenido-pie"></div>
                                </div>
                                <!-- F: CONTENIDO PRINCIPAL -->
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

