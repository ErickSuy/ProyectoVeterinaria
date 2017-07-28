<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 3/09/14
 * Time: 06:52 AM
 */

require_once("../../path.inc.php");
require_once("$dir_portal/fw/model/Connection.php");

class ValidateCourseSchedule_SQL
{
    private $objConnection;

    public function ValidateCourseSchedule_SQL()
    {
        $this->objConnection = new Connection();
    }

    public function queryCourseSchedule($p_index, $p_course, $p_type, $p_section, $p_year, $p_schoolyear,$p_career)
    {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_GETCOURSESCHEDULE", "SELECT * FROM f_getcourseschedule($1::smallint,$2::smallint,$3::smallint,$4,$5::smallint,$6::smallint,$7::smallint);")) {
            $result = $this->objConnection->ejecuteStatement("SELECT_GETCOURSESCHEDULE", array($p_index, $p_course, $p_type, $p_section, $p_year, $p_schoolyear,$p_career));
            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array('type' => $row['r_type'],
                        'index' => $row['r_index'],
                        'course' => $row['r_course'],
                        'section' => $row['r_section'],
                        'starttime' => $row['r_starttime'],
                        'endtime' => $row['r_endtime'],
                        'mon' => $row['r_mon'],
                        'tue' => $row['r_tue'],
                        'wed' => $row['r_wed'],
                        'thu' => $row['r_thu'],
                        'fri' => $row['r_fri'],
                        'sat' => $row['r_sat'],
                        'sun' => $row['r_sun']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
    }
}

?>