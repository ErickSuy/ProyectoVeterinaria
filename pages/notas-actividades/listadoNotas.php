<?php

// *************************
//    Erick Suy 2016
//    Patron de pagina
// ***********************


include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/listado_SQL.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");


require "conectar.php"; /* archivo que maneja la conexion y la variable BD*/

$_verificarSesion = true;

// Creacion visual de la pagina.
$tpl = new TemplatePower("listadoNotas.tpl");

// creacion de la estructura de la pagina.
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");
$tpl->prepare();

session_start(); // inicializar el contexto para manejo de variables de Sesion.

/*******************************************************************************  VARIABLES GLOBALES */

$opcion; /*Validara que Template cargar, esto lo delimita el usuario dependiendo
          *de donde provengan los click dados en la vista.*/

$_SESSION["idcurso"];
$_SESSION["index"];
$_SESSION["seccion"];
$_SESSION["carrera"];
$_SESSION["docentes"];
$_SESSION["nombreCurso"];

global $controladorListadoActividad;
global $bd;
global $manejoCadena;
global $ManejoErrores;
global $Reposicion;


/*******************************************************************************  Asignacion globales.*/
$opcion = $_GET['opcion']; 
$_SESSION["sPeriodo"]; /* ya asignada*/
$_SESSION["sAnio"]; /* ya asignada*/
$_SESSION['regper'];/* ya asignada*/
$controladorListadoActividad = new listado_SQL();
$manejoCadena= new ManejoString();
$ManejoErrores="";

/*******************************************************************************  DEFINICION FUNCIONES METODOS */
function verificar_sistemaHabilitado($bd,$Periodo,$Anio,$Carrera,$Curso){
    /*Este metodo se encargara de verificar si el sistema es apto para cargar
      aactividades o presentarle al usuario la carga de finales.     */
    return habilitacionSistema($bd,$Periodo,$Anio,$Carrera,$Curso);
    //$_resVerificacion=100;
    
}

function esModular($txtIndex, $txtCurso, $txtCarrera)
{
    // false = Introductorio o basico
    // true  = Modular
    global $controladorListadoActividad;
    $resultado = false;

    $bd=conectarBase(); //conectividad temporal, para que no afecte el flujo 
                        //donde se invoca el metodo modular, donde se invoca, se tiene en ejecucion una 
                        //conexion se requiere que esta conexion no se vea afectada
    $SqlSeccionMagistral  = $controladorListadoActividad->esCursoModular($txtIndex,$txtCurso,$txtCarrera);
    if($bd->query($SqlSeccionMagistral) AND $bd->num_rows() > 0) {

        $bd->next_record();
        $Resultado=$bd->r();
        if(($Resultado[0] + 0)==true) {
            $resultado = true;
        }
    }
    return $resultado;
}


function getZonaMinima(){
    /* Articulo 15 Normativo pormocion FMVZ
     *  Nivel Introductorio:        Treinta y un (31) puntos
        Nivel Básico:        Treinta y un (31) puntos
        Nivel Modular:        Cincuenta (50) puntos
        Escuela de Vacaciones:         Cuarenta (40) puntos
    */
    if($_SESSION["idcurso"]==id_EPS){
        return ZONAMIN_EPS;
    }else if(esModular($_SESSION["index"], $_SESSION["idcurso"], $_SESSION["carrera"])){
        return ZONAMIN_MODULAR;
    }else if ($_SESSION["sPeriodo"]==VACACIONES_DEL_PRIMER_SEMESTRE || $_SESSION["sPeriodo"]== VACACIONES_DEL_SEGUNDO_SEMESTRE){
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



function mostrarListado($tpl,$curso,$carrera,$periodo,$anio){
    global $controladorListadoActividad;
    global $bd;
    global $manejoCadena;
    global $Reposicion;
    
    $tpl->assign("AtxtCurso", $curso);
    $tpl->assign("AtxtIndex", $_SESSION['index']);
    $tpl->assign("AtxtCarrera", $carrera);
    $tpl->assign("AtxtSeccion", $carrera);

    //$tpl->assign('dirActividades','../notas-actividades/crearActividad.php?opcion=1&curso='.$_SESSION["idcurso"].'&index='.$_SESSION["index"].'&carrera='.$_SESSION["carrera"].'&docentes='.$_SESSION["docentes"]);
    //para direccionar pdf
    $tpl->assign('aParametros', 'txtCursoNombre='.$_SESSION["nombreCurso"].'&txtCarrera='.$manejoCadena->StringCarrera('0' . $carrera).'&txtPeriodo='.$manejoCadena->funTextoPeriodo($periodo).'&txtAnio='.$anio.'&txtCurso='.$curso);
    
    $tpl->newblock("listado");
       /*encabezado*/
        $tpl->assign("txtCurso", $curso);
        $tpl->assign("txtSeccion", $carrera);
        $tpl->assign("nombrePeriodo", $_SESSION["nombreperiodo"] . " $anio");
        $tpl->assign("vCurso", $_SESSION['idcurso']);
        $tpl->assign("vNombre", $_SESSION["nombreCurso"]);
        $tpl->assign("vCarrera", $manejoCadena->StringCarrera('0' . $carrera));
        $tpl->assign("vPeriodo", $manejoCadena->funTextoPeriodo($periodo));
        $tpl->assign("vAnio", $anio);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));
    
    $queryCountActividades=$controladorListadoActividad->queryNombreActividad($curso, $periodo, $carrera, $anio);
    $bd->query($queryCountActividades);
    $countActividades = $bd->num_rows();
    
    if($countActividades>0){
        $queryGetNotas = $controladorListadoActividad->querygetZonas($curso, $carrera, $periodo, $anio);
        $bd->query($queryGetNotas);
        $x=0;  // para manejar el acceso a las actividades del estudiante
                
        $Contador=0; // controla cuantos estudiantes existen.
        $detalleZona = ''; //detalla la zona de cada estudiantea para presentarla
        $zonaEstudiante=0; //maneja la zona de cada estudiante
        $actividadesFalta=0; // maneja la cantidad de actividades faltantes de cada estudiante
        $zonasTotales = array(); // maneja todas las zonas por estudiante, y las setea a una variable de sesion

        while (($bd->next_record()) != null) {
            $FilaEstudiante = $bd->r();
            
            if ($x == 0) {
                
                $Contador++;
                
                
                $tpl->newblock("DatoOcultar");
                $tpl->assign("carnet", "Det" . $FilaEstudiante[rcarnet]);

                $tpl->newblock("filaestudiante");
                $Link = $FilaEstudiante[rcarnet];
                $tpl->assign("txtContador", $Contador);
                $tpl->assign("txtLinkCarnet", $Link);
                $tpl->assign("txtCarnet", $FilaEstudiante[rcarnet]);
                $tpl->assign("txtNombre", $FilaEstudiante[rname]);

                $detalleZona = $detalleZona . '<div align="center"><table width="90%" cellpadding="5"  cellspacing="0" border="0" style="padding-left:250px !important;">';
                $detalleZona = $detalleZona . '<tr align="center"><td><strong>ACTIVIDAD&nbsp;&nbsp;&nbsp;&nbsp;</strong></td><td><strong>PONDERACIÓN DE LA ACTIVIDAD&nbsp;&nbsp;&nbsp;&nbsp;</strong></td><td><strong>NOTA OBTENIDA EN LA ACTIVIDAD</strong></td><td><strong>OBSERVACION</strong></td></tr>';
                
            }

            if(($x+1)>=$countActividades){ //si es el ultimo registro de actividad del estudiante
                
                if($FilaEstudiante[rtipo]!=5){
                    
                    
                    if($FilaEstudiante[rnotaobtenida]>=0){
                        $zonaEstudiante=$zonaEstudiante+$FilaEstudiante[rnotaobtenida];
                    }else{
                        $FilaEstudiante[rnotaobtenida]='NSP';
                        $actividadesFalta++; //SUMAR LAS FALTAS QUE HAYA COMETIDO
                    }
                    $detalleZona = $detalleZona . '<tr><td><span style="text-transform: uppercase;">'.$FilaEstudiante[rnombre].'</span></td><td align="right">'.$FilaEstudiante[rponderacion].'</td><td align="center">'.$FilaEstudiante[rnotaobtenida].'</td><td></td></tr>';
                }else{
                    $Reposicion=true;//controla a nivel global que existe un examen de reposicion$Reposicion
                    $detalleZona = $detalleZona . '<tr><td><span style="text-transform: uppercase;">'.$FilaEstudiante[rnombre].'</span></td><td align="right">'.$FilaEstudiante[rponderacion].'</td><td align="center">'.$FilaEstudiante[rnotaobtenida].'</td><td>Nota intercambiada por el parcial de menor punteo</td></tr>';
                }
                
                
                $detalleZona = $detalleZona .'<tr style="background-color: #4CAF50; color: white;"><td colspan="3" align="center" style="text-transform: uppercase;"><strong>Total</strong></td><td align="center"><strong>'.$zonaEstudiante.'</strong></td></tr>';
                
                
                
                $ZonaAproxEstudiante= round($zonaEstudiante, 0);
                
                //calcular el porcentaje para ver si tiene o no derecho a examen final
                $derechoAExamen=false;
                if (getPorcentajeAsisitencia($countActividades, $actividadesFalta) >= 80) {
                    if ($ZonaAproxEstudiante >= getZonaMinima()){
                        $detalleZona = $detalleZona . '<tr><td colspan="4" align="center" style="text-transform: uppercase;"><td align="center"><strong>DERECHO A EXAMEN FINAL</strong></td></tr>';
                        $derechoAExamen=true;
                    }
                    else
                        $detalleZona = $detalleZona . '<tr><td colspan="4" align="center" style="text-transform: uppercase;"><td align="center"><strong>SIN DERECHO A EXAMEN FINAL ZONA MINIMA INSUFICIENTE</strong></td></tr>';
                }else {
                    if ($ZonaAproxEstudiante >= getZonaMinima())
                        $detalleZona = $detalleZona . '<tr><td colspan="4" align="center" style="text-transform: uppercase;"><td align="center"><strong>SIN DERECHO A EXAMEN FINAL PORCENTAJE DE ASISTENCIA INSUFICIENTE</strong></td></tr>';
                    else
                        $detalleZona = $detalleZona . '<tr><td colspan="4" align="center" style="text-transform: uppercase;"><td align="center"><strong>SIN DERECHO A EXAMEN FINAL ZONA MINIMA INSUFICIENTE</strong></td></tr>';
                }




                $detalleZona = $detalleZona .'</table></div>';
                $tpl->newblock("totalmagistral");                
                $tpl->assign("txtTotalZona",$ZonaAproxEstudiante );
                $textObservacionImpresion="SDE"; //gestiona la observacion en el pdf
                if($derechoAExamen){
                    $tpl->assign("color","GREEN");//"rgba(65, 244, 110,.5)");
                    $textObservacionImpresion="DE";//DERECHO A EXAMN
                }
                else
                    $tpl->assign("color","RED");//"rgba(255,0,0,0.5)");
                
                $tpl->assign("aDetalle", $detalleZona);
                
                
                //setear los arrays, para luego setear la variable de sesion
                $estudiante = array("carnet"=>$FilaEstudiante[rcarnet], "nombre"=>$FilaEstudiante[rname], "zona"=>$ZonaAproxEstudiante, "obs"=>$textObservacionImpresion);
                array_push($zonasTotales,$estudiante);
                // inicializar variables.
                $zonaEstudiante=0;
                $detalleZona='';
                $actividadesFalta=0;
                $x=0;
                
            }else{ // si seguimos iterando el mismo estudiante.
                
                if($FilaEstudiante[rtipo]!=5){
                    
                    if($FilaEstudiante[rnotaobtenida]>=0){
                        $zonaEstudiante=$zonaEstudiante+$FilaEstudiante[rnotaobtenida];
                    }else{
                        $FilaEstudiante[rnotaobtenida]='NSP';
                        $actividadesFalta++;
                    }
                    $detalleZona = $detalleZona . '<tr><td><span style="text-transform: uppercase;">'.$FilaEstudiante[rnombre].'</span></td><td align="right">'.$FilaEstudiante[rponderacion].'</td><td align="center">'.$FilaEstudiante[rnotaobtenida].'</td><td></td></tr>';
                }else{
                    $Reposicion=true;//controla a nivel global que existe un examen de reposicion$Reposicion
                    $detalleZona = $detalleZona . '<tr><td><span style="text-transform: uppercase;">'.$FilaEstudiante[rnombre].'</span></td><td align="right">'.$FilaEstudiante[rponderacion].'</td><td align="center">'.$FilaEstudiante[rnotaobtenida].'</td><td>Nota intercambiada por el parcial de menor punteo</td></tr>';
                }
                
                
                $x++;
            }
        
        }//fin de recorrer todas las notas
        
        $_SESSION['zonasActividades'] = $zonasTotales;
        
        //print_r($Reposicion);
        /*observar si este curso ya fueron aprobadas las notas*/
        $queryGetAprobacion = $controladorListadoActividad->queryGetAprobacionCurso($curso, $carrera, $periodo, $anio);
        $bd->query($queryGetAprobacion);
        $resultadoQuery=(($bd->next_record()) != null)? $bd->r():null;
        if($resultadoQuery!=null &&  $resultadoQuery[aprobado]==1){ 
            $_SESSION[CursoAprobado] = 1;            
        }else{
            $_SESSION[CursoAprobado] = 0;            
        }
        
    }else{
        //mensaje de alerta notificando, que no exiten actividades para este curso.
        $tpl->newblock("mensaje");
        $tpl->assign("mensaje", '<div class="alert alert-danger">
        <h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>
        No existen actividades creadas para este curso.
        </div>');
    }
}

function aprobarNotasCurso($tpl,$curso,$carrera,$periodo,$anio){
    global $controladorListadoActividad;
    global $bd;
    
    if($_SESSION[CursoAprobado] == 1){
        $tpl->newblock("mensaje");
        $tpl->assign("mensaje", '<div class="alert alert-success">
            <h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>
            Este es el reporte de notas de actividades ya aprobadas.
            </div>');
            return ;
        }else{
            // APROBAR TODAS LAS NOTAS
            $bd->query($controladorListadoActividad->begin());  // INICIAR TRANSACCION
            $ERROR = 0;
            $ManejoErrores="";
            
            $zonasTotales = $_SESSION['zonasActividades'];
            
            for($i=0;$i<sizeof($zonasTotales);$i++){
                
                $row = $zonasTotales[$i];                
                $carnet = $row['carnet'];
                $zona =$row['zona'];
                $queryAprobacion = $controladorListadoActividad->queryAprobarCurso($zona,$carnet,$curso, $carrera, $periodo, $anio,$_SESSION['regper']);
                $ERROR+=(!$bd->query($queryAprobacion)) ? 1 :  0;
            }
            
            
            
            if ($ERROR == 0) {
                $bd->query($controladorListadoActividad->commit());
                $ManejoErrores = "Aprobacion de notas de curso exitosa";
            }else{
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al aprobar las notas del curso solicite apoyo Unidad Virtual FMVZ.";
                }
            $bd->query($controladorListadoActividad->end());
            
            $tpl->newblock("mensaje");
            $tpl->assign("mensaje", '<div class="alert alert-success">'
                    . '<h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>'.$ManejoErrores.'</div>');
        }
    
}
/*******************************************************************************  INICIO LOGICA DEL SISTEMA*/
switch ($opcion) {
    case 0:// APRUEBA LA INFORMACION DEL CURSO
        $txtCurso = $_GET['txtCurso'];
        $txtLaSeccion = $_GET['txtLaSeccion'];
        $txtCarrera = $_GET['txtCarrera'];
        $txtPeriodo = $_GET['txtPeriodo'];
        $txtAnio = $_GET['txtAnio'];
        aprobarNotasCurso($tpl,$txtCurso,$txtCarrera,$txtPeriodo,$txtAnio);
        break;
    case 1:// MUESTRA LAS NOTAS DE LOS ESTUDIANTES
        $txtCurso = $_GET['txtCurso'];
        $txtLaSeccion = $_GET['txtLaSeccion'];
        $txtCarrera = $_GET['txtCarrera'];
        $txtPeriodo = $_GET['txtPeriodo'];
        $txtAnio = $_GET['txtAnio'];
        $txtTieneLaboratorio = $_GET['laboratorio'];
        $txtRegPer = $_GET['txtRegPer'];
        $txtSeccion = str_replace("*", "+", "$txtLaSeccion");


        mostrarListado($tpl,$txtCurso,$txtSeccion,$txtPeriodo,$txtAnio);
        break;
        }
$tpl->gotoBlock('_ROOT');
$sistema = verificar_sistemaHabilitado($bd, $_SESSION["sPeriodo"], $_SESSION["sAnio"],$_SESSION["carrera"], $_SESSION["idcurso"]);

if($sistema==100){
    $tpl->assign("RegresarActividades", '<a href="../notas-actividades/crearActividad.php?opcion=1&curso='.$_SESSION["idcurso"].'&index='.$_SESSION["index"].'&carrera='.$_SESSION["carrera"].'&docentes='.$_SESSION["docentes"].'"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de actividades" ></a>');
}else{
    $tpl->assign("RegresarActividades", '<a href="../menu/D_CourseList.php"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de cursos" ></a>');
}

$tpl->newblock("botones");        
$tpl->assign("txtCurso", $txtCurso);
$tpl->assign("txtLaSeccion", $txtLaSeccion);
$tpl->assign("txtCarrera", $txtCarrera);
$tpl->assign("txtPeriodo", $txtPeriodo);
$tpl->assign("txtAnio", $txtAnio);


$tpl->assign("InitTabla",'<script>var table = $("#dgTablaDatos").DataTable( {language: {url: "../../libraries/js/DataTables-1.10.6/lang/es_ES.json"}, scrollCollapse: false, paging: false,searching: false, ordering: false,columnDefs: [{"className": \'details-control\',"orderable": false, "data":null, "defaultContent": \'\', width: "4%", targets: 0 }, {width: "8%", targets: 1 }, {width: "73%", targets: 2 },{width: "15%", targets: 3 }]});</script>');






if($Reposicion){
    $tpl->assign(notaInasistencia,"&nbsp; &nbsp; &nbsp; - La notas de parciales de reposicion unicamente se cambian si: <br>"
            . "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; * La nota de Reposicion es mayor a cero <br>"
            . "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; * Si existe nota de un Parcial menor a la nota de Reposicion <br>"
            . "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; * Si se cumple lo anterior:<br>"
            . "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; ---- La nota actual que se muestra en el Examen de Reposicion sera la del parcial con el Menor Punteo<br>"
            . "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; ---- La nota del parcial con menor Punteo sera la de Reposicion<br>"
            . "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; ---- La nota actual que se muestra en el Examen de Reposicion no se sumara a la Zona Total dado que ya se intercambio.<br>");
    
}


if($_SESSION[CursoAprobado] == 0){
    $tpl->assign("tipoaprobar", "submit");
}else {
    $tpl->assign("tipoaprobar", "hidden");
    $tpl->newblock("mensaje");
    $tpl->assign("mensaje", '<div class="alert alert-success">
        <h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>
        Este es el reporte de notas de actividades ya aprobadas.
        </div>');
}

        
$tpl->printToScreen();
unset($tpl);