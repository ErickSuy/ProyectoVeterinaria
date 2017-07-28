<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 14/08/14
 * Time: 07:16 AM
 */

require_once("../.././fw/model/mapping/TbUser.php");
require_once("../.././fw/model/mapping/TbPrivilege.php");
require_once("../../libraries/biblio/SysConstant.php");
session_start();

$objuser = unserialize($_SESSION['usuario']);
if ($objuser) {
    $privileges = $objuser->getPrivileges();
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!--[if lt IE 7]>
    <mce:script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js"
                mce_src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js"
                type="text/javascript"></mce:script> <![endif]-->
    <?php
    include("top_page.php");
    ?>
    <title>FMVZ - Usac.</title>
    <script>
        $(function () {
            $.ajax({
                url: '../.././fw/view/ProfileForm.php?service=<?php echo(SRV_OBTENER_DEPARTAMENTOS);?>',
                type: 'post',
                success: function (response) {
                    $("#cbdepartamentos").html(response);
                }
            });
        });

        function listarSeleccionado(indice, valor) {
            var dpt = document.getElementById('extendidaendepto')[indice].text;

            var seleccionado = false;

            for (var i = 0; i <= document.getElementById('extendidaenmunic').length; i++) {
                if ((document.getElementById('extendidaenmunic')[i]).value == dpt) {
                    (document.getElementById('extendidaenmunic')[i]).style.display = "block";
                    if (!seleccionado) {
                        document.getElementById('extendidaenmunic')[i].selected = true;
                        seleccionado = true;
                    }
                } else {
                    (document.getElementById('extendidaenmunic')[i]).style.display = "none";
                }
            }
        }

    </script>
    <script language="javascript">

    function no_trabaja() {
        document.getElementById("trabaja").style.display = "none"
    }
    function si_trabaja() {
        document.getElementById("trabaja").style.display = "block"
    }
    function no_cambiocarrera() {
        document.getElementById("cambiocarrera").style.display = "none"
    }
    function si_cambiocarrera() {
        document.getElementById("cambiocarrera").style.display = "block"
    }
    function no_trasladofacultad() {
        document.getElementById("trasladofacultad").style.display = "none"
    }
    function si_trasladofacultad() {
        document.getElementById("trasladofacultad").style.display = "block"
    }
    function no_trasladouniversidad() {
        document.getElementById("trasladouniversidad").style.display = "none"
    }
    function si_trasladouniversidad() {
        document.getElementById("trasladouniversidad").style.display = "block"
    }

    function cedulaCorrecta(forma) {
        var cedula = forma.cedula.value;
        if (!cedula.match("[A-Za-z]-[0-9][0-9] [0-9]*")) {
            return 0;
        }
        cedula = cedula.substring(0, 4);
        var indice_seleccionado = forma.extendidaendepto.selectedIndex;
        switch (indice_seleccionado) {
            case 0:
                if (cedula == "A-01") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 1:
                if (cedula == "D-04") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 2:
                if (cedula == "B-02") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 3:
                if (cedula == "C-03") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 4:
                if (cedula == "E-05") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 5:
                if (cedula == "F-06") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 6:
                if (cedula == "G-07") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 7:
                if (cedula == "H-08") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 8:
                if (cedula == "I-09") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 9:
                if (cedula == "J-10") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 10:
                if (cedula == "K-11") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 11:
                if (cedula == "L-12") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 12:
                if (cedula == "M-13") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 13:
                if (cedula == "N-14") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 14:
                if (cedula == "Ñ-15") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 15:
                if (cedula == "O-16") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 16:
                if (cedula == "P-17") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 17:
                if (cedula == "Q-18") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 18:
                if (cedula == "R-19") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 19:
                if (cedula == "S-20") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 20:
                if (cedula == "T-21") {
                    return 1;
                }
                else {
                    return 0;
                }
                break;
            case 21:
                if (cedula == "U-22") {
                    return 1;
                }
                else {
                    return 0;
                }
        }
    }

    function imprimirformulario(argsForm) {
        var i;
        var r = 0;
        var trabaja = 'No', cambiocarrera = 'No', trasladofacultad = 'No', trasladouniversidad = 'No', solicitoequivalencias = 'No';
        if (argsForm.radiobutton[1].checked) {
            trabaja = 'Si';
        } else {
            argsForm.lugartrabajo.value = "";
            argsForm.cargo.value = "";
            argsForm.direcciontrabajo.value = "";
        }
        if (argsForm.radiobutton1[1].checked) {
            cambiocarrera = 'Si';
        } else {
            argsForm.carreraorigen.value = "";
        }
        if (argsForm.radiobutton2[1].checked) {
            trasladofacultad = 'Si';
        } else {
            argsForm.facultadorigen.value = "";
        }
        if (argsForm.radiobutton3[1].checked) {
            trasladouniversidad = 'Si';
        } else {
            argsForm.universidadorigen.value = "";
        }
        if (argsForm.radiobutton4[1].checked) {
            solicitoequivalencias = 'Si';
        } else {
            solicitoequivalencias = 'No';
        }

        if (argsForm.radiobutton[1].checked && (argsForm.lugartrabajo.value == '' || argsForm.cargo.value == '' || argsForm.direcciontrabajo.value == '')) {
            r = 1;
        }
        if (argsForm.radiobutton1[1].checked && (argsForm.carreraorigen.value == '')) {
            r = 1;
        }
        if (argsForm.radiobutton2[1].checked && (argsForm.facultadorigen.value == '')) {
            r = 1;
        }
        if (argsForm.radiobutton3[1].checked && (argsForm.universidadorigen.value == '')) {
            r = 1;
        }
        if (argsForm.cedula.value != '' && argsForm.lugarnacimiento.value != '' && argsForm.titulo.value != '' && argsForm.establecimiento.value != '' && argsForm.nombrepadre.value != '' && argsForm.nombremadre.value != '' && argsForm.anioingreso.value != '' && r == 0) {
            if (cedulaCorrecta(argsForm) == 1) {
                var altura = screen.height;
                var ancho = screen.width - 150;
                var propiedades = "top=7,left=170,toolbar=no,directories=no,menubar=no,status=no,scrollbars=yes";
                propiedades = propiedades + ",height=" + altura;
                propiedades = propiedades + ",width=" + ancho;


                var cadenaparametros = 'cedula=' + transformarCadena(argsForm.cedula.value) +
                    ' &extendidaen=' + transformarCadena(argsForm.extendidaenmunic[argsForm.extendidaenmunic.selectedIndex].label) + ", " +
                    transformarCadena(argsForm.extendidaendepto[argsForm.extendidaendepto.selectedIndex].id) +
                    ' &lugarnac=' + transformarCadena(argsForm.lugarnacimiento.value) +
                    ' &titulo=' + transformarCadena(argsForm.titulo.value) +
                    ' &establecimiento=' + transformarCadena(argsForm.establecimiento.value) +
                    ' &lugartrabajo=' + transformarCadena(argsForm.lugartrabajo.value) +
                    ' &cargo=' + transformarCadena(argsForm.cargo.value) +
                    ' &direcciontrabajo=' + transformarCadena(argsForm.direcciontrabajo.value) +
                    ' &nombrepadre=' + transformarCadena(argsForm.nombrepadre.value) +
                    ' &nombremadre=' + transformarCadena(argsForm.nombremadre.value) +
                    ' &carreraorigen=' + transformarCadena(argsForm.carreraorigen.value) +
                    ' &facultadorigen=' + transformarCadena(argsForm.facultadorigen.value) +
                    ' &universidadorigen=' + transformarCadena(argsForm.universidadorigen.value) +
                    ' &mesingreso=' + transformarCadena(argsForm.select2[argsForm.select2.selectedIndex].value) +
                    ' &anioingreso=' + transformarCadena(argsForm.anioingreso.value) +
                    ' &trabaja=' + trabaja +
                    ' &cambiocarrera=' + cambiocarrera +
                    ' &trasladofacultad=' + trasladofacultad +
                    ' &trasladouniversidad=' + trasladouniversidad +
                    ' &solicitoequivalencias=' + solicitoequivalencias;
                Ventana = document.open('vistaprevia.php?' + cadenaparametros, 'InformacionGraduandos', propiedades);
                GuardarDatos(argsForm);
            } else {
                alert("Cédula Incorrecta. El número de orden no corresponde con el departamento o formato de cédula equivocado");
            }
        } else {
            alert("Debe llenar todos los campos marcados como obligatorios");
        }
    }

    function GuardarDatos(argsForm) {
        var i;
        var r = 0;
        var trabaja = 0, cambiocarrera = 0, trasladofacultad = 0, trasladouniversidad = 0, solicitoequivalencias = 0;
        if (argsForm.radiobutton[1].checked) {
            trabaja = 1;
        } else {
            argsForm.lugartrabajo.value = "";
            argsForm.cargo.value = "";
            argsForm.direcciontrabajo.value = "";
        }
        if (argsForm.radiobutton1[1].checked) {
            cambiocarrera = 1;
        } else {
            argsForm.carreraorigen.value = "";
        }
        if (argsForm.radiobutton2[1].checked) {
            trasladofacultad = 1;
        } else {
            argsForm.facultadorigen.value = "";
        }
        if (argsForm.radiobutton3[1].checked) {
            trasladouniversidad = 1;
        } else {
            argsForm.universidadorigen.value = "";
        }
        if (argsForm.radiobutton4[1].checked) {
            solicitoequivalencias = 1;
        }
        if (argsForm.radiobutton[1].checked && (argsForm.lugartrabajo.value == '' || argsForm.cargo.value == '' || argsForm.direcciontrabajo.value == '')) {
            r = 1;
        }
        if (argsForm.radiobutton1[1].checked && (argsForm.carreraorigen.value == '')) {
            r = 1;
        }
        if (argsForm.radiobutton2[1].checked && (argsForm.facultadorigen.value == '')) {
            r = 1;
        }
        if (argsForm.radiobutton3[1].checked && (argsForm.universidadorigen.value == '')) {
            r = 1;
        }
        if (argsForm.cedula.value != '' && argsForm.lugarnacimiento.value != '' && argsForm.titulo.value != '' && argsForm.establecimiento.value != '' && argsForm.nombrepadre.value != '' && argsForm.nombremadre.value != '' && argsForm.anioingreso.value != '' && r == 0) {
            if (cedulaCorrecta(argsForm) == 1) {
                var cadenaparametros = 'cedula=' + transformarCadena(argsForm.cedula.value) +
                    ' &extmunic=' + argsForm.extendidaenmunic[argsForm.extendidaenmunic.selectedIndex].value +
                    ' &extdepto=' + argsForm.extendidaendepto[argsForm.extendidaendepto.selectedIndex].value +
                    ' &lugarnac=' + transformarCadena(argsForm.lugarnacimiento.value) +
                    ' &titulo=' + transformarCadena(argsForm.titulo.value) +
                    ' &establecimiento=' + transformarCadena(argsForm.establecimiento.value) +
                    ' &lugartrabajo=' + transformarCadena(argsForm.lugartrabajo.value) +
                    ' &cargotrabajo=' + transformarCadena(argsForm.cargo.value) +
                    ' &direcciontrabajo=' + transformarCadena(argsForm.direcciontrabajo.value) +
                    ' &nombrepadre=' + transformarCadena(argsForm.nombrepadre.value) +
                    ' &nombremadre=' + transformarCadena(argsForm.nombremadre.value) +
                    ' &carreraorigen=' + transformarCadena(argsForm.carreraorigen.value) +
                    ' &facultadorigen=' + transformarCadena(argsForm.facultadorigen.value) +
                    ' &universidadorigen=' + transformarCadena(argsForm.universidadorigen.value) +
                    ' &mesingreso=' + argsForm.select2[argsForm.select2.selectedIndex].value +
                    ' &anioingreso=' + argsForm.anioingreso.value +
                    ' &trabaja=' + trabaja +
                    ' &cambiocarrera=' + cambiocarrera +
                    ' &trasladofacultad=' + trasladofacultad +
                    ' &trasladouniversidad=' + trasladouniversidad +
                    ' &solicitoequivalencias=' + solicitoequivalencias;
                document.location.href = "GuardarDatos.php?" + cadenaparametros;
            } else {
                alert("Cédula Incorrecta. El número de orden no corresponde con el departamento o formato de cédula equivocado");
            }
        } else {
            alert("Debe llenar todos los campos marcados como obligatorios");
        }
    }
    function seleccionarGuate(forma) {
        forma.extendidaendepto[0].selected = true;
        listarSeleccionado(forma);
    }

    function listarSeleccionado(forma) {
        var dpt = forma.extendidaendepto.value;
        var seleccionado = false;
        for (var i = 0; i <= forma.extendidaenmunic.length; i++) {
            if (forma.extendidaenmunic[i].id == dpt) {
                forma.extendidaenmunic[i].style.display = "block";
                if (!seleccionado) {
                    forma.extendidaenmunic[i].selected = true;
                    seleccionado = true;
                }
            } else {
                forma.extendidaenmunic[i].style.display = "none";
            }
        }
    }

    function rellenarLugarnac(forma) {
        forma.lugarnacimiento.value = "";
        forma.lugarnacimiento.value = forma.extendidaenmunic[forma.extendidaenmunic.selectedIndex].label + ", " + forma.extendidaendepto[forma.extendidaendepto.selectedIndex].id;
        forma.lugarnacimiento.selected = true;
    }
    function transformarCadena(cadena) {
        //cadena = cadena.toUpperCase();
        cadena = cadena.replace("Á", "Aacute");
        cadena = cadena.replace("á", "aacute");
        cadena = cadena.replace("É", "Eacute");
        cadena = cadena.replace("é", "eacute");
        cadena = cadena.replace("Í", "Iacute");
        cadena = cadena.replace("í", "iacute");
        cadena = cadena.replace("Ó", "Oacute");
        cadena = cadena.replace("ó", "oacute");
        cadena = cadena.replace("Ú", "Uacute");
        cadena = cadena.replace("ú", "uacute");
        cadena = cadena.replace("Ñ", "Ntilde");
        cadena = cadena.replace("ñ", "ntilde");
        return cadena;
    }
    </script>
    </head>

    <body onload="document.informacionexpediente.cedula.focus()">
    <?php
    include("../includes/session_header.php");
    ?>
    <table width="90" align="center">
        <tr>
            <td>
                <div id="wrapper">
                    <div id="content">
                        <div id="colOne">
                            <?php include("menu.php"); ?>
                        </div>
                        <div id="colTwo">
                            <table width="105%" cellspacing="8" cellpadding="0" class="formbg">
                            <tr>
                                <td>
                                    <table style="width: 100%" class=assign-form">
                                        <tr>
                                            <td align="left" valign="middle" width="10%"><img
                                                    src="../../resources/images/menu-img1.png"
                                                    width="46" height="46"
                                                    border="1" class="step-img">
                                            </td>
                                            <td align="left" width="90%"><h1>Información de Expediente</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><br/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><img class="profile-img1"
                                                                 src="../.././resources/images/profile-img.png"
                                                                 alt=""></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <form name="informacionexpediente" id="informacionexpediente" action="" method=POST onSubmit="">
                            <tr>
                            <td>
                            <table align="right">
                                <tr>
                                    <td width="100%" align="right" class='labeltext'>
                                        <?php
                                        $time = strtotime(date("d.m.Y"));
                                        $month = date("m", $time);
                                        $year = date("Y", $time);
                                        $day = date("d", $time);
                                        switch ($month) {
                                            case '01' :
                                                $month = "Enero";
                                                break;
                                            case '02' :
                                                $month = "Febrero";
                                                break;
                                            case '03' :
                                                $month = "Marzo";
                                                break;
                                            case '04' :
                                                $month = "Abril";
                                                break;
                                            case '05' :
                                                $month = "Mayo";
                                                break;
                                            case '06' :
                                                $month = "Junio";
                                                break;
                                            case '07' :
                                                $month = "Julio";
                                                break;
                                            case '08' :
                                                $month = "Agosto";
                                                break;
                                            case '09' :
                                                $month = "Septiembre";
                                                break;
                                            case '10' :
                                                $month = "Octubre";
                                                break;
                                            case '11' :
                                                $month = "Noviembre";
                                                break;
                                            case '12' :
                                                $month = "Diciembre";
                                                break;
                                            default   :
                                                $month = "Mes";
                                        }
                                        echo 'Guatemala, ' . $day . ' de ' . $month . ' de ' . $year;
                                        ?>
                                        <br/>
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%" class="assign-form">
                                <tr>
                                    <td>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="65%" style="text-align:left" class='labeltext'><b>Nombre
                                                    Completo: </b> <?php echo $objuser->getSurname() . ", " . $objuser->getName(); ?> </td>
                                            <td width="35%" style="text-align:right" class='labeltext'><b>Número de
                                                    carnet:</b> <?php echo $objuser->getId(); ?> </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="60%" style="text-align:left" class='labeltext'>
                                                <b>Carrera:</b> <?php switch ($objuser->getCareer()) {
                                                    case VETERINARIA:
                                                        echo("02 MEDICINA VETERINARIA");
                                                        break;
                                                    case ZOOTECNIA:
                                                        echo("03 ZOOTECNIA");
                                                        break;
                                                } ?> </td>
                                            <td width="20%" style="text-align:center" class='labeltext'>
                                                <b>Edad:</b> <?php echo $objuser->getAge(); ?> </td>
                                            <td width="20%" style="text-align:right" class='labeltext'>
                                                <b>Sexo:</b> <?php echo $objuser->getGender(); ?> </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="50%" style="text-align:left" class='labeltext'>
                                                <b>Nacionalidad:</b> <?php echo $objuser->getNationality(); ?> </td>
                                            <td width="50%" style="text-align:right" class='labeltext'><b>Fecha de
                                                    nacimiento:</b> <?php $time = strtotime($objuser->getBirthDate());
                                                $month = date("m", $time);
                                                $year = date("Y", $time);
                                                $day = date("d", $time);
                                                echo $day . '/' . $month . '/' . $year; ?> </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="70%" style="text-align:left" class='labeltext'><b>Direcci&oacute;n
                                                    domiciliaria:</b> <?php $objuser->getAddress(); ?> </td>
                                            <td width="30%" style="text-align:right" class='labeltext'><b>Tel&eacute;fono
                                                    fijo:</b> <?php echo $objuser->getPhone(); ?> </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="40%" style="text-align:left" class='labeltext'><b>Tel&eacute;fono
                                                    m&oacute;vil:</b> <?php echo $objuser->getCelular(); ?> </td>
                                            <td width="60%" style="text-align:right" class='labeltext'><b>Correo
                                                    electr&oacute;nico:</b> <?php echo $objuser->getMail(); ?> </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="30%" style="text-align:left" class='labeltext'><b>DPI:* </b>*
                                                <input name="cedula" type="text" class='labeltext' id="cedula" value="{cedula}" size="16"
                                                       maxlength="13"/>
                                            </td>
                                            <td width="70%" style="text-align:left" class='labeltext'><b>Extendida en:* </b>
                                                <span id="cbdepartamentos"></span>
                                                ,
                                                <select name="extendidaenmunic" class='labeltext' id="_extendidaenmunic"/>
                                                <!-- START BLOCK : LLENAR_SELECT_MUNIC -->
                                                <option value="{i_munic}" id="{indice_munic}" label="{nombre_munic}">{nombre_munic}</option>
                                                <!-- END BLOCK : LLENAR_SELECT_MUNIC -->
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="40%" style="text-align:left" class='labeltext'>
                                                <font color="#FF0000" size="-2">Formato: X-XX XXXXXXX<br/>
                                                    Ej.: A-01 0000000, P-17 1111111
                                                </font></td>
                                            <td width="60%" style="text-align:left" class='labeltext'>
                                                <font color="#FF0000" size="-2">Departamento, municipio</font></td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="25%" style="text-align:left" class='labeltext'><b>Lugar de Nacimiento:</b>*
                                            </td>
                                            <td width="75%" style="text-align:left" class='labeltext'>
                                                <input name="textfield2" type="text" class='labeltext' id="lugarnacimiento"
                                                       onfocus="javascript:rellenarLugarnac(document.informacionexpediente);"
                                                       value="<?php echo $objuser->getBirthaddres(); ?>" size="70" maxlength="60"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="25%" style="text-align:left" class='labeltext'><b>T&iacute;tulo de estudios a nivel
                                                    medio:</b>*
                                            </td>
                                            <td width="75%" style="text-align:left" class='labeltext'>
                                                <input name="textfield3" type="text" class='labeltext' id="titulo"
                                                       value="<?php echo $objuser->getTitle(); ?>" size="70" maxlength="85"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="25%" style="text-align:left" class='labeltext'><b>Establecimiento donde lo obtuvo:</b>*</td>
                                            <td width="75%" style="text-align:left" class='labeltext'>
                                                <input name="textfield4" type="text" class='labeltext' id="establecimiento"
                                                       value="<?php echo $objuser->getInstitutionName(); ?>" size="70" maxlength="80"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100%" style="text-align:left" class='labeltext'>
                                                <b>&iquest;Trabaja actualmente?</b> No
                                                <input name="radiobutton" type="radio" value="no" class='labeltext' checked="checked"
                                                       onClick="no_trabaja();"/>
                                                S&iacute;
                                                <input name="radiobutton" type="radio" value="si" class='labeltext' onClick="si_trabaja();"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr>
                                                        <td width="45%" style="text-align:left" class='labeltext'><b>Lugar de trabajo:</b>*</td>
                                                        <td width="55%" style="text-align:left" class='labeltext'>
                                                            <input name="textfield5" type="text" class='labeltext' id="lugartrabajo"
                                                                   value="{lugartrabajo}" size="75" maxlength="60"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr>
                                                        <td width="45%" style="text-align:left" class='labeltext'><b>Cargo que desempe&ntilde;a:</b>*
                                                        </td>
                                                        <td width="55%" style="text-align:left" class='labeltext'>
                                                            <input name="textfield6" type="text" class='labeltext' id="cargo" value="{cargotrabajo}"
                                                                   size="75" maxlength="50"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr>
                                                        <td width="45%" style="text-align:left" class='labeltext'><b>Direcci&oacute;n del
                                                                trabajo:* </b>
                                                        </td>
                                                        <td width="55%" style="text-align:left" class='labeltext'>
                                                            <input name="textfield7" type="text" class='labeltext' id="direcciontrabajo"
                                                                   value="{direcciontrabajo}" size="75" maxlength="85"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="25%" style="text-align:left" class='labeltext'><b>Nombre del padre:* </b>
                                            </td>
                                            <td width="75%" style="text-align:left" class='labeltext'>
                                                <input name="textfield8" type="text" class='labeltext' id="nombrepadre"
                                                       value="<?php $objuser->getFather(); ?>" size="85" maxlength="60"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="25%" style="text-align:left" class='labeltext'><b>Nombre de la madre:* </b>
                                            </td>
                                            <td width="75%" style="text-align:left" class='labeltext'>
                                                <input name="textfield9" type="text" class='labeltext' id="nombremadre"
                                                       value="<?php $objuser->getMother(); ?>" size="85" maxlength="60"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100%" style="text-align:left" class='labeltext'><b>&iquest;Cambió de Carrera? </b>No
                                                <input name="radiobutton1" type="radio" value="no" checked="checked" class='labeltext'
                                                       onClick="no_cambiocarrera();"/>
                                                Sí
                                                <input name="radiobutton1" type="radio" value="si" class='labeltext' onClick="si_cambiocarrera();"/>
                                                <label id="cambiocarrera" class="labeltext"><b><br/>
                                                        Carrera Origen*: </b>
                                                    <input name="textfield9" type="text" class='labeltext' id="carreraorigen"
                                                           value="{carreraorigen}" size="75" maxlength="50"/>
                                                </label>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100%" style="text-align:left" class='labeltext'><b>&iquest;Traslado de Facultad? </b>No
                                                <input name="radiobutton2" type="radio" value="no" checked="checked" class='labeltext'
                                                       onClick="no_trasladofacultad();"/>
                                                S&iacute;
                                                <input name="radiobutton2" type="radio" value="si" class='labeltext'
                                                       onClick="si_trasladofacultad();"/>
                                                <label id="trasladofacultad" class="labeltext"><b><br/>
                                                        Facultad de Origen*: </b>
                                                    <input name="textfield10" type="text" class='labeltext' id="facultadorigen"
                                                           value="{facultadorigen}" size="75" maxlength="50"/>
                                                </label>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100%" style="text-align:left" class='labeltext'><b>&iquest;Traslado de Universidad? </b>No
                                                <input name="radiobutton3" type="radio" value="no" checked="checked" class='labeltext'
                                                       onClick="no_trasladouniversidad();"/>
                                                S&iacute; <input name="radiobutton3" type="radio" value="si" class='labeltext'
                                                                 onClick="si_trasladouniversidad();"/>
                                                <label id="trasladouniversidad" class="labeltext"><b><br/>
                                                        Universidad de Origen*: </b>
                                                    <input name="textfield11" type="text" class='labeltext' id="universidadorigen"
                                                           value="{universidadorigen}" size="75" maxlength="40"/>
                                                </label></td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100%" style="text-align:left" class='labeltext'><b>&iquest;Solicitó equivalencias por su
                                                    carrera? </b>No
                                                <input name="radiobutton4" type="radio" value="no" checked="checked" class='labeltext'/>
                                                S&iacute; <input name="radiobutton4" type="radio" value="si" class='labeltext'/></td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100%" style="text-align:left" class='labeltext'>
                                                <label class="labeltext"><b>Fecha de Ingreso a la Facultad de Veterinaria</b></label>
                                                <font color="#FF0000">(verifique el año)</font>:
                                                <label class='labeltext'> Mes *
                                                    <select name="select2" class='labeltext' id="select2">
                                                        <option value="1">Enero</option>
                                                        <option value="2">Febrero</option>
                                                        <option value="3">Marzo</option>
                                                        <option value="4">Abril</option>
                                                        <option value="5">Mayo</option>
                                                        <option value="6">Junio</option>
                                                        <option value="7">Julio</option>
                                                        <option value="8">Agosto</option>
                                                        <option value="9">Septiembre</option>
                                                        <option value="10">Octubre</option>
                                                        <option value="11">Noviembre</option>
                                                        <option value="12">Diciembre</option>
                                                    </select>
                                                </label>
                                                <label class='labeltext'> Año* </label>
                                                <input name="textfield12" type="text" class='labeltext' id="anioingreso" value="{anioingreso}"
                                                       size="15" maxlength="4"/>
                                            </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>

                            <p class='labeltext' align="center">* Indica campo obligatorio &nbsp;</p>

                            <p>&nbsp;</p>
                            <table width="100%" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td width="34%" style="text-align:center" class='labeltext'>
                                        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" name="btnSave" onclick="no_trabaja();">Guardar Datos</a>
                                    </td>
                                    <td width="33%" style="text-align:center" class='labeltext'>&nbsp;</td>
                                    <td width="33%" style="text-align:center" class='labeltext'>
                                        <a href="#" class="easyui-linkbutton" iconCls="icon-print" name="btnPrint" onclick="javascript:imprimirformulario(document.informacionexpediente);">Imprimir Formulario</a>
                                    </td>
                                </tr>
                            </table>
                            </td>
                            </tr>
                            <script language="javascript">
                                no_trabaja();
                                no_cambiocarrera();
                                no_trasladofacultad();
                                no_trasladouniversidad();
                                document.informacionexpediente.radiobutton4[0].checked = true;
                                seleccionarGuate(document.informacionexpediente);
                            </script>
                            </form>
                            </table>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
            </td>
        </tr>
    </table>
    <div id="footer">
        <?php include(".././includes/footer.php"); ?>
    </div>
    </body>

    </html>
<?php
} else {
    header("Location: ../index.php");
}
?>