<?php
    require_once("Validator.php");

    class ValidatorDefault extends Validator
    {
        public function ValidatorDefault($namefield, $field, $required)
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

            return TRUE;
        }
    }

?>