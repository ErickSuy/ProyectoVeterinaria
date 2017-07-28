<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 9/10/14
 * Time: 03:46 PM
 */

include("../../path.inc.php");
include_once("$dir_portal/fw/model/sql/D_FileLoadCourseNotesManager_SQL.php");
include_once("$dir_portal/fw/controller/manager/D_CourseNotesManager.php");

global $vector_info,$vectorNotasTemporal;
global $ErrorExisteDoble, $ErrorZonaCongelamiento;

class D_FileLoadCourseNotesManager extends D_CourseNotesManager {
    // Atributos de la clase
    var $mUsuarioid;
    var $mTipoSeccion;
    var $mNombreArchivo;
    var $mErrorEncontrado;
    var $mNumeroDeTuplas;
    var $mStringMensaje;
    var $mEnlaceManual;      //Contiene el link hacia el proceso manual
    var $mEnlaceAprobacion;  //Contiene el link hacia el proceso de aprobacion
    var $labVigentes;
    /*
     * Variable para utilizar las consultas
     */
    var $gsql_IA;


    // ***************************************************************
    // Constructor
    // ***************************************************************
    function D_FileLoadCourseNotesManager()
    {
        $this->mErrorEncontrado  = 0;   //Inicializa la variable que indicara si existe algun error
        $this->mNumeroDeTuplas   = 0;   //Inicializa la indicacion de numero de tuplas en ingresotemporal
        $this->mStringMensaje    = "El archivo fue procesado satisfactoriamente";
        $this->mEnlaceManual     = "";
        $this->mEnlaceAprobacion = "";

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql_IA = new D_FileLoadCourseNotesManager_SQL();
    }


    // *******************************************************************
    //                                                 VerificaLaboratorio
    // Verifica si exite una tupla para ese curso que sea tiposec = 2 la
    // seccion puede ser como ejemplo A, _A la cual se toma como seccion A
    // *******************************************************************
    function VerificaLaboratorio()
    {
        /*
             $query = sprintf("select * from horario
                               where laboratorio  = '1'
                               and curso          = '%s'
                               and seccion        = '%s'
                               and periodo        = '%s'
                               and anio           = '%s';",
                               $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);
        */
//Modificado por Pancho López, el 06/06/2007, para administrar de mejor forma el laboratorio
//     $query = sprintf("select tipo from horariodetalle
//                       where
//                           curso          = '%s'
//                       and periodo        = '%s'
//                       and anio           = '%s'
//					   and ( tipo=2 or tipo=6 );",
////                       $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);
//                       $this->mCurso,$this->mPeriodo,$this->mAnio);

        $query = $this->gsql_IA->VerificaLaboratorio_select1($this->mCurso,$this->mPeriodo,$this->mAnio,$this->mIndex);


        $_SESSION["sNotas"]->query($query);
        if ( $_SESSION["sNotas"]->num_rows() < 1 )
        {  return 0;  }
        else  {
            $_SESSION["sNotas"]->next_record();
            return $_SESSION["sNotas"]->f('idscheduletype');
        }
    }//Fin de VerificaLaboratorio


    // *******************************************************************************
    //                                                         VerificaDecimalNumero
    // Verifica que los valores ingresado para las diferentes columnas de laboratorio,
    // zona y examen final sean valores numericos y no sean decimales.
    // *******************************************************************************
    function VerificaDecimalNumero($param_lab,$param_zona,$param_final)
    {
        $procesar1 = 0;
        $procesar2 = 0;
        $columna1 = is_numeric($param_lab);
        $columna2 = is_numeric($param_zona);
        $columna3 = is_numeric($param_final);
        if( $this->mCursoSinNota == 0 ) // si es un curso que lleva notas
        {
            $decimal1 = substr_count($param_lab, ".");
            $decimal2 = substr_count($param_zona,".");
            $decimal3 = substr_count($param_final,".");
            if ( ($columna1 == 1) && ($columna2 == 1) && ($columna3 == 1) )  {  $procesar1 = 1;  }
            else
            {
                if ( $columna3 == 0 )
                {
                    $param_final = strtoupper($param_final);    //Este bloque es para validar si son siglas
//             (strcmp($param_final,"NZM")==0)||
                    if ((strcmp($param_final,"NSP")==0) || (strcmp($param_final,"NSE")==0) ||
                        (strcmp($param_final,"SDE")==0))
                    {  $procesar1 = 1;  }
                    else {  $procesar1 = 0;  }
                }
                else {  $procesar1 = 0;  }
            }

            if ( ($decimal1 == 0) && ($decimal2 == 0) && ($decimal3 == 0) )  {  $procesar2 = 1;  }
            else
            {
                if ( $decimal3 == 0 )
                {
                    $aux = $param_final + 0;
                    if (!(($aux >= -2) && ($param_final <= $this->mFinal)))  {  $procesar2 = 0;  }
                }
            }
        }
        else  // es un curso que no lleva notas
        {
            $procesar2 = 1;
            if( $columna3 == 0 ) // sino es numero este dato
            {
                $param_final = trim(strtoupper($param_final));    //Este bloque es para validar si son siglas
//             (strcmp($param_final,"NZM")==0)||
                if ((strcmp($param_final,"APR")==0) || (strcmp($param_final,"REP")==0) ||
                    (strcmp($param_final,"SDE")==0))
                {  $procesar1 = 1;  }
                else { $procesar1 = 0;  }
            }
            else
            {
                switch( $param_final )
                {
                    case 1:
                    case 2: $procesar1 = 1; break;

                    default: $procesar1 = 0;
                }
            }
        }

        if ( ($procesar1 == 1) && ($procesar2 == 1) )  {  return 1;  }
        else  {  return 0;  }
    } //Fin de Numero y Decimal


    // ********************************************
    // VerificaLaboratorioZona
    // ********************************************
    function VerificaLaboratorioZona($param_lab,$param_zona)
    {
        $valido1 = 0;
        $valido2 = 0;
        if ( ($param_lab >= 0) && ($param_lab <= 100) )  {  $valido1 = 1;  }
        else {  $valido1 = 0;  }

        if (($param_zona >= 0) && ($param_zona <= $this->mZona))  {  $valido2 = 1;  }
        else {  $valido2 = 0;  }

        if ( ($valido1 == 1) && ($valido2 == 1) )  {  return 1;  }
        else  {  return 0;  }
    } //Fin de Laboratorio y Zona


    // ********************************************
    //          VerificaExamenFinal
    // ********************************************
    function VerificaExamenFinal($param_final)
    {
        if( $this->mCursoSinNota == 0 ) // para cursos que llevan notas
        {
            if ( ($param_final >= -2) && ($param_final <= 30) )  {  return 1;  }
            else {  return 0;  }
        }
        else // para cursos sin nota
        {
            $param_final = trim(strtoupper($param_final));
            switch( $param_final )
            {
                case 1:
                case 2: $valor = 1; break;

                case 'APR':
                case 'REP': $valor = 1; break;

                default: $valor = 0;
            }
            return $valor;
        }
    } //Fin de Verifica ExamenFinal


    // *********************************************************
    //                     VerificaPeriodo
    // Se obtiene la informacion necesaria para hacer el paso a
    // ingresotemporal de la asignacion del periodo en cuestion.
    // *********************************************************
    function VerificaPeriodo()
    {
        if (($this->mPeriodo == PRIMER_SEMESTRE)||($this->mPeriodo == SEGUNDO_SEMESTRE)||($this->mPeriodo == VACACIONES_DEL_PRIMER_SEMESTRE)||($this->mPeriodo == VACACIONES_DEL_SEGUNDO_SEMESTRE))
        {
            $query = $this->gsql_IA->VerificaPeriodo_select1($this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo,$this->mAnio,$this->mIndex);

        }
        else
        {
            if (($this->mPeriodo == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE)||($this->mPeriodo == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)||($this->mPeriodo == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE)||($this->mPeriodo == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE))
            {
                $query = $this->gsql_IA->VerificaPeriodo_select2($this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo,$this->mAnio,$this->mIndex);

            }//Fin de if periodos
        }//Fin de if periodos

        $_SESSION["sNotas"]->query($query);
        if ($_SESSION["sNotas"]->num_rows()<1)  {  return 0;  } //No existe el curso
        else  {  return 1;  }
    }//Fin de la función VerificaPeriodo


    // ****************************************************************************
    //                                                         VerificaDatosTabla
    // Verifica que un curso no se encuentre registrado en la tabla ingresoregistro
    // ******************************+*********************************************
    function VerificaDatosTabla()
    {
        $query_registro = $this->gsql_IA->VerificaDatosTabla_select1($this->mUsuarioid,$this->mCurso,
            $this->mCarrera,$this->mPeriodo,$this->mAnio,$this->mIndex);

        $_SESSION["sNotas"]->query($query_registro);
        $cuantos = $_SESSION["sNotas"]->num_rows();
        if ($cuantos > 0)
        {
            $qrydel = $this->gsql_IA->VerificaDatosTabla_delete1($this->mUsuarioid,$this->mCurso,
                $this->mCarrera/*$this->mSeccion*/,$this->mPeriodo,$this->mAnio,$this->mIndex);
            $_SESSION["sNotas"]->query($qrydel);
        }
        return $cuantos;
    }//Fin de VerificaDatosTabla
//Warning: str_pad(): Padding string cannot be empty in /var/www/fw/controller/manager/D_FileLoadCourseNotesManager.php on line 880


    // *******************************************************
    //                                         VerificaDatos
    //
    // *******************************************************
    function VerificaDatos($param_num,$param_notas,$param_archivo,$bloquearLabZona)
    {
        global $vectorNotasTemporal;
        global $ErrorExisteDoble;

        $ErrorExisteDoble = 0;
        $noexisten = 0;
        $ubicaciondoble = 0;
        for ($c=0; $c < $param_num; $c++)
        {
            if ($c != 0)  //en la posicion 0 el encabezado del archivo
            {  //aqui se separa la cadena de los datos de cada estudiante
                //list($card,$name,$laboratorio,$zona,$final) = split(",",$param_notas[$c]);
                list($card,$final) = split(",",$param_notas[$c]);
                //$laboratorio = trim($laboratorio);
                //$zona        = trim($zona);

                $laboratorio = 0;
                $zona        = 0;
                $final       = trim($final);
                if ($this->mTipoSeccion == 0)  {  $laboratorio = 0;  }
                $cantdigitos = strlen($card);

                // ----------modificacion realizada para el carnet
                switch($cantdigitos)
                {
                    case 9: /*carne bueno */ break;
                    case 7: $subcadena = substr($card, 0, 2);
                        $factible = $subcadena + 0;
                        if ($factible < 98)      //aqui preguntar si los primeros dos digitos son < 98
                        {  /*$card = '00'.$card;*/$card = $card+0;  }
                        else              //si son mayores a 98 entonces agregar 19
                        {  $card = ('19'.$card)+0;  }
                        break;
                    default: $faltan = 9 - $cantdigitos;
                    for($i=0; $i < $faltan; $i++) $card = 0+$card/*'0'.$card*/;
                }//Fin del switch

                //Si los valores son NSP = -1 le asigna su valor correspondiente
                if (strcmp(strtoupper($zona),'NSP')==0 || strcmp(strtoupper($zona),'SDE')==0)
                {  $zona = 0;  }
                else
                {
                    if (strcmp(strtoupper($laboratorio),'NZM')==0 || strcmp(strtoupper($laboratorio),'SDE')==0)
                    {  $laboratorio = 0;  }
                    else
                        if (strcmp(strtoupper($final),'NZM')==0||strcmp(strtoupper($final),'SDE')==0)
                        {  $final = -2;  }
                        else
                            if ((strcmp(strtoupper($final),'NSP')==0)||(strcmp(strtoupper($final),'NSE')==0))
                            {  $final = -1;  }
                }

                //aqui se verifica que el carne se encuentre en asignacion y asignaciondetalle
                //sino no se traslada a ingresotemporal, ademas para los periodos de retrasada
                //se le coloca a la zona y el laboratorio los valores de sus semestres respectivos
                $banderaencontrado = -1;
                for ($j=0; $j < $_SESSION["sNotas"]->num_rows(); $j++)
                {
                    $_SESSION["sNotas"]->Row = $j;
                    $_SESSION["sNotas"]->next_record();
                    $vectasig1[usuarioid]       = trim($_SESSION["sNotas"]->f('idstudent'));
                    $vectasig1[carrera]         = trim($_SESSION["sNotas"]->f('idcareer'));
                    $vectasig1[zona]            = trim($_SESSION["sNotas"]->f('classzone'));
                    $vectasig1[zonalaboratorio] = trim($_SESSION["sNotas"]->f('labnote'));
                    $vectasig1[estadoExamenFinal] = trim($_SESSION["sNotas"]->f('idfinalexamstate'));
                    $vectasig1[notaExamenFinal] = trim($_SESSION["sNotas"]->f('notefinalexam'));
                    $vectasig1[detProblemas] = trim($_SESSION["sNotas"]->f('problemdetail'));

                    if (strcmp($vectasig1[usuarioid], $card) == 0 )
                    {
                        $banderaencontrado = 1;
                        $carrera = trim($vectasig1[carrera]);
                        if (($this->mPeriodo == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE)||($this->mPeriodo == SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE)|| ($this->mPeriodo == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE)
                            ||($this->mPeriodo == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE)||($bloquearLabZona>0))
                        {
                            $laboratorio = $vectasig1[zonalaboratorio];  //asigno el laboratorio
                            $zona        = $vectasig1[zona]; //asigno la zona
                            $problemas = $vectasig1[detProblemas];
                        } else {
                            $laboratorio = $vectasig1[zonalaboratorio];  //asigno el laboratorio
                            $zona        = $vectasig1[zona]; //asigno la zona
                            $problemas = $vectasig1[detProblemas];
                            if (($this->mPeriodo == PRIMER_SEMESTRE)||($this->mPeriodo == SEGUNDO_SEMESTRE)|| ($this->mPeriodo == VACACIONES_DEL_PRIMER_SEMESTRE)
                                ||($this->mPeriodo == VACACIONES_DEL_SEGUNDO_SEMESTRE)||($bloquearLabZona>0))
                            {
                                if(((int)$vectasig1[estadoExamenFinal])==-2 || ((int)$vectasig1[notaExamenFinal])==-2) {
                                    $final = -2;
                                }
                            }
                        }
                        break;
                    }
                }//Fin del for

                // Si los valores son encontrados se va guardando en un vector temporal para luego
                // ser insertado en la tabla ingresotemporal
                if ($banderaencontrado == 1 )
                {
                    $numero = is_numeric($final);
                    if ($numero)
                    {
                        $convfinal = 0;
                        $convfinal = 0 + $final;
                    }
                    else  {  $convfinal = $final;  }

                    $notas[$c-1] = $this->mUsuarioid."|".$card."|".$carrera."|".$this->mCurso."|".$this->mIndex."|".
                        $this->mCarrera/*$this->mSeccion*/."|".$this->mPeriodo."|".$this->mAnio."|".$zona.
                        "|".$laboratorio."|".$convfinal."|0|".$problemas."";

                    //busca que no sea un registro repetido
                    $valor = -5;
                    $banderaduplicado = 0;
                    for ($r = 0; $r < $c-1; $r++)
                    {
                        if ($valor != 1)
                        {
                            $valor =  substr_count($notasfinales[$r], $card); //cuenta cuantas veces aparece
                            if ($valor > 0)   //si lo encontro al menos una vez
                            {
//                     $tempdup = substr($notasfinales[$r],0,strlen($notasfinales[$r])-1);
//                     $tempdup = $tempdup."8003,".$carrera;
//                     $notasfinales[$r] = "";
//                     $notasfinales[$r] = $tempdup;
                                $valor = -5;
                                $banderaduplicado = 1;
                                $ErrorExisteDoble = 1;
                                $ubicaciondoble = $c+1;
                            }
                        }
                    } //Fin del For

                    if ($banderaduplicado == 0)  {  $notasfinales[$c-1] = $notas[$c-1];  }
                    $banderaencontrado = -1;
                }//fin del if de encontrado
                else  {  $noexisten++;  }
            } //Fin del if c!=0
        }//Fin del for
//echo "<br>vector --",$notasfinales[0][$card],"-- no existen <<",$noexisten,">>";
        //Si existe en el archivo alguna tupla repetida se descarta de una vez el archivo
        if ($ErrorExisteDoble == 0)
        {
            //cuando hay registros agregados hay que verificar cuantos son dependiendo el porcentaje se desecha el archivo
            $porcentaje = $this->mAsignados * 0.50;

            if (($noexisten < $porcentaje)&&($notasfinales != null))
            {
                $transaccion_ok = 1;
//           $transaccion_ok = $_SESSION["sNotas"]->query("begin");

                $transaccion_ok = $_SESSION["sNotas"]->query($this->gsql_IA->begin());

                $registros = $this->VerificaDatosTabla();

//         $old_error_handler  = set_error_handler("Maneja_Error_Copy");
                $resultado = pg_copy_from($_SESSION["sNotas"]->Link_ID,"tbtempentry", $notasfinales,"|");
//         restore_error_handler();
                if ($resultado)
                {  //COMMIT TRANSACTION
//              $rst_transaccion = $_SESSION["sNotas"]->query("commit");

                    $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->commit());

                    // Muestra resultados
                    if ($this->upload_report($registros,$param_archivo) == 1)  {  return 1;         }
                    else {  return 0;  }
                }
                else
                {  // Ha ocurrido algún error. Recupera el backup de la tabla de notas
                    //ROLLBACK TRANSACTION
//              $rst_transaccion = $_SESSION["sNotas"]->query("rollback");

                    $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->rollback());

                    $this->mStringMensaje = "No fu&eacute; posible cargar el archivo<br>
                                               por favor revise la informaci&oacute;n y vuelva a intentarlo.";
                    return 0;
                } //END TRANSACTION
//                 $rst_transaccion = $_SESSION["sNotas"]->query("end");

                $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->end());

            }
            else
            {
                $this->mStringMensaje = "No coinciden los alumnos del archivo con los asignados<br>
                                    revise que el archivo realmente sea el correcto<br>
                                    y vuelva a intentarlo.";
                return 0;
            }
        }
        else
        {
            $this->mStringMensaje = "Existen registros repetidos.<br>Verificar la l&iacute;nea : ".$ubicaciondoble;
            return 0;
        }//Fin de if de existe tupla repetida en el archivo
    }//Fin del la funcion VerificaDatos


    // ********************************************
    // Validacion de Archivo de Ingreso de Notas
    // Devuelve los Datos de un curso en específico
    // ********************************************
    function ValidaArchivo($param_num,$param_notas,$param_archivo,$bloquearLabZona)
    {
        global $ErrorExisteDoble;
        $continuar = 0;

        //Verifica que el archivo contenga el encabezado correspondiente
        //list($card,$name,$laboratorio,$zona,$final) = split(",",$param_notas[0]);
        list($card,$final) = split(",",$param_notas[0]);
        $card = trim(strtoupper($card));
        switch ($card)
        {
            case 'CARNE'  :
            case 'CARNÉ'  :
            case 'CARNET' : break;
            default       : $this->mStringMensaje = "El archivo no es delimitado por comas o <br>
                                                         no contiene el encabezado correspondiente :<br>
                                                         Carnet,Final";
            return 0;
            break;
        }

        // Edwin Sabán. Se comentarizo la verificacion de horario de laboratorio pq no se manejan notas de labs
        $this->mTipoSeccion = 0/*$this->VerificaLaboratorio()*/;
        for ($d=1; $d<$param_num; $d++)
        {
            if( $this->mCursoSinNota == 0 ) // si es un curso que lleva notas
            {
                //list($card,$name,$laboratorio,$zona,$final) = split(",",$param_notas[$d]);
                list($card,$final) = split(",",$param_notas[$d]);
                //aqui verificar si es numero
                //$laboratorio = trim($laboratorio);
                //$zona = trim($zona);
                $laboratorio = 0;
                $zona = 0;
            }
            else
            {
                //list($card,$name,$final) = split(",",$param_notas[$d]);
                list($card,$final) = split(",",$param_notas[$d]);
                $laboratorio = 0;
                $zona = 0;
            }
            $final = trim($final);
            $nolinea = $d + 1;
            if ( $this->VerificaDecimalNumero($laboratorio,$zona,$final) == 1 )
            {
                $aux  = $laboratorio + 0;
                $zona = $zona + 0;
                if ( $this->VerificaLaboratorioZona($aux,$zona) == 1 )
                {
                }
                else
                {
                    $this->mStringMensaje = "No fue posible cargar la informaci&oacute;n.<br> Revise la L&iacute;nea N&uacute;mero : ".$nolinea."<br>
                                       Verifique lo siguiente<br>
                                       *  Que los valores de zona esten entre 0 y ".$this->mZona;
                    return 0;
                }

                if ($this->VerificaExamenFinal($final) == 1) {}
                else
                {
                    $this->mStringMensaje = "No fue posible cargar la informaci&oacute;n.<br> Revise la L&iacute;nea N&uacute;mero : ".$nolinea."<br>
                                       Verifique lo siguiente<br>
                                       *  Que los valores del examen final esten entre 0 y ".$this->mFinal;
                    return 0;
                }

            }
            else
            {
                $this->mStringMensaje = "No fue posible cargar la informaci&oacute;n.<br> Revise la L&iacute;nea N&uacute;mero : ".$nolinea."<br>
                                   Verifique lo siguiente<br>
                                   *  Que los valores sean num&eacute;ricos<br>
                                   *  Que el archivo no contenga decimales<br>
                                   *  Que los valores del examen final esten entre 0 y ".$this->mFinal;
                return 0;
            }
        }//Fin del for

        if ( $this->VerificaPeriodo() == 1 )
        {
        }
        else
        {
            $this->mStringMensaje = "No se puede obtener informaci&oacute;n del curso.";
            return 0;
        }

        if( $this->mCursoSinNota == 0 ) // si el curso lleva notas
        {
            if ( $this->VerificaDatos($param_num,$param_notas,$param_archivo,$bloquearLabZona) == 1 )
            {
            }
            else
            {
                return 0;
            }
        }
        else
        {
            if ( $this->VerificaDatos_2($param_num,$param_notas,$param_archivo) == 1 )
            {
            }
            else
            {
                return 0;
            }
        }

        return 1;
    }//Fin de Función ValidaArchivo


    // ****************************************************************************
    //                                                         Upload_Report
    // Reporte del resultado de la copia y carga del archivo
    // ****************************************************************************
    function upload_report($param_registros,$parametro_archivo)
    {
        global $ErrorExisteDoble,$ErrorZonaCongelamiento;

        $Resultado_Validacion = $this->VerificaDatosArchivo();

        if( $this->mCursoSinNota == 1 ) $valorexamen = -4;  // si es un curso que no lleva notas -4=reprobado
        else                            $valorexamen = 0;

        // inserta registros de estudiantes no contenidos en el archivo
        if (($this->mPeriodo == PRIMER_SEMESTRE)||($this->mPeriodo == SEGUNDO_SEMESTRE)||($this->mPeriodo == VACACIONES_DEL_PRIMER_SEMESTRE)||($this->mPeriodo == VACACIONES_DEL_SEGUNDO_SEMESTRE))
        {
//        $qrytemp = sprintf("insert into ingresotemporal
//                   select '%s',a.usuarioid,a.carrera,ad.curso,ad.seccion,ad.periodo,%d,0,0,%d,'0000',0
//                   from asignacion a,asignaciondetalle ad
//                   where a.transaccion = ad.transaccion
//                   and a.fechaasignacion = ad.fechaasignacion
//                   and a.usuarioid not in ( select carnet
//                                            from ingresotemporal b
//                                            where b.usuarioid = '%s'
//                                            and b.curso = '%s'
//                                            and b.seccion = '%s'
//                                            and b.periodo = '%s'
//                                            and b.anio = '%s')
//                   and ad.curso   = '%s'
//                   and ad.seccion = '%s'
//                   and ad.periodo = '%s'
//                   and ad.anio    = '%s';",
//                   $this->mUsuarioid,$this->mAnio,$valorexamen,$this->mUsuarioid,
//                   $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio,$this->mCurso,
//                   $this->mSeccion,$this->mPeriodo,$this->mAnio);

            $qrytemp = $this->gsql_IA->upload_report_insert1($this->mUsuarioid,$this->mAnio,$valorexamen,
                $this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo);

        }
        else
        {
            if (($this->mPeriodo == PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE)||($this->mPeriodo == SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE)||($this->mPeriodo == PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE)||($this->mPeriodo == SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE))
            {
//           $PeriodoAnterior = '01';
//           if (($this->mPeriodo=='07')||($this->mPeriodo=='08'))  {  $PeriodoAnterior = '05';  }
//           $qrytemp = sprintf("insert into ingresotemporal
//                               select '%s',a.usuarioid,b.carrera,ad.curso,ad.seccion,b.periodo,%d,
//                               ad.zona, ad.zonalaboratorio, %d, '0000'
//                               from asignacion as a, asignaciondetalle ad,
//                               ( select a2.usuarioid,a2.carrera,ad2.periodo
//                                 from asignacion as a2, asignaciondetalle ad2
//                                 where a2.transaccion   = ad2.transaccion
//                                 and a2.fechaasignacion = ad2.fechaasignacion
//                                 and ad2.curso           = '%s'
//                                 and ad2.seccion         = '%s'
//                                 and ad2.periodo         = '%s'
//								 and ad2.anio            = '%s'
//                                 and a2.usuarioid not in ( select c.carnet
//                                                           from ingresotemporal c
//                                                           where c.usuarioid = '%s'
//                                                           and c.curso       = '%s'
//                                                           and c.seccion     = '%s'
//                                                           and c.periodo     = '%s'
//                                                           and c.anio        = '%s' )
//                               ) as b
//                               where a.transaccion   = ad.transaccion
//                               and a.fechaasignacion = ad.fechaasignacion
//                               and a.usuarioid       = b.usuarioid
//                               and a.carrera         = b.carrera
//                               and ad.curso          = '%s'
//                               and ad.seccion        = '%s'
//                               and ad.periodo        = '%s'
//							   and ad.anio           = '%s';",
//                               $this->mUsuarioid,$this->mAnio,$valorexamen,
//                               $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio,
//                               $this->mUsuarioid,$this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio,
//                               $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);

                $qrytemp = $this->gsql_IA->upload_report_insert2($this->mUsuarioid,$this->mAnio,$valorexamen,
                    $this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo);

//echo "query --",$qrytemp,"--";  die;
            } // Fin de if periodos 03,04,07 y 08
        } // Fin de if periodos 01 y 05

        //echo $qrytemp."<br>";
        //echo $insertados; die;



        $_SESSION["sNotas"]->query($qrytemp);
        $insertados = $_SESSION["sNotas"]->affected_rows();


        if ($Resultado_Validacion == 1)  // encontro errores
        {
            if ($ErrorExisteDoble == 1)
            {
                $Resultado = 2;
                $this->mStringMensaje = "El archivo conten&iacute;a registros duplicados <br> por
                                    favor revise la informaci&oacute;n en la opci&oacute;n manual.";
                //$this->mEnlaceManual  = "<a href='../../fw/controller/manager/D_ManuaEntryLoadNotesManager.php'>Modo Manual </a>";
                return 0;
            }
            else
            {
                if ($insertados != 0)
                {
                    if ($this->mTipoSeccion > 0)
                    {
//                 $qryUpdate = sprintf("update ingresotemporal set zonalaboratorio = cast(laboratorio.nota as integer) from laboratorio
//                                       where ingresotemporal.periodo = '%s'
//                                       and   ingresotemporal.anio      = '%s'
//                                       and   ingresotemporal.curso     = '%s'
//                                       and   ingresotemporal.seccion   = '%s'
//                                       and   ingresotemporal.carnet    = laboratorio.usuarioid
//                                       and   ingresotemporal.curso     = laboratorio.curso
//                                       and   ingresotemporal.zonalaboratorio = 0;",$this->mPeriodo,$this->mAnio,
//                                       $this->mCurso,$this->mSeccion);

                        //Edwin Saban, comentarizado ya que no se trabajan notas de labs
                        //$qryUpdate = $this->gsql_IA->upload_report_update1($this->mPeriodo,$this->mAnio,
                          //  $this->mCurso,$this->mSeccion);
                        $qryUpdate = '';
                        //echo $qryUpdate."<br>";
                        //echo $insertados; die;

//                 $_SESSION["sNotas"]->query("begin");

                        $_SESSION["sNotas"]->query($this->gsql_IA->begin());

                        $_SESSION["sNotas"]->query($qryUpdate);
//                 $_SESSION["sNotas"]->query("commit");

                        $_SESSION["sNotas"]->query($this->gsql_IA->commit());

                    }
                    $Resultado = 2;
                    $this->mStringMensaje = "El archivo NO contenia todos los registros  <br> por
                                       favor revise la informaci&oacute;n en la Opci&oacute;n Manual.";
                    //$this->mEnlaceManual = "<a href='../../fw/controller/manager/D_ManuaEntryLoadNotesManager.php'>Modo Manual </a>";
                }
                else   //si no inserto y no existe doble existe otro error
                {
                    $Resultado = 2;
                    $this->mStringMensaje = "Los datos fueron cargados con algunos errores,<br>
                                                   puede revisarlos en la Opci&oacute;n Manual.";
                    //$this->mEnlaceManual = "<a href='../../fw/controller/manager/D_ManuaEntryLoadNotesManager.php'>Modo Manual </a>";
                }
            } //Si existe doble
        } //Fin de si existe error
        else  // No encontro errores
        {
            if ($insertados != 0)
            {
                if ($this->mTipoSeccion > 0)
                {
//              $qryUpdate = sprintf("update ingresotemporal
//                                    set zonalaboratorio = cast(laboratorio.nota as integer)
//									from laboratorio
//                                    where periodo       = '%s'
//                                    and anio            = '%s'
//                                    and curso           = '%s'
//                                    and seccion         = '%s'
//                                    and carnet          = laboratorio.usuarioid
//                                    and codcurso        = laboratorio.curso
//                                    and zonalaboratorio = 0;",
//                                    $this->mPeriodo,$this->mAnio,
//                                    $this->mCurso,$this->mSeccion);

                    //Edwin Saban, comentarizado ya que no se trabajan notas de labs
                    //$qryUpdate = $this->gsql_IA->upload_report_update2($this->mPeriodo,$this->mAnio,
                    //    $this->mCurso,$this->mSeccion);
                    $qryUpdate = '';

//              $_SESSION["sNotas"]->query("begin");

                    $_SESSION["sNotas"]->query($this->gsql_IA->begin());

                    $_SESSION["sNotas"]->query($qryUpdate);
//              $_SESSION["sNotas"]->query("commit");

                    $_SESSION["sNotas"]->query($this->gsql_IA->commit());

                }
                $Resultado = 1;
                $this->mStringMensaje = "El archivo indicado NO contenia todos los alumnos asignados, <br>
                                            estos fueron agregados autom&aacute;ticamente,<br>
                                                                        rev&iacute;selos en la Opci&oacute;n Manual.";
                //$this->mEnlaceManual     = "<a href='../../fw/controller/manager/D_ManuaEntryLoadNotesManager.php'>Modo Manual </a>";
            }
            else
            {
                $Resultado = 1;
                $this->mStringMensaje = "Los datos fueron cargados Satisfactoriamente,<br>
                                            y ahora puede revisar la informaci&oacute;n en la<br>
                                                                        opci&oacute;n manual o aprobar.";
                //$this->mEnlaceManual     = "<a href='../../fw/controller/manager/D_ManuaEntryLoadNotesManager.php'>Modo Manual </a>";
                //$this->mEnlaceAprobacion = "<a href='modoaprobacion.php'>Aprobaci&oacute;n </a>";
            }
        }

        //Realiza los procesos sobre ingresoregistro,bitacoraacta y horario
        $this->InsertarRegistro($this->mNombreArchivo,$parametro_archivo,$this->mUsuarioid,2);
        $_SESSION["sObjNotas"]->mEstado= $this->mEstado;
        return 1;
    }//Fin de upload_report


    // *************************************************************************************
    //                                                         LevantaTemporal
    // Obtiene la informacion de la tabla ingresotemporal y la coloca en vectorNotasTemporal
    // y de asignacion - asignacion detalle colocandola en vector_info
    // para su validacion correspondiente.
    // *************************************************************************************
    function LevantaTemporal()
    {
        global $vector_info,$vectorNotasTemporal;

//     $query = sprintf("select distinct *
//                       from ingresotemporal
//                       where curso = '%s'
//                       and seccion = '%s'
//                       and periodo = '%s'
//                       and anio    = %d
//                       order by carnet;",$this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);

        $query = $this->gsql_IA->LevantaTemporal_select1($this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo,$this->mAnio);


        $_SESSION["sNotas"]->query($query);
        $this->mNumeroDeTuplas = $_SESSION["sNotas"]->num_rows();

        if ( $_SESSION["sNotas"]->num_rows()<1 )
        {
            $this->mStringMensaje = "No se puede obtener informaci&oacute;n del curso.";
            return 0;
        }

        for ($i=0; $i<$this->mNumeroDeTuplas; $i++)
        {
            $_SESSION["sNotas"]->next_record();
            $vectorNotasTemporal[$i] = $_SESSION["sNotas"]->Record;
        }

        #Levantar la información del curso a memoria
        //and ad.fechaasignacion between '%d-01-01' and '%d-12-31'
//     $query = sprintf("select distinct a.usuarioid, ad.problema, a.fechainscripcion
//                       from asignacion a, asignaciondetalle ad
//                       where a.transaccion   = ad.transaccion
//                       and a.fechaasignacion = ad.fechaasignacion
//                       and ad.curso          = '%s'
//                       and ad.seccion        = '%s'
//                       and ad.periodo        = '%s'
//                       and ad.anio           = '%s'
//                       order by a.usuarioid;",
//                       $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);

        $query = $this->gsql_IA->LevantaTemporal_select2($this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo,$this->mAnio);


        $_SESSION["sNotas"]->query($query);
        $NN = $_SESSION["sNotas"]->num_rows();

        if ( $_SESSION["sNotas"]->num_rows()<1 )
        {
            $this->mStringMensaje = "No se puede obtener informaci&oacute;n del curso.";
            return 0;
        }

        for ($i=0; $i<$NN; $i++)
        {
            $_SESSION["sNotas"]->next_record();
            $vector_info[$i] = $_SESSION["sNotas"]->Record;
        }

        return 1;
    }// Fin de LevantaTemporal


    // ****************************************************************************
    //                                                         CambiaCarne
    // ****************************************************************************
    function CambiaCarne()
    {
        global $vectorNotasTemporal;

        for ($control=0; $control<$this->mNumeroDeTuplas; $control++)
        {
            $errorCarnet = 1;
            $carnet = trim($vectorNotasTemporal[$control][carnet]);
            if ((strlen($carnet) >=1) ||(strlen($carnet) <= 9)) // longitud aceptada
            {
                if (ctype_digit($carnet)) // caracter correcto numerico
                {
                    if (strlen($carnet) <= 7)
                    {
                        $cambia = 1;
                        $ncarnet = $carnet + 0;
                        if ($ncarnet > 9799999)  {  $carnet = ('19'.$carnet)+0;  } // agrega el complemento
                        else  {  $carnet = str_pad($carnet, 9, "", STR_PAD_LEFT)+0;  }
                    }
                    else $cambia=0;
                }
                else  {  $errorCarnet = 0;  }
            }
            else  {  $errorCarnet = 0;  }

            if ($errorCarnet == 1)
            {
                if ($cambia)  {  $vectorNotasTemporal[$control][carnet] = $carnet;  }
            }
            else  {  $vectorNotasTemporal[$control][problema] = 8000;  }
        }//Fin del for
    }//Fin de CambiaCarne


    //*************************************************************
    //                                                BusquedaBinaria
    //  Se obtiene la informacion del curso de la asignacion de las
    // tablas asignacion y asignaciondetalle y se ingresa al vector
    // &vector_info y se busca que exista el carne enviado como
    // parametro.
    //*************************************************************
    function BusquedaBinaria($param_carne,$pajar)
    {
        global $vector_info;
        $primero = 0;
//         $encontrado = 0;
        $ultimo = $this->mNumeroDeTuplas;

        /*
             while (($primero <= $ultimo) && (!$encontrado))
             {
                $central = (int)(($primero+$ultimo)/2);
                if (strcmp($vector_info[$central][usuarioid],$param_carne) == 0)
                {
                   $encontrado = 1;
                   $resultado[problema] = $vector_info[$central][problema];
                }
                else
                {
                   if (strcmp($param_carne,$vector_info[$central][usuarioid])>0)  {  $primero = $central + 1;  }
                   else  {  $ultimo = $central - 1;  }
                }
             }//while
        */
        $posicion = array_search($param_carne, $pajar);
        // a la posicion se le debe quitar 1, pues en el vector_info empieza de 0
        // y en el vector pajar a partir de 1
        if ( $posicion == false )
        {
            $encontrado = false;  // es estudiante que viene en el archivo no esta asignado
        }
        else
        {  // el estudiante esta asignado, se toma el codigo de problema
            $encontrado = true;
            $resultado[problema] = $vector_info[$posicion-1]["problema"];
        }

        if (!$encontrado)  {  return -1;  }
        else  {  return $resultado;  }
    }//Fin de BusquedaBinaria


    //*************************************************************
    //                                               SiglasPermitidas
    //*************************************************************
    function SiglasPermitidas($cont)
    {
        global $vectorNotasTemporal;
        $valido = false;

        if (strcmp(strtoupper($vectorNotasTemporal[$cont][zona]),'NSP') == 0 ||
            strcmp(strtoupper($vectorNotasTemporal[$cont][zona]),'SDE') == 0)
        {
            $vectorNotasTemporal[$cont][zona] = 0;
            $valido = true;
        }
        else
            if (strcmp(strtoupper($vectorNotasTemporal[$cont][zonalaboratorio]),'NZM') == 0 ||
                strcmp(strtoupper($vectorNotasTemporal[$cont][zonalaboratorio]),'SDE') == 0)
            {
                $vectorNotasTemporal[$cont][zonalaboratorio] = 0;
                $valido = true;
            }
            else
                if (strcmp(strtoupper($vectorNotasTemporal[$cont][examenfinal]),'NZM') == 0 ||
                    strcmp(strtoupper($vectorNotasTemporal[$cont][examenfinal]),'SDE') == 0)
                {
                    $vectorNotasTemporal[$cont][examenfinal] = -2;
                    $valido = true;
                }
                else
                    if ((strcmp(strtoupper($vectorNotasTemporal[$cont][examenfinal]),'NSP') == 0)||
                        (strcmp(strtoupper($vectorNotasTemporal[$cont][examenfinal]),'NSE') == 0))
                    {
                        $vectorNotasTemporal[$cont][examenfinal] = -1;
                        $valido=true;
                    }

        return $valido;
    } //Fin de SiglasPermitidas


    function obtieneLaboratoriosVigentes() {

        if (($this->mPeriodo == PRIMER_SEMESTRE || $this->mPeriodo == VACACIONES_DEL_PRIMER_SEMESTRE || $this->mPeriodo == SEGUNDO_SEMESTRE || $this->mPeriodo == VACACIONES_DEL_SEGUNDO_SEMESTRE) &&
            $this->mCursoSinNota == 0 && $this->mTipoSeccion == 2) {
            $sqlQuery = sprintf("select a.usuarioid,ad.zonalaboratorio
                        from asignacion a, asignaciondetalle ad
                        where a.transaccion   = ad.transaccion
                        and a.fechaasignacion = ad.fechaasignacion
                        and ad.curso          = '%s'
						and ad.seccion        = '%s'
						and ad.periodo        = '%s'
 					 	and ad.anio           = '%s'
 					 	and ad.zonalaboratorio>60
						order by a.usuarioid;",
                $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);
            $_SESSION["sNotas"]->query($sqlQuery);
            $total = $_SESSION["sNotas"]->num_rows();
            if ($total>0) {
                for ($i=0; $i<$total; $i++) {
                    $_SESSION["sNotas"]->next_record();
                    $FilaDato=$_SESSION["sNotas"]->r();
                    $this->labVigentes[$FilaDato["usuarioid"]] = $FilaDato["zonalaboratorio"];
//         echo "[" . $i . "] " . $FilaDato["usuarioid"] . "=>[" . $this->labVigentes[$FilaDato["usuarioid"]] . "]<br>";
                }
            } //No existe el curso
        }
    }//Fin de la función VerificaPeriodo


    function verificaNotaLaboratorio($notaLaboratorio,$elCarnet,$zona) {
        if (($this->mPeriodo == '01' || $this->mPeriodo == '02' || $this->mPeriodo == '05' || $this->mPeriodo == '06') &&
            $this->mCursoSinNota == 0 && $this->mTipoSeccion == 2) {
            if (sizeof($this->labVigentes)>0) {
                //   echo "datos: " . $elCarnet . "=>" . $this->labVigentes[$elCarnet] . "[" . $notaLaboratorio . "]<br>";
                if (isset($this->labVigentes[$elCarnet]) && $notaLaboratorio <= 60) { //Laboratorio en el archivo, no aprobado
//        echo $elCarnet . "=>***[" . $this->labVigentes[$elCarnet] . "]***<br>";
//Agregado el 26/06/2013  para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                    if ($zona<36 && ($this->mEscuela==1 || $this->mEscuela==5))
                        return $notaLaboratorio;
                    else
//Fin código agregado el 26/06/2013  para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                        return $this->labVigentes[$elCarnet];
                }
                else
                    return $notaLaboratorio;
            }
            else
                return $notaLaboratorio;
        }
        else
            return $notaLaboratorio;
    }
    //*************************************************************
    //  Se realizan las siguientes verificaciones:
    //  1. Que el estudiante esté inscrito
    //  2. Que el estudiante esté asignado
    //  3. Verificar zona si el curso lo lleva congelado
    //*************************************************************
    function Verificaciones()
    {
        global $vectorNotasTemporal;
        global $ErrorExisteDoble;
        global $ErrorZonaCongelamiento;

        $this->labVigentes = array();
        $pajar = array();
        global $vector_info;
        //$this->obtieneLaboratoriosVigentes();
        for($i=0; $i < count($vector_info); $i++)
        {

            $pajar[$i+1] = $vector_info[$i]["usuarioid"];
            // para que la busqueda funcione el vector pajar debe empezar desde 1
//		echo "<br> $i ".print_r($vector_info[$i])."<br>";
//	 echo "entra aqui 333";
        }
        $NotaExamenFinal = 100 - $this->mZona;
        for ($i = 0; $i < $this->mNumeroDeTuplas; $i++)
        {
            $resultado = $this->BusquedaBinaria(trim($vectorNotasTemporal[$i]["carnet"]),$pajar);
            if ($resultado == -1) //el carne no se encuentra
            {
                $vectorNotasTemporal[$i][problema] = 8002;
                $this->mErrorEncontrado = 1;
            }
            else
            {
                // Verifica caracteres no permitidos
                $vectorNotasTemporal[$i][zona] = $vectorNotasTemporal[$i][zona]*1;  //elimina ceros a la izquierda
                $vectorNotasTemporal[$i][zonalaboratorio] = $vectorNotasTemporal[$i][zonalaboratorio]*1;  //elimina ceros a la izquierda
                $vectorNotasTemporal[$i][examenfinal] = $vectorNotasTemporal[$i][examenfinal]*1;  //elimina ceros a la izquierda

                if( $this->mCursoSinNota == 0 ) // debe verificar porque es un curso que lleva notas
                {

                    // Verifica valores negativos y que las notas sean consistentes
                    if (((int)$vectorNotasTemporal[$i][zona] < 0) || ((int)$vectorNotasTemporal[$i][zonalaboratorio] < 0) ||
                        ((int)$vectorNotasTemporal[$i][examenfinal] < -2) || ((int)$vectorNotasTemporal[$i][zona] > (100 - $this->mFinal))||
                        ((int)$vectorNotasTemporal[$i][examenfinal] > $this->mFinal)||
                        ((int)$vectorNotasTemporal[$i][zonalaboratorio] > 100))
                    {
                        $vectorNotasTemporal[$i][problema] = 8006;
                        $this->mErrorEncontrado = 1;
                    }
                }  // fin de curso que lleva notas
                else
                {
//		     echo "entro port aca ".$vectorNotasTemporal[$i][examenfinal]." <br>";
                    if( $vectorNotasTemporal[$i][examenfinal] <> -3 && $vectorNotasTemporal[$i][examenfinal] <> -4 )
                    {
                        $vectorNotasTemporal[$i][problema] = 8006;
                        $this->mErrorEncontrado = 1;
                    }
                }

                //Verificar zona de congelamiento

                if ($this->esCursoCongelado($resultado[problema])===true)  //verificar congelamiento
                {

                }//Fin verificar zona de congelamiento
// Verificacion si no es congelado
                else
                {
                    if ($this->mTipoSeccion == 2)  //Si lleva laboratorio
                    {
                        if ($vectorNotasTemporal[$i][zonalaboratorio] < 61)  //Si perdio el laboratorio
                        {
                            $vectorNotasTemporal[$i][zona] = 0;   //zona
                            $vectorNotasTemporal[$i][examenfinal] = -2;  //examenfinal
                        }
                        else  //Si gano el laboratorio
                        {
                            if ($vectorNotasTemporal[$i][zona] < 36)   //Zona menor que la minima de 36
                            {
                                // $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                                $vectorNotasTemporal[$i][examenfinal] = -2;  //examenfinal
                            }
                        }
                    }
                    else  //Si no lleva laboratorio
                    {
                        if( $this->mCursoSinNota == 0 ) // si el curso lleva notas
                        {
                            if ($vectorNotasTemporal[$i][zona] < $this->mZona)   //Zona menor que la minima
                            {
                                // $_SESSION["sVectorAprobacion"][$param_pos][3] = 0;   //zona
                                $vectorNotasTemporal[$i][examenfinal] = -2;  //examenfinal
                            }
                        }  // fin de si es un curso con notas
                    }


                }
// fin de modificacion febrero 2009




                if( $this->mCursoSinNota == 0 )
                {

                    //Verifica que la zona sea mayor que la zona minima
//Comentarizada y modificada por Pancho López el 09/10/2012 por los nuevos códigos de problemas en la asignación
//                   if ($resultado[problema] == 0)
                    if ($this->esCursoCongelado($resultado[problema])===false)
                    {
                        $zonaMinima = 61 - (100 - $this->mZona);
                        if ($vectorNotasTemporal[$i][zona] < $zonaMinima)
                        {
                            $vectorNotasTemporal[$i][examenfinal] = -2;
                        }
                    }
                } // fin de curso que lleva notas

                //Verificar existencia doble
                $contador=0;
                for ($j = $i+1; $j < $this->mNumeroDeTuplas; $j++)
                {
                    if (strcmp($vectorNotasTemporal[$j][carnet],$vectorNotasTemporal[$i][carnet]) == 0)
                    {
                        //if ($vectorNotasTemporal[$i][problema] == 0000)
                        //{
                        $vectorNotasTemporal[$i][problema] = 8003;
                        $vectorNotasTemporal[$j][problema] = 8003;
                        $ErrorExisteDoble = 1;
                        $this->mErrorEncontrado = 1;
                        //}
                    }
                }//For fin verificar existencia doble

            }//if, el carnet no se encuentra

            $Nvector[$i] = $vectorNotasTemporal[$i][usuarioid]."|".
                $vectorNotasTemporal[$i][carnet]."|".
                $vectorNotasTemporal[$i][carrera]."|".
                $vectorNotasTemporal[$i][curso]."|".
                $vectorNotasTemporal[$i][index]."|".
                $vectorNotasTemporal[$i][seccion]."|".
                $vectorNotasTemporal[$i][periodo]."|".
                $vectorNotasTemporal[$i][anio]."|".
                $vectorNotasTemporal[$i][zona]."|".
//Modificada el 26/06/2013  para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                //$this->verificaNotaLaboratorio($vectorNotasTemporal[$i][zonalaboratorio],$vectorNotasTemporal[$i][carnet],
                //    $vectorNotasTemporal[$i][zona])."|".
                "0|".
                $vectorNotasTemporal[$i][examenfinal]."|0|".
                $vectorNotasTemporal[$i][problema];
        }//Fin del for

        //BEGIN TRANSACTION
        $transaccion_ok=1;
//         $rst_transaccion = $_SESSION["sNotas"]->query("begin");

        $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->begin());


        //borrar las datos correspondientes a tempnotas
//     $query = sprintf("delete from ingresotemporal
//                       where curso = '%s' and seccion ='%s'
//                       and periodo = '%s' and anio = %d;",
//                       $this->mCurso,$this->mSeccion,$this->mPeriodo,$this->mAnio);

        $query = $this->gsql_IA->Verificaciones_delete1($this->mCurso,$this->mCarrera/*$this->mSeccion*/,$this->mPeriodo,$this->mAnio);

        $_SESSION["sNotas"]->query($query);

//$old_error_handler  = set_error_handler("Maneja_Error_Copy");
        $result = pg_copy_from($_SESSION["sNotas"]->Link_ID,"tbtempentry",$Nvector,"|");
//restore_error_handler();

        if (!$result)  {  $transaccion_ok = 0;  }

        if ($transaccion_ok==0)
        {  //ROLLBACK TRANSACTION
//        $rst_transaccion = $_SESSION["sNotas"]->query("rollback");

            $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->rollback());

        }
        else
        {  //COMMIT TRANSACTION
//              $rst_transaccion = $_SESSION["sNotas"]->query("commit");

            $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->commit());

        }

        //END TRANSACTION
//         $rst_transaccion = $_SESSION["sNotas"]->query("end");

        $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->end());


    }//Fin de Verificaciones


    // *******************************************************************
    //                                                 VerificaDatosArchivo
    // *******************************************************************
    function VerificaDatosArchivo()
    {
        global $ErrorExisteDoble;

        if ($this->LevantaTemporal() == 1)
        {
            $this->CambiaCarne();
            $this->Verificaciones();
        }

        return $this->mErrorEncontrado;
    } //Fin de VerificaDatosArchivo

    // *******************************************************
    //         VerificaDatos_2
    // verificacion para los cursos que no llevan notas
    // $param_num numero de filas
    // $param_notas arreglo con los datos del archivo
    // $param_archivo tamanio del archivo que se cargo
    // *******************************************************
    function VerificaDatos_2($param_num,$param_notas,$param_archivo)
    {
        global $vectorNotasTemporal;
        global $ErrorExisteDoble;

        $ErrorExisteDoble = 0;
        $noexisten = 0;
        $ubicaciondoble = 0;
        for ($c=1; $c < $param_num; $c++)
        {
            list($card,$name,$final) = split(",",$param_notas[$c]);
            $laboratorio = 0;
            $zona        = 0;
            $final       = trim($final);
            $cantdigitos = strlen($card);
            // ----------modificacion realizada para el carnet
            switch($cantdigitos)
            {
                case 9: /*carne bueno */ break;
                case 7: $subcadena = substr($card, 0, 2);
                    $factible = $subcadena + 0;
                    if ($factible < 98)      //aqui preguntar si los primeros dos digitos son < 98
                    {  $card = '00'.$card;  }
                    else              //si son mayores a 98 entonces agregar 19
                    {  $card = '19'.$card;  }
                    break;
                default: $faltan = 9 - $cantdigitos;
                for($i=0; $i < $faltan; $i++) $card = '0'.$card;
            }//Fin del switch

            //Se le coloca el valor correspondiente sin son APR = -3, REP = -4
            $valor = strtoupper($final);
            switch( $valor )
            {
                case '1':   $final = -3; break;
                case '2':   $final = -4; break;
                case 'APR': $final = -3; break;
                case 'REP': $final = -4; break;
            }
            //echo "final -* - $final <br>";

            //aqui se verifica que el carne se encuentre en asignacion y asignaciondetalle
            //sino no se traslada a ingresotemporal, ademas para los periodos de retrasada
            //se le coloca a la zona y el laboratorio los valores de sus semestres respectivos
            $banderaencontrado = -1;
            for ($j=0; $j < $_SESSION["sNotas"]->num_rows(); $j++)
            {
                $_SESSION["sNotas"]->Row = $j;

                $_SESSION["sNotas"]->next_record();
                $vectasig1[usuarioid]       = trim($_SESSION["sNotas"]->f('idstudent'));
                $vectasig1[carrera]         = trim($_SESSION["sNotas"]->f('idcareer'));
                $vectasig1[zona]            = trim($_SESSION["sNotas"]->f('classzone'));
                $vectasig1[zonalaboratorio] = trim($_SESSION["sNotas"]->f('labnote'));

                if (strcmp($vectasig1[usuarioid], $card) == 0 )
                {
                    $banderaencontrado = 1;
                    $carrera = trim($vectasig1[carrera]);
                    $laboratorio = 0;  //asigno el laboratorio
                    $zona        = 0; //asigno la zona
                    break;
                }
            }//Fin del for


            // Si los valores son encontrados se va guardando en un vector temporal para luego
            // ser insertado en la tabla ingresotemporal
            if ($banderaencontrado == 1 )
            {
                $numero = is_numeric($final);
                if ($numero)
                {
                    $convfinal = 0;
                    $convfinal = 0 + $final;
                }
                else  {  $convfinal = $final;  }

                $notas[$c-1] = $this->mUsuarioid."|".$card."|".$carrera."|".$this->mCurso."|".$this->mIndex."|".
                    $this->mSeccion."|".$this->mPeriodo."|".$this->mAnio."|".$zona.
                    "|".$laboratorio."|".$convfinal."|0|0";

                //echo " -*-*- ".$notas[$c-1]."<br>";  die;

                //busca que no sea un registro repetido
                $valor = -5;
                $banderaduplicado = 0;
                for ($r = 0; $r < $c-1; $r++)
                {
                    if ($valor != 1)
                    {
                        $valor =  substr_count($notasfinales[$r], $card); //cuenta cuantas veces aparece
                        if ($valor > 0)   //si lo encontro al menos una vez
                        {
//                     $tempdup = substr($notasfinales[$r],0,strlen($notasfinales[$r])-1);
//                     $tempdup = $tempdup."8003,".$carrera;
//                     $notasfinales[$r] = "";
//                     $notasfinales[$r] = $tempdup;
                            $valor = -5;
                            $banderaduplicado = 1;
                            $ErrorExisteDoble = 1;
                            $ubicaciondoble = $c+1;
                            //   echo "entro con ".$card."<br>";
                        }
                    }
                } //Fin del For

                if ($banderaduplicado == 0)
                {
                    $notasfinales[$c-1] = $notas[$c-1];
                    // echo "notas finales ".$notasfinales[$c-1]." <br>";
                }

                $banderaencontrado = -1;
            }//fin del if de encontrado
            else  {  $noexisten++;  }
        }//Fin del for
//echo "<br>vector --",$notasfinales[0][$card],"-- no existen <<",$noexisten,">>";
        //Si existe en el archivo alguna tupla repetida se descarta de una vez el archivo

//	  echo  "error --- ".$ErrorExisteDoble."<br>";   die;
        if ($ErrorExisteDoble == 0)
        {
            //cuando hay registros agregados hay que verificar cuantos son dependiendo el porcentaje se desecha el archivo
            $porcentaje = $this->mAsignados * 0.50;

            if (($noexisten < $porcentaje)&&($notasfinales != null))
            {
                $transaccion_ok = 1;
//           $transaccion_ok = $_SESSION["sNotas"]->query("begin");

                $transaccion_ok = $_SESSION["sNotas"]->query($this->gsql_IA->begin());

                $registros = $this->VerificaDatosTabla();

                /*		   $nf = count($notasfinales);
                        for( $x=0; $x < nf; $x++)
                           {
                               echo $notasfinales[$x]."<br>";
                           }

                                       print_r($notasfinales);

                        die; */

                //Manejo de errores en la carga del vector a la tabla
//         $old_error_handler  = set_error_handler("Maneja_Error_Copy");
                $resultado = pg_copy_from($_SESSION["sNotas"]->Link_ID,"tbtempentry", $notasfinales,"|");
//         restore_error_handler();
                if ($resultado)
                {  //COMMIT TRANSACTION
                    //echo "entro por aca <br>";
//              $rst_transaccion = $_SESSION["sNotas"]->query("commit");

                    $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->commit());


                    //echo "hasta aqui solo los q estan en archivo"; die;
                    // Muestra resultados
                    if ($this->upload_report($registros,$param_archivo) == 1)  {  return 1;         }
                    else {  return 0;  }
                }
                else
                {  // Ha ocurrido algún error. Recupera el backup de la tabla de notas
                    //ROLLBACK TRANSACTION
//              $rst_transaccion = $_SESSION["sNotas"]->query("rollback");

                    $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->rollback());

                    $this->mStringMensaje = "No fu&eacute; posible cargar el archivo<br>
                                               por favor revise la informaci&oacute;n y vuelva a intentarlo.";
                    return 0;
                } //END TRANSACTION
//                 $rst_transaccion = $_SESSION["sNotas"]->query("end");

                $rst_transaccion = $_SESSION["sNotas"]->query($this->gsql_IA->end());

            }
            else
            {
                $this->mStringMensaje = "No coinciden los alumnos del archivo con los asignados<br>
                                    revise que el archivo realmente sea el correcto<br>
                                    y vuelva a intentarlo.";
                return 0;
            }
        }
        else
        {
            $this->mStringMensaje = "Existen registros repetidos.<br>Verificar la l&iacute;nea : ".$ubicaciondoble;
            return 0;
        }//Fin de if de existe tupla repetida en el archivo

    }//Fin del la funcion VerificaDatos_2
    // ********************************************
    // Validacion de Archivo de Ingreso de Notas
    // Devuelve los Datos de un curso en específico
    // para los cursos que no llevan nota como las practicas
    // ********************************************
    function ValidaArchivo_2($param_num,$param_notas,$param_archivo)
    {
        global $ErrorExisteDoble;
        $continuar = 0;

        //Verifica que el archivo contenga el encabezado correspondiente
        list($card,$name,$laboratorio,$zona,$final) = split(",",$param_notas[0]);
        $card = trim(strtoupper($card));
        switch ($card) {
            case 'CARNE'  :
            case 'CARNÉ'  :
            case 'CARNET' : break;
            default       : $this->mStringMensaje = "El archivo no es delimitado por comas o <br>
                                                         no contiene el encabezado correspondiente :<br>
                                                         Carnet,Nombre del Estudiante,Laboratorio 100%,Zona 75%,Final 25%";
            return 0;
            break;
        }

        for ($d=1; $d<$param_num; $d++)
        {
            list($card,$name,$final) = split(",",$param_notas[$d]);
            $laboratorio = 0;
            $zona = 0;
            $final = trim($final);
            $nolinea = $d + 1;

//		echo "es el curso : $this->mCurso <br>";
//		echo "lleva nota el curso : $this->mCursoSinNota <br>";		die;

            if ( $this->VerificaDecimalNumero($laboratorio,$zona,$final) == 1 ) {
                $aux  = $laboratorio + 0;
                $zona = $zona + 0;
                if ($this->VerificaExamenFinal($final) == 1) {
                }
                else {
                    $this->mStringMensaje = "No fue posible cargar la informaci&oacute;n.<br> Revise la L&iacute;nea N&uacute;mero : ".$nolinea."<br>
                                       Verifique lo siguiente<br>
                                       *  Que los valores de la nota final esten entre 1 (APR) y 2 (REP).";
                    return 0;
                }
            }
            else {
                $this->mStringMensaje = "No fue posible cargar la informaci&oacute;n.<br> Revise la L&iacute;nea N&uacute;mero : ".$nolinea."<br>
                                   Verifique lo siguiente<br>
                                   *  Que los valores sean los indicados<br>
                                   *  Que el archivo no contenga decimales<br>
                                   *  Que los valores de la nota final esten entre 1 (APR) y 2 (REP).";
                return 0;
            }
        }//Fin del for


        if ( $this->VerificaPeriodo() == 1 )
        {
        }
        else
        {
            $this->mStringMensaje = "No se puede obtener informaci&oacute;n del curso.";
            return 0;
        }

        if ( $this->VerificaDatos_2($param_num,$param_notas,$param_archivo) == 1 ) {
        }
        else {
            return 0;
        }

        return 1;
    }//Fin de Función ValidaArchivo_2



} //Fin de Clase ingresonota

?>
