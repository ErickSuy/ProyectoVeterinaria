<?php

include_once("/var/www/path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/controller/Control.php");
include_once("$dir_portal/fw/model/ServiceQuery.php");
include_once("$dir_portal/fw/model/mapping/Tbcourse.php");
include_once("$dir_portal/fw/model/sql/ControlCourse_SQL.php");

class ControlCourse extends Control
{
    private $objservice;
    private $objcourse;
    private $objServiceQuery;

    public function ControlCourse($name, $description, $idinstitution, $duration, $state)
    {
        $this->objservice = new ServiceQuery();
        $this->objServiceQuery = new ControlCourse_SQL();
        $this->objcourse = new TbCourse(NULL, $name, $description, $duration, $idinstitution, NULL, NULL, NULL, NULL, NULL);
        $this->objcourse->setState($state);

    }

    public function setCourse($objcourse)
    {
        $this->objcourse = $objcourse;
    }

    public function getCourse()
    {
        return $this->objcourse;
    }

    private function getResult($result)
    {
        if ($result == 'OK') {
            echo json_encode(array('success' => TRUE, 'uno' => 'nuevo'));
        } else {
            echo json_encode(array('msg' => $result));
        }
    }

    public function getCourses()
    {
        return json_encode($this->objservice->getCourses());
    }

    public function insertCourse()
    {
        $result = 'OK';
        $result = $this->objservice->insertCourse($this->objcourse);
        $this->getResult($result);
    }

    public function updateCourse($idcourse)
    {
        $result = 'OK';
        $this->objcourse->setIdCourse($idcourse);
        $result = $this->objservice->updateCourse($this->objcourse);

        $this->getResult($result);
    }

    public function deleteCourse($idcourse)
    {
        $result = 'OK';
        $this->objcourse->setIdCourse($idcourse);
        $result = $this->objservice->deleteCourse($this->objcourse);

        return $this->getResult($result);
    }

    public function getidcourses($idcourse)
    {
        return $this->objservice->getidcourses($idcourse);
    }

    public function activate($idcourse)
    {
        $result = 'OK';
        $result = $this->objservice->activateCourse($idcourse);

        return $result;
    }

    public function getCoursesInstitutions($idcourse)
    {
        $this->objcourse->setIdCourse($idcourse);

        return json_encode($this->objservice->getCoursesInstitutions($this->objcourse->getIdCourse()));
    }

    public function insertCourseInstitution($idcourse, $idinstitution, $section, $periodo, $anio)
    {

        $this->objcourse->setIdCourse($idcourse);
        $this->objcourse->setIdInstitution($idinstitution);
        $this->objcourse->setSection($section);
        $this->objcourse->setPeriodo($periodo);
        $this->objcourse->setAnio($anio);
        $result = $this->objservice->insertCourseInstitution($this->objcourse->getIdCourse(), $this->objcourse->getIdInstitution(), $this->objcourse->getSection(), $this->objcourse->getPeriodo(), $this->objcourse->getAnio());

        return $this->getResult($result);
    }

    public function updateInstitutionCourse($idcourse, $idinstitutionh, $sectionh, $periodoh, $anioh, $idinstitution, $section, $periodo, $anio, $state)
    {
        $result = 'OK';
        $result = $this->objservice->updateInstitutionCourse($idcourse, $idinstitutionh, $sectionh, $periodoh, $anioh, $idinstitution, $section, $periodo, $anio, $state);

        return $this->getResult($result);
    }

    public function activateInstitutionCourse($idcourse, $idinstitution, $section, $periodo, $anio, $state)
    {
        $result = 'OK';
        $result = $this->objservice->activateInstitutionCourse($idcourse, $idinstitution, $section, $periodo, $anio, $state);

        return $this->getResult($result);
    }

    public function findAll()
    {
        $result = $this->objservice->getCursos();

        return json_encode($result);
    }

    public function getCourseList(&$pObjUser)
    {
        $result = $this->objServiceQuery->queryCoursetList($pObjUser->getId(), $pObjUser->getCareer());
        $result2 = NULL;

        $catalogoAprobados = array();
        $catalogoCierre = array();

        if ($result != NULL) {
            $numReg1 = 0;
            $numReg2 = 0;
            foreach ($result as $curso) {
                if ($curso['idcourse'] != 743 and $curso['cycle'] < 11) {
                    $catalogoAprobados[$numReg1]['num'] = $numReg1 + 1;
                    $catalogoAprobados[$numReg1]['cod'] = $curso['idcourse'];
                    $catalogoAprobados[$numReg1]['nom'] = $curso['name'];
                    $catalogoAprobados[$numReg1]['cred'] = $curso['credits'];
                    $catalogoAprobados[$numReg1]['fechaa'] = date("m", strtotime($curso['approvaldate'])) . '-' . date("Y", strtotime($curso['approvaldate']));
                    $catalogoAprobados[$numReg1]['nota'] = $curso['score'];
                    $catalogoAprobados[$numReg1]['desc'] = $curso['description'];

                    $numReg1 += 1;
                } else {
                    $catalogoCierre[$numReg2]['num'] = $numReg2 + 1;
                    $catalogoCierre[$numReg2]['cod'] = $curso['idcourse'];
                    $catalogoCierre[$numReg2]['nom'] = $curso['name'];
                    $catalogoCierre[$numReg2]['cred'] = $curso['credits'];
                    $catalogoCierre[$numReg2]['fechaa'] = date("m", strtotime($curso['approvaldate'])) . '-' . date("Y", strtotime($curso['approvaldate']));
                    $catalogoCierre[$numReg2]['nota'] = $curso['score'];
                    $catalogoCierre[$numReg2]['desc'] = $curso['description'];
                    $numReg2 += 1;
                }
            }
            unset($result);
        }

        $result2[] = $catalogoAprobados;
        $result2[] = $catalogoCierre;

        return $result2;
    }
    
    public function getCertifcateList(&$pObjUser)
    {
       // echo $pObjUser->getId()." ".$pObjUser->getCareer().">>> ".$pObjUser->getCurriculum();die;
        $result = $this->objServiceQuery->queryCertificateList($pObjUser->getId(), $pObjUser->getCareer(),$pObjUser->getCurriculum());
        $result2 = NULL;

        $catalogoAprobados = array();
        $catalogoCierre = array();

        if ($result != NULL) {
            $numReg1 = 0;
            $numReg2 = 0;
            $numReales = 0;
            foreach ($result as $curso) {
                
                if ($curso['idcourse'] != 743 and $curso['cycle'] < 11) {
                    $catalogoAprobados[$numReg1]['nomciclo'] = $curso['nombreciclo'];
                    $catalogoAprobados[$numReg1]['num'] = $numReg1 + 1;
                    $catalogoAprobados[$numReg1]['cod'] = $curso['curso'];
                    $catalogoAprobados[$numReg1]['nom'] = $curso['cursonombre'];
                    $catalogoAprobados[$numReg1]['nota'] = $curso['nota'];
                    $catalogoAprobados[$numReg1]['letras'] = $curso['letras'];
                    $catalogoAprobados[$numReg1]['fechaa'] = date("m", strtotime($curso['fechaaprobacion'])) . '-' . date("Y", strtotime($curso['fechaaprobacion']));
                    $catalogoAprobados[$numReg1]['desc'] = $curso['descripcion'];
                    $numReg1 += 1;
                    
                    if ($curso['descripcion']!='SUFICIENCIA' && $curso['descripcion']!='EQUIVALENCIA'  && $curso['descripcion']!='AUT. POR J.D.'){
                        $numReales += 1;
                    }
                    
                } else {
                    $catalogoCierre[$numReg2]['num'] = $numReg2 + 1;
                    $catalogoAprobados[$numReg1]['nomciclo'] = $curso['nombreciclo'];
                    $catalogoCierre[$numReg2]['cod'] = $curso['curso'];
                    $catalogoCierre[$numReg2]['nom'] = $curso['cursonombre'];
                    $catalogoCierre[$numReg2]['nota'] = $curso['nota'];
                    $catalogoCierre[$numReg2]['letras'] = $curso['letras'];
                    $catalogoCierre[$numReg2]['fechaa'] = date("m", strtotime($curso['approvaldate'])) . '-' . date("Y", strtotime($curso['approvaldate']));
                    $catalogoCierre[$numReg2]['desc'] = $curso['descripcion'];
                    $numReg2 += 1;
                }
            }
            unset($result);
        }
        
        $result2[] = $catalogoAprobados;
        $result2[] = $catalogoCierre;
        $reslut2[] = $numReales;
        return $result2;
    }

    public function getCierrePensum(&$pObjUser){
        $result = $this->objServiceQuery->queryCierrePensum($pObjUser->getId(), $pObjUser->getCareer(),$pObjUser->getCurriculum());
        $result2 = NULL;
        $catalogoAprobados = null;
        if ($result != NULL) {
             $numReg1 = 0;
           foreach ($result as $curso) {
                    $catalogoAprobados[$numReg1]['student'] = $curso['student'];
                    $catalogoAprobados[$numReg1]['carrera'] = $curso['carrera'];
                    $catalogoAprobados[$numReg1]['curso'] = $curso['curso'];
                    $catalogoAprobados[$numReg1]['aprobacion'] = $curso['aprobacion'];
                    
           }
           $result2[] = $catalogoAprobados;
           
        return $result2;
       }
    }
    
    public function getEps(&$pObjUser){
        $result = $this->objServiceQuery->queryEPS($pObjUser->getId(), $pObjUser->getCareer(),$pObjUser->getCurriculum());
        $catalogoAprobados = null;
        if ($result != NULL) {
             $numReg1 = 0;
           foreach ($result as $curso) {
                    $catalogoAprobados[$numReg1]['estudiante'] = $curso['estudiante'];
                    $catalogoAprobados[$numReg1]['aprobacion'] = $curso['aprobacion'];
                    $catalogoAprobados[$numReg1]['nota'] = $curso['nota'];
                    $catalogoAprobados[$numReg1]['letras'] = $curso['letras'];
                    $catalogoAprobados[$numReg1]['descripcion'] = $curso['descripcion'];
                    $catalogoAprobados[$numReg1]['fechain'] = $curso['fechain'];
                    $catalogoAprobados[$numReg1]['fechafn'] = $curso['fechafn'];
                    $catalogoAprobados[$numReg1]['lugarnom'] = $curso['lugarnom'];
                    $catalogoAprobados[$numReg1]['lugardir'] = $curso['lugardir'];
            }
        return $catalogoAprobados;
       }
        
    }
    
    public function getCourseListInfo(&$pObjUser)
    {
        return $this->objServiceQuery->queryAvgCourseList($pObjUser->getId(), $pObjUser->getCareer());
    }
    
    public function getSection($carnet,$idcurso)
    {
        $result=$this->objServiceQuery->querySection($carnet,$idcurso);
        return $result;
    }
    

    
    
    
	
    public function getSchoolyearOrder($ORDENPAGO)
    {
        $result=$this->objServiceQuery->querySchoolyearOrder($ORDENPAGO);
        return $result;
    }
	
	public function restaurarOrden($ORDENPAGO)
    {
        $result=$this->objServiceQuery->queryRestoreMaster($ORDENPAGO);
        if($result)
            $result=$this->objServiceQuery->queryRestoreSlave($ORDENPAGO);
        else
            return false;
        return $result;
    }
    
    public function searchOrdenWS($ORDENPAGO)
    {
        $result=$this->objServiceQuery->querySearchOrderWS($ORDENPAGO);
        return $result;
    }

    
    public function insertProcesoAsignacion($ORDENPAGO)
    {
        $result=$this->objServiceQuery->queryProcesoAsignacion($ORDENPAGO);
        return $result;
    }
	
	public function insertProcesoAsignacionVacas($ORDENPAGO)
    {
        $result=$this->objServiceQuery->queryProcesoAsignacionVacas($ORDENPAGO);
        return $result;
    }
      
    public function verifyPagoOrdenR1()
    {
        
    }   
    
    
	
	public function verifyInscripcion($idstudent, $year, $idcareer)
    {
        $result=$this->objServiceQuery->queryVerifyInscripcion($idstudent, $year, $idcareer);
        return $result;
    }
    //*******************FUNCIONES PARA GENERACIÓN DE BOLETA DE PRIMERA RETRASADA**************
    public function getActivationRetrasada1()
    {
        $result=$this->objServiceQuery->queryActivationRetrasada1();
        return $result;
    }
    //Metodo para obtener lista de cursos disponbbles para 1ra. retrasada.
    public function getCursosRetrasada1($carnet, $pAnio, $pPeriodo,$periodoActual,$anioSis,$carrera,$diasretra)
    {
        $result=$this->objServiceQuery->queryCursosRetrasada1($carnet, $pAnio, $pPeriodo, $periodoActual, $anioSis, $carrera, $diasretra);
        
        return $result;
    }
    public function searchOrdenR1($idstudent, $year, $idschoolyear)
    {
         $result=$this->objServiceQuery->queryBuscarOrdenR1($idstudent, $year, $idschoolyear);
         return $result;
    }
    public function searchOrdenPagadaR1($idstudent, $year, $idschoolyear,$carrera)
    {
         $result=$this->objServiceQuery->queryBuscarOrdenPagadaR1($idstudent, $year, $idschoolyear, $carrera);
         return $result;
    }
    public function searchDetalleOrdenR1($paymentorder)
    {
        $result=$this->objServiceQuery->queryBuscarDetOrdenR1($paymentorder);
        return $result;
    }
    //public function insertOrdenRetrasada1($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $idpersonal, $year, $idschoolyear, $complementorder, $rubro){
    public function insertOrdenRetrasada1($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $complementorder, $rubro){
        //$result=$this->objServiceQuery->queryOrdenRetrasada1($paymentorder, $amount, $verca, $verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $idpersonal, $year, $idschoolyear, $complementorder, $rubro);
        $result=$this->objServiceQuery->queryOrdenRetrasada1($paymentorder, $amount, $verca, $verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $complementorder, $rubro);
        return $result;
    }
    
    public function insertDetalleRetrasada1($paymentorder, $price, $inscriptionprice, $idcourse, $lab )
    {
        $result=$this->objServiceQuery->queryDetalleRetrasada1($paymentorder, $price, $inscriptionprice, $idcourse, $lab);
        return $result;
    }
    public function moveOrdenR1($paymentorder)
    {
        $result=$this->objServiceQuery->queryMoveOrdenR1($paymentorder);
        return $result;
    }
    //*******************FUNCIONES PARA GENERACIÓN DE BOLETA DE SEGUNDA RETRASADA**************
    public function getActivationRetrasada2()
    {
        $result=$this->objServiceQuery->queryActivationRetrasada2();
        return $result;
    }
    public function searchOrdenPagadaR2($idstudent, $year, $idschoolyear,$carrera)
    {
         $result=$this->objServiceQuery->queryBuscarOrdenPagadaR2($idstudent, $year, $idschoolyear, $carrera);
         return $result;
    }
    //Metodo para obtener lista de cursos disponbbles para 1ra. retrasada.
    public function getCursosRetrasada2($carnet, $pAnio, $pPeriodo,$periodoActual,$anioSis,$carrera,$diasretra)
    {
        $result=$this->objServiceQuery->queryCursosRetrasada2($carnet, $pAnio, $pPeriodo, $periodoActual, $anioSis, $carrera, $diasretra);
        
        return $result;
    }
    public function searchOrdenR2($idstudent, $year, $idschoolyear)
    {
         $result=$this->objServiceQuery->queryBuscarOrdenR2($idstudent, $year, $idschoolyear);
         return $result;
    }
    public function searchDetalleOrdenR2($paymentorder)
    {
        $result=$this->objServiceQuery->queryBuscarDetOrdenR2($paymentorder);
        return $result;
    }
    //public function insertOrdenRetrasada2($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $idpersonal, $year, $idschoolyear, $complementorder, $rubro){
    public function insertOrdenRetrasada2($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $complementorder, $rubro){    
        $result=$this->objServiceQuery->queryOrdenRetrasada2($paymentorder, $amount, $verca, $verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $complementorder, $rubro);
        return $result;
    }
    
    public function insertDetalleRetrasada2($paymentorder, $price, $inscriptionprice, $idcourse, $lab )
    {
        $result=$this->objServiceQuery->queryDetalleRetrasada2($paymentorder, $price, $inscriptionprice, $idcourse, $lab);
        return $result;
    }
    public function moveOrdenR2($paymentorder)
    {
        $result=$this->objServiceQuery->queryMoveOrdenR2($paymentorder);
        return $result;
    }
    
    // ******************** FUNCIONES PARA MODULO DE VACACIONES *******************
    public function getActivationVacaciones(){
        $result=$this->objServiceQuery->queryActivationVacaciones();
        return $result;
    }
    
    //Metodo para obtener lista de cursos disponbbles para 1ra. retrasada.
    public function getCursosVacaciones($pstudent, $pcarrera, $pperiodosis,$aniosis, $periodoEnviar, $anioEnviar)
    {
        $result=$this->objServiceQuery->queryCursosVacaciones($pstudent, $pcarrera, $pperiodosis,$aniosis, $periodoEnviar, $anioEnviar);   
        return $result;
    }
    
    public function insertOrdenVacaciones($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $complementorder, $rubro){
        //$result=$this->objServiceQuery->queryOrdenRetrasada1($paymentorder, $amount, $verca, $verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $idpersonal, $year, $idschoolyear, $complementorder, $rubro);
        $result=$this->objServiceQuery->queryOrdenVacaciones($paymentorder, $amount, $verca, $verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $complementorder, $rubro);
        return $result;
    }
    
    public function insertDetalleVacaciones($paymentorder, $price, $inscriptionprice, $idcourse, $lab )
    {
        $result=$this->objServiceQuery->queryDetalleVacaciones($paymentorder, $price, $inscriptionprice, $idcourse, $lab);
        return $result;
    }
    public function searchOrdenV($idstudent, $year, $idschoolyear)
    {
         $result=$this->objServiceQuery->queryBuscarOrdenV($idstudent, $year, $idschoolyear);
         return $result;
    }
    public function searchDetalleOrdenV($paymentorder)
    {
        $result=$this->objServiceQuery->queryBuscarDetOrdenV($paymentorder);
        return $result;
    }
    public function moveOrdenV($paymentorder)
    {
        $result=$this->objServiceQuery->queryMoveOrdenV($paymentorder);
        return $result;
    }
}
?>