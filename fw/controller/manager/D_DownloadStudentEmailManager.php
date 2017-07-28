<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 12/11/2014
 * Time: 06:23 PM
 */

include("../../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/config/DB_Params.php");
include_once("$dir_portal/fw/model/sql/D_DownloadStudentEmailManager_SQL.php");

global $gsql_in_dca;
$gsql_in_dca = new D_DownloadStudentEmailManager_SQL();

// Implementación de Funciones

function obtenerNotaLaboratorio($listadoLab,$carnet) {
    $total1 = sizeof($listadoLab);
    if ($total1==0) return 0;
    for ($i=0; $i<$total1; $i++) {
        if ($carnet==$listadoLab[$i][0])
            return $listadoLab[$i][1];
    }
    return 0;
}

function notasDeLaboratorio($periodo,$anio,$curso,$seccion,$conn1,$index) {
    global $gsql_in_dca;
    $vector = array();
    switch ($periodo) {
        case PRIMER_SEMESTRE : //$comparacionAdicional="l.aniofin>=ad.anio";
            $comparacionAdicional= $gsql_in_dca->notasDeLaboratorio_select1_1();
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE : //$comparacionAdicional="(l.aniofin>ad.anio or (l.periodofin in ('02','05','06') and l.aniofin=ad.anio))";
            $comparacionAdicional= $gsql_in_dca->notasDeLaboratorio_select1_2();
            break;
        case SEGUNDO_SEMESTRE : //$comparacionAdicional="(l.aniofin>ad.anio or (l.periodofin in ('05','06') and l.aniofin=ad.anio))";
            $comparacionAdicional= $gsql_in_dca->notasDeLaboratorio_select1_3();
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE : //$comparacionAdicional="(l.aniofin>ad.anio or (l.periodofin in ('06') and l.aniofin=ad.anio))";
            $comparacionAdicional= $gsql_in_dca->notasDeLaboratorio_select1_4();
            break;
    }
    $qryListadoLab= $gsql_in_dca->notasDeLaboratorio_select1($periodo,$anio,$curso,$seccion,$comparacionAdicional,$index);

    $listadoLab = pg_Exec ($conn1,$qryListadoLab);
    $total1=pg_num_rows($listadoLab);
    if ($total1>0) {
        for ($i=0; $i<$total1; $i++) {
            $registro1 = pg_fetch_array ($listadoLab, $i);
            $vector[$i][0] = $registro1["idstudent"];
            $vector[$i][1] = $registro1["labnote"];
        }
    }
    return $vector;
}

// Finaliza Implementación de Funciones

session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../../pages/LogOut.php");
}

$curso=$_GET["cur"];
$seccion=$_GET["sec"];
$periodo=$_GET["per"];
$anio=$_GET["ani"];
$index = $_GET["index"];

if(strlen($seccion)==2) {
    $signo = substr($seccion,1,1);
    if ($signo=='*')
        $seccion[1]='+';
}

$contenido='';
//$conn1 = pg_connect("user=" . USR_INGRESO_NOTAS . " password=" . PWD_INGRESO_NOTAS . " dbname=" . INGENIERIA2 . " host=" . HOST_WEB . " port=5432"); // para postgreSQL
//$conn1 = pg_connect("user=" . BD_USR_INGRESO_NOTAS . " password=" . BD_PWD_INGRESO_NOTAS . " dbname=" . BD_NAME_INGENIERIA2 . " host=" . BD_HOST_INGENIERIA2 . " port=".BD_PORT_INGENIERIA2); // para postgreSQL
$conn1 = pg_connect("user=" . USR_ROOT . " password=" . PWD_ROOT . " dbname=" . DB_FMVZ . " host=" . HOST_WEB . " port=" . BD_PORT); // para postgreSQL

if ($periodo==PRIMER_SEMESTRE || $periodo==VACACIONES_DEL_PRIMER_SEMESTRE || $periodo==SEGUNDO_SEMESTRE || $periodo==VACACIONES_DEL_SEGUNDO_SEMESTRE)
    $qryListadoEstudiantes= $gsql_in_dca->_select1($curso,$seccion,$periodo,$anio,$index);

else {
    if ($periodo==PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE || $periodo==SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE)
        $periodoAnterior=PRIMER_SEMESTRE;
    else
        $periodoAnterior=SEGUNDO_SEMESTRE;
    $qryListadoEstudiantes= $gsql_in_dca->_select2($periodoAnterior,$anio,$periodo,$curso,$seccion,$index);

}
$listadoEstudiantes = pg_Exec ($conn1,$qryListadoEstudiantes);
$total=pg_num_rows($listadoEstudiantes);
if ($total) {
    $contenido="";
    for ($i=0; $i<$total; $i++) {
        $registro = pg_fetch_array ($listadoEstudiantes, $i);
        if ($i<$total-1)
            //$contenido = $contenido . ltrim($registro["idstudent"],'0')."@usac.edu.gt". "," ;
            $contenido = $contenido . $registro['email']. ",";
        else
            //$contenido = $contenido . ltrim($registro["idstudent"],'0')."@usac.edu.gt" ;
            $contenido = $contenido . $registro['email']. "," ;
    }
}

if ($contenido!='') {
    $tamanio=strlen($contenido);
    $archivoSalida = $curso . $seccion . $periodo . $anio . ".txt";
    $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header('Content-Transfer-Encoding: none');
    header('Content-Type: ' . $mime_type);
    header('Content-Type: ' . $mime_type . '; name="' . $archivoSalida . '"'); //This should work for the rest
    header('Content-Disposition: inline; filename="' . $archivoSalida . '"');
    header("Content-length: $tamanio");
    echo $contenido;
}
else {
    echo 'No se encontró ningun archivo para descarga.';
}

exit();
?>