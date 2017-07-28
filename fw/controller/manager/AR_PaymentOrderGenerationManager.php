<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 03/05/2015
 * Time: 10:03 AM
 */

include("../../path.inc.php");
require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/DB_Connection.php");
require_once("$dir_portal/fw/model/sql/AR_PaymentOrderGenerationManager_SQL.php");

//require_once("$dir_biblio/const.ValidaTraslapes.inc.php");
//include_once("class.transaccion.inc.php");


class AR_PaymentOrderGenerationManager
{
    var $mUsuario;
    var $mCarrera;
    var $mTransaccion;
    var $mFechaIns;
    var $mPeriodo;
    var $mAnio;
    var $vectorCursoSeccion;
    var $mNumeroHoras;
    var $codCursoVer; //
    var $SeccionVer;  //
    var $labVac;
    var $existen;
    var $nombre;
    var $mCarnet;
    var $mPerAnterior;
    var $mNumCursos;
    var $codError;
    var $extension;

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;


    /* Constructor */
    public function AR_PaymentOrderGenerationManager($mUsuario, $mCarrera)
    {
        $_SESSION['mUsuario'] = $this->mCarnet = $mUsuario;
        $_SESSION['mCarrera'] = $this->mCarrera = $mCarrera;
        $this->codError = 0;

        $_SESSION["sConRet"] = NEW DB_Connection();
        $_SESSION["sConRet"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new AR_PaymentOrderGenerationManager_SQL();
    }

    function CargarDatosAcademicos()
    {
        $fechaAsigna = strftime("%Y-%m-%d");

        $qrydatos = $this->gsql->CargarDatosAcademicos_select1($fechaAsigna, ASIGNACION_RETRASADAS);

        if ($_SESSION["sConRet"]->query($qrydatos) and ($_SESSION["sConRet"]->num_rows() > 0)) {
            $_SESSION["sConRet"]->next_record();
            if((int)$_SESSION["sConRet"]->f("r_result")) {
                $_SESSION["periodoproceso"] = $this->mPeriodo = $_SESSION["sConRet"]->f("r_schoolyear");
                $_SESSION["anioproceso"] = $this->mAnio = $_SESSION["sConRet"]->f("r_year");
                $periodo = 0 + $this->mPeriodo;

                switch ($periodo) {
                    case 102:
                    case 103:
                        $this->mPerAnterior = PRIMER_SEMESTRE;
                    $_SESSION["periodoanterior"] = $this->mPerAnterior;
                        break;
                    case 202:
                    case 203:
                        $this->mPerAnterior = SEGUNDO_SEMESTRE;
                    $_SESSION["periodoanterior"] = $this->mPerAnterior;
                        break;
                }
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }

    function obtieneDatosCursosAsignarRetrasada()
    {
        if (strcmp($this->mPerAnterior, SEGUNDO_SEMESTRE) == 0) {
            $anioINFO = DATE(Y) - 1;
        } else {
            $anioINFO = DATE(Y);
        }

        $query_retrasada = $this->gsql->obtieneDatosCursosAsignarRetrasada_select1($this->mCarnet, $this->mAnio, $this->mPerAnterior, $this->mCarrera);
        $i = 1;
        if ($_SESSION["sConRet"]->query($query_retrasada)) {
            if ($_SESSION["sConRet"]->num_rows() > 0) {
                $_SESSION["numCarreras"] = 0;
                $_SESSION["n_cursos"] = $_SESSION["sConRet"]->num_rows();

                for ($cont = 0; $cont < $_SESSION["sConRet"]->num_rows(); $cont++) {
                    $_SESSION["sConRet"]->next_record();
                    $marca = 1;
                    if ($marca == 1) {
                        $_SESSION["cursosAsig"][$i]['curso'] = trim($_SESSION["sConRet"]->f("idcourse"));
                        $_SESSION["cursosAsig"][$i]['mNomCurso'] = trim($_SESSION["sConRet"]->f("nomcurso"));
                        $_SESSION["cursosAsig"][$i]['mZonaCurso'] = trim($_SESSION["sConRet"]->f("classzone"));
                        $_SESSION["cursosAsig"][$i]['mZonaLab'] = trim($_SESSION["sConRet"]->f("labnote"));
                        $_SESSION["cursosAsig"][$i]['mExamenFinal'] = 0;
                        $_SESSION["cursosAsig"][$i]['mCodProblema'] = trim($_SESSION["sConRet"]->f("problemdetail"));
                        $_SESSION["cursosAsig"][$i]['seccion'] = trim($_SESSION["sConRet"]->f("section"));
                        $_SESSION["cursosAsig"][$i]['mCarreraCurso'] = trim($_SESSION["sConRet"]->f("idcareer"));
                        $_SESSION["cursosAsig"][$i]['mEstadoAsignado'] = 0 + trim($_SESSION["sConRet"]->f("idactstate"));// Estado del acta
                        $_SESSION["cursosAsig"][$i]['mNomCarrera'] = trim($_SESSION["sConRet"]->f("nomcarr"));
                        $_SESSION["cursosAsig"][$i]['mEstadoAsignar'] = 0;
                        $_SESSION["cursosAsig"][$i]['mEstado'] = 0;
                        $_SESSION["cursosAsig"][$i]['carrera'] = trim($_SESSION["sConRet"]->f("idcareer"));
                        $_SESSION["cursosAsig"][$i]['grupolab'] = trim($_SESSION["sConRet"]->f("labgroup"));
                        $_SESSION["cursosAsig"][$i]['inscripcion'] = trim($_SESSION["sConRet"]->f("enrollmentdate"));
                        $_SESSION["cursosAsig"][$i]['index'] = trim($_SESSION["sConRet"]->f("index"));
                        $i++;
                    }
                } // for
                $_SESSION["numCursos"] = $this->mNumCursos = $i - 1;
                return 1;
            } // if numero carga mayor 0
        } // if verifica respuesta de ejecucion query
    } // fin procedimiento obtieneDatosCursosAsignarRetrasada

    /**
     * Modificado: 15/11/2012, Edwin Saban
     * Se realizaron las adecuaciones necesarias para hacer el mapeo de marcas
     * de asignacion a las correpondientes, si el curso cuando se esta asignando
     * no posee nota en el sistema.
     */

    function cargainfoDatosPreAsignados($pos, $curso, $marca, $marcaAsig)
    {
        $query_retrasada = $this->gsql->cargainfoDatosPreAsignados_select1($this->mCarnet, $this->mAnio, $this->mPerAnterior, $curso, $this->mCarrera);

        if ($_SESSION["sConRet"]->query($query_retrasada)) {
            $_SESSION["sConRet"]->next_record();
            $_SESSION["cursosAsig"][$pos]['curso'] = trim($_SESSION["sConRet"]->f("idcourse"));
            $_SESSION["cursosAsig"][$pos]['mNomCurso'] = trim($_SESSION["sConRet"]->f("nomcurso"));
            $_SESSION["cursosAsig"][$pos]['mZonaCurso'] = trim($_SESSION["sConRet"]->f("classzone"));
            $_SESSION["cursosAsig"][$pos]['mZonaLab'] = trim($_SESSION["sConRet"]->f("labnote"));
            $_SESSION["cursosAsig"][$pos]['mExamenFinal'] = 0;
            $_SESSION["cursosAsig"][$pos]['mCodProblema'] = trim($_SESSION["sConRet"]->f("problemdetail"));

            $_SESSION["cursosAsig"][$pos]['seccion'] = trim($_SESSION["sConRet"]->f("section"));
            $_SESSION["cursosAsig"][$pos]['mCarreraCurso'] = trim($_SESSION["sConRet"]->f("idcareer"));
            $_SESSION["cursosAsig"][$pos]['mEstadoAsignado'] = 0 + trim($_SESSION["sConRet"]->f("idactstate"));
            $_SESSION["cursosAsig"][$pos]['mNomCarrera'] = trim($_SESSION["sConRet"]->f("nomcarr"));
            $_SESSION["cursosAsig"][$pos]['mEstadoAsignar'] = $marcaAsig;
            $_SESSION["cursosAsig"][$pos]['grupolab'] = trim($_SESSION["sConRet"]->f("labgroup"));
            $_SESSION["cursosAsig"][$pos]['inscripcion'] = trim($_SESSION["sConRet"]->f("enrollmentdate"));
            $_SESSION["cursosAsig"][$pos]['index'] = trim($_SESSION["sConRet"]->f("index"));

            if ($marca) {
                $_SESSION["cursosAsig"][$pos]['mEstado'] = 1;
            } else {
                $_SESSION["cursosAsig"][$pos]['mEstado'] = 0;
            }
        } // if verifica respuesta de ejecucion query
        return 1;
    }


    // funcion donde se coloca un digito verificdor de 1 a los cuersos
    // que si pueden asignarse como retrasada, simple y sencillo porque
    // no aparece como curso asignado
    function verCurAprobados()
    {
        for ($j = 1; $j <= $this->mNumCursos; $j++) {
            $query_retrasada = $this->gsql->verCurAprobados_select1(trim($this->mCarnet), trim($_SESSION["cursosAsig"][$j]['curso']), trim($_SESSION["cursosAsig"][$j]['mCarreraCurso']));

            if (($_SESSION["sConRet"]->query($query_retrasada)) AND ($_SESSION["sConRet"]->num_rows() >= 1)) {
                $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 3;
            } // curso aprobado, se puede asignar en restrasada
            // fin de compacion de ejecucion del query
        } // fin del for de numero de cursos
    } // fin funcion verCursosAprobados

    function verCurAsignados()
    {
        if ((strcmp($this->mPeriodo, SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE) == 0)) {
            $anio = DATE(Y) - 1;
        } else {
            $anio = DATE(Y);
        }
        for ($j = 1; $j <= $this->mNumCursos; $j++) {
            $query_retrasada = $this->gsql->verCurAsignados_select1($this->mCarnet, $_SESSION["cursosAsig"][$j]['curso'], $_SESSION["cursosAsig"][$j]['mCarreraCurso'], $this->mPeriodo, $anio);

            if (($_SESSION["sConRet"]->query($query_retrasada)) AND ($_SESSION["sConRet"]->num_rows() > 0)) {
                $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 1;
                $_SESSION["cursosAsig"][$j]['mEstado'] = 1;
                $_SESSION["cursosAsig"][$j]['mEstadoGenera'] = 1;
            } // curso asignado anteriormente, se puede asignar en restrasada
            // fin de comparacion de ejecucion del query
        } // fin del for de numero de cursos
    }

    // funcion por medio de la cual se logra definir si un curso puede
    // ser asignado o no el curso en retrasada, segun si se llego a zona minima o bien sea no
    // sido ingresada el acta del curso en semestre.
    function verZonasCursos()
    {

        for ($j = 1; $j <= $this->mNumCursos; $j++) {

            // Se excluyen los cursos marcados como:
            // 'mEstadoAsignar' = 1 (Curso ya asignado como retrasada en el periodo actual)
            // 'mEstadoAsignar' = 3 (Curso ya cargado como curso aprobado)
            // 'mEstadoAsignar' = 5 (Congelados para los cuales aún no se ha aprobado la retrasada única)

            // Ingresa cuando el curso no tienen ninguna de las marcas anteriores y las notas ya fueron aprobadas por el caterdratico
            if ($_SESSION["cursosAsig"][$j]['mEstadoAsignar'] == 0 AND $_SESSION["cursosAsig"][$j]['mEstadoAsignado']>=3) {

                $zonaMinimaCurso = 31; // Zona mínima para asignación de retrasada
                $query_retrasada = $this->gsql->ver_TipoCurso(1,$_SESSION["cursosAsig"][$j]['curso'],$_SESSION["cursosAsig"][$j]['mCarreraCurso']);

                if (($_SESSION["sConRet"]->query($query_retrasada)) AND ($_SESSION["sConRet"]->num_rows() > 0)) {
                    $_SESSION["sConRet"]->next_record();
                    $resultado = (int)$_SESSION["sConRet"]->f("resultado");

                    switch($resultado) {
                        case 0:
                            $zonaMinimaCurso =31;
                            break;
                        case 1:
                            $zonaMinimaCurso =50;
                            break;
                    }
                }
                //echo 'curso= '.$_SESSION["cursosAsig"][$j]['curso'].' :: zona='.$_SESSION["cursosAsig"][$j]['mZonaCurso'].' :: zona minima='.$zonaMinimaCurso.'<br>';

                // Se verifica si la zona del curso es la necesaria para poder asignarse retrasada
                if (((int)$_SESSION["cursosAsig"][$j]['mZonaCurso'] < $zonaMinimaCurso) AND ((int)$_SESSION["cursosAsig"][$j]['mZonaCurso'] > 0)) {
                   // $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 0;
                    $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 4;
                    //echo $_SESSION["cursosAsig"][$j]['curso'].'<br>';
                } else {
                    if ((int)$_SESSION["cursosAsig"][$j]['mZonaCurso'] == 0) {
                        $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 0;
                        $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 4;
                        //$_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 4;
                    } else {
                        $_SESSION["cursosAsig"][$j]['mEstadoAsignar'] = 4;
                        //echo $_SESSION["cursosAsig"][$j]['curso'].'<br>';
                    }
                }
            } // if si entra a comparar
        } // del for del ciclo del numero de cursos asignados
    } // fin de funcion verZonaCursos


    function CalculaMontoA_Cancelar()
    {
        $valorRetrasada = $montoAsignar = 0;
        $error = 0;
        $periodo = 0 + $this->mPeriodo;

        switch ($periodo) {
            case 102:
            case 202:
                $valorRetrasada = VALOR_PRIMERA_RETRASADA;
                break;
            case 103:
            case 203:
                $valorRetrasada = VALOR_SEGUNDA_RETRASADA;
                break;
        }

        for ($i = 0; $i <= $this->mNumCursos; $i++) {
            if ((strcmp($_SESSION["cursosAsig"][$i]['curso'], "") != 0) AND ($_SESSION["cursosAsig"][$i]['curso'] != NULL)) {
                if ($_SESSION["cursosAsig"][$i]['mEstado'] == 1) {
                    $montoAsignar = $montoAsignar + $valorRetrasada;
                }
            }
        }
        return $montoAsignar;
    }

    function  buscaMotosBDD()
    {
        $query_usuario = $this->gsql->buscaMotosBDD_select1($this->mCarnet, _TIPO_PAGO_PRIMERA_RETRASADA, _TIPO_PAGO_SEGUNDA_RETRASADA, $this->mAnio, $this->mPeriodo, $this->mCarrera,_TIPO_PAGO_PRIMERA_RETRASADA2, _TIPO_PAGO_SEGUNDA_RETRASADA2);

        $_SESSION['NUMERO_ORDENES_CANCELADAS'] = 0;

        if ($_SESSION["sConRet"]->query($query_usuario)) {
            if ($_SESSION["sConRet"]->num_rows() < 1) {
                $montoBBD = 0;
            } //No tiene registros de pago
            else {
                $nfilas = $_SESSION["sConRet"]->num_rows();
                $montoBBD = 0;
                $pos = 0;

                while ($nfilas > 0) {
                    $_SESSION["sConRet"]->next_record();
                    if (($_SESSION["sConRet"]->f("verws") == 0) AND ($_SESSION["sConRet"]->f("verca") == 0)) {
                        $_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['orden'] = $_SESSION["sConRet"]->f("paymentorder");
                        $_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['fecha'] = $_SESSION["sConRet"]->f("paymentorderdate");
                        $pos++;
                    } else {
                        $_SESSION['ORDENPAGO_CANCELADA'][$pos]['orden'] = $_SESSION["sConRet"]->f("paymentorder");
                        $_SESSION['ORDENPAGO_CANCELADA'][$pos]['fecha'] = $_SESSION["sConRet"]->f("paymentorderdate");
                        $_SESSION['NUMERO_ORDENES_CANCELADAS']++;
                        $montoBBD += $_SESSION["sConRet"]->f("amount");
                    }
                    $nfilas--;
                }
                $_SESSION['NUMERO_ORDENES_NO_CANCELADAS'] = $pos;
            }
        }

        if ($_SESSION["sConRet"]->query($query_usuario)) {
            if ($_SESSION["sConRet"]->num_rows() < 1) {
                $montoBBD = 0;
            } //No tiene registros de pago
            else {
                $nfilas = $_SESSION["sConRet"]->num_rows();
                $montoBBD = 0;
                $pos = 0;

                while ($nfilas > 0) {
                    $_SESSION["sConRet"]->next_record();
                    if (($_SESSION["sConRet"]->f("verws") == 0)  AND ($_SESSION["sConRet"]->f("verca") == 0)) {
                        $_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['orden'] = $_SESSION["sConRet"]->f("paymentorder");
                        $_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['fecha'] = $_SESSION["sConRet"]->f("paymentorderdate");
                        $pos++;
                    } else {
                        $montoBBD += $_SESSION["sConRet"]->f("amount");
                    }
                    $nfilas--;
                }
                $_SESSION['NUMERO_ORDENES_NO_CANCELADAS'] = $pos;
            }
        }
        return $montoBBD;
    }

    function calculaMontoDePago()
    {
        // se inicializan los tres valores a cero
        $montoGenerar = $montoACancelar = $montoCancelado = 0;

        //de primero se debe de buscar posibles pagos generados o ya pagados anteriormente
        //del mismo periodo y del mismo proceso
        //Si es la primera vez que el estudiante se asigna, el monto  Cancelado sera de 0, si no es asi, se
        //obntendra el monto correspondiente.

        $montoCancelado = $this->buscaMotosBDD();

        //Siguienre paso es calcular el monto que debe  que cancelar el estudiante por lo que se desea asignar
        //tomando los cursos de 2 hrs., 4hrs., inscripcion, laboratorio, etc.
        $montoACancelar = $this->CalculaMontoA_Cancelar();

        //Lo siguiente es realizar el proceso de calcular el monto que se utilizara para generar la
        //Orden de Pago
        //La cual se realiza residuo del
        //montoACancelar - montoCancelado = montoGenerar
        $montoGenerar = $montoACancelar - $montoCancelado;
        $_SESSION['monto_Generar'] = $_SESSION['montoGeneral'] = $montoGenerar; // se toma el monto con que se generara el recibo de pago

        if ($_SESSION['NUMERO_ORDENES_NO_CANCELADAS'] != NULL) {
            //printf("nonc = %d", $_SESSION['NUMERO_ORDENES_NO_CANCELADAS']);
        }
    }


    function verificaFechaExamen_bloquea()
    {
        if ((strcmp($this->mPeriodo, SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE) == 0)) {
            $anio = DATE(Y) - 1;
        } else {
            $anio = DATE(Y);
        }

        for ($pos = 1; $pos <= $this->mNumCursos; $pos++) {
            switch($this->mPeriodo) {
                case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
                case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $sql = $this->gsql->verificaFechaExamen_bloquea_select1($anio, $this->mPeriodo, trim($_SESSION["cursosAsig"][$pos]['curso']), trim($_SESSION["cursosAsig"][$pos]['carrera']));
                    break;
                case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
                case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $sql = $this->gsql->verificaFechaExamen_bloquea_select11($anio, $this->mPeriodo, trim($_SESSION["cursosAsig"][$pos]['curso']), trim($_SESSION["cursosAsig"][$pos]['carrera']));
                    break;
            }

            if (($_SESSION["sConRet"]->query($sql)) AND ($_SESSION["sConRet"]->num_rows() == 0)) {
                $_SESSION["cursosAsig"][$pos]['mEstadoAsignar'] = 2;  // si esta bloqueado para no ser asignable
                $_SESSION["cursosAsig"][$pos]['mEstado'] = 2;
            } // si existe algun curso para verificar si no es asignable
        } // del ciclo del for
    } //  de la funcion


    function obtieneNombreEstudiante()
    {
        $sql = $this->gsql->obtieneNombreEstudiante_select1($this->mCarnet);

        if (($_SESSION["sConRet"]->query($sql)) AND ($_SESSION["sConRet"]->num_rows() > 0)) // verifica la consulta
        {echo $sql;
            $_SESSION["sConRet"]->next_record();
            $_SESSION["nombre"] = $this->nombre = trim($_SESSION["sConRet"]->f("name")) . " " . trim($_SESSION["sConRet"]->f("surname"));
            $_SESSION["datosGenerales"]->nombreEstudiante = $this->nombre;
        }
    }

//} // fin de clase  manejoAsignaSimultanea TomaNombreCurso
    function ver_Extension()
    { //$sql = sprintf("SELECT extension FROM estudiantecarrera WHERE usuarioid = '%s';",$this->mCarnet);

        $sql = $this->gsql->ver_Extension_select1($this->mCarnet);

        if (($_SESSION["sConRet"]->query($sql)) AND ($_SESSION["sConRet"]->num_rows() > 0)) // verifica la consulta
        {
            $_SESSION["sConRet"]->next_record();
            $extension = $_SESSION["sConRet"]->f("extension");
        } else {
            $extension = -2;
        }
        return $extension;
    }

    /**
     ** Creado: 15/11/2012, Edwin Saban
     *** Procedimiento que verifica si el curso tiene un problema especifico.
     *** @param character $p_marcas cadena que contiene los problemas del curso
     *** @param integer $problema el problema que se busca dentro de la cadena.
     *** @return boolean TRUE si la marca existe de lo contrario FALSE
     ***/
    function existeElProblema($p_marcas, $problema)
    {
        /**
         *** Se crea un vector con las marcas de asignación del curso.
         ***/
        $marcas_curso = explode(',', $p_marcas);

        /**
         *** Se recorren las marcas del curso para verificar si el curso
         *** tienen marca enviada como parametro $problema
         ***/
        for ($i = 0; $i < count($marcas_curso); $i++) {
            if ((strcmp(trim($marcas_curso[$i]), "" . $problema . "") == 0)) {
                //El curso es la retrasada única
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     *** Creado: 15/11/2012, Edwin Saban
     *** Procedimiento que verifica si el curso tiene un problema especifico.
     *** @param character $p_marcas cadena que contiene los problemas del curso
     *** @param integer $problema el problema que se busca dentro de la cadena.
     *** @return boolean TRUE si la marca existe de lo contrario FALSE
     ***/
    function replaceProblema($p_marcas, $problema, $replace)
    {
        /**
         *** Se crea un vector con las marcas de asignación del curso.
         ***/
        $marcas_curso = explode(',', $p_marcas);

        /**
         *** Se recorren las marcas del curso para verificar si el curso
         *** tienen marca enviada como parametro $problema
         ***/
        for ($i = 0; $i < count($marcas_curso); $i++) {
            if ((strcmp(trim($marcas_curso[$i]), "" . $problema . "") == 0)) {
                //El curso es la retrasada única
                $marcas_curso[$i] = "" . $replace . "";
            }
        }
        $cadena_separado_por_comas = implode(",", $marcas_curso);
        return $cadena_separado_por_comas;
    }
} // fin de clase manejoRetrasada
?>
