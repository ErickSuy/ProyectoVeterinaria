<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 07/05/2015
 * Time: 08:50 AM
 */
include("../../path.inc.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");
require_once("$dir_biblio/biblio/librerias_externas/class.PrintAnything.inc.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl  = new TemplatePower("../includes/AR_PrintBlock.tpl");

$tpl->prepare();

$dato = new OG_PaymentOrderGenerationWS();
$_SESSION["pa"] = new PrintAnything();

$dato->obtieneInfoOrdenPago($_SESSION['OrdenPago']);
if ( $dato->datosOrden != FALSE)
{
    $periodo="";
    $tpl->assign("vcarne",0+$dato->datosOrden['carne']);
    $tpl->assign("numeroOrden",$dato->datosOrden['ordenpago']);
    $tpl->assign("vnombre",$dato->datosOrden['nombreest']);
    $tpl->assign("vnomCarrera",$dato->datosOrden['nombrecar']);
    //$tpl->assign("periodo",$dato->datosOrden['chofo']);
    $tpl->assign("periodo",$_SESSION["datosGenerales"]->periodo);
    $tpl->assign("vmonto",$dato->datosOrden['monto']);
    $tpl->assign("vunidad",$dato->datosOrden['unidad']);
    $tpl->assign("vextension",'0'.$dato->datosOrden['extension']);
    $tpl->assign("vcarrera",'0'.$dato->datosOrden['carrera']);
    $tpl->assign("vrubro",$dato->datosOrden['rubro']);
    $tpl->assign("vllave",$dato->datosOrden['checksum']);

    switch ($_SESSION["datosGenerales"]->periodo)
    { case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE: $periodo = ' PRIMERA RETRASADA, PRIMER SEMESTRE ';break;
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE: $periodo = ' SEGUNDA RETRASADA, PRIMER SEMESTRE ';break;
        case VACACIONES_DEL_PRIMER_SEMESTRE: $periodo = ' ESCUELA DE VACACIONES, PRIMER SEMESTRE ';break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE: $periodo = ' ESCUELA DE VACACIONES, SEGUNDA SEMESTRE ';break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE: $periodo = ' PRIMERA RETRASADA, SEGUNDO SEMESTRE ';break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE: $periodo = ' SEGUNDA RETRASADA, SEGUNDO SEMESTRE ';break;
    }
    $periodo .= $_SESSION["datosGenerales"]->anio;

    $tpl->assign("periodo",$periodo);
	
	$vecCursos=$_SESSION["NombreCursos2"];
    if($vecCursos){
        foreach ($vecCursos as $curso){
            $tpl->newBlock('DETALLE');
            $tpl->assign("codCurso",$curso['idcourse']);
            $tpl->assign("nombreCurso",$curso['name']);
            $tpl->assign("precioCurso",$curso['price']);
        }
    }
	
}
//$tpl->assign("vTitulo","Orden de Pago ".$dato->datosOrden['ordenpago']);
$tpl->assign("vTitulo","Orden de Pago ".$dato->datosOrden['ordenpago']);

$_SESSION['contenidoImpresion'] = $_SESSION["pa"]->addPrintContext($tpl->getOutputContent());
$tpl->printToScreen();
unset($dato);
unset($tpl);

?>