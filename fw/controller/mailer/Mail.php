<?php

    require_once("class.phpmailer.php");
    require_once("class.smtp.php");

    class Mail
    {
        private $mail;

        public function Mail()
        {
            $this->mail = new PHPMailer();
            $this->mail->IsSMTP();
            $this->mail->SMTPAuth = TRUE;
            $this->mail->SMTPSecure = "ssl";
            $this->mail->Host = "smtp.gmail.com";
            $this->mail->Port = 465;
            $this->mail->Username = "no.reply.saesap@gmail.com";
            $this->mail->Password = "Sa35apwe8";
            /*$this->mail->Host = "correo.ingenieria-usac.edu.gt";
            $this->mail->Port = 25;
            $this->mail->Username = "itcoeprueba";
            $this->mail->Password = "itcoe42";*/
            $this->mail->From = "no.reply.saesap@gmail.com";
            $this->mail->FromName = "Equipo SAESAP";
        }

        public function send($mail, $name, $surname, $subject, $message, $messagetxt, &$result)
        {
            if ($subject and $message) {
                $this->mail->Subject = $subject;
                $this->mail->AltBody = $messagetxt;
                $this->mail->MsgHTML($message);
                $this->mail->IsHTML(TRUE);
                $this->mail->AddAddress($mail, $name . "  " . $surname);

                if (!$this->mail->Send()) {
                    $result = "Error: " . $this->mail->ErrorInfo;

                    return "Error: " . $this->mail->ErrorInfo;
                } else {
                    $result = 'OK';

                    return "Se ha enviado un correo, a la direccion proporcionada para verficiacion";
                }
            } else {

                $result = "Error: No se tienen todos los campos obligatorios  del correo";

                return "Error: No se tienen todos los campos obligatorios  del correo";
            }
        }
    }

?>