<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 4/10/14
 * Time: 06:35 PM
 */
?>

<?php
require_once("../.././fw/model/mapping/TbUser.php");
require_once("../.././fw/model/mapping/TbPrivilege.php");
require_once("../../libraries/biblio/SysConstant.php");
session_start();


$objuser = unserialize($_SESSION['usuario']);
if ($objuser) {
    $privileges = $objuser->getPrivileges();
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Expires" CONTENT="0">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-store">
        <meta http-equiv="Cache-Control" content="must-revalidate">
        <meta http-equiv="Cache-Control" content="post-check=0">
        <meta http-equiv="Cache-Control" content="pre-check=0">
        <?php
        include("top_page.php");
        ?>
        <script language='javascript'>
            // valores preestablecidos para el ingreso manual
            MinLab = 61;
            MaxLab = 100;
            MinZona = 36;
            MinCong = 45;
            MaxZona = 75;
            MaxExamen = 25;
            continuar = false;
            indice = 0;  // indica la posicion actual de la celda que tiene el foco
            tipo = 1;  // indica el tipo de celda que es 1=lab,2=zona y 3=exfinal
        </script>
        <script type="text/javascript" language="JavaScript" src="../../libraries/ViewManualData.js"></script>
        <script language="javascript">
            Periodo = <?php echo($_SESSION["sActaManual"]->mPeriodo); ?>;
            Laboratorio = <?php echo($_SESSION["sActaManual"]->mLaboratorio); ?>;
        </script>
        <script language='javascript'>
            function salida() {
                alert("Se esta cerrando!!!");
                window.open('../LogOut.php');
            }
        </script>
    </head>
    <body>
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
                                                <td align="left" width="90%"><h1>Carga de Notas de Cursos</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <form name='Bloque' action="grabarbloque.php" method="post">
                                            <span id="tbbloquedatos"/>
                                        </form>
                                    </td>
                                </tr>
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