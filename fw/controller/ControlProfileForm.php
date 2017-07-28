<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 17/08/14
 * Time: 12:39 PM
 *
 */

include_once("../../path.inc.php");
include_once("Control.php");
include_once("ControlService.php");
include_once("$dir_portal/fw/model/sql/ControlProfileForm_SQL.php");

class ControlProfileForm extends Control
{
    private $objServiceQuery;
    private $objUser;

    public function ControlProfileForm($pUser)
    {
        $this->objServiceQuery = new ControlProfileForm_SQL();
        $this->objUser = $pUser;
    }

    public function setUser($pUser)
    {
        $this->objUser = $pUser;
    }

    public function getUser()
    {
        return $this->objUser;
    }

    public function getDepartmentsList()
    {
        $result = $this->objServiceQuery->queryDepartmentList();
        $datos = '' .
            '<SELECT  id="_extendidaendepto" name="extendidaendepto" class="labeltext" onchange="listarSeleccionado(this.selectedIndex,this.value);">';

        if ($result != NULL) {
            foreach ($result as $departamento) {
                $datos .= '<option value="' . $departamento['id'] . '" id="' . $departamento['name'] . '">' . $departamento['name'] . '</option>';
            }
            unset ($result);
        }

        $datos .= '</SELECT>' . '';

        return $datos;
    }
}

?>