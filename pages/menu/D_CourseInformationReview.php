<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 1/10/14
 * Time: 07:38 PM
 */
include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/D_LoadNotesMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

define ('_ACTA_APROBADA', 5);
session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$curso = $_REQUEST["curso"];
$seccion = $_REQUEST["seccion"];
$index = $_REQUEST["index"];
$carrera = $_REQUEST["carrera"];

if(isset($_REQUEST['next'])) {
    die;
}

if(!isset($_GET['ajax'])) {

    //Creacion de las instancias
    $_SESSION["sObjNotas"] = new D_CourseNotesManager();
    $obj_cad = new ManejoString();

    $seccion_temporal = $seccion;
    if (strlen($seccion) == 2) {
        $signo = substr($seccion, 1, 1);
        if ($signo == '*') {
            $seccion[1] = '+';
            $seccion_temporal[1] = 'm';
        }
    }

    $_SESSION["sObjNotas"]->mCurso = $curso;
    $_SESSION["sObjNotas"]->mSeccion = $seccion;
    $_SESSION["sObjNotas"]->mIndex = $index;
    $_SESSION["sObjNotas"]->mCarrera = $carrera;
    $_SESSION["sObjNotas"]->mPeriodo = $_SESSION["sPeriodo"];
    $_SESSION["sObjNotas"]->mAnio = $_SESSION["sAnio"];

    $_SESSION["sObjNotas"]->CalculaAsignados();

    $_SESSION["sObjNotas"]->CursoDatos();

    $_SESSION["sObjNotas"]->clasificarCurso($index,$curso,$carrera);

// El tipo de acta debe de ser W, para poder ser procesado por este medio
    if ($_SESSION["sObjNotas"]->mTipoActa == 'W') {
        $alerta = 1;
        //Verificación sobre la fecha actual si se encuentra en el rango permitido para Ingreso de Notas
        if ($_SESSION["sObjNotas"]->ValidaRangoFechas($_SESSION["sPeriodo"], $_SESSION["sAnio"],$_SESSION["sObjNotas"]->mCurso,$_SESSION["sObjNotas"]->mCarrera) == 0) {
            if (isset($_SESSION["sObjNotas"]->mEstado) && $_SESSION["sObjNotas"]->mEstado >= _ACTA_APROBADA)
                header("Location:../../pages/menu/D_ApprovedActList.php");
            else
                $msgnotas = 300;
            //Fuera de Fecha para Ingreso de Notas
        } //Verifica que haya sido cargado el curso en el semestre para poder procesar su retrasada
        else {
            if (($_SESSION["sPeriodo"] == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE) || ($_SESSION["sPeriodo"] == SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE)) {
                $periodo_aconsultar = PRIMER_SEMESTRE;
                $alerta = $_SESSION["sObjNotas"]->ValidaIngresoAnterior($periodo_aconsultar);
            } else
                if (($_SESSION["sPeriodo"] == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE) || ($_SESSION["sPeriodo"] == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)) {
                    $periodo_aconsultar = SEGUNDO_SEMESTRE;
                    $alerta = $_SESSION["sObjNotas"]->ValidaIngresoAnterior($periodo_aconsultar);
                }
            if ($alerta == 1) $_SESSION["sObjNotas"]->mHabilitado = 1;
            else {
                $_SESSION["sObjNotas"]->mHabilitado = 0;
                $msgnotas = $alerta;
            }
        }
    } else $msgnotas = 306;

// Verificar si el periodo de ingreso de notas es correcto para generar enlace de descarga de listado de asignados
    $enlaceDescarga = '&nbsp;';
    if ($_SESSION["sObjNotas"]->BajarArchivo($_SESSION["sPeriodo"], $_SESSION["sAnio"]) != 0) {
        $parametros = $index . "_" . $curso .  $_SESSION["sObjNotas"]->mCarrera/*$seccion_temporal*/ . $_SESSION["sObjNotas"]->mPeriodo . $_SESSION["sObjNotas"]->mAnio . ".csv";
        $enlaceDescarga = '<a id="lbDescargaArchivo" href="../../fw/controller/manager/D_DownloadCourseAct.php?dl=' . $parametros . '" class="easyui-linkbutton"><i class="fa fa-download fa-lg"></i>&nbsp;&nbsp;Descargar archivo</a>';
    }

    $tInfoCurso = '<table cellspacing="0" width="100%" align="center" class="fffields"><tbody>';
    $tInfoCurso .= '<tr><td class="page_col1">Número de asignados</td><td class="page_col2"><input type="text" spellcheck="false" disabled="true" value="' . $_SESSION["sObjNotas"]->mAsignados . '" ></td></tr>';
    $tInfoCurso .= '<tr><td class="page_col1">Estado de acta</td><td class="page_col2"><input type="text" spellcheck="false" disabled="true" value="' . $obj_cad->EstadoActa($_SESSION["sObjNotas"]->mEstado) . '" ></td></tr>';
    //$tInfoCurso .= '<tr><td class="page_col1">¿Curso con laboratorio?</td><td class="page_col2"><input type="text" spellcheck="false" disabled="true" value="' . $obj_cad->DescribeLaboratorio($_SESSION["sObjNotas"]->mLaboratorio) . '" ></td></tr>';
    $tInfoCurso .= '</tbody></table>';
    $tInfoCurso .= '<table cellspacing="0" width="100%" align="center"><tbody>';
    $tInfoCurso .= '<tr><td colspan="2"> ';
    $tInfoCurso .= '<div class="easyui-panel" title="Horario del curso" style="width:800px;height:auto;padding: 1px;margin: 0px" data-options="iconCls: \'fa fa-bell-o fa-lg\',collapsible:true,footer:\'#ft\'">';
    $tInfoCurso .= '<table id="dghorario" class="easyui-datagrid" aling="center" style="width:100%; height:auto" fitColumns="true" rownumbers="true"  singleSelect="true">';
    $tInfoCurso .= '<thead><tr>';
    $tInfoCurso .= "<th field='idcourse'width='50' sortable='true' styler='cellStyler'>CURSO</th>";
    $tInfoCurso .= "<th field='name' width='250' sortable='true' styler='cellStyler'>NOMBRE</th>";
    $tInfoCurso .= "<th field='section' width='60' styler='cellStyler'>SECCIÓN</th>";
    $tInfoCurso .= "<th field='building' width='60' styler='cellStyler'>EDIFICIO</th>";
    $tInfoCurso .= "<th field='idclassroom' width='60' styler='cellStyler'>SALON</th>";
    $tInfoCurso .= "<th field='starttime' width='60' styler='cellStyler'>INICIO</th>";
    $tInfoCurso .= "<th field='endtime' width='60' styler='cellStyler'>FINAL</th>";
    $tInfoCurso .= "<th field='mon' width='20' styler='cellStyler'>L</th>";
    $tInfoCurso .= "<th field='tue' width='20' styler='cellStyler'>M</th>";
    $tInfoCurso .= "<th field='wed' width='20' styler='cellStyler'>M</th>";
    $tInfoCurso .= "<th field='thu' width='20' styler='cellStyler'>J</th>";
    $tInfoCurso .= "<th field='fri' width='20' styler='cellStyler'>V</th>";
    $tInfoCurso .= "<th field='sat' width='20' styler='cellStyler'>S</th>";
    $tInfoCurso .= "<th field='sun' width='20' styler='cellStyler'>D</th>";
    $tInfoCurso .= "<th field='nombrecat' width='300' sortable='true' styler='cellStyler'>CATEDRÁTICO</th>";
    $tInfoCurso .= "<th field='type' width='60' sortable='true' styler='cellStyler' style='visibility: hidden !important;'>TIPO</th>";
    $tInfoCurso .= '</tr></thead>';
    $tInfoCurso .= '</table></div>';
    $tInfoCurso.= '<div id="ft" style="padding:5px;">';
    $tInfoCurso .= '<table><tr><td>[<font color=#3d3d3d>Clase Magistral</font>]</td><td>|</td><td><font color=#0000FF>[Laboratorio]</font></td><td>| <font color=#008000>[Práctica]</font></td><td>| <font color=#FF00CC>[Tutoria]</font></td></tr></table>';
    $tInfoCurso .= '</div>';
    $tInfoCurso .= '</td></tr>';
    $tInfoCurso .= '<tr><td colspan="2" align="center"><br></td></tr>';
    $tInfoCurso .= '<tr><td colspan="2" align="right"><table><tr><td style="border-right: 2px solid #4c4c4c">' . $enlaceDescarga . '</td><td style="border-right: 2px solid #4c4c4c"><a id="lbCargaManual" href="../../fw/controller/manager/D_ManuaEntryLoadNotesManager.php" class="easyui-linkbutton" ><i class="fa fa-hand-o-up fa-lg"></i>&nbsp;&nbsp;Carga manual</a></td><td><a id="lbCargaxArchivo" href="D_FileLoadCourseNotes.php" class="easyui-linkbutton"><i class="fa fa-file-excel-o fa-lg"></i>&nbsp;&nbsp;Carga por achivo</a></td></tr></table></td></tr>';
    $tInfoCurso .= ($msgnotas>0) ? "<tr><td colspan='2' ><div class='alert alert-danger'><h4><i class='fa fa-warning'></i> FUERA DE FECHA</h4>El período para el ingreso de notas ya ha finalizado o aún no se encuentra dentro de las fechas estipuladas para el ingreso de notas. Para poder realizar el proceso de carga de notas fuera de las fechas estipuladas en el calendario de labores debe solicitarlo a la Secretaria Académica.´</div></td></tr>" : '';
    $tInfoCurso .= '</tbody></table>';

    $result = '<div id="sitebody">';
    $result = $result . '<br><hr>';
    $result = $result . '<div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>';
    $result = $result . '<div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>';
    $result = $result . '<div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>';
    $result = $result . '<div class="siterow"><br/><div class="siterow-center"><span>CARGA DE NOTAS DE EXAMEN FINAL</span></div><br/></div>';
    $result = $result . '<div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label"> ' . "" . $curso . " - " . $_SESSION["sObjNotas"]->mNombreCorto . '</span><span class="page_label">de la carrera: </span><span class="underline_label">'.$obj_cad->StringCarrera('0'.$carrera).'</span></div>';
    $result = $result . '<div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label"> ' . $obj_cad->funTextoPeriodo($_SESSION["sPeriodo"]) . '</span><span class="page_label">de: </span><span class="underline_label"> ' . $_SESSION["sAnio"] . '</span><span class="page_time_label"> Fecha: ' . Date("d-m-Y") . '&nbsp;&nbsp;Hora: ' . Date("H:i") . ' </span></div>';
    $result = $result . '<hr>';
    $result = $result . '<div id="dynheader" class="restrict_right"></div>';
    $result = $result . '<div id="dynbody" class="restrict_right ff"><div id="notesrow2">' . $tInfoCurso . '</div></div>';
    $result = $result . '<div><hr></div>';
    $result = $result . '<br><br>';
    $result = $result . '</div>';
    $result = $result . "<div id='buttons'><input type=\"submit\" name=\"Regresar\" id=\"Regresar\" value=\"Regresar a listado de cursos\" class=\"nbtn rbtn btn_midi btn_exp_h okbutton\" onclick=\"location.href='D_CourseList.php?periodo=".$_SESSION["sPeriodo"]."&anio=".$_SESSION["sAnio"]."'\"/></div><div class=\"clear\"></div>";

    $cursop = $_SESSION["sObjNotas"]->mCurso;
    $seccionp = $_SESSION["sObjNotas"]->mSeccion;
    $periodop = $_SESSION["sObjNotas"]->mPeriodo;
    $aniop = $_SESSION["sObjNotas"]->mAnio;
    $laboratoriop = $_SESSION["sObjNotas"]->mLaboratorio;

    $_SESSION['laboratorio'] = $laboratoriop;
    $_SESSION['curso'] = $cursop;
    $_SESSION['seccion'] = $seccionp;
    $_SESSION['periodo'] = $periodop;
    $_SESSION['anio'] = $aniop;
    $_SESSION['regper'] = $reg_per;
    $_SESSION["nombrecorto"] = $_SESSION["sObjNotas"]->mNombreCorto;
    $_SESSION["tipoingreso"] = $_SESSION["sObjNotas"]->mLaboratorio;

    unset($_SESSION["sBdd"]);

    $tpl = new TemplatePower("D_CourseInformationReview.tpl");

    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");
    $tpl->assignInclude("icontenido",$result,T_BYVAR);

    $tpl->prepare();

    $tpl->assign("aCurso",$curso);
    $tpl->assign("aSeccion",$seccion);
    $tpl->assign("aIndex",$index);
    $tpl->assign("aCarrera",$carrera);

    $tpl->printToScreen();
    unset($tpl,$obj_cad);
    $Horario = NEW D_LoadNotesScheduleManager;

} else {
    //echo json_encode($Horario->obtenerHorarioCurso($_SESSION["sPeriodo"],$_SESSION["sAnio"],$curso,'',1/*$index,$curso,$_SESSION["sAnio"],$_SESSION["sPeriodo"],$carrera*/));
    $Horario = NEW D_LoadNotesScheduleManager;
    echo json_encode($Horario->obtenerHorarioCursoCarrera($index,$curso,$_SESSION["sAnio"],$_SESSION["sPeriodo"],$carrera));
    unset($Horario);
}
?>