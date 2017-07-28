<?php
set_include_path(get_include_path().PATH_SEPARATOR.'/var/www/PEAR');
print dirname(__FILE__)." <br>";
print get_include_path()." <br>";

// Works in all PHP versions
print ini_get('include_path')." <br>";

include("SOAP/Client.php"); 
require_once "/var/www/biblioteca/librerias_externas/class.xml_a_array.inc.php";
// Dirección del servicio web

//$url = 'http://parnaso.usac.edu.gt:7777/wsOperacionOrdenPago/wsOperacionOrdenPagoSoapHttpPort?WSDL';
//$url = 'http://parnaso.usac.edu.gt:7777/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
$url = 'http://parnaso.usac.edu.gt:7777/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort';
print "conexion a ".$url." <br>";
try 
 {
//    $wsdl = new SOAP_WSDL("http://parnaso.usac.edu.gt:7777/WSOperacionOrdenPago/WSOperacionOrdenPagoSoapHttpPort?WSDL");
    $wsdl = new SOAP_WSDL($url.'?WSDL');
	//$soapcliente = $wsdl->getProxy();
	$cliente = $wsdl->getProxy();
	print "conecta al cliente<br>";

    $cadena = "<GENERAR_ORDEN><CARNET>8830661</CARNET><UNIDAD>8</UNIDAD><EXTENSION>0</EXTENSION><CARRERA>9</CARRERA><NOMBRE>Jose Rodolfo España de Leon--Prueba</NOMBRE><MONTO>303</MONTO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2008</ANIO_TEMPORADA><ID_RUBRO>2</ID_RUBRO><ID_VARIANTE_RUBRO>2</ID_VARIANTE_RUBRO><ANIO_PENSUM></ANIO_PENSUM><TIPO_CURSO>CURSO</TIPO_CURSO><CURSO>1</CURSO><SECCION>U</SECCION><SUBTOTAL>70</SUBTOTAL></DETALLE_ORDEN_PAGO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2008</ANIO_TEMPORADA><ID_RUBRO>2</ID_RUBRO><ID_VARIANTE_RUBRO>2</ID_VARIANTE_RUBRO><ANIO_PENSUM></ANIO_PENSUM><TIPO_CURSO>CURSO</TIPO_CURSO><CURSO>2</CURSO><SECCION>U</SECCION><SUBTOTAL>71</SUBTOTAL></DETALLE_ORDEN_PAGO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2008</ANIO_TEMPORADA><ID_RUBRO>2</ID_RUBRO><ID_VARIANTE_RUBRO>2</ID_VARIANTE_RUBRO><ANIO_PENSUM></ANIO_PENSUM><TIPO_CURSO>CURSO</TIPO_CURSO><CURSO>3</CURSO><SECCION>U</SECCION><SUBTOTAL>72</SUBTOTAL></DETALLE_ORDEN_PAGO><DETALLE_ORDEN_PAGO><ANIO_TEMPORADA>2008</ANIO_TEMPORADA><ID_RUBRO>2</ID_RUBRO><ID_VARIANTE_RUBRO>1</ID_VARIANTE_RUBRO><TIPO_CURSO></TIPO_CURSO><ANIO_PENSUM></ANIO_PENSUM><CURSO></CURSO><SECCION></SECCION><SUBTOTAL>15</SUBTOTAL></DETALLE_ORDEN_PAGO></GENERAR_ORDEN>";
	  
	printf("solicitud al web_server ::: %s<br>",$url);

	printf("<br>cadena de informacion mandada...%s<br>",htmlspecialchars($cadena, ENT_QUOTES));
	
    //$resultado=$soapcliente->generarOrdenPago($cadena);
	$resultado = $cliente->call('generarOrdenPago',$cadena,$url);
//	$resultado=$cliente->generarOrdenPago($cadena);
    print ("solicito generarOrdenPago <br>");
	
/*	if (!PEAR::isError($resultado)) {
        $resultado = true;
      } else {
		    echo "Error: ".$resultado->getMessage()."<br> Informacion detallada: ".$resultado->getUserinfo();
      }
*/	
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
//-----------print "resultado inicial  ".$resultado."<br>";
//printf("resultado ---> %s<br>",$resultado);
printf(" SE TERMINO TODO<br>");
print_r($resultado);
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
//echo $result;
?>

