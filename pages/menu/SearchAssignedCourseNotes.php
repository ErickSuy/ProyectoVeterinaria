<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 27/04/2015
 * Time: 11:06 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/model/sql/AssignedCourseRecord_SQL.php");
include_once("$dir_portal/fw/controller/manager/AssignationGeneralsManager.php");
define('MODULARES','625,606,634,600,605,615,1605,610,625,606,634');

function MiconectarBase(){
    $base = NEW DB_Connection;
    $base->connect();
    return $base;
}

function obtieneNombrePeriodo($periodo){
    $nombreP = "";
    switch ($periodo) {
        case PRIMER_SEMESTRE:
            $nombreP = "PRIMER SEMESTRE";
            break;
        case VACACIONES_DEL_PRIMER_SEMESTRE:
            $nombreP = "VACACIONES DE JUNIO";
            break;
        case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "PRIMERA RETRASADA PRIMER SEMESTRE";
            break;
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
            $nombreP = "SEGUNDA RETRASADA PRIMER SEMESTRE";
            break;
        case SUFICIENCIAS_DEL_PRIMER_SEMESTRE:
            $nombreP = "SUFICIENCIA PRIMER SEMESTRE";
            break;
        case SEGUNDO_SEMESTRE:
            $nombreP = "SEGUNDO SEMESTRE";
            break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "VACACIONES DE DICIEMBRE";
            break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "PRIMERA RETRASADA SEGUNDO SEMESTRE";
            break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "SEGUNDA RETRASADA SEGUNDO SEMESTRE";
            break;
        case SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE:
            $nombreP = "SUFICIENCIA SEGUNDO SEMESTRE";
            break;
        default:
            $nombreP = "PRIMER SEMESTRE";
    }
    return $nombreP;
}

function obtenerPeriodoActivo(){
    $diaActual = Date("d");
    $mesActual = Date("m");
    $anioActual = Date("Y");
    $fechaActual = mktime(0, 0, 0, $mesActual, $diaActual, $anioActual);
    $periodoActivo = PRIMER_SEMESTRE;
    if ($fechaActual >= mktime(0, 0, 0, "01", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "02", "02", $anioActual))
        $periodoActivo = PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
    else
        if ($fechaActual >= mktime(0, 0, 0, "02", "02", $anioActual) && $fechaActual < mktime(0, 0, 0, "03", "01", $anioActual))
            $periodoActivo = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
        else
            if ($fechaActual >= mktime(0, 0, 0, "03", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "05", "15", $anioActual))
                $periodoActivo = PRIMER_SEMESTRE;
            else
                if ($fechaActual >= mktime(0, 0, 0, "05", "15", $anioActual) && $fechaActual < mktime(0, 0, 0, "07", "10", $anioActual))
                    $periodoActivo = VACACIONES_DEL_PRIMER_SEMESTRE;
                else
                    if ($fechaActual >= mktime(0, 0, 0, "07", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "08", "10", $anioActual))
                        $periodoActivo = PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE;
                    else
                        if ($fechaActual >= mktime(0, 0, 0, "08", "10", $anioActual) && $fechaActual < mktime(0, 0, 0, "09", "01", $anioActual))
                            $periodoActivo = SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE;
                        else
                            if ($fechaActual >= mktime(0, 0, 0, "09", "01", $anioActual) && $fechaActual < mktime(0, 0, 0, "11", "15", $anioActual))
                                $periodoActivo = SEGUNDO_SEMESTRE;
                            else
                                $periodoActivo = VACACIONES_DEL_SEGUNDO_SEMESTRE;
    return $periodoActivo;
}

function obtieneEstadoActa($estado,$tipoacta) {
    $nombreE="";
    switch ($estado) {
        case  2: $nombreE="PENDIENTE DE PROCESAR";
           // break;
        case  3:
        case  4:
            $nombreE="INGRESADA VÍA WEB (ACTA EN PROCESO)";
            break;
        case  5:
        case  6:
        case  7:
        case  8:
        case  9:
        case 10:
        case 11:
        case 12:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
        case 701:
        case 702:
        case 703:
        case 801:
        case 802:
        case 803:
        case 804:
        case 1001: if ($tipoacta=='N')
            $nombreE= "ACTA EN PROCESO";
        else
            $nombreE="APROBADA VÍA WEB (ACTA EN PROCESO)";
            break;
        case 13:
        case 14:
        case 1401:
            $nombreE="ACTA CON NOTAS REALES";
            break;
//  default: $nombreE="Pendiente de Procesar";
        default: $nombreE="???";
    }
    return $nombreE;
}

function CargaNotasActividades($tpl,$Curso,$Seccion,$Periodo,$Anio,$Carnet,$notasPracticasVigentes,
                               $cursosConLab,$escuela,$zonaTotal,$estadoActa)
{
    global $gsql_a_nca;

    $Mibd=MiconectarBase();

    $sqlActividades = $gsql_a_nca -> CargaNotasActividades_select1($Curso,$Seccion,$Periodo,$Anio,$Carnet);


    $FilasTipoActividad=0;
    $ResultadoActividades=$Mibd->query($sqlActividades);
    $FilasTipoActividad=$Mibd->num_rows();
    if($FilasTipoActividad==0) {
    }
    else
    {
        $detalleZona = '';
        $detalleZona = $detalleZona . '<table class="RAsig-table" cellpadding="5"  cellspacing="0" border="0" style="padding-left:250px !important;">';
        $detalleZona = $detalleZona . '<thead><tr><th>No.</th><th><strong>ACTIVIDAD&nbsp;&nbsp;&nbsp;&nbsp;</strong></th><th><strong>PONDERACIÓN DE LA ACTIVIDAD&nbsp;&nbsp;&nbsp;&nbsp;</strong></th><th><strong>NOTA OBTENIDA EN LA ACTIVIDAD</strong></th></tr></thead><tbody>';
        $i = 1;
        $zona = 0;
        while (($Mibd->next_record())!=null)
        {
            $FilaActividad=$Mibd->r();
            $Posicion=$FilaActividad[posicion];

            $Nota=$FilaActividad[actividades];
            $zona = $zona + $Nota;
            if($FilaActividad[nombreactividad]=="")
                $NombreActividad = $FilaActividad[nombretipoactividad];
            else
                $NombreActividad = $FilaActividad[nombreactividad];
            $Poderacion=$FilaActividad[ponderacion];

            $detalleZona = $detalleZona . "<tr><td>$i.</td><td>$NombreActividad</td><td><div align='center'>$Poderacion pts.</div></td><td><div align='center'>$Nota pts.</div></td></tr>";
            $i++;
        }// del while que recorre actividades

        $zona = round($zona);

        $detalleZona = $detalleZona . "</tbody></table>";
        $tpl->assign('aZona',$zona);
        $tpl->assign('aDetalle',$detalleZona);
    }

}
// fin de la funcion CargaNotasActividades;

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

global $gsql_a_nca;
$gsql_a_nca = new AssignedCourseRecord_SQL();

$tpl = new TemplatePower("SearchAssignedCourseNotes.tpl");
$reg = new AssignationGeneralsManager($objuser->getId(), $objuser->getCareer(), obtenerPeriodoActivo(), Date("Y"));
$reg->mPensum = $objuser->getCurriculum();

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("iselectciclo", "../includes/selectciclos.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

if (isset($_POST["Buscar"])) {
    $periodoRevisado = $_POST["periodo"];
    $anioRevisado = $_POST["anio"];
} else {
    $periodoRevisado = obtenerPeriodoActivo();
    if ($periodoRevisado == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
        $anioRevisado = Date("Y") - 1;
    else
        if ($periodoRevisado == VACACIONES_DEL_SEGUNDO_SEMESTRE) {
            $fechaActual = mktime(0, 0, 0, Date("m"), Date("d"), Date("Y"));
            if ($fechaActual >= mktime(0, 0, 0, "01", "01", Date("Y")) && $fechaActual < mktime(0, 0, 0, "01", "10", Date("Y")))
                $anioRevisado = Date("Y") - 1;
            else
                $anioRevisado = Date("Y");
        } else
            $anioRevisado = Date("Y");
}
$anioActual = Date("Y");
$anioInicioCombo = 2014; // Apartir de aca es que hay informacion para mostrar

$tpl->gotoBlock("_ROOT");
for ($i = $anioInicioCombo; $i <= $anioActual; $i++) {
    $tpl->newBlock("selectAnio");
    $tpl->assign("anio_select", $i);
}

$info = $reg->VerNotasCursosAsignados($periodoRevisado,$anioRevisado);
$tpl->gotoBlock("_ROOT");
$tpl->assign("aPeriodo", obtieneNombrePeriodo($periodoRevisado));
$tpl->assign("aAnio", $anioRevisado);
$tpl->assign('aEstudiante', ($objuser->getId() . ' - ' . $objuser->getName() . ' ' . $objuser->getSurName()));
$tpl->assign('aCarrera', $objuser->getCareerName());

if ( $info == false) {
    $tpl->newBlock("b_sinasignacion");
}
else {
    $tpl->newBlock("b_asignados");

    $info[0] = Date("d-m-Y");
    $info[1] = Date("H:i");

    $tpl->assign("aFechaAsignacion", $info[8]);
    $tpl->assign("aTransaccion", $info[9]);
    $tpl->assign("aFecha", $info[0]);
    $tpl->assign("aHora", $info[1]);

    $cursosConLaboratorio=array();
    $notasPracticasVigentes=array();
    $notasLabVigentes=array();

    $detalle = $reg->VerDetalleNotas($periodoRevisado,$anioRevisado,$cursosConLaboratorio);
    $tpl->gotoBlock( "_ROOT" );
    if ($detalle) {
        $totaldet = sizeof($detalle);

        for ($i=1; $i<=$totaldet; $i++) {
            $cursoo = $detalle[$i]["cur"];
            if (strcmp($cursoo,'160')==0) {
                $cursoo = '0'.$cursoo;
            }

            if(substr_count(MODULARES,$cursoo)==0) {
                switch ((int)$detalle[$i]['exa']) {
                    case -1:
                        $valor_actual = 'NSP';
                        $nota = (int)$detalle[$i]['zon'];
                        break;

                    case -2:
                        $valor_actual = 'SDE';
                        $nota = (int)$detalle[$i]['zon'];
                        break;

                    default:
                        $valor_actual = ((int)$detalle[$i]['exa']).' .Pts';
                        $nota = (int)$detalle[$i]['zon'] + (int)$detalle[$i]['exa'];
                }


                $tpl->newBlock("b_detalleasignacion");
                $tpl->assign('aFont', '#3d3d3d');
                $tpl->assign('aCurso', $detalle[$i]['cur']);
                $tpl->assign('aNombreCurso', $detalle[$i]['nom']);
                $tpl->assign('aZona', (int)$detalle[$i]['zon']);
                $tpl->assign('aExamen', $valor_actual);
                $tpl->assign('aNota', $nota);
                $tpl->assign('aEstado', obtieneEstadoActa($detalle[$i]["est"],$detalle[$i]["tip"]));

                if ($periodoRevisado==PRIMER_SEMESTRE || $periodoRevisado==VACACIONES_DEL_PRIMER_SEMESTRE || $periodoRevisado==SEGUNDO_SEMESTRE || $periodoRevisado==VACACIONES_DEL_SEGUNDO_SEMESTRE)
                    CargaNotasActividades($tpl,$detalle[$i]["cur"],$detalle[$i]["car"],$periodoRevisado,$anioRevisado,$info[5],
                        $notasPracticasVigentes,$cursosConLaboratorio,$detalle[$i]["esc"],$detalle[$i]["zon"],$detalle[$i]["est"]);
            }

            $tpl->gotoBlock( "_ROOT" );
        }
        $tpl->assign("InitTabla",'<script>var table = $("#dgTablaDatos").DataTable( {language: {url: "../../libraries/js/DataTables-1.10.6/lang/es_ES.json"}, scrollCollapse: false, paging: false,searching: false, ordering: false,columnDefs: [{"className": \'details-control\',"orderable": false, "data":null, "defaultContent": \'\', width: "4%", targets: 0}, {width: "6%", targets: 1 }, {width: "35%", targets: 2 },{width: "8%", targets: 3 },{width: "12%", targets: 4},{width: "12%", targets: 5}]});</script>');
        if (isset($notasPracticasVigentes))
            unset($notasPracticasVigentes);
        if (isset($notasLabVigentes))
            unset($notasLabVigentes);
        if (isset($cursosConLaboratorio))
            unset($cursosConLaboratorio);
    }
}

$tpl->gotoBlock("_ROOT");
$tpl->assign("periodo", $periodoRevisado);
$tpl->assign("anio", $anioRevisado);
$tpl->printToScreen();
unset($tpl);
unset($reg);

?>