<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 30/10/2014
 * Time: 09:01 AM
 */

include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */
Class AssignationCountReportManager_SQL extends General_SQL
{
    /**
     * @functionOrigen  numeroVeces
     * @ref #4
     * @línea   #82
     */
    function numeroVeces_select1_12($pCarnet, $pPensum)
    {
        return sprintf("select t.curso,t.nombrec,sum(t.conteo) as conteo,t.nombree,t.apellido
from (
SELECT tsql.pensum,tsql.curso,tc.name as nombrec,count(tsql.curso) AS conteo,te.name as nombreE, te.surname as apellido
                     FROM tbasignacion_sqlserver tsql, tbcourse tc, tbstudent te
                     WHERE tsql.carnet=%d
                      AND tsql.pensum=%d
                      AND ((tsql.anio >= 2006 AND trim(upper(tsql.semestreasignacion)) IN ('SEMESTRE1','SEMESTRE2')))
                      AND tsql.estatusasignacion=1
                      AND TRIM(UPPER(tsql.periodoaprobacion)) NOT IN ('SUFICIENCIA','EQUIVALENCIA')
                      AND (tc.idcourse=tsql.curso and tc.index=1)
                      AND te.idstudent=tsql.carnet
                     GROUP BY tsql.pensum,tsql.curso,tc.name,te.name,te.surname

UNION ALL
SELECT ad.index,ad.idcourse,c.name,count(ad.idcourse) AS veces,e.name AS nombreE, e.surname
                        FROM tbassignation a,tbassignationdetail ad,tbcourse c,tbstudent e
                        WHERE a.idassignation = ad.idassignation
                            AND (ad.idcourse = c.idcourse and ad.index=c.index)
                            AND a.idstudent = e.idstudent
                            AND (ad.year >= '2014'
                            AND ad.idschoolyear IN ('100','200'))
                            AND a.idstudent = %d GROUP BY ad.index,ad.idcourse,c.name,e.name,e.surname ) t
                            group by t.curso,t.nombrec,t.nombree,t.apellido
                            order by t.curso", $pCarnet, $pPensum, $pCarnet);
    }

    /**
     * @functionOrigen  numeroVeces
     * @ref #5
     * @línea   #106
     */
    function numeroVeces_select1_13($pCarnet, $pPensum)
    {
        return sprintf("select t.curso,t.nombrec,sum(t.conteo) as conteo,t.nombree,t.apellido
from (
SELECT tsql.pensum,tsql.curso,tc.name as nombrec,count(tsql.curso) AS conteo,te.name as nombreE, te.surname as apellido
                     FROM tbasignacion_sqlserver tsql, tbcourse tc, tbstudent te
                     WHERE tsql.carnet=%d
                      AND tsql.pensum=%d
                      AND ((tsql.anio >= 2006 AND trim(upper(tsql.semestreasignacion)) IN ('JUNIO','DICIEMBRE')))
                      AND tsql.estatusasignacion=1
                      AND TRIM(UPPER(tsql.periodoaprobacion)) NOT IN ('SUFICIENCIA','EQUIVALENCIA')
                      AND (tc.idcourse=tsql.curso and tc.index=1)
                      AND te.idstudent=tsql.carnet
                     GROUP BY tsql.pensum,tsql.curso,tc.name,te.name,te.surname

UNION ALL
SELECT ad.index,ad.idcourse,c.name,count(ad.idcourse) AS veces,e.name AS nombreE, e.surname
                        FROM tbassignation a,tbassignationdetail ad,tbcourse c,tbstudent e
                        WHERE a.idassignation = ad.idassignation
                            AND (ad.idcourse = c.idcourse and ad.index=c.index)
                            AND a.idstudent = e.idstudent
                            AND (ad.year >= '2014'
                            AND ad.idschoolyear IN ('101','201'))
                            AND a.idstudent = %d GROUP BY ad.index,ad.idcourse,c.name,e.name,e.surname ) t
                            group by t.curso,t.nombrec,t.nombree,t.apellido
                            order by t.curso", $pCarnet, $pPensum, $pCarnet);
    }

}

//fin consultas respecto a la versión 9.0

?>