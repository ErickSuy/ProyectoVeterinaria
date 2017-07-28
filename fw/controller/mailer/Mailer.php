<?php
    require_once("Mail.php");

    class Mailer extends Mail
    {
        private $destinationmail;
        private $linkactivate;
        private $objuser;
        private $link;
        private $subject;
        private $message;
        private $messagetxt;

        public function Mailer($objuser)
        {
            $this->Mail();
            $this->destinationmail = $objuser->getMail();
            $this->objuser = $objuser;
            $this->setLink();
            $this->setSubject();
            $this->setMessage();
            $this->setMessageTxt();

        }

        private function setLink()
        {
            $path = "http://saesap.ingenieria.usac.edu.gt/sigca_desarrollo/fw/view/";
            $this->link = $path . "ServiceAuth.php?service=activate&code=" . $this->objuser->getIdUser() . "&codegenerate=" . md5('getIdUser(' . $this->objuser->getIdUser() . ')') . "&activatekey=" . $this->objuser->getActivateLink() . "";
        }

        public function getLink()
        {
            return $this->link;
        }

        private function setSubject()
        {
            $this->subject = "Activacion de usuario SAE/SAP";
        }

        public function getSubject()
        {
            return $this->subject;
        }

        private function setMessage()
        {
            $mail = $this->objuser->getMail();
            $this->message = '<table cellpadding="8" cellspacing="0" style="padding:0;width:100%!important;background:#ffffff;margin:0;background-color:#ffffff" border="0">
                                                    <tbody>
                                                        <tr><td valign="top">
                                                            <table cellpadding="0" cellspacing="0" style="border-radius:4px;border:1px #dceaf5 solid" border="0" align="center">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="3" height="6"></td> 
                                                                </tr>
                                                                <tr style="line-height:0px">
                                                                    <td width="100%" style="font-size:0px" align="center" height="1">
                                                                        <img width="80px" style="max-height:90px;width:200px" alt="" src="http://saesap.ingenieria.usac.edu.gt/sigca_desarrollo/resources/images/fmvz-img.jpg">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table cellpadding="0" cellspacing="0" style="line-height:25px" border="0" align="center">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan="3" height="30">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="36">
                                                                                    </td>
                                                                                    <td width="454" align="left" style="color:#444444;border-collapse:collapse;font-size:11pt;font-family:proxima_nova,\'Open Sans\',\'Lucida Grande\',\'Segoe UI\',Arial,Verdana,\'Lucida Sans Unicode\',Tahoma,\'Sans Serif\';max-width:454px" valign="top">
                                                                                        Hola ' . $this->objuser->getName() . '   ' . $this->objuser->getSurName() . ', te damos la bienvenida a SAE/SAP.
                                                                                        <br>
                                                                                        <br>
                                                                                        Tu usuario es: <span class="il">' . $mail . '</span>
                                                                                        <br>
                                                                                        <br>
                                                                                        Tu contraseña es: ' . $this->objuser->getPassw() . '
                                                                                        <br>
                                                                                        <br>
                                                                                        Actualmente tu cuenta esta desactivada, puedes activarla <a href="' . $this->link . '" target="_blank">aquí</a>:
                                                                                        <br>
                                                                                        <br>
                                                                                        <center>
                                                                                            <a style="border-radius:3px;color:white;font-size:15px;padding:14px 7px 14px 7px;max-width:210px;font-family:proxima_nova,\'Open Sans\',\'lucida grande\',\'Segoe UI\',arial,verdana,\'lucida sans unicode\',tahoma,sans-serif;border:1px #1373b5 solid;text-align:center;text-decoration:none;width:210px;margin:6px auto;display:block;background-color:#007ee6" href="' . $this->link . '" target="_blank">Activar Cuenta</a>
                                                                                        </center>
                                                                                       <br>
                                                                                       Si no te has registrado en nuestra plataforma, haz caso omiso de este mensaje.
                                                                                       <br>
                                                                                       <br>
                                                                                       ¡Gracias!
                                                                                       <br>
                                                                                       - El equipo de <span class="il">Sae/Sap</span>, no es necesario que respondas este mail.
                                                                                    </td>
                                                                                    <td width="36">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="3" height="36">
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                     </td>
                                                                  </tr>
                                                               </tbody>
                                                            </table>
                                                            <table cellpadding="0" cellspacing="0" align="center" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td height="10">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding:0;border-collapse:collapse">
                                                                        <table cellpadding="0" cellspacing="0" align="center" border="0">
                                                                        <tbody>
                                                                        <tr style="color:#a8b9c6;font-size:11px;font-family:proxima_nova,\'Open Sans\',\'Lucida Grande\',\'Segoe UI\',Arial,Verdana,\'Lucida Sans Unicode\',Tahoma,\'Sans Serif\'">
                                                                            <td width="400" align="left">
                                                                            </td>
                                                                            <td width="128" align="right">
                                                                            © 2014 <span class="il">Sae/Sap</span>
                                                                            </td>
                                                                        </tr>
                                                            </tbody>
                                                            </table>
                                                       </td>
                                                    </tr>
                                                  </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                  </tbody>
                             </table>';


        }

        public function getMessage()
        {
            return $this->message;
        }

        private function setMessageTxt()
        {
            $mail = $this->objuser->getMail();
            $this->messagetxt = '  SU CLAVE : ' . $this->objuser->getPassw() . '\n
         	SU LINK DE ACTIVACION:' . $this->link . '\n
         	POR FAVOR HAGA CLICK EN LINK DE ARRIBA PARA ACTIVAR SU CUENRA Y ACCEDER A LA PAGINA SIN RESTRICCIONES\n
         	SI EL LINK NO FUNCIONA ALA PRIMERA COPIE Y PEGUE EL LINK\n         
         	GRACIAS POR REGISTRARSE EN SAESAP.\n';

        }

        public function getMessageTxt()
        {
            return $this->messagetxt;
        }


        public function sender(&$result)
        {
            return $this->send($this->objuser->getMail(), $this->objuser->getName(), $this->objuser->getSurName(), $this->getSubject(), $this->getMessage(), $this->getMessageTxt(), $result);
        }

    }

?>
