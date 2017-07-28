<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 12/11/2014
 * Time: 10:28 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/config/DB_Params.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/D_StudentReport_SQL.php");

session_start();

global $gsql_in_ac;

$objuser = unserialize($_SESSION['usuario']);

if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

if (isset($_GET['detalle']) and $_GET['detalle'] == OK) {
    $gsql_in_ac = new D_StudentReport_SQL();

    $conn1 = 0;
    $conn1 = pg_connect("user=" . USR_ROOT . " password=" . PWD_ROOT . " dbname=" . DB_FMVZ . " host=" . HOST_WEB . " port=" . BD_PORT); // para postgreSQL

    if (!$conn1) {
        exit;
    }

    $qryListadoEstudiantes = $gsql_in_ac->_select2($_GET["curso"],$_GET["carrera"],$_GET["periodo"],$_GET["anio"],$_GET["index"]);

    $listadoEstudiantes = pg_Exec($conn1, $qryListadoEstudiantes);
    $total = pg_num_rows($listadoEstudiantes);

    if($total) {
        $vEstudiantes = array();

        for ($i = 0; $i < $total; $i++) {
            $vEstudiantes[] = pg_fetch_array($listadoEstudiantes, $i);
        }

        echo json_encode($vEstudiantes);
    }

    unset($gsql_in_ac,$conn1,$vCursos);
}



?>