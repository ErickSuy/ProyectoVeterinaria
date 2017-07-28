<?php

include_once("General_SQL.php");

/**
 *
 * PostgreSQL @version 9.0
 */
Class D_LoadNotesScheduleManager_SQL extends General_SQL {

    /**
     * @cambios
     * @functionOrigen  HacerHorario
     * @ref #1
     * @línea   #90
     */
    function HacerHorario_select1($periodo, $anio) {
        return sprintf("select distinct c.idcourse,c.name,h.section,case when (hd.building='0' or hd.building='' or hd.building is null) then '-' else hd.building end as building,
                            case when (hd.idclassroom=0 or hd.idclassroom is null) then '-' else hd.idclassroom::text end as idclassroom,to_char(hd.starttime, 'HH24:MI') as starttime,to_char(hd.endtime, 'HH24:MI') as endtime,hd.mon,hd.tue,hd.wed,hd.thu,hd.fri,hd.sat,hd.sun,hd.idscheduletype, (t.name||' '||t.surname) as nombrecat, case when hd.idcareer=2 then 'MEDICINA VETERINARIA' else 'ZOOTECNIA' end as career
                        from tbschedule h, tbcourse c, tbscheduledetail hd left join tbteacher t on hd.idteacher= t.idteacher
                        where h.idschoolyear = %d and
                            h.year    = %d      and
                            (h.idcourse   = hd.idcourse   and h.index=hd.index) and
                            h.section = hd.section and
                            h.idschoolyear = hd.idschoolyear and
                            h.year    = hd.year    and
                            h.idcareer = hd.idcareer and
                            (c.idcourse = h.idcourse and c.index = h.index)
UNION
select distinct c.idcourse,c.name,h.section,case when (hd.building='0' or hd.building='' or hd.building is null) then '-' else hd.building end as building,
                            case when (hd.idclassroom=0 or hd.idclassroom is null) then '-' else hd.idclassroom::text end as idclassroom,to_char(hd.starttime, 'HH24:MI') as starttime,to_char(hd.endtime, 'HH24:MI') as endtime,hd.mon,hd.tue,hd.wed,hd.thu,hd.fri,hd.sat,hd.sun,hd.idscheduletype, (t.name||' '||t.surname) as nombrecat, case when hd.idcareer=2 then 'MEDICINA VETERINARIA' else 'ZOOTECNIA' end as career
                        from tbschedule h, tbcourse c, tbmodule_scheduledetail hd left join tbteacher t on hd.idteacher= t.idteacher
                        where h.idschoolyear = %d and
                            h.year    = %d       and
                            (h.idcourse   = hd.idcourse   and h.index=hd.index) and
                            h.section = hd.section and
                            h.idschoolyear = hd.idschoolyear and
                            h.year    = hd.year    and
                            h.idcareer = hd.idcareer and
                            (c.idcourse = h.idcourse and c.index = h.index)
                           order by idcourse,section,idscheduletype
                        ", $periodo, $anio, $periodo, $anio);
    }

    function HacerHorarioCurso_select1($periodo, $anio,$curso,$seccion,$index) {
        return sprintf("select distinct c.idcourse,c.name,h.section,hd.building,
                            hd.idclassroom,hd.starttime,hd.endtime,hd.mon,hd.tue,hd.wed,hd.thu,hd.fri,hd.sat,hd.sun,hd.idscheduletype, (t.name||' '||t.surname) as nombrecat
                        from tbschedule h, tbcourse c, tbscheduledetail hd left join tbteacher t on hd.idteacher= t.idteacher
                        where h.idschoolyear = %d and
                            h.year    = %d       and
                            (h.idcourse=%d and h.index=%d) and
                            h.section='%s' and
                            (h.idcourse   = hd.idcourse   and h.index=hd.index) and
                            h.section = hd.section and
                            h.idschoolyear = hd.idschoolyear and
                            h.year    = hd.year    and
                            (c.idcourse = h.idcourse and c.index = h.index)
                        order by c.idcourse,h.section,hd.idscheduletype;
                        ", $periodo, $anio,$curso,$index,$seccion);
    }

    function HacerHorarioCurso_select2($index,$curso,$anio,$periodo,$carrera) {
        return sprintf("select * from f_get_courseschedule_bycareer(%d,%d,%d,%d,%d);", $index,$curso,$anio,$periodo,$carrera);
    }

    /**
     * @cambios
     * @functionOrigen  HacerHorario
     * @ref #2
     * @línea   #112

     */
    function HacerHorario_update1($periodo, $anio) {
        return sprintf("update tbproc_processactivation set state=1
                        where " . "state  = 0    and "
            . "schoolyear = '%s' and "
            . "year    = '%s';
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

    function COAC_DarListadoDeCursos_selec1($anio,$periodo,$carrera=-1) {
        switch($carrera) {
            case VETERINARIA:
                return sprintf("select * from f_get_act_courselist(%d,%d,%d);",$anio,$periodo,$carrera);
                break;
            case ZOOTECNIA:
                return sprintf("select * from f_get_act_courselist(%d,%d,%d);",$anio,$periodo,$carrera);
                break;
            case -1:
                return sprintf("select * from f_get_act_courselist(%d,%d,%d) union select * from f_get_act_courselist(%d,%d,%d) union select * from f_get_act_courselist(%d,%d,%d) order by idcareer,cycle;",$anio,$periodo,VETERINARIA,$anio,$periodo,ZOOTECNIA,$anio,$periodo,1);
                break;
        }
    }

    /**
     * @cambios
     * @functionOrigen  DarListadoDeCursos
     * @ref #8
     * @línea   #402
     */
    function DarListadoDeCursos_select1($periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario) {
        /**
         *  -- ORIGINAL CON FILTRADO DE SECCION --
         * select distinct(hd.idcourse),c.index,c.name,hd.section,h.idactstate as state
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
        order by 1;
         */
        return sprintf("select distinct(hd.idcourse),c.index,c.name,h.idactstate as state,hd.idcareer
                         from tbscheduledetail hd,tbschedule h,tbcourse c
                         where hd.idschoolyear = %d
                             and hd.year = %d
                             and hd.idscheduletype = 1
							 and h.idschoolyear = hd.idschoolyear
                             and h.year = hd.year
                             and h.section = hd.section
                             and h.idcareer=hd.idcareer
                             and (h.index=hd.index and h.idcourse = hd.idcourse)
                             and h.idschoolyear = %d
                             and h.year = %d
                             and hd.idteacher= %d
                             and (c.idcourse = hd.idcourse and c.index=hd.index)
                             union

                             select distinct(hd.idcourse),c.index,c.name,h.idactstate as state,hd.idcareer
                         from tbmodule_scheduledetail hd,tbschedule h,tbcourse c
                         where hd.idschoolyear = %d
                             and hd.year = %d
                             and hd.idscheduletype = 1
							 and h.idschoolyear = hd.idschoolyear
                             and h.year = hd.year
                             and h.section = hd.section
                             and h.idcareer=hd.idcareer
                             and (h.index=hd.index and h.idcourse = hd.idcourse)
                             and h.idschoolyear = %d
                             and h.year = %d
                             and hd.idteacher= %d
                             and (c.idcourse = hd.idcourse and c.index=hd.index)
                             order by 1;", $periodo, $anio, $periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario
            , $periodo, $anio, $periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario);
    }

    /**
     * @cambios
     * @functionOrigen  DarListadoDeCursos
     * @ref #9
     * @línea   #472
     */
    function DarListadoDeCursos1_select1($periodo, $anio, $_SESSIONsUsuarioDeSesionmUsuario) {
        /**
         *  -- ORIGINAL
         * select distinct hd.idcourse,c.name,c.index,hd.section,h.idactstate,hd.idscheduletype" .
        " from tbscheduledetail hd,tbschedule h,tbcourse c, (select distinct curso from cursozona" .
        " where (trim(fechafinvigencia)='' or fechafinvigencia is null or (periodo='" . $periodo . "' and anio='" . $anio .
        "')) and anio>='2010') cz where hd.year>='2010' and hd.idschoolyear = '" . $periodo .
        "' and hd.year = '" . $anio . "' and (hd.idscheduletype=2 or (hd.idscheduletype=1 and (select count(*) from tbassignationdetail ad" .
        " where ad.idschoolyear=hd.idschoolyear and ad.year=hd.year and ad.idcourse=hd.idcourse and ad.index=hd.index and ad.section=hd.section)>0))" .
        " and hd.idcourse=cz.curso::smallint  and h.idcourse = hd.idcourse and h.index=hd.index and h.idschoolyear = hd.idschoolyear and h.year = hd.year" .
        " and h.section = hd.section and h.idschoolyear = hd.idschoolyear and h.year = hd.year and hd.idteacher= '" .
        $_SESSIONsUsuarioDeSesionmUsuario . "' and c.idcourse = hd.idcourse and c.index=hd.index order by hd.idcourse
         */
        return "select distinct hd.idcourse,c.name,c.index,h.idactstate,hd.idscheduletype,hd.idcareer,1 as type
                from tbscheduledetail hd,tbschedule h,tbcourse c,(select distinct curso
                                                                  from cursozona
	                                                              where (trim(fechafinvigencia)='' or fechafinvigencia is null or (periodo='".$periodo."' and anio='".$anio."')) and anio>='2010') cz
                where hd.year>='2010'
                and hd.idschoolyear = '".$periodo."'
                and hd.year = '".$anio."'
                and ((hd.idscheduletype=1
                      and ( select count(*)
                            from tbassignation a,tbassignationdetail ad
							where ad.idschoolyear=hd.idschoolyear and
							 ad.year=hd.year and
							 ad.idcourse=hd.idcourse and
							 ad.index=hd.index and
							 a.idcareer=hd.idcareer and
							 ad.section=hd.section and
							 a.idassignation=ad.idassignation)>0))
                and hd.idcourse=cz.curso::smallint
                and h.idcourse = hd.idcourse
                and h.index=hd.index
                and h.idschoolyear = hd.idschoolyear
                and h.year = hd.year
                and h.section = hd.section
                and h.idcareer =hd.idcareer
                and h.idschoolyear = hd.idschoolyear
                and h.year = hd.year
                and hd.idteacher=".$_SESSIONsUsuarioDeSesionmUsuario."
                and c.idcourse = hd.idcourse
                and c.index=hd.index
                union
                select distinct hd.idcourse,c.name,c.index,h.idactstate,hd.idscheduletype,hd.idcareer,2 as type
                from tbmodule_scheduledetail hd,tbschedule h,tbcourse c,(select distinct curso
                                                                  from cursozona
	                                                              where (trim(fechafinvigencia)='' or fechafinvigencia is null or (periodo='".$periodo."' and anio='".$anio."')) and anio>='2010') cz
                where hd.year>='2010'
                and hd.idschoolyear = '".$periodo."'
                and hd.year = '".$anio."'
                and ((hd.idscheduletype=1
                      and ( select count(*)
                            from tbassignation a,tbassignationdetail ad
							where ad.idschoolyear=hd.idschoolyear and
							 ad.year=hd.year and
							 ad.idcourse=hd.idcourse and
							 ad.index=hd.index and
							 a.idcareer=hd.idcareer and
							 ad.section=hd.section and
							 a.idassignation=ad.idassignation)>0))
                and hd.idcourse=cz.curso::smallint
                and h.idcourse = hd.idcourse
                and h.index=hd.index
                and h.idschoolyear = hd.idschoolyear
                and h.year = hd.year
                and h.section = hd.section
                and h.idcareer =hd.idcareer
                and h.idschoolyear = hd.idschoolyear
                and h.year = hd.year
                and hd.idteacher=".$_SESSIONsUsuarioDeSesionmUsuario."
                and c.idcourse = hd.idcourse
                and c.index=hd.index";
    }

    function DarListadoDocentesCurso_select1($curso,$index,$carrera, $anio,$periodo, $_SESSIONsUsuarioDeSesionmUsuario, $tipo) {
        if((int)$tipo==1){
            return sprintf("select distinct idteacher from tbscheduledetail where idcourse=%d and index=%d and year=%d and idschoolyear=%d and idcareer=%d and idscheduletype=%d and idteacher!=%d;",
                $curso,$index,$anio,$periodo,$carrera,1,$_SESSIONsUsuarioDeSesionmUsuario);
        } else{
            return sprintf("select distinct idteacher from tbmodule_scheduledetail where idcourse=%d and index=%d and year=%d and idschoolyear=%d and idcareer=%d and idscheduletype=%d and idteacher!=%d;",
                $curso,$index,$anio,$periodo,$carrera,1,$_SESSIONsUsuarioDeSesionmUsuario);
        }

    }

    /**
     * @cambios
     *  -   anio='" . $anio . "'
     * @functionOrigen  AsignadosDelCurso
     * @ref #10
     * @línea   #510
     */
    function AsignadosDelCurso_select1($anio, $periodo, $curso, $carrera,$index) {
        /**
         * -- ORIGINAL CON SECCION --
         * elect count(*) as numero
        from tbassignationdetail " .
        "where year=" . $anio .
        " and idschoolyear=" . $periodo .
        " and (idcourse=" . $curso . " and index=".$index.")" .
        " and section='" . $seccion . "';
         */
        return sprintf("select count(*) as numero
                 from tbassignation a, tbassignationdetail ad
        where year=%d
         and idschoolyear=%d
         and (idcourse=%d and index=%d)
         and idcareer=%d
         and a.idassignation=ad.idassignation",$anio, $periodo, $curso,$index, $carrera);
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
