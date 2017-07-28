<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 17/01/2015
 * Time: 1:34 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/libconst.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/view/RegisterBLog.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/AssignationMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/ControlAssignationRequeriments1.php");
include_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");

define("PASO3_ASIGNACION_CURSOS", 153);
define('MODULARES','625,606,634,600,605,615,1605,610,625,606,634');

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);

if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$obj_cad = new ManejoString();
$tpl = new TemplatePower("AS_AssignationResult.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$objAssignationParams = unserialize($_SESSION['asignacion']);
$info = $objAssignationParams->getAssignationInfo($objuser->getId(), $objuser->getCareer(), $objuser->getCurriculum());

if ($info) { //pdateAssignationProcessCount($pStudent, $pCareer,$pGrupo,$pSitio)
    $objAssignationParams->updateAssignationProcessCount($objuser->getId(), $objuser->getCareer(), $objuser->getGroup(), SITIO_ASIGNACIONREGULAR, $objuser->getMainCareer());
    $horarioCursoAsignacion = $objAssignationParams->getAssignationDetailInfo($objuser->getId(), $objuser->getCareer(), $objuser->getCurriculum());
    $totaldet = sizeof($horarioCursoAsignacion);

    $tpl->assign('aPeriodo', $obj_cad->funTextoPeriodo($objAssignationParams->getSchoolYear()));
    $tpl->assign('aAnio', $objAssignationParams->getYear());
    $tpl->assign('aEstudiante', ($objuser->getId() . ' - ' . $objuser->getName() . ' ' . $objuser->getSurName()));
    $tpl->assign('aCarrera', $objuser->getCareerName());

// Formateo de la fecha d-m-YY
    $time = strtotime($info[8]);
    $month = date("m", $time);
    $year = date("Y", $time);
    $day = date("d", $time);

    $tpl->assign('aFechaAsignacion', $day . '-' . $month . '-' . $year);
    $tpl->assign('aTransaccion', $info[9]);
    $tpl->assign('aFecha',Date("d-m-Y"));
    $tpl->assign('aHora',Date("H:i"));

    for ($i = 1; $i <= $totaldet; $i++) {
        if(substr_count(MODULARES,$horarioCursoAsignacion[$i]['cur'])!=0) {
            //$tpl->gotoBlock('_ROOT');
            $tpl->newBlock('iasignacion');
            $tpl->assign('aClaseFila', 'module');
            $tpl->assign('aFont', '#3d3d3d');
            $tpl->assign('aCurso', $horarioCursoAsignacion[$i]['cur']);
            $tpl->assign('aNombreCurso', $horarioCursoAsignacion[$i]['nom']);
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
            // $tpl->gotoBlock('_ROOT');
            $tpl->newBlock('iasignacion');
            switch ($horarioCursoAsignacion[$i]["tip"]) {
                case 1:
                    $fontColor = "#3d3d3d";
                    break;
                case 2:
                    $fontColor = "#0000FF";
                    break;
                case 3:
                    $fontColor = "#008000";
                    break;
                case 4:
                    $fontColor = "#FF00CC";
                    break;
                case 5:
                    $fontColor = "#FF0000";
                    break;
            }

            $tpl->assign('aFont', $fontColor);

            $tpl->assign('aCurso', $horarioCursoAsignacion[$i]['cur']);
            $tpl->assign('aNombreCurso', $horarioCursoAsignacion[$i]['nom']);
            $tpl->assign('aSeccion', $horarioCursoAsignacion[$i]['sec']);
            $tpl->assign('aEdificio', $horarioCursoAsignacion[$i]['edi']);
            $tpl->assign('aSalon', $horarioCursoAsignacion[$i]['sal']);
            $tpl->assign('aInicio', $horarioCursoAsignacion[$i]['ini']);
            $tpl->assign('aFinal', $horarioCursoAsignacion[$i]['fin']);
            $tpl->assign('aL', $horarioCursoAsignacion[$i]['lu']);
            $tpl->assign('aM', $horarioCursoAsignacion[$i]['ma']);
            $tpl->assign('aMi', $horarioCursoAsignacion[$i]['mi']);
            $tpl->assign('aJ', $horarioCursoAsignacion[$i]['ju']);
            $tpl->assign('aV', $horarioCursoAsignacion[$i]['vi']);
            $tpl->assign('aS', $horarioCursoAsignacion[$i]['sa']);
            $tpl->assign('aD', $horarioCursoAsignacion[$i]['do']);
        }
    }
}

$objBLog = new RegisterBLog();
$objBLog->DarSitio(PASO3_ASIGNACION_CURSOS);
$objBLog->RegistroNavegacion($objuser->getId(), $objuser->getGroup(), 0);

$tpl->printToScreen();

unset($objBLog,$tpl,$obj_cad);

?>