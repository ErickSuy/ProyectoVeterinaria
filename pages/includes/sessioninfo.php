<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 5/10/14
 * Time: 05:03 PM
 */

include("../../path.inc.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");

session_start();
$tpl = new TemplatePower("../includes/sessioninfo.tpl");
$objuser = unserialize($_SESSION['usuario']);

$tpl->assignInclude("isession", $objuser->getId() . '&nbsp;/&nbsp;' . $objuser->getName() . ' ' . $objuser->getSurName(), T_BYVAR);
$tpl->prepare();

$tpl->printToScreen();

?> 