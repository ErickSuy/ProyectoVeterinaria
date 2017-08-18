<?php
include_once("$dir_portal/fw/model/Connection.php");
include_once("$dir_portal/fw/modelmapping/TbUser.php");
include_once("$dir_portal/fw/mapping/TbPrivilege.php");
include_once("$dir_biblio/biblio/SysConstant.php");

class ServiceQuery
{
    private $objuser;
    private $objconn;

    public function ServiceQuery()
    {
        $this->objconn = new Connection();
    }

    public function setUser(&$user)
    {
        $this->$objuser = $user;
    }

    public function getUser()
    {
        return $this->objuser;
    }

    public function getRol()
    {
        $result = $this->objconn->ejecuteQuery("SELECT idrol,name FROM tbrol order by idrol;");
        while ($row = $this->objconn->getResult($result)) {
            $rol = new TbRol($row['idrol'], $row['name']);
            $rols[] = $rol->getObjects();
        }

        return $rols;
    }

    public function validateUser(&$objuser)
    {
        if ($this->objconn->prepared("CREATE_SESSION", "SELECT * from f_createsession($1,$2,$3,$4,$5,$6,$7,$8);")) {
            $result = $this->objconn->ejecuteStatement("CREATE_SESSION", $objuser->getValidate());

            if ((strpos('' . $result, 'ERROR') === FALSE) and $result) {
                $row = $this->objconn->getRow($result, 0);

                $objuser->setIdUser($row[1]);
                $objuser->setName($row[2]);

                if ($objuser->getIdUser()) {
                    while ($row = $this->objconn->getResult($result)) {
                        if ($row[0] != 1) {
                            $privilege = new TbPrivilege($row[1], $row[2]);
                            $privileges[] = $privilege;
                        }
                    }

                    if ($privileges) {
                        $objuser->setPrivileges($privileges);
                    }

                    switch ($objuser->getGroup()) {
                        case GRUPO_ESTUDIANTE:
                            if ($this->objconn->prepared("GET_STUDENT", "SELECT ts.idstudent,upper(trim(ts.name)),upper(trim(ts.surname)), case when idcedula is null then '' else idcedula end, case when dpi is null then '' else dpi end,case when idpassport is null then '' else idpassport end, case when birthday is null then '1900-01-01' else birthday::character varying end, case when address is null then '' else address end,case when birthaddress is null then '' else birthaddress end, case when maritalstatus is null then '' else maritalstatus end,case when phone is null or phone='' then '00000000' else phone end, case when cellphone is null or cellphone='' then '00000000' else cellphone end,case when sex is null then '' else sex end,case when titlename is null then '' else titlename end, case when institutionname is null then '' else institutionname end,universityadmissiondate,facultyadmissiondate,diseasedescription,case when mother is null then '' else mother end,case when motherphone is null or motherphone='' then '00000000' else motherphone end,case when father is null then '' else father end,case when fatherphone is null or fatherphone='' then '00000000' else fatherphone end,emergencyname,emergencyphone,ts.idnationality,case when email is null then '' else email end,case when nov is null then '' else nov end,tn.idnationality,country,gentilicio from tbstudent ts, tbnationality tn where ts.idnationality=tn.idnationality and idstudent=$1;")) {
                                $param = array($objuser->getId());
                                $resultu = $this->objconn->ejecuteStatement("GET_STUDENT", $param);
                                $rowu = $this->objconn->getRow($resultu, 0);
                                $objuser->setIdUser($rowu[0]);
                                $objuser->setName($rowu[1]);
                                $objuser->setSurname($rowu[2]);
                                $objuser->setCedula($rowu[3]);
                                $objuser->setDpi($rowu[4]);
                                $objuser->setBirthdate($rowu[6]);
                                $objuser->setAddress($rowu[7]);
                                $objuser->setBirthAddres($rowu[8]);
                                $objuser->setMaritialStatus($rowu[9]);
                                $objuser->setPhone($rowu[10]);
                                $objuser->setCelular($rowu[11]);
                                $objuser->setGender($rowu[12]);
                                $objuser->setTitle($rowu[13]);
                                $objuser->setInstitutionName($rowu[14]);
                                $objuser->setMother($rowu[18]);
                                $objuser->setFather($rowu[20]);
                                $objuser->setMail($rowu[25]);
                                $objuser->setNationality($rowu[29]);
                                $objuser->setGroup(GRUPO_ESTUDIANTE);
                                $curriculum = $this->getQueryCurriculumInfo($objuser->getId(), $objuser->getCareer());
                                $objuser->setCurriculum($curriculum['idcurriculum']);
                            }
                            break;
                        case GRUPO_DOCENTE:
                            if ($this->objconn->prepared("GET_TEACHER", "select ts.idteacher,name,surname,case when email is null then '' else email end,case when phone is null or phone='' then '00000000' else phone end, case when cellphone is null or cellphone='' then '00000000' else cellphone end,ts.idnationality,case when sex is null then '' else sex end,case when maritalstatus is null then '' else maritalstatus end,case when address is null then '' else address end,tn.idnationality,country,gentilicio,tr.idrating, trt.ratingname from tbteacher ts, tbnationality tn,tbteacherrating tr, tbteacherratingtype trt where ts.idteacher=$1 and ts.idnationality=tn.idnationality and ts.idteacher=tr.idteacher and tr.idrating=trt.idrating;")) {
                                $param = array($objuser->getId());
                                $resultu = $this->objconn->ejecuteStatement("GET_TEACHER", $param);
                                $rowu = $this->objconn->getRow($resultu, 0);

                                $objuser->setIdUser($rowu[0]);
                                $objuser->setName($rowu[1]);
                                $objuser->setSurname($rowu[2]);
                                $objuser->setMail($rowu[3]);
                                $objuser->setPhone($rowu[4]);
                                $objuser->setCelular($rowu[5]);
                                $objuser->setGender($rowu[7]);
                                $objuser->setMaritialStatus($rowu[8]);
                                $objuser->setAddress($rowu[9]);
                                $objuser->setNationality($rowu[12]);
                                $objuser->setGroup(GRUPO_DOCENTE);
                                $objuser->setRatingType($rowu[13]);
                                $objuser->setRatingTypeName($rowu[14]);
                            }
                            break;
                        case GRUPO_CONTROL_ACADEMICO:
                            $objuser->setIdUser($objuser->getId());
                            $objuser->setGroup(GRUPO_CONTROL_ACADEMICO);
                    }

                    return 'OK';
                }
            } else {
                return substr($result, strpos($result, 'ERROR'), 11);
            }
        }
    }
    
    

    public function deleteConnection(&$objuser)
    {
        if ($this->objconn->prepared("DELETE_CONNECTION", "select * from f_deletesession($1::numeric,$2::smallint);")) {
            $result = $this->objconn->ejecuteStatement("DELETE_CONNECTION", $objuser->getConnectionParams());
            $row = $this->objconn->getRow($result, 0);

            return $row[0];
        }
    }


    public function getUserById($id)
    {
        $result = $this->objconn->ejecuteQuery("
								SELECT *
								FROM tbauth_user
								WHERE iduser = " . $id . "");
        if ($row = $this->objconn->getResult($result)) {
            $data = array('user' => $row[0], 'pass' => $row[2], 'keyword' => $row[3]);
        }

        return $data;
    }

    public function getQueryCurriculumInfo($id, $career)
    {
        $result = $this->objconn->ejecuteQuery("
                SELECT t1.idstudent,t1.idcareer,t1.year,t1.idcurriculum, t1.enrollmentdate
                    FROM tbenrollment t1 join tbstudentcareer t2 on t1.idstudent=t2.idstudent and t1.idcareer=t2.idcareer
                        WHERE t1.idstudent=" . $id . " and t1.idcareer=" . $career . " order by year desc limit 1;");
        if ($row = $this->objconn->getResult($result)) {
            $data = array('idcurriculum' => $row[3]);
        }

        return $data;
    }
    
    /*********************************************************************************/
    public function getDataUser(&$objuser)
    {
        switch ($objuser->getGroup()) {
                        case GRUPO_ESTUDIANTE:
                            $curriculum = $this->getQueryCurriculumInfo($objuser->getId(), $objuser->getCareer());
                            $curriculum = $curriculum['idcurriculum'];   
                            if ($this->objconn->prepared("GET_STUDENT", "SELECT ts.idstudent,upper(trim(ts.name)),upper(trim(ts.surname)), 
                                    case when idcedula is null then '' else idcedula end, 
                                    case when dpi is null then '' else dpi end,
                                    case when idpassport is null then '' else idpassport end, 
                                    case when birthday is null then '1900-01-01' 
                                    else birthday::character varying end, 
                                    case when address is null then '' else address end,
                                    case when birthaddress is null then '' else birthaddress end, 
                                    case when maritalstatus is null then '' else maritalstatus end,
                                    case when phone is null or phone='' then '00000000' else phone end, 
                                    case when cellphone is null or cellphone='' then '00000000' else cellphone end,
                                    case when sex is null then '' else sex end,
                                    case when titlename is null then '' else titlename end, 
                                    case when institutionname is null then '' else institutionname end,
                                    universityadmissiondate,facultyadmissiondate,diseasedescription,
                                    case when mother is null then '' else mother end,
                                    case when motherphone is null or motherphone='' then '00000000' else motherphone end,
                                    case when father is null then '' else father end,
                                    case when fatherphone is null or fatherphone='' then '00000000' else fatherphone end,
                                    emergencyname,emergencyphone,ts.idnationality,
                                    case when email is null then '' else email end,case when nov is null then '' else nov end,tn.idnationality,country,gentilicio, 
                                    c.\"name\" as careername, cd.\"name\" as curriculumname
                                    from tbstudent ts, tbnationality tn,tbauth_user au,tbcareer c, tbcurriculumdata cd
                                    where ts.idnationality=tn.idnationality 
                                    and ts.idstudent=$1
                                    and c.idcareer = $2
                                    and cd.idcurriculum = $3;")) {
                                $param = array($objuser->getId(),$objuser->getCareer(),$curriculum);
                                $resultu = $this->objconn->ejecuteStatement("GET_STUDENT", $param);
                                $rowu = $this->objconn->getRow($resultu, 0);
                                $objuser->setIdUser($rowu[0]);
                                $objuser->setName($rowu[1]);
                                $objuser->setSurname($rowu[2]);
                                $objuser->setCedula($rowu[3]);
                                $objuser->setDpi($rowu[4]);
                                $objuser->setBirthdate($rowu[6]);
                                $objuser->setAddress($rowu[7]);
                                $objuser->setBirthAddres($rowu[8]);
                                $objuser->setMaritialStatus($rowu[9]);
                                $objuser->setPhone($rowu[10]);
                                $objuser->setCelular($rowu[11]);
                                $objuser->setGender($rowu[12]);
                                $objuser->setTitle($rowu[13]);
                                $objuser->setInstitutionName($rowu[14]);
                                $objuser->setMother($rowu[18]);
                                $objuser->setFather($rowu[20]);
                                $objuser->setMail($rowu[25]);
                                $objuser->setNationality($rowu[29]);
                                $objuser->setPassword($rowu[30]);
                                $objuser->setGroup(GRUPO_ESTUDIANTE);
                                $objuser->setCareer($objuser->getCareer());
                                $objuser->setCurriculum($curriculum);
                                
                            }
                            break;
        }
    }
    
    
}

?>
