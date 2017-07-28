<?php
    /**
     * Created by PhpStorm.
     * User: emsaban
     * Date: 21/08/14
     * Time: 02:34 PM
     */

    require_once("../../path.inc.php");
    require_once("$dir_portal/fw/model/Connection.php");

    class ControlAssignationRequeriments1_SQL
    {
        private $objConnection;

        public function ControlAssignationRequeriments1_SQL()
        {
            $this->objConnection = new Connection();
        }

        public function  queryProcessInformation($pAnio,$pPeriodo1,$pPeriodo2,$pProceso,$pFecha)
        {
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_PROCESSACTIVE", "SELECT * FROM f_activeprocess($1::smallint,$2::smallint,array[$3::smallint,$4::smallint],$5);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_PROCESSACTIVE", array($pProceso,$pAnio,$pPeriodo1,$pPeriodo2,$pFecha));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'=> $row['r_result'],
                                      'msg' => $row['r_resultmsg'],
                            'schoolyear' => $row['r_schoolyear']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }

        public function  queryCheckEnrollment($pUsuario,$pCarrera,$pAnio)
        {
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_CHECKENROLLMENT", "SELECT * FROM f_activeenrollment($1::numeric,$2::smallint,$3::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_CHECKENROLLMENT", array($pUsuario,$pCarrera,$pAnio));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'=> $row['r_result'],
                                      'msg' => $row['r_resultmsg'],
                            'curriculum' => $row['r_curriculum'],
                            'enrollmentdate' => $row['r_enrollmentdate']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }

        public function  queryCheckAccessSite($pUsuario,$pGrupo,$pSitio,$pCarrera,$pAnio,$pPeriodo)
        {
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_CHECKACCESSSITE","SELECT * FROM f_accesssite($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint,$6::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_CHECKACCESSSITE", array($pUsuario,$pGrupo,$pSitio,$pCarrera,$pAnio,$pPeriodo));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'=> $row['r_result'],
                            'msg' => $row['r_resultmsg']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }

        public function queryScheduleInformation($pAnio,$pPeriodo,$pPensum,$pCarrera,$pTipo){
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_SCHEDULEINFORMATION","SELECT * FROM f_getschedule($1::smallint,$2::smallint,$3::smallint,$4::smallint,$5::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_SCHEDULEINFORMATION", array($pPensum,$pCarrera,$pTipo,$pAnio,$pPeriodo));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('index'=> $row['r_index'],
                            'course' => $row['r_course'],
                            'name' => $row['r_name'],
                            'lab' => $row['r_lab'],
                            'required' => $row['r_required'],
                            'requirement' => $row['r_requirement'],
                            'credits' => $row['r_credits'],
                            'curriculum' => $row['r_curriculum'],
                            'section' => $row['r_section']);
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
                $pos=0;
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('cindex'=> $row['r_index'],
                            'course' => $row['r_course'],
                            'section' => $row['r_section'],
                            'name' => $row['r_name'],
                            'credits' => $row['r_credits'],
                            'lab' => $row['r_lab'],
                            'labgroup' => $row['r_labgroup'],
                            'requirement' => $row['r_requirement'],
                            'assigned' => $row['r_assigned'],
                            'check' => $row['r_check']);
                        $vecResult[++$pos] = $rRow;
                    }
                }
            }
            return $vecResult;
        }
    }

?>