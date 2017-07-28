<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 3/10/14
 * Time: 01:54 PM
 */
require_once("$dir_portal/fw/model/sql/D_ManualLoadNotes_SQL.php");
require_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");

/*
 * Incluyendo archivo con sentencias SQL
 */


class D_ManualLoadNotes extends D_CourseNotesManager
{
    // Atributos de la clase
    var $mPaginas;
    var $mRegistros;
    var $mBloqueActual;
    var $mIndice;
    var $mTope;

    /*
     * Variable para utilizar las consultas
     */
    var $gsql_IM;


    // *************************
    // Constructor de la clase
    // *************************
    function D_ManualLoadNotes()
    {
        $this->mPaginas = 0;
        $this->mRegistros = 0;
        $this->mBloqueActual = 1;
        $this->mIndice = 1;
        $this->mTope = 0;

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql_IM = new D_ManualLoadNotes_SQL();
    } // fin del constructor


    // ************************************************************
    // Calcula el numero de paginas a mostrar para la opcion
    // manual y el numero de registros por pagina
    // ************************************************************
    function PaginasYregistros()
    {
        if ($this->mAsignados > 0)
            switch ($this->mAsignados) {
                case ($this->mAsignados < 16):
                    $this->mRegistros = $this->mAsignados;
                    $this->mPaginas = 1;
                    break;


                case ($this->mAsignados < 301):
                    $this->mRegistros = 15;
                    $this->mPaginas = ceil($this->mAsignados / 15);
                    break;

                case ($this->mAsignados < 401):
                    $this->mRegistros = 20;
                    $this->mPaginas = ceil($this->mAsignados / 20);
                    break;

                case ($this->mAsignados < 501):
                    $this->mRegistros = 25;
                    $this->mPaginas = ceil($this->mAsignados / 25);
                    break;

                case ($this->mAsignados < 601):
                    $this->mRegistros = 30;
                    $this->mPaginas = ceil($this->mAsignados / 30);
                    break;

                case ($this->mAsignados < 701):
                    $this->mRegistros = 35;
                    $this->mPaginas = ceil($this->mAsignados / 35);
                    break;

                case ($this->mAsignados < 801):
                    $this->mRegistros = 40;
                    $this->mPaginas = ceil($this->mAsignados / 40);
                    break;

                default:
                    $this->mPaginas = 20;
                    $this->mRegistros = ceil($this->mAsignados / 20);
            } // fin del  switch($this->mAsignados)
        $this->mTope = $this->mRegistros;
    } // fin de la función PaginasYregistros

    // ********************************************
    // ListadoEstudiantes
    // Devuelve el listado de estudiantes del curso
    // ********************************************
    function ListadoEstudiantes()
    {
        $query_listado = $this->gsql_IM->ListadoEstudiantes_select1($this->mCurso,
           $this->mCarrera /*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex);

        $_SESSION["sNotas"]->query($query_listado);

        for ($i = 1; $i <= $this->mAsignados; $i++) {
            $_SESSION["sNotas"]->next_record();
            $_SESSION["Nombre"][$i - 1][name] = $_SESSION["sNotas"]->f('name');
            $_SESSION["Apellido"][$i - 1][surname] = $_SESSION["sNotas"]->f('surname');
        } // fin del for $i

        $query_datos = $this->gsql_IM->ListadoEstudiantes_select2($this->mCurso,
            $this->mCarrera /*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex
        );

        $_SESSION["sNotas"]->query($query_datos);

        $vector_acta = pg_copy_to($_SESSION["sNotas"]->Link_ID, "webingtemporal", "|");

        $query_datos = sprintf("drop table webingtemporal;");

        $query_datos = $this->gsql_IM->ListadoEstudiantes_drop1();


        $_SESSION["sNotas"]->query($query_datos);

        for ($i = 1; $i <= $this->mAsignados; $i++) {
            $_SESSION["Acta"][$i - 1] = explode("|", $vector_acta[$i - 1]);
        }

    } // fin de la función ListadoEstudiantes


    // ********************************************
    // Inserta el listado de estudiantes
    // a la tabla ingresotemporal
    // ********************************************
    function GrabaraIngresoTemporal()
    {
        for ($i = 1; $i <= $this->mAsignados; $i++) {
            $vector_acta[$i - 1] = implode("|", $_SESSION["Acta"][$i - 1]);
//	  echo "<br>".$vector_acta[$i-1];
        }

        $query_limpiar = $this->gsql_IM->GrabaraIngresoTemporal_delete1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex
        );


        $_SESSION["sNotas"]->query($query_limpiar);

        pg_copy_from($_SESSION["sNotas"]->Link_ID, "tbtempentry",
            $vector_acta, "|");

    } // fin de GrabaraIngresoTemporal


    // ********************************************
    // Inserta el listado de estudiantes
    // a la tabla ingresotemporal
    // ********************************************
    function InsertarTemporal()
    {

        $vector[0] = '008816316,009212702,09,0152,A,01,2007,30,45,25,0,0';
        $vector[1] = '008816316,009612498,09,0152,A,01,2007,30,45,25,0,0';


        /*   print_r($_SESSION["sNotas"]);
            echo "  hasta aqui sesion <br>";
            print_r(get_declared_classes());
            echo " hasta aqui clases <br>"; */


//    $vector = pg_copy_to($_SESSION["sNotas"]->Link_ID,"ingresotemporal");

        /*    echo "<br>";
            echo $vector[0];
            echo "<br>";
            echo $vector[1]; die;       */

        pg_copy_from($_SESSION["sNotas"]->Link_ID, "ingresotemporal", $vector, "|");

    } // fin de la funcion InsertarRegistro

    // ********************************************
    // Inserta el listado de estudiantes
    // a la tabla ingresotemporal
    // ********************************************
    function LlenarIngresoTemporal($mUsuario)
    {
        $query_llenar = $this->gsql_IM->LlenarIngresoTemporal_select1($mUsuario,
            $this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex);

        $_SESSION["sNotas"]->query($query_llenar);

// echo $query_llenar."<br>";  die;
    } // fin de LlenarIngresoTemporal

    // ********************************************
    // Inserta el listado de estudiantes
    // a la tabla ingresotemporal
    // ********************************************
    function GuardarConsistencia($mUsuario)
    {
        $query_consistencia = $this->gsql_IM->GuardarConsistencia_select1($mUsuario,
            $this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex);

        $_SESSION["sNotas"]->query($query_consistencia);

        $_SESSION["sNotas"]->next_record();

        $_SESSION["consistencia"] = $this->mCurso . "-" . $this->mCarrera /*$this->mSeccion*/ . "-" . $_SESSION["sNotas"]->f('f_saveconsistency');


        //echo "viene aqui <br>";
        //echo $query_consistencia."<br>"; die;
    } // fin de LlenarIngresoTemporal

    // ********************************************
    // Genera el vector de los laboratorios
    // finales
    // ********************************************
    function GenerarLaboratorioFinal()
    {

        $query_datos = $this->gsql_IM->GenerarLaboratorioFinal_select1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex
        );

//echo $query_datos; die;

        $_SESSION["sNotas"]->query($query_datos);

        $_SESSION["labfinal"] = array();


        $notas_lab = pg_copy_to($_SESSION["sNotas"]->Link_ID, "web_labfinal", "|");


        for ($i = 0; $i < $this->mAsignados; $i++) {
            list($_SESSION["labfinal"]["carnet"][$i], $_SESSION["labfinal"]["curso"][$i],, $_SESSION["labfinal"]["index"][$i],
                $_SESSION["labfinal"]["seccion"][$i],$_SESSION["labfinal"]["periodo"][$i],
                $_SESSION["labfinal"]["anio"][$i], $_SESSION["labfinal"]["nota"][$i]) =
                explode("|", $notas_lab[$i]);

            $_SESSION["labfinal"]["carnet"][$i] = trim($_SESSION["labfinal"]["carnet"][$i]);
            $_SESSION["labfinal"]["curso"][$i] = trim($_SESSION["labfinal"]["curso"][$i]);
            $_SESSION["labfinal"]["index"][$i] = trim($_SESSION["labfinal"]["index"][$i]);
            $_SESSION["labfinal"]["seccion"][$i] = trim($_SESSION["labfinal"]["seccion"][$i]);
            $_SESSION["labfinal"]["periodo"][$i] = trim($_SESSION["labfinal"]["periodo"][$i]);
            $_SESSION["labfinal"]["anio"][$i] = trim($_SESSION["labfinal"]["anio"][$i]);
            $_SESSION["labfinal"]["nota"][$i] = trim($_SESSION["labfinal"]["nota"][$i]);
        }

        $_SESSION["sNotas"]->query($this->gsql_IM->GenerarLaboratorioFinal_drop1());


    } // fin de GenerarLaboratorioFinal()

// ********************************************
// Devuelve el laboratorio final que se
// pide por medio del carnet
// ********************************************
    function DarLaboratorioFinal($carnet, $escuela, $zona)
    {

        $posicion = array_search($carnet, $_SESSION["labfinal"]["carnet"]);

        if (strcmp($_SESSION["labfinal"]["curso"][$posicion].'', $_SESSION["sActaManual"]->mCurso.'') == 0 &&
            strcmp($_SESSION["labfinal"]["index"][$posicion].'', $_SESSION["sActaManual"]->mIndex.'') == 0 &&
            strcmp($_SESSION["labfinal"]["seccion"][$posicion].'', $_SESSION["sActaManual"]->mSeccion.'') == 0 &&
            strcmp($_SESSION["labfinal"]["periodo"][$posicion].'', $_SESSION["sActaManual"]->mPeriodo.'') == 0 &&
            strcmp($_SESSION["labfinal"]["anio"][$posicion].'', $_SESSION["sActaManual"]->mAnio.'') == 0
        ) {

            if ($zona < 31 && ($escuela == 1 || $escuela == 5))
                return 0 * 1;
            return $_SESSION["labfinal"]["nota"][$posicion] * 1;
        } else   return 0 * 1;

    } // fin de DarLaboratorioFinal

    // ********************************************
    // Inserta el listado de estudiantes a la tabla
    // ingresotemporal para cursos que no llevan notas
    // ********************************************
    function LlenarIngresoTemporal_2($mUsuario)
    {
        $query_llenar = $this->gsql_IM->LlenarIngresoTemporal_2_select1($mUsuario,
            $this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio, $this->mIndex);

        $_SESSION["sNotas"]->query($query_llenar);

// echo $query_llenar."<br>";  die;
    } // fin de LlenarIngresoTemporal

} // Fin de la Clase ingresoManual

?>
