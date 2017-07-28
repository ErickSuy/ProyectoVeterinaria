<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 28/05/2015
 * Time: 07:56 PM
 */


include_once("../../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");

session_start();

$posicion = strrpos($_SERVER['HTTP_REFERER'], "?");
$origenInicial = substr($_SERVER['HTTP_REFERER'], 0, $posicion);
$partes = explode("/", $origenInicial);
$origen = "";

for ($i = 2; $i < sizeof($partes); $i++) {
    if ($i == 2)
        $origen = $partes[$i];
    else
        $origen = $origen . "/" . $partes[$i];
}
$url_origen_1 = "coac.fmvz.usac.edu.gt/pages/menu/D_CourseInformationReview.php";
$url_origen_2 = "coac.fmvz.usac.edu.gt/pages/menu/D_CourseInformationReview.php";
$url_origen_3 = "coac.fmvz.usac.edu.gt/pages/menu/D_CourseInformationReview.php";
$url_origen_4 = "coac.fmvz.usac.edu.gt/pages/menu/D_CourseInformationReview.php";

// necesario para determinar si el curso esta habilitado?
if (isset($_SESSION["sObjNotas"])) {

    if (($_SESSION["sObjNotas"]->mHabilitado == 1) && (strcmp($origen, $url_origen_1) == 0 || strcmp($origen, $url_origen_2) == 0 ||
            strcmp($origen, $url_origen_3) == 0 || strcmp($origen, $url_origen_4) == 0)
    ) {
        header("Location:../../../pages/menu/D_ApprovedActList.php");
    } else {
        // sino se redirecciona a la info general del curso
        $seccion = $_SESSION["sObjNotas"]->mSeccion;
        if (strlen($seccion) == 2) {
            $signo = substr($_SESSION["sObjNotas"]->mSeccion, 1, 1);
            if ($signo == '+') {
                $seccion[1] = '*';
            }
        }
        $destino = "Location:../../../pages/menu/COAC_CourseDetailInformation.php?curso=" . $_SESSION["sObjNotas"]->mCurso . "&carrera=" . $_SESSION["sObjNotas"]->mCarrera . "&index=" . $_SESSION["sObjNotas"]->mIndex;
        header($destino);
        die;
    }
}

$url_origen_1 = "coac.fmvz.usac.edu.gt/pages/menu/COAC_CourseDetailInformation.php";
$url_origen_2 = "coac.fmvz.usac.edu.gt/pages/menu/COAC_CourseDetailInformation.php";
$url_origen_3 = "coac.fmvz.usac.edu.gt/pages/menu/COAC_CourseDetailInformation.php";
$url_origen_4 = "coac.fmvz.usac.edu.gt/pages/menu/COAC_CourseDetailInformation.php";

if (strcmp($origen, $url_origen_1) == 0 || strcmp($origen, $url_origen_2) == 0 ||
    strcmp($origen, $url_origen_3) == 0 || strcmp($origen, $url_origen_4) == 0
)
    header("Location: ../../../pages/menu/D_CourseAct.php?bloque=1");
?>