<?php
/*****************************************************
Registro de operaciones realizadas por el usuario
en las tablas de bitacoras.
 ******************************************************/

/* 
 * Incluyendo archivo con sentencias SQL 
 */
require_once("../../path.inc.php");
require_once("$dir_portal/fw/model/sql/RegisterBLog_SQL.php");
require_once("$dir_portal/fw/model/DB_Connection.php");

class RegisterBLog {
    private $mSitio;     // id del sitio en la base de datos

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;


    /* Constructor, realiza la conexion a la BD */
    function RegisterBLog() {
        $_SESSION["sConIns"]           = NEW DB_Connection();
        $_SESSION["sConIns"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new RegisterBLog_SQL();
    }

    /* Asigna el valor del número de página del sitio en la BD */
    function DarSitio($num) {
        $this->mSitio = $num;
        return $this->mSitio;
    }

    /* Asigna el estado de la operación realizada en la página correspondiente para control interno */
    function RegistroNavegacion($pUsuario,$pGrupo,$evento) {
        $query_bitacora = $this->gsql->RegistroNavegacion_insert1(now,$evento,$this->mSitio,$pGrupo,$pUsuario);
        if (!$_SESSION["sConIns"]->query($query_bitacora))
            if ($_SESSION["sConIns"]->affected_rows()>0)
                return true;  // operacion realizada con exito
        return false;
    }
}

?>
