<!--
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 5/10/14
 * Time: 03:01 AM
 */
-->
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
    <script type="text/javascript" src="../../libraries/ViewManualData.js"></script>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script language='javascript'>
        // valores preestablecidos para el ingreso manual
        MinLab = 61;
        MaxLab = 100;
        MinZona = {vMinZona};
        MinCong = 0;
        MaxZona = {vMaxZona};
        MaxExamen = {vMaxExamen};
        continuar = false;
        indice = 0;  // indica la posicion actual de la celda que tiene el foco
        tipo = 1;  // indica el tipo de celda que es 1=lab,2=zona y 3=exfinal

        function redireccionar(nuevadireccion)
        {
            location.href=nuevadireccion;
        }
    </script>

    <script language="javascript">
        Periodo = {periodo};
        Laboratorio = {laboratorio};
        Escuela = {escuela};
    </script>
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
                                                            <form name='Bloque' action="../../fw/controller/manager/D_SavePageNotes.php" method="post">
                                                                <div id="sitebody">
                                                                    <br>
                                                                    <hr>
                                                                    <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                    <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                    <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                    <div class="siterow"><br/><div class="siterow-center"><span>CARGA DE NOTAS DE EXAMEN FINAL</span></div><br/></div>
                                                                    <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                                                    <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span><span class="page_time_label"> Fecha: {vFecha}&nbsp;&nbsp;Hora:{vHora} </span></div>
                                                                    <hr>
                                                                    <div id="dynheader" class="restrict_right">
                                                                        <span>Páginas del acta:</span>
                                                                        <table width='200' border='0' align='right'>
                                                                            <tr>
                                                                                <!-- START BLOCK : PAGINACION -->
                                                                                {Pagina}
                                                                                <!-- END BLOCK : PAGINACION -->
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div id="dynbody" class="restrict_right ff">
                                                                        <div id="notesrow2">
                                                                            <table class='RAsig-table' align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                                <thead>
                                                                                <tr>
                                                                                    <th width="5%">No.</th>
                                                                                    <th width="5%" align="center">CARNET</th>
                                                                                    <th width="50%">NOMBRE</th>
                                                                                    <th width="10%" align="center">&nbsp;<!--Laboratorio<br/>(100 Pts.)--></th>
                                                                                    <th width="10%" align="center">ZONA<br/>({vMaxZona} Pts.)</th>
                                                                                    <th width="10%" align="center">EXAMEN FINAL<br/>({vMaxExamen} Pts.)</th>
                                                                                    <th width="10%" align="center">NOTA FINAL<br/>(100 Pts.)</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <!-- START BLOCK : CATALOGO -->
                                                                                <tr>
                                                                                    <td>{Numero}</td>
                                                                                    <td>{Carne}</td>
                                                                                    <td>{Apellido}, {Nombre} {Congelado}</td>
                                                                                    
                                                                                    <td align="center">
                                                                                        <input type='hidden' {Disponible} border="0"
                                                                                               name="laboratorio[]" align="center"
                                                                                               size="5" maxlength="3"
                                                                                               value="{Laboratorio}" autocomplete="off"
                                                                                               onkeypress="return
                                                                                                       IngresoEnLaboratorio(event,{Posicion_Lab},{SaltoZona},{SaltarLab});"
                                                                                               onFocus="if(indice != {Posicion_Lab} )
                                                                                                       {
                                                                                                       if( EsValido(indice,tipo,{SaltarLab}) )
                                                                                                       {
                                                                                                       indice={Posicion_Lab}; tipo=1;
                                                                                                       document.Bloque.elements[{Posicion_Lab}].select();
                                                                                                       }
                                                                                                       else {
                                                                                                       document.Bloque.elements[indice].focus();
                                                                                                       }
                                                                                                       }
                                                                                                       else
                                                                                                       document.Bloque.elements[{Posicion_Lab}].select();"
                                                                                                />
                                                                                    </td>
                                                                                    
                                                                                    <td align="center">
                                                                                        <input {DisponibleZona}
                                                                                                type='text'
                                                                                               style="width: 40px; !important"
                                                                                               border="0" name="zona[]"
                                                                                               align="center"
                                                                                               size="3" maxlength="3"
                                                                                               value="{Zona}"
                                                                                               autocomplete="off"
                                                                                               onKeyPress ='return IngresoEnZona(event,{Posicion_Zona},{SaltoExamen},{SaltarLab});'
                                                                                               onFocus="if(indice != {Posicion_Zona})
                                                                                                       {
                                                                                                       if( EsValido(indice,tipo,{SaltarLab}) )
                                                                                                       {
                                                                                                       indice = {Posicion_Zona}; tipo = 2;
                                                                                                       document.Bloque.elements[{Posicion_Zona}].select();
                                                                                                       }
                                                                                                       else {
                                                                                                       document.Bloque.elements[indice].focus();
                                                                                                       }
                                                                                                       }
                                                                                                       else
                                                                                                       document.Bloque.elements[{Posicion_Zona}].select();">
                                                                                    </td>
                                                                                    <td align="center">
                                                                                         <input {DisponibleExamen}
                                                                                                type='text'
                                                                                                style="width: 40px; !important"
                                                                                                border="0"
                                                                                                name="examen[]" size="3" maxlength="3" value="{Examen}"
                                                                                                autocomplete="off"
                                                                                                onKeyPress='return IngresoEnExamen(event,{Posicion_Examen},{Salto},{SaltarLab});'
                                                                                                onFocus='if(indice != {Posicion_Examen})
                                                                                                        {
                                                                                                        if( EsValido(indice,tipo,{SaltarLab}) )
                                                                                                        {
                                                                                                        indice = {Posicion_Examen}; tipo = 3;
                                                                                                        document.Bloque.elements[{Posicion_Examen}].select();
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                        document.Bloque.elements[indice].focus();
                                                                                                        }
                                                                                                        }
                                                                                                        else
                                                                                                        document.Bloque.elements[{Posicion_Examen}].select();'>
                                                                                    </td>
                                                                                    <!--    javascript:SumarSaltar(event,{Posicion_Examen},{Salto},{ValorExamen});"
                                onblur = "javascript:VerificaExamen({Posicion_Examen},{ValorExamen});" -->
                                                                                    <td align="center">
                                                                                        <input type='text' border="0" disabled="true"
                                                                                               style="width: 40px; !important"
                                                                                               class="note-font-style"
                                                                                               autocomplete="off"
                                                                                               name="notafinal[]"
                                                                                               value="{ValorNotaFinal}"
                                                                                               align="center" size="5"
                                                                                               maxlength="3"/>

                                                                                        <input type="hidden" name="problemal[]" value="{Problema}">
                                                                                    </td>
                                                                                </tr>
                                                                                <!-- END BLOCK :   CATALOGO -->
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <script language="javascript">
                                                                            document.Bloque.elements[0].select();
                                                                        </script>
                                                                    </div>
                                                                    <div>
                                                                        <hr>
                                                                        <div id="siteactions" class="siterow restrict_right">
                                                                            {BtnAnterior}
                                                                            &nbsp;
                                                                            <input type="button" name="siguiente" style="width: auto !important;" class="nbtn gbtn btn_midi"
                                                                                   id="siguiente" value="Grabar y Siguiente >>"
                                                                                   onClick="if( EsValido(indice,tipo,{SaltarLaboratorio}) )
                                                                                           {
                                                                                           document.Bloque.siguiente.disabled = true;
                                                                                           document.Bloque.submit();
                                                                                           }
                                                                                           else
                                                                                           {
                                                                                           document.Bloque.elements[indice].focus();
                                                                                           };">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <br>
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
                                                           onclick='javascript:redireccionar("D_CourseList.php?anio={Anio}&periodo={periodo}");'/>
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