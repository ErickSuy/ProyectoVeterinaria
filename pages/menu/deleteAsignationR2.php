<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 31/01/2015
 * Time: 2:27 PM
 */

//
// ------------------------------------------------
// Guardar datos del formulario Información de Graduandos
// -------------------------------------------------

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/UserManager.php");
include_once("$dir_portal/fw/controller/ControlCourse.php");
include_once("$dir_portal/fw/controller/RequestOrden.php");

include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");

session_start();
$_SESSION["contador2"]=1; //variable bandera para no insertar dos veces
if($_SESSION["contador"] != 0)
{
    unset($_SESSION["contador"]);
    unset($_SESSION["contador2"]);
    header("Location: ../../pages/estudiantes/estudiantes.htm");
}
else
{
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

//Creacion de los objetos
$tpl = new TemplatePower("deleteAsignationR2.tpl");
$obj_cad = new ManejoString();
$objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);

//Asignacion de menús generales a la plantilla
$vector_modi[0] = $objuser->getId();
$vector_modi[1] = $objuser->getGroup();
$vector_modi[2] = $objuser->getCareer();
$vector_modi[3] = $objuser->getName();

$obj_pin = new UserManager($vector_modi[1]);

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();  //TemplatePower se prepara


//******************** OBTENIENDO PARAMETROS 
$noOrden=$_GET["noOrden"];

$resultDelete=$objControlCourse->moveOrdenR2($noOrden);
$result=0;

if($resultDelete){
    foreach ($resultDelete as $value) {
        $result=$value['result'];
    }
}

if($result==1){
    $tpl->newBlock('BOLETA');
    $tpl->assign('aClass','msg-info-txt');
    $tpl->assign('aIcono','fa fa-check fa-2x');
    $tpl->assign('aMensaje','SE ELIMINÓ CORRECTAMENTE LA ORDEN: '.$noOrden);
}else{
    $tpl->newBlock('BOLETA');
    $tpl->assign('aClass','msg-danger-txt');
    $tpl->assign('aIcono','fa fa-close fa-2x');
    $tpl->assign('aMensaje','NO SE PUDO ELIMINAR LA ORDEN: '.$noOrden);
}

$_SESSION["contador"] = 1;
$tpl->printToScreen();
unset($_SESSION["NombreCursos"]);
unset($tpl);
unset($obj_pin);
unset($obj_cad);
}
?>