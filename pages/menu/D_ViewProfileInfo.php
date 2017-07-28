<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 12/08/14
 * Time: 09:36 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/manager/UserManager.php");

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("D_ViewProfileInfo.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$objUserManager = new UserManager(null);
$vector_modi[0] = $objuser->getId();
$vector_modi[1] = $objuser->getGroup();

$result = $objUserManager->DatosDocente($vector_modi);

// Tab: General
$tpl->assign('aGrupo',$objuser->getGroupName());
$tpl->assign('aPersonal',$objuser->getId());
$tpl->assign('aTitularidad',$objuser->getRatingTypeName());
$tpl->assign('aNombre',$objuser->getName());
$tpl->assign('aApellido',$objuser->getSurName());
$time = strtotime($result['fechanac']);
$month = date("m", $time);
$year = date("Y", $time);
$day = date("d", $time);
$tpl->assign('aFechaNacimiento',$day . '/' . $month . '/' . $year);
$tpl->assign('aCedula',$result['cedula']);
$tpl->assign('aDpi',$result['dpi']);
$tpl->assign('aExtMunicipio',$result['cedula_munic_nombre']);
$tpl->assign('aExtDepto',$result['cedula_depto_nombre']);
$tpl->assign('aGenero',$result['sexo_nombre']);

//$tpl->assign('aFechaNacimiento',$objuser->getBirthdateText());

// Tab: Dirección
$tpl->assign('aDereccion',$result['direccion']);
$tpl->assign('aMunicipio',$result['dir_munic_nombre']);
$tpl->assign('aDepartamento',$result['dir_depto_nombre']);
$tpl->assign('aDepartamento',$result['dir_depto_nombre']);
$tpl->assign('aNacionalidad',$result['nacionalidad_nombre']);

// Tab: Contacto
$tpl->assign('aCorreoP',trim($result['correo1']));
$tpl->assign('aTelP',trim($result['tel1']));
$tpl->assign('aCelP',trim($result['cel1']));

$tpl->gotoBlock('_ROOT');
$tpl->assign( "anchoImg", ANCHO_IMG);
$tpl->assign( "altoImg", ALTO_IMG);

$tpl->printToScreen();

unset($tpl,$objUserManager);

?>


