<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:43 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/client9.php : US-ASCII
-->

<?php
$camino="/var/www/html/portal2/conexion_pagos";
print $camino."<br>";
require_once("$camino/lib/nusoap.php");
	$usac_url='http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
	$oSoapClient = new nusoap_client($usac_url, true);
	$aParametros = array("pxml" => $xml_genera_orden);
	$aRespuesta = $oSoapClient->call("generarOrdenPago", $aParametros);
$xml_Genera_Pago = "<GENERAR_ORDEN>
							<CARNET>200320203</CARNET>
							<UNIDAD>01</UNIDAD>
							<EXTENSION>0</EXTENSION>
							<CARRERA>2</CARRERA>
							<NOMBRE>SAZO GUERRA, DIEGO ARMANDO</NOMBRE>
							<MONTO>10</MONTO>
							<DETALLE_ORDEN_PAGO>
							   <ANIO_TEMPORADA>2009</ANIO_TEMPORADA>
							   <ID_RUBRO>6</ID_RUBRO>
							   <ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO>
							   <TIPO_CURSO>CURSO</TIPO_CURSO>
							   <CURSO>10113</CURSO>
							   <SECCION>23</SECCION>
							   <SUBTOTAL>10</SUBTOTAL>
							 </DETALLE_ORDEN_PAGO>
							</GENERAR_ORDEN>
						 ";
//Generamos la orden
//	echo $xml_Genera_Pago;
echo '<h2>PETICION</h2><pre>' . htmlspecialchars($xml_Genera_Pago, ENT_QUOTES) . '</pre>';
	$val=generar_orden_pago($xml_Genera_Pago);
	echo($val);
?>
