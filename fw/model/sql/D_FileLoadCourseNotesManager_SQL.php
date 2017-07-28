<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 9/10/14
 * Time: 03:49 PM
 */

/**
 */
include_once("General_SQL.php");

/**
 *
 * PostgreSQL @version 9.0
 */
Class D_FileLoadCourseNotesManager_SQL extends General_SQL {

    /**
     * @cambios
     * @functionOrigen  VerificaLaboratorio
     * @ref #1
     * @línea   #79
     */
    function VerificaLaboratorio_select1($mCurso, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select idscheduletype
                        from tbscheduledetail
                        where  (idcourse      = '%s' and index=%d)
                          and idschoolyear     = '%s'
                          and year        = '%s'
                          and ( idscheduletype=2 or idscheduletype=6 );",
            $mCurso, $mIndex, $mPeriodo, $mAnio);
    }

    /**
     * @cambios
     * @functionOrigen  VerificaPeriodo
     * @ref #2
     * @línea   #229
     */
    function VerificaPeriodo_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select a.idstudent, a.idcareer, ad.classzone, ad.labnote,ad.notefinalexam,ad.idfinalexamstate
                          from tbassignation a, tbassignationdetail ad
                          where a.idassignation   = ad.idassignation
                              and (ad.idcourse          = '%s' and ad.index=%d)
                              and a.idcareer        = '%s'
                              and ad.idschoolyear        = '%s'
                              and ad.year           = '%s'
						  order by a.idstudent;
                          ", $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @cambios
     * @functionOrigen  VerificaPeriodo
     * @ref #3
     * @línea   #247
     */
    function VerificaPeriodo_select2($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select distinct a.idstudent,a.idcareer,ad.labnote,ad.classzone,0 as notefinalexam,ad.idfinalexamstate
                         from tbassignation a, tbassignationdetail ad
                         where a.idassignation   = ad.idassignation
                             and (ad.idcourse          = '%s' and ad.index=%d)
                             and a.idcareer        = '%s'
                             and ad.idschoolyear        = '%s'
                             and ad.year           = '%s'
                             order by a.idstudent;
                         ", $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @cambios
     * @functionOrigen  VerificaDatosTabla
     * @ref #4
     * @línea   #274
     */
    function VerificaDatosTabla_select1($mUsuarioid, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select *
                         from tbrecordentry
                         where idgroup   = 2
                            and idpersonal = '%s'
                            and (idcourse     = '%s' and index=%d)
                            and section   = '%s'
                            and idschoolyear   = '%s'
                            and year      = '%s';
                        ", $mUsuarioid, $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @cambios
     * @functionOrigen  VerificaDatosTabla
     * @ref #5
     * @línea   #290
     */
    function VerificaDatosTabla_delete1($mUsuarioid, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("delete from tbtempentry
                        where idpersonal = '%s'
                           and (idcourse       = '%s' and index=%d)
                           and section     = '%s'
                           and idschoolyear     = '%s'
                           and year        = '%s';
                        ", $mUsuarioid, $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * begin en @línea   #439
     */

    /**
     * commit en @línea   #461
     */

    /**
     * rollback en @línea   #472
     */

    /**
     * end en @línea   #480
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  upload_report
     * @ref #6
     * @línea   #649
     */
    function upload_report_insert1($mUsuarioid, $mAnio, $valorexamen, $mCurso, $mSeccion, $mPeriodo) {
        return sprintf("insert into tbtempentry
                            select '%s',a.idstudent as usuarioid,a.idcareer as carrera,ad.idcourse as curso,ad.index,a.idcareer,ad.idschoolyear as periodo,%d,ad.classzone,ad.labnote,ad.notefinalexam,'0000',''
                            from tbassignation a,tbassignationdetail ad
                            where a.idassignation = ad.idassignation
                               and a.idstudent not in ( select idstudent
                                from tbtempentry b
                                where b.idpersonal = '%s'
                                    and b.idcourse = '%s'
                                    and b.section = '%s'
                                    and b.idschoolyear = '%s'
                                    and b.year = '%s')
                               and ad.idcourse   = '%s'
                               and a.idcareer = '%s'
                               and ad.idschoolyear = '%s'
                               and ad.year    = '%s';
                           ", $mUsuarioid, $mAnio, $mUsuarioid, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mCurso, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  upload_report
     * @ref #7
     * @línea   #692
     */
    function upload_report_insert2($mUsuarioid, $mAnio, $valorexamen, $mCurso, $mSeccion, $mPeriodo) {
        return sprintf("insert into tbtempentry
                           select '%s',a.idstudent,b.idcareer,ad.idcourse,ad.index,b.idcareer,b.idschoolyear,%d,
                            ad.classzone, ad.labnote, ad.notefinalexam, '0000',''
                           from tbassignation as a, tbassignationdetail ad,
                           ( select a2.idstudent,a2.idcareer,ad2.idschoolyear
                             from tbassignation as a2, tbassignationdetail ad2
                             where a2.idassignation   = ad2.idassignation
                                 and ad2.idcourse           = '%s'
                                 and a2.idcareer         = '%s'
                                 and ad2.idschoolyear         = '%s'
                                 and ad2.year            = '%s'
                                 and a2.idstudent not in ( select c.idstudent
                                   from tbtempentry c
                                   where c.idpersonal = '%s'
                                   and c.idcourse       = '%s'
                                   and c.seccion     = '%s'
                                   and c.idschoolyear     = '%s'
                                   and c.year        = '%s' )
                           ) as b
                           where a.idassignation   = ad.idassignation
                               and a.idstudent       = b.idstudent
                               and a.idcareer         = b.idcareer
                               and ad.idcourse          = '%s'
                               and a.idcareer        = '%s'
                               and ad.idschoolyear        = '%s'
                               and ad.year           = '%s';
                           ", $mUsuarioid, $mAnio,  $mCurso, $mSeccion, $mPeriodo, $mAnio, $mUsuarioid, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mCurso, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  upload_report
     * @ref #8
     * @línea   #734
     */
    function upload_report_update1($mPeriodo, $mAnio, $mCurso, $mSeccion) {
        return sprintf("update ingresotemporal
                        set zonalaboratorio = cast(laboratorio.nota as integer)
                        from laboratorio
                        where ingresotemporal.periodo = '%s'
                           and   ingresotemporal.anio      = '%s'
                           and   ingresotemporal.curso     = '%s'
                           and   ingresotemporal.seccion   = '%s'
                           and   ingresotemporal.carnet    = laboratorio.usuarioid
                           and   ingresotemporal.curso     = laboratorio.curso
                           and   ingresotemporal.zonalaboratorio = 0;

                        ", $mPeriodo, $mAnio, $mCurso, $mSeccion);
    }

    /**
     * begin en @línea   #742
     */

    /**
     * commit en @línea   #747
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   error ambiguedad en el campo curso, no existe el campo codcurso
     * @functionOrigen  upload_report
     * @ref #9
     * @línea   #786
     */
    function upload_report_update2($mPeriodo, $mAnio, $mCurso, $mSeccion) {
        return sprintf("update ingresotemporal
                        set zonalaboratorio = cast(laboratorio.nota as integer)
                        from laboratorio
                        where periodo       = '%s'
                            and anio            = '%s'
                            and curso           = '%s'
                            and seccion         = '%s'
                            and carnet          = laboratorio.usuarioid
                            and codcurso        = laboratorio.curso
                            and zonalaboratorio = 0;
                         ", $mPeriodo, $mAnio, $mCurso, $mSeccion);
    }

    /**
     * begin en @línea   #788
     */

    /**
     * commit en @línea   #793
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio = '%d'
     * @functionOrigen  LevantaTemporal
     * @ref #10
     * @línea   #838
     */
    function LevantaTemporal_select1($mCurso, $mSeccion, $mPeriodo, $mAnio) {
        return sprintf("select distinct idpersonal as usuarioid,idstudent as carnet,idcareer as carrera,idcourse as curso,index,section as seccion,idschoolyear as periodo,year as anio,classzone as zona, labnote as zonalaboratorio,notefinalexam as examenfinal,idloadevent as evento,problemdetail as problema
                        from tbtempentry
                        where idcourse = '%s'
                            and section = '%s'
                            and idschoolyear = '%s'
                            and year    = '%d'
                        order by idstudent;
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  LevantaTemporal
     * @ref #11
     * @línea   #869
     */
    function LevantaTemporal_select2($mCurso, $mSeccion, $mPeriodo, $mAnio) {
        return sprintf("select distinct a.idstudent as usuarioid, ad.problemdetail as problema, a.enrollmentdate as fechainscripcion
                        from tbassignation a, tbassignationdetail ad
                        where a.idassignation   = ad.idassignation
                           and ad.idcourse          = '%s'
                           and a.idcareer        = '%s'
                           and ad.idschoolyear        = '%s'
                           and ad.year           = '%s'
                        order by a.idstudent;
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * begin en @línea   #1230
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio = '%d'
     * @functionOrigen  Verificaciones
     * @ref #12
     * @línea   #1239
     */
    function Verificaciones_delete1($mCurso, $mSeccion, $mPeriodo, $mAnio) {
        return sprintf("delete from tbtempentry
                        where idcourse = '%s'
                            and section ='%s'
                            and idschoolyear = '%s'
                            and year = '%d';
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * rollback en @línea   #1253
     */

    /**
     * commit en @línea   #1260
     */

    /**
     * end en @línea   #1267
     */

    /**
     * begin en @línea   #1431
     */

    /**
     * commit en @línea   #1454
     */

    /**
     * rollback en @línea   #1467
     */

    /**
     * end en @línea   #1475
     */

}
//fin consultas respecto a la versión 9.0

?>
