<?
include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
require_once("$dir_portal/fw/model/sql/conectar_SQL.php");

global $gsql_na_c;
$gsql_na_c = new conectar_SQL();

// **************************************************
// funcion que permite la conexion a la base de datos
// **************************************************
//  Se realiza una conexion con Servidor de Base de datos
define ("_GRUPO_DOCENTE", 1);

function sesionAunActiva($bd) {
  global $gsql_na_c;
  if (isset($_SESSION['regper'])) {
    if ($_SESSION["regper"]!="")
      return true;
//    $qryUsuario="select * from conexion where sesionid='" . session_id() . "' and grupo=" . _GRUPO_DOCENTE . " and fecha>='" . date("Y") . 
//	            "-" . date("m") . "-" . date("d") . " " . (date("H")-1) . ":" . date("i"). ":" . date("s") . ".000000000'";
	            
    $qryUsuario= $gsql_na_c->sesionAunActiva_select1(_GRUPO_DOCENTE);
	            
    $bd->query($qryUsuario); 
    $total=$bd->num_rows();
    if ($total) {
      $regUsuario=$bd->next_record();
	  $regUsuario=$bd->r();				  
      $_SESSION["regper"]=trim($regUsuario["iduser"]);
      return true;
      }
	else
	  return false;
	}
  else
    return false;
  }

function sistemaHabilitado($bd,$txtPeriodo,$txtAnio,$txtCurso,$txtSeccion,$txtRegPer) {
    global $gsql_na_c;
//  $sqlbusca="select * from  ing_calendarioactividades where periodo='". $txtPeriodo ."' and anio=" . $txtAnio;  
  $sqlbusca= $gsql_na_c->sistemaHabilitado_select1($txtPeriodo,$txtAnio,$txtCurso,$txtSeccion);

  $bd->query($sqlbusca);
  if($bd->num_rows()>0) {
    $regCalendario=$bd->next_record();
	$regCalendario=$bd->r();				  
    $fechaInicio = trim($regCalendario["inicioperiodo"]);
    $fechaFin    = trim($regCalendario["finalperiodo"]);
    $fechahoy    = date("Y")."-".date("m")."-".date("d");
    if (($fechahoy >= $fechaInicio) && ($fechahoy <= $fechaFin))
      return 100; //Sistema habilitado y aún en fecha para el ingreso de actividades
    else { //Sistema habilitado pero fuera de fecha para el ingreso de actividades
//      $sqlbusca="select distinct pertenecea from ing_actividad where activo=1 and periodo='" . $txtPeriodo . "' and anio=". $txtAnio .
//	            " and regper='" . $txtRegPer . "' and curso='" . $txtCurso . "' and seccion='" . $txtSeccion . "' order by pertenecea";
      
      $sqlbusca = $gsql_na_c->sistemaHabilitado_select2($txtPeriodo, $txtAnio, $txtRegPer, $txtCurso, $txtSeccion);
      
      $bd->query($sqlbusca);
      if($bd->num_rows()>0) {
        $resultado=$bd->next_record();
	    $resultado=$bd->r();				  
        $_pertenecea=$resultado["pertenecea"];
        if ($_pertenecea==2) {
//          $sqlbusca="select * from ing_notasactividad na, ing_actividad a where a.activo=1 and a.periodo=na.periodo" .
//		            " and a.curso=na.curso and a.anio=na.anio and a.seccion=na.seccionactividad[a.posicion] and na.curso='". $txtCurso .
//					"' and na.seccionactividad[a.posicion]='" . $txtSeccion . "' and na.periodo='" . $txtPeriodo .
//					"' and na.anio=" . $txtAnio . " and a.regper='" . $txtRegPer . "'";		  
          
          $sqlbusca = $gsql_na_c->sistemaHabilitado_select3($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtRegPer);
          
		  }
        else {
//          $sqlbusca="select * from ing_notasactividad where periodo='" . $txtPeriodo . "' and anio=". $txtAnio .
//	                " and curso='" . $txtCurso . "' and seccion='" . $txtSeccion . "'";
          
          $sqlbusca = $gsql_na_c->sistemaHabilitado_select4($txtPeriodo, $txtAnio, $txtCurso, $txtSeccion);
          
		  }
        $bd->query($sqlbusca);
        if ($bd->num_rows()>0)
	      return 2; //Existe información de actividades procesadas
	    else
	      return 3; //Sin información de actividades procesadas
	    }
	  else {
	    return 3; //Sin informaciÓn de actividades procesadas
	    }
	  }
    }
  else
    return 1; // Calendario de actividades sin registrar en BDD aún

  }

function conectarBase()
{	
	$base = NEW DB_Connection;
	$base->connect();
return $base;
}


$bd = conectarBase();

  if($_verificarSesion===true && sesionAunActiva($bd)===false) {
    header("Location:../LogOut.php");
    die;
    }

?>
