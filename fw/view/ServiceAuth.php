<html>

<title>Autorizaci&oacute;n</title>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content=""/>
    <meta name="Description" content=""/>
    <link href="favicon.ico" rel="shortcut icon"/>

    <!--css principal y libreria jquery principal--->
    <script type="text/javascript" src="../.././libraries/jquery-1.4.4.min.js"></script>
    <link href="../.././resources/css/theme-sae.css" rel="stylesheet" type="text/css"/>

    <!--Librer�a de Jquery Easy UI --->
    <link rel="stylesheet" type="text/css" href="../.././libraries/themes/default/easyui.css">
    <script type="text/javascript" src="../.././libraries/jquery2.easyui.min.js"></script>
    <script type="text/javascript" src="../.././libraries/locale/easyui-lang-es.js"></script>
    <link rel="stylesheet" type="text/css" href="../.././libraries/themes/gray/easyui.css">
    <link rel="stylesheet" type="text/css" href="../.././libraries/themes/icon.css">


</head>


<body>
<table id="headr" width=100% align="center" BACKGROUND="../.././resources/images/pfondo.jpg">
    <tr>
        <td width="10%">

            <img src="../.././resources/images/FACING.png" alt="" width="80" height="80" class="image"/>
        </td>
        <td width="80%">
            <h1>
                Campus Virtual
                <br>
            </h1>
        </td>
        <td width="10%">
            <img src="../.././resources/images/usac.png" alt="" width="80" height="80" class="image"/>
        </td>
    </tr>
</table>

<?php
    require_once("../controller/ControlService.php");


    if (isset($_REQUEST['service']) && $_REQUEST['service'] != "") {

        $service = $_REQUEST['service'];
        $objservice = new ControlService($service);


    }


    if ($service == 'activate') {
        ?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <form name="redirect">
            <center>

                <font face="Arial"><b>
                        <?php
                            $codegenerate = $_REQUEST['codegenerate'];
                            $code = $_REQUEST['code'];
                            $activatekey = $_REQUEST['activatekey'];

                            if ($codegenerate == md5('getIdUser(' . $code . ')')) {

                                echo $objservice->Activate($code, $activatekey);
                            }
                        ?>, ser&aacute; redireccionado en <br><br>

                        <form>
                            <input type="text" size="3" name="redirect2">
                        </form>
                        segundos</b></font>
            </center>
        </form>
        <script>

            var targetURL = "../.././pages/index.php";
            var countdownfrom = 5;


            var currentsecond = document.redirect.redirect2.value = countdownfrom + 1;
            function countredirect() {
                if (currentsecond != 1) {
                    currentsecond -= 1;
                    document.redirect.redirect2.value = currentsecond;
                }
                else {
                    window.location = targetURL;
                    return;
                }
                setTimeout("countredirect()", 1000);
            }

            countredirect();
            //-->
        </script>
        <br>
        <br>
        <br><br>
        <br>
        <br>
        <br>
    <?php
    } else if ($service == 'recover') {
        $codegenerate = $_REQUEST['codegenerate'];
        $code = $_REQUEST['code'];
        $activatekey = $_REQUEST['activatekey'];
        $password = $_REQUEST['password'];
        if ($codegenerate == md5('getIdUser(' . $code . ')')) {
            ?>
            <script>
                function cancel() {
                    window.location = "../.././pages/index.php";
                }
                function recover(codegenerate, code, activatekey) {
                    // alert('hola');
                    var cg = codegenerate;
                    var c = code;
                    var ak = activatekey;
                    var pass = document.getElementById("id_password").value;
                    var pass2 = document.getElementById("id_pass_comp").value;
                    if (pass == "" || pass2 == "") {

                        $.messager.alert('Error', 'No puede ir en blanco', 'info');

                    }

                    else {
                        if (pass == pass2) {
                            window.location = ".././view/ServiceAuth.php?service=recover2&codegenerate=" + cg + "&code=" + c + "&activatekey=" + ak + "&password=" + pass;
                        } else {

                            $.messager.alert('Error', 'Password no concuerdan', 'info');

                        }
//var x= ".././view/ServiceAuth.php?service=recover2&codegenerate="+cg+"&code="+c+"&activatekey="+ak+"&password="+pass;
                        //alert(x);
                    }
                }

                $().ready(function () {
                    $.extend($.fn.validatebox.defaults.rules, {
                        minLength: {
                            validator: function (value, param) {
                                value = $.trim((value));
                                return value.length >= param[0];
                            },
                            message: 'Debe introducir m&iacute;nimo {0} caracteres'
                        },
                        txt_valid_pass: {
                            validator: function (value, param) {
                                var ck_pass = document.getElementById("id_password").value;
                                if (value != null) {
                                    if (value == ck_pass)
                                        return true;
                                    else
                                        return false;
                                } else
                                    return false;
                            },
                            message: 'Debe ingresar la misma contrase&ntilde;a'

                        }

                    });
                });

            </script>



            <div style="height: 30%;width: 45%;padding-left: 25%;padding-top: 10%;padding-bottom: 10%">
                <div name="pg_recover">

                    <form method="post" id="fm_recover" action="">


                        <div id="panelm4" class="easyui-panel" title="Restablecimiento de contrase&ntilde;a"
                             collapsible="true" style="background:#fafafa;">
                            <table width="100%" border="0" cellspacing="3" cellpadding="3">
                                <tr>
                                    <td width="35%" align="left"><label for="id_password">* Nueva
                                            contrase&ntilde;a: </label></td>
                                    <td width="65%"><input type="password" name="id_password" id="id_password"
                                                           class="easyui-validatebox" size="30" validType="minLength[5]"
                                                           value="" required="required"/></td>

                                </tr>

                                <tr>
                                    <td width="35%" align="left"><label for="id_pass_comp">* Vuelve a introducir la
                                            contrase&ntilde;a: </label></td>
                                    <td width="65%"><input type="password" name="id_pass_comp" id="id_pass_comp"
                                                           class="easyui-validatebox" size="30"
                                                           validType="txt_valid_pass" value="" required="required"/>
                                    </td>
                                </tr>

                            </table>
                            <table align="center">
                                <tr>
                                    <td><a href="#" class="easyui-linkbutton" iconCls="icon-ok"
                                           onclick="recover(<?php echo '\'' . $codegenerate . '\',\'' . $code . '\',\'' . $activatekey . '\'' ?>);">Guardar
                                            Cambios</a></td>
                                    <td><a href="#" class="easyui-linkbutton" iconCls="icon-back" onclick="cancel();">Regresar</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>

        <?php
        }
    } else if ($service == 'recover2') {
        ?>
        <br>
        <br>
        <br>
        <form name="redirect4">

        <center>
        <?php
        $codegenerate = $_REQUEST['codegenerate'];
        $code = $_REQUEST['code'];
        $activatekey = $_REQUEST['activatekey'];
        $password = $_REQUEST['password'];
        if ($codegenerate == md5('getIdUser(' . $code . ')')) {


            echo $objservice->ActivateRecover($code, $activatekey, $password);
            ?>, ser&aacute; redireccionado en



            <br>
            <br>
            <form>
                <input type="text" size="3" name="redirect5">
            </form>
            segundos</b></font>
            </center>
            </form>
            <script>

                var targetURL2 = "../.././pages/index.php";
                var countdownfrom2 = 5;


                var currentsecond2 = document.redirect4.redirect5.value = countdownfrom2 + 1;
                function countredirect2() {
                    if (currentsecond2 != 1) {
                        currentsecond2 -= 1;
                        document.redirect4.redirect5.value = currentsecond2;
                    }
                    else {
                        window.location = targetURL2;
                        return;
                    }
                    setTimeout("countredirect2()", 1000);
                }

                countredirect2();
                //-->
            </script>
            <br>
            <br>
            <br><br>
            <br>
            <br>
            <br>
        <?php
        } else {


            echo "<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><center>Pagina no Encontrada</center><BR><BR><BR><BR><BR><BR><BR><BR>";
        }
    } else {


        echo "<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><center>Pagina no Encontrada</center><BR><BR><BR><BR><BR><BR><BR><BR>";
    }
?>

<div id="footer">
    <?php require_once("../.././pages/includes/footer.php"); ?>
</div>
</body>
</html>

