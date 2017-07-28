<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 17/05/2015
 * Time: 01:15 PM
 */

include_once("../../path.inc.php");
require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/sql/AR_ValidatePayment_SQL.php");

class AR_ValidatePayment
{
    var $conexion;
    var $usuarioid;
    var $carnet;
    var $periodo;
    var $anio;
    /*
    * Variable para utilizar las consultas
    */
    var $gsql;

    public function AR_ValidatePayment($usuarioid,$carrera,$periodo,$anio)
    { // Se realiza una conexion con Servidor de Base de datos
        $this->conexion = NEW DB_Connection;
        $this->conexion->connect();

        /*
        * Instanciando la variable en la clase donde se encuentran las consultas
        */
        $this->gsql = new AR_ValidatePayment_SQL();


        $this->periodo = $periodo;
        $this->anio = $anio;
        $this->carnet = $usuarioid;
        $this->carrera = $carrera;

    }

    function valida_pagorealizados_retrasadas()
    {
        $consulta = $this->gsql->valida_pagorealizados_select1($this->carnet, _TIPO_PAGO_PRIMERA_RETRASADA, _TIPO_PAGO_SEGUNDA_RETRASADA, $this->anio, $this->periodo,$this->carrera);
        if (($this->conexion->query($consulta)) AND ($this->conexion->num_rows() > 0)) // verifica la consulta
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>

