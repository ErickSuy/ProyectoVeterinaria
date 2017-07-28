<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 01/02/2015
 * Time: 4:35 PM
 */

require_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");

session_start();    //Inicio de sesión

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$vector_modi[0] = $objuser->getId();//Obtiene el usuario
$vector_modi[1] = $objuser->getGroup();  //Obtiene el grupo

$connection = new MongoClient(MONGO_CLIENT);
$db = $connection->selectDB(MONGO_DB);

$grid = $db->getGridFS(MONGO_COLLECTION);
$image = $grid->findOne($vector_modi[0]);

//header("Content-type: image/jpeg");
if (null == $image) {
    $image = $grid->findOne('000000000');
    //echo $image->getBytes();

} else {
    //echo $image->getBytes();
$image->write('/var/www/uploads/'.$vector_modi[0].'.jpg');
}
?>