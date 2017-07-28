<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script type="text/javascript" src="../../libraries/AssignationFunctions.js"></script>
    <script language="javascript">
        var infojsCurso = new Array();
        function enviarInfojs(usar, flag, num, index, cod, nom, sec, obs, ind, cre, ret, val, req, marca, marcaAsig,lab,labSec) {
            if (usar) {
                if (flag == 1 && ((num - 1)) == 0) {
                    infojsCurso = null;
                    infojsCurso = new Array();
                }
            }
            infojsCurso[num - 1] = [cod, nom, sec, obs, ind, cre, ret, val, req, marca, marcaAsig, index,lab,labSec];
            return true;
        }

        function quitarInfojs_old(num) {
            infojsCurso[num - 1] = null;
            return true;
        }

        function quitarInfojs(num) {
            var i;
            var totalCursos = infojsCurso.length;
            for (i = num - 1; i < totalCursos - 1; i++)
                infojsCurso[i] = infojsCurso[i + 1];

            infojsCurso[totalCursos - 1] = null;
            return true;
        }

        function verInfojs(num) {
            alert(infojsCurso[num - 1][1]);
            return true;
        }

        function darInfojs() {
            return infojsCurso;
        }

        function arrayLong() {
            return infojsCurso.length;
        }

        function limpiarInfojs() {
            for (var i = 0; i < infojsCurso.length; i++) {
                infojsCurso[i] = null;
            }
            return true;
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
                                <div id="treepane" tabindex="100">
                                    <!-- INCLUDESCRIPT BLOCK : isessioninfo -->
                                    <div class="demo tree tree-default" id="thetree" tabindex="100">
                                        <div id="ffframe-large">
                                            <div id="ffheader">
                                                <span id="pff_title"
                                                      class="page_title">Asignación de cursos de semestre</span>

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
                                                    <br>
                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class='easyui-tabs' style='width:auto;height:auto'>
                                                                <div title='2. Especificación de asignación de cursos' style="">
                                                                    <div class="ff_pane" style="display: block; overflow: hidden;">
                                                                        <div class="easyui-panel" style="width:inherit;height:auto;">
                                                                            <div id="sitebody">
                                                                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <form name="form1">
                                                                                                <script language='JavaScript'>
                                                                                                    totalRegistros = {aTotalRegistros};
                                                                                                    var infoPensum = new Array();

                                                                                                    <!-- START BLOCK : INFOPENSUM -->
                                                                                                    infoPensum[{aPosicion}] = new Array();
                                                                                                    infoPensum[{aPosicion}][0] = "{aCursoIP}";
                                                                                                    infoPensum[{aPosicion}][1] = "{aNombreIP}";
                                                                                                    infoPensum[{aPosicion}][2] = "{aObligatorioIP}";
                                                                                                    infoPensum[{aPosicion}][3] = "{aCreditosIP}";
                                                                                                    infoPensum[{aPosicion}][4] = "{aPosicionIP}";
                                                                                                    infoPensum[{aPosicion}][5] = "{aVisibleIP}";
                                                                                                    infoPensum[{aPosicion}][6] = "{aRequisitosIP}";
                                                                                                    infoPensum[{aPosicion}][7] = "{aIndexIP}";
                                                                                                    infoPensum[{aPosicion}][8] = "{aLaboratorioIP}";
                                                                                                    infoPensum[{aPosicion}][9] = "{aLaboratorioSecIP}";
                                                                                                    <!-- END BLOCK : INFOPENSUM -->

                                                                                                    totalCursos = {aTotalCursos};

                                                                                                </script>
                                                                                                <table width="96%" align="center" cellspacing="0" cellpadding="0">
                                                                                                    <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <table cellspacing="0" cellpadding="0" class="fffields">
                                                                                                                <tbody>
                                                                                                                <tr>
                                                                                                                    <td class="page_col1">
                                                                                                                        <label>Seleccione curso&nbsp;/&nbsp;módulo</label>
                                                                                                                    </td>
                                                                                                                    <td class="page_col2">
                                                                                                                        <select name="cursos" id="cursos" onChange="VerSecciones(this.form);">
                                                                                                                            <option value='0' selected>...</option>
                                                                                                                        </select>
                                                                                                                        <script language='JavaScript'>
                                                                                                                            addOptionCursos();
                                                                                                                        </script>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td class="page_col1">
                                                                                                                        <label>Seleccione grupo curso</label>
                                                                                                                    </td>
                                                                                                                    <td class="page_col2">
                                                                                                                        <select name='secciones'  id="secciones">
                                                                                                                            <option value='0' selected>...</option>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td rowspan="2" style="vertical-align: middle;">
                                                                                                            <input id="add" name="add" type="button" value="Agregar curso al listado de la asignación" onClick="valSelect()" class="floatright nbtn dbtn btn_mini btn_exp_h nopad"/>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>

                                                                                                <script language="javascript">
                                                                                                    var infoHorario = null;
                                                                                                    infoHorario = new Array();

                                                                                                    <!-- START BLOCK : INFOHORARIO -->
                                                                                                    infoHorario[{aIndice}] = new Array();
                                                                                                    infoHorario[{aIndice}][0] = "{aCursoIH}";
                                                                                                    infoHorario[{aIndice}][1] = "{aSeccionIH}";
                                                                                                    infoHorario[{aIndice}][2] = "{aNombreIH}";
                                                                                                    infoHorario[{aIndice}][3] = "{aIndexIH}";
                                                                                                    infoHorario[{aIndice}][4] = "{aLaboratorioIH}";
                                                                                                    <!-- END BLOCK : INFOHORARIO -->
                                                                                                </script>
                                                                                            </form>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <hr/>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <form name="form2" method="post" action="../../fw/view/AssignationProcess.php">
                                                                                                <table width="100%">
                                                                                                    <tr>
                                                                                                        <td align="left">
                                                                                                            {aDesplegarErrorAsignacion}
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="left">
                                                                                                            <div id="dynheader" class="restrict_right"></div>
                                                                                                            <div id="dynbody" class="restrict_right">
                                                                                                                <div id="notesrow2">
                                                                                                                    <table id="tblListaCursos" class='reporte-cursos RAsig-table' width="100%" border="1" cellpadding="0" cellspacing="0">
                                                                                                                        <thead>
                                                                                                                        <tr>
                                                                                                                            <th width="5%" align="left">CURSO</th>
                                                                                                                            <th width="75%" align="left">NOMBRE</th>
                                                                                                                            <th width="7%" align="center">GRUPO</th>
                                                                                                                            <th width="10%" align="center">GRUPO LAB.</th>
                                                                                                                            <th colspan="2" width="6%" align="center">ACCIONES</th>
                                                                                                                            <th hidden="true" align="center">INDICE</th>
                                                                                                                            <th hidden="true" align="center">CREDITOS</th>
                                                                                                                            <th hidden="true" align="center">REQUISITOS</th>
                                                                                                                            <th hidden="true" align="center">MARCA </th>
                                                                                                                            <th hidden="true" align="center">MARCA ASIG.</th>
                                                                                                                            <th hidden="true" align="center">INDICE</th>
                                                                                                                            <th hidden="true" align="center">NUMERO</th>
                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                        <tr></tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <input name="totalcur" id="theValue" type="hidden" value="0"/>
                                                                                                            <input name="pensum" id="pensum" type="hidden" value="{aPensum}"/>
                                                                                                            <input name="periodo" type="hidden" value="{aPeriodo}"/>
                                                                                                            <input name="confirmar" id="confirmar" type="hidden" value="0"/>
                                                                                                            <input name="asignar" type="button" value="Asignar los cursos del listado" onclick="asignarCursos();" class="nbtn rbtn btn_midi btn_exp_h okbutton" style="margin-left: 700px !important;"/>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </form>
                                                                                            <script language="javascript">
                                                                                                <!-- START BLOCK : ADDREGISTRO -->
                                                                                                {aAddRegistro}
                                                                                                <!-- END BLOCK : ADDREGISTRO -->

                                                                                                var p = arrayLong();
                                                                                                var matrizcur = new Array();
                                                                                                matrizcur = darInfojs();
                                                                                                for (var j = 0; j <= p; j++) {
                                                                                                    if (matrizcur[j] != null) {
                                                                                                        addEvent(matrizcur[j][13],matrizcur[j][12],matrizcur[j][11], matrizcur[j][0], matrizcur[j][1], matrizcur[j][2], matrizcur[j][3], matrizcur[j][4], matrizcur[j][5], matrizcur[j][6], matrizcur[j][7], matrizcur[j][8], matrizcur[j][9], matrizcur[j][10]);
                                                                                                    }
                                                                                                }
                                                                                            </script>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><hr/></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>

                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td align="center">
                                                                                        <table border="0" align="center" cellspacing="0" cellpadding="0">
                                                                                            <tr>
                                                                                                <td width="66"><img src="../../resources/images/1_off.gif" width="34" height="34" border="1" class="step-img"></td>
                                                                                                <td width="66"><img src="../../resources/images/2_on.gif" width="34" height="34" border="1" class="step-img"></td>
                                                                                                <td width="66"><img src="../../resources/images/3_off.gif" width="34" height="34" border="1" class="step-img"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>&nbsp;</td>
                                                                                                <td>&nbsp;</td>
                                                                                                <td>&nbsp;</td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>

                                                                            <div id="menu_acc" class="easyui-accordion" style="width:100%;">
                                                                                <div title="Información importante" iconCls="fa fa-question-circle fa-lg" style="overflow:auto;">
                                                                                    <div class='easyui-tabs' style='width:auto;height:auto'>
                                                                                        <div title='Nivel introductorio' style="padding:10px;">
                                                                                            <div class='alert alert-warning'>
                                                                                                <h4><i class='fa fa-warning'></i> TOMAR NOTA</h4>
                                                                                                <ul>
                                                                                                    <li> Seleccione curso y sección. Si el curso no tiene sección seleccione '-'</li>
                                                                                                    <li> Agregue el curso la lista</li>
                                                                                                    <li> Finalice el proceso (luego de agregar todos los cursos), presionando el botón <strong>&quot;Asignar cursos&quot;</strong>.</li>
                                                                                                    <li> Con asignación exitosa, verá la boleta de asignación.</li>
                                                                                                    <li> Si NO es exitosa, verá los resultados en las casillas de observaciones de esta pantalla.</li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div title='Básico' style="padding:10px;">
                                                                                            <div class='alert alert-warning'>
                                                                                                <h4><i class='fa fa-warning'></i> TOMAR NOTA</h4>
                                                                                                <ul>
                                                                                                    <li> Seleccione curso y sección. Si el curso no tiene sección seleccione '-'</li>
                                                                                                    <li> Agregue el curso la lista</li>
                                                                                                    <li> Al agregar el curso, si este tiene labarotario seleccione el grupo del mismo en el listado.</li>
                                                                                                    <li> Finalice el proceso (luego de agregar todos los cursos), presionando el botón <strong>&quot;Asignar cursos&quot;</strong>.</li>
                                                                                                    <li> Con asignación exitosa, verá la boleta de asignación.</li>
                                                                                                    <li> Si NO es exitosa, verá los resultados en las casillas de observaciones de esta pantalla.</li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div title='Modular' style="padding:10px;">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
</body>
</html>