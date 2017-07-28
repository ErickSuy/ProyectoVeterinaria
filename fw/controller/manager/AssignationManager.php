<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");

class AssignationManager{

    function obtenerPeriodoActivo()
    {
        $diaActual = Date("d");
        $mesActual = Date("m");
        $anioActual = Date("Y");
        $fechaActual = mktime(0, 0, 0, $mesActual, $diaActual, $anioActual);
        $periodoActivo = PRIMER_SEMESTRE;
        if ($fechaActual >= mktime(0, 0, 0, "01", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "02", "02", $anioActual))
            $periodoActivo = PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
        else
            if ($fechaActual >= mktime(0, 0, 0, "02", "02", $anioActual) && $fechaActual < mktime(0, 0, 0, "03", "01", $anioActual))
                $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
            else
                if ($fechaActual >= mktime(0, 0, 0, "03", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "05", "15", $anioActual))
                    $periodoActivo = PRIMER_SEMESTRE;
                else
                    if ($fechaActual >= mktime(0, 0, 0, "05", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "07", "10", $anioActual))
                        $periodoActivo = VACACIONES_DEL_PRIMER_SEMESTRE;
                    else
                        if ($fechaActual >= mktime(0, 0, 0, "07", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "08", "10", $anioActual))
                            $periodoActivo = PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE;
                        else
                            if ($fechaActual >= mktime(0, 0, 0, "08", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "09", "01", $anioActual))
                                $periodoActivo = SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE;
                            else
                                if ($fechaActual >= mktime(0, 0, 0, "09", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "11", "15", $anioActual))
                                    $periodoActivo = SEGUNDO_SEMESTRE;
                                else
                                    $periodoActivo = VACACIONES_DEL_SEGUNDO_SEMESTRE;
        return $periodoActivo;
    }

    
}

?>