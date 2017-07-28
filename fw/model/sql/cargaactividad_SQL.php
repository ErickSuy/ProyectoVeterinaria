<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 22/11/2014
 * Time: 07:52 PM
 */
include_once("General_SQL.php");
Class cargaactividad_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ad.anio = '$Anio'
     *  -   aa.anio (smallint) = ad.anio (char)
     *  -- conversión a char(4) to_char(aa.anio,'9999') eliminando espacios de la conversión trim(to_char(aa.anio,'9999'))
     * @functionOrigen  LevantaNotasActividades
     * @ref #1
     * @línea   #85
     */
    function LevantaNotasActividades_select1($CompletaSelect,$Curso,$Seccion,$Periodo,$Anio){
        return " select aa.carnet,(trim(e.surname)||', '||trim(e.name)) as nombre $CompletaSelect
                 from ing_notasactividad aa, tbassignationdetail ad, tbassignation a, tbstudent e
                 where
				 a.idassignation=ad.idassignation
				 and a.idstudent = aa.carnet::NUMERIC
				 and aa.periodo::smallint=ad.idschoolyear and aa.anio=ad.year
				 and aa.curso::smallint = ad.idcourse and aa.seccion::SMALLINT = a.idcareer
				 and e.idstudent=a.idstudent
				 and ad.idcourse='$Curso'
                 and a.idcareer='$Seccion'
                 and ad.idschoolyear='$Periodo'
                 and ad.year='$Anio'
                 order by aa.carnet::integer
                 ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #2
     * @línea   #344
     */
    function _select1($txtAnio,$txtPeriodo,$txtCurso,$txtCarrera){
        return " select * from ing_calendarioactividades where anio=$txtAnio and periodo='$txtPeriodo' and curso=$txtCurso and carrera=$txtCarrera";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #3
     * @línea   #373
     */
    function _select2_1($txtRegPer){
        return " and regper @> array[$txtRegPer] ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #4
     * @línea   #389
     */
    function _select2($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$filtropersonal){
        return " select a.*,ta.nombre  as nombretipoactividad,ta.superactividad
	             from ing_actividad a,ing_tipoactividad ta
                 where curso='$txtCurso'
				  and a.tipoactividad=ta.idtipoactividad
                  and seccion='$txtSeccion'
                  and periodo='$txtPeriodo'
                  and anio=$txtAnio
                  and a.activo=1
                  and ta.activo=1
				  $filtropersonal
                  order by fecharealizar
                 ";
    }

    /**
     * begin en @línea   #528
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #5
     * @línea   #547
     */
    function _update1_1($CompletaUpdaNotas,$numero,$nota){
        return $CompletaUpdaNotas. ", actividades[".$numero."]=".$nota;
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #6
     * @línea   #562
     */
    function _update1($CompletaUpdaNotas,$txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$_SESSIONVecCarnetAlumnoRecorreAlumnos){
        return " update ing_notasactividad set
				activo=1
				$CompletaUpdaNotas
			   where Curso='$txtCurso'
			   and Seccion='$txtSeccion'
			   and Periodo='$txtPeriodo'
			   and Anio=$txtAnio
			   and Carnet='".$_SESSIONVecCarnetAlumnoRecorreAlumnos."';";
    }

    /**
     * rollback en @línea   #581
     * No estaba bien escrito roolback
     */

}
?>