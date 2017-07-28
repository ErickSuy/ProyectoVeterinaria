<?php
    require_once("Validator.php");

    class ValidatorMail extends Validator
    {
        public function ValidatorMail($namefield, $field, $required)
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

            if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $this->getField())) {
                $this->addMessage('\n Porfavor escriba un correo electronico valido');

                return FALSE;
            }

            return TRUE;
        }
    }

?>