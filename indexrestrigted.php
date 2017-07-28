<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function obtenerDireccionIP()
{
    if (!empty($_SERVER ['HTTP_CLIENT_IP'] ))
      $ip=$_SERVER ['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER ['HTTP_X_FORWARDED_FOR'] ))
      $ip=$_SERVER ['HTTP_X_FORWARDED_FOR'];
    else
      $ip=$_SERVER ['REMOTE_ADDR'];
 
    return $ip;
}

function restringirRango()
{
    $ipCliente = obtenerDireccionIP();
     
    if((substr($ipCliente, 0, 3 ) == "10."))
    {
        header('location: http://biblioteca.fmvz.usac.edu.gt/cgi-bin/koha/pages.pl?p=eresources');
    }
    else
    {
        header('location: http://biblioteca.fmvz.usac.edu.gt/cgi-bin/koha/pages.pl?p=error_access');
        
    }
}

restringirRango();
/*
if(restringirRango()){
    //header('Location: '."https://www.dropbox.com/s/odxrdf3vt0hng4n/Api%20Pa%2C%20Mo%20Aptoward%20T%2C%20D%20D%2C%20DruDis%20in%20I%20Di.pdf?dl=0");
  //  echo '<iframe src="https://www.dropbox.com/s/odxrdf3vt0hng4n/Api%20Pa%2C%20Mo%20Aptoward%20T%2C%20D%20D%2C%20DruDis%20in%20I%20Di.pdf?dl=0" width="100%" height="700" frameborder="0"></iframe>';
   // echo '<iframe src="https://www.w3schools.com" width="100%" height="700" frameborder="0"></iframe>';
    
    echo "adfa";die;
   
    
}*/