<?php
// *************************
//    Patron de pagina
// ***********************

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/creaactividad_SQL.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");

global $gsql_na_cra;
$gsql_na_cra = new creaactividad_SQL();

//Inicia Sección de Implementación de Funciones

//Definición de variables globales
global $tpl;
global $txtCurso;
global $txtSeccion;
global $txtPeriodo;
global $txtAnio;
global $HabilitadoClaseMagistral;
global $HabilitadoLaboratorio;
global $HabilitadoTrabajoDirigido;
global $HabilitadoDibujo;
global $HabilitadoPractica;
global $HabilitadoPracticaTipoLaboratorio;
global $NoParciales;

function nombrePeriodo($periodo)
{
    $texto = "";
    switch ($periodo) {
        case(PRIMER_SEMESTRE) :
            $texto = " PRIMER SEMESTRE ";
            break;
        case(VACACIONES_DEL_PRIMER_SEMESTRE) :
            $texto = " CURSO DE VACACIONES JUNIO";
            break;
        case(PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE) :
            $texto = " PRIMERA RETRASADA PRIMER SEMESTRE ";
            break;
        case(SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE) :
            $texto = " SEGUNDA RETRASADA PRIMER SEMESTRE ";
            break;
        case(SEGUNDO_SEMESTRE) :
            $texto = " SEGUNDO SEMESTRE ";
            break;
        case(VACACIONES_DEL_SEGUNDO_SEMESTRE) :
            $texto = " CURSO DE VACACIONES DICIEMBRE";
            break;
        case(PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE) :
            $texto = " PRIMERA RETRASADA SEGUNDO SEMESTRE ";
            break;
        case(SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE) :
            $texto = " SEGUNDA RETRASADA SEGUNDO SEMESTRE ";
            break;
    }
    return $texto;
}

function DevuelveNombreCorto($bd, $periodo, $curso, $index)
{
    global $gsql_na_cra;
//   $SqlNombre=" select nombrecorto from curso where curso ='".$_SESSION['curso']."'";   
    //$SqlNombre = $gsql_na_cra->queryNombreCurso($curso, $index);
    $SqlNombre = $gsql_na_cra->DevuelveNombreCorto_select1($curso, $index);
    

    $bd->query($SqlNombre);
    if ($bd->num_rows() > 0) {
        $bd->next_record();
        $FilaDato = $bd->r();
        $_SESSION["nombrecorto"] = $FilaDato[name]." este es el nombre";
    }
    $_SESSION["nombreperiodo"] = nombrePeriodo($periodo);
} // fin funcion devuelve el nombre corto. . . 


// recupera el valor maximo permitido de laboratorio si no se encuentra quiere decir que
// el curso no tiene laboratorio, por lo que el maximo laboratorio es 0
function valorTopeLaboratorio($bd, $curso, $periodo, $anio)
{
    global $gsql_na_cra;
    $topeLab = 0;
//  $BuscaTopeLaboratorio="select * from cursozona where curso='$curso' and periodo='$periodo' and anio=$anio" ;
//  $BuscaTopeLaboratorio="select * from cursozona where curso='" . $curso . "' and (trim(fechafinvigencia)='' or fechafinvigencia is null" .
//                        " or (periodo='" . $periodo . "' and anio='" . $anio . "')) and anio>='2010' order by fechafinvigencia";                        
    $BuscaTopeLaboratorio = $gsql_na_cra->valorTopeLaboratorio_select1($curso, $periodo, $anio);

    $bd->query($BuscaTopeLaboratorio);

    if ($bd->num_rows() > 0) {


        if ($bd->num_rows() == 1) {


            $bd->next_record();
            $FilaDato = $bd->r();
            $topeLab = $FilaDato["laboratorio"];

        } else {
            $sinFinVigencia = 0;
            $conFinVigencia = 0;
            while (($bd->next_record()) != null) {
                $FilaDato = $bd->r();
                if (trim($FilaDato["fechafinvigencia"]) == "")
                    $sinFinVigencia = $FilaDato["laboratorio"];
                else {
                    if (trim($FilaDato["periodo"]) == $periodo && (trim($FilaDato["anio"]) == $anio))
                        $conFinVigencia = $FilaDato["laboratorio"];
                }
            }// del while reocorre tipoactividad
            if ($conFinVigencia != 0)
                $topeLab = $conFinVigencia;
            else
                $topeLab = $sinFinVigencia;
        }
    }


    return $topeLab;
}

function verSeccionClaseMagistral($bd, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtIndex)
{
    global $gsql_na_cra;
//  $SqlSeccionMagistral="  select distinct(tipo) from horariodetalle where curso='$txtCurso' and seccion='$txtSeccion'
//					    and periodo='$txtPeriodo' and anio=$txtAnio and tipo=1";

    $SqlSeccionMagistral  = $gsql_na_cra->esCursoModular($txtIndex,$txtCurso,$txtCarrera);
    if($bd->query($SqlSeccionMagistral) AND $bd->num_rows() > 0) {
        $bd->next_record();
        $SqlSeccionMagistral = $gsql_na_cra->verSeccionClaseMagistral_select1($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtIndex,$bd->r('resultado'));
    }

    $resultado = 0;
    $bd->query($SqlSeccionMagistral);
    if ($bd->num_rows() > 0) {
        $resultado = 1;
    }
    return $resultado;
}

function tipoDeCurso($bd,$txtIndex, $txtCurso, $txtCarrera)
{
    global $gsql_na_cra;
    $resultado = 1;

    $SqlSeccionMagistral  = $gsql_na_cra->esCursoModular($txtIndex,$txtCurso,$txtCarrera);
    if($bd->query($SqlSeccionMagistral) AND $bd->num_rows() > 0) {

        $bd->next_record();
        $Resultado=$bd->r();
        if(($Resultado['resultado'] + 0)==true) {
            $resultado = 2;
        }
    }
    return $resultado;
}


function cambiarFormatoFecha($fecha)
{

    list($anio, $mes, $dia) = explode("/", $fecha);
    return $dia . "-" . $mes . "-" . $anio;
}

function ImprimeFecha($fecha)
{
    list($anio, $mes, $dia) = explode("-", $fecha);
    return $dia . "/" . $mes . "/" . $anio;
}


// inserta los estudiantes asignados al curso a la tablas donde se guardan las notas de actividades
function AgregarFilasAsignacionActividad($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
{
    global $gsql_na_cra;
    global $bd;

//    $SqlInsertaAsignacionEstudiantes= " insert into ing_notasactividad(curso,seccion,periodo,anio,carnet)
//
//                                        select '$txtCurso','$txtSeccion','$txtPeriodo',$txtAnio,usuarioid
//                                        from asignacion  a, asignaciondetalle ad
//					                    where a.transaccion = ad.transaccion
//										  AND a.fechaasignacion = ad.fechaasignacion
//										  AND ad.Curso='$txtCurso'
//					                      AND ad.Seccion='$txtSeccion'
//	                                      AND ad.Periodo='$txtPeriodo'
//					                      AND ad.Anio=$txtAnio
//					                    ORDER BY a.usuarioid
//                                      ";                                      
    $SqlInsertaAsignacionEstudiantes = $gsql_na_cra->AgregarFilasAsignacionActividad_insert1($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio);


//    echo "<br> inserta asignacionestudiantes== $SqlInsertaAsignacionEstudiantes";
    $bd->query($SqlInsertaAsignacionEstudiantes);
}

// funcion que descarga los datos de los parciales a la funcion ValidaFechaParciales qeu se encuentra en el archivo tpl
// esta funcion esta dentro de head ya que es una funcion javascript.
function DescargaDatosParciales()
{
    global $tpl;
    $tpl->newblock("datosparciales");
    $tpl->assign("txtFechaPrimerParcial", $_SESSION[FechaPrimerParcial]);
    $tpl->assign("txtPonderacionPrimerParcial", $_SESSION[PonderacionPrimerParcial]);
    $tpl->assign("txtFechaSegundoParcial", $_SESSION[FechaSegundoParcial]);
    $tpl->assign("txtPonderacionSegundoParcial", $_SESSION[PonderacionSegundoParcial]);
    $tpl->assign("txtFechaTercerParcial", $_SESSION[FechaTercerParcial]);
    $tpl->assign("txtPonderacionTercerParcial", $_SESSION[PonderacionTercerParcial]);
    $tpl->assign("txtFechaParcialReposicion", $_SESSION[FechaParcialReposicion]);
    $tpl->assign("txtSumaParciales", $_SESSION[SumaParciales]);
    $tpl->assign("txtTopeLaboratorio", $_SESSION[TopeLaboratorio]);
    $tpl->assign("txtSumaLaboratorio", $_SESSION[SumaLaboratorio]);
    $tpl->assign("txtFinalPrimerParcial", $_SESSION[FinalPrimerParcial]);
    $tpl->assign("txtFinalSegundoParcial", $_SESSION[FinalSegundoParcial]);
    $tpl->assign("txtFinalTercerParcial", $_SESSION[FinalTercerParcial]);
    $tpl->assign("txtSumaPonderacionActividades", $_SESSION[SumaPonderacionActividades]);
    $tpl->assign("txtInicioPeriodo", $_SESSION[InicioPeriodo]);
    $tpl->assign("txtFinalPeriodo", $_SESSION[FinalPeriodo]);
    $tpl->assign("txtPonderacionMaxima", $_SESSION[PonderacionMaxima]);
    $tpl->assign("txtTipoCurso", $_SESSION[TipoCurso]);

}

// fin de la funcion descargafatosparciales


// Funcion BuscaPosicionActividad esta actividad recorre las actividades asignadas al curso
// buscando una actividad donde activo sea igual a 0 que quiere decir que esta disponible esta posicion
// si encuentra -1 quiere decir que la actividad fue anulada y se puede utilizar esta posicion
// el valor que regresa sirve para mapear la posicion de la actividad dentro del vector que se guarda en la 
// base de datos para despues poder cargar notas a esa posicion.
function BuscaPosicionActividad($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio)
{
    global $gsql_na_cra;
    global $tpl;
    global $bd;
//  $SqlBuscaPosicion=" select idactividad,posicion,activo 
//					  from ing_actividad
//					  where curso='$txtCurso'
//					  and seccion='$txtSeccion'
//					  and periodo='$txtPeriodo'
//					  and anio=$txtAnio
//					  order by posicion
//                    ";                     
    $SqlBuscaPosicion = $gsql_na_cra->BuscaPosicionActividad_select1($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio);

    //echo "<br> sqlPosicion== $SqlBuscaPosicion";

    $bd->query($SqlBuscaPosicion);
    $FilasPosicion = $bd->num_rows();
    if ($FilasPosicion == 0) // indica que no existen las tuplas en la tabla de asignacionactividades
    {
        $RegresaPosicion = 1;
        AgregarFilasAsignacionActividad($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio);
    } else {
        $RecorrePosicion = 0;
        $Salir = 0;
        $UltimaPosicion = 0;
        while (($RecorrePosicion < $FilasPosicion) and ($Salir == 0)) {
            $bd->next_record();
            if (($bd->f('activo') == 0)) {
                $RegresaPosicion = $bd->f('posicion');
                $Salir = 1;
//				$SqlUpdate=" update ing_actividad set activo=-1 where idactividad=".$bd->f('idactividad');

                $SqlUpdate = $gsql_na_cra->BuscaPosicionActividad_update1($bd->f('idactividad'));

                $bd->query($SqlUpdate);
            } else {
                $RecorrePosicion++;
                $UltimaPosicion = $bd->f('posicion');
            }
        }

        if ($Salir == 0) {
            $RegresaPosicion = $UltimaPosicion + 1;
        }
    }
    return ($RegresaPosicion);
}

// FINALIZA la funcion que busca la posicion dentro del vector

//Finaliza Sección de Implementación de Funciones
$_verificarSesion = true;
session_start();
require "conectar.php";


// Creacion visual de la pagina.
$tpl = new TemplatePower("creaactividad.tpl");

// creacion de la estructura de la pagina.
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();
$obj_cad = new ManejoString();

$opcion = $_GET['opcion'];

// variable que guarda el maximo numero de actividades permitidas, si se modifica aumenta o disminuye.
$_SESSION[MaximoActividades] = 100;
unset($_SESSION['zonasActividades']);

//Opcion proveniente de diversos lugares del sitio identifica el lugar especifico de donde provino el clic
switch((int)$opcion){
    case 0:
        // 1. creaactividad.php -> cuando le da clic a crear nueva actividad
        $txtCurso = $_SESSION["curso"];
        $txtIndex = $_SESSION["index"];
        $txtCarrera = $_SESSION["carrera"];
        $txtSeccion = $_SESSION["seccion"];
        break;
    case 1:
        // 1. creaactividad.php -> cuando le da clic a Grabar Actividad
        $txtCurso = $_POST["txtCurso"];
        $txtIndex = $_POST["txtIndex"];
        $txtCarrera = $_POST["txtCarrera"];
        $txtSeccion = $_POST["txtSeccion"];
        break;
    case 3:// 3. cuando quiere editar una actividad especifica
        $txtCurso = $_SESSION["curso"];
        $txtIndex = $_SESSION["index"];
        $txtCarrera = $_SESSION["carrera"];
        $txtSeccion = $_SESSION["seccion"];
        break;
    case 4:
        $txtCurso = $_SESSION["curso"];
        $txtIndex = $_SESSION["index"];
        $txtCarrera = $_SESSION["carrera"];
        $txtSeccion = $_SESSION["seccion"];
        break;
    case 5:
        $txtCurso = $_GET["txtCurso"];
        $txtIndex = $_SESSION["index"];
        $txtCarrera = $_GET["txtCarrera"];
        $txtSeccion = $_GET["txtSeccion"];
        break;
    case 9:
        // 1. D_CourseList.php -> cuando navega por primera ves a esta página
        $txtCurso = $_GET["curso"];
        $txtIndex = $_GET["index"];
        $txtCarrera = $_GET["carrera"];
        $txtSeccion = $_GET["seccion"];
        $txtDocentes = $_GET['docentes'];
        if(isset($_GET['docentes']) and strlen($_GET['docentes'])>0)
            $_SESSION['docentes'] = $_GET['docentes'];
        break;
}

$txtPeriodo = $_SESSION["sPeriodo"];
$txtAnio = $_SESSION["sAnio"];
$txtTieneLaboratorio = $_SESSION['laboratorio'];
$txtRegPer = $_SESSION['regper'];

//Establecer valores en la sesion
$_SESSION["curso"] = $txtCurso;
$_SESSION["index"] = $txtIndex;
$_SESSION["seccion"] = $txtSeccion;
$_SESSION['carrera'] = $txtCarrera;

$despuesopcion = $_GET["despuesopcion"];
$txtPosicion = $_GET['txtPosicion'];

$txtTieneLaboratorio = 2;
$txtLaSeccion = str_replace("+", "*", "$txtSeccion");
$txtSeccion = str_replace("*", "+", "$txtSeccion");

$_resVerificacion = sistemaHabilitado($bd, $txtPeriodo, $txtAnio, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtRegPer);
$_resVerificacion=100;
/*
 * Verificacion de habilitacion del sistema
 * si no se encuentra habilitado despliega una serie de msj.
 */
if ($_resVerificacion != 100) {  //resultado 100 significa que Sistema habilitado y aún en fecha para el ingreso de actividades
    $_colorTxtMsg = "<font color='red' style='font-size: 13px; font-weight: bold;'>";
    switch ($_resVerificacion) {
        case 1 :
            DevuelveNombreCorto($bd, $txtPeriodo, $txtCurso, $txtIndex);
            $mensaje = '* El Sistema de Ingreso de Notas de Actividades no se encuentra habilitado en esta fecha para el curso: <span class="underline_label">'. $txtCurso . ' - ' . $_SESSION["nombrecorto"]. '</span>,<br/>';
            $mensaje = $mensaje . '&nbsp;&nbsp;de la carrera: <span class="underline_label">'. $obj_cad->StringCarrera('0' . $txtCarrera) . '</span> correspondiente a: <span class="underline_label">' . nombrePeriodo($_SESSION['sPeriodo']) . ' </span>  del año <span class="underline_label">' . $_SESSION['sAnio'] . '</span>.<br/><br/>' ;
            $mensaje = $mensaje . '* Si no pudo finalizar el ingreso de Notas de Actividades correspondientes a: <span class="underline_label">' . nombrePeriodo($_SESSION['sPeriodo']) . ' - ' . $_SESSION['sAnio'] . '</span>, para el curso correspondiente. <br/>&nbsp;&nbsp;Debe notificarlo a la Secretaría Académica.';

            $_elMensaje .= '<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i>  FUERA DE FECHA</h4>'.$mensaje.'</div>';
            break;
        case 2 :
            $_SESSION[CursoAprobado] = 1;
            $_SESSION[TopeLaboratorio] = valorTopeLaboratorio($bd, $txtCurso, $txtPeriodo, $txtAnio);
            $_SESSION[ExisteSeccionMagistral] = verSeccionClaseMagistral($bd, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtIndex);
            DevuelveNombreCorto($bd, $txtPeriodo, $txtCurso, $txtIndex);
            $_SESSION[TituloPagina] = $_SESSION["nombrecorto"] . "<br> Sección. $txtSeccion <br>" . $_SESSION["nombreperiodo"] . " $txtAnio";

            $txtSeccion = str_replace("+", "*", "$txtLaSeccion");
            $pagina = "../notas-actividades/listado.php?opcion=1&txtCurso=$txtCurso&txtLaSeccion=$txtSeccion&txtAnio=" .
                "$txtAnio&txtPeriodo=$txtPeriodo&txtRegPer=$txtRegPer&txtCarrera=$txtCarrera";
            //print $pagina; die;
            header('location: ' . $pagina);
            die;
            break;
        case 3 :
            DevuelveNombreCorto($bd, $txtPeriodo, $txtCurso, $txtIndex);
            $mensaje = '* No se encontraron notas de actividades procesadas para el curso: <span class="underline_label">'. $txtCurso . ' - ' . $_SESSION["nombrecorto"] . '</span>,<br/>';
            $mensaje = $mensaje . '&nbsp;&nbsp;de la carrera: <span class="underline_label">'. $obj_cad->StringCarrera('0' . $txtCarrera) . '</span> para el: <span class="underline_label">' . nombrePeriodo($_SESSION['sPeriodo']) . ' </span> del año: <span class="underline_label">' . $_SESSION['sAnio'] . '</span>.<br/><br/>' ;
            $mensaje = $mensaje . '* Si no pudo finalizar el ingreso de Notas de Actividades correspondientes a <span class="underline_label">' . nombrePeriodo($_SESSION['sPeriodo']) . ' - ' . $_SESSION['sAnio'] . '</span>, para el curso correspondiente.<br/>&nbsp;&nbsp;Debe notificarlo a la Secretaría Académica.';

            $_elMensaje .= '<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i>  SIN INFORMACIÓN DE NOTAS PROCESADAS</h4>'.$mensaje.'</div>';
            break;
    }
    $tpl->newblock("mensaje");
    $tpl->assign("mensaje", $_elMensaje);
    $tpl->printToScreen();
    unset($tpl);
    die;
}


$TopeLaboratorio = 0;
$TopeLaboratorio = valorTopeLaboratorio($bd, $txtCurso, $txtPeriodo, $txtAnio); // No se utiliza

$HabilitadoClaseMagistral = "disabled";
$HabilitadoLaboratorio = "disabled";
$HabilitadoTrabajoDirigido = "disabled";
$HabilitadoDibujo = "disabled";
$HabilitadoPractica = "disabled";
$HabilitadoLaboratorioTipoPractica = "disabled";
$NoParciales = " and (idtipoactividad>5) ";
//$NoParciales="  ";

/*
// realiza la verificacion si el catedratico que ingreso tiene permitido el ingreso de clase magistral,
// laboratorio y otros, por default las opciones estan desabilitadas y se habilitan solo las que tenga
// tenga asignado el catedratico
*/
$_SESSION[ExisteSeccionMagistral] = verSeccionClaseMagistral($bd, $txtCurso,$txtCarrera /*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtIndex);
$sqlTipoSeccion = $gsql_na_cra->_select2($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtRegPer, $txtIndex);

$_SESSION[HabilitaClaseMagistral] = 0;
$_SESSION[HabilitaLaboratorio] = 0;
$_SESSION[HabilitaTrabajoDirigido] = 0;
$_SESSION[HabilitaDibujo] = 0;
$_SESSION[HabilitaPractica] = 0;
$_SESSION[HabilitaLaboratorioTipoPractica] = 0;
$_SESSION[VectorPerteneceA] = " (";
$ContadorTipoSeccion = 0;
$bd->query($sqlTipoSeccion);
//  echo "$ContadorTipoSeccion";

while (($bd->next_record()) != null) {
    $FilaDatoSeccion = $bd->r();
    if ($ContadorTipoSeccion > 0) {
        $_SESSION[VectorPerteneceA] = $_SESSION[VectorPerteneceA] . ",'" . $FilaDatoSeccion["idscheduletype"] . "'";
    } else {
        //Edwin Saban. Se agrego que busque actividades de lab
        //Original: $_SESSION[VectorPerteneceA] = $_SESSION[VectorPerteneceA] . "'" . $FilaDatoSeccion["idscheduletype"] . "'";
        $_SESSION[VectorPerteneceA] = $_SESSION[VectorPerteneceA] . "'" . $FilaDatoSeccion["idscheduletype"] . "','2'";
    }

    switch ($FilaDatoSeccion["idscheduletype"]) {
        case 0:
        case 1:
            $_SESSION[HabilitaClaseMagistral] = 1;
            $HabilitadoClaseMagistral = "checked";
            $NoParciales = " ";
            $ContadorTipoSeccion++;
            //Para efectos de que puedan clasificar las actividades en practica y teoria se comentarizó
            // el break. Y al estar el horario del curso se habilitan ambos.
            //break;
        case 2:
            $_SESSION[HabilitaLaboratorio] = 1;
            $HabilitadoLaboratorio = "checked";
            // Se comentarizo esta línea debido a que solo se buscan las tuplas de clase magistral
            //$ContadorTipoSeccion++;
            if ($TopeLaboratorio == 0)
                $TopeLaboratorio = 25;
            break;
    }
}// del while que ajusta los  tipo seccion

if ($ContadorTipoSeccion > 0) {
    $_SESSION[VectorPerteneceA] = " and a.pertenecea in " . $_SESSION[VectorPerteneceA] . ") ";
} else {
    $_SESSION[VectorPerteneceA] = " ";
}// finaliza la habilitacion de las areas (clase magistral, laboratorio, etc) que tiene permitido el catedratico

// Levantado del formulario correspondiente a la opción seleccionada
switch ($opcion) {
    case 0:// ingresa nueva actividad
        $DespuesIrA = $_GET['DespuesIrA'];
        $RecorreActividades = 0;

        // descarga el vector de actividades ya ingresadas para validar si ya existe una actividad en la fecha
        // de la nueva actividad
        $tpl->newblock("iniciovectoractividades");
        $tpl->assign("totalactividades", $_SESSION[LevantoActividades]);
        while ($RecorreActividades < $_SESSION[LevantoActividades]) {
            $tpl->newblock("valorvector");
            $tpl->assign("posicion", $RecorreActividades);
            $tpl->assign("datoNombre", $_SESSION[NombreActividades][$RecorreActividades]);
            $tpl->assign("datoFecha", ImprimeFecha($_SESSION[Fecha][$RecorreActividades]));
            $tpl->assign("datoPerteneceA", $_SESSION[PerteneceA][$RecorreActividades]);
            $RecorreActividades++;
        }
        // fin de la descarga de las actividades ya grabadas.

        // crea el formulario para crea una nueva actividad
        // validando que el catedratico pueda  ingresar clase magistral, laboratorio, etc.
        $tpl->newblock("creaactividad");
        $tpl->assign("Opcion", 1);
        $tpl->assign("DespuesIrA", $DespuesIrA);
        $tpl->assign("NombreCuadro", "CREAR NUEVA ACTIVIDAD");
        $tpl->assign("vCurso", $txtCurso);
        $tpl->assign("vNombre", $_SESSION["nombrecorto"]);
        $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $txtCarrera));
        $tpl->assign("vPeriodo", $_SESSION["nombreperiodo"]);
        $tpl->assign("vAnio", $txtAnio);

        $tpl->assign("txtCurso", $txtCurso);
        $tpl->assign("txtIndex", $txtIndex);
        $tpl->assign("txtSeccion", $txtSeccion);
        $tpl->assign("txtCarrera", $txtCarrera);
        $tpl->assign("txtPeriodo", $txtPeriodo);
        $tpl->assign("txtAnio", $txtAnio);
        $tpl->assign("txtRegPer", $txtRegPer);
        $tpl->assign("txtEstadoActividad", 0);

        $tpl->assign("HabilitadoClaseMagistral", $HabilitadoClaseMagistral);
        $tpl->assign("HabilitadoLaboratorio", $HabilitadoLaboratorio);
        $tpl->assign("HabilitadoTrabajoDirigido", $HabilitadoTrabajoDirigido);
        $tpl->assign("HabilitadoDibujo", $HabilitadoDibujo);
        $tpl->assign("HabilitadoPractica", $HabilitadoPractica);
        $tpl->assign("HabilitadoLaboratorioTipoPractica", $HabilitadoLaboratorioTipoPractica);

        $tpl->newblock("tipoactividad");
        $sqlTipoActividad = $gsql_na_cra->_select3($NoParciales);
//			echo "<br>SQL== $sqlTipoActividad";

        $bd->query($sqlTipoActividad);
        $FilasTipoActividad = $bd->num_rows();
        $tpl->newblock("opciontipoactividad");
        $tpl->assign("valoropciontipoactividad", "0");
        $tpl->assign("nombreopciontipoactividad", "Seleccione . . . ");

        while (($bd->next_record()) != null) {
            $FilaTipoActividad = $bd->r();
            $tpl->newblock("opciontipoactividad");
            $tpl->assign("valoropciontipoactividad", $FilaTipoActividad[idtipoactividad]);
            $tpl->assign("nombreopciontipoactividad", $FilaTipoActividad[nombre]);

        }// del while reocorre tipoactividad

        $tpl->gotoBlock('_ROOT');
        $tpl->assign("RegresarActividades", '<a href="creaactividad.php?opcion=9&curso='.$txtCurso.'&index='.$txtIndex.'&carrera='.$txtCarrera.'&seccion='.$txtSeccion.'"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de actividades" ></a>');
        // fin del codigo que levanta el formulario para el ingreso de una nueva actividad
        $_SESSION[PonderacionMaxima] = -1;
        DescargaDatosParciales();
        break;

    case 1: // grabar la nueva actividad
        // en esta opcion se graba en base de datos la nueva actividad ingresada
        $txtNombreActividad = strtoupper($_POST['txtNombreActividad']);
        $txtFechaRealizar = $_POST['txtFechaRealizar'];
        $txtPonderacion = $_POST['txtPonderacion'];
        $txtPerteneceA = $_POST['txtPerteneceA'];
        $txtTipoActividad = $_POST['txtTipoActividad'];
        $txtArchivoEnunciado = $_POST['txtArchivoEnunciado'];

        $txtDocentes = $_SESSION['docentes'];
        //echo $txtDocentes .'...    <br>';

        if(strlen(trim($txtDocentes)) > 0) {
            $txtDocentes = $txtRegPer . ',' . $txtDocentes;
        } else{
            $txtDocentes = $txtRegPer;
        }

        //echo $txtDocentes.'<br>';

        $bd->query($gsql_na_cra->begin()); //" begin "); // inicia la transaccion . . .  .

        // inicializando variables para manejar errores
        $Error1 = 0;
        $Error2 = 0;
        $txtFechaRealizar = cambiarFormatoFecha($txtFechaRealizar);
        $Posicion = BuscaPosicionActividad($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio);
        if ($txtTipoActividad > 0 and $txtTipoActividad < 5) {
            $txtPerteneceA = 1;
        }

        $SqlInserta = $gsql_na_cra->_insert1($txtNombreActividad, $txtFechaRealizar, $txtPonderacion,
            $txtPerteneceA, $txtDocentes, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo,
            $txtAnio, $txtArchivoEnunciado, $txtTipoActividad, $Posicion,$txtRegPer);

        //echo "<br> SQLINSERTA= $SqlInserta" ; die;

        //$ResultadoInserta= mysql_query($SqlInserta);
        if (!$bd->query($SqlInserta))
            $Error = 1;
        // crea el espacio en la tabla para las notas
        $SqlUpdate = $gsql_na_cra->_update1($Posicion, $txtCarrera/*$txtSeccion*/, $txtCurso, $txtPeriodo, $txtAnio);

        //echo "$SqlUpdate";
        if (!$bd->query($SqlUpdate))
            $Error2 = 1;
        // fin de crea el espacio para la tabla de notas. . ..
//echo "Error1 = " .$Error1 . " Error2 = " .$Error2;
        if (($Error1 + $Error2) == 0) {
            $bd->query($gsql_na_cra->commit()); //"commit"); // si no hubo error
            // echo "COMMIT"; die;
        } else {
            //echo "rollback"; die;
            $bd->query($gsql_na_cra->rollback()); //"rollback");  // si hubo error
        }

        $bd->query($gsql_na_cra->end()); //" end ");  // finaliza la transaccion

        header('location: ../notas-actividades/creaactividad.php?opcion=9&curso='.$txtCurso.'&index='.$txtIndex.'&carrera='.$txtCarrera.'&seccion='.$txtSeccion);
        break;

    case 3: // OPCION MUESTRA DATOS PARA MODIFICAR UNA ACTIVIDAD
        $txtIdActividad = $_GET['txtIdActividad'];
        $SqlBusca = $gsql_na_cra->_select4($txtIdActividad);
        //echo " sqlbusca== $SqlBusca";

        $bd->query($SqlBusca);
        $FilasLista = $bd->num_rows();

//     busca la actividad a modificar
        if ($FilasLista == 1) {
            $tpl->newblock("creaactividad");
            $tpl->assign("Opcion", 4);
            $tpl->assign("DespuesIrA", 1);

            $tpl->assign("NombreCuadro", "MODIFICAR ACTIVIDAD");
            $tpl->assign("vCurso", $txtCurso);
            $tpl->assign("vNombre", $_SESSION["nombrecorto"]);
            $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $txtCarrera));
            $tpl->assign("vPeriodo", $_SESSION["nombreperiodo"]);
            $tpl->assign("vAnio", $txtAnio);

            $tpl->assign("txtIdActividad", $txtidActividad);
            $FilaDato = $bd->next_record();
            $FilaDato = $bd->r();
            $tpl->assign("txtIdActividad", $FilaDato[idactividad]);
            $tpl->assign("txtNombreActividad", $FilaDato[nombre]);
            $tpl->assign("txtFechaRealizar", ImprimeFecha($FilaDato[fecharealizar]));
            $tpl->assign("txtPonderacion",$FilaDato[ponderacion] );//number_format($FilaDato[ponderacion]));

            switch ($FilaDato[pertenecea]) {
                case 1:
                    $tpl->assign("SeleccionaRadioClaseMagistral", "checked");
                    break;
                case 2:
                    $tpl->assign("SeleccionaRadioLaboratorio", "checked");
                    break;
                case 3:
                    $tpl->assign("SeleccionaRadioTrabajoDirigido", "checked");
                    break;
                case 4:
                    $tpl->assign("SeleccionaRadioDibujo", "checked");
                    break;
                case 5:
                    $tpl->assign("SeleccionaRadioPractica", "checked");
                    break;

            }// del switch PerteneceA
            $tpl->assign("txtPerteneceA", $FilaDato[pertenecea]);


            $tpl->assign("txtRegPer", $txtRegPer);
            $tpl->assign("txtCurso", $FilaDato[curso]);
            $tpl->assign("txtSeccion", $FilaDato[seccion]);
            $tpl->assign("txtCarrera", $FilaDato[seccion]);
            $tpl->assign("txtPeriodo", $FilaDato[periodo]);
            $tpl->assign("txtArchivoEnunciado", $FilaDato[archivoenunciado]);
            $tpl->assign("txtAnio", $FilaDato[anio]);
            $tpl->assign("txtTipoActividad", $FilaDato[tipoactividad]);
            $tpl->assign("txtTieneLaboratorio", $FilaDato[tienelaboratorio]);
            $tpl->newblock("tipoactividad");
            $Posicion = $FilaDato[posicion];
            $esactividadTipo = $FilaDato[tipoactividad] * 1;

            $sqlTipoActividad = $gsql_na_cra->_select5();

            $bd->query($sqlTipoActividad);
            $FilasTipoActividad = $bd->num_rows();
            $tpl->newblock("opciontipoactividad");
            $tpl->assign("valoropciontipoactividad", "0");
            $tpl->assign("nombreopciontipoactividad", "Seleccione . . . .");

            while (($bd->next_record()) != null) {
                $FilaTipoActividad = $bd->r();
                $tpl->newblock("opciontipoactividad");
                $tpl->assign("valoropciontipoactividad", $FilaTipoActividad[idtipoactividad]);
                $tpl->assign("nombreopciontipoactividad", $FilaTipoActividad[nombre]);
                if ($esactividadTipo == $FilaTipoActividad[idtipoactividad]) {
                    $tpl->assign("txtSeleccionado", "selected");
                }

            }// del while reocorre tipoactividad


//              $txtFechaRealizar=cambiarFormatoFecha($txtFechaRealizar);
        } //
        else {
            $tpl->newblock("mensaje");
            $tpl->assign("mensaje", "En este momento no se puede modificar la actividad . . ");
        }
        //echo '<br><input name="regresar" type="button" value="regresar al inicio" onclick="AlInicio()"/>';
        // RESTAR EL VALOR DE ESTA PONDERACION A LA VARIABLE DE SESSION
        $_SESSION[SumaPonderacionActividades] = $_SESSION[SumaPonderacionActividades] - $FilaDato[ponderacion];

//				$SqlMaximaNota=" select max(actividades[$Posicion]) as maxima from ing_notasactividad
//				                 where curso='$txtCurso'
//								 and seccion='$txtSeccion'
// 								 and periodo='$txtPeriodo'
//								 and anio=$txtAnio
//								  ";

        $SqlMaximaNota = $gsql_na_cra->_select6($Posicion, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio);

        //echo "<br> NotaMaxima==  $SqlMaximaNota"; die;
        $bd->query($SqlMaximaNota);
        $bd->next_record();
        $FilaMaxima = $bd->r();
        $_SESSION[PonderacionMaxima] = $FilaMaxima[maxima];

        $tpl->gotoBlock('_ROOT');
        $tpl->assign("RegresarActividades", '<a href="creaactividad.php?opcion=9&curso='.$txtCurso.'&index='.$txtIndex.'&carrera='.$txtCarrera.'&seccion='.$txtSeccion.'"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de actividades" ></a>');
        DescargaDatosParciales();
        break; // DE LA OPCION MUESTRA DATOS PARA MODIFICAR UNA ACTIVIDAD
    case 4: // opcion para update de la info
        // opcion que actializa la actividad.
        $txtNombreActividad = $_POST['txtNombreActividad'];
        $txtPonderacion = $_POST['txtPonderacion'];
        $txtPerteneceA = $_POST['txtPerteneceA'];
        $txtFechaRealizar= $_POST['txtFechaRealizar'];
        $txtIdActividad= $_POST['txtIdActividad'];
        $txtTipoActividad = $_POST['txtTipoActividad'];

        $txtFechaRealizar = cambiarFormatoFecha($txtFechaRealizar);

        $SqlUpdate = $gsql_na_cra->_update2($txtNombreActividad, $txtPonderacion, $txtPerteneceA, $txtFechaRealizar, $txtIdActividad,$txtTipoActividad);
//echo $SqlUpdate; die;
        $bd->query($SqlUpdate);
        header('location: ../notas-actividades/creaactividad.php?opcion=9&curso='.$txtCurso.'&index='.$txtIndex.'&carrera='.$txtCarrera.'&seccion='.$txtSeccion);
        break;
    case 5: // opcion para inactivo=0;
        // OPCION QUE BORRA UNA ACTIVIDAD, LIMPIA EL VECTOR DE NOTAS, INICIALIZANDOLO A 0
        $txtIdActividad = $_GET['txtIdActividad'];
        $txtPosicion = $_GET['txtPosicion'];

        // inicio transaccion
        $bd->query($gsql_na_cra->begin()); //" begin ");
        // inicializando variables para manejar errores
        $Error1 = 0;
        $Error2 = 0;


//			 $SqlUpdate=" update ing_actividad set activo=0
//						  where IdActividad=$txtIdActividad";

        $SqlUpdate = $gsql_na_cra->_update3($txtIdActividad);

        if (!$bd->query($SqlUpdate)) $Error1 = 1;
        //$ResultadoUpdate= mysql_query($SqlUpdate);
//			 $SqlUpdate=" update ing_notasactividad set actividades[$txtPosicion]=0
//                          where Curso='$txtCurso'
//						  and	Seccion='$txtSeccion'
//						  and	Periodo='$txtPeriodo'
//						  and   Anio=$txtAnio
//						  ";

        $SqlUpdate = $gsql_na_cra->_update4($txtPosicion, $txtCurso, $txtCarrera /*$txtSeccion*/, $txtPeriodo, $txtAnio);

        //echo "$SqlUpdate";
        if (!$bd->query($SqlUpdate)) $Error2 = 1;
        //$ResultadoUpdate= mysql_query($SqlUpdate);
        if ($Error1 + $Error2 == 0)
            $bd->query($gsql_na_cra->commit()); //"commit");
        else
            $bd->query($gsql_na_cra->rollback()); //"rollback");


        $bd->query($gsql_na_cra->end()); //" end ");


        //echo 'Se elimino la actividad <br><input name="regresar" type="button" value="regresar al inicio" onclick="AlInicio()"/>';
        header('location: ../notas-actividades/creaactividad.php?opcion=9&curso='.$txtCurso.'&index='.$txtIndex.'&carrera='.$txtCarrera.'&seccion='.$txtSeccion);

        break;  // de la opcion que elimina una actividad del catalogo de actividades.


    case 9:  // listado de actividades que activas del curso.
        //echo " <BR><center><h1> <br>  A LISTAR ACTIVIDADES . . . . .</h1></center>" ;
        DevuelveNombreCorto($bd, $txtPeriodo, $txtCurso, $txtIndex);

        $bd->query($gsql_na_cra->begin()); //" begin ");
        // inicializando variables para manejar errores
        $Error1 = 0;
        $Error2 = 0;
        $Error3 = 0;
        $Error4 = 0;
        $Error5 = 0;
        $Error6 = 0;

//			  $sqlCursoAprobado=" select i1.* from ing_fechaaprobacionactividad i1, ing_actividad i2" .
//			                    " where i2.curso=i1.curso and i2.seccion=i1.seccion and i2.periodo=i1.periodo and i2.anio=i1.anio" .
//								" and i2.regper=i1.regper and i1.curso= '" . $txtCurso . "' and i1.seccion= '" . $txtSeccion .
//								"' and i1.periodo= '" . $txtPeriodo . "' and i1.anio= '" . $txtAnio . "' and i1.regper='" . $txtRegPer ."'";

        $sqlCursoAprobado = $gsql_na_cra->_select7($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtRegPer);
        if (!$bd->query($sqlCursoAprobado)) $Error1 = 0;
        $_SESSION[CursoAprobado] = 0;
        $FechaAprobado = "";
        if ($bd->num_rows() == 0) {

//				   $sqlCurso=" insert into ing_fechaaprobacionactividad (curso,seccion,periodo,anio,regper)".
//				             " (select distinct curso,seccion,periodo,anio,regper from ing_actividad where curso='" . $txtCurso .
//							 "' and seccion='" . $txtSeccion . "' and periodo='" . $txtPeriodo . "' and anio=" . $txtAnio .
//							 " and regper='" . $txtRegPer . "')";

            $sqlCurso = $gsql_na_cra->_insert2($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtRegPer);

            if (!$bd->query($sqlCurso)) $Error2 = 1;
        } // del num_rows==0
        else {
            $bd->next_record();
            $Fila = $bd->r();
            $FechaAprobado = $Fila[fecha];
            if ($FechaAprobado != "")
                $_SESSION[CursoAprobado] = 1;
        } // del else


//			  $sqlfechaXperiodo=" select * from ing_calendarioactividades where anio=$txtAnio and periodo='$txtPeriodo' ";

        $sqlfechaXperiodo = $gsql_na_cra->_select8($txtAnio, $txtPeriodo,$txtCurso,$txtCarrera);

        if (!$bd->query($sqlfechaXperiodo)) $Error3 = 1;
        if ($bd->num_rows() > 0) {
            $bd->next_record();
            $FilaCalendario = $bd->r();
            $FinalPrimerParcial = "'" . ImprimeFecha($FilaCalendario["finalprimerparcial"]) . "'";
            $FinalSegundoParcial = "'" . ImprimeFecha($FilaCalendario["finalsegundoparcial"]) . "'";
            $FinalTercerParcial = "'" . ImprimeFecha($FilaCalendario["finaltercerparcial"]) . "'";
            $InicioPeriodo = "'" . ImprimeFecha($FilaCalendario["inicioperiodo"]) . "'";
            $FinalPeriodo = "'" . ImprimeFecha($FilaCalendario["finalperiodo"]) . "'";
            $PeriodoHabilitadoParaModificacion = $FilaCalendario["activaractividadesperiodo"];
            $PeriodoHabilitadoParaConsulta = $FilaCalendario["sololistadoaprobado"];
        } else {
            $InicioPeriodo = -1;
            $FinalPeriodo = -1;
            $FinalPrimerParcial = -1;
            $FinalSegundoParcial = -1;
            $FinalTercerParcial = -1;
        }

        $PonderacionClaseMagistral = 0;
        $NumeroActividadesClaseMagistral = 0;
        $PonderacionLaboratorio = 0;
        $NumeroActividadesLaboratorio = 0;
        $Contador = 0;
        $TotalPonderacion = 0;
        $FechaPrimerParcial = -1;
        $FechaSegundoParcial = -1;
        $FechaTercerParcial = -1;
        $PonderacionPrimerParcial = 0;
        $PonderacionSegundoParcial = 0;
        $PonderacionTercerParcial = 0;
        $SumaParciales = 0;
        $SumaLaboratorio = 0;
        $FechaParcialReposicion = -1;
        $TipoCurso = tipoDeCurso($bd,$txtIndex,$txtCurso,$txtCarrera);

        $SqlContarParciales = $gsql_na_cra->_select9($txtCurso,$txtCarrera /*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtRegPer);
//echo $SqlContarParciales;
        if (!$bd->query($SqlContarParciales)) $Error4 = 1;
        $bd->next_record();
        $FilaDato = $bd->r();
        $CuentaParciales = $FilaDato["parciales"];

        $SqlLista = $gsql_na_cra->_select10();
        $SqlLista = $gsql_na_cra->_select_1($SqlLista);
        $SqlLista = $gsql_na_cra->_select_2($SqlLista, $_SESSION[VectorPerteneceA]);
        $SqlLista = $gsql_na_cra->_select_3($SqlLista, $txtCurso);
        $SqlLista = $gsql_na_cra->_select_4($SqlLista, $txtCarrera/*$txtSeccion*/);
        $SqlLista = $gsql_na_cra->_select_5($SqlLista, $txtPeriodo);
        $SqlLista = $gsql_na_cra->_select_6($SqlLista, $txtAnio);
        $SqlLista = $gsql_na_cra->_select_7($SqlLista, $txtRegPer);
        $SqlLista = $gsql_na_cra->_select_8($SqlLista);
        //echo "<br> $SqlLista";die;

        $tpl->newblock("listaactividades");
        $tpl->assign("nombrePeriodo", $_SESSION["nombreperiodo"] . " $txtAnio");
        $tpl->assign("vCurso", $txtCurso);
        $tpl->assign("vNombre", $_SESSION["nombrecorto"]);
        $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $txtCarrera));
        $tpl->assign("vPeriodo", $obj_cad->funTextoPeriodo($txtPeriodo));
        $tpl->assign("vAnio", $txtAnio);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));

        if (!$bd->query($SqlLista)) $Error5 = 1;
        $FilasLista = $bd->num_rows();

        unset($_SESSION[NombreActividades]);
        unset($_SESSION[Fecha]);
        unset($_SESSION[pertenecea]);
        unset($_SESSION[LevantoActividades]);
        unset($_SESSION[FechaPrimerParcial]);
        unset($_SESSION[PonderacionPrimerParcial]);
        unset($_SESSION[FechaSegundoParcial]);
        unset($_SESSION[PonderacionSegundoParcial]);
        unset($_SESSION[FechaTercerParcial]);
        unset($_SESSION[PonderacionTercerParcial]);
        unset($_SESSION[TipoCurso]);

        $_SESSION[LevantoActividades] = 0;
        if ($FilasLista > 0) {
            $tpl->newblock("tablalistaactividades");
            $Contador = 0;
            $PonderacionClaseMagistral = 0;
            $NumeroActividadesClaseMagistral = 0;
            $PonderacionLaboratorio = 0;
            $NumeroActividadesLaboratorio = 0;

            while (($bd->next_record()) != null) {
                $FilaDato = $bd->r();
                $Contador = $Contador + 1;

                $TotalPonderacion = $TotalPonderacion + $FilaDato["ponderacion"];
                $tpl->newblock("filatablalistaactividades");
                $tpl->assign("Contador", $Contador.'.');
                $tpl->assign("FechaRealizar", ImprimeFecha($FilaDato["fecharealizar"]));
                if ($FilaDato["nombre"] <> "")
                    $tpl->assign("Nombre", $FilaDato["nombre"]);
                else
                    $tpl->assign("Nombre", $FilaDato["nombretipoactividad"]);
                $tpl->assign("PerteneceA", $FilaDato["pertenecea"]);
                $tpl->assign("Ponderacion", $FilaDato["ponderacion"]);
                $tpl->assign("txtIdActividad", $FilaDato["idactividad"]);
                $tpl->assign("txtPosicion", $FilaDato["posicion"]);
                $tpl->assign("txtCurso", $txtCurso);
                $tpl->assign("txtSeccion", $txtSeccion);
                $tpl->assign("txtCarrera", $txtCarrera);
                $tpl->assign("txtLaSeccion", $txtLaSeccion);
                $tpl->assign("txtPeriodo", $txtPeriodo);
                $tpl->assign("txtAnio", $txtAnio);
                $tpl->assign("Tipo", $FilaDato["nombretipoactividad"]);
                $tpl->assign("txtTipoActividad", $FilaDato["tipoactividad"]);
                $tpl->assign("PerteneceA", $FilaDato["descripcion"]);
                $tpl->assign("txtEsSuperActividad", $FilaDato["superactividad"]);
                $tpl->assign("BotonBorrar", "button");
                $tpl->assign("BotonMensajeBorrar", "hidden");

                // Agregar || ($PeriodoHabilitadoParaModificacion==0)
                if (($_SESSION[CursoAprobado] == 1)) {
                    $tpl->assign("BotonHabilitado", "disabled");
                }

                if (substr_count($FilaDato["regper"], $txtRegPer)>0) {
                    // mostraropcionesactividades,
                }
//					 $CuentaParciales=2;
                $tpl->assign("botoneditar", "button");
                $tpl->assign("BotonAsignarSsecciones", "hidden");
                $tpl->assign("BotonCargarArchivo", "button");
//					 echo "<br> TIPOACTIVIDD== ".$FilaDato["tipoactividad"];
                switch ($FilaDato["tipoactividad"]) {
                    case 1: { // es primer parcial
                        $FechaPrimerParcial = "'" . ImprimeFecha($FilaDato["fecharealizar"]) . "'";
                        $PonderacionPrimerParcial = $FilaDato["ponderacion"];
                        $SumaParciales = $SumaParciales + $FilaDato["ponderacion"];
                        if ($CuentaParciales > 1) {
                            $tpl->assign("BotonBorrar", "hidden");
                            $tpl->assign("BotonMensajeBorrar", "button");
                            $tpl->assign("txtMensajeBorrar", "alert('debe borrar primero el segundo parcial')");
                        }
                        break;
                    }
                    case 2: { // segundo parcial
                        $FechaSegundoParcial = "'" . ImprimeFecha($FilaDato["fecharealizar"]) . "'";
                        $PonderacionSegundoParcial = $FilaDato["ponderacion"];
                        $SumaParciales = $SumaParciales + $FilaDato["ponderacion"];
                        if ($CuentaParciales > 2) {
                            $tpl->assign("BotonBorrar", "hidden");
                            $tpl->assign("BotonMensajeBorrar", "button");
                            $tpl->assign("txtMensajeBorrar", "alert('debe borrar primero el tercer parcial')");
                        }

                        break;
                    }
                    case 3: { // tercer parcial
                        $FechaTercerParcial = "'" . ImprimeFecha($FilaDato["fecharealizar"]) . "'";
                        $PonderacionTercerParcial = $FilaDato["ponderacion"];
                        $SumaParciales = $SumaParciales + $FilaDato["ponderacion"];
                        if ($CuentaParciales > 3) {
                            $tpl->assign("BotonBorrar", "hidden");
                            $tpl->assign("BotonMensajeBorrar", "button");
                            $tpl->assign("txtMensajeBorrar", "alert('debe borrar primeros los otros parciales ')");
                        }
                        break;
                    }
                    case 4: { // otros parciales
                        $SumaParciales = $SumaParciales + $FilaDato["ponderacion"];
                        $tpl->newblock("botonborraractividad");
                        $tpl->assign("BotonBorrar", "button");
                        $tpl->assign("BotonMensajeBorrar", "button");
                        break;
                    }
                    case 5: { // Parcial de Reposicion
                        $FechaParcialReposcion = "'" . ImprimeFecha($FilaDato["fecharealizar"]) . "'";
                        $tpl->newblock("botonborraractividad");
                        $tpl->assign("BotonBorrar", "button");
                        $tpl->assign("BotonMensajeBorrar", "button");
                        break;
                    }
                }
                if ($FilaDato[superactividad] == 1) {
                    $tpl->assign("BotonBorrar", "hidden");
                    $tpl->assign("BotonMensajeBorrar", "hidden");
                    $tpl->assign("botoneditar", "hidden");
                    $tpl->assign("txtBotones", "Super actividad. . .");
                    if ($FilaDato[asignarsecciones] == 1) {
                        $tpl->assign("BotonAsignarSsecciones", "hidden");
                        $tpl->assign("BotonCargarArchivo", "button");
                    } else {
                        $tpl->assign("BotonAsignarSsecciones", "hidden");
                        $tpl->assign("BotonCargarArchivo", "button");
                    }
                }

                $_SESSION[NombreActividades][$_SESSION[LevantoActividades]] = $FilaDato["nombre"];
                $_SESSION[Fecha][$_SESSION[LevantoActividades]] = $FilaDato["fecharealizar"];
                $_SESSION[PerteneceA][$_SESSION[LevantoActividades]] = $FilaDato["pertenecea"];

                switch ($FilaDato["pertenecea"]) {
                    case 1:
                        // echo "<br> entro al 1";
                        $PonderacionClaseMagistral = $PonderacionClaseMagistral + $FilaDato["ponderacion"];
                        $NumeroActividadesClaseMagistral = $NumeroActividadesClaseMagistral + 1;

                        break;
                    default:
                        // echo "<br> entro al default";
                        $PonderacionLaboratorio = $PonderacionLaboratorio + $FilaDato["ponderacion"];
                        $NumeroActividadesLaboratorio = $NumeroActividadesLaboratorio + 1;
                        break;
                } // del swtich filadato
                $_SESSION[LevantoActividades] = $_SESSION[LevantoActividades] + 1;
            }// del while recorre actividades
        }// del if FilasLista>0

        $tpl->newblock("resumenactividades");
        $tpl->assign("PonderacionClaseMagistral", $PonderacionClaseMagistral);
        $tpl->assign("NumeroActividadesClaseMagistral", $NumeroActividadesClaseMagistral);
        $tpl->assign("PonderacionLaboratorio", $PonderacionLaboratorio);
        $tpl->assign("NumeroActividadesLaboratorio", $NumeroActividadesLaboratorio);
        $tpl->assign("TotalNumero", $Contador);
        $tpl->assign("TotalPonderacion", $TotalPonderacion);

        $tpl->newblock("enlaces");
        if ($_SESSION[CursoAprobado] == 0) {
            $tpl->assign("listarcursoaprobado", "");

            if ($Contador <= $_SESSION[MaximoActividades]) {
                $tpl->assign("nuevaactividad", '<a id="nuevaactividad" href="../notas-actividades/creaactividad.php?opcion=0" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-plus fa-lg"></i><span>&nbsp;&nbsp;Agregar Actividad</span></a>');
            }
            $pagina = "../notas-actividades/listado.php?opcion=1&txtCurso=$txtCurso&txtLaSeccion=$txtSeccion&txtAnio=$txtAnio&txtPeriodo=$txtPeriodo&txtRegPer=$txtRegPer&txtCarrera=$txtCarrera";
            if ($Contador > 0) {
                $tpl->assign("cargarnotasactividad", '<a id="cmanual" href="../notas-actividades/cargaactividad.php?opcion=0" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-hand-o-up fa-lg"></i><span>&nbsp;&nbsp;Cargar Notas Manualmente</span></a>');
                $tpl->assign("listarcursoaprobado", '<a id="lstactividades" href="' . $pagina . '" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-list fa-lg"></i><span>&nbsp;&nbsp;Ver notas de actividades</span></a>');
            }
        } else {
            $txtSeccion = str_replace("+", "*", "$txtLaSeccion");
            $pagina = "../notas-actividades/listado.php?opcion=1&txtCurso=$txtCurso&txtLaSeccion=$txtSeccion&txtAnio=$txtAnio&txtPeriodo=$txtPeriodo&txtRegPer=$txtRegPer&txtCarrera=$txtCarrera";
            $tpl->assign("listarcursoaprobado", '<a id="lstactividades" href="' . $pagina . '" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-list fa-lg"></i><span>&nbsp;&nbsp;Ver notas de actividades</span></a>');
            $tpl->assign("nuevaactividad", "");
            $tpl->assign("cargarnotasactividad", "");

        }// else del if CursoAprobado==0
        $_SESSION[FechaPrimerParcial] = $FechaPrimerParcial;
        $_SESSION[PonderacionPrimerParcial] = $PonderacionPrimerParcial;
        $_SESSION[FechaSegundoParcial] = $FechaSegundoParcial;
        $_SESSION[PonderacionSegundoParcial] = $PonderacionSegundoParcial;
        $_SESSION[FechaTercerParcial] = $FechaTercerParcial;
        $_SESSION[PonderacionTercerParcial] = $PonderacionTercerParcial;
        $_SESSION[FechaParcialReposicion] = $FechaParcialReposicion;
        $_SESSION[SumaLaboratorio] = $PonderacionLaboratorio;
        $_SESSION[SumaParciales] = $SumaParciales;
        $_SESSION[TopeLaboratorio] = $TopeLaboratorio;
        $_SESSION[FinalPrimerParcial] = $FinalPrimerParcial;
        $_SESSION[FinalSegundoParcial] = $FinalSegundoParcial;
        $_SESSION[FinalTercerParcial] = $FinalTercerParcial;
        $_SESSION[SumaPonderacionActividades] = $TotalPonderacion;
        $_SESSION[InicioPeriodo] = $InicioPeriodo;
        $_SESSION[FinalPeriodo] = $FinalPeriodo;
        $_SESSION[PonderacionMaxima] = 0;
        $_SESSION[TipoCurso] = $TipoCurso;

        if (($Error1 + $Error2 + $Error3 + $Error4 + $Error5) == 0)
            $bd->query($gsql_na_cra->commit());//"commit");
        else
            $bd->query($gsql_na_cra->rollback());//"rollback");

        $bd->query($gsql_na_cra->end());//" end ");

        break; // case 9 lista actividades del curso

} // del switch


$tpl->newblock("mensaje");
//$tpl->assign("mensaje","<strong>Artículo 37º.</strong> El número mínimo de exámenes parciales será de dos por ciclo lectivo. (Articulo 37. del normativo de evaluaci&oacute;n de Ingenier&iacute;a)");

$tpl->printToScreen();
unset($tpl,$obj_cad);

?>
