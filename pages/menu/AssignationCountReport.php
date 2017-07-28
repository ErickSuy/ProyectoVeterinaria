<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 30/10/2014
 * Time: 07:53 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/msg/StudentMsgs.php");
include_once("$dir_portal/fw/model/config/DB_Params.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/manager/AssignationCountReportManager.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("AssignationCountReport.tpl");
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();
$consulta = new AssignationCountReportManager($objuser->getId(),$objuser->getCareer(),$objuser->getCurriculum());
$consulta->mtipoConsulta = $_GET['type'];
$consulta->posVector = -1;
$consulta->numeroVeces();

//printf("tipo de envio %d<br>",$consulta->mtipoConsulta);
switch ($consulta->mtipoConsulta) {
    case -1:
        $mensaje = 'SEMESTRE Y VACACIONES';
        break;
    case 1:
        $mensaje = 'SEMESTRE';
        break;
    case 2:
        $mensaje = 'VACACIONES';
        break;
}

$tpl->assign('aCiclo',$mensaje);

if ($consulta->posVector >= 0) {
    //$tpl->newBlock("contenido");
    $tpl->assign('aEstudiante', ($consulta->mUsuario . ' - ' . $consulta->mNombre . ' ' . $consulta->mApellido));
    $tpl->assign('aCarrera', $objuser->getCareerName());

    if ($consulta->mtipoConsulta == -1) {
        $tpl->newBlock("b_conteogeneral");
    } else {
        $tpl->newBlock("b_conteociclo");
    }
//printf("numero cursos : %d<br>",$consulta->posVector);
    $poss = 0;
    for ($pos = 0; $pos <= $consulta->posVector; $pos++) {

        if (strcmp($consulta->mVectorCurso['curso'][$pos], "") != 0) {
            if ($consulta->mtipoConsulta == -1) {
                $poss ++;
                $tpl->newBlock("detalleconteogeneral");
                $tpl->assign("vCod", $consulta->mVectorCurso['curso'][$pos]);
                $tpl->assign("vCurso", $consulta->mVectorCurso['nombre'][$pos]);
                $tpl->assign("vNumSem", $consulta->mVectorCurso['semestre'][$pos]);
                $tpl->assign("vNumVac", $consulta->mVectorCurso['vacaciones'][$pos]);
            } else {
                $poss ++;
                $tpl->newBlock("detalleconteociclo");
                $tpl->assign("vCod", $consulta->mVectorCurso['curso'][$pos]);
                $tpl->assign("vCurso", $consulta->mVectorCurso['nombre'][$pos]);
                switch ($consulta->mtipoConsulta) {
                    case 1:
                        $tpl->assign("vNumVeces", $consulta->mVectorCurso['semestre'][$pos]);
                        break;
                    case 2:
                        $tpl->assign("vNumVeces", $consulta->mVectorCurso['vacaciones'][$pos]);
                        break;
                }
            }
        }

    }
    if ($consulta->mtipoConsulta == -1) {
        $tpl->gotoBlock( "b_conteogeneral" );
    } else {
        $tpl->gotoBlock( "b_conteociclo" );
    }

    $tpl->assign("aFecha", Date("d-m-Y"));
    $tpl->assign("aHora", Date("H:i"));
} else {
    $tpl->gotoBlock("_ROOT");
    $tpl->newBlock("b_sinhistorial");
}
$tpl->gotoBlock("_ROOT");

$tpl->printToScreen();
unset($tpl);
unset($reg);

?>