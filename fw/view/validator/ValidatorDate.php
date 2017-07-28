<?php
    require_once("Validator.php");

    class ValidatorDate extends Validator
    {
        public function ValidatorDate($namefield, $field, $required)
        {
            $this->setNameField($namefield);
            $this->setField($field);
            $this->setRequired($required);
        }

        public function verify()
        {
            if (!$this->validate()) {
                return FALSE;
            } else {
                if ($this->isEmpty()) return TRUE;
            }

            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $this->getField())) {
                $this->addMessage('\n Porfavor escriba una fecha valida');

                return FALSE;
            }

            return TRUE;
        }

        public function date_format($date)
        {
            //list($dia_ini, $mes_ini, $ano_ini) = explode('[/.-]', $date);
            //$fecha[]= explode('[/.-]', $date);

            // $date_format = $ano_ini . '-' . $mes_ini . '-' . $dia_ini;
            //$date_format = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];

            $dateArr = explode("/", $date);
            $fecha = $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];

            return $fecha;
        }
    }

?>