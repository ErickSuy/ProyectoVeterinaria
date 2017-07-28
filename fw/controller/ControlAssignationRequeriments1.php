<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 21/08/14
 * Time: 02:33 PM
 */

include_once("../../path.inc.php");
include_once("Control.php");
include_once("ControlService.php");
include_once("$dir_portal/fw/model/sql/ControlAssignationRequeriments1_SQL.php");
include_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/view/RegisterBLog.php");
include_once("/var/www/fw/controller/manager/consumidorWS_RyE.php");

define("PASO1_ASIGNACION_CURSOS",151);

class ControlAssignationRequeriments1 extends Control
{
    private $objServiceQuery;

    public function ControlAssignationRequeriments1()
    {
        $this->objServiceQuery = new ControlAssignationRequeriments1_SQL();
    }

    public function checkProcessAssignationSem($pAnio, $pPeriodo1, $pPeriodo2, $pProceso, $pFecha, &$pUser, &$pAssignationMngmt)
    {
        $rresult[] = array();
        $result1[] = array('result' => 0, 'msg' => '');
        $result2[] = array('result' => 0, 'msg' => '');
        $result3[] = array('result' => 0, 'msg' => '');

        $result1 = $this->objServiceQuery->queryProcessInformation($pAnio, $pPeriodo1, $pPeriodo2, $pProceso, $pFecha);
/*
"200619755,201119678,8816786,200410530,201119679,201513804,201112197,201115035,201513358,200310734,200715577,201516923,201407994,201318297,201210909,200722625,9211981,200918241,200715598,200817684,200121049,200211384,201515481,201220345,200313790,200715455,200516160"
 if(substr_count("9410032",$pUser->getId())==0) {
            $rresult[0] = array('result' => FAIL, 'msg' => '<input type="submit" name="btnSubmit" onclick="Cancelar();" value="Cancelar" class="datos">');
            $rresult[1]  = array('result' => FAIL, 'msg' => 'SIN ACCESO AL PROCESO ');
            return $rresult;
        }
*/

        
        if ($result1[0]['result'] == OK) {
            $rresult[1]  = array('result' => OK, 'msg' => 'SISTEMA ACTIVO PARA ASIGNACIÓN  DE CURSOS DEL ' . $result1[0]['msg']);

            $pAssignationMngmt->setSchoolYear($result1[0]['schoolyear']);
            $result2 = $this->objServiceQuery->queryCheckEnrollment($pUser->getId(), $pUser->getCareer(), $pAnio);
            $id = $pUser->getId();
            $car = $pUser->getCareer();
            
            if($result2 != OK){
             //   $ryews = new RyEWS();
              //  agregar_inscripcion($id, $car, $pAnio);
               $respuesta = consultar_inscrito($id, $car, $pAnio);
              // PRINT_R( $respuesta);DIE;
               if ($respuesta!='NO_INSCRITO'){
                   $this->objServiceQuery->queryAddEnrollment($respuesta,$car,$pAnio);
                $result2 = $this->objServiceQuery->queryCheckEnrollment($pUser->getId(), $pUser->getCareer(), $pAnio);
               
               }
                
                
                }
            
            
            
            if ($result2[0]['result'] == OK) {
                $pUser->setCurriculum($result2[0]['curriculum']);
                $pUser->setEnrollmentDate($result2[0]['enrollmentdate']);

                $rresult[2]  = array('result' => OK, 'msg' => $result2[0]['msg']);

                $result3 = $this->objServiceQuery->queryCheckAccessSite($pUser->getId(), $pUser->getGroup(), SITIO_ASIGNACIONREGULAR, $pUser->getCareer(), $pAnio, $result1[0]['schoolyear']);

                if ($result3[0]['result'] == OK) {
                    $rresult[3]  = array('result' => OK, 'msg' => $result3[0]['msg']);
                } else {
                    $rresult[3]  = array('result' => FAIL, 'msg' => $result3[0]['msg']);
                }
            } else {
                $rresult[2]  = array('result' => FAIL, 'msg' => $result2[0]['msg']);
            }
        } else {
            $rresult[1]  = array('result' => FAIL, 'msg' => $result1[0]['msg']);
        }

        if ($result1[0]['result'] AND $result2[0]['result'] AND $result3[0]['result']) {
            $rresult[0] = array('result' => OK, 'msg' => '<input type="submit" name="btnSubmit" onclick="Siguiente();"  value="Siguiente" class="datos">');

            $obtRLog = new RegisterBLog();
            $obtRLog->DarSitio(PASO1_ASIGNACION_CURSOS);
            $obtRLog->RegistroNavegacion($pUser->getId(),$pUser->getGroup(),0);
            unset($obtRLog);
        } else {
            $rresult[0] = array('result' => FAIL, 'msg' => '<input type="submit" name="btnSubmit" onclick="Cancelar();" value="Cancelar" class="datos">');
        }

        return $rresult;
    }

    public function getScheduleInformation($pAnio, $pPeriodo, $pPensum, $pCarrera, $pTipo)
    {
        return $this->objServiceQuery->queryScheduleInformation($pAnio, $pPeriodo, $pPensum, $pCarrera, $pTipo);
    }

    public function getAssignation($pEstudiante, $pCarrera, $pPensum, $pAnio, $pPeriodo)
    {
        return $this->objServiceQuery->queryCheckAssignation($pEstudiante, $pCarrera, $pPensum, $pAnio, $pPeriodo);
    }
}

?>