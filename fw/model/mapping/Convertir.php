<?php

    class Convertir
    {

        function ConvertirNumero($numero)
        {
            if ($numero == 1) {
                $valor = "un";
            } elseif ($numero > 1 && $numero < 30) {
                $valor = $this->getUnidad($numero);
            } elseif ($numero >= 30 && $numero <= 100) {
                $decena = $this->getDecena($numero);
                $numero = $numero % 10;

                if ($numero > 0) {
                    $unidad = $this->getUnidad($numero);
                    $valor = $decena . ' y ' . $unidad;
                } else {
                    $valor = $decena;
                }

            } elseif ($numero > 100 && $numero < 1000) {
                $centena = $this->getCentena($numero);
                $numero = $numero % 100;

                if ($numero > 0) {
                    if ($numero < 30) {
                        $decena = $this->getUnidad($numero);
                        $valor = $centena . " " . $decena;
                    } else {
                        $decena = $this->getDecena($numero);
                        $numero = $numero % 10;

                        $unidad = $this->getUnidad($numero);

                        if ($numero == 0) {
                            $valor = $centena . " " . $decena;
                        } else {
                            $valor = $centena . " " . $decena . " y " . $unidad;
                        }
                    }

                } else {

                    $valor = $centena;
                }


            } elseif ($numero >= 1000 && $numero < 10000) {


                $millar = $this->getMil($numero);
                $numero = $numero % 1000;

                if ($numero > 0) {
                    $centena = $this->getCentena($numero);
                    $numero = $numero % 100;

                    if ($numero > 0) {
                        if ($numero < 30) {
                            $decena = $this->getUnidad($numero);
                            $valor = $millar . " " . $centena . " " . $decena;
                        } else {
                            $decena = $this->getDecena($numero);
                            $numero = $numero % 10;

                            $unidad = $this->getUnidad($numero);

                            if ($numero == 0) {
                                $valor = $millar . " " . $centena . " " . $decena;
                            } else {
                                $valor = $millar . " " . $centena . " " . $decena . " y " . $unidad;
                            }
                        }
                    } else {
                        $valor = $millar . " " . $centena;
                    }

                } else {
                    $valor = $millar;
                }


            }

            return $valor;
        }

        function getUnidad($numero)
        {

            switch ($numero) {
                Case 0:
                    $dia = "";
                    break;
                Case 1:
                    $dia = "uno";
                    break;
                Case 2:
                    $dia = "dos";
                    break;
                Case 3:
                    $dia = "tres";
                    break;
                Case 4:
                    $dia = "cuatro";
                    break;
                Case 5:
                    $dia = "cinco";
                    break;
                Case 6:
                    $dia = "seis";
                    break;
                Case 7:
                    $dia = "siete";
                    break;
                Case 8:
                    $dia = "ocho";
                    break;
                Case 9:
                    $dia = "nueve";
                    break;
                Case 10:
                    $dia = "diez";
                    break;
                Case 11:
                    $dia = "once";
                    break;
                Case 12:
                    $dia = "doce";
                    break;
                Case 13:
                    $dia = "trece";
                    break;
                Case 14:
                    $dia = "catorce";
                    break;
                Case 15:
                    $dia = "quince";
                    break;
                Case 16:
                    $dia = "dieciseis";
                    break;
                Case 17:
                    $dia = "diecisiete";
                    break;
                Case 18:
                    $dia = "dieciocho";
                    break;
                Case 19:
                    $dia = "diecinueve";
                    break;
                Case 20:
                    $dia = "veinte";
                    break;
                Case 21:
                    $dia = "veintiuno";
                    break;
                Case 22:
                    $dia = "veintidos";
                    break;
                Case 23:
                    $dia = "veintitres";
                    break;
                Case 24:
                    $dia = "veinticuatro";
                    break;
                Case 25:
                    $dia = "veinticinco";
                    break;
                Case 26:
                    $dia = "veintiseis";
                    break;
                Case 27:
                    $dia = "veintisiete";
                    break;
                Case 28:
                    $dia = "veintiocho";
                    break;
                Case 29:
                    $dia = "veintinueve";
                    break;

            }


            return $dia;
        }


        function getDecena($numero)
        {
            $decena = "";
            if ($numero > 20 && $numero < 30) {
                $decena = "veinti";
            } elseif ($numero >= 30 && $numero < 40) {
                $decena = "treinta";
            } elseif ($numero >= 40 && $numero < 50) {
                $decena = "cuarenta";
            } elseif ($numero >= 50 && $numero < 60) {
                $decena = "cincuenta";
            } elseif ($numero >= 60 && $numero < 70) {
                $decena = "sesenta";
            } elseif ($numero >= 70 && $numero < 80) {
                $decena = "setenta";
            } elseif ($numero >= 80 && $numero < 90) {
                $decena = "ochenta";
            } elseif ($numero >= 90 && $numero < 100) {
                $decena = "noventa";
            } elseif ($numero >= 100) {
                $decena = "cien";
            }


            return $decena;
        }

        function getCentena($numero)
        {
            $decena = "";
            if ($numero > 100 && $numero < 200) {
                $decena = "ciento";
            } elseif ($numero >= 200 && $numero < 300) {
                $decena = "doscientos";
            } elseif ($numero >= 300 && $numero < 400) {
                $decena = "trescientos";
            } elseif ($numero >= 400 && $numero < 500) {
                $decena = "cuatrocientos";
            } elseif ($numero >= 500 && $numero < 600) {
                $decena = "quinientos";
            } elseif ($numero >= 600 && $numero < 700) {
                $decena = "seiscientos";
            } elseif ($numero >= 700 && $numero < 800) {
                $decena = "setecientos";
            } elseif ($numero >= 800 && $numero < 900) {
                $decena = "ochocientos";
            } elseif ($numero >= 900 && $numero < 1000) {
                $decena = "novecientos";
            }

            return $decena;
        }

        function getMil($numero)
        {
            $mil = "";
            if ($numero >= 1000 && $numero < 2000) {
                $mil = "mil";
            } elseif ($numero >= 2000 && $numero < 3000) {
                $mil = "dos mil";
            } elseif ($numero >= 3000 && $numero < 4000) {
                $mil = "tres mil";
            } elseif ($numero >= 4000 && $numero < 5000) {
                $mil = "cuatro mil";
            } elseif ($numero >= 5000 && $numero < 6000) {
                $mil = "cinco mil";
            } elseif ($numero >= 6000 && $numero < 7000) {
                $mil = "seis mil";
            } elseif ($numero >= 7000 && $numero < 8000) {
                $mil = "siete mil";
            } elseif ($numero >= 8000 && $numero < 9000) {
                $mil = "ocho mil";
            } elseif ($numero >= 9000 && $numero < 10000) {
                $mil = "nueve mil";
            }

            return $mil;
        }
    }

?>
