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
    $objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);

    //******************** OBTENIENDO PARAMETROS 
    $noOrden=$_GET["noOrden"];

    $cursosOrden=$objControlCourse->searchDetalleOrdenV($noOrden);
    echo 'CODIGO DE LA ORDEN'. $noOrden;
    $cur=$_SESSION["NombreCursos"]; //Arreglo que contiene informacion de todos los cursos disponibles

    //****************** CONVIRTIENDO CURSOS A OBJETO
    
    
    foreach ($cursosOrden as $curso) {
        echo 'ITERANDO PARA IMPRIMIR, ENCONTRE';
        foreach ($cur as $curDisp){
            if($curso['idcourse']==$curDisp['idcourse']){
                $rRow = array('idcourse'=> $curDisp['idcourse'],
                          'name'=> $curDisp['name'],
                          'price'=> $curDisp['price']
                );
                echo 'ITERANDO PARA IMPRIMIR, CURSO: '.$curDisp['idcourse'];
            }
            
        }
        $listaCursos[]= $rRow;
    }

    //***************** GENERAR BOLETA
    $_SESSION["contador"] = 1;
    $_SESSION["NombreCursos2"]=$listaCursos;// Vector con id y nombre de los cursos seleccionados
    $_SESSION['OrdenPago']=$noOrden;

    unset($_SESSION["NombreCursos"]);
    header("Location:/pages/includes/AR_PrintPdf.php");
}
?>