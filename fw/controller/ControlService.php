<?php
include_once("../../path.inc.php");
include_once("$dir_portal/fw/model/ServiceQuery.php");

class ControlService
{
    private $service;

    public function ControlService($service)
    {
        $this->service = $service;
    }

    public function randomText($length)
    {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{rand(0, 35)};
        }

        return $key;
    }

    public static function generateRandom($length)
    {
        $exp_reg = "[^A-Z0-9]";

        return substr(preg_replace($exp_reg, "", md5(rand())) . preg_replace($exp_reg, "", md5(rand())) . preg_replace($exp_reg, "", md5(rand())), 0, $length);
    }

    public function getCaptcha()
    {
        $var = $this->randomText(8);

        return json_encode(array('uno' => $var, 'dos' => md5('getcaptcha(' . $var . ')')));
    }

    public function getSchool()
    {
        $objservice = new ServiceQuery();

        return json_encode($objservice->getTypeSchool());
    }

    public function getRol()
    {
        $objservice = new ServiceQuery();

        return json_encode($objservice->getRol());
    }

    public function getClassRooms()
    {
        $objservice = new ServiceQuery();

        return json_encode($objservice->getClassRoomsAvalables());
    }

    public function getProfesores()
    {
        $objservice = new ServiceQuery();

        return json_encode($objservice->getProfesores());
    }

    public function Activate($code, $activatekey)
    {
        $objservice = new ServiceQuery();
        $array = array($code, $activatekey);
        $response = $objservice->activateUserStudent($array);
        if ($response == 'OK') {
            return "Activaci&oacute;n de cuenta realizada correctamente";
        } else {
            return "No se realiz&oacute; la activaci&oacute;n de cuenta correctamente";
        }
    }

    public function ActivateRecover($code, $activatekey, $password)
    {
        $objservice = new ServiceQuery();
        $passw = md5($password);
        $array = array($code, $passw, $activatekey);
        $response = $objservice->activateRecover($array);
        if ($response == 'OK') {
            return "Su contrase&#241a  ha sido cambiada con &eacute;xito";
        } else {
            return "No se realiz&oacute; la activaci&oacute;n de cuenta correctamente";
        }
    }

    public function getInstitution()
    {
        $objservice = new ServiceQuery();

        return json_encode($objservice->getInstitution());
    }

    public function getCourseStates()
    {
        $objservice = new ServiceQuery();

        return json_encode($objservice->getCourseStates());
    }

    public function closeSession(&$objuser)
    {
        $objservice = new ServiceQuery();
        $result = $objservice->deleteConnection($objuser);

        return $result;
    }
}

?>
