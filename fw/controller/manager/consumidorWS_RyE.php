<?php

/****
 * 
 * author: Angel Hernandez
 * date :  24.01.2017
 * 
 * 
 * 
 */

 
 
 // include_once("../path.inc.php");
  
//  include_once("$dir_biblio/nusoap/lib/nusoap.php");
//  include_once("/var/www/pages/conexion_pagos/nusoap/lib/nusoap.php");
  
  //class RyEWS {
      
   


      function consultar_inscrito($student,$career,$year){
      
//      ECHO $student." ".$career." ".$year;die;
      $cliente = new SoapClient('http://www.registro.usac.edu.gt/WS/consultaEstudianteRyEv2.0.php?wsdl',array('soap_version' => SOAP_1_1));
      if ($err) {	echo 'Error en Constructor' . $err ; }

 $xmlsolicitado = '<SOLICITUD_DATOS_RYE>
		<DEPENDENCIA>UA05</DEPENDENCIA>
		<LOGIN>S1s05M3d1c1n4</LOGIN>
		<PWD>98c48e9c0855f6394e3d12b3d46d0936a137549c</PWD>
		<CARNET>'.$student.'</CARNET>
		<UNIDAD_ACADEMICA>10</UNIDAD_ACADEMICA>
		<EXTENSION>00</EXTENSION>
		<CARRERA>'.$career.'</CARRERA>
		<CICLO>'.$year.'</CICLO>
	</SOLICITUD_DATOS_RYE>';
 
  $result = $cliente->datosGenerales($xmlsolicitado);
  //echo($result);die;
 $RESPUESTA= array();
 $RESPUESTA[] = json_decode(json_encode((array) simplexml_load_string($result)),1); 

 // $resultado = $cliente -> call('datosGenerales',$xmlsolicitado);
 $nombre = $RESPUESTA[0]['NOMBRE']; 
 $nombre = utf8_decode($nombre);
 //echo $nombre;die;
// print_r($RESPUESTA);die;
 //echo "<br>";
 //echo "<br>";
 //ECHO $RESPUESTA[0]['CARNET'];DIE;
// ECHO $RESPUESTA[0]['DETALLE_ACADEMICO']['CICLO_ACTIVO'];DIE;
if(isset($RESPUESTA[0]['DETALLE_ACADEMICO']['CICLO_ACTIVO'])) {
        IF ($RESPUESTA[0]['DETALLE_ACADEMICO']['CICLO_ACTIVO']==$year && $RESPUESTA[0]['DETALLE_ACADEMICO']['UNIDAD']==10  && $RESPUESTA[0]['DETALLE_ACADEMICO']['CARRERA']==$career){
            return $RESPUESTA;
        }else{
            return 'NO_INSCRITO';
       }

 }else{
     $cont_carrera=0;
     $carreras = true;
     while($carreras){
        if(isset($RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['CICLO_ACTIVO'])) {
    //        ECHO " CICLO ".$RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['CICLO_ACTIVO'];
    //        ECHO " UNIDAD ".$RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['UNIDAD'];
     //       ECHO " CARRERA ".$RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['CARRERA'];
      //      ECHO '<BR>';
            
        IF ($RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['CICLO_ACTIVO']==$year && $RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['UNIDAD']==10 && $RESPUESTA[0]['DETALLE_ACADEMICO'][$cont_carrera]['CARRERA']==$career){
            return $RESPUESTA;
        }else{
            $cont_carrera++;
       }
     }else{
         $carreras=false;
         return "NO_INSCRITO";
     }
     }
 }
 
 //echo print_r($RESPUESTA[0]['DETALLE_ACADEMICO']['CICLO_ACTIVO'].">>");DIE;
  //print_r(utf8_decode($nombre)."<br>");
  
 // return $RESPUESTA; 
  }
 

//print_r(consultar_inscrito(201321780,3,2017));
 // $err = $client->getError();
  

  //}
  
