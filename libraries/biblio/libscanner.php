<?php
require_once("libconst.php");

function esnumero($string)
{
    for($i = 0; $i < strlen($string); $i++)
    { //if (!in_array($string[$i],array("0","1","2","3","4","5","6","7","8","9"," ")))
        if (!in_array($string[$i],array("0","1","2","3","4","5","6","7","8","9")))
        { return false; }
    }
    return true;
}



function Precedencia($Valor,$Posicion)
{  // funcion donde se regresa el valor de la prescedencia de los operadores
    // que seran utilizados en la validacion de un
    switch ($Valor[$Posicion])
    {  case '(' : return cParAbierto;break;
        case ')' : return cParCerrado;break;
        case '&' : return cAnd;break;
        case '|' : return cOr;break;
        default  : return cError;
    }
} // fin Precedencia

function ScanNumero($StrPre,&$strToken,&$Puntero)
{
    $Puntero1 = $Puntero;
    $vartmp = substr($StrPre,$Puntero,1);

    while ((esnumero($vartmp)) && ($Puntero < strlen($StrPre)))
    { $Puntero++;
        $vartmp = substr($StrPre,$Puntero,1);
    }
    $strToken = substr($StrPre,$Puntero1,($Puntero - $Puntero1));
    // Elininacion de Blancos
    while (substr($StrPre,$Puntero,1) == ' ')
    { $Puntero++; }

    if (($StrPre[$Puntero] == "C") && ($StrPre[$Puntero+1] == "R"))
    { $Puntero = $Puntero + 2;
        $strToken = $strToken . "CR";
        return cCr; // retorna que el numer correponde a Creditos
    }
    if (strlen($strToken) != 3)
    { return cError; }  // retorno de Error

    return cNumero;  // retorna que encontro un numero
} // fin ScanNumero

function Scanner($strPre,&$strToken,&$Puntero)
{ while (1)
{  $strToken = '\0';
    switch ($strPre[$Puntero])
    {  case '(' :  $strToken = '(';
        $Puntero++;
        return(cParAbierto);
        break;
        case ')' :  $strToken = ')';
            $Puntero++;
            return(cParCerrado);
            break;
        case '&' :  $strToken = '&';
            $Puntero++;
            return(cAnd);
        case '|' :  $strToken = '|';
            $Puntero++;
            return(cOr);
            break;
        case ',' :  $strToken = ',';
            $Puntero++;
            return(cComa);
            break;
        case '0' :
        case '1' :
        case '2' :
        case '3' :
        case '4' :
        case '5' :
        case '6' :
        case '7' :
        case '8' :
        case '9' :  return(ScanNumero($strPre,$strToken,$Puntero));
            break;
        case ' ' :
        case '\t':  $Puntero++;
            break;
        case '\0':  return(cFin);
            break;
        default  :  $strToken = substr($strPre,$Puntero,1);
        $Puntero++;
        return(cError);
        break;
    } // end switch
} // end while
} // Fin Scanner
?>