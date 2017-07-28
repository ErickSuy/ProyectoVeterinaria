<?php

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/model/sql/cargaactividad_SQL.php");

global $gsql_na_caa;
$gsql_na_caa = new cargaactividad_SQL();
global $obj_cad;
$obj_cad = new ManejoString();

//Inicia Sección de Implementación de Funciones

//Definición de variables globales

function cambiarFormatoFecha($fecha)
{
    list($anio, $mes, $dia) = explode("/", $fecha);
    return $dia . "-" . $mes . "-" . $anio;
}

function ImprimeFecha($fecha)
{
    list($anio, $mes, $dia) = explode("-", $fecha);
    return $dia . "-" . $mes . "-" . $anio;
}

// funcion que descarga el vector para validar el maximo valor a ingresar en una actividad
// este valor fue modificado para que sea 100 el valor maximo, ya que las notas se ingresan
// de 0 a 100 puntos.
function DescargaValidacionJavascript()
{
    global $tpl;
    $RecorreActividades = 0;
    while ($RecorreActividades < $_SESSION[TotalActividades]) {
        $tpl->newblock("ponderacionValida");
        // LA SIGUIENTE LINEA SE COLOCO PARA VALIDAR DE 0 A 100. . . .
        //$tpl->assign("nuevaponderacion", "VecPonderacion[" . $RecorreActividades . "]=100");
// LA SIGUIENTE LINEA HACE QUE LA VALIDACION SEA DE 0 AL VALOR DE LA PONDERACION.
          $tpl->assign("nuevaponderacion","VecPonderacion[".$RecorreActividades."]=".$_SESSION[VecPonderacionActividades][$RecorreActividades]);
        $tpl->newblock("nuevavalidacion");

        $tpl->assign("NumeroActividad", "ValorActividad" . $_SESSION[VecPosicionActividades][$RecorreActividades]);
// ORIGINAL          $tpl->assign("PonderacionActividad",$_SESSION[VecPonderacionActividades][$RecorreActividades]);
        $tpl->assign("PonderacionActividad", 100);

        $RecorreActividades++;
    } // del while recorreactividades


} // de la funcion DescargaValidacionJavaScript
// fin de la FUNCION DESCARGAVALIDADCIONJAVASCRIPT


// Funcion que levanta el vector de estudiantes con las notas respectivas de cada una de las
// actividades del curso, 
// eje x: ordenado por el numero de carnet
// eje y: ordenado por cronologicamente por la fecha de la actividad.
function LevantaNotasActividades($Curso, $Seccion, $Periodo, $Anio)
{
    global $gsql_na_caa;
    global $tpl;
    global $bd;

    $RecorreActividades = 0;
    $CompletaSelect = "";
    while ($RecorreActividades < $_SESSION[TotalActividades]) {
//original   $CompletaSelect=$CompletaSelect." ,act".$_SESSION[VecPosicionActividades][$RecorreActividades];
        $CompletaSelect = $CompletaSelect . " ,actividades[" . $_SESSION[VecPosicionActividades][$RecorreActividades] . "] as act" . $_SESSION[VecPosicionActividades][$RecorreActividades] . " ";
        $RecorreActividades++;
    }

//echo "<br>CompletaSelect == $CompletaSelect";

    unset($_SESSION[$VecAlumnos]);
    $_SESSION[TotalAlumnos] = 0;
    $_SESSION[$VecNombreAlumnos] = 0;
//    $SqlAlumnos=" select aa.carnet,(trim(e.nombre)||' '||trim(e.apellido)) as nombre $CompletaSelect
//                 from ing_notasactividad aa, asignaciondetalle ad, asignacion a, estudiante e
//                 where
//				 a.transaccion=ad.transaccion and a.fechaasignacion=ad.fechaasignacion 
//				 and a.usuarioid = aa.carnet
//				 and aa.periodo=ad.periodo and aa.anio=ad.anio
//				 and aa.curso = ad.curso and aa.seccion = ad.seccion
//				 and e.usuarioid=a.usuarioid 
//				 and ad.curso='$Curso'
//                 and ad.seccion='$Seccion'
//                 and ad.periodo='$Periodo'
//                 and ad.anio=$Anio
//                 order by aa.Carnet
//                 ";

    $SqlAlumnos = $gsql_na_caa->LevantaNotasActividades_select1($CompletaSelect, $Curso, $Seccion, $Periodo, $Anio);

    //echo "<br>SQLAlumnos== $SqlAlumnos";	die;
    $bd->query($SqlAlumnos);

    //$ResultadoAlumnos= mysql_query($SqlAlumnos);
    //$FilasTotalAlumnos=mysql_num_rows($ResultadoAlumnos);
    $FilasTotalAlumnos = $bd->num_rows();
    //echo "<br> SqlAlumnos== $SqlAlumnos";
    if ($FilasTotalAlumnos > 0) {
        $RecorreAlumnos = 0;
        while (($bd->next_record()) != null) {
            $FilaDatoAlumno = $bd->r();
            $_SESSION[VecNombreAlumno][$RecorreAlumnos] = $FilaDatoAlumno["nombre"];
            $_SESSION[VecCarnetAlumno][$RecorreAlumnos] = $FilaDatoAlumno["carnet"];
            $RecorreActividades = 0;
//              echo "<br>";
            while ($RecorreActividades < $_SESSION[TotalActividades]) {
                $NombreCampo = "act" . $_SESSION[VecPosicionActividades][$RecorreActividades];

                //echo "<br> El nombre del campo: $NombreCampo==".$FilaDatoAlumno[$NombreCampo];
                $_SESSION[VecNotasActividades][$RecorreAlumnos][$RecorreActividades] = $FilaDatoAlumno[$NombreCampo];
                $RecorreActividades++;
            }
            $RecorreAlumnos++;
        } // del Recorre alumnos
        $_SESSION[TotalAlumnos] = $RecorreAlumnos;
    } else {
        if ($_SESSION[ExisteSeccionMagistral] == 0) {
            header("location: ../notas-actividades/cargaactividad.php?opcion=4");

        } else {
            $tpl->newblock("mensaje");
            $tpl->assign("mensaje", "No se encontraron alumnos");
        }
    }

} // de la funcion LevantaAlumnos
// fin de la funcion que levanta el vector de alumnos.


// ///////////////////////////////////////////  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// funcion que imprime la fila de encabezados (rotulos) de cada actividad. ordenado por la fecha de la actividad.
function ImprimeEncabezadoActvidades($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $Inicio)
{
    global $tpl;
    global $obj_cad;
    $tpl->gotoBlock("_ROOT");
    $tpl->assign("vCurso", $txtCurso);
    $tpl->assign("vNombre", $_SESSION["nombrecorto"]);
    $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $txtSeccion));
    $tpl->assign("vPeriodo", $_SESSION["nombreperiodo"]);
    $tpl->assign("vAnio", $txtAnio);
    $tpl->assign("vFecha", Date("d-m-Y"));
    $tpl->assign("vHora", Date("H:i"));

//    echo "<br> VINO A IMPRIMIR ENCABEZADOS";
    $RecorreActividades = 0;

    $tpl->newblock("tablalistado");
    $tpl->assign("txtCurso", $txtCurso);
    //$tpl->assign("txtSeccion", $txtSeccion);
    $tpl->assign("txtCarrera", $txtSeccion);
    $tpl->assign("txtPeriodo", $txtPeriodo);
    $tpl->assign("txtAnio", $txtAnio);
    $tpl->assign("txtInicio", $Inicio);
    $tpl->assign("TituloPagina", $_SESSION[TituloPagina]);


    //echo "<br> Total Actividades en Imprime Encabezados: ".$_SESSION[TotalActividades];
    while ($RecorreActividades < ($_SESSION[TotalActividades])) {
//        echo "<br> VINO A IMPRIMIR ENCABEZADOS$RecorreActividades";

        $tpl->newblock("nuevoencabezadoactividad");
        $tpl->assign("ActividadID", 'A'.($RecorreActividades+1));
        $tpl->assign("NombreActividad", $_SESSION[VecNombreActividades][$RecorreActividades]);
        $tpl->assign("vTipoActividad", $_SESSION[VecTipoActividades][$RecorreActividades]);
        $tpl->assign("vFechaActividad", $_SESSION[VecFechaActividades][$RecorreActividades]);
        $tpl->assign("vDocenciaActividad", $_SESSION[VecDocenciaActividades][$RecorreActividades]);

        $tpl->assign("Ponderacion", $_SESSION[VecPonderacionActividades][$RecorreActividades]);//number_format($_SESSION[VecPonderacionActividades][$RecorreActividades] . "<br>pos:" . $_SESSION[VecPosicionActividades][$RecorreActividades]));
        $RecorreActividades = $RecorreActividades + 1;
    }// del while RecorreActvidades<$_SEssion[TotalActividades] . . . . .
} // de la funcion ImprimeEncabezadoActividades
// ///////////////////////////////////////////  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


// ///////////////////////////////////////////  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
function ImprimeListadoEstudiante($Inicio)
{
    global $tpl;
    $RecorreAlumnos = 0;
    $Indice = 1;
    $MiElemento = 5;
    //echo "<br> Inicia a imprimir en: ".$Inicio;
    while (/* AGREGAR CAMBIOS*/
        ($RecorreAlumnos < $_SESSION[AlumnosXHoja]) && /* AGREGAR CAMBIOS*/
        ($RecorreAlumnos + $Inicio) < $_SESSION[TotalAlumnos]) {
        $tpl->newblock("filaalumno");
        $tpl->assign("no", $RecorreAlumnos + 1 + $Inicio);
        $tpl->assign("PosicionVector", $RecorreAlumnos + $Inicio);
        $tpl->assign("CarnetAlumno", $_SESSION[VecCarnetAlumno][$RecorreAlumnos + $Inicio]);
        $tpl->assign("NombreAlumno", $_SESSION[VecNombreAlumno][$RecorreAlumnos + $Inicio]);
        $RecorreActividades = 0;
        $MiElemento = $MiElemento + 2;
        while ($RecorreActividades < $_SESSION[TotalActividades]) {
//		      echo "<br> entro . . . .";
            $tpl->newblock("datofilaactividad");
            $tpl->assign("nombrecampoactividad", "ValorActividad" . $_SESSION[VecPosicionActividades][$RecorreActividades] . "[]");
            $tpl->assign("valorcampoactividad", $_SESSION[VecNotasActividades][$RecorreAlumnos + $Inicio][$RecorreActividades]);
            $tpl->assign("txtTabIndex", $Indice);
            if ($RecorreActividades == ($_SESSION[TotalActividades] - 1)) {
                $tpl->assign("txtSiguienteElemento", $MiElemento + 2);
            } else {
                $tpl->assign("txtSiguienteElemento", $MiElemento);
            }
            if ($_SESSION[VecPermiteIngresoActividades][$RecorreActividades] == 0) {
                $txtReadOnly = "readonly";
                $txtAlt = "No se puede modificar el valor de la nota";
            } else {
                $txtReadOnly = "";
                $txtAlt = "";
            }
            $tpl->assign("txtReadOnly", $txtReadOnly);
            $RecorreActividades++;
            $MiElemento++;
            $Indice++;
        }
        $RecorreAlumnos++;
    }
    return $RecorreAlumnos;
} // fin de la funcion ImprimeListadoEstudiante
// ///////////////////////////////////////////  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


function ImprimeTablaIngreso($Inicio, $txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
{
    global $tpl;
    DescargaValidacionJavascript();
    ImprimeEncabezadoActvidades($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $Inicio);
    $Desplegados = ImprimeListadoEstudiante($Inicio);

    $tpl->gotoBlock('_ROOT');

    $tpl->assign("InitTabla",'<script>var table = $("#dgTablaDatos").DataTable( {language: {url: "../../libraries/js/DataTables-1.10.6/lang/es_ES.json"},scrollY: "536px", scrollX: true, scrollCollapse: false, paging: false,searching: false, ordering: false,columnDefs: [{ width: "3%", targets: 0 }, {width: "7%", targets: 1 }, {width: "40%", targets: 2 }]});new $.fn.dataTable.FixedColumns( table, {leftColumns: 3});</script>');

    /* AGREGAR CAMBIOS*/
    $tpl->newblock("tablabotones");
    $Anterior = $Inicio - $_SESSION[AlumnosXHoja];
    if ($Anterior < 0) $Anterior = 0;
    if ($Anterior + 1 >= $_SESSION[TotalAlumnos]) $Anterior = $_SESSION[TotalAlumnos] - 1;
    $tpl->assign("txtAnterior", $Anterior);
    $tpl->assign("txtSiguiente", $Desplegados + $Inicio);
    $tpl->assign("Desplegados", $Desplegados);
    $tpl->assign("Inicio", $Inicio);
//		  $tpl->assign();
    if (($Inicio + $_SESSION[AlumnosXHoja]) < $_SESSION[TotalAlumnos]) {
        $tpl->assign("ActivoSiguiente", "enabled");
        $tpl->assign("txtTipoSiguiente", "button");
        $tpl->assign("txtTipoListar", "hidden");
    } else {
        $tpl->assign("ActivoSiguiente", "disabled");
        $tpl->assign("txtTipoSiguiente", "hidden");
        $tpl->assign("txtTipoListar", "button");


    }
    $tpl->assign("ActivoAnterior", "enabled");
    if ($Inicio == 0) {
        $tpl->assign("ActivoAnterior", "disabled");
    }


    $tpl->newblock("validacion");
    $tpl->assign("DatosDesplegados", $Desplegados);
    $tpl->assign("NumeroActividades", $_SESSION[TotalActividades]);

}// ImprimeTablaIngreso


function ActualizaVector($Inicio, $Desplegados)
{
    $RecorreAlumnos = 0;
    //echo "<br> Inicio=$Inicio, Desplegados=$Desplegados.";
    while ($RecorreAlumnos < $Desplegados) {
        $RecorreActividades = 0;
        $Posicion = $_POST[PosicionVector][$RecorreAlumnos];
        //echo "<br> Posicion== $Posicion";
        while ($RecorreActividades < $_SESSION[TotalActividades]) {
            //echo " Act$RecorreActividades=".$_SESSION[VecNotasActividades][$Posicion][$RecorreActividades];

            $numero = $_SESSION[VecPosicionActividades][$RecorreActividades];
            $NombreVariableHtml = "" . "ValorActividad" . $numero;
            $_SESSION[VecNotasActividades][$Posicion][$RecorreActividades] = $_POST[$NombreVariableHtml][$RecorreAlumnos];
            //echo " ".$_SESSION[VecNotasActividades][$Posicion][$RecorreActividades].",";
            $RecorreActividades++;
        }
        $RecorreAlumnos++;
    }
} // fin de la funcion ActualizaVector


//Finaliza Sección de Implementación de Funciones

$_verificarSesion = true;
session_start();
require "conectar.php";

/* AGREGAR CAMBIOS*/
$_SESSION[AlumnosXHoja] = 15;
$_SESSION[DiasDespues] = 20;
/* AGREGAR CAMBIOS*/


global $tpl;
$tpl = new TemplatePower("cargaactividad.tpl");
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$txtCurso = $_SESSION['curso'];
$txtSeccion = $_SESSION['seccion'];
$txtCarrera = $_SESSION['carrera'];
$txtIndex = $_SESSION['index'];
$txtRegPer = $_SESSION['regper'];
$TopeLaboratorio = $_SESSION['TopeLaboratorio'];
$txtPeriodo = $_SESSION['sPeriodo'];
$txtAnio = $_SESSION['sAnio'];


$txtSeccion = str_replace("*", "+", "$txtSeccion");
$opcion = $_REQUEST['opcion'];
switch ($opcion) {
    case 0:// cargaactividad a memoria

        $tpl->assign("AtxtCurso",$txtCurso);
        $tpl->assign("AtxtIndex",$txtIndex);
        $tpl->assign("AtxtCarrera",$txtCarrera);
        $tpl->assign("AtxtSeccion",$txtSeccion);

        $sqlfechaXperiodo = $gsql_na_caa->_select1($txtAnio, $txtPeriodo,$txtCurso,$txtCarrera);

        $bd->query($sqlfechaXperiodo);
        if ($bd->num_rows() > 0) {
            $bd->next_record();
            $FilaCalendario = $bd->r();
            $FinalPrimerParcial = $FilaCalendario["finalprimerparcial"];
            $FinalSegundoParcial = $FilaCalendario["finalsegundoparcial"];
            $FinalTercerParcial = $FilaCalendario["finaltercerparcial"];
            $InicioPeriodo = $FilaCalendario["inicioperiodo"];
            $FinalPeriodo = $FilaCalendario["finalperiodo"];
            $_SESSION[ActivoPrimerParcial] = $FilaCalendario["activoprimerparcial"];
            $_SESSION[ActivoSegundoParcial] = $FilaCalendario["activosegundoparcial"];
            $_SESSION[ActivoTercerParcial] = $FilaCalendario["activotercerparcial"];
        } else {
            $InicioPeriodo = -1;
            $FinalPeriodo = -1;
            $FinalPrimerParcial = -1;
            $FinalSegundoParcial = -1;
            $FinalTercerParcial = -1;
        }


//       $filtropersonal=" and regper='$txtRegPer' "; 

        $filtropersonal = $gsql_na_caa->_select2_1($txtRegPer);

        $SqlActividadesCurso = $gsql_na_caa->_select2($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $filtropersonal);


        //echo "<br> sqllistaactividadescurso= $SqlActividadesCurso";

        //$ResultadoListaActividades= mysql_query($SqlActividadesCurso);
        $bd->query($SqlActividadesCurso);
        $TotalActividades = $bd->num_rows();
        // echo "<br> $TotalActividades";
        if ($TotalActividades > 0) {
//                 echo "<br> ENTROOOOOO";
//                 $tpl->newblock("tablalistado");
//                 $tpl->newblock("listaactividad");
            $IndiceActividad = 0;
            unset($_SESSION[VecTipoActividades]);
            unset($_SESSION[VecFechaActividades]);
            unset($_SESSION[VecDocenciaActividades]);

            unset($_SESSION[VecNombreActividades]);
            unset($_SESSION[VecPonderacionActividades]);
            unset($_SESSION[VecPosicionActividades]);
            unset($_SESSION[VecPermiteIngresoActividades]);
            unset($_SESSION[MayorPonderecionParciales]);
            $_SESSION[MayorPonderacionParciales] = 0;
            $FechaHoy = date("d/m/Y");
            while (($bd->next_record()) != null) {
                $FilaDatoListaActividades = $bd->r();
                if ($FilaDatoListaActividades["nombre"] != "") {
                    $NombreActividad = $FilaDatoListaActividades["nombre"];
                } else {
                    $NombreActividad = $FilaDatoListaActividades["nombretipoactividad"];
                }

                $_SESSION[VecTipoActividades][$IndiceActividad] = $FilaDatoListaActividades["nombretipoactividad"];
                $_SESSION[VecFechaActividades][$IndiceActividad] = ImprimeFecha($FilaDatoListaActividades["fecharealizar"]);
                $_SESSION[VecDocenciaActividades][$IndiceActividad] = ((int)$FilaDatoListaActividades["pertenecea"]==1) ? 'TEORIA' : 'PRACTICA';

                $_SESSION[VecNombreActividades][$IndiceActividad] = $NombreActividad;
                $_SESSION[VecPonderacionActividades][$IndiceActividad] = $FilaDatoListaActividades["ponderacion"];
                $_SESSION[VecPosicionActividades][$IndiceActividad] = $FilaDatoListaActividades["posicion"];

                switch ($FilaDatoListaActividades["tipoactividad"]) {
                    case 1: // primer parcial
                        if ($FilaDatoListaActividades["ponderacion"] > $_SESSION[MayorPonderacionParciales]) {
                            $_SESSION[MayorPonderacionParciales] = $FilaDatoListaActividades["ponderacion"];
                        }

                        if ($FinalPrimerParcial != -1) {
                            if (($FinalPrimerParcial + $_SESSION[DiasDespues] <= $fecha) || ($_SESSION[ActivoPrimerParcial])) $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                            else  $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 0;
                        } // de FinalPrimerParcial=-1
                        else {
                            $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                        }

                        break;
                    case 2: // segundo parcial
                        if ($FilaDatoListaActividades["ponderacion"] > $_SESSION[MayorPonderacionParciales]) {
                            $_SESSION[MayorPonderacionParciales] = $FilaDatoListaActividades["ponderacion"];
                        }

                        if ($FinalSegundoParcial != -1) {
                            if (($FinalSegundoParcial + $_SESSION[DiasDespues] <= $fecha) || ($_SESSION[ActivoSegundoParcial])) $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                            else  $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 0;
                        } // de FinalPrimerParcial=-1
                        else {
                            $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                        }
                        break;
                    case 3: // tercer parcial
                        if ($FilaDatoListaActividades["ponderacion"] > $_SESSION[MayorPonderacionParciales]) {
                            $_SESSION[MayorPonderacionParciales] = $FilaDatoListaActividades["ponderacion"];
                        }

                        if ($FinalTercerParcial != -1) {
                            if (($FinalTercerParcial + $_SESSION[DiasDespues] <= $fecha) || ($_SESSION[ActivoTercerParcial])) $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                            else  $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 0;
                        } // de FinalPrimerParcial=-1
                        else {
                            $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                        }
                        break;
                    case 4:
                        if ($FilaDatoListaActividades["ponderacion"] > $_SESSION[MayorPonderacionParciales]) {
                            $_SESSION[MayorPonderacionParciales] = $FilaDatoListaActividades["ponderacion"];
                        }
                        $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                        break;
                    case 5:
                        $_SESSION[VecPonderacionActividades][$IndiceActividad] = $_SESSION[MayorPonderacionParciales];
                        $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;

                        break;

                    default:
                        $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 1;
                        if ($FilaDatoListaActividades["superactividad"] == 1) {
                            $_SESSION[VecPermiteIngresoActividades][$IndiceActividad] = 0;
                        }

                } // del case 1 del switch. . . . .tipoactividad


                $IndiceActividad++;
            }// del while filadatolistaactividades
            $_SESSION[TotalActividades] = $TotalActividades;
            LevantaNotasActividades($txtCurso,$txtCarrera /*$txtSeccion*/, $txtPeriodo, $txtAnio);

            /**/
//               ImprimeEncabezadoActvidades();
//               ImprimeListadoEstudiante(0);
            ImprimeTablaIngreso(0, $txtCurso,$txtCarrera /*$txtSeccion*/, $txtPeriodo, $txtAnio);

        }// del if resultadolistaactividades


        break;

    case 1: // Graba la informacion
    case 4:

    $Inicio = $_GET['Inicio'];
    $Desplegados = $_POST['Desplegados'];
    $txtCurso = $_POST['txtCurso'];
    $txtSeccion = $_POST['txtSeccion'];
    $txtPeriodo = $_POST['txtPeriodo'];
    $txtAnio = $_POST['txtAnio'];
    $txtCarrera = $_POST['txtCarrera'];

    if($opcion==1) {
        //echo $opcion.' == '.$txtCurso . ' - ' . $txtCarrera . ' - ' . $txtPeriodo. ' - ' .$txtAnio.'<br>';
    } else {
        //echo $txtCurso . ' - ' . $txtCarrera . ' - ' . $txtPeriodo. ' - ' .$txtAnio.'<br>';die;
    }
        // ajusta valores ingresados al vector de sesiones. . . . . .

        // listado de los alumnos directos de las variables
//                  echo "<br> ******* INICIO DE VARIABLES DIRECTAS";
        $RecorreAlumnos = 0;
        $SqlUpdateNotas = "";
        ActualizaVector($Inicio, $Desplegados);
        $ERROR = 0;
        $nombrearchivo = "Semaforos/" . $txtCurso . "-" . $txtPeriodo . "-" . $txtAnio;

        if ($PtrArchivo = fopen($nombrearchivo, "a")) //PtrArchivo== handler
        {
            while (!flock($PtrArchivo, LOCK_EX)) // bloqueamos $file
            {
                //aqui sigue en el while hasta que logre la exclusividad en el archivo
            }
//							 $bd->query("begin");

            $bd->query($gsql_na_caa->begin());

            while ($RecorreAlumnos < $_SESSION[TotalAlumnos]) {

                $RecorreActividades = 0;
                $CompletaUpdaNotas = "";
                while ($RecorreActividades < $_SESSION[TotalActividades]) {
                    $numero = $_SESSION[VecPosicionActividades][$RecorreActividades];
                    $NombreVariableHtml = "" . "ValorActividad" . $numero;
                    //echo " <br>NOMBREVAR== ***".$NombreVariableHtml;
                    //                       echo "*".$_POST[$NombreVariableHtml][$RecorreAlumnos];
                    //                     $_SESSION[VecNotasActividades][$RecorreAlumnos][$RecorreActividades]=$_POST[$NombreVariableHtml][$RecorreAlumnos];
                    //					   echo "<br>el actualiza== "."Act".$numero."='".$_POST[$NombreVariableHtml][$RecorreAlumnos]."'";
                    $nota = $_SESSION[VecNotasActividades][$RecorreAlumnos][$RecorreActividades];
                    if ($nota == "") {
                        $nota = 0;
                    }

                    //ORIGINAL                       $CompletaUpdaNotas=$CompletaUpdaNotas. ", Act".$numero."='".$nota."'";
//									$CompletaUpdaNotas=$CompletaUpdaNotas. ", actividades[".$numero."]=".$nota;

                    $CompletaUpdaNotas = $gsql_na_caa->_update1_1($CompletaUpdaNotas, $numero, $nota);

                    //                       echo " ALumno= $RecorreAlumnos,$RecorreActividades =".$_SESSION[VecNotasActividades][$RecorreAlumnos][$RecorreActividades];
                    $RecorreActividades++;
                } // del while recorreactividades
                // echo "<br>";
//								  $SqlUpdateNotas=" update ing_notasactividad set
//													activo=1
//													$CompletaUpdaNotas
//												   where Curso='$txtCurso'
//												   and Seccion='$txtSeccion'
//												   and Periodo='$txtPeriodo'
//												   and Anio=$txtAnio
//												   and Carnet='".$_SESSION[VecCarnetAlumno][$RecorreAlumnos]."';";

                $SqlUpdateNotas = $gsql_na_caa->_update1($CompletaUpdaNotas, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $_SESSION[VecCarnetAlumno][$RecorreAlumnos]);

                //echo "<br> el sql para el update=== ".$SqlUpdateNotas;

                //$ResultadoActualiza= mysql_query($SqlUpdateNotas);
                if ($bd->query($SqlUpdateNotas) == 0) {
                    $ERROR = 1;
                    break;
                };

                $RecorreAlumnos++;
            }// del while recorrealumnos
            if ($ERROR) {
//					   $bd->query("roolback");

                $bd->query($gsql_na_caa->rollback());

                //roolback
            } else {
//					$bd->query("commit");

                $bd->query($gsql_na_caa->commit());

                // commit
            }
//					$bd->query("end");

            $bd->query($gsql_na_caa->end());


            flock($PtrArchivo, 3); // terminada la escritura, quitamos el candado
            fclose($PtrArchivo);
        } // fin del open archivo. . . . .
        //grabarVector
        if ($opcion == 1) {

            header("location: ../notas-actividades/cargaactividad.php?opcion=3&Inicio=$Inicio&Desplegados=$Desplegados");
        } else
            if ($opcion == 4) {
                $txtLaSeccion = str_replace("+", "*", "$txtSeccion");
                header("location: ../notas-actividades/listado.php?opcion=1&txtCurso=$txtCurso&txtLaSeccion=$txtLaSeccion&txtAnio=$txtAnio&txtPeriodo=$txtPeriodo&txtRegPer='$txtRegper'&txtCarrera=$txtCarrera");
            }

        break;

    case 3: //  inicio de la opcion que despliega otro bloque de alumnos.
        $Inicio = $_GET['Inicio'];
        $Desplegados = $_POST['Desplegados'];
        if(isset($_POST['txtCurso'])) {
            $txtCurso = $_POST['txtCurso'];
            $txtSeccion = $_POST['txtSeccion'];
            $txtPeriodo = $_POST['txtPeriodo'];
            $txtAnio = $_POST['txtAnio'];
            $txtCarrera = $_POST['txtCarrera'];
        }

        $tpl->assign("AtxtCurso",$txtCurso);
        $tpl->assign("AtxtIndex",$txtIndex);
        $tpl->assign("AtxtCarrera",$txtCarrera);
        $tpl->assign("AtxtSeccion",$txtSeccion);

        if ($Inicio < 0) {
            $Inicio = 0;
        }
        if ($Inicio <= $_SESSION[TotalAlumnos]) {
            ActualizaVector($Inicio, $Desplegados);
            ImprimeTablaIngreso($Inicio, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio);
        }

        break; // de la opcion 3  que despliega otro bloque de alumnos


} // del switch


$tpl->printToScreen();
unset($tpl,$obj_cad);
?>
