<html>
<HEAD><TITLE>Resumen meteorológico</TITLE>
</HEAD>
<body bgcolor= "#66CCFF">
<br>
<h1 align="center"> Resumen meteorológico</h1>
<hr>
<br>

<?php

include "nusoap.php";


 $cliente = new soapclient ('http://live.capescience.com/wsdl/GlobalWeather.wsdl', true);
 $proxy = $cliente -> getProxy ();

 $resumen = $proxy -> getWeatherReport ($pista);

echo "<font size=5><b><u>- Localización:</b></u><br></font>";

echo "<br>";
echo "<b>Pais: </b>".$resumen['station']['country'];  //pais

echo "<br>";
echo "<b>Localidad: </b>".$resumen['station']['name'];  //localidad

echo "<br>";
echo "<b>Altitud: </b>".$resumen['station']['elevation']." metros";  //altura

echo "<br>";
echo "<br>";

echo "<font size=5><b><u>- Fecha de último muestreo:</b></u></font><br><br>";
echo substr($resumen['timestamp'],8,2)."/".substr($resumen['timestamp'],5,2)."/".substr($resumen['timestamp'],0,4)."  ".substr($resumen['timestamp'],11,8);  //fecha del ultimo muestreo

echo "<br>";
echo "<br>";

echo "<font size=5><b><u>- Temperatura:</b></u><br><br></font>";
echo "<b>Ambiente: </b>".$resumen['temperature']['ambient']." ºC<br>";  //temperatura ambiente
echo "<b>Punto de condensacion: </b>".$resumen['temperature']['dewpoint']." ºC<br>";
echo "<b>Humedad relativa: </b>".$resumen['temperature']['relative_humidity']."%";

echo "<br>";
echo "<br>";

echo "<font size=5><b><u>- Presion:</b></u></font><br><br>";
echo $resumen['pressure']['string'];

echo "<br>";
echo "<br>";

// Muestro la informacion del cielo
 echo "<font size=5><b><u>- Cielo:</b></u><br></font>";
 // Desplazo los datos, para una mejor presentacion
 echo "<blockquote>";
 echo "<table border=\"0\">";
 // Si hay capas, el tipo de la variable es un Array
 if (gettype($resumen["sky"]['layers']) == 'array')
   {
      // Muestro la cabecera de la tabla
      echo "<tr><td><b>Altitud</b></td><td><b>Tipo</b></td></tr>";
      // Si el cielo no esta cerrado
      if ($resumen["sky"]['ceiling_altitude'] == INF)
         {
            // Muestro un sol
	    echo "<tr><td></td><td><IMG alt=Soleado src=imagenes/sunny.jpg>&nbsp;&nbsp;&nbsp;Soleado</td><td></td></tr>";
         };
      // Las distintas capas que hay, estan ordenadas segun
      // la altitud, asi que, para mostrar las capas segun el orden
      // hay que recorrer el array de forma inversa

      // Me situo sobre el ultimo elemento del Array
      end($resumen["sky"]['layers']);
      // Recorro el array de forma inversa
      do{
  	  // Obtengo la capa actual
          $layer = current($resumen["sky"]['layers']);
	  // Inicio la fila
	  echo "<tr>";
          // Muestro la altitud de la capa
	  echo "<td align=\"right\">" . $layer['altitude'] . " m.</td>";

 	  // Segun el tipo del fenomeno, muestro uno u otro dibujo
	  switch($layer['type'])
	   {
	      case 'MIST':echo "<td><IMG alt=Nublado src=imagenes/mist.bmp>&nbsp;&nbsp;&nbsp;Nublado</td>";
				break;
	      case 'FOG' :echo "<td><IMG alt=Niebla src=imagenes/fog.bmp>&nbsp;&nbsp;&nbsp;Niebla</td>";
			    break;
	      case 'CLOUD':echo "<td><IMG alt=Nuboso src=imagenes/cloud.bmp>&nbsp;&nbsp;&nbsp;Nuboso</td>";
                break;
	      case 'RAIN' :echo "<td><IMG alt=Lluvia src=imagenes/rain.bmp>&nbsp;&nbsp;&nbsp;Lluvia</td>";
                break;
	          default :echo "<td>".$layer['type']."</td>";
                break;
	   };
	 
	 // Cierro la fila
	 echo "</tr>";
      }while(prev($resumen["sky"]['layers']));
   }
 else
   {
       echo "<tr><td><IMG alt=Cielo despejado src=imagenes/fair.bmp>&nbsp;&nbsp;&nbsp;Cielo despejado</td></td>";
   };
 echo "</table>";
 echo "</blockquote>";

?>
</body>
</html>