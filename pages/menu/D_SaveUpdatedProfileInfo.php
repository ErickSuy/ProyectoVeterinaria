<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 31/01/2015
 * Time: 2:27 PM
 */

//
// ------------------------------------------------
// Guardar datos del formulario Información de Graduandos
// -------------------------------------------------

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/UserManager.php");

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

//Creacion de los objetos
$tpl = new TemplatePower("D_SaveUpdatedProfileInfo.tpl");
$obj_cad = new ManejoString();

//Asignacion de menús generales a la plantilla
$vector_modi[0] = $objuser->getId();
$vector_modi[1] = $objuser->getGroup();
$vector_modi[2] = $objuser->getCareer();

$obj_pin = new UserManager($vector_modi[1]);

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();  //TemplatePower se prepara

$parametros[0] = $_GET['genero'];
$parametros[1] = $_GET['fecha_nacimiento'];
$parametros[2] = $_GET['cedula'];
$parametros[3] = $_GET['cedula_depto'];
$parametros[4] = $_GET['cedula_munic'];
$parametros[5] = $_GET['dpi'];
$parametros[6] = $_GET['direccion'];
$parametros[7] = $_GET['domicilio_depto'];
$parametros[8] = $_GET['domicilio_munic'];
$parametros[9] = $_GET['nacionalidad'];
$parametros[10] = $_GET['correo_prin'];
$parametros[11] = $_GET['telefono_prin'];
$parametros[12] = $_GET['celular_prin'];

// Los datos antes de la actualización
$datosActuales = unserialize($_SESSION['INFO_USUARIO']);

$datosModificados = '';
$datosModificados = $datosModificados . (strcmp($datosActuales['sexo'],$_GET['genero_int']) == 0 ? 'sex' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['fechanac'],$parametros[1]) == 0 ? ',birthday' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['cedula'],$parametros[2]) == 0 ? ',idcedula' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['departamentocedula'],$parametros[3]) == 0 ? ',ceduladepartment' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['municipiocedula'],$parametros[4]) == 0 ? ',cedulatown' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['dpi'],$parametros[5]) == 0 ? ',dpi' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['direccion'],$parametros[6]) == 0 ? ',address' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['departamento_dir'],$parametros[7]) == 0 ? ',address_department' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['municipio_dir'],$parametros[8]) == 0 ? ',address_town' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['nacionalidad'],$parametros[9]) == 0 ? ',idnationality' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['correo1'],$parametros[10]) == 0 ? ',email' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['tel1'],$parametros[11]) == 0 ? ',phone' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['cel1'],$parametros[12]) == 0 ? ',cellphone' : '');

if ($obj_pin->guardarActualizacionDatosDocente($objuser->getId(),$parametros) == 0) {
    $tpl->assign("RESULTADO", "Hubo un problema al guardar los datos. Por favor intentelo nuevamente mas tarde");
    $tpl->assign("ENLACE", '<a href="D_UpdateProfileInfo.php">Haga click aqui para regresar a la página</a>');
} else {
    $obj_pin->registrarLogActualizacionDatos('WEB',$vector_modi[0],$vector_modi[1],$vector_modi[0],$datosModificados);
    $tpl->assign("RESULTADO", "Datos Guardados exitosamente.");
    $tpl->assign("ENLACE", '<script language="javascript"> setTimeout ("redireccionar()",1500);</script>');
}

//Imprime el resultado
$tpl->printToScreen();
unset($tpl);
unset($obj_pin);
unset($obj_cad);

?>