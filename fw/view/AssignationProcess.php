<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 31/08/14
 * Time: 08:33 AM
 */

require_once("../../path.inc.php");
require_once("$dir_portal/fw/view/RegisterBLog.php");
require_once("$dir_portal/fw/controller/manager/AssignationManagement1.php");
require_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");
require_once("$dir_portal/fw/controller/validator/RegisterCourseAssignation.php");


define("PASO2_ASIGNACION_CURSOS",152);

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);

/*
 * Validación de existencia de sesión
 */
if (!$objuser) {
    header("Location: ../index.php");
}

if (isset($_POST['procesar_asignacion'])) {
    // Se verifica que tenga cursos seleccionados
    $vecAssignation = NULL;
    $index = 1;

    /*
     * Se obtiene la información de los cursos seleccionados por el usuario
     */
    for ($i = 1; $i <= $_POST['totalcur']; $i++) {
        if (isset($_POST['curso' . $i]) AND ((strcmp($_POST["curso" . $i], "") != 0) AND ($_POST["curso" . $i] != NULL))) {

            $lab = 0;
            if(isset($_POST['grupolab' . $i]) AND strcmp(trim($_POST['grupolab' . $i]),'-')!=0) {
                $lab=1;
            }

            $assignation = array('cindex' => $_POST["index" . $i],
                'course' => $_POST["curso" . $i],
                'section' => $_POST["grupo" . $i],
                'check' => $_POST["marca" . $i],
                'name' => $_POST["nombre" . $i],
                'index' => $_POST["indice" . $i],
                'credits' => $_POST["creditos" . $i],
                'requirement' => $_POST["prerrequisito" . $i],
                'check' => $_POST["marca" . $i],
                'lab' => $lab,
                'labgroup' => (isset($_POST['grupolab' . $i]) ? $_POST['grupolab' . $i] : ''),
                'assigned' => $_POST["marcaAsig" . $i]);
            $vecAssignation[$index++] = $assignation;
        }
    }

    $objAssignationParanHandler = unserialize($_SESSION['asignacion']);
    $objUser = unserialize($_SESSION['usuario']);

    $objAssignationParanHandler->setAssignationSelected($vecAssignation);

    $objAssignationMagement1 = new AssignationManagement1($objAssignationParanHandler, $objUser);

    $result = $objAssignationMagement1->validateProcess();

    // Guardamos el objeto que gestiona la información de la asignación luego de los cambios de las
    // validaciones
    $_SESSION['asignacion'] = serialize($objAssignationParanHandler);

    $next = FAIL;

    switch ($result) {
        case OK:
            $next = OK;
            break;
        case FAIL:
            $errorCod = 200; // Error general
            break;
        default;
    }

    if ($next == OK) {
        $objRegisterCourseAssignation = new RegisterCourseAssignation($objUser,$objAssignationParanHandler);
        $result = $objRegisterCourseAssignation->registrationProcess();

        if ( $result != FAIL) {
            // redireccionar la pagina de boleta de asignacion si la asignación fue exitosa
            header("Location:../../pages/menu/AS_AssignationResult.php");
            $objBlog = new RegisterBLog();
            $objBlog->DarSitio(PASO2_ASIGNACION_CURSOS);
            $objBlog->RegistroNavegacion($objuser->getId(),$objuser->getGroup(),0);
            unset($objBlog);
        }
    } else {
        $objBlog = new RegisterBLog();
        $objBlog->DarSitio(PASO2_ASIGNACION_CURSOS);
        $objBlog->RegistroNavegacion($objuser->getId(),$objuser->getGroup(),0);
        unset($objBlog);
        header("Location:../../pages/menu/AS_AssignationSelect.php?paso=2&error=".$errorCod);
    }
}

?>