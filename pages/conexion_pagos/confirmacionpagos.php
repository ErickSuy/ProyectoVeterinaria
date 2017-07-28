<?php
  include_once("../path.inc.php");
//  include_once("$dir_biblio/nusoap/lib/nusoap.php");
  include_once("/var/www/html/portal2/conexion_pagos/nusoap/lib/nusoap.php");
  
  $rawPost = strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') == 0? (isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input")) : NULL;
  
  function verificaPago ($xmlEnviado)
   { $saludo = "Hola ". $xmlEnviado;
   
     //return new soapval('return','xsd:string',$saludo);
	 return empty( $xmlEnviado )  ? new soap_fault('Cliente','','Ingrese un nombre válido') : "Hola " . $xmlEnviado;
   }
  
  $server = new soap_server();

  $server -> configureWSDL('verificaPago');

  $server -> register('verificaPago','xsd:string');
  
  $server -> service($rawPost);
  
  
  
?>