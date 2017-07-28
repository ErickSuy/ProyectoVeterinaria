<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 04/01/2015
 * Time: 8:43 AM
 */

header('Content-Tupe: text/html; charset=UTF-8');

include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");

session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$obj_cad = new ManejoString();

$tpl = new TemplatePower("../includes/selectciclos.tpl");
$tpl->prepare();

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',SUFICIENCIAS_DEL_PRIMER_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(SUFICIENCIAS_DEL_PRIMER_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',PRIMER_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(PRIMER_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',VACACIONES_DEL_PRIMER_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(VACACIONES_DEL_PRIMER_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',SEGUNDO_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(SEGUNDO_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',VACACIONES_DEL_SEGUNDO_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(VACACIONES_DEL_SEGUNDO_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE));

$tpl->newBlock('opcionciclo');
$tpl->assign('aCiclo',SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE);
$tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE));

$tpl->printToScreen();
unset($obj_cad,$tpl);

?>