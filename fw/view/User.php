<?php
include("../../path.inc.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_biblio/biblio/SysConstant.php");

include_once("$dir_portal/fw/controller/ControlUser.php");
include_once("$dir_portal/fw/controller/ControlService.php");
include_once("$dir_portal/fw/view/validator/ValidatorCaptcha.php");
include_once("$dir_portal/fw/view/validator/ValidatorMail.php");
include_once("$dir_portal/fw/view/validator/ValidatorInteger.php");
include_once("$dir_portal/fw/view/validator/ValidatorDate.php");
include_once("$dir_portal/fw/view/validator/ValidatorDefault.php");
include_once("$dir_portal/fw/view/validator/ValidatorPassword.php");
include_once("$dir_portal/fw/view/validator/FuncionesDeInicio.php");
include_once("$dir_portal/fw/view/validator/ManejoCrypt.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");

session_start();
$_SESSION["strRan"] = 0;
$service = $_REQUEST['service'];

if ($service == SRV_LOGUEO_PERFIL) {
    $mail = 'testfmvz@usac.edu.gt'; // Texto aleatorio colocada para no moficar el flujo
    $user = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $group = $_REQUEST['group'];
    $career = $_REQUEST['career'];

    $valmail = new ValidatorMail('Correo', $mail, TRUE);
    $valpassword = new ValidatorPassword('contrasena', $password, TRUE);

    switch ($group) {
        case GRUPO_DOCENTE:
            $site = SITIO_DOCENTE; //sitio docentes
            $career = 0;
            break;
        case GRUPO_AUXILIAR:
            $site = SITIO_AUXILIAR; //sitio auxiliares
            $career = 0;
            break;
        case GRUPO_ESTUDIANTE:
            $site = SITIO_ESTUDIANTE; // sitio estudiantes
            break;
        case GRUPO_CONTROL_ACADEMICO:
            $site = SITIO_DOCENTE; // sitio estudiantes
            break;
    }
    if ($valmail->verify()) {
        //$pin = funDeCript($valpassword->getField()); // Primera desencriptación
        //$objencrypt = new Encripta();
        //$clave = $objencrypt->Transforma($pin);

        //$objstring = new ManejoString();
        //$clave = $objstring->cambiaString($clave);

        //unset($objstring);
        //unset($objencrypt);

        $objcontroller = new ControlUser($valmail->getField(), md5($valpassword->getField()), $user, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $career, NULL, NULL, $group, $site, session_id());

        $result = $objcontroller->validate();
        if ($result == 'OK') {
            //   Se realiza una conexion con Servidor de Base de datos
            $_SESSION["sConexion"] = NEW DB_Connection();
            $_SESSION["sConexion"]->connect();

            switch ($site) {
                case SITIO_DOCENTE:
                    session_start();
                    $_SESSION['usuario'] = serialize($objcontroller->getUser());
                    //header("Location: ../../pages/menu/ViewProfileInfo.php");
                    header("Location: ../../pages/docentes/docentes.htm");
                    exit;
                    break;
                case 1:
                    session_start();
                    $_SESSION['usuario'] = serialize($objcontroller->getUser());
                    //header("Location: ../../pages/menu/ViewProfileInfo.php");
                    header("Location: ../../pages/estudiantes/estudiantes.htm");
                    exit;
                    break;
            }

        } else {
            $msg = '';
            switch ($result) {
                case strcmp(substr($result, strpos($result, '101', 0), 3), '101') == 0:
                    $msg = "El usuario no pertenece a la carrera especificada";
                    break;
                case strcmp(substr($result, strpos($result, '102', 0), 3), '102') == 0:
                    $msg = "El usuario no existe o no pertenece al grupo especificado";
                    break;
                case strcmp(substr($result, strpos($result, '102', 0), 3), '103') == 0:
                    $msg = "El usuario esta suspendido";
                    break;
                case strcmp(substr($result, strpos($result, '104', 0), 3), '104') == 0:
                    $msg = "El pin es incorrecto";
                    break;
            }

            switch ($group) {
                case GRUPO_DOCENTE:
                    $site = "../../pages/coac/indexD.php"; //login docentes
                    break;
                case GRUPO_ESTUDIANTE:
                    $site = "../../pages/coac/indexE.php"; //login estudiantes
                    break;
            }

            header("Location: ". $site . "?fail=" . $msg);
            exit;
        }
    } else {
        header("Location: ../../pages/index.php");
    }
}

/* Obtiene datos de usuario para modificar */
if ($service == 102) {
    $iduser = $_REQUEST['id'];
    $objcontroller = new ControlUser(NULL, NULL, $iduser, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
    if ($objcontroller->getUserById($iduser)) {
        session_start();
        $_SESSION['userEdit'] = serialize($objcontroller->getUserById($iduser));
        header("Location: ../../pages/menu/EpditUser.php");
        exit;
    }
}

?>

