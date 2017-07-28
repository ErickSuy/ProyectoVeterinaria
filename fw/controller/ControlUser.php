<?php
include_once("../../path.inc.php");
include_once("$dir_portal/fw/controller/Control.php");
include_once("$dir_portal/fw/model/ServiceQuery.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/controller/mailer/Mailer.php");
include_once("$dir_portal/fw/controller/ControlService.php");
include_once("$dir_portal/fw/controller/mailer/MailerRecover.php");


class ControlUser extends Control
{
    private $objservice;
    private $objuser;

    public function ControlUser($mail, $password, $id, $name, $surname, $address, $gender, $idtypeschool, $birthdate, $phone, $celular, $carnet, $unity, $extention, $career, $state, $idtypetrainer, $group, $site, $session)
    {
        $this->objservice = new ServiceQuery();
        $this->objuser = new TbUser($mail, $password, $id, $name, $surname, $address, $gender, $idtypeschool, $birthdate, $phone, $celular, $carnet, $unity, $extention, $career, $state, $idtypetrainer, $group, $site, $session);
    }

    public function updateStatus($iduser, $status)
    {
        $result = $this->objservice->updateStatusUser($iduser, $status);

        return json_encode($result);
    }

    public function setUser($objuser)
    {
        $this->objuser = $objuser;
    }

    public function getUser()
    {
        return $this->objuser;
    }

    private function getResult($result)
    {
        if ($result == 'OK') {
            echo json_encode(array('success' => TRUE, 'uno' => $this->objuser->getPassw(), 'dos' => $this->objuser->getPassword()));
        } else {
            echo json_encode(array('msg' => $result));
        }
    }

    public function registerStudent()
    {
        $result = 'OK';
        $key = ControlService::generateRandom(10);
        $this->objuser->setActivateLink(md5('getPassword(' . $key . ')'));
        $result = $this->objservice->insertUserStudent($this->objuser);
        if ($result == 'OK') {
            $objmail = new Mailer($this->objuser);
            $resultmail = $objmail->sender($result);
        }
        $this->getResult($result);
    }

    public function recover()
    {
        //si se desea activar el envio de correo, se debe descomentar lo anterior y afectar el metodo getRecov de $this->ojbuser para que envie la activacion del link
        //y en el script actualizar para que en lugar de actualizar el password actualize la activacion de link
        $result = 'OK';

        $password = ControlService::generateRandom(10);
        $this->objuser->setPassw($password);
        $passhid = md5($password);

        $this->objuser->setPassword($passhid);
        //$result=$this->objservice->recover($this->objuser);
        //esta parte el envio de correo y la generacion de la clave del link

        $linkhid = md5('getPassword(' . $password . ')');
        $this->objuser->setActivateLink($linkhid);

        $result = $this->objservice->recover($this->objuser);
        if ($result == 'OK') {
            $objmail = new MailerRecover($this->objuser);
            $resultmail = $objmail->sender();
        } else {

            $resultmail = "Verifique correo y verificaci&oacuten de seguridad.";
        }

        $this->getResult($result, $resultmail);
    }

    public function validate()
    {
        $result = $this->objservice->validateUser($this->objuser);

        return $result;
    }
    
    public function getUserData()
    {
       $result = $this->objservice->getDataUser($this->objuser);

        return $result;
    
    }
    
    

    public function editProfile($iduser)
    {
        $result = 'OK';
        $this->objuser->setIdUser($iduser);
        if (!$this->objuser->getPassw()) {
            $this->objuser->setPassword(NULL);
        }
        $this->objuser->setUnity($this->objuser->getUnity());
        $result = $this->objservice->updateChangeProfile($this->objuser);

        return $this->getResult($result); //,$resultmail);
    }

    public function editUser($iduser)
    {
        $result = 'OK';
        $this->objuser->setIdUser($iduser);
        if (!$this->objuser->getPassw()) {
            $this->objuser->setPassword(NULL);
        }
        $this->objuser->setUnity($this->objuser->getUnity());
        $result = $this->objservice->updateChangeProfile($this->objuser);

        return $this->getResult($result); //,$resultmail);
    }

    public function searchUser($filtroCorreo, $filtroNombre, $filtroTipoUsuario)
    {
        $result = $this->objservice->searchUser($filtroCorreo, $filtroNombre, $filtroTipoUsuario);

        return json_encode($result);
    }

    public function addRolUser($idrol)
    {
        $result = $this->objservice->insertRolUser($idrol);

        return json_encode($result);
    }

    public function getUserById($id)
    {
        $result = $this->objservice->getUserById($id);

        return $result;
    }

}

?>