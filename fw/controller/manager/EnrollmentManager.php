<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 31/10/2014
 * Time: 07:44 PM
 */

/*******************************************************
 * Registrar los resultados de la inscripción de estudiantes
 * Cambio en el estatus del estudiante a inscrito
 * Inserción de datos en bitacoras
 ********************************************************/

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/sql/Transaction.php");

/*
 * Incluyendo archivo con sentencias SQL
 */
include_once("$dir_portal/fw/model/sql/EnrollmentManager_SQL.php");

class EnrollmentManager
{
    var $mUsuario;
    var $mNombre;
    var $mApellido;
    var $mCarrera;
    var $mTxtCarrera;
    var $mTransaccion;
    var $mFechains;
    var $mCiclo;

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;


    function EnrollmentManager($pUser, $pCareer)
    {
        $this->mUsuario = $pUser;
        $this->mCarrera = $pCareer;
        $this->mFechains = strftime("%Y-%m-%d %H:%M:%S.0");
        $this->mCiclo = CICLO;
        // Se realiza una conexion con Servidor de Base de datos
        $_SESSION["sConIns"] = NEW DB_Connection();
        $_SESSION["sConIns"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new EnrollmentManager_SQL();
    }

    function Encriptar($accion, $string, $key = "1")
    {
        $result = '';
        $keychar = ord($key);
        for ($i = 1; $i <= strlen($string); $i++) {
            $char = ord(substr($string, $i - 1, 1));
            if ($accion) {
                $op = $char + $keychar;
                if ($op > 255)
                    $op = $op - 255;
            } else {
                $op = $char - $keychar;
                if ($op < 0)
                    $op = $op + 255;
            }
            $result .= chr($op);
        }
        return $result;
    }

    function VerHistorialInscripcion()
    {
        $qryInscrito = $this->gsql->VerHistorialInscripcion_select1($this->mUsuario, $this->mCarrera);
        if ($_SESSION["sConIns"]->query($qryInscrito)) {
            for ($i = 0; $i < $_SESSION["sConIns"]->num_rows(); $i++) {
                $_SESSION["sConIns"]->next_record();
                $this->mUsuario = trim($_SESSION["sConIns"]->f("idstudent"));
                $this->mNombre = trim($_SESSION["sConIns"]->f("namee"));
                $this->mApellido = trim($_SESSION["sConIns"]->f("surname"));
                $this->mCarrera = trim($_SESSION["sConIns"]->f("idcareer"));
                $this->mTxtCarrera = '[0' . $this->mCarrera . '] ' . trim($_SESSION["sConIns"]->f("namec"));
                $this->mPensum = trim($_SESSION["sConIns"]->f("idcurriculum"));
                $this->mFechains = trim($_SESSION["sConIns"]->f("enrollmentdate"));
                $this->mCiclo = trim($_SESSION["sConIns"]->f("year"));
                $this->mTransaccion = 0;

                $Trans = new Transaction();
                $info[$i][0] = Date("d-m-Y");
                $info[$i][1] = Date("H:i");
                $info[$i][2] = $this->mCiclo;
                $info[$i][3] = $this->mNombre;
                $info[$i][4] = $this->mApellido;
                $info[$i][5] = $this->mUsuario;
                $info[$i][6] = $this->mCarrera;
                $info[$i][7] = $this->mTxtCarrera;
                $info[$i][8] = $this->mFechains;
                $info[$i][9] = $Trans->Encriptar(1, $this->mUsuario . "e" . $this->mCiclo . "e" . $this->mTransaccion, 1);
                unset($Trans);
            }
            return $info;
        }
        return false;
    }

}  // fin RegistroInscripcion
?>