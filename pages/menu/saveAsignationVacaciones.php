<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 31/01/2015
 * Time: 2:27 PM
 */

//
// ------------------------------------------------
// Guardar datos del formulario Información de Graduandos
// -------------------------------------------------

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/UserManager.php");
include_once("$dir_portal/fw/controller/ControlCourse.php");
include_once("$dir_portal/fw/controller/RequestOrden.php");
include_once("$dir_portal/fw/controller/ControlCourseInfo.php");

include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");

session_start();
$_SESSION["contador2"]=1; //variable bandera para no insertar dos veces
if($_SESSION["contador"] != 0)
{
    unset($_SESSION["contador"]);
    unset($_SESSION["contador2"]);
    header("Location: ../../pages/estudiantes/estudiantes.htm");
}
else
{
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

//Creacion de los objetos
$tpl = new TemplatePower("saveAsignationVacaciones.tpl");
$obj_cad = new ManejoString();
$objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);
$objCursos = new ControlCourseInfo($_SESSION["NombreCursos"]); //Objeto para obtener metodos de los cursos

//Asignacion de menús generales a la plantilla
$vector_modi[0] = $objuser->getId();
$vector_modi[1] = $objuser->getGroup();
$vector_modi[2] = $objuser->getCareer();
$vector_modi[3] = $objuser->getName();

$obj_pin = new UserManager($vector_modi[1]);

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();  //TemplatePower se prepara


//******************** OBTENIENDO PARAMETROS 
$codigoClase=$_GET["codigo"];

$periodoDeAsignacion=$_SESSION["datosGenerales"]->periodo;
$anioAsignacion=$_SESSION["datosGenerales"]->anio;

$idpaymenttype=0;
if ($periodoDeAsignacion==VACACIONES_DEL_PRIMER_SEMESTRE){
    $idpaymenttype=_TIPO_PAGO_VACACIONES_JUNIO;
}else if($periodoDeAsignacion==VACACIONES_DEL_SEGUNDO_SEMESTRE)
{
    $idpaymenttype=_TIPO_PAGO_VACACIONES_DICIEMBRE;
}

//******************** OBTIENENDO MONTO
$cur=$_SESSION["NombreCursos"]; //Arreglo que contiene informacion de todos los cursos disponibles
$monto=0;
$i=0;       
foreach ($cur as $curDisp) {
    if($curDisp['idcourse']==$codigoClase){
        $monto=$curDisp['price'];
    }
}
$_SESSION["montoGeneral"]=$monto;
$ver="Monto de la orden: ".$monto;

//****************** CONVIRTIENDO CURSOS A OBJETO      
foreach ($cur as $curDisp) {
    if($curDisp['idcourse']==$codigoClase){
        $rRow = array('idcourse'=> $curDisp['idcourse'],
                      'name'=> $curDisp['name'],
                      'price'=> $curDisp['price']
            );
    }
}
$listaCursos[]= $rRow;

if($_SESSION["contador"] ==0){
    //***************** GENERAR BOLETA
    $_SESSION["contador"] = 1;
    //$monto; //Monto total de la orden existente
    //$listaCursos[] ; //Tiene los cursos correspondientes a la orden existente
    //$objuser->getCareer(); //Carrera del estudiante
    //$objuser->getId(); //Carnete del estudiante
    //$periodoDeAsignacion; //Corresponde al periodo al que se esta asignando
    //$anioAsignacion;
    //VALOR_PRIMERA_RETRASADA; // Valor del curso para primera retrasada
    $cantidadHoras=$objCursos->getDuracion($rRow['idcourse']);
    //echo 'Duración del curso: '.$cantidadHoras;
    //die;
    $posCont = 0;
    $vecCursos[]=NULL;
    //Creando xml de solicitud de orden
    $vectorClasesXML = array($posCont,
        'mEstado'   => 1,
        'montoLaboratorio'   => 0,
        'curso'   => $rRow['idcourse'],
        'seccion'   => "A",
        'numHoras'   => $cantidadHoras,
        'marca'   => FALSE,
        'retUnica'   => FALSE,
        'precio' => $rRow['price']
        );
    $posCont++;
    $vecCursos[] = $vectorClasesXML;

    //Generando solicitud de orden de pago
    $generaBoleta = new OG_PaymentOrderGenerationWS();
    $resultadoOb=$generaBoleta->procesoGeneracion(1, $vecCursos);

    if($resultadoOb){
        $resultInsertOrden=$objControlCourse->insertOrdenVacaciones($_SESSION['ORDEN_PAGO']['numeroOrden'], $monto, VERCA, VERWS, $idpaymenttype, $objuser->getCareer(), $objuser->getId(), VERIFIER, REQUESTYPE, $anioAsignacion, $periodoDeAsignacion, $_SESSION['ORDEN_PAGO']['identificador'], $_SESSION['ORDEN_PAGO']['rubropago']);
        if($resultInsertOrden)
        {
            $objControlCourse->insertDetalleVacaciones($_SESSION['ORDEN_PAGO']['numeroOrden'], $rRow['price'], 0, $rRow['idcourse'], 0);
        }

        $_SESSION["NombreCursos2"]=$listaCursos;// Vector con id y nombre de los cursos seleccionados

        $ver=$ver.' Orden de pago = '.$_SESSION['ORDEN_PAGO']['numeroOrden'];
        $tpl->newBlock('BOLETA');
        $tpl->assign("aCarnet",$objuser->getId());
        $tpl->assign("aOrden",$_SESSION['ORDEN_PAGO']['numeroOrden']);
        $tpl->assign("aMonto",$monto);
        $_SESSION['OrdenPago']=$_SESSION['ORDEN_PAGO']['numeroOrden'];

    }else{
        $ver=$ver. " No se recibio nada del web service";
        $tpl->newBlock('MENSAJES');
        $tpl->assign('aClass','msg-danger-txt');
        $tpl->assign('aIcono','fa fa-close fa-2x');
        $tpl->assign('aMensaje',$_SESSION['ORDEN_PAGO']['descripcion']);
    }
}


//******* TERMINA Guardar orden pago en la bd || Mostrar orden existente

$tpl->gotoBlock("_ROOT");
$tpl->assign('RESULTADO',$ver);

$tpl->printToScreen();
unset($_SESSION["NombreCursos"]);
unset($tpl);
unset($obj_pin);
unset($obj_cad);
}
?>