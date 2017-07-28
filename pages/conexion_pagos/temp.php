<?php


/* 
 * Incluyendo archivo con sentencias SQL 
 */

include_once( "/var/www/html/portal2/path.inc.php");
require_once( "$dir_biblio/librerias_externas/class.xml_a_array.inc.php" );
require_once( "$dir_biblio/librerias_externas/array2xml.class.php" );
include_once( "SOAP/Client.php" );

function _verificaConexionServer($url)
  {/* Tiempo límite de espera entre la conexión de 10 segundos */
   $timeout = stream_context_create(array('http' => array('timeout' => 10)));

   /* Verifica si la url existe */
   if(@file_get_contents($url, 0, $timeout))
     { return TRUE;} // SI EXISTE CONEXION
    else{return FALSE;} // NO EXISTE CONEXION
  }  
  
 function _generarOrdenPago()
  {
  	echo "!<br>";
   $url = 'https://www.ingenieria.usac.edu.gt/conexion_pagos/confirmarOrdenPago';

 $resultadoObtenido =
                 "<RESPUESTA>
            <CODIGO_RESP>800</CODIGO_RESP>
            <DESCRIPCION>EL PROCESO NO SE PUEDE REALIZAR EN ESTE MOMENTO</DESCRIPCION>
        </RESPUESTA>
                 ";
echo "!!<br>";
   if (_verificaConexionServer($url)) // verifica CONEXION
   {
   	echo "Muere conexion!<br>";
    try 
    { 
     $wsdl = new SOAP_WSDL($url);
     echo "Muere claudio!<br>";
	  $cliente = $wsdl->getProxy();

    } catch (Exception $e)    //CONTROLAMOS LA EXCEPCIONES
		{ //Si el mensaje de error es nuestro, lo limpiamos para verlo mejor
		printf("Error:sendSms: %s\n",$e->__toString());
		$resultadoObtenido = 	
		 "<RESPUESTA>
            <CODIGO_RESP>800</CODIGO_RESP>
            <DESCRIPCION>EL PROCESO NO SE PUEDE REALIZAR EN ESTE MOMENTO</DESCRIPCION> 
          </RESPUESTA>
		 ";	
		}  
   }		
	echo "!!!<br>";
	 unset ($wsdl);
    return TRUE; // se retorna el arreglo ya convertido del XML de respuesta del WS de procesamiento de datos
	
  } // fin de funcion generarOrdenPago
  echo "Hola <br>";
  _generarOrdenPago();
  echo "Hola !<br>";
?>