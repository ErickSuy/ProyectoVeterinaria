<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 06/05/2015
 * Time: 08:34 AM
 */

include("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/sql/Transaction.php");


/*
 * Incluyendo archivo con sentencias SQL
 */
require_once("$dir_portal/fw/model/sql/AR_PaymentOrderGenerationRegisterManager_SQL.php");


class AR_PaymentOrderGenerationRegisterManager
{
    var $mUsuario;
    var $mCarrera;
    var $mTransaccion;
    var $mEncriptada;
    var $mfechains;
    var $mPeriodo;
    var $mAnio;
    var $gestion;
    var $carreraActual;
    var $fechaAsigna;
    var $numCurAsignar;
    var $conexionBDD;
    /*
    * Variable para utilizar las consultas
    */
    var $gsql;


    /* Constructor */
    public function AR_PaymentOrderGenerationRegisterManager()
    {
        $this->mUsuario = $_SESSION["datosGenerales"]->usuarioid;
        $this->mPeriodo = $_SESSION["datosGenerales"]->periodo;
        $periodo = 0 + $this->mPeriodo;
        switch ($periodo) {
            case 102:
            case 103:
                $this->mPerAnterior = PRIMER_SEMESTRE;
                break;
            case 202:
            case 203:
                $this->mPerAnterior = SEGUNDO_SEMESTRE;
                break;
        }
        $this->mAnio = $_SESSION["datosGenerales"]->anio;

        $this->conexionBDD = NEW DB_Connection();
        $this->conexionBDD->connect();

        /*
        * Instanciando la variable en la clase donde se encuentran las consultas
        */
        $this->gsql = new AR_PaymentOrderGenerationRegisterManager_SQL();

    }

    function procesaEncabezado()
    {
        $query_asig = $this->gsql->procesaEncabezado_select1($this->mUsuario, $this->mPeriodo, $this->mAnio, $this->carreraActual);

        if (($this->conexionBDD->query($query_asig)) AND ($this->conexionBDD->num_rows() >= 1)) {
            $this->conexionBDD->next_record();
            $transaccionRealizada = $this->conexionBDD->f('idassignation');
            $this->fechaAsignacionRealizada = $this->conexionBDD->f('assignationdate');
            $query_del = $this->gsql->procesaEncabezado_delete1($this->mPeriodo, $this->mAnio, $transaccionRealizada, $this->fechaAsignacionRealizada);

//printf("2::%s<br>",$query_del);
            if (!$this->conexionBDD->query($query_del)) {
                $this->gestion = 0;
            } // se aborta proceso fallo eliminacion de asignacion
            else {
                $query_del = $this->gsql->procesaEncabezado_delete2($transaccionRealizada, $this->fechaAsignacionRealizada, $this->carreraActual);

//printf("3::%s<br>gestion..%d<br>",$query_del,$this->gestion);
                if (!$this->conexionBDD->query($query_del)) {
                    $this->gestion = 0;
                } // se aborta proceso fallo eliminacion de asignacion
//printf("4::gestion..%d<br>",$this->gestion);
            }
        }

        if ($this->gestion) {
            $query_insE = $this->gsql->procesaEncabezado_insert1($this->mTransaccion[$this->carreraActual], $this->fechaAsigna, $this->mUsuario, $this->carreraActual, $this->mfechains[$this->carreraActual]);

//printf("5::%s...%d<br>",$query_insE,$this->gestion);
            if (!$this->conexionBDD->query($query_insE)) {
                $this->gestion = 0;
            } //No pudo insertar en asignacion en maestro
            else {
                $horaAsignacion = strftime("%H:%M:%S"); // se obtiene la hora de la asignacion

                $query_insE = $this->gsql->procesaEncabezado_insert2($this->mTransaccion[$this->carreraActual], $this->fechaAsigna, $this->mUsuario, $this->carreraActual, 0, $this->mIP, $horaAsignacion);

//printf("6::%s...%d<br>",$query_insAE,$this->gestion);
                if (!$this->conexionBDD->query($query_insE)) {
                    $this->gestion = 0;
                } //No pudo insertar en AUDITORIA DE ASIGNACION en maestro
//printf("6.1::%d<br>",$this->gestion);
            }  // del else
        } // fin de gestion
    } // fin de procedimiento procesaEncabezado

    function procesaDetalle($i)
    {
//printf("--7::gestion %d<br>",$this->gestion);
        if ($this->gestion == 1) {// debe de iniciar el proceso de asignacion
            if (strcmp($_SESSION['cursosAsig'][$i]['curso'], "") != 0) {//if ($_SESSION['cursosAsig'][$i]['mEstadoAsignar']==10)
                if ($_SESSION["cursosAsig"][$i]['mEstado'] == 1) {// se realiza la asignacion de detalle de la asignacion

                    $query_insD = $this->gsql->procesaDetalle_insert1($this->mTransaccion[$this->carreraActual], $this->fechaAsigna, $_SESSION['cursosAsig'][$i]['curso'], $_SESSION['cursosAsig'][$i]['seccion'], $this->mPeriodo, $this->mAnio, $_SESSION['cursosAsig'][$i]['mZonaLab'], $_SESSION['cursosAsig'][$i]['mZonaCurso'], $_SESSION['cursosAsig'][$i]['mExamenFinal'], $_SESSION['cursosAsig'][$i]['mCodProblema'], $_SESSION['cursosAsig'][$i]['mEstadoExamen'],$_SESSION['cursosAsig'][$i]['index'],$_SESSION['cursosAsig'][$i]['grupolab']);

//printf("7.1::detalle %s<br>",$query_insD);//$this->gestion=0;
                    if (!$this->conexionBDD->query($query_insD)) {
                        $this->gestion = 0;
                    } else {// asignacion del detalle de la asignacion

                        $query_insAD = $this->gsql->procesaDetalle_insert2($this->mTransaccion[$this->carreraActual], $this->fechaAsigna, $_SESSION['cursosAsig'][$i]['curso'], $_SESSION['cursosAsig'][$i]['seccion'], $this->mPeriodo, $this->mAnio, $_SESSION['cursosAsig'][$i]['mCodProblema'],$_SESSION['cursosAsig'][$i]['index'],$_SESSION['cursosAsig'][$i]['grupolab']);

//printf("8::Auditoria Detalle <br>%s<br>gestion::%d<br>",$query_insAD,$this->gestion);
                        //----------$this->gestion=0;
                        if (!$this->conexionBDD->query($query_insAD)) {
                            $this->gestion = 0;
                        } else {

                        }
                    } // fin del Estado de Asignardetalle
//printf("13::>>>gestion>> %d<br>",$this->gestion);
                }
            } // ver si el curso tiene espacio en blanco
        } // fin del if de gestion
//printf("fin de detalle -- gestion::%d<br>",$this->gestion);
    } // fin de funcion procesaDetalle

//---------------------------------------------------------------------------------


    function _eliminarDetalleOrdenPago($orden, $fecha)
    {
//    $sql = sprintf(" DELETE FROM recibopagows_detalle WHERE ordenpago = '%s' AND fechaordenpago = '%s'; ",$orden,$fecha);

        $sql = $this->gsql->_eliminarDetalleOrdenPago_delete1($orden, $fecha);


        if (!$this->conexionBDD->query($sql)) {
            $this->gestion = 0;
        } //No pudo elimnar el detalle de la orden

    }


    function _eliminarOrdenPago($orden, $fecha)
    {
//    $sql = sprintf(" DELETE FROM recibopagows WHERE usuarioid = '%s' AND ordenpago = '%s' AND fechaordenpago = '%s';
//                  ",$this->mUsuario,$orden,$fecha);

        $sql = $this->gsql->_eliminarOrdenPago_delete1($this->mUsuario, $orden, $fecha);

        // -- AND fechaordenpago = '%s';

        if (!$this->conexionBDD->query($sql)) {
            $this->gestion = 0;
        } //No pudo elimnar el detalle de la orden

    }

    function _ProcesoGenracionOrdenPago()
    {

//   $sql = sprintf(" SELECT verws,verca,verftp,ordenpago,fechaordenpago
//                      FROM recibopagows
//                     WHERE usuarioid='%s' AND tipopago IN ('%s','%s') AND anio = '%s' AND periodo = '%s'
//						AND carrera = '%s'
//					 ORDER BY ordenpago,fechaordenpago;
//                  ",$this->mUsuario,_TIPO_PAGO_PRIMERA_RETRASADA,_TIPO_PAGO_SEGUNDA_RETRASADA,$this->mAnio,$this->mPeriodo,$this->carreraActual);

        $sql = $this->gsql->_ProcesoGenracionOrdenPago_select1($this->mUsuario, _TIPO_PAGO_PRIMERA_RETRASADA, _TIPO_PAGO_SEGUNDA_RETRASADA, $this->mAnio, $this->mPeriodo, $this->carreraActual);

//printf("ordenes::::%s <br>",$sql);
        $numeroOrdenes = 0;
        $_SESSION['ORDEN_EXISTENTE'] = FALSE;
        if (($this->conexionBDD->query($sql)) AND ($this->conexionBDD->num_rows() > 0)) {
            for ($pos = 0; $pos < $this->conexionBDD->num_rows(); $pos++) {
                $numeroOrdenes = $this->conexionBDD->num_rows();
                $this->conexionBDD->next_record();
                $orden[$pos] = $this->conexionBDD->r(); // obtiene el registro completo de la tupla de la BDD
            }
        }
        $ordenPrincipal = 0;

//die;
        $_SESSION['marcaInscripcion'] = TRUE;
        for ($pos = 0; $pos < $numeroOrdenes; $pos++) {
            if (($orden[$pos]['verws'] == 0) AND ($orden[$pos]['verca'] == 0)) {
                if ($orden[$pos]['paymentorder'] != $ordenPrincipal) {
                    $this->_eliminarDetalleOrdenPago($orden[$pos]['paymentorder'], $orden[$pos]['paymentorderdate']);
                }
                $this->_eliminarOrdenPago($orden[$pos]['paymentorder'], $orden[$pos]['paymentorderdate']);
//		$_SESSION['marcaInscripcion'] = TRUE;
            } else {
                $_SESSION['ORDEN_EXISTENTE'] = TRUE;
                $ordenPrincipal = $orden[$pos]['paymentorder'];
                $fechaOrden = $orden[$pos]['paymentorderdate'];
                $this->_eliminarDetalleOrdenPago($orden[$pos]['paymentorder'], $orden[$pos]['paymentorderdate']);
                $_SESSION['marcaInscripcion'] = FALSE;
            }
//printf("(%d)...ws::: %d...ca::: %d...ftp::: %d<br>",$pos,$orden[$pos]['verws'],$orden[$pos]['verca'],$orden[$pos]['verftp']);
        }
//printf("(monto)::::%d...(gestion)::::%d<br>",$_SESSION['monto_Generar'],$this->gestion);
        if ($_SESSION['monto_Generar'] > 0 AND $this->gestion) {
            if ($this->numCurAsignar > 0) {
                $generaBoleta = new OG_PaymentOrderGenerationWS();
                $errGenerarboleta = $generaBoleta->procesoGeneracion($this->numCurAsignar, $_SESSION['cursosAsig']);
            } else {
                $this->gestion = TRUE;
            }
            $this->gestion = $errGenerarboleta;
//printf("error de generacion:::: %d<br>",$errGenerarboleta);
            $horaAsignacion = strftime("%H:%M:%S"); // se obtiene la hora de la asignacion
            if ($this->gestion) {
//          $sql = sprintf("INSERT INTO recibopagows (usuarioid,carrera,ordenpago,fechaordenpago,tipopago,rubropago,monto,anio,periodo,verificador,ordencomplemento,horaordenpago)
//						 VALUES ('%s','%s','%s','%s','%s','%s',%d,'%s','%s','%s','%s','%s');
//						",$_SESSION['ORDEN_PAGO']['carne'],$_SESSION['ORDEN_PAGO']['carrera'],$_SESSION['ORDEN_PAGO']['numeroOrden'],
//						  $_SESSION['ORDEN_PAGO']['fecha'],$_SESSION['ORDEN_PAGO']['tipo'],$_SESSION['ORDEN_PAGO']['rubropago'],
//						  $_SESSION['ORDEN_PAGO']['Monto'],$_SESSION['ORDEN_PAGO']['anio'],$_SESSION['ORDEN_PAGO']['periodo'],
//						  $_SESSION['ORDEN_PAGO']['verificador'],$ordenPrincipal,$horaAsignacion
//					   );

                $sql = $this->gsql->_ProcesoGenracionOrdenPago_insert1($_SESSION['ORDEN_PAGO']['carne'], $_SESSION['ORDEN_PAGO']['carrera'], $_SESSION['ORDEN_PAGO']['numeroOrden'],
                    $_SESSION['ORDEN_PAGO']['fecha'], $_SESSION['ORDEN_PAGO']['tipo'], $_SESSION['ORDEN_PAGO']['rubropago'],
                    $_SESSION['ORDEN_PAGO']['Monto'], $_SESSION['ORDEN_PAGO']['anio'], $_SESSION['ORDEN_PAGO']['periodo'],
                    $_SESSION['ORDEN_PAGO']['verificador'], $ordenPrincipal, $horaAsignacion);

//printf( "orden pago :::%s...%d <br>",$sql,$this->gestion);
                if (($this->conexionBDD->query($sql)) AND ($this->conexionBDD->affected_rows() > 0)) {
                    $this->gestion = TRUE;

                    $ordenPrincipal = $_SESSION['ORDEN_PAGO']['numeroOrden'];
                    $fechaOrden = $_SESSION['ORDEN_PAGO']['fecha'];
//			 $sql = sprintf(" INSERT INTO backup_recibopagows (usuarioid,carrera,ordenpago,fechaordenpago,monto,anio,periodo,verificador,horaordenpago,tipopago)
//						      VALUES ('%s','%s','%s','%s',%d,'%s','%s','%s','%s','%s');
//						    ",$_SESSION['ORDEN_PAGO']['carne'],$_SESSION['ORDEN_PAGO']['carrera'],
//							  $_SESSION['ORDEN_PAGO']['numeroOrden'],
//						      $_SESSION['ORDEN_PAGO']['fecha'],$_SESSION['ORDEN_PAGO']['Monto'],
//							  $_SESSION['ORDEN_PAGO']['anio'],$_SESSION['ORDEN_PAGO']['periodo'],
//							  $_SESSION['ORDEN_PAGO']['verificador'],$horaAsignacion,
//							  $_SESSION['ORDEN_PAGO']['tipo']
//					       );

                    $sql = $this->gsql->_ProcesoGenracionOrdenPago_insert2($_SESSION['ORDEN_PAGO']['carne'], $_SESSION['ORDEN_PAGO']['carrera'],
                        $_SESSION['ORDEN_PAGO']['numeroOrden'],
                        $_SESSION['ORDEN_PAGO']['fecha'], $_SESSION['ORDEN_PAGO']['Monto'],
                        $_SESSION['ORDEN_PAGO']['anio'], $_SESSION['ORDEN_PAGO']['periodo'],
                        $_SESSION['ORDEN_PAGO']['verificador'], $horaAsignacion,
                        $_SESSION['ORDEN_PAGO']['tipo']);

                    if (($this->conexionBDD->query($sql)) AND ($this->conexionBDD->affected_rows() > 0)) {
                        $this->gestion = TRUE;//printf("</br>GESTION[1]=%d",$this->gestion);
                    } else {
                        $this->gestion = FALSE; //printf("GESTION[11]=%d, </br>%s",$this->gestion,$sql);
                    }  //  FRACASO LA INSERCION DE UNA NUEVO BACKUP ORDEN DE PAGO
                }       //  FUE UN EXITO LA INSERCION DE LA ORDEN DE PAGO
                else {
                    $this->gestion = FALSE;
                }  //  FRACASO LA INSERCION DE UNA NUEVA ORDEN DE PAGO
                //printf("</br>GESTION[2]=%d",$this->gestion);
            }
        }  // FIN DEL monto_Generar
//printf("orden pago::: %d...orden principal::: %d<br>",$orden[$pos]['ordenpago'],$ordenPrincipal);
        if ($ordenPrincipal > 0) {
            for ($pos = 1; $pos <= $this->numCurAsignar; $pos++) {
                if ($_SESSION["cursosAsig"][$pos]['mEstado'] == 1) {
                    $laboratorio = 0;
                    if ($this->mCursosIns[$pos]['retUnica']) {
                        $laboratorio = 1;
                    }

//		 $sql = sprintf("INSERT INTO recibopagows_detalle (ordenpago,fechaordenpago,curso,verlaboratorio)
//		                 VALUES ('%s','%s','%s',%d)
//		               ",$ordenPrincipal,$fechaOrden,$_SESSION['cursosAsig'][$pos]['curso'],$laboratorio
//			          );

                    $sql = $this->gsql->_ProcesoGenracionOrdenPago_insert3($ordenPrincipal, $fechaOrden, $_SESSION['cursosAsig'][$pos]['curso'], $laboratorio);

//printf( "orden pago detalle:::%s...%d <br>",$sql,$this->gestion);
                    if (($this->conexionBDD->query($sql)) AND ($this->conexionBDD->affected_rows() > 0)) {
                        $this->gestion = TRUE;
                    }       //  FUE UN EXITO LA INSERCION DEL DETALLE DE LA ORDEN DE PAGO
                    else {
                        $this->gestion = FALSE;
                    }  //  FRACASO LA INSERCION DEL DETALLE DE UNA NUEVA ORDEN DE PAGO
                }
            }
        }

        // mandar un codigo de error de generacion de orden
    } // FIN DE LA FUNCION DONDE SE MANDA A GENERAR UNA NUEVA ORDEN DE PAGO


//---------------------------------------------------------------------------------

    function insertaCursos()
    {
        $this->fechaAsigna = strftime("%Y-%m-%d");
        $this->gestion = 1;
        // inicia la transacción de asignacion de cursos
        //if (!$this->conexionBDD->query("BEGIN"))
        if (!$this->conexionBDD->query($this->gsql->begin())) {
            echo("err:No inicia transaccion");
            $this->gestion = 0;
        }
        // curso asignado anteriormente, se puede asignar en restrasada
        $this->mIP = $_SESSION["IPGlobal"];
        $Trans = new Transaction();
        $this->carreraAcutal = "";
//print "numero CURSOS::".$_SESSION["numCursos"]."<br>";
        $this->carreraActual = $_SESSION['cursosAsig'][1]['mCarreraCurso'];
        $this->mfechains = $_SESSION['cursosAsig'][1]['inscripcion'];

        /***************************************************************************************************************************************************/
        // Para que no inserte una nueva asignación
        //$this->mTransaccion[$this->carreraActual] = $Trans->ObtenerTransaccion();
        //$this->procesaEncabezado();
        /***************************************************************************************************************************************************/

//printf("de encabezado...gestion :::: %d<br>",$this->gestion);


        /***************************************************************************************************************************************************/
        /* Inicia
         Para que no registre los detalles de la asignación
        if ($this->gestion)
        {for ($i=1;$i<=$_SESSION["numCursos"];$i++)
          {if ($this->gestion)
             {$this->procesaDetalle($i); }
      //printf("gestion >> %d<br>",$this->gestion);
          } // fin for
         } // fin de gestion

           Finaliza*/

        /***************************************************************************************************************************************************/

//printf("gestion:::: %d<br>",$this->gestion);
        if ($_SESSION["numCursos"] > 0) {
            $this->numCurAsignar = $_SESSION["numCursos"];
            $this->_ProcesoGenracionOrdenPago();
        }

//  if ($this->gestion==1) { $query = sprintf('commit');}
        if ($this->gestion == 1) {
            $query = sprintf($this->gsql->commit());
        } //    else {$query = sprintf('rollback');}
        else {
            $query = sprintf($this->gsql->rollback());
        }
//printf("proceso:::::%s<br>",$query);
        if (!$this->conexionBDD->query($query)) {
            echo('no realizo operación');
        }   // finaliza la transacción
//  if (!$this->conexionBDD->query("end"))  {echo('no fin de transacción');}
        if (!$this->conexionBDD->query($this->gsql->end())) {
            echo('no fin de transacción');
        }
        return $this->gestion;
    } // fin de la funcion de insertaCursos

    function registrarAsignacion()
    {
        $this->fechaAsigna = strftime("%Y-%m-%d");
        $this->gestion = 1;

        if (!$this->conexionBDD->query($this->gsql->begin())) {
            echo("err:No inicia transaccion");
            $this->gestion = 0;
        }
        // curso asignado anteriormente, se puede asignar en restrasada
        $this->mIP = $_SESSION["IPGlobal"];
        $Trans = new Transaction();
        $this->carreraAcutal = "";
//print "numero CURSOS::".$_SESSION["numCursos"]."<br>";
        $this->carreraActual = $_SESSION['cursosAsig'][1]['mCarreraCurso'];
        $this->mfechains[$this->carreraActual] = $_SESSION['cursosAsig'][1]['inscripcion'];
        $this->mTransaccion[$this->carreraActual] = $Trans->ObtenerTransaccion();
        $this->procesaEncabezado();
//printf("de encabezado...gestion :::: %d<br>",$this->gestion);

        if ($this->gestion) {
            for ($i = 1; $i <= $_SESSION["numCursos"]; $i++) {
                if ($this->gestion) {
                    $this->procesaDetalle($i);
                }
//printf("gestion >> %d<br>",$this->gestion);
            } // fin for
        } // fin de gestion
//printf("gestion:::: %d<br>",$this->gestion);

        if ($_SESSION["numCursos"] > 0) {
            $this->numCurAsignar = $_SESSION["numCursos"];
            //$this->_ProcesoGenracionOrdenPago();
        }

        if ($this->gestion == 1) { //$query = sprintf('commit');

            $query = sprintf($this->gsql->commit());
        } else {//$query = sprintf('rollback');

            $query = sprintf($this->gsql->rollback());
        }
//printf("proceso:::::%s<br>",$query);
        if (!$this->conexionBDD->query($query)) {
            echo('no realizo operación');
        }   // finaliza la transacción
//  if (!$this->conexionBDD->query("end"))


        if (!$this->conexionBDD->query($this->gsql->end())) {
            echo('no fin de transacción');
        }
        return $this->gestion;
    } // fin de la funcion de insertaCursos


}  // fin de clase
?>

