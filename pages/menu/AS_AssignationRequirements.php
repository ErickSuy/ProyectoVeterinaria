<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 13/01/2015
 * Time: 7:28 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/ControlAssignationRequeriments1.php");
include_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);

if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("AS_AssignationRequirements.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$objControlService = new ControlAssignationRequeriments1();
$objAssignationMngmt = new AssignationParamHandler(date("Y"), ASIGNACION_DE_SEMESTRE);

$result = $objControlService->checkProcessAssignationSem(date("Y"), PRIMER_SEMESTRE, SEGUNDO_SEMESTRE, ASIGNACION_DE_SEMESTRE, date('Y-m-d H:i:s'), $objuser, $objAssignationMngmt);

if ($result[0]['result'] == OK) {
    $objAssignationMngmt->setSchedule($objControlService->getScheduleInformation($objAssignationMngmt->getYear(), $objAssignationMngmt->getSchoolYear(), $objuser->getCurriculum(), $objuser->getCareer(), CLASE_MAGISTRAL));

    $_SESSION['asignacion'] = serialize($objAssignationMngmt);
    $_SESSION['usuario'] = serialize($objuser);
    $tpl->assign('aButton','<input name="btnSubmit" type="submit" onclick="Siguiente();"  class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Siguiente paso >>">');
} else{
    $tpl->assign('aButton','<input name="btnSubmit" type="submit" onclick="Cancelar();"  class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Cancelar">');
}

for($i=1;$i<count($result);$i++) {
    $tpl->newBlock('MENSAJES');
    if($result[$i]['result'] == OK) {
        $tpl->assign('aClass','msg-info-txt');
        $tpl->assign('aIcono','fa fa-check fa-2x');
    } else {
        $tpl->assign('aClass','msg-danger-txt');
        $tpl->assign('aIcono','fa fa-close fa-2x');
    }
    $tpl->assign('aMensaje',$result[$i]['msg']);
}

$tpl->printToScreen();

unset($tpl,$objControlService,$objAssignationMngmt);

?>