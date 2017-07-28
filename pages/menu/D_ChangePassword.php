<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 18/04/2015
 * Time: 2:54 PM
 */
include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/UserManager.php");

session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$obj_cad   = new ManejoString();
$objUserManager = new UserManager(null);

$tpl = new TemplatePower("D_ChangePassword.tpl");
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();  //TemplatePower hace algo

//Asignacion de menús generales a la plantilla
$vector_modi[0] = $objuser->getId();
$vector_modi[1] = $objuser->getGroup();  //Obtiene el grupo

$objUserManager->DatosUsuario($vector_modi);  //Obtiene informacion de la Bdd

$contrasenia1 = $objUserManager->mContrasenia;
$devuelve  = $contrasenia1;

//Boton de Actualizar Correo y Contraseña
if ( isset($_POST['btnModPIN']) ) {
    $actualizar2 = 0;
    $actualizar3 = 0;
    $longN = 0;
    $longC = 0;
    $_POST['txtCorreo'] = strtolower($_POST['txtCorreo']);  //Todo a minusculas
    $vector_modi[7] = trim($_POST['txtCorreo']);			//Correo electronico
    $correo_temporal = $vector_modi[7];
    $vector_modi[8] = trim($_POST['txtPalabraClave']);   //Palabra clave
    $vector_modi[8] = strtoupper($vector_modi[8]);
    $long_palabraclave = strlen($vector_modi[8]);
    $long_correo = strlen($vector_modi[7]);

    if ($_POST['Dato10']==1)  {
        if (($long_palabraclave!=0)&&(strcmp($vector_modi[8],"_")!=0)&&($_POST['Dato12']==1))  {
            $actualizar2 = 1;     //Palabra Clave
        }
        else {
            $actualizar2 = 0;
            $actualizar3 = 1;
            $tpl->assign("Mensaje","<div class=\"alert alert-danger\"><h4><i class=\"fa fa-times-circle fa-lg\"></i> ERROR</h4>El valor ingresado en \"Palabra Clave\" no es correcto</div>");  //Debe de ingresar la Palabra Clave
        }
    }

    if (($_POST['Dato7']==1)&&($_POST['Dato8']==1)&&($_POST['Dato9']==1)) {
        $actualizar2 = 1;
    }

    $clave_pantalla = $_POST['txtContrasenia'];
    if ($_POST['Dato7']==1)  {      //Modificacion de Contraseña
        if ( strlen($clave_pantalla) != 0 )  {
            if ((trim(md5($clave_pantalla))) == (trim($devuelve))) {
                $vector_modi[6] = trim($_POST['txtContraseniaN']);
                $clave_temporal = $vector_modi[6];
                $longN = strlen($vector_modi[6]);
                $longC = strlen($_POST['txtContraseniaC']);
                if (($longN != 0)&&($longC != 0)&&($longN == $longC))  {
                    if (strcmp(trim($clave_pantalla),trim($clave_temporal)) !=0 ) {
                        $vector_modi[6] = md5($clave_temporal);
                        $long = strlen($vector_modi[6]);
                        $actualizar2 = 1;
                    }
                    else { $actualizar2 = 0; $tpl->assign("Mensaje","<div class=\"alert alert-danger\"><h4><i class=\"fa fa-times-circle fa-lg\"></i> ERROR</h4>El valor ingresado en \"Contraseña Nueva\" debe ser un valor distinto a la contraseña actual</div>"); } //Ingresó la misma Contraseña
                }
                else { $actualizar2 = 0; $tpl->assign("Mensaje","<div class=\"alert alert-danger\"><h4><i class=\"fa fa-times-circle fa-lg\"></i> ERROR</h4>El valor ingresado en \"Repetir la Contraseña\" no es correcto</div>"); } //Debe ingresar datos de la Nueva Contraseña o la Confirmación no coincide
            }
            else { $actualizar2 = 0; $tpl->assign("Mensaje","<div class=\"alert alert-danger\"><h4><i class=\"fa fa-times-circle fa-lg\"></i> ERROR</h4>El valor ingresado en \"Contraseña Actual\" no es correcto</div>");} //La Clave que ingresó no es la Actual
        }
        else { $actualizar2 = 0; $tpl->assign("Mensaje","<div class=\"alert alert-danger\"><h4><i class=\"fa fa-times-circle fa-lg\"></i> ERROR</h4>Para poder cambiar la contraseña debe ingresar el valor correspondiente en \"Contraseña Actual\"</div>"); } //Debe ingresar la Clave Actual
    }  //Fin de if para modificar Contraseña
    else { $vector_modi[6] = $contrasenia1; }

    if ( $actualizar2 == 1 ) {
        if ( $actualizar3 == 0 ) {
            if ($objUserManager->ModificaUsuario($vector_modi) == 1) {
                $tpl->assign("Mensaje","<div class=\"alert alert-success\"><h4><i class=\"fa fa-info-circle fa-lg\"></i> INFORMACIÓN</h4>La contraseña fue modificada correctamente.</div>");  //Datos y Clave Modificados. Se le enviaron los cambios a su Correo Electrónico
            } //De la funcion ModificarUsuario
        } //Control de error en otros datos
    }
    unset($_POST['btnModPIN']);
}  //Fin de if para modificar clave o correo
$tpl->assign("PalabraClave",$objuser->getId());
$tpl->assign("aData","{service:'getcaptcha'}");

//Imprime el resultado
$tpl->printToScreen();

unset($tpl,$obj_cad)
?>