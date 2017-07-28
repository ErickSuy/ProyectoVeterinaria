<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 20/10/2014
 * Time: 06:52 AM
 */

include_once("General_SQL.php");

/**
 * Centralización de consultas
 *
 * PostgreSQL @version 9.0
 */
Class AssignedCourseRecord_SQL extends General_SQL
{

    function __select11__($txtAnio)
    {
        return "startyear<'" . $txtAnio . "'";
    }

    function __select12__($txtAnio)
    {
        return "endyear>='" . $txtAnio . "'";
    }

    function __select21__($txtAnio)
    {
        return "startyear<'" . $txtAnio . "' or (startschoolyear in (100) and startyear='" . $txtAnio . "')";
    }

    function __select22__($txtAnio)
    {
        return "endyear>'" . $txtAnio . "' or (endschoolyear in ('101','200','201') and endyear='" . $txtAnio . "')";
    }

    function __select31__($txtAnio)
    {
        return "startyear<'" . $txtAnio . "' or (startschoolyear in ('100','101') and startyear='" . $txtAnio . "')";
    }

    function __select32__($txtAnio)
    {
        return "endyear>'" . $txtAnio . "' or (endschoolyear in ('200','201') and endyear='" . $txtAnio . "')";
    }

    function __select41__($txtAnio)
    {
        return "startyear<'" . $txtAnio . "' or (startschoolyear in ('100','101','200') and staryear='" . $txtAnio . "')";
    }

    function __select42__($txtAnio)
    {
        return "endyear>'" . $txtAnio . "' or (endschoolyear in ('201') and endyear='" . $txtAnio . "')";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen CargaNotasActividades
     * @ref #1
     * @línea   #57
     */
    function CargaNotasActividades_select1($Curso, $Seccion, $Periodo, $Anio, $Carnet)
    {
        return "select a.nombre as nombreactividad,a.tipoactividad,a.pertenecea, a.posicion,a.fecharealizar,
                    ta.nombre as nombretipoactividad, ts.shortname as abreviatura,
                    na.actividades[a.posicion],a.ponderacion
                from ing_actividad a, ing_tipoactividad ta,ing_notasactividad na,tbscheduletype ts
                where a.activo=1
                    and a.tipoactividad=ta.idtipoactividad
                    and a.curso='$Curso'
                    and a.seccion='$Seccion'
                    and a.periodo='$Periodo'
                    and a.anio=$Anio
                    and a.curso=na.curso
                    and a.seccion=na.seccion
                    and a.periodo=na.periodo
                    and a.anio=na.anio
                    and na.carnet='$Carnet'
                    and ts.idscheduletype=a.pertenecea::SMALLINT
                order by a.fecharealizar,a.tipoactividad";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen notasLaboratorioVigentes
     * @ref #1
     * @línea   #57
     */
    function notasLaboratorioVigentes_select1($compAdicional1, $compAdicional2, $txtPeriodo, $txtAnio, $txtCarnet, $txtCarrera)
    {
        return "select distinct l.idstudent, l.idcourse, l.index, l.labnote" .
        " from tbassignation a, tbassignationdetail ad, tblab l" .
        " where l.idstudent=a.idstudent and (l.idcourse=ad.idcourse and l.index=ad.index) and (" . $compAdicional1 . ") and (" . $compAdicional2 .
        ") and a.idassignation=ad.idassignation and ad.idschoolyear='" . $txtPeriodo .
        "' and ad.year='" . $txtAnio . "' and a.idstudent='" . $txtCarnet . "' and a.idcareer='" . $txtCarrera . "'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen notasHistoricasVigentes
     * @ref #1
     * @línea   #57
     */
    function notasHistoricasVigentes_select1_1($txtAnio)
    {
        return "anioinicio<" . $txtAnio;
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen notasHistoricasVigentes
     * @ref #1
     * @línea   #57
     */
    function notasHistoricasVigentes_select1_2($txtAnio)
    {
        return "aniofin>=" . $txtAnio;
    }
}

?>