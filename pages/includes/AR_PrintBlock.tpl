<?xml version="1.0" encoding="utf-8"?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="distribution" content="global" />
    <TITLE> {vTitulo} </TITLE>
</head>
<body>
<table width=100% border="3px">
	<td>
		<table width=100%>
			<tr>
				<td colspan="3"><div align="center"><font face="Arial, Helvetica, sans-serif" size="-2"><strong>ORDEN DE PAGO</strong></font></div></td>
			</tr>
			<tr>
				<td width="22%"><div align="right"><font size="-2">No.</font></div></td>
				<td width="4%"></td>
				<td width="74%"><font size="-2">{numeroOrden}</font></td>
			</tr>
			<tr>
				<td><div align="right"><font size="-2">Carn&eacute;</font></div></td>
				<td></td>
				<td><font size="-2">{vcarne}</font></td>
			</tr>
			<tr>
				<td><div align="right"><font size="-2">Nombre</font></div></td>
				<td></td>
				<td><font size="-2">{vnombre}</font></td>
			</tr>
			<tr>
				<td><div align="right"><font size="-2">Facultad</font></div></td>
				<td></td>
				<td><font size="-2">Medicina Veterinaria y Zootecnia</font></td>
			</tr>
			<tr>
				<td><div align="right"><font size="-2">Extensión</font></div></td>
				<td></td>
				<td><font size="-2">Plan Diario</font></td>
			</tr>
			<tr>
				<td><div align="right">Carrera</div></td>
				<td></td>
				<td>{vnomCarrera}</td>
			</tr>
			<tr>
				<td colspan="3"><br /></td>
			</tr>
			<tr>
				<td colspan="3"><div align="center"><strong>DETALLE DE PAGO DE</strong></div></td>
			</tr>
			<td colspan="3"><div align="right"> {periodo}</div></td>
			<!-- START BLOCK : DETALLE -->
            <tr>
				<td><div align="right"><font size="-2">{codCurso}</font></div></td>
				<td></td>
				<td><font size="-2">{nombreCurso} - Q.{precioCurso}</font></td>
			</tr>
            <!-- END BLOCK : DETALLE -->
			<tr>
				<td><div align="right"></div></td>
				<td></td>
				<td align="right"><strong>Total a Pagar</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><font size="+2">{vmonto}</font></strong></td>
			</tr>
		</table>
	</td>
	<td>
		<table width=100%>
			<tr>
				<td colspan="2"><div align="center"><strong><font face="Arial, Helvetica, sans-serif">PARA USO EXCLUSIVO DEL BANCO</font></strong></div></td>
			</tr>
			<tr><td><br /></td></tr>
			<tr>
				<td align="right">Orden de Pago&nbsp;&nbsp;</td>
				<td>{numeroOrden}</td>
			</tr>
			<tr>
				<td align="right">Carn&eacute;&nbsp;&nbsp;</td>
				<td>{vcarne}</td>
			</tr>
			<tr>
				<td align="right">Total a Pagar&nbsp;&nbsp;</td>
				<td>{vmonto}</td>
			</tr>
			<tr>
				<td align="right">C&oacute;digo Unidad&nbsp;&nbsp;</td>
				<td>{vunidad}</td>
			</tr>
			<tr>
				<td align="right">C&oacute;digo Extensi&oacute;n&nbsp;&nbsp;</td>
				<td>{vextension}</td>
			</tr>
			<tr>
				<td align="right">C&oacute;digo Carrera&nbsp;&nbsp;</td>
				<td>{vcarrera}</td>
			</tr>
			<tr>
				<td align="right">Rubro de pago&nbsp;&nbsp;</td>
				<td>{vrubro}</td>
			</tr>
			<tr>
				<td align="right">Llave&nbsp;&nbsp;</td>
				<td>{vllave}</td>
			</tr>
			<tr>
				<td colspan="2" >
					<div align="center"><strong>Puede digirse a efectuar su pago a cualquier agencia o por medio de banca virtual de BANRURAL (ATX-253) o gytContinental</strong>
					</div>
				</td>
			</tr>
		</table>
	</td>
</table>
</body>
</html>