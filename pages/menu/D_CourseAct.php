<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 5/10/14
 * Time: 03:05 AM
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

define('CURSOS_SIN_NOTA','744');

session_start();
$bloque = $_GET['bloque'];
//Verificacion de sesión
if (!isset($_SESSION["sUsuarioInterno"])) {
    $objuser = unserialize($_SESSION['usuario']);
    if (!$objuser) {
        header("Location: ../../pages/LogOut.php");
    }
}
$obj_cad = new ManejoString();
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

$tpl = new TemplatePower("D_CourseAct.tpl");
if (!isset($_SESSION["sUsuarioInterno"])) {
    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");
} else {
}

$tpl->prepare();
$tpl->assign("periodo", $_SESSION["sActaManual"]->mPeriodo);
$tpl->assign("laboratorio", $_SESSION["sActaManual"]->mLaboratorio);
$tpl->assign("escuela", $_SESSION["sActaManual"]->mEscuela);
$tpl->assign("Anio", $_SESSION["sActaManual"]->mAnio);

switch((int)$_SESSION["sActaManual"]->mPeriodo) {
    case PRIMER_SEMESTRE:
    case SEGUNDO_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    case SUFICIENCIAS_DEL_PRIMER_SEMESTRE:
    case SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE:
    if($_SESSION["sActaManual"]->mTipoCurso==2) {
        if (((int)$_SESSION["sActaManual"]->mCurso)==744) {
            $tpl->assign("vMinZona", 0);
            $tpl->assign("vMaxZona", 0);
            $tpl->assign("vMaxExamen", 100);
            $tpl->assign('vTipoCursoNota',1);
        } else  {
            $tpl->assign("vMinZona", 50);
            $tpl->assign("vMaxZona", 80);
            $tpl->assign("vMaxExamen", 20);
            $tpl->assign('vTipoCursoNota',1);
        }
        
    } else {
        if (((int)$_SESSION["sActaManual"]->mCurso)==744) {
            $tpl->assign("vMinZona", 0);
            $tpl->assign("vMaxZona", 0);
            $tpl->assign("vMaxExamen", 100);
            $tpl->assign('vTipoCursoNota',1);
        } else  {
            $tpl->assign("vMinZona", 31);
            $tpl->assign("vMaxZona", 70);
            $tpl->assign("vMaxExamen", 30);
            $tpl->assign('vTipoCursoNota',1);
        } 
    }
    break;
    case VACACIONES_DEL_PRIMER_SEMESTRE:
    case VACACIONES_DEL_SEGUNDO_SEMESTRE:
    $tpl->assign("vMinZona", 40);
    $tpl->assign("vMaxZona", 70);
    $tpl->assign("vMaxExamen", 30);
    $tpl->assign('vTipoCursoNota',1);
    break;
}

if (isset($bloque)) {
    if ($bloque != $_SESSION["sActaManual"]->mBloqueActual) {
        $_SESSION["sActaManual"]->mIndice += ($bloque - $_SESSION["sActaManual"]->mBloqueActual) * $_SESSION["sActaManual"]->mRegistros;
        $_SESSION["sActaManual"]->mTope = $bloque * $_SESSION["sActaManual"]->mRegistros;
        if ($_SESSION["sActaManual"]->mTope > $_SESSION["sActaManual"]->mAsignados)
            $_SESSION["sActaManual"]->mTope = $_SESSION["sActaManual"]->mAsignados;
        $_SESSION["sActaManual"]->mBloqueActual = $bloque;
    }
}

$tpl->assign("vFecha", Date("d-m-Y"));
$tpl->assign("vHora", Date("H:i"));
$tpl->assign("vCurso", $_SESSION["sActaManual"]->mCurso);
$tpl->assign("vNombre", $_SESSION["sActaManual"]->mNombreCorto);
$tpl->assign("vPeriodo", $obj_cad->funTextoPeriodo($_SESSION["sActaManual"]->mPeriodo));
$tpl->assign("vAnio", $_SESSION["sActaManual"]->mAnio);
$tpl->assign("vCarrera", $obj_cad->StringCarrera('0'.$_SESSION["sActaManual"]->mCarrera));

for ($i = 1; $i <= $_SESSION["sActaManual"]->mPaginas; $i++) {
    if ($_SESSION["sActaManual"]->mBloqueActual == $i) {
        $bloque = "<td><a href='D_CourseAct.php?bloque=" . $i . ";'><font color='#FF3300' size='+3'>" . $i . "</font></a></td>";
    } else {
        $bloque = "<td><a href='D_CourseAct.php?bloque=" . $i . ";'><font color='#FFF'>" . $i . "</font></a></td>";
    }

    $tpl->newBlock("PAGINACION");
    $tpl->assign("Pagina", $bloque);
}

$tpl->gotoBlock("_ROOT");

$posicion_lab = 0;
$posicion_zona = 1;
$posicion_examen = 2;

$salto_zona = 1;
$salto_examen = 2;

$posicion = 1;

$lab_activo = "     "; // lleva laboratorio
$salta_lab = 0; // no se salta el laboratorio


//Condición comentarizada para que se salte hasta la casilla del examen final
//if ($_SESSION["sActaManual"]->mLaboratorio == '0') {
    $lab_activo = "disabled=true";
    $salta_lab = 2; // si se salta el laboratorio
    $salta_final = $salta_lab;
//}

$zona_activo = "      "; // si hay que deshabilitar la casilla de  la zona

switch ($_SESSION["sActaManual"]->mPeriodo) {
    case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
        $lab_activo = "readonly=true";
        $zona_activo = "readonly=true";
        $salta_final = 2;
        break;
    default  :
        //Condición comentarizada para que se salte hasta la casilla del examen final
        //if ($_SESSION["sActaManual"]->mBloquearLabZona > 0) {
            $lab_activo = "readonly=true";
            $zona_activo = "readonly=true";
            $salta_final = 2;
        //}
}

for ($i = $_SESSION["sActaManual"]->mIndice; $i <= $_SESSION["sActaManual"]->mTope; $i++) {
    $destino = (5 * $posicion) + $salta_final;
    if ($i == $_SESSION["sActaManual"]->mTope) $destino = 5 * $posicion;
    $posicion++;


    $tpl->newBlock("CATALOGO");
    $tpl->assign("Numero", $i);
    $tpl->assign("Carne", $_SESSION["Acta"][$i - 1][1]);
    $tpl->assign("Nombre", trim($_SESSION["Nombre"][$i - 1][name]));
    $tpl->assign("Apellido", trim($_SESSION["Apellido"][$i - 1][surname]));

    $tpl->assign("Congelado", "    ");
    $examen_activo = "  ";
    // *******************************************************************
    // bloque de codigo para los estudiantes que llevan el curso congelado
    // *******************************************************************
    if ($_SESSION["sActaManual"]->esCursoCongelado($_SESSION["Acta"][$i - 1][11]) === true) {
        $tpl->assign("Congelado", " (congelando)");
        $lab_activo_global = $lab_activo;
        $zona_activo_global = $zona_activo;
        $examen_activo_global = $examen_activo;
        if ($_SESSION["sActaManual"]->mPeriodo == PRIMER_SEMESTRE || $_SESSION["sActaManual"]->mPeriodo == SEGUNDO_SEMESTRE) {
            $examen_activo = "readonly=true";
            $_SESSION["Acta"][$i - 1][9] = -2; // se le asigna ya directamente el valor SDE, sin derecho a examen
        } elseif ($_SESSION["sActaManual"]->mPeriodo == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE || $_SESSION["sActaManual"]->mPeriodo == SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE ||
            $_SESSION["sActaManual"]->mPeriodo == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE || $_SESSION["sActaManual"]->mPeriodo == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE
        ) {
            if ($_SESSION["sActaManual"]->aproboRetrasadaUnica($_SESSION["Acta"][$i - 1][1], $_SESSION["sActaManual"]->mCurso,
                    $_SESSION["sActaManual"]->mPeriodo) === false
            ) {
                $examen_activo = "readonly=true";
                $_SESSION["Acta"][$i - 1][9] = -2; // se le asigna ya directamente el valor SDE, sin derecho a examen
            }
        }
        // **********************************************

    }
    // ***********************************
    // fin del bloque para curso congelado
    // ***********************************

    $tpl->assign("Laboratorio", (int)$_SESSION["Acta"][$i - 1][9]);
    $tpl->assign("Posicion_Lab", $posicion_lab);
    $tpl->assign("Maximo", 100); // INTRODUCTORIO & BASICO & MODULAR
    $tpl->assign("Minimo3", 61); // INTRODUCTORIO & BASICO & MODULAR
    $tpl->assign("SaltoZona", $salto_zona);
    $tpl->assign("Disponible", $lab_activo);

    $tpl->assign("Zona", (int)$_SESSION["Acta"][$i - 1][8]);
    $tpl->assign("ValorZona", $_SESSION["sActaManual"]->mZona);
    $tpl->assign("Minimo", 31); // INTRODUCTORIO & BASICO
    $tpl->assign("Minimo2", 50); // NIVEL MODULAR
    $tpl->assign("SaltarLab", $salta_lab);
    $tpl->assign("Posicion_Zona", $posicion_zona);
    $tpl->assign("SaltoExamen", $salto_examen);
    $tpl->assign("DisponibleZona", $zona_activo);

//()
/*
    if (substr_count(CURSOS_MODULARES,($_SESSION["Acta"][$i - 1][3])."")==0)
    {
        if(((int)$_SESSION["Acta"][$i - 1][8])<31) {
            $_SESSION["Acta"][$i - 1][10] = -2;
        }
    } else{
        if(((int)$_SESSION["Acta"][$i - 1][8])<50) {
            $_SESSION["Acta"][$i - 1][10] = -2;
        }
    }
*/
    $valor_actual = (int)$_SESSION["Acta"][$i - 1][10];

    switch ((int)$_SESSION["Acta"][$i - 1][10]) {
        case -1:
            $valor_actual = 'NSP';
            //$valor_notafinal = 'NSP';
            $valor_notafinal = (int)$_SESSION["Acta"][$i - 1][8];
            break;

        case -2:
            $valor_actual = 'SDE';
            //$valor_notafinal = 'SDE';
            $valor_notafinal = (int)$_SESSION["Acta"][$i - 1][8];
            //$examen_activo = 'readonly=true';
            break;

        default:
            $valor_notafinal = (int)$_SESSION["Acta"][$i - 1][8] + (int)$_SESSION["Acta"][$i - 1][10];
    }

    $tpl->assign("Examen", $valor_actual);
    $tpl->assign("ValorExamen", $_SESSION["sActaManual"]->mFinal);
    $tpl->assign("Posicion_Examen", $posicion_examen);
    $tpl->assign("Salto", $destino);
    $tpl->assign("ValorNotaFinal", $valor_notafinal);
    $tpl->assign("DisponibleExamen", $examen_activo);

    if ($_SESSION["sActaManual"]->esCursoCongelado($_SESSION["Acta"][$i - 1][11]) === true) {
        $lab_activo = $lab_activo_global;
        $zona_activo = $zona_activo_global;
        $examen_activo = $examen_activo_global;
    }

    $tpl->assign("Problema", $_SESSION["Acta"][$i - 1][11]);

    $posicion_lab += 5;
    $posicion_zona += 5;
    $posicion_examen += 5;

    $salto_zona += 5;
    $salto_examen += 5;

} // fin del for

$tpl->gotoBlock("_ROOT");
$tpl->assign("SaltarLaboratorio", $salta_lab);
$tpl->printToScreen();
unset($tpl);
?> 