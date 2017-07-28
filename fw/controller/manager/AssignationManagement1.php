<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 29/08/14
 * Time: 02:41 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/sql/AssignationManagement1_SQL.php");
include_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");
include_once("$dir_portal/fw/controller/validator/ValidateCourseSchedule.php");
include_once("$dir_portal/fw/controller/validator/ValidateCourseRequirement.php");

define("MAXIMOREPITENCIA", 3);

class AssignationManagement1
{
    private $objAssignationParamHandler;
    private $objServiceQuery;
    private $objUser;

    public function AssignationManagement1(&$pAssignarionParamHandler, $pUser)
    {
        $this->objServiceQuery = new AssignationManagement1_SQL();
        $this->objAssignationParamHandler = $pAssignarionParamHandler;
        $this->objUser = $pUser;
    }

    public function validateProcess()
    {
        $this->clearCourseRemarks($this->objAssignationParamHandler->getAssignationSelected());
        $this->getCourseRequirement($this->objAssignationParamHandler->getAssignationSelected());

        // Verificacion traslape de cursos
        $courseScheduleValidator = new ValidateCourseSchedule($this->objUser, $this->objAssignationParamHandler);
        $courseScheduleValidator->setAssignation($this->objAssignationParamHandler->getAssignationSelected());

        $result1 = $courseScheduleValidator->validateScheduleProcess();

        $this->objAssignationParamHandler->setAssignationSelected($courseScheduleValidator->getAssignation());

        //if ($result1 == OK) {
            // Verificacion de prerrequisitos del curso
            $courseRequirementValidator = new ValidateCourseRequirement($this->objUser);
            $courseRequirementValidator->setAssignation($this->objAssignationParamHandler->getAssignationSelected());

            $result2 = $courseRequirementValidator->validateRequirement();
            $this->objAssignationParamHandler->setAssignationSelected($courseRequirementValidator->getAssignation());

        //}


        $result3 = $this->validateAssignationCountt(); // Corregir la consulta para que tome las repitencias a partir del
        // segundo semestre 2006
        $result4 = $this->validateSimultaneousCourseAssignation();
        $result5 = $this->validateLanguajeCourseLevel();
        $result6 = $this->validateAssignationLimit();
        if ($result1 != OK OR $result2 != OK OR $result3 != OK OR $result4 != OK OR $result5 != OK OR $result6 != OK) {
            return FAIL;
        } else {
            return OK;
        }
    }

    private function getCourseRequirement(&$pAssignation)
    {
        foreach ($pAssignation as $course) {
            if ((strcmp($course['course'], "") != 0) AND ($course['course'] != NULL)) {
                $resul = $this->objServiceQuery->queryCourseRequirement($course['cindex'], $course['course'], $this->objUser->getCurriculum(), $this->objUser->getCareer());
                if ($resul[0]['result'] == OK) {
                    $course['requirement'] = $resul[0]['requirement'];
                }
            }
        }
    }

    private function clearCourseRemarks(&$pAssignation)
    {
        foreach ($pAssignation as $course) {
            if ((strcmp($course['course'], "") != 0) AND ($course['course'] != NULL)) {
                $course['remark'] = array();
            }
        }
    }

    private function validateAssignationCountt()
    {
        $resultado = OK;

        $mCursosVal = $this->objAssignationParamHandler->getAssignationSelected();
        $totalCursos = count($this->objAssignationParamHandler->getAssignationSelected());

        for ($intControl = 1; $intControl <= $totalCursos; $intControl++) {
            if (strcmp($mCursosVal[$intControl]['course'], "") != 0) {

                $resul = $this->objServiceQuery->queryCourseAssignationCountt(
                    $mCursosVal[$intControl]['cindex'],
                    $mCursosVal[$intControl]['course'],
                    $this->objUser->getCurriculum(),
                    $this->objUser->getId(),
                    $this->objAssignationParamHandler->getYear(),
                    $this->objAssignationParamHandler->getSchoolYear());

                if ((int)$resul[0]['count'] >= MAXIMOREPITENCIA) { 
                    $mCursosVal[$intControl]['remark'][REPITENCIA_TOPADA] = $resul[0]['count'];
                    $resultado = FAIL;
                }
            }
        }

        $this->objAssignationParamHandler->setAssignationSelected($mCursosVal);
        return $resultado;
    }

    private function validateAssignationLimit()
    {
        $resultado = OK;
//queryScheduleCourseCount($pIndex,$pCurso,$pSeccion,$pLab,$pCarrera,$pAnio,$pPeriodo)
        $mCursosVal = $this->objAssignationParamHandler->getAssignationSelected();
        $totalCursos = count($this->objAssignationParamHandler->getAssignationSelected());

        for ($intControl = 1; $intControl <= $totalCursos; $intControl++) {
            if (strcmp($mCursosVal[$intControl]['course'], "") != 0) {

                $resul = $this->objServiceQuery->queryScheduleCourseCount(
                    $mCursosVal[$intControl]['cindex'],
                    $mCursosVal[$intControl]['course'],
                    $mCursosVal[$intControl]['section'],
                    $mCursosVal[$intControl]['labgroup'],
                    $this->objUser->getCareer(),
                    $this->objAssignationParamHandler->getYear(),
                    $this->objAssignationParamHandler->getSchoolYear());

                if ((int)$resul[0]['cupo'] == FAIL) {
                    $mCursosVal[$intControl]['remark'][CUPO_COMPLETO] = $resul[0]['cupo'];
                    $resultado = FAIL;
                }
            }
        }

        $this->objAssignationParamHandler->setAssignationSelected($mCursosVal);
        return $resultado;
    }

    function validateSimultaneousCourseAssignation()
    {
        $result1 = $this->objServiceQuery->querySimultaneousCareer($this->objUser->getId(), $this->objUser->getCareer(), $this->objAssignationParamHandler->getYear());

        $mCursosVal = $this->objAssignationParamHandler->getAssignationSelected();
        $totalCursos = count($this->objAssignationParamHandler->getAssignationSelected());

        $resultado = OK;

        if ($result1[0]['result'] == OK) { // si lleva carrera simultanea

            $result2 = $this->objServiceQuery->queryCheckAssignation(
                $this->objUser->getId(),
                $result1[0]['career'],
                $result1[0]['curriculum'],
                $this->objAssignationParamHandler->getYear(),
                $this->objAssignationParamHandler->getSchoolYear());

            if (count($result2) > 0) { // si tiene asignados cursos en carrera simultanea
                // buscar los cursos de la carrera simultanea en la asignacion actual
                $numeroCursosSimultaneos = count($result2);
                $i = 0;
                while ($numeroCursosSimultaneos > 0) {
                    $cursoSimultanea = $result2[$i]['course'];
                    for ($pos = 1; $pos <= $totalCursos; $pos++) {
                        if (strcmp($mCursosVal[$pos]['course'], $result2[$i]['course']) == 0 AND strcmp($mCursosVal[$pos]['cindex'], $result2[$i]['index']) == 0) {
                            $mCursosVal[$pos]['remark'][CURSO_ASIGNADO_CARRERA_SIMULTANEA] = 1;
                            $resultado = FAIL;
                        } // del if de comparacion de cursos
                    } // del for
                    $numeroCursosSimultaneos--;
                    $i++;
                }
            } // del if
            else {
                return $resultado;
            } // no se asigno cursos en simultanea
        } else {
            return $resultado;
        } // no tiene carrea simultanea

        $this->objAssignationParamHandler->setAssignationSelected($mCursosVal);

        return $resultado;
    }

    function validateLanguajeCourseLevel() {
        $modulares = '600,605,615,610,620,621,622,623,625,606,634';

        $resultado = OK;

        $mCursosVal = $this->objAssignationParamHandler->getAssignationSelected();
        $totalCursos = count($this->objAssignationParamHandler->getAssignationSelected());

        for ($intControl = 1; $intControl <= $totalCursos; $intControl++) {
            if (strcmp($mCursosVal[$intControl]['course'], "") != 0) {
                if (substr_count($modulares,$mCursosVal[$intControl]['course'])>0) {
                    $result1 = $this->objServiceQuery->queryLanguageCourseLevele($this->objUser->getId());
                    if (count($result1) == 0) {
                        $mCursosVal[$intControl]['remark'][CERTIFICADO_CALUSAC] = 0;
                        $resultado = FAIL;
                    }
                }
            }
        }

        $this->objAssignationParamHandler->setAssignationSelected($mCursosVal);
        return $resultado;
    }
}