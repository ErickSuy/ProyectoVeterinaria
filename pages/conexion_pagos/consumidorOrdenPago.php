<?php
/*
require_once( '../path.inc.php' );
require_once( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once( "$dir_biblio/librerias_externas/array2xml.class.php" );
require_once( "$dir_biblio/nusoap_php5/lib/nusoap.php" );

$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';

$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';

$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';

$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';

echo '<h2>Constructor</h2>';

$cliente = new soapclient('http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?wsdl');
//			   http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?wsdl
//echo '<h2>CONECTO----Constructor</h2>';

$error = $cliente->getError();

if ($error) {

	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';

}

echo '<h2>Debug</h2><pre>' . htmlspecialchars($cliente->getDebug(), ENT_QUOTES) . '</pre>';

$cliente->clearDebug();

// A persistent connection is *optional*, *not* necessary

$cliente->useHTTPPersistentConnection();

//echo '<h2>GetSessionID</h2>';
$info1 = array('GENERAR_ORDEN'=>array
                             ('CARNET'=>'2008000016',
			      'UNIDAD'=>'8',
			      'EXTENSION'=>0,
			      'CARRERA'=>1,
			      'NOMBRE'=>"Gustavo Alexander Orozco Tay -- PRUEBA INGENIERIA--",
			      'MONTO'=>101,
			      'DETALLE_ORDEN_PAGO'=>array
 				('ANIO_TEMPORADA'=>2009,
				 'ID_RUBRO'=>2,
				 'ID_VARIANTE_RUBRO'=>1,
				 'ANIO_PENSUM'=>0,
				 'CURSO'=>15,
				 'SECCION'=>'',
				 'SUBTOTAL'=>101
				)
			     )
			    );
			 
 $XML_peticion = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
 $XML_peticion->array_xml($info1);
echo '<h2>PETICION</h2><pre>' . htmlspecialchars($XML_peticion->XMLtext, ENT_QUOTES) . '</pre>';
$cadena="<GENERAR_ORDEN><CARNET>2008000016</CARNET><UNIDAD>8</UNIDAD><EXTENSION>0</EXTENSION><CARRERA>1</CARRERA><NOMBRE>Gustavo Alexander Orozco Tay -- PRUEBA INGENIERIA--</NOMBRE><MONTO>101</MONTO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2009</ANIO_TEMPORADA><ID_RUBRO>2</ID_RUBRO><ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO><ANIO_PENSUM></ANIO_PENSUM><CURSO></CURSO><SECCION></SECCION><SUBTOTAL>101</SUBTOTAL></DETALLE_ORDEN_PAGO></GENERAR_ORDEN>";
// $resultado = $cliente->generarOrdenPago($XML_peticion->XMLtext);
 $resultado = $cliente->generarOrdenPago($cadena);
// $resultado = $cliente->VerificaPIN($XML_peticion->XMLtext);
//---$resultado = $cliente->VerificaInscripcion($XML_peticion->XMLtext);
//$resultado = $cliente->VerificaNuevos($XML_peticion->XMLtext);
unset($XML_peticion);

// Check for a fault

if ($cliente->fault) {

	echo '<h2>Fault</h2><pre>';

	print_r($resultado);

	echo '</pre>';

} else {

//---	$err = $cliente->getError();

	if ($err) {

		// Display the error

		echo '<h2>Error</h2><pre>' . $err . '</pre>';

	} else {
		echo '<h2>Resultado</h2><pre>';
        echo htmlspecialchars($resultado, ENT_QUOTES);
		echo '</pre>';
	}

}

echo '<h2>Request</h2><pre>' . htmlspecialchars($cliente->request, ENT_QUOTES) . '</pre>';

echo '<h2>Response</h2><pre>' . htmlspecialchars($cliente->response, ENT_QUOTES) . '</pre>';

*/





require_once( '../path.inc.php' );
include ("SOAP/Client.php"); 
require_once ( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once ( "$dir_biblio/librerias_externas/array2xml.class.php" );
// Dirección del servicio web

$url = 'http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
//$url = 'http://parnaso.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
try 
 {  $wsdl = new SOAP_WSDL($url);
	
	$cliente = $wsdl->getProxy();
	$cadena = "<GENERAR_ORDEN>
			     <CARNET>2008000016</CARNET>
				 <UNIDAD>8</UNIDAD>
				 <EXTENSION>0</EXTENSION>
				 <CARRERA>1</CARRERA>
				 <NOMBRE>Gustavo Alexander Orozco Tay -- PRUEBA INGENIERIA--</NOMBRE>
				 <MONTO>101</MONTO>
				 <DETALLE_ORDEN_PAGO>
				  <ANIO_TEMPORADA>2008</ANIO_TEMPORADA>
				  <ID_RUBRO>1</ID_RUBRO>
				  <ID_VARIANTE_RUBRO>2</ID_VARIANTE_RUBRO>
				  <ANIO_PENSUM></ANIO_PENSUM>
				  <CURSO></CURSO>
				  <SECCION></SECCION>
				  <SUBTOTAL>101</SUBTOTAL>
				 </DETALLE_ORDEN_PAGO>
			  </GENERAR_ORDEN>
			";
       $array_cadena = array('GENERAR_ORDEN'=>array
                             ('CARNET'=>'2008000016',
			      'UNIDAD'=>'8',
			      'EXTENSION'=>0,
			      'CARRERA'=>1,
			      'NOMBRE'=>"Gustavo Alexander Orozco Tay -- PRUEBA INGENIERIA--",
			      'MONTO'=>101,
			      'DETALLE_ORDEN_PAGO'=>array
 				('ANIO_TEMPORADA'=>2008,
				 'ID_RUBRO'=>1,
				 'ID_VARIANTE_RUBRO'=>2,
				 'TIPO_CURSO'=>0,
				 'CURSO'=>"0101",
				 'SECCION'=>'',
				 'SUBTOTAL'=>101
				)
			     )
			    );
    // se hace referencia al metodo donde genera la orden de pago
	// y se almacena en la variable resultado para que se realice como se necesite
 $XML_peticion = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
 $XML_peticion->array_xml($array_cadena);



 $cadena = $XML_peticion->XMLtext;

$cadena = "<GENERAR_ORDEN><CARNET>2008000016</CARNET><UNIDAD>8</UNIDAD><EXTENSION>0</EXTENSION><CARRERA>1</CARRERA><NOMBRE>Gustavo Alexander Orozco Tay -- PRUEBA INGENIERIA--</NOMBRE><MONTO>101</MONTO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2009</ANIO_TEMPORADA><ID_RUBRO>2</ID_RUBRO><ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO><ANIO_PENSUM></ANIO_PENSUM><CURSO></CURSO><SECCION></SECCION><SUBTOTAL>101</SUBTOTAL></DETALLE_ORDEN_PAGO></GENERAR_ORDEN>";
echo '<h2>PETICION</h2><pre>' . htmlspecialchars($XML_peticion->XMLtext, ENT_QUOTES) . '</pre>';
echo '<h2>PETICION</h2><pre>' . htmlspecialchars($cadena, ENT_QUOTES) . '</pre>'; 
	$resultado=$cliente->generarOrdenPago($cadena);
 } catch (SOAP_Fault $exception)    //CONTROLAMOS LA EXCEPCIONES
		{ //Si el mensaje de error es nuestro, lo limpiamos para verlo mejor
		    $delimitador = '@@';
		    $e = $exception->faultstring;
		    $p = strpos($e, $delimitador);
		    if ($p !== false)
		     {   $q = strpos ($e, $delimitador,$p+2);
		         $sError = substr($e, $p+2, $q-$p-2);
		                echo "ERROR: ",$sError,"<BR>";
		     }
		     else
		      {
		        print " ERROR GENERAL :::". $e ."<BR>";
		      }
		    die();
		}

// Imprimimos el resultado
print_r ($resultado);
printf("<br> SE TERMINO TODO<br>");

//Creating Instance of the Class
$xmlObj    = new Xml_a_Array($resultado);
//Creating Array
$arrayData = $xmlObj->createArray();
//Displaying the Array
echo "<pre>";
print_r($arrayData);
echo "</pre>";

echo "<pre>";
print $arrayData[RESPUESTA][CODIGO_RESP]."::::".$arrayData[RESPUESTA][ID_ORDEN_PAGO]."<br>";
echo "</pre>";


?>

