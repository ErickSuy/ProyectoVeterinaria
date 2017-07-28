<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:44 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/consumir_local.php : US-ASCII
-->

<?php
header('Content-type: text/html; charset=iso-8859-1');
require_once( '../path.inc.php' );
require_once( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once( "$dir_biblio/librerias_externas/array2xml.class.php" );
require_once( "$dir_biblio/class.db_pgsql.inc.php" );
require_once( "$dir_biblio/class.verificaciones_manejo_proceso_WS.inc.php" );


function confirmacionPago ($XML_peticion)
{ $xmlObj    = new Xml_a_Array($XML_peticion); // objeto que creara el arreglo desde un string XML
  $arregloDatos = $xmlObj->createArray();  // donde creamos el arreglo proveniente del string XML, que envian
print_r  ($arregloDatos);//.":::<br>";
echo "<br>";
  $verificacionWS = new verificacionProcesoWS(); 
  $XML_respuesta = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
  
  $respuesta=$verificacionWS->actualizaOrdenPago($arregloDatos); // donde se hacen las verificaciones en la BDD

//  $respuesta = array('RESP_CONFIRMACION' => array('STATUS' => 0));
  return $XML_respuesta->array_xml($respuesta);
}

$xmlPeticion  = "<CONFIRMACION_PAGO>
					<ID_ORDEN_PAGO>326543</ID_ORDEN_PAGO>
					<CARNET>200117556</CARNET>
					<UNIDAD>8</UNIDAD>
					<EXTENSION>0</EXTENSION>
					<CARRERA>9</CARRERA>
					<BANCO>BANRURAL</BANCO>
					<NO_BOLETA_DEPOSITO>11796305</NO_BOLETA_DEPOSITO>
					<FECHA_CERTIF_BCO>20090601</FECHA_CERTIF_BCO>
					<TIPO_PETICION>1</TIPO_PETICION>
				 </CONFIRMACION_PAGO>
				";
		
$respuesta = confirmacionPago($xmlPeticion);

$xmlObj    = new Xml_a_Array($respuesta);
//Creating Array
$arrayData = $xmlObj->createArray();
//Displaying the Array
print "datos::::".$respuesta."<br>";
echo "<h2>RESPUESTA:::</h2>";
echo "<pre>";
print_r($arrayData);
echo "</pre>";
?>