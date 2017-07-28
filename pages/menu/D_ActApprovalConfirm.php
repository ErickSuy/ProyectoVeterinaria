<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 7/10/14
 * Time: 02:52 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/D_ActApprovalConfirm_SQL.php");
include_once("$dir_portal/fw/controller/mapping/D_ManualLoadNotes.php");


function obtieneNombrePeriodo($periodo)
{
    $nombreP = "";
    switch ($periodo) {
        case PRIMER_SEMESTRE:
            $nombreP = "Primer Semestre";
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE:
            $nombreP = "Vacaciones (Primer Semestre)";
            break;
        case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "Primera Retrasada (Primer Semestre)";
            break;
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "Segunda Retrasada (Primer Semestre)";
            break;
        case SEGUNDO_SEMESTRE:
            $nombreP = "Segundo Semestre";
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Vacaciones (Segundo Semestre)";
            break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Primera Retrasada (Segundo Semestre)";
            break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "Segunda Retrasada (Segundo Semestre)";
            break;
        default:
            $nombreP = "Primer Semestre";
    }
    return $nombreP;
}

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
    $pagina = "Location:../../pages/menu/D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&carrera=" . $_SESSION["sActaManual"]->mCarrera . "&index=" . $_SESSION["sActaManual"]->mIndex;
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

global $gsql_in_apn;
$gsql_in_apn = new D_ActApprovalConfirm_SQL();


//Obtiene el nombre del archivo y el tamanio si el ingreso fuera por archivo
$nombrearchivo = '';
$tamanioarchivo = 0;
$_SESSION["sActaManual"]->ObtieneDatosArchivo($nombrearchivo, $tamanioarchivo);

// cambio de estado de la acta
switch ($_SESSION["sActaManual"]->mEstado) {
    // acta aprobada sin incovenientes
    case 3  :
        $_SESSION["sActaManual"]->mEstado = 5;
        break;
    // acta aprobada con espera de recuperar la impresa
    case 4  :
        $_SESSION["sActaManual"]->mEstado = 6;
        break;
    // acta aprobada con el sistema de ingreso interno, se asigna para carga automáticamente
    case 9  :
        $_SESSION["sActaManual"]->mEstado = 10;
        break;
} // fin del switch($estado)
//    $query_crear = sprintf("select f_createmp();");

$query_crear = $gsql_in_apn->_select1();

$_SESSION["sNotas"]->query($query_crear);

$notasfinales = pg_copy_to($_SESSION["sNotas"]->Link_ID, "webtablafinal", "|");
//print_r($notasfinales);die;
for ($i = 0; $i < $_SESSION["sActaManual"]->mAsignados; $i++) {
    $notasfinales[$i] = implode("|", $_SESSION["sVectorAprobacion"][$i]);
    echo $notasfinales[$i]."<br>";
}

// en webtablafinal estan los datos finales
pg_copy_from($_SESSION["sNotas"]->Link_ID, "webtablafinal",
    $notasfinales, "|");

// Se define una varible mininalab que indica la nota minima de aprobacion del laboratorio
// actualmente para laboratorio y practica tipo laboratorio se usa 61

switch ($_SESSION["sActaManual"]->mLaboratorio) {
    case 2:
        $minimalab = 61;
        break;
    case 6:
        $minimalab = 61;
        break;
}


// inicia transaccion
$_SESSION["sNotas"]->query($gsql_in_apn->begin_transaction());

// PASO 1: Movimientos de las notas de laboratorio
// Se realiza los movimientos de los laboratorios
// PASO 2: Se consolida la informacion en la info de asignacion
// PASO 3: Se inserta en bitacoraacta, se actualiza en horario y en ingresoregistro

// Todo lo anterior se lleva dentro de este procedimiento almacenado con parametros:
$query_final = $gsql_in_apn->_select2($objuser->getId(),
    $nombrearchivo,
    $tamanioarchivo,
    $_SESSION["sActaManual"]->mCurso,
    $_SESSION["sActaManual"]->mCarrera
    /*$_SESSION["sActaManual"]->mSeccion*/,
    $_SESSION["sActaManual"]->mPeriodo,
    $_SESSION["sActaManual"]->mAnio,
    $_SESSION["sActaManual"]->mEstado,
    0/*$_SESSION["sActaManual"]->mLaboratorio*/,
    $_SESSION["sPeriodo"],
    $_SESSION["sAnio"],
    $minimalab,
    $_SESSION["sActaManual"]->mIndex
);

$_SESSION["sNotas"]->query($query_final);

// finaliza la transaccion
$_SESSION["sNotas"]->query($gsql_in_apn->commit_transaction());
//print_r($_SESSION["sNotas"]);die; 
//  die;
// actualizacion automatica de los periodos de retrasada correspondientes
// si el periodo es de finales es 01 o  05
if (strcmp(trim($_SESSION["sActaManual"]->mPeriodo), PRIMER_SEMESTRE . '') == 0 ||
    strcmp(trim($_SESSION["sActaManual"]->mPeriodo), SEGUNDO_SEMESTRE . '') == 0
) {
    // se necesita saber en que estado esta la primera retrasada y la segunda retrasada
    if (strcmp(trim($_SESSION["sActaManual"]->mPeriodo), PRIMER_SEMESTRE . '') == 0) {
        $consulta = $gsql_in_apn->_select3($_SESSION["sActaManual"]->mAnio,
            $_SESSION["sActaManual"]->mCurso,
            $_SESSION["sActaManual"]->mCarrera
            /*$_SESSION["sActaManual"]->mSeccion*/,
            $_SESSION["sActaManual"]->mIndex);

    } else {
        $consulta = $gsql_in_apn->_select4($_SESSION["sActaManual"]->mAnio,
            $_SESSION["sActaManual"]->mCurso,
            $_SESSION["sActaManual"]->mCarrera
            /*$_SESSION["sActaManual"]->mSeccion*/,
            $_SESSION["sActaManual"]->mIndex);

    }

    $_SESSION["sNotas"]->query($consulta);
    $numerodefilas = $_SESSION["sNotas"]->num_rows();

    //echo "numerodefinal ".$numerodefilas;  die;

    for ($i = 1; $i <= $numerodefilas; $i++) {
        $_SESSION["sNotas"]->next_record();
        $periododeretrasada[$i - 1] = trim($_SESSION["sNotas"]->f('idschoolyear'));
        $estadoderetrasada[$i - 1] = $_SESSION["sNotas"]->f('idactstate');
    }

    for ($i = 1; $i <= $numerodefilas; $i++) {
        // se actualizan los periodos de retrasada correspondientes  03,04 o 07,08
        $_SESSION["sNotas"]->query($gsql_in_apn->begin_transaction());
        $valor1 = $periododeretrasada[$i - 1];
        $valor2 = $estadoderetrasada[$i - 1];
//             $consulta = "select f_actualizarretrasada('".$_SESSION["sActaManual"]->mAnio."','".$_SESSION["sActaManual"]->mPeriodo."','$valor1',$valor2,'".$_SESSION["sActaManual"]->mCurso."','".$_SESSION["sActaManual"]->mSeccion."');";

        $consulta = $gsql_in_apn->_select5($_SESSION["sActaManual"]->mAnio, $_SESSION["sActaManual"]->mPeriodo, $valor1, $valor2, $_SESSION["sActaManual"]->mCurso, $_SESSION["sActaManual"]->mSeccion, $_SESSION["sActaManual"]->mIndex);

        $_SESSION["sNotas"]->query($consulta);

        $_SESSION["sNotas"]->query($gsql_in_apn->commit_transaction());
    }

}
// fin de la autolizacion automatica

//  echo "Se termino"; die;
if (!isset($_SESSION["sUsuarioInterno"])) {
    unset($_SESSION["sObjNotas"]);
    unset($_SESSION["sActaManual"]);
    header("Location: D_CourseList.php");
    die;
//    header("Location: listadocursos.php?periodo=".$_SESSION["sPeriodo"]."&anio=".$_SESSION["sAnio"]);
} else {
    $tpl = new TemplatePower("aprobacion.tpl");
    $tpl->assignInclude("itop", "../topIngresoInterno.htm");
    $tpl->prepare();

    $tpl->assign("Titulo", "(" . $_SESSION["sActaManual"]->mCurso . ")  " . $_SESSION["sActaManual"]->mNombreCorto);
    $tpl->assign("Anio", $_SESSION["sActaManual"]->mAnio);
    $tpl->assign("Periodo", obtieneNombrePeriodo($_SESSION["sActaManual"]->mPeriodo));
    $tpl->assign("Seccion", $_SESSION["sActaManual"]->mSeccion);
    $tpl->assign("Horario", $_SESSION["sActaManual"]->mHorario);
    $tpl->assign("Asignados", $_SESSION["sActaManual"]->mAsignados);
    //$tpl->assign("Estado",$_SESSION["sActaManual"]->mEstado);
    $tpl->assign("Estado", "Asignada para Carga");
    if ($_SESSION["sActaManual"]->mLaboratorio)
        $tpl->assign("Laboratorio", "Curso con Laboratorio");
    else
        $tpl->assign("Laboratorio", "Curso sin Laboratorio");
    $tpl->assign("Aprobar", "Aprobada con el Sistema de Ingreso de Notas Interno.<br>" .
        "El acta ha sido asignada para carga automáticamente.");

    $tpl->assign("Msg", " ");
    $tpl->newBlock("REGRESAR");
    $tpl->assign("REGISTRO", $_SESSION["sUsuarioInterno"]);
    $tpl->assign("CICLO", $_SESSION["sActaManual"]->mAnio);
    $tpl->assign("ELPERIODO", $_SESSION["sActaManual"]->mPeriodo);
// fin de nuevo bloque
//    $tpl->assign("Msg","");
    $tpl->printToScreen();
    unset($_SESSION["sObjNotas"]);
    unset($_SESSION["sActaManual"]);
    unset($tpl);
}
?>