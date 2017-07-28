<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 30/09/14
 * Time: 08:21 AM
 */

include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");

session_start();

if (isset($_SESSION["sObjNotas"])) unset($_SESSION["sObjNotas"]);
if (isset($_SESSION["sActaManual"])) unset($_SESSION["sActaManual"]);
if (isset($_SESSION["sNotas"])) unset($_SESSION["sNotas"]);
if (isset($_SESSION["Nombre"])) unset($_SESSION["Nombre"]);
if (isset($_SESSION["Apellido"])) unset($_SESSION["Apellido"]);
if (isset($_SESSION["Acta"])) unset($_SESSION["Acta"]);
if (isset($_SESSION["sVectorAprobacion"])) unset($_SESSION["sVectorAprobacion"]);
if (isset($_SESSION["labfinal"])) unset($_SESSION["labfinal"]);
if (isset($_SESSION["sIngresoArchivo"])) unset($_SESSION["sIngresoArchivo"]);

if (isset($_POST["periodo"])) {
    $periodo = $_POST["periodo"];
    $anio = $_POST["anio"];
    $_SESSION["sPeriodo"] = $periodo;
    $_SESSION["sAnio"] = $anio;
}

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$obj_cad = new ManejoString();
$periodo = $_SESSION["sPeriodo"];
$anio = $_SESSION["sAnio"];

$base = NEW DB_Connection();
$base->connect();

$listado = new D_LoadNotesScheduleManager();
$numero_filas = $listado->DarListadoDeCursos($periodo, $anio, $objuser->getId());

//  Este vector contiene los recuperados, luego de asignarles los enlaces con la acciones que van a poder realizarse
$vListadoCursos = array();

/** INICIO: INGRESO DE NOTAS DE FINALES **/
if ($numero_filas > 0) {
    for ($i = 1; $i <= $numero_filas; $i++) {
        $_SESSION["sConexion"]->next_record();

        $codigo = $_SESSION["sConexion"]->f('idcourse');
        $index = $_SESSION["sConexion"]->f('index');
        $nombre = $_SESSION["sConexion"]->f('name');
        $seccion = trim($_SESSION["sConexion"]->f('section'));
        $catedratico = $objuser->getId();

        //printf("%d %s %s <br>",$index,$codigo,$seccion);

        switch ($_SESSION["sConexion"]->f('state')) {
            case 2 :
            case 3 :
                $descripcion = "INGRESO VIA WEB";
                break;
            case 4 :
                $descripcion = "REINGRESO VIA WEB";
                break;
            case 5 :
                $descripcion = "APROBADA";
                break;
            case 6 :
                $descripcion = "APROBADA EN ESPERA";
                break;
            default:
                $descripcion = "APROBADA";
        }

        $secciont = $seccion;

        if (strlen($seccion) == 2) {
            $signo = substr($seccion, 1, 1);
            if ($signo == '+') $seccion[1] = '*';
        }

        $parametros = '?curso=' . $codigo . '&seccion=' . $seccion . '&index=' . $index;

        $vRegistroCurso = array(
            'cur' => $codigo,
            'idx' => $index,
            'nom' => $nombre,
            'sec' => $seccion,
            'per' => $catedratico,
            'acc' => array());

        if ($listado->AsignadosDelCurso($base, $anio, $periodo, $codigo, trim($secciont), $index) == 0) {
            $vRegistroCurso['des'] = "Sin Estudiantes Asignados";
        } else {
            $vRegistroCurso['des'] = $descripcion;
            $vRegistroCurso['acc'][0] = sprintf('D_CourseInformationReview.php%s', $parametros);
        }
        array_push($vListadoCursos, $vRegistroCurso);
        unset($vRegistroCurso);
    }
}
//die;
/** FIN: INGRESO DE NOTAS DE FINALES **/

/** INICIO: INGRESO DE NOTAS DE ACTIVIDADES **/
$_SESSION['regper'] = $objuser->getId();
$numero_filas1 = $listado->DarListadoDeCursos1($periodo, $anio, $objuser->getId());

if ($numero_filas1 > 0) {
    for ($i = 1; $i <= $numero_filas1; $i++) {
        $_SESSION["sConexion"]->next_record();
        $codigo = $_SESSION["sConexion"]->f('idcourse');
        $index = $_SESSION["sConexion"]->f('index');
        $nombre = $_SESSION["sConexion"]->f('name');
        $seccion = trim($_SESSION["sConexion"]->f('section'));
        $catedratico = $objuser->getId();

        switch ($_SESSION["sConexion"]->f('idscheduletype')) {
            case 1 :
                $descripcion = "CLASE MAGISTRAL";
                break;
            case 2 :
                $descripcion = "LABORATORIO";
                break;
            default:
                $descripcion = "LABORATORIO";
        }

        $secciont = $seccion;

        if (strlen($seccion) == 2) {
            $signo = substr($seccion, 1, 1);
            if ($signo == '+') $seccion[1] = '*';
        }

        $parametros = 'opcion=9&curso=' . $codigo . '&seccion=' . $seccion . '&index=' . $index;

        for ($i = 0; $i < count($vListadoCursos); $i++) {
            if ($vListadoCursos[$i]['cur'] == $codigo AND $vListadoCursos[$i]['idx'] == $index) {
                $vListadoCursos[$i]['acc'][$_SESSION["sConexion"]->f('idscheduletype')] = sprintf('../notas-actividades/creaactividad.php?%s', $parametros);
                break;
            }
        }
    }
}
/** FIN: INGRESO DE NOTAS DE ACTIVIDADES **/

/** Crear la tabla de cursos recuperados **/
if (count($vListadoCursos)) {
    $tCursos = '';
    $tCursos = "<table align='center' width='100%' cellspacing='0' cellpadding='0'>";
    $tCursos .=
        "<thead>
     <tr><td class='encabezado' colspan='5' align='center'>INGRESO DE NOTAS</td></tr>
     <tr><td>Curso</td><td>Nombre</td><td align='center'>Sección</td><td>Estado</td><td align='center'>Carga</td></tr>
    </thead>
    <tbody>";

    for ($i = 0; $i < count($vListadoCursos); $i++) {
        $lAcciones = '';
        $lAcciones .= '<nav><ul><li><a href="#">Carga de notas<div id="down-triangle"></div></a><ul>';

        $vAcciones = $vListadoCursos[$i]['acc'];

        if(count($vAcciones)) {
            while (list($key, $value) = each($vAcciones)) {
                switch ($key) {
                    case 1 :
                        $lAcciones .= '<li><a href="#"> Actividades CM<div class="circle"></div></a>';
                        break;
                    case 2 :
                        $lAcciones .= '<li><a href="#"> Actividades LB<div class="circle"></div></a>';
                        break;
                    default:
                        $lAcciones .= '<li><a href="#"> Examen final<div class="circle"></div></a>';
                }
            }
        }

        $lAcciones .= '</ul></li></ul></nav>';

        $tCursos .= sprintf("<tr><td>%s</td><td>%s</td><td align='center'>%s</td><td>%s</td><td align='center'>%s</td></tr>",
            $vListadoCursos[$i]['cur'],
            $vListadoCursos[$i]['nom'],
            $vListadoCursos[$i]['sec'],
            $vListadoCursos[$i]['des'],
            $lAcciones);

    }

    $tCursos .= '</tbody></table>';
    $contenidoResultado = '<div id="dynbody" class="restrict_right">' . $tCursos . '</div>';

} else {
    $contenidoResultado = '<div id="dynbody" class="restrict_right"><div id="notesrow2"><textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">No tiene cursos para realizar carga de notas, para el período correspondiente a ' . $obj_cad->funTextoPeriodo($periodo) . ' ' . $anio . '.</textarea></div></div>';
}


$result = '<div id="sitebody">';
$result = $result . '<br><hr>';
$result = $result . '<div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"><span class="page_time_label"> Fecha: ' . Date("d-m-Y") . '&nbsp;&nbsp;Hora: ' . Date("H:i") . ' </span>
                                </div></div>';
$result = $result . '<hr>';
$result = $result . '<div id="notesrow1" class="siterowtop section_hdg"><span id="_docwrite_site14">Resultado:</span></div>';
$result = $result . '<div id="dynheader" class="restrict_right"></div>';
$result = $result . $contenidoResultado;
$result = $result . '<div><hr></div>';
$result = $result . '<br><br>';
$result = $result . '</div>';

$tpl = new TemplatePower("D_CourseList.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");
$tpl->assignInclude("icontenido",$result,T_BYVAR);

$tpl->prepare();
$tpl->printToScreen();

echo($result);
unset($vListadoCursos, $obj_cad, $tpl);
?>
