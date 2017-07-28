<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 28/05/2015
 * Time: 06:58 PM
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
} else {
    if (isset($_REQUEST["periodo"])) {
        $periodo = $_REQUEST["periodo"];
        $anio = $_REQUEST["anio"];
        $_SESSION["sPeriodo"] = $periodo;
        $_SESSION["sAnio"] = $anio;
    }
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
$numero_filas = $listado->COAC_DarListadoDeCursos($anio, $periodo);

//  Este vector contiene los recuperados, luego de asignarles los enlaces con la acciones que van a poder realizarse
$vListadoCursos = array();

/** INICIO: INGRESO DE NOTAS DE FINALES **/
if ($numero_filas > 0) {
    for ($i = 1; $i <= $numero_filas; $i++) {
        $_SESSION["sConexion"]->next_record();

        $codigo = $_SESSION["sConexion"]->f('idcourse');
        $index = 1;
        $nombre = $_SESSION["sConexion"]->f('coursename');
        // Para no tomar en cuenta secciones
        //$seccion = trim($_SESSION["sConexion"]->f('section'));
        $carrera = trim($_SESSION["sConexion"]->f('idcareer'));
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

        // Original con seccion
        //$parametros = '?curso=' . $codigo . '&seccion=' . $seccion . '&index=' . $index;
        $parametros = '?curso=' . $codigo . '&carrera=' . $carrera . '&index=' . $index;

        $vRegistroCurso = array(
            'cur' => $codigo,
            'idx' => $index,
            'nom' => $nombre,
            //'sec' => $seccion,
            'car' => $carrera,
            'per' => $catedratico,
            'acc' => array());

        if ($listado->AsignadosDelCurso($base, $anio, $periodo, $codigo,$carrera /*trim($secciont)*/, $index) == 0) {
            $vRegistroCurso['des'] = "Sin Estudiantes Asignados";
        } else {
            $vRegistroCurso['des'] = $descripcion;
            $vRegistroCurso['acc'][0] = sprintf('COAC_CourseDetailInformation.php%s', $parametros);
            //echo $parametros.'<br>';
        }
        array_push($vListadoCursos, $vRegistroCurso);
        unset($vRegistroCurso);
    }
}
//die;
/** FIN: INGRESO DE NOTAS DE FINALES **/

/** INICIO: INGRESO DE NOTAS DE ACTIVIDADES **/
$_SESSION['regper'] = $objuser->getId();

/** Crear la tabla de cursos recuperados **/
if (count($vListadoCursos)) {
    $tCursos = '';
    $tCursos = "<table class='reporte-cursos' align='center' width='100%' cellspacing='0' cellpadding='0'>";
    $tCursos .=
        "<thead>
     <tr><td>CURSO</td><td>NOMBRE</td><td align='center'>CARRERA</td><td>OBSERVACIÓN</td><td align='center'>ESTADO</td></tr>
    </thead>
    <tbody>";

    for ($i = 0; $i < count($vListadoCursos); $i++) {
        $lAcciones = '';
        $vAcciones = $vListadoCursos[$i]['acc'];

        if(count($vAcciones)) {
            $lAcciones .= '<a href="#" class="easyui-menubutton" data-options="menu:\'#mm' . $i . '\'"><i class="fa fa-pencil"></i></a>';
            $lAcciones .= '<div id="mm' . $i . '" style="width:150px;">';

            while (list($key, $value) = each($vAcciones)) {
                switch ($key) {
                    case 1 :
                        $lAcciones .= '<div iconCls="fa fa-tasks" title="Carga de notas de las actividades correspondientes a la clase magistral" onclick="window.location.href = \''. $value .'\'"">Zonas del curso</div>';
                        //echo $value . '<br>';
                        break;
                    case 2 :
                        $lAcciones .= '<div iconCls="fa fa-flask" title="Carga de notas de las actividades correspondientes al laboratorio" onclick="window.location.href = \''. $value .'\'""> Laboratorio</div>';
                        break;
                    default:
                        $lAcciones .= '<div iconCls="fa fa-check" title="Carga de notas correspondientes al examen final" onclick="window.location.href = \''. $value .'\'">Ver Nota Finales</div>';
                }
            }
            $lAcciones .= '</div>';
        }

        $tCursos .= sprintf("<tr><td>%s</td><td>%s</td><td align='center'>%s</td><td>%s</td><td align='center'>%s</td></tr>",
            $vListadoCursos[$i]['cur'],
            $vListadoCursos[$i]['nom'],
            $obj_cad->StringCarrera('0'.$vListadoCursos[$i]['car']),
            $vListadoCursos[$i]['des'],
            $lAcciones);
    }

    $tCursos .= '</tbody></table>';
    $contenidoResultado = '<div id="dynbody" class="restrict_right"><div id="notesrow2">' . $tCursos . '</div></div>';

} else {
    $contenidoResultado = '<div id="dynbody" class="restrict_right"><div id="notesrow2"><textarea  disabled="disabled" id="notes" cols="60" rows="10" spellcheck="false" autocomplete="off">No tiene cursos para realizar carga de notas, para el período correspondiente a ' . $obj_cad->funTextoPeriodo($periodo) . ' ' . $anio . '.</textarea></div></div>';
}

$result = '<div id="sitebody">';
$result = $result . '<br><hr>';
$result = $result . '<div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>';
$result = $result . '<div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>';
$result = $result . '<div class="siterow"><br/><div class="siterow-center"><span>LISTADO ACTAS DE ASIGNATURAS</span></div><br/></div>';
$result = $result . '<div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label"> ' . $obj_cad->funTextoPeriodo($periodo) . '</span><span class="page_label">de: </span><span class="underline_label"> ' . $anio . '</span><span class="page_time_label"> Fecha: ' . Date("d-m-Y") . '&nbsp;&nbsp;Hora: ' . Date("H:i") . ' </span></div>';
$result = $result . '<hr>';
$result = $result . '<div id="dynheader" class="restrict_right"></div>';
$result = $result . $contenidoResultado;
$result = $result . '<div><hr></div>';
$result = $result . '<br><br>';
$result = $result . '</div>';

$tpl = new TemplatePower("D_CourseList.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");
$tpl->assignInclude("icontenido",$result,T_BYVAR);

$tpl->prepare();
$tpl->printToScreen();

unset($vListadoCursos, $obj_cad, $tpl);
?>
