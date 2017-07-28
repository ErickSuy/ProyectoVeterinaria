<?php
header('Content-Type: text/html; charset=ISO-8859-1');

function funVuelta($original, $ran)
{
    $aleatoria = $original;
    $tempo = "";
    for ($i = 0; $i < 18; $i++) {
        for ($j = 0; $j < 10; $j++) {
            $Pos = $ran[$j];
            $tempo = $aleatoria[$j + ($i * 10)];
            $ot = (0 + $Pos) + ((($i + $j) % 18) * 10);
            $aleatoria[$j + ($i * 10)] = $aleatoria[$ot];
            $aleatoria[$ot] = $tempo;
        };
    };
    return $aleatoria;
}

function funOrdenT($ids)
{
    $idf = $ids[0] . $ids[6] . $ids[12] . $ids[18] . $ids[24] . $ids[30] . $ids[36] . $ids[42] . $ids[48] . $ids[54];
    $strAux = "0123456789";
    $intPos = 0;
    for ($i = 0; $i < 10; $i++) {
        $intPos = 0;
        for ($j = 0; $j < 10; $j++) {
            if ($i != $j) {
                if ($idf[$i] >= $idf[$j]) $intPos++;
            }
        }
        for ($j = $i + 1; $j < 10; $j++) {
            if ($idf[$i] == $idf[$j]) $intPos--;
        }
        $strAux[$i] = $intPos;
    }
    return $strAux;
}

function funOrden($ids)
{
    $strOrd = "";
    $idf = $ids[0] . $ids[6] . $ids[12] . $ids[18] . $ids[24] . $ids[30] . $ids[36] . $ids[42] . $ids[48] . $ids[54];
    $strOrd = "0123456789";
    $intPos = 0;
    for ($i = 0; $i < 10; $i++) {
        $intPos = 0;
        for ($j = 0; $j < 10; $j++) {
            if ($i != $j) {
                if ($idf[$i] >= $idf[$j]) $intPos++;
            }
        }
        for ($j = $i + 1; $j < 10; $j++) {
            if ($idf[$i] == $idf[$j]) $intPos--;
        }
        $strOrd[$i] = $intPos;
    }
    return $strOrd;
}

function funBusca($chrCar, $strValidos)
{
    for ($i = 0; $i < 186; $i++) {
        if ($strValidos[$i] == $chrCar) {
            return $i;
        };
    };
    return -1;

}

function funPos($strOT, $k)
{
    for ($i = 0; $i < 10; $i++)
        if ($strOT[$i] == $k) return $i;
    return -1;
}

function funDeCript($strPwdCrp)
{
//    global $_SESSION["strRan"];

// echo "--".$_SESSION["strRan"]."--<br>";
    if (strlen($_SESSION["strRan"]) != 10) {
//       echo "en el if"; die;
        return "-----";
    }

    //String original de caracteres para encriptar debe corresponder al string original en libSeguridad.php
    $strCaracteres = '!#$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~ÇüéâäàåçêëèïîìÄÅÉæÆôöòùÿÖÜø£Ø×áíóúñÑªº¿®¬œŒ¡«»ÁÂÀ©¢¥ãÃ€ðÐÊËÈÍÎÏŠÌÓßÔÒõÕµþÞÚÛÙýÝ¯Ž­±Ÿû§÷ž°š·¹³²';
    //String semi-aleatorio de caracteres para encriptar
    $strCaracteres = funVuelta($strCaracteres, $_SESSION["strRan"]);

//print_r($_SESSION["strRan"]);
    $strRnd = session_id();
    $strAux = "";
    $strAux = funOrdenT($strRnd);
    $strDecri = "";
    $strOrd = "";
    $strDec = "";
    $i = 0;
    $intCociente = 0;
    $intResiduo = 0;

    for ($i = 0; $i < 10; $i++) {
        for ($j = 0; $j < 10; $j++) {
            $intCociente = funPos($strAux, $j);
            $intResiduo = $i + ($intCociente * 10);
            $strOrd = $strOrd . $strPwdCrp[$intResiduo];
        };
    };

    for ($i = 0; $i < 96; $i++) {
        $intResiduo = funBusca($strOrd[$i], $strCaracteres);
        $intCociente = ($intResiduo - ord($strRnd[$i % strlen($strRnd)]));
        if ($intCociente < 0) $intCociente = 255 + $intCociente;
        $strDecri = $strDecri . chr($intCociente);
    };

    $strOrd = "";

    for ($i = 0; $i < 96; $i += 2) {
        $strDec = $strDecri[$i] . $strDecri[$i + 1];
        $intCociente = 18 + $strDec;
        $strOrd = $strOrd . chr($intCociente);
    };

    unset($strDecri);

    for ($i = 0; $i < 48; $i += 3) {
        $strDec = $strOrd[$i] . $strOrd[$i + 1] . $strOrd[$i + 2];
        $intCociente = 0 + $strDec;
        $Caracter = sprintf("%c", $intCociente);
        $strDecri .= $Caracter;
//        $strDecri=$strDecri.chr($intCociente);
    }

    //Elimina los caracteres agregados por longitud no 16
    for ($m = 0; $m < 16; $m++) {
        if (strcmp($strDecri[$m], "�") == 0) {
            $strDecri = substr($strDecri, 0, $m);
            $m = 16;
        }
    }
//    $strDecri[strlen($strDecri)]='\0';
//    $strDecri=trim($strDecri);
//    echo " StrDecri:$strDecri:<br>";
    return $strDecri;
}


?>
