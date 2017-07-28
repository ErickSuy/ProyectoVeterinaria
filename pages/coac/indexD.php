<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="es"> <!--<![endif]-->
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="es" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>

    <title>::Facultad de Medicina Veterinaria y Zootecnia - USAC/Admin. Académica/DOCENTE</title>
    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- BEGIN FOUNDATIONS STYLES -->
    <link rel="stylesheet" href="stylesheets/foundation.min.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <!-- END FOUNDATIONS STYLES -->

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="assets/css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN FOUNDATIONS JS -->
    <script src="javascripts/modernizr.foundation.js"></script>
    <!-- END FOUNDATIONS JS -->

    <script type="text/javascript" src="../../libraries/GeneralFunctions.js"></script>
    <script type="text/javascript" src="../../libraries/jquery-1.4.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/themes/default/easyui.css">
    <script type="text/javascript" src="../../libraries/jquery2.easyui.min.js"></script>
    <script type="text/javascript" src="../../libraries/locale/easyui-lang-es.js"></script>

    <!-- Google fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Playfair+Display:400italic'
          rel='stylesheet' type='text/css'/>

    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <style type="text/css">
        .fmvzlogo {
            width: 106px;
            height: 106px;
            margin: 0 auto 10px;
            display: block;
        }

        .dropdown p {
            display: inline-block !important;
            font-weight: bold !important;
        }

        .dropdown select {

            border: 0 !important; /*Removes border*/
            -webkit-appearance: none !important; /*Removes default chrome and safari style*/
            -moz-appearance: none !important; /* Removes Default Firefox style*/
            background: url('dropdown_arrow.png') no-repeat !important; /*Adds background-image*/
            background-position: 82px 7px !important; /*Position of the background-image*/
            width: 100px; /*Width of select dropdown to give space for arrow image*/
            text-indent: 0.01px; /* Removes default arrow from firefox*/
            text-overflow: ""; /*Removes default arrow from firefox*/

            /*My custom style for fonts*/

            color: #1455a2;
        }
    </style>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<div id="wrapper">
    <nav class="top-bar" id="header-wrap" data-topbar role="navigation">
        <ul class="title-area">
            <li class="name">
                <h1><a href="http://www.fmvz.usac.edu.gt">FMVZ</a></h1>
            </li>
            <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
            <li class="toggle-topbar menu-icon"><a href="http://www.fmvz.usac.edu.gt"><span>Menu</span></a></li>
        </ul>
    </nav>
    <div id="corte">
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="form-vertical login-form" action="../../fw/view/User.php?service=3" method="post">
                <h3 class="form-title">DOCENTE</h3>

                <div class="alert alert-error hide">
                    <button class="close" data-dismiss="alert"></button>
                    <span>AMBOS DATOS SON OBLIGATORIOS.</span>
                </div>
                <div class="control-group">
                    <label class="control-label visible-ie8 visible-ie9">Reg. Personal</label>

                    <div class="controls">
                        <div class="input-icon leftt">
                            <i class="icon-user"></i>
                            <input class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Reg. Personal"
                                   name="username" id="username"
                                   value="" maxlength="9"/>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label visible-ie8 visible-ie9">Contraseña</label>

                    <div class="controls">
                        <div class="input-icon leftt">
                            <i class="icon-lock"></i>
                            <input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Contraseña"
                                   name="password" id="password"
                                   value="" maxlength="16"/>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label visible-ie8 visible-ie9">Carrera</label>

                    <div class="controls">
                        <div class="input-icon ">
                            <input type="hidden" value="2" name="group" id="group"/>
                            <?php
                            if (isset($_GET['fail'])) {
                                echo '<span role="alert">';
                                echo $_GET['fail'];
                                echo '</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn green pull-right" name="btnAcces" id="btnAcces">
                        Login <i class="m-icon-swapright m-icon-white"></i>
                    </button>
                </div>
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="form-vertical forget-form" action="../coac/index.html" method="post">
                <h3>Recuperar contraseña</h3>

                <p>Complete la siguiente información, para recuperar su contraseña.</p>

                <div class="control-group">
                    <div class="controls">
                        <div class="input-icon left">
                            <i class="icon-envelope"></i>
                            <input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" autocomplete="off"
                                   name="email"/>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn">
                        <i class="m-icon-swapleft"></i> Back
                    </button>
                    <button type="submit" class="btn blue pull-right">
                        Submit <i class="m-icon-swapright m-icon-white"></i>
                    </button>
                </div>

            </form>
            <!-- END FORGOT PASSWORD FORM -->
        </div>
        <!-- END LOGIN -->
    </div>
</div>

<div id="footer">
    <div class="row" style="width: 100%;">
        <div class="twelve columns">
            <div id="credito">
                TRICENTENARIA UNIVERSIDAD DE SAN CARLOS DE GUATEMALA, FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA, CIUDAD
                DE GUATEMALA CAMPUS CENTRAL ZONA 12, EDIFICIO M-6. TEL 24188300. Sugerencias, Comentarios: <a
                    href="mailto:avirtual.fmvz@usac.edu.gt" class="mailto-info-fmvz">avirtual.fmvz@usac.edu.gt</a>
            </div>
        </div>
    </div>
</div>

<!-- Included JS Files (Compressed) -->
<script src="javascripts/jquery.js" type="text/javascript">
</script>
<script src="javascripts/foundation.min.js" type="text/javascript">
</script>
<script>
    $(document).foundation();
</script>
<!-- Initialize JS Plugins -->
<script src="javascripts/app.js" type="text/javascript">
</script>
<!-- END COPYRIGHT -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<script src="assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"
        type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="assets/plugins/excanvas.min.js"></script>
<script src="assets/plugins/respond.min.js"></script>
<![endif]-->
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/scripts/app.js" type="text/javascript"></script>
<script src="assets/scripts/login-soft-D.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function () {
        App.init();
        Login.init();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>