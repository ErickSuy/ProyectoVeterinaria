<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 03/05/2015
 * Time: 9:57 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/AR_ValidatePayment.php");
include_once("$dir_portal/fw/controller/manager/AR_PaymentOrderGenerationManager.php");
include_once("$dir_portal/fw/controller/manager/AR_PaymentOrderGenerationRegisterManager.php");
include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");

require_once("$dir_biblio/biblio/librerias_externas/array2xml.class.php");
require_once("$dir_biblio/biblio/librerias_externas/class.xml_a_array.inc.php");

//include_once("SOAP/Client.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
define("CURSO_APROBADO", 3);
define("FECHA_LIMITE_CADUCADA", 2);
define("EXAMEN_YA_ASIGNADO", 1);
define("NO_ZONA_MINIMA", 0);

if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$msg[2] = 'curso a asignarse condicionado por falta de acta';
$msg[325] = 'PROCESO NO CONCLUIDO -- VUELVA A INTENTARLO MAS TARDE';
$msg[310] = "EL MONTO DE PAGOS DETECTADOR POR EL SISTEMA NO CUBRE EL TOTAL DEL CURSOS QUE QUIERE REGISTRAR.";
$msg[320] = "NUMERO DE RECIBO, NO VALIDO.";

$cadenadeAsignacion="000000000,200011847,200715500,200722625,200810431,200915818,200916143,200918257,201013442,201013443,201013758,201021738,201111964,201124099,201210744,201210779,201318175,201407927";
if (substr_count($cadenadeAsignacion,$objuser->getId())==0)
{ //header("Location: boletaasignacion.php");
    // header("Location: ../estudiantes/boletaCursosAsignados.php");
    header("Location: ../../pages/menu/ViewProfileInfo.php");
    die;
}

$tpl = new TemplatePower("AR_PaymentOrderGeneration.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$asig = new  AR_PaymentOrderGenerationManager($objuser->getId(), $objuser->getCareer());
$obj_cad = new ManejoString();
$pasr=0;

if (isset($_POST["btnContinuar"])) {
    $numeroCursos = $_POST["totalcur"];
    $asig->mPerAnterior = $_SESSION['periodoanterior'];
    $asig->mPeriodo = $_SESSION["periodoproceso"];
    $asig->mAnio = $_SESSION["anioproceso"];

    for ($i = 1; $i <= $_POST["totalcur"]; $i++) {
        if (isset($_POST["curso" . $i])) {
            if ((strcmp($_POST["curso" . $i], "") != 0) AND ($_POST["curso" . $i] != NULL)) {
                $marca = $_POST[$_POST["curso" . $i]];
                $marcaAsig = $_POST["marcacurso" . $i];
                $info = $asig->cargainfoDatosPreAsignados($i, $_POST["curso" . $i], $marca, $marcaAsig);
            }
        }
    }

    $asig->mNumCursos = $i-1;
    $error = 1;
    $asig->calculaMontoDePago();

    $_SESSION["datosGenerales"]->usuarioid = $objuser->getId();
    $_SESSION["datosGenerales"]->carrera = $objuser->getCareer();
    $_SESSION["datosGenerales"]->nombreEstudiante = $objuser->getName().' '.$objuser->getSurName();

    if (strcmp($_SESSION["periodoproceso"], 0) == 0) {
        $_SESSION["datosGenerales"]->periodo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
    } else {
        $_SESSION["datosGenerales"]->periodo = $_SESSION["periodoproceso"];
    }

    if (strcmp($_SESSION["anioproceso"], 0) == 0) {
        $_SESSION["datosGenerales"]->anio = 2014;
    } else {
        $_SESSION["datosGenerales"]->anio = $_SESSION["anioproceso"];
    }
    if ($numeroCursos > 0) {
        if($_SESSION['monto_Generar']>0) {
            $pasr=1;
            $asig->codError = 310;
        }
        else {
            $asigna = new AR_PaymentOrderGenerationRegisterManager(); // crea clase de insercion de codigo
            $errinsert = $asigna->registrarAsignacion();

            if ( $errinsert != 0) {
                if ($presentaBoleta == FALSE) {
                    header("Location:AR_PaymentOrder.php");
                }   // redireccionar la pagina de boleta de asignacion si la asignación fue exitosa
                else {
                    header("Location:AR_PaymentOrder.php");
                }   // redireccionar la pagina de boleta de mostrar boleta de pago
            }
            else {// $tpl->assign("ErrorGeneral",$msg[$errinsert]);
                $asig->codError = 325;
            }
        }

        /**
         * Cuando ya este integrados los pagos con el SIIF usar esta parte y crear el otro script
         */
        /*
        $asigna = new AR_PaymentOrderGenerationRegisterManager(); // crea clase de insercion de codigo
        //
        $errinsert = $asigna->insertaCursos();
       // printf("ERROR::::%d<br>", $errinsert);
        $errinsert=1;
        if ($errinsert != 0) {
            if ($presentaBoleta == FALSE) {
                header("Location:./AR_PaymentOrder.php");
            }   // redireccionar la pagina de boleta de asignacion si la asignación fue exitosa
            else {
                header("Location:./AR_PaymentOrder.php");
            }   // redireccionar la pagina de boleta de mostrar boleta de pago
        } else {// $tpl->assign("ErrorGeneral",$msg[$errinsert]);
            $asig->codError = 325;
        }
        */
    } else {
        $tpl->assign("ErrorGeneral", $msg[$errGenerarboleta]);
    }

} else {
    if (!$asig->CargarDatosAcademicos()) {
        $tpl->newblock("b_sinasignacion");

        $_mensajeTxt = "*** El sistema no se encuentra habilitado en esta fecha ...";

        $tpl->assign("aMensaje", $_mensajeTxt);
    } else {
        $verpago = new AR_ValidatePayment($objuser->getId(),$objuser->getCareer(),$_SESSION["periodoproceso"],$_SESSION["anioproceso"]);
        $valor = $verpago->valida_pagorealizados_retrasadas();

        if($valor == FALSE) {
            $tpl->newblock("b_sinasignacion");

            $_mensajeTxt = "*** No se encontró registro de pagos realizados en este momento (vuelva a intentelo más tarde). Si ya realizó algún pago y el sistema sigue sin detectarlo diríjase al Segundo Nivel Edifico M6. Unidad de Administración Virtual.";

            $tpl->assign("aMensaje", $_mensajeTxt);
        } else {
            $info = $asig->obtieneDatosCursosAsignarRetrasada(); // obtiene informacion de cursos a asignar
            $asig->verCurAsignados();
        }
    }
}
$periodo = 0 + $asig->mPeriodo;

if ($info != false) {


    $asig->verCurAprobados(); // logra verificar que cursos fueron ya aprobados
    $asig->verZonasCursos();  // ver que cursos llegaron a zona minima

    $asig->verificaFechaExamen_bloquea(); // verifica si la fecha de examen ya fue realizada
//printf("%d",$asig->mNumCursos);

    $tpl->newBlock("b_asignados");
    $tpl->assign("aPeriodo", $obj_cad->funTextoPeriodo($asig->mPeriodo));
    $tpl->assign("aAnio", $asig->mAnio);
    $tpl->assign("vSubmit", "Registrar derecho retrasada");

    for ($i = 1; $i <= $asig->mNumCursos; $i++) {
        if ((strcmp($_SESSION["cursosAsig"][$i]['curso'], "") != 0) AND ($_SESSION["cursosAsig"][$i]['curso'] != NULL)) {
            $tpl->newBlock("despCurso");
            $tpl->assign("numcurso", "curso" . $i);
            $tpl->assign("marcacurso", "marcacurso" . $i);
            $tpl->assign("curso", trim($_SESSION["cursosAsig"][$i]['curso']));
// print_r($_SESSION["cursosAsig"][$i]);print "<br>";
            $tpl->assign("nombre_curso", trim(' (' . $_SESSION["cursosAsig"][$i]['curso'] . ') ' . $_SESSION["cursosAsig"][$i]['mNomCurso']));

            // Se excluyen los cursos marcados como:
            // 'mEstadoAsignar' = 3 (Curso ya cargado como curso aprobado)
            // 'mEstadoAsignar' = 5 (Congelados para los cuales aún no se ha aprobado la retrasada única)
            if (($_SESSION["cursosAsig"][$i]['mEstadoAsignar'] == NO_ZONA_MINIMA) OR // No llego a zona minima
                ($_SESSION["cursosAsig"][$i]['mEstadoAsignar'] == EXAMEN_YA_ASIGNADO) OR // Ya se asigno la retrasada del curso
                ($_SESSION["cursosAsig"][$i]['mEstado'] == EXAMEN_YA_ASIGNADO) OR // Ya se asigno la retrasada del curso
                ($_SESSION["cursosAsig"][$i]['mEstadoAsignar'] == FECHA_LIMITE_CADUCADA)
            ) {

                $elEstado = $_SESSION["cursosAsig"][$i]['mEstadoAsignar'];

                switch ($elEstado) {
                    case NO_ZONA_MINIMA:
                        $tpl->assign("habilita", 'disabled');
                        $tpl->assign("observacion", 'NO TIENE ZONA MÍNIMA');
                        break;
                    case EXAMEN_YA_ASIGNADO:
                        $tpl->assign("habilita", 'checked'); // Se chequea por defecto
                        $tpl->assign("observacion", 'EXÁMEN YA ASIGNADO');
                        break;
                    case FECHA_LIMITE_CADUCADA:
                        $tpl->assign("habilita", 'disabled');
                        $tpl->assign("observacion", 'FECHA PARA PAGO YA FINALIZÓ');
                        break;
                }

                // Curso ya asignado como retrasada
                if ($_SESSION["cursosAsig"][$i]['mEstado'] == EXAMEN_YA_ASIGNADO) {
                    $tpl->assign("habilita", 'checked'); // Se chequea por defecto
                }

            } else {
                $elEstado = $_SESSION["cursosAsig"][$i]['mEstadoAsignar'];

                switch ($elEstado) {
                    case 4:
                        $tpl->assign("observacion", 'CURSO SIN PROBLEMA');
                        break;
                }
                $tpl->assign("habilita", '');
            }

            $tpl->assign("valor", 'on');
            $tpl->assign("marca", $_SESSION["cursosAsig"][$i]['mEstadoAsignar']);
            $estado = 0 + $_SESSION["cursosAsig"][$i]['mEstadoAsignar'];
            //$tpl->assign("observacion", $msg[$estado]);
        }  // del if de comparacion de cursos en blanco
    } // del for de $i

    $tpl->assign("totalcursos", ($i-1));
    if ($asig->codError >= 310) {
        $tpl->gotoBlock( "_ROOT" );
        $tpl->newblock("erroresManejables1");
        $tpl->assign("mensaje", $msg[$asig->codError]);
        $tpl->assign("aTipoMensaje",'danger');
        $tpl->assign("aEncabezadoMensaje",'PROBLEMA');
    }

    $tpl->gotoBlock("_ROOT");
    //$nav->RegistroNavegacion(0);      // Agregado por Edwin Saban
}

$tpl->printToScreen();  //imprime el resultado

unset($tpl);
unset($asig, $obj_cad);

?>