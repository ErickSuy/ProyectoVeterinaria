<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 22/11/2014
 * Time: 08:55 PM
 */
include_once("General_SQL.php");

Class paggeneral_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  BuscaDeterminante
     * @ref #1
     * @línea   #21
     */
    function BuscaDeterminante_select1($txtCurso,$txtTipoActividad){
        return " select * from ing_actividaddeterminante
               where curso='$txtCurso'
			   and tipoactividad=$txtTipoActividad
			   ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #2
     * @línea   #51
     */
    function RecuperaActividadesGanadas_select_1($txtAnio){
        return "startyear<'" . $txtAnio."'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #3
     * @línea   #54
     */
    function RecuperaActividadesGanadas_select_2($txtAnio){
        return "endyear>='" . $txtAnio."'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #4
     * @línea   #60
     */
    function RecuperaActividadesGanadas_select_3($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #5
     * @línea   #64
     */
    function RecuperaActividadesGanadas_select_4($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('101','200','201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #6
     * @línea   #70
     */
    function RecuperaActividadesGanadas_select_5($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100','101') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #7
     * @línea   #74
     */
    function RecuperaActividadesGanadas_select_6($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('200','201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #8
     * @línea   #80
     */
    function RecuperaActividadesGanadas_select_7($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100','101','200') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #9
     * @línea   #85
     */
    function RecuperaActividadesGanadas_select_8($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen RecuperaActividadesGanadas
     * @ref #10
     * @línea   #101
     */
    function RecuperaActividadesGanadas_select1($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio){
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
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #11
     * @línea   #118
     */
    function RecuperaActividadesGanadas_update1($Posicion,$txtCurso,$txtPeriodo,$txtAnio,$txtSeccion){
        return " update ing_notasactividad set notaactividadganada[$Posicion]=0
            where curso='$txtCurso' and periodo='$txtPeriodo' and anio=$txtAnio and seccion='$txtSeccion'
            ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  RecuperaActividadesGanadas
     * @ref #12
     * @línea   #139
     */
    function RecuperaActividadesGanadas_update2($Posicion,$txtPeriodo,$txtAnio,$txtCurso,$txtSeccion,$TipoActividad,$compAdicional1,$compAdicional2){
        return "  update ing_notasactividad
					set notaactividadganada[$Posicion]=ing_notasactividadesguardadas.nota
				from ing_notasactividadesguardadas
				where
					ing_notasactividad.carnet=ing_notasactividadesguardadas.carnet
					and ing_notasactividad.curso=ing_notasactividadesguardadas.curso
					and ing_notasactividad.periodo='$txtPeriodo'
					and ing_notasactividad.anio=$txtAnio
					and ing_notasactividadesguardadas.curso='$txtCurso'
					and ing_notasactividad.seccion='$txtSeccion'
					and ing_notasactividadesguardadas.tipoactividad=$TipoActividad
					and ($compAdicional1) and ($compAdicional2)
                 ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  RecuperaNotasLaboratorio
     * @ref #13
     * @línea   #157
     */
    function RecuperaNotasLaboratorio_update1($txtCurso,$txtPeriodo,$txtAnio){
        return "update ing_notasactividad set notaganolaboratorio=0 where curso='$txtCurso' and periodo='$txtPeriodo' and anio=$txtAnio ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen
     * @ref #14
     * @línea   #174
     */
    function RecuperaNotasLaboratorio_select_1($txtAnio){
        return "startyear<'" . $txtAnio."'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen
     * @ref #15
     * @línea   #178
     */
    function RecuperaNotasLaboratorio_select_2($txtAnio){
        return "endyear>='" . $txtAnio."'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen
     * @ref #16
     * @línea   #184
     */
    function RecuperaNotasLaboratorio_select_3($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (starschoolyear in (100) and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -'" . $txtAnio."'"
     * @functionOrigen
     * @ref #17
     * @línea   #188
     */
    function RecuperaNotasLaboratorio_select_4($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('101','200','201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen
     * @ref #18
     * @línea   #194
     */
    function RecuperaNotasLaboratorio_select_5($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('100','200') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen
     * @ref #19
     * @línea   #198
     */
    function RecuperaNotasLaboratorio_select_6($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('200','201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   '" . $txtAnio."'"
     * @functionOrigen
     * @ref #20
     * @línea   #204
     */
    function RecuperaNotasLaboratorio_select_7($txtAnio){
        return "startyear<'" . $txtAnio."'" . " or (startschoolyear in ('01','02','05') and startyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen
     * @ref #21
     * @línea   #209
     */
    function RecuperaNotasLaboratorio_select_8($txtAnio){
        return "endyear>'" . $txtAnio."'" . " or (endschoolyear in ('201') and endyear='" . $txtAnio."'" . ")";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen
     * @ref #22
     * @línea   #225
     */
    function RecuperaNotasLaboratorio_update2($txtPeriodo,$txtAnio,$txtCurso,$compAdicional1,$compAdicional2){
        return "  update ing_notasactividad
					set notaganolaboratorio=tblab.labnote
				from tblab
				where
					ing_notasactividad.carnet::numeric=tblab.idstudent
					and ing_notasactividad.curso::SMALLINT =tblab.idcourse
					and ing_notasactividad.periodo='$txtPeriodo'
					and ing_notasactividad.anio=$txtAnio
					and tblab.idcourse='$txtCurso'
					and ($compAdicional1) and ($compAdicional2)
                 ";
    }

    /**
     * begin en @línea   #247
     */

    /**
     * commit en @línea   #257
     */

    /**
     * rollback en @línea   #265
     * No esta bien escrito roolback
     */

    /**
     * end en @línea   #272
     */

}
//fin consultas respecto a la versión 9.0

?>