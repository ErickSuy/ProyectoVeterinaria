<?php

    class Validator
    {
        private $empty = FALSE;
        private $namefield;
        private $field;
        private $message;
        private $required; // Tipo Boleeando
        private $min; // Tipo entero valor minimo de caracteres
        private $max; // Tipo entero valor maximo de caracteres;


        public function setEmpty($empty)
        {
            $this->empty = $empty;
        }

        public function isEmpty()
        {
            return $this->empty;
        }


        public function setNameField($namefield)
        {
            $this->namefield = $namefield;
        }

        public function getNameField()
        {
            return $this->namefield;
        }

        public function setField($field)
        {
            $this->field = $field;
        }

        public function getField()
        {
            return $this->field;
        }

        public function addMessage($message)
        {
            $this->message .= $message;
        }

        public function setMessage($message)
        {
            $this->message = $message;
        }

        public function getMessage()
        {
            return $this->message;
        }

        public function setRequired($required)
        {
            $this->required = $required;
        }

        public function isRequired()
        {
            return $this->required;
        }

        public function setMin($min)
        {
            $this->min = $min;
        }

        public function getMin()
        {
            return $this->min;
        }

        public function setMax($max)
        {
            $this->max = $max;
        }

        public function getMax()
        {
            return $this->max;
        }

        public function validate()
        {
            $this->message = $this->namefield . ': ';

            if ($this->required) {
                if ($this->field != 0 and empty($this->field)) {
                    $this->message = $this->message . '\n Este campo es requerido';

                    return FALSE;
                }
            } else {
                if (!$this->field) {
                    $this->field = NULL;
                    $this->empty = TRUE;

                    return TRUE;
                }
            }

            if ($this->min and strlen($this->field) < $this->min) {
                $this->message = $this->message . '\n Debe introducir minimo ' . $this->min . " caracteres";

                return FALSE;
            }

            if ($this->max and strlen($this->field) > $this->max) {
                $this->message = $this->message . '\n Debe introducir maximo ' . $this->max . " caracteres";

                return FALSE;
            }

            return TRUE;
        }
    }

?>