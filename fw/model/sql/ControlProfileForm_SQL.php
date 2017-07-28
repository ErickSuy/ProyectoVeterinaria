<?php
    /**
     * Created by PhpStorm.
     * User: EdwinMac-donall
     * Date: 17/08/14
     * Time: 12:55 PM
     */

    require_once("../../path.inc.php");
    require_once("$dir_portal/fw/model/Connection.php");

    class ControlProfileForm_SQL
    {
        private $objConnection;

        public function ControlProfileForm_SQL()
        {
            $this->objConnection = new Connection();
        }

        public function queryDepartmentList()
        {
            $vecDepartments = NULL;

            if ($this->objConnection->prepared("SELECT_DEPARTAMENTS", "SELECT * FROM TBDEPARTMENT WHERE NOT(IDDEPARTMENT=$1) ORDER BY IDDEPARTMENT;")) {
                $result = $this->objConnection->ejecuteStatement("SELECT_DEPARTAMENTS", array(0));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $depRow = array('id' => $row['iddepartment'], 'name' => $row['name'], 'orden' => $row['idorden']);
                        $vecDepartments[] = $depRow;
                    }
                }
            }

            return $vecDepartments;
        }
    }

?>