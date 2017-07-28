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
$tpl = new TemplatePower("SaveUpdatedProfileInfo.tpl");
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
$parametros[1] = $_GET['estadocivil'];
$parametros[2] = $_GET['fecha_nacimiento'];
$parametros[3] = $_GET['cedula'];
$parametros[4] = $_GET['cedula_depto'];
$parametros[5] = $_GET['cedula_munic'];
$parametros[6] = $_GET['dpi'];
$parametros[7] = $_GET['avenida'];
$parametros[8] = $_GET['num_casa'];
$parametros[9] = $_GET['apartamento'];
$parametros[10] = $_GET['zona'];
$parametros[11] = $_GET['colonia'];
$parametros[12] = $_GET['domicilio_depto'];
$parametros[13] = $_GET['domicilio_munic'];
$parametros[14] = $_GET['nacionalidad'];
$parametros[15] = $_GET['pasaporte'];
$parametros[16] = strcmp($_GET['pasaporte_fecha'],'')==0 ? '1900-01-01' : $_GET['pasaporte_fecha'];
$parametros[17] = $_GET['pasaporte_pais'];
$parametros[18] = $_GET['titulo_bach'];
$parametros[19] = $_GET['titulo_institucion'];
$parametros[20] = $_GET['correo_prin'];
$parametros[21] = $_GET['correo_alte'];
$parametros[22] = $_GET['telefono_prin'];
$parametros[23] = $_GET['telefono_alte'];
$parametros[24] = $_GET['telefono_papa'];
$parametros[25] = $_GET['telefono_mama'];
$parametros[26] = $_GET['telefono_emer'];
$parametros[27] = $_GET['nombre_mama'];
$parametros[28] = $_GET['nombre_papa'];
$parametros[29] = $_GET['nombre_emer'];
$parametros[30] = $_GET['celular_prin'];
$parametros[31] = $_GET['celular_alte'];

// Los datos antes de la actualización
$datosActuales = unserialize($_SESSION['INFO_USUARIO']);

$datosModificados = '';
$datosModificados = $datosModificados . (strcmp($datosActuales['sexo'],$_GET['genero_int']) == 0 ? 'sex' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['estadocivil'],$_GET['estadocivil_int']) == 0 ? ',maritalstatus' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['fechanac'],$parametros[2]) == 0 ? ',birthday' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['cedula'],$parametros[3]) == 0 ? ',idcedula' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['departamentocedula'],$parametros[4]) == 0 ? ',cedulatown' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['municipiocedula'],$parametros[5]) == 0 ? ',ceduladepartment' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['dpi'],$parametros[6]) == 0 ? ',dpi' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['avenida'],$parametros[7]) == 0 ? ',avenue_or_similar' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['numerocasa'],$parametros[8]) == 0 ? ',housenumber_or_similar' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['apartamento'],$parametros[9]) == 0 ? ',apartment_or_similar' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['zona'],$parametros[10]) == 0 ? ',zonenumber' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['colonia'],$parametros[11]) == 0 ? ',suburb_or_similar' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['departamento_dir'],$parametros[12]) == 0 ? ',address_department' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['municipio_dir'],$parametros[13]) == 0 ? ',address_town' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['nacionalidad'],$parametros[14]) == 0 ? ',idnationality' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['pasaporte'],$parametros[15]) == 0 ? ',idpassport' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['fechapasaporte'],$parametros[16]) == 0 ? ',passport_date' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['paispasaporte'],$parametros[17]) == 0 ? ',passport_country' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['carrera_nombre'],$parametros[18]) == 0 ? ',titlename' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['establecimiento'],$parametros[19]) == 0 ? ',institutionname' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['correo1'],$parametros[20]) == 0 ? ',email' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['email2'],$parametros[21]) == 0 ? ',email2' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['tel1'],$parametros[22]) == 0 ? ',phone' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['tel2'],$parametros[23]) == 0 ? ',phone2' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['telpadre'],$parametros[24]) == 0 ? ',fatherphone' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['telmadre'],$parametros[25]) == 0 ? ',motherphone' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['telresponsable'],$parametros[26]) == 0 ? ',emergencyphone' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['madre'],$parametros[27]) == 0 ? ',mother' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['padre'],$parametros[28]) == 0 ? ',father' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['responsable'],$parametros[29]) == 0 ? ',emergencyname' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['cel1'],$parametros[30]) == 0 ? ',cellphone' : '');
$datosModificados = $datosModificados . (strcmp($datosActuales['cel2'],$parametros[31]) == 0 ? ',cellphone2' : '');

if ($obj_pin->guardarActualizacionDatos($objuser->getId(),$parametros) == 0) {
    $tpl->assign("RESULTADO", "Hubo un problema al guardar los datos. Por favor intentelo nuevamente mas tarde");
    $tpl->assign("ENLACE", '<a href="UpdateProfileInfo.php">Haga click aqui para regresar a la página</a>');
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