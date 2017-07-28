<?php
require_once("../../path.inc.php");
require_once("$dir_portal/fw/model/Connection.php");

class AssignationManagement1_SQL
{
    private $objConnection;

    public function AssignationManagement1_SQL()
    {
        $this->objConnection = new Connection();
    }

    public function queryCourseRequirement($pIndex,$pCurso,$pPensum,$pCarrera){
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_COURSEREQUIREMENT","SELECT * FROM f_courserequirement($1::smallint,$2::smallint,$3::smallint,$4::smallint);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_COURSEREQUIREMENT", array($pIndex,$pCurso,$pPensum,$pCarrera));
            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('result'=> $row['r_result'],
                        'requirement' => $row['r_requirement']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryCourseAssignationCountt($pIndex,$pCurso,$pPensum,$pUsuario,$pAnio,$pPeriodo){
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_COURSEASSIGNATIONCOUNT","SELECT * FROM f_getcourse_assignationcount_semester($1::smallint,$2::smallint,$3::smallint,$4::numeric,$5::smallint,$6::smallint );")){
            $result = $this->objConnection->ejecuteStatement("SELECT_COURSEASSIGNATIONCOUNT", array($pIndex,$pCurso,$pPensum,$pUsuario,$pAnio,$pPeriodo));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('index' => $row['r_index'],
                        'course' => $row['r_course'],
                        'count'=> $row['r_count']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryScheduleCourseCount($pIndex,$pCurso,$pSeccion,$pLab,$pCarrera,$pAnio,$pPeriodo){
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_COURSESCHEDULECOUNT","SELECT f_check_course_limit as cupo FROM f_check_course_limit($1,$2,$3,$4,$5,$6,$7 );")){
            $result = $this->objConnection->ejecuteStatement("SELECT_COURSESCHEDULECOUNT", array($pIndex,$pCurso,$pSeccion,$pLab,$pCarrera,$pAnio,$pPeriodo));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('cupo' => $row['cupo']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function querySimultaneousCareer($pUsuario,$pAnioo,$pAnio){
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_SIMULTANEOUSCAREER","SELECT * FROM f_get_simultaneouscareer($1::numeric,$2::smallint,$3::smallint );")){
            $result = $this->objConnection->ejecuteStatement("SELECT_SIMULTANEOUSCAREER", array($pUsuario,$pAnioo,$pAnio));
            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('result' => $row['r_result'],
                        'career' => $row['r_career'],
                        'curriculum' => $row['r_curriculum']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryCheckAssignation($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo) {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_CHECKASSIGNATION","SELECT * FROM f_getassignation($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_CHECKASSIGNATION", array($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('index'=> $row['r_index'],
                        'course' => $row['r_course'],
                        'section' => $row['r_section'],
                        'name' => $row['r_name'],
                        'credits' => $row['r_credits'],
                        'requirement' => $row['r_requirement'],
                        'assigned' => $row['r_assigned'],
                        'check' => $row['r_check'],);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryLanguageCourseLevele($pEstudiante) {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_LANGUAJE","SELECT * FROM tbapprovedlanguajecourse where idstudent=$1::numeric;")){
            $result = $this->objConnection->ejecuteStatement("SELECT_LANGUAJE", array($pEstudiante));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('idstudent'=> $row['idstudent'],
                        'level' => $row['level']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }
}

?>