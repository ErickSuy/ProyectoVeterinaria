<?php

class TbUser
{

    private $iduser;
    private $mail;
    private $password;
    private $id;
    private $name;
    private $surname;
    private $address;
    private $gender;
    private $idtypeschool;
    private $birthdate;
    private $phone;
    private $celular;
    private $carnet;
    private $unity;
    private $extention;
    private $career;
    private $state;
    private $idtypetrainer;
    private $activatelink;
    private $passw;
    private $privileges;
    private $idgroup;
    private $idsite;
    private $idsession;
    private $birthaddres;
    private $maritalstatus;
    private $title;
    private $institutionname;
    private $mother;
    private $father;
    private $dpi;
    private $cedula;
    private $nationality;
    private $curriculum;
    private $enrollmentDate;
    private $mainCareer;
    private $ratingType;
    private $ratingTypeName;
    private $alternateMail;
    private $alternatePhone;
    private $alternateCelular;
    private $titlePrefix;

    public function TbUser($mail, $password, $id, $name, $surname, $address, $gender, $idtypeschool, $birthdate, $phone, $celular, $carnet, $unity, $extention, $career, $state, $idtypetrainer, $group, $site, $session)
    {
        $this->mail = $mail;
        $this->password = $password;
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->address = $address;
        $this->gender = $gender;
        $this->idtypeschool = $idtypeschool;
        $this->birthdate = $birthdate;
        $this->phone = $phone;
        $this->celular = $celular;
        $this->carnet = $carnet;
        $this->unity = $unity;
        $this->extention = $extention;
        $this->career = $career;
        $this->state = $state;
        $this->idtypetrainer = $idtypetrainer;
        $this->passw = $password;
        $this->idgroup = $group;
        $this->idsite = $site;
        $this->idsession = $session;
    }

    public function &getEnrollmentDate()
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate($pEnroll)
    {
        $this->enrollmentDate = $pEnroll;
    }

    public function  &getCurriculum()
    {
        return $this->curriculum;
    }

    public function setCurriculum($pCurriculum)
    {
        $this->curriculum = $pCurriculum;
    }

    public function &getSite()
    {
        return $this->idsite;
    }

    public function setSite($site)
    {
        $this->idsite = $site;
    }

    public function &getGroup()
    {
        return $this->idgroup;
    }

    public function &getGroupName()
    {
        switch ($this->getGroup()) {
            case GRUPO_ESTUDIANTE:
                return ("ESTUDIANTE");
                break;
            case GRUPO_DOCENTE:
                return ("DOCENTE");
                break;
            case GRUPO_AUXILIAR:
                return ("AUXILIAR");
                break;
        }
    }

    public function &getCurriculumName()
    {

        switch ($this->getCurriculum()) {
            case 1:
                return ("PENSUM DE ESTUDIOS 82");
                break;
            case 2:
                return ("PENSUM DE ESTUDIOS 1,999");
                break;
            case 3:
                return ("PENSUM DE ESTUDIOS 82");
                break;
            case 4:
                return ("PENSUM DE ESTUDIOS 1,999");
                break;
            case 5:
                return ("PENSUM DE ESTUDIOS 1,970");
                break;
        }
    }

    public function setGroup($group)
    {
        $this->idgroup = $group;
    }

    public function &getSession()
    {
        return $this->idsession;
    }

    public function setSession($session)
    {
        $this->idsession = $session;
    }

    public function &getPassw()
    {
        return $this->passw;
    }

    public function setPassw($passw)
    {
        $this->passw = $passw;
    }

    public function &getIdUser()
    {
        return $this->iduser;
    }

    public function setIdUser(&$iduser)
    {
        $this->iduser = $iduser;
    }

    public function &getName()
    {
        return $this->name;
    }

    public function setName(&$name)
    {
        $this->name = $name;
    }

    public function &getSurName()
    {
        return $this->surname;
    }

    public function setSurName(&$surname)
    {
        $this->surname = $surname;
    }

    public function &getCarnet()
    {
        return $this->carnet;
    }

    public function setCarnet(&$carnet)
    {
        $this->carnet = $carnet;
    }

    public function setAlternateMail(&$pAlternateMail)
    {
        $this->alternateMail = $pAlternateMail;
    }

    public function &getAlternateMail()
    {
        return $this->alternateMail;
    }

    public function setAlternatePhone(&$pAlternatePhone)
    {
        $this->alternatePhone = $pAlternatePhone;
    }

    public function &getAlternatePhone()
    {
        return $this->alternatePhone;
    }

    public function setAlternateCelular(&$pAlternateCelular)
    {
        $this->alternateCelular = $pAlternateCelular;
    }

    public function &getAlternateCelular()
    {
        return $this->alternateCelular;
    }

    public function setTitlePrefix(&$pTitlePrefix)
    {
        $this->titlePrefix = $pTitlePrefix;
    }

    public function &getTitlePrefix()
    {
        return $this->titlePrefix;
    }

    public function &getMail()
    {
        return $this->mail;
    }

    public function setMail(&$mail)
    {
        $this->mail = $mail;
    }


    public function &getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function &getGender()
    {
        return $this->gender;
    }

    public function setGender(&$gender)
    {
        $this->gender = $gender;
    }

    public function &getBirthdate()
    {
        return $this->birthdate;
    }

    public function &getBirthdateText()
    {
        $time = strtotime($this->getBirthdate());

        $month = date("m", $time);
        $year = date("Y", $time);
        $day = date("d", $time);

        switch ($month) {
            case '01' :
                $month = "Enero";
                break;
            case '02' :
                $month = "Febrero";
                break;
            case '03' :
                $month = "Marzo";
                break;
            case '04' :
                $month = "Abril";
                break;
            case '05' :
                $month = "Mayo";
                break;
            case '06' :
                $month = "Junio";
                break;
            case '07' :
                $month = "Julio";
                break;
            case '08' :
                $month = "Agosto";
                break;
            case '09' :
                $month = "Septiembre";
                break;
            case '10' :
                $month = "Octubre";
                break;
            case '11' :
                $month = "Noviembre";
                break;
            case '12' :
                $month = "Diciembre";
                break;
            default   :
                $month = "Mes";
        }

        return $day . ' de ' . $month . ' de ' . $year;
    }

    public function setBirthdate(&$birthdate)
    {
        $this->birthdate = $birthdate;
    }

    public function &getPhone()
    {
        return $this->phone;
    }

    public function setPhone(&$phone)
    {
        $this->phone = $phone;
    }

    public function &getCelular()
    {
        return $this->celular;
    }

    public function setCelular(&$celular)
    {
        $this->celular = $celular;
    }

    public function &getUnity()
    {
        return $this->unity;
    }

    public function setUnity(&$unity)
    {
        $this->unity = $unity;
    }

    public function &getId()
    {
        return $this->id;
    }

    public function setId(&$id)
    {
        $this->id = $id;
    }

    public function &getExtention()
    {
        return $this->extention;
    }

    public function setExtention(&$extention)
    {
        $this->extention = $extention;
    }

    public function &getCareer()
    {
        return $this->career;
    }

    public function &getCareerName()
    {
        switch($this->career) {
            case VETERINARIA:
                return "[02] MEDICINA VETERINARIA";
            case ZOOTECNIA:
                return "[03] ZOOTECNIA";
        }
    }

    public function setCareer(&$career)
    {
        $this->career = $career;
    }

    public function &getAddress()
    {
        return $this->address;
    }

    public function setAddress(&$address)
    {
        $this->address = $address;
    }

    public function &getState()
    {
        return $this->state;
    }

    public function setState(&$state)
    {
        $this->state = $state;
    }

    public function &getIdTypeTrainer()
    {
        return $this->idtypetrainer;
    }

    public function setIdTypeTrainer(&$idtypetrainer)
    {
        $this->idtypetrainer = $idtypetrainer;
    }

    public function &getIdTypeSchool()
    {
        return $this->idtypeschool;
    }

    public function setIdTypeSchool(&$idtypeschool)
    {
        $this->idtypeschool = $idtypeschool;
    }

    public function &getActivateLink()
    {
        return $this->activatelink;
    }

    public function setActivateLink($activatelink)
    {
        $this->activatelink = $activatelink;
    }

    public function &getPrivileges()
    {
        return $this->privileges;
    }

    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }

    public function get()
    {
        $parameters = array($this->mail, $this->password, $this->id, $this->name, $this->surname, $this->address, $this->gender, $this->idtypeschool, $this->birthdate, $this->phone, $this->celular, $this->carnet, $this->unity, $this->extention, $this->career, $this->state, $this->idtypetrainer, $this->activatelink);

        return $parameters;
    }

    public function getAll()
    {

        $parameters = array($this->iduser, $this->mail, $this->password, $this->id, $this->name, $this->surname, $this->address, $this->gender, $this->idtypeschool, $this->birthdate, $this->phone, $this->celular, $this->carnet, $this->unity, $this->extention, $this->career, $this->state, $this->idtypetrainer, $this->activatelink);

        return $parameters;
    }

    public function getRecov()
    {
        $parameters = array($this->mail, $this->activatelink);

        return $parameters;
    }

    public function getValidate()
    {
        $parameters = array($this->id, $this->idgroup, $this->idsession, 'now', 0, $this->idsite, $this->getPassw(), $this->career);

        return $parameters;
    }

    public function getProfile()
    {
        $parameters = array($this->iduser, $this->password, $this->id, $this->address, $this->carnet, $this->unity, $this->extention, $this->career);

        return $parameters;
    }

    public function getConnectionParams()
    {
        return array($this->id, $this->idgroup);
    }

    public function getObjects()
    {
        return array('iduser' => $this->id, 'name' => $this->name, 'mail' => $this->mail, 'idtypetrainer' => $this->getIdTypeTrainer(), 'state' => $this->getState());
    }

    public function setBirthAddres($birthaddres)
    {
        $this->birthaddres = $birthaddres;
    }

    public function &getBirthaddres()
    {
        return $this->birthaddres;
    }

    public function setMaritialStatus($maritialstatus)
    {
        $this->maritalstatus = $maritialstatus;
    }

    public function &getMaritialStatus()
    {
        return $this->maritalstatus;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function &getTitle()
    {
        return $this->title;
    }

    public function setInstitutionName($institutionname)
    {
        $this->institutionname = $institutionname;
    }

    public function &getInstitutionName()
    {
        return $this->institutionname;
    }

    public function setMother($mother)
    {
        $this->mother = $mother;
    }

    public function &getMother()
    {
        return $this->mother;
    }

    public function setFather($father)
    {
        $this->father = $father;
    }

    public function &getFather()
    {
        return $this->father;
    }

    public function setDpi($dpi)
    {
        $this->dpi = $dpi;
    }

    public function &getDpi()
    {
        return $this->dpi;
    }

    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    public function &getCedula()
    {
        return $this->cedula;
    }

    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    public function &getNationality()
    {
        return $this->nationality;
    }

    public function &getAge()
    {
        $time = strtotime($this->getBirthdate());
        $mesnac = date("m", $time);
        $anionac = date("Y", $time);
        $dianac = date("d", $time);

        $dia = date(d);
        $tmes = date(m);
        $tanio = date(Y);


        $edad = 0;
        if ($mesnac <= $tmes) { //si el mes de nacimiento es menor o igual al mes actual, es posible que ya haya cumplido años
            if ($mesnac == $tmes) { //si el mes es el mismo que el de nacimiento, es posible que ya haya cumplido años
                if ($dianac > $dia) { //si el día de nacimiento es mayor al día actual, no ha cumplido años
                    $edad = $tanio - $anionac - 1;
                } else { //si el día de nacimiento es menor o igual al actual, ya cumplió años
                    $edad = $tanio - $anionac;
                }
            } else { //si el mes de nacimiento es menor al actual, ya cumplió años
                $edad = $tanio - $anionac;
            }
        } else { //si el mes de nacimiento es mayor al mes actual, no ha cumplido años
            $edad = $tanio - $anionac - 1;
        }

        return $edad;
    }

    public function setMainCareer($pMainCareer)
    {
        $this->mainCareer = $pMainCareer;
    }

    public function &getMainCareer()
    {
        return $this->mainCareer;
    }

    public function setRatingType($pRatingType)
    {
        $this->ratingType = $pRatingType;
    }

    public function setRatingTypeName($pRatingTypeName)
    {
        $this->ratingTypeName = $pRatingTypeName;
    }

    public function &getRatingType()
    {
        return $this->ratingType;
    }

    public function &getRatingTypeName()
    {
        return $this->ratingTypeName;
    }
}

?>
