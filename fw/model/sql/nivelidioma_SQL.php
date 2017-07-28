<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("$dir_portal/fw/model/Connection.php");

Class nivelidioma_SQL
{
    private $objConnection;
    
    function nivelidioma_SQL()
    {
        $this->objConnection = new Connection();
    }

    /**
     * String de la consulta de niveles de idiomas para un estudiante especifico
     * @param int $idstudent - registro academico del estudiante
     * @return array con los certificados almacenados para el estudiante consultado
     */
    function nivelIdiomaRegistrado_select($idstudent)
    {
        $vecResult = NULL;
        if ($this->objConnection->prepared("SELECT_NIVELIDIOMA","SELECT * FROM tbapprovedlanguajecourse where idstudent=$1::numeric;")){
            $result = $this->objConnection->ejecuteStatement("SELECT_NIVELIDIOMA", array($idstudent));

            if ($result) {
                while ($row = $this->objConnection->getResult($result)) {
                    $rRow = array(
                        'idstudent'=> $row['idstudent'],
                        'level' => $row['level'],
                        'receptiondate' => $row['receptiondate']);
                    $vecResult[] = $rRow;
                }
            }
        }
        return $vecResult;
        
    }

    /**
     * String de inserción para un nuevo registro para un nivel de idioma
     * @param int $idstudent - registro academico del estudiante
     * @param int $level - nivel del certificado que presento
     * @return string con resultado de la transaccion
     */
    function registrarNivelIdioma_insert($idstudent, $level)
    {
        $result = NULL;
        if ($this->objConnection->prepared("INSERT_NIVELIDIOMA","INSERT INTO tbapprovedlanguajecourse VALUES($1::numeric,$2::numeric,now());")){
            $result = $this->objConnection->ejecuteStatement("INSERT_NIVELIDIOMA", array($idstudent,$level));
        }
        return $result;
        
    }
    

}

