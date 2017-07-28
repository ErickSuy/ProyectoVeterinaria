<?php
header('Content-type: text/html; charset=iso-8859-1');
include("../../path.inc.php");
require_once("$dir_biblio/nusoap/lib/nusoap.php" );
require_once("$dir_portal/fw/model/DB_Connection.php");
require_once("$dir_biblio/biblio/librerias_externas/array2xml.class.php");
require_once("$dir_biblio/biblio/librerias_externas/class.xml_a_array.inc.php");
include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");

function confirmacionPago ($XML_peticion)
{ $xmlObj    = new Xml_a_Array($XML_peticion); // objeto que creara el arreglo desde un string XML
  $arregloDatos = $xmlObj->createArray();  // donde creamos el arreglo proveniente del string XML, que envian
 
  $verificacionWS = new OG_PaymentOrderGenerationWS();
  $XML_respuesta = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
  
  $respuesta=$verificacionWS->actualizaOrdenPago($arregloDatos); // donde se hacen las verificaciones en la BDD
  
  return $XML_respuesta->array_xml($respuesta);
}

$servidor = new soap_server();

$ns="http://10.50.23.170/pages/conexion_pagos/confirmarOrdenPago";
$servidor->configurewsdl('ApplicationServices',$ns);
$servidor->wsdl->schematargetnamespace=$ns;
$servidor->register('confirmacionPago',array('XML_peticion' => 'xsd:string'),array('return' => 'xsd:string'),$ns);

if (isset($HTTP_RAW_POST_DATA))
{
	$input = $HTTP_RAW_POST_DATA;
}
else
{
	$input = implode("\r\n", file('php://input'));
}
$servidor->service($input);
exit;
?>
