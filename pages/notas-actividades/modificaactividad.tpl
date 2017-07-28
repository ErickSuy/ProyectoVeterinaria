<!-- Plantilla:
Conversión de codificación a UTF-8
11/21/11-10:53:54
 utf-8
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<script language="javascript">
function trim(cadena)
{
	for(i=0; i<cadena.length; )
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}
	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	return(cadena)
}// de la function TRIM


function ValidaAgregar()
{
document.ABCTipoActividad.txtNuevaActividad.value=trim(document.ABCTipoActividad.txtNuevaActividad.value);
   if(document.ABCTipoActividad.txtNuevaActividad.value=="")
   {
      alert("Debe ingresar el nombre del tipo de la actividad");
	  document.ABCTipoActividad.txtNuevaActividad.focus();
	  document.ABCTipoActividad.txtNuevaActividad.select();
	  return false;
	  
   }
document.ABCTipoActividad.opcion.value=1;
document.ABCTipoActividad.submit();  
}// de la funcion ValidaAgregar


function ValidaActualizar()
{
document.ABCTipoActividad.txtNombreActualizar.value=trim(document.ABCTipoActividad.txtNombreActualizar.value);
   if(document.ABCTipoActividad.txtNombreActualizar.value=="")
   {
      alert("Debe ingresar el nombre del tipo de la actividad");
	  document.ABCTipoActividad.txtNombreActualizar.focus();
	  document.ABCTipoActividad.txtNombreActualizar.select();
	  return false;
	  
   }
document.ABCTipoActividad.opcion.value=3;
document.ABCTipoActividad.submit();  
}// de la funcion ValidaAgregar




function InactivaTipoActividad(tipoactividad)
{
  var direccion="modificaactividad.php?opcion=2&txtTipoActividad="+tipoactividad;
  location.href=direccion;
}

function SuperActividad(tipoactividad,esSuperActividad)
{
  
  var direccion="modificaactividad.php?opcion=4&txtTipoActividad="+tipoactividad+"&txtSuperActividad="+esSuperActividad;
  location.href=direccion;
}


function AjustaModificar(codigo,nombre)
{
  var targetElement = document.getElementById("tbActualizar");
  targetElement.style.display = "";  
  document.ABCTipoActividad.txtNombreActualizar.value=nombre;
  document.ABCTipoActividad.txtCodigoActualizar.value=codigo;
}

</script>
</head>

<body>
<!-- START BLOCK : actividad -->
<form id="ABCTipoActividad" name="ABCTipoActividad" method="post" action="">
<div align="center">INGRESO DE NUEVA ACTIVIDAD
</div>
<table border="0" align="center">
  <tr>
    <td><label>
      <input name="opcion" type="hidden" id="opcion" value="0" size="3" maxlength="3" />
    </label></td>
    <td>
      <label>
        <input type="text" name="txtNuevaActividad" id="txtNuevaActividad" />
        </label>    </td>
    <td><label>
      <input type="Button" name="button" id="button" value="   Agregar   " onclick="ValidaAgregar();"/>
    </label></td>
  </tr>
</table>
<br />
<table border="0" align="center">
  <tr>
    <td colspan="3"><div align="center">LISTADO DE ACTIVIDADES </div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center">Nombre </div></td>
    <td>&nbsp;</td>
  </tr>
<!-- START BLOCK : filaactividad -->  
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input name="txtCodigoTipoActividad" type="hidden" id="txtCodigoTipoActividad" value="{txtCodigoTipoActividad}" size="3" maxlength="3" />
    </label>
      {txtNombreTipoActividad}</td>
    <td>
		<!-- START BLOCK : botonesactividad -->  
        <img src="Editar.png" alt="Editar" width="16" height="16" onclick="AjustaModificar({txtCodigoTipoActividad}, '{txtNombreTipoActividad}') "/>
          &nbsp;&nbsp;
          <img src="Borrar.png" alt="Eliminar" onclick="InactivaTipoActividad({txtCodigoTipoActividad})"/>  
          &nbsp;&nbsp;
          <img src="SuperActividad.jpg" alt="Super Actividad" onclick="SuperActividad({txtCodigoTipoActividad},{txtEsSuperActvividad})"/>  

		<!-- END BLOCK : botonesactividad -->     </td>
  </tr>
<!-- START BLOCK : filaactividad -->    
</table>
<br />
<table border="0" align="center" style="display:none" id="tbActualizar">
  <tr>
    <td><label>
      <input name="txtCodigoActualizar" type="hidden" id="txtCodigoActualizar" value="0" size="3" maxlength="3" />
    </label></td>
    <td><label>
      <input type="text" name="txtNombreActualizar" id="txtNombreActualizar" />
      </label>
    </td>
    <td><label>
      <input type="button" name="button2" id="button2" value="   Actualizar   " onclick="ValidaActualizar();"/>
    </label></td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
<!-- END BLOCK : actividad -->
</body>
</html>