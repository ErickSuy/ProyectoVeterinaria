<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 20/10/2014
 * Time: 06:33 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/sql/Transaction.php");
include_once("$dir_portal/fw/model/sql/AssignationParamHandler_SQL.php");
include_once("$dir_portal/fw/model/sql/AssignationGeneralsManager_SQL.php");

define("ASIGNACIONREGULAR", 10);

class AssignationGeneralsManager
{
    var $mUsuario;
    var $mCarrera;
    var $mTxtCarrera;
    var $mPensum;
    var $mTransaccion;
    var $mFechaAsig;
    var $mFechaIns;
    var $mPeriodo;
    var $mAnio;

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;

    /* Constructor */
    function AssignationGeneralsManager($pUser, $pCareer, $pSchoolYear, $pYear)
    {
        $this->mUsuario = $pUser;
        $this->mCarrera = $pCareer;
        $this->mPeriodo = $pSchoolYear;
        $this->mAnio = $pYear;

        $_SESSION["sConAsig"] = NEW DB_Connection();
        $_SESSION["sConAsig"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new AssignationGeneralsManager_SQL();
    }

    function VerNotasCursosAsignados($periodo, $anio)
    {
        $Trans = new Transaction();

        $qryAsignados = $this->gsql->VerNotasCursosAsignados_select1($this->mUsuario, $this->mCarrera, $periodo, $anio);

        if ($_SESSION["sConAsig"]->query($qryAsignados)) {
            if ($_SESSION["sConAsig"]->num_rows() > 0) {
                $_SESSION["sConAsig"]->next_record();
                $this->mUsuario = trim($_SESSION["sConAsig"]->f("idstudent"));
                $this->mNombre = trim($_SESSION["sConAsig"]->f("name"));
                $this->mApellido = trim($_SESSION["sConAsig"]->f("surname"));
                $this->mCarrera = trim($_SESSION["sConAsig"]->f("idcareer"));
                switch ($this->mCarrera) {
                    case VETERINARIA:
                        $this->mTxtCarrera = "[02] MEDICINA VETERINARIA";
                        break;
                    case ZOOTECNIA:
                        $this->mTxtCarrera = "[03] ZOOTECNIA";
                        break;
                }
                $this->mTransaccion = trim($_SESSION["sConAsig"]->f("idassignation"));
                $this->mFechaAsig = trim($_SESSION["sConAsig"]->f("assignationdate"));

                $info[0] = Date("d-m-Y");
                $info[1] = Date("H:i");
                $info[2] = $this->mNombre;
                $info[3] = $this->mApellido;
                $info[5] = $this->mUsuario;
                $info[6] = $this->mCarrera;
                $info[7] = $this->mTxtCarrera;
                $info[8] = $this->mFechaAsig;
                $info[9] = $Trans->Encriptar(1, $this->mUsuario . "e" . $this->mTransaccion . "e" . $this->mPeriodo . "e" . $this->mAnio, 1);
                unset($Trans);
                return $info;
            }
        }
        unset($Trans);
        return false;
    }

    function VerDetalleNotas($periodo, $anio, &$cursosConLab)
    {
        $qryAsignados = $this->gsql->VerDetalleNotas_select0($this->mTransaccion, $periodo, $anio);
        if ($_SESSION["sConAsig"]->query($qryAsignados) and ($_SESSION["sConAsig"]->num_rows() > 0)) {
            for ($pos = 1; $pos <= $_SESSION["sConAsig"]->num_rows(); $pos++) {
                $_SESSION["sConAsig"]->next_record();
                $codigo = trim($_SESSION["sConAsig"]->f("idcourse"));
                $index = trim($_SESSION["sConAsig"]->f("index"));
                $cursosConLab[$codigo] = $_SESSION["sConAsig"]->f("idscheduletype");
            }
        }

        $qryAsignados = $this->gsql->VerDetalleNotas_select1($this->mUsuario,$this->mCarrera,$this->mPensum, $periodo, $anio);
        if ($_SESSION["sConAsig"]->query($qryAsignados) and ($_SESSION["sConAsig"]->num_rows() > 0)) {
            for ($pos = 1; $pos <= $_SESSION["sConAsig"]->num_rows(); $pos++) {
                $_SESSION["sConAsig"]->next_record();
                $horario[$pos]["cur"] = trim($_SESSION["sConAsig"]->f("r_course"));
                $horario[$pos]["ind"] = trim($_SESSION["sConAsig"]->f("r_index"));
                $horario[$pos]["nom"] = trim($_SESSION["sConAsig"]->f("r_name"));
                $horario[$pos]["sec"] = trim($_SESSION["sConAsig"]->f("r_section"));
                $horario[$pos]["lab"] = trim((int)($_SESSION["sConAsig"]->f("r_labnote")));
                $horario[$pos]["zon"] = trim((int)($_SESSION["sConAsig"]->f("r_classzone")));
                $horario[$pos]["exa"] = (int)($_SESSION["sConAsig"]->f("r_notefinalexam"));
                $horario[$pos]["eef"] = $_SESSION["sConAsig"]->f("r_idfinalexamstate");
                $horario[$pos]["est"] = trim($_SESSION["sConAsig"]->f("r_idactstate"));
                $horario[$pos]["tip"] = trim($_SESSION["sConAsig"]->f("r_acttype"));
                $horario[$pos]["esc"] = trim($_SESSION["sConAsig"]->f("r_idschool"));
                $horario[$pos]['car'] = trim($_SESSION["sConAsig"]->f("r_idcareer"));
            }
            return $horario;
        }
        return false;
    }

    function VerListadoCursosAsignados($periodo, $anio)
    {
        $Trans = new Transaction();

        $qryAsignados = $this->gsql->VerListadoCursosAsignados_select1($this->mUsuario, $this->mCarrera, $periodo, $anio);

        if ($_SESSION["sConAsig"]->query($qryAsignados)) {
            if ($_SESSION["sConAsig"]->num_rows() > 0) {   //|| $_SESSION["sConIns"]->num_rows()<1
                $_SESSION["sConAsig"]->next_record();
                $this->mUsuario = trim($_SESSION["sConAsig"]->f("idstudent"));
                $this->mNombre = trim($_SESSION["sConAsig"]->f("name"));
                $this->mApellido = trim($_SESSION["sConAsig"]->f("surname"));
                $this->mCarrera = trim($_SESSION["sConAsig"]->f("idcareer"));
                switch ($this->mCarrera) {
                    case VETERINARIA:
                        $this->mTxtCarrera = "[02] MEDICINA VETERINARIA";
                        break;
                    case ZOOTECNIA:
                        $this->mTxtCarrera = "[03] ZOOTECNIA";
                        break;
                }
                $this->mTransaccion = trim($_SESSION["sConAsig"]->f("idassignation"));
                $this->mFechaAsig = trim($_SESSION["sConAsig"]->f("assignationdate"));

                $info[0] = Date("d-m-Y");
                $info[1] = Date("H:i");
                $info[2] = $this->mNombre;
                $info[3] = $this->mApellido;
                $info[5] = $this->mUsuario;
                $info[6] = $this->mCarrera;
                $info[7] = $this->mTxtCarrera;
                $info[8] = date("d-m-Y", strtotime($this->mFechaAsig));
                $info[9] = $Trans->Encriptar(1, '' . $this->mUsuario . "e" . $this->mTransaccion . "e" . '' . $periodo . "e" . '' . $anio, 1);

                unset($Trans);
                return $info;
            }
        }
        unset($Trans);
        return false;
    }

    function VerDetalleAsig()
    {
        $qryAsignados = $this->gsql->VerDetalleAsig_select1($this->mTransaccion);

        if ($_SESSION["sConAsig"]->query($qryAsignados) and ($_SESSION["sConAsig"]->num_rows() > 0)) {
            for ($pos = 1; $pos <= $_SESSION["sConAsig"]->num_rows(); $pos++) {
                $_SESSION["sConAsig"]->next_record();
                $horario[$pos]["cur"] = trim($_SESSION["sConAsig"]->f("idcourse"));
                $horario[$pos]["nom"] = trim($_SESSION["sConAsig"]->f("name"));
                $horario[$pos]["sec"] = trim($_SESSION["sConAsig"]->f("section"));
                $horario[$pos]["ini"] = trim($_SESSION["sConAsig"]->f("starttime"));
                $horario[$pos]["fin"] = trim($_SESSION["sConAsig"]->f("endtime"));
                $horario[$pos]["lu"] = $_SESSION["sConAsig"]->f("mon");
                $horario[$pos]["ma"] = $_SESSION["sConAsig"]->f("tue");
                $horario[$pos]["mi"] = $_SESSION["sConAsig"]->f("wed");
                $horario[$pos]["ju"] = $_SESSION["sConAsig"]->f("thu");
                $horario[$pos]["vi"] = $_SESSION["sConAsig"]->f("fri");
                $horario[$pos]["sa"] = $_SESSION["sConAsig"]->f("sat");
                $horario[$pos]["do"] = $_SESSION["sConAsig"]->f("sun");
                $horario[$pos]["sal"] = trim($_SESSION["sConAsig"]->f("idclassroom"));
                $horario[$pos]["edi"] = trim($_SESSION["sConAsig"]->f("building"));
                $horario[$pos]["tip"] = trim($_SESSION["sConAsig"]->f("idscheduletype"));
            }
            //print_r($horario);die;
            return $horario;
        }
        return false;
    }

    public function getAssignationDetailInfo($pStudent, $pCareer, $pCurriculum, $year, $schoolYear)
    {
        $objServiceQuery = new AssignationParamHandler_SQL();
        $info = NUll;

        $result = $objServiceQuery->queryGetAssignationDetailInfo($pStudent, $pCareer, $pCurriculum, $year, $schoolYear);

        if ($result != NULL AND count($result) > 0) {
            $pos = 1;
            foreach ($result as $cursoAsignado) {
                $horario[$pos]["cur"] = trim($cursoAsignado['course']);
                $horario[$pos]["nom"] = trim($cursoAsignado['name']);
                $horario[$pos]["sec"] = trim($cursoAsignado['section']);
                $horario[$pos]["ini"] = trim($cursoAsignado['starttime']);
                $horario[$pos]["fin"] = trim($cursoAsignado['endtime']);
                $horario[$pos]["lu"] = $cursoAsignado['mon'] ? 'X' : '-';
                $horario[$pos]["ma"] = $cursoAsignado['tue'] ? 'X' : '-';
                $horario[$pos]["mi"] = $cursoAsignado['wed'] ? 'X' : '-';
                $horario[$pos]["ju"] = $cursoAsignado['thu'] ? 'X' : '-';
                $horario[$pos]["vi"] = $cursoAsignado['fri'] ? 'X' : '-';
                $horario[$pos]["sa"] = $cursoAsignado['sat'] ? 'X' : '-';
                $horario[$pos]["do"] = $cursoAsignado['sun'] ? 'X' : '-';
                $horario[$pos]["sal"] = trim($cursoAsignado['classroom']);
                $horario[$pos]["edi"] = trim($cursoAsignado['building']);
                $horario[$pos]["tip"] = trim($cursoAsignado['scheduletype']);
                $pos++;
            }
            return $horario;
        }

        unset($objServiceQuery);
        return false;
    }

    function VerCursosAsignados()
    {
        $qryAsignados = $this->gsql->VerCursosAsignados_select1($this->mTransaccion, $this->mFechaAsig, $this->mPensum, $this->mCarrera);

        if ($_SESSION["sConAsig"]->query($qryAsignados) and ($_SESSION["sConAsig"]->num_rows() > 0)) {
            for ($pos = 1; $pos <= $_SESSION["sConAsig"]->num_rows(); $pos++) {
                $_SESSION["sConAsig"]->next_record();
                $curso[$pos]["curso"] = trim($_SESSION["sConAsig"]->f("curso"));
                $curso[$pos]["seccion"] = trim($_SESSION["sConAsig"]->f("seccion"));
                $curso[$pos]["nombre"] = trim($_SESSION["sConAsig"]->f("nombre"));
                $curso[$pos]["retra"] = ($_SESSION["sConAsig"]->f("problema") == 2) ? sprintf(trim($_SESSION["sConAsig"]->f("curso"))) : sprintf("");
                $curso[$pos]["creditos"] = trim($_SESSION["sConAsig"]->f("creditos"));
                $curso[$pos]["cursoError"] = trim($_SESSION["sConAsig"]->f("problema"));
                $curso[$pos]["prerrequisito"] = trim($_SESSION["sConAsig"]->f("prerrequisito"));
            }
            return $curso;
        }
        return false;
    }
}

?>

