<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 26/08/14
 * Time: 07:23 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/sql/Transaction.php");
include_once("$dir_portal/fw/model/sql/AssignationParamHandler_SQL.php");

class AssignationParamHandler
{
    private $year;
    private $schoolYear;
    private $processType;
    private $schedule;
    private $assignationPrevious;
    private $assignationSelected;
    private $assignationDate;

    public function AssignationParamHandler($pYear, $pProcessType)
    {
        $this->year = $pYear;
        $this->processType = $pProcessType;
    }

    public function setYear($pYear)
    {
        $this->year = $pYear;
    }

    public function &getYear()
    {
        return $this->year;
    }

    public function setSchoolYear($pSchoolYear)
    {
        $this->schoolYear = $pSchoolYear;
    }

    public function &getSchoolYear()
    {
        return $this->schoolYear;
    }

    public function setProcessType($pProcessType)
    {
        $this->processType = $pProcessType;
    }

    public function &getProcessType()
    {
        return $this->processType;
    }

    public function setSchedule($pSchedule)
    {
        $this->schedule = $pSchedule;
    }

    public function &getSchedule()
    {
        return $this->schedule;
    }

    public function setAssignationPrev($pAssignation)
    {
        $this->assignationPrevious = $pAssignation;
    }

    public function &getAssignationPrev()
    {
        return $this->assignationPrevious;
    }

    public function setAssignationSelected($pAssignationSelected)
    {
        $this->assignationSelected = $pAssignationSelected;
    }

    public function &getAssignationSelected()
    {
        return $this->assignationSelected;
    }

    public function &getAssignationDate()
    {
        return $this->assignationDate;
    }

    public function setAssignationDate($pDate)
    {
        $this->assignationDate = $pDate;
    }

    public function getAssignationInfo($pStudent, $pCareer, $pCurriculum)
    {
        $objServiceQuery = new AssignationParamHandler_SQL();
        $objTransaction = new Transaction();
        $info = NUll;

        $result = $objServiceQuery->queryGetAssignationInfo($pStudent, $pCareer, $pCurriculum, $this->year, $this->schoolYear);

        if ($result != NULL AND count($result) > 0 AND $result[0]['result'] == OK) {
            $mTxtCarrera = '';
            switch ($result[0]['career']) {
                case 2:
                    $mTxtCarrera = "[02] MEDICINA VETERINARIA";
                    break;
                case 3:
                    $mTxtCarrera = "[03] ZOOTECNIA";
                    break;
            }

            $info[0] = Date("d-m-Y");
            $info[1] = Date("H:i");
            $info[2] = $result[0]['name'];
            $info[3] = $result[0]['surname'];
            $info[5] = $result[0]['student'];
            $info[6] = $result[0]['career'];
            $info[7] = $mTxtCarrera;
            $info[8] = $result[0]['assignationdate'];
            $info[9] = $objTransaction->Encriptar(1, $result[0]['student'] . "e" . $result[0]['transaction'] . "e" . $this->schoolYear . "e" . $this->year, 1);

            switch ($this->schoolYear) {
                case PRIMER_SEMESTRE:
                    $mensaje_periodo = " PRIMER SEMESTRE ";
                    break;
                case SEGUNDO_SEMESTRE:
                    $mensaje_periodo = " SEGUNDO SEMESTRE ";
                    break;
                case VACACIONES_DEL_PRIMER_SEMESTRE:
                    $mensaje_periodo = " ESCUELA DE VACACIONES DE JUNIO ";
                    break;
                case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                    $mensaje_periodo = " ESCUELA DE VACACIONES DE DICIEMBRE ";
                    break;
            }
            $info[4] = $mensaje_periodo . $this->year;
            return $info;
        }

        unset($objTransaction);
        unset($objServiceQuery);
        return FALSE;
    }

    public function getAssignationDetailInfo($pStudent, $pCareer, $pCurriculum)
    {
        $objServiceQuery = new AssignationParamHandler_SQL();
        $info = NUll;

        $result = $objServiceQuery->queryGetAssignationDetailInfo($pStudent, $pCareer, $pCurriculum, $this->year, $this->schoolYear);

        if ($result != NULL AND count($result) > 0) {
            $pos = 1;
            foreach ($result as $cursoAsignado) {
                $horario[$pos]["cur"] = trim($cursoAsignado['course']);
                $horario[$pos]["nom"] = trim($cursoAsignado['name']);
                $horario[$pos]["sec"] = trim($cursoAsignado['section']);
                $horario[$pos]["ini"] = trim($cursoAsignado['starttime']);
                $horario[$pos]["fin"] = trim($cursoAsignado['endtime']);
                $horario[$pos]["lu"] = $cursoAsignado['mon'] ? 'X' : '-';
                $horario[$pos]["ma"] = $cursoAsignado['tue'] ? 'X' : '-';
                $horario[$pos]["mi"] = $cursoAsignado['wed'] ? 'X' : '-';
                $horario[$pos]["ju"] = $cursoAsignado['thu'] ? 'X' : '-';
                $horario[$pos]["vi"] = $cursoAsignado['fri'] ? 'X' : '-';
                $horario[$pos]["sa"] = $cursoAsignado['sat'] ? 'X' : '-';
                $horario[$pos]["do"] = $cursoAsignado['sun'] ? 'X' : '-';
                $horario[$pos]["sal"] = trim($cursoAsignado['classroom']);
                $horario[$pos]["edi"] = trim($cursoAsignado['building']);
                $horario[$pos]["tip"] = trim($cursoAsignado['scheduletype']);
                $pos++;
            }
            return $horario;
        }

        unset($objServiceQuery);
        return false;
    }

    public function updateAssignationProcessCount($pStudent, $pCareer,$pGrupo,$pSitio,$mainCareer) {
        $objServiceQuery = new AssignationParamHandler_SQL();
        return $objServiceQuery->queryUpdateAssignationProcessCount($pStudent, $pCareer,$this->year,$this->schoolYear,$pGrupo,$pSitio,$mainCareer);
    }
}

?>