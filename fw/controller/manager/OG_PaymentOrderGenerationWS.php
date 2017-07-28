<?php
//include("../../path.inc.php");
include("path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");

/* 
 * Incluyendo archivo con sentencias SQL 
 */
include_once("$dir_portal/fw/model/sql/OG_PaymentOrderGenerationWS_SQL.php");
include_once("$dir_biblio/biblio/librerias_externas/array2xml.class.php");
include_once("$dir_biblio/biblio/librerias_externas/class.xml_a_array.inc.php");
//include_once("$dir_portal/libraries/biblio/librerias_externas/nusoap/WS/lib/nusoap.php");
//include_once("$dir_portal/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/controller/ControlCourse.php");

class OG_PaymentOrderGenerationWS
{
    var $numCursosAsignar;
    var $cursosAsignar;
    var $numeroOrdenes;
    var $listaOrden;
    var $datosOrden;
    var $detalleOrden;
    var $numerodetalle;

    /*
     * Variable para utilizar las consultas
     */
    var $gsql;


    function OG_PaymentOrderGenerationWS()
    {// Se realiza una conexion con Servidor de Base de datos
        $_SESSION["sConVerPago"] = NEW DB_Connection();
        $_SESSION["sConVerPago"]->connect();

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new OG_PaymentOrderGenerationWS_SQL();

        $this->numeroOrdenes = 0;
    }


    function _verifica_en_historial($arreglo_datos)
    {
        $respuesta = FALSE;
        //str_pad($arreglo_datos[CONFIRMACION_PAGO][CARNET], LONGITUD_CARNET, DIGITO_RELLENO, STR_PAD_LEFT)
        //str_pad($arreglo_datos[CONFIRMACION_PAGO][CARRERA], LONGITUD_CARRERA, DIGITO_RELLENO, STR_PAD_LEFT)
        $query = $this->gsql->_verifica_en_historial_select1($arreglo_datos[CONFIRMACION_PAGO][CARNET],
            $arreglo_datos[CONFIRMACION_PAGO][ID_ORDEN_PAGO],
            $arreglo_datos[CONFIRMACION_PAGO][CARRERA]);

//printf( "busqueda pago :::%s... <br>",$query);
        if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->num_rows() > 0)) {
            $_SESSION["sConVerPago"]->next_record();
            $nuevaOrden = $_SESSION["sConVerPago"]->r();
//	   print_r ($nueva_orden);
//	   print "<br>";
            $rubro = 102;
            $horaVerificacion = strftime("%H:%M:%S"); // se obtiene la hora de verifiacion del pago
            $sql = $this->gsql->_verifica_en_historial_insert1($nuevaOrden['idstudent'], $nuevaOrden['idcareer'], $nuevaOrden['paymentorder'],
                $nuevaOrden['paymentorderdate'], $nuevaOrden['idpaymenttype'], $arreglo_datos[CONFIRMACION_PAGO][NO_BOLETA_DEPOSITO],
                $arreglo_datos[CONFIRMACION_PAGO][FECHA_CERTIF_BCO], $rubro,
                $arreglo_datos[CONFIRMACION_PAGO][BANCO], $nuevaOrden['amount'], $nuevaOrden['year'], $nuevaOrden['idschoolyear'],
                $nuevaOrden['verifier'], $nuevaOrden['paymentordertime'], $horaVerificacion
            );

//printf( "orden pago :::%s... <br>",$sql);
//       $respuesta = 0;
            if (($_SESSION["sConVerPago"]->query($sql)) AND ($_SESSION["sConVerPago"]->affected_rows() > 0)) {
                $respuesta = TRUE;
            }
        }
        return $respuesta;
    }


    function actualizaOrdenPago($result)
    {  // debe de acutilizarse la infomacion del recibo pago, los siguientes campos
        //  numero de recibo pagoado
        // fecha de pago
        // banco en donde se realizo la transaccion
        //print htmlspecialchars($result, ENT_QUOTES).':::<br>';
        $arreglo_datos = (array) simplexml_load_string($result);
        $objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);
        $resultoption=$objControlCourse->searchOrdenWS($arreglo_datos['ID_ORDEN_PAGO']);
        echo $resultoption[0]['estatus'];
        if($resultoption[0]['estatus'] == '2' || $resultoption[0]['estatus'] == '3')
        {
            //copiar orden y detalle de tborder y tborderdetail1
            $Restaurar=$objControlCourse->restaurarOrden($arreglo_datos['ID_ORDEN_PAGO']);
        }
        $horaVerificacion = strftime("%H:%M:%S"); // se obtiene la hora de verifiacion del pago

        $query = $this->gsql->actualizaOrdenPago_update1($arreglo_datos['NO_BOLETA_DEPOSITO'],
            $arreglo_datos['FECHA_CERTIF_BCO'],
            $arreglo_datos['BANCO'],
            $arreglo_datos['TIPO_PETICION'],
            $horaVerificacion,
            //str_pad($arreglo_datos[CONFIRMACION_PAGO][CARNET], LONGITUD_CARNET, DIGITO_RELLENO, STR_PAD_LEFT),
            $arreglo_datos['CARNET'],
            $arreglo_datos['ID_ORDEN_PAGO'],
            //str_pad($arreglo_datos[CONFIRMACION_PAGO][CARRERA], LONGITUD_CARRERA, DIGITO_RELLENO, STR_PAD_LEFT));
            $arreglo_datos['CARRERA']);
//echo $query;
//print $query . "<br>";
        if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->affected_rows() > 0)) {
            $respuesta = 1;
            $mensajeResp = 'EXITO';
        }       //  FUE UN EXITO EL PROCESO DE ACTUAILZACION DE LA ORDEN DE PAGO
        else {
            //$respuesta = $this->_verifica_en_historial($arreglo_datos);
  	        $respuesta = 0;
                $mensajeResp = 'FRACASO';
        }  //  FRACASO LA ACTUALIZACION DE LA ORDEN DE PAGO
        //$objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);
         if($resultoption[0]['estatus'] == '1' || $resultoption[0]['estatus'] == '2')
        {
             //echo 'entro bien';
             $schoolyear = $objControlCourse->getSchoolyearOrder($arreglo_datos['ID_ORDEN_PAGO']);
             echo $schoolyear[0]['periodo'];
             if($schoolyear[0]['periodo'] == '102' || $schoolyear[0]['periodo'] == '202' || $schoolyear[0]['periodo'] == '103' || $schoolyear[0]['periodo'] == '203')
             {
                //echo 'retra';
                $resultDetalleOrden=$objControlCourse->insertProcesoAsignacion($arreglo_datos['ID_ORDEN_PAGO']); // Consulta para obtener los cursos de la orden obtenida
             }
             else if ($schoolyear[0]['periodo'] == '101' || $schoolyear[0]['periodo'] == '201')
             {
                // echo 'vacas ';
                 $resultDetalleOrden=$objControlCourse->insertProcesoAsignacionVacas($arreglo_datos['ID_ORDEN_PAGO']); // Consulta para obtener los cursos de la orden obtenida
             }
        }
        else
        {
            //echo 'entro mal';
        }
        //foreach ($resultDetalleOrden as $cursodet) { 
            //Iteracion para crear variable que se envia a tpl (REVISAR +=)
            //$cursosOrden="-c".$cantidadDetalle."_".$cursodet['idcourse']; // En javascript se reemplaza - y _
            //$idCourse = array('idcourse' => $cursodet['idcourse']);
            //$cursosPagados[]=$idCourse;
            //$cantidadDetalle++;
        //}
        $arreglo_respuesta =   sprintf("<RESP_CONFIRMACION>
                                    <STATUS>%s</STATUS>
                                    <MSG>%s</MSG>
                                </RESP_CONFIRMACION>",$respuesta,$mensajeResp);
        return $arreglo_respuesta;
        //return $query;
    } // fin de la funcion actualizaOrdenPago

    function _actualizaOrdenPago_NOCANCELADAS()
    {  // debe de acutilizarse la infomacion del recibo pago, los siguientes campos
        //  tipopago

        for ($pos = 0; $pos < $_SESSION['NUMERO_ORDENES_NO_CANCELADAS']; $pos++) {//$query = sprintf(" UPDATE recibopagoWS SET tipopago = '800'
//                        WHERE ordenpago = '%s' AND fechaordenpago = '%s'
//				      ",$_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['orden'],$_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['fecha']
//					 );	

            $query = $this->gsql->_actualizaOrdenPago_NOCANCELADAS_update1($_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['orden'], $_SESSION['ORDENPAGO_NO_CANCELADA'][$pos]['fecha']
            );

//print $query."<br>";					 
            if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->affected_rows() > 0)) {
                $respuesta = 1;
            }       //  FUE UN EXITO EL PROCESO DE ACTUAILZACION DE LA ORDEN DE PAGO
            else {
                $respuesta = 0;
            }  //  FRACASO LA ACTUALIZACION DE LA ORDEN DE PAGO
        }
        return $respuesta;
    } // fin de la funcion actualizaOrdenPago

    function obtenerSubtotal($numeroHoras, $tipo)
    {
        switch ($_SESSION["datosGenerales"]->periodo) {
            case VACACIONES_DEL_PRIMER_SEMESTRE:
            case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                switch ($tipo) {
                    case 0:
                        $monto = VALOR_INSCRIPCION;
                        break;
                    case 1:
                        switch ($numeroHoras) {
                            case 2:
                                $monto = VALOR_CURSO_DOS_HRS;
                                break;
                            case 4:
                                $monto = VALOR_CURSO_CUATRO_HRS;
                                break;
                        }
                        break;
                    case 2:
                        if ($_SESSION['valor_laboratorio'] > 0) {
                            $monto = $_SESSION['valor_laboratorio'];
                        } else {
                            $monto = VALOR_LABORATORIO;
                        }
                        break;
                    case 3:
                        $monto = $_SESSION['montoGeneral'];
                        break;
                }
                break;
            case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
            case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $monto = VALOR_PRIMERA_RETRASADA;
                break;
            case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
            case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $monto = VALOR_SEGUNDA_RETRASADA;
                break;
        }
        return $monto;
    }

    function obtenerVariante($variante)
    {
        switch ($variante) {
            case 1:
                $dato = 'INSCRIPCION';
                break;
            case 2:
                $dato = 'CURSO';
                break;
            default:
                $dato = '';
                break;
        }
        return $dato;
    }

    function obtenerTipocurso($tipo)
    {
        switch ($tipo) {
            case 1:
                $dato = 'CURSO';
                break;
            case 2:
                $dato = 'LABORATORIO';
                break;
            default:
                $dato = '';
                break;
        }
        return $dato;
    }

    function obtenerIDrubro($periodo)
    {
        switch ($periodo) {
            case VACACIONES_DEL_PRIMER_SEMESTRE                  :
                $rubro = 2;
                break;  // rubro para esc. de vacaciones junio con  id_variante_rubro=2
            case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE :
                $rubro = 4;
                break;  // rubro para primera retrasada con  id_variante_rubro=1 -- primer semestre
            case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE :
                $rubro = 5;
                break;  // rubro para primera retrasada con  id_variante_rubro=1 -- primer semestre
            case VACACIONES_DEL_SEGUNDO_SEMESTRE              :
                $rubro = 3;
                break;  // rubro para esc. de vacaciones diciembre con  id_variante_rubro=2
            case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $rubro = 6;
                break;  // rubro para primera retrasada con  id_variante_rubro=2 -- segundo semestre
            case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $rubro = 7;
                break;  // rubro para primera retrasada con  id_variante_rubro=2 -- segundo semestre
        }
        return $rubro;
    }

    function _verificacionOdenCancelada($tipo)
    {
        if (!$_SESSION['ORDEN_EXISTENTE']) {
            return $tipo;
        } else {
            return 3;
        }
    }

    function _generarBloqueDetalleOrdenPago()
    {
        $tipo = 0;
        $valorMonto = $_SESSION['montoGeneral'];
        $XML_peticion = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
        if ((strcmp($_SESSION["datosGenerales"]->periodo, VACACIONES_DEL_PRIMER_SEMESTRE) == 0) OR
            (strcmp($_SESSION["datosGenerales"]->periodo, VACACIONES_DEL_SEGUNDO_SEMESTRE) == 0)
        ) // SE VERIFICA QUE ESTE BLOQUE SE EJECUTE SOLO PARA PERIODO DE VACACIONES
        {
            $variante = 1; // variante de INSCRIPCION
            $detalleOrdenPago = array('DETALLE_ORDEN_PAGO' => array(
                'ANIO_TEMPORADA' => $_SESSION["datosGenerales"]->anio,
                'ID_RUBRO' => $this->obtenerIDrubro($_SESSION["datosGenerales"]->periodo),
                'ID_VARIANTE_RUBRO' => $variante,
                'TIPO_CURSO' => $this->obtenerTipocurso($tipo),
                'CURSO' => NULL,
                'SECCION' => NULL,
                'SUBTOTAL' => $this->obtenerSubtotal(NULL, $tipo)
            )
            );
            if (!$_SESSION['ORDEN_EXISTENTE']) {
                if (!$_SESSION['marcaInscripcion']) {
                    $XML_peticion->array_xml($detalleOrdenPago);
                }
            }// convertinos a XML
        } // fin del bloque generacion de inscripcion

        //    for($pos=1;$pos <= $this->numCursosAsignar;$pos++)
        /*
        if (strcmp($_SESSION["datosGenerales"]->periodo,'02') == 0)
        {
         printf("numero de cursos asignacdos:::%d<br>",$this->numCursosAsignar);
        }
            */
//printf("numero de cursos asignacdos:::%d<br>",$this->numCursosAsignar);	
        for ($pos = 1; $pos <= $this->numCursosAsignar; $pos++) {
            $tipo = 1;
            $_SESSION['valor_laboratorio'] = 0;

            if ((strcmp($_SESSION["datosGenerales"]->periodo, PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE) == 0) OR
                (strcmp($_SESSION["datosGenerales"]->periodo, PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE) == 0) OR
                (strcmp($_SESSION["datosGenerales"]->periodo, SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE) == 0) OR
                (strcmp($_SESSION["datosGenerales"]->periodo, SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE) == 0)
            ) {//if ($this->cursosAsignar[$pos]['mEstadoAsignar']==10) {$gestion=TRUE;}

                if (($this->cursosAsignar[$pos]['mEstado'] == 1)) // AND ($this->cursosAsignar[$pos]['mEstadoAsignar'] > 1))
                {
                    $gestion = TRUE;
                } else {
                    $gestion = FALSE;
                }
            } else {
                $gestion = TRUE;
            }

//printf("estado...(%d)::::(%d)...gestion(%d)<br>",$this->cursosAsignar[$pos]['mEstado'],$this->cursosAsignar[$pos]['mEstadoAsignar'],$gestion);

            switch ($_SESSION["datosGenerales"]->periodo) {
                case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:                       // rubro para primera retrasada con  id_variante_rubro=1 -- primer semestre
                case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:                      // rubro para primera retrasada con  id_variante_rubro=1 -- primer semestre
                case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:                      // rubro para primera retrasada con  id_variante_rubro=2 -- segundo semestre
                case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                    $variante = 1;
                    break; // rubro para primera retrasada con  id_variante_rubro=2 -- segundo semestre
                case VACACIONES_DEL_PRIMER_SEMESTRE:                                       // rubro para esc. de vacaciones junio con  id_variante_rubro=2
                case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                    $variante = 1;
                    break;         // rubro para esc. de vacaciones diciembre con  id_variante_rubro=2
            }

//	  $variante=2; // variante de CURSO
//print $this->cursosAsignar[$pos]['marca']."::::marca(".$pos."):::".$_SESSION['marcaInscripcion']."...<br>";
//print "usuario:: ".$_SESSION["datosGenerales"]->usuarioid." periodo::: ".$_SESSION["datosGenerales"]->periodo."<br>";	  
            $_SESSION['valor_laboratorio'] = $this->cursosAsignar[$pos]['montoLaboratorio'];
            $detalleOrdenPago = array('DETALLE_ORDEN_PAGO' => array(
                'ANIO_TEMPORADA' => $_SESSION["datosGenerales"]->anio,
                'ID_RUBRO' => $this->obtenerIDrubro($_SESSION["datosGenerales"]->periodo),
                'ID_VARIANTE_RUBRO' => $variante,
                'TIPO_CURSO' => $this->obtenerTipocurso($tipo),
                'CURSO' => $this->cursosAsignar[$pos]['curso'],
                'SECCION' => trim($this->cursosAsignar[$pos]['seccion']),
                'SUBTOTAL' => $this->obtenerSubtotal($this->cursosAsignar[$pos]['numHoras'], $this->_verificacionOdenCancelada($tipo))
            )
            );
//print_r ($detalleOrdenPago);
            /*
            //if (strcmp($_SESSION["datosGenerales"]->periodo,'06') == 0)	/
            {
            printf ("marca de benida:::%d<br>",$this->cursosAsignar[$pos]['marca']);
            // print_r ($detalleOrdenPago);
            }
            */
//printf("<br>(%d) gestion:::%s<br> ",$pos,$gestion);
//printf ("marca de curso:::: %d<br>",$this->cursosAsignar[$pos]['marca']); 
//printf ("marca de Estado:::: %d<br>",$this->cursosAsignar[$pos]['mEstado']); 
//printf ("marca de Estado:::: %d<br>",$this->cursosAsignar[$pos]['mEstadoAsignar']); 
//printf ("marca de Genera:::: %d<br>",$this->cursosAsignar[$pos]['mEstadoGenera']); 

            if ($gestion) {
                switch ($_SESSION["datosGenerales"]->periodo) {
                    case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:                       // rubro para primera retrasada con  id_variante_rubro=1 -- primer semestre
                    case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:                        // rubro para primera retrasada con  id_variante_rubro=1 -- primer semestre
                    case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:                      // rubro para primera retrasada con  id_variante_rubro=2 -- segundo semestre
                    case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:                      // rubro para primera retrasada con  id_variante_rubro=2 -- segundo semestre
                        if (!$this->cursosAsignar[$pos]['marca']) {//if ($this->cursosAsignar[$pos]['mEstadoGenera'] == 0)
                            $XML_peticion->array_xml($detalleOrdenPago);
                            $valorMonto = $valorMonto - $this->obtenerSubtotal($this->cursosAsignar[$pos]['numHoras'], 0);
                        }
                        break;
                    case VACACIONES_DEL_PRIMER_SEMESTRE:                                       // rubro para esc. de vacaciones junio con  id_variante_rubro=2
                    case VACACIONES_DEL_SEGUNDO_SEMESTRE:                                   // rubro para esc. de vacaciones diciembre con  id_variante_rubro=2
                        $XML_peticion->array_xml($detalleOrdenPago);
                        $valorMonto = $valorMonto - $this->obtenerSubtotal($this->cursosAsignar[$pos]['numHoras'], $this->_verificacionOdenCancelada($tipo));
                        break;
                }
            }

//print $this->cursosAsignar[$pos]['retUnica'] . ":::(retra:::<br>";	  
//print $this->cursosAsignar[$pos]['marcalab']."::::marcalab (".$pos."):::<br>";
            if (!$_SESSION['ORDEN_EXISTENTE']) {
                if ($this->cursosAsignar[$pos]['retUnica']) {
                    $tipo = 2;
                    $_SESSION['valor_laboratorio'] = $this->cursosAsignar[$pos]['montoLaboratorio'];
                    $detalleOrdenPagoLab = array('DETALLE_ORDEN_PAGO' => array(
                        'ANIO_TEMPORADA' => $_SESSION["datosGenerales"]->anio,
                        'ID_RUBRO' => $this->obtenerIDrubro($_SESSION["datosGenerales"]->periodo),
                        'ID_VARIANTE_RUBRO' => $variante,
                        'TIPO_CURSO' => $this->obtenerTipocurso($tipo),
                        'CURSO' => trim($this->cursosAsignar[$pos]['curso']),
                        'SECCION' => $this->cursosAsignar[$pos]['seccion'],
                        'SUBTOTAL' => $this->obtenerSubtotal($this->cursosAsignar[$pos]['numHoras'], $tipo)
                    )
                    );
                    $XML_peticion->array_xml($detalleOrdenPagoLab);
                } else {
                    $peticionXML2 = '';
                    $detalleOrdenPagoLab = '';
                }
            } else {
                $peticionXML2 = '';
                $detalleOrdenPagoLab = '';
            }
            if ($valorMonto == 0) {
                break;
            }
//---	 if ($_SESSION['ORDEN_EXISTENTE'])
//---	  { break; }
        }
        $peticion = $XML_peticion->XMLtext;
//print 	$XML_peticion->XMLtext."::::(XML_Peticion<br>";
        unset ($XML_peticion);
        return $peticion;
    } // fin de funcion  generarBloqueDetalleOrdenPago

    function _crearVectorSolicitarOrden()
    {
//print $_SESSION['montoGeneral'].":::monto<br>";  
        $XML_peticion = new multidi_array2xml(); // objeto desde donde generamos un string XML desde un arreglo que sera retornado
        $orden_pago_solicitada = array('CARNET' => 0 + $_SESSION["datosGenerales"]->usuarioid,
            'UNIDAD' => UNIDAD_ACADEMICA,
            'EXTENSION' => EXTENSION_ACADEMICA,
            'CARRERA' => 0 + $_SESSION["datosGenerales"]->carrera,
            'NOMBRE' => $_SESSION["datosGenerales"]->nombreEstudiante, // . " -- PRUEBA INGENIERIA --",
            'MONTO' => $_SESSION['montoGeneral']
        );

//-------    print_r ($vector_generar_XML);
        $peticionXML = '<GENERAR_ORDEN>' . $XML_peticion->array_xml($orden_pago_solicitada) . $this->_generarBloqueDetalleOrdenPago() . '</GENERAR_ORDEN>'; // convertinos a XML

        unset ($XML_peticion);
        return $peticionXML;
    } // fin de funcion crearVectorSolicitarOrden

    function _verificaConexionServer($url)
    {/* Tiempo límite de espera entre la conexión de 10 segundos */
        $timeout = stream_context_create(array('http' => array('timeout' => 10)));

        /* Verifica si la url existe */
        if (@file_get_contents($url, 0, $timeout)) {
            return TRUE;
        } // SI EXISTE CONEXION
        else {
            return FALSE;
        } // NO EXISTE CONEXION
    }

    function _generarOrdenPago($peticionXML)
    {// $url = 'http://parnaso.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
        // $url = 'http://216.230.138.103/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
        // $url = 'http://10.0.0.18/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
        // cambio de direccion a partir de noviembre del 2009, por solicitud de procesamiento de datos
        // parnaso.usac.edu.gt ==> siif.usac.edu.gt
        //$url = 'https://www.ingenieria.usac.edu.gt/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
        //$url = 'http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?WSDL';
        //$url = 'http://localhost/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php';
        //$url = 'http://10.50.23.229/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php';
        /*
        if (strcmp($_SESSION["datosGenerales"]->periodo,'06') == 0)
        {

        print htmlspecialchars($peticionXML, ENT_QUOTES).'::::<br>';
        }
        */
        
        //print_r($peticionXML);print '<br>';  
        //print htmlspecialchars($peticionXML, ENT_QUOTES).'::::<br>';
        //die;
        
        $datosObtenidos =
            "<RESPUESTA>
            <CODIGO_RESP>800</CODIGO_RESP>
            <DESCRIPCION>INICIO EL PROCESO NO SE PUEDE REALIZAR EN ESTE MOMENT
            O</DESCRIPCION>
        </RESPUESTA>
                 ";
        /*
        if (strcmp($_SESSION["datosGenerales"]->periodo,'02') == 0)
        {
        print_r($peticionXML);print '<br>';
        print htmlspecialchars($peticionXML, ENT_QUOTES).'::::<br>';
        }
        */
//print_r($peticionXML);print '<br>';  
//print htmlspecialchars($peticionXML, ENT_QUOTES).':::<br>';
        if ($this->_verificaConexionServer(URL_CONEXION_WS)) // verifica CONEXION
        {
            try {
                
                try 
                { 
                    //ini_set("soap.wsdl_cache_enabled", "0"); // Set to zero to avoid caching WSDL
                    $soapClient = new SoapClient(URL_CONEXION_WS);     
                } 
                catch (Exception $e)
                { 
                    $v_msg_error = "No se pudo realizar la operacion [" . $e->getMessage() . "]";
                    $v_resultado_invoke = 0;
                    return;
                }
                //echo 'Corre Procedimieto :<br>';                                      
                try 
                {
                    $soapClient->__setLocation(LOCATION_URL);
                    $dato = (object)$soapClient->generarOrdenPago(array('pxml'=>utf8_encode($peticionXML)));
                    $RESPUESTA= array();
                    $RESPUESTA[] = json_decode(json_encode((array) simplexml_load_string($dato->result)),1);                    
                } 
                catch (Exception $e) 
                { 
                    $v_msg_error = "No se pudo realizar la operacion [" . $e->getMessage() . "]";
                    $v_resultado_invoke = 0;
                    return;
                }
                
            } catch (Exception $e)    //CONTROLAMOS LA EXCEPCIONES
            { //Si el mensaje de error es nuestro, lo limpiamos para verlo mejor
                /*	    $delimitador = '@@';
                        $e = $exception->faultstring;
                        $p = strpos($e, $delimitador);
                        if ($p !== false)
                         {   $q = strpos ($e, $delimitador,$p+2);
                             $sError = substr($e, $p+2, $q-$p-2);
                                    echo "ERROR: ",$sError,"<BR>";
                         }
                         else
                          {
                            print " ERROR GENERAL :::". $e ."<BR>";
                          }
                        die();
                    */
                //printf("Error:sendSms: %s\n", $e->__toString());
                $datosObtenidos =
                    "<RESPUESTA>
                        <CODIGO_RESP>800</CODIGO_RESP>
                        <DESCRIPCION>EXCEPTION EL PROCESO NO SE PUEDE REALIZAR EN ESTE MOMENTO</DESCRIPCION> 
                    </RESPUESTA>
                    ";
            }
        }
//    $xmlparser = xml_parser_create (); 
        // parse the data chunk / / Analizar la cantidad de datos
//    xml_parse($xmlparser,$resultadoObtenido);
//$codigo = xml_get_error_code($xmlparser);
//print $codigo."::::".xml_error_string($codigo).":::::<br>";
        /*	print "ERROR:"
             . xml_error_string(xml_get_error_code($xmlparser)) xml_error_string (xml_get_error_code ($ xmlparser))
             . "<br />" "<br />"
             . "Line: " "Line:"
             . xml_get_current_line_number($xmlparser) xml_get_current_line_number ($ xmlparser)
             . "<br />" "<br />"
             . "Column: " "Columna"
             . xml_get_current_column_number($xmlparser) xml_get_current_column_number ($ xmlparser)
             . "<br />"); "<br />";
            die;
            */
        /*
        if (strcmp($_SESSION["datosGenerales"]->periodo,'06') == 0)
        {
          print_r($resultadoObtenido);print '<br>';
          print htmlspecialchars($resultadoObtenido, ENT_QUOTES).'::::<br>';
        }
        */
//print_r($resultadoObtenido);print '<br>';		
//:::
//print htmlspecialchars($RESPUESTA[0], ENT_QUOTES).'::::<br>';  
        // ser convierte el XML Obtenido a un arreglo
        //echo 'RESULTADO+';
        //print htmlspecialchars($resultadoObtenido, ENT_QUOTES).'::::<br>';
        //die;
        //print_r($datosObtenidos);
//echo "<pre>";
//print_r ($datosObtenidos);// $arrayData[RESPUESTA][CODIGO_RESP]."::::".$arrayData[RESPUESTA][ID_ORDEN_PAGO]."<br>";
//echo "</pre>";  

        //unset ($wsdl);
        unset ($xmlObtenido);
        return $RESPUESTA[0]; // se retorna el arreglo ya convertido del XML de respuesta del WS de procesamiento de datos
    } 

    function  _obtieneTipoPago()
    {
        switch ($_SESSION["datosGenerales"]->periodo) {
            case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
                $tipopago = _TIPO_PAGO_PRIMERA_RETRASADA;
                break;                                              // tipo de pago para primera retrasada con  id_variante_rubro=1 -- primer semestre
            case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $tipopago = _TIPO_PAGO_PRIMERA_RETRASADA2;
                break; // tipo de pago para primera retrasada con  id_variante_rubro=2 -- segundo semestre
            case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
                $tipopago = _TIPO_PAGO_SEGUNDA_RETRASADA;
                break;                                               // tipo de pago para primera retrasada con  id_variante_rubro=1 -- primer semestre
            case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                $tipopago = _TIPO_PAGO_SEGUNDA_RETRASADA2;
                break;                           // tipo de pago para primera retrasada con  id_variante_rubro=2 -- segundo semestre
            case VACACIONES_DEL_PRIMER_SEMESTRE:
                $tipopago = _TIPO_PAGO_VACACIONES_JUNIO;
                break;                   // tipo de pago para esc. de vacaciones junio con  id_variante_rubro=2
            case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                $tipopago = _TIPO_PAGO_VACACIONES_DICIEMBRE;
                break;          // tipo de pago para esc. de vacaciones diciembre con  id_variante_rubro=2
        }
        return $tipopago;
    }


    function _actualizaBDD_InfoRecibida($info_recibida) //se recibe arreglo de la respuesta del servidor
    {
        $respuestaObtenida = FALSE;
        //echo 'FUNCION ACTUALIZAR BDD_INFO RECIBIDA';
        
        if ($info_recibida['CODIGO_RESP'] == 1) // respuesta exitosa de la peticion de la orden de pago
        {
            $tipopago = $this->_obtieneTipoPago();
            $_SESSION['ORDEN_PAGO']['numeroOrden'] = $info_recibida['ID_ORDEN_PAGO'];
            $_SESSION['ORDEN_PAGO']['carne'] = $info_recibida['CARNET'];
            $_SESSION['ORDEN_PAGO']['carrera'] = $info_recibida['CARRERA'];
            $_SESSION['ORDEN_PAGO']['Monto'] = $info_recibida['MONTO'];
            $_SESSION['ORDEN_PAGO']['fecha'] = $info_recibida['FECHA'];
            $_SESSION['ORDEN_PAGO']['identificador'] = $info_recibida['CHECKSUM'];
            $_SESSION['ORDEN_PAGO']['rubropago'] = $info_recibida['RUBROPAGO'];
            $_SESSION['ORDEN_PAGO']['verificador'] = $info_recibida['CHECKSUM'];

            switch ($_SESSION["datosGenerales"]->periodo) {
                case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
                case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
                case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
                    //$_SESSION['ORDEN_PAGO']['carne']= str_pad($_SESSION['ORDEN_PAGO']['carne'], LONGITUD_CARNET, DIGITO_RELLENO, STR_PAD_LEFT);
                    //$_SESSION['ORDEN_PAGO']['carrera']= str_pad($_SESSION['ORDEN_PAGO']['carrera'], LONGITUD_CARRERA, DIGITO_RELLENO, STR_PAD_LEFT);
                    $_SESSION['ORDEN_PAGO']['carne'] = $_SESSION['ORDEN_PAGO']['carne'];
                    $_SESSION['ORDEN_PAGO']['carrera'] = $_SESSION['ORDEN_PAGO']['carrera'];
                    $_SESSION['ORDEN_PAGO']['tipo'] = $tipopago;
                    $_SESSION['ORDEN_PAGO']['anio'] = $_SESSION["datosGenerales"]->anio;
                    $_SESSION['ORDEN_PAGO']['periodo'] = $_SESSION["datosGenerales"]->periodo;
                    $respuestaObtenida = TRUE;
                    /*
                      $this->_actualizaOrdenPago_NOCANCELADAS();
                      $query = sprintf(" INSERT INTO recibopagows (usuarioid,carrera,ordenpago,fechaordenpago,tipopago,rubropago,monto,anio,periodo,verificador)
                                           VALUES('%s','%s','%s','%s','%s','%s',%d,'%s','%s','%s');
                                       ",str_pad($_SESSION['ORDEN_PAGO']['carne'], LONGITUD_CARNET, DIGITO_RELLENO, STR_PAD_LEFT),
                                         str_pad($_SESSION['ORDEN_PAGO']['carrera'], LONGITUD_CARRERA, DIGITO_RELLENO, STR_PAD_LEFT),
                                         $_SESSION['ORDEN_PAGO']['numeroOrden'], $_SESSION['ORDEN_PAGO']['fecha'],$tipopago, $_SESSION['ORDEN_PAGO']['rubropago'],
                                         $_SESSION['ORDEN_PAGO']['Monto'], $_SESSION["datosGenerales"]->anio, $_SESSION["datosGenerales"]->periodo,
                                         $_SESSION['ORDEN_PAGO']['verificador']
                                      );
              //print $query . "::::<br>";
                      if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->affected_rows() > 0))
                        { $respuestaObtenida = TRUE; }	   //  FUE UN EXITO LA INSERCION DE LA ORDEN DE PAGO
                       else { $respuestaObtenida = FALSE; }  //  FRACASO LA INSERCION DE UNA NUEVA ORDEN DE PAGO
                    */
                    break;
                // MODIFICACIONES EXCLUSIVAMENTE PARA PROCESO DE ESCUELA DE VACACIONES
                // MODIFICADO EL 23 DE MAYO 2009 --- ROES ---
                case VACACIONES_DEL_PRIMER_SEMESTRE:
                case VACACIONES_DEL_SEGUNDO_SEMESTRE:
                    //$_SESSION['ORDEN_PAGO']['carne']= str_pad($_SESSION['ORDEN_PAGO']['carne'], LONGITUD_CARNET, DIGITO_RELLENO, STR_PAD_LEFT);
                    //$_SESSION['ORDEN_PAGO']['carrera']= str_pad($_SESSION['ORDEN_PAGO']['carrera'], LONGITUD_CARRERA, DIGITO_RELLENO, STR_PAD_LEFT);
                    $_SESSION['ORDEN_PAGO']['carne'] = $_SESSION['ORDEN_PAGO']['carne'];
                    $_SESSION['ORDEN_PAGO']['carrera'] = $_SESSION['ORDEN_PAGO']['carrera'];
                    $_SESSION['ORDEN_PAGO']['tipo'] = $tipopago;
                    $_SESSION['ORDEN_PAGO']['anio'] = $_SESSION["datosGenerales"]->anio;
                    $_SESSION['ORDEN_PAGO']['periodo'] = $_SESSION["datosGenerales"]->periodo;
                    $respuestaObtenida = TRUE;
                    break;
            }

        } else {
            $respuestaObtenida = FALSE;
            if($info_recibida != "")
                $_SESSION['ORDEN_PAGO']['descripcion'] = $info_recibida['DESCRIPCION'];
            else
                $_SESSION['ORDEN_PAGO']['descripcion'] ="SISTEMA DE GENERACION NO DISPONIBLE";
        } // HUBO ERROR EN LA INFORMACION ENVIADA
        return $respuestaObtenida;
    } //


    function procesoGeneracion($totCursosAsignar, $vectorCursos)
    {
        $this->numCursosAsignar = $totCursosAsignar;
        $this->cursosAsignar = $vectorCursos;
//printf("numero cursos (%d)::::%d<br>",$this->numCursosAsignar,$totCursosAsignar);	
        $respuestaObtenida = $this->_actualizaBDD_InfoRecibida($this->_generarOrdenPago($this->_crearVectorSolicitarOrden()));

//print "respuesta de insercion de Orden Pago:::::".$respuestaObtenida."<br>";
        return $respuestaObtenida;
    }  // fin de funcion procesoGeneracion

    function detalleOrdenesPago()
    {
        $tipopago = $this->_obtieneTipoPago();
        $query = $this->gsql->detalleOrdenesPago_select1($_SESSION["datosGenerales"]->usuarioid, $_SESSION["datosGenerales"]->carrera, $_SESSION["datosGenerales"]->periodo,
            $_SESSION["datosGenerales"]->anio, $tipopago);
//    if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->affected_rows() > 0))
        if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->num_rows() > 0)) {
//printf ("numero filas ::(%d)...numero afectadas :.(%d)....<br>",$_SESSION["sConVerPago"]->num_rows(),$_SESSION["sConVerPago"]->affected_rows());
            $this->numeroOrdenes = $_SESSION["sConVerPago"]->num_rows();
            for ($pos = 0; $pos < $this->numeroOrdenes; $pos++) {
                $_SESSION["sConVerPago"]->next_record();
                $this->listaOrden[$pos]['ordenpago'] = $_SESSION["sConVerPago"]->f('paymentorder');
                $this->listaOrden[$pos]['fechaorden'] = $_SESSION["sConVerPago"]->f('paymentorderdate');
                $this->listaOrden[$pos]['monto'] = $_SESSION["sConVerPago"]->f('amount');
                $this->listaOrden[$pos]['verificaws'] = $_SESSION["sConVerPago"]->f('verws');
                $this->listaOrden[$pos]['verificaCA'] = $_SESSION["sConVerPago"]->f('verca');
            }
        }

    }

    function obtieneInfoOrdenPago($OrdenPago)
    {
        $this->datosOrden = FALSE;
        $query = $this->gsql->obtieneInfoOrdenPago_select1($OrdenPago,
            $_SESSION["datosGenerales"]->periodo,
            $_SESSION["datosGenerales"]->anio,
            $_SESSION["datosGenerales"]->carrera,
            _TIPO_PAGO_PRIMERA_RETRASADA,
            _TIPO_PAGO_SEGUNDA_RETRASADA,
            _TIPO_PAGO_VACACIONES_JUNIO,
            _TIPO_PAGO_VACACIONES_DICIEMBRE,
            _TIPO_PAGO_PRIMERA_RETRASADA2,
            _TIPO_PAGO_SEGUNDA_RETRASADA2);

//print $query .":::::<br>";
        if (($_SESSION["sConVerPago"]->query($query)) AND ($_SESSION["sConVerPago"]->num_rows() > 0)) {
            $_SESSION["sConVerPago"]->next_record();
            $this->datosOrden['ordenpago'] = $_SESSION["sConVerPago"]->f('ordenpago');
            $this->datosOrden['carne'] = $_SESSION["sConVerPago"]->f('usuarioid');
            $this->datosOrden['unidad'] = $_SESSION["sConVerPago"]->f('unidad');
            $this->datosOrden['extension'] = $_SESSION["sConVerPago"]->f('extension');
            $this->datosOrden['carrera'] = $_SESSION["sConVerPago"]->f('carrera');
            $this->datosOrden['rubro'] = $_SESSION["sConVerPago"]->f('rubropago');
            $this->datosOrden['verificador'] = $_SESSION["sConVerPago"]->f('verificador');
            $this->datosOrden['nombreest'] = $_SESSION["sConVerPago"]->f('nombreest');
            $this->datosOrden['nombrecar'] = $_SESSION["sConVerPago"]->f('nombrecar');
            $this->datosOrden['monto'] = $_SESSION["sConVerPago"]->f('monto');
            $this->datosOrden['fechaordenpago'] = $_SESSION["sConVerPago"]->f('fechaordenpago');
            $this->datosOrden['checksum'] = $_SESSION["sConVerPago"]->f('checksum');
            //$this->datosOrden = TRUE;
        }
    }

    function obtieneDetalleOrden($orden, $fecha)
    {
        $this->detalleOrden = FALSE;
//    $sql = sprintf(" SELECT  rd.curso,c.nombre,rd.verlaboratorio as laboratorio
//                       FROM recibopagows_detalle rd,curso c
//					  WHERE rd.curso = c.curso
//					    AND rd.ordenpago = '%s'
//						AND rd.fechaordenpago = '%s';
//                   ",$orden,$fecha);

        $sql = $this->gsql->obtieneDetalleOrden_select1($orden, $fecha);

//print $query .":::::<br>";				   
        if (($_SESSION["sConVerPago"]->query($sql)) AND ($_SESSION["sConVerPago"]->num_rows() > 0)) {
            $this->numerodetalle = $_SESSION["sConVerPago"]->num_rows();
            for ($pos = 0; $pos < $this->numerodetalle; $pos++) {
                $_SESSION["sConVerPago"]->next_record();
                $this->detalleOrden[$pos]['curso'] = $_SESSION["sConVerPago"]->f('curso');
                $this->detalleOrden[$pos]['nombre'] = $_SESSION["sConVerPago"]->f('nombre');
                $this->detalleOrden[$pos]['laboratorio'] = $_SESSION["sConVerPago"]->f('laboratorio');
            }
        }
    }

    function rectificaPeriodo($periodo)
    {
        $_SESSION["datosGenerales"]->periodo = $periodo;
    }


} // fin de la clase   verificacionProcesoWS


?>
