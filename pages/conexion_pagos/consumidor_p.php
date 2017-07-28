<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:43 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/consumidor_p.php : US-ASCII
-->

<?php
header('Content-type: text/html; charset=iso-8859-1');
require_once( '../path.inc.php' );
require_once( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once( "$dir_biblio/nusoap/lib/nusoap.php" );

$ns="http://www.ingenieria.usac.edu.gt/conexion_pagos/confirmarOrdenPago";
$oSoap = new nusoap_client($ns.'?wdsl');
/*
$info = "<CONFIRMACION_PAGO>
			<ID_ORDEN_PAGO>251280</ID_ORDEN_PAGO>
			<CARNET>200113296</CARNET>
			<UNIDAD>8</UNIDAD>
			<EXTENSION></EXTENSION>
			<CARRERA>4</CARRERA>
			<BANCO>CHOFO</BANCO>
			<NO_BOLETA_DEPOSITO>778956</NO_BOLETA_DEPOSITO>
			<FECHA_CERTIF_BCO>29/01/2009</FECHA_CERTIF_BCO>
			<TIPO_PETICION>1</TIPO_PETICION>
		</CONFIRMACION_PAGO>
        ";
*/		
$info = "<CONFIRMACION_PAGO>
			<ID_ORDEN_PAGO>326545</ID_ORDEN_PAGO>
			<CARNET>200117556</CARNET>
			<UNIDAD>8</UNIDAD>
			<EXTENSION>0</EXTENSION>
			<CARRERA>9</CARRERA>
			<BANCO>BANRURAL</BANCO>
			<NO_BOLETA_DEPOSITO>11796305</NO_BOLETA_DEPOSITO>
			<FECHA_CERTIF_BCO>20090629</FECHA_CERTIF_BCO>
			<TIPO_PETICION>1</TIPO_PETICION>
		 </CONFIRMACION_PAGO>
		";
		
print $info . ":::<br>";
$dato = $oSoap->call('confirmacionPago',array('XML_peticion' => $info),$ns);
 if ($oSoap->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($dato);
	echo '</pre>';
} else {
	$err = $oSoap->getError();
	if ($err) {
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		echo '<h2>Result</h2><pre>';
		print_r($dato);
	echo '</pre>';
	}
}
//Creating Instance of the Class
$xmlObj    = new Xml_a_Array($dato);
//Creating Array
$arrayData = $xmlObj->createArray();
//Displaying the Array
print "datos::::".$dato."<br>";
echo "<h2>RESPUESTA:::</h2>";
echo "<pre>";
print_r($arrayData);
echo "</pre>";

echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($$oSoap->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($$oSoap->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($$oSoap->debug_str, ENT_QUOTES) . '</pre>';

?>