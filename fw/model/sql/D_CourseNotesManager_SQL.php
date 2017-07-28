<?php
include_once("General_SQL.php");

/**
 *
 * PostgreSQL @version 9.0 
 */
Class D_CourseNotesManager_SQL extends General_SQL {

    /**
     * @functionOrigen  ValidaRangoFechas
     * ref #1
     * @línea   #78
     */
    function ValidaRangoFechas_select1($param_periodo, $param_anio, $curso,$carrera) {
        return sprintf("select startdate, enddate
                         from tbparameterentry
                         where active = '1'
                            and idschoolyear = %d
                            and year = %d
                            and idcourse=%d
                            and idcareer=%d;
                        ", $param_periodo, $param_anio, $curso,$carrera);

    }

    function esCursoModular($txtIndex, $txtCurso, $txtCarrera)
    {
        return sprintf("select case when f_check_modulecourse='t' then 1 else 0 end as resultado from f_check_modulecourse(%d,%d,%d);", $txtIndex, $txtCurso, $txtCarrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     *  -   anio = '%d'
     * @functionOrigen  BajarArchivo
     * ref #2
     * @línea   #111
     */
    function BajarArchivo_select1($param_periodo, $param_anio) {
        return sprintf("select downloadfile
                         from tbparameterentry
                         where idschoolyear = '%s'
                            and   year = '%d';
                        ", $param_periodo, $param_anio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  CursoDatos
     * ref #3
     * @línea   #158
     */
    function CursoDatos_select1($mCurso, $mCarrera/*$mSeccion*/, $mPeriodo, $mAnio, $mIndex) {
        /**
         *  -- ORIGINAL CON SECCIONES --
         * select c.name,h.year, 70 as zone,
        h.idschoolyear,h.year,h.lab,
        h.idactstate as state,h.acttype,
        hd.starttime,hd.endtime,
        hd.building,hd.idclassroom,hd.assignedcount
        from tbcourse c, tbschedule h, tbscheduledetail hd
        where (c.idcourse = h.idcourse and c.index=h.index)
        and (h.idcourse   = hd.idcourse and h.index=hd.index)
        and h.section = hd.section
        and h.idschoolyear = hd.idschoolyear
        and h.year    = hd.year
        and (h.idcourse   = '%s' and h.index='%s')
        and h.section = '%s'
        and h.idschoolyear = '%s'
        and h.year    = '%s'
        and hd.idscheduletype   = 1;
         */
        return sprintf("select r_curso,r_index,r_nombre,r_anio,r_periodo,r_zona,r_lab,r_estado,r_tipo,sum(r_asignados) as r_asignados from f_get_courseinformation(%d,%d,%d,%d,%d) group by r_curso,r_index,r_nombre,r_anio,r_periodo,r_zona,r_lab,r_estado,r_tipo;", $mCurso, $mIndex,$mAnio,$mPeriodo,$mCarrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     *  -   curso='$curso'
     * @functionOrigen  CursoDatos
     * ref #4
     * @línea   #204
     */
    function CursoDatos_select2($curso) {
        return "select curso 
                from curso 
                where clasificacion=3 
                    and curso='$curso';";

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     *  -   anio='" . $mAnio . "'
     * @functionOrigen  CursoDatos
     * ref #5
     * @línea   #237

     */
    function CursoDatos_select3($mPeriodo, $mAnio, $mCurso, $mIndex) {
        return "select hd.idscheduletype, c.idschool
                from tbscheduledetail hd, tbcourse c
                where (c.idcourse=hd.idcourse and c.index=hd.index) and hd.idschoolyear='" . $mPeriodo . "'
                    and hd.year='" . $mAnio . "'
                    and (hd.idcourse=" . $mCurso . " and hd.index=".$mIndex.")
                    and hd.idscheduletype in (2,6);";

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  CursoDatos
     * ref #6
     * @línea   #256
     */
    function CursoDatos_select4($mPeriodo, $mAnio, $mCurso, $mSeccion) {
        return "select * 
                from ing_fechaaprobacionactividad 
                where periodo='" . $mPeriodo .
                "' and anio=" . $mAnio .
                "  and curso='" . $mCurso .
                "' and seccion='" . $mSeccion .
                "' and trasladada=1";
    }

    /**
     * @functionOrigen  CrearArchivo
     * ref #7
     * @línea   #296
     */
    function CrearArchivo_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select a.idstudent,e.name,e.surname,
                         b.labnote,b.classzone,b.notefinalexam
                         from
                             (select idassignation,labnote,
                              classzone,notefinalexam from tbassignationdetail
                              where (idcourse   = %d and index=%d)
                              and   idschoolyear = %d
                              and   year    =  %d) b,
                          tbassignation a,
                          tbstudent e
                         where b.idassignation=a.idassignation and a.idcareer='%s'
                         and   e.idstudent=a.idstudent
                        order by a.idstudent;
                        ", $mCurso, $mIndex, $mPeriodo, $mAnio, $mSeccion);

    }

    /**
     * @functionOrigen  CrearArchivo
     * ref #8
     * @línea   #373
     */
    function CrearArchivo_2_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select a.idstudent,e.name,e.surname,
                         b.notefinalexam
                         from
                             (select idassignation,labnote,
                              classzone,notefinalexam
                              from tbassignationdetail
                              where (idcourse   = %d and index=%d)
                                  and   idschoolyear = %d
                                  and   year    =  %d) b,
                          tbassignation a,
                          tbstudent e
                         where b.idassignation=a.idassignation and a.idcareer='%s'
                             and   e.idstudent=a.idstudent
                         order by a.idstudent;
                        ", $mCurso, $mIndex, $mPeriodo, $mAnio, $mSeccion);

    }

    /**
     * @cambios
     * @functionOrigen  CalculaAsignados
     * ref #9
     * @línea   #443
     */
    function CalculaAsignados_select1($mCurso, $mCarrera/*$mSeccion*/, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select f_calculate_assignedcount
                            (%d,%d,%d,%d,%d);
                        ", $mCurso, $mCarrera, $mPeriodo, $mAnio, $mIndex);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarRegistro
     * ref #10
     * @línea   #519
     */
    function InsertarRegistro_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select description
                        from tblog_courseact
                        where (idcourse='%s' and index='%s')
                            and section='%s'
                            and idschoolyear='%s'
                            and year='%s'
                        order by date desc;
                        ", $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio
        );

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarRegistro
     * ref #11
     * @línea   #554
     */
    function InsertarRegistro_insert1($mCurso, $mSeccion, $mPeriodo, $mAnio, $_SESSIONsUsuarioDeSesionmUsuario, $mEstado, $campodescripcion, $param_nombrearchivo, $param_tamanio, $mIndex) {
        return sprintf("insert into tblog_courseact
                         (idcourse,section,idschoolyear,year,date,idpersonal,
                         idactstate,description,acttype,print,filename,size,index)
                            values('%s','%s','%s','%s','now()','%s',%d,'%s','W','0','%s',%d,%d);
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio, $_SESSIONsUsuarioDeSesionmUsuario, $mEstado, $campodescripcion, $param_nombrearchivo, $param_tamanio, $mIndex);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarRegistro
     * ref #12
     * @línea   #585
     */
    function InsertarRegistro_select2($mCurso, $mSeccion, $mPeriodo, $mAnio, $mIndex) {
        return sprintf("select * 
                        from tbrecordentry
                        where (idcourse  = '%s' and index=%d)
                            and section = '%s'
                            and idschoolyear = '%s'
                            and year    = '%s';
                        ", $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarRegistro
     * ref #13
     * @línea   #611
     */
    function InsertarRegistro_insert2($mCurso, $mSeccion, $mPeriodo, $mAnio, $param_nombrearchivo, $param_tamanio, $_SESSIONsUsuarioDeSesionmUsuario, $_SESSIONsUsuarioDeSesionmGrupo, $mEstado, $mIndex) {
        return sprintf("insert into tbrecordentry
                         (idcourse,section,idschoolyear,year,filename,size,
                         entrydate,idpersonal,idgroup,idrecordstate,index)
                           values('%s','%s','%s','%s','%s',%d,now(),'%s',%d,%d, %d);
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio, $param_nombrearchivo, $param_tamanio, $_SESSIONsUsuarioDeSesionmUsuario, $_SESSIONsUsuarioDeSesionmGrupo, $mEstado, $mIndex);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarRegistro
     * ref #14
     * @línea   #640
     */
    function InsertarRegistro_update1($mEstado, $param_nombrearchivo, $param_tamanio, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio,$mIndex) {
        return sprintf("update tbrecordentry
                        set idrecordstate = %d, filename = '%s',
                            size = %d, entrydate = current_timestamp
                        where idpersonal = '%s'
                            and (idcourse       = '%s' and index=%d)
                            and section     = '%s'
                            and idschoolyear     = '%s'
                            and year        = '%s';
                        ", $mEstado, $param_nombrearchivo, $param_tamanio, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mIndex,$mSeccion, $mPeriodo, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     *  -   estado = '%d'
     * @functionOrigen  InsertarRegistro
     * ref #15
     * @línea   #677
     */
    function InsertarRegistro_update2($mEstado, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio,$mIndex) {
        return sprintf("update tbschedule
                        set idactstate=%d,
                          idpersonal = '%s'
                        where (idcourse = '%s' and index=%d)
                            and idcareer = '%s'
                            and idschoolyear = '%s'
                            and year    = '%s';
                        ", $mEstado, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mIndex, $mSeccion, $mPeriodo, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarAprobacion
     * ref #16
     * @línea   #728

     */
    function InsertarAprobacion_insert1($mCurso, $mSeccion, $mPeriodo, $mAnio, $_SESSIONsUsuarioDeSesionmUsuario, $mEstado, $param_nombrearchivo, $param_tamanio) {
        return sprintf("insert into bitacoraacta
                          (curso,seccion,periodo,anio,fecha,usuarioid,
                          estado,descripcion,tipoacta,impresa,nombrearchivo,tamanio)
                            values('%s','%s','%s','%s','now()','%s',%d,'usuario web','W','0','%s',%d);
                        ", $mCurso, $mSeccion, $mPeriodo, $mAnio, $_SESSIONsUsuarioDeSesionmUsuario, $mEstado, $param_nombrearchivo, $param_tamanio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     *  -   estado = '%d'
     * @functionOrigen  InsertarAprobacion
     * ref #17
     * @línea   #759

     */
    function InsertarAprobacion_update1($mEstado, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio) {
        return sprintf("update horario 
                        set estado='%d', usuarioid = '%s'
                        where curso = '%s'
                            and seccion = '%s'
                            and periodo = '%s'
                            and anio    = '%s';

                        ", $mEstado, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  InsertarAprobacion
     * ref #18
     * @línea   #788
     */
    function InsertarAprobacion_update2($mEstado, $param_nombrearchivo, $param_tamanio, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio) {

        return sprintf("update ingresoregistro 
                        set estado = %d, nombrearchivo = '%s',
                           tamanio = %d, fechaaprobacion = current_timestamp
                        where usuarioid = '%s'
                            and curso       = '%s'
                            and seccion     = '%s'
                            and periodo     = '%s'
                            and anio        = '%s';
                        ", $mEstado, $param_nombrearchivo, $param_tamanio, $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     *  -   f_manejolaboratorio(character, character, character, numeric, numeric)
     *  --  ('%s','%s','%s','%d',%d)
     * @functionOrigen  MoverLaboratorios
     * ref #19
     * @línea   #809
     */
    function MoverLaboratorios_select1($carnet, $curso, $periodo, $anio, $notalaboratorio) {
        return sprintf("select f_manejolaboratorio
                            ('%s','%s','%s','%d',%d);"
            , $carnet, $curso, $periodo, $anio, $notalaboratorio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  GrabarNotasFinales
     * ref #20
     * @línea   #823
     */
    function GrabarNotasFinales_select1() {
        return sprintf("select f_createmp();");

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  GrabarNotasFinales
     * ref #21
     * @línea   #835
     */
    function GrabarNotasFinales_grant1() {
        return sprintf("grant all on webtablafinal to usringnotas;");

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  GrabarNotasFinales
     * ref #22
     * @línea   #875
     */
    function GrabarNotasFinales_select2($_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio) {
        return sprintf("select f_llenarasignaciondetalle
                            ('%s','%s','%s','%s','%s');
                        ", $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio
        );

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  GrabarNotasFinales
     * ref #23
     * @línea   #888

     */
    function GrabarNotasFinales_drop1() {
        return sprintf("drop table webtablafinal;");

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  ListarAprobados
     * ref #24
     * @línea   #910

     */
    function ListarAprobados_select1($_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio,$mIndex) {
        return sprintf("select f_list_courseact
                            ('%s','%s','%s','%s','%s',%d);
                        ", $_SESSIONsUsuarioDeSesionmUsuario, $mCurso, $mSeccion, $mPeriodo, $mAnio,$mIndex
        );

    }

    function COAC_DarInformacionDeCurso_selec1($anio,$periodo,$carrera,$curso) {
        return sprintf("select * from f_get_act_courselist(%d,%d,%d) where idcourse=%d;",$anio,$periodo,$carrera,$curso);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  ListarAprobados
     * ref #25
     * @línea   #926
     */
    function ListarAprobados_drop1() {
        return sprintf("drop table weblistadoaprobado;");

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  ObtieneDatosArchivo
     * ref #26
     * @línea   #1084
     */
    function ObtieneDatosArchivo_select1($mCurso, $mSeccion, $mPeriodo, $mAnio, $pIndex) {
        return sprintf("select filename,size
                        from tbrecordentry
                        where (idcourse  = '%s' and index=%d)
                            and section = '%s'
                            and idschoolyear = '%s'
                            and year    = '%s';
                        ", $mCurso, $pIndex, $mSeccion, $mPeriodo, $mAnio);

    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf 
     * @cambios
     * @functionOrigen  ValidaIngresoAnterior
     * ref #27
     * @línea   #1118
     */
    function ValidaIngresoAnterior_select1($mCurso, $mSeccion, $periodo, $_SESSIONsAnio, $mIndex) {
        return sprintf("select state
                        from tbschedule
                        where (idcourse = '%s' and index=%d)
                            and section = '%s'
                            and idschoolyear = '%s'
                            and year    = '%s';
                        ", $mCurso, $mIndex, $mSeccion, $periodo, $_SESSIONsAnio
        );

    }
    
    /**
      * @detalleBD BD_portal2.html 
      * @diagramaBD BD_portal2.pdf 
      * @cambios 
      * @functionOrigen  obtieneRetrasadasUnicas
      * @ref #28
      * @línea   #143
      */
     function obtieneRetrasadasUnicas_select1_1(){
        return "'01'";
     }

     /**
      * @detalleBD BD_portal2.html 
      * @diagramaBD BD_portal2.pdf 
      * @cambios 
      * @functionOrigen  obtieneRetrasadasUnicas
      * @ref #29
      * @línea   #145
      */
     function obtieneRetrasadasUnicas_select1_2(){
        return "'01','03'";
     }

     /**
      * @detalleBD BD_portal2.html 
      * @diagramaBD BD_portal2.pdf 
      * @cambios 
      * @functionOrigen  obtieneRetrasadasUnicas
      * @ref #30
      * @línea   #147
      */
     function obtieneRetrasadasUnicas_select1_3(){
        return  "'05'";
     }

     /**
      * @detalleBD BD_portal2.html 
      * @diagramaBD BD_portal2.pdf 
      * @cambios 
      * @functionOrigen  obtieneRetrasadasUnicas
      * @ref #31
      * @línea   #149
      */
     function obtieneRetrasadasUnicas_select1_4(){
        return "'05','07'";
     }

     /**
      * @detalleBD BD_portal2.html 
      * @diagramaBD BD_portal2.pdf 
      * @cambios 
      *     -   ad1.anio='" . $anio . "'
      *     -   ad.anio='" . $anio . "'
      * @functionOrigen  obtieneRetrasadasUnicas
      * @ref #32
      * @línea   #155
      */
     function obtieneRetrasadasUnicas_select1($anio, $periodo, $curso, $seccion, $periodoAprobUnica){
        return "select distinct a.usuarioid, ad.curso,(ad.zona+ad.examenfinal) as notafinal, ad.anio, ad.periodo" .
	              " from asignacion a, asignaciondetalle ad, (select a1.usuarioid, ad1.curso, ad1.seccion" .
				  " from asignacion a1, asignaciondetalle ad1 where a1.transaccion=ad1.transaccion" .
				  " and a1.fechaasignacion=ad1.fechaasignacion and ad1.anio='" . $anio . "' and ad1.periodo='" . $periodo .
				  "' and ad1.curso='" . $curso . "' and ad1.seccion='" . $seccion ."' and ad1.problema in (3,17)) ad2, horario h" .
				  " where a.transaccion=ad.transaccion and a.fechaasignacion=ad.fechaasignacion and h.anio=ad.anio" .
				  " and h.periodo=ad.periodo and h.curso=ad.curso and h.seccion=ad.seccion and h.tipoacta='W' and h.estado>=5" .
				  " and h.estado<=17 and ad.periodo='" . $periodoAprobUnica . "' and ad.anio='" . $anio . "' and ad.problema in (2)" .
				  " and (ad.zona+ad.examenfinal)>60 and a.usuarioid=ad2.usuarioid order by 1";
     }

     /**
      * @detalleBD BD_portal2.html 
      * @diagramaBD BD_portal2.pdf 
      * @cambios 
      *     -   ad1.anio='" . $anio . "'
      *     -   ad.anio='" . $anio . "'
      * @functionOrigen  obtieneRetrasadasUnicas
      * @ref #33
      * @línea   #169
      */
     function obtieneRetrasadasUnicas_select2($anio, $periodo, $curso, $seccion, $periodoAprobUnica){
        return "select distinct ca.usuarioid, ca.curso, ca.nota as notafinal, substring(ca.fechaaprobacion,1,4) as anio, ca.periodo" .
	              " from cursoaprobado ca, (select a.usuarioid,a.carrera,ad.curso,ad.periodo from asignacion a, asignaciondetalle ad," .
				  " (select a1.usuarioid, ad1.curso, ad1.seccion from asignacion a1, asignaciondetalle ad1" .
				  " where a1.transaccion=ad1.transaccion and a1.fechaasignacion=ad1.fechaasignacion and ad1.anio='" . $anio . 
				  "' and ad1.periodo='" . $periodo . "' and ad1.curso='" . $curso . "' and ad1.seccion='" . $seccion . 
				  "' and ad1.problema in (3,17)) ad2, horario h where a.transaccion=ad.transaccion" .
				  " and a.fechaasignacion=ad.fechaasignacion and h.anio=ad.anio and h.periodo=ad.periodo and h.curso=ad.curso" .
				  " and h.seccion=ad.seccion and h.tipoacta='W' and h.estado>=5 and h.estado<=17 and ad.periodo in (" . 
				  $periodoAprobUnica . ") and ad.anio='" . $anio . "' and ad.problema in (2) and a.usuarioid=ad2.usuarioid) t1" .
				  " where ca.usuarioid=t1.usuarioid and ca.carrera=t1.carrera and ca.curso=t1.curso and ca.periodo=t1.periodo" .
				  " and substring(ca.fechaaprobacion,1,4)='" . $anio . "' order by 1";
     }



}

//fin consultas respecto a la versión 9.0

?>
