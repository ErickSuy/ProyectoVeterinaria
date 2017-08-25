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
include_once("$dir_portal/fw/model/sql/creaactividad_SQL.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");

require "conectar.php"; /* archivo que maneja la conexion y la variable BD*/



// Creacion visual de la pagina.
$tpl = new TemplatePower("crearActividad.tpl");

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
$_SESSION["totalZonaActual"]; // manejara la zona actual del curso para notificarle al usuario 
                              // cuanta zona le falta completar, manejado por un array de posiciones
                              // [0] = zona clase magistral
                              // [1] = zona laboratorio     
                              // [2] = zona total de curso actual
$_SESSION["notaReposicion"]; // manejara si existe reposicion true|false
global $controladorCrearActividad;
global $bd;
global $manejoCadena;
global $noActividades;
global $ponderacionActividades;
global $ManejoErrores;



/*******************************************************************************  Asignacion globales.*/
$opcion = $_GET['opcion']; 
$_SESSION["sPeriodo"]; /* ya asignada*/
$_SESSION["sAnio"]; /* ya asignada*/
$_SESSION['regper'];/* ya asignada*/
$_SESSION['group'];/*ya asignada*/
$controladorCrearActividad = new creaactividad_SQL();
$manejoCadena= new ManejoString();
$ponderacionActividades=0.0;
$ManejoErrores="";

/*******************************************************************************  DEFINICION FUNCIONES METODOS */
function retornarNombreCurso($curso, $index)
{
    global $bd;
    global $controladorCrearActividad; 
    $SqlNombre = $controladorCrearActividad->queryNombreCurso($curso, $index);    
    
    $bd->query($SqlNombre);
    if ($bd->num_rows() > 0) {
        $bd->next_record();
        $FilaDato = $bd->r();
       return $FilaDato[name];
    }
    return "";
}

function programaActividadesDefault($Curso, $Periodo, $Carrera, $Anio,$Index,$Parciales,$lab){
    /* Planificacion standar Erick Suy2016:
     *  * *  ---- cursos SIN laboratorio 
         * INTRODUCTORIO Y BASICO
         * ---------- TEORICA --------------- tbschudletype = 1
         *  -- 2 parciales 15pts c/u
         *  -- Examenes Cortos 20 pts
         *  -- Tareas/Trabajos 10 pts
         *  -- Practicas       10 pts / Laboratorio
         * MODULAR
         * ---------- TEORICA --------------- tbschudletype = 1
         *  -- 2 parciales 20pts c/u
         *  -- Examenes Cortos 20 pts
         *  -- Tareas/Trabajos 10 pts 
         *  -- Practicas       10 pts / Labotario
         */
    
    global $bd;
    global $controladorCrearActividad;
    global $ManejoErrores;
    $ManejoErrores="";
    
    $regPer = $_SESSION['regper'];
    $docentes = $_SESSION['docentes'];

    if (strlen(trim($docentes)) > 0) {
        $docentes = $regPer . ',' . $docentes;
    } else {
        $docentes = $regPer;
    }
    $docentes ="{".$docentes."}";//para darle formato de postgresql ejemplo.{12,35,65}

    $activo = OK;
    $sqlCalendarioActividad = $controladorCrearActividad->queryCalendarioActividades($Curso, $Periodo, $Carrera, $Anio);
    $bd->query($sqlCalendarioActividad);
    $resultadoCalendario = (($bd->next_record()) != null) ? $bd->r() : null;
    if ($resultadoCalendario != null) {
        if (!is_null($resultadoCalendario[inicioperiodo]) || !is_null($resultadoCalendario[finalperiodo])) {
            $bd->query($controladorCrearActividad->begin()); //" begin " iniciar la transaccion para ingresar las actividades
            $ERROR = 0;
            if (!is_null($resultadoCalendario[inicioprimerparcial])){
                $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "Primer Parcial", PRIMER_PARCIAL, $resultadoCalendario[inicioprimerparcial], $Parciales, CLASE_MAGISTRAL, OK);
                $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
                $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
                $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            }
            
            if(!is_null($resultadoCalendario[iniciosegundoparcial])){
                 $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "Segundo Parcial", SEGUNDO_PARCIAL, $resultadoCalendario[iniciosegundoparcial], $Parciales, CLASE_MAGISTRAL, OK);
                 $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
                 $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
                 $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);                
            }
            
            
            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "Examenes Cortos", EXAMEN_CORTO, $resultadoCalendario[finalperiodo], 20, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);

            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "Tareas/Trabajos", TAREA, $resultadoCalendario[finalperiodo], 10, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);

            if ($lab) {
                //Laboratorio
                $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "Laboratorio", ACTIVIDAD_LAB, $resultadoCalendario[finalperiodo], 10, LABORATORIO, OK);
                $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
                $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
                $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            } else {
                $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "Practicas", PRACTICAS_INTEGRADAS, $resultadoCalendario[finalperiodo], 10, CLASE_MAGISTRAL, OK);
                $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
                $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
                $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            }

            if ($ERROR == 0) {
                $bd->query($controladorCrearActividad->commit());
                $ManejoErrores = "Creacion de Actividades por defecto con exito. <br> Las actividades, presentadas fueron creadas en base a un "
                        . "analisis previo sobre los programas de cursos abstrayendo actividades en comun, se le recomienda editar las que "
                        . "considere convenientes, pero no eliminar las activididades pertenencientes al normativo."
                        . "(Examenes, Parciales, Laboratorio/si aplicara)";
            } else {
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al ingresar actividades por defecto solicite apoyo Unidad Virtual FMVZ.";
            }
            $bd->query($controladorCrearActividad->end());
        }else{
            $ManejoErrores = "Error, No existe calendario definido para este curso, solicite a control academico la revision de dicho problema";
        }
    } else {
        $ManejoErrores = "Error, No existe calendario definido para este curso, solicite a control academico la revision de dicho problema";
    }
    return $ManejoErrores;
}

function fechaAprobacionActividad($curso, $seccion, $periodo, $anio){
    /*
     * Funcion encargada de insertar un registro en la tabla ing_fechaaprobacionActividad
     * la cual es la que se encarga de gestionar la aprobacion de notas al final de semestre.
     */
    global $bd;
    global $controladorCrearActividad;
    $error=0;
    $regPer = $_SESSION['regper'];
    
    $sqlExiste=$controladorCrearActividad->queryExisteFechaAprobacion_Actividad($curso, $seccion, $periodo, $anio);
    (!$bd->query($sqlExiste));
    $rsl = (($bd->next_record()) != null) ? $bd->r() : null;
    
    if ( is_null($rsl)  || $rsl[cantidad]==0) {
        //insertar una nueva entrada para fecha de aprobacion
        $sqlInsert = $controladorCrearActividad->queryInsertFechaAprobacion_Actividad($curso, $seccion, $periodo, $anio, $regPer);
        (!$bd->query($sqlInsert)) ? $error+=1 : "";
    }
            
    
    return $error;
}

function programaActividadesDefaultEPS($Curso, $Periodo, $Carrera, $Anio){
     /* Planificacion EPS 2017:
      *---- Organizacion 25 pts
      *---- Servicio 25 pts
      *---- Docencia y Extension 25 pts
      *---- Investigacion 10 pts
      *---- Supervision 15 pts     
         */
    
    global $bd;
    global $controladorCrearActividad;
    global $ManejoErrores;
    $ManejoErrores="";
    
    $regPer = $_SESSION['regper'];
    $docentes = $_SESSION['docentes'];

    if (strlen(trim($docentes)) > 0) {
        $docentes = $regPer . ',' . $docentes;
    } else {
        $docentes = $regPer;
    }
    $docentes ="{".$docentes."}";//para darle formato de postgresql ejemplo.{12,35,65}

    $activo = OK;
    $sqlCalendarioActividad = $controladorCrearActividad->queryCalendarioActividades($Curso, $Periodo, $Carrera, $Anio);
    $bd->query($sqlCalendarioActividad);
    $resultadoCalendario = (($bd->next_record()) != null) ? $bd->r() : null;
    if ($resultadoCalendario != null) {
        if (!is_null($resultadoCalendario[inicioperiodo]) || !is_null($resultadoCalendario[finalperiodo])) {
            $bd->query($controladorCrearActividad->begin()); //" begin " iniciar la transaccion para ingresar las actividades
            $ERROR = 0;
            
            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "ORGANIZACION", ORGANIZACION_SUPERVISION, $resultadoCalendario[finalperiodo], ORG, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            
            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "SERVICIO", SERVICIO, $resultadoCalendario[finalperiodo], SERV, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            
            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "DOCENCIA Y EXTENSION", DOCENCIA_EXTENSION, $resultadoCalendario[finalperiodo], DOCE, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            
            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "INVESTIGACION", INVESTIGACION, $resultadoCalendario[finalperiodo], INV, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            
            $ERROR+= insertarActividad($Curso, $Anio, $Periodo, $Carrera, $resultadoCalendario[inicioperiodo], "SUPERVISION", ORGANIZACION_SUPERVISION, $resultadoCalendario[finalperiodo], SUPER, CLASE_MAGISTRAL, OK);
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
            
            if ($ERROR == 0) {
                $bd->query($controladorCrearActividad->commit());
                $ManejoErrores = "Creacion de Actividades por defecto con exito. <br> Las actividades presentadas en esta seccion se obtuvieron del documento \"Boleta de Control y Evaluacion E.P.S\" "
                        . "se le recomienda editar sus fechas, pero no alterar el programa a menos que lo considere conveniente.";
            } else {
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al ingresar actividades por defecto solicite apoyo Unidad Virtual FMVZ.";
            }
            $bd->query($controladorCrearActividad->end());
            
        }else
            $ManejoErrores = "Error, No existe calendario definido para este curso, solicite a control academico la revision de dicho problema";
    }else
        $ManejoErrores = "Error, No existe calendario definido para este curso, solicite a control academico la revision de dicho problema";
    
    return $ManejoErrores;
    
}

function agregarActividadesDefault($Curso, $Periodo, $Carrera, $Anio,$Index){
    /*Para la estandarizacion de todos los cursos se preveen una serie de actividades que todos deben inlcuir
     * segun el reglamento establecido por la facultad*/
    global $bd;
    global $controladorCrearActividad;
    global $ManejoErrores;
        
    $sqlNoActividad = $controladorCrearActividad->queryNumeroActividades($Curso, $Periodo, $Carrera, $Anio);    
    $bd->query($sqlNoActividad);
    $resultadoQuery=(($bd->next_record()) != null)? $bd->r():null;
    if($resultadoQuery!=null &&  $resultadoQuery[conteo]==0){ 
        //no existen actividades aun registradas es el inicio y se deben precargar.
        
        
        // primero se debe de cargar la fecha de aprobacion de curso
        if(fechaAprobacionActividad($Curso, $Carrera, $Periodo, $Anio)>0){
            return "No se ha posido ingresar informacion para la aprobacion de las notas finales, intente nuevamente o solicite a control academico la revision de dicho problema";
        }
        
        if($Curso == id_EPS) // si es EPS es un caso especial que tiene un progrma diferente
            return programaActividadesDefaultEPS($Curso, $Periodo, $Carrera, $Anio);
        
        //insertar programa de curso por defecto
        $sqlCalendarioActividad = $controladorCrearActividad->queryCalendarioActividades($Curso, $Periodo, $Carrera, $Anio);
        $bd->query($sqlCalendarioActividad);
        $resultadoCalendario = (($bd->next_record()) != null) ? $bd->r() : null;
        if ($resultadoCalendario != null) {
            
            //Atributos Actividad 
            // -- generales
            $cursoAtrib = $Curso;
            $anioAtrib = $Anio;
            $periodoAtrib = $Periodo;
            $carreraAtrib = $Carrera;
            $inicioPeriodo = $resultadoCalendario[inicioperiodo];

                    
            if (esModular($Index, $Curso, $carreraAtrib)) {
                //print_r("\n soy modular");
                if (tieneLab($Curso, $Periodo, $Carrera, $Anio,true)) {
                    //print_r("\n Tengo lab");
                    return programaActividadesDefault($Curso, $Periodo, $Carrera, $Anio, $Index, PARCIAL_MODULAR, true);
                } else {
                    //print_r("\nNO tengo lab");
                    return programaActividadesDefault($Curso, $Periodo, $Carrera, $Anio, $Index, PARCIAL_MODULAR, false);
                }
            }//fin if es modular
            else {   
                //print_r("\nNO soy modular");
                if (tieneLab($Curso, $Periodo, $Carrera, $Anio,false)) {
                    //print_r("\n Tengo lab");
                    return programaActividadesDefault($Curso, $Periodo, $Carrera, $Anio, $Index, PARCIAL_INTROBASICVACAS, true);
                } else {
                    //print_r("\nNO tengo lab");
                    return programaActividadesDefault($Curso, $Periodo, $Carrera, $Anio, $Index, PARCIAL_INTROBASICVACAS, false);
                }
            }//fin if es modular
            
        }//fin if resultado calendario
        else{
            return "No existe calendario definido para este curso, solicite a control academico la revision de dicho problema";
        }       
    }
    return null;
}

function insertarActividad($cursoAtrib, $anioAtrib, $periodoAtrib, $carreraAtrib, 
        $inicioPeriodo,$nombre, $tipo, $fechaEntrega, $ponderacion, $docencia, $activo) {
    global $bd;
    global $controladorCrearActividad;    

    $queryActividades = $controladorCrearActividad->queryInsertActividad($nombre, $tipo, 
            $fechaEntrega, $ponderacion, $docencia, $activo, 
            $cursoAtrib, $anioAtrib, $periodoAtrib, $carreraAtrib, 
            $inicioPeriodo);
    
    return (!$bd->query($queryActividades)) ? 1 :  0;
}

function insertarRegPersonalResponsable($actividad,$responsable,$registroPersonal){
    global $bd;
    global $controladorCrearActividad;
    
    $queryActividades = $controladorCrearActividad->queryInsertRegPersonal_Actividad($actividad, $responsable, $registroPersonal);
    return (!$bd->query($queryActividades)) ? 1 :  0;
}

function asignarEstudianteActividad($idActividad,$Curso, $Periodo, $Carrera, $Anio){
    global $bd;
    global $controladorCrearActividad;
    $error=0;
    $queryAsignarEstuante = $controladorCrearActividad->queryNotasActividad($idActividad, $Curso, $Periodo, $Carrera, $Anio);
    (!$bd->query($queryAsignarEstuante)) ? $error+=1 : "";
    $queryAsignarEstuante = $controladorCrearActividad->queryInsertBitacoraInicial($_SESSION['regper'], $_SESSION['group'], $idActividad,$Curso, $Periodo, $Carrera, $Anio);
    //print_r($queryAsignarEstuante);
    (!$bd->query($queryAsignarEstuante)) ? $error+=1 : "";    
    //$error+=1;
    return $error;    
}

function crearVistaActividadesTmp($Curso,$Periodo, $Carrera, $Anio,$ScheduleType,$Tipo,$tpl){
    global $bd;
    global $controladorCrearActividad;
    global $noActividades;
    global $ponderacionActividades;
    
    $sqlActividades = $controladorCrearActividad->queryGetActividades($Curso,$Periodo,$Carrera,$Anio,$ScheduleType,$Tipo,null /*comparar method all?*/);
    $bd->query($sqlActividades);

    while (($bd->next_record()) != null) {
        $Actividad = $bd->r();
        $tpl->newblock("actividadTmp");
            $tpl->assign("nombreActividad", $Actividad[nombre]);
            $tpl->assign("PonderacionActividad", $Actividad[ponderacion]);
            $tpl->assign("FechaActividad",ImprimeFecha($Actividad[fechaentrega]));
            $tpl->assign("noActividad", $noActividades);
            $tpl->assign("refEditarActividad", '../notas-actividades/crearActividad.php?opcion=5&idActividad='.$Actividad[idactividad]);
            $tpl->assign("refBorrarActividad",$Actividad[idactividad]);
            $tpl->assign("refCargarActividad",'../notas-actividades/manejoarchivoactividades.php?opcion=20&txtIdActividad='.$Actividad[idactividad].'&txtPonderacionActividad='.$Actividad[ponderacion].'&txtCurso='.$Curso.'&txtSeccion='.$Carrera.'&txtPeriodo='.$Periodo.'&txtAnio='.$Anio.'&txtTipoActividad='.$Actividad[tipo].'&txtCarrera='.$Carrera);
            $tpl->assign("redireccionarPag",'../notas-actividades/crearActividad.php?opcion=1&curso='.$_SESSION["idcurso"].'&index='.$_SESSION["index"].'&carrera='.$_SESSION["carrera"].'&docentes='.$_SESSION["docentes"]);
        // para evitar sumar los de reposicion
        if($Actividad[tipo]!=5){
            $ponderacionActividades+=$Actividad[ponderacion];
            //$_SESSION['notaReposicion']=false;
        }else{
            $_SESSION['notaReposicion']=true;
        }
        $noActividades++;
        
    }
}
function tieneActividadesCategoria($Curso,$Periodo, $Carrera, $Anio,$ScheduleType,$TipoArray){
    global $bd;
    global $controladorCrearActividad;
    global $noActividades;
    $sqlActividades = $controladorCrearActividad->queryGetActividades($Curso,$Periodo,$Carrera,$Anio,$ScheduleType,$TipoArray, null/*compara method all?*/);
    $bd->query($sqlActividades);
    
    if($bd->num_rows()>0)
        return true;
    
    return false;
}
function crearVistaTabDetalle($Curso,$Periodo, $Carrera, $Anio,$Docencia,$TipoArray,$tpl,$methoALL){
    global $bd;
    global $noActividades;    
    global $controladorCrearActividad;
    
    $BASE2=$bd;
    $sqlActividades = $controladorCrearActividad->queryGetActividades($Curso,$Periodo,$Carrera,$Anio,$Docencia,$TipoArray,$methoALL);
    $BASE2->query($sqlActividades);
    // -------------------------------------------------- HEAD TABLA
    $tpl->newBlock("tituloColumna");
        $tpl->assign("textotituloColumna","Nombre");
    $tpl->newBlock("tituloColumna");
        $tpl->assign("textotituloColumna","Ponderacion");
    $tpl->newBlock("tituloColumna");
        $tpl->assign("textotituloColumna","Fecha");
    $tpl->newBlock("tituloColumna");
        $tpl->assign("textotituloColumna","Entregados");
    $tpl->newBlock("tituloColumna");
        $tpl->assign("textotituloColumna","Promedio Nota");
    $tpl->newBlock("tituloColumna");
        $tpl->assign("textotituloColumna","Accion");
    
    
    $bd2 = conectarBase();
    while (($BASE2->next_record()) != null) {
        $Actividad = $BASE2->r();
        
        $sqlPromedio = $controladorCrearActividad->queryGetPromedioEntregados($Actividad[idactividad]);
        $bd2->query($sqlPromedio);
        $infoActividad =(($bd2->next_record()) != null) ?$bd2->r() :  null;
        $infoActividadTotal =(($bd2->next_record()) != null) ?$bd2->r() :  null;
        $promedioEntregado=($infoActividad[entregados]*100)/$infoActividadTotal[entregados];
        $promedioNota=($infoActividad[promedio]*100)/$Actividad[ponderacion];
        
        $tpl->newBlock("NuevaFila");
                    $tpl->newBlock("NuevaColumna");
                    $tpl->assign("contenidoColumna",$Actividad[nombre]);
                    $tpl->newBlock("NuevaColumna");
                    $tpl->assign("contenidoColumna",$Actividad[ponderacion]."pts");
                    $tpl->newBlock("NuevaColumna");
                    $tpl->assign("contenidoColumna",$Actividad[fechaentrega]);
                                       
                    if($promedioEntregado!=null && $promedioEntregado>0 && $promedioNota!=null && $promedioNota>0){
                        // bajo = 0 - 33 %
                        // bueno = 34 - 66 %
                        // excelente = 67 - 100%
                        $tpl->newBlock("NuevaColumna");
                        if($promedioEntregado>=0 && $promedioEntregado<34)
                            $tpl->assign("color","rgba(255,0,0,0.5)");
                        else if($promedioEntregado>=34 && $promedioEntregado<67)
                            $tpl->assign("color","rgba(220, 244, 66,.5)");
                        else
                            $tpl->assign("color","rgba(65, 244, 110,.5)");
                        $tpl->assign("contenidoColumna","$infoActividad[entregados]/$infoActividadTotal[entregados]");
                        $tpl->assign("align",'align="center"');
                        
                                               
                        $tpl->newBlock("NuevaColumna");
                        if($promedioNota>=0 && $promedioNota<34)
                            $tpl->assign("color","rgba(255,0,0,0.5)");
                        else if($promedioNota>=34 && $promedioNota<67)
                           $tpl->assign("color","rgba(220, 244, 66,.5)  "); 
                        else
                            $tpl->assign("color","rgba(65, 244, 110,.5)");
                        $tpl->assign("contenidoColumna",$infoActividad[promedio]."pts");
                        $tpl->assign("align",'align="center"');
                    }else{
                       $tpl->newBlock("NuevaColumna");
                       $tpl->assign("color","rgba(255,0,0,0.5)");
                       $tpl->assign("contenidoColumna","0/$infoActividadTotal[entregados]");
                       $tpl->assign("align",'align="center"');
                       
                       $tpl->newBlock("NuevaColumna");
                       $tpl->assign("color","rgba(255,0,0,0.5)");
                       $tpl->assign("contenidoColumna","0 pts");
                       $tpl->assign("align",'align="center"');
                    }
                    
                    $tpl->newBlock("menuFila");
                    //$actvidad=$i."".$x;
                        $tpl->assign("noActividad","$noActividades");
                        $tpl->assign("refEditarActividad", '../notas-actividades/crearActividad.php?opcion=5&idActividad=' . $Actividad[idactividad]);
                        $tpl->assign("refBorrarActividad", $Actividad[idactividad]);
                        $tpl->assign("refCargarActividad", '../notas-actividades/manejoarchivoactividades.php?opcion=20&txtIdActividad=' . $Actividad[idactividad] . '&txtPonderacionActividad=' . $Actividad[ponderacion] . '&txtCurso=' . $Curso . '&txtSeccion=' . $Carrera . '&txtPeriodo=' . $Periodo . '&txtAnio=' . $Anio . '&txtTipoActividad=' . $Actividad[tipo] . '&txtCarrera=' . $Carrera);
                        if($Docencia==2)
                            $tpl->assign("redireccionarPag", '../notas-actividades/crearActividad.php?opcion=2&docencia=PRACTICA');
                        else
                            $tpl->assign("redireccionarPag", '../notas-actividades/crearActividad.php?opcion=2&docencia=TEORICA');
                        
                    $tpl->gotoBlock('nuevaTab');
                    $tpl->assign("cargarNotas",'<a id="editarNotas" href="../notas-actividades/crearActividad.php?opcion=4" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-hand-o-up fa-lg"></i><span>&nbsp;&nbsp;Editar Notas</span></a>');
        
        $noActividades++;
    
    }
}

function esModular($txtIndex, $txtCurso, $txtCarrera)
{
    // false = Introductorio o basico
    // true  = Modular
    global $bd;
    global $controladorCrearActividad;
    $resultado = false;

    $SqlSeccionMagistral  = $controladorCrearActividad->esCursoModular($txtIndex,$txtCurso,$txtCarrera);
    if($bd->query($SqlSeccionMagistral) AND $bd->num_rows() > 0) {

        $bd->next_record();
        $Resultado=$bd->r();
        if(($Resultado[0] + 0)==true) {
            $resultado = true;
        }
    }
    return $resultado;
}

function tieneLab($Curso, $Periodo, $Carrera, $Anio,$esModular){
    global $bd;
    global $controladorCrearActividad;
    global $noActividades;
    $sqlLaboratorio = $controladorCrearActividad->queryHorarioLab($Curso, $Periodo, $Carrera, $Anio,$esModular);
    $bd->query($sqlLaboratorio);
    $resultadoQuery=(($bd->next_record()) != null)? $bd->r():null;
    if($resultadoQuery!=null){
        if($resultadoQuery[laboratorio]==1){
        return true;        
        }
    }
    return false;
    //($resultadoQuery!=null)?$resultadoQuery[laboratorio]:0;
    
}

function crearMenuCalendario($Curso,$Periodo, $Carrera, $Anio,$tpl){
    global $bd;
    global $controladorCrearActividad;
    $sqlActividades = $controladorCrearActividad->queryCalendarioActividades($Curso,$Periodo, $Carrera, $Anio);
    $bd->query($sqlActividades);
    $resultadoCalendario = (($bd->next_record()) != null) ? $bd->r() : null;
    if ($resultadoCalendario != null) {
        $tpl->gotoBlock('_ROOT');
        $tpl->newBlock("menuCalendario");
        $tpl->newBlock("actividadCalendario");
            $tpl->assign("nombreActividad", 'Semestre');
            $tpl->assign("fechaInicio", $resultadoCalendario[inicioperiodo]);
            $tpl->assign("fechaFin", $resultadoCalendario[finalperiodo]);
           
        $tpl->newBlock("actividadCalendario");
            $tpl->assign("nombreActividad", 'Primer Parcial');
            $tpl->assign("fechaInicio", $resultadoCalendario[inicioprimerparcial]);
            $tpl->assign("fechaFin", $resultadoCalendario[finalprimerparcial]);
            
        $tpl->newBlock("actividadCalendario");
            $tpl->assign("nombreActividad", 'Segundo Parcial');
            $tpl->assign("fechaInicio", $resultadoCalendario[iniciosegundoparcial]);
            $tpl->assign("fechaFin", $resultadoCalendario[finalsegundoparcial]);
            
        $tpl->newBlock("actividadCalendario");
            $tpl->assign("nombreActividad", 'Examenes Finales');
            $tpl->assign("fechaInicio", $resultadoCalendario[iniciofinales]);
            $tpl->assign("fechaFin", $resultadoCalendario[finalfinales]);
        
        if($resultadoCalendario[primeraretrasada]!=null){
        $tpl->newBlock("actividadCalendario");
            $tpl->assign("nombreActividad", 'Primera Retrasada');
            $tpl->assign("colspan","colspan='2' align='center'");
            $tpl->assign("fechaInicio", $resultadoCalendario[primeraretrasada]);                       
        }
        
        if($resultadoCalendario[segundaretrasada]!=null){
            $tpl->newBlock("actividadCalendario");
            $tpl->assign("nombreActividad", 'Segunda Retrasada');
            $tpl->assign("colspan","colspan='2' align='center'");
            $tpl->assign("fechaInicio", $resultadoCalendario[segundaretrasada]);
            
        }
        
    }
   
}
/*******************************************************************************  FIN DEFINICION FUNCIONES METODOS*/
/*agregar menu calendario*/

function OpcionUno_ProvenienteListadoCursos($tpl){
    global $noActividades;
    global $manejoCadena;
    global $noActividades;
    global $ponderacionActividades;
    $_SESSION['notaReposicion']=false; // variable que gestiona si existe o no nota de reposicion (parcial)
    
    $_SESSION["idcurso"] = $_GET["curso"];
        $_SESSION["index"]=$_GET["index"];
        $_SESSION["carrera"] = $_GET["carrera"];
        $_SESSION["docentes"]=$_GET['docentes'];
        $_SESSION["nombreCurso"] = retornarNombreCurso($_SESSION["idcurso"], $_SESSION["index"]);
        
        crearMenuCalendario($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], $_SESSION["sAnio"], $tpl);
        
        //----------------------------------- TEMPLATE POWER
        $tpl->gotoBlock('_ROOT');
        /*encabezado*/
        $tpl->assign("vCurso", $_SESSION["idcurso"]);
        $tpl->assign("vNombre", $_SESSION["nombreCurso"]);
        $tpl->assign("tituloSeccion", "INGRESO DE NOTAS DE ACTIVIDADES");
        $tpl->assign("vCarrera", $manejoCadena->StringCarrera('0' . $_SESSION["carrera"]));
        $tpl->assign("vPeriodo", $manejoCadena->funTextoPeriodo($_SESSION["sPeriodo"]));
        $tpl->assign("vAnio", $_SESSION["sAnio"]);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));
              
        $retornoDefault = agregarActividadesDefault($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], $_SESSION["sAnio"], $_SESSION["index"]);
        
        if ($retornoDefault != null) {
            if (strpos($retornoDefault, 'error') !== false || strpos($retornoDefault, 'Error') !== false) {
                $_elMensaje .= '<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Actividades por Defecto </h4>' . $retornoDefault . '</div>';
            } else {
                $_elMensaje .= '<div class="alert alert-success"><h4><i class="fa fa-info-circle fa-lg"></i> Actividades por Defecto </h4>' . $retornoDefault . '</div>';
            }
            $tpl->newBlock("mensaje");
            $tpl->assign("mensaje", $_elMensaje);
        }

    $tpl->gotoBlock('_ROOT');
        /*cuerpo la seccion*/        
        
        $noActividades=0; // variable global que mantiene cuantas actividades existen.

//MENU TEMPORAL
        //$noActividades=0;
        $tpl->gotoBlock('_ROOT');
        $tpl->newBlock("docenciaTmp");
        $tpl->assign("nombreDocencia", "TEORICA");
        $tpl->assign("iconoDocencia", "fa fa-pencil-square-o");
        $tpl->assign("vermasDocencia","../notas-actividades/crearActividad.php?opcion=2&docencia=TEORICA");
        $ponderacionActividades=0.0;
        crearVistaActividadesTmp($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"],CLASE_MAGISTRAL,null,$tpl);
        $tpl->gotoBlock("docenciaTmp");
        $tpl->assign("noActividadesDocencia",$noActividades);
        
        $noActividadestmp=$noActividades;
        $ponderacionActividadtmp = $ponderacionActividades;
        
        $tpl->gotoBlock('_ROOT');
        $tpl->newBlock("docenciaTmp");
        $tpl->assign("nombreDocencia", "PRACTICA");
        $tpl->assign("iconoDocencia", "fa fa-flask");
        $tpl->assign("vermasDocencia","../notas-actividades/crearActividad.php?opcion=2&docencia=PRACTICA");
        crearVistaActividadesTmp($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"],LABORATORIO,null,$tpl);
        $tpl->gotoBlock("docenciaTmp");
        $tpl->assign("noActividadesDocencia",$noActividades-$noActividadestmp);
        
        $paginaListadoCursos = "../notas-actividades/listadoNotas.php?opcion=1&txtCurso=".$_SESSION["idcurso"].""
                . "&txtLaSeccion=".$_SESSION["carrera"]."&txtAnio=".$_SESSION["sAnio"].""
                . "&txtPeriodo=".$_SESSION["sPeriodo"]."&txtRegPer=".$_SESSION['regper'].""
                . "&txtCarrera=".$_SESSION["carrera"];
        $tpl->newBlock('enlaces');
        $tpl->assign("nuevaactividad", '<a id="nuevaactividad" href="../notas-actividades/crearActividad.php?opcion=3" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-plus fa-lg"></i><span>&nbsp;&nbsp;Agregar Actividad</span></a>');
        $tpl->assign("cargarNotas",'<a id="editarNotas" href="../notas-actividades/crearActividad.php?opcion=4" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-hand-o-up fa-lg"></i><span>&nbsp;&nbsp;Editar Notas</span></a>');
        $tpl->assign("listadoNotas",'<a id="editarNotas" href="'.$paginaListadoCursos.'" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-hand-o-up fa-lg"></i><span>&nbsp;&nbsp;Listado de Notas</span></a>');
                
        $tpl->gotoBlock('_ROOT');
        $tpl->newBlock("resumenactividades");
        $tpl->assign("NumeroActividadesClaseMagistral",$noActividadestmp);
        $tpl->assign("PonderacionClaseMagistral",$ponderacionActividadtmp);
        
        $tpl->assign("NumeroActividadesLaboratorio",$noActividades-$noActividadestmp);
        $tpl->assign("PonderacionLaboratorio",$ponderacionActividades-$ponderacionActividadtmp);
        
        $tpl->assign("TotalNumero",$noActividades);
        $tpl->assign("TotalPonderacion",$ponderacionActividades);
        
        $tpl->gotoBlock('_ROOT');
        
        //$zonaMaxima = (esModular($_SESSION["index"], $_SESSION["idcurso"], $_SESSION["carrera"])) ? ZONA_MODULAR :  ZONA_INTROBASICVACAS;
                
        $zonaMaxima =0;
        $parciales = 0;
        if( $_SESSION["idcurso"]==id_EPS){
            $zonaMaxima=ZONA_EPS;
        }else if(esModular($_SESSION["index"], $_SESSION["idcurso"], $_SESSION["carrera"])){
            $zonaMaxima=ZONA_MODULAR;
            $parciales=PARCIAL_MODULAR;
        }else
        {
            $zonaMaxima=ZONA_INTROBASICVACAS;
            $parciales=PARCIAL_INTROBASICVACAS;
        }
        
               
        $_SESSION["totalZonaActual"] = array($ponderacionActividadtmp,
            $ponderacionActividades - $ponderacionActividadtmp, $ponderacionActividades);
        
        
        if($zonaMaxima==$_SESSION["totalZonaActual"][2]){
         $tpl->newBlock("alertZona");
            $tpl->assign("tipo", "alert alert-success");
            $tpl->assign("mensaje", "Zona de Curso");
            if ($_SESSION['notaReposicion']==true) {
                $tpl->assign("mensajeStrong", "Completa con Parcial de Reposicion");
            } else {
                $tpl->assign("mensajeStrong", "Completa");
            }
        }else if($zonaMaxima>$_SESSION["totalZonaActual"][2]){
            $tpl->newBlock("alertZona");
            $tpl->assign("tipo","alert alert-info");
            $tpl->assign("mensajeStrong","Incompleta faltan: [".($zonaMaxima-$_SESSION["totalZonaActual"][2])."] pts");
            $tpl->assign("mensaje","Zona de Curso");
        }

}

function OpcionCuatro_editar_SubirNotasView($tpl){
    
    global $manejoCadena;
    global $controladorCrearActividad;
    global $bd;
    
      //----------------------------------- TEMPLATE POWER
        $tpl->gotoBlock('_ROOT');
        /*encabezado*/
        $tpl->assign("vCurso", $_SESSION["idcurso"]);
        $tpl->assign("vNombre", $_SESSION["nombreCurso"]);
        $tpl->assign("tituloSeccion", "INGRESO DE NOTAS DE ACTIVIDADES");
        $tpl->assign("vCarrera", $manejoCadena->StringCarrera('0' . $_SESSION["carrera"]));
        $tpl->assign("vPeriodo", $manejoCadena->funTextoPeriodo($_SESSION["sPeriodo"]));
        $tpl->assign("vAnio", $_SESSION["sAnio"]);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));
        /*body seccion*/
        $tpl->newBlock("IngresoNotas");
        $tpl->newBlock("frozen");
            $tpl->assign("idFrozen","carnet");
            $tpl->assign("nombreFrozen","Carnet<br>");
        $tpl->newBlock("frozen");
            $tpl->assign("idFrozen","nombre");
            $tpl->assign("nombreFrozen","Nombre<br>");
        $tpl->newBlock("frozen");
            $tpl->assign("idFrozen","cui");
            $tpl->assign("nombreFrozen","CUI<br>");
            
            //$Curso,$Periodo, $Carrera, $Anio
        $Actividades = $controladorCrearActividad->queryDistinctNombreActividad($_SESSION["idcurso"],$_SESSION["sPeriodo"],$_SESSION["carrera"],$_SESSION["sAnio"]);
        $bd->query($Actividades);
        while (($bd->next_record()) != null) {
            $FilaTipoActividad = $bd->r();
            $tpl->newBlock("nofrozen");
                $tpl->assign(rowNoFrozen,"<th data-options=\"field:'$FilaTipoActividad[idactividad]',width:100,align:'right',editor:{type:'numberbox',options:{precision:2}}\">$FilaTipoActividad[nombre] <br>($FilaTipoActividad[ponderacion] pts)</th>");                
        }// del while reocorre ACTIVIDADES
        
        // enlaces
        $tpl->gotoBlock('_ROOT');
        $tpl->assign("RegresarActividades", '<a href="../notas-actividades/crearActividad.php?opcion=1&curso='.$_SESSION["idcurso"].'&index='.$_SESSION["index"].'&carrera='.$_SESSION["carrera"].'&docentes='.$_SESSION["docentes"].'"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de actividades" ></a>');
        
}

function OpcionDos_Vermas($tpl){
    global $manejoCadena;
    global $controladorCrearActividad;
    global $bd;
           $tituloSeccion = $_GET['docencia'];
        /*----- Encabezado*/
        $tpl->gotoBlock('_ROOT');        
        $tpl->assign("vCurso", $_SESSION["idcurso"]);        
        $tpl->assign("vNombre", $_SESSION["nombreCurso"]);
        $tpl->assign("tituloSeccion", "DETALLE DE DOCENCIA: $tituloSeccion");
        $tpl->assign("vCarrera", $manejoCadena->StringCarrera('0' . $_SESSION["carrera"]));
        $tpl->assign("vPeriodo", $manejoCadena->funTextoPeriodo($_SESSION["sPeriodo"]));
        $tpl->assign("vAnio", $_SESSION["sAnio"]);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));
        /*----- Cuerpo de Seccion*/
        $tpl->newBlock("tabDetalle");
        
        
        $noActividades=0;
        $docencia =2;
        if(strpos($tituloSeccion, 'TEORICA') !== false){
            $docencia=1;
        }
        ///----------------------------- PARCIALES
        if(tieneActividadesCategoria($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"], $docencia, PARCIALES)){
            $tpl->newBlock("nuevaTab");
            $tpl->assign("tituloTab","EXAMENES PARCIALES");
             $tpl->newBlock("tablaContenidoTab");
                $tpl->assign("noColumnas",6);                
                crearVistaTabDetalle($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                        $_SESSION["sAnio"], $docencia, PARCIALES, $tpl, null);
                //crearVistaTabDetalle($tpl);
                    }
     
        ///----------------------------- CORTOS
         if(tieneActividadesCategoria($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"], $docencia, EXAMENES_CORTOS)){
            $tpl->newBlock("nuevaTab");
            $tpl->assign("tituloTab","EXAMENES CORTOS");
             $tpl->newBlock("tablaContenidoTab");
                $tpl->assign("noColumnas",6);
                crearVistaTabDetalle($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                        $_SESSION["sAnio"], $docencia, EXAMENES_CORTOS, $tpl,null);
                    }
        ///----------------------------- TAREAS
         if(tieneActividadesCategoria($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"], $docencia, TAREAS)){            
        $tpl->newBlock("nuevaTab");
            $tpl->assign("tituloTab","TAREAS");
             $tpl->newBlock("tablaContenidoTab");
                $tpl->assign("noColumnas",6);
                crearVistaTabDetalle($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                        $_SESSION["sAnio"], $docencia, TAREAS, $tpl,null);
                    }
        ///----------------------------- PRACTICAS
        if(tieneActividadesCategoria($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"], $docencia, PRAC_INTEGRADAS)){ 
        $tpl->newBlock("nuevaTab");
            $tpl->assign("tituloTab","PRACTICAS");
             $tpl->newBlock("tablaContenidoTab");
                $tpl->assign("noColumnas",6);
                crearVistaTabDetalle($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                        $_SESSION["sAnio"], $docencia, PRAC_INTEGRADAS, $tpl,null);
                    }
                
        ///----------------------------- PROYECTS
        if(tieneActividadesCategoria($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                    $_SESSION["sAnio"], $docencia, PROYECTO_TRABAJO)){ 
        $tpl->newBlock("nuevaTab");
            $tpl->assign("tituloTab","PROYECTOS");
             $tpl->newBlock("tablaContenidoTab");
                $tpl->assign("noColumnas",6);
                crearVistaTabDetalle($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                        $_SESSION["sAnio"], $docencia, PROYECTO_TRABAJO, $tpl,null);
        }
        
        //------------------------------ todas las actividades que no sean las anteriores.
        $unionTodasActividades = PARCIALES.",".EXAMENES_CORTOS.",".TAREAS.",".PRAC_INTEGRADAS.",".PROYECTO_TRABAJO;
        $tpl->newBlock("nuevaTab");
            $tpl->assign("tituloTab","OTRAS ACTIVIDADES");
             $tpl->newBlock("tablaContenidoTab");
                $tpl->assign("noColumnas",6);
                crearVistaTabDetalle($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], 
                        $_SESSION["sAnio"], $docencia, $unionTodasActividades, $tpl,"all");
        
        // enlaces
        $tpl->gotoBlock('_ROOT');
        $tpl->assign("RegresarActividades", '<a href="../notas-actividades/crearActividad.php?opcion=1&curso='.$_SESSION["idcurso"].'&index='.$_SESSION["index"].'&carrera='.$_SESSION["carrera"].'&docentes='.$_SESSION["docentes"].'"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de actividades" ></a>');
       
    
}


function ImprimeFecha($fecha)
{
    list($anio, $mes, $dia) = explode("-", $fecha);
    return $dia . "/" . $mes . "/" . $anio;
}

function OpcionTresCinco_crearVistaNuevaEditarActividad($tpl,$idActividad){
    global $bd;
    global $manejoCadena;
    global $controladorCrearActividad;
    $tpl->gotoBlock('_ROOT');
        /*encabezado*/
        $tpl->assign("vCurso", $_SESSION["idcurso"]);
        $tpl->assign("vNombre", $_SESSION["nombreCurso"]);
        $tpl->assign("tituloSeccion", "AGREGAR O EDITAR UNA ACTIVIDAD");
        $tpl->assign("vCarrera", $manejoCadena->StringCarrera('0' . $_SESSION["carrera"]));
        $tpl->assign("vPeriodo", $manejoCadena->funTextoPeriodo($_SESSION["sPeriodo"]));
        $tpl->assign("vAnio", $_SESSION["sAnio"]);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));
        /*cuerpo seccion*/
        $tpl->newBlock("crearEditarActividad");
        
        $ResultadoActividad=null; // unicamente si $idActividad !=null
        if($idActividad!=null){
            $sqlActividad=$controladorCrearActividad->queryGetActividad($idActividad);
            $bd->query($sqlActividad);
            $bd->next_record();
            $ResultadoActividad =$bd->r();
        }else{
            $tpl->assign("txtPonderacionAnterior",0);
        }
        
        if($ResultadoActividad!=null){
            //$tpl->assign("error",$ResultadoActividad[idactividad].'asdfasdfasdf');
            $tpl->assign("txtIdActividad", $ResultadoActividad[idactividad]);
            $tpl->assign("txtNombreActividad", $ResultadoActividad[nombre]);
            $tpl->assign("txtFechaRealizar", ImprimeFecha($ResultadoActividad[fechaentrega]));
            $tpl->assign("txtPonderacion",$ResultadoActividad[ponderacion] );//number_format($FilaDato[ponderacion]));
            $tpl->assign("txtPonderacionAnterior",$ResultadoActividad[ponderacion]);
            switch ($ResultadoActividad[scheduletype]) {
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
            $tpl->assign("txtPerteneceA", $ResultadoActividad[scheduletype]);
        }
        
        $sqlTipoActividad = $controladorCrearActividad->queryTipoActividad();
        $bd->query($sqlTipoActividad);
        $tpl->newblock("tipoactividad");
        
        $tpl->newblock("opciontipoactividad");
        $tpl->assign("valoropciontipoactividad", "0");
        $tpl->assign("nombreopciontipoactividad", "Seleccione un tipo");

        while (($bd->next_record()) != null) {
            $FilaTipoActividad = $bd->r();
            $tpl->newblock("opciontipoactividad");
            $tpl->assign("valoropciontipoactividad", $FilaTipoActividad[idtipoactividad]);
            $tpl->assign("nombreopciontipoactividad", $FilaTipoActividad[nombre]);
            if ($ResultadoActividad != null) {
                if ($ResultadoActividad[tipo] == $FilaTipoActividad[idtipoactividad]) {
                    $tpl->assign("txtSeleccionado", "selected");
                }
            }
        }// del while reocorre tipoactividad
        
    // enlaces
        $tpl->gotoBlock('_ROOT');
        $tpl->assign("RegresarActividades", '<a href="../notas-actividades/crearActividad.php?opcion=1&curso='.$_SESSION["idcurso"].'&index='.$_SESSION["index"].'&carrera='.$_SESSION["carrera"].'&docentes='.$_SESSION["docentes"].'"><input type="button" name="btnRegresar" id="btnRegresar" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de actividades" ></a>');
        
        
        $zonaMaxima =0;
        $parciales = 0;
        if($_SESSION["idcurso"]== id_EPS){
            $zonaMaxima=ZONA_EPS;
        }else if(esModular($_SESSION["index"], $_SESSION["idcurso"], $_SESSION["carrera"])){
            $zonaMaxima=ZONA_MODULAR;
            $parciales=PARCIAL_MODULAR;
        }else
        {
            $zonaMaxima=ZONA_INTROBASICVACAS;
            $parciales=PARCIAL_INTROBASICVACAS;
        }
      
    if ($zonaMaxima == $_SESSION["totalZonaActual"][2]) {
        $tpl->newBlock("alertZona");
        $tpl->assign("tipo", "alert alert-success");
        $tpl->assign("mensaje", "Zona de Curso");
        if ($_SESSION['notaReposicion']) {
            $tpl->assign("mensajeStrong", "Completa con Parcial de Reposicion");
        } else {
            $tpl->assign("mensajeStrong", "Completa");
            if ($ResultadoActividad == null) {
                $tpl->newBlock('enlaces');
                $tpl->assign("grabarActividad", '<a id="btnEnviar" href="javascript:void(0);" onclick="crearActividad(false);" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-database fa-lg"></i><span>&nbsp;&nbsp;Grabar Actividad  </span></a>');
            }
        }
    } else if ($zonaMaxima > $_SESSION["totalZonaActual"][2]) {
        $tpl->newBlock("alertZona");
        $tpl->assign("tipo", "alert alert-info");
        $tpl->assign("mensajeStrong", "Incompleta faltan: [" . ($zonaMaxima - $_SESSION["totalZonaActual"][2]) . "] pts");
        $tpl->assign("mensaje", "Zona de Curso");
        if ($ResultadoActividad == null) {
            $tpl->newBlock('enlaces');
            $tpl->assign("grabarActividad", '<a id="btnEnviar" href="javascript:void(0);" onclick="crearActividad(false);" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-database fa-lg"></i><span>&nbsp;&nbsp;Grabar Actividad   </span></a>');
        }
    }

    if ($ResultadoActividad != null) {
        $tpl->newBlock('enlaces');
        $tpl->assign("grabarActividad", '<a id="btnEnviar" href="javascript:void(0);" onclick="crearActividad(true);" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;"><i class="fa fa-floppy-o fa-lg"></i><span>&nbsp;&nbsp;Guardar cambios   </span></a>');
    }
}

/*******************************************************************************  INICIO LOGICA DEL SISTEMA*/
switch ($opcion){
    case 1: // Proveniende del listado de cursos. D_CourseList.php             
        //verificar --- la habilitacion del sistema
        OpcionUno_ProvenienteListadoCursos($tpl);
        break;
    case 2: // Proveniente del "ver mas" en la presentacion de actividades; muestra el detalle de las actividades.
        OpcionDos_Vermas($tpl);
        break;
    case 3: //  Proveniente de crear NUEVAACTIVIDAD
        crearMenuCalendario($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], $_SESSION["sAnio"], $tpl);
        //----------------------------------- TEMPLATE POWER
        OpcionTresCinco_crearVistaNuevaEditarActividad($tpl,null);        
        break;
    case 4: // Proveniente de editar o subir NOTAS.
       OpcionCuatro_editar_SubirNotasView($tpl); 
       break;
    case 5:
        crearMenuCalendario($_SESSION["idcurso"], $_SESSION["sPeriodo"], $_SESSION["carrera"], $_SESSION["sAnio"], $tpl);
        //----------------------------------- TEMPLATE POWER        
        OpcionTresCinco_crearVistaNuevaEditarActividad($tpl,$_GET['idActividad']);        
        break;
}


$tpl->printToScreen();
unset($tpl);

