<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 03/05/2015
 * Time: 10:04 AM
 */

include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */
Class AR_PaymentOrderGenerationManager_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _verExtensionEstudiante
     * @ref #1
     * @línea   #84
     */
    function _verExtensionEstudiante_select1($mCarnet) {
        return sprintf("SELECT extension
                        FROM estudiantecarrera
                        WHERE usuarioid='%s';
                        ", $mCarnet);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  CargarDatosAcademicos
     * @ref #2
     * @línea   #107
     */
    function CargarDatosAcademicos_select1($fechaAsigna, $TIPOPROCESORETRASADA_WEB/*, $extension*/) {
        return sprintf("SELECT * FROM f_activeprocess(%d::smallint,%d::smallint,array[102::smallint,103::smallint,203::smallint,204::smallint],'%s');", $TIPOPROCESORETRASADA_WEB,Date('Y'),$fechaAsigna);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  obtieneDatosCursosAsignarRetrasada
     * @ref #3
     * @línea   #152
     */
    function obtieneDatosCursosAsignarRetrasada_select1($mCarnet, $mAnio, $mPerAnterior, $mCarrera) {
        return sprintf(" SELECT distinct(ad.idcourse),c.name AS nomcurso,h.section,ad.classzone,ad.labnote,ad.notefinalexam,
                              a.idcareer,ad.problemdetail,h.idactstate,cc.name as nomcarr,ad.labgroup,a.enrollmentdate,ad.index
                         FROM tbassignation a,tbassignationdetail ad,tbcourse c,tbschedule h,tbcareer cc
                         WHERE a.idassignation = ad.idassignation
                          and ad.idcourse = c.idcourse and ad.index=c.index
                          and ad.idcourse = h.idcourse and ad.index=h.index
                          and ad.section  = h.section
                          and a.idcareer = h.idcareer
                          and ad.idschoolyear = h.idschoolyear
                          and ad.year = h.year
                          and a.idcareer = cc.idcareer
                          and a.idstudent = '%s'
                          and ad.year = '%s'
                          and ad.idschoolyear = '%s'
                          and a.idcareer = '%s'
                         order by a.idcareer,ad.idcourse
                         ", $mCarnet, $mAnio, $mPerAnterior, $mCarrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  cargainfoDatosPreAsignados
     * @ref #4
     * @línea   #214
     */
    function cargainfoDatosPreAsignados_select1($mCarnet, $mAnio, $mPerAnterior, $curso,$carrera) {
        return sprintf(" SELECT distinct(ad.idcourse),c.name AS nomcurso,h.section,ad.classzone,ad.labnote,ad.notefinalexam,
                              a.idcareer,ad.problemdetail,h.idactstate,cc.name as nomcarr,ad.labgroup,a.enrollmentdate,ad.index
                         FROM tbassignation a,tbassignationdetail ad,tbcourse c,tbschedule h,tbcareer cc
                         WHERE a.idassignation = ad.idassignation
                          and ad.idcourse = c.idcourse and ad.index=c.index
                          and ad.idcourse = h.idcourse and ad.index=h.index
                          and ad.section  = h.section
                          and a.idcareer = h.idcareer
                          and ad.idschoolyear = h.idschoolyear
                          and ad.year = h.year
                          and a.idcareer = cc.idcareer
                          and a.idstudent = '%s'
                          and ad.year = '%s'
                          and ad.idschoolyear = '%s'
                          and ad.idcourse = %d
                          and a.idcareer = %d
                         order by a.idcareer,ad.idcourse
                       ", $mCarnet, $mAnio, $mPerAnterior, $curso, $carrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verCurAprobados
     * @ref #5
     * @línea   #272
     */
    function verCurAprobados_select1($mCarnet, $_SESSIONcursosAsigjcurso, $_SESSIONcursosAsigjmCarreraCurso) {
        return sprintf("SELECT *
                        FROM tbapprovedcourse
                        WHERE idstudent='%s'
                            AND idcourse='%s'
                            AND idcareer='%s';
                        ", $mCarnet, $_SESSIONcursosAsigjcurso, $_SESSIONcursosAsigjmCarreraCurso);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verCurAsignados
     * @ref #6
     * @línea   #291
     */
    function verCurAsignados_select1($mCarnet, $_SESSIONcursosAsigjcurso, $_SESSIONcursosAsigjmCarreraCurso, $mPeriodo, $anio) {
        return sprintf("SELECT idcourse
                        FROM tbassignation a,tbassignationdetail ad
                        WHERE a.idassignation = ad.idassignation
                            AND a.idstudent = '%s'
                            AND ad.idcourse='%s'
                            AND a.idcareer='%s'
                            AND ad.idschoolyear='%s'
                            AND ad.year='%s'
                        ", $mCarnet, $_SESSIONcursosAsigjcurso, $_SESSIONcursosAsigjmCarreraCurso, $mPeriodo, $anio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  buscaMotosBDD
     * @ref #7
     * @línea   #406
     */
    function buscaMotosBDD_select1($mCarnet, $_TIPO_PAGO_PRIMERA_RETRASADA, $_TIPO_PAGO_SEGUNDA_RETRASADA, $mAnio, $mPeriodo, $mCarrera,$_TIPO_PAGO_PRIMERA_RETRASADA2, $_TIPO_PAGO_SEGUNDA_RETRASADA2) {
        return sprintf(" SELECT amount,verws,verca,paymentorder,paymentorderdate
                         FROM tbcoursepayment
                         WHERE idstudent='%s'
                            AND idpaymenttype IN (%d,%d,%d,%d)
                            AND year = '%s'
                            AND idschoolyear = '%s'
                            AND idcareer='%s'
                        ORDER BY paymentorder,paymentorderdate;
                        ", $mCarnet, $_TIPO_PAGO_PRIMERA_RETRASADA, $_TIPO_PAGO_SEGUNDA_RETRASADA,$_TIPO_PAGO_PRIMERA_RETRASADA2, $_TIPO_PAGO_SEGUNDA_RETRASADA2, $mAnio, $mPeriodo, $mCarrera
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio = '%d'
     * @functionOrigen  verificabloqueo
     * @ref #8
     * @línea   #515
     */
    function verificabloqueo_select1($_SESSIONcursosAsigposcurso, $mPeriodo, $mAnio) {
        return sprintf("SELECT problema
                        FROM asignacionregla
                        WHERE curso='%s'
                            AND periodo='%s'
                            AND anio=%d;
                        ", $_SESSIONcursosAsigposcurso, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verificaFechaExamen_bloquea
     * @ref #9
     * @línea   #537
     */
    function verificaFechaExamen_bloquea_select1($mAnio, $mPeriodo, $_SESSIONcursosAsigposcurso,$carrera) {
        return sprintf("SELECT e.primeraretrasada,e.segundaretrasada
                        FROM ing_calendarioactividades e
                        WHERE current_date < e.primeraretrasada
                            AND e.anio=%d
                            AND e.periodoretrasada1 = '%s'
                            AND e.curso = %d AND e.carrera=%d;", $mAnio, $mPeriodo, $_SESSIONcursosAsigposcurso,$carrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verificaFechaExamen_bloquea
     * @ref #9
     * @línea   #537
     */
    function verificaFechaExamen_bloquea_select11($mAnio, $mPeriodo, $_SESSIONcursosAsigposcurso,$carrera) {
        return sprintf("SELECT e.primeraretrasada,e.segundaretrasada
                        FROM ing_calendarioactividades e
                        WHERE current_date < e.segundaretrasada
                            AND e.anio=%d
                            AND e.periodoretrasada2 = '%s'
                            AND e.curso = %d AND e.carrera=%d;", $mAnio, $mPeriodo, $_SESSIONcursosAsigposcurso,$carrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  obtieneNombreEstudiante
     * @ref #10
     * @línea   #578
     */
    function obtieneNombreEstudiante_select1($mCarnet) {
        return sprintf("SELECT name,surname
                        FROM tbstudent
                        WHERE idstudent='%s'
                        ", $mCarnet);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ver_Extension
     * @ref #11
     * @línea   #592
     */
    function ver_Extension_select1($mCarnet) {
        return sprintf("SELECT extension
                        FROM estudiantecarrera
                        WHERE usuarioid = '%s';
                        ", $mCarnet);

    }

    function ver_TipoCurso($txtIndex, $txtCurso, $txtCarrera)
    {
        return sprintf("select case when f_check_modulecourse='t' then 1 else 0 end as resultado from f_check_modulecourse(%d,%d,%d);", $txtIndex, $txtCurso, $txtCarrera);
    }

}
//fin consultas respecto a la versión 9.0


?>