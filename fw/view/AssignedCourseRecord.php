<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 25/01/2015
 * Time: 11:42 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/AssignationMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/model/sql/AssignedCourseRecord_SQL.php");
include_once("$dir_portal/fw/controller/manager/AssignationGeneralsManager.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);

/*
 * Validación de existencia de sesión
 */
if (!$objuser) {
    header("Location: ../index.php");
}

global $gsql_a_nca;
$gsql_a_nca = new AssignedCourseRecord_SQL();

function MiconectarBase()
{
    $base = NEW DB_Connection;
    $base->connect();
    return $base;
}

//haya aprobado dichos laboratorios en semestres anteriores, y que la nota aún se encuentre vigente en el período/año para el que se
//muestran las notas de cursos asignados.
function notasLaboratorioVigentes($txtPeriodo, $txtAnio, $txtCarnet, $txtCarrera)
{
    global $gsql_a_nca;

    $_vector = array();
    $Mibd = MiconectarBase();
    $compAdicional1 = "";
    $compAdicional2 = "";
    switch ($txtPeriodo) {
        case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE :
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE :
            $txtPeriodo = PRIMER_SEMESTRE;
            break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE :
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE :
            $txtPeriodo = SEGUNDO_SEMESTRE;
            break;
    }
    switch ($txtPeriodo) {
        case PRIMER_SEMESTRE :
            $compAdicional1 = $gsql_a_nca->__select11__($txtAnio);
            $compAdicional2 = $gsql_a_nca->__select12__($txtAnio);
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE :
            $compAdicional1 = $gsql_a_nca->__select21__($txtAnio);
            $compAdicional2 = $gsql_a_nca->__select22__($txtAnio);
            break;
        case SEGUNDO_SEMESTRE :
            $compAdicional1 = $gsql_a_nca->__select31__($txtAnio);
            $compAdicional2 = $gsql_a_nca->__select32__($txtAnio);
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE :
            $compAdicional1 = $gsql_a_nca->__select41__($txtAnio);
            $compAdicional2 = $gsql_a_nca->__select42__($txtAnio);
            break;
    }

    $sqlQuery = $gsql_a_nca->notasLaboratorioVigentes_select1($compAdicional1, $compAdicional2, $txtPeriodo, $txtAnio, $txtCarnet, $txtCarrera);
//echo $sqlQuery;
    $Mibd->query($sqlQuery);
    $total = $Mibd->num_rows();
    if ($total > 0) {
        for ($i = 0; $i < $total; $i++) {
            $Mibd->next_record();
            $FilaDato = $Mibd->r();
            $_vector[$FilaDato["idstudent"]][$FilaDato["idcourse"]] = (int)($FilaDato["labnote"]);
        }
    }
    if (isset($Mibd))
        unset($Mibd);
    return $_vector;
}

function obtieneNombrePeriodo($periodo)
{
    $nombreP = "";
    switch ($periodo) {
        case PRIMER_SEMESTRE:
            $nombreP = "PRIMER SEMESTRE";
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE:
            $nombreP = "VACACIONES DE JUNIO";
            break;
        case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "PRIMERA RETRASADA (PRIMER SEMESTRE)";
            break;
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "SEGUNDA RETRASADA (PRIMER SEMESTRE)";
            break;
        case SEGUNDO_SEMESTRE:
            $nombreP = "SEGUNDO SEMESTRE";
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "VACACIONES DE DICIEMBRE";
            break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "PRIMERA RETRASADA (SEGUNDO SEMESTRE)";
            break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "SEGUNDA RETRASADA (SEGUNDO SEMESTRE)";
            break;
        default:
            $nombreP = "PRIMER SEMESTRE";
    }
    return $nombreP;
}

function obtieneEstadoActa($estado, $tipoacta)
{
    $nombreE = "";
    switch ($estado) {
        case  2:
            $nombreE = "Pendiente de Procesar";
            break;
        case  3:
        case  4:
            $nombreE = "Ingresada vía web (Acta en Proceso)";
            break;
        case  5:
        case  6:
        case  7:
        case  8:
        case  9:
        case 10:
        case 11:
        case 12:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
        case 701:
        case 702:
        case 703:
        case 801:
        case 802:
        case 803:
        case 804:
        case 1001:
            if ($tipoacta == 'N')
                $nombreE = "Acta en Proceso";
            else
                $nombreE = "Aprobada vía web (Acta en Proceso)";
            break;
        case 13:
        case 14:
        case 1401:
            $nombreE = "Acta con notas reales";
            break;
        default:
            $nombreE = "Revisión por administrador";
    }
    return $nombreE;
}

function obtenerPeriodoActivo()
{
    $diaActual = Date("d");
    $mesActual = Date("m");
    $anioActual = Date("Y");
    $fechaActual = mktime(0, 0, 0, $mesActual, $diaActual, $anioActual);
    if ($fechaActual >= mktime(0, 0, 0, "01", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "02", "15", $anioActual))
        $periodoActivo = PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
    else
        if ($fechaActual >= mktime(0, 0, 0, "02", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "03", "15", $anioActual))
            $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
        else
            if ($fechaActual >= mktime(0, 0, 0, "03", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "06", "05", $anioActual))
                $periodoActivo = PRIMER_SEMESTRE;
            else
                if ($fechaActual >= mktime(0, 0, 0, "06", "05", $anioActual) && $fechaActual < mktime(0, 0, 0, "07", "10", $anioActual))
                    $periodoActivo = VACACIONES_DEL_PRIMER_SEMESTRE;
                else
                    if ($fechaActual >= mktime(0, 0, 0, "07", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "08", "05", $anioActual))
                        $periodoActivo = PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE;
                    else
                        if ($fechaActual >= mktime(0, 0, 0, "08", "05", $anioActual) && $fechaActual < mktime(0, 0, 0, "09", "15", $anioActual))
                            $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
                        else
                            if ($fechaActual >= mktime(0, 0, 0, "09", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "12", "31", $anioActual))
                                $periodoActivo = SEGUNDO_SEMESTRE;
                            else
                                $periodoActivo = VACACIONES_DEL_SEGUNDO_SEMESTRE;
    return $periodoActivo;
}

$reg = new AssignationGeneralsManager($objuser->getId(),$objuser->getCareer(),obtenerPeriodoActivo(),Date("Y"));
$objString = new ManejoString();

if (isset($_POST["Buscar"])) {
    $periodoRevisado = $_POST["periodo"];
    $anioRevisado = $_POST["anio"];
}

$info = $reg->VerNotasCursosAsignados($periodoRevisado, $anioRevisado);

$result = $result . '<div class="easyui-panel" style="width:inherit;height:auto;">';
$result = $result . '<div id="sitebody">';
$result = $result . '<br><hr>';
$result = $result . '<div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>';
$result = $result . '<div class="siterow"><br/><div class="siterow-center"><span>NOTAS DE CURSOS ASIGNADOS</span></div><br/></div>';
$result = $result . '<div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">' . $objString->funTextoPeriodo($periodoRevisado) . '</span><span class="page_label">de: </span><span class="underline_label">' . $anioRevisado . '</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">Estudiante: </span><span class="underline_label">' . ($objuser->getId() . ' - ' . $objuser->getName() . ' ' . $objuser->getSurName()) . '</span><span class="page_label">Carrera: </span><span class="underline_label">' . $objuser->getCareerName() . '</span></div>';

if ($info == false) {
    $result = $result . '<hr>';
    $result = $result . '<div id="dynheader" class="restrict_right"></div>';
    $result = $result . '<div id="dynbody" class="restrict_right">';
    $result = $result . '<div id="notesrow2">';
    $result = $result . '<textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">';
    $result = $result . 'No tiene cursos asignados.';
    $result = $result . '</textarea>';
    $result = $result . '</div>';
    $result = $result . '</div>';

    $result = $result . '</div>';
    $result = $result . '</div>';

    $result = $result . '';
    
} else {
    $result = $result . '<div class="siterow"><span class="page_label">Fecha de asignación:</span><span class="underline_label"> ' . date("d-m-Y",strtotime($info[8])) . '</span><span class="page_label">Transacción No.: </span><span class="transaction_label">' . $info[9] . '</span></div>';
    $$result = $result . '<hr>';
    $result = $result . '<div id="dynheader" class="restrict_right"></div>';
    $result = $result . '<div id="dynbody" class="restrict_right">';
    $result = $result . '<div id="notesrow2">';

    $result = $result . '<div class="easyui-panel" style="width:inherit;height:auto;" data-options="footer:\'#ft\'">';
    $result = $result . '<table class="RAsig-table" align=\'center\' width=\'100%\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\'>';
    $result = $result . '<thead>';
    $result = $result . '<tr>';
    $result = $result . '<td width="5%">CURSO</td>';
    $result = $result . '<td width="70%">NOMBRE</td>';
    $result = $result . '<td width="7%">LABORATORIO</td>';
    $result = $result . '<td width="5%" align="center">ZONA</td>';
    $result = $result . '<td width="5%" align="center">FINAL</td>';
    $result = $result . '<td width="8%" align="center">ESTADO DEL ACTA</td>';
    $result = $result . '</tr>';
    $result = $result . '</thead>';
    $result = $result . '<tbody>';
    $result = $result . '';


    $cursosConLaboratorio = array();
    $detalle = $reg->VerDetalleNotas($periodoRevisado, $anioRevisado, $cursosConLaboratorio);

    if ($detalle) {
        $totaldet = sizeof($detalle);
        if ($periodoRevisado == PRIMER_SEMESTRE || $periodoRevisado == VACACIONES_DEL_PRIMER_SEMESTRE || $periodoRevisado == SEGUNDO_SEMESTRE || $periodoRevisado == VACACIONES_DEL_SEGUNDO_SEMESTRE) {

        } else{
            $notasPracticasVigentes = array();
        }

        $notasLabVigentes = notasLaboratorioVigentes($periodoRevisado, $anioRevisado, $info[5], $info[6]);
        for ($i = 1; $i <= $totaldet; $i++) {
            switch ($detalle[$i]["tip"]) {
                case 1:
                    $cellColor = "#3d3d3d";
                    break;
                case 2:
                    $cellColor = "#0000FF";
                    break;
                case 3:
                    $cellColor = "#008000";
                    break;
                case 4:
                    $cellColor = "#FF00CC";
                    break;
                case 5:
                    $cellColor = "#FF0000";
                    break;
            }

            $curso = $detalle[$i]["cur"];
            $nombreCurso = $detalle[$i]["nom"];
            $seccionCurso = $detalle[$i]["sec"];

            if ($detalle[$i]["lab"] == 0 && isset($notasLabVigentes) && sizeof($notasLabVigentes) > 0) {
                if (isset($notasLabVigentes[$info[5]][$detalle[$i]["cur"]])) {
                    $lab = $notasLabVigentes[$info[5]][$detalle[$i]["cur"]];
                } else {
                    $lab = $detalle[$i]["lab"];
                }
            } elseif ($detalle[$i]["zon"] < 31 && isset($cursosConLaboratorio) && sizeof($cursosConLaboratorio) > 0 && $detalle[$i]["est"] >= 5) {
                //Si el curso lleva laboratorio y además pertenece a escuelas donde deben llegar a zona minima para que les valga el lab
                if (isset($cursosConLaboratorio[$detalle[$i]["cur"]]) && ($detalle[$i]["esc"] == 1 || $detalle[$i]["esc"] == 5)) {
                    $lab = 0;
                } else {
                    $lab = $detalle[$i]["lab"];
                }

            } else {
                $lab = $detalle[$i]["lab"];
            }

            $zona = $detalle[$i]["zon"];

            if ($detalle[$i]["cur"] == '2025' || $detalle[$i]["cur"] == '2036')
                switch ($detalle[$i]["eef"]) {
                    case -3:
                        $tpl->assign("vExamen", "APROBADO");
                        break;
                    case -4:
                        $tpl->assign("vExamen", "REPROBADO");
                        break;
                    default:
                        $tpl->assign("vExamen", $detalle[$i]["exa"]);
                }
            else {
                $examen = $detalle[$i]["exa"];
            }

            $estado = obtieneEstadoActa($detalle[$i]["est"], $detalle[$i]["tip"]);

            $result = $result . '<tr>';
            $result = $result . '<td>' . $curso . '</td>';
            $result = $result . '<td>' . $nombreCurso . '</td>';
            $result = $result . '<td>' . $lab. '</td>';
            $result = $result . '<td>' . $zona . '</td>';
            $result = $result . '<td>' . $examen . '</td>';
            $result = $result . '<td>' . $estado . '</td>';
            $result = $result . '</tr>';
        }
        if (isset($notasPracticasVigentes))
            unset($notasPracticasVigentes);
        if (isset($notasLabVigentes))
            unset($notasLabVigentes);
        if (isset($cursosConLaboratorio))
            unset($cursosConLaboratorio);
    }

    $result = $result . '</body>';
    $result = $result . '</table>';
    $result = $result . '</div>';//Del panel
    $result = $result . '<div id="ft" class="panel-footer" style="padding:5px;">';
    $result = $result . ' <table><tr><td align="right"><span class="page_time_label"> Fecha: ' . Date("d-m-Y") . '&nbsp;&nbsp;Hora: ' . Date("H:i") . ' </span></td></tr></table>';
    $result = $result . '</div>';
    $result = $result . '</div>';
    $result = $result . '</div>';

    $result = $result . '</div>';
    $result = $result . '</div>';

    $result = $result . '';
}

unset ($objString);
echo $result;

?>