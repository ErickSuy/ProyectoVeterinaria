<?php
    require_once("../controller/ControlService.php");

    $service = $_REQUEST['service'];

    $objservice = new ControlService($service);


    if ($service == 'getcaptcha') {
        echo $objservice->getCaptcha();
    } else if ($service == 'getschool') {
        echo $objservice->getSchool();
    } else if ($service == 'logout') {
        session_start();
        $objuser = unserialize($_SESSION['usuario']);
        $objservice->closeSession($objuser);
        unset($_SESSION);
        session_destroy();
        //header("Location: ../../pages/index.php");
    } else if ($service == 'getinstitution') {
        echo $objservice->getInstitution();
    } else if ($service == 'getstatecourse') {

        echo $objservice->getCourseStates();
    } else if ($service == 'getrol') {
        echo $objservice->getRol();
    } else if ($service == 'getsalones') {
        echo $objservice->getClassRooms();
    } else if ($service == 'getprofesores') {
        echo $objservice->getProfesores();
    }

?>


