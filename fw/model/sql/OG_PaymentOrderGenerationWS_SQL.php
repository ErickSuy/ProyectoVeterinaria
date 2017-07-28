<?php
/**
 * @origen biblioteca/class.verificaciones_manejo_proceso_WS.inc.php
 */
include_once("General_SQL.php");

/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */
Class OG_PaymentOrderGenerationWS_SQL extends General_SQL
{

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   ordenpago = '%d'
     * @functionOrigen  _verifica_en_historial
     * @ref #1
     * @línea   #54
     */
    function _verifica_en_historial_select1($arreglo_datosCON_PAGCARNETLONG_DIG_RELLENOSTR, $arreglo_datosCON_PAGID_ORDEN_PAGO, $arreglo_datosCON_PAGCARRERALON_DIG_RELLENOSTR)
    {
        return sprintf("SELECT *
                    FROM tbcoursepayment_back
                    WHERE idstudent = '%s' AND paymentorder = '%s'
                        AND idcareer = '%s';", $arreglo_datosCON_PAGCARNETLONG_DIG_RELLENOSTR, $arreglo_datosCON_PAGID_ORDEN_PAGO, $arreglo_datosCON_PAGCARRERALON_DIG_RELLENOSTR
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _verifica_en_historial
     * @ref #2
     * @línea   #77
     */
    function _verifica_en_historial_insert1($nuevaOrdenusuarioid, $nuevaOrdencarrera, $nuevaOrdenordenpago, $nuevaOrdenfechaordenpago, $nuevaOrdentipopago, $arreglo_datosCONFIRMACION_PAGONO_BOLETA_DEPOSITO, $arreglo_datosCONFIRMACION_PAGOFECHA_CERTIF_BCO, $rubro, $arreglo_datosCONFIRMACION_PAGOBANCO, $nuevaOrdenmonto, $nuevaOrdenanio, $nuevaOrdenperiodo, $nuevaOrdenverificador, $nuevaOrdenhoraordenpago, $horaVerificacion)
    {
        return sprintf("INSERT INTO tbcoursepayment
	                       (idstudent,idcareer,paymentorder,paymentorderdate,idpaymenttype,paymentidnumber,paymentdate,rubro,bankname,
	                        amount,verws,year,idschoolyear,verifier,paymentordertime,paymenttime)
					   VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s',%d::NUMERIC ,1,'%s','%s','%s','%s','%s');
					  ", $nuevaOrdenusuarioid, $nuevaOrdencarrera, $nuevaOrdenordenpago,
            $nuevaOrdenfechaordenpago, $nuevaOrdentipopago, $arreglo_datosCONFIRMACION_PAGONO_BOLETA_DEPOSITO,
            $arreglo_datosCONFIRMACION_PAGOFECHA_CERTIF_BCO, $rubro,
            $arreglo_datosCONFIRMACION_PAGOBANCO, $nuevaOrdenmonto, $nuevaOrdenanio, $nuevaOrdenperiodo,
            $nuevaOrdenverificador, $nuevaOrdenhoraordenpago, $horaVerificacion
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  actualizaOrdenPago
     * @ref #3
     * @línea   #110
     */
    function actualizaOrdenPago_update1($arreglo_datosCON_PAG_BOL_DEP, $arreglo_datosCON_PAGFECHA_CERTIF_BCO, $arreglo_datosCON_PAGBANCO, $arreglo_datosCON_PAGTIPO_PETICION, $horaVerificacion, $arreglo_datosCON_PAGCARNETLONG_DIG_RELLENOSTR, $arreglo_datosCON_PAGID_ORDEN_PAGO, $arreglo_datosCON_PAGCARRERALONG_DIG_RELLENOSTR)
    {
        return sprintf(" UPDATE tbcoursepayment SET paymentidnumber='%s', paymentdate=to_date('%s','YYYYMMDD'), bankname='%s',verws=1,requesttype=%d,
	                          paymenttime='%s', idpersonal=%s
                        WHERE idstudent = %s AND paymentorder = %s
						  AND idcareer = %s;",
            $arreglo_datosCON_PAG_BOL_DEP, $arreglo_datosCON_PAGFECHA_CERTIF_BCO, $arreglo_datosCON_PAGBANCO, $arreglo_datosCON_PAGTIPO_PETICION, $horaVerificacion, $arreglo_datosCON_PAGCARNETLONG_DIG_RELLENOSTR, $arreglo_datosCON_PAGCARNETLONG_DIG_RELLENOSTR, $arreglo_datosCON_PAGID_ORDEN_PAGO, $arreglo_datosCON_PAGCARRERALONG_DIG_RELLENOSTR);
    }
	
	function insertarAsignacionDetalle($mTransaccion_carreraActual,$fechaAsigna,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$_SESSIONcursosAsig_imZonaLab,$_SESSIONcursosAsig_imZonaCurso,$_SESSIONcursosAsig_imExamenFinal,$_SESSIONcursosAsig_imCodProblema,$_SESSIONcursosAsig_imEstadoExamen,$index,$lab){
        return sprintf(" INSERT INTO tbassignationdetail (iddetailassignation,idassignation,state,labnote,classzone,notefinalexam,note,examdescription,idcourse,section,idschoolyear,year,index,problemdetail,labgroup,idfinalexamstate)
                               VALUES(nextval('numtransacciondetalle'),%d,1,%d,%d,%d,%d,'','%s','%s','%s','%s','%s','%s','%s',%d);
                             ",$mTransaccion_carreraActual,$_SESSIONcursosAsig_imZonaLab,$_SESSIONcursosAsig_imZonaCurso,$_SESSIONcursosAsig_imExamenFinal,0,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$index,$_SESSIONcursosAsig_imCodProblema,$lab,$_SESSIONcursosAsig_imEstadoExamen);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _actualizaOrdenPago
     * @ref #4
     * @línea   #139
     */
    function _actualizaOrdenPago_NOCANCELADAS_update1($_SESSIONORDENPAGO_NO_CANCELADAposorden, $_SESSIONORDENPAGO_NO_CANCELADAposfecha)
    {

        return sprintf(" UPDATE recibopagoWS SET tipopago = '800'
                        WHERE ordenpago = '%s' AND fechaordenpago = '%s'
				      ", $_SESSIONORDENPAGO_NO_CANCELADAposorden, $_SESSIONORDENPAGO_NO_CANCELADAposfecha
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   anio='%d'
     * @functionOrigen  detalleOrdenesPago
     * @ref #5
     * @línea   #585
     */
    function detalleOrdenesPago_select1($_SESSIONdatosGeneralesusuarioid, $_SESSIONdatosGeneralescarrera, $_SESSIONdatosGeneralesperiodo, $_SESSIONdatosGeneralesanio, $tipopago)
    {
        return sprintf(" SELECT paymentorder,paymentorderdate,amount,verws,verca
                          FROM tbcoursepayment
					     WHERE idstudent = '%s'
					       AND idcareer = '%s' AND idschoolyear = '%s'
				  		   AND year='%s'
						   AND idpaymenttype = '%s'
			         ", $_SESSIONdatosGeneralesusuarioid, $_SESSIONdatosGeneralescarrera, $_SESSIONdatosGeneralesperiodo,
            $_SESSIONdatosGeneralesanio, $tipopago);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   rp.ordenpago='%d'
     *  -   rp.anio='%d'
     * @functionOrigen  obtieneInfoOrdenPago
     * @ref #6
     * @línea   #623
     */
    function obtieneInfoOrdenPago_select1($OrdenPago, $_SESSIONdatosGeneralesperiodo, $_SESSIONdatosGeneralesanio, $_SESSIONdatosGeneralescarrera, $_TIPO_PAGO_PRIMERA_RETRASADA, $_TIPO_PAGO_SEGUNDA_RETRASADA, $_TIPO_PAGO_VACACIONES_JUNIO, $_TIPO_PAGO_VACACIONES_DICIEMBRE, $_TIPO_PAGO_PRIMERA_RETRASADA2, $_TIPO_PAGO_SEGUNDA_RETRASADA2)
    {
        return sprintf(" SELECT rp.paymentorder as ordenpago, rp.idstudent as usuarioid, 10 AS unidad, 0 AS extension,rp.idcareer as carrera, rp.rubro as rubropago, rp.verifier as verifier ,
                                 rtrim(e.name) || ' ' || rtrim(e.surname) AS nombreest, c.name as nombrecar, rp.amount as monto,rp.checksum as checksum,
							  rp.paymentorderdate as fechaordenpago
                            FROM tbcoursepayment rp,tbstudent e, tbcareer c
                           WHERE rp.idstudent = e.idstudent
                             AND rp.idcareer = c.idcareer
                             AND rp.paymentorder = '%s'
                             AND rp.idschoolyear = '%s'
                             AND rp.year = '%s'
                             AND rp.idcareer = '%s'
							 AND rp.idpaymenttype IN ('%s','%s','%s','%s','%s','%s')
   	                 ",
            $OrdenPago,
            $_SESSIONdatosGeneralesperiodo,
            $_SESSIONdatosGeneralesanio,
            $_SESSIONdatosGeneralescarrera,
            $_TIPO_PAGO_PRIMERA_RETRASADA,
            $_TIPO_PAGO_SEGUNDA_RETRASADA,
            $_TIPO_PAGO_VACACIONES_JUNIO,
            $_TIPO_PAGO_VACACIONES_DICIEMBRE,
            $_TIPO_PAGO_PRIMERA_RETRASADA2,
            $_TIPO_PAGO_SEGUNDA_RETRASADA2);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  obtieneDetalleOrden
     * @ref #7
     * @línea   #660
     */
    function obtieneDetalleOrden_select1($orden, $fecha)
    {
        return sprintf(" SELECT  rd.curso,c.nombre,rd.verlaboratorio as laboratorio
                       FROM recibopagows_detalle rd,curso c
					  WHERE rd.curso = c.curso
					    AND rd.ordenpago = '%s'
						AND rd.fechaordenpago = '%s';
                   ", $orden, $fecha);
    }

}

//fin consultas respecto a la versión 9.0

/**
 * Asignando herencia a la clase SQL a la versión que corresponde
 */
?>
