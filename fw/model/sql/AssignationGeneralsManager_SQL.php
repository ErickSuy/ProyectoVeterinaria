<?php

/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 20/10/2014
 * Time: 06:36 AM
 */

include_once("General_SQL.php");

class AssignationGeneralsManager_SQL extends General_SQL
{
    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ec.extension tipo char, ec.extension = 'valor'
     * @functionOrigen  VerInscripcion
     * @ref #1
     * @línea   #79
     */
    function VerInscripcion_select1_1()
    {
        return " AND ec.extension = ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -  $mensaje_extesion."'".$_EXTENSION."'"
     * @functionOrigen  VerInscripcion
     * @ref #2
     * @línea   #83
     */
    function VerInscripcion_select1_3($mensaje_extesion, $_EXTENSION)
    {
        return $mensaje_extesion . "'" . $_EXTENSION . "'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * -   $mensaje_extesion."'".$_EXTENSION_ITUGS."'"
     * @functionOrigen  VerInscripcion
     * @ref #3
     * @línea   #84
     */
    function VerInscripcion_select1_4($mensaje_extesion, $_EXTENSION_ITUGS)
    {
        return $mensaje_extesion . "'" . $_EXTENSION_ITUGS . "'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ec.extension tipo char, ec.extension = 'valor'
     * @functionOrigen  VerInscripcion
     * @ref #4
     * @línea   #94
     */
    function VerInscripcion_select1($mUsuario, $mCarrera, $mAnio/*, $condicion_extension*/)
    {
        /**
         * Código comentado por Edwin Saban 08/03/2012.
         *
         * return sprintf("SELECT ic.fecha,ec.extension
         * FROM inscripcioncarrera ic
         * JOIN estudiantecarrera ec
         * ON (ic.usuarioid = ec.usuarioid AND ic.carrera = ec.carrera)
         * WHERE ic.usuarioid='%s'
         * AND ic.carrera='%s'
         * AND ic.anio='%s'
         * %s
         * ", $mUsuario, $mCarrera, $mAnio, $condicion_extension);
         */

        /**
         * Codigo insertado por Edwin Saban, 08/03/2012
         *
         */
        return sprintf("SELECT ic.fecha,ec.extension
                           FROM inscripcioncarrera ic
                           JOIN estudiantecarrera ec
                             ON (ic.usuarioid = ec.usuarioid AND ic.carrera = ec.carrera)
                           WHERE ic.usuarioid='%s'
                            AND ic.carrera='%s'
                            AND ic.anio='%s'
                        ", $mUsuario, $mCarrera, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  VerAccesos
     * @ref #5
     * @línea   #121
     */
    function VerAccesos_select1($mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $mCarrera, $mPeriodo, $mAnio)
    {
        return sprintf(" SELECT contador
                         FROM accesositio
                         WHERE usuarioid='%s'
                            AND grupo=%d
                            AND sitio=%d
                            AND carrera='%s'
                            AND periodo='%s'
                            AND anio='%s'
                           ", $mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $mCarrera, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ActualizarAccesos
     * @ref #6
     * @línea   #140
     */
    function ActualizarAccesos_update1($mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $mCarrera, $mPeriodo, $mAnio)
    {
        return sprintf("UPDATE accesositio
                        SET contador=contador+1
                        WHERE usuarioid='%s'
                            AND grupo=%d
                            AND sitio=%d
                            AND carrera='%s'
                            AND periodo='%s'
                            AND anio='%s';
					     ", $mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $mCarrera, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ActualizarAccesos
     * @ref #7
     * @línea   #153
     */
    function ActualizarAccesos_insert1($mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $mCarrera, $mPeriodo, $mAnio, $_SESSIONprincipal)
    {
        return sprintf("INSERT INTO accesositio (usuarioid,grupo,sitio,carrera,periodo,anio,rehabilitacion,contador,carreraprincipal)
                          VALUES('%s',%d,%d,'%s','%s','%s',0,1,'%s')
						 ", $mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $mCarrera, $mPeriodo, $mAnio, $_SESSIONprincipal);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ActualizarAccesos
     * @ref #8
     * @línea   #166
     */
    function ActualizarAccesos_update2($mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $_SESSIONcarreraCambiante, $mPeriodo, $mAnio)
    {
        return sprintf("UPDATE accesositio
                        SET carreraprincipal = FALSE
                        WHERE usuarioid='%s'
                            AND grupo=%d
                            AND sitio=%d
                            AND carrera='%s'
                            AND periodo='%s'
                            AND anio='%s';
					     ", $mUsuario, $GRUPO_ESTUDIANTE, $ASIGNACIONREGULAR, $_SESSIONcarreraCambiante, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  VerBloqueos
     * @ref #9
     * @línea   #182
     */
    function VerBloqueos_select1($mUsuario, $fecha)
    {
        return sprintf("select t.tipo, t.descripcion
                        from tipobloqueo t, estudiantebloqueo e
                        where usuarioid='%s'
                            and fechainicio <= '%s'
                            and (fechafin >= '%s' OR fechafin IS NULL)
                            and t.tipo = e.tipo
					   ", $mUsuario, $fecha, $fecha);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  CargarDatosAcademicos
     * @ref #10
     * @línea   #207
     */
    function CargarDatosAcademicos_select1($fechaAsigna, $tipo_proceso_Asignacion)
    {
        /**
         * Código comentariado por Edwin Saban 08/03/2012
         *
         * return sprintf("SELECT periodo, anio, porcreditos, porcentajezonaminima,porcentajezonaminimacongelado,fechainicioasig,fechafinasig,fechainscripcion,preasignacion,extension
         * FROM periodovigencia
         * WHERE activo=1
         * AND periodo in ('01','05')
         * AND fechainicioasig <= '%s'
         * AND fechafinasig >= '%s'
         * AND tipoproceso = %d
         * ", $fechaAsigna, $fechaAsigna, $tipo_proceso_Asignacion);
         *
         */

        /**
         * Código insertado por Edwin Saban 08/03/2012
         *
         */
        return sprintf("SELECT PV.periodo, PV.anio, PV.porcreditos, PV.porcentajezonaminima,
                        PV.porcentajezonaminimacongelado,PV.fechainicioasig,PV.fechafinasig,
                        PV.fechainscripcion,PV.preasignacion,PVR.extension,PVR.horainicio, PVR.horafin,
                        PVR.datos_actualizacion,PVR.fechainicioactualizacion,PVR.fechafinvalidaactualizacion,
                        PVR.validar_datosactualizacion
                        FROM periodovigencia PV
                          JOIN extensionperiodovigencia PVR
                             ON (PV.fechainicioasig = PVR.fechainicioasig AND PV.periodo=PVR.periodo AND PV.anio=PVR.anio AND PV.tipoproceso=PVR.tipoproceso AND PV.fechafinasig=PVR.fechafinasig)
                        WHERE PV.ACTIVO=1
                            AND PV.PERIODO IN('01','05')
                            AND PV.TIPOPROCESO=%d
                            AND PV.FECHAINICIOASIG<='%s'
                            AND PV.FECHAFINASIG>='%s'
                       ORDER BY PV.FECHAINICIOASIG,PVR.EXTENSION;
                       ", $tipo_proceso_Asignacion, $fechaAsigna, $fechaAsigna);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  CargarDatosAcademicos
     * @ref #11
     * @línea   #228
     */
    function CargarDatosAcademicos_select2($mUsuario, $mCarrera, $mAnio)
    {
        return sprintf("select fecha
                        from inscripcioncarrera
                        where usuarioid='%s'
                            and carrera='%s'
                            and anio='%s';
                        ", $mUsuario, $mCarrera, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  VerAsignacionProblema
     * @ref #12
     * @línea   #256
     */
    function VerAsignacionProblema_select1($mUsuario, $mCarrera, $mPeriodo, $mAnio)
    {
        return sprintf("SELECT distinct (n.curso), c.nombre " .
            "FROM asignacionnorealizada n, curso c " .
            "WHERE n.usuarioid='%s'
                            AND n.carrera='%s'
                            AND n.periodo='%s' " .
            "   AND n.curso=c.curso
                            AND n.anio='%s'
                        ", $mUsuario, $mCarrera, $mPeriodo, $mAnio);
    }

    /**
     * @functionOrigen  VerNotasCursosAsignados
     * @ref #13
     * @línea   #281
     */
    function VerNotasCursosAsignados_select1($mUsuario, $mCarrera, $periodo, $anio)
    {
        return sprintf("SELECT a.idstudent,a.idcareer,a.idassignation,a.assignationdate,e.name,e.surname " .
            "FROM tbassignation a, tbassignationdetail ad, tbstudent e " .
            "WHERE a.idstudent='%s'
                            AND a.idcareer='%s'
                            AND ad.idschoolyear='%s'
                            AND ad.year='%s' " .
            "   AND a.idassignation = ad.idassignation " .
            "   AND a.idstudent=e.idstudent
                        ", $mUsuario, $mCarrera, $periodo, $anio);
    }

    /**
     * @functionOrigen  VerDetalleNotas
     * @ref #14
     * @línea   #335
     */

    function VerDetalleNotas_select0($mTransaccion, $periodo, $anio)
    {
        // Manejar los cursos cuyo idcareer en horariodetalle es -1, que significa para ambas carreras
        return sprintf("SELECT distinct hd.idcourse,hd.index,hd.idscheduletype " .
            "FROM tbassignationdetail ad, tbscheduledetail hd " .
            "WHERE ad.idassignation='%s' " .
            "AND hd.idschoolyear=ad.idschoolyear AND hd.year=ad.year AND (hd.idcourse=ad.idcourse and hd.index=ad.index) AND hd.idscheduletype=2 " .
            "AND ad.idschoolyear='%s' AND ad.year='%s'", $mTransaccion, $periodo, $anio);
    }

    function VerDetalleNotas_select1($carnet,$carrera,$pensum,$periodo, $anio)
    {
        /*
        return sprintf("SELECT distinct h.idcourse,h.index,c.name,h.section,ad.labnote,ad.classzone,ad.notefinalexam," .
            "   ad.idfinalexamstate,h.idactstate,h.acttype, c.idschool,a.idcareer " .
            "FROM tbassignation a,tbassignationdetail ad, tbscheduledetail hd,tbschedule h, tbcourse c " .
            "WHERE ad.idassignation=%d
                            AND a.idassignation=ad.idassignation
                            AND (ad.idcourse=h.idcourse and ad.index=h.index)
                            AND ad.section=h.section
                            AND a.idcareer=h.idcareer" .
            "   AND hd.idschoolyear=h.idschoolyear
                            AND hd.year=h.year
                            AND (hd.idcourse=h.idcourse and hd.index=h.index)
                            AND hd.section=h.section
                            AND hd.idcareer=h.idcareer
                            AND hd.idscheduletype=1 " .
            "   AND (ad.idcourse=c.idcourse and ad.index=c.index)
                            AND ad.idschoolyear=h.idschoolyear
                            AND ad.year=h.year " .
            "   AND h.idschoolyear=%d
                            AND h.year=%d
                        ", $mTransaccion, $periodo, $anio);
        */
        //return sprintf("select * from f_getassignationdetail_notes_info(%d,%d,%d,%d,%d);",$carnet,$carrera,$pensum,$anio,$periodo);
        return sprintf("select * from f_getassignationdetail_notes_updatedinfo(%d,%d,%d,%d,%d);",$carnet,$carrera,$pensum,$anio,$periodo);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  VerAsignacion
     * @ref #15
     * @línea   #368
     */
    function VerAsignacion_select1($mUsuario, $mCarrera, $mPeriodo, $mAnio)
    {
        return sprintf("SELECT a.usuarioid,a.carrera,a.transaccion,a.fechaasignacion,e.nombre,e.apellido " .
            "FROM asignacion a, asignaciondetalle ad, estudiante e " .
            "WHERE a.usuarioid='%s'
                            AND a.carrera='%s'
                            AND ad.periodo='%s'
                            AND ad.anio='%s' " .
            "   AND a.transaccion = ad.transaccion " .
            "   AND a.fechaasignacion = ad.fechaasignacion " .
            "   AND a.usuarioid=e.usuarioid
                        ", $mUsuario, $mCarrera, $mPeriodo, $mAnio);
    }

    /**
     * @functionOrigen  VerListadoCursosAsignados
     * @ref #16
     * @línea   #436
     */
    function VerListadoCursosAsignados_select1($mUsuario, $mCarrera, $periodo, $anio)
    {
        return sprintf("SELECT a.idstudent,a.idcareer,a.idassignation,a.assignationdate,e.name,e.surname " .
            "FROM tbassignation a, tbassignationdetail ad, tbstudent e " .
            "WHERE a.idstudent=%d
                            AND a.idcareer=%d
                            AND ad.idschoolyear=%d
                            AND ad.year=%d " .
            "   AND a.idassignation = ad.idassignation " .
            "   AND a.idstudent=e.idstudent ", $mUsuario, $mCarrera, $periodo, $anio);
    }

    /**
     * @functionOrigen  VerDetalleAsig
     * @ref #17
     * @línea   #489
     */
    function VerDetalleAsig_select1($mTransaccion)
    {
        return sprintf("SELECT a.idcourse,c.name,h.section,h.starttime,h.endtime,h.mon,h.tue,h.wed,h.thu,h.fri,h.sat,h.sun,h.idclassroom,h.building, h.idscheduletype
                        FROM tbassignationdetail a, tbscheduledetail h, tbcourse c
                        WHERE a.idassignation=%d
                            AND a.section=h.section
                            AND (a.idcourse = h.idcourse AND a.index=h.index)
                            AND (a.idcourse=c.idcourse AND a.index=c.index)
                            AND a.idschoolyear=h.idschoolyear
                            AND a.year=h.year
                            ", $mTransaccion);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   a.transaccion= '%d'
     *  -   p.pensum= '%d'
     * @functionOrigen  VerCursosAsignados
     * @ref #18
     * @línea   #522
     */
    function VerCursosAsignados_select1($mTransaccion, $mFechaAsig, $mPensum, $mCarrera)
    {
        return sprintf("SELECT DISTINCT a.curso, a.seccion, a.problema, c.nombre, p.creditos, p.prerrequisito " .
            "FROM asignaciondetalle a, curso c, cursopensum p " .
            "WHERE a.transaccion='%d'
                            AND a.fechaasignacion='%s' " .
            "   AND a.curso=c.curso
                            AND a.curso=p.curso
                            AND (p.finvigencia = '' OR p.finvigencia IS NULL) " .
            "   AND p.pensum='%d'
                            AND p.carrera='%s'" .
            "", $mTransaccion, $mFechaAsig, $mPensum, $mCarrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  calculoCreditosValidosAsignar
     * @ref #19
     * @línea   #559
     */
    function calculoCreditosValidosAsignar_select1($mAnio, $mPeriodo, $mUsuario)
    {
        return " SELECT SUM(cp.creditos) AS creditos,a.carrera
                 FROM asignacion a,asignaciondetalle ad,cursopensum cp,accesositio ac,estudiantecarrera ec
                 WHERE a.transaccion = ad.transaccion
                    and a.fechaasignacion = ad.fechaasignacion
                   AND a.carrera = cp.carrera
                   AND ad.curso = cp.curso
                   AND a.usuarioid = ac.usuarioid
                   AND a.carrera = ac.carrera
                   AND ad.periodo = ac.periodo
                   AND ad.anio = ac.anio
                   AND a.usuarioid = ec.usuarioid
                   AND a.carrera = ec.carrera
                   AND ec.pensum = cp.pensum
                   AND ac.carreraprincipal = TRUE
                   AND (cp.finvigencia = '' OR cp.finvigencia IS NULL)
                   AND ad.anio = '" . $mAnio . "'
                   and ad.periodo = '" . $mPeriodo . "'
                   AND a.usuarioid = '" . $mUsuario . "'
                   GROUP BY a.carrera; ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  calculoCreditosValidosAsignar
     * @ref #20
     * @línea   #620
     */
    function calculoCreditosValidosAsignar_select2($mUsuario, $mCarrera)
    {
        return sprintf(" SELECT (power(AVG(nota),2)* 0.002878)+(AVG(nota) * 0.3613)+35.1 AS promedio
                         FROM cursoaprobado
                         WHERE usuarioid='%s'
                            AND carrera = '%s'
                            AND curso < '1000'
                            AND forma NOT IN ('03','05','08','17')
                          ", $mUsuario, $mCarrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  calculoCreditosValidosAsignar
     * @ref #21
     * @línea   #633
     */
    function calculoCreditosValidosAsignar_select3($promedioGeneral, $promedioGeneral1)
    {


        return sprintf(" SELECT creditosmaximo
                         FROM creditosasignacion
                         WHERE %d <= rangosuperior
                         AND %d >= rangoinferior
                          ", $promedioGeneral, $promedioGeneral1);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  calculoCreditosValidosAsignar
     * @ref #22
     * @línea   #648
     */
    function calculoCreditosValidosAsignar_select4($mUsuario, $mCarrera, $mPeriodo, $mAnio)
    {
        return sprintf(" SELECT cantidadcreditos
                         FROM creditosautorizadosJD
                         WHERE usuarioid = '%s'
                            AND carrera = '%s'
                            AND periodo = '%s'
                            AND anio = '%s'
                       ", $mUsuario, $mCarrera, $mPeriodo, $mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  datos_estudiante
     * @ref #23
     * @línea   #693
     */
    function datos_estudiante_select1($_SESSIONdatosGeneralesusuarioid, $_SESSIONdatosGeneralescarrera)
    {
        return sprintf("SELECT e.usuarioid,e.nombre,e.apellido,c.nombre as nomcarrera
                        FROM estudiantecarrera ec
                            JOIN estudiante e ON (ec.usuarioid = e.usuarioid)
                            JOIN carrera c ON (ec.carrera = c.carrera)
                        WHERE ec.usuarioid = '%s'
                            AND ec.carrera = '%s'
                   ", $_SESSIONdatosGeneralesusuarioid, $_SESSIONdatosGeneralescarrera
        );
    }
}

?>
