<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 21/09/14
 * Time: 05:07 AM
 */

include_once("General_SQL.php");

/**
 * Centralización de consultas de portal
 *
 * PostgreSQL @version 9.0
 */
Class RegisterCourseAssignation_SQL extends General_SQL {
    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  inserta registro base de datos
     * @ref #1
     * @línea   #547
     */
    function inserta_registro_base_datos($usuarioid,$carrera,$problema_asignacion_insert,$fechains, $periodo,$fechaAsigna,$mTransaccion){
        return sprintf(" INSERT INTO formaasignacionunica VALUES('%s','%s','%s','%s','%s','%s',%d);
                    ",$usuarioid,$carrera,$problema_asignacion_insert,$fechains,
            $periodo,$fechaAsigna,$mTransaccion);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  inserta registro de base de datos
     * @ref #2
     * @línea   #553
     */




    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  eliminaasigunica
     * @ref #1
     * @línea   #454
     */
    function elimina_asignacion_unica($usuarioid,$carrera,$periodo,$anio) {
        return sprintf(" delete from formaasignacionunica ".
            " where usuarioid = '%s' and carrera = '%s' and".
            " periodo='%s' and (to_char(fechaasignacion,'YYYY'))='%s';".
            "",$usuarioid,$carrera,$periodo,$anio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen eliminaasigunica
     * @ref #2
     * @línea   #461
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verificaasigunica
     * @ref #1
     * @línea   #441
     */
    function verifica_asignacion_unica($usuarioid,$carrera,$periodo,$anio) {
        return sprintf("select * from formaasignacionunica fau
                 where fau.usuarioid='%s' and fau.carrera='%s' and
                 fau.periodo='%s' and (to_char(fau.fechaasignacion,'YYYY'))='%s';",
            $usuarioid, $carrera,$periodo,$anio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verificaasigunica
     * @ref #2
     * @línea   #449
     */
    /**
     * begin en @línea   #107
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #1
     * @línea   #124
     */
    function funInstertarAsignacion_select1($usuarioid, $periodo, $anio, $carrera) {
        return sprintf(" SELECT a.idassignation,a.assignationdate " .
            " FROM tbassignation a, tbassignationdetail ad " .
            " WHERE a.idstudent='%s' " .
            "    AND a.idassignation = ad.idassignation " .
            "    AND ad.idschoolyear='%s'
                 AND ad.year = '%s' " .
            "    AND a.idcareer = '%s' " .
            "", $usuarioid, $periodo, $anio, $carrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #2
     * @línea   #141
     */
    function funInstertarAsignacion_delete1($transaccionActual) {
        return sprintf(" DELETE FROM tbassignationdetail " .
            " WHERE idassignation =%d " .
            "",$transaccionActual);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #3
     * @línea   #154
     */
    function funInstertarAsignacion_delete2($fechaAsignacionActual, $transaccionActual) {
        return sprintf(" DELETE FROM tbassignation " .
            "  WHERE assignationdate = '%s' " .
            "    AND idassignation =%d " .
            "", $fechaAsignacionActual, $transaccionActual);
    }

    /**
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #4
     * @línea   #172
     */
    function funInstertarAsignacion_insert1($mTransaccion, $fechaAsigna, $usuarioid, $carrera,$fechainscripcion) {
        return sprintf("INSERT INTO tbassignation (idassignation,assignationdate,state,idstudent,idcareer,enrollmentdate) VALUES(%d,'%s',1,%d,%d,'%s');", $mTransaccion, $fechaAsigna, $usuarioid, $carrera,$fechainscripcion);
    }

    /**
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #5
     * @línea   #182
     */
    function funInstertarAsignacion_insert2($mTransaccion, $fechaAsigna, $usuarioid, $carrera, $horaAsignacion) {
        return sprintf(" INSERT INTO tbassignationaudit (idassignation,assignationdate,idstudent,idcareer,idpersonal,terminal,ip,time)
                           VALUES(%d,'%s','%s','%s','0','WEB','','%s'); " .
            "", $mTransaccion, $fechaAsigna, $usuarioid, $carrera, $horaAsignacion);
    }

    /**
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #6
     * @línea   #224
     */
    function funInstertarAsignacion_insert3($mTransaccion,$mCursosInsposcurso, $mCursosInsposseccion, $periodo, $anio, $index,$grupoLab) {
        return sprintf(" INSERT INTO tbassignationdetail " .
            "(iddetailassignation,idassignation,state,labnote,classzone,notefinalexam,note,examdescription,idcourse,section,idschoolyear,year,index,problemdetail,labgroup) ".
            "   VALUES(nextval('numtransacciondetalle'),%d,1,0,0,0,0,'','%s','%s','%s','%s','%s','','%s');" .
            "", $mTransaccion,$mCursosInsposcurso, $mCursosInsposseccion, $periodo, $anio, $index,$grupoLab);
    }

    /**
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #7
     * @línea   #238
     */
    function funInstertarAsignacion_insert4($mTransaccion, $fechaAsigna, $mCursosInsposcurso, $mCursosInsposseccion, $periodo, $anio, $problema,$index,$grupoLab) {
        return sprintf(" INSERT INTO tbassignationauditdetail " .
            " (idassignation,assignationdate,idcourse,section,idschoolyear,year,problemdetail,index,labgroup) " .
            "   VALUES(%d,'%s','%s','%s','%s','%s','%s',%d,'%s');" .
            "", $mTransaccion, $fechaAsigna, $mCursosInsposcurso, $mCursosInsposseccion, $periodo, $anio, $problema,$index,$grupoLab);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #8
     * @línea   #255

     */
    function funInstertarAsignacion_select2($usuarioid, $periodo, $charFec, $carrera) {
        return sprintf("SELECT *
                        from asignacioncierre

                        WHERE usuarioid = '%s' " .
            " AND periodo='%s'
              AND to_char(fechaasignacion,'YYYY') = '%s' " .
            " AND carrera = '%s'

            ", $usuarioid, $periodo, $charFec, $carrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInstertarAsignacion
     * @ref #9
     * @línea   #263
     */
    function funInstertarAsignacion_insert5($usuarioid, $carrera, $fechains, $periodo, $mTransaccion, $fechaAsigna) {
        return sprintf(" INSERT INTO asignacioncierre " .
            "   VALUES('%s','%s','%s','%s',%d,'%s') " .
            "", $usuarioid, $carrera, $fechains, $periodo, $mTransaccion, $fechaAsigna);
    }

    /**
     * commit en @línea   #286
     */

    /**
     * rollback en @línea   #290
     */

    /**
     * end en @línea   #300
     */

    /**
     * begin en @línea   #313
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInsertaCursosNoAsignados
     * @ref #10
     * @línea   #323
     */
    function funInsertaCursosNoAsignados_select1($usuarioid, $carrera, $periodo, $anio) {

        return sprintf(" SELECT usuarioid
                         FROM asignacionnorealizada " .
            " WHERE usuarioid ='%s'
                AND carrera = '%s' " .
            "   AND periodo='%s'
                AND to_char(fechaasignacion,'YYYY')='%s' " .
            " ", $usuarioid, $carrera, $periodo, $anio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInsertaCursosNoAsignados
     * @ref #11
     * @línea   #332
     */
    function funInsertaCursosNoAsignados_delete1($usuarioid, $carrera, $periodo, $anio) {
        return sprintf(" DELETE FROM asignacionnorealizada
                         WHERE usuarioid = '%s'" .
            "   AND carrera = '%s'
                AND periodo = '%s'
                AND to_char(fechaasignacion,'YYYY')='%s' " .
            " ", $usuarioid, $carrera, $periodo, $anio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  funInsertaCursosNoAsignados
     * @ref #12
     * @línea   #354
     */
    function funInsertaCursosNoAsignados_insert1($usuarioid, $carrera, $periodo, $fechaAsig, $mCursosInsposcurso, $mTransaccion, $anio) {
        return sprintf(" INSERT INTO asignacionnorealizada
                            VALUES('%s','%s','%s','%s','%s',%d,'%s'); " .
            " ", $usuarioid, $carrera, $periodo, $fechaAsig, $mCursosInsposcurso, $mTransaccion, $anio);
    }

    /**
     * commit en @línea   #366
     */

    /**
     * rollback en @línea   #371
     */

    /**
     * end en @línea   #382
     */


}
//fin consultas respecto a la versión 9.0

?>