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
    <script type="text/javascript" src="../../libraries/easyui/extensions/datagrid-filter.js"></script>
    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }
    </script>
    <script>
        $(function () {
            $('#periodo').combobox({
                valueField: 'ciclo',
                textField: 'cicloTxt',
                panelHeight: 'auto',
                editable: false,
                required:true,
                data: [
                    <!-- START BLOCK : selectPeriodo -->
                    {
                        ciclo: '{aCiclo}',
                        cicloTxt: '{aCicloNombre}'
                    } {aComa}
                    <!-- END BLOCK : selectPeriodo -->
                ],
                onSelect: function(rec) {
                    document.getElementById("Buscar").disabled = true;
                    $('#horario').combobox('clear');
                    var url = 'CourseSchedule.php?select=1&ciclo=' + rec.ciclo;
                    $('#horario').combobox('reload', url);
                }
            });

            $('#horario').combobox({
                valueField: 'horario',
                textField: 'horarioTXT',
                panelHeight: 'auto',
                editable: false,
                required:true,
                onSelect: function(rec) {
                    document.getElementById("Buscar").disabled = false;
                }
            });
        });

        function buscar() {
            var rows = $("#dgschedule").datagrid("getRows").length;
            var i;

            for (i = 0; i < rows; i++) {
                $("#dgschedule").datagrid("deleteRow", 0);
            }

            var ciclo = $('#periodo').combobox('getValue');
            var nCiclo = $('#periodo').combobox('getText');
            var tHorario = $('#horario').combobox('getValue');
            var nHorario = $('#horario').combobox('getText');
            var tAnio = $('#anio').find('option:selected').text();
            var nAnio = $('#anio').find('option:selected').text();

            var url = 'CourseSchedule.php?periodo=' + ciclo + '&horario=' + tHorario + '&anio=' + tAnio;
            $('#dgschedule').datagrid({
                //title: nHorario + ' :: ' + nCiclo + ' ' + ((new Date()).getFullYear()),
                title: nHorario + ' :: ' + nCiclo + ' ' + (nAnio),
                iconCls:'fa fa-bell',
                pageSize: 20,
                pageList:[20],
                pagination: true,
                nowrap: true,
                striped: false,
                fitColumns: true,
                rownumbers: true,
                singleSelect: true,
                sortName: 'idcourse',
                sortOrder: 'asc',
                idField: 'idcourse',
                remoteSort: false,
                url: url,
                onLoadError: function(data){
                    if (!$("#dgschedule").datagrid('getRows').length){
                        $.messager.alert("Importante","No ha encontrado datos para mostrar. La información estará disponible más adelante.",'warning');
                    }
                }
            });

            $("#dgschedule").datagrid('disableFilter');
            $("#dgschedule").datagrid('enableFilter',[{
                field:'section',
                type:'label'
            },{
                field:'building',
                type:'label'
            },{
                field:'idclassroom',
                type:'label'
            },{
                field:'starttime',
                type:'label'
            },{
                field:'endtime',
                type:'label'
            },{
                field:'mon',
                type:'label'
            },{
                field:'tue',
                type:'label'
            },{
                field:'wed',
                type:'label'
            },{
                field:'thu',
                type:'label'
            },{
                field:'fri',
                type:'label'
            },{
                field:'sat',
                type:'label'
            },{
                field:'sun',
                type:'label'
            }]);
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
                                        <div id="fframe-reporte">
                                            <div id="ffheader">
                                                <span class="page_title">Consulta de horarios</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                            <span>Especifique el período y horario para la búsqueda de información...</span>
                                                            <!-- Selects para periodo y año -->
                                                            <table width="100%" align="center" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td class="page_col1">
                                                                                    <label>Período:</label>
                                                                                </td>
                                                                                <td class="page_col2">
                                                                                    <input id="periodo" class="easyui-combobox inputselect" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">
                                                                                    <label>Año:</label>
                                                                                </td>
                                                                                <td class="page_col2">
                                                                                    <select id="anio" name="anio">
                                                                                        <!-- START BLOCK : selectAnio -->
                                                                                        <option value="{anio_select}">{anio_select}</option>
                                                                                        <!-- END BLOCK : selectAnio -->
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">
                                                                                    <label>Horario:</label>
                                                                                </td>
                                                                                <td class="page_col2">
                                                                                    <input id="horario" class="easyui-combobox inputselect" />
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td rowspan="2" style="vertical-align: middle;">
                                                                        <input id="Buscar" name="buscar" type="button" value="Buscar horario" onclick="buscar()" disabled="disabled" class="nbtn rbtn btn_midi btn_exp_h okbutton"/>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:697px;'>
                                                                <div title='Catálogo' style='padding:0px; margin: 0px;'>
                                                                    <table id='dgschedule' class='easyui-datagrid' title="" aling='center' style='width:inherit;height:610px' footer="#ft">
                                                                        <thead>
                                                                        <tr>
                                                                            <th field='idcourse' width='50' sortable='true' styler='cellStyler'>CURSO</th>
                                                                            <th field='name' width='300' sortable='true' styler='cellStyler'>NOMBRE</th>
                                                                            <th field='section' width='60' styler='cellStyler'>SECCIÓN</th>
                                                                            <th field='building' width='60' styler='cellStyler'>EDIFICIO</th>
                                                                            <th field='idclassroom' width='60' styler='cellStyler'>SALON</th>
                                                                            <th field='starttime' width='60' styler='cellStyler'>INICIO</th>
                                                                            <th field='endtime' width='60' styler='cellStyler'>FINAL</th>
                                                                            <th field='mon' width='20' styler='cellStyler'>L</th>
                                                                            <th field='tue' width='20' styler='cellStyler'>M</th>
                                                                            <th field='wed' width='20' styler='cellStyler'>M</th>
                                                                            <th field='thu' width='20' styler='cellStyler'>J</th>
                                                                            <th field='fri' width='20' styler='cellStyler'>V</th>
                                                                            <th field='sat' width='20' styler='cellStyler'>S</th>
                                                                            <th field='sun' width='20' styler='cellStyler'>D</th>
                                                                            <th field='nombrecat' width='300' sortable='true' styler='cellStyler'>CATEDRÁTICO</th>
                                                                            <th field='type' width='60' sortable='true' hidden='true' styler='cellStyler'>TIPO</th>
                                                                            <th field='career' width='60' sortable='true' styler='cellStyler'>CARRERA</th>
                                                                        </tr>
                                                                        </thead>
                                                                    </table>
                                                                    <div id="ft" class="panel-footer" style="padding:5px;">
                                                                        <table><tr><td>[<font color=#3d3d3d>Clase Magistral</font>]</td><td>|</td><td><font color=#0000FF>[Laboratorio]</font></td><td>|<font color=#008000>[Práctica]</font></td><td>|<font color=#FF00CC>[Tutoria]</font></td></tr></table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons"></div>
                                                <div class="clear"></div>
                                            </div>
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
                                        $('#lbRAsignados').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para descargar el archivo con los asignados del curso</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#lbRCorreos').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para descargar el archivo con los correos de los asignados</span>',
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