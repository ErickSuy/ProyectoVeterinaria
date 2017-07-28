<?php
include("path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");
include_once("$dir_portal/libraries/biblio/librerias_externas/nusoap/WS/lib/nusoap.php");
include_once("$dir_portal/libraries/biblio/librerias_externas/array2xml.class.php");
//include_once("$dir_portal/libraries/biblio/librerias_externas/class.xml_a_array.inc.php");

$boleta =   "<CONFIRMACION_PAGO>
                <ID_ORDEN_PAGO>666</ID_ORDEN_PAGO>
                <CARNET>201503323</CARNET>
                <UNIDAD>01</UNIDAD>
                <EXTENSION>00</EXTENSION>
                <CARRERA>02</CARRERA>
                <BANCO>BANRURAL</BANCO>
                <NO_BOLETA_DEPOSITO>111111111</NO_BOLETA_DEPOSITO>
                <FECHA_CERTIF_BCO>2016-04-07</FECHA_CERTIF_BCO>
                <ANIO_TEMPORADA>2016</ANIO_TEMPORADA>
                <TIPO_PETICION>1</TIPO_PETICION>
            </CONFIRMACION_PAGO>";
    
$boleta2 = '<GENERAR_ORDEN><CARNET>201210882</CARNET><UNIDAD>10</UNIDAD><EXTENSION>0</EXTENSION><CARRERA>2</CARRERA><NOMBRE>ADELFA ALEJANDRA LOPEZ ARGUETA</NOMBRE><MONTO>20</MONTO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2016</ANIO_TEMPORADA><ID_RUBRO>4</ID_RUBRO><ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO><TIPO_CURSO>CURSO</TIPO_CURSO><CURSO>109</CURSO><SECCION>A</SECCION><SUBTOTAL>10</SUBTOTAL></DETALLE_ORDEN_PAGO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2016</ANIO_TEMPORADA><ID_RUBRO>4</ID_RUBRO><ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO><TIPO_CURSO>CURSO</TIPO_CURSO><CURSO>128</CURSO><SECCION>-</SECCION><SUBTOTAL>10</SUBTOTAL></DETALLE_ORDEN_PAGO></GENERAR_ORDEN>';
	
$boleta3 = '<GENERAR_ORDEN>	<CARNET>201210693</CARNET> <UNIDAD>10</UNIDAD> <EXTENSION>0</EXTENSION> <CARRERA>3</CARRERA> <NOMBRE>GERMAN LUIS ALDANA VILLATORO</NOMBRE> <MONTO>15</MONTO> <DETALLE_ORDEN_PAGO> <ANIO_TEMPORADA>2015</ANIO_TEMPORADA> <ID_RUBRO>7</ID_RUBRO> <ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO> <TIPO_CURSO>CURSO</TIPO_CURSO> <CURSO>204</CURSO> <SECCION>A</SECCION> <SUBTOTAL>15</SUBTOTAL> </DETALLE_ORDEN_PAGO> </GENERAR_ORDEN>';
	
$url ='http://coac.fmvz.usac.edu.gt/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php?wsdl';
$url2 ='http://coac.fmvz.usac.edu.gt:80/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php';
//$url ='http://localhost/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php?wsdl';
//$url2 ='http://localhost:80/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php';

echo 'Entra a ws';

$verificacionWS = new OG_PaymentOrderGenerationWS();
$respuesta=$verificacionWS->actualizaOrdenPago($boleta); // donde se hacen las verificaciones en la BDD
echo 'salio';
/*
try 
{ 
    //ini_set("soap.wsdl_cache_enabled", "0"); // Set to zero to avoid caching WSDL
    $soapClient = new SoapClient($url);     
} 
catch (Exception $e)
{ 
    $v_msg_error = "No se pudo realizar la operacion [" . $e->getMessage() . "]";
    $v_resultado_invoke = 0;
    return;
}
try 
{
    $soapClient->__setLocation($url2);
    $dato = (object)$soapClient->procesoConfirmacion($boleta);         
    print_r("cliente");
    print_r($dato);
} 
catch (Exception $e) 
{ 
    $v_msg_error = "No se pudo realizar la operacion [" . $e->getMessage() . "]";
    $v_resultado_invoke = 0;
    return;
}
*/