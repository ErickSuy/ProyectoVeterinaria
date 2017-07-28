<?php

include_once("General_SQL.php");

/**
 *
 * PostgreSQL @version 9.0 
 */
Class D_CourseListManager_SQL extends General_SQL {

/**
 * @cambios
 * @functionOrigen  HacerHorario
 * @ref #1
 * @línea   #90
 */
    function HacerHorario_select1($periodo, $anio, $DIGITO_DIPLOMADO) {
        return sprintf("select distinct c.curso,c.nombre,h.seccion,hd.edificio,
                            hd.salon,hd.horainicio,hd.horafinal,hd.dias,hd.tipo, (t.nombre||' '||t.apellido) as nombrecat 
                        from horario h, curso c, horariodetalle hd left join tmppersonal t on hd.personal= t.personal 
                        where h.periodo = '%s' and 
                            h.anio    = '%s'       and 
                            h.curso   = hd.curso   and 
                            h.seccion = hd.seccion and 
                            h.periodo = hd.periodo and 
                            h.anio    = hd.anio    and 
                            ((substring(c.curso,0,2)= '%s' 
                                AND substring(c.curso,2,4) = substring(h.curso,2,4)) 
                                OR c.curso = h.curso )
                        order by 2,3,9;
                        ", $periodo, $anio, $DIGITO_DIPLOMADO);
    }

/**
 * @cambios
 * @functionOrigen  HacerHorario
 * @ref #2
 * @línea   #112

 */
    function HacerHorario_update1($periodo, $anio) {
        return sprintf("update periodovigencia set estadohorariocursos='0' 
                        where " . "activo  = 1    and "
                        . "periodo = '%s' and "
                        . "anio    = '%s';
                        ", $periodo, $anio);
    }

/**
 * @cambios
 *  -   error no existe la tabla horarioexamen
 * @functionOrigen  HacerHorarioExamen
 * @ref #3
 * @línea   #234
 */
    function HacerHorarioExamen_select1($periodo, $_SESSIONsUsuarioDeSesionmUsuario) {
        return sprintf(" SELECT h.codcurso,c.nombre,h.jornada," .
                        "   h.diasemana,h.dia,h.salon,h.hora " .
                        "FROM horarioexamen h,curso c,asignacion a " .
                        "WHERE h.periodo = '%s' " .
                        "   AND h.codcurso = c.codcurso " .
                        "   AND h.periodo = a.periodo " .
                        "   AND h.codcurso = a.codcurso " .
                        "   AND a.userid = '%s' 
                        ", $periodo, $_SESSIONsUsuarioDeSesionmUsuario);
    }

/**
 * @cambios
 * @functionOrigen  HacerHorarioExamen
 * @ref #4
 * @línea   #248
 */
    function HacerHorarioExamen_select1_1() {
        return "AND to_char(a.fechasignacion,'YYYY') = h.anio ";
    }

/**
 * @cambios
 *  -   error no existe la tabla horarioexamen
 * @functionOrigen  HacerHorarioExamen
 * @ref #5
 * @línea   #252
 */
    function HacerHorarioExamen_select1_2($query_examenes,$agrega,$anio) {
        return $query_examenes . $agrega . sprintf( "AND to_char(a.fechasignacion,'YYYY') = '%d'",$anio);
    }

/**
 * @cambios
 * @functionOrigen  DarHorarioFinales
 * @ref #6
 * @línea   #281
 */
    function DarHorarioFinales_select1($periodo, $anio) {
        return sprintf("SELECT e.curso,c.nombre,e.jornada,to_char(d.fecha,'DD') as dia,e.salones,e.edificio,e.horainicio,d.fecha
                        FROM examencurso e,curso c,dia d
                        WHERE e.periodo = '%s' AND
                              e.anio = '%s'    AND
                              d.anio = '%s'    AND 
                              d.periodo='%s' AND 
                              d.dia=e.dia AND
                              e.curso = c.curso 
                        order by 2;
                       ", $periodo, $anio, $anio, $periodo);
    }

/**
 * @cambios
 * @functionOrigen  DarHorarioFinales
 * @ref #7
 * @línea   #290
 */
    function DarHorarioFinales_update1($periodo, $anio) {
        return sprintf("update periodovigencia set estadohorarioexamenes='0' "
                        . "where periodo = '%s' and anio='%s' and activo=1;", $periodo, $anio);
    }

/**
 * @cambios
 * @functionOrigen  DarListadoDeCursos
 * @ref #8
 * @línea   #402
 */
    function DarListadoDeCursos_select1($periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario) {
        return sprintf("select distinct(hd.idcourse),c.index,c.name,hd.section,h.state
                         from tbscheduledetail hd,tbschedule h,tbcourse c
                         where hd.idschoolyear = '%s'
                             and hd.year = '%s'
                             and hd.idscheduletype = 1
							 and h.idschoolyear = hd.idschoolyear
                             and h.year = hd.year
                             and h.section = hd.section
                             and (h.index=hd.index and h.idcourse = hd.idcourse)
                             and h.idschoolyear = '%s'
                             and h.year = '%s'
                             and hd.idteacher= '%s'
                             and (c.idcourse = hd.idcourse and c.index=hd.index)
                             order by 1;", $periodo, $anio, $periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario);
    }

/**
 * @cambios
 * @functionOrigen  DarListadoDeCursos
 * @ref #9
 * @línea   #472
 */
    function DarListadoDeCursos1_select1($periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario) {
        return "select distinct hd.curso,c.nombre,hd.seccion,h.estado,hd.tipo" .
                " from horariodetalle hd,horario h,curso c, (select distinct curso from cursozona" .
                " where (trim(fechafinvigencia)='' or fechafinvigencia is null or (periodo='" . $periodo . "' and anio='" . $anio .
                "')) and anio>='2010') cz where hd.anio>='2010' and hd.periodo = '" . $periodo .
                "' and hd.anio = '" . $anio . "' and (hd.tipo=2 or (hd.tipo=1 and (select count(*) from asignaciondetalle ad" .
                " where ad.periodo=hd.periodo and ad.anio=hd.anio and ad.curso=hd.curso and ad.seccion=hd.seccion)>0))" .
                " and hd.curso=cz.curso  and h.curso = hd.curso and h.periodo = hd.periodo and h.anio = hd.anio" .
                " and h.seccion = hd.seccion and h.periodo = hd.periodo and h.anio = hd.anio and hd.personal= '" .
                $_SESSIONsUsuarioDeSesionmUsuario . "' and c.curso = hd.curso order by curso";
    }

/**
 * @cambios
 *  -   anio='" . $anio . "'
 * @functionOrigen  AsignadosDelCurso
 * @ref #10
 * @línea   #510
 */
    function AsignadosDelCurso_select1($anio, $periodo, $curso, $seccion,$index) {
        return "select count(*) as numero 
                 from tbassignationdetail " .
                "where year=" . $anio .
                " and idschoolyear=" . $periodo .
                " and (idcourse=" . $curso . " and index=".$index.")" .
                " and section='" . $seccion . "';";
    }

/**
 * @cambios
 * @functionOrigen  HorarioVac
 * @ref #11
 * @línea   #558
 */
    function HorarioVac_select1($periodo, $anio, $DIGITO_DIPLOMADO) {
        return sprintf("select c.curso,c.nombre,h.seccion,hd.edificio,
                            hd.salon,hd.horainicio,hd.horafinal,hd.dias,hd.tipo, (t.nombre||' '||t.apellido) as nombrecat 
                        from horario h,curso c,horariodetalle hd  left join tmppersonal t on hd.personal= t.personal where 
                            h.periodo = '%s'       and 
                            h.anio    = '%s'       and 
                            h.curso   = hd.curso   and 
                            h.seccion = hd.seccion and 
                            h.periodo = hd.periodo and 
                            h.anio    = hd.anio    and 
                            ((substring(c.curso,0,2)= '%s' AND substring(c.curso,2,4) = substring(h.curso,2,4)) OR c.curso = h.curso )
                        order by 2,3,9;", $periodo, $anio, $DIGITO_DIPLOMADO);

    }

/**
 * @cambios
 * @functionOrigen  HorarioVac
 * @ref #12
 * @línea   #580
 */
    function HorarioVac_update1($periodo, $anio) {
        return sprintf("update periodovigencia set estadohorariocursos='0' where "
                        . "activo  = 1    and "
                        . "periodo = '%s' and "
                        . "anio    = '%s';", $periodo, $anio);
    }

}
?>
