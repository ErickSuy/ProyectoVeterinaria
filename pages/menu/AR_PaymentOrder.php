<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 07/05/2015
 * Time: 07:09 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
require_once("$dir_biblio/biblio/librerias_externas/class.PrintAnything.inc.php");
include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");

session_start();

header("Cache-control: private");
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("AR_PaymentOrder.tpl");
$registro = new OG_PaymentOrderGenerationWS();
$_SESSION["pa"] = new PrintAnything();

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$registro->detalleOrdenesPago();

//print "numero de ordenes::". $registro->numeroOrdenes."::::<br>";
$_SESSION["pa"]->_textoBloque = '';
for ($pos = 0; $pos < $registro->numeroOrdenes; $pos++) {
    if (($registro->listaOrden[$pos]['verificaws'] == 0)  AND ($registro->listaOrden[$pos]['verificaCA'] == 0)) {
        $_SESSION['OrdenPago'] = 0 + $registro->listaOrden[$pos]['ordenpago'];
        $tpl->assignInclude("bloqueImpresionOrden", "../includes/AR_PrintBlock.php");
        $contenidoProceso[$pos] .= $_SESSION['contenidoImpresion'];
    }
}
$tpl->prepare();
$obj_cad = new ManejoString();
$tpl->assign("aPeriodo", $obj_cad->funTextoPeriodo($_SESSION["datosGenerales"]->periodo));
$tpl->assign("aAnio", $_SESSION["datosGenerales"]->anio);
for ($i = 0; $i <= $_SESSION["numCursos"]; $i++) {
    if ($_SESSION["cursosAsig"][$i]['mEstado'] == 1) {
        $_SESSION["cursosAsig"][$i]['mEstadoAsignar'] = 10;
    }
} // for

for ($i = 1; $i <= $_SESSION["numCursos"]; $i++) {
    if ($_SESSION["cursosAsig"][$i]['mEstadoAsignar'] == 10) {
        $conteo = $conteo + 1;
        $tpl->newBlock("lisAsigCurso");
        $tpl->assign("vNum", $conteo);
        $tpl->assign("vCurso", $_SESSION["cursosAsig"][$i]['curso']);
        $tpl->assign("vNomCurso", $_SESSION["cursosAsig"][$i]['mNomCurso']);
        $tpl->assign("vSeccion", $_SESSION["cursosAsig"][$i]['seccion']);
        $tpl->assign("vObserv", "");
        $tpl->assign("vCarrera", $_SESSION["cursosAsig"][$i]['mNomCarrera']);
    }
} // for
$p = 0;
for ($pos = 0; $pos < $registro->numeroOrdenes; $pos++) {
    $tpl->newBlock("lista_ordenes");
    $tpl->assign('vorden', $registro->listaOrden[$pos]['ordenpago']);
    $tpl->assign('vfechaorden', $registro->listaOrden[$pos]['fechaorden']);
    $tpl->assign('vmontoorden', $registro->listaOrden[$pos]['monto']);
    if ($registro->listaOrden[$pos]['verificaws'] OR $registro->listaOrden[$pos]['verificaCA']) {
        $mensaje = 'ORDEN CANCELADA';
        $linkeaImpresion = FALSE;
    } else {
        $mensaje = 'ORDEN PENDIENTE DE PAGO';
        $linkeaImpresion = TRUE;
    }
    $tpl->assign('vmensajeEstado', $mensaje);
    $mensaje = "Imprimir Orden " . $registro->listaOrden[$pos]['ordenpago'];
    if ($linkeaImpresion) {// $linkeo = '<a href="javascript:PA_GoPrint_'.($pos+1).'()">'.$mensaje.'</a>';
        $linkeo = '<a href="javascript:PA_GoPrint_' . ($p + 1) . '()">' . $mensaje . '</a>';
        $p++;
    } else {
        $linkeo = "&nbsp;";
    }
    $tpl->assign('vlinkImprimir', $linkeo);
}
//imprime el resultado
$tpl->gotoBlock("_ROOT");
$tpl->printToScreen();
unset($_SESSION["pa"]);
unset($tpl);

?>