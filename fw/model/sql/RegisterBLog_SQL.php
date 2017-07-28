<?php

/**
 *@origen biblioteca/class.registrobitacora.inc.php
 */
include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */
Class RegisterBLog_SQL extends General_SQL {

    /**
     * @cambios
     * @functionOrigen  RegistroNavegacion
     * @ref #1
     * @línea   #48
     */
    function RegistroNavegacion_insert1($now, $evento, $mSitio, $_SESSIONsUsuarioDeSesionmGrupo, $_SESSIONsUsuarioDeSesionmUsuario) {
        return sprintf("INSERT INTO tblog_sitenavigation (date,event,idsite,idgroup,iduser) VALUES ('%s',%d,%d,%d,%d);",
            $now, $evento, $mSitio, $_SESSIONsUsuarioDeSesionmGrupo, $_SESSIONsUsuarioDeSesionmUsuario);
    }

}
//fin consultas respecto a la versión 9.0

?>
