<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 21/09/14
 * Time: 10:07 AM
 */
require_once("../../path.inc.php");
require_once("$dir_portal/fw/model/Connection.php");

class AssignationParamHandler_SQL
{
    private $objConnection;

    public function AssignationParamHandler_SQL()
    {
        $this->objConnection = new Connection();
    }

    public function queryGetAssignationInfo($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo) {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_CHECKASSIGNATION_INFO","SELECT * FROM f_getassignation_info($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_CHECKASSIGNATION_INFO", array($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('result'=> $row['r_result'],
                        'student' => $row['r_idstudent'],
                        'career' => $row['r_idcareer'],
                        'transaction' => $row['r_transaction'],
                        'assignationdate' => $row['r_assignationdate'],
                        'name' => $row['r_name'],
                        'surname' => $row['r_surname']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryGetAssignationDetailInfo($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo) {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_CHECKASSIGNATIONDETAIL_INFO","SELECT * FROM f_getassignationdetail_info($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_CHECKASSIGNATIONDETAIL_INFO", array($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('index'=> $row['r_index'],
                        'course' => $row['r_course'],
                        'section' => $row['r_section'],
                        'name' => $row['r_name'],
                        'credits' => $row['r_credits'],
                        'starttime' => $row['r_starttime'],
                        'endtime' => $row['r_endtime'],
                        'mon' => $row['r_mon'],
                        'tue' => $row['r_tue'],
                        'wed' => $row['r_wed'],
                        'thu' => $row['r_thu'],
                        'fri' => $row['r_fri'],
                        'sat' => $row['r_sat'],
                        'sun' => $row['r_sun'],
                        'classroom' => $row['r_classroom'],
                        'building' => $row['r_building'],
                        'scheduletype' => $row['r_scheduletype']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryUpdateAssignationProcessCount($pEstudiante,$pCarrera,$pAnio,$pPeriodo,$pGrupo,$pSitio,$mainCareer) {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_UPDATEASSIGNATIONPROCESS_COUNT","SELECT * FROM f_update_accesssite($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint,$6::smallint,$7);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_UPDATEASSIGNATIONPROCESS_COUNT", array($pEstudiante,$pGrupo,$pSitio,$pCarrera,$pAnio,$pPeriodo,$mainCareer));
            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('result'=> $row['r_result']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }
}

?>