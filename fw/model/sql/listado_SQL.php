<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 22/11/2014
 * Time: 08:52 PM
 */
include_once("General_SQL.php");

Class listado_SQL extends General_SQL {

    /**
     * begin en @línea   #45
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #1
     * @línea   #57
     */
    function _select_1($txtAnio){
        return "startyear<'" . $txtAnio."'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #2
     * @línea   #61
     */
    
    
    
    
    /*funciones para generar el listado Erick Suy 2017*/
    function querygetZonas($Curso,$Carrera,$Periodo,$Anio){
        /*retorna una tabla con la siguiente estructura
         * rcarnet numeric, rname text, rdpi character varying, 
         * rnotaobtenida numeric, rponderacion numeric, rnombre character varying, 
         * ridactividad integer, rtipo integer
         * 
         * cada tupla es una actividad
         */
        return "select * from getzonasfinales ($Anio,'$Periodo',$Curso,$Carrera );";
    }
    
    function  queryGetAprobacionCurso($Curso,$Carrera,$Periodo,$Anio){
        return "select  
	case when fecha isnull then 0
			else 1
	end as aprobado
        from ing_fechaaprobacionactividad 
        where curso='$Curso' and "
                . "seccion='$Carrera' and "
                . "anio='$Anio' and "
                . "periodo='$Periodo'";
        
    }
    
    function queryGetIdAssignation($Curso,$Carrera,$Periodo,$Anio,$idStudent){
        return "select a.idassignation
            from tbassignation  a, tbassignationdetail ad
            where a.idassignation = ad.idassignation
            AND ad.idcourse='$Curso'
            AND a.idcareer='$Carrera'
            AND ad.idschoolyear='$Periodo'
            AND ad.year='$Anio'
            and a.idstudent='$idStudent'";
    }
            
    function queryAprobarCurso($Zona,$Carnet,$Curso, $Carrera, $Periodo,$Anio,$regPersonal){
    
        return "select * from aprobarNotas($Zona::numeric,"
                . "$Carnet::numeric,"
                . "$regPersonal::bpchar,"
                . "$Curso::int2,"
                . "$Anio::int2,"
                . "$Periodo::int2,"
                . "$Carrera::int2);";
    }
            
     function queryNombreActividad($Curso,$Periodo, $Carrera, $Anio){ //Agregar curso,periodo,carrera,anio
        return "select  nombre,ponderacion,idactividad,fechaentrega from tbactividad_curso where
            curso=$Curso
            and carrera=$Carrera
            and periodo='$Periodo'
            AND anio=$Anio ORDER BY fechaentrega;";
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
    /*fin de funciones para generar listado Erick Suy 2017*/
    
    
    function _select_2($txtAnio){
        return "endyear>='" . $txtAnio."'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #3
     * @línea   #67
     */
    function _select_3($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #4
     * @línea   #71
     */
    function _select_4($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('101','200','201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #5
     * @línea   #77
     */
    function _select_5($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100','101') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #6
     * @línea   #81
     */
    function _select_6($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('200','201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #7
     * @línea   #87
     */
    function _select_7($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100','101','200') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  ninguna
     * @ref #8
     * @línea   #92
     */
    function _select_8($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #9
     * @línea   #108
     */
    function _select1($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio){
        return " select a.tipoactividad,posicion
				from ing_actividad a,ing_guardaractividadescurso gac
				where a.curso='$txtCurso'
					and seccion='$txtSeccion'
					and periodo='$txtPeriodo'
					and anio=$txtAnio
					and a.curso=gac.curso
					and a.tipoactividad=gac.tipoactividad
					and activo=1
					";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #10
     * @línea   #123
     */
    function _update1($Posicion,$txtCurso,$txtPeriodo,$txtAnio){
        return " update ing_notasactividad set notaactividadganada[$Posicion]=0
            where curso='$txtCurso' and periodo='$txtPeriodo' and anio=$txtAnio
            ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   no existe la columna ing_notasactividadesguardadas.actividad, la columna existente parecida es tipoactividad
     *  --  cambiando ing_notasactividadesguardadas.actividad a ing_notasactividadesguardadas.tipoactividad
     * @functionOrigen  ninguna
     * @ref #11
     * @línea   #144
     */
    function _update2($Posicion,$txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$TipoActividad,$compAdicional1,$compAdicional2){
        return "  update ing_notasactividad
					set notaactividadganada[$Posicion]=ing_notasactividadesguardadas.nota
				from ing_notasactividadesguardadas
				where
					ing_notasactividad.carnet=ing_notasactividadesguardadas.carnet
					and ing_notasactividad.curso=ing_notasactividadesguardadas.curso
					and ing_notasactividadesguardadas.curso='$txtCurso'
					and ing_notasactividad.seccion='$txtSeccion'
					and ing_notasactividad.periodo='$txtPeriodo'
					and ing_notasactividad.anio=$txtAnio
					and ing_notasactividadesguardadas.tipoactividad=$TipoActividad
					and ($compAdicional1) and ($compAdicional2)

           ";
    }

    /**
     * begin en @línea   #168
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio = $txtAnio
     * @functionOrigen  ninguna
     * @ref #12
     * @línea   #178
     */
    function _update3($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$txtRegPer){
        return " update ing_fechaaprobacionactividad set fecha=now(),regper='$txtRegPer'
            where
					curso= '$txtCurso'
					and seccion= '$txtSeccion'
               and periodo= '$txtPeriodo'
					and anio= $txtAnio
					--and regper='$txtRegPer'
			   ";
    }

    /**
     * commit en @línea   #187
     */

    /**
     * rollback en @línea   #189
     */

    /**
     * end en @línea   #190
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #13
     * @línea   #257
     */
    function _select2($txtSeccion,$txtPeriodo,$txtAnio,$txtCurso,$_SESSIONregper){
        return sprintf("select distinct a.curso,a.seccion,a.tipoactividad,a.idactividad, a.pertenecea, ta.nombre,a.posicion,a.ponderacion,c.idschool as escuela
        from ing_actividad a,ing_tipoactividad ta, ing_notasactividad na, tbcourse c
        where ta.idtipoactividad=a.tipoactividad and ta.activo=1 and na.curso=a.curso and a.seccion='%s'
        and na.periodo=a.periodo and na.anio=a.anio and a.activo=1 and a.periodo='%s' and a.anio='%s'
        and na.seccionactividad[a.posicion]='%s' and a.curso='%s'
        and a.regper @> array[%d] and (c.idcourse=a.curso::integer and c.index=1) order by tipoactividad",
            $txtSeccion,$txtPeriodo,$txtAnio,$txtSeccion,$txtCurso,$_SESSIONregper);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #14
     * @línea   #285
     */
    function _select3($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio){
        return "select  a.tipoactividad,a.idactividad,
					a.pertenecea, ta.nombre,a.posicion,a.ponderacion,c.idschool as escuela
				from ing_actividad a, ing_tipoactividad ta, tbcourse c
				where a.activo=1
					and ta.activo=1
					and a.tipoactividad=ta.idtipoactividad
				   and a.curso='$txtCurso'
	            and seccion='$txtSeccion'
	 			   and periodo='$txtPeriodo'
	 				and anio=$txtAnio
				   and (c.idcourse=a.curso::SMALLINT and index=1)
				order by a.tipoactividad";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #15
     * @línea   #469
     */
    function _select4($CompletaSelect,$txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$_SESSIONregper){
        return " select distinct (trim(e.name)||' '||trim(e.surname)) as nombre,idstudent as carnet, notaganolaboratorio,
              $CompletaSelect
 			  from ing_notasactividad na, tbstudent e, ing_actividad a
           where e.idstudent=na.carnet::NUMERIC
				  and a.periodo=na.periodo
				  and a.curso=na.curso
				  and a.anio=na.anio
				  and a.seccion=na.seccionactividad[a.posicion]
				  and na.curso='$txtCurso'
              and na.seccionactividad[a.posicion]='$txtSeccion'
              and na.periodo='$txtPeriodo'
              and na.anio=$txtAnio
				  and a.regper @> array[$_SESSIONregper]
			  order by idstudent";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #16
     * @línea   #487
     */
    function _select5($CompletaSelect,$txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$_SESSIONregper){
        return " select distinct (trim(e.surname)||', '||trim(e.name)) as nombre,idstudent as carnet, notaganolaboratorio,
              $CompletaSelect
 			  from ing_notasactividad na, tbstudent e, ing_actividad a
           where e.idstudent=na.carnet::NUMERIC
				  and a.periodo=na.periodo
				  and a.curso=na.curso
				  and a.anio=na.anio
				  and a.seccion=na.seccion
				  and na.curso='$txtCurso'
              and na.seccion='$txtSeccion'
              and na.periodo='$txtPeriodo'
              and na.anio=$txtAnio
				  and a.regper @> array[$_SESSIONregper]
			  order by idstudent";
    }

}
//fin consultas respecto a la versión 9.0

?>