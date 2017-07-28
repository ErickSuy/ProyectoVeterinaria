<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 29/10/2014
 * Time: 02:04 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/manager/AssignationGeneralsManager.php");
define('MODULARES','625,606,634,600,605,615,1605,610,625,606,634');
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
            $nombreP = "PRIMERA RETRASADA PRIMER SEMESTRE";
            break;
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "SEGUNDA RETRASADA PRIMER SEMESTRE";
            break;
        case SUFICIENCIAS_DEL_PRIMER_SEMESTRE:
            $nombreP = "SUFICIENCIA PRIMER SEMESTRE";
            break;
        case SEGUNDO_SEMESTRE:
            $nombreP = "SEGUNDO SEMESTRE";
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "VACACIONES DE DICIEMBRE";
            break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "PRIMERA RETRASADA SEGUNDO SEMESTRE";
            break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "SEGUNDA RETRASADA SEGUNDO SEMESTRE";
            break;
        case SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "SUFICIENCIA SEGUNDO SEMESTRE";
            break;
        default:
            $nombreP = "PRIMER SEMESTRE";
    }
    return $nombreP;
}

function obtenerPeriodoActivo()
{
    $diaActual = Date("d");
    $mesActual = Date("m");
    $anioActual = Date("Y");
    $fechaActual = mktime(0, 0, 0, $mesActual, $diaActual, $anioActual);
    $periodoActivo = PRIMER_SEMESTRE;
    if ($fechaActual >= mktime(0, 0, 0, "01", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "02", "02", $anioActual))
        $periodoActivo = PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
    else
        if ($fechaActual >= mktime(0, 0, 0, "02", "02", $anioActual) && $fechaActual < mktime(0, 0, 0, "03", "01", $anioActual))
            $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
        else
            if ($fechaActual >= mktime(0, 0, 0, "03", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "05", "15", $anioActual))
                $periodoActivo = PRIMER_SEMESTRE;
            else
                if ($fechaActual >= mktime(0, 0, 0, "05", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "07", "10", $anioActual))
                    $periodoActivo = VACACIONES_DEL_PRIMER_SEMESTRE;
                else
                    if ($fechaActual >= mktime(0, 0, 0, "07", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "08", "10", $anioActual))
                        $periodoActivo = PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE;
                    else
                        if ($fechaActual >= mktime(0, 0, 0, "08", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "09", "01", $anioActual))
                            $periodoActivo = SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE;
                        else
                            if ($fechaActual >= mktime(0, 0, 0, "09", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "11", "15", $anioActual))
                                $periodoActivo = SEGUNDO_SEMESTRE;
                            else
                                $periodoActivo = VACACIONES_DEL_SEGUNDO_SEMESTRE;
    return $periodoActivo;
}

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("SearchAssignedCourse.tpl");

$reg = new AssignationGeneralsManager($objuser->getId(), $objuser->getCareer(), obtenerPeriodoActivo(), Date("Y"));

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("iselectciclo", "../includes/selectciclos.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

if (isset($_POST["Buscar"])) {
    $periodoRevisado = $_POST["periodo"];
    $anioRevisado = $_POST["anio"];
} else {
    $periodoRevisado = obtenerPeriodoActivo();
    if ($periodoRevisado == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
        $anioRevisado = Date("Y") - 1;
    else
        if ($periodoRevisado == VACACIONES_DEL_SEGUNDO_SEMESTRE) {
            $fechaActual = mktime(0, 0, 0, Date("m"), Date("d"), Date("Y"));
            if ($fechaActual >= mktime(0, 0, 0, "01", "01", Date("Y")) && $fechaActual < mktime(0, 0, 0, "01", "10", Date("Y")))
                $anioRevisado = Date("Y") - 1;
            else
                $anioRevisado = Date("Y");
        } else
            $anioRevisado = Date("Y");
}
$anioActual = Date("Y");
$anioInicioCombo = 2014; // Apartir de aca es que hay informacion para mostrar

$tpl->gotoBlock("_ROOT");
for ($i = $anioInicioCombo; $i <= $anioActual; $i++) {
    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", $i);
}

$info = $reg->VerListadoCursosAsignados($periodoRevisado, $anioRevisado);

$tpl->gotoBlock("_ROOT");
$tpl->assign("aPeriodo", obtieneNombrePeriodo($periodoRevisado));
$tpl->assign("aAnio", $anioRevisado);
$tpl->assign('aEstudiante', ($objuser->getId() . ' - ' . $objuser->getName() . ' ' . $objuser->getSurName()));
$tpl->assign('aCarrera', $objuser->getCareerName());

if ($info == false) {
    $tpl->newBlock("b_sinasignacion");
} else {
    $tpl->newBlock("b_asignados");

    $info[0] = Date("d-m-Y");
    $info[1] = Date("H:i");

    $tpl->assign("aFechaAsignacion", $info[8]);
    $tpl->assign("aTransaccion", $info[9]);
    $tpl->assign("aFecha", $info[0]);
    $tpl->assign("aHora", $info[1]);

    $detalle = $reg->getAssignationDetailInfo($objuser->getId(),$objuser->getCareer(),$objuser->getCurriculum(),$anioRevisado,$periodoRevisado);
    $tpl->gotoBlock( "_ROOT" );
    if ($detalle) {
        $totaldet = sizeof($detalle);
        for ($i = 1; $i <= $totaldet; $i++) {
            $tpl->newBlock("b_detalleasignacion");
            $cursoo = $detalle[$i]["cur"];
            if (strcmp($cursoo,'160')==0) {
                $cursoo = '0'.$cursoo;
            }

            if(substr_count(MODULARES,$cursoo)!=0) {
                //$tpl->gotoBlock('_ROOT');
                $tpl->assign('aClaseFila', 'module');
                $tpl->assign('aFont', '#3d3d3d');
                $tpl->assign('aCurso', $detalle[$i]['cur']);
                $tpl->assign('aNombreCurso', $detalle[$i]['nom']);
                $tpl->assign('aSeccion', '');
                $tpl->assign('aEdificio', '');
                $tpl->assign('aSalon', '');
                $tpl->assign('aInicio', '');
                $tpl->assign('aFinal', '');
                $tpl->assign('aL', '');
                $tpl->assign('aM', '');
                $tpl->assign('aMi', '');
                $tpl->assign('aJ', '');
                $tpl->assign('aV', '');
                $tpl->assign('aS', '');
                $tpl->assign('aD', '');
            } else {
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

                $tpl->assign("aFont", $cellColor);

                $tpl->assign("aCurso", $detalle[$i]["cur"]);
                $tpl->assign("aNombreCurso", $detalle[$i]["nom"]);
                $tpl->assign("aSeccion", $detalle[$i]["sec"]);
                $tpl->assign("aEdificio", $detalle[$i]["edi"]);
                $tpl->assign("aSalon", $detalle[$i]["sal"]);
                $tpl->assign("aInicio", $detalle[$i]["ini"]);
                $tpl->assign("aFinal", $detalle[$i]["fin"]);
                $tpl->assign("aL", $detalle[$i]["lu"]);
                $tpl->assign("aM", $detalle[$i]["ma"]);
                $tpl->assign("aMi", $detalle[$i]["mi"]);
                $tpl->assign("aJ", $detalle[$i]["ju"]);
                $tpl->assign("aV", $detalle[$i]["vi"]);
                $tpl->assign("aS", $detalle[$i]["sa"]);
                $tpl->assign("aD", $detalle[$i]["do"]);
            }
        }
    }
}
$tpl->gotoBlock("_ROOT");
$tpl->assign("periodo", $periodoRevisado);
$tpl->assign("anio", $anioRevisado);
$tpl->printToScreen();
unset($tpl);
unset($reg);

?>