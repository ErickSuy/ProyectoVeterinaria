<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 5/10/14
 * Time: 10:38 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/model/mapping/TbPrivilege.php");

session_start();
$tpl = new TemplatePower("../includes/menu.tpl");
$tpl->prepare();

function isPrivilege($nameprivilege, $privileges)
{
    for ($i = 0; $i < count($privileges); $i++) {
        $privilege = $privileges[$i];
        if ($privilege->getName() == $nameprivilege) {
            return true;
        }
    }
    return false;
}

$objuser = unserialize($_SESSION['usuario']);
$privileges = $objuser->getPrivileges();

$vMenu = '';
$vMenu = '<div id="menu_acc" class="easyui-accordion" style="width:238px;">';
if ($objuser->getGroup() == GRUPO_ESTUDIANTE) {
    if (isPrivilege("INFORMACION PERSONAL", $privileges)) {
        $vMenu = $vMenu .
        '<div title="Usuario" iconCls="fa fa-user" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/ViewProfileInfo.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Consultar datos</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/UpdateProfileInfo.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Modificar datos</td>
                                    </span></tr>
                            </table>
                        </a></li>
                </ul>
            </div>
        </div>';
    }

    if (isPrivilege("ASIGNACION DE CURSOS", $privileges)) {
        $vMenu = $vMenu .
        '<div title="Asignación" iconCls="fa fa-pencil" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/AS_AssignationRequirements.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Asignación de Semestre</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/AsignationRetrasada1.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Primera Retrasada</span></td>
                                </tr>
                            </table>
                        </a></li>
                     <li><a href="../menu/AsignationRetrasada2.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Segunda Retrasada</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/AsignationVacaciones.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Asignación de Vacaciones</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/SearchAssignedCourse.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Consulta Cursos Asignados</span></td>
                                </tr>
                            </table>
                        </a></li>
                </ul>
            </div>
        </div>';
    }
    if (isPrivilege("NOTAS DE CURSOS", $privileges)) {
        $vMenu = $vMenu .
            '<div title="Notas" iconCls="fa fa-list-alt" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/CourseList.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Listado de Cursos Aprobados</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/SearchAssignedCourseNotes.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Notas de Cursos Asignados</span></td>
                                </tr>
                            </table>
                        </a></li>
                </ul>
            </div>
        </div>';
    }
    if (isPrivilege("REPITENCIA DE CURSOS", $privileges)) {
        $vMenu = $vMenu .
            '<div title="Repitencia" iconCls="fa fa-list-ol" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/AssignationCountReport.php?type=1">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Semestre</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/AssignationCountReport.php?type=2">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Vacaciones</span></td>
                                </tr>
                            </table>
                        </a></li>
                    <li><a href="../menu/AssignationCountReport.php?type=-1">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Semestre y Vacaciones</span></td>
                                </tr>
                            </table>
                        </a></li>
                </ul>
            </div>
        </div>';
    }

    if (isPrivilege("HORARIO DE CURSOS", $privileges)) {
        $vMenu = $vMenu .
        '<div title="Horario" iconCls="fa fa-bell"  style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li>
                        <a href="../menu/CourseSchedule.php?init=1">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Consulta de horarios</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
    }

    if (isPrivilege("CALENDARIO LABORES", $privileges)) {
        $vMenu = $vMenu .
            '<div title="Calendario" iconCls="fa fa-calendar" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li>
                        <a href="#">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Consulta de calendario</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
    }

    if (isPrivilege("VARIOS", $privileges)) {
        $vMenu = $vMenu .
            '<div title="Varios" iconCls="fa fa-plus" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <ul>
                        <li><a href="../menu/Enrollment.php">
                                <table>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o">
                                        </td>
                                        <td><span>&nbsp;Inscripción</span></td>
                                    </tr>
                                </table>
                            </a></li>
                    </ul>
            </div>
        </div>';
    }
} else {
    if($objuser->getGroup()==GRUPO_DOCENTE) {
        if (isPrivilege("INFORMACION PERSONAL", $privileges)) {
            $vMenu = $vMenu .
                '<div title="&nbsp;Usuario" iconCls="fa fa-info-circle" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/D_ViewProfileInfo.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Datos Personales</span></td>
                                </tr>
                            </table>
                        </a></li>
                        <li><a href="../menu/D_UpdateProfileInfo.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Modificar datos</td>
                                    </span></tr>
                            </table>
                        </a></li>
                        <li><a href="../menu/D_ChangePassword.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Modificar contraseña</td>
                                    </span></tr>
                            </table>
                        </a></li>
                </ul>
            </div>
        </div>';
        }

        if (isPrivilege("CARGA DE NOTAS DE CURSOS", $privileges)) {
            $vMenu = $vMenu .
                '<div title="&nbsp;Notas" iconCls="fa fa-bar-chart" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/D_InitLoadNotes.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Carga de Notas</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
        }

        if (isPrivilege("CARGA ACADEMICA", $privileges)) {
            $vMenu = $vMenu .
                '<div title="&nbsp;Cursos" iconCls="fa fa-book" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li><a href="../menu/D_StudentReport.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></td>
                                    <td><span>&nbsp;Estudiantes Asignados</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
        }

        if (isPrivilege("HORARIO DE CURSOS", $privileges)) {
            $vMenu = $vMenu .
                '<div title="&nbsp;Horarios" iconCls="fa fa-bell"  style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li>
                        <a href="../menu/CourseSchedule.php?init=1">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Consulta de horarios</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
        }

        if (isPrivilege("CALENDARIO LABORES", $privileges)) {
            $vMenu = $vMenu .
                '<div title="&nbsp;Calendario" iconCls="fa fa-calendar" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li>
                        <a href="#">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Consulta de calendario</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
        }
    } else{
        if($objuser->getGroup()==GRUPO_CONTROL_ACADEMICO) {
            if (isPrivilege("ACTA DE CURSO", $privileges)) {
                $vMenu = $vMenu .
                    '<div title="&nbsp;Actas" iconCls="fa fa-calendar" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li>
                        <a href="../menu/D_InitLoadNotes.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Acta de Curso</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
            }
        if (isPrivilege("CERTIFICACION DE CURSOS", $privileges)) {
                $vMenu = $vMenu .
                    '<div title="&nbsp;Certificaciones" iconCls="fa fa-bars" style="overflow:auto;padding:10px;">
            <div id="main-menu-v">
                <ul class="main-menu">
                    <li>
                        <a href="../menu/COAC_CertificateGeneration.php">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dot-circle-o"></i></td>
                                    <td><span>&nbsp;Certificacion de Cursos</span></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                </ul>
            </div>
        </div>';
            }
        }
    }
}

$vMenu = $vMenu . '</div></div>';

$tpl->assign("menu",$vMenu);
$tpl->printToScreen();
unset($tpl);
?>

