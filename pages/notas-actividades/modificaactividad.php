<?php

include_once("/var/www/biblioteca/sql/portal2_notasactividades_modificaactividad.sql.php");

$_verificarSesion=true;
session_start();

require "conectar.php";
require "Template/TemplatePower.inc.php";

global $gsql_na_ma;
$gsql_na_ma = new notasactividades_modificaactividadSQL();

global $tpl;

    $tpl = new TemplatePower( "modificaactividad.tpl" );
    $tpl->prepare();

switch ($opcion)
{
       case 0:// Muestra la pantalla

            break;
              
       case 1: // grabar la nueva actividad
//                      $SqlInserta=" insert into ing_tipoactividad (nombre,activo) values ('$txtNuevaActividad',1);";
                      
                      $SqlInserta= $gsql_na_ma->_insert1($txtNuevaActividad);
                      
//                      echo "$SqlInserta";
                      $bd->query($SqlInserta);
 //                      header ("Location:modificatipoactividad.php?opcion=0");

			  break;
       
       case 2: // pone activo==0
//                   $sqlUpdate=" update ing_tipoactividad set activo=0 where idtipoactividad=$txtTipoActividad";
                   
                   $sqlUpdate= $gsql_na_ma->_update1($txtTipoActividad);
                   
//                   echo "$sqlUpdate";
                   $bd->query($sqlUpdate);
//                   echo "  ".mysql_errno();

               break; // DE LA OPCION MUESTRA DATOS PARA MODIFICAR UNA ACTIVIDAD


       case 3: // opcion para update de la info
//                   $sqlUpdate=" update ing_tipoactividad set nombre='$txtNombreActualizar' where idtipoactividad=$txtCodigoActualizar";
                   
                   $sqlUpdate= $gsql_na_ma->_update2($txtNombreActualizar,$txtCodigoActualizar);
                   
//                   echo "$sqlUpdate";
                   $bd->query($sqlUpdate);
//                   echo "  ".mysql_errno();
			   break;

       case 4: // cambia superactividad 
	                $CambiarA=0;
	   				if($txtSuperActividad==0) {$CambiarA=1;}
//                   $sqlUpdate=" update ing_tipoactividad set superactividad=$CambiarA where idtipoactividad=$txtTipoActividad";
                   
                   $sqlUpdate= $gsql_na_ma->_update3($CambiarA,$txtTipoActividad);
                   
//                   echo "$sqlUpdate  *<br>";
                   $bd->query($sqlUpdate);
//                   echo "  ".mysql_errno();

               break; // DE LA OPCION MUESTRA DATOS PARA MODIFICAR UNA ACTIVIDAD


              
} // del switch


$tpl->newblock("actividad");

//$slqListaActividades= " select * from ing_tipoactividad where activo=1 and idtipoactividad>0 order by idtipoactividad";

$slqListaActividades= $gsql_na_ma->_select1();

    $bd->query($slqListaActividades);
	
    $FilasPosicion=$bd->num_rows();
    if($FilasPosicion>0)
    {
          while (($bd->next_record())!=null)
          {   
				$FilaActividad=$bd->r();
		  
		    	  $tpl->newblock("filaactividad");
		          $tpl->assign("txtNombreTipoActividad",$FilaActividad["nombre"]." -->SA=".$FilaActividad["superactividad"]);
                  $tpl->assign("txtCodigoTipoActividad",$FilaActividad["idtipoactividad"]);
                  if($FilaActividad["idtipoactividad"]>5)
                  {
                     $tpl->newblock("botonesactividad");
                         $tpl->assign("txtCodigoTipoActividad",$FilaActividad["idtipoactividad"]);
                        $tpl->assign("txtNombreTipoActividad",$FilaActividad["nombre"]);
						$tpl->assign("txtEsSuperActvividad",$FilaActividad["superactividad"]);
                         
                  }
          } // del while recorre actividades
    }


  $tpl->printToScreen();
?>
