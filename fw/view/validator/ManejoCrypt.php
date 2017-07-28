<?php

/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 7/08/14
 * Time: 02:04 PM
 */
class Encripta
{
    // Atributos de la clase
    var $mInfoTrans;
    var $mInfoDevuelve;


    function Encripta()
    {
        //   Inicialización de atributos
        $this->mInfoTrans = '';
        $this->mInfoDevuelve = '';
    }

    function Transforma($string)
    {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr(LLAVE, ($i % strlen(LLAVE)), 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return base64_encode($result);
    }

    function Devuelve($string)
    {
        $result = '';
        $string = base64_decode($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr(LLAVE, ($i % strlen(LLAVE)), 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }

} //Fin de Clase Encripta

?>