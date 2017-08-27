<?php
// *************************
//    Patron de pagina
//    Erick Suy 2017
// ***********************


include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/sql/creaactividad_SQL.php");
require "conectar.php"; /* archivo que maneja la conexion y la variable BD*/
global $bd;
global $controladorCrearActividad;
session_start(); // inicializar el contexto para manejo de variables de Sesion,
                 // todas las variables de sesion ya se encuentran asignadas ya que provienen 
                 // de crearActividad.php
$controladorCrearActividad = new creaactividad_SQL();


if($_SERVER['REQUEST_METHOD']=="POST") {
    $function = $_POST['llamar'];
    if(function_exists($function)) {
        call_user_func($function);
    } else {
        //$data=array('error' => 'Funcion no encontrada');
        echo 'ERROR';
    }
}else if($_SERVER['REQUEST_METHOD']=="GET"){
    $function = $_GET['llamar'];
    if(function_exists($function)) {
        call_user_func($function);
    } else {
        //$data=array('error' => 'Funcion no encontrada');
        echo 'ERROR';
    } 
}

function updateNotas(){
    global $bd;
    // el formato que maneja Javascript concatena \" cuando simplemente es "
    //          - {\" dato\": \"dato 1\" }
    // el primer json_decode --> elimina este problema
    //          - {"dato": "dato 1" }
    // el segundo json_decode --> decodifica y vuelve en array el formato json.
    $notasCambios = json_decode($_POST['cambios'],true);
    $notasCambios = json_decode($notasCambios,true);
    $resultado=""; //retorno a javascript
    foreach ($notasCambios as $estudiante) {
       $idActividades = array_keys($estudiante);
       $actividades="";
       foreach($idActividades as $act){// recorrer las actividades del formato json           
           $actTemp = (string)$act;
           $actividades.=$actTemp.",";  
       }
       $posIndex=strpos($actividades,"carnet");
       if($posIndex>0){
           $actividades=substr($actividades,0,($posIndex-1));
       }
       
       $notas="";
       foreach($estudiante as $nota){  //recorrer las notas del formato json         
           $notas.=(string)$nota.","; 
       }       
       $posIndex=strripos($notas, ",");
       $notas=substr($notas,0,($posIndex-1)); // ultima coma(,)
       $posIndex=strripos($notas, ",");
       $notas=substr($notas,0,($posIndex)); //quitar el carnet ,
       //print_r($notas);
       $carnet = $estudiante[carnet];
       
       //variables que se tienen que ir a storeprocedure para actualizar las notas,
       $actividades; // el cual tiene el siguiente formato 1,2,3,5,4,6,(idactividad de cada actividad)
       $notas; // el cual tiene el siguiente formato 10.00,2.00,56.00,(notaObtenida en cada actividad)
       $reg = $_SESSION['regper'];
       $goup = $_SESSION['group'];
       $query="select * from actualizarNotasActividad($reg,$goup,'$actividades','$notas',$carnet);";
       $bd->query($query);
       
       while (($bd->next_record()) != null) {
           $InsertResult = $bd->r();
           $resultado.=$InsertResult[0];
       }
    }
    echo $resultado;
}


//********************************************************************* METODOS PARA AGREGAR ACTIVIDAD

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
        if(($Resultado['resultado'] + 0)==true) {
            $resultado = true;
        }
    }
    return $resultado;
}


function agregarParcialReposicion(){
    //Esta funcon agrega no importanto si la zona este completa o no unicamente valida lo siguinte
    //    -- que hayan ingresado PRIMER PARCIAL, SEGUNDO PARCIAL
    //    -- que tipo de Curso es introductorio basico | modular

    global $bd;
    global $controladorCrearActividad;

    $curso = $_SESSION["idcurso"];
    $anio = $_SESSION["sAnio"];
    $periodo = $_SESSION["sPeriodo"];
    $carrera = $_SESSION["carrera"];
    $index = $_SESSION["index"];




    $nombre = $_POST['txtNombreActividad'];
    //echo $nombre;
    $crearla = $_POST['crearla']; // true|false 
    //true: cuando aun sabiendo que hay actividades
    // dentro de esa fecha decide el usuario crearla.
    // false: cuando no se sabe si hay o no actividades dentro de esa fecha.
    $_esActualizacion = $_POST['actualizacion']; // true|false cuando es una actualizacion de la actividad.
    $esActualizacion = ($_esActualizacion === 'true'); // para comparar con php hay que castear el valor a 
    //un valor booleano, en el lado de la bd no es 
    //importante este casteo el dbms lo maneja.
    $idActividaActualizacion = $_POST['txtIdActividad']; //unicamente se toma cuando se va actulizar la actividad
    $notaAnterior = $_POST['ponderacionAnterior'];
    $fecha = $_POST['txtFechaRealizar'];
    $ponderacion = $_POST['txtPonderacion'];
    $docencia = $_POST['txtPerteneceA'];
    $tipo = $_POST['txtTipoActividad'];
    $archivo = "";
    $regPer = $_SESSION['regper'];
    $docentes = $_SESSION['docentes'];

    if (strlen(trim($docentes)) > 0) {
        $docentes = $regPer . ',' . $docentes;
    } else {
        $docentes = $regPer;
    }
    $docentes = "{" . $docentes . "}"; //para darle formato de postgresql ejemplo.{12,35,65}


    $notaParcial = (esModular($index, $curso, $carrera)) ? PARCIAL_MODULAR : PARCIAL_INTROBASICVACAS;

    $queryValidar = $controladorCrearActividad->queryGetParciales($curso, $carrera, $periodo, $anio);
    $bd->query($queryValidar);
    if ($bd->num_rows() >= 2) {
        $sqlCalendarioActividad = $controladorCrearActividad->queryCalendarioActividades($curso, $periodo, $carrera, $anio);
        $bd->query($sqlCalendarioActividad);
        $resultadoCalendario = (($bd->next_record()) != null) ? $bd->r() : null;

        if ($resultadoCalendario != null && $esActualizacion == false) {
            $bd->query($controladorCrearActividad->begin()); //iniciar transaccion
            $ERROR = 0;
            $ERROR+= insertarActividad($curso, $anio, $periodo, $carrera,
                    $resultadoCalendario[inicioperiodo], "REPOSICION PARCIAL", 5, $resultadoCalendario[finalperiodo], $notaParcial, 1, OK);

            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $curso, $periodo, $carrera, $anio);

            if ($ERROR == 0) {
                $bd->query($controladorCrearActividad->commit());
                $ManejoErrores = "Creacion de Parcial Reposicion con Exito.";
            } else {
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al ingresar actividad solicite apoyo Unidad Virtual FMVZ.";
            }
            $bd->query($controladorCrearActividad->end());
            echo $ManejoErrores;
            return;
        } else if ($resultadoCalendario != null && $esActualizacion == true) {
            $bd->query($controladorCrearActividad->begin()); //iniciar transaccion
            $ERROR = 0;
            $ERROR+=updateActividad($idActividaActualizacion, $nombre, $tipo, cambiarFormatoFecha($fecha), $ponderacion, $docencia);
            if ($ERROR == 0) {
                $bd->query($controladorCrearActividad->commit());
                $ManejoErrores = "Actualizacion con exito.";
            } else {
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al editar actividad solicite apoyo Unidad Virtual FMVZ.";
            }
            $bd->query($controladorCrearActividad->end());
            echo $ManejoErrores;
            return;
        }
    }else if ($bd->num_rows()==3){
        echo 'Ya tiene ingresado un Parcial de Reposicion';
        return;
        
    }
        echo "Para incluir un Parcial de Reposicion, debe agregar primero un Primer Parcial y a continuacion Segundo Parcial";
        return;    
    }

function agregarActividad(){
    // esta funcion agrega una nueva actividad a la base de datos, las validaciones se hacen a nivel
    // de base de datos con procedimientos almacenados
            //                  -- ver validaractividadparciales
            //                  -- ver validaractividad    
    
    global $bd;
    global $controladorCrearActividad;
    
    $curso=$_SESSION["idcurso"];
    $anio=$_SESSION["sAnio"];
    $periodo=$_SESSION["sPeriodo"];
    $carrera=$_SESSION["carrera"];
    $index=$_SESSION["index"];
           
    
    
    
    $nombre = $_POST['txtNombreActividad'];
    $nombre = strtoupper($nombre);
    //echo $nombre;
    $crearla = $_POST['crearla']; // true|false 
                                  //true: cuando aun sabiendo que hay actividades
                                  // dentro de esa fecha decide el usuario crearla.
                                  // false: cuando no se sabe si hay o no actividades dentro de esa fecha.
    $_esActualizacion =$_POST['actualizacion'];// true|false cuando es una actualizacion de la actividad.
    $esActualizacion = ($_esActualizacion === 'true'); // para comparar con php hay que castear el valor a 
                                                       //un valor booleano, en el lado de la bd no es 
                                                       //importante este casteo el dbms lo maneja.
    $idActividaActualizacion=$_POST['txtIdActividad']; //unicamente se toma cuando se va actulizar la actividad
    $notaAnterior=$_POST['ponderacionAnterior'];
    $fecha = $_POST['txtFechaRealizar'];
    $ponderacion = $_POST['txtPonderacion'];
    $docencia = $_POST['txtPerteneceA'];
    $tipo = $_POST['txtTipoActividad'];
    $archivo = "";
    $regPer = $_SESSION['regper'];
    $docentes = $_SESSION['docentes'];

    if (strlen(trim($docentes)) > 0) {
        $docentes = $regPer . ',' . $docentes;
    } else {
        $docentes = $regPer;
    }
    $docentes ="{".$docentes."}";//para darle formato de postgresql ejemplo.{12,35,65}
    
    
    if($curso==id_EPS){
        $zonaMaxima=ZONA_EPS;
    }else
        $zonaMaxima = (esModular($index, $curso, $carrera)) ? ZONA_MODULAR :  ZONA_INTROBASICVACAS;
    
    $queryValidar=$controladorCrearActividad->queryValidarActividad($anio,$periodo,$curso,$carrera, 
            cambiarFormatoFecha($fecha), $crearla, $zonaMaxima, $ponderacion, $tipo, $_esActualizacion,$notaAnterior);
    
    $bd->query($queryValidar);   
    print_r($anio.'  '.$periodo.'  '.$curso.'  '.$carrera.'  '.cambiarFormatoFecha($fecha).'  '.$crearla.'  '.$zonaMaxima.'  '.$ponderacion.'  '.$tipo.'  '. $_esActualizacion.'  '.$notaAnterior);
    while (($bd->next_record()) != null) {
        $InsertResult = $bd->r();
        $resultado.=$InsertResult[0];
    }
       
    //echo $resultado;
    if(strpos($resultado,"ok")>0){
        
        $sqlCalendarioActividad = $controladorCrearActividad->queryCalendarioActividades($curso, $periodo,
                $carrera, $anio);
        $bd->query($sqlCalendarioActividad);
        $resultadoCalendario = (($bd->next_record()) != null) ? $bd->r() : null;
        
        if ($resultadoCalendario != null && $esActualizacion==false) {
            $bd->query($controladorCrearActividad->begin()); //iniciar transaccion
            $ERROR = 0;
            $ERROR+= insertarActividad($curso, $anio, $periodo, $carrera, 
                    $resultadoCalendario[inicioperiodo], $nombre, $tipo, 
            cambiarFormatoFecha($fecha), $ponderacion, $docencia, OK);
                        
            $idActividad = $controladorCrearActividad->getUltimoId($bd, "tbactividad_curso_idactividad_seq");
            $ERROR+=insertarRegPersonalResponsable($idActividad, $regPer, $docentes);
            $ERROR+= asignarEstudianteActividad($idActividad, $curso, $periodo, $carrera, $anio);
            
            if ($ERROR == 0) {
                $bd->query($controladorCrearActividad->commit());
                $ManejoErrores = "Creacion de Actividad con exito.";
            } else {
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al ingresar actividad solicite apoyo Unidad Virtual FMVZ.";
            }
            $bd->query($controladorCrearActividad->end());
            echo $ManejoErrores;
            return;
        }else if ($resultadoCalendario != null && $esActualizacion==true){
            $bd->query($controladorCrearActividad->begin()); //iniciar transaccion
            $ERROR = 0;
            $ERROR+=updateActividad($idActividaActualizacion, $nombre, $tipo, cambiarFormatoFecha($fecha), $ponderacion, $docencia);
            if ($ERROR == 0) {
                $bd->query($controladorCrearActividad->commit());
                $ManejoErrores = "Actualizacion con exito.";
            } else {
                $bd->query($controladorCrearActividad->rollback());
                $ManejoErrores = "Sucedio un error al editar actividad solicite apoyo Unidad Virtual FMVZ.";
            }
            $bd->query($controladorCrearActividad->end());
            echo $ManejoErrores;
            return;
        }else{
            echo "No existe calendario definido para este curso, solicite a control academico la revision de dicho problema";
            return;
        }
    }else{
        echo $resultado;
        return;
        //echo 'no encontre ok'.strpos($ejemp,"ok");
    }


    /*inicio la transaccion para agregar la nueva actividad*/
    
    
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
    return $error;    
}

function updateActividad($idActividad,$nombre, $tipo, $fechaEntrega, $ponderacion, $docencia){
    global $bd;
    global $controladorCrearActividad;
    $queryActividades = $controladorCrearActividad->queryUpdateActividad($idActividad, $nombre, $tipo, $fechaEntrega, $ponderacion, $docencia);
    return (!$bd->query($queryActividades)) ? 1 :  0;
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

function cambiarFormatoFecha($fecha)
{

    list($anio, $mes, $dia) = explode("/", $fecha);
    return $dia . "-" . $mes . "-" . $anio;
}
//************************************************************************** METODO ELIMINAR ACTIVIDAD
function eliminarActividad(){
    global $bd;
    global $controladorCrearActividad;    
    
    $idactividad=$_POST['idactividad'];
    $queryActividad = $controladorCrearActividad->queryGetActividad($idactividad);
    $bd->query($queryActividad);
    $result = (($bd->next_record()) != null) ? $bd->r() : null;
    $idtipoActividad=null;
    if($result!=null){$idtipoActividad=$result[tipo];}
    $noLineas=0;
    if($idtipoActividad!=null){
        $queryActividad= $controladorCrearActividad->queryEsSuperActividad($idtipoActividad);
        $bd->query($queryActividad);        
        $noLineas=$bd->num_rows();
    }
    
    if($noLineas==0){ // si No es superactividad se permite borrar
        $queryActividades = $controladorCrearActividad->queryDeleteActividad($idactividad);
        echo ($bd->query($queryActividades)) ? 1 :  0;
        return;
    }
    echo 0;
    return;
    
}
//************************************************************************** METODOS PARA CARGAR TABLA DE NOTAS

function getNotasJson(){
    /*crea un formato json para ser presentado en la tabla de edicion
      es un datagrid proveniente de la libreria jquery easy ui     */
    global $bd;
    global $controladorCrearActividad;  
    
    $curso=$_SESSION["idcurso"];
    $anio=$_SESSION["sAnio"];
    $periodo=$_SESSION["sPeriodo"];
    $carrera=$_SESSION["carrera"];
    
    $qryCount=$controladorCrearActividad->queryDistinctNombreActividad($curso, $periodo,$carrera,$anio);
    $bd->query($qryCount);   
    $contadorActividades=$bd->num_rows();
    
    $qryEstudiantes = $controladorCrearActividad->queryGetNotas($curso, $periodo,$carrera,$anio);
    $bd->query($qryEstudiantes);
    
    $retornoArray = '';
    $NoEstudiantes=$bd->num_rows();
    $estudiantes=0;
    $x=0;
    if ($NoEstudiantes > 0) {
        while (($bd->next_record()) != null) {
            $Estudiante = $bd->r();
            
            if($x==0){
                $retornoArray.='{ "carnet" : "' . $Estudiante[carnet] . '", "nombre": "'.$Estudiante[name].'  '.$Estudiante[surname].'", "cui" : "'.$Estudiante[dpi].'"';
                $estudiantes++;
            }
            /*$notaObt = $Estudiante[notaobtenida];
            if($notaObt<0){
                $notaObt = '-540555';
            }*/
            $retornoArray.=', "' . $Estudiante[idactividad] . '" : ' . $Estudiante[notaobtenida];
            //$retornoArray.=', "' . $Estudiante[idactividad] . '" : ' . $notaObt;
            
            if(($x+1)>=$contadorActividades){
                $retornoArray.='},';
                $x=0;
            }else{
                $x++;
            }
                      
        }
    }
    $retornoArrayMenosComaUltima = substr($retornoArray,0, (strlen($retornoArray)-1)); 
    echo '{"total":'.$estudiantes.', "rows":['.$retornoArrayMenosComaUltima.']}';
}
