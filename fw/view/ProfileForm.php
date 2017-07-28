<?php
    /**
     * Created by PhpStorm.
     * User: EdwinMac-donall
     * Date: 17/08/14
     * Time: 12:31 PM
     */

    require_once("../../libraries/biblio/SysConstant.php");
    require_once("../controller/ControlProfileForm.php");

    $service = $_REQUEST['service'];


    function getDepartmentsCode()
    {
        $objControlService = new ControlProfileForm(NULL);

        echo($objControlService->getDepartmentsList());
    }

    switch ($service) {
        case SRV_OBTENER_DEPARTAMENTOS:
            getDepartmentsCode();
            break;
    }
?> 