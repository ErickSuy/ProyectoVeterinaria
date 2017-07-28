<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 3/10/14
 * Time: 01:55 PM
 */

/**
 */
include_once("General_SQL.php");

/**
 *
 * PostgreSQL @version 9.0
 */
Class D_ManualLoadNotes_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio = '%d'
     * @functionOrigen  ListadoEstudiantes
     * @ref #1
     * @línea   #122
     */
    function ListadoEstudiantes_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select e.idstudent,e.name,e.surname,
                            i.labnote,i.classzone,i.notefinalexam
                         from tbtempentry i,tbstudent e
                         where (i.idcourse   = '%s' and i.index = %d)
                            and   i.section = '%s'
                            and   i.idschoolyear = '%s'
                            and   i.year    =  '%d'
                            and   i.idstudent  = e.idstudent
                         order by e.idstudent;
                         ", $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);
    }

    /**
     * @cambios
     * @functionOrigen  ListadoEstudiantes
     * @ref #2
     * @línea   #175
     */
    function ListadoEstudiantes_select2($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select f_makecourselist
                            ('%s','%s','%s','%s',%d);

                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex
        );
    }


    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ListadoEstudiantes
     * @ref #3
     * @línea   #190
     */
    function ListadoEstudiantes_drop1() {
        return sprintf("drop table webingtemporal;");
    }

    /**
     * @cambios
     * @functionOrigen  GrabaraIngresoTemporal
     * @ref #4
     * @línea   #252
     */
    function GrabaraIngresoTemporal_delete1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("delete from tbtempentry
                         where (idcourse ='%s' and index=%d)
                             and   section ='%s'
                             and   idschoolyear = '%s'
                             and   year = '%s';
                         ", $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  LlenarIngresoTemporal
     * @ref #5
     * @línea   #313
     */
    function LlenarIngresoTemporal_select1($_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select f_filltempentry
                             (%d,%d,'%s',%d,%d,%d);
                         ", $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  GuardarConsistencia
     * @ref #6
     * @línea   #340
     */
    function GuardarConsistencia_select1($_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select f_saveconsistency
                                 (%d,%d,'%s',%d,%d,%d);
                         ", $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  GenerarLaboratorioFinal
     * @ref #7
     * @línea   #373
     */
    function GenerarLaboratorioFinal_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select f_makelabnotes1
                           (%d,'%s',%d,%d,%d);
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio,$mIndex
        );
    }


    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  GenerarLaboratorioFinal
     * @ref #8
     * @línea   #447
     */
    function GenerarLaboratorioFinal_drop1() {
        return "drop table web_labfinal;";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  LlenarIngresoTemporal_2
     * @ref #9
     * @línea   #486
     */
    function LlenarIngresoTemporal_2_select1($_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select f_filltempentry2
                             (%d,%d,'%s',%d,%d,%d);
                        ", $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex);
    }

}
//fin consultas respecto a la versión 9.0

?>
