<?php
    /**
     * Created by PhpStorm.
     * User: emsaban
     * Date: 21/08/14
     * Time: 02:05 PM
     */
    require_once("../../path.inc.php");
    require_once("$dir_portal/fw/controller/ControlAssignationRequeriments1.php");
    require_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");
    require_once("$dir_biblio/biblio/SysConstant.php");

    session_start();
    $service = $_REQUEST['service'];

    function getActiveProcessAssignationSem()
    {
        $objuser = unserialize($_SESSION['usuario']);
        $objControlService = new ControlAssignationRequeriments1();
        $objAssignationMngmt = new AssignationParamHandler(date("Y"),ASIGNACION_DE_SEMESTRE);

        $result = $objControlService->checkProcessAssignationSem(date("Y"),PRIMER_SEMESTRE,SEGUNDO_SEMESTRE,ASIGNACION_DE_SEMESTRE,date('Y-m-d H:i:s'),$objuser,$objAssignationMngmt);
        if($result[0]['result']==OK){
            $objAssignationMngmt->setSchedule($objControlService->getScheduleInformation($objAssignationMngmt->getYear(),$objAssignationMngmt->getSchoolYear(),$objuser->getCurriculum(),$objuser->getCareer(),CLASE_MAGISTRAL));

            $_SESSION['asignacion'] = serialize($objAssignationMngmt);
            $_SESSION['usuario'] = serialize($objuser);
        }

        echo($result[0]['msg']);
    }

    switch ($service) {
        case SRV_VALIDACIONES_ASIG1SEM:
            getActiveProcessAssignationSem();
            break;
    }

?>