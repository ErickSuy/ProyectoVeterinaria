<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 01/11/2014
 * Time: 12:04 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/model/sql/CourseSchedule_SQL.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

session_start();

global $gsql_h_hcat;

// aun es valida la session?
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

if (isset($_GET['init']) AND $_GET['init'] == OK) { // Ingresa durante la primera llamada y establece todos los parámetros del TPL
    $tpl = new TemplatePower("CourseSchedule.tpl");

    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");

    $tpl->prepare();

    $obj_cad = new ManejoString();

    $tpl->newBlock('selectPeriodo');
    $tpl->assign('aCiclo',PRIMER_SEMESTRE);
    $tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(PRIMER_SEMESTRE));
    $tpl->assign('aComa',',');

    $tpl->newBlock('selectPeriodo');
    $tpl->assign('aCiclo',VACACIONES_DEL_PRIMER_SEMESTRE);
    $tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(VACACIONES_DEL_PRIMER_SEMESTRE));
    $tpl->assign('aComa',',');

    $tpl->newBlock('selectPeriodo');
    $tpl->assign('aCiclo',SEGUNDO_SEMESTRE);
    $tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(SEGUNDO_SEMESTRE));
    $tpl->assign('aComa',',');

    $tpl->newBlock('selectPeriodo');
    $tpl->assign('aCiclo',VACACIONES_DEL_SEGUNDO_SEMESTRE);
    $tpl->assign('aCicloNombre',$obj_cad->funTextoPeriodo(VACACIONES_DEL_SEGUNDO_SEMESTRE));

    $anioActual = Date("Y");
    $anioInicioCombo = 2014; // Apartir de aca es que hay informacion para mostrar

    $tpl->gotoBlock("_ROOT");
    for ($i = $anioInicioCombo; $i <= ($anioActual+1); $i++) {
        $tpl->newBlock("selectAnio");
        $tpl->assign("anio_select", $i);
    }

    $tpl->printToScreen();

    unset($tpl,$obj_cad);
} else { // Acá ingresa en la llamada AJAX
    if(isset($_GET['select']) AND $_GET['select'] == OK) { //Llemar el combobox de horario
        $periodo = $_GET['ciclo'];
        switch($periodo) {
            case PRIMER_SEMESTRE:
                $vHorario = array();
                array_push($vHorario,array('horario'=>PRIMER_SEMESTRE,'horarioTXT'=>'HORARIO DE CURSOS'));
                array_push($vHorario,array('horario'=>FINALES_DEL_PRIMER_SEMESTRE,'horarioTXT'=>'HORARIO DE EXÁMENES FINALES'));
                array_push($vHorario,array('horario'=>PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE,'horarioTXT'=>'HORARIO DE PRIMERA RETRASADA'));
                array_push($vHorario,array('horario'=>SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE,'horarioTXT'=>'HORARIO DE SEGUNDA RETRASADA'));
                echo (json_encode($vHorario));
                break;
            case VACACIONES_DEL_PRIMER_SEMESTRE:
                $vHorario = array();
                array_push($vHorario,array('horario'=>VACACIONES_DEL_PRIMER_SEMESTRE,'horarioTXT'=>'HORARIO DE CURSOS DE VACACIONES'));
                echo (json_encode($vHorario));
                break;
            case SEGUNDO_SEMESTRE:
                $vHorario = array();
                array_push($vHorario,array('horario'=>SEGUNDO_SEMESTRE,'horarioTXT'=>'HORARIO DE CURSOS'));
                array_push($vHorario,array('horario'=>FINALES_DEL_SEGUNDO_SEMESTRE,'horarioTXT'=>'HORARIO DE EXÁMENES FINALES'));
                array_push($vHorario,array('horario'=>PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE,'horarioTXT'=>'HORARIO DE PRIMERA RETRASADA'));
                array_push($vHorario,array('horario'=>SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE,'horarioTXT'=>'HORARIO DE SEGUNDA RETRASADA'));
                echo (json_encode($vHorario));
                break;
            case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                $vHorario = array();
                array_push($vHorario,array('horario'=>VACACIONES_DEL_SEGUNDO_SEMESTRE,'horarioTXT'=>'HORARIO DE CURSOS DE VACACIONES'));
                echo (json_encode($vHorario));
                break;
        }
    } else {
        $gsql_h_hcat = new CourseSchedule_SQL();

        $anio = date("Y");
        $pPeriodo = $_GET['periodo'];
        $pHorario = $_GET['horario'];
        $anio = $_GET['anio'];

        switch($pPeriodo) {
            case PRIMER_SEMESTRE:
                $proceso = PROCESOS_GENERALES_PRIMER_SEMESTRE;
                break;
            case VACACIONES_DEL_PRIMER_SEMESTRE:
                $proceso = PROCESOS_GENERALES_VACACIONES_JUNIO;
                break;
            case SEGUNDO_SEMESTRE:
                $proceso = PROCESOS_GENERALES_SEGUNDO_SEMESTRE;
                break;
            case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                $proceso = PROCESOS_GENERALES_VACACIONES_DICIEMBRE;
                break;
        }

        $query_parametros = $gsql_h_hcat->_select1($proceso, $pHorario, $anio);
        $_SESSION["sConexion"]->query($query_parametros);

        // La siguiente linea se penso para que vaya en línea con la activación el proceso de asignación correspondiente
        // analizarlo mejor....
        //if($_SESSION["sConexion"]->num_rows()) { // Verifica si ya se encuentra en las fechas para mostrar el horario.
            $Horario = NEW D_LoadNotesScheduleManager;
            echo json_encode($Horario->obtenerHorario($pHorario, $anio));
            unset($Horario);
        //}
        unset($gsql_h_hcat);
    }
}
?>