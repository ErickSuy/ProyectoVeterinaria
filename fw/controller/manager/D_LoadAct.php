<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 3/10/14
 * Time: 01:51 PM
 */
include("../../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/controller/mapping/D_ManualLoadNotes.php");
include_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");

session_start();

//Verificacion de sesión
if (!isset($_SESSION["sUsuarioInterno"])) {
    $objuser = unserialize($_SESSION['usuario']);
    if (!$objuser) {
        header("Location: ../../../pages/LogOut.php");
    }
} else {}


//Creacion de las instancia
$_SESSION["sActaManual"] = new D_ManualLoadNotes(); //Para manejo de ingreso de notas manual

$_SESSION["sActaManual"]->mCurso = $_SESSION["sObjNotas"]->mCurso;
$_SESSION["sActaManual"]->mIndex = $_SESSION["sObjNotas"]->mIndex;
$_SESSION["sActaManual"]->mSeccion = $_SESSION["sObjNotas"]->mSeccion;
$_SESSION["sActaManual"]->mCarrera = $_SESSION["sObjNotas"]->mCarrera;
$_SESSION["sActaManual"]->mTipoCurso = $_SESSION["sObjNotas"]->mTipoCurso;
$_SESSION["sActaManual"]->mPeriodo = $_SESSION["sObjNotas"]->mPeriodo;
$_SESSION["sActaManual"]->mZona = $_SESSION["sObjNotas"]->mZona;
$_SESSION["sActaManual"]->mFinal = $_SESSION["sObjNotas"]->mFinal;
$_SESSION["sActaManual"]->mAnio = $_SESSION["sObjNotas"]->mAnio;
$_SESSION["sActaManual"]->mNombreCorto = $_SESSION["sObjNotas"]->mNombreCorto;
$_SESSION["sActaManual"]->mHorario = $_SESSION["sObjNotas"]->mHorario;
$_SESSION["sActaManual"]->mAsignados = $_SESSION["sObjNotas"]->mAsignados;
$_SESSION["sActaManual"]->mEstado = $_SESSION["sObjNotas"]->mEstado;
$_SESSION["sActaManual"]->mLaboratorio = $_SESSION["sObjNotas"]->mLaboratorio;
$_SESSION["sActaManual"]->mCursoSinNota = $_SESSION["sObjNotas"]->mCursoSinNota;
$_SESSION["sActaManual"]->mBloquearLabZona = $_SESSION["sObjNotas"]->mBloquearLabZona;
$_SESSION["sActaManual"]->retrasadaUnica = $_SESSION["sObjNotas"]->retrasadaUnica;
$_SESSION["sActaManual"]->mHabilitado = $_SESSION["sObjNotas"]->mHabilitado;
$_SESSION["sActaManual"]->mEscuela = $_SESSION["sObjNotas"]->mEscuela;
$_SESSION["sActaManual"]->gsql = $_SESSION["sObjNotas"]->gsql;

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

// se inserta en ingresoregistro
$_SESSION["sActaManual"]->InsertarRegistro('modomanual', 0, $objuser->getId(), $objuser->getGroup());

if ($_SESSION["sActaManual"]->mCursoSinNota == 1)
    $_SESSION["sActaManual"]->LlenarIngresoTemporal_2($objuser->getId());
else // Se llena la tabla ingresotemporal
    $_SESSION["sActaManual"]->LlenarIngresoTemporal($objuser->getId());

// se guarda la consistencia entre lo que posee asignacion e ingresotemporal
$_SESSION["sActaManual"]->GuardarConsistencia($objuser->getId());


// Se calcula el #de paginas y de registros por pagina
$_SESSION["sActaManual"]->PaginasYregistros();

// ciclo para llenar la matriz de listado
$_SESSION["sActaManual"]->ListadoEstudiantes();

if ($_SESSION["sActaManual"]->mCursoSinNota == 1) {
    header("Location: actadelcurso_2.php");
} else {
    header("Location: ../../../pages/menu/D_CourseAct.php");
}

?>