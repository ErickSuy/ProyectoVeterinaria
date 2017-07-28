<?php

class TbPrivilege
{

    private $idPrivilege;

    private $name;

    private $state;

    private $tbPrivilegeRol;

    public function TbPrivilege($idtypeschool, $name)
    {
        $this->idtypeschool = $idtypeschool;
        $this->name = $name;
    }

    public function &getIdPrivilege()
    {
        return $this->idPrivilege;
    }

    public function setIdPrivilege(&$idPrivilege)
    {
        $this->idPrivilege = $idPrivilege;
    }

    public function &getName()
    {
        return $this->name;
    }

    public function setName(&$name)
    {
        $this->name = $name;
    }

    public function &getState()
    {
        return $this->state;
    }

    public function setState(&$state)
    {
        $this->state = $state;
    }

    public function getTbPrivilegeRol()
    {
        return $this->tbPrivilegeRol;
    }

    public function setTbPrivilegeRol($tbPrivilegeRol)
    {
        $this->tbPrivilegeRol = $tbPrivilegeRol;
    }

    public function getObjects()
    {
        return array('id' => $this->idprivilege, 'text' => $this->name);
    }

}

?>
