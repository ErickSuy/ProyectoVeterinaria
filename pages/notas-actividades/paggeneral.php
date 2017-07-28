<?php

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/paggeneral_SQL.php");

global $gsql_na_pg;
$gsql_na_pg = new paggeneral_SQL();

//Inicia Sección de Implementación de Funciones

//Definición de variables globales
// actividad determinante
// busca si la actividad ingresada es determinante para botar el laboratorio
function BuscaDeterminante($txtCurso,$txtTipoActividad,$txtPerteneceA)
{
  global $gsql_na_pg;
  $bdAux=ConectarBase();
//  $SqlAux=" select * from ing_actividaddeterminante 
//               where curso='$txtCurso'
//			   and tipoactividad=$txtTipoActividad
//			   ";			   
  $SqlAux= $gsql_na_pg->BuscaDeterminante_select1($txtCurso,$txtTipoActividad);
			   
//  echo "<br>BuscaDeterminante==  $SqlAux";			   
  $ResultadoAux=$bdAux->query($SqlAux);
  $Filas=$bdAux->num_rows();

//  echo "<br>$txtCurso, $txtTipoActividad  FILAS== $Filas";
 return( $Filas);
}
//actividaddeterminante

// funcion que busca si existe actividades con nota ya ganadas en periodo anteriores.
function RecuperaActividadesGanadas($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$bd,$existeSeccionMagistral)
{
  global $gsql_na_pg;
  if (!$existeSeccionMagistral)
    return 1;
 $compAdicional1="";
 $compAdicional2="";

				switch ($txtPeriodo) {
	  				case PRIMER_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio;
	  				
	  				            $compAdicional1= $gsql_na_pg->RecuperaActividadesGanadas_select_1($txtAnio);	  				
//					            $compAdicional2="aniofin>=" . $txtAnio; 
					            
					            $compAdicional2= $gsql_na_pg->RecuperaActividadesGanadas_select_2($txtAnio);
					            
					            break;
	  				case VACACIONES_DEL_PRIMER_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01') and anioinicio=" .
//					                            $txtAnio . ")";
					                            
                           $compAdicional1= $gsql_na_pg->RecuperaActividadesGanadas_select_3($txtAnio);
					                            
//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('02','05','06') and aniofin=" . $txtAnio . ")"; 
					            
					            $compAdicional2= $gsql_na_pg->RecuperaActividadesGanadas_select_4($txtAnio);
					            
					            break;
	  				case SEGUNDO_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01','02') and anioinicio=" .
//					                            $txtAnio . ")";
					                            
                           $compAdicional1= $gsql_na_pg->RecuperaActividadesGanadas_select_5($txtAnio);
					                            
	//				            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('05','06') and aniofin=" . $txtAnio . ")";
					            
					            $compAdicional2= $gsql_na_pg->RecuperaActividadesGanadas_select_6($txtAnio);
					            					            
					            break;
	  				case VACACIONES_DEL_SEGUNDO_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01','02','05') and anioinicio=" .
//					                            $txtAnio . ")";
					            
					            $compAdicional1= $gsql_na_pg->RecuperaActividadesGanadas_select_7($txtAnio);
					            
//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('06') and aniofin=" . 
//					            $txtAnio . ")"; 
					            
					            $compAdicional2= $gsql_na_pg->RecuperaActividadesGanadas_select_8($txtAnio);
					            
								break;
	  				}

//$SqlRecorreActividades=" select a.tipoactividad,posicion 
//						from ing_actividad a,ing_guardaractividadescurso gac
//						where a.curso='$txtCurso'
//						and seccion='$txtSeccion'
//						and periodo='$txtPeriodo'
//						and anio=$txtAnio
//						and a.curso=gac.curso
//						and a.tipoactividad=gac.tipoactividad
//						and activo=1
//						";
						
$SqlRecorreActividades= $gsql_na_pg->RecuperaActividadesGanadas_select1($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio);
						
//		echo "$SqlRecorreActividades<br> ";			
				$bd2 = ConectarBase();
			    if($bd2->query($SqlRecorreActividades)==0) 
				   return 0;
				   
				for($i=1; $i<=$bd2->num_rows();$i++)
				{
   				    $bd2->next_record();
				     $FilaActividad=$bd2->r();
					 $Posicion=$FilaActividad[posicion];
					 $TipoActividad=$FilaActividad[tipoactividad];
//					 $SqlPoneACero=" update ing_notasactividad set notaactividadganada[$Posicion]=0 
//					                 where curso='$txtCurso' and periodo='$txtPeriodo' and anio=$txtAnio and seccion='$txtSeccion'
//					               ";
					               
					 $SqlPoneACero= $gsql_na_pg->RecuperaActividadesGanadas_update1($Posicion,$txtCurso,$txtPeriodo,$txtAnio,$txtSeccion);
					               
//					echo "<br>pone 0s $SqlPoneACero";			   
					if($bd->query($SqlPoneACero)==0)
					   return 0;
					 
					
//					 $SqlActualizaactividades="  update ing_notasactividad
//													set notaactividadganada[$Posicion]=ing_notasactividadesguardadas.nota
//													from ing_notasactividadesguardadas
//													where
//													ing_notasactividad.carnet=ing_notasactividadesguardadas.carnet
//													and ing_notasactividad.curso=ing_notasactividadesguardadas.curso 
//													and ing_notasactividad.periodo='$txtPeriodo'
//													and ing_notasactividad.anio=$txtAnio
//													and ing_notasactividadesguardadas.curso='$txtCurso'
//													and ing_notasactividad.seccion='$txtSeccion'
//													and ing_notasactividadesguardadas.tipoactividad=$TipoActividad 
//													and ($compAdicional1) and ($compAdicional2)
//					                          ";
					                          
					 $SqlActualizaactividades= $gsql_na_pg->RecuperaActividadesGanadas_update2($Posicion,$txtPeriodo,$txtAnio,$txtCurso,$txtSeccion,$TipoActividad,$compAdicional1,$compAdicional2);
					                          
											  
//						echo "<br>$SqlActualizaactividades";					  
					    if($bd->query($SqlActualizaactividades)==0) 
  						     return 0;

				}//del for
return 1;				
} // de la funcion recupera actividadesganadas. . . . 



function RecuperaNotasLaboratorio($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$bd)
{
    global $gsql_na_pg;
//		 $sqlPoneacero="update ing_notasactividad set notaganolaboratorio=0 where curso='$txtCurso' and periodo='$txtPeriodo' and anio=$txtAnio ";
		 
		 $sqlPoneacero= $gsql_na_pg->RecuperaNotasLaboratorio_update1($txtCurso,$txtPeriodo,$txtAnio);		 
				     if($bd->query($sqlPoneacero)==0)
					    return 0;
	   
	                 // actualiza los laboratorios
					 $compAdicional1="";
					 $compAdicional2="";
				switch ($txtPeriodo) {
	  				case PRIMER_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio;
	  				
	  				            $compAdicional1= $gsql_na_pg->RecuperaNotasLaboratorio_select_1($txtAnio);
	  				
//					            $compAdicional2="aniofin>=" . $txtAnio; 
					            
	  				            $compAdicional2= $gsql_na_pg->RecuperaNotasLaboratorio_select_2($txtAnio);
					            
					            break;
	  				case VACACIONES_DEL_PRIMER_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01') and anioinicio=" .
//					                            $txtAnio . ")";
					                            
	  				            $compAdicional1= $gsql_na_pg->RecuperaNotasLaboratorio_select_3($txtAnio);
					                            
//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('02','05','06') and aniofin=" . $txtAnio . ")"; 
					            
	  				            $compAdicional2= $gsql_na_pg->RecuperaNotasLaboratorio_select_4($txtAnio);
					            
					            break;
	  				case SEGUNDO_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01','02') and anioinicio=" .
//					                            $txtAnio . ")";
					                            
	  				            $compAdicional1= $gsql_na_pg->RecuperaNotasLaboratorio_select_5($txtAnio);
					                            
//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('05','06') and aniofin=" . $txtAnio . ")";
					            
	  				            $compAdicional2= $gsql_na_pg->RecuperaNotasLaboratorio_select_6($txtAnio);
					            
					            break;
	  				case VACACIONES_DEL_SEGUNDO_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01','02','05') and anioinicio=" .
//					                            $txtAnio . ")";
					                            
	  				            $compAdicional1= $gsql_na_pg->RecuperaNotasLaboratorio_select_7($txtAnio);
					                            
//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('06') and aniofin=" . 
//					            $txtAnio . ")"; 
					            
	  				            $compAdicional2= $gsql_na_pg->RecuperaNotasLaboratorio_select_8($txtAnio);
					            
								break;
	  				}
//					 $SqlActualizaLaboratorio="  update ing_notasactividad
//													set notaganolaboratorio=laboratorio.nota
//													from laboratorio
//													where
//													ing_notasactividad.carnet=laboratorio.usuarioid
//													and ing_notasactividad.curso=laboratorio.curso 
//													and ing_notasactividad.periodo='$txtPeriodo'
//													and ing_notasactividad.anio=$txtAnio
//													and laboratorio.curso='$txtCurso'
//													and ($compAdicional1) and ($compAdicional2)
//					                          ";
					                          
					 $SqlActualizaLaboratorio= $gsql_na_pg->RecuperaNotasLaboratorio_update2($txtPeriodo,$txtAnio,$txtCurso,$compAdicional1,$compAdicional2);
					                          
											  
						//echo "<br>$SqlActualizaLaboratorio";					  
					    if($bd->query($SqlActualizaLaboratorio)==0)
						   return 0;
						
					 
					 // fin actualiza los laboratorios.

   return 1;
}




function NotasAnteriores($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$bd,$bd2)
{
   global $gsql_na_pg;
   $bdTransaccion=ConectarBase();   
//   $SqlTransaccion=" begin ";
   
   $SqlTransaccion= $gsql_na_pg->begin();
   
   $bdTransaccion->query($SqlTransaccion);
   
	if((RecuperaNotasLaboratorio($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$bd)) &&
		(RecuperaActividadesGanadas($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$bd,$_SESSION[ExisteSeccionMagistral]))) {
//		$SqlTransaccion=" commit ";
		
      $SqlTransaccion= $gsql_na_pg->commit();
		
	}
	else
	{
//		$SqlTransaccion=" roolback ";
		
      $SqlTransaccion= $gsql_na_pg->rollback();
		
	}	

		$bdTransaccion->query($SqlTransaccion);
   
   $SqlTransaccion= $gsql_na_pg->end();
   
   $bdTransaccion->query($SqlTransaccion);
}

//Finaliza Sección de Implementación de Funciones


session_start();

require "conectar.php";

?>
