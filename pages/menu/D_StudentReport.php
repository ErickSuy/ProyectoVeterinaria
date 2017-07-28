<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 10/10/14
 * Time: 11:15 AM
 */
include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/config/DB_Params.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/D_StudentReport_SQL.php");

global $gsql_in_ac;

function obtenerPeriodoActivo()
{
    $diaActual = Date("d");
    $mesActual = Date("m");
    $anioActual = Date("Y");
    $fechaActual = mktime(0, 0, 0, $mesActual, $diaActual, $anioActual);
    $periodoActivo = PRIMER_SEMESTRE;
    if ($fechaActual >= mktime(0, 0, 0, "01", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "02", "10", $anioActual))
        $periodoActivo = PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
    else
        if ($fechaActual >= mktime(0, 0, 0, "02", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "03", "01", $anioActual))
            $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
        else
            if ($fechaActual >= mktime(0, 0, 0, "03", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "05", "15", $anioActual))
                $periodoActivo = PRIMER_SEMESTRE;
            else
                if ($fechaActual >= mktime(0, 0, 0, "05", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "07", "10", $anioActual))
                    $periodoActivo = VACACIONES_DEL_PRIMER_SEMESTRE;
                else
                    if ($fechaActual >= mktime(0, 0, 0, "07", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "08", "10", $anioActual))
                        $periodoActivo = PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE;
                    else
                        if ($fechaActual >= mktime(0, 0, 0, "08", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "09", "01", $anioActual))
                            $periodoActivo = SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE;
                        else
                            if ($fechaActual >= mktime(0, 0, 0, "09", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "11", "15", $anioActual))
                                $periodoActivo = SEGUNDO_SEMESTRE;
                            else
                                $periodoActivo = VACACIONES_DEL_SEGUNDO_SEMESTRE;
    return $periodoActivo;
}

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

if (!isset($_GET['buscar'])) {
    $tpl = new TemplatePower("D_StudentReport.tpl");

    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("iselectciclo", "../includes/selectciclos.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");

    $tpl->prepare();

    $periodoRevisado = obtenerPeriodoActivo();
    if ($periodoRevisado == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE || $periodoRevisado == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
        $anioRevisado = Date("Y") - 1;
    else
        if ($periodoRevisado == VACACIONES_DEL_SEGUNDO_SEMESTRE) {
            $fechaActual = mktime(0, 0, 0, Date("m"), Date("d"), Date("Y"));
            if ($fechaActual >= mktime(0, 0, 0, "01", "01", Date("Y")) && $fechaActual < mktime(0, 0, 0, "01", "10", Date("Y")))
                $anioRevisado = Date("Y") - 1;
            else
                $anioRevisado = Date("Y");
        } else
            $anioRevisado = Date("Y");

    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", Date("Y") - 1);
    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", Date("Y"));

    $tpl->gotoBlock("_ROOT");
    $tpl->assign("periodo", $periodoRevisado);
    $tpl->assign("anio", $anioRevisado);
    $tpl->printToScreen();
    unset($tpl);

} else {
    if (isset($_GET['buscar']) and $_GET['buscar'] == OK) {
        $gsql_in_ac = new D_StudentReport_SQL();

        $conn1 = 0;
        $conn1 = pg_connect("user=" . USR_ROOT . " password=" . PWD_ROOT . " dbname=" . DB_FMVZ . " host=" . HOST_WEB . " port=" . BD_PORT); // para postgreSQL

        if (!$conn1) {
            exit;
        }

        $regPersonal = $objuser->getId();
        $periodoRevisado = $_GET["periodo"];
        $anioRevisado = $_GET["anio"];

        $qryListadoCursos = $gsql_in_ac->_select1($regPersonal, $periodoRevisado, $anioRevisado);

        $listadoCursos = pg_Exec($conn1, $qryListadoCursos);
        $total = pg_num_rows($listadoCursos);

        if ($total) {
            $vCursos = array();
            for ($i = 0; $i < $total; $i++) {
                $vCursos[] = pg_fetch_array($listadoCursos, $i);
            }

            echo json_encode($vCursos);
        }

        unset($gsql_in_ac, $conn1, $vCursos);
    }
}
?>