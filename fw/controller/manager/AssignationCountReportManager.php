<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 30/10/2014
 * Time: 08:58 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");

/*
 * Incluyendo archivo con sentencias SQL
 */
include_once("$dir_portal/fw/model/sql/AssignationCountReportManager_SQL.php");

Class AssignationCountReportManager
{
    var $mUsuario;
    var $mNombre;
    var $mPensum;
    var $mApellido;
    var $mtipoConsulta;
    var $posVector;
    var $mCarrera;

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;

//   var $mSeccion;

    /* Constructor */
    function AssignationCountReportManager($pUser,$pCareer, $pPensum)
    {
        $this->mUsuario = $pUser;
        $this->mCarrera = $pCareer;
        if($pPensum==NULL or $pPensum=-1) {
            if($this->mCarrera==VETERINARIA) $this->mPensum = 2;
            if($this->mCarrera==ZOOTECNIA) $this->mPensum = 4;
        }
        
        // Se realiza una conexion con Servidor de Base de datos
        $_SESSION["sConAsig"] = NEW DB_Connection();
        $_SESSION["sConAsig"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new AssignationCountReportManager_SQL();
    }


    function busquedaCurso($curso)
    {
        for ($pos = 0; $pos <= $this->posVector; $pos++) {
            if (strcmp($this->mVectorCurso['curso'][$pos], $curso) == 0) {
                $retorno = $pos;
                break;
            } else {
                $retorno = -1;
            }
        }
        return $retorno;
    }

    function  numeroVeces()
    {
        $query = '';

        if (($this->mtipoConsulta == -1) OR ($this->mtipoConsulta == 1)) {
            $query = $this->gsql->numeroVeces_select1_12($this->mUsuario,$this->mPensum);
        }

        if (($_SESSION["sConAsig"]->query($query)) and ($_SESSION["sConAsig"]->num_rows() > 0)) {
            for ($pos = 0; $pos < $_SESSION["sConAsig"]->num_rows(); $pos++) {
                $_SESSION["sConAsig"]->next_record();
                $this->mVectorCurso['curso'][$pos] = $_SESSION["sConAsig"]->f("curso");
                $this->mVectorCurso['nombre'][$pos] = $_SESSION["sConAsig"]->f("nombrec");
                $this->mVectorCurso['semestre'][$pos] = $_SESSION["sConAsig"]->f("conteo");
                $this->mVectorCurso['vacaciones'][$pos] = 0;
                $this->mNombre = $_SESSION["sConAsig"]->f("nombree");
                $this->mApellido = $_SESSION["sConAsig"]->f("apellido");
            } // fin del for
            $this->posVector = $pos;
        } else {
            $this->posVector = $pos;
        }


        if (($this->mtipoConsulta == -1) OR ($this->mtipoConsulta == 2)) {
            $query = $this->gsql->numeroVeces_select1_13($this->mUsuario,$this->mPensum);
        }

        //echo $query;
//printf("%s<br>",$query);
        if (($_SESSION["sConAsig"]->query($query)) and ($_SESSION["sConAsig"]->num_rows() > 0)) {
            for ($pos = 0; $pos < $_SESSION["sConAsig"]->num_rows(); $pos++) {
                $_SESSION["sConAsig"]->next_record();
                $posicion = $this->busquedaCurso($_SESSION["sConAsig"]->f("curso"));
//printf("posicion %d<br>",$posicion);
                if ($posicion == -1) {
                    $this->posVector = $this->posVector + $pos;
                    $this->mVectorCurso['curso'][$this->posVector] = $_SESSION["sConAsig"]->f("curso");
                    $this->mVectorCurso['nombre'][$this->posVector] = $_SESSION["sConAsig"]->f("nombrec");
                    $this->mVectorCurso['vacaciones'][$this->posVector] = $_SESSION["sConAsig"]->f("conteo");
                    $this->mVectorCurso['semestre'][$this->posVector] = 0;
                    $this->mNombre = $_SESSION["sConAsig"]->f("nombree");
                    $this->mApellido = $_SESSION["sConAsig"]->f("apellido");
//printf("(%d)%s...%s..(%d)..%d<br>",$this->posVector,$this->mVectorCurso['curso'][$this->posVector],$this->mVectorCurso['nombre'][$this->posVector],$this->mVectorCurso['vacaciones'][$this->posVector],$this->mVectorCurso['semestre'][$this->posVector]);
                } else {
                    $this->mVectorCurso['vacaciones'][$posicion] = $_SESSION["sConAsig"]->f("conteo");
                    if ($this->mVectorCurso['semestre'][$this->posVector] == 0) {
                        $this->mVectorCurso['semestre'][$this->posVector] = 0;
                    }
                }
            } // fin del for
        }
        //else
        //{MENSAJE DE ERROR}
    } // fin de la funcion numero de veces (numeroVeces)
}

?>