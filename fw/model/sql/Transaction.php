<?php
/***************************************************************
 * Manejo del número de transacción utilizada en los procesos de:
 * Inscripción
 * Asignación de cursos
 ****************************************************************/

/* 
 * Incluyendo archivo con sentencias SQL 
 */
include_once("Transaction_SQL.php");


class Transaction
{
    var $mTransaccion = 0; // Almacena el número de transacción generada

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;


    /* Constructor */
    public function Transaction()
    {
        // Se realiza una conexion con Servidor de Base de datos
        $_SESSION["sConTrans"] = NEW DB_Connection();
        $_SESSION["sConTrans"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new Transaction_SQL();
    }

    /* Obtiene el siguiente correlativo en la secuencia "numinscripcion" */
    function ObtenerTransaccion()
    {
        // Inicia transacción en la base de datos
        $query_trans = $this->gsql->begin();

        $_SESSION["sConTrans"]->query($query_trans);
        if (!$_SESSION["sConTrans"]->Query_ID) {
            echo("Error: No se puede generar una trasacción en este momento.");
        }

        $query_trans = $this->gsql->ObtenerTransaccion_select1();

        $_SESSION["sConTrans"]->query($query_trans);
        if (!$_SESSION["sConTrans"]->Query_ID) {
            echo("Error: No se puede generar una trasacción en este momento.");
        } else {
            $_SESSION["sConTrans"]->next_record();
            $this->mTransaccion = $_SESSION["sConTrans"]->f("nextval"); // Asignar el número de Transacción obtenido
        }

        $query_trans = $this->gsql->end();

        $_SESSION["sConTrans"]->query($query_trans);
        if (!$_SESSION["sConTrans"]->Query_ID) {
            echo("Error: No se puede finalizar la trasacción en este momento.");
        }
        return $this->mTransaccion; // Devuelve el número de trasacción generado, error devuelve 0
    }

    public function Encriptar($accion, $string, $key = "1")
    {
        $string = $this->toUnicode($string);
        $key = $this->toUnicode($key);

        $result = '';
        $keychar = ord($key);
        for ($i = 1; $i <= strlen($string); $i++) {
            $char = ord(substr($string, $i - 1, 1));
            if ($accion) {
                $op = $char + $keychar;
                if ($op > 255)
                    $op = $op - 255;
            } else {
                $op = $char - $keychar;
                if ($op < 0)
                    $op = $op + 255;
            }
            $result .= chr($op);
        }
        return $this->toUTF8($result);
    }

    function toUTF8($txt)
    {
        $encoding = mb_detect_encoding($txt, 'ASCII,UTF-8,ISO-8859-1');
        if ($encoding == "ISO-8859-1") {
            //Caracteres que no tiene representación en la tabla de caracteres UTF8 respecto a tabla Unicode (128-160)
            $txt = ereg_replace(chr(128), "&#8364;", $txt);
            $txt = ereg_replace(chr(130), "&sbquo;", $txt); // Single Low-9 Quotation Mark
            $txt = ereg_replace(chr(131), "&fnof;", $txt); // Latin Small Letter F With Hook
            $txt = ereg_replace(chr(132), "&bdquo;", $txt); // Double Low-9 Quotation Mark
            $txt = ereg_replace(chr(133), "&hellip;", $txt); // Horizontal Ellipsis
            $txt = ereg_replace(chr(134), "&dagger;", $txt); // Dagger
            $txt = ereg_replace(chr(135), "&Dagger;", $txt); // Double Dagger
            $txt = ereg_replace(chr(136), "&circ;", $txt); // Modifier Letter Circumflex Accent
            $txt = ereg_replace(chr(137), "&permil;", $txt); // Per Mille Sign
            $txt = ereg_replace(chr(138), "&Scaron;", $txt); // Latin Capital Letter S With Caron
            $txt = ereg_replace(chr(139), "&lsaquo;", $txt); // Single Left-Pointing Angle Quotation Mark
            $txt = ereg_replace(chr(140), "&OElig;", $txt); // Latin Capital Ligature OE
            $txt = ereg_replace(chr(141), "&#141;", $txt);
            $txt = ereg_replace(chr(142), "&#8211;", $txt);
            $txt = ereg_replace(chr(143), "&#143;", $txt);
            $txt = ereg_replace(chr(144), "&#144;", $txt);
            $txt = ereg_replace(chr(145), "&lsquo;", $txt); // Left Single Quotation Mark
            $txt = ereg_replace(chr(146), "&rsquo;", $txt); // Right Single Quotation Mark
            $txt = ereg_replace(chr(147), "&ldquo;", $txt); // Left Double Quotation Mark
            $txt = ereg_replace(chr(148), "&rdquo;", $txt); // Right Double Quotation Mark
            $txt = ereg_replace(chr(149), "&#8226;", $txt); // bullet
            $txt = ereg_replace(chr(150), "&ndash;", $txt); // en dash
            $txt = ereg_replace(chr(151), "&mdash;", $txt); // em dash
            $txt = ereg_replace(chr(152), "&tilde;", $txt); // Small Tilde
            $txt = ereg_replace(chr(153), "&#8482;", $txt); // trademark
            $txt = ereg_replace(chr(154), "&scaron;", $txt); // Latin Small Letter S With Caron
            $txt = ereg_replace(chr(155), "&rsaquo;", $txt); // Single Right-Pointing Angle Quotation Mark
            $txt = ereg_replace(chr(156), "&oelig;", $txt); // Latin Small Ligature OE
            $txt = ereg_replace(chr(157), "&#157;", $txt);
            $txt = ereg_replace(chr(158), "&#382;", $txt); // Latin Capital Letter Y With Diaeresis
            $txt = ereg_replace(chr(159), "&Yuml;", $txt); // Latin Capital Letter Y With Diaeresis
            $txt = ereg_replace(chr(160), "&#160;", $txt);
            $txt = ereg_replace(chr(169), "&copy;", $txt); // copyright mark
            $txt = ereg_replace(chr(174), "&reg;", $txt); // registration mark

            $txt = utf8_encode($txt);
        }
        return $txt;
    }

    function toUnicode($txt)
    {
        $encoding = mb_detect_encoding($txt, 'ASCII,UTF-8,ISO-8859-1');
        if ($encoding == "UTF-8") {

            $txt = utf8_decode($txt);
        }
        return $txt;
    }

}

?>
