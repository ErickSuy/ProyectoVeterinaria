<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../../path.inc.php");

class RequestOrden
{
    public function RequestOrden() {
        
    }
    
    
    
    public function noOrden($ultima)
    {
        $numero=$ultima+1;
        
        return $numero;
    }
}


?>
