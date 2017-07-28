<?php
    require_once("Validator.php");

    class ValidatorPassword extends Validator
    {
        private $strSec = '!#$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~ÇüéâäàåçêëèïîìÄÅÉæÆôöòùÿÖÜø£Ø×áíóúñÑªº¿®¬½¼¡«»ÁÂÀ©¢¥ãÃ¤ðÐÊËÈÍÎÏ¦ÌÓßÔÒõÕµþÞÚÛÙýÝ¯´­±¾û§÷¸°¨·¹³²';

        public function ValidatorPassword($namefield, $field, $required)
        {
            $this->setNameField($namefield);
            $this->setField($field); // Password sin encriptar
            $this->setRequired($required);
        }

        public function verify($passcomp)
        {
            if (!$this->validate()) {
                return FALSE;
            } else {
                if ($this->isEmpty()) return TRUE;
            }

            if (!$passcomp) {
                $this->addMessage('\n El campo de repetir contraseña es requerido');

                return FALSE;
            }

            if ($this->getField() != $passcomp) {
                echo 'debe ingresar la misma contrasenia';
                $this->addMessage('\n Debe ingresar la misma constraseña');

                return FALSE;
            }

            return TRUE;
        }

        //***********************************************************************
        // Funcion para obtener el orden de transposiciòn de encripciòn
        // recibe el Id de session(32 caracteres) y analiza las posiciones 0,6,12,
        // 18,24 y 30 para devolver un juego de 5 caracteres.
        // Esta función está duplicada en libFuncionesInicio.inc como funOrdenT.
        // Es importante que ambas funciones se mantengan sincronizadas.
        //***********************************************************************
        private function funOrden($ids)
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

        //********************************************************************
        // Esta función genera un string de 10 dígitos aleatorios.  Este
        // string es utilizado como semilla para generar la cadena de
        // caracteres válidos al momento de encriptar.  El valor devuelto
        // debe ser almacenado en una variable de sesión como $_SESSION["strRan"].
        //********************************************************************
        private function funGenera()
        {
            $ranN = "";
            srand((float)microtime() * 1000000);
            for ($i = 0; $i < 10; $i++) $ranN = $ranN . rand(0, 9);

            return $ranN;
        }

        //**********************************************************************
        // Funcion que da un orden semi-aleatorio al juego de caracteres validos
        // para encriptar.
        //**********************************************************************
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
                }
            }

            return $aleatoria;
        }

        //**********************************************************************
        // Realiza encriptación del password para verificar si es el correcto.
        //**********************************************************************
        private function cript()
        {
            $_SESSION["strRan"] = $this->funGenera();
            $strSec = $this->funVuelta($this->strSec, $_SESSION["strRan"]);
            $intCociente = 0;
            $intResiduo = 0;
            $CantCar = 0;
            $chrCaracter = '';
            $strRnd = '';
            $strBhex = '';
            $strPwd = '';
            $strDec = '';
            $strTmp = '';
            $strOrd = '';
            $strAux = '';
            $strCaracteres = '';

            $strPwd = $this->getField();

            $strRnd = session_id();
            $strCaracteres = '' . $strSec . '';
            $strAux = $this->funOrden(session_id());
            $strOrd = "";
            $strDec = "";
            $strTmp = "";

            $CantCar = 16 - strlen($strPwd);

            for ($m = 1; $m <= $CantCar; $m++) {
                $strPwd = $strPwd . '§';
            }

            for ($i = 0; $i < strlen($strPwd); $i++) {
                $strTmp = '' . ord($strPwd[$i]);

                for (; strlen($strTmp) < 3;) {
                    $strTmp = '0' . $strTmp;

                }
                $strDec = $strDec . $strTmp;
            }
            $strTmp = '';

            for ($i = 0; $i < strlen($strDec); $i++) {
                $strTmp = $strTmp . '' . '' . (ord($strDec[$i]) - 18);
            }

            $strDec = $strTmp;
            $strTmp = '';

            for ($i = 0; $i < strlen($strDec); $i++) {
                $strTmp = $strTmp . '' . $strCaracteres[(ord($strRnd[$i % strlen($strRnd)]) + ord($strDec[$i])) % 181];
            }

            $strTmp = $strTmp . substr($strRnd, strlen($strRnd) - 100 + strlen($strTmp), 12);

            $strOrd = '';

            for ($i = 0; $i < 10; $i++) {
                $intCociente = ord($strAux[$i]) - 48;
                for ($j = 0; $j < 10; $j++) {
                    $intResiduo = ($j * 10) + $intCociente;
                    $strOrd = $strOrd . $strTmp[$intResiduo];
                }
            }

            return $strOrd;
        }
    }

?>