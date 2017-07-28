<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 01/11/2014
 * Time: 12:08 PM
 */

include_once("General_SQL.php");
/**
 *
 * PostgreSQL @version 9.0
 */
Class CourseSchedule_SQL extends General_SQL {

    /**
     * @cambios
     * @functionOrigen
     * @ref #1
     * @línea   #52
     */
    function _select1($proceso,$periodo,$anio){
        return sprintf("select startdate::date,enddate::date,schoolyear,year,state
            from tbproc_processactivation where
            state  = 1    and
            idprocess = %d and
            schoolyear = %d and
            year    = %d;",$proceso,$periodo,$anio);
    }


}
?>