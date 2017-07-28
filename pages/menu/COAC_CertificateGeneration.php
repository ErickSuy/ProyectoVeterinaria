<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("COAC_CertificateGeneration.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("iselectciclo", "../includes/selectciclos.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$tpl->assign("aPost", 'COAC_CoursesCertificate.php');



$anioInicio = 2014;
$anioFin = Date("Y");
for ($_anio = $anioInicio; $_anio <= $anioFin; $_anio++) {
    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", $_anio);

}
/*
$periodoRevisado=obtenerPeriodoActivo();
if ($periodoRevisado==PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE ||$periodoRevisado==SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
    $anioRevisado=Date("Y")-1;
else
    if ($periodoRevisado==VACACIONES_DEL_SEGUNDO_SEMESTRE) {
        $fechaActual=mktime(0,0,0,Date("m"),Date("d"),Date("Y"));
        if ($fechaActual>=mktime(0,0,0,"01","01",Date("Y")) && $fechaActual<mktime(0,0,0,"01","10",Date("Y")))
            $anioRevisado=Date("Y")-1;
        else
            $anioRevisado=Date("Y");
    } else
        $anioRevisado=Date("Y");

$tpl->gotoBlock('_ROOT');
$tpl->assign("aAnio", $anioRevisado);
$tpl->assign("aPeriodo", $periodoRevisado);
*/
$tpl->printToScreen();
unset($tpl);