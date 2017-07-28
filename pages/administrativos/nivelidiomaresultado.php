<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");

include_once("$dir_portal/fw/controller/ControlUser.php");
include_once("$dir_portal/fw/model/sql/nivelidioma_SQL.php");

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}


$objNivelIdioma = new nivelidioma_SQL();

$tpl = new TemplatePower("nivelidiomaresultado.tpl");

$tpl->assignInclude("ihead", "../includes/head.php");
$tpl->assignInclude("iheader", "../includes/header.php");
$tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
$tpl->assignInclude("imenu", "../includes/menu.php");
$tpl->assignInclude("ifooter", "../includes/footer.php");

$tpl->prepare();

$opcion = $_GET['opcion'];

// fuente de los valores de las variables según la opción indicada
switch($opcion){
    case 0:
        $iduser = $_POST['ff_cui'];
        $career = $_POST['career'];
        
		error_reporting(E_ERROR | E_PARSE);
        $objcontroller = new ControlUser('testfmvz@usac.edu.gt', NULL, $iduser, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $career, NULL, NULL, 3, 1, session_id());
        $result = $objcontroller->getUserData();
        $objUser = $objcontroller->getUser();
        break;
    case 1:
    case 3:
        $objUser = unserialize($_SESSION['objUser']);
        break;
    case 2:
        $objUser = unserialize($_SESSION['objUser']);
        $newLevelRecord = $_POST['txtNivelIdioma'];
        break;
}

// almacenamos nuestro objeto usuario
$_SESSION['objUser'] = serialize($objUser);

// elementos a desplegar según la opcion indicada
switch($opcion){
    case 1: // ingresar nuevo certificado
        $tpl->newBlock("b_ingresocertificado");
        $tpl->assign("aEstudiante", ($objUser->getId() . ' - ' . $objUser->getName() . ' ' . $objUser->getSurName()));
        $tpl->assign("aCarrera", $objUser->getCareerName());
        $tpl->gotoBlock("_ROOT");
        break;
    
    case 2: // grabar nuevo certificado
        if($newLevelRecord>0 && $newLevelRecord<=20){
            error_reporting(E_ERROR | E_PARSE);
			$insertResult = $objNivelIdioma->registrarNivelIdioma_insert($objUser->getId(), $newLevelRecord);
        } else {
            $insertResult = "ERROR";
        }
        
        if(strpos($insertResult, "ERROR") === false || is_null(strpos($insertResult, "ERROR"))) {
            header('Location: ../administrativos/nivelidiomaresultado.php?opcion=3');
        
        } else{
            $mensaje = "TRANSACCIÓN: El registro no pudo almacenarse en la base de datos. ";
            $mensaje = $mensaje . "Verifique que el certificado a ingresar no<br>";
            $mensaje = $mensaje . "exista aun, que tenga conexión a la base de datos y que el valor ingresado sea válido";
            
            $tpl->newBlock("b_error");
            $tpl->assign("mensajeError", $mensaje);
            $tpl->gotoBlock("_ROOT");
        }
        break;
    
    case 3:
    case 0: // vista inicial de resultado
        if(is_null($objUser->getIdUser())){
            $mensaje = "BUSQUEDA: El registro del estudiante solicitado para consulta no existe. ";
            $mensaje = $mensaje . "Verifique el número de registro académico<br>";
            $mensaje = $mensaje . "y la carrera correspondiente";
            
            $tpl->newBlock("b_error");
            $tpl->assign("mensajeError", $mensaje);
            
        } else {
            $resultCert = $objNivelIdioma->nivelIdiomaRegistrado_select($objUser->getId());
        
            $tpl->newBlock("b_listacertificados");
            $tpl->assign("aEstudiante", ($objUser->getId() . ' - ' . $objUser->getName() . ' ' . $objUser->getSurName()));
            $tpl->assign("aCarrera", $objUser->getCareerName());

            if(count($resultCert)>0){
                $tpl->newBlock("b_detalle");
                $tpl->assign("aFecha", Date("d-m-Y"));
                $tpl->assign("aHora", Date("H:i"));

                $counter = 1;
                foreach ($resultCert as $record) {
                    $tpl->newBlock("b_itemdetalle");
                    $tpl->assign("vNo", $counter);
                    $tpl->assign("vNivel", $record['level']);
                    $tpl->assign("vFecha", ($record['receptiondate']=="")?"NO REGISTRADA":$record['receptiondate']);
                    $counter++;
                }

            } else {
                $tpl->newBlock("b_sindatos");
            }
        }
        
        $tpl->gotoBlock("_ROOT");
        break;
}

$tpl->printToScreen();
unset($tpl);