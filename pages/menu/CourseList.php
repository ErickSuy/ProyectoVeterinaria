<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 14/08/14
 * Time: 07:16 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/ControlCourse.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("CourseList.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);
$result = $objControlCourse->getCourseList($objuser);

$tpl->gotoBlock('_ROOT');
$tpl->assign('aEstudiante',($objuser->getId() . ' - ' . $objuser->getName() . ' ' . $objuser->getSurName()));
$tpl->assign('aCarrera',$objuser->getCareerName());
$tpl->assign('aPensum',$objuser->getCurriculumName());
$tpl->assign('aPromedio','0');
$tpl->assign('aTotalCursos','0');

if($result) {
    $result1 = $objControlCourse->getCourseListInfo($objuser);
    if($result1) {
        $tpl->assign('aPromedio',$result1[0]['avg']);
        $tpl->assign('aTotalCursos',$result1[0]['numc']);
    }

    //$tpl->gotoBlock('_ROOT');
    $tpl->newBlock('b_aprobados');

    $catalogoAprobados = $result[0];

    foreach($catalogoAprobados as $curso) {
        $tpl->newBlock('detalleaprobados');
        $tpl->assign('aNum',$curso['num']);
        $tpl->assign('aCurso',$curso['cod']);
        $tpl->assign('aNombreCurso',$curso['nom']);
        $tpl->assign('aCreditos',$curso['cred']);
        $tpl->assign('aFechaAprobacion',$curso['fechaa']);
        $tpl->assign('aNota',strcmp($curso['nota'],'EQV')==0?$curso['nota']:(int)$curso['nota']);
    }

    if($result[1] != NULL && count($result[1])) {
        $catalogoCierre = $result[1];
        $tpl->newBlock('b_cierre');
        foreach($catalogoCierre as $curso) {
            $tpl->newBlock('b_detalleaprobadoscierre');
            $tpl->assign('aNum',$curso['num']);
            $tpl->assign('aCurso',$curso['cod']);
            $tpl->assign('aNombreCurso',$curso['nom']);
            $tpl->assign('aNota',(int)$curso['nota']);
            $tpl->assign('aFechaAprobacion',$curso['fechaa']);
            $tpl->assign('aObservacion',$curso['desc']);
            ;
        }
    }
    $tpl->gotoBlock('_ROOT');
    $tpl->assign('aFecha',Date("d-m-Y"));
    $tpl->assign('aHora',Date("H:i"));
} else {
    $tpl->newBlock('b_sinaprobados');
}

$tpl->printToScreen();
unset($tpl,$objControlCourse);

?>