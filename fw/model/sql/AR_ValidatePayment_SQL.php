<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 17/05/2015
 * Time: 01:17 PM
 */
include_once("General_SQL.php");
/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */

Class AR_ValidatePayment_SQL extends General_SQL{

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  valida_pagorealizados
     * @ref #1
     * @línea   #43
     */
    function valida_pagorealizados_select1($carnet, $_TIPO_PAGO_PRIMERA_RETRASADA,$_TIPO_PAGO_SEGUNDA_RETRASADA,$anio, $periodo,$carrera){
        return sprintf(" SELECT amount,verws,verca,paymentorder,paymentorderdate
                                 FROM tbcoursepayment
                                WHERE idstudent='%s' AND idpaymenttype IN ('%s','%s') AND year = '%s' AND idschoolyear = '%s'
								  AND (verws = 1 OR verca = 1) and idcareer=%d
								ORDER BY paymentorder,paymentorderdate;
                        ",$carnet,$_TIPO_PAGO_PRIMERA_RETRASADA,$_TIPO_PAGO_SEGUNDA_RETRASADA,$anio,$periodo,$carrera);
    }

}
?>