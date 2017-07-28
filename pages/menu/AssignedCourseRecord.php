<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 20/10/2014
 * Time: 05:10 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/AssignationMsgs.php");


session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
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

$tpl = new TemplatePower("AssignedCourseRecord.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("iselectciclo", "../includes/selectciclos.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$periodoRevisado = obtenerPeriodoActivo();
if ($periodoRevisado == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE || $periodoRevisado == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE){
    $anioRevisado = Date("Y") - 1;
} else {
    if ($periodoRevisado == VACACIONES_DEL_SEGUNDO_SEMESTRE) {
        $fechaActual = mktime(0, 0, 0, Date("m"), Date("d"), Date("Y"));
        if ($fechaActual >= mktime(0, 0, 0, "01", "01", Date("Y")) && $fechaActual < mktime(0, 0, 0, "01", "15", Date("Y"))) {
            $anioRevisado = Date("Y") - 1;
        } else {
            $anioRevisado = Date("Y");
        }
    } else {
        $anioRevisado = Date("Y");
    }
}

$anioActual = Date("Y");
$anioInicioCombo = $anioActual - 0;

if ($periodoRevisado == PRIMER_SEMESTRE || $periodoRevisado == VACACIONES_DEL_PRIMER_SEMESTRE || $periodoRevisado == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE || $periodoRevisado == SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE) {
    if ($anioInicioCombo < 2006)
        $anioInicioCombo = 2006;
} else {
    if ($anioInicioCombo < 2005)
        $anioInicioCombo = 2005;
}

for ($i = $anioInicioCombo; $i <= $anioActual; $i++) {
    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", $i);
}

$tpl->gotoBlock("_ROOT");
$tpl->assign("periodo", $periodoRevisado);
$tpl->assign("anio", $anioRevisado);

$tpl->printToScreen();
unset($tpl);

?>
