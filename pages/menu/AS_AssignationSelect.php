<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 13/01/2015
 * Time: 2:24 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/libconst.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/AssignationMsgs.php");
include_once("$dir_portal/fw/controller/ControlAssignationRequeriments1.php");
include_once("$dir_portal/fw/controller/mapping/AssignationParamHandler.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);

if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$tpl = new TemplatePower("AS_AssignationSelect.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$objAssignation = unserialize($_SESSION['asignacion']);
$schedule = $objAssignation->getSchedule();
$objControlService = new ControlAssignationRequeriments1();

// Verificar si viene del paso 1, para verificar los cursos asignados anteriormente
if(isset($_GET["paso"]) and $_GET["paso"]==1) {
    $objAssignation->setAssignationPrev($objControlService->getAssignation($objuser->getId(),$objuser->getCareer(),$objuser->getCurriculum(),$objAssignation->getYear(),$objAssignation->getSchoolYear()));
    $objAssignation->setAssignationSelected($objAssignation->getAssignationPrev());
} else if(isset($_GET["paso"]) and $_GET["paso"]==2) {
    //print_r($objAssignation->getAssignationSelected());
    if(isset($_GET['error'])) {
        $tpl->gotoBlock("_ROOT");
        $tpl->assign('aDesplegarErrorAsignacion',"<div style='width: 92%' class='alert alert-danger'><h4><i class='fa fa-close fa-lg'></i> RECTIFIQUE SU ASIGNACIÓN</h4><p>Para ver los problemas en la asignación haga clic en la opción <i class='fa fa-info-circle'></i> presente en la columna <strong>ACCIONES</strong>. Rectifíquelos y luego vuelva a intentarlo.</p></div>");
    }
    $objAssignation->setAssignationPrev(null);
}

// Se obtiene la información del vector que maneja la asignación
// que se tiene seleccionada
$assignation = $objAssignation->getAssignationSelected();

$aScheduleCount = $schedule != NULL ? count($schedule) : 0; // Para asignar al marcador {aTotalRegistros}
$assignationCount = $assignation != NULL ? count($assignation) : 0;

$tpl->gotoBlock("_ROOT");
$tpl->assign('aTotalRegistros',$aScheduleCount);

$index = 0;
$numCurso = 0;
$ultimoCodigo = 0;

foreach($schedule as $reg) {
    if($reg['course']!=$ultimoCodigo) {
        $tpl->newBlock('INFOPENSUM');
        $numCurso++;

        $tpl->assign('aPosicion',$numCurso);
        $tpl->assign('aCursoIP',$reg['course']);
        $tpl->assign('aNombreIP',$reg['name']);
        $tpl->assign('aObligatorioIP',($reg['required']==1?'*':''));
        $tpl->assign('aCreditosIP',$reg['credits']);
        $tpl->assign('aPosicionIP',$numCurso);

        $index2 = 1;
        if($assignationCount) {
            foreach($assignation as $reg2) {
                if($reg2['course']==$reg['course']) {
                    $tpl->assign('aVisibleIP',"0");
                    $assignation[$index2]['indice'] = $numCurso;
                } else {
                    $tpl->assign('aVisibleIP',"1");
                }
                $index2++;
            }
        } else {
            $tpl->assign('aVisibleIP',"1");
        }

        $tpl->assign('aRequisitosIP',$reg['requirement']);
        $tpl->assign('aIndexIP',$reg['index']);
        $tpl->assign('aLaboratorioIP',$reg['lab']); /***************************************************  JALAR SI EL CURSO TIENE LAB O NO  ******************/
        $tpl->assign('aLaboratorioSecIP','-'); /***************************************************  JALAR SI EL CURSO TIENE LAB O NO  ******************/

        $ultimoCodigo = $reg['course'];
    }
    $index++;
}

$tpl->gotoBlock("_ROOT");
$tpl->assign('aTotalCursos',$numCurso);

$index = 0;
foreach($schedule as $reg) {
    $index++;
    $tpl->newBlock('INFOHORARIO');
    $tpl->assign('aIndice',$index);

    $tpl->assign('aCursoIH',$reg['course']);
    $tpl->assign('aSeccionIH',$reg['section']);
    $tpl->assign('aNombreIH',$reg['name']);
    $tpl->assign('aIndexIH',$reg['index']);
    $tpl->assign('aLaboratorioIH',$reg['lab']); /***************************************************  JALAR SI EL CURSO TIENE LAB O NO  ******************/
}

$tpl->gotoBlock("_ROOT");
$tpl->assign("aPeriodo", $objAssignation->getSchoolYear());
$tpl->assign("aPensum", $objuser->getCurriculum());

for($x=1;$x<=count($assignation);$x++) {
    $param0 = $assignation[$x]['cindex'];
    $param1 = $assignation[$x]['course'];
    $param2 = $assignation[$x]['name'];
    $param3 = $assignation[$x]['section'];

    $str1 = '';
    if(isset($assignation[$x]['remark']) and count($assignation[$x]['remark'])>0) {
        $str1 = '<ul>';
        $str21 = '';
        $str22 = '';
        $str23 = '';
        $vecRemarks = $assignation[$x]['remark'];

        while (list($key, $value) = each($vecRemarks)) {
            switch($key){
                case CURSO_FALTA_PRERREQUISITO:
                    $str21 = '<li><span><table><tr><td align="center"><img src="../../resources/images/error.png"  width="20" height="20"></td><td>&nbsp&nbsp' . $msg[201] . '</td></tr></table></span>';
                    $str21 = $str21 .'</li><li>';
                    $tablaRequisitos = '' . '<table class="reporte-cursos RAsig-table" align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><thead><tr><th width="8%"><strong>CURSO</strong></th><th width="82%" align="left"><strong>NOMBRE</strong></th><th width="10%" align="left"><strong>PRERREQUITO</strong></th></tr></thead><tbody>';
                    $i = 1;

                    $or_Requirements = $value[cOr];
                    while(list($keyIndex,$courseArray) = each($or_Requirements)) {
                        $tablaRequisitosDet = '';

                        $tablaRequisitosDet = '<tr>' . '<td width="8%" >' . $courseArray[0]['course'] . '</td>' . '<td width="82%" align="left" >' . $courseArray[0]['name'] . '</td>' . '<td width="10%" align="left" >Opcional</td></tr>';

                        $tablaRequisitos = $tablaRequisitos . $tablaRequisitosDet;
                        $i ++;
                    }

                    $and_Requirements = $value[cAnd];
                    while(list($keyIndex,$courseArray) = each($and_Requirements)) {
                        $tablaRequisitosDet = '';

                        $tablaRequisitosDet = '<tr>' . '<td width="8%" >' . $courseArray[0]['course'] . '</td>' . '<td width="82%" align="left" >' . $courseArray[0]['name']  . '</td>' . '<td width="10%" align="left" >No aprobado</td></tr>';

                        $tablaRequisitos = $tablaRequisitos . $tablaRequisitosDet;
                        $i ++;
                    }

                    $tablaRequisitos = $tablaRequisitos . '</tbody></table>';
                    $str21 = $str21 . $tablaRequisitos . '</li>';
                    break;
                case CURSO_TRASLAPE:
                    $str22 = '<li><span><table><tr><td align="center"><img src="../../resources/images/error.png"  width="20" height="20"></td><td>&nbsp&nbsp' . $msg[202] . '</td></tr></table></span>';
                    $str22 = $str22 .'</li><li>';
                    $str22 = $str22 . '<table width="100%" align="center"><tr><td align="center"><table><tr><td>[<font color="#3d3d3d">Clase Magistral</font>]</td><td>|</td><td><font color="#0000FF">[Laboratorio]</font></td><td>| <font color="#008000">[Práctica]</font></td><td>| <font color="#FF00CC">[Tutoria]</font></td></tr></table></td></tr></table>';
                    $tablaHorario = '' . '<table class="reporte-cursos RAsig-table" align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><thead><tr><th width="5%" align="left">CURSO</th><th width="81%" align="left">NOMBRE</th><th width="2%" align="center">L</th><th width="2%" align="center">M</th><th width="2%" align="center">M</th><th width="2%" align="center">J</th><th width="2%" align="center">V</th><th width="2%" align="center">S</th><th width="2%" align="center">D</th></tr></thead><tbody>';
                    $i = 1;
                    while(list($courseKey,$courseScheduleInt) = each($value)) {
                        list($coursePos,$courseIndex,$courseId,$courseScheduleType) = explode(':',$courseKey);
                        $tableHorarioDet = '';

                        if((($assignation[$coursePos]['course'] + 0)== $courseId) and $assignation[$coursePos]['cindex'] == $courseIndex) {

                            switch ($courseScheduleType) {
                                case 1:
                                    $cellColor = "#3d3d3d";
                                    break;
                                case 2:
                                    $cellColor = "#0000FF";
                                    break;
                                case 3:
                                    $cellColor = "#008000";
                                    break;
                                case 4:
                                    $cellColor = "#FF00CC";
                                    break;
                                case 5:
                                    $cellColor = "#FF0000";
                                    break;
                            }

                            $courseName = $assignation[$coursePos]['name'];

                            $tableHorarioDet = '' . '<tr>' . '<td width="5%" align="left" ><font color="' . $cellColor . '">' . $courseId . '</font></td>' . '<td width="81%" align="left" ><font color="' . $cellColor . '">' . $courseName . '</font></td>';

                            $tableHorarioDet = (isset($courseScheduleInt['mon'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';
                            $tableHorarioDet = (isset($courseScheduleInt['tue'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';
                            $tableHorarioDet = (isset($courseScheduleInt['wed'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';
                            $tableHorarioDet = (isset($courseScheduleInt['thu'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';
                            $tableHorarioDet = (isset($courseScheduleInt['fri'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';
                            $tableHorarioDet = (isset($courseScheduleInt['sat'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';
                            $tableHorarioDet = (isset($courseScheduleInt['sun'])) ? $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">X</font></td>' : $tableHorarioDet.''.'<td  width="2%" align="left"><font color="' . $cellColor . '">-</font></td>';

                            $tableHorarioDet = $tableHorarioDet . '</tr>';
                        }

                        $tablaHorario = $tablaHorario . $tableHorarioDet;
                        $i++;
                    }

                    $tablaHorario = $tablaHorario . '</tbody>' . '</table>';
                    $str22 = $str22 . $tablaHorario . '</li>';
                    break;
                case REPITENCIA_TOPADA:
                    $str23 = '<li><span><table><tr><td align="center"><img src="../../resources/images/error.png"  width="20" height="20"></td><td>&nbsp&nbsp' . $msg[203] . '</td></tr></table></span></li>';
                    break;
                case CURSO_ASIGNADO_CARRERA_SIMULTANEA:
                    $str24 = '<li><span><table><tr><td align="center"><img src="../../resources/images/error.png"  width="20" height="20"></td><td>&nbsp&nbsp' . $msg[204] . '</td></tr></table></span></li>';
                    break;
                case CUPO_COMPLETO:
                    $str25 = '<li><span><table><tr><td align="center"><img src="../../resources/images/error.png"  width="20" height="20"></td><td>&nbsp&nbsp' . $msg[208] . '</td></tr></table></span></li>';
                    break;
                case CERTIFICADO_CALUSAC:
                    $str26 = '<li><span><table><tr><td align="center"><img src="../../resources/images/error.png"  width="20" height="20"></td><td>&nbsp&nbsp' . $msg[303] . '</td></tr></table></span></li>';
                    break;
            }
        }
        $str1 = $str1 . $str21 . $str22 . $str23 . $str25. $str26. '</ul>' ;
    }

    $param4 = $str1;
    $param5 = $assignation[$x]['indice'];
    $param6 = $assignation[$x]['credits'];
    $param7 = sprintf("");
    $param8 = '';
    $param9 = $assignation[$x]['requirement'];
    $param10 = $assignation[$x]['check'];
    $param11 = $assignation[$x]['assigned'];
    $param12 = $assignation[$x]['lab']; // Indica si el curso tiene lab, este mapeo se hace cuando se jala la asignacion y se verifica que la seccion del lab sea diferente a cadena vacia
    $param13 = $assignation[$x]['labgroup']; // Indica la la seccion donde esta asignado el lab

    $tpl->newBlock('ADDREGISTRO');
    $tpl->assign('aAddRegistro',"enviarInfojs(1,1,'".$x."','".$param0."','".$param1."','".$param2."','".$param3."','".$param4."','".$param5."','".$param6."','".$param7."','".$param8."','".$param9."','".$param10."','".$param11."','".$param12."','".$param13."');");

}

$tpl->printToScreen();
unset($tpl);

?>