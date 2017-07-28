<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 10/10/14
 * Time: 01:30 PM
 */

/**
 * @origen portal2/ingresodenotas/asignacionCursos.php
 */
include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */
Class D_StudentReport_SQL extends General_SQL
{

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   hd.anio='".$anioRevisado."'
     * @functionOrigen  ninguna
     * @ref #1
     * @línea   #143
     */
    function _select1($regPersonal, $periodoRevisado, $anioRevisado)
    {
        /*
         *  -- ORIGINAL CON FILTRADO POR SECCIÓN
         * return "select distinct c.idcourse, c.index,trim(c.name) as name,trim(hd.section) as section" .
        " from tbcourse c,tbscheduledetail hd, tbassignationdetail ad" .
        " where (c.idcourse=hd.idcourse and c.index=hd.index) and hd.idschoolyear=ad.idschoolyear and hd.year=ad.year and (hd.idcourse=ad.idcourse and hd.index=ad.index)" .
        " and hd.section=ad.section and hd.idscheduletype=1 and hd.idteacher=" . $regPersonal . "" .
        " and hd.idschoolyear=" . $periodoRevisado . " and hd.year=" . $anioRevisado . " order by 1,3";
         */
        return sprintf("  select idcourse,index,name,idcareer,career,sum(count) as count from (
select distinct c.idcourse, c.index, section,trim(c.name) as name, hd.idcareer, ca.name as career,hd.assignedcount as count from tbcourse c,tbscheduledetail hd,tbcareer ca where (c.idcourse=hd.idcourse and c.index=hd.index) and hd.idcareer=ca.idcareer and hd.idscheduletype=1 and hd.idteacher= %d and hd.idschoolyear= %d and hd.year= %d
                          union
                          select distinct c.idcourse, c.index, section,trim(c.name) as name, hd.idcareer, ca.name as career,hd.assignedcount as count from tbcourse c,tbmodule_scheduledetail hd,tbcareer ca where (c.idcourse=hd.idcourse and c.index=hd.index) and hd.idcareer=ca.idcareer and hd.idscheduletype=1 and hd.idteacher= %d and hd.idschoolyear= %d and hd.year= %d
                          order by 1,5,3)s group by idcourse,index,name,idcareer,career",
            $regPersonal, $periodoRevisado, $anioRevisado,$regPersonal, $periodoRevisado, $anioRevisado);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #2
     * @línea   #184
     */
    function _select2($registrocurso, $registroseccion, $periodoRevisado, $anioRevisado, $mIndex)
    {
        /*
         * -- ORIGINAL CON FILTRADO DE SECCIONES
         * "select distinct e.idstudent,trim(e.name)||' '||trim(e.surname) as name, case when e.email='' or e.email is null then 'S/E' else e.email end as email, '[0'||tc.idcareer||'] '||trim(tc.name) as career" .
        " from tbstudent e, tbassignation a, tbassignationdetail ad, tbcareer tc" .
        " where a.idassignation=ad.idassignation " .
        " and  e.idstudent=a.idstudent and a.idcareer=tc.idcareer and (ad.idcourse=" . $registrocurso . " and ad.index=" . $mIndex . ") " .
        " and ad.section='" . $registroseccion . "' and ad.idschoolyear=" . $periodoRevisado . "" .
        " and ad.year=" . $anioRevisado . " order by 1";
         */
        return sprintf("select distinct e.idstudent,trim(e.surname)||', '||trim(e.name) as name,
                         case when e.email='' or e.email is null then '' else e.email end as email,
                         trim(tc.name) as career,ad.section,ad.labgroup
                        from tbstudent e, tbassignation a, tbassignationdetail ad, tbcareer tc
                        where a.idassignation=ad.idassignation
                          and  e.idstudent=a.idstudent and a.idcareer=tc.idcareer and (ad.idcourse=%d and ad.index=%d)
                          and ad.idschoolyear=%d
                          and ad.year=%d and a.idcareer=%d order by 4,5,1", $registrocurso, $mIndex, $periodoRevisado, $anioRevisado,$registroseccion);
    }

}

//fin consultas respecto a la versión 9.0

?>
