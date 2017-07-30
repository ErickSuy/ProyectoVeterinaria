<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 28/09/14
 * Time: 12:30 PM
 */

function obtieneNombrePeriodo($periodo)
{
    $nombreP = "";
    switch ($periodo) {
        case PRIMER_SEMESTRE:
            $nombreP = "Primer Semestre";
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE:
            $nombreP = "Vacaciones de Junio";
            break;
        case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "Primera Retrasada Primer Semestre";
            break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Segunda Retrasada Primer Semestre";
            break;
        case SEGUNDO_SEMESTRE:
            $nombreP = "Segundo Semestre";
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Vacaciones de Diciembre";
            break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Primera Retrasada Segundo Semestre";
            break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Segunda Retrasada Segundo Semestre";
            break;
        default:
            $nombreP = "Primer Semestre";
    }
    return $nombreP;
}

function obtenerPeriodoActivo()
{
    $diaActual = Date("d");
    $mesActual = Date("m");
    $anioActual = Date("Y");
    $fechaActual = mktime(0, 0, 0, $mesActual, $diaActual, $anioActual);
    if ($fechaActual >= mktime(0, 0, 0, "01", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "02", "10", $anioActual))
        $periodoActivo = PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
    else
        if ($fechaActual >= mktime(0, 0, 0, "02", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "03", "01", $anioActual))
            $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
        else
            if ($fechaActual >= mktime(0, 0, 0, "03", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "05", "22", $anioActual))
                $periodoActivo = PRIMER_SEMESTRE;
            else
                if ($fechaActual >= mktime(0, 0, 0, "05", "22", $anioActual) && $fechaActual < mktime(0, 0, 0, "05", "31", $anioActual))
                    $periodoActivo = PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE;
                else
                    if ($fechaActual >= mktime(0, 0, 0, "05", "31", $anioActual) && $fechaActual < mktime(0, 0, 0, "06", "15", $anioActual))
                        $periodoActivo = SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE;
                    else
                        if ($fechaActual >= mktime(0, 0, 0, "06", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "07", "15", $anioActual))
                            $periodoActivo = VACACIONES_DEL_PRIMER_SEMESTRE;
                        else
                            if ($fechaActual >= mktime(0, 0, 0, "07", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "11", "15", $anioActual))
                                $periodoActivo = SEGUNDO_SEMESTRE;
                            else
                                $periodoActivo = VACACIONES_DEL_SEGUNDO_SEMESTRE;
    return $periodoActivo;
}

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("D_InitLoadNotes.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("iselectciclo", "../includes/selectciclos.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

if($objuser->getGroup()==GRUPO_CONTROL_ACADEMICO) {
    $tpl->assign("aPost", 'COAC_ActCourseList.php');
    $tpl ->assign("print",'COAC_ActCourseList.php');//BORRAR
} else {
    $tpl->assign("aPost", 'D_CourseList.php');
    $tpl ->assign("print",'D_CourseList.php');//BORRAR
}

$anioInicio = 2014;
$anioFin = Date("Y");
for ($_anio = $anioInicio; $_anio <= $anioFin; $_anio++) {
    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", $_anio);

}

$periodoRevisado=obtenerPeriodoActivo();
if ($periodoRevisado==PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE ||$periodoRevisado==SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
    $anioRevisado=Date("Y")-1;
else
    if ($periodoRevisado==VACACIONES_DEL_SEGUNDO_SEMESTRE) {
        $fechaActual=mktime(0,0,0,Date("m"),Date("d"),Date("Y"));
        if ($fechaActual>=mktime(0,0,0,"01","01",Date("Y")) && $fechaActual<mktime(0,0,0,"01","10",Date("Y")))
            $anioRevisado=Date("Y")-1;
        else
            $anioRevisado=Date("Y");
    } else
        $anioRevisado=Date("Y");

$tpl->gotoBlock('_ROOT');
$tpl->assign("aAnio", $anioRevisado);
$tpl->assign("aPeriodo", $periodoRevisado);

$tpl->printToScreen();
unset($tpl);
?>