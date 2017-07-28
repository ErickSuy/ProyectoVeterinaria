<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 7/10/14
 * Time: 03:09 PM
 */

include_once("General_SQL.php");

/**
 *
 * PostgreSQL @version 9.0
 */
Class D_ActApprovalConfirm_SQL extends General_SQL
{

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #1
     * @línea   #68
     */
    function _select1()
    {
        return sprintf("select f_createmp();");
    }

    /**
     * begin_transacction en @línea   #107
     */


    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #2
     * @línea   #133
     */
    function _select2($_SESSIONsUsuarioDeSesionmUsuario, $nombrearchivo, $tamanioarchivo, $_SESSIONsActaManualmCurso, $_SESSIONsActaManualmSeccion, $_SESSIONsActaManualmPeriodo, $_SESSIONsActaManualmAnio, $_SESSIONsActaManualmEstado, $_SESSIONsActaManualmLaboratorio, $_SESSIONsPeriodo, $_SESSIONsAnio, $minimalab, $mIndex)
    {
        return sprintf("select f_webentryfinalstep('%s','%s',%d,'%s','%s','%s','%s',%d,%d,'%s','%s',%d,%d);",
            $_SESSIONsUsuarioDeSesionmUsuario,
            $nombrearchivo,
            $tamanioarchivo,
            $_SESSIONsActaManualmCurso,
            $_SESSIONsActaManualmSeccion,
            $_SESSIONsActaManualmPeriodo,
            $_SESSIONsActaManualmAnio,
            $_SESSIONsActaManualmEstado,
            $_SESSIONsActaManualmLaboratorio,
            $_SESSIONsPeriodo,
            $_SESSIONsAnio,
            $minimalab,
            $mIndex
        );
    }

    /**
     * commit_transaction en @línea   #157
     */


    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #3
     * @línea   #174
     */
    function _select3($_SESSIONsActaManualmAnio, $_SESSIONsActaManualmCurso, $_SESSIONsActaManualmSeccion, $pIndex)
    {
        return "select idschoolyear,idactstate from tbschedule
            where year = '" . $_SESSIONsActaManualmAnio . "'
			     and   idschoolyear in (102,103)
		        and   (idcourse = " . $_SESSIONsActaManualmCurso . " and index=" . $pIndex . ")
		        and   idcareer = '" . $_SESSIONsActaManualmSeccion . "';";
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  ninguna
     * @ref #4
     * @línea   #186
     */
    function _select4($_SESSIONsActaManualmAnio, $_SESSIONsActaManualmCurso, $_SESSIONsActaManualmSeccion, $mIndex)
    {
        return "select idschoolyear,idactstate from tbschedule
            where year = '" . $_SESSIONsActaManualmAnio . "'
			     and   idschoolyear in (202,203)
		        and   (idcourse = " . $_SESSIONsActaManualmCurso . " and index=" . $mIndex . ")
		        and   idcareer = '" . $_SESSIONsActaManualmSeccion . "';";
    }

    /**
     * begin_transaction en @línea   #207
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen
     * @ref #5
     * @línea   #212
     */
    function _select5($_SESSIONsActaManualmAnio, $_SESSIONsActaManualmPeriodo, $valor1, $valor2, $_SESSIONsActaManualmCurso, $_SESSIONsActaManualmSeccion, $mIndex)
    {
        return "select f_update_lateassignation('" . $_SESSIONsActaManualmAnio . "','" . $_SESSIONsActaManualmPeriodo . "','$valor1',$valor2,'" . $_SESSIONsActaManualmCurso . "','" . $_SESSIONsActaManualmSeccion . "'," . $mIndex . ");";
    }

    /**
     * commit_transaction en @línea   #216
     */

}

//fin consultas respecto a la versión 9.0

?>
