<?php

function procesoConfirmacion($xml_peticion)
{
    
    include("path.inc.php");
    include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");
    
    $verificacionWS = new OG_PaymentOrderGenerationWS();
    $respuesta=$verificacionWS->actualizaOrdenPago($xml_peticion); // donde se hacen las verificaciones en la BDD
    return $respuesta;
    /*
    $orden = '<RESPUESTA><CODIGO_RESP>1</CODIGO_RESP><DESCRIPCION>EXITO</DESCRIPCION><ID_ORDEN_PAGO>55555</ID_ORDEN_PAGO>NIDAD><EXTENSION>00</EXTENSION><CARRERA>02</CARRERA><CARNET>201503323</CARNET><NOMBRE>ALEJANDRA ABIGAIL PÉREZ PÉREZ</NOMBRE><MONTO>1200</MONTO><FECHA>2016-05-29</FECHA><CHECKSUM>1234</CHECKSUM><RUBROPAGO>102</RUBROPAGO></RESPUESTA>';
    return $orden;
    */
}

ini_set("soap.wsdl_cache_enabled", "0");
$servicioConfirmacion = new SoapServer('ProcesarPagos.wsdl');
$servicioConfirmacion->addFunction("procesoConfirmacion");
$servicioConfirmacion->handle();

?>