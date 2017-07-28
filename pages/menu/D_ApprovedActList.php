<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 9/10/14
 * Time: 02:52 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

//  echo "mostrar listado aprobado"; die;

$_SESSION["sObjNotas"]->ListarAprobados($objuser->getId());
$informacion_curso = $_SESSION["sObjNotas"]->COAC_DarInformacionDeCurso($_SESSION["sObjNotas"]->mAnio,$_SESSION["sObjNotas"]->mPeriodo,$_SESSION["sObjNotas"]->mCarrera, $_SESSION["sObjNotas"]->mCurso);
$obj_cad = new ManejoString();

$tpl = new TemplatePower("D_ApprovedActList.tpl");
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$tpl->assign("vFecha", Date("d-m-Y"));
$tpl->assign("vHora", Date("H:i"));
$tpl->assign("vCurso", $_SESSION["sObjNotas"]->mCurso);
$tpl->assign("vNombre", $_SESSION["sObjNotas"]->mNombreCorto);
$tpl->assign("vPeriodo", $obj_cad->funTextoPeriodo($_SESSION["sObjNotas"]->mPeriodo));
$tpl->assign("periodo", $_SESSION["sObjNotas"]->mPeriodo);
$tpl->assign("vAnio", $_SESSION["sObjNotas"]->mAnio);
$tpl->assign("vCarrera", $obj_cad->StringCarrera('0'.$_SESSION["sObjNotas"]->mCarrera));

$cuenta_aprobados = 0;
$cuenta_perdidos = 0;
$nota_aprobacion = 61;

switch((int)$_SESSION["sObjNotas"]->mPeriodo) {
    case PRIMER_SEMESTRE:
    case SEGUNDO_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
        if($_SESSION["sObjNotas"]->mTipoCurso==2) {
            $nota_aprobacion = 70;
        }
        break;
    case VACACIONES_DEL_PRIMER_SEMESTRE:
    case VACACIONES_DEL_SEGUNDO_SEMESTRE:
    $nota_aprobacion = 70;
        break;
}

$catedraticos= str_replace('"', "", $informacion_curso[0][teacher]);
$catedraticos= str_replace('{', "",$catedraticos);
$catedraticos= str_replace('}', "",$catedraticos);

switch((int)$_SESSION["sObjNotas"]->mPeriodo) {
    case PRIMER_SEMESTRE:
    case SEGUNDO_SEMESTRE:
    case VACACIONES_DEL_PRIMER_SEMESTRE:
    case VACACIONES_DEL_SEGUNDO_SEMESTRE:
    $fecha = $informacion_curso[0][exam_date];
        break;
    case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    $fecha = $informacion_curso[0][exam1_date];
        break;
    case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    $fecha = $informacion_curso[0][exam2_date];
        break;
}

$tpl->assign('aParametros', 'txtCursoNombre='.$_SESSION["sObjNotas"]->mNombreCorto.'&txtCarrera='.$obj_cad->StringCarrera('0'.$_SESSION["sObjNotas"]->mCarrera).'&txtPeriodo='.$obj_cad->funTextoPeriodo($_SESSION["sObjNotas"]->mPeriodo).'&txtAnio='.$_SESSION["sObjNotas"]->mAnio.'&txtCurso='.$_SESSION["sObjNotas"]->mCurso.'&txtCiclo='.$informacion_curso[0][cyclename].'&txtFecha='.$fecha.'&txtDocente='.$catedraticos.'&periodo='.$_SESSION["sObjNotas"]->mPeriodo);

$zonasTotales = array();
// se imprime cada tupla de cada estudiante
for ($i = 1; $i <= $_SESSION["sObjNotas"]->mAsignados; $i++) {
    $tpl->newBlock("LISTADO");

    $tpl->assign("Numero", $i);
    $tpl->assign("Cui", trim( $_SESSION["Aprobado"][$i - 1][12]));
    $tpl->assign("Carne", $_SESSION["Aprobado"][$i - 1][0]);
    $tpl->assign("Nombre", trim($_SESSION["Aprobado"][$i - 1][1]));
   // $tpl->assign("Apellido", trim($_SESSION["Aprobado"][$i - 1][2]));
    //$zonasTotales[$i][nombre] = trim($_SESSION["Aprobado"][$i - 1][2]) . ', ' . trim($_SESSION["Aprobado"][$i - 1][1]);
    $zonasTotales[$i][nombre] = trim(trim($_SESSION["Aprobado"][$i - 1][1])) . " (" . $_SESSION["Aprobado"][$i - 1][0] . ")";
    $zonasTotales[$i][carnet] = trim( $_SESSION["Aprobado"][$i - 1][12]);
    $congelando = "   ";
//Comentarizada y modificada por Pancho López el 09/10/2012 para el nuevo control de códigos de problema en la asignación
//      if($_SESSION["Aprobado"][$i-1][3] == 3 || $_SESSION["Aprobado"][$i-1][3] == 17)

    if ($_SESSION["sObjNotas"]->esCursoCongelado($_SESSION["Aprobado"][$i - 1][3]) === true) {
        $congelando = "(congelando)";
    }
    $tpl->assign("Congelando", $congelando);

    //$tpl->assign("Laboratorio", (int)($_SESSION["Aprobado"][$i - 1][4]));
    $tpl->assign("Zona", (int)$_SESSION["Aprobado"][$i - 1][5]);

    //  switch($_SESSION["Aprobado"][$i-1][6])
    $examen = $_SESSION["Aprobado"][$i - 1][6];
    $nota_final = $_SESSION["Aprobado"][$i - 1][7];
    /*
    switch ($_SESSION["Aprobado"][$i - 1][7]) {
        case -1:
            $examen = 'NSP';
            //$nota_final = 'NSP';
            $nota_final = (int)$_SESSION["Aprobado"][$i - 1][5];
            break;

        case -2:
            $examen = 'SDE';
            //$nota_final = 'SDE';
            $nota_final = (int)$_SESSION["Aprobado"][$i - 1][5];
            if ($_SESSION["sObjNotas"]->mCursoSinNota == 1) $cuenta_perdidos++;
            break;

        case -3:
            $examen = 'APR';
            $nota_final = 'APR';
            $cuenta_aprobados++;
            break;

        case -4:
            $examen = 'REP';
            $nota_final = 'REP';
            $cuenta_perdidos++;
            break;

        default:
            $examen = (int)$_SESSION["Aprobado"][$i - 1][6];
            $nota_final = (int)$_SESSION["Aprobado"][$i - 1][5] + $examen;
    } // Fin del Switch
*/
    if ($_SESSION["sObjNotas"]->mCursoSinNota == 0) {

        // Calcula la cantidad de estudiantes que aprobaron y reprobaron un curso
        if ($nota_final >= $nota_aprobacion) {
            $cuenta_aprobados++;
        } else {
            $cuenta_perdidos++;
        }
    }

    $tpl->assign("Examen", $examen);
    $tpl->assign("NotaFinal", $nota_final);

    $zonasTotales[$i][zona] = (int)$_SESSION["Aprobado"][$i - 1][5];
    $zonasTotales[$i][examen] = trim($examen);
    $zonasTotales[$i][nota] = trim($nota_final);
    $zonasTotales[$i][derecho] = (int)$_SESSION["Aprobado"][$i - 1][10];
} // fin del for
$_SESSION['notasFinales'] = serialize($zonasTotales);
$tpl->gotoBlock("_ROOT");

if(strcmp($objuser->getId(),'20111203')==0 || strcmp($objuser->getId(),'17204')==0 ) {
  $tpl->newBlock('ACTACURSO');
}
$tpl->gotoBlock("_ROOT");
$tpl->assign("Aprobados", $cuenta_aprobados);
$tpl->assign("Perdidos", $cuenta_perdidos);

$tpl->printToScreen();
unset($tpl);
unset($_SESSION["Aprobado"]);

?>