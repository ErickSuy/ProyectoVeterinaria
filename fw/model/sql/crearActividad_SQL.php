<?php

include_once("General_SQL.php");

Class creaactividad_SQL extends General_SQL
{

    /************************************************************ FUNCIONES */
    /*
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  queryNombreCurso
     * @ref #1
     * @línea   #50
     */
    /*
     * reemplaza a DevuelveNombreCorto_select1
     */
    function queryNombreCurso($_SESSIONcurso, $index)
    {
       return " select name from tbcourse where idcourse ='" . $_SESSIONcurso . "' and index=" . $index . ";";      
    }
    
    /*
     */
    function queryTipoActividad() /*retonra las actividades que esten registradas exceptuando Finales*/
    {
        return " select * from ing_tipoactividad where activo=1 order by nombre; ";
    }
    
    
    
    /*
     * retorna count de actividades para un curso,carrera, periodo, año especifico
     */
    function queryNumeroActividades($Curso,$Periodo, $Carrera, $Anio){
        return " select count(*) as conteo from tbactividad_curso where curso=$Curso and periodo='".$Periodo."' and anio=$Anio and carrera=$Carrera; ";
    }
    
    function queryCalendarioActividades($Curso,$Periodo, $Carrera, $Anio){
        return "select * from ing_calendarioactividades where curso=$Curso and carrera=$Carrera and anio=$Anio and periodo='".$Periodo."';";
    }
    
    function queryInsertActividad($nombre,$tipo,$fechaEntrega,$ponderacion,$docencia,$activo,$curso,$anio,$periodo,$carrera,$inicioPeriodo){
        return "INSERT INTO tbactividad_curso (nombre,
            tipo,
            fechaentrega,
            ponderacion,
            scheduletype,
            activo,
            curso,
            anio,
            periodo,
            carrera,
            inicioperiodo) VALUES (
            '$nombre',$tipo, '$fechaEntrega',$ponderacion,$docencia,$activo,$curso,$anio,'$periodo',$carrera,'$inicioPeriodo');";
    }
    
    
    function queryDeleteActividad($idactividad){
        return "DELETE FROM tbactividad_curso WHERE idactividad=$idactividad;";
    }
    function queryEsSuperActividad($idactividad){
        /*superactividad se utiliza denotar que actividades se pueden borrar y que actividades no*/
        return "select * from ing_tipoactividad where superactividad=1 and idtipoactividad=$idactividad;";
    }
    function queryUpdateActividad($idactividad,$nombre,$tipo,$fecha,$ponderacion,$docencia){
        return "UPDATE tbactividad_curso SET nombre = '$nombre', tipo=$tipo, "
        . "fechaentrega='$fecha', ponderacion=$ponderacion, scheduletype=$docencia "
        . "WHERE idactividad = $idactividad;";
    }
    
    function queryGetActividad($idactividad){
        return "select * from tbactividad_curso WHERE idactividad=$idactividad;";
    }
    
    function queryGetParciales($curso,$carrera,$periodo,$anio){
        //utilizado pra comprobar que parciales han ingresado, antes de ingresar uno de reposicion.
        return "select * from tbactividad_curso where "
        . "curso=$curso "
        . "and carrera=$carrera "
        . "and periodo='$periodo'"
        . "and anio=$anio "
        . "and (tipo=1 or tipo=2 or tipo=5);";
    }
    
    function queryInsertRegPersonal_Actividad($actividad,$responsable,$regpersonal){
        // El flujo es el siguiente:
        //      una actividad tiene muchos registros de personal pero solo un responsable.
        // la modelacion interna de este proceso se lleva de la siguiente forma:
            // regpersonal = {reg,reg2,reg(n)} donde reg son los registros de personal para este curso
            // responsable = reg; es el catedratico que creo la actividad.
            return "INSERT INTO tbregpersonal_actividad (actividad,responsable,regpersonal) "
        . "VALUES ($actividad,$responsable,'$regpersonal');";
    }
    
    function queryInsertFechaAprobacion_Actividad($curso, $seccion, $periodo, $anio, $regPersonal){
        return "INSERT INTO ing_fechaaprobacionactividad (curso,seccion,periodo,anio,regper)"
        . "VALUES ($curso,$seccion,$periodo,$anio,$regPersonal)";
        
    }
    
    function queryExisteFechaAprobacion_Actividad($curso,$carrera,$periodo,$anio){
        return "select count(*) as cantidad from ing_fechaaprobacionactividad where "
        . "curso='$curso' and periodo='$periodo' and anio=$anio and seccion='$carrera';";
    }
    
    function queryValidarActividad($Anio,$Periodo,$Curso,$Carrera,$Fecha,$Crearla,$ZonaMaxima,$Ponderacion,$tipoActividad,$esActualizacion,$notaAnterior){
        /*la zona maxima proviene de 70|80 para nivel introductorio basico o modulares respectiviamtne
          crearla = true|false -- el usuario decide crear la actividad aunque existiese una activdad en la misma fecha
         *esActualizacion = true|false -- para verifcacion si fuese una edicion de la actividad         
          notaAnterior = es un numero de la ponderacion anterior de la actividad a modificar esto solo si se decide modificar la actividad.     */
        $retorno = "select * from validarActividad($Anio,'$Periodo'::bpchar,$Curso,$Carrera,"
                . "'$Fecha',$Crearla,$ZonaMaxima,$Ponderacion,$tipoActividad,$esActualizacion,$notaAnterior);";
        
        return $retorno;
              
    }
    
    function getUltimoId($ConnBaseDatos,$tableSeq){
        $queryLast = "select last_value from $tableSeq;";
        $ConnBaseDatos->query($queryLast);
        $resultadoQuery=(($ConnBaseDatos->next_record()) != null)? $ConnBaseDatos->r():null;
        if($resultadoQuery!=null)
            return $resultadoQuery[last_value];
        return -1;
    }
    
    function queryNotasActividad($idActividad,$Curso,$Periodo, $Carrera, $Anio){
        return " insert into tbnotas_actividad(actividad,carnet,notaobtenida,estado)
             select '$idActividad',idstudent,0,1
             from tbassignation  a, tbassignationdetail ad
             where a.idassignation = ad.idassignation
	           AND ad.idcourse='$Curso'
              AND a.idcareer='$Carrera'
              AND ad.idschoolyear='$Periodo'
              AND ad.year='$Anio'
            ORDER BY a.idstudent ";
    }
    
    function queryInsertBitacoraInicial($regPersonal,$group, $idActividad,$Curso,$Periodo, $Carrera, $Anio){
        //la ponderacion = 0 porque es la inicial.
        return "insert into tbbitacora_actividades (autor, idgroup,carnet,actividad,ponderacion) "
        . "select $regPersonal,$group,idstudent,$idActividad,0
             from tbassignation  a, tbassignationdetail ad
             where a.idassignation = ad.idassignation
	           AND ad.idcourse='$Curso'
              AND a.idcareer='$Carrera'
              AND ad.idschoolyear='$Periodo'
              AND ad.year='$Anio'
            ORDER BY a.idstudent ;";
    }
    
    function queryGetActividades($Curso,$Periodo, $Carrera, $Anio,$doncencia,$TipoActividadArray,$all){
        
        //TipoActividadArray : para poder obetener un filtro especifico de actividades por tipo
        //all = para poder comparar por medio de all(array[]), que incluye cuando las actividades no coinciden con lo propuesto tip
        //docencia = lab o practica (1,2 respectivamente).
        
        
        
        if($TipoActividadArray==null && $doncencia==null)
            return "select * from tbactividad_curso where curso=$Curso and anio=$Anio and periodo='".$Periodo."' and carrera=$Carrera order by fechaentrega;";
        else if($TipoActividadArray!=null && $doncencia==null)
            return "select * from tbactividad_curso where curso=$Curso and anio=$Anio and periodo='".$Periodo."' and carrera=$Carrera and tipo= ANY(array[$TipoActividadArray]) order by fechaentrega;";
        else if($doncencia!=null && $TipoActividadArray!=null && $all!=null)
            return "select * from tbactividad_curso where scheduletype=$doncencia and curso=$Curso and anio=$Anio and periodo='".$Periodo."' and carrera=$Carrera and tipo!= ALL(array[$TipoActividadArray]) order by fechaentrega;";
        else if($doncencia!=null && $TipoActividadArray!=null)
            return "select * from tbactividad_curso where scheduletype=$doncencia and curso=$Curso and anio=$Anio and periodo='".$Periodo."' and carrera=$Carrera and tipo= ANY(array[$TipoActividadArray]) order by fechaentrega;";
        else
            return "select * from tbactividad_curso where scheduletype=$doncencia and curso=$Curso and anio=$Anio and periodo='".$Periodo."' and carrera=$Carrera order by fechaentrega;";
    }
    
    function queryGetPromedioEntregados($idActividad){
        return "select count(*) as entregados ,trunc(avg(notaobtenida), 2) as promedio
            from tbnotas_actividad where actividad=$idActividad and notaobtenida>=0 
            union 
            select count(*),null from tbnotas_actividad where actividad=$idActividad;";
    }
    
    function queryAlumnos($Curso,$Periodo, $Carrera, $Anio){
        return "select idstudent
             from tbassignation  a, tbassignationdetail ad
             where a.idassignation = ad.idassignation
	           AND ad.idcourse='$Curso'
              AND a.idcareer='$Carrera'
              AND ad.idschoolyear='$Periodo'
              AND ad.year='$Anio'
            ORDER BY a.idstudent ";
    }
    
    function queryHorarioLab($Curso,$Periodo, $Carrera, $Anio,$esModular){
        if($esModular){
            return "SELECT 
	                CASE
	                    WHEN (count(x.*) > 0) THEN 1
	                ELSE 0
	            END as laboratorio
	            FROM public.tbmodule_scheduledetail x WHERE idcourse=$Curso and year=$Anio 
	            and idschoolyear=$Periodo and idscheduletype=2 and idcareer=$Carrera;";
        }
        return "SELECT 
                    CASE
                        WHEN (count(x.*) > 0) THEN 1
                        ELSE 0
                    END as laboratorio
                    FROM public.tbscheduledetail x WHERE idcourse=$Curso and year=$Anio 
                        and idschoolyear=$Periodo and idscheduletype=2 and idcareer=$Carrera";
    }
    //--------------------------
   
    
     function queryGetNotas($Curso,$Periodo, $Carrera, $Anio){
        return "select Nota.carnet,Estudiante.name,Estudiante.surname,Estudiante.dpi,Nota.notaobtenida, Actividad.nombre, Actividad.idactividad FROM tbactividad_curso AS Actividad,tbnotas_actividad as Nota, tbstudent as Estudiante
	    WHERE Actividad.idactividad=Nota.actividad
	    AND Estudiante.idstudent=Nota.carnet	
            AND Actividad.curso=$Curso
            AND carrera=$Carrera
            AND Actividad.periodo='$Periodo'
            AND Actividad.anio=$Anio
            ORDER BY Nota.carnet, Actividad.fechaentrega;";
    }
    
    function queryDistinctNombreActividad($Curso,$Periodo, $Carrera, $Anio){ //Agregar curso,periodo,carrera,anio
        return "select  distinct(nombre),ponderacion,idactividad,fechaentrega from tbactividad_curso where
            curso=$Curso
            and carrera=$Carrera
            and periodo='$Periodo'
            AND anio=$Anio ORDER BY fechaentrega;";
    }

    
    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  DevuelveNombreCorto
     * @ref #1
     * @línea   #50
     */
    function DevuelveNombreCorto_select1($_SESSIONcurso, $index)
    {
        return " select idcourse from tbcourse where idcourse ='" . $_SESSIONcurso . "' and index=" . $index . ";";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  valorTopeLaboratorio
     * @ref #2
     * @línea   #69
     */
    function valorTopeLaboratorio_select1($curso, $periodo, $anio)
    {
        return "select * from cursozona where curso='" . $curso . "' and (trim(fechafinvigencia)='' or fechafinvigencia is null" .
        " or (periodo='" . $periodo . "' and anio='" . $anio . "')) and anio>='2010' order by fechafinvigencia";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  verSeccionClaseMagistral
     * @ref #3
     * @línea   #103
     */
    function verSeccionClaseMagistral_select1($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtIndex, $txtModular)
    {
        /**
         *  -- ORIGINAL --
         * select distinct(idscheduletype) from tbscheduledetail where idcourse='".$txtCurso."' and index='".$txtIndex."' and section='".$txtSeccion."'
         * and idschoolyear='".$txtPeriodo."' and year='".$txtAnio."' and idscheduletype=1
         */
        if ($txtModular['resultado']+0) {
            return "  select distinct(idscheduletype) from tbmodule_scheduledetail where idcourse='" . $txtCurso . "' and index='" . $txtIndex . "' and idcareer='" . $txtCarrera . "'
					    and idschoolyear='" . $txtPeriodo . "' and year='" . $txtAnio . "' and idscheduletype=1";
        } else {
            return "  select distinct(idscheduletype) from tbscheduledetail where idcourse='" . $txtCurso . "' and index='" . $txtIndex . "' and idcareer='" . $txtCarrera . "'
					    and idschoolyear='" . $txtPeriodo . "' and year='" . $txtAnio . "' and idscheduletype=1";
        }

    }

    function esCursoModular($txtIndex, $txtCurso, $txtCarrera)
    {
        /**
         *  -- ORIGINAL --
         * select distinct(idscheduletype) from tbscheduledetail where idcourse='".$txtCurso."' and index='".$txtIndex."' and section='".$txtSeccion."'
         * and idschoolyear='".$txtPeriodo."' and year='".$txtAnio."' and idscheduletype=1
         */

        return sprintf("select case when f_check_modulecourse='t' then 1 else 0 end as resultado from f_check_modulecourse(%d,%d,%d);", $txtIndex, $txtCurso, $txtCarrera);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ad.Anio = '$txtAnio'
     * @functionOrigen  AgregarFilasAsignacionActividad
     * @ref #4
     * @línea   #133
     */
    function AgregarFilasAsignacionActividad_insert1($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
    {
        return " insert into ing_notasactividad(curso,seccion,periodo,anio,carnet)
             select '$txtCurso','$txtSeccion','$txtPeriodo',$txtAnio,idstudent
             from tbassignation  a, tbassignationdetail ad
             where a.idassignation = ad.idassignation
	           AND ad.idcourse='$txtCurso'
              AND a.idcareer='$txtSeccion'
              AND ad.idschoolyear='$txtPeriodo'
              AND ad.year='$txtAnio'
            ORDER BY a.idstudent ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  BuscaPosicionActividad
     * @ref #5
     * @línea   #197
     */
    function BuscaPosicionActividad_select1($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
    {
        return " select idactividad,posicion,activo
				  from ing_actividad
				  where curso='$txtCurso'
					  and seccion='$txtSeccion'
					  and periodo='$txtPeriodo'
					  and anio=$txtAnio
				  order by posicion
                    ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  BuscaPosicionActividad
     * @ref #6
     * @línea   #227
     */
    function BuscaPosicionActividad_update1($bdfidactividad)
    {
        return " update ing_actividad set activo=-1 where idactividad=" . $bdfidactividad;
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio = '$txtAnio'
     * @functionOrigen  ninguna
     * @ref #7
     * @línea   #363
     */
    function _select1($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
    {
        return "  select distinct(tipo)
			    from horariodetalle
			    where curso='$txtCurso'
				    and seccion='$txtSeccion'
				    and periodo='$txtPeriodo'
				    and anio='$txtAnio'
				    and tipo=1
				     ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #8
     * @línea   #393
     */
    function _select2($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtRegPer, $index)
    {
        /**
         * -- ORIGINAL --
         * select distinct(idscheduletype) from tbscheduledetail where idcourse='" . $txtCurso . "' and index=" . $index . " and section='" . $txtSeccion .
        "' and idschoolyear='" . $txtPeriodo . "' and year='" . $txtAnio . "' and idteacher='" . $txtRegPer . "'
         *
         */
        return sprintf("select distinct idscheduletype from (
                           select schedulenumber,idscheduletype,starttime,endtime,startdate,enddate,price,mon,tue,wed,thu,fri,sat,sun,building,idclassroom,index,idcourse,section,idcareer,year,idschoolyear,idteacher,assignedcount
                           from tbscheduledetail
                           union
                           select *
                           from tbmodule_scheduledetail)sc
                           where idcourse=%d and index=%d and idcareer=%d and year=%d and idschoolyear=%d and idteacher=%d and idscheduletype=1;",
            $txtCurso,$index,$txtCarrera,$txtAnio,$txtPeriodo,$txtRegPer);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #9
     * @línea   #516
     */
    function _select3($NoParciales)
    {
        return " select * from ing_tipoactividad where activo=1 and superactividad=0 $NoParciales order by nombre; ";
    }

    /**
     * begin en @línea   #508
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #10
     * @línea   #553
     */
    function _insert1($txtNombreActividad, $txtFechaRealizar, $txtPonderacion, $txtPerteneceA, $txtRegPer, $txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtArchivoEnunciado, $txtTipoActividad, $Posicion,$personal)
    {
        return " insert into ing_actividad
               ( nombre,fecharealizar,ponderacion,
               pertenecea,regper,activo,curso,seccion,
               periodo,anio, archivoenunciado,estadoactividad,tipoactividad,Posicion,responsable)
           values
           ('$txtNombreActividad','$txtFechaRealizar',$txtPonderacion,
            $txtPerteneceA,'{ $txtRegPer }',1,'$txtCurso','$txtSeccion','$txtPeriodo',$txtAnio,
            '$txtArchivoEnunciado',0,$txtTipoActividad,$Posicion,$personal
           ); ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #11
     * @línea   #570
     */
    function _update1($Posicion, $txtSeccion, $txtCurso, $txtPeriodo, $txtAnio)
    {
        return " update ing_notasactividad set actividades[$Posicion]=0,notaactividadganada[$Posicion]=0,seccionactividad[$Posicion]='$txtSeccion'
              where Curso='$txtCurso'
					  and	Seccion='$txtSeccion'
					  and	Periodo='$txtPeriodo'
					  and   Anio=$txtAnio
					  ";
    }

    /**
     * rollback en @línea   #552
     */

    /**
     * commit en @línea   #554
     */

    /**
     * end en @línea   #555
     */


    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #12
     * @línea   #590
     */
    function _select4($txtIdActividad)
    {
        return " select * from ing_actividad where idactividad=$txtIdActividad";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #13
     * @línea   #656
     */
    function _select5()
    {
        return " select * from ing_tipoactividad where activo=1 order by nombre;";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #14
     * @línea   #696
     */
    function _select6($Posicion, $txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
    {
        return " select max(actividades[$Posicion]) as maxima from ing_notasactividad
              where curso='$txtCurso'
					 and seccion='$txtSeccion'
					 and periodo='$txtPeriodo'
					 and anio=$txtAnio
					  ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #15
     * @línea   #721
     */
    function _update2($txtNombreActividad, $txtPonderacion, $txtPerteneceA, $txtFechaRealizar, $txtIdActividad,$tipoActividad)
    {
        return "  update ing_actividad set
              nombre='$txtNombreActividad',
              ponderacion=$txtPonderacion,
              pertenecea=$txtPerteneceA,
              fecharealizar='$txtFechaRealizar',
              tipoactividad=$tipoActividad
              where IdActividad=$txtIdActividad";
    }

    /**
     * begin en @línea   #705
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #16
     * @línea   #737
     */
    function _update3($txtIdActividad)
    {
        return " update ing_actividad set activo=0
				  where IdActividad=$txtIdActividad";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #17
     * @línea   #748
     */
    function _update4($txtPosicion, $txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
    {
        return " update ing_notasactividad set actividades[$txtPosicion]=0
              where Curso='$txtCurso'
					  and	Seccion='$txtSeccion'
					  and	Periodo='$txtPeriodo'
					  and   Anio=$txtAnio
					  ";
    }

    /**
     * commit en @línea   #731
     */

    /**
     * rollback en @línea   #733
     */

    /**
     * end en @línea   #736
     */

    /**
     * begin en @línea   #751
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #18
     * @línea   #777
     */
    function _select7($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtRegPer)
    {
        return " select i1.* from ing_fechaaprobacionactividad i1, ing_actividad i2" .
        " where i2.curso=i1.curso and i2.seccion=i1.seccion and i2.periodo=i1.periodo and i2.anio=i1.anio" .
        //" and i2.regper=i1.regper and i1.curso= '" . $txtCurso . "' and i1.seccion= '" . $txtSeccion .
        " and i1.curso= '" . $txtCurso . "' and i1.seccion= '" . $txtSeccion .
        //"' and i1.periodo= '" . $txtPeriodo . "' and i1.anio= '" . $txtAnio . "' and i1.regper='" . $txtRegPer . "'";
        "' and i1.periodo= '" . $txtPeriodo . "' and i1.anio= '" . $txtAnio . "';";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #19
     * @línea   #798
     */
    function _insert2($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtRegPer)
    {
        return " insert into ing_fechaaprobacionactividad (curso,seccion,periodo,anio,regper)" .
        " (select distinct curso,seccion,periodo,anio,'".$txtRegPer."' from ing_actividad where curso='" . $txtCurso .
        "' and seccion='" . $txtSeccion . "' and periodo='" . $txtPeriodo . "' and anio=" . $txtAnio .
        " and regper @> array[" . $txtRegPer . "])";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #20
     * @línea   #819
     */
    function _select8($txtAnio, $txtPeriodo,$txtCurso,$txtCarrera)
    {
        return "select * from  ing_calendarioactividades where periodo='". $txtPeriodo ."' and anio=" . $txtAnio ." and curso=" . $txtCurso ." and carrera=" . $txtCarrera.";";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #21
     * @línea   #864
     */
    function _select9($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtRegPer)
    {
        return " select count(*) as parciales from ing_actividad" .
        " where activo=1 and curso= '" . $txtCurso . "' and seccion= '" . $txtSeccion .
        "' and periodo= '" . $txtPeriodo . "' and anio= '" . $txtAnio . "' and regper @> array[" . $txtRegPer .
        "] and tipoactividad<5";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #22
     * @línea   #874
     */
    function _select10()
    {
        return " select a.*,ta.nombre as nombretipoactividad, case when tsecc.idscheduletype=1 then 'TEORIA' else 'PRACTICA' end as descripcion,ta.superactividad,a.asignarsecciones
	    from ing_actividad a,ing_tipoactividad ta, tbscheduletype tsecc where ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #23
     * @línea   #882
     */
    function _select_1($SqlLista)
    {
        return $SqlLista . " a.activo = 1 and a.tipoactividad=ta.idtipoactividad and tsecc.idscheduletype=a.pertenecea ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #24
     * @línea   #886
     */
    function _select_2($SqlLista, $_SESSIONVectorPerteneceA)
    {
        return $SqlLista . $_SESSIONVectorPerteneceA . " ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #25
     * @línea   #890
     */
    function _select_3($SqlLista, $txtCurso)
    {
        return $SqlLista . " and curso= '$txtCurso'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #26
     * @línea   #894
     */
    function _select_4($SqlLista, $txtSeccion)
    {
        return $SqlLista . " and seccion= '$txtSeccion'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #27
     * @línea   #898
     */
    function _select_5($SqlLista, $txtPeriodo)
    {
        return $SqlLista . " and periodo= '$txtPeriodo'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #28
     * @línea   #902
     */
    function _select_6($SqlLista, $txtAnio)
    {
        return $SqlLista . " and anio= '$txtAnio'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #29
     * @línea   #906
     */
    function _select_7($SqlLista, $txtRegPer)
    {
        return $SqlLista . " and regper @> array[" . $txtRegPer . "] ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #30
     * @línea   #911
     */
    function _select_8($SqlLista)
    {
        return $SqlLista . " order by FechaRealizar,PerteneceA";
    }

    /**
     * commit en @línea   #1108
     */

    /**
     * rollback en @línea   #1110
     */

    /**
     * end en @línea   #1113
     */

}

?>