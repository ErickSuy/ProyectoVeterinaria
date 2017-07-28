<?php



include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */

Class manejoarchivoactividades_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  bloqueCargaNotas
     * @ref #1
     * @línea   #71
     */
    function bloqueCargaNotas_select1($txtPeriodo,$txtAnio,$txtCurso,$txtSeccion,$_SESSIONregper,$txtTipoActividad,$txtIdActividad){
        return "select trim(t.nombre) as nombre_t,trim(a.nombre) as nombre_a,a.ponderacion" .
        " from ing_tipoactividad t,ing_actividad a where a.tipoactividad=t.idtipoactividad" .
        " and a.periodo='" . $txtPeriodo . "' and a.anio=" . $txtAnio . " and a.curso='" . $txtCurso.
        "' and a.seccion='" . $txtSeccion . "' and a.regper @> array[" . $_SESSIONregper .
        "] and t.idtipoactividad=" . $txtTipoActividad . " and a.idactividad=" . $txtIdActividad;
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  notasYaExistentes
     * @ref #2
     * @línea   #103
     */
    function notasYaExistentes_select1($txtTipoActividad,$txtAnio,$txtPeriodo,$txtCurso){
        return "select na.curso,na.seccion,na.carnet,na.actividades[a.posicion] as nota,na.seccionactividad[a.posicion] as secactividad" .
        " from ing_actividad a, ing_notasactividad na" .
        " where a.anio=na.anio and a.periodo=na.periodo and a.curso=na.curso and a.seccion=na.seccion" .
        " and a.tipoactividad in (select idtipoactividad from ing_tipoactividad where activo=1 and superactividad=1) and a.activo=1" .
        " and a.tipoactividad=" . $txtTipoActividad . " and na.anio=" . $txtAnio . " and na.periodo='" . $txtPeriodo .
        "' and na.curso='" . $txtCurso . "' and na.actividades[a.posicion]>0 order by na.carnet";
    }

    /**
     * begin en @línea   #257
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #3
     * @línea   #304
     */
    function _update1_1($txtTipoActividad){
        return " and ing_actividad.tipoactividad=$txtTipoActividad ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #4
     * @línea   #308
     */
    function _update1_2($txtSeccion){
        return ", seccionactividad[ing_actividad.posicion]='" . $txtSeccion . "'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #5
     * @línea   #314
     */
    function _update1_3($txtIdActividad){
        return " and ing_actividad.idactividad=$txtIdActividad ";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #6
     * @línea   #318
     */
    function _update1_4(){
        return "";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #7
     * @línea   #337
     */
    function _update1($nota,$seccionActividad,$carnet,$SqlUpdateExtra,$txtCurso,$txtPeriodo,$txtAnio){
        return " update ing_notasactividad
			    set actividades[ing_actividad.posicion]=$nota" . $seccionActividad .
        " from ing_actividad
		      where
			    ing_notasactividad.carnet='$carnet'
			    $SqlUpdateExtra 
			    and ing_actividad.curso='$txtCurso'
			    and ing_actividad.periodo='$txtPeriodo'
			    and ing_actividad.anio=$txtAnio
			    and ing_actividad.activo=1
			    and ing_actividad.curso=ing_notasactividad.curso
			    and ing_actividad.seccion=ing_notasactividad.seccion
			    and ing_actividad.periodo=ing_notasactividad.periodo
			    and ing_actividad.anio=ing_notasactividad.anio
          " ;
    }

    /**
     * commit en @línea   #358
     */

    /**
     * end en @línea   #362
     */

    /**
     * commit en @línea   #382
     */

    /**
     * end en @línea   #386
     */

    /**
     * rollback en @línea   #401
     */

    /**
     * end en @línea   #405
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #8
     * @línea   #171
     */
    function guardaNotasNoAsignados_create1($tablaTemp){
        return "create temp table " . $tablaTemp . " as select * from ing_notasactividadesguardadas where carnet='0000000000'";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #9
     * @línea   #252
     */
    function guardaNotasNoAsignados_delete1($tablaTemp){
        return "delete from " . $tablaTemp . " using ing_notasactividadesguardadas i where " . $tablaTemp . ".carnet=i.carnet" .
        " and " . $tablaTemp . ".curso=i.curso and " . $tablaTemp . ".tipoactividad=i.tipoactividad" .
        " and " . $tablaTemp. ".nota<=i.nota;";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #10
     * @línea   #264
     */
    function guardaNotasNoAsignados_insert1($tablaTemp){
        return "insert into ing_bitacoranotasactividadesguardadas (select distinct i.* from " . $tablaTemp .
        " p, ing_notasactividadesguardadas i where p.carnet=i.carnet and p.curso=i.curso" .
        " and p.tipoactividad=i.tipoactividad and i.nota<p.nota)";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #11
     * @línea   #273
     */
    function guardaNotasNoAsignados_delete2($tablaTemp){
        return "delete from ing_notasactividadesguardadas using " . $tablaTemp .
        " p where p.carnet=ing_notasactividadesguardadas.carnet and p.curso=ing_notasactividadesguardadas.curso" .
        " and p.tipoactividad=ing_notasactividadesguardadas.tipoactividad and ing_notasactividadesguardadas.nota<p.nota";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #12
     * @línea   #286
     */
    function guardaNotasNoAsignados_update1($tablaTemp){
        return "update ing_notasactividadesguardadas set nota=p.nota from " . $tablaTemp .
        " p where p.carnet=ing_notasactividadesguardadas.carnet and p.curso=ing_notasactividadesguardadas.curso" .
        " and p.tipoactividad=ing_notasactividadesguardadas.tipoactividad" .
        " and p.periodoinicio=ing_notasactividadesguardadas.periodoinicio" .
        " and p.anioinicio=ing_notasactividadesguardadas.anioinicio and ing_notasactividadesguardadas.nota<p.nota";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #13
     * @línea   #296
     */
    function guardaNotasNoAsignados_delete3($tablaTemp){
        return "delete from " . $tablaTemp . " using ing_notasactividadesguardadas i where " . $tablaTemp . ".carnet=i.carnet" .
        " and " . $tablaTemp . ".curso=i.curso and " . $tablaTemp . ".tipoactividad=i.tipoactividad" .
        " and " . $tablaTemp . ".periodoinicio=i.periodoinicio and " . $tablaTemp . ".anioinicio=i.anioinicio" .
        " and i.nota>=" . $tablaTemp . ".nota";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #14
     * @línea   #308
     */
    function guardaNotasNoAsignados_insert2($tablaTemp){
        return "insert into ing_notasactividadesguardadas (select distinct p.* from " . $tablaTemp . " p," .
        " (select carnet,curso,tipoactividad from " . $tablaTemp . " except select carnet,curso,tipoactividad" .
        " from ing_notasactividadesguardadas) as t where p.carnet=t.carnet and p.curso=t.curso" .
        " and p.tipoactividad=t.tipoactividad)";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  guardaNotasNoAsignados
     * @ref #15
     * @línea   #319
     */
    function guardaNotasNoAsignados_drop1($tablaTemp){
        return "drop table " . $tablaTemp;
    }

}
//fin consultas respecto a la versión 9.0

?>
