<?php

include_once("../../path.inc.php");
require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/sql/D_CourseListManager_SQL.php");


class D_CourseListManager
{
    /*
     * Variable para utilizar las consultas
     */
    var $gsql;

    /*
     * Creando Constructor para inicializar la variable $gsql;
     */
    public function D_CourseListManager()
    {
        /*
        * Instanciando la variable en la clase donde se encuentran las consultas
        */

        $this->gsql = new D_CourseListManager_SQL();
    }


    function DarListadoDeCursos($periodo, $anio, $pIdDocente)
    {
        $query_curso = $this->gsql->DarListadoDeCursos_select1($periodo, $anio,$pIdDocente);
        $_SESSION["sConexion"]->query($query_curso);

        $numero_filas = $_SESSION["sConexion"]->num_rows();

        return $numero_filas;
    } //  fin de la funcion DarListadoDeCursos

    function AsignadosDelCurso($base, $anio, $periodo, $curso, $seccion,$index)
    {
        $query_asignados = $this->gsql->AsignadosDelCurso_select1($anio, $periodo, $curso, $seccion,$index);

        $base->query($query_asignados);
        $base->next_record();

        $asignados = $base->f('numero');

        return $asignados;

    } // fin function AsignadosDelCurso()

} // final de la definición de la clase Horario


function NombreDelDia($dia)
{
    switch ($dia) {
        case 1:
            $nombre_dia = "Lunes";
            break;
        case 2:
            $nombre_dia = "Martes";
            break;
        case 3:
            $nombre_dia = "Miercoles";
            break;
        case 4:
            $nombre_dia = "Jueves";
            break;
        case 5:
            $nombre_dia = "Viernes";
            break;
        case 6:
            $nombre_dia = "Sabado";
            break;
        case 7:
            $nombre_dia = "Domingo";
            break;
    }
    return $nombre_dia;
}

function NombreDelMes($mes)
{
    switch ($mes) {
        case 1:
            $nombre_mes = "enero";
            break;
        case 2:
            $nombre_mes = "febrero";
            break;
        case 3:
            $nombre_mes = "marzo";
            break;
        case 4:
            $nombre_mes = "abril";
            break;
        case 5:
            $nombre_mes = "mayo";
            break;
        case 6:
            $nombre_mes = "junio";
            break;
        case 7:
            $nombre_mes = "julio";
            break;
        case 8:
            $nombre_mes = "agosto";
            break;
        case 9:
            $nombre_mes = "septiembre";
            break;
        case 10:
            $nombre_mes = "octubre";
            break;
        case 11:
            $nombre_mes = "noviembre";
            break;
        case 12:
            $nombre_mes = "diciembre";
            break;
    }
    return $nombre_mes;
}


?>
