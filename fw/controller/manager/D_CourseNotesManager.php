<?php

require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/DB_Connection.php");
require_once("$dir_portal/fw/model/sql/D_CourseNotesManager_SQL.php");

define ("INI_CAMBIO_PROC_PRACTICA", 2011);
define ("MODULARES_ZOOTECNIA","106,110,112,116,120,122,123,190,251,508,516,526,236,238,239,242,511,520,260,262,407,408,418,430");
define ("MODULARES_VETERINARIA","620,621,622,623,151,197,198,330,508,511,407,407");


class D_CourseNotesManager
{
    // Atributos de la clase
    var $mCurso;
    var $mIndex;
    var $mSeccion;
    var $mPeriodo;
    var $mCarrera;
    var $mZona;
    var $mFinal;
    var $mAnio;
    var $mNombreCorto;
    var $mHorario;
    var $mAsignados;
    var $mEstado;
    var $mTipoActa;
    var $mLaboratorio;
    var $mHabilitado;
    var $mZonaCongelado;
    var $mCursoSinNota; // nuevo miembro para determinar que es un curso que no lleva notas
    var $mBloquearLabZona; // para controlar si se deben bloquear las casillas de laboratorio y zona por ingreso de actividades
    var $retrasadaUnica; // para verificar aprobación de la retrasada única y permitir procesar datos del congelado
    var $mEscuela; //Agregada el 24/06/2013  para el requerimiento solicitado por la administración a través del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
    var $mTipoCurso;
    /*
     * Variable para utilizar las consultas
     */
    var $gsql;

// ***************************************************************
    // Constructor
    // ***************************************************************
    function D_CourseNotesManager()
    { //   Se realiza una conexion con el Servidor de la Base de datos
        $_SESSION["sNotas"] = NEW DB_Connection();
        if ($_SESSION["sNotas"]->connect() == 100) {
            //retorna 100 si falla
            $_SESSION["sErrorBDD"] = 0;
        }
        $this->mHabilitado = 0; //Inicializa la variable que indicara que puede ingresar notas

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new D_CourseNotesManager_SQL();

    }

    // ************************************************************
    // Valida Fechas de Ingreso de Notas
    // Verifica si la fecha actual se encuentra dentro del Rango
    // permitido para ingreso de notas de un período en específico.
    // ************************************************************
    function ValidaRangoFechas($param_periodo, $param_anio,$_SESSION_curso,$_SESSION_carrera)
    {
        /*
         * retorna 0  si no existen datos
         * retorna 1  luego de verificar fechas dentro del rago permitido
         */
        $query_fecha = $this->gsql->ValidaRangoFechas_select1($param_periodo, $param_anio,$_SESSION_curso,$_SESSION_carrera);

        $_SESSION["sNotas"]->query($query_fecha);

        if ($_SESSION["sNotas"]->num_rows() < 1) {
            return 0; //No existe el usuario
        } else {
            $_SESSION["sNotas"]->next_record();
            $fechainicia = trim($_SESSION["sNotas"]->f('startdate'));
            $fechafin = trim($_SESSION["sNotas"]->f('enddate'));
            $fechahoy = date(Y) . "-" . date(m) . "-" . date(d);

            if (($fechahoy >= $fechainicia) && ($fechahoy <= $fechafin)) {
                return 1; //Si existen datos
            }
        }
    } //Fin de ValidaRangoFechas

    // ************************************************************
    // Verifica si esta habilitado para descargar archivo
    // ************************************************************
    function BajarArchivo($param_periodo, $param_anio)
    { //  $query_descarga = sprintf("select descargaarchivo
//                             from ingresoparametro
//                             where periodo = '%s'
//                             and   anio = %d;",
//                             $param_periodo,$param_anio);

        $query_descarga = $this->gsql->BajarArchivo_select1($param_periodo, $param_anio);

        $_SESSION["sNotas"]->query($query_descarga);

        if ($_SESSION["sNotas"]->num_rows() < 1) {
            return 0; //No hay info
        } else {
            $_SESSION["sNotas"]->next_record();
            $descarga = trim($_SESSION["sNotas"]->f('downloadfile'));

            if ($descarga == '1') {
                return 1; //Si existen datos
            }
        }
    } //Fin de esta habilitado para descarga


//Para nuevo manejo de congelados (Pancho López - 15/05/2012)
    // ********************************************
    // obtieneRetrasadasUnicas
    // Devuelve los Datos de la retrasada única, ya aprobada, de los estudiante que esten congelando el curso en ese período/año
    // ********************************************
    function obtieneRetrasadasUnicas($conn1, $anio, $periodo, $curso, $seccion)
    {
//Debido a que se quitó la funcionalidad de verificación de congelados y retrasada única, se incluyó la siguiente instrucción de tal forma
//que el sistema funcione como estaba anteriormente. Si se quisiera que funcionara la verificación es necesario eliminar únicamente esta
//instruccion. Pancho López, 09/10/2012
        return;
//    $vectorTemp = array();
        $periodoAprobUnica = $periodo;
        switch ($periodo) {
            case "03" :
                $periodoAprobUnica = $this->gsql->obtieneRetrasadasUnicas_select1_1(); //"'01'"; //Sólo se toma en cuenta la aprobación en semestre regular
                break;
            case "04" :
                $periodoAprobUnica = $this->gsql->obtieneRetrasadasUnicas_select1_2(); //"'01','03'"; //Se toma en cuenta la aprobación en semestre regular o en primera retrasada
                break;
            case "07" :
                $periodoAprobUnica = $this->gsql->obtieneRetrasadasUnicas_select1_3(); //"'05'"; //Sólo se toma en cuenta la aprobación en semestre regular
                break;
            case "08" :
                $periodoAprobUnica = $this->gsql->obtieneRetrasadasUnicas_select1_4(); //"'05','07'"; //Se toma en cuenta la aprobación en semestre regular o en primera retrasada
                break;
        }

        if ($periodo == "01" || $periodo == "02" || $periodo == "05" || $periodo == "06") {
            //Consulta para buscar la retrasada única aprobada durante el período correspondiente a la asignación del congelado. Se toma
            //en cuenta sí y sólo sí el acta de dicha retrasada única ya se encuentra en un estado mayor o igual a APROBADA VIA WEB
//	  $sqlQuery = "select distinct a.usuarioid, ad.curso,(ad.zona+ad.examenfinal) as notafinal, ad.anio, ad.periodo" .
//	              " from asignacion a, asignaciondetalle ad, (select a1.usuarioid, ad1.curso, ad1.seccion" .
//				  " from asignacion a1, asignaciondetalle ad1 where a1.transaccion=ad1.transaccion" .
//				  " and a1.fechaasignacion=ad1.fechaasignacion and ad1.anio=" . $anio . " and ad1.periodo='" . $periodo .
//				  "' and ad1.curso='" . $curso . "' and ad1.seccion='" . $seccion ."' and ad1.problema in (3,17)) ad2, horario h" .
//				  " where a.transaccion=ad.transaccion and a.fechaasignacion=ad.fechaasignacion and h.anio=ad.anio" .
//				  " and h.periodo=ad.periodo and h.curso=ad.curso and h.seccion=ad.seccion and h.tipoacta='W' and h.estado>=5" .
//				  " and h.estado<=17 and ad.periodo='" . $periodoAprobUnica . "' and ad.anio=" . $anio . " and ad.problema in (2)" .
//				  " and (ad.zona+ad.examenfinal)>60 and a.usuarioid=ad2.usuarioid order by 1";

            $sqlQuery = $this->gsql->obtieneRetrasadasUnicas_select1($anio, $periodo, $curso, $seccion, $periodoAprobUnica);

        } else {
            //Consulta para buscar la retrasada única aprobada durante el período inmediato anterior a la asignación del congelado. Se toma
            //en cuenta sí y sólo sí la retrasada única ya se encuentra cargada en el listado de cursos aprobados.
//	  $sqlQuery = "select distinct ca.usuarioid, ca.curso, ca.nota as notafinal, substring(ca.fechaaprobacion,1,4) as anio, ca.periodo" .
//	              " from cursoaprobado ca, (select a.usuarioid,a.carrera,ad.curso,ad.periodo from asignacion a, asignaciondetalle ad," .
//				  " (select a1.usuarioid, ad1.curso, ad1.seccion from asignacion a1, asignaciondetalle ad1" .
//				  " where a1.transaccion=ad1.transaccion and a1.fechaasignacion=ad1.fechaasignacion and ad1.anio=" . $anio . 
//				  " and ad1.periodo='" . $periodo . "' and ad1.curso='" . $curso . "' and ad1.seccion='" . $seccion . 
//				  "' and ad1.problema in (3,17)) ad2, horario h where a.transaccion=ad.transaccion" .
//				  " and a.fechaasignacion=ad.fechaasignacion and h.anio=ad.anio and h.periodo=ad.periodo and h.curso=ad.curso" .
//				  " and h.seccion=ad.seccion and h.tipoacta='W' and h.estado>=5 and h.estado<=17 and ad.periodo in (" . 
//				  $periodoAprobUnica . ") and ad.anio=" . $anio . " and ad.problema in (2) and a.usuarioid=ad2.usuarioid) t1" .
//				  " where ca.usuarioid=t1.usuarioid and ca.carrera=t1.carrera and ca.curso=t1.curso and ca.periodo=t1.periodo" .
//				  " and substring(ca.fechaaprobacion,1,4)='" . $anio . "' order by 1";

            $sqlQuery = $this->gsql->obtieneRetrasadasUnicas_select2($anio, $periodo, $curso, $seccion, $periodoAprobUnica);

        }
//    echo $sqlQuery . "<br>";
        $conn1->query($sqlQuery);
        $total = $conn1->num_rows();
        if ($total > 0) {
            for ($i = 0; $i < $total; $i++) {
                $conn1->next_record();
                $FilaDato = $conn1->r();
                $this->retrasadaUnica[$FilaDato["usuarioid"]][$curso]["ret_unica"] = $FilaDato["curso"];
//	    $_vectorTemp[$FilaDato["usuarioid"]][$curso]["ret_unica"]=$FilaDato["curso"];
                //$_vectorTemp[$FilaDato["usuarioid"]][$curso]["nota_ret_unica"]=$FilaDato["notafinal"];
            }
        }
//Para considerar los casos en los que los estudiantes se asignan y aprueban la retrasada única en el curso de vacaciones se puede utilizar
//la siguiente operación de bases de datos:
        /*
        select distinct ca.usuarioid, ca.curso, ca.nota as notafinal, ca.fechaaprobacion,ca.periodo,t2.periodo from cursoaprobado ca, (select av1.usuarioid,av1.carrera,adv1.curso,adv1.periodo from asignacion av1, asignaciondetalle adv1, (select a.usuarioid,a.carrera,ad.curso,ad.periodo,ad.anio from asignacion a,asignaciondetalle ad where a.transaccion=ad.transaccion and a.fechaasignacion=ad.fechaasignacion and ad.anio=2012 and ad.periodo='01' and ad.problema=2) t1 where av1.transaccion=adv1.transaccion and av1.fechaasignacion=adv1.fechaasignacion and av1.usuarioid=t1.usuarioid and av1.carrera=t1.carrera and adv1.curso=t1.curso and adv1.anio=t1.anio and adv1.periodo='02' and adv1.zona+adv1.examenfinal>60) t2 where ca.usuarioid=t2.usuarioid and ca.carrera=t2.carrera and ca.curso=t2.curso order by 1;

        //Del resultado se pueden filtrar aquellos que ya estuvieron incluidos con el segmento de código anterior y únicamente incluir los nuevos
        //que hayan aprobado y cursado la retrasada única en el curso de vacaciones relacionado.
        */

//    return $vectorTemp;
    }

//Para nuevo manejo de congelados (Pancho López - 15/05/2012)
    function verificarVector()
    {
        $tamanio = sizeof($this->retrasadaUnica);
        echo $tamanio . "<br>";
        return;
        if ($tamanio > 0)
            for ($i = 0; $i < $tamanio; $i++)
                echo $this->retrasadaUnica[$i] . "<br>";
    }


//Para nuevo manejo de congelados (Pancho López - 15/05/2012)
//Verifica si para el carné y congelado existe un registro de que el estudiante ya aprobó la retrasada única en el período adecuado
    function aproboRetrasadaUnica($carnet, $congelado, $periodo)
    {
//Debido a que se quitó la funcionalidad de verificación de congelados y retrasada única, se incluyó la siguiente instrucción de tal forma
//que el sistema funcione como estaba anteriormente. Si se quisiera que funcionara la verificación es necesario eliminar únicamente esta
//instruccion. Pancho López, 09/10/2012
        return true;

        $periodosRetrasadas = array("03", "04", "07", "08"); //períodos para los que se debe hacer la verificación
        if (in_array($periodo, $periodosRetrasadas) === false)
            return true;
        if (isset($this->retrasadaUnica) && sizeof($this->retrasadaUnica) > 0) {
            if (isset($this->retrasadaUnica[$carnet][$congelado]) && $this->retrasadaUnica[$carnet][$congelado]["ret_unica"] != "") {
                return true; //aparece en el listado de los que ya aprobaron la retrasada única asociada a ese congelado
            } else
                return false; //no aparece en el listado de los que ya aprobaron la retrasada única asociada a ese congelado
        }
        return false; //no existe listado de estudiantes que ya hayan aprobado la retrasada única asociada a ese congelado
    }

//Para nuevo manejo de códigos de tipos de problemas de un curso en la asignación (Pancho López - 09/10/2012)
//Verifica si dentro de los tipos de problemas que posee un curso asignado, se encuentra el tipo CONGELADO
    function esCursoCongelado($__problemas)
    {
        if (strlen(trim($__problemas)) == 0)
            return false; //El curso no presentó problemas en la asignación
        $vectorProblemas = explode(",", trim($__problemas));
        if (sizeof($vectorProblemas) == 0)
            return false; //El curso no presentó problemas en la asignación
        $tamanio = sizeof($vectorProblemas);
        for ($i = 0; $i < $tamanio; $i++)
            if ($vectorProblemas[$i] == 3 || $vectorProblemas[$i] == 17)
                return true; //El curso está siendo congelado
        return false; //El curso no presenta problemas de congelado
    }

    function verificarLaboratorioYZonaMinima($periodo, $anio)
    {
        $periodos2doSem = array(SEGUNDO_SEMESTRE, VACACIONES_DEL_SEGUNDO_SEMESTRE, PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE, SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE);
        if ($anio > 2013) //La verificación se acordó que sería a partir del segundo semestre de 2013 en adelante
            return true;
        if ($anio == 2013 && in_array($periodo, $periodos2doSem) === true) //si el período corresponde al segundo semestre de 2013
            return true;
        return false; //para períodos del primer semestre de 2013 y años anteriores
    }

    function clasificarCurso($txtIndex, $txtCurso, $txtCarrera) {
        $SqlSeccionMagistral  = $this->gsql->esCursoModular($txtIndex,$txtCurso,$txtCarrera);
        if($_SESSION["sNotas"]->query($SqlSeccionMagistral) AND $_SESSION["sNotas"]->num_rows() > 0) {
            $_SESSION["sNotas"]->next_record();
            $Resultado=$_SESSION["sNotas"]->r();
            if($Resultado['resultado'] + 0) {
                $this->mTipoCurso = 2;
            } else {
                $this->mTipoCurso = 1;
            }
        }

        switch($this->mPeriodo) {
            case PRIMER_SEMESTRE:
            case SEGUNDO_SEMESTRE:
                if($this->mTipoCurso==2) {
                    $this->mZona = 50;
                    $this->mFinal = 20;
                } else {
                    $this->mZona = 31;
                    $this->mFinal = 30;
                }
                break;
            case VACACIONES_DEL_PRIMER_SEMESTRE:
            case VACACIONES_DEL_SEGUNDO_SEMESTRE:
            $this->mZona = 40;
            $this->mFinal = 30;
                break;
        }
    }


    // ********************************************
    // Curso Datos
    // Devuelve los Datos de un curso en específico
    // ********************************************
    function CursoDatos()
    {
        $query_datos = $this->gsql->CursoDatos_select1($this->mCurso, $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo, $this->mAnio, $this->mIndex);


        $_SESSION["sNotas"]->query($query_datos);

        //echo $query_datos;

        if ($_SESSION["sNotas"]->num_rows() < 1) {
            return 0; //No existe el curso
        } else {
            $_SESSION["sNotas"]->next_record();
            $this->mNombreCorto = trim($_SESSION["sNotas"]->f('r_nombre'));
            $this->mZona = trim($_SESSION["sNotas"]->f('r_zona'));
            $this->mFinal = 100 - $this->mZona;
            $this->mZonaCongelado = 0.60 * $this->mZona;
            $this->mAnio = trim($_SESSION["sNotas"]->f('r_anio'));
            $this->mPeriodo = trim($_SESSION["sNotas"]->f('r_periodo'));
            //$this->mHorario = "Edificio : " . $_SESSION["sNotas"]->f('building') .
               // "&nbsp;&nbsp;&nbsp;Sal&oacute;n : " . $_SESSION["sNotas"]->f('idclassroom') .
               // "&nbsp;&nbsp;&nbsp;Hora : De " . $_SESSION["sNotas"]->f('starttime') .
               // " a " . $_SESSION["sNotas"]->f('endtime') . " hrs.";
            $this->mAsignados = trim($_SESSION["sNotas"]->f('r_asignados'));
            $this->mEstado = trim($_SESSION["sNotas"]->f('r_estado'));
            $this->mTipoActa = $_SESSION["sNotas"]->f('r_tipo');

            $this->mCursoSinNota = 0; // si lleva notas
// fin del bloque nuevo

            $conn1 = NEW DB_Connection();
            if ($conn1->connect() == 100) {
                $_SESSION["sErrorBDD"] = 0;
            }

// bloque que determina si el curso lleva laboratorio o una practica tipo laboratorio		  
//        $queryLaboratorio="select hd.tipo, c.escuela from horariodetalle hd, curso c where c.curso=hd.curso and hd.periodo='" . $this->mPeriodo . "' and hd.anio=" . $this->mAnio ." and curso='" . $this->mCurso . "' and hd.tipo in (2,6);";		  

            $queryLaboratorio = $this->gsql->CursoDatos_select3($this->mPeriodo, $this->mAnio, $this->mCurso, $this->mIndex);

            $conn1->query($queryLaboratorio);

            if ($conn1->num_rows() < 1) {
                $this->mLaboratorio = 0;
                $this->mEscuela = 0; //Como el curso no lleva laboratorio, no importa la escuela a la que pertenezca (agregada el 24/06/2013)
            } else {
                $conn1->next_record();
                $this->mLaboratorio = $conn1->f('idscheduletype');
                if ($this->verificarLaboratorioYZonaMinima($this->mPeriodo, $this->mAnio) === true)
                    $this->mEscuela = $conn1->f('idschool');
                else
                    $this->mEscuela = 0; //Como no hay que verificar, no importa la escuela a la que pertenezca (agregada el 17/10/2013)
            }
            $this->mBloquearLabZona = 0;
            return 1; //Curso con información
        }
    } //Fin de Curso Datos

    // ********************************************
    // CrearArchivo, realiza la creacion del
    // listado de estudiantes del curso que se
    // específica
    // ********************************************
    function CrearArchivo()
    {
        $query_listado = $this->gsql->CrearArchivo_select1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
        $this->mIndex);

//echo $query_listado; die;

        $_SESSION["sNotas"]->query($query_listado);

        $seccion_temporal = $this->mSeccion;

        if (strlen($this->mSeccion) == 2) {
            $signo = substr($this->mSeccion, 1, 1);
            if ($signo == '+') $seccion_temporal[1] = '+';
        }

        $nombre_archivo = "/var/www/downloads/" . $this->mIndex . "_" . $this->mCurso . $this->mCarrera/*$seccion_temporal*/ . $this->mPeriodo . $this->mAnio . ".csv";
        $archivo = fopen($nombre_archivo, "w");

        $encabezado = "Carnet,Estudiante \x0D";

        fwrite($archivo, $encabezado);

        for ($i = 1; $i <= $this->mAsignados; $i++) //   for($i = 1; $i <= $Asignados; $i++)
        {
            $_SESSION["sNotas"]->next_record();

            $carne = trim($_SESSION["sNotas"]->f('idstudent'));
            $nombre = iconv('UTF-8', 'ISO-8859-1', trim($_SESSION["sNotas"]->f('surname')) . ", " . trim($_SESSION["sNotas"]->f('name')));
            //$laboratorio = trim($_SESSION["sNotas"]->f('labnote'));
            //$zona = trim($_SESSION["sNotas"]->f('classzone'));
            //$examen = trim($_SESSION["sNotas"]->f('notefinalexam'));

            $linea_estudiante = sprintf("%d,%s \x0D", $carne,$nombre);
                //, $laboratorio, $zona, $examen);


            fwrite($archivo, $linea_estudiante);

        } // fin del for $i
        fclose($archivo);
    } //Fin de crear archivo con el listado de estudiantes

    // ********************************************
    // CrearArchivo_2, realiza la creacion del
    // listado de estudiantes del curso que se indica
    // y que son cursos sin nota
    // ********************************************
    function CrearArchivo_2()
    {

//    $query_listado = sprintf("select a.usuarioid,e.nombre,e.apellido,
//                         b.examenfinal
//                 from
//                 (select transaccion,fechaasignacion,zonalaboratorio,
//                  zona,examenfinal from asignaciondetalle
//                  where curso   = '%s'
//                  and   seccion = '%s'
//                  and   periodo = '%s'
//                  and   anio    =  %d) b,
//                  asignacion a,
//                  estudiante e
//                 where
//                       b.transaccion=a.transaccion
//                 and   b.fechaasignacion=a.fechaasignacion
//                 and   e.usuarioid=a.usuarioid order by a.usuarioid;",
//                       $this->mCurso,
//                       $this->mSeccion,
//                       $this->mPeriodo,
//                       $this->mAnio);

        $query_listado = $this->gsql->CrearArchivo_2_select1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
        $this->mIndex);

//echo $query_listado; die;

        $_SESSION["sNotas"]->query($query_listado);

        //  $Asignados = $_SESSION["sNotas"]->num_rows();
        //  echo $Asignados;

        $seccion_temporal = $this->mSeccion;

        if (strlen($this->mSeccion) == 2) {
            $signo = substr($this->mSeccion, 1, 1);
            if ($signo == '+') $seccion_temporal[1] = '+';
        }

        $nombre_archivo = "/var/www/downloads/" . $this->mIndex . "_" . $this->mCurso . $this->mCarrera/*$seccion_temporal*/ . $this->mPeriodo . $this->mAnio . ".csv";
        $archivo = fopen($nombre_archivo, "w");

        $encabezado = "Carnet,Nombre del Estudiante,Nota Final \x0D";

        fwrite($archivo, $encabezado);

        for ($i = 1; $i <= $this->mAsignados; $i++) //   for($i = 1; $i <= $Asignados; $i++)
        {
            $_SESSION["sNotas"]->next_record();

            $carne = trim($_SESSION["sNotas"]->f('idstudent'));
//        $nombre = trim($_SESSION["sNotas"]->f('nombre'))." ".trim($_SESSION["sNotas"]->f('apellido'));
            $nombre = iconv('UTF-8', 'ISO-8859-1', trim($_SESSION["sNotas"]->f('name')) . " " . trim($_SESSION["sNotas"]->f('surname')));
//        $laboratorio = trim($_SESSION["sNotas"]->f('zonalaboratorio'));
//        $zona = trim($_SESSION["sNotas"]->f('zona'));
            $examen = trim($_SESSION["sNotas"]->f('notefinalexam'));

//        $linea_estudiante = sprintf("%s,%s,%d,%d,%d \x0D",$carne,
//                            $nombre,$laboratorio,$zona,$examen);

            $linea_estudiante = sprintf("%d,%s \x0D", $carne,
                $nombre);//, $examen);


            fwrite($archivo, $linea_estudiante);

        } // fin del for $i
        fclose($archivo);
    } //Fin de crear archivo con el listado de estudiantes


    // ********************************************
    // funcion que calcula y actualiza cantidad
    // de estudiantes asignados de un curso especifico
    // ********************************************
    function CalculaAsignados()
    {
        $query_asignados = $this->gsql->CalculaAsignados_select1($this->mCurso,
            /*$this->mSeccion,*/
            $this->mCarrera,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex);

//echo $query_asignados;

        $_SESSION["sNotas"]->query($query_asignados);
    } // Fin de CalculaAsignados


    // ***********************************************
    //              CalculaVigencia
    // Calcula la fecha de vigencia para el laboratorio
    // ***********************************************
    function CalculaVigencia($periodoInicio, $anioInicio, &$periodoFin, &$anioFin)
    {
//Comentarizado por Pancho López el 18/10/2011 debido a la nueva funcionalidad que solicitó la Administración
        /*
             switch ($periodoInicio)
                 {
                    case "01" :        $periodoFin = "06";
                                            $anioFin = $anioInicio + 1;
                                                break;
                        case "02" :        $periodoFin = "01";
                                            $anioFin = $anioInicio + 2;
                                                break;
                        case "05" : $periodoFin = "02";
                                                $anioFin = $anioInicio + 2;
                                                break;
                        case "06" :        $periodoFin = "05";
                                                $anioFin = $anioInicio + 2;
             }//Fin de Switch
        */
//Inicia nuevo código agregado por Pancho López el 18/10/2011 para la nueva forma de calcular las vigencias del laboratorio
        $periodoFin = $periodoInicio;
        $anioFin = $anioInicio + 2;
//Finaliza nuevo código agregado por Pancho López el 18/10/2011 para la nueva forma de calcular las vigencias del laboratorio
    } //Fin de CalculaVigencia

    // ********************************************
    // Inserta en las tabla bitacoraacta
    // inserta o actualiza en ingresoregistro,
    // inserta o actualiza en horario
    // ********************************************
    function InsertarRegistro($param_nombrearchivo, $param_tamanio, $mUsuario, $mGrupo)
    {
        $cambio_estado = true;

        switch ($this->mEstado) {
            case 2  :
                $this->mEstado = 3;
                break;
            case 3  : // si es estado 3 o 4 se queda en el mismo estado
            case 4  :
                $cambio_estado = false;
                break;
            case 15 :
                $this->mEstado = 4;
                break;
            case 18 :
                $this->mEstado = 3;
                break;
        } // fin del switch($estado)

        $query_bitacora = $this->gsql->InsertarRegistro_select1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex
        );

        $_SESSION["sNotas"]->query($query_bitacora);

        if ($_SESSION["sNotas"]->num_rows() > 0) // si hay info ->comparar
        {
            $_SESSION["sNotas"]->next_record();
            $campodescripcion = trim($_SESSION["sNotas"]->f('description'));
            if (strcmp($campodescripcion, 'Reingreso interno (Area Operacion)') != 0) // si no es igual
            {
                $campodescripcion = 'usuario web';
            } // se queda con usuario web
        } else {
            $campodescripcion = 'usuario web';
        } // se queda con usuario web
// fin de modificacion 
//*********************************************	 	 

        //Por cualquier tipo de proceso siempre se inserta en bitacoraacta
        $query_bitacora = $this->gsql->InsertarRegistro_insert1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $mUsuario,
            $this->mEstado,
            $campodescripcion,
            $param_nombrearchivo,
            $param_tamanio,
            $this->mIndex);


        $_SESSION["sNotas"]->query($query_bitacora);


        //echo $query_bitacora."<br>";


        //Verificar si ya existe una tupla en ingresoregistro del curso en cuestion
        // 1. si no existe se inserta
        // 2. si ya existe un registro, se actualiza
        $query_ingresoreg = $this->gsql->InsertarRegistro_select2($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex);


        $_SESSION["sNotas"]->query($query_ingresoreg);

//echo $_SESSION["sNotas"]->num_rows()."<br>"; die;

        if ($_SESSION["sNotas"]->num_rows() < 1) {
            $query_registro = $this->gsql->InsertarRegistro_insert2($this->mCurso,
                $this->mCarrera/*$this->mSeccion*/,
                $this->mPeriodo,
                $this->mAnio,
                $param_nombrearchivo,
                $param_tamanio,
                $mUsuario,
                $mGrupo,
                $this->mEstado,
                $this->mIndex);

        } else {
            $query_registro = $this->gsql->InsertarRegistro_update1($this->mEstado,
                $param_nombrearchivo,
                $param_tamanio,
                $mUsuario,
                $this->mCurso,
                $this->mCarrera/*$this->mSeccion*/,
                $this->mPeriodo,
                $this->mAnio,
                $this->mIndex);
        }
        $_SESSION["sNotas"]->query($query_registro);

        //echo $query_registro; die;

        if ($cambio_estado) {

            // esto se deberia llenar cuando se dispare un trigger de la tabla
            // ingresoregistro tabla bitacoraacta

            // esto se deberia llenar cuando se dispare un trigger de la tabla
            // ingresoregistro tabla horario

            $query_horario = $this->gsql->InsertarRegistro_update2($this->mEstado,
                $mUsuario,
                $this->mCurso,
                $this->mCarrera/*$this->mSeccion*/,
                $this->mPeriodo,
                $this->mAnio,
                $this->mIndex);


            //echo $query_horario; die;

            $_SESSION["sNotas"]->query($query_horario);


        }
        //fin si cambio el estado
    } // fin de la funcion InsertarRegistro

    // ********************************************
    // Inserta en bitacoraacta
    // actualiza en ingresoregistro,
    // actualiza en horario, con estadoacta = 5 o 6
    // ********************************************
    function InsertarAprobacion($param_nombrearchivo, $param_tamanio)
    {
        $cambio_estado = true;

        switch ($this->mEstado) {
            // acta aprobada sin incovenientes
            case 3  :
                $this->mEstado = 5;
                break;
            // acta aprobada con espera de recuperar la impresa
            case 4  :
                $this->mEstado = 6;
                break;

        } // fin del switch($estado)

        //Por cualquier tipo de proceso siempre se inserta en bitacoraacta
//     $query_bitacora = sprintf("insert into bitacoraacta
//                                (curso,seccion,periodo,anio,fecha,usuarioid,
//                                estado,descripcion,tipoacta,impresa,nombrearchivo,tamanio)
//                                values('%s','%s','%s','%s','now()','%s',
//                                        %d,'usuario web','W','0','%s',%d);",
//                                $this->mCurso,
//                                $this->mSeccion,
//                                $this->mPeriodo,
//                                $this->mAnio,
//                                $_SESSION["sUsuarioDeSesion"]->mUsuario,
//                                $this->mEstado,
//                                $param_nombrearchivo,
//                                $param_tamanio);

        $query_bitacora = $this->gsql->InsertarAprobacion_insert1($this->mCurso,
            $this->mSeccion,
            $this->mPeriodo,
            $this->mAnio,
            $_SESSION["sUsuarioDeSesion"]->mUsuario,
            $this->mEstado,
            $param_nombrearchivo,
            $param_tamanio);


        $_SESSION["sNotas"]->query($query_bitacora);

        // esto se deberia llenar cuando se dispare un trigger de la tabla
        // ingresoregistro tabla bitacoraacta

        // esto se deberia llenar cuando se dispare un trigger de la tabla
        // ingresoregistro tabla horario

//        $query_horario = sprintf("update horario set estado='%s',
//                                                  usuarioid = '%s'
//                                  where curso = '%s'
//                                  and seccion = '%s'
//                                  and periodo = '%s'
//                                  and anio    = '%s';",
//                                  $this->mEstado,
//                                  $_SESSION["sUsuarioDeSesion"]->mUsuario,
//                                  $this->mCurso,
//                                  $this->mSeccion,
//                                  $this->mPeriodo,
//                                  $this->mAnio);

        $query_horario = $this->gsql->InsertarAprobacion_update1($this->mEstado,
            $_SESSION["sUsuarioDeSesion"]->mUsuario,
            $this->mCurso,
            $this->mSeccion,
            $this->mPeriodo,
            $this->mAnio);


        //echo $query_horario; die;

        $_SESSION["sNotas"]->query($query_horario);


//        $query_registro = sprintf("update ingresoregistro set estado = %d, nombrearchivo = '%s',
//                                   tamanio = %d, fechaaprobacion = current_timestamp
//                                                     where usuarioid = '%s'
//                                                     and curso       = '%s'
//                                                     and seccion     = '%s'
//                                                     and periodo     = '%s'
//                                                     and anio        = '%s';",
//                                              $this->mEstado,
//                                              $param_nombrearchivo,
//                                              $param_tamanio,
//                                              $_SESSION["sUsuarioDeSesion"]->mUsuario,
//                                              $this->mCurso,
//                                              $this->mSeccion,
//                                              $this->mPeriodo,
//                                              $this->mAnio);

        $query_registro = $this->gsql->InsertarAprobacion_update2($this->mEstado,
            $param_nombrearchivo,
            $param_tamanio,
            $_SESSION["sUsuarioDeSesion"]->mUsuario,
            $this->mCurso,
            $this->mSeccion,
            $this->mPeriodo,
            $this->mAnio);


        $_SESSION["sNotas"]->query($query_registro);

        //echo $query_registro; die;

    } // fin de la funcion InsertarAprobacion

    function MoverLaboratorios($carnet, $curso, $periodo, $anio, $notalaboratorio)
    {
//     $query_laboratorio = sprintf("select f_manejolaboratorio('%s','%s','%s','%s',%d);"
//                                ,$carnet,$curso,$periodo,$anio,$notalaboratorio);

        $query_laboratorio = $this->gsql->MoverLaboratorios_select1($carnet, $curso, $periodo, $anio, $notalaboratorio);


        $_SESSION["sNotas"]->query($query_laboratorio);
    }

    // ********************************************
    // Inserta en Asignaciondetalle los datos ingresados
    // ********************************************
    function GrabarNotasFinales()
    {

//    $query_crear = sprintf("select f_createmp();");

        $query_crear = $this->gsql->GrabarNotasFinales_select1();

        /*     $query_crear = sprintf("create temp table webtablafinal
                            (carnet char(9),carrera char(2),
                             laboratorio numeric(3,0),zona numeric(3,0),examen numeric(3,0));");*/

        $_SESSION["sNotas"]->query($query_crear);

//echo "--->",$query_crear;    die;

//$query_crear = sprintf("grant all on webtablafinal to usringnotas;");

        $query_crear = $this->gsql->GrabarNotasFinales_grant1();

        $_SESSION["sNotas"]->query($query_crear);

        // $notasfinales = pg_copy_to($_SESSION["sNotas"]->Link_ID, "webtablafinal","|");

//echo "los asignados suman: ".$this->mAsignados; die;

        for ($i = 0; $i < $this->mAsignados; $i++) {
            $notasfinales[$i] = implode("|", $_SESSION["sVectorAprobacion"][$i]);
            // echo $notasfinales[$i]."<br>";
            // echo $_SESSION["sVectorAprobacion"][$i][0]."<br>";
            // echo $_SESSION["sVectorAprobacion"][$i][1]."<br>";
            // echo $_SESSION["sVectorAprobacion"][$i][2]."<br>";
            // echo $_SESSION["sVectorAprobacion"][$i][3]."<br>";
            // echo $_SESSION["sVectorAprobacion"][$i][4]."<br>";
        }

//	 $array[0] = array ('008411139','05',70,45,10) ;
// 	 $array[1] = array ('008830661','05',70,45,10,11) ;

        $array[0] = array($_SESSION["sVectorAprobacion"][0][0], $_SESSION["sVectorAprobacion"][0][1], '40', $_SESSION["sVectorAprobacion"][0][3], $_SESSION["sVectorAprobacion"][0][4]);
        $array[1] = array($_SESSION["sVectorAprobacion"][1][0], $_SESSION["sVectorAprobacion"][1][1], $_SESSION["sVectorAprobacion"][1][2], $_SESSION["sVectorAprobacion"][1][3], $_SESSION["sVectorAprobacion"][1][4]);

        $a[0] = implode("|", $array[0]);
        $a[1] = implode("|", $array[1]);

//     pg_copy_from($_SESSION["sNotas"]->Link_ID,"webtablafinal",$notasfinales[0], "|");
        pg_copy_from($_SESSION["sNotas"]->Link_ID, "webtablafinal", $a, "|");

//     $query_llenarasig = sprintf("select f_llenarasignaciondetalle
//                    ('%s','%s','%s','%s','%s');",
//                  $_SESSION["sUsuarioDeSesion"]->mUsuario,
//                  $this->mCurso,
//                  $this->mSeccion,
//                  $this->mPeriodo,
//                  $this->mAnio
//                    );

        $query_llenarasig = $this->gsql->GrabarNotasFinales_select2($_SESSION["sUsuarioDeSesion"]->mUsuario,
            $this->mCurso,
            $this->mSeccion,
            $this->mPeriodo,
            $this->mAnio
        );

//echo "query ->".$query_llenarasig; die;

        $_SESSION["sNotas"]->query($query_llenarasig);

//     $query_borrar = sprintf("drop table webtablafinal;");

        $query_borrar = $this->gsql->GrabarNotasFinales_drop1();


        $_SESSION["sNotas"]->query($query_borrar);

//echo "query ->".$query_llenarasig; die;
    }

    function COAC_DarInformacionDeCurso($anio,$periodo,$carrera,$curso){
        $sql = $this->gsql->COAC_DarInformacionDeCurso_selec1($anio,$periodo,$carrera,$curso);

        if ($_SESSION["sNotas"]->query($sql) AND $_SESSION["sNotas"]->num_rows() > 0) {
            $_SESSION["sNotas"]->next_record();
            $resultado[] = $_SESSION["sNotas"]->r();
            return $resultado;
        }

        return $_SESSION["sNotas"]->num_rows();
    }

    // ********************************************
    // Inserta en Asignaciondetalle los datos ingresados
    // ********************************************
    function ListarAprobados($mUsuario)
    {

        $query_datos = $this->gsql->ListarAprobados_select1($mUsuario,
            $this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
            $this->mIndex
        );


//echo "query datos --",$query_datos,"--"; die;

        $_SESSION["sNotas"]->query($query_datos);

        $vector_acta = pg_copy_to($_SESSION["sNotas"]->Link_ID, "weblistadoaprobado", "|");

//    $query_datos = sprintf("drop table weblistadoaprobado;");

        $query_datos = $this->gsql->ListarAprobados_drop1();


        $_SESSION["sNotas"]->query($query_datos);

        /*    print_r($_SESSION["sNotas"]);
            echo "  hasta aqui sesion <br>";
            print_r(get_declared_classes());
            echo " hasta aqui clases <br>"; */

        for ($i = 1; $i <= $this->mAsignados; $i++) {
            $_SESSION["Aprobado"][$i - 1] = explode("|", $vector_acta[$i - 1]);
        }

        /*    echo $_SESSION["Acta"][1][1]."<br>"; carnet
            echo $_SESSION["Acta"][1][8]."<br>"; lab
            echo $_SESSION["Acta"][2][7]."<br>"; zona
            echo $_SESSION["Acta"][2][9]."<br>"; die;  examen */

    } // fin de la función


    // ********************************************
    //                 ValidaCongelado
    // ********************************************
    function ValidaCongelado($param_pos, $param_laboratorio, $param_zona)
    {
//         if ($this->mLaboratorio == '1')  //Si lleva laboratorio
//      if ($this->mLaboratorio == 2)  //Si lleva laboratorio normal tipo 2 unicamente
        /*
           if( $this->mLaboratorio == 2 || $this->mLaboratorio == 6 )
           // para laboratorio normal (2) y practica tipo laboratorio (6)
             {
                    if ($param_laboratorio >= 61)  //Si gano el laboratorio
                        {
                           if ($param_zona < 45)   //Zona menor que la congelada 45 perdio el curso
                           {
                              $_SESSION["sVectorAprobacion"][$param_pos][2] = 0;   //zonalaboratorio
                              $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                                  $_SESSION["sVectorAprobacion"][$param_pos][4] = -2;  //examenfinal
                           }
                        }
                        else  //Si perdio el laboratorio
                        {
                           $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                           $_SESSION["sVectorAprobacion"][$param_pos][4] = -2;  //examenfinal
                    }
                 }
                 else  //Si no lleva laboratorio
                 {
                    if ($param_zona < 45) //Zona menor que la zona de congelamiento
                        {
                           $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                           $_SESSION["sVectorAprobacion"][$param_pos][4] = -2;  //examenfinal
                        }
        //                else  //Zona es mayor
        //                {}
             }
        */
        switch ($this->mLaboratorio) {
            case 2: // curso con laboratorio normal, nota de aprobacion del mismo 61
                if ($param_laboratorio >= 61) //Si gano el laboratorio
                {
                    if ($param_zona < 45) //Zona menor que la congelada 45 perdio el curso
                    {
                        $_SESSION["sVectorAprobacion"][$param_pos][2] = 0; //zonalaboratorio
                        $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                        $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                    } //Para nuevo manejo de congelados (Pancho López - 15/05/2012)
                    elseif ($this->aproboRetrasadaUnica($_SESSION["sVectorAprobacion"][$param_pos][0], $this->mCurso, $this->mPeriodo) === false) {
                        // elseif ($this->aproboRetrasadaUnica($_SESSION["sVectorAprobacion"][$param_pos][0],$this->mCurso)===false) {
                        $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                        $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                    }
                    // *******************************************************
                } else //Si perdio el laboratorio
                {
                    $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                    $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                }
                break;
            case 6: // curso con practica tipo laboratorio, no necesariamente se tiene que aprobar
                if ($param_zona < 45) { //Zona menor que la zona de congelamiento
                    $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                    $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                    if ($param_laboratorio > 60) // si el laboratorio es 61 o mas
                    {
                        $_SESSION["sVectorAprobacion"][$param_pos][2] = 0; //zonalaboratorio
                    }
                } //Para nuevo manejo de congelados (Pancho López - 15/05/2012)
                elseif ($this->aproboRetrasadaUnica($_SESSION["sVectorAprobacion"][$param_pos][0], $this->mCurso, $this->mPeriodo) === false) {
                    $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                    $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                }
                // *******************************************************
                break;
            default:
                if ($param_zona < 45) //Zona menor que la zona de congelamiento
                {
                    $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                    $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                } //Para nuevo manejo de congelados (Pancho López - 15/05/2012)
                elseif ($this->aproboRetrasadaUnica($_SESSION["sVectorAprobacion"][$param_pos][0], $this->mCurso, $this->mPeriodo) === false) {
                    //    elseif ($this->aproboRetrasadaUnica($_SESSION["sVectorAprobacion"][$param_pos][0],$this->mCurso)===false) {
                    $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                    $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                }
            // *******************************************************
        } // end del switch
    }//Fin de ValidaCongelado


    // ********************************************
    //                 ValidaNoCongelado
    // ********************************************
    function ValidaNoCongelado($param_pos, $param_laboratorio, $param_zona)
    {
        /*echo $this->mLaboratorio."<br>";
        echo $param_pos."<br>";
        echo $param_laboratorio."<br>";
        echo $param_zona."<br>";*/

        if ($this->mLaboratorio == 2) //Si lleva laboratorio
        {
            if ($param_laboratorio < 61) //Si perdio el laboratorio
            {
                $_SESSION["sVectorAprobacion"][$param_pos][3] = 0; //zona
                $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
            } else //Si gano el laboratorio
            {
                if ($param_zona < 36) //Zona menor que la minima de 36
                {
                    // $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                    //Agregado el 24/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                    if ($this->mEscuela == 1 || $this->mEscuela == 5) //Si el curso pertenece a la escuela de Civil o Química
                        $_SESSION["sVectorAprobacion"][$param_pos][2] = 0; //laboratorio
                    //Finaliza código agregado el 24/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                    $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
                }
            }
        } else //Si no lleva laboratorio
        {
            if ($param_zona < 36) //Zona menor que la minima de 36
            {
                // $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                $_SESSION["sVectorAprobacion"][$param_pos][4] = -2; //examenfinal
            }
        }
        /*$str2 = "";
        $str2 = implode(',',$_SESSION["sVectorAprobacion"][$param_pos])."<br>";
        echo $str2;*/

    }//Fin de ValidaNoCongelado


    // **************************************************************************
    //                                         ObtieneDatosArchivo
    // Obtiene el nombre y el tamanio  del archivo si las notas fueron ingresadas
    // por este medio, de lo contrario devuelve en los parametros valores nulos.
    // **************************************************************************
    function ObtieneDatosArchivo(&$nombrearchivo, &$tamanioarchivo)
    {

        $query_archivo = $this->gsql->ObtieneDatosArchivo_select1($this->mCurso,
            $this->mCarrera/*$this->mSeccion*/,
            $this->mPeriodo,
            $this->mAnio,
        $this->mIndex);


        $_SESSION["sNotas"]->query($query_archivo);

        if ($_SESSION["sNotas"]->num_rows() > 0) {
            $_SESSION["sNotas"]->next_record();
            $nombrearchivo = trim($_SESSION["sNotas"]->f('filename'));
            $tamanioarchivo = trim($_SESSION["sNotas"]->f('size'));
        }
    }//Fin de ObtieneDatosArchivo

    // **************************************************************************
    //                      ValidaIngresoAnterior
    //  Verificar que el período anterior este aprobado en el caso de un ingreso
    // del período de retrasada.
    // **************************************************************************
    function ValidaIngresoAnterior($periodo)
    {
        $estado = 0;
//     $query_procesado = sprintf("select estado 
//	                             from horario 
//								 where curso = '%s' 
//								 and seccion = '%s'
//                                 and periodo = '%s' 
//								 and anio    = '%s';",
//								 $this->mCurso,$this->mSeccion,
//								 $periodo,$_SESSION["sAnio"]
//								 );               

        $query_procesado = $this->gsql->ValidaIngresoAnterior_select1($this->mCurso, $this->mSeccion,
            $periodo, $_SESSION["sAnio"], $this->mIndex);

        $_SESSION["sNotas"]->query($query_procesado);
        if ($_SESSION["sNotas"]->num_rows() > 0) {
            $_SESSION["sNotas"]->next_record();
            $estado = $_SESSION["sNotas"]->f('state');
        }
//	   echo "estado".$estado; die;
        if ($estado == 2 || $estado == 3 || $estado == 4)
            $msg = 310; // este curso aun no tiene notas finales de semestre
        else $msg = 1;
        return $msg;
    }
    //Fin de ValidaIngresoAnterior

} //Fin de Clase ingresonota

?>
