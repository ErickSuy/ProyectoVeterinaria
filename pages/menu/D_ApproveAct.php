<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 7/10/14
 * Time: 08:07 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/D_LoadNotesMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/mapping/D_ManualLoadNotes.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

session_start();

//Verificacion de sesión
if (!isset($_SESSION["sUsuarioInterno"])) {
    $objuser = unserialize($_SESSION['usuario']);
    if (!$objuser) {
        header("Location: ../../pages/LogOut.php");
    }
}

// Verificacion de que a este curso se le puedan ingresar notas
// sino se puede se redirecciona
if ($_SESSION["sActaManual"]->mHabilitado == 0) {
    $pagina = "Location: D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&carrera=" . $_SESSION["sActaManual"]->mCarrera . "&index=" . $_SESSION["sActaManual"]->mIndex;
    header($pagina);
    die;
}


// Verificacion de que a este curso se le puedan ingresar notas
// sino se puede se redirecciona
if ($_SESSION["sActaManual"]->mAsignados == 0) {
    $pagina = "Location: D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&carrera=" . $_SESSION["sActaManual"]->mCarrera . "&index=" . $_SESSION["sActaManual"]->mIndex . "&msgnotas=302";
    header($pagina);
    die;
}
$obj_cad = new ManejoString();
$tpl = new TemplatePower("D_ApproveAct.tpl");
if (!isset($_SESSION["sUsuarioInterno"])) {
    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");
} else {
}
$tpl->prepare();

//  ahora se tiene que considerar siendo tipo 2 = laboratorio normal y 6 = practica tipo laboratorio
//  if($_SESSION["sActaManual"]->mLaboratorio == '1')
if ($_SESSION["sActaManual"]->mLaboratorio == 2 || $_SESSION["sActaManual"]->mLaboratorio == 6) {
    // Comentarizado por Edwin Sabán, ya que no se maneja lab por aparte
    // $_SESSION["sActaManual"]->GenerarLaboratorioFinal();
}

$_SESSION["sVectorAprobacion"][0][0] = '';
$_SESSION["sVectorAprobacion"][0][1] = '';
$_SESSION["sVectorAprobacion"][0][2] = 0;
$_SESSION["sVectorAprobacion"][0][3] = 0;
$_SESSION["sVectorAprobacion"][0][4] = 0;
// Valida la informacion sobre el laboratorio, zona y examenfinal
for ($pos = 0; $pos < $_SESSION["sActaManual"]->mAsignados; $pos++) {

    $_SESSION["sVectorAprobacion"][$pos][0] = $_SESSION["Acta"][$pos][1]; //carne
    $_SESSION["sVectorAprobacion"][$pos][1] = $_SESSION["Acta"][$pos][2]; //carrera

//  ahora se tiene que considerar siendo tipo 2 = laboratorio normal y 6 = practica tipo laboratorio
//  if($_SESSION["sActaManual"]->mLaboratorio == '1')
    if ($_SESSION["sActaManual"]->mLaboratorio == 2 || $_SESSION["sActaManual"]->mLaboratorio == 6){
        // Comentarizado por Edwin Sabán ya que no se guardan notas de laboratorios
        //$_SESSION["sVectorAprobacion"][$pos][2] = $_SESSION["sActaManual"]->DarLaboratorioFinal($_SESSION["Acta"][$pos][1], $_SESSION["sActaManual"]->mEscuela,
          //  $_SESSION["Acta"][$pos][8]);
        $_SESSION["sVectorAprobacion"][$pos][2] = 0;
    }
    //$_SESSION["sVectorAprobacion"][$pos][2] = $_SESSION["labfinal"][$pos][0]; //zonalaboratorio
    else    {
        $_SESSION["sVectorAprobacion"][$pos][2] = 0;
    }

    $_SESSION["sVectorAprobacion"][$pos][3] = $_SESSION["Acta"][$pos][8]; //zona
    $_SESSION["sVectorAprobacion"][$pos][4] = $_SESSION["Acta"][$pos][10]; //examenfinal

    // Comentarizado por Edwin Sabán ya que no existen congelados
    /*
//         if (($_SESSION["Acta"][$pos][11] == 3)||($_SESSION["Acta"][$pos][11] == 17))
    if ($_SESSION["sActaManual"]->esCursoCongelado($_SESSION["Acta"][$pos][12]) === true) {
        // $_SESSION["sActaManual"]->ValidaCongelado($pos,$_SESSION["labfinal"][$pos][0],$_SESSION["Acta"][$pos][7]);

        $_SESSION["sActaManual"]->ValidaCongelado($pos, $_SESSION["sVectorAprobacion"][$pos][2], $_SESSION["Acta"][$pos][8]);
    } else {
        //$_SESSION["sActaManual"]->ValidaNoCongelado($pos,$_SESSION["labfinal"][$pos][0],$_SESSION["Acta"][$pos][7]);
        if ($_SESSION["sActaManual"]->mCursoSinNota == 0) // debe ser curso con notas
        {
            // Comentarizado por Edwin Sabán ya que no se guardan notas de laboratorios
            //$_SESSION["sActaManual"]->ValidaNoCongelado($pos, $_SESSION["sVectorAprobacion"][$pos][2], $_SESSION["Acta"][$pos][8]);
        }
    }
*/
    $str2 = "";
    //print_r($_SESSION["sVectorAprobacion"][$pos]); echo  "<br>";
    $str2 = implode(',', $_SESSION["sVectorAprobacion"][$pos]) . "<br>";
}
//Fin del for de Valida laboratorio, zona y examenfinal

//die;

$tpl->assign("vFecha", Date("d-m-Y"));
$tpl->assign("vHora", Date("H:i"));
$tpl->assign("vCurso", $_SESSION["sActaManual"]->mCurso);
$tpl->assign("vNombre", $_SESSION["sActaManual"]->mNombreCorto);
$tpl->assign("vPeriodo", $obj_cad->funTextoPeriodo($_SESSION["sActaManual"]->mPeriodo));
$tpl->assign("periodo", $_SESSION["sActaManual"]->mPeriodo);
$tpl->assign("vAnio", $_SESSION["sActaManual"]->mAnio);
$tpl->assign("vCarrera", $obj_cad->StringCarrera('0'.$_SESSION["sActaManual"]->mCarrera));

$tpl->assign('aParametros', 'txtCursoNombre='.$_SESSION["sActaManual"]->mNombreCorto.'&txtCarrera='.$obj_cad->StringCarrera('0'.$_SESSION["sActaManual"]->mCarrera).'&txtPeriodo='.$obj_cad->funTextoPeriodo($_SESSION["sActaManual"]->mPeriodo).'&txtAnio='.$_SESSION["sActaManual"]->mAnio.'&txtCurso='.$_SESSION["sActaManual"]->mCurso);
$zonasTotales = array();
// se imprime cada tupla de cada estudiante
for ($i = 1; $i <= $_SESSION["sActaManual"]->mAsignados; $i++) {

    $tpl->newBlock("LISTADO");

    $tpl->assign("Fila", $estiloclass);
    $tpl->assign("Numero", $i);
    $tpl->assign("Carne", $_SESSION["Acta"][$i - 1][1]);
    $tpl->assign("Nombre",trim($_SESSION["Nombre"][$i - 1][name]));
    $tpl->assign("Apellido", trim($_SESSION["Apellido"][$i - 1][surname]));

    $zonasTotales[$i][nombre] = trim($_SESSION["Apellido"][$i - 1][surname]) . ', ' . trim($_SESSION["Nombre"][$i - 1][name]);
    $zonasTotales[$i][carnet] = trim($_SESSION["Acta"][$i - 1][1]);
    $congelando = "   ";

//      if($_SESSION["Acta"][$i-1][11] == 3 || $_SESSION["Acta"][$i-1][11] == 17)
    if ($_SESSION["sActaManual"]->esCursoCongelado($_SESSION["Acta"][$i - 1][12]) === true) {
        $congelando = "(congelando)";
    }
    $tpl->assign("Congelando", $congelando);


//      $tpl->assign("Laboratorio",$_SESSION["labfinal"][$i-1]);
    //print_r($_SESSION["sVectorAprobacion"]);die;
    $tpl->assign("Laboratorio", $_SESSION["sVectorAprobacion"][$i - 1][2]);

//      switch($_SESSION["Acta"][$i-1][9])
    switch ($_SESSION["sVectorAprobacion"][$i - 1][4]) {
        case -1:
            $examen = 'NSP';
            //$nota_final = 'NSP';
            $nota_final = (int)$_SESSION["Acta"][$i - 1][8];
            break;

        case -2:
            $examen = 'SDE';
            //$nota_final = 'SDE';
            $nota_final = (int)$_SESSION["Acta"][$i - 1][8];
            break;

        case -3:
            $examen = 'APR';
            $nota_final = 'APR';
            break;

        case -4:
            $examen = 'REP';
            $nota_final = 'REP';
            break;
        default:
            $examen = (int)$_SESSION["Acta"][$i - 1][10];
            $nota_final = (int)$_SESSION["Acta"][$i - 1][8] + $examen;
    }

    $tpl->assign("Zona", (int)$_SESSION["sVectorAprobacion"][$i - 1][3]);
    $tpl->assign("Examen", $examen);
    $tpl->assign("NotaFinal", $nota_final);

    $zonasTotales[$i][zona] = (int)$_SESSION["sVectorAprobacion"][$i - 1][3];
    $zonasTotales[$i][examen] = trim($examen);
    $zonasTotales[$i][nota] = trim($nota_final);
} // fin del for
$_SESSION['notasFinales'] = serialize($zonasTotales);
$tpl->gotoBlock("_ROOT");

$tpl->printToScreen();
unset($tpl);
//  unset($_SESSION["sBdd"]);

?>
