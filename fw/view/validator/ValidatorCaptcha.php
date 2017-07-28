<?php
    require_once("Validator.php");

    class ValidatorCaptcha extends Validator
    {
        private $hidfield;

        public function ValidatorCaptcha($namefield, $field, $hidfield, $required)
        {
            $this->setNameField($namefield);
            $this->setField($field);
            $this->setRequired($required);
            $this->hidfield = $hidfield;
        }

        public function setHidField($hidfield)
        {
            $this->hiedfield = $hidfield;
        }

        public function getHidField()
        {
            return $this->hidfield;
        }

        public function verify()
        {
            if ($this->hidfield == md5('getcaptcha(' . $this->getField() . ')')) {
                return TRUE;
            } else {
                $this->addMessage('C&oacutedigo de Seguridad no valido');

                return FALSE;

            }
        }
    }

?>