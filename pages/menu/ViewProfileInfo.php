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

session_start();
$_SESSION["contador"] = 0;
$_SESSION["contador2"] = 0;
unset($_SESSION["contador"]);
unset($_SESSION["contador2"]);
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}
$tpl = new TemplatePower("ViewProfileInfo.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

// Tab: General
$tpl->assign('aGrupo',$objuser->getGroupName());
$tpl->assign('aCarrera',$objuser->getCareerName());
$tpl->assign('aCarnet',$objuser->getId());
$tpl->assign('aNombre',$objuser->getName());
$tpl->assign('aApellido',$objuser->getSurName());
$tpl->assign('aGenero',$objuser->getGender());
$tpl->assign('aFechaNacimiento',$objuser->getBirthdateText());

// Tab: Formación
$tpl->assign('aCarreraTitulo',$objuser->getTitlePrefix());
$tpl->assign('aCarreraBr',$objuser->getTitle());
$tpl->assign('aEstablecimiento',$objuser->getInstitutionName());

// Tab: Dirección
$tpl->assign('aDomicilio',$objuser->getAddress());
$tpl->assign('aLugarNacimiento',$objuser->getBirthAddres());
$tpl->assign('aNacionalidad',$objuser->getNationality());

// Tab: Contacto
$tpl->assign('aCorreoP',$objuser->getMail());
$tpl->assign('aCorreoA',$objuser->getAlternateMail());
$tpl->assign('aTelP',$objuser->getPhone());
$tpl->assign('aCelP',$objuser->getCelular());
$tpl->assign('aTelA',$objuser->getAlternatePhone());
$tpl->assign('aCelA',$objuser->getAlternateCelular());

$tpl->gotoBlock('_ROOT');
$tpl->assign( "anchoImg", ANCHO_IMG);
$tpl->assign( "altoImg", ALTO_IMG);

//$connection = new MongoClient(MONGO_CLIENT);
//$db = $connection->selectDB(MONGO_DB);

//$grid = $db->getGridFS(MONGO_COLLECTION);
//$image = $grid->findOne($objuser->getId());

//header("Content-type: image/jpeg");
//if (null == $image) {
  //  $image = $grid->findOne('000000000');
    //echo $image->getBytes();
//$tpl->assign('aFoto','000000000');

//} else {
    //echo $image->getBytes();
//$image->write('/var/www/fotos/'.$objuser->getId().'.jpg');
//$tpl->assign('aFoto',$objuser->getId());
//}


$tpl->printToScreen();

unset($tpl,$connection);

?>