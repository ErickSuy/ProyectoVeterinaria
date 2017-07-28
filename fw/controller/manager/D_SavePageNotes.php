<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 7/10/14
 * Time: 07:05 AM
 */

include("../../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/D_LoadNotesMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/mapping/D_ManualLoadNotes.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

session_start();

$laboratorio = $_POST['laboratorio'];
$zona = $_POST['zona'];
$examen = $_POST['examen'];


//Verificacion de sesión
if (!isset($_SESSION["sUsuarioInterno"])) {
    $objuser = unserialize($_SESSION['usuario']);
    if (!$objuser) {
        header("Location: ../../../pages/LogOut.php");
    }
}

// Verificacion de que a este curso se le puedan ingresar notas
// sino se puede se redirecciona
if ($_SESSION["sActaManual"]->mHabilitado == 0) {
    $pagina = "Location:../../../pages/menu/D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&carrera=" . $_SESSION["sActaManual"]->mCarrera . "&index=" . $_SESSION["sActaManual"]->mIndex;
    header($pagina);
    die;
}


// Verificacion de que a este curso se le puedan ingresar notas
// sino se puede se redirecciona
if ($_SESSION["sActaManual"]->mAsignados == 0) {
    $pagina = "Location:../../../pages/menu/D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&carrera=" . $_SESSION["sActaManual"]->mCarrera . "&index=" . $_SESSION["sActaManual"]->mIndex . "&msgnotas=302";
    header($pagina);
    die;
}

$delapagina = ($_SESSION["sActaManual"]->mTope - $_SESSION["sActaManual"]->mIndice) + 1;
// j es el indice tabla web ingresotemporal que es el Acta
$j = $_SESSION["sActaManual"]->mIndice - 1;


// sino tiene laboratorio se deje igual la columna
// si tiene (==1) se traslada
//  ahora se tiene que considerar siendo tipo 2 = laboratorio normal y 6 = practica tipo laboratorio
if ($_SESSION["sActaManual"]->mLaboratorio == 2 || $_SESSION["sActaManual"]->mLaboratorio == 6) {
    // Se traslada los datos del laboratorio sino NO
    for ($i = 0; $i < $delapagina; $i++) // desde cero
    {
        $_SESSION["Acta"][$j][9] = $laboratorio[$i];
        $j++;
    }
}

$j = $_SESSION["sActaManual"]->mIndice - 1;
// llenar tabla web ingresotemporal con datos de la pagina
for ($i = 0; $i < $delapagina; $i++) // desde cero
{
    $_SESSION["Acta"][$j][8] = $zona[$i]; // columna zona

    switch ($examen[$i]) {
        case 'NSP':
            $_SESSION["Acta"][$j][10] = -1; // columna examen [9]
            break;

        case 'SDE':
            $_SESSION["Acta"][$j][10] = -2; // columna examen [9]
            break;
        case 'APR':
            $_SESSION["Acta"][$j][10] = -3; // columna examen [9]
            break;

        case 'REP':
            $_SESSION["Acta"][$j][10] = -4; // columna examen [9]
            break;
        default:
            $_SESSION["Acta"][$j][10] = $examen[$i]; //columna examen [9]
    }
    $j++;
}

$_SESSION["sActaManual"]->GrabaraIngresoTemporal();

if ($_SESSION["sActaManual"]->mBloqueActual < $_SESSION["sActaManual"]->mPaginas) {
    if ($_SESSION["sActaManual"]->mCursoSinNota == 1) // es un curso que no lleva notas
        header("Location: actadelcurso_2.php?bloque=" . ($_SESSION["sActaManual"]->mBloqueActual + 1));
    else
        header("Location:../../../pages/menu/D_CourseAct.php?bloque=" . ($_SESSION["sActaManual"]->mBloqueActual + 1));
} else {
    header("Location: ../../../pages/menu/D_ApproveAct.php");
}
?>