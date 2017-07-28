<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 12/11/2014
 * Time: 02:28 PM
 */

include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */

Class D_DownloadStudentReport_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  notasDeLaboratorio
     * @ref #1
     * @línea   #34
     */
    function notasDeLaboratorio_select1_1(){
        return "l.endyear >= ad.year";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #2
     * @línea   #37
     */
    function notasDeLaboratorio_select1_2(){
        return "(l.endyear > ad.year or (l.endshooyear in ('101','200','201') and l.endyear=ad.year))";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #3
     * @línea   #39
     */
    function notasDeLaboratorio_select1_3(){
        return "(l.endyear > ad.year or (l.endschoolyear in ('200','201') and l.endyear=ad.year))";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #4
     * @línea   #43
     */
    function notasDeLaboratorio_select1_4(){
        return "(l.endyear > ad.year or (l.endschoolyear in ('201') and l.endyear=ad.year))";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ad.anio='".$anio."'
     * @functionOrigen  ninguna
     * @ref #5
     * @línea   #52
     */
    function notasDeLaboratorio_select1($periodo,$anio,$curso,$seccion,$comparacionAdicional,$index){
        return " select l.idstudent,l.labnote" .
        " from tbassignation a,tbassignationdetail ad,tblab l".
        " where a.idassignation=ad.idassignation and l.idstudent=a.idstudent".
        " and (l.idcourse=ad.idcourse and l.index=ad.index) and ad.idschoolyear=" . $periodo . " and ad.year=" . $anio . " and (ad.idcourse=" . $curso . " and ad.index=". $index.")" .
        " and ad.section='" . $seccion . "' and " . $comparacionAdicional . " order by l.idstudent";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ad.anio='".$anio."'
     * @functionOrigen  ninguna
     * @ref #6
     * @línea   #99
     */
    function _select1($curso,$seccion,$periodo,$anio,$index){
        return "select distinct e.idstudent,trim(e.name)||' '||trim(e.surname) as name" .
        " from tbstudent e, tbassignation a, tbassignationdetail ad" .
        " where a.idassignation=ad.idassignation" .
        " and  e.idstudent=a.idstudent and (ad.idcourse='" . $curso . "' and ad.index=" . $index . ")" .
        " and ad.section='" . $seccion . "' and ad.idschoolyear='" . $periodo . "'" .
        " and ad.year='" . $anio . "' order by 1";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ad2.anio='".$anio."'
     * @functionOrigen  ninguna
     * @ref #7
     * @línea   #115
     */
    function _select2($periodoAnterior,$anio,$periodo,$curso,$seccion,$index){
        return "select a2.idstudent,trim(e.name)||' '||trim(e.surname) as name,ad1.labnote,ad1.classzone" .
        " from tbassignationdetail ad1,tbassignation a1,tbassignation a2,tbassignationdetail ad2,tbstudent e" .
        " where e.idstudent=a2.idstudent and a1.idstudent=a2.idstudent and a1.idcareer=a2.idcareer" .
        " and a1.idassignation=ad1.idassignation ".
        " and a2.idassignation=ad2.idassignation and ad1.year=ad2.year" .
        " and (ad1.idcourse=ad2.idcourse and ad1.index=ad2.index) and ad1.section=ad2.section and ad1.idschoolyear='" . $periodoAnterior .
        "' and ad2.year='" . $anio . "' and ad2.idschoolyear='" . $periodo . "' and (ad2.idcourse='" . $curso . " and ad2.index=".$index.")" .
        "' and ad2.section='" . $seccion . "' order by 1";
    }

}
//fin consultas respecto a la versión 9.0

?>