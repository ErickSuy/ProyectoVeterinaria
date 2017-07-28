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
            window.open("../LogOut.php");
        }

        function actualizarSelect(periodo, anio) {
            var i;
            var longitud;

            longitud = document.getElementById("periodo").length;
            for (i = 0; i < longitud; i++) {
                if (document.getElementById("periodo").options[i].value == periodo) {
                    document.getElementById("periodo").options[i].selected = true;
                    break;
                }
            }
            longitud = document.getElementById("anio").length;
            for (i = 0; i < longitud; i++) {
                if (document.getElementById("anio").options[i].value == anio) {
                    document.getElementById("anio").options[i].selected = true;
                    break;
                }
            }
        }
    </script>

    <script>
        var periodoSelect = {periodo};
        var anioSelect = {anio};

        $(function () {
            $('#dgcursos').datagrid({
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
                url: 'D_StudentReport.php?buscar=1&periodo={periodo}&anio={anio}',
                rowStyler: function(index,row){
                    if (index % 2 == 0){
                        return 'color: #3d3d3d;text-transform: capitalize;font-weight: lighter;';
                    } else {
                        return 'color: #3d3d3d;text-transform: capitalize;font-weight: lighter;';
                    }
                },
                onClickRow: function (rowIndex) {
                    verAsignados();
                }
            });

            $('#dgasignados').datagrid({
                pageSize: 20,
                pageList:[20],
                pagination: true,
                nowrap: true,
                striped: false,
                fitColumns: true,
                rownumbers: true,
                singleSelect: true,
                idField: 'idstudent',
                remoteSort: false,
                rowStyler: function(index,row){
                    if (index % 2 == 0){
                        return 'color: #3d3d3d;text-transform: capitalize;font-weight: lighter;';
                    } else {
                        return 'color: #3d3d3d;text-transform: capitalize;font-weight: lighter;';
                    }
                }
            });
        });

        function buscar() {
            borrarDatos();
            anioSelect = $('#anio').val();
            periodoSelect = $('#periodo').val();

            var url = "D_StudentReport.php?buscar=1&anio=" + $( "#anio" ).val() + "&periodo=" + $( "#periodo" ).val();

            $("#dgcursos").datagrid({
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
                    if (!$("#dgcursos").datagrid('getRows').length){
                        $.messager.alert("Importante","No ha encontrado datos para mostrar.",'warning');
                    }
                }
            });

            $("#dgcursos").datagrid('disableFilter');
            $("#dgcursos").datagrid('enableFilter',[{
                field:'section',
                type:'label'
            }]);

            $('#lPanelWest').panel({
                title: 'Cursos impartidos :: ' + $("#periodo option:selected").html() + ' ' + $( "#anio" ).val()
            });

            $('#lytDgAsignados').layout('expand','west');
        }

        function verAsignados() {
            var row = $('#dgcursos').datagrid('getSelected');
            var rows = $("#dgasignados").datagrid("getRows").length;
            var i;

            for (i = 0; i < rows; i++) {
                $("#dgasignados").datagrid("deleteRow", 0);
            }

            var url = '../../fw/view/D_StudentReport.php?detalle=1&curso=' + row.idcourse + '&index=' + row.index + '&anio=' + $( "#anio" ).val() + '&periodo=' + $( "#periodo" ).val() + '&carrera=' + row.idcareer ;

            $('#dgasignados').datagrid({
                pageSize: 20,
                pageList:[20],
                pagination: true,
                nowrap: true,
                striped: false,
                fitColumns: true,
                rownumbers: true,
                singleSelect: true,
                sortName: 'idstudent',
                sortOrder: 'asc',
                idField: 'idstudent',
                remoteSort: false,
                url: url
            });

            $("#dgasignados").datagrid('disableFilter');
            $("#dgasignados").datagrid('enableFilter',[{
                field:'career',
                type:'label'
            },{
                field:'email',
                type:'label'
            }]);

            $('#lPanelCenter').panel({
                title: '[' + row.idcourse + '] ' + row.name + ' :: '
            });

            $('#lytDgAsignados').layout('collapse','west');
        }

        function borrarDatos() {
            $('#lPanelCenter').panel({
                title: ' '
            });
            $('#lPanelWest').panel({
                title: ' '
            });

            var rows = $("#dgcursos").datagrid("getRows").length;
            for (i = 0; i < rows; i++) {
                $("#dgcursos").datagrid("deleteRow", 0);
            }

            rows = $("#dgasignados").datagrid("getRows").length;
            for (i = 0; i < rows; i++) {
                $("#dgasignados").datagrid("deleteRow", 0);
            }
        }

        function descargarListadoEstudiantes() {
            var rows = $("#dgasignados").datagrid("getRows").length;
            if(rows>0) {
                var row = $('#dgcursos').datagrid('getSelected');
                window.location.href = '../../fw/controller/manager/D_DownloadStudentReportManager.php?cur=' + row.idcourse + '&per=' + periodoSelect + '&ani=' + anioSelect + '&index=' + row.index + '' + '&car=' + row.idcareer + '';
            } else {
                $.messager.alert("Importante","No ha seleccionado ningun curso del catálogo.",'warning');
            }
        }

        function descargarListadoCorreos() {
            var rows = $("#dgasignados").datagrid("getRows").length;
            if(rows>0) {
                var row = $('#dgcursos').datagrid('getSelected');
                window.location.href = '../../fw/controller/manager/D_DownloadStudentEmailManager.php?cur=' + row.idcourse + '&sec=' + row.section + '&per=' + periodoSelect + '&ani=' + anioSelect + '&index=' + row.index + '';
            } else {
                $.messager.alert("Importante","No ha seleccionado ningun curso del catálogo.",'warning');
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
                                        <div id="fframe-reporte">
                                            <div id="ffheader">
                                                <span class="page_title">Consulta de estudiantes asignados</span>
                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                            <span>Especifique el año y período para la búsqueda de información...</span>
                                                            <!-- Selects para periodo y año -->
                                                            <table width="100%" align="center" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
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
                                                                                    <label>Período:</label>
                                                                                </td>
                                                                                <td class="page_col2">
                                                                                    <!-- INCLUDESCRIPT BLOCK : iselectciclo -->
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td rowspan="2" style="vertical-align: middle;">
                                                                        <input id="Buscar" name="buscar" type="button" value="Buscar cursos impartidos" onclick="buscar()" class="nbtn rbtn btn_midi btn_exp_h okbutton"/>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:711px;'>
                                                                <div title='Cátalogo' style='padding:0px; margin: 0px;'>
                                                                    <div id="lytDgAsignados" class="easyui-layout" style="width:100%;height:650px;padding:0px;margin:0px;">
                                                                        <div id="lPanelWest" data-options="region:'west'" title=" " style="width:inherit;height:inherit;padding:0px;margin:0px;">
                                                                            <table id='dgcursos' class='easyui-datagrid' aling='center' style="width:100%; height:621px;padding:0px;margin:0px;" data-options="footer:'#ft1'">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th field='idcourse' width='50' sortable='true'>CURSO</th>
                                                                                    <th field='name' width='530' sortable='true'>NOMBRE</th>
                                                                                    <th field='career' width='150' sortable='true'>CARRERA</th>
                                                                                    <th field='count' width='150'>NÚMERO DE ASIGNADOS</th>
                                                                                    <th field='index' width='0' hidden="true">I</th>
                                                                                    <th field='idcareer' width='0' hidden="true">C</th>
                                                                                </tr>
                                                                                </thead>
                                                                            </table>
                                                                            <div id="ft1" style="padding:5px;">
                                                                                <div style="width: inherit; height: 26px;"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="lPanelCenter" data-options="region:'center'" title=" " style="width:inherit;height:inherit;padding:0px;margin:0px;">
                                                                            <table id='dgasignados' class='easyui-datagrid' aling='center' style="width:99%; height:621px;padding:0px;margin:0px;" data-options="footer:'#ft2'">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th field='idstudent' width='70' sortable='true'>CARNET</th>
                                                                                    <th field='name' width='250' sortable='true'>ESTUDIANTE</th>
                                                                                    <th field='career' width='100' sortable='true'>CARRERA</th>
                                                                                    <th field='section' width='70' sortable='true'>TEORÍA</th>
                                                                                    <th field='labgroup' width='70' sortable='true'>LABORATORIO</th>
                                                                                    <th field='email' width='180'>CORREO</th>
                                                                                </tr>
                                                                                </thead>
                                                                            </table>
                                                                            <div id="ft2" style="padding:5px;">
                                                                                <div style="width: inherit;height: 26px; margin-left: 660px">
                                                                                    <a id="lbRAsignados" href="javascript:void(0)" onclick="descargarListadoEstudiantes()" class="easyui-linkbutton" ><i class="fa fa-file-excel-o fa-lg"></i>&nbsp;&nbsp;Asignados</a>
                                                                                    <a id="lbRCorreos" href="javascript:void(0)" onclick="descargarListadoCorreos()" class="easyui-linkbutton"><i class="fa fa-file-excel-o fa-lg"></i>&nbsp;&nbsp;Correos</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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

<script language="JavaScript">
    actualizarSelect({periodo}, {anio});
</script>

</body>
</html>