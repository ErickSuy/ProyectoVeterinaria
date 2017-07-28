<?php
/**
 *@origen biblioteca/class.transaccion.inc.php
 */
include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */

Class Transaction_SQL extends General_SQL{

    /**
     * begin en @línea   #46
     */

    /**
     * @cambios
     * @functionOrigen  ObtenerTransaccion
     * @ref #1
     * @línea   #55
     */
    function ObtenerTransaccion_select1(){
        return sprintf("select nextval('numtransaccion') for update;");
    }

}

?>
