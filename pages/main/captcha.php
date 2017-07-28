<?php
    header('Content-Type: image/gif');

    $texto = $_REQUEST['texto'];

    $captcha = imagecreatefromgif("bgcaptcha.gif");
    $colText = imagecolorallocate($captcha, 0, 0, 0);
    imagestring($captcha, 5, 16, 7, $texto, $colText);
    imagegif($captcha);

?>                                                                                                                                                                                                                                                                                                                                                         