<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 9/10/14
 * Time: 08:29 AM
 */

include("../../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");

session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../../pages/LogOut.php");
}

if ($_SESSION["sObjNotas"]->mCursoSinNota == 0)
    $_SESSION["sObjNotas"]->CrearArchivo();
else    $_SESSION["sObjNotas"]->CrearArchivo_2();


// a partir de aqui codigo que realiza la descarga de un archivo
$dir = '/var/www/downloads/';
//$file = $dir.basename($_REQUEST['dl']);
$archivo =  $_SESSION["sObjNotas"]->mIndex . "_" . $_SESSION["sObjNotas"]->mCurso .  $_SESSION["sObjNotas"]->mCarrera/*$_SESSION["sObjNotas"]->mSeccion*/ . $_SESSION["sObjNotas"]->mPeriodo . $_SESSION["sObjNotas"]->mAnio . ".csv";
$file = $dir . $archivo;
//if (isset($_REQUEST['dl']) && file_exists($file) ) {
if (file_exists($file)) {
    $f = fopen($file, "rb");
    $content_len = (int)filesize($file);
    $content_file = fread($f, $content_len);
    fclose($f);

    //$output_file = basename($_REQUEST['dl']);
    $output_file = $archivo;

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

    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header('Content-Transfer-Encoding: none');
    header('Content-Type: ' . $mime_type);

    header('Content-Type: ' . $mime_type . '; name="' . $output_file . '"'); //This should work for the rest
    header('Content-Disposition: inline; filename="' . $output_file . '"');
    header("Content-length: $content_len");

    echo $content_file;
} else {
    echo 'No file with this name for download.';
}

exit();
?>
