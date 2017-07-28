<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 1/09/14
 * Time: 10:47 PM
 */

require_once("../../path.inc.php");
require_once("$dir_biblio/biblio/libscanner.php");
require_once("$dir_biblio/biblio/classRevisPrer.php");
require_once("$dir_portal/fw/model/sql/ValidateCourseRequeriment_SQL.php");
require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/mapping/TbUser.php");

class ValidateCourseRequirement
{
    private $vecCursos;
    private $fechaAprobacionLimite;
    private $objServiceQuery;
    private $objUser;

    public function ValidateCourseRequirement($pUser)
    {
        $this->objUser = $pUser;
        $this->fechaAprobacionLimite = date("Y-m-d");
        $this->objServiceQuery = new ValidateCourseRequeriment_SQL();
    }

    public function  &getAssignation()
    {
        return $this->vecCursos;
    }

    public function setAssignation($pAssignation)
    {
        $this->vecCursos = $pAssignation;
    }

    public function &getFechaAprobacionLimite()
    {
        return $this->fechaAprobacionLimite;
    }

    public function setFechaAprobacionLimite($pFechaAprobLim)
    {
        $this->fechaAprobacionLimite = $pFechaAprobLim;
    }

    private function convValor($valor)
    {
        switch ($valor) {
            case cOr:
                $token = "|";
                break;
            case cParAbierto:
                $token = "(";
                break;
            case cParCerrado:
                $token = ")";
                break;
            case cAnd:
                $token = "&";
                break;
        }
        return $token;
    }

    private function makePostFix($sPre, &$punt, &$post)
    {
        $pila = new pilaClass;
        $cont = 0;

        $strToken = sprintf("\0");

        while ($punt <= (strlen($sPre) + 1)) {
            $token = scanner($sPre, $strToken, $punt);
            switch ($token) {
                case cCr:
                case cNumero:
                    $post->insertaPila($strToken);
                    break;
                case cParAbierto:
                    $pila->insertaPila($token);
                    break;
                case cParCerrado:
                    while ($pila->mostrarValorPila() != cParAbierto) {
                        $post->insertaPila($this->convValor($pila->sacaPila()));
                    }
                    $pila->sacaPila();
                    break;
                case cAnd:
                case cOr:
                    while (($pila->muestraPosicion() > 0) && ($pila->mostrarValorPila() != cParAbierto) && ($pila->mostrarValorPila() >= $token)) {
                        $post->insertaPila($this->convValor($pila->sacaPila()));
                    }
                    $pila->insertaPila($token);
                    break;
            } // fin del case o switch
        } // fin del while

        while ($pila->muestraPosicion() > 0) {
            $post->insertaPila($this->convValor($pila->sacaPila()));
        }

        DestroyObject("pila"); // devuelve la memoria utilziada para la conversion de postfijo;
    }

    /**
     *** Modificó:    Edwin Saban
     *** Fecha:       16-09-2012
     *** Descripción: Se agregó el parametro $p_vector_requisitos_noaprobados para
     ***              almacenar los requisitos obligatorios no satisfechos
     ***/
    private function validate_OR(&$ptr, &$p_vector_requisitos_noaprobados)
    {
        $primerCod = $ptr->muestraAprobacion();
        $requisito_uno = $ptr->sacaPila();
        $segundoCod = $ptr->muestraAprobacion();
        $requisito_dos = $ptr->sacaPila();

        $resultado = $primerCod || $segundoCod;
        $ptr->insertaPila("");
        $ptr->modAprobado($resultado);

        /**
         *** Agregó:      Edwin Saban
         *** Fecha:       16-09-2012
         *** Descripción: validanción para determinar si se debe ingresar
         ***              en el vector $p_vector_requisitos_noaprobados -
         ***              los prerrequisitos evaluados como no satisfac--
         ***              torios.
         ***/
        if (!$resultado) {
            if (strcmp($requisito_uno, "") != 0) {
                array_push($p_vector_requisitos_noaprobados[cOr], $this->objServiceQuery->queryCourseInformation(1,$requisito_uno));
            }
            if (strcmp($requisito_dos, "") != 0) {
                array_push($p_vector_requisitos_noaprobados[cOr], $this->objServiceQuery->queryCourseInformation(1,$requisito_dos));
            }
        }
    }


    /**
     *** Modificó:    Edwin Saban
     *** Fecha:       16-09-2012
     *** Descripción: Se agregó el parametro $p_vector_requisitos_noaprobados para
     ***              almacenar los requisitos obligatorios no satisfechos
     ***/
    private function validate_AND(&$ptr, &$p_vector_requisitos_noaprobados)
    {
        $primerCod = $ptr->muestraAprobacion();
        $requisito_uno = $ptr->sacaPila();
        $segundoCod = $ptr->muestraAprobacion();
        $requisito_dos = $ptr->sacaPila();

        $resultado = $primerCod && $segundoCod;
        $ptr->insertaPila("");
        $ptr->modAprobado($resultado);

        /**
         *** Agregó:      Edwin Saban
         *** Fecha:       16-09-2012
         *** Descripción: validanción para determinar si se debe ingresar
         ***              en el vector $p_vector_requisitos_noaprobados -
         ***              los prerrequisitos evaluados como no satisfac--
         ***              torios.
         ***/
        if (!$resultado) {
            if ($primerCod == 0 AND strcmp($requisito_uno, "") != 0) {
                array_push($p_vector_requisitos_noaprobados[cAnd], $this->objServiceQuery->queryCourseInformation(1,$requisito_uno));
            }
            if ($segundoCod == 0 AND strcmp($requisito_dos, "") != 0) {
                array_push($p_vector_requisitos_noaprobados[cAnd], $this->objServiceQuery->queryCourseInformation(1,$requisito_dos));
            }
        }
    }

    /**
     *** Modificó:    Edwin Saban
     *** Fecha:       07-09-2012
     *** Descripción: Procedimiento que evalua si el prerrequisito del curso
     ***              se encuentra aprobado.
     *** Parametros:
     *** @param char $curso el prerrequisito que se esta evaluando
     *** @param char $fechaRev es la fecha en la que se esta evaluando el prerrequisito
     *** @param char $retUnic es el curso que se esta asignando como retrasada única
     *** @param integer $intCongelada bandera enviada por referencia para controlar
     ***        si el curso es un congelado
     *** @param integer $posicion es la posición del curso cuyo prerrequisito se es-
     ***        tá evaluando, en el vector que contiene todos los cursos de la asig-
     ***        nación.
     *** @return integer retorna un valor de 1 si el prerrequisto ya fue aprobado y
     ***         un 0 si el prerrequisito no ha sido aprobado.
     ***/
    private function validateApprovedRequirement($curso,&$result)
    {
        //printf("validateApprovedRequirement %s<br>",$curso);
        $result = $this->objServiceQuery->queryValidateCourseApproval($this->objUser->getId(),$this->objUser->getCareer(),1,$curso,$this->objUser->getCurriculum(),$this->fechaAprobacionLimite);
        return $result[0]['result'];
    }


    /**
     *** Modificó:    Edwin Saban
     *** Fecha:       07-09-2012
     *** Descripción: Procedimiento que evalua cada uno de los prerrequisitos del
     ***              curso evaluado. Los prerrequisitos son evaluados de acuerdo
     ***              a la precedencia de los operadores de la cadena de prerre-
     ***              quisitos con la que se define en la base de datos, en la ta-
     ***              bla 'tbcurriculum'
     *** Parametros:
     *** @param array $post es la pila donde se encuentra mapeada la cadena de --
     ***        prerrequistos del curso, en modo posix. Por ejemplo para la cade-
     ***        na de prerrequisitos del curso '0368' (Principios de Metrología);
     ***        (0732&0152&(0354|0348)), la pila tiene mapeada dicha cadena como
     ***        se muestra en la gráfica:
     ***
     ***                        |&   |
     ***                        |/   |
     ***                        |0348|
     ***                        |0354|
     ***                        |&   |
     ***                        |0152|
     ***                        |0732|
     ***
     *** @param char $fechaRev es la fecha en la que se esta evaluando el prerre-
     ***        quisito
     *** @param char $retUnic es el curso que se esta asignando como retrasada ú-
     ***        nica
     *** @param integer $intCongelada bandera enviada por referencia para contro-
     ***        lar si el curso es un congelado
     *** @param integer $posicion es la posición del curso cuyo prerrequisito se
     ***        está evaluando, en el vector que contiene todos los cursos de la
     ***        asignación.
     *** @return integer retorna un valor de 1 si el curso cumple con todos los
     ***         prerrequisitos segun la cadena de prerrequisitos del curso. Ca-
     ***         contrario un valor de 0 es retornado
     ***/
    private function parseCourseRequirement($post,&$problemas)
    {
        /**
         *** Agregó:      Edwin Saban
         *** Fecha:       16-09-2012
         *** Descripción: vector para el manejo de los prerrequisitos obligatorios
         ***              y opcionales que no se cumplen.
         ***/
        $vector_requisitos_noaprobados = array(cAnd => array(), cOr => array());

        $indice = 1;
        $punt = 0;

        $ptrEvalua = new pilaEvalua;

        for ($indice = 0; $indice <= $post->muestraPosicion(); $indice++) {
            $strToken = '';
            $valorToken = scanner($post->mostrarValor($indice), $strToken, $punt);
            $punt = 0;
            switch ($valorToken) {
                case cOr:
                    $this->validate_OR($ptrEvalua, $vector_requisitos_noaprobados);
                    break; // Se agrego el parametro $vector_requisitos_noaprobados, 16-09-2012
                case cAnd:
                    $this->validate_AND($ptrEvalua, $vector_requisitos_noaprobados);
                    break; // Se agrego el parametro $vector_requisitos_noaprobados, 16-09-2012
                case cNumero:
                    $ptrEvalua->insertaPila($strToken);
                    $ptrEvalua->modAprobado($this->validateApprovedRequirement($strToken,$result)); // Se agrego el parametro $problemas, 07-09-2012
                    break;
                case cCr://Implementar cuando hayan prerrequisitos de créditos
                    $ptrEvalua->insertaPila($strToken);
                    $ptrEvalua->modAprobado(1);
                    break;
            }
        }

        $valor = $ptrEvalua->muestraAprobacion();
        $requisito_evaluado = $ptrEvalua->sacaPila();

        if ($valor == FAIL) {
            if ($indice == 2) // el curso solo tiene un prerrequisito
            {
                array_push($vector_requisitos_noaprobados[cAnd], $this->objServiceQuery->queryCourseInformation(1,$requisito_evaluado));
            }
            array_push($problemas, array(CURSO_FALTA_PRERREQUISITO => $vector_requisitos_noaprobados));
        }

        unset($vector_requisitos_noaprobados);

        DestroyObject("ptrEvalua");
        return $valor;
    }

    private function checkRequirement($sPre,&$problema)
    {
        $post = new pilaClass;
        $aprobacion = 1;

        $Puntero = 0;

        if (strcmp(trim($sPre), '') != 0) { // ¿Tiene prerrequisitos?

            $this->makePostFix(trim($sPre), $Puntero, $post);
            //printf("checkRequirement %s <br>",$sPre);
           // print_r($post);
            $aprobacion = $this->parseCourseRequirement($post,$problema);
        }

        DestroyObject("post"); // devuelve la memoria utilziada para la conversion de postfijo;
        return $aprobacion;
    }

    public function validateRequirement()
    {
        /**
         *** Vector para almacenar los problemas de los cursos validados, antes de
         *** validar si posee una forma de asignación válida
         ***/
        $pos = 0;
        $return = OK;

        foreach($this->vecCursos as $curso_a_validarse) {
            $pos++;
            // Se obtiene todo el registro del curso
            if (strcmp($curso_a_validarse['course'], "") != 0) {
                $vector_problemas_curso_validado = array();
                $prerrequisitos = $curso_a_validarse['requirement'];

                $resultado = $this->checkRequirement($prerrequisitos,$vector_problemas_curso_validado);
                //printf("u=%s, ca=%s, cu=%s, pr=%s => res=%d <br>", $this->objUser->getId(), $this->objUser->getCareer(),$curso_a_validarse['course'],$prerrequisitos,$resultado);

                /**
                 *** Se mapea el vector de problemas para que las claves sean los códigos
                 *** de los problemas
                 ***/
                while ($problema_curso = current($vector_problemas_curso_validado)) {
                    list($clave, $valor) = each($problema_curso);
                    $this->vecCursos[$pos]['remark'][$clave] = $valor;
                    next($vector_problemas_curso_validado);
                }

                switch (intval($resultado, 10)) {
                    case FAIL:/***  CON FALTA DE PRERREQUISTO ***/
                        /**
                         *** Se le agrega como primer valor el problema de falta de prerrequisito
                         ***/
                        $return = FAIL;

                        if (!array_key_exists(CURSO_FALTA_PRERREQUISITO, $this->vecCursos[$pos]['remark'])) {
                            //printf("faltaprerequisito!<br>");
                            $this->vecCursos[$pos]['remark'][CURSO_FALTA_PRERREQUISITO] = array(cAnd => array(), cOr => array());
                        }
                        break;
                }

                unset($vector_problemas_curso_validado);
            } // del if donde verifica que curso no sea blanco
        }

        /**
         *** Inicia la validación de los problemas de asignación de los cursos
         **
        for($ptr_f2=0;$ptr_f2<count($array_cursos_problemas);$ptr_f2++){
            // **
            // *** Se valida que el curso actual del $array_cursos_problemas, corresponda
            // *** al mismo curso apuntado por el puntero al que apunta 'index' en los --
            // *** cursos de la asignación
            // ***
            if(strcmp($array_cursos_problemas[$ptr_f2]['course'],$this->vecCursos[$array_cursos_problemas[$ptr_f2]['indice']]['course'])==0 AND
                ($array_cursos_problemas[$ptr_f2]['index']==$this->vecCursos[$array_cursos_problemas[$ptr_f2]['indice']]['cindex'])){
                if(count($array_cursos_problemas[$ptr_f2]['remarks'])>0 AND // Valida si el curso tiene marcas de problemas
                    array_key_exists(CURSO_FALTA_PRERREQUISITO,$array_cursos_problemas[$ptr_f2]['remarks'])){ // Verifica si tiene la maraca de falta de prerrequisito
                    $this->vecCursos[$array_cursos_problemas[$ptr_f2]['indice']]['remark'] = $array_cursos_problemas[$ptr_f2]['remarks'];
                    $return = FAIL;
                } else {
                    $this->vecCursos[$array_cursos_problemas[$ptr_f2]['indice']]['remark'] = array();
                }
            }
        }
         */

        return $return;
    }
}

?> 