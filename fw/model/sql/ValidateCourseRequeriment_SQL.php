<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 2/09/14
 * Time: 08:31 AM
 */
require_once("../../path.inc.php");
require_once("$dir_portal/fw/model/Connection.php");

class ValidateCourseRequeriment_SQL
{
    private $objConnection;

    public function ValidateCourseRequeriment_SQL()
    {
        $this->objConnection = new Connection();
    }

    public function queryValidateCourseApproval($pUsuario,$pCarrera,$pIndex,$pCurso,$pPensum,$pFechaValida){
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_VALIDATEAPPROVEDCOURSE","SELECT * FROM f_validatecourseapproval($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint,$6);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_VALIDATEAPPROVEDCOURSE", array($pUsuario,$pCarrera,$pIndex,$pCurso,$pPensum,$pFechaValida));
            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('result'=> $row['r_result'],
                        'course' => $row['r_course'],
                        'name' => $row['r_coursename']);

                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }

    public function queryCourseInformation($pIndex,$pCurso){
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_COURSEINFO","select * from f_getcourse($1,$2);")){
            $result = $this->objConnection->ejecuteStatement("SELECT_COURSEINFO", array($pIndex,$pCurso));
            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('index'=> $row['r_index'],
                        'course' => $row['r_course'],
                        'name' => $row['r_name']);

                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }
}

?>