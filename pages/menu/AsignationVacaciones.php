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
    $tpl = new TemplatePower("AsignationVacaciones.tpl");

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
$verificacion = $objControlCourse->getActivationVacaciones();
$isActivated=false;
$anioEnviar=0;//enviamos el anio actual menos dos anios para comprobar los curso validos dos anios atras
$periodoEnviar=0;//enviamos el semestre desde el cual vamos a comprobar los cursos (desde donde empieza a ser valido el curso)
$periodoActual=0;//EL SEMESTRE ACUTAL 
$periodoAsignacionV=0; //parametro para buscar las ordenes de pago para ese periodo
$anioSis = 0;
$tpl->assign('SEMESTRE',0);
if($verificacion){
    foreach($verificacion as $row)
    {
        if($row['estado']==1)
        {
            $isActivated = true;
            $anioSis = $row['anio'];
            switch($row['schoolyear'])
            {
                case VACACIONES_DEL_PRIMER_SEMESTRE:
                    $periodoEnviar=SEGUNDO_SEMESTRE;//enviamos el semestre desde el cual vamos a comprobar los cursos (desde donde empieza a ser valido el curso)
                    $periodoActual=PRIMER_SEMESTRE;//EL SEMESTRE ACUTAL 
                    $periodoAsignacionV = VACACIONES_DEL_PRIMER_SEMESTRE;
                    $anioEnviar = $row['anio'] - 2;
                    break;
                case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                    $periodoEnviar=PRIMER_SEMESTRE;//enviamos el semestre desde el cual vamos a comprobar los cursos (desde donde empieza a ser valido el curso)
                    $periodoActual=SEGUNDO_SEMESTRE;//EL SEMESTRE ACTUAL
                    $periodoAsignacionV = VACACIONES_DEL_SEGUNDO_SEMESTRE;
                    $anioEnviar = $row['anio'] - 1;
                    break;
            }
        }
    }
}

$_SESSION["datosGenerales"]->usuarioid = $objuser->getId();
$_SESSION["datosGenerales"]->carrera = $objuser->getCareer();
$_SESSION["datosGenerales"]->nombreEstudiante = $objuser->getName().' '.$objuser->getSurName();
$_SESSION["datosGenerales"]->periodo = $periodoAsignacionV;
$_SESSION["datosGenerales"]->anio = $anioSis;
$_SESSION['ORDEN_EXISTENTE'] = TRUE;


if($isActivated){
    $txtClase1='msg-info-txt';
    $txtIcono1='fa fa-check fa-2x';
    $txtMensaje1='SISTEMA ACTIVO PARA ASIGNACIÓN DE CURSOS DE VACACIONES';
}else
{
    $txtClase1='msg-danger-txt';
    $txtIcono1='fa fa-close fa-2x';
    $txtMensaje1='SISTEMA NO ACTIVO PARA ASIGNACIÓN DE CURSOS DE VACACIONES';
}

// Verificar inscripcion el periodo activo
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
// Termina verificacion de inscripcion

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

//Enviando información al template para el paso2
if(!$isActivated)//comprobar si el periodo es valido para asignarse a primera retrasada
{
    $tpl->gotoBlock("_ROOT");
    $tpl->assign('isActive',0);
    
    $tpl->gotoBlock("_ROOT");
    $tpl->assign('NumDisp',0);
    $tpl->assign('tmpPermitido',0);
}
else
{
    $tpl->gotoBlock("_ROOT");
    $tpl->assign('isActive',1);
    $tpl->assign('tmpPermitido',TIEMPO_VACACIONES);
    
    $result = $objControlCourse->getCursosVacaciones($objuser->getId(), $objuser->getCareer(), $periodoAsignacionV, $anioSis, $periodoEnviar, $anioEnviar);//enviamos la consulta
    $resultOrden=$objControlCourse->searchOrdenV($objuser->getId(), $anioSis, $periodoAsignacionV); //realiza consulta para verificar si existen ordenes generadas y devuelve los codigos
    
    if($result){
        $_SESSION["NombreCursos"] = $result;
    }
    $objCursos = new ControlCourseInfo($result); //Objeto para obtener metodos de los cursos
    
    if($result){
        if(!$resultOrden){
            if($result) 
            {
                $contadorCursos =0;
                foreach($result as $curso) {
                    $tpl->newBlock('cursosDisponibles');
                    $tpl->assign('aCurso',$curso['idcourse']);
                    $tpl->assign('aNombreCurso',$curso['name']);

                    $tpl->newBlock('INFOCURSOS');
                    $tpl->assign('aPosicion',$contadorCursos);
                    $tpl->assign('aIdCourse',$curso['idcourse']);
                    $tpl->assign('aName',$curso['name']);
                    $tpl->assign('aSeccion',"A");
                    $tpl->assign('aEdificio',"-");
                    $tpl->assign('aSalon',"-");
                    $tpl->assign('aInicio',$curso['inicio']);
                    $tpl->assign('aFinal',$curso['fin']);
                    $tpl->assign('aLun',($curso['lun']==1)?"X":"-");
                    $tpl->assign('aMar',($curso['mar']==1)?"X":"-");
                    $tpl->assign('aMie',($curso['mie']==1)?"X":"-");
                    $tpl->assign('aJue',($curso['jue']==1)?"X":"-");
                    $tpl->assign('aVie',($curso['vie']==1)?"X":"-");
                    $tpl->assign('aSab',($curso['sab']==1)?"X":"-");
                    $tpl->assign('aDom',($curso['dom']==1)?"X":"-");
                    $tpl->assign('aPrice',$curso['price']);
                    $tpl->assign('aInicioMin',$objCursos->getMinutos($curso['inicio']));
                    $tpl->assign('aFinalMin',$objCursos->getMinutos($curso['fin']));
                    $contadorCursos++;
                }
                $tpl->gotoBlock("_ROOT");
                $tpl->assign('NumDisp',$contadorCursos);
            }else{
                $tpl->gotoBlock("_ROOT");
                $tpl->assign('NumDisp',0);
            }
        }else // si existen ordenes generadas y/o canceladas
        {
            $cursosPagados=NULL;
            $cursosGenerados=NULL;
            foreach ($resultOrden as $orden) {
                if($orden['paymentidnumber']!=NULL && $orden['bankname']!=NULL && $orden['paymentdate']!=NULL && $orden['paymenttime']!=NULL) //Si la orden ya esta cancelada
                {
                    $temporal = $objControlCourse->searchDetalleOrdenV($orden['paymentorder']); //Crea bloque para boletas pagadas
                    if($temporal){
                        foreach ($temporal as $cursodet) {
                            $idcourse=$cursodet['idcourse'];
                            $tuplaTemp= array('idcourse' => $cursodet['idcourse']);
                            $cursosPagados[]=$tuplaTemp;
                        }
                        $infoCurso=$objCursos->getInfoCurso($idcourse);

                        if($infoCurso){
                            //echo 'SOY PAGADO Y ENCONTRE EL CURSO: '.$idcourse;
                            $tpl->newBlock('PAGADOS');
                            $tpl->assign('aCurso', $infoCurso['idcourse']);
                            $tpl->assign('aNombreCurso', $infoCurso['name']);
                            $tpl->assign('aInicio', $infoCurso['inicio']);
                            $tpl->assign('aFinal', $infoCurso['fin']);
                            $tpl->assign('aL', ($infoCurso['lun']==1)?"X":"-");
                            $tpl->assign('aM', ($infoCurso['mar']==1)?"X":"-");
                            $tpl->assign('aMi', ($infoCurso['mie']==1)?"X":"-");
                            $tpl->assign('aJ', ($infoCurso['jue']==1)?"X":"-");
                            $tpl->assign('aV', ($infoCurso['vie']==1)?"X":"-");
                            $tpl->assign('aOrden', $orden['paymentorder']);
                        }
                    }
                }else //ordenes generadas
                {
                    $temporal = $objControlCourse->searchDetalleOrdenV($orden['paymentorder']); //Crea bloque para boletas generadas
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
                            $tpl->assign('aInicio', $infoCurso['inicio']);
                            $tpl->assign('aFinal', $infoCurso['fin']);
                            $tpl->assign('aL', ($infoCurso['lun']==1)?"X":"-");
                            $tpl->assign('aM', ($infoCurso['mar']==1)?"X":"-");
                            $tpl->assign('aMi', ($infoCurso['mie']==1)?"X":"-");
                            $tpl->assign('aJ', ($infoCurso['jue']==1)?"X":"-");
                            $tpl->assign('aV', ($infoCurso['vie']==1)?"X":"-");
                            $tpl->assign('aOrden', $orden['paymentorder']);
                        }
                    }
                }
            }

            if($result){
                $contadorCursos =0;
                foreach($result as $curso) {
                    $colocar=1;

                    if($cursosPagados!=NULL){
                        foreach ($cursosPagados as $value) {
                            if($value['idcourse']==$curso['idcourse']){
                                $colocar=0;
                            }
                        }
                    }

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
                    $tpl->assign('aSeccion',"A");
                    $tpl->assign('aEdificio',"-");
                    $tpl->assign('aSalon',"-");
                    $tpl->assign('aInicio',$curso['inicio']);
                    $tpl->assign('aFinal',$curso['fin']);
                    $tpl->assign('aLun',($curso['lun']==1)?"X":"-");
                    $tpl->assign('aMar',($curso['mar']==1)?"X":"-");
                    $tpl->assign('aMie',($curso['mie']==1)?"X":"-");
                    $tpl->assign('aJue',($curso['jue']==1)?"X":"-");
                    $tpl->assign('aVie',($curso['vie']==1)?"X":"-");
                    $tpl->assign('aSab',($curso['sab']==1)?"X":"-");
                    $tpl->assign('aDom',($curso['dom']==1)?"X":"-");
                    $tpl->assign('aPrice',$curso['price']);
                    $tpl->assign('aInicioMin',$objCursos->getMinutos($curso['inicio']));
                    $tpl->assign('aFinalMin',$objCursos->getMinutos($curso['fin']));
                    $contadorCursos++;
                }
                $tpl->gotoBlock("_ROOT");
                $tpl->assign('NumDisp',$contadorCursos);
            }else{
                $tpl->gotoBlock("_ROOT");
                $tpl->assign('NumDisp',0);
            }
        }
    }else{
        $tpl->gotoBlock("_ROOT");
        $tpl->assign('NumDisp',0);
    }
    //Agregando cursos disponibles para seleccionar
    
    
    //*************** Pruebas 
    /*$tpl->newBlock('cursosDisponibles');
    $tpl->assign('aCurso',102);
    $tpl->assign('aNombreCurso',"Quimica");
    */
    
    //Verificando ordenes existentes y ordenes pagadas
    

    /*
    $tpl->newBlock("GENERADOS");
    $tpl->assign('aCurso', 333);
    $tpl->assign('aNombreCurso', "Otorrinolarringologia aplicada");
    $tpl->assign('aInicio', '7:00');
    $tpl->assign('aFinal', '8:00');
    $tpl->assign('aL', 'X');
    $tpl->assign('aM', 'X');
    $tpl->assign('aMi', 'X');
    $tpl->assign('aJ', 'X');
    $tpl->assign('aV', 'X');
    $tpl->assign('aOrden', 334455);
    
 */
    
    
}

    
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