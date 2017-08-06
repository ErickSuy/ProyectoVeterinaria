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
include_once("$dir_portal/fw/model/sql/listado_SQL.php");

require "../notas-actividades/conectar.php"; /* archivo que maneja la conexion y la variable BD*/
session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

//  echo "mostrar listado aprobado"; die;
$opcion;

$opcion = $_GET['opcion']; 
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
    $tpl->assign("Carne", $_SESSION["Aprobado"][$i - 1][0]);
    $tpl->assign("Nombre", trim($_SESSION["Aprobado"][$i - 1][1]));
   // $tpl->assign("Apellido", trim($_SESSION["Aprobado"][$i - 1][2]));
    //$zonasTotales[$i][nombre] = trim($_SESSION["Aprobado"][$i - 1][2]) . ', ' . trim($_SESSION["Aprobado"][$i - 1][1]);
    $zonasTotales[$i][nombre] = trim(trim($_SESSION["Aprobado"][$i - 1][1]));
    $zonasTotales[$i][carnet] = trim( $_SESSION["Aprobado"][$i - 1][0]);
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


//***************************************************************************************  
//                                                                                      *
//          LISTADO DE ZONAS PRELIMINARES                                               *
//          ANTES DE SER APROBADAS, ESTO SE IMPRIMIRA UNICAMENTE SI                     *
//               * paso la fecha para aprobar las notas,                                *
//               * El catedratico encargado del curso no aprobo las notas               *              
//***************************************************************************************

$curso = $_SESSION["sObjNotas"]->mCurso;
$carrera = $_SESSION["sObjNotas"]->mCarrera;
$periodo = $_SESSION["sObjNotas"]->mPeriodo;
$anio = $_SESSION["sObjNotas"]->mAnio;
$index = $_SESSION["sObjNotas"]->mIndex;

global $bd;
global $controladorListadoAct;
global $manejoCadena;
$controladorListadoAct = new listado_SQL();
$manejoCadena= new ManejoString();
//global $bd;


switch ($opcion){
    case 1: //peticion aprobar.
        $txtCurso = $_GET['txtCurso'];
        $txtLaSeccion = $_GET['txtLaSeccion'];
        $txtCarrera = $_GET['txtCarrera'];
        $txtPeriodo = $_GET['txtPeriodo'];
        $txtAnio = $_GET['txtAnio'];
        aprobarNotasCurso($tpl,$txtCurso,$txtCarrera,$txtPeriodo,$txtAnio,getCursoAprobado($curso, $carrera, $periodo, $anio));
        //print_r("estoy aprobando");
        break;
}



if(!getCursoAprobado($curso, $carrera, $periodo, $anio)){
    // necesito mostrar el listado de zona de alumnos SI NO SE APROBO EL CURSO
    mostrarListadoAlumnos($tpl, $index, $curso, $carrera, $periodo, $anio,$objuser->getId());
   
}



function esModular($txtIndex, $txtCurso, $txtCarrera)
{
    // false = Introductorio o basico
    // true  = Modular
    global $controladorListadoAct;
    $resultado = false;

    $bd=conectarBase(); //conectividad temporal, para que no afecte el flujo 
                        //donde se invoca el metodo modular, donde se invoca, se tiene en ejecucion una 
                        //conexion se requiere que esta conexion no se vea afectada
    $SqlSeccionMagistral  = $controladorListadoAct->esCursoModular($txtIndex,$txtCurso,$txtCarrera);
    if($bd->query($SqlSeccionMagistral) AND $bd->num_rows() > 0) {

        $bd->next_record();
        $Resultado=$bd->r();
        if(($Resultado[0] + 0)==true) {
            $resultado = true;
        }
    }
    return $resultado;
}

function getZonaMinima($index,$idCurso,$carrera,$periodo){
    /* Articulo 15 Normativo pormocion FMVZ
     *  Nivel Introductorio:        Treinta y un (31) puntos
        Nivel Básico:        Treinta y un (31) puntos
        Nivel Modular:        Cincuenta (50) puntos
        Escuela de Vacaciones:         Cuarenta (40) puntos
    */
    if($idCurso==id_EPS){
        return ZONAMIN_EPS;
    }else if(esModular($index, $idCurso, $carrera)){
        return ZONAMIN_MODULAR;
    }else if ($periodo==VACACIONES_DEL_PRIMER_SEMESTRE || $periodo== VACACIONES_DEL_SEGUNDO_SEMESTRE){
        return ZONAMIN_VACAS;
    }else{
        return ZONAMIN_INTROBASIC;
    }
}

function getPorcentajeAsisitencia($CantiActividades,$CantidadFaltas){
    //Articulo 30 Normativo promocion FMVZ
    /* "...el estudiante deberá cumplir con el 80 % de las actividades descritas
      en el programa..." */
    
    if($CantidadFaltas==0) return 100;
    $cantidadEntregadas = $CantiActividades-$CantidadFaltas;
    $porcentaje = ($cantidadEntregadas*100)/$CantiActividades;
    
    $porcentaje = round($porcentaje,0);
    return $porcentaje;
  
}

function mostrarListadoAlumnos($tpl,$index,$curso,$carrera,$periodo,$anio,$regPersonal){
    global $controladorListadoAct;
    global $bd;
    global $manejoCadena;
    global $Reposicion;
    
    
    $tpl->newblock("listadoZonas");      
    
    /* boton para aprobar curso*/
    $tpl->assign("txtCurso", $curso);
    $tpl->assign("txtLaSeccion", $carrera);
    $tpl->assign("txtCarrera", $carrera);
    $tpl->assign("txtPeriodo", $periodo);
    $tpl->assign("txtAnio", $anio);
    $tpl->assign("txtRegPer",$regPersonal);
    $tpl->assign("tipoaprobar", "submit");
    
    
    $queryCountActividades=$controladorListadoAct->queryNombreActividad($curso, $periodo, $carrera, $anio);
    $bd->query($queryCountActividades);
    $countActividades = $bd->num_rows();
    
    
    
    
    if($countActividades>0){
        $queryGetNotas = $controladorListadoAct->querygetZonas($curso, $carrera, $periodo, $anio);
        $bd->query($queryGetNotas);
        $x=0;  // para manejar el acceso a las actividades del estudiante
                
        $Contador=0; // controla cuantos estudiantes existen.
        
        $zonaEstudiante=0; //maneja la zona de cada estudiante
        $actividadesFalta=0; // maneja la cantidad de actividades faltantes de cada estudiante
        $zonasTotales = array(); // maneja todas las zonas por estudiante, y las setea a una variable de sesion

        while (($bd->next_record()) != null) {
            $FilaEstudiante = $bd->r();
            
            if ($x == 0) {
                
                $Contador++;
                
                $tpl->newblock("filaestudiante");
                $Link = $FilaEstudiante[rcarnet];
                $tpl->assign("txtContador", $Contador);
                $tpl->assign("txtLinkCarnet", $Link);
                $tpl->assign("txtCarnet", $FilaEstudiante[rcarnet]);
                $tpl->assign("txtNombre", $FilaEstudiante[rname]);                
                
            }

            if(($x+1)>=$countActividades){ //si es el ultimo registro de actividad del estudiante
                
                if($FilaEstudiante[rtipo]!=5){
                    
                    if($FilaEstudiante[rnotaobtenida]>=0){
                        $zonaEstudiante=$zonaEstudiante+$FilaEstudiante[rnotaobtenida];
                    }else{
                        $actividadesFalta++; //SUMAR LAS FALTAS QUE HAYA COMETIDO
                    }
                }else{
                    $Reposicion=true;//controla a nivel global que existe un examen de reposicion$Reposicion                    
                }
                
                
                $ZonaAproxEstudiante= round($zonaEstudiante, 0);
                
                //calcular el porcentaje para ver si tiene o no derecho a examen final
                $derechoAExamen=false;
                if (getPorcentajeAsisitencia($countActividades, $actividadesFalta) >= 80) {
                    if ($ZonaAproxEstudiante >= getZonaMinima($index,$curso,$carrera,$periodo)){
                        // si tiene derecho a examen
                        $derechoAExamen=true;
                    }
                     //Zona Minima insuficiente
                }else {
                    if ($ZonaAproxEstudiante >= getZonaMinima($index,$curso,$carrera,$periodo)){
                        //No tiene asistencia suficiente
                    }else{
                        //Zona minima insuficiente
                    }
                }

                $tpl->newblock("totalmagistral");                
                $tpl->assign("txtTotalZona",$ZonaAproxEstudiante );
                $textObservacionImpresion="SDE"; //gestiona la observacion en el pdf
                if($derechoAExamen){
                    $tpl->assign("color","GREEN");//"rgba(65, 244, 110,.5)");
                    $textObservacionImpresion="DE";//DERECHO A EXAMN
                }
                else
                    $tpl->assign("color","RED");//"rgba(255,0,0,0.5)");
                
                
                //setear los arrays, para luego setear la variable de sesion
                $estudiante = array("carnet"=>$FilaEstudiante[rcarnet], "nombre"=>$FilaEstudiante[rname], "zona"=>$ZonaAproxEstudiante, "obs"=>$textObservacionImpresion);
                array_push($zonasTotales,$estudiante);
                // inicializar variables.
                $zonaEstudiante=0;
                $actividadesFalta=0;
                $x=0;
                
            }else{ // si seguimos iterando el mismo estudiante.
                
                if($FilaEstudiante[rtipo]!=5){                    
                    if($FilaEstudiante[rnotaobtenida]>=0){
                        $zonaEstudiante=$zonaEstudiante+$FilaEstudiante[rnotaobtenida];
                    }else{
                        $actividadesFalta++;
                    }
                }else{
                    $Reposicion=true;//controla a nivel global que existe un examen de reposicion$Reposicion                    
                }
                $x++;
            }
        
        }//fin de recorrer todas las notas
        
        $_SESSION['zonasActividades'] = $zonasTotales;
        
    }else{
        //mensaje de alerta notificando, que no exiten actividades para este curso.
        $tpl->newblock("mensaje");
        $tpl->assign("mensaje", '<div class="alert alert-danger">
        <h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>
        No existen actividades creadas para este curso.
        </div>');
    }
}

function getCursoAprobado($curso, $carrera, $periodo, $anio){
    global $controladorListadoAct;
    global $bd;
    /*observar si este curso ya fueron aprobadas las notas*/
        $queryGetAprobacion = $controladorListadoAct->queryGetAprobacionCurso($curso, $carrera, $periodo, $anio);
        $bd->query($queryGetAprobacion);
        $resultadoQuery=(($bd->next_record()) != null)? $bd->r():null;
        if($resultadoQuery!=null &&  $resultadoQuery[aprobado]==1){ 
            //$_SESSION[CursoAprobado] = 1;            
            return true;;
        }else{
            //$_SESSION[CursoAprobado] = 0;
            return false;
        }
}

function aprobarNotasCurso($tpl,$curso,$carrera,$periodo,$anio, $aprobado){
    global $controladorListadoAct;
    global $bd;
    
    $tpl->gotoBlock("listadoZonas");
    
    if($aprobado){
        $tpl->newblock("mensaje");
        $tpl->assign("mensaje", '<div class="alert alert-success">
            <h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>
            Este es el reporte de notas de actividades ya aprobadas.
            </div>');
            return ;
        }else{
            // APROBAR TODAS LAS NOTAS
            $bd->query($controladorListadoAct->begin());  // INICIAR TRANSACCION
            $ERROR = 0;
            $ManejoErrores="";
            
            $zonasTotales = $_SESSION['zonasActividades'];
            
            for($i=0;$i<sizeof($zonasTotales);$i++){
                
                $row = $zonasTotales[$i];                
                $carnet = $row['carnet'];
                $zona =$row['zona'];
                $queryAprobacion = $controladorListadoAct->queryAprobarCurso($zona,$carnet,$curso, $carrera, $periodo, $anio,$_SESSION['regper']);
                $ERROR+=(!$bd->query($queryAprobacion)) ? 1 :  0;
            }
            
            
            
            if ($ERROR == 0) {
                $bd->query($controladorListadoAct->commit());
                $ManejoErrores = "Aprobacion de notas de curso exitosa";
            }else{
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al aprobar las notas del curso solicite apoyo Unidad Virtual FMVZ.";
                }
            $bd->query($controladorListadoAct->end());
            
            $tpl->newblock("mensaje");
            $tpl->assign("mensaje", '<div class="alert alert-success">'
                    . '<h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>'.$ManejoErrores.'</div>');
        }
    
}


//****************************************FIN LISTADO ZONAS*******************************





$_SESSION['notasFinales'] = serialize($zonasTotales);
$tpl->gotoBlock("_ROOT");

if(strcmp($objuser->getId(),'20111203')==0) {
  $tpl->newBlock('ACTACURSO');
}
$tpl->gotoBlock("_ROOT");
$tpl->assign("Aprobados", $cuenta_aprobados);
$tpl->assign("Perdidos", $cuenta_perdidos);

$tpl->printToScreen();
unset($tpl);
unset($_SESSION["Aprobado"]);

?>