<?php
	/**
	* Funcion que muestra la estructura de carpetas a partir de la ruta dada.
	*/
	function obtener_estructura_directorios($ruta){
		
		// Se comprueba que realmente sea la ruta de un directorio
		if (is_dir($ruta)){
			// Abre un gestor de directorios para la ruta indicada
			$gestor = opendir($ruta);
			echo "<ul>";

			// Recorre todos los elementos del directorio
			while ($archivo = readdir($gestor))  {
				
				$ruta_completa = $ruta . "/" . $archivo;

				// Se muestran todos los archivos y carpetas excepto "." y ".."
				if ($archivo != "." && $archivo != "..") {
					// Si es un directorio se recorre recursivamente
					if (is_dir($ruta_completa)) {
						echo "<li>" . $archivo . "</li>";
						obtener_estructura_directorios($ruta_completa);
					} else {
						echo "<li><a href='".$ruta_completa."'>" . $archivo . "</a></li>";
					}
				}
			}
			// Cierra el gestor de directorios
			closedir($gestor);
			echo "</ul>";
		} else {
			echo "No es una ruta de directorio valida<br/>";
		}
		
	}

	/**
	* Funcion que muestra las imagenes que hay en la ruta pasada como parametro
	*/
	function mostrar_imagenes($ruta){

		// Se comprueba que realmente sea la ruta de un directorio
		if (is_dir($ruta)){
			// Abre un gestor de directorios para la ruta indicada
			$gestor = opendir($ruta);

			// Recorre todos los archivos del directorio
			while (($archivo = readdir($gestor)) !== false)  {
				// Solo buscamos archivos sin entrar en subdirectorios
				if (is_file($ruta."/".$archivo)) {
					echo "<img src='".$ruta."/".$archivo."' width='200px' alt='".$archivo."' title='".$archivo."'>";
				}
			
			}

			// Cierra el gestor de directorios
			closedir($gestor);
		} else {
			echo "No es una ruta de directorio valida<br/>";
		}
	}
?>

<html>
<head>
	<title>Mostrar archivos de una carpeta con PHP</title>
</head>
<body>
	<div>
		<h2>Estructura de directorios</h2>
		<?php obtener_estructura_directorios("./"); ?>
	</div>

	<div>
		<h2>Imagenes del directorio</h2>
		<?php mostrar_imagenes("imagenes"); ?>
	</div>
</body>
</html>
