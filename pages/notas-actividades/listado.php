<?php
include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/sql/listado_SQL.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");

$_verificarSesion = true;
session_start();

include "paggeneral.php";


global $gsql_na_l;
$gsql_na_l = new listado_SQL();
$obj_cad = new ManejoString();

$TopeLaboratorio = $_SESSION['TopeLaboratorio'];

$SesentaYUno = round(($TopeLaboratorio * 61 / 100), 2);
$SesentaYUnoAproximado = round($SesentaYUno) + 0.5;
//echo "<bre> ***sesenta y uno= $SesentaYUno y sesentayUnoAproximado= $SesentaYUnoAproximado";

global $tpl;

$tpl = new TemplatePower("listado.tpl");
$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();
//	$tpl->printToScreen();

function RecuperaActividadesGanadas222($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $bd, $bd2)
{
    global $gsql_na_l;
    $compAdicional1 = "";
    $compAdicional2 = "";
    $bd->query($gsql_na_l->begin());//" begin ");
    $Error1 = 0;
    $Error2 = 0;
    $Error3 = 0;


//if($bd2){echo "<br> si BD2";}
//return 0;
    switch ($txtPeriodo) {
//Código original modificado por Pancho López el 04/06/2010 para que se incluyan también los laboratorios con aniofin>$txtAnio
        /*
                              case '01' : $comparacionAdicional=""; break;
                              case '02' : $comparacionAdicional=" and (periodofin in ('01') and aniofin=" . $txtAnio . ")"; break;
                              case '05' : $comparacionAdicional=" and (periodofin in ('01','02') and aniofin=" . $txtAnio . ")"; break;
                              case '06' : $comparacionAdicional=" and (periodofin in ('01','02','05') and aniofin=" . $txtAnio . ")"; break;
        */
        case PRIMER_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio;

            $compAdicional1 = $gsql_na_l->_select_1($txtAnio);

//					            $compAdicional2="aniofin>=" . $txtAnio; 

            $compAdicional2 = $gsql_na_l->_select_2($txtAnio);

            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01') and anioinicio=" .
//					                            $txtAnio . ")";

            $compAdicional1 = $gsql_na_l->_select_3($txtAnio);

//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('02','05','06') and aniofin=" . $txtAnio . ")"; 

            $compAdicional2 = $gsql_na_l->_select_4($txtAnio);

            break;
        case SEGUNDO_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01','02') and anioinicio=" .
//					                            $txtAnio . ")";

            $compAdicional1 = $gsql_na_l->_select_5($txtAnio);

//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('05','06') and aniofin=" . $txtAnio . ")";

            $compAdicional2 = $gsql_na_l->_select_6($txtAnio);

            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE : //$compAdicional1="anioinicio<" . $txtAnio . " or (periodoinicio in ('01','02','05') and anioinicio=" .
//					                            $txtAnio . ")";

            $compAdicional1 = $gsql_na_l->_select_7($txtAnio);

//					            $compAdicional2="aniofin>" . $txtAnio . " or (periodofin in ('06') and aniofin=" . 
//					            $txtAnio . ")"; 

            $compAdicional2 = $gsql_na_l->_select_8($txtAnio);

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

    $SqlRecorreActividades = $gsql_na_l->_select1($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio);

//		echo "<br> Las Actividades== $SqlRecorreActividades ";			
//				$bd2 = ConectarBase();
    if (!$bd2->query($SqlRecorreActividades)) $Error = 1;;
    for ($i = 1; $i <= $bd2->num_rows(); $i++) {
        $bd2->next_record();
        $FilaActividad = $bd2->r();
        $Posicion = $FilaActividad[posicion];
        $TipoActividad = $FilaActividad[tipoactividad];
//					 $SqlPoneACero=" update ing_notasactividad set notaactividadganada[$Posicion]=0 
//					                 where curso='$txtCurso' and periodo='$txtPeriodo' and anio=$txtAnio
//					               ";

        $SqlPoneACero = $gsql_na_l->_update1($Posicion, $txtCurso, $txtPeriodo, $txtAnio);

//					echo "<br>pone 0s $SqlPoneACero";			   
        if (!$bd->query($SqlPoneACero)) $Error2 = 1;

//					 $SqlActualizaactividades="  update ing_notasactividad
//													set notaactividadganada[$Posicion]=ing_notasactividadesguardadas.nota
//													from ing_notasactividadesguardadas
//													where
//													ing_notasactividad.carnet=ing_notasactividadesguardadas.carnet
//													and ing_notasactividad.curso=ing_notasactividadesguardadas.curso 
//													and ing_notasactividadesguardadas.curso='$txtCurso'
//													
//													and ing_notasactividad.seccion='$txtSeccion'
//													and ing_notasactividad.periodo='$txtPeriodo'
//													and ing_notasactividad.anio=$txtAnio
//													
//													and ing_notasactividadesguardadas.actividad=$TipoActividad 
//													and ($compAdicional1) and ($compAdicional2)
//					                          ";

        $SqlActualizaactividades = $gsql_na_l->_update2($Posicion, $txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $TipoActividad, $compAdicional1, $compAdicional2);


//						echo "<br>$SqlActualizaactividades";					  
        if (!$bd->query($SqlActualizaactividades)) $Error3 = 0;
    }//del for
} // de la funcion recupera actividadesganadas. . . . 

$txtCurso = '';
$txtSeccion ='';
$txtCarrera = '';
$txtPeriodo = '';
$txtAnio = '';
$txtTieneLaboratorio = '';
$txtRegPer = '';
$txtLaSeccion = '';

$opcion = $_GET['opcion'];
//echo "existeSeccionMagistral:[" . $_SESSION[ExisteSeccionMagistral] . "]<br>";
switch ($opcion) {
    case 0:// APRUEBA LA INFORMACION DEL CURSO
        $txtCurso = $_REQUEST['txtCurso'];
        $txtCarrera = $_REQUEST['txtCarrera'];
        $txtPeriodo = $_REQUEST['txtPeriodo'];
        $txtAnio = $_REQUEST['txtAnio'];
        $txtRegPer = $_REQUEST['txtRegPer'];
        $txtLaSeccion = $_REQUEST['txtLaSeccion'];

        // inicia transaccion
        $Error3 = 0;
        $bd->query($gsql_na_l->begin());//" begin ");


//	         $SqlAprueba=" update ing_fechaaprobacionactividad set fecha=now() 
//			               where
//									curso= '$txtCurso'
//               						and seccion= '$txtSeccion'
//					                and periodo= '$txtPeriodo'
//               						and anio= '$txtAnio'
//									and regper='$txtRegPer'
//						   ";

        $SqlAprueba = $gsql_na_l->_update3($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtRegPer);

        if (!$bd->query($SqlAprueba)) $Error3 = 1;
//					echo "<br><br>$SqlAprueba<br>";
//					die;
        $_SESSION[CursoAprobado] = 1;
        if ($Error3 == 0)
            $bd->query($gsql_na_l->commit());//"commit");
        else
            $bd->query($gsql_na_l->rollback());//"rollback");
        $bd->query($gsql_na_l->end());//" end ");


        header("location: ../notas-actividades/listado.php?opcion=1&txtCurso=$txtCurso&txtLaSeccion=$txtLaSeccion&txtAnio=$txtAnio&txtPeriodo=$txtPeriodo&txtRegPer=$txtRegPer&txtCarrera=$txtCarrera");

        break;

    case 1: //  LEVANTA VECTORES . . . .
        $txtCurso = $_GET['txtCurso'];
        $txtLaSeccion = $_GET['txtLaSeccion'];
        $txtCarrera = $_GET['txtCarrera'];
        $txtPeriodo = $_GET['txtPeriodo'];
        $txtAnio = $_GET['txtAnio'];
        $txtTieneLaboratorio = $_GET['laboratorio'];
        $txtRegPer = $_GET['txtRegPer'];
        $txtSeccion = str_replace("*", "+", "$txtLaSeccion");

        $tpl->assign("AtxtCurso",$txtCurso);
        $tpl->assign("AtxtIndex",$_SESSION['index']);
        $tpl->assign("AtxtCarrera",$txtCarrera);
        $tpl->assign("AtxtSeccion",$txtSeccion);

        $tpl->assign('aParametros', 'txtCursoNombre='.$_SESSION["nombrecorto"].'&txtCarrera='.$obj_cad->StringCarrera('0' . $txtCarrera).'&txtPeriodo='.$obj_cad->funTextoPeriodo($txtPeriodo).'&txtAnio='.$txtAnio.'&txtCurso='.$txtCurso);

        $bd2 = ConectarBase();
//RecuperaActividadesGanadas($txtCurso,$txtSeccion,$txtPeriodo,$txtAnio,$bd,$bd2);
        NotasAnteriores($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $bd, $bd2);

        // fin actualiza los laboratorios.

        if ($_SESSION[ExisteSeccionMagistral] == 0) { //Sólo se deben mostrar las notas de acuerdo a la información enviada de las superactividades o también filtrar la información de tal forma
            //que tome en cuenta el campo seccionactividad de la tabla ing_notasactividad y sólo mostrar esos datos que coincidan con la sección
            $sqlActividades = $gsql_na_l->_select2($txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $txtCurso, $_SESSION[regper]);
        } else {
            $sqlActividades = $gsql_na_l->_select3($txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio);
        }
//           echo "(" . $_SESSION[ExisteSeccionMagistral] . ")<br>" . "$sqlActividades<br>";
        $ResultadoActividades = $bd->query($sqlActividades);

        $tpl->newblock("listado");
        $tpl->assign("txtCurso", $txtCurso);
        $tpl->assign("txtSeccion", $txtSeccion);
        $tpl->assign("TopeLaboratorio", $TopeLaboratorio);

        $tpl->assign("nombrePeriodo", $_SESSION["nombreperiodo"] . " $txtAnio");
        $tpl->assign("vCurso", $_SESSION['curso']);
        $tpl->assign("vNombre", $_SESSION["nombrecorto"]);
        $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $txtCarrera));
        $tpl->assign("vPeriodo", $obj_cad->funTextoPeriodo($txtPeriodo));
        $tpl->assign("vAnio", $txtAnio);
        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));

        if ($_SESSION[ExisteSeccionMagistral] == 0)
            $tpl->assign("numeroColumnas", "5");
        else {
            $tpl->assign("numeroColumnas", "7");
            $tpl->newblock("restoencabezado");
            $tpl->gotoblock("listado");
        }

        if ($bd->num_rows() <= 0) {
            $tpl->newblock("botones");
            $tpl->assign("txtCurso", $txtCurso);
            $tpl->assign("txtLaSeccion", $txtLaSeccion);
            $tpl->assign("txtCarrera", $txtCarrera);
            $tpl->assign("txtPeriodo", $txtPeriodo);
            $tpl->assign("txtAnio", $txtAnio);
            $tpl->assign("txtRegPer", $_SESSION[regper]);
            $tpl->assign("tipoaprobar", "hidden");
            $tpl->newblock("mensaje");
            $tpl->assign("mensaje", "<font color='red' style='font-size: 13px; font-weight: bold;'>" .
                "<center>No existen datos procesados aún para ese curso/sección. . . </font></center>");
            $tpl->printToScreen();
            die;
        }

        $HacerReposicionParcial = 0;
        unset ($VecActividadesCM);
        unset ($VecActividadesLab);
        unset($VecLaboratorio);
        $RecorreLaboratorio = 0;
        unset($VecClaseMAgistral);
        unset($VecTipoActividad);
        unset($VecPonderacionLab);
        unset($VecPonderacionCL);

        // actividaddetermminante
        unset($VecLabDeterminante);
        unset($VecCLDeterminante);
        // actividaddeterminante


        $RecorreClaseMagistral = 0;
        $TotalActividadesLaboratorio = 0;
        $TotalActividadesClaseMagistral = 0;
        $RecorreActividades = 0;
        $RActividadLab = 0;
        $RActividadCL = 0;
        $ExisteSegundoParcial = 0;
        $SiguienteParcial = 4;

        $laEscuela = -1;
        while (($bd->next_record()) != null) // recorre actividades . . . . .
        {
            $FilaDatoListaActividad = $bd->r();

            if ($laEscuela == -1)
                $laEscuela = $FilaDatoListaActividad[escuela];
// actividadDeterminante
            $EsDeterminante = BuscaDeterminante($txtCurso, $FilaDatoListaActividad[tipoactividad], $FilaDatoListaActividad[pertenecea]);
// actividadDeterminante

            $VarPertenecea = $FilaDatoListaActividad[pertenecea];
            $VarTipoActividad = $FilaDatoListaActividad[tipoactividad];
            $VarPosicion = $FilaDatoListaActividad[posicion];
            if ($VarTipoActividad == 5) $HacerReposicionParcial = 1;
            if ($VarTipoActividad == 2) $ExisteSegundoParcial = 1;
            $IdTipoActividad = $FilaDatoListaActividad[idtipoactividad];
            $VarNombre = str_replace(" ", "_", $FilaDatoListaActividad[nombre]);

            switch ($VarPertenecea) {
                case 2: // es laboratorio
                    $VecLabNombre[$RActividadLab] = strtolower("L$VarNombre");
                    $VecLabNombreConsulta[$RActividadLab] = strtolower("L" . $FilaDatoListaActividad[idactividad]);

                    // determinante
                    $VecLabDeterminante[$RActividadLab] = $EsDeterminante;
                    // determinante


// PARA la actividad ganada
//						             $VecLabNombreActividadGanda[$RActividadLab]=strtolower("G$VarNombre");
                    $VecLabNombreConsultaActividadGanada[$RActividadLab] = strtolower("G" . $FilaDatoListaActividad[idactividad]);
// FIN PARA LA ACTIVIDAD GANADA									 

                    $VecLabPosicion[$RActividadLab] = $VarPosicion;
                    $VecLabPonderacion[$RActividadLab] = $FilaDatoListaActividad[ponderacion];
                    $VecLabTipoActividad[$RActividadLab] = $VarTipoActividad;
                    $TotalActividadesLaboratorio++;
                    $RActividadLab++;
                    break; // del case 2 laboratorio

                default:

                    // determinante
                    $VecLabDeterminante[$RActividadLab] = $EsDeterminante;
                    // determinante

                    $VecCLNombre[$RActividadCL] = strtolower("C$VarNombre");
                    $VecCLNombreConsulta[$RActividadCL] = strtolower("C" . $FilaDatoListaActividad[idactividad]);

// PARA la actividad ganada
//						             $VecCLNombreActividadGanda[$RActividadLab]=strtolower("G$VarNombre");
                    $VecCLNombreConsultaActividadGanada[$RActividadCL] = strtolower("G" . $FilaDatoListaActividad[idactividad]);
// FIN PARA LA ACTIVIDAD GANADA									 

                    $VecCLPosicion[$RActividadCL] = $VarPosicion;
                    $VecCLPonderacion[$RActividadCL] = $FilaDatoListaActividad[ponderacion];
                    $VecCLTipoActividad[$RActividadCL] = $VarTipoActividad;
                    $TotalActividadesClaseMagistral++;
                    $RActividadCL++;


            } // del switch pertencea
        }// del while recorre actividades. . ..

//		echo "<br> Totales: CL=$TotalActividadesClaseMagistral		LAB=$TotalActividadesLaboratorio";


        $RecorreLaboratorio = 0;
        $WhereLaboratorio = "";
        while ($RecorreLaboratorio < $TotalActividadesLaboratorio) {
            if ($WhereLaboratorio != "") {
                $WhereLaboratorio = $WhereLaboratorio . ", ";
            }
            $WhereLaboratorio = $WhereLaboratorio . " actividades[" . $VecLabPosicion[$RecorreLaboratorio] . "] as " . $VecLabNombreConsulta[$RecorreLaboratorio];

// para la actividad ganada					
            $WhereLaboratorio = $WhereLaboratorio . ", notaactividadganada[" . $VecLabPosicion[$RecorreLaboratorio] . "] as " . $VecLabNombreConsultaActividadGanada[$RecorreLaboratorio];
// FIN PARA LA ACTIVIDAD GANADA					

            $RecorreLaboratorio++;
        }  // del WHILE recorre LABORATORIO
//				echo "<br>WhereLab== $WhereLaboratorio<br>";					


        $RecorreClaseMagistral = 0;
        $WhereClaseMagistral = "";
        while ($RecorreClaseMagistral < $TotalActividadesClaseMagistral) {
            if ($WhereClaseMagistral != "") {
                $WhereClaseMagistral = $WhereClaseMagistral . ", ";
            }
            $WhereClaseMagistral = $WhereClaseMagistral . " actividades[" . $VecCLPosicion[$RecorreClaseMagistral] . "] as " . $VecCLNombreConsulta[$RecorreClaseMagistral];
// para la actividad ganada					
            $WhereClaseMagistral = $WhereClaseMagistral . ", notaactividadganada[" . $VecCLPosicion[$RecorreClaseMagistral] . "] as " . $VecCLNombreConsultaActividadGanada[$RecorreClaseMagistral];
// FIN PARA LA ACTIVIDAD GANADA					


            $RecorreClaseMagistral++;


        }//    del WHILE recorre clase magistral
//				echo "<br>Where Clase== $WhereClaseMagistral<br>";					

        if ($WhereClaseMagistral != "" && $WhereLaboratorio != "") {
            $CompletaSelect = $WhereLaboratorio . ", " . $WhereClaseMagistral;

        } else  $CompletaSelect = $WhereLaboratorio . " " . $WhereClaseMagistral;
        if ($_SESSION[ExisteSeccionMagistral] == 0) {
            $ValidaSeccion = " ";
        } else {
            $ValidaSeccion = " and seccion='$txtCarrera' ";
        }
        if ($_SESSION[ExisteSeccionMagistral] == 0)
            $SqlNotas = $gsql_na_l->_select4($CompletaSelect, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $_SESSION[regper]);

        else
            $SqlNotas = $gsql_na_l->_select5($CompletaSelect, $txtCurso, $txtCarrera/*$txtSeccion*/, $txtPeriodo, $txtAnio, $_SESSION[regper]);
        $bd2 = ConectarBase();
//echo $SqlNotas . "<br>";
        $ResultadoEstudiantes = $bd2->query($SqlNotas);
        //echo $bd2->ernno();


// //////////////////////////////////////////   TERMINA SACA EL SQL \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

        $Contador = 0;
        $MasActividades = $TotalActividadesClaseMagistral;
        if ($TotalActividadesLaboratorio > $TotalActividadesClaseMagistral) {
            $MasActividades = $TotalActividadesLaboratorio;
            //echo "<br><br>Mayor Lab $MasActividades";
        }

        $verOcultarTodos = '';

        $zonasTotales = array();

        for ($i = 1; $i <= $bd2->num_rows(); $i++) {
            $bd2->next_record();
            //Actividaddeterminante
            $BotarLab = 0;
            $BotarCL = 0;
            //Actividaddeterminante

            $FilaEstudiante = $bd2->r();

            $TotalLaboratorio = 0;
            $TotalClaseMagistral = 0;
            $Contador++;
            $RecorreActividades = 0;
            $RecorreActividadesGeneral = 0;
            $NetoLab = 0;

            $tpl->newblock("DatoOcultar");
            $tpl->assign("carnet", "Det" . $FilaEstudiante[carnet]);

            $tpl->newblock("filaestudiante");
            $Link = $FilaEstudiante[carnet];
            $tpl->assign("txtContador", $Contador);
            $tpl->assign("txtLinkCarnet", $Link);
            $tpl->assign("txtCarnet", $FilaEstudiante[carnet]);
            $tpl->assign("txtNombre", $FilaEstudiante[nombre]);
            $NuevaFila = 1;

            $RActividadLab = 0;
            $RActividadCL = 0;
            $NotaMenorParcial = -1;
            $PonderacionMenorParcial = 0;
            $ReemplazoMenorParcial = 0;

            $detalleZona = '';
            $detalleZona = $detalleZona . '<table cellpadding="5"  cellspacing="0" border="0" style="padding-left:250px !important;">';
            $detalleZona = $detalleZona . '<tr><td><strong>ACTIVIDAD&nbsp;&nbsp;&nbsp;&nbsp;</strong></td><td><strong>PONDERACIÓN DE LA ACTIVIDAD&nbsp;&nbsp;&nbsp;&nbsp;</strong></td><td><strong>NOTA OBTENIDA EN LA ACTIVIDAD</strong></td></tr>';
//					 echo "<br>RECORREACTIVIDADES=".$RecorreActividades." TotalActividadLaboratorio=".$TotalActividadesLaboratorio;
            while ($RecorreActividades < $MasActividades) // Recorre Actividades
            {
                //echo "ENTRO+$RecorreActividades+";
                if ($NuevaFila == 1) {
                    $tpl->newblock("detalleestudiante");
                    $NuevaFila = 0;
                }
                if ($TotalActividadesLaboratorio > $RecorreActividades) {
//echo "Lab <br>";
                    $NombreActividad = str_replace("_", " ", $VecLabNombre[$RActividadLab]);
                    $NombreActividad = substr($NombreActividad, 1, strlen($NombreActividad));
                    $NombreCampo = $VecLabNombreConsulta[$RActividadLab];

                    $ValorSql = $FilaEstudiante[strtolower($NombreCampo)];

                    // ACTIVIDAD GANADA
                    $NombreCampoGanada = $VecLabNombreConsultaActividadGanada[$RActividadLab];
                    //echo "<br>Lab: $NombreCampoGanada";
                    $ValorSqlGanada = $FilaEstudiante[strtolower($NombreCampoGanada)];
//							echo "<br>NotaLab: $ValorSqlGanada";
                    $txtGanada = "";
                    $ValorCalculo = $ValorSql;
                    if ($ValorSqlGanada > 0) {
                        $txtGanada = "*$ValorSqlGanada*";
                    }
//							echo "<br> $txtGanada";							
                    if ($ValorSqlGanada > $ValorSql) {
                        $ValorCalculo = $ValorSqlGanada;
                    }
                    // FIN ACTVIDAD GANADA

                    //  actividaddeterminante
                    if (($ValorCalculo < 61) && ($VecLabDeterminante[$RActividadLab])) {
                        $BotarLab = 1;
                    }
                    // actividaddeterminante


                    $TipoActividad = $VecLabTipoActividad[$RActividadLab];
                    $Ponderacion = $VecLabPonderacion[$RActividadLab];

// original se cambio por actividad ganada							$NetoLab=$NetoLab+$ValorSql*$Ponderacion/100;
                    $NetoLab = $NetoLab + $ValorCalculo * $Ponderacion / 100;

// aqui la aproximacion						
// ORIGINAL APROXIMACION							$ValorNeto=round($ValorSql*$Ponderacion/100);							
                    $ValorNeto = round(($ValorSql * $Ponderacion / 100), 2);
//							echo "<br>Labora: valor=$ValorSql  RecorreActividaLab=$RActividadLab ";

                    $tpl->assign("txtNombreActividadLaboratorio", $NombreActividad . "(" . round($VecLabPonderacion[$RActividadLab]) . ")");
                    $tpl->assign("txtValorActividadLaboratorio", "(" . round($ValorNeto) . ") $ValorSql $txtGanada");
                    $NuevaFila = 1;
                    //$TotalLaboratorio = $TotalLaboratorio + $ValorNeto;
                    $TotalLaboratorio = $TotalLaboratorio + $ValorSql;
                    $detalleZona = $detalleZona . '<tr><td><span style="text-transform: uppercase;">'.$NombreActividad.'</span></td><td align="right">'.$VecLabPonderacion[$RActividadLab].'</td><td align="center">'.$ValorSql.'</td></tr>';
                    $RActividadLab++;

                } // del if totalactividadesLaboratorio > RecorreActividades

                $TotalLaboratorio = round($TotalLaboratorio, 2);

                if ($TotalActividadesClaseMagistral > $RecorreActividades && $_SESSION[ExisteSeccionMagistral]) {
                    $NombreActividad = str_replace("_", " ", $VecCLNombre[$RActividadCL]);
                    $NombreActividad = substr($NombreActividad, 1, strlen($NombreActividad));
                    $NombreCampo = $VecCLNombreConsulta[$RActividadCL];
                    $ValorSql = $FilaEstudiante[strtolower($NombreCampo)];

                    // ACTIVIDAD GANADA
                    $NombreCampoGanada = $VecCLNombreConsultaActividadGanada[$RActividadCL];
//							echo "<br>CL: $NombreCampoGanada";							
                    $ValorSqlGanada = $FilaEstudiante[strtolower($NombreCampoGanada)];
                    $txtGanada = "";
                    $ValorCalculo = $ValorSql;
                    if ($ValorSqlGanada > 0) {
                        $txtGanada = "*$ValorSqlGanada*";
                    }
                    if ($ValorSqlGanada > $ValorSql) {
                        $ValorCalculo = $ValorSqlGanada;
                    }

//							echo "<br>NotaCL: $ValorSqlGanada";
                    // FIN ACTVIDAD GANADA

                    //  actividaddeterminante
                    if (($ValorCalculo < 61) && ($VecCLDeterminante[$RActividadCL])) {
                        $BotarCL = 1;
                    }
                    // actividaddeterminante

                    $TipoActividad = $VecCLTipoActividad[$RActividadCL];
                    $Ponderacion = $VecCLPonderacion[$RActividadCL];

                    if ($HacerReposicionParcial == 1) {
                        if ($TipoActividad < 5) // es parcial
                        {
                            if ($NotaMenorParcial == -1) // para que tome el valor del primer parcial que encuentre
                            {
                                $NotaMenorParcial = $ValorSql;
                                $PonderacionMenorParcial = $Ponderacion;
                            } else
                                if ($ValorSql < $NotaMenorParcial) {
                                    //echo "<br>es menor $ValorSql < $NotaMenorParcial";
                                    $NotaMenorParcial = $ValorSql;
                                    $PonderacionMenorParcial = $Ponderacion;
                                    //echo "	, el menor es*** $NotaMenorParcial con ponderacion=$PonderacionMenorParcial***";
                                }
                        } // del if tipoActividad>5

                        if ($TipoActividad == 5) // es el parcial de reposicion
                        {
                            if ($ValorSql > $NotaMenorParcial) {
//										echo " resto: $NotaMenorParcial * $PonderacionMenorParcial  =".($NotaMenorParcial*$PonderacionMenorParcial);
                                $TotalClaseMagistral = $TotalClaseMagistral - round($NotaMenorParcial * $PonderacionMenorParcial / 100, 2);
//									    echo "  Total CL despues= ".$TotalClaseMagistral;
                                $Ponderacion = $PonderacionMenorParcial;


                            } // del if valorsql>NotaMenorParcial
                        }
                    }// del HacerReposicionParcial==1;

                    $ValorNeto = round($ValorCalculo * $Ponderacion / 100, 2);
//							echo "<br>Labora: valor=$ValorSql  RecorreActividaLab=$RActividadLab ";
                    $tpl->newblock("detallemagistral");
                    $tpl->assign("txtNombreActividadClaseMagistral", $NombreActividad . "(" . round($VecCLPonderacion[$RActividadCL]) . ")");
                    $tpl->assign("txtValorActividadClaseMagistral", "(" . round($ValorNeto) . ") $ValorSql $txtGanada");
                    $NuevaFila = 1;
                    //$TotalClaseMagistral = $TotalClaseMagistral + $ValorNeto;
                    $TotalClaseMagistral = $TotalClaseMagistral + $ValorSql;
                    $detalleZona = $detalleZona . '<tr><td><span style="text-transform: uppercase;">'.$NombreActividad.'</span></td><td align="right">'.$VecCLPonderacion[$RActividadCL].'</td><td align="center">'.$ValorSql.'</td></tr>';

                    $NuevaFila = 1;
                    $RActividadCL++;

                } // del if totalactividadesClaseMagistral>RecorreActividades


                $RecorreActividades++;
            } // del Recorre Actividades
            $detalleZona = $detalleZona .'</table>';

            $tpl->gotoblock("filaestudiante");


            // actividaddeterminante
            if ($BotarLab == 1) {
                $TotalLaboratorio = 0;
            }
            if ($BotarCL == 1) {
                $TotalClaseMagistral = 0;
            }
            // actividaddeterminante

            $tpl->assign("txtTotalLaboratorio", round($TotalLaboratorio));
            if ($TopeLaboratorio > 0) {
                $TotalNotaLaboratorio = $TotalLaboratorio * 100 / $TopeLaboratorio;
//					   echo "<br> ***ENTRO y EL TOTALNotaLaboratorio= $TotalNotaLaboratorio  $TotalLaboratorio";

            } else
                $TotalNotaLaboratorio = 0;

            //echo "<br>***$NetoLab  $TotalLaboratorio      $TotalNotaLaboratorio";
            if (($NetoLab >= $SesentaYUno) && ($NetoLab < $SesentaYUnoAproximado)) {
//					      echo " ENTRO ****";
//					      $TotalNotaLaboratorio=61;
            }

//					 if($FilaEstudiante[$notaganolaboratorio]>$txtTotalLaboratorio){$TotalLaboratorio=$TotalLaboratorio."(".$FilaEstudiante[$notaganolaboratorio].")";}
            $txtLaboratorioGanado = "";
            if ($FilaEstudiante['notaganolaboratorio'] > 0) {
                $TotalNotaLaboratorio = round($TotalNotaLaboratorio);
                $txtLaboratorioGanado = "*" . $FilaEstudiante['notaganolaboratorio'] . "*";
                //."*".round($FilaEstudiante['notaganolaboratorio'])."*";
            }
            if ($FilaEstudiante['notaganolaboratorio'] > $TotalNotaLaboratorio) {
                $TotalLaboratorio = round($FilaEstudiante['notaganolaboratorio'] * $TopeLaboratorio / 100, 2);
//						  echo "<br>Entro con ".$FilaEstudiante['notaganolaboratorio']." TotalLab== ".$TotalLaboratorio;
            }

//Agregado el 27/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
            $laZonaTotal = round($TotalLaboratorio + $TotalClaseMagistral);
            if ($_SESSION[ExisteSeccionMagistral] && ($laZonaTotal > 0 && $laZonaTotal < 30) && ($laEscuela == 1 || $laEscuela == 5)) {//Es curso de la escuela de Civil o Química
                $TotalNotaLaboratorio = 0; //No llegó a zona mínima, no tiene derecho a nota de laboratorio
                if ($txtLaboratorioGanado != "")
                    $txtLaboratorioGanado = "**"; //No tiene derecho a revalidar el laboratorio, aprobado en algún semestre anterior
            }
//Finaliza código agregado el 27/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico

            $tpl->assign("txtNotaLaboratorio", round($TotalNotaLaboratorio) . " $txtLaboratorioGanado");
            if ($_SESSION[ExisteSeccionMagistral]) {
                $tpl->newblock("totalmagistral");
                $tpl->assign("EstiloClass", $estiloclass);
                $tpl->assign("txtTotalClaseMagistral", round($TotalClaseMagistral));
                $tpl->assign("txtTotalZona", $laZonaTotal);
                $tpl->assign("aDetalle", $detalleZona);

                $FilaEstudiante[zona]=$laZonaTotal;
                array_push($zonasTotales,$FilaEstudiante);
            }

        } // termina el for

        $_SESSION['zonasActividades'] = $zonasTotales;
        $tpl->gotoBlock("_ROOT");
        break;

} // del switch

$tpl->newblock("botones");
$tpl->assign("txtCurso", $txtCurso);
$tpl->assign("txtLaSeccion", $txtLaSeccion);
$tpl->assign("txtCarrera", $txtCarrera);
$tpl->assign("txtPeriodo", $txtPeriodo);
$tpl->assign("txtAnio", $txtAnio);
$tpl->assign("txtRegPer", $_SESSION['regper']);

$tpl->assign("InitTabla",'<script>var table = $("#dgTablaDatos").DataTable( {language: {url: "../../libraries/js/DataTables-1.10.6/lang/es_ES.json"}, scrollCollapse: false, paging: false,searching: false, ordering: false,columnDefs: [{"className": \'details-control\',"orderable": false, "data":null, "defaultContent": \'\', width: "4%", targets: 0 }, {width: "8%", targets: 1 }, {width: "73%", targets: 2 },{width: "15%", targets: 3 }]});</script>');

if ($_SESSION[CursoAprobado] == 0) {

    $tpl->assign("tipoaprobar", "submit");

} else {

    $tpl->assign("tipoaprobar", "hidden");
    $tpl->newblock("mensaje");

    $tpl->assign("mensaje", '<div class="alert alert-success">
                                                                    <h4><i class="fa fa-info"></i> NOTAS DE ACTIVIDADES</h4>
                                                                    Este es el reporte de notas de actividades ya aprobadas.
                                                                </div>');

}

$tpl->printToScreen();
?>
