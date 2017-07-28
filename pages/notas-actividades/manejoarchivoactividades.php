<?php
include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
require_once("$dir_portal/fw/model/sql/manejoarchivoactividades_SQL.php");

//Inicia Sección de Implementación de Funciones


global $gsql_na_maa;
$gsql_na_maa = new manejoarchivoactividades_SQL();
global $obj_cad;
$obj_cad = new ManejoString();

// funcion que agrega ceros al carnet si es menor al año 98
function CompletaCarnet($QCarnet)
{
    $Longitud = strlen($QCarnet);
    $NuevoCarnet = "";
    if (($Longitud <= 0) || (!is_numeric($QCarnet))) return 0;
//	echo "<br><br>carnet: $QCarnet <br>    Longitud=$Longitud";

    switch ($Longitud) {
        case 7:
//	   		   echo "<br>  Entro a longitud 7 ";	   	   
            if ($QCarnet > 9799999) {
                $NuevoCarnet = "" . "$QCarnet";
                //$NuevoCarnet = "19" . "$QCarnet";
            } else {
                $NuevoCarnet = "" . "$QCarnet";
                //$NuevoCarnet = "00" . "$QCarnet";
            }
            break;

        case 9:
//		   echo "<br>  Entro a longitud 9 ";	   
            $Anio = substr($QCarnet, 0, 4);
            $Resto = substr($QCarnet, 4, $Longitud);
//			  echo "<br>     anio= $Anio  resto=$Resto";
            if ($Anio < 1998) {
                //echo "     anio=[$Anio]  resto=[$Resto]<br>";
                $Longitud1 = strlen($Anio);
                $Anio1 = substr($Anio, 0, 2);
                $Anio2 = substr($Anio, 2, $Longitud1);
                if ($Anio1 == 0 && ($Anio2 == 98 || $Anio2 == 99)) {
                    $NuevoCarnet = "19" . $Anio2 . substr($QCarnet, 4, $Longitud);
                    //$NuevoCarnet = "19" . $Anio2 . substr($QCarnet, 4, $Longitud);
                }
                else {
                    $NuevoCarnet = "" . substr($QCarnet, 2, $Longitud);
                    //$NuevoCarnet = "00" . substr($QCarnet, 2, $Longitud);
                }
            }
            break;
        default:
//		   echo "<br>  Entro al default   ";
            for ($i = 0; $i < 9 - $Longitud; $i++) {
                $NuevoCarnet = $NuevoCarnet . "0";
            }
            $NuevoCarnet = $NuevoCarnet . $QCarnet;

    } // del case longitud


    if ($NuevoCarnet == "") {
        $NuevoCarnet = $QCarnet;
    }
//echo "con $QCarnet regreso= $NuevoCarnet   longitud=".strlen($NuevoCarnet);
    return $NuevoCarnet;
} // fin de la funcion que completa carnet

function truncaCarnet($carnet)
{
    $tmpCarnet = trim($carnet);
    if (strlen($tmpCarnet) > 9)
        $tmpCarnet = substr($tmpCarnet, 0, 9); //trunca el carné a 9 posiciones si su longitud es mayor que eso
    $tmpCarnet = CompletaCarnet($tmpCarnet);
    return $tmpCarnet;
}

function bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad, $txtEsSuperActividad, $tpl, $bd, $txtCarrera)
{
    global $gsql_na_maa;
    global $obj_cad;
    $_tituloActividad = "";
//  $SqlNombreActividad="select trim(t.nombre) as nombre_t,trim(a.nombre) as nombre_a" .
//                      " from ing_tipoactividad t,ing_actividad a where a.tipoactividad=t.idtipoactividad" .
//					  " and a.periodo='" . $txtPeriodo . "' and a.anio=" . $txtAnio . " and a.curso='" . $txtCurso. 
//					  "' and a.seccion='" . $txtSeccion . "' and a.regper='" . $_SESSION['regper'] . 
//					  "' and t.idtipoactividad=" . $txtTipoActividad . " and a.idactividad=" . $txtIdActividad;

    $SqlNombreActividad = $gsql_na_maa->bloqueCargaNotas_select1($txtPeriodo, $txtAnio, $txtCurso, $txtCarrera/*$txtSeccion*/, $_SESSION['regper'], $txtTipoActividad, $txtIdActividad);
    $bd->query($SqlNombreActividad);
    if ($bd->num_rows() > 0) {
        $bd->next_record();
        $FilaDato = $bd->r();
        $ponderacionActividad = $FilaDato["ponderacion"];
        if ($FilaDato["nombre_a"] != ""){
            $_tituloActividad = $FilaDato["nombre_a"];
        }
        else{
            $_tituloActividad = $FilaDato["nombre_t"];
        }
    }
    if ($_tituloActividad != "")
        $_tituloActividad = "DE " . $_tituloActividad;

    $tpl->newblock("carganotas");
    $tpl->assign("vCurso", $txtCurso);
    $tpl->assign("vNombre", $_SESSION["nombrecorto"]);
    $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $txtCarrera));
    $tpl->assign("vPeriodo", $_SESSION["nombreperiodo"]);
    $tpl->assign("vAnio", $txtAnio);
    $tpl->assign("vFecha", Date("d-m-Y"));
    $tpl->assign("vHora", Date("H:i"));
    $tpl->assign("txtTituloPagina", $_SESSION[TituloPagina]);
    $tpl->assign("txtCurso", $txtCurso);
    $tpl->assign("txtSeccion", $txtSeccion);
    $tpl->assign("txtCarrera", $txtCarrera);
    $tpl->assign("txtPeriodo", $txtPeriodo);
    $tpl->assign("txtAnio", $txtAnio);
    $tpl->assign("txtTipoActividad", $txtTipoActividad);
    $tpl->assign("tituloActividad", $_tituloActividad);
    $tpl->assign("txtIdActividad", $txtIdActividad);
    $tpl->assign("txtEsSuperActividad", $txtEsSuperActividad);
    $tpl->assign("txtPonderacionActividad", (int)$ponderacionActividad);
}


// funcion que verifica si ya existen notas ingresadas de los estudiantes
function notasYaExistentes($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad, $txtEsSuperActividad, $tpl, $bd)
{
    global $gsql_na_maa;
    $_vector = array();

    $sqlQuery = $gsql_na_maa->notasYaExistentes_select1($txtTipoActividad, $txtAnio, $txtPeriodo, $txtCurso);

    $bd->query($sqlQuery);
    $total = $bd->num_rows();
    if ($total > 0) {
        for ($i = 0; $i < $total; $i++) {
            $bd->next_record();
            $FilaDato = $bd->r();
            $_vector[$FilaDato["carnet"]]["seccion"] = $FilaDato["seccion"];
            $_vector[$FilaDato["carnet"]]["nota"] = $FilaDato["nota"];
            $_vector[$FilaDato["carnet"]]["secactividad"] = $FilaDato["secactividad"];
        }
    }
    return $_vector;
}


// funcion que valida si la nota ingresada es mayor o menor que la existente en la base de datos.
function verificaNotasExistentes($_notasExistentes, $carnet, $nota, $txtSeccion, &$_erroresManejables1, &$_listaErrores1)
{
    $cambiarNota = true;
    if (sizeof($_notasExistentes) > 0) {
        if (isset($_notasExistentes[$carnet])) {
            if ($_notasExistentes[$carnet]["nota"] > $nota) {
                if ($_notasExistentes[$carnet]["secactividad"] != $txtSeccion) {
                    $_listaErrores1[$_erroresManejables1]["carnet"] = $carnet;
                    $_listaErrores1[$_erroresManejables1]["nota_existente"] = $_notasExistentes[$carnet]["nota"];
                    $_listaErrores1[$_erroresManejables1]["nueva_nota"] = $nota;
                    $_listaErrores1[$_erroresManejables1]["caso"] = 1;
                    $_erroresManejables1++;
                    $cambiarNota = false;
                } else {
                    $_listaErrores1[$_erroresManejables1]["carnet"] = $carnet;
                    $_listaErrores1[$_erroresManejables1]["nota_existente"] = $_notasExistentes[$carnet]["nota"];
                    $_listaErrores1[$_erroresManejables1]["nueva_nota"] = $nota;
                    $_listaErrores1[$_erroresManejables1]["caso"] = 2;
                    $_erroresManejables1++;
                }
            } else { //nota existente menor que nueva nota que se esta procesando
                if ($_notasExistentes[$carnet]["nota"] > 0) {
                    if ($_notasExistentes[$carnet]["secactividad"] != $txtSeccion) {
                        $_listaErrores1[$_erroresManejables1]["carnet"] = $carnet;
                        $_listaErrores1[$_erroresManejables1]["nota_existente"] = $_notasExistentes[$carnet]["nota"];
                        $_listaErrores1[$_erroresManejables1]["nueva_nota"] = $nota;
                        $_listaErrores1[$_erroresManejables1]["caso"] = 3;
                        $_erroresManejables1++;
                    }
                }
            }
        }
    }
    return $cambiarNota;
}

function guardaNotasNoAsignados($bd, $_noAsignados, $txtCurso, $txtTipoActividad, $txtPeriodo, $txtAnio)
{
    global $gsql_na_maa;
    return false; //IMPORTANTE!!!  Eliminar esta línea si autorizan que se guarden las notas de los no asignados
    $_ocurrioError = false;
    $tamVector = sizeof($_noAsignados);
    if ($tamVector > 0) {
        $tablaTemp = "ing_practicasnoasignados";
//    $sqlQuery="create temp table " . $tablaTemp . " as select * from ing_notasactividadesguardadas where carnet='0000000000'";

        $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_create1($tablaTemp);

        $resultado = $bd->query($sqlQuery);
        if (!$resultado || $resultado === false)
            $_ocurrioError = true;
        else {
            $resultado = pg_copy_from($bd->Link_ID, $tablaTemp, $_noAsignados, "|");
            if ($resultado === false)
                $_ocurrioError = true;
            else {
                /*
                IMPORTANTE: No es factible eliminar las notas de (TODOS) los no asignados, de la tabla ing_notasactividadesguardadas que hayan sido procesadas durante el período/año del curso para el que se procesan notas de actividades debido a que dichas notas pudieron haber sido procesadas por distintos encargados de las prácticas, sin embargo, se podría analizar la posibilidad de eliminar únicamente las notas de aquellos que se están incluyendo en un archivo específico para los que ya existan notas procesadas en ese período/año en dicha tabla. Por el momento dicha funcionalidad no será incluida en esta versión.
                */

                // 1. Trasladar las notas de los no asignados al segmento de bitácora; tomando en cuenta carnet, curso y tipoactividad para las
                //    que hayan caducado la vigencia. El mejor lugar para realizar esta tarea es en la página de habilitación del sistema, igual
                //    que el módulo de ingreso de notas de examen final.

                // 2. Buscar las notas de los no asignados, pero que ya existen con el mismo valor en ing_notasactividadesguardadas y eliminarlas
                //    de la tabla temporal en donde se almacenen los no asignados.

                // 3. Buscar las notas de los no asignados, pero que ya existen con un valor mayor en ing_notasactividadesguardadas y eliminarlas
                //    de la tabla temporal en dónde se almacenen los no asignados. Esta consulta podría fusionarse con la anterior.
                //    Al fusionar los dos pasos anteriores, se podrí utilizar la siguiente operacion de bases de datos:
                //    delete from ing_practicasnoasignados using ing_notasactividadesguardadas i where ing_practicasnoasignados.carnet=i.carnet and ing_practicasnoasignados.curso=i.curso and ing_practicasnoasignados.tipoactividad=i.tipoactividad and ing_practicasnoasignados.nota<=i.nota;

                // 4. Buscar las notas de los no asignados, pero que ya existen con un valor menor en ing_notasactividadesguardadas y si el
                //    periodoinicio/anioinicio es distinto al periodo/anio que se está procesando entonces trasladarlas a la bitacora desde la
                //    tabla ing_notasacti... y luego eliminarlas de dicha tabla. Para ello se pueden usar las siguientes operaciones, verificando
                //    que ambos resultados sean los mismos, para asegurar que no existiá error. Del resultado de la primera operación depende
                //    la necesidad de ejecutar la segunda:
                /*

                insert into ing_bitacoranotasactividadesguardadas
                select distinct i.*
                from ing_practicasnoasignados p, ing_notasactividadesguardadas i
                where p.carnet=i.carnet and p.curso=i.curso and p.tipoactividad=i.tipoactividad
                and i.nota<p.nota;

                delete
                from ing_notasactividadesguardadas using ing_practicasnoasignados p
                where p.carnet=ing_notasactividadesguardadas.carnet and p.curso=ing_notasactividadesguardadas.curso
                and p.tipoactividad=ing_notasactividadesguardadas.tipoactividad and ing_notasactividadesguardadas.nota<p.nota;
                */

                // 5. Buscar las notas de los no asignados, pero que ya existen con un valor menor en ing_notasactividadesguardadas y si el
                //    periodoinicio/anioinicio es igual al periodo/anio que se está procesando entonces actualizarlas en ing_notasactivid...
                //    y luego eliminarlos de la tabla temporal. Del resultado de la primera operación depende la ejecución de la segunda

                /*
                update ing_notasactividadesguardadas set nota=p.nota
                from ing_practicasnoasignados p
                where p.carnet=ing_notasactividadesguardadas.carnet and p.curso=ing_notasactividadesguardadas.curso
                and p.tipoactividad=ing_notasactividadesguardadas.tipoactividad
                and p.periodoinicio=ing_notasactividadesguardadas.periodoinicio and p.anioinicio=ing_notasactividadesguardadas.anioinicio
                and ing_notasactividadesguardadas.nota<p.nota;

                delete from ing_practicasnoasignados
                using ing_notasactividadesguardadas i
                where ing_practicasnoasignados.carnet=i.carnet and ing_practicasnoasignados.curso=i.curso
                and ing_practicasnoasignados.tipoactividad=i.tipoactividad
                and ing_practicasnoasignados.periodoinicio=i.periodoinicio and ing_practicasnoasignados.anioinicio=i.anioinicio
                and i.nota>=ing_practicasnoasignados.nota;
                */


                // 6. Buscar las notas de los no asignados, que aún no existen en la tabla ing_notasactividadesguardadas e insertarlas en dicha
                //    tabla eliminándolas posteriormente de la tabla temporal; tomando en cuenta para ello carnet, curso y tipoactividad.
                /*
                insert into ing_notasactividadesguardadas
                select distinct p.* from ing_practicasnoasignados p,
                (select carnet,curso,tipoactividad from ing_practicasnoasignados
                except select carnet,curso,tipoactividad from ing_notasactividadesguardadas) as t
                where p.carnet=t.carnet and p.curso=t.curso and p.tipoactividad=t.tipoactividad
                */
                //Paso 1. Ese se hará en el módulo de habilitación del sistema.
                //Pasos 2 y 3. Borrar notas de no asignados iguales en ambas tablas o menores en la tabla temporal
//        $sqlQuery = "delete from " . $tablaTemp . " using ing_notasactividadesguardadas i where " . $tablaTemp . ".carnet=i.carnet" .
//		            " and " . $tablaTemp . ".curso=i.curso and " . $tablaTemp . ".tipoactividad=i.tipoactividad" .
//					" and " . $tablaTemp. ".nota<=i.nota;";

                $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_delete1($tablaTemp);

                $resultado = $bd->query($sqlQuery);
                $total = $bd->affected_rows();
                if ($total > 0) {

                }
                //Paso 4. Trasladar a bitacora notas menores de la tabla ing_notasactivida... con relación a las notas de la tabla temporal
//        $sqlQuery = "insert into ing_bitacoranotasactividadesguardadas (select distinct i.* from " . $tablaTemp .
//		            " p, ing_notasactividadesguardadas i where p.carnet=i.carnet and p.curso=i.curso" .
//					" and p.tipoactividad=i.tipoactividad and i.nota<p.nota)";

                $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_insert1($tablaTemp);

                $resultado = $bd->query($sqlQuery);
                $total = $bd->affected_rows();
                if ($total > 0) {
//          $sqlQuery = "delete from ing_notasactividadesguardadas using " . $tablaTemp . 
//		              " p where p.carnet=ing_notasactividadesguardadas.carnet and p.curso=ing_notasactividadesguardadas.curso" .
//					  " and p.tipoactividad=ing_notasactividadesguardadas.tipoactividad and ing_notasactividadesguardadas.nota<p.nota";

                    $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_delete2($tablaTemp);

                    $resultado = $bd->query($sqlQuery);
                    //Analizar si es necesario, adecuado y factible comparar los resultados de las dos operaciones de bases de datos anteriores
                    //para asegurar que no se presentaron errores inesperados.
                }
                //Paso 5. Actualizar notas menores en ing_notasactividad... por notas mayores en la tabla temporal
//		$sqlQuery = "update ing_notasactividadesguardadas set nota=p.nota from " . $tablaTemp . 
//		            " p where p.carnet=ing_notasactividadesguardadas.carnet and p.curso=ing_notasactividadesguardadas.curso" .
//					" and p.tipoactividad=ing_notasactividadesguardadas.tipoactividad" .
//					" and p.periodoinicio=ing_notasactividadesguardadas.periodoinicio" .
//					" and p.anioinicio=ing_notasactividadesguardadas.anioinicio and ing_notasactividadesguardadas.nota<p.nota";

                $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_update1($tablaTemp);

                $resultado = $bd->query($sqlQuery);
                $total = $bd->affected_rows();
                if ($total > 0) {
//          $sqlQuery = "delete from " . $tablaTemp . " using ing_notasactividadesguardadas i where " . $tablaTemp . ".carnet=i.carnet" .
//		              " and " . $tablaTemp . ".curso=i.curso and " . $tablaTemp . ".tipoactividad=i.tipoactividad" .
//					  " and " . $tablaTemp . ".periodoinicio=i.periodoinicio and " . $tablaTemp . ".anioinicio=i.anioinicio" .
//					  " and i.nota>=" . $tablaTemp . ".nota";

                    $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_delete3($tablaTemp);

                    $resultado = $bd->query($sqlQuery);
                    //Analizar si es necesario, adecuado y factible comparar los resultados de las dos operaciones de bases de datos anteriores
                    //para asegurar que no se presentaron errores inesperados.
                }
                //Paso 6. Insertar en ing_notasactividad... los registros de la tabla temporal que no existen en dicha tabla (la primera)
//        $sqlQuery = "insert into ing_notasactividadesguardadas (select distinct p.* from " . $tablaTemp . " p," .
//		            " (select carnet,curso,tipoactividad from " . $tablaTemp . " except select carnet,curso,tipoactividad" .
//					" from ing_notasactividadesguardadas) as t where p.carnet=t.carnet and p.curso=t.curso" .
//					" and p.tipoactividad=t.tipoactividad)";

                $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_insert2($tablaTemp);

                $resultado = $bd->query($sqlQuery);
                $total = $bd->affected_rows();
                if ($total > 0) {

                }

            }
//      $sqlQuery="drop table " . $tablaTemp;

            $sqlQuery = $gsql_na_maa->guardaNotasNoAsignados_drop1($tablaTemp);

            $resultado = $bd->query($sqlQuery);
            if ($resultado === false)
                $_ocurrioError = true;
        }
    }
    return $_ocurrioError;
}

$opcion = $_GET['opcion'];

$_verificarSesion = true;
session_start();

require "conectar.php";
$tpl = new TemplatePower("manejoarchivoactividades.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$txtSeccion = str_replace("*", "+", "$txtSeccion");

if (($opcion == "")) {
    $opcion = 20;
}

switch ($opcion) {
    case 1:
        $tpl->newblock("cargaarchivo");
        $tpl->assign("txtCurso", $txtCurso);
        $tpl->assign("txtSeccion", $txtSeccion);
        $tpl->assign("txtPeriodo", $txtPeriodo);
        $tpl->assign("txtAnio", $txtAnio);
        $tpl->assign("txtTipoActividad", $txtTipoActividad);
        break;

    case 2:
        header('Location: creaactividad.php?opcion=9');
//					}// del if mover archivo. . .
        break;
    // del case 2
    case 20:
        $txtIdActividad = $_GET['txtIdActividad'];
        $txtCurso = $_GET['txtCurso'];
        $txtSeccion = $_GET['txtSeccion'];
        $txtPeriodo = $_GET['txtPeriodo'];
        $txtAnio= $_GET['txtAnio'];
        $txtTipoActividad = $_GET['txtTipoActividad'];
        $txtEsSuperActividad = $_GET['txtEsSuperActividad'];
        $txtCarrera = $_GET['txtCarrera'];

        $tpl->assign("AtxtCurso",$txtCurso);
        $tpl->assign("AtxtIndex",$_SESSION['index']);
        $tpl->assign("AtxtCarrera",$txtCarrera);
        $tpl->assign("AtxtSeccion",$txtSeccion);

        bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad, $txtEsSuperActividad, $tpl, $bd, $txtCarrera);
        break;
    // del case 20 de despliegue de la opcion carga archivo notas

    case 21:  // carga el archivo al servidor para ser procesado. . .
        $txtCurso = $_POST['txtCurso'];
        $txtSeccion = $_POST['txtSeccion'];
        $txtPeriodo = $_POST['txtPeriodo'];
        $txtAnio= $_POST['txtAnio'];
        $txtTipoActividad = $_POST['txtTipoActividad'];
        $txtEsSuperActividad = $_POST['txtEsSuperActividad'];
        $txtCarrera = $_POST['txtCarrera'];
        $txtIdActividad = $_POST['txtIdActividad'];
        $txtPonderacionActividad = $_POST['txtPonderacionActividad'];

        $tpl->assign("AtxtCurso",$txtCurso);
        $tpl->assign("AtxtIndex",$_SESSION['index']);
        $tpl->assign("AtxtCarrera",$txtCarrera);
        $tpl->assign("AtxtSeccion",$txtSeccion);

        $nombre_archivo = $_FILES["userfile"]["name"];
        $nombre_archivo = str_replace(" ", "_", $nombre_archivo);
        $tipo_archivo = $_FILES["userfile"]["type"];
        $tamano_archivo = $_FILES["userfile"]["size"];
        $ubicacion = 'notas-secciones/';
        //datos del arhivo
        $destino = $ubicacion . $txtCurso . $txtCarrera . $txtPeriodo . $txtAnio . $txtTipoActividad;
        $_tipoArchivoIncorrecto = false;
        if (strcmp($tipo_archivo, "text/plain") != 0 && strcmp($tipo_archivo, "application/vnd.ms-excel") != 0)
            $_tipoArchivoIncorrecto = true;
        else {
            $_extensionArchivo = @strtolower(@strrchr($nombre_archivo, "."));
            if (@strpos($_extensionArchivo, '.') === false)
                $_tipoArchivoIncorrecto = true;
            else {
                $_extensionArchivo = @substr($_extensionArchivo, 1);
                if (strtolower(trim($_extensionArchivo)) != "txt" && strtolower(trim($_extensionArchivo)) != "csv")
                    $_tipoArchivoIncorrecto = true;
            }
        }
        if ($_tipoArchivoIncorrecto === true) {
            bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad,
                $txtEsSuperActividad, $tpl, $bd, $txtCarrera);
            $tpl->newblock("mensaje");
            $_mensajeTxt = "* Imposible cargar el archivo para procesarlo ...<br>" .
                "* El archivo deber ser un archivo separado por por comas con extensión .CSV o .TXT ...<br>";

            $tpl->assign("mensaje", $_mensajeTxt);
            $tpl->assign("aTipoMensaje",'danger');
            $tpl->assign("aEncabezadoMensaje",'ARCHIVO NO CARGADO');
        } else {
            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $destino)) {
                $fp = fopen($destino, "r");
                $contador = 0;
                $lineaac = "";
                $_ocurrioError = false;
                $_erroresManejables = 0;
                $_erroresManejables1 = 0;
                //$_listaErrores="[  carne  ][nota]<br>";
//Aquí se debiera llamar a la consulta que obtiene los datos ya existentes en BDD de esa actividad para tomarlos en cuenta
//a la hora de procesar el archivo, de tal forma que si un estudiante ya tiene nota en dicha actividad y la misma es mayor que la
//que se está reportando en esta oportunidad se le informe al encargado lo adecuado de acuerdo a cada uno de los siguientes casos:
//   -  Si la sección de la nota con la que se procesó dicho dato es distinta a la del encargado, dicha nota no podrá sustituir la
//      ya existente debido a que la nota mayor prevalece.
//   -  Si la sección de la nota con la que se procesó dicho dato es igual a la del encargado, dicha nota se sustituirá pero debe
//      aconsejarse al encargado que revise su información para ver si no se equivocó al enviar nuevamente el archivo.
//Lo mejor será utilizar un vector para guardar dicha información para efectos de comparación.
                $_notasExistentes = notasYaExistentes($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad,
                    $txtEsSuperActividad, $tpl, $bd);
                $_listaErrores = array();
                $_listaErrores1 = array();
                $_noAsignados = array();
                $_practicasCivil = array("10", "11", "12", "13", "14");
                // inicio de transaccion
//					   $bd->query("begin");

                $bd->query($gsql_na_maa->begin());

                //
                while (!feof($fp)) {
                    $_errorManejable = false;
                    $linea = fgets($fp, 4096);
                    $linea = trim(str_replace(";", ",", $linea));
                    $contador++;
                    if ($contador > 1) {
                        if ($linea != "") {
                            //$linea=trim($linea);
                            $coma = strpos($linea, ",");
                            if ($coma == 0) {
                                bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad,
                                    $txtEsSuperActividad, $tpl, $bd, $txtCarrera);
                                $tpl->newblock("mensaje");

                                $_mensajeTxt = "* Imposible cargar el archivo para procesarlo ...<br>" .
                                    "* El archivo no tiene la estructura correcta ...<br>" .
                                    "* Recuerde que en la primera columna debe ir el carnet y en la siguiente la nota en puntos netos ...<br>".
                                "* El archivo deber ser un archivo separado por por comas con extensión .CSV o .TXT ...<br>".
                                    "* Solamente se puede procesar la nota de una actividad por cada archivo ...<br>";

                                $tpl->assign("mensaje", $_mensajeTxt);
                                $tpl->assign("aTipoMensaje",'danger');
                                $tpl->assign("aEncabezadoMensaje",'ARCHIVO NO CARGADO');

                                $ocurrioError = true;
                                break;
                            } else {
                                $carnet = trim(substr($linea, 0, $coma));
                                $carnet = CompletaCarnet($carnet);
                                $nota = trim(substr($linea, $coma + 1, strlen($linea)));
                                $coma2 = strpos($nota, ",");
                                if ($coma2 > 0 || trim($nota) == ",") {
                                    bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad,
                                        $txtEsSuperActividad, $tpl, $bd, $txtCarrera);
                                    $tpl->newblock("mensaje");

                                    $_mensajeTxt = "* Imposible cargar el archivo para procesarlo ...<br>" .
                                        "* El archivo no tiene la estructura correcta ...<br>" .
                                        "* Recuerde que en la primera columna debe ir el carnet y en la siguiente la nota en puntos netos ...<br>".
                                        "* El archivo deber ser un archivo separado por por comas con extensión .CSV o .TXT ...<br>".
                                        "* Solamente se puede procesar la nota de una actividad por cada archivo ...<br>";

                                    $tpl->assign("mensaje", $_mensajeTxt);
                                    $tpl->assign("aTipoMensaje",'danger');
                                    $tpl->assign("aEncabezadoMensaje",'ARCHIVO NO CARGADO');

                                    $ocurrioError = true;
                                    break;
                                }
                                if (is_numeric($nota)) {
                                    if ($nota < 0 || $nota > $txtPonderacionActividad)
                                        $_errorManejable = true;
                                    else{
                                        $nota = round($nota,2);
                                    }
                                } else
                                    $_errorManejable = true;
                                if ($_errorManejable === false) {
                                    $_cambiaNotaExistente = verificaNotasExistentes($_notasExistentes, $carnet, $nota, $txtCarrera/*$txtSeccion*/,
                                        $_erroresManejables1, $_listaErrores1);
                                    if ($_cambiaNotaExistente === true) {
                                        if ($txtEsSuperActividad == 1) {
//										     $SqlUpdateExtra=" and ing_actividad.tipoactividad=$txtTipoActividad ";

                                            $SqlUpdateExtra = $gsql_na_maa->_update1_1($txtTipoActividad);

//										     $seccionActividad=", seccionactividad[ing_actividad.posicion]='" . $txtSeccion . "'";

                                            $seccionActividad = $gsql_na_maa->_update1_2( $txtCarrera/*$txtSeccion*/);

                                        } else {
//										     $SqlUpdateExtra=" and ing_actividad.idactividad=$txtIdActividad ";

                                            $SqlUpdateExtra = $gsql_na_maa->_update1_3($txtIdActividad);

//										     $seccionActividad="";

                                            $seccionActividad = $gsql_na_maa->_update1_4();

                                        }
//										   $update= " update ing_notasactividad
//												    set actividades[ing_actividad.posicion]=$nota" . $seccionActividad .
//												    "  from ing_actividad
//												    where
//												    ing_notasactividad.carnet='$carnet'
//												    $SqlUpdateExtra 
//												    and ing_actividad.curso='$txtCurso'
//												    and ing_actividad.periodo='$txtPeriodo'
//												    and ing_actividad.anio=$txtAnio
//												    and ing_actividad.activo=1
//												    and ing_actividad.curso=ing_notasactividad.curso
//												    and ing_actividad.seccion=ing_notasactividad.seccion
//												    and ing_actividad.periodo=ing_notasactividad.periodo
//												    and ing_actividad.anio=ing_notasactividad.anio
//									                " ;

                                        $update = $gsql_na_maa->_update1($nota, $seccionActividad, $carnet, $SqlUpdateExtra, $txtCurso, $txtPeriodo, $txtAnio);
                                        $bd->query($update);
                                        if ($bd->affected_rows() == 0 && $txtEsSuperActividad == 1 &&
                                            in_array($txtTipoActividad, $_practicasCivil) && $nota > 60
                                        ) {
                                            $_txtAnio2 = $txtAnio + 2;
                                            $_noAsignados[] = truncaCarnet($carnet) . "|" . $txtCurso . "|" . $nota . "|" .
                                                $txtTipoActividad . "|" . $txtPeriodo . "|" . $txtAnio . "|" .
                                                $txtPeriodo . "|" . $_txtAnio2 . "|1";
                                        }
                                    }
                                } else {
                                    //$_listaErrores = $_listaErrores . "[" . $carnet . "][" . $nota . "]<br>";
                                    $_listaErrores[$_erroresManejables][0] = $carnet;
                                    $_listaErrores[$_erroresManejables][1] = $nota;
                                    $_erroresManejables++;
                                }


                            } // del if lin
                        }// del if contador>1
                    }

                } // del else de coma==0

                // fin de transaccion
                if ($_ocurrioError === false && sizeof($_noAsignados) > 0) {
                    $_ocurrioError = guardaNotasNoAsignados($bd, $_noAsignados, $txtCurso, $txtTipoActividad, $txtPeriodo, $txtAnio);
                }
                if ($_ocurrioError === false) {
                    if ($_erroresManejables > 0 || $_erroresManejables1 > 0) {
// 					 $bd->query("commit");

                        $bd->query($gsql_na_maa->commit());

//					    $bd->query("end");

                        $bd->query($gsql_na_maa->end());

                        bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad,
                            $txtEsSuperActividad, $tpl, $bd, $txtCarrera);
                        if ($_erroresManejables1 > 0) {
                            $tpl->newblock("erroresManejables");
                            $_mensajeTxt = "Se encontraron algunas notas ya existentes en la base de datos, procesadas anteriormente.<br>" .
                                "Por lo que se sugiere revisar detenidamente el siguiente listado, dentro de su archivo:<br>";
                            $tpl->assign("mensaje1", $_mensajeTxt);
                            for ($i = 0; $i < $_erroresManejables1; $i++) {
                                $tpl->newblock("errorManejable");
                                $tpl->assign("elCarnet", $_listaErrores1[$i]["carnet"]);
                                switch ($_listaErrores1[$i]["caso"]) {
                                    case 1 :
                                        $laNota1_1 = $_listaErrores1[$i]["nota_existente"];
                                        $laNota1_2 = "&nbsp;";
                                        $laNota1_3 = "&nbsp;";
                                        $laNota1_4 = $_listaErrores1[$i]["nueva_nota"];
                                        break;
                                    case 2 :
                                        $laNota1_1 = "&nbsp;";
                                        $laNota1_2 = $_listaErrores1[$i]["nota_existente"];
                                        $laNota1_3 = $_listaErrores1[$i]["nueva_nota"];
                                        $laNota1_4 = "&nbsp;";
                                        break;
                                    case 3 :
                                        $laNota1_1 = $_listaErrores1[$i]["nota_existente"];
                                        $laNota1_2 = "&nbsp;";
                                        $laNota1_3 = $_listaErrores1[$i]["nueva_nota"];
                                        $laNota1_4 = "&nbsp;";
                                        break;
                                }
                                $tpl->assign("laNota1_1", $laNota1_1);
                                $tpl->assign("laNota1_2", $laNota1_2);
                                $tpl->assign("laNota1_3", $laNota1_3);
                                $tpl->assign("laNota1_4", $laNota1_4);
                            }
                        }
                        if ($_erroresManejables > 0) {
                            $tpl->newblock("erroresManejables1");
                            $_mensajeTxt = "* Se encontraron los errores listados en la tabla siguiente ...<br>" .
                                "* Para poder cargar las notas, es necesario volver a enviarlo con los errores corregidos";
                            $tpl->assign("mensaje2", $_mensajeTxt);
                            $tpl->assign("aTipoMensaje",'danger');
                            $tpl->assign("aEncabezadoMensaje",'ARCHIVO NO CARGADO');
                            for ($i = 0; $i < $_erroresManejables; $i++) {
                                $tpl->newblock("errorManejable1");
                                $tpl->assign("elCarnet1", $_listaErrores[$i][0]);
                                $tpl->assign("laNota2", $_listaErrores[$i][1]);
                            }
                        }

                    } else {
// 					 $bd->query("commit");

                        $bd->query($gsql_na_maa->commit());

//					    $bd->query("end");

                        $bd->query($gsql_na_maa->end());

                        $tpl->newblock("mensaje");
                        $_mensajeTxt = "* El archivo fué procesado completamente sin encontrar errores en su contenido<br>" .
                            "* Para revisar la información cargada: click en <strong>'Regresar a listado de actividades'</strong> y luego ir a la opción <strong>'Cargar Notas Manualmente'</strong><br>" .
                            "* No olvide aprobar sus notas de actividades con el botón adecuado antes de la fecha límite<br>";
                        $tpl->assign("mensaje", $_mensajeTxt);
                        $tpl->assign("aTipoMensaje",'success');
                        $tpl->assign("aEncabezadoMensaje",'IMPORTANTE');
                        //header('Location: creaactividad.php?opcion=9');
                    }
                } else {
//					  $bd->query("rollback");


                    $bd->query($gsql_na_maa->rollback());

//					  $bd->query("end");

                    $bd->query($gsql_na_maa->end());

                }
            }// del if mover archivo. . .
            else {
                bloqueCargaNotas($txtCurso, $txtSeccion, $txtPeriodo, $txtAnio, $txtTipoActividad, $txtIdActividad,
                    $txtEsSuperActividad, $tpl, $bd, $txtCarrera);
                $tpl->newblock("mensaje");

                $_mensajeTxt = "* Imposible guardar el archivo temporalmente para procesarlo ...<br>" .
                    "* Se sugiere intentarlo más tarde ...<br>" .
                    "* Si experimenta el mismo problema, favor de comunicarse con el administrador ...<br>";

                $tpl->assign("mensaje", $_mensajeTxt);
                $tpl->assign("aTipoMensaje",'danger');
                $tpl->assign("aEncabezadoMensaje",'ARCHIVO NO CARGADO');
            }
        }
        break;

    // del case 21 opcion que muestra el menu cargar archivo notas
}


$tpl->printToScreen();
unset($obj_cad);

?>
