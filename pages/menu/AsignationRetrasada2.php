<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 11/01/2015
 * Time: 4:23 PM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/controller/manager/UserManager.php");
include_once("$dir_portal/fw/controller/manager/AssignationManager.php");
include_once("$dir_portal/fw/controller/ControlCourse.php");
include_once("$dir_portal/fw/controller/ControlCourseInfo.php");

session_start();
if(isset($_SESSION["contador2"]))
{
    unset($_SESSION["contador"]);
    unset($_SESSION["contador2"]);
    header("Location: ../../pages/estudiantes/estudiantes.htm");
}
else
{
    //echo "no existe";
    $_SESSION["contador"] = 0;
}

$_SESSION["datosGenerales"] = new stdClass();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

if(!isset($_REQUEST['cargar'])) {
    $objUserManager = new UserManager(null);
    $tpl = new TemplatePower("AsignationRetrasada2.tpl");

    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");

    $tpl->prepare();

    $vector_modi[0] = $objuser->getId();
    $vector_modi[1] = $objuser->getGroup();
    $vector_modi[2] = $objuser->getCareer();

    $result = $objUserManager->DatosEstudiante($vector_modi);
    $_SESSION['INFO_USUARIO'] = serialize($result);
    
$objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);

//********************** OBTENER FECHA Y CURSOS DISPONIBLES ***********************
$tpl->gotoBlock("_ROOT");
$verificacion = $objControlCourse->getActivationRetrasada2();
$isActivated=false;
$anioEnviar=0;//enviamos el anio actual menos dos anios para comprobar los curso validos dos anios atras
$periodoEnviar=0;//enviamos el semestre desde el cual vamos a comprobar los cursos (desde donde empieza a ser valido el curso)
$periodoActual=0;//EL SEMESTRE ACUTAL 
$periodoAsignacionR2=0; //parametro para buscar las ordenes de pago para ese periodo
$anioSis = 0;
$tpl->assign('SEMESTRE',0);
if($verificacion)
{
    foreach($verificacion as $row)
    {
        if($row['estado']==1)
        {
            $isActivated = true;
            $anioSis = $row['anio'];
            switch($row['schoolyear'])
            {
                case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
                    $periodoEnviar=SEGUNDO_SEMESTRE;//enviamos el semestre desde el cual vamos a comprobar los cursos (desde donde empieza a ser valido el curso)
                    $periodoActual=PRIMER_SEMESTRE;//EL SEMESTRE ACUTAL 
                    $periodoAsignacionR2 = SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE;
                    $anioEnviar = $row['anio'] - 2;
                    break;
                case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                    $periodoEnviar=PRIMER_SEMESTRE;//enviamos el semestre desde el cual vamos a comprobar los cursos (desde donde empieza a ser valido el curso)
                    $periodoActual=SEGUNDO_SEMESTRE;//EL SEMESTRE ACTUAL
                    $periodoAsignacionR2 = SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE;
                    $anioEnviar = $row['anio'] - 1;
                    break;
            }
        }
    }
}

$_SESSION["datosGenerales"]->usuarioid = $objuser->getId();
$_SESSION["datosGenerales"]->carrera = $objuser->getCareer();
$_SESSION["datosGenerales"]->nombreEstudiante = $objuser->getName().' '.$objuser->getSurName();
$_SESSION["datosGenerales"]->periodo = $periodoAsignacionR2;
$_SESSION["datosGenerales"]->anio = $anioSis;
$_SESSION['ORDEN_EXISTENTE'] = TRUE;

if($isActivated){
    $txtClase1='msg-info-txt';
    $txtIcono1='fa fa-check fa-2x';
    $txtMensaje1='SISTEMA ACTIVO PARA ASIGNACIÓN DE SEGUNDA RETRASADA';
}else
{
    $txtClase1='msg-danger-txt';
    $txtIcono1='fa fa-close fa-2x';
    $txtMensaje1='SISTEMA NO ACTIVO PARA ASIGNACIÓN DE SEGUNDA RETRASADA';
}

// -- Verificar inscripcion el periodo activo:  NOTA: Este metodo se utiliza en la primera y segunda retrasada
$respInscripcion = $objControlCourse->verifyInscripcion($objuser->getId(), $anioSis, $objuser->getCareer());
$inscrito=0;

if($respInscripcion)
{
    foreach($respInscripcion as $row)
    {
        $inscrito=$row['resultado'];
    }
}

if($inscrito==1){
    $txtClase2='msg-info-txt';
    $txtIcono2='fa fa-check fa-2x';
    $txtMensaje2='INSCRITO EN EL PERIODO ACTUAL';
}else{
    $txtClase2='msg-danger-txt';
    $txtIcono2='fa fa-close fa-2x';
    $txtMensaje2='NO SE ENCUENTRA INSCRITO EN EL PERIODO ACTUAL';
    
    $isActivated=false; //cambiando bandera para inhabilitar paso 1
}
// -- Termina verificacion de inscripcion

//Notificaciones del paso 1
if($isActivated==true && $anioSis!=0){
    $tpl->newBlock('MENSAJES');
    $tpl->assign('aClass',$txtClase1);
    $tpl->assign('aIcono',$txtIcono1);
    $tpl->assign('aMensaje',$txtMensaje1);

    $tpl->newBlock('MENSAJES2');
    $tpl->assign('aClass2',$txtClase2);
    $tpl->assign('aIcono2',$txtIcono2);
    $tpl->assign('aMensaje2',$txtMensaje2);
}else{
    $tpl->newBlock('MENSAJES');
    $tpl->assign('aClass',$txtClase1);
    $tpl->assign('aIcono',$txtIcono1);
    $tpl->assign('aMensaje',$txtMensaje1);    
}


if(!$isActivated)//comprobar si el periodo es valido para asignarse a primera retrasada
{
    $tpl->gotoBlock("_ROOT");
    $tpl->assign('isActive',0);
    
    $tpl->gotoBlock("_ROOT");
    $tpl->assign('NumDisp',0);
}
else
{
    $tpl->gotoBlock("_ROOT");
    $tpl->assign('isActive',1);
    $result = $objControlCourse->getCursosRetrasada2($objuser->getId(), $anioEnviar, $periodoEnviar,$periodoActual,$anioSis,$objuser->getCareer(),DIAS_ANTES_DE_PAGO_RETRASADAS);//enviamos la consulta
    $resultOrden=$objControlCourse->searchOrdenR2($objuser->getId(), $anioSis, $periodoAsignacionR2); //realiza consulta para verificar si existe orden de pago y devuelve el codigo de la orden
    $resultPago=$objControlCourse->searchOrdenPagadaR2($objuser->getId(), $anioSis, $periodoAsignacionR2,$objuser->getCareer());
    
    if($result){
        $_SESSION["NombreCursos"] = $result;
    }
    
    $objCursos = new ControlCourseInfo($result); //Objeto para obtener metodos de los cursos
    
    if($result){
        if(!$resultOrden){ //Si no hay ordenes pendientes de pago
            $contadorCursos =0;
            foreach($result as $curso) {
                $tpl->newBlock('cursosDisponibles');
                $tpl->assign('aCurso',$curso['idcourse']);
                $tpl->assign('aNombreCurso',$curso['name']);

                $tpl->newBlock('INFOCURSOS');
                $tpl->assign('aPosicion',$contadorCursos);
                $tpl->assign('aIdCourse',$curso['idcourse']);
                $tpl->assign('aName',$curso['name']);
                $contadorCursos++;
            }
            $tpl->gotoBlock("_ROOT");
            $tpl->assign('NumDisp',$contadorCursos);
        }else{ //Si hay ordenes pendientes de pago
            $cursosGenerados=NULL;
            foreach ($resultOrden as $orden) {
                if($orden['paymentidnumber']==NULL && $orden['bankname']==NULL && $orden['paymentdate']==NULL && $orden['paymenttime']==NULL) //Si la orden no esta cancelada
                {
                    $temporal = $objControlCourse->searchDetalleOrdenR2($orden['paymentorder']); //Crea bloque para boletas generadas
                    if($temporal){
                        foreach ($temporal as $cursodet) {
                            $idcourse=$cursodet['idcourse'];
                            $tuplaTemp= array('idcourse' => $cursodet['idcourse']);
                            $cursosGenerados[]=$tuplaTemp;
                        }
                        $infoCurso=$objCursos->getInfoCurso($idcourse);

                        if($infoCurso){
                            $tpl->newBlock('GENERADOS');
                            $tpl->assign('aCurso', $infoCurso['idcourse']);
                            $tpl->assign('aNombreCurso', $infoCurso['name']);
                            $tpl->assign('aOrden', $orden['paymentorder']);
                        }
                    }
                }
            }
            
            $contadorCursos =0;
            foreach($result as $curso) {
                $colocar=1;

                if($cursosGenerados!=NULL){
                    foreach ($cursosGenerados as $value) {
                        if($value['idcourse']==$curso['idcourse']){
                            $colocar=0;
                        }
                    }
                }

                if($colocar==1){
                    $tpl->newBlock('cursosDisponibles');
                    $tpl->assign('aCurso',$curso['idcourse']);
                    $tpl->assign('aNombreCurso',$curso['name']);
                }

                $tpl->newBlock('INFOCURSOS');
                $tpl->assign('aPosicion',$contadorCursos);
                $tpl->assign('aIdCourse',$curso['idcourse']);
                $tpl->assign('aName',$curso['name']);
                $contadorCursos++;
            }
            $tpl->gotoBlock("_ROOT");
            $tpl->assign('NumDisp',$contadorCursos);
        }
    }else{
        $tpl->gotoBlock("_ROOT");
        $tpl->assign('NumDisp',0);
    }
    
    if($resultPago){
        foreach($resultPago as $orden) {
            if($orden['paymentidnumber']!=NULL && $orden['bankname']!=NULL && $orden['paymentdate']!=NULL && $orden['paymenttime']!=NULL){
                $tpl->newBlock('PAGADOS');
                $tpl->assign('aCurso', $orden['idcourse']);
                $tpl->assign('aNombreCurso', $orden['name']);
                $tpl->assign('aOrden', $orden['paymentorder']);
            }
        }
    }
}
//*******************************************************************************
    
/*
$connection = new MongoClient(MONGO_CLIENT);
$db = $connection->selectDB(MONGO_DB);

$grid = $db->getGridFS(MONGO_COLLECTION);
$image = $grid->findOne($objuser->getId());

//header("Content-type: image/jpeg");
if (null == $image) {
    $image = $grid->findOne('000000000');
    //echo $image->getBytes();
$tpl->assign('aFoto','000000000');

} else {
    //echo $image->getBytes();
$image->write('/var/www/fotos/'.$objuser->getId().'.jpg');
$tpl->assign('aFoto',$objuser->getId());
}*/

    $tpl->printToScreen();

    unset($tpl,$objUserManager,$connection);
} else {

}


?>