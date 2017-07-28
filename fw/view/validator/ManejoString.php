<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 7/08/14
 * Time: 02:05 PM
 */

//**************************************************************
//        Libreria ManejoString
// Clase para el manejo strings de variables a insertar en la BD
//**************************************************************
include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");

class ManejoString
{
    //************************************************
    //Si encuentra comillas en un string le antepone \
    // ***********************************************
    function cambiaString($dato)
    {
        $d1 = 0;
        while ($d1 < strlen($dato)) {
            if ($dato[$d1] == chr(34) || $dato[$d1] == chr(39)) {
                $dato0 = $dato0 . chr(92) . $dato[$d1];
            } else {
                $dato0 = $dato0 . $dato[$d1];
            }
            $d1++;
        }

        return $dato0;
    }

    // *************************
    //Si encuentra \ se la quita
    // *************************
    function quitaDiagonal($dato)
    {
        $d1 = 0;
        while ($d1 < strlen($dato)) {
            if ($dato[$d1] != chr(92)) {
                $dato0 = $dato0 . $dato[$d1];
            }
            $d1++;
        }

        return $dato0;
    }

    //*********************************
    //Si encuentra "" las cambia por ''
    //*********************************
    function cambiaComilla($dato)
    {
        $d1 = 0;
        while ($d1 < strlen($dato)) {
            if ($dato[$d1] == chr(34)) {
                $dato0 = $dato0 . chr(39);
            } else {
                $dato0 = $dato0 . $dato[$d1];
            }
            $d1++;
        }

        return $dato0;
    }

    //*******************************************************************
    //Esta función devuelve el nombre de la carrera, según el código de
    //carrera enviado por parámetro.
    //*******************************************************************
    function StringCarrera($param_carrera)
    {
        $carrera = '';
        switch ($param_carrera) {
            case '02':
                $carrera = "02 Medicina Veterinaria";
                break;
            case '03':
                $carrera = "03 Zootecnia";
                break;
        }

        return $carrera;
    }

    //*******************************************************************
    //Esta función devuelve la descripción del Puesto de los Catedráticos
    //que van a ingresar al portal.
    //*******************************************************************
    function StringPuesto($param_puesto)
    {
        $despue = '';
        switch ($param_puesto) {
            case '210110':
                $despue = "Titular I";
                break;
            case '210111':
                $despue = "Titular I";
                break;
            case '210121':
                $despue = "Titular II";
                break;
            case '210131':
                $despue = "Titular III";
                break;
            case '210141':
                $despue = "Titular IV";
                break;
            case '210151':
                $despue = "Titular V";
                break;
            case '210161':
                $despue = "Titular VI";
                break;
            case '210165':
                $despue = "Titular VII";
                break;
            case '210170':
                $despue = "Titular VIII";
                break;
            case '210175':
                $despue = "Titular IX";
                break;
            case '210180':
                $despue = "Titular X";
                break;
            case '210220':
                $despue = "Profesor Interino";
                break;
            case '210310':
                $despue = "Ayudante de C&aacute;tedra I";
                break;
            case '210320':
                $despue = "Ayudante de C&aacute;tedra II";
                break;
            case '999999':
                $despue = "Digitador Curso de Vacaciones";
                break;
        }

        return $despue;
    }


    //*******************************************************************
    //Esta función devuelve el nombre del problema, que pueden presentar
    //algunos cursos en la tabla cursoaprobado.
    //*******************************************************************
    function DescribeProblema($param_problema)
    {
        $desc_problema = '';
        switch ($param_problema) {
            case 1  :
                $desc_problema = "Falta Prerrequisito";
                break;
            case 2  :
                $desc_problema = "Curso no pertenece a la carrera";
                break;
            case 25 :
                $desc_problema = "Cambio en cadena de Pensa de Estudios";
                break;
            case 30 :
                $desc_problema = "Sin zona m&iacute;nima de congelamiento";
                break;
            case 98 :
                $desc_problema = "Traslape de Horario";
                break;
            case 99 :
                $desc_problema = "Asignado en Carrera diferente a la Carrera Actual";
                break;
        }

        return $desc_problema;
    }

    //*******************************************************************
    //Esta función devuelve la descripción de la Forma como fue Aprobado
    //algun curso de formaprobacion de cursoaprobado.
    //*******************************************************************
    function FormaAprobacion($param_formapro)
    {
        $desc_formapro = '';
        switch ($param_formapro) {
            case 100  :
                $desc_formapro = "";
                break;
            case 200  :
                $desc_formapro = "SUFICIENCIA";
                break;
            case 300  :
                $desc_formapro = "EQUIVALENCIA";
                break;
            case 400  :
                $desc_formapro = "JUNTA DIRECTIVA";
                break;
        }

        return $desc_formapro;
    }

    //*********************************************************
    //Esta función devuelve la descripción del período en curso
    //*********************************************************
    function funTextoPeriodo($periodoH)
    {
        $periodoH = $periodoH + 0;
        switch ($periodoH) {
            case PRIMER_SEMESTRE :
                $texto = 'PRIMER SEMESTRE ';
                break;
            case VACACIONES_DEL_PRIMER_SEMESTRE :
                $texto = 'ESCUELA DE VACACIONES DE JUNIO';
                break;
            case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE :
                $texto = 'PRIMERA RETRASADA PRIMER SEMESTRE';
                break;
            case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
                $texto = 'SEGUNDA RETRASADA PRIMER SEMESTRE';
                break;
            case SUFICIENCIAS_DEL_PRIMER_SEMESTRE :
                $texto = 'SUFICIENCIAS PRIMER SEMESTRE';
                break;
            case SEGUNDO_SEMESTRE :
                $texto = 'SEGUNDO SEMESTRE';
                break;
            case VACACIONES_DEL_SEGUNDO_SEMESTRE :
                $texto = 'ESCUELA DE VACACIONES DE DICIEMBRE';
                break;
            case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE :
                $texto = 'PRIMERA RETRASADA SEGUNDO SEMESTRE';
                break;
            case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE :
                $texto = 'SEGUNDA RETRASADA SEGUNDO SEMESTRE';
                break;
            case SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE :
                $texto = 'SUFICIENCIAS DEL SEGUNDO SEMESTRE';
                break;
            case EQUIVALENCIA :
                $texto = 'EQUIVALENCIAS';
                break;
            case SIN_PERIODO :
                $texto = 'SIN PERIODO';
                break;
        }

        return $texto;
    }

    //*******************************************************************
    // EstadoActa
    // Esta función devuelve la descripción de los Estado en los cuales se
    // puede encontrar el acta de un curso en especial
    //*******************************************************************
    function EstadoActa($param_estado)
    {
        $desc_estado = '';
        switch ($param_estado) {
            case 2  :
            case 3  :
            case 4  :
                $desc_estado = "Curso Habilitado para Ingreso";
                break;
            case 5  :
                $desc_estado = "Curso Aprobado";
                break;
            case 6  :
                $desc_estado = "Curso Aprobado en espera";
                break;
            default :
                $desc_estado = "Curso Aprobado";
                break;

        }

        return $desc_estado;
    }

    //*******************************************************************
    // DescribeLabortorio
    // Esta función devuelve la descripción para un Curso si Incluye Laboratorio
    // lo que implica que el catedrático deberá definir el valor de la nota para
    // el laboratorio de dicho curso.
    //*******************************************************************
    function DescribeLaboratorio($param_laboratorio)
    {
        $desc_laboratorio = '';
        switch ($param_laboratorio) {
            case 0  :
                $desc_laboratorio = "Curso Sin Laboratorio";
                break;
            case 2  :
                $desc_laboratorio = "Curso que Incluye Laboratorio";
                break;
            case 6  :
                $desc_laboratorio = "Curso que Incluye Practica tipo laboratorio";
                break;
        }

        return $desc_laboratorio;
    }


    // ***********************************************************************
    //  MesEnLetras
    // Esta función retorna el mes actual en letras
    // ***********************************************************************
    function MesEnLetras()
    {
        $mes = date(m);
        switch ($mes) {
            case '01' :
                $txtmes = 'Enero';
                break;
            case '02' :
                $txtmes = 'Febrero';
                break;
            case '03' :
                $txtmes = 'Marzo';
                break;
            case '04' :
                $txtmes = 'Abril';
                break;
            case '05' :
                $txtmes = 'Mayo';
                break;
            case '06' :
                $txtmes = 'Junio';
                break;
            case '07' :
                $txtmes = 'Julio';
                break;
            case '08' :
                $txtmes = 'Agosto';
                break;
            case '09' :
                $txtmes = 'Septiembre';
                break;
            case '10' :
                $txtmes = 'Octubre';
                break;
            case '11' :
                $txtmes = 'Noviembre';
                break;
            case '12' :
                $txtmes = 'Diciembre';
                break;
            default   :
                $txtmes = 'Mes';
        }

        return $txtmes;
    }

// **********************************************
//  funcion que recibe una fecha en formato
//  YYYY-MM-DDy la convierte en formato DD-MES-YYYY
// **********************************************
    function FechaaDIAMESANIO($fechaentrada)
    {
        $anio = substr($fechaentrada, 0, 4);
        $mes = substr($fechaentrada, 5, 2);
        $dia = substr($fechaentrada, 8, 2);

        switch ($mes) {
            case '01':
                $mes = 'ENE';
                break;
            case '02':
                $mes = 'FEB';
                break;
            case '03':
                $mes = 'MAR';
                break;
            case '04':
                $mes = 'ABR';
                break;
            case '05':
                $mes = 'MAY';
                break;
            case '06':
                $mes = 'JUN';
                break;
            case '07':
                $mes = 'JUL';
                break;
            case '08':
                $mes = 'AGO';
                break;
            case '09':
                $mes = 'SEP';
                break;
            case '10':
                $mes = 'OCT';
                break;
            case '11':
                $mes = 'NOV';
                break;
            case '12':
                $mes = 'DIC';
                break;
        }
        $fechasalida = $dia . "-" . $mes . "-" . $anio;

        return $fechasalida;
    }

}

?>