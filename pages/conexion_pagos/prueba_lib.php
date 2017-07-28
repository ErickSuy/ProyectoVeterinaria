<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:43 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/prueba_lib.php : US-ASCII
-->

<?php
header('Content-type: text/html; charset=utf-8');
require_once( '../path.inc.php' );
require_once( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once( "$dir_biblio/librerias_externas/array2xml.class.php" );
require_once( "$dir_biblio/class.db_pgsql.inc.php" );
require_once( "$dir_biblio/class.verificaciones_manejo_proceso_WS.inc.php" );


function confirmacionPago ($XML_peticion)
{ 
  $xmlObj    = new Xml_a_Array($XML_peticion); // objeto que creara el arreglo desde un string XML
  $arregloDatos = $xmlObj->createArray();  // donde creamos el arreglo proveniente del string XML, que envian

  $verificacionWS = new verificacionProcesoWS(); 
  
  $respuesta=$verificacionWS->actualizaOrdenPago($arregloDatos); // donde se hacen las verificaciones en la BDD
//  $respuesta = $arrreglosDatos;
  $XML_respuesta = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
//  $respuesta = array('RESP_CONFIRMACION' => array('STATUS' => 1));
  return $XML_respuesta->array_xml($respuesta);
}

$info = "<CONFIRMACION_PAGO>
			<ID_ORDEN_PAGO>1425</ID_ORDEN_PAGO>
			<CARNET>8830661</CARNET>
			<UNIDAD>08</UNIDAD>
			<EXTENSION></EXTENSION>
			<CARRERA>9</CARRERA>
			<BANCO></BANCO>
			<NO_BOLETA_DEPOSITO>125632</NO_BOLETA_DEPOSITO>
			<FECHA_CERTIF_BCO>09/10/2008</FECHA_CERTIF_BCO>
		</CONFIRMACION_PAGO>
        ";

 print  htmlspecialchars($info, ENT_QUOTES)."<br>";
 print " RESPUESTA:::<br>";		
 print  htmlspecialchars(confirmacionPago($info), ENT_QUOTES)."<br>";
 
?>