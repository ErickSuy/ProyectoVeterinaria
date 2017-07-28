<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 25/09/14
 * Time: 07:20 AM
 */
?>

<html>
<head>
    <meta http-equiv="refresh" content="2; URL=redirigir.html">
    <meta name="language" http-equiv="content-language" content="es">

    <!-- INICIO: CSS Contenido General --->
    <link rel="stylesheet" href="../resources/css/layout.css?xcache=3226">
    <link rel="stylesheet" type="text/css" href="../resources/css/formfill.css">
    <link rel="stylesheet" type="text/css" href="../resources/css/buttons.css">
    <link rel="stylesheet" type="text/css" href="../resources/css/style-fmvz.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../resources/css/Font-Awesome-master/css/font-awesome.min.css">
    <!-- Google fonts -->

    <!-- INICIO: JS --->
    <script type="text/javascript" src="../libraries/js/wizard/jquery-2.0.0.min.js"></script>
    <script type="text/javascript" src="../libraries/js/FactoryContent.js?v=1"></script>
    <script type="text/javascript" src="../libraries/js/ContentManager.js"></script>
    <!-- FIN: JS --->

    <link rel="shortcut icon" href="fmvz.ico"/>

    <script>
        $(function () {
            $.ajax({
                url: '../fw/view/Service.php?service=logout',
                type: 'post',
                success: function (response) {
                    $("#lbmsglogout").html(response);
                }
            });
        });
    </script>
</head>
<body onUnload="parent.frames['topFrame'].salida=1;">
<script language='javascript'>
    function HaciaInicio() {
        parent.location.replace('index.php');
    }
</script>

<!-- ENCABEZADO -->
<div id="head">
    <div class="container_16">
        <div class="grid_logo" id="encabezado-principal-logo">
            <img src="../resources/images/banner-fmvz-p.png" width="239" height="108" alt="Logo Principal">
        </div>
        <div class="grid_12">
            <div class="buscador grid_7">
            </div>
            <div class="enc-p-b">
                <div id="enc-p-bb">
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CONTENIDO -->
<div class="bga">
    <div id="content">
        <table width="100%">
            <tbody>
            <tr>
                <td valign="top" id="vaultcol">
                    <div id="usercoldiv" class="usercolvisible" name="usercol"></div>
                    <table id="tabstable">
                        <tbody>
                        <tr>
                            <td colspan="2" id="tdtabrow"></td>
                        </tr>
                        <tr id="treerow">
                            <td valign="top" colspan="2">
                                <div id="treepane" tabindex="100">
                                    <div class="contenido-enc">
                                        <div id="contenido-enc_a">
                                            <div class="ltcolvisible" name="ltcol" id="ltcol"
                                                 style="padding-right: calc(5% + 20px);">
                                                <span>&nbsp;</span>
                                            </div>
                                            <div id="actionscol">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="demo tree tree-default" id="thetree" tabindex="100">
                                        <div id="ffframe-large" style="vertical-align: middle !important;">
                                            <div id="ffheader">
                                                <span id="pff_title" class="page_title">Cerrar sesión</span>

                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>
                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2">
                                                            <span>Cerrando sesión en el Sistema ...</span>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <div id="ff_content">
                                                        <div class="ff_pane" style="display: block;">
                                                            <div class="titulo">
                                                                <center><img align="middle" name='imagen_1'
                                                                             src='../resources/images/espera.gif'
                                                                             width='375' height='19'></center>
                                                                <spam id="lbmsglogout"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    <input type='Submit' name='Seguir' value='Aceptar'
                                                           class='nbtn rbtn btn_midi btn_exp_h okbutton'
                                                           onClick="javascript:HaciaInicio();javascript:parent.frames['topFrame'].salida=1;">
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contenido-pie"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- PIE -->
<div id="foot">
    <div id="credito">
        TRICENTENARIA UNIVERSIDAD DE SAN CARLOS DE GUATEMALA, FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA, CIUDAD
        DE GUATEMALA CAMPUS CENTRAL ZONA 12, EDIFICIO M-6. TEL 24188300. Sugerencias, Comentarios:
        <a href="mailto:avirtual.fmvz@usac.edu.gt" class="mailto-info-fmvz">avirtual.fmvz@usac.edu.gt</a>
    </div>
</div>
</body>
</html>