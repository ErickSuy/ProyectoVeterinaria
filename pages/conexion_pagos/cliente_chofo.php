<!-- Conversion:
Conversión de codificación a UTF-8
17/11/2011 08:09:43 PM
/home/boris/Documentos/EPS/Sistemas/portal2/portal2/conexion_pagos/cliente_chofo.php : US-ASCII
-->

<?php
require_once( '../path.inc.php' );
include ("SOAP/Client.php"); 
require_once ( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once ( "$dir_biblio/librerias_externas/array2xml.class.php" );

$peticionXML = 
   "<GENERAR_ORDEN>
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


$url = 'http://parnaso.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
//$url = 'http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
print $url."<br>";
    try 
    { $wsdl = new SOAP_WSDL($url);
	  $cliente = $wsdl->getProxy();
	  $resultadoObtenido=$cliente->generarOrdenPago($peticionXML);
    } catch (SOAP_Fault $exception)    //CONTROLAMOS LA EXCEPCIONES
		{ //Si el mensaje de error es nuestro, lo limpiamos para verlo mejor
	/*	    $delimitador = '@@';
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
		*/	
		$resultadoObtenido = 	
		 "<RESPUESTA>
            <CODIGO_RESP>800</CODIGO_RESP>
            <DESCRIPCION>EL PROCESO NO SE PUEDE REALIZAR EN ESTE MOMENTO</DESCRIPCION> 
          </RESPUESTA>
		 ";	
		}  
		
//    $xmlparser = xml_parser_create (); 
   // parse the data chunk / / Analizar la cantidad de datos 
//    xml_parse($xmlparser,$resultadoObtenido);
//$codigo = xml_get_error_code($xmlparser);
//print $codigo."::::".xml_error_string($codigo).":::::<br>";
/*	print "ERROR:" 
     . xml_error_string(xml_get_error_code($xmlparser)) xml_error_string (xml_get_error_code ($ xmlparser)) 
     . "<br />" "<br />" 
     . "Line: " "Line:" 
     . xml_get_current_line_number($xmlparser) xml_get_current_line_number ($ xmlparser) 
     . "<br />" "<br />" 
     . "Column: " "Columna" 
     . xml_get_current_column_number($xmlparser) xml_get_current_column_number ($ xmlparser) 
     . "<br />"); "<br />"; 
	die; 
	*/
/*	
if (strcmp($_SESSION["datosGenerales"]->periodo,'02') == 0)	
{
  print_r($resultadoObtenido);print '<br>';		
  print htmlspecialchars($resultadoObtenido, ENT_QUOTES).'::::<br>';  
}
*/
    // ser convierte el XML Obtenido a un arreglo
    $xmlObtenido    = new Xml_a_Array($resultadoObtenido); 
    $datosObtenidos = $xmlObtenido->createArray(); // metodo donde se convierte el XML Obtenido a un arreglo
	
//echo "<pre>";
print_r ($datosObtenidos);// $arrayData[RESPUESTA][CODIGO_RESP]."::::".$arrayData[RESPUESTA][ID_ORDEN_PAGO]."<br>";
//echo "</pre>";  
	
	unset ($wsdl);
	unset ($xmlObtenido);
	
    return $datosObtenidos; // se retorna el arreglo ya convertido del XML de respuesta del WS de procesamiento de datos
	




?>