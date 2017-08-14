<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 22/11/2014
 * Time: 04:36 AM
 */

include_once("General_SQL.php");

Class conectar_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  sesionAunActiva
     * @ref #1
     * @línea   #30
     */
    function sesionAunActiva_select1($_GRUPO_DOCENTE){
        return "select * from tbconn_connection where idsession='" . session_id() . "' and idgroup=" . $_GRUPO_DOCENTE . " and date>='" . date("Y") .
        "-" . date("m") . "-" . date("d") . " " . (date("H")-1) . ":" . date("i"). ":" . date("s") . ".000000000'";
    }
    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  sistemaHabilitado
     * @ref #2
     * @línea   #50
     */
    function sistemaHabilitado_calendario($txtPeriodo,$txtAnio,$txtCurso,$txtCarrera){
        return "select * from  ing_calendarioactividades where periodo='". $txtPeriodo ."' and anio=" . $txtAnio ." and curso=" . $txtCurso ." and carrera=" . $txtCarrera.";";
    }
    
     function sistemaHabilitado($txtPeriodo,$txtAnio,$txtCurso,$txtCarrera){
        return "select * from  ing_calendarioactividades where periodo='". $txtPeriodo ."' and anio=" . $txtAnio ." and curso=" . $txtCurso ." and carrera=" . $txtCarrera.";";
    }
    
    function sistemaHabilitado_getActividades($txtPeriodo,$txtAnio,$txtCurso,$txtCarrera){
        return "select * from tbactividad_curso
            where 
            curso='$txtCurso'
            and periodo='$txtPeriodo'
            and carrera='$txtCarrera'
            and anio='$txtAnio'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  sistemaHabilitado
     * @ref #3
     * @línea   #65
     */
    function sistemaHabilitado_select2($txtPeriodo,$txtAnio,$txtRegPer,$txtCurso,$txtSeccion){
        return "select distinct pertenecea from ing_actividad where activo=1 and periodo='" . $txtPeriodo . "' and anio=". $txtAnio .
        " and regper @> array[" . $txtRegPer . "] and curso='" . $txtCurso . "' and seccion='" . $txtSeccion . "' order by pertenecea";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  sistemaHabilitado
     * @ref #4
     * @línea   #78
     */
    function sistemaHabilitado_select3($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$txtRegPer){
        return "select * from ing_notasactividad na, ing_actividad a where a.activo=1 and a.periodo=na.periodo" .
        " and a.curso=na.curso and a.anio=na.anio and a.seccion=na.seccionactividad[a.posicion] and na.curso='". $txtCurso .
        "' and na.seccionactividad[a.posicion]='" . $txtSeccion . "' and na.periodo='" . $txtPeriodo .
        "' and na.anio=" . $txtAnio . " and a.regper @> array[" . $txtRegPer . "]";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  sistemaHabilitado
     * @ref #5
     * @línea   #85
     */
    function sistemaHabilitado_select4($txtPeriodo,$txtAnio,$txtCurso,$txtSeccion){
        return "select * from ing_notasactividad where periodo='" . $txtPeriodo . "' and anio=". $txtAnio .
        " and curso='" . $txtCurso . "' and seccion='" . $txtSeccion . "'";
    }

}
?>