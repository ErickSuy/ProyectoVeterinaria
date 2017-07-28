<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 31/10/2014
 * Time: 07:42 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/sql/Transaction.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/EnrollmentMsgs.php");
include_once("$dir_portal/fw/controller/manager/EnrollmentManager.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl  = new TemplatePower("Enrollment.tpl");
$reg  = new EnrollmentManager($objuser->getId(),$objuser->getCareer());

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$infos = $reg->VerHistorialInscripcion();

if (isset($_POST["Buscar"])) {
    $anioRevisado = $_POST["anio"];
    $tpl->newBlock("b_contenido");
    $encontrado = FAIL;

    foreach($infos as $i=>$info){
        if ($info[2]==$anioRevisado){
            $tpl->assign("aEstudiante",($info[5] . ' - ' . $info[3] . ' ' . $info[4]));
            $tpl->assign("aCarrera",$info[7]);

            $tpl->newBlock('b_detalle');

            $tpl->assign("vCiclo",$info[2]);
            $tpl->assign("vFechaIns",date("d-m-Y",strtotime($info[8])));
            $tpl->assign("vObser","INSCRITO EN RYE");
            $tpl->assign('aFecha',$info[0]);
            $tpl->assign('aHora',$info[1]);
            $encontrado = OK;
            break;
        }
    }

    if($encontrado==FAIL) {
        $tpl->assign('aEstudiante', ($objuser->getId() . ' - ' . $objuser->getName() . ' ' . $objuser->getSurName()));
        $tpl->assign('aCarrera', $objuser->getCareerName());

        $tpl->newBlock('b_sindatos');
        $tpl->assign("aAnioConsulta",$anioRevisado);
    }
}

if ($infos) {
    $tpl->newBlock("b_selecthistorial");

    foreach($infos as $i=>$info){
        $tpl->newBlock("selectAnio");
        $tpl->assign("anio_select",$info[2]);
    }
}else{
    $tpl->newBlock("b_sinhistorial");
}
//imprime el resultado
$tpl->gotoBlock("_ROOT");
$tpl->assign("anio",isset($anioRevisado) ? $anioRevisado : Date("Y"));
$tpl->printToScreen();
unset($tpl);
unset($reg);
?>