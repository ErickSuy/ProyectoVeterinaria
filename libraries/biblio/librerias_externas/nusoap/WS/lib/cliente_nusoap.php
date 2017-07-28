<?php
include("../../../../../path.inc.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("$dir_portal/libraries/biblio/librerias_externas/nusoap/WS/lib/nusoap.php");
include_once("$dir_portal/libraries/biblio/librerias_externas/array2xml.class.php");
include_once("$dir_portal/libraries/biblio/librerias_externas/class.xml_a_array.inc.php");
/*
//$url = 'http://localhost/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php';
$url = 'http://10.50.23.229/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php';
$client = new nusoap_client($url);

$error = $client->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}
$boleta =   "<CONFIRMACION_PAGO>
                <ID_ORDEN_PAGO>9888913</ID_ORDEN_PAGO>
                <CARNET>201210882</CARNET>
                <UNIDAD>01</UNIDAD>
                <EXTENSION>00</EXTENSION>
                <CARRERA>02</CARRERA>
                <BANCO>BANRURAL</BANCO>
                <NO_BOLETA_DEPOSITO>111111111</NO_BOLETA_DEPOSITO>
                <FECHA_CERTIF_BCO>2016-04-07</FECHA_CERTIF_BCO>
                <ANIO_TEMPORADA>2016</ANIO_TEMPORADA>
                <TIPO_PETICION>1</TIPO_PETICION>
            </CONFIRMACION_PAGO>";

print_r($boleta);

$result = $client->call("procesarPagos", $boleta);

if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
}
else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
        print_r($client);
        
    }
    else {
        echo "<h2>Books</h2><pre>";
        print_r($result);
        echo "</pre>";
    }
}*/