<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:43 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/server_p.php : US-ASCII
-->

<?php
header('Content-type: text/html; charset=utf-8');
require_once( '../path.inc.php' );
require_once( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once( "$dir_biblio/librerias_externas/array2xml.class.php" );
require_once( "$dir_biblio/class.db_pgsql.inc.php" );
require_once( "$dir_biblio/nusoap/lib/nusoap.php" );
require_once( "$dir_biblio/class.verificaciones_manejo_proceso_WS.inc.php" );


function confirmacionPago ($XML_peticion)
{ $xmlObj    = new Xml_a_Array($XML_peticion); // objeto que creara el arreglo desde un string XML
  $arregloDatos = $xmlObj->createArray();  // donde creamos el arreglo proveniente del string XML, que envian
 
  $verificacionWS = new verificacionProcesoWS(); 
  $XML_respuesta = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
  
  $respuesta=$verificacionWS->actualizaOrdenPago($arregloDatos); // donde se hacen las verificaciones en la BDD
  
  return $XML_respuesta->array_xml($respuesta);
}

$servidor = new soap_server();
$ns="https://www.ingenieria.usac.edu.gt/conexion_pagos/server_p";
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