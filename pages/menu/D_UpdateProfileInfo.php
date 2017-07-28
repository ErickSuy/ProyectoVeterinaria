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

session_start();
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

if(!isset($_REQUEST['cargar'])) {
    $objUserManager = new UserManager(null);
    $tpl = new TemplatePower("D_UpdateProfileInfo.tpl");

    $tpl->assignInclude("ihead", "../includes/head.php");
    $tpl->assignInclude("iheader", "../includes/header.php");
    $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
    $tpl->assignInclude("imenu", "../includes/menu.php");
    $tpl->assignInclude("ifooter", "../includes/footer.php");

    $tpl->prepare();

    $vector_modi[0] = $objuser->getId();
    $vector_modi[1] = $objuser->getGroup();

    $result = $objUserManager->DatosDocente($vector_modi);
    $_SESSION['INFO_USUARIO'] = serialize($result);

    $tpl->gotoBlock('_ROOT');
    // Información general
    $tpl->assign('aGrupo',$objuser->getGroupName());
    $tpl->assign('aPersonal',$objuser->getId());
    $tpl->assign('aTitularidad',$objuser->getRatingTypeName());
    $tpl->assign('aNombre',$objuser->getName());
    $tpl->assign('aApellido',$objuser->getSurName());
    $time = strtotime($result['fechanac']);
    $month = date("m", $time);
    $year = date("Y", $time);
    $day = date("d", $time);
    $tpl->assign('aFechaNacimiento',$day . '/' . $month . '/' . $year);
    $tpl->assign('aCedula',$result['cedula']);
    $tpl->assign('aDpi',$result['dpi']);

    // Información de residencia
    $tpl->gotoBlock('_ROOT');
    $tpl->assign('aDireccion',$result['direccion']);

    // Información de formación de contacto
    $tpl->gotoBlock('_ROOT');
    $tpl->assign('aCorreoP',trim($result['correo1']));
    $tpl->assign('aTelP',trim($result['tel1']));
    $tpl->assign('aCelP',trim($result['cel1']));

    $objUserManager->ListarDepartamentos();
    $objUserManager->ListarMunicipios();
    $objUserManager->ListarNacionalidades();

    //Bloque que llena el array con los departamentos cargados
    $departamentos = $objUserManager->mDepartamentos;
    $tamaño = $objUserManager->mnodepartamentos;
    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("VECTOR_DEPARTAMENTO");
        $tpl->assign("index", $i+1);
        $tpl->assign("cod_depto", $departamentos[$i][0]);
        $tpl->assign("nom_depto", $departamentos[$i][1]);
    }
    //Ahora llena el combo de departamento
    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("LLENAR_SELECT_DEPTO");
        $tpl->assign("indice_depto", $departamentos[$i][0]);
        $tpl->assign("nombre_depto", $departamentos[$i][1]);
        $tpl->assign("no_orden", $departamentos[$i][2]);
    }

    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("LLENAR_SELECT_DEPTO2");
        $tpl->assign("indice_depto2", $departamentos[$i][0]);
        $tpl->assign("nombre_depto2", $departamentos[$i][1]);
        $tpl->assign("no_orden2", $departamentos[$i][2]);
    }

    //Bloque que llena el array con los municipios previamente cargados
    $municipios = $objUserManager->mMunicipios;
    $tamaño = $objUserManager->mnomunicipios;
    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("VECTOR_MUNICIPIO");
        $tpl->assign("indice", $i+1);
        $tpl->assign("depto", $municipios[$i][0]);
        $tpl->assign("munic", $municipios[$i][1]);
        $tpl->assign("nom_munic", $municipios[$i][2]);
    }
    //Ahora llena el combo de municipio
    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("LLENAR_SELECT_MUNIC");
        $tpl->assign("i_munic", $i+1);
        $tpl->assign("indice_munic", $municipios[$i][0]);
        $tpl->assign("indice_depto", $municipios[$i][1]);
        $tpl->assign("nombre_munic", $municipios[$i][2]);
    }
    $indice_select = 0;
    //Ahora llena el combo de municipio
    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("LLENAR_SELECT_MUNIC2");
        $tpl->assign("i_munic2", $i+1);
        $tpl->assign("indice_munic2", $municipios[$i][0]);
        $tpl->assign("indice_depto2", $municipios[$i][1]);
        $tpl->assign("nombre_munic2", $municipios[$i][2]);

        if((int)($result['departamento_dir']).$result['municipio_dir']==((int)($municipios[$i][0].$municipios[$i][1]))){
            $indice_select = $i+1;
        }
    }

    $nacionalidades= $objUserManager->mNacionalidades;
    $tamaño = $objUserManager->mnonacionalidades;

    //Ahora llena el combo de municipio
    for($i = 0; $i < $tamaño; $i++) {
        $tpl->newBlock("LLENAR_SELECT_NAC");
        $tpl->assign("indice_nac", $nacionalidades[$i][0]);
        $tpl->assign("nombre_nac", $nacionalidades[$i][2]);

        if($result['nacionalidad'] == $nacionalidades[$i][0]) {
            $tpl->assign("aSelected", 'selected');
        }
    }

    $tpl->gotoBlock('_ROOT');

    $tpl->newBlock('INIT');
    $tpl->assign('aSelGenero',$result['sexo']);
    $tpl->assign('aSelEstadoCivil',$result['estadocivil']);
    $tpl->assign("extdepto", (int)($result['departamentocedula']));
    $tpl->assign("extmunic", (int)($result['municipiocedula']));
    $tpl->assign("resdepto", (int)($result['departamento_dir']));
    $tpl->assign("resmunic", $indice_select);
    $tpl->assign("paisselect", (int)($result['paispasaporte']));

    $tpl->gotoBlock('_ROOT');
    $tpl->assign( "anchoImg", ANCHO_IMG);
    $tpl->assign( "altoImg", ALTO_IMG);

    /*$connection = new MongoClient(MONGO_CLIENT);
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
    }
*/
    $tpl->printToScreen();

    unset($tpl,$objUserManager,$connection);
} else {

}

?>