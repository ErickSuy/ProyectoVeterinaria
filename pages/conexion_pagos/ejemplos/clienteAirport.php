<html>
<HEAD><TITLE>Búsqueda de aeropuerto</TITLE>
</HEAD>
<body bgcolor= "#66CCFF">

<br>
<h1 align="center"> Búsqueda de un aeropuerto específico</h1>
<hr>
<br>



<?php
 echo "<font size=4>";
 echo "<p align=center> Aeropuertos de    ";
 echo "<br>";

 echo "</font>";
 echo "<b>";
 echo "<font size=10>";
 echo strtoupper($pais);
 echo "</font>";
 echo "</b>";
 echo "</p>";
?>

<CENTER>
<IMG 
      alt="Aeropuerto" 
      src="imagenes/airport.jpg"
> 
</CENTER>

<br>
<p align="center">A continuacion debera elegir un aeropuerto del cual se quiera hacer la consulta meteorológica:</p>
<?php


include "nusoap.php";

 $cliente = new soapclient ('http://live.capescience.com/wsdl/GlobalWeather.wsdl', true);
 $proxy = $cliente -> getProxy ();

 $pistas = $proxy -> SearchByCountry ($pais);

echo "<CENTER>";
 echo "<form method=\"post\" action=\"meteorologia.php\">";
 echo "<b>Seleccione Aeropuerto:</b> <select name=\"pista\">";
 
  asort($pistas);
  foreach ($pistas as $pista) {
	$nombre=$pista['name'];
	$codigo=$pista['wmo'];
   echo "<option value=\"$codigo\">$nombre";
 }

 

 
 echo "</select>";

 echo "<p>";

 echo "<input type=\"submit\" value=\"Elegir aeropuerto\">";
 echo "</form>";
echo "</CENTER>";
?>
</body>
</html>