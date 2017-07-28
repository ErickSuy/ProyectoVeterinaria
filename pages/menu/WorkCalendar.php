<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 01/11/2014
 * Time: 10:23 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

session_start();

// el parametro de la pagina es $periodo

// aun es valida la session?
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}


// se captura el anio actual
$anio = date("Y");  // anio presente
$periodo = $_GET['ciclo'];
$ciclo = ($periodo=="100") ? "PRIMER SEMESTRE" : "SEGUNDO SEMESTRE";

$ciclo = $ciclo." ".$anio;

$nombre_archivo = "$dir_portal/pages/calendario/labores".$periodo.$anio.".htm";

$tpl = new TemplatePower("WorkCalendar.tpl");
$tpl->assignInclude("itoppage", "./top_page.php");
$tpl->assignInclude("isessionheader", "../includes/nav_sessionbar.php");
$tpl->assignInclude("imenu", "./ver_menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

if (file_exists($nombre_archivo))
{ // si el archivo existe
    $tpl->assignInclude( "ihorario", $nombre_archivo );
    $tpl->prepare();
    $tpl->newBlock("inota");
}
else {
    $nombre_archivo = "<br><br>Informaci&oacute;n en proceso,".
        "<br>disponible en corto tiempo<br><br>GRACIAS POR SU COMPRENSI&Oacute;N<br><br>";
    $tpl->assignInclude( "ihorario", $nombre_archivo, T_BYVAR );
    $tpl->prepare();
}


$tpl->assign("vFecha",Date("d-m-Y"));
$tpl->assign("vHora",Date("H:i"));
$tpl->assign("TITULO",$ciclo);
$tpl->printToScreen();
unset($tpl);
?>