<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:42 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/consumidorWS.php : US-ASCII
-->

<?php
  include_once("../path.inc.php");
//  include_once("$dir_biblio/nusoap/lib/nusoap.php");
  include_once("/var/www/html/portal2/conexion_pagos/nusoap/lib/nusoap.php");
  
//  $cliente = new nusoap_client('https://www.ingenieria.usac.edu.gt/conexion_pagos/confirmacionpagos.php?wsdl',true);
  $cliente = new nusoap_client('https://www.ingenieria.usac.edu.gt/conexion_pagos/confirmacionpagos.php?wsdl','wsdl');
  
  $err = $cliente->getError();
  if ($err) { echo '<h2>Error al construir la conexion</h2><pre>' . $err . '</pre>'; }
  
  $xmlsolicitado = '<CONFIRMACION_PAGO> 
                      <ID_ORDEN_PAGO>1425</ID_ORDEN_PAGO>
                    </CONFIRMACION_PAGO>';
  $resultado = $cliente -> call('',$xmlsolicitado);
  
  if ($cliente->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($resultado);
	echo '</pre>';
} else {
	$err = $cliente->getError();
	if ($err) {
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		echo '<h2>Result</h2><pre>';
		print_r($resultado);
	echo '</pre>';
	}
}
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($cliente->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($cliente->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($cliente->debug_str, ENT_QUOTES) . '</pre>';
//  print_r($resultado);
  
?>  