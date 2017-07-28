<html>
<HEAD><TITLE>Meteorologia del planeta</TITLE>
</HEAD>
<body bgcolor= "#66CCFF">

<br>
<h1 align="center"> Meteorología del planeta</h1>

<hr>
<br>
<CENTER>
<IMG 
      alt="Mapa mundi" 
      src="imagenes/world.jpg"
> 
</CENTER>

<font size="4"><p align="justify">Esta página está orientada a la previsión meteorológica de los diferentes aeropuertos existentes en el mundo. Para ello se rastrea a cierta hora cada uno de ellos y se envian los datos a un centro de recogida. A partir de ahí, desde esta página se solicitan las previsiones y se muestran en páginas posteriores. Alguno de los aeropuertos puede aparecer en el listado, y sin embargo no tener unas previsiones dadas. Estos últimos vienen dados por una localización remota.</p>

<p align="left">A continuación se muestra una lista de paises. El usuario debe elegir aquel en el que se encuentre el aeropuerto que está buscando:</p>

</font><BR>

<?php


 include "/var/www/biblioteca/nusoap/lib/nusoap.php";


//$cliente = new soapclient ('http://live.capescience.com/wsdl/GlobalWeather.wsdl', true);
$cliente = new nusoap_client ('http://live.capescience.com/wsdl/GlobalWeather.wsdl', true);
 $proxy = $cliente -> getProxy ();

 
 
 
 //$paises = $proxy -> ListCountries ();
$paises = $cliente -> call($proxy,'ListCountries');
print_r($paises);print("<br>");
 echo "<CENTER>";
 echo "<form method=\"post\" action=\"clienteAirport.php\">";
 echo "<b>Seleccione pais :  </b> <select name=\"pais\">";
 foreach ($paises as $pais) {
   echo "<option value=\"$pais\">$pais";
 }
 echo "</select>";

 echo "<p>";

 echo "<input type=\"submit\" value=\"Elegir pais\">";
 echo "</form>";
 echo "</CENTER>";
?>
<br>
<hr>
<b>Autor: </b> José Ignacio Alcázar Agueria
<br>
<b>Código Fuente: <a href="Codigo.zip">Codigo.zip</a>
<br>
<b>E-Mail: </b><A href="mailto:i2619734@petra.euitio.uniovi.es?subject=Global Weather">i2619734@petra.euitio.uniovi.es</A><BR><BR>
</body>
</html>