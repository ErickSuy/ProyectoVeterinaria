<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 21/09/14
 * Time: 04:50 AM
 */

require_once("../../path.inc.php");
require_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");
require_once("$dir_portal/fw/model/sql/RegisterCourseAssignation_SQL.php");
require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/mapping/TbUser.php");
require_once("$dir_portal/fw/model/DB_Connection.php");
require_once("$dir_portal/fw/model/sql/Transaction.php");

class RegisterCourseAssignation
{
    private $mCursosIns;
    private $numCurAsignar;
    private $anio;
    private $periodo;
    private $usuarioid;
    private $carrera;
    private $fechains;
    private $fechaAsigna;
    private $pensum;

    private $mTransaccion;

    private $gsql;


    public function RegisterCourseAssignation($pUser, &$pAssigParamHandler)
    {
        $this->anio = $pAssigParamHandler->getYear();
        $this->periodo = $pAssigParamHandler->getSchoolYear();
        $this->mCursosIns = $pAssigParamHandler->getAssignationSelected();
        $this->numCurAsignar = count($this->mCursosIns);
        $this->usuarioid = $pUser->getId();
        $this->carrera = $pUser->getCareer();
        $this->pensum = $pUser->getCurriculum();
        $this->fechains = $pUser->getEnrollmentDate();

        $pAssigParamHandler->setAssignationDate(strftime("%Y-%m-%d"));

        $this->fechaAsigna = $pAssigParamHandler->getAssignationDate();

        $_SESSION["sConAsigIns"] = NEW DB_Connection();
        $_SESSION["sConAsigIns"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new RegisterCourseAssignation_SQL();
    }

    private function registerAssignation()
    {
        $okTransaccion = OK;

        if (!$_SESSION["sConAsigIns"]->query($this->gsql->begin())) {
            $okTransaccion = FAIL;
        }

        $Trans = new Transaction();
        $this->mTransaccion = $Trans->ObtenerTransaccion();
        // verifica si existe una asignación anterior
        $consulta = $this->gsql->funInstertarAsignacion_select1($this->usuarioid, $this->periodo, $this->anio, $this->carrera);
        if (($_SESSION["sConAsigIns"]->query($consulta)) AND ($_SESSION["sConAsigIns"]->num_rows() > 0)) {
            $_SESSION["sConAsigIns"]->next_record();
            $transaccionActual = $_SESSION["sConAsigIns"]->f('idassignation');
            $fechaAsignacionActual = $_SESSION["sConAsigIns"]->f('assignationdate');

            $consulta = $this->gsql->funInstertarAsignacion_delete1($fechaAsignacionActual, $transaccionActual);

            if (!$_SESSION["sConAsigIns"]->query($consulta)) {
                $okTransaccion = FAIL;
            } else {
                $consulta = $this->gsql->funInstertarAsignacion_delete2($fechaAsignacionActual, $transaccionActual);

                if (!$_SESSION["sConAsigIns"]->query($consulta)) {
                    $okTransaccion = FAIL;
                }
            }
        }
        if ($okTransaccion == OK) {
            $querycarnet = $this->gsql->funInstertarAsignacion_insert1($this->mTransaccion, $this->fechaAsigna, $this->usuarioid, $this->carrera, $this->fechains);

            if (!$_SESSION["sConAsigIns"]->query($querycarnet)) {
                $okTransaccion = FAIL;
            }
            $horaAsignacion = strftime("%H:%M:%S");

            $querycarnet = $this->gsql->funInstertarAsignacion_insert2($this->mTransaccion, $this->fechaAsigna, $this->usuarioid, $this->carrera, $horaAsignacion);

            if (!$_SESSION["sConAsigIns"]->query($querycarnet)) {
                $okTransaccion = FAIL;
            }

            for ($pos = 1; $pos <= $this->numCurAsignar; $pos++) // ciclo para poder asignar los cursos
            {
                if ((strcmp($this->mCursosIns[$pos]['course'], "") != 0)) {
                    $querycarnet = $this->gsql->funInstertarAsignacion_insert3($this->mTransaccion, $this->mCursosIns[$pos]['course'], $this->mCursosIns[$pos]['section'], $this->periodo, $this->anio, $this->mCursosIns[$pos]['cindex'],$this->mCursosIns[$pos]['labgroup']);
                    if (!$_SESSION["sConAsigIns"]->query($querycarnet)) {
                        $okTransaccion = FAIL;
                    } //No pudo insertar en asignacion en el detalle

                    // inserta hacia la tabla de auditoria de asignacion
                    $querycarnet = $this->gsql->funInstertarAsignacion_insert4($this->mTransaccion, $this->fechaAsigna, $this->mCursosIns[$pos]['course'], $this->mCursosIns[$pos]['section'], $this->periodo, $this->anio, '',$this->mCursosIns[$pos]['cindex'],$this->mCursosIns[$pos]['labgroup']);

                    if (!$_SESSION["sConAsigIns"]->query($querycarnet)) {
                        $okTransaccion = FAIL;
                    } //No pudo insertar en AUDITORIA DETALLE DE ASIGNACION
                } // fin de comparacion del if de espacio en blanco
            } // fin del for donde se toma en cuanto todos los cursos a asignarse
        }
        // aqui tiene que ir la actualizacion de la cantidad de veces que se asigna un estudiante
        if ($okTransaccion == OK) {
            $querycarnet = sprintf($this->gsql->commit());
        } else {
            $querycarnet = sprintf($this->gsql->rollback());
        }

        if (!$_SESSION["sConAsigIns"]->query($querycarnet)) { }

        // finaliza la transacción
        if (!$_SESSION["sConAsigIns"]->query($this->gsql->end())) { }
        return $okTransaccion; // retorno
    }

    public function registrationProcess()
    {
        return $this->registerAssignation();
    }
}

?>