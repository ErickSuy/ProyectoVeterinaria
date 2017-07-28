<!-- Plantilla:
Conversión de codificación a UTF-8
11/21/11-10:53:55
 utf-8
-->

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <link rel="stylesheet" type="text/css" href="../../libraries/js/DataTables-1.10.6/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/js/DataTables-1.10.6/extensions/FixedColumns/css/dataTables.fixedColumns.css">
    <script type="text/javascript" charset="utf8" src="../../libraries/js/DataTables-1.10.6/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="../../libraries/js/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script language="javascript">
        function redireccionar(nuevadireccion)
        {
            location.href=nuevadireccion;
        }

        // INICIO FUNCIO QUE VALIDA
        function ValidaNumero(dato,maximo,control)
        {

            if (isNaN(dato))
            {
                //alert(dato);
                alert ("(" + dato + ") No es numero");
                return false;
            }
            if(dato>maximo)
            {
                alert ("Las notas deben ser entre 0 y " + maximo + " puntos. . .");
                return false;
            }

            if(checkDecimals(control,dato)==false)
            {
                //alert ("(" + dato + ") No estan permitidos valores con decimales ");
                return false;
            }

            if(dato*1<0)
            {
                alert ("(" + dato + ") No estan permitidos valores negativos ");
                return false;
            }
            return true;
        }

        function checkDecimals(fieldName, fieldValue) {

            decallowed = 2; // how many decimals are allowed?

            if (fieldValue.indexOf('.') == -1) fieldValue += ".";
            dectext = fieldValue.substring(fieldValue.indexOf('.')+1, fieldValue.length);

            if (dectext.length > decallowed)
            {
                alert ("Sólo se permiten notas con hasta " + decallowed + " números decimales.");
                fieldName.select();
                fieldName.focus();
                return false;
            }
            else {
                if(dectext.length==0) {
                    fieldName.value=fieldValue+'00';
                } else {
                    if(dectext.length==1) {
                        fieldName.value=fieldValue+'0';
                    }
                }
            }
            return true;
        }


        function ValidaDatos()
        {
            var DatosDesplegados;
            var RecorreDatos;
            VecPonderacion = new Array;
            var ValorDato;
            var ValorMaximo;
            var NumeroActividades;
            var RecorreActividades;
            var Salir;
            <!-- START BLOCK : validacion -->
            DatosDesplegados={DatosDesplegados};
            NumeroActividades={NumeroActividades}
                <!-- END BLOCK : validacion -->
                <!-- START BLOCK : ponderacionValida -->
                    {nuevaponderacion}
                <!-- END BLOCK :   ponderacionValida -->
                    RecorreDatos=0;
            //alert ("llego a valida datos javascript");
//   alert(DatosDesplegados);
            Elemento=6;Salir=0;
            while ((RecorreDatos<DatosDesplegados) &&Salir==0 )
            {
                RecorreActividades=0;
                Elemenento=Elemento+1;
//      alert (RecorreDatos);
//	  alert(DatosDesplegados);
                while ((RecorreActividades<NumeroActividades)&& (Salir==0) )
                {
                    Elemento=Elemento+1;
                    ValorDato=document.listadoactividades.elements[Elemento].value;
                    if(ValorDato=="")
                    {
                        document.listadoactividades.elements[Elemento].value=0;

                    }
                    if(ValidaNumero(ValorDato,VecPonderacion[RecorreActividades],document.listadoactividades.elements[Elemento])==false)
                    {
//		     alert ("FUE AQUI");
                        document.listadoactividades.elements[Elemento].focus();
                        document.listadoactividades.elements[Elemento].select();
                        document.listadoactividades.elements[Elemento].value=0;
                        Salir=1;
                        return false
                    }
                    else
                    {
                        document.listadoactividades.elements[Elemento].value=document.listadoactividades.elements[Elemento].value*1;
                    }

                    RecorreActividades++;
                } //del While RecorreActividades
                RecorreDatos++;
                Elemento=Elemento+2
            } // del while recorreDatos
            return true;
        }

        // FIN FUNCION QUE VALIDA

        function valida_envia(Inicio)
        {

            if(ValidaDatos())
            {
                direccion="cargaactividad.php?opcion=1&Inicio="+Inicio;
                document.listadoactividades.action=direccion;
                document.listadoactividades.submit();
            }
        }


        function AlInicio()
        {
            direccion="index.php";
            redireccionar(direccion);
        }



        function ParamGrabar()
        {
//  validar
//    direccion="cargaactividad.php?opcion=1";
//	direccion="direccion"+
        }

        function ListaAlumnos(Inicio)
        {
            direccion="cargaactividad.php?opcion=3&Inicio="+Inicio;
            document.listadoactividades.action=direccion;

            if(ValidaDatos(Inicio))
            {
                document.listadoactividades.submit();
            }
        }


        function VerListado(Inicio)
        {
            direccion="cargaactividad.php?opcion=4";
            document.listadoactividades.action=direccion;

            if(ValidaDatos(Inicio))
            {
                document.listadoactividades.submit();
            }


        }

        function ValidaTecla(e,estaen,SiguienteElemento)
        {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla==13)
            {
                //   alert ('Has pulsado enter');
//	alert (SiguienteElemento);
                document.listadoactividades.elements[SiguienteElemento].select();
                document.listadoactividades.elements[SiguienteElemento].focus();
            }
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
                                                            <form name="listadoactividades" method="post" action="cargaactividad.php">
                                                                <div id="sitebody">
                                                                    <br>
                                                                    <hr>
                                                                    <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                                    <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                                    <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                                    <div class="siterow"><br/><div class="siterow-center"><span>CARGA DE NOTAS DE ACTIVIDADES MANUAL</span></div><br/></div>
                                                                    <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                                                    <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span><span class="page_time_label"> Fecha: {vFecha}&nbsp;&nbsp;Hora:{vHora} </span></div>
                                                                    <hr>
                                                                    <div id="dynheader" class="restrict_right"></div>
                                                                    <div id="dynbody" class="restrict_right ff">
                                                                        <div id="notesrow2">
                                                                            <!-- START BLOCK : tablalistado -->
                                                                            <div align="center">
                                                                                <input name="txtCurso" type="hidden" value="{txtCurso}" size="5" />
                                                                                <input name="txtSeccion" type="hidden" id="txtSeccion" value="{txtSeccion}" size="5" />
                                                                                <input name="txtPeriodo" type="hidden" value="{txtPeriodo}" size="5" />
                                                                                <input name="txtAnio" type="hidden" value="{txtAnio}" size="5" />
                                                                                <input name="txtCarrera" type="hidden" value="{txtCarrera}" size="5" />
                                                                            </div>

                                                                            <table class='stripe row-border order-column  compact' id="dgTablaDatos" name="dgTablaDatos" align='center'  cellspacing="0" width="100%" data-page-length='15'>
                                                                                <thead>
                                                                                <tr>
                                                                                    <th><div align="center">No&nbsp;</div></th>
                                                                                    <th><div align="center">CARNET</div></th>
                                                                                    <th><div align="center">ALUMNO</div></th>
                                                                                    <!-- START BLOCK : nuevoencabezadoactividad -->
                                                                                    <th>
                                                                                        <div style="display:none">
                                                                                            <div id="toolbar{ActividadID}" style="padding: 5px;">
                                                                                                <div class="siterow"><span class="page_label">Nombre: </span><span class="underline_label">{NombreActividad}</span></div>
                                                                                                <div class="siterow"><span class="page_label">Tipo: </span><span class="underline_label">{vTipoActividad}</span></div>
                                                                                                <div class="siterow"><span class="page_label">Fecha: </span><span class="underline_label">{vFechaActividad}</span></div>
                                                                                                <div class="siterow"><span class="page_label">Docencia: </span><span class="underline_label">{vDocenciaActividad}</span></div>
                                                                                                <div class="siterow"><span class="page_label">Poderación: </span><span class="underline_label">{Ponderacion} pts.</span></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div style="width: 45px; !important" align="center">
                                                                                            <div style="width: 45px; !important" align="center">
                                                                                                <a id="lnk{ActividadID}" href="javascript:void(0)" class="easyui-tooltip">{ActividadID}</a>
                                                                                            </div>
                                                                                            <script>
                                                                                                $('#lnk{ActividadID}').tooltip({
                                                                                                            hideEvent: 'none',
                                                                                                            content: function(){
                                                                                                                return $('#toolbar{ActividadID}');
                                                                                                            },
                                                                                                            onShow: function(){
                                                                                                                var t = $(this);
                                                                                                                t.tooltip('tip').focus().unbind().bind('blur',function(){
                                                                                                                    t.tooltip('hide');
                                                                                                                });
                                                                                                            }
                                                                                                        }
                                                                                                );
                                                                                            </script>
                                                                                        </div>

                                                                                    </th>
                                                                                    <!-- END BLOCK : nuevoencabezadoactividad -->
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <!-- START BLOCK : filaalumno -->
                                                                                <tr>
                                                                                    <td>
                                                                                        {no}&nbsp;<input name="PosicionVector[]" type="hidden" id="CarneAlumno[]" value="{PosicionVector}" size="8"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input name="CarnetAlumno[]" type="hidden" id="CarneAlumno[]" value="{CarnetAlumno}" size="8"/>
                                                                                        {CarnetAlumno}&nbsp;
                                                                                    </td>
                                                                                    <td>{NombreAlumno}&nbsp;</td>
                                                                                    <!-- START BLOCK : datofilaactividad -->t
                                                                                    <td>
                                                                                        <div style="width: 45px; !important; height: 25px !important;" align="center">
                                                                                            <input style="width: 50px; !important" name="{nombrecampoactividad}" type="text" value="{valorcampoactividad}" tabindex="{txtTabIndex}" {txtReadOnly} {txtAlt} onKeyPress="javascript:ValidaTecla(event,{txtTabIndex},{txtSiguienteElemento});" size="3" maxlength="5" onChange="ValidaDatos();"/>
                                                                                            &nbsp;
                                                                                        </div>
                                                                                    </td>
                                                                                    <!-- END BLOCK : datofilaactividad -->
                                                                                </tr>
                                                                                <!-- END BLOCK : filaalumno -->
                                                                                </tbody>
                                                                            </table>
                                                                            <!-- END BLOCK : tablalistado -->
                                                                            {InitTabla}
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <hr>
                                                                        <!-- START BLOCK : tablabotones -->
                                                                        <div id="siteactions" class="siterow restrict_right">
                                                                            <input type="Button" name="btnAnterior" id="btnAnterior" value="Anterior" onClick="ListaAlumnos({txtAnterior})" style="width: auto !important;" class="nbtn gbtn btn_midi" {ActivoAnterior}/>
                                                                            <input type="{txtTipoSiguiente}" name="btnSiguiente" id="btnSiguiente" value="Siguiente" onClick="ListaAlumnos({txtSiguiente})" style="width: auto !important;" class="nbtn gbtn btn_midi" {ActivoSiguiente}/>
                                                                            <input type="{txtTipoListar}" name="btnListar" id="btnListar" value="Ver listado final" onClick="VerListado()" style="width: auto !important;" class="nbtn gbtn btn_midi"/>
                                                                            <input name="txtInicio" type="hidden" id="txtInicio" value="{Inicio}" size="5" />
                                                                            <input type="hidden" name="Desplegados" id="Desplegados" value="{Desplegados}" size="5"/>
                                                                            <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar"  onClick="valida_envia({Inicio});" style="width: auto !important;" class="nbtn gbtn btn_midi"/>
                                                                        </div>
                                                                        <!-- END BLOCK : tablabotones -->
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                </div>
                                                            </form>
                                                            <!-- START BLOCK : mensaje -->
                                                            <center>{mensaje}</center>
                                                            <!-- END BLOCK : mensaje -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <input type="submit" name="Regresar" id="Regresar" value="Regresar a listado de actividades" class="nbtn rbtn btn_midi btn_exp_h okbutton" onclick='javascript:redireccionar("../notas-actividades/creaactividad.php?opcion=9&curso={AtxtCurso}&index={AtxtIndex}&carrera={AtxtCarrera}&seccion={AtxtSeccion}");'/>
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