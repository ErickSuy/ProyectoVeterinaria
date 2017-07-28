<?php
/**
 * Created by PhpStorm.
 * User: sonyvaio
 * Date: 12/11/2014
 * Time: 02:25 PM
 */

include("../../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/config/DB_Params.php");
include_once("$dir_portal/fw/model/sql/D_DownloadStudentReportManager_SQL.php");

global $gsql_in_da;
$gsql_in_da = new D_DownloadStudentReportManager_SQL();

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
    global $gsql_in_da;
    $vector = array();
    switch ($periodo) {
        case PRIMER_SEMESTRE : //$comparacionAdicional="l.aniofin>=ad.anio";
            $comparacionAdicional= $gsql_in_da->notasDeLaboratorio_select1_1();
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE : //$comparacionAdicional="(l.aniofin>ad.anio or (l.periodofin in ('02','05','06') and l.aniofin=ad.anio))";
            $comparacionAdicional= $gsql_in_da->notasDeLaboratorio_select1_2();
            break;
        case SEGUNDO_SEMESTRE : //$comparacionAdicional="(l.aniofin>ad.anio or (l.periodofin in ('05','06') and l.aniofin=ad.anio))";
            $comparacionAdicional= $gsql_in_da->notasDeLaboratorio_select1_3();
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE : //$comparacionAdicional="(l.aniofin>ad.anio or (l.periodofin in ('06') and l.aniofin=ad.anio))";
            $comparacionAdicional= $gsql_in_da->notasDeLaboratorio_select1_4();
            break;
    }

    $qryListadoLab= $gsql_in_da->notasDeLaboratorio_select1($periodo,$anio,$curso,$seccion,$comparacionAdicional,$index);

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

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../../pages/LogOut.php");
}

$curso=$_GET["cur"];
$carrera=$_GET["car"];
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
    $qryListadoEstudiantes= $gsql_in_da->_select1($curso,$carrera,$periodo,$anio,$index);

else {
    if ($periodo==PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE || $periodo==SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
        $periodoAnterior=PRIMER_SEMESTRE;
    else
        $periodoAnterior=SEGUNDO_SEMESTRE;

    $qryListadoEstudiantes= $gsql_in_da->_select2($periodoAnterior,$anio,$periodo,$curso,$carrera,$index);

}

$listadoEstudiantes = pg_Exec ($conn1,$qryListadoEstudiantes);
$total=pg_num_rows($listadoEstudiantes);
if ($total) {
    if ($periodo==PRIMER_SEMESTRE || $periodo==VACACIONES_DEL_PRIMER_SEMESTRE || $periodo==SEGUNDO_SEMESTRE || $periodo==VACACIONES_DEL_SEGUNDO_SEMESTRE) {
        $listadoLab=notasDeLaboratorio($periodo,$anio,$curso,$seccion,$conn1,$index);
        $contenido="Carnet,Apellido,Nombre,GrupoTeoria,GrupoLab,Carrera,Correo \x0D";
    }
    else
        $contenido="Carnet,Apellido,Nombre,Carrera,NumOrden,BoletaPago,Banco,FechaPago,Correo \x0D";

    for ($i=0; $i<$total; $i++) {
        $registro = pg_fetch_array ($listadoEstudiantes, $i);
        if ($periodo==PRIMER_SEMESTRE || $periodo==VACACIONES_DEL_PRIMER_SEMESTRE || $periodo==SEGUNDO_SEMESTRE || $periodo==VACACIONES_DEL_SEGUNDO_SEMESTRE) {
            if (sizeof($listadoLab))
                //$contenido = $contenido . $registro["idstudent"] . "," . $registro["name"]  ."," . ltrim($registro["idstudent"],'0')."@usac.edu.gt". "," .
                $contenido = $contenido . $registro["idstudent"] . "," . iconv('UTF-8', 'ISO-8859-1', $registro["name"]) . "," . $registro["section"] . "," . $registro["labgroup"] . "," . $registro["career"]  ."," . $registro['email'] . " \x0D";
            else
                //$contenido = $contenido . $registro["idstudent"] . "," . $registro["name"] . "," . ltrim($registro["idstudent"],'0')."@usac.edu.gt". " \x0D";
                $contenido = $contenido . $registro["idstudent"] . "," . iconv('UTF-8', 'ISO-8859-1', $registro["name"]) . "," . $registro["section"] . "," . $registro["labgroup"] . "," . $registro["career"]  ."," . $registro['email'] . " \x0D";
        }
        else
            //$contenido = $contenido . $registro["idstudent"] . "," . $registro["name"] . "," . ltrim($registro["idstudent"],'0')."@usac.edu.gt". "," . $registro["labnote"] . "," .
            $contenido = $contenido . $registro["idstudent"] . "," . iconv('UTF-8', 'ISO-8859-1', $registro["name"]) . "," . $registro['career']."," . $registro['paymentorder']. "," . $registro['paymentidnumber']. "," . $registro['bankname']. "," . $registro['paymentdate']. "," . $registro['email'] . " \x0D";
    }
}

if ($contenido!='') {
    $tamanio=strlen($contenido);
    $archivoSalida = $curso . $seccion . $periodo . $anio . ".csv";
    if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
        $UserBrowser = "Opera";
    elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
        $UserBrowser = "IE";
    else
        $UserBrowser = '';

/// important for download im most browser
    $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

    @ob_end_clean();
    @ini_set('zlib.output_compression', 'Off');
    header('Pragma: public');

    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header('Content-Transfer-Encoding: none');
    header('Content-Type: ' . $mime_type);

//header('Content-Type: application/octetstream; name="' . $archivoSalida . '"'); //This should work for IE & Opera
//header('Content-Type: application/octet-stream; name="' . $archivoSalida . '"'); //This should work for the rest
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