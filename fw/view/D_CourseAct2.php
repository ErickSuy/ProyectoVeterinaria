<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 4/10/14
 * Time: 07:04 PM
 */
include_once("../path.inc.php");
require_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/view/msg/D_LoadNotesMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");
include_once("$dir_portal/fw/controller/mapping/D_ManualLoadNotes.php");

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
    $pagina = "Location:../../pages/menu/D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&seccion=" . $_SESSION["sActaManual"]->mSeccion . "&index=" . $_SESSION["sActaManual"]->mIndex;
    header($pagina);
}

// Verificacion de que a este curso se le puedan ingresar notas
// sino se puede se redirecciona
if ($_SESSION["sActaManual"]->mAsignados == 0) {
    $pagina = "Location:../../../pages/menu/D_CourseInformationReview.php?curso=" . $_SESSION["sActaManual"]->mCurso . "&seccion=" . $_SESSION["sActaManual"]->mSeccion . "&index=" . $_SESSION["sActaManual"]->mIndex . "&msgnotas=302";
    header($pagina);
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

$titulo = "[" . $_SESSION["sActaManual"]->mCurso . "] " . $_SESSION["sActaManual"]->mNombreCorto . " " . $_SESSION["sActaManual"]->mSeccion;


// se imprimen los indices de los bloques
for ($i = 1; $i <= $_SESSION["sActaManual"]->mPaginas; $i++) {
    if ($_SESSION["sActaManual"]->mBloqueActual == $i) {
        $bloque = "<td><a href='D_CourseAct2.php?bloque=" . $i . ";'><font color='#FF3300' size='+3'>&nbsp;&nbsp;" . $i . "&nbsp;&nbsp;</font></a></td>";
    } else {
        $bloque = "<td><a href='D_CourseAct2.php?bloque=" . $i . ";'>&nbsp;&nbsp;" . $i . "&nbsp;&nbsp;</a></td>";
    }
}

$posicion_lab = 0;
$posicion_zona = 1;
$posicion_examen = 2;


$salto_zona = 1;
$salto_examen = 2;

$fila = "filaPar";
$posicion = 1;

$lab_activo = "     "; // lleva laboratorio
$salta_lab = 0; // no se salta el laboratorio
if ($_SESSION["sActaManual"]->mLaboratorio == '0') {
    $lab_activo = "disabled=true";
    $salta_lab = 1; // si se salta el laboratorio
    $salta_final = $salta_lab;
}

$zona_activo = "      "; // si hay que deshabilitar la casilla de  la zona

// Verificacion de periodos
// se deshabilita el laboratorio y la zona del curso
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
        if ($_SESSION["sActaManual"]->mBloquearLabZona > 0) {
            $lab_activo = "readonly=true";
            $zona_activo = "readonly=true";
            $salta_final = 2;
        }
}

// *****************************************************************
// bloque de codigo para los cursos 2025,2036 y 2037 (practicas), o
// para aquellos que no manejan nota sino solo aprobado/reprobado
// *****************************************************************
if ($_SESSION["sActaManual"]->mCursoSinNota == 1) // sino lleva notas el ingreso del curso
{
    $zona_activo = "readonly=true";
    $salta_final = 2;
    $es_practica = true;
    $ingreso_examen = "IngresoEnExamen_2";
    $tipo_examen = 4;
} else {
    $ingreso_examen = "IngresoEnExamen";
    $tipo_examen = 3;
}

for ($i = $_SESSION["sActaManual"]->mIndice; $i <= $_SESSION["sActaManual"]->mTope; $i++) {
    $destino = (5 * $posicion) + $salta_final;
    if ($i == $_SESSION["sActaManual"]->mTope) $destino = 5 * $posicion;
    $posicion++;


    $tpl->newBlock("LISTADO");


    $examen_activo = "  ";
    // *******************************************************************
    // bloque de codigo para los estudiantes que llevan el curso congelado
    // *******************************************************************
//Comentarizada y modificada por Pancho López el 09/10/2012 para control de los nuevos códigos de problema en la asignación
//      if($_SESSION["Acta"][$i-1][11] == 3 || $_SESSION["Acta"][$i-1][11] == 17)
    if ($_SESSION["sActaManual"]->esCursoCongelado($_SESSION["Acta"][$i - 1][11]) === true) {
        $tpl->assign("Congelado", " (congelando)");
        $lab_activo_global = $lab_activo;
        $zona_activo_global = $zona_activo;
        $examen_activo_global = $examen_activo;
        if ($_SESSION["sActaManual"]->mPeriodo == PRIMER_SEMESTRE || $_SESSION["sActaManual"]->mPeriodo == SEGUNDO_SEMESTRE) {
            $examen_activo = "readonly=true";
            $_SESSION["Acta"][$i - 1][9] = -2; // se le asigna ya directamente el valor SDE, sin derecho a examen
        } //Para nuevo manejo de congelados (Pancho López - 15/05/2012)
        elseif ($_SESSION["sActaManual"]->mPeriodo == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE || $_SESSION["sActaManual"]->mPeriodo == SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE ||
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

    // *****************************************************************
    // bloque de codigo para los cursos 2025,2036 y 2037 (practicas)
    // *****************************************************************
    if ($es_practica && $_SESSION["Acta"][$i - 1][9] >= 0) {
        $_SESSION["Acta"][$i - 1][9] = -4; // se le asigna ya directamente el valor REP
    }
    // ****************************************
    // fin del bloque para las practicas de EPS
    // ****************************************


    $tpl->assign("Laboratorio", $_SESSION["Acta"][$i - 1][8]);
    $tpl->assign("Posicion_Lab", $posicion_lab);
    $tpl->assign("Maximo", 100);
    $tpl->assign("Minimo3", 61);
    $tpl->assign("SaltoZona", $salto_zona);
    $tpl->assign("Disponible", $lab_activo);

    $tpl->assign("Zona", $_SESSION["Acta"][$i - 1][7]);
    $tpl->assign("ValorZona", $_SESSION["sActaManual"]->mZona);
    $tpl->assign("Minimo", 36);
    $tpl->assign("Minimo2", 45);
    $tpl->assign("SaltarLab", $salta_lab);
//      $tpl->assign("Minimo3",61);
    $tpl->assign("Posicion_Zona", $posicion_zona);
    $tpl->assign("SaltoExamen", $salto_examen);
    $tpl->assign("DisponibleZona", $zona_activo);

    $valor_actual = $_SESSION["Acta"][$i - 1][9];

    switch ($_SESSION["Acta"][$i - 1][9]) {
        case -4:
            $valor_actual = 'REP';
            $valor_notafinal = 'REP';
            break;

        case -3:
            $valor_actual = 'APR';
            $valor_notafinal = 'APR';
            break;

        case -1:
            $valor_actual = 'NSP';
            $valor_notafinal = 'NSP';
            break;

        case -2:
            $valor_actual = 'SDE';
            $valor_notafinal = 'SDE';
            break;

        default:
            $valor_notafinal = $_SESSION["Acta"][$i - 1][7] + $_SESSION["Acta"][$i - 1][9];

//        $valor_actual = 0;
        //               $valor_notafinal = 0;
    }

    $tpl->assign("Examen", $valor_actual);
    $tpl->assign("ValorExamen", $_SESSION["sActaManual"]->mFinal);
    $tpl->assign("Posicion_Examen", $posicion_examen);
    $tpl->assign("Salto", $destino);
    $tpl->assign("ValorNotaFinal", $valor_notafinal);
    $tpl->assign("DisponibleExamen", $examen_activo);
    $tpl->assign("IngresoExamen", $ingreso_examen);
    $tpl->assign("Tipo_Examen", $tipo_examen);
    //Para nuevo manejo de congelados (Pancho López - 15/05/2012)
//Comentarizada y modificada por Pancho López el 09/10/2012 para control de los nuevos códigos de problema en la asignación
//      if($_SESSION["Acta"][$i-1][11] == 3 || $_SESSION["Acta"][$i-1][11] == 17) {
    if ($_SESSION["sActaManual"]->esCursoCongelado($_SESSION["Acta"][$i - 1][11]) === true) {
        $lab_activo = $lab_activo_global;
        $zona_activo = $zona_activo_global;
        $examen_activo = $examen_activo_global;
    }
    // **********************************************

    $tpl->assign("Problema", $_SESSION["Acta"][$i - 1][11]);

    $fila = ($fila == "filaImpar") ? "filaPar" : "filaImpar";

    $posicion_lab += 5;
    $posicion_zona += 5;
    $posicion_examen += 5;

    $salto_zona += 5;
    $salto_examen += 5;

    $bloque_listado = sprintf("
                <tr>
                    <td> %d <!-- {Numero} --></td>
                    <td> %s <!-- {Carne} --></td>
                    <td> %s <!-- {Nombre} {Apellido} {Congelado}--></td>
                    <td>
                        <input type='hidden' name='laboratorio[]' value='0'>
                        <input type='hidden' name='zona[]' value='0'>
                        <input %s<!-- {DisponibleExamen} --> border='0' name='examen[]' align='CENTER' size='5'
                                                   maxlength='3' type='text' value= %s<!-- {Examen} --> autocomplete='off'
                                                   onkeypress='return %s<!--{IngresoExamen}-->(event,%s<!--{Posicion_Examen}-->,%s<!--{Salto}-->,<!--{SaltarLab}-->);'
                                                   onFocus='if(indice != %s<!--{Posicion_Examen}-->)
                                                           {
                                                           if( EsValido(indice,tipo,%s<!--{SaltarLab}-->) )
                                                           {
                                                           indice = %d<!--{Posicion_Examen}-->; tipo = %d<!--{Tipo_Examen}-->;
                                                           document.Bloque.elements[%d<!--{Posicion_Examen}-->].select();
                                                           }
                                                           else
                                                           {
                                                           document.Bloque.elements[indice].focus();
                                                           }
                                                           }
                                                           else document.Bloque.elements[%d<!--{Posicion_Examen}-->].select();'>

                        <input type='hidden' name='notafinal[]' value=%s<!--{ValorNotaFinal}-->>
                        <input type='hidden' name='problemal[]' value='%s<!--{Problema}-->'>
                </td>", $i, $_SESSION["Acta"][$i - 1][1], $_SESSION["Nombre"][$i - 1][nombre] . ' ' . $_SESSION["Apellido"][$i - 1][apellido]);
    $tpl->assign("Laboratorio", $_SESSION["Acta"][$i - 1][8]);
    $tpl->assign("Posicion_Lab", $posicion_lab);
    $tpl->assign("Maximo", 100);
    $tpl->assign("Minimo3", 61);
    $tpl->assign("SaltoZona", $salto_zona);
    $tpl->assign("Disponible", $lab_activo);

    $tpl->assign("Zona", $_SESSION["Acta"][$i - 1][7]);
    $tpl->assign("ValorZona", $_SESSION["sActaManual"]->mZona);
    $tpl->assign("Minimo", 36);
    $tpl->assign("Minimo2", 45);
    $tpl->assign("SaltarLab", $salta_lab);
//      $tpl->assign("Minimo3",61);
    $tpl->assign("Posicion_Zona", $posicion_zona);
    $tpl->assign("SaltoExamen", $salto_examen);
    $tpl->assign("DisponibleZona", $zona_activo);

    // ABAJO CODIGO DE MIGUEL
    $tpl->newblock("ACTIVIDADES");
    $tpl->assign("carnet", $_SESSION["Acta"][$i - 1][1]);
    // ARRIBA CODIGO DE MIGUEL


} // fin del for


$span_datos = sprintf("<tr>
        <td>
            <table width='200' border='0' align='left'>
                <tr>
                    <td>  Bloque: </td>
                    %s<!-- {Bloque} -->
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table with='640' height='82' border='1' align='center'>
                <tr>
                    <td class='encabezado' width='32'>No.</td>
                    <td class='encabezado' width='96'>Carné</td>
                    <td class='encabezado' width='256'>Estudiante</td>
                    <td class='encabezado' width='64'>Nota final</td>
                </tr>
                <!-- START BLOCK : LISTADO -->
                <tr>
                    <td class='{Fila}'> {Numero}</td>
                    <td class='{Fila}'>{Carne}</td>
                    <td class='{Fila}'> {Nombre} {Apellido} {Congelado}</td>
                    <td class='{Fila}'>
                        <input type='hidden' name='laboratorio[]' value='0'>
                        <input type='hidden' name='zona[]' value='0'>
                         <input {DisponibleExamen} border='0' name='examen[]' align='CENTER' size='5'
                                                   maxlength='3' type='text' value={Examen} autocomplete='off'
                                                   onkeypress='return {IngresoExamen}(event,{Posicion_Examen},{Salto},{SaltarLab});'
                                                   onFocus='if(indice != {Posicion_Examen})
                                                           {
                                                           if( EsValido(indice,tipo,{SaltarLab}) )
                                                           {
                                                           indice = {Posicion_Examen}; tipo = {Tipo_Examen};
                                                           document.Bloque.elements[{Posicion_Examen}].select();
                                                           }
                                                           else
                                                           {
                                                           document.Bloque.elements[indice].focus();
                                                           }
                                                           }
                                                           else document.Bloque.elements[{Posicion_Examen}].select();'
                                >

                        <input type='hidden' name='notafinal[]' value={ValorNotaFinal}>
                        <input type='hidden' name='problemal[]' value='{Problema}'>

                    </td>
                    <!-- START BLOCK : ACTIVIDADES -->
                <tr name='{carnet}' id='{
    carnet}' style='display:none'>
                    <td class='{Fila} Estilo3'>&nbsp;</td>
                    <td class='{Fila}'>&nbsp;</td>
                    <td class='{Fila}'>
                        <div align='right'><span class='{
    Fila} Estilo3'>Actividades</span></div>
                    </td>
                    <td class='{Fila}'>&nbsp;</td>
                    <td class='{Fila}'>&nbsp;</td>
                    <td colspan='2' class='{Fila}'><span class='{
    Fila} Estilo3'>Actividades</span> <span
                                class='Estilo3'>zona</span></td>
                </tr>
                <!-- START BLOCK : ACTIVIDADES -->


                <!-- END BLOCK :   LISTADO -->
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;
        </td>
    </tr>
    <script language='javascript'>
        document.Bloque.elements[0].select();
    </script>
    <tr>
        <td align='center' class='footer'>
            <div align='center'>
                <!-- INCLUDESCRIPT BLOCK : imensajenotas -->
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <table width='200' border='0' align='right'>
                <tr>
                    <td>{BtnAnterior}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type='button' name='Siguieante' id='siguiente' value='Grabar y Siguiente'
                               onClick='if (EsValido(indice, tipo,{SaltarLaboratorio}) )
                                       {
                                           document . Bloque . siguiente . disabled = true;
                                           document . Bloque . submit();
                                       }
                                       else
                                       {
                                           document . Bloque . elements[indice] . focus();
                                       };'
                                ></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>", $bloque);

?> 