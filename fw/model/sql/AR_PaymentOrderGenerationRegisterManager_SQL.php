<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 06/05/2015
 * Time: 08:37 AM
 */
include_once("General_SQL.php");
/**
 * Centralización de consultas de portal2
 *
 * PostgreSQL @version 9.0
 */

Class AR_PaymentOrderGenerationRegisterManager_SQL extends General_SQL {

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaEncabezado
     * @ref #1
     * @línea   #82
     */
    function procesaEncabezado_select1($mUsuario,$mPeriodo,$mAnio,$carreraActual){
        return sprintf(" SELECT distinct (a.idassignation),a.assignationdate".
            "   FROM tbassignation a,tbassignationdetail ad ".
            "  WHERE a.idassignation = ad.idassignation ".
            "    AND a.idstudent = '%s' AND ad.idschoolyear = '%s' ".
            "    AND ad.year = '%s' AND a.idcareer ='%s' ".
            "",$mUsuario,$mPeriodo,$mAnio,$carreraActual);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaEncabezado
     * @ref #2
     * @línea   #95
     */
    function procesaEncabezado_delete1($mPeriodo,$mAnio,$transaccionRealizada,$fechaAsignacionRealizada){
        return sprintf(" DELETE FROM tbassignationdetail ".
            "  WHERE tbassignationdetail.idschoolyear = '%s' ".
            "    AND tbassignationdetail.year = '%s' ".
            "    AND tbassignationdetail.idassignation = %d ".
            "",$mPeriodo,$mAnio,$transaccionRealizada);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaEncabezado
     * @ref #3
     * @línea   #107
     */
    function procesaEncabezado_delete2($transaccionRealizada,$fechaAsignacionRealizada,$carreraActual){
        return sprintf(" DELETE FROM tbassignation ".
            "  WHERE tbassignation.idassignation = %d ".
            "    AND tbassignation.assignationdate = '%s' ".
            "    AND tbassignation.idcareer = '%s' ".
            "",$transaccionRealizada,$fechaAsignacionRealizada,$carreraActual);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaEncabezado
     * @ref #4
     * @línea   #120
     */
    function procesaEncabezado_insert1($mTransaccion_carreraActual,$fechaAsigna,$mUsuario,$carreraActual,$mfechains_carreraActual){
        return sprintf(" INSERT INTO tbassignation (idassignation,assignationdate,state,idstudent,idcareer,enrollmentdate) VALUES (%d,to_date('%s','YYYY-MM-DD'),1,'%s','%s',to_date('%s','YYYY-MM-DD'));
                           ",$mTransaccion_carreraActual,$fechaAsigna,$mUsuario,$carreraActual,$mfechains_carreraActual);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaEncabezado
     * @ref #5
     * @línea   #130
     */
    function procesaEncabezado_insert2($mTransaccion_carreraActual,$fechaAsigna,$mUsuario,$carreraActual,$_SESSIONsUsuarioUsurpador_mUsuario,$mIP,$horaAsignacion){


        return sprintf(" INSERT INTO tbassignationaudit (idassignation,assignationdate,idstudent,idcareer,idpersonal,terminal,ip,time) VALUES(%d,to_date('%s','YYYY-MM-DD'),'%s','%s','%s','WEB','%s','%s'); ".
            "",$mTransaccion_carreraActual,$fechaAsigna,$mUsuario,$carreraActual,$_SESSIONsUsuarioUsurpador_mUsuario,$mIP,$horaAsignacion);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaDetalle
     * @ref #6
     * @línea   #153
     */
    function procesaDetalle_insert1($mTransaccion_carreraActual,$fechaAsigna,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$_SESSIONcursosAsig_imZonaLab,$_SESSIONcursosAsig_imZonaCurso,$_SESSIONcursosAsig_imExamenFinal,$_SESSIONcursosAsig_imCodProblema,$_SESSIONcursosAsig_imEstadoExamen,$index,$lab){
        return sprintf(" INSERT INTO tbassignationdetail (iddetailassignation,idassignation,state,labnote,classzone,notefinalexam,note,examdescription,idcourse,section,idschoolyear,year,index,problemdetail,labgroup,idfinalexamstate)
                               VALUES(nextval('numtransacciondetalle'),%d,1,%d,%d,%d,%d,'','%s','%s','%s','%s','%s','%s','%s',%d);
                             ",$mTransaccion_carreraActual,$_SESSIONcursosAsig_imZonaLab,$_SESSIONcursosAsig_imZonaCurso,$_SESSIONcursosAsig_imExamenFinal,0,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$index,$_SESSIONcursosAsig_imCodProblema,$lab,$_SESSIONcursosAsig_imEstadoExamen);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaDetalle
     * @ref #7
     * @línea   #164
     */
    function procesaDetalle_insert2($mTransaccion_carreraActual,$fechaAsigna,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$_SESSIONcursosAsig_imCodProblema,$index,$lab){
        return sprintf(" INSERT INTO tbassignationauditdetail
                               (idassignation,assignationdate,idcourse,section,idschoolyear,year,problemdetail,index,labgroup)
                                    VALUES(%d,to_date('%s','YYYY-MM-DD'),'%s','%s','%s','%s','%s',%d,'%s');
                                  ",$mTransaccion_carreraActual,$fechaAsigna,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$_SESSIONcursosAsig_imCodProblema,$index,$lab);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaDetalle
     * @ref #8
     * @línea   #179
     */
    function procesaDetalle_select1($_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio){
        return sprintf(" SELECT usuarioid FROM ingresotemporal WHERE curso = '%s' AND seccion='%s' AND periodo='%s'
					                                                                AND anio= '%s';
				             ",$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaDetalle
     * @ref #9
     * @línea   #191
     */
    function procesaDetalle_select2($_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$mUsuario){
        return sprintf(" SELECT usuarioid FROM ingresotemporal
				             WHERE curso = '%s' AND seccion='%s' AND periodo='%s'
					           AND anio= '%s' AND carnet ='%s';
				          ",$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$mUsuario);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  procesaDetalle
     * @ref #10
     * @línea   #201
     */
    function procesaDetalle_insert3($usuarioid,$mUsuario,$carreraActual,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$_SESSIONcursosAsig_imZonaCurso,$_SESSIONcursosAsig_imZonaLab,$_SESSIONcursosAsig_imExamenFinal,$_SESSIONcursosAsig_imCodProblema){
        return sprintf(" INSERT INTO ingresotemporal VALUES ('%s','%s','%s','%s','%s','%s','%s',%d,%d,%d,0,'%s');
					        ",$usuarioid,$mUsuario,$carreraActual,$_SESSIONcursosAsig_icurso,$_SESSIONcursosAsig_iseccion,$mPeriodo,$mAnio,$_SESSIONcursosAsig_imZonaCurso,$_SESSIONcursosAsig_imZonaLab,$_SESSIONcursosAsig_imExamenFinal,$_SESSIONcursosAsig_imCodProblema);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _eliminarDetalleOrdenPago
     * @ref #11
     * @línea   #226
     */
    function _eliminarDetalleOrdenPago_delete1($orden,$fecha){
        return sprintf(" DELETE FROM tbcoursepaymentdetail WHERE paymentorder = '%s' ; ",$orden);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _eliminarOrdenPago
     * @ref #12
     * @línea   #239
     */
    function _eliminarOrdenPago_delete1($mUsuario,$orden,$fecha){
        return sprintf(" DELETE FROM tbcoursepayment WHERE idstudent = '%s' AND paymentorder = '%s' AND paymentorderdate = '%s';
                  ",$mUsuario,$orden,$fecha);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _ProcesoGenracionOrdenPago
     * @ref #13
     * @línea   #258
     */
    function _ProcesoGenracionOrdenPago_select1($mUsuario,$_TIPO_PAGO_PRIMERA_RETRASADA,$_TIPO_PAGO_SEGUNDA_RETRASADA,$mAnio,$mPeriodo,$carreraActual){
        return sprintf(" SELECT verws,verca,paymentorder,paymentorderdate
                      FROM tbcoursepayment
                     WHERE idstudent='%s' AND idpaymenttype IN ('%s','%s') AND year = '%s' AND idschoolyear = '%s'
						AND idcareer = '%s'
					 ORDER BY paymentorder,paymentorderdate;
                  ",$mUsuario,$_TIPO_PAGO_PRIMERA_RETRASADA,$_TIPO_PAGO_SEGUNDA_RETRASADA,$mAnio,$mPeriodo,$carreraActual);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _ProcesoGenracionOrdenPago
     * @ref #14
     * @línea   #310
     */
    function _ProcesoGenracionOrdenPago_insert1($_SESSIONORDEN_PAGO_carne,$_SESSIONORDEN_PAGO_carrera,$_SESSIONORDEN_PAGO_numeroOrden,
                                                $_SESSIONORDEN_PAGO_fecha,$_SESSIONORDEN_PAGO_tipo,$_SESSIONORDEN_PAGO_rubropago,
                                                $_SESSIONORDEN_PAGO_Monto,$_SESSIONORDEN_PAGO_anio,$_SESSIONORDEN_PAGO_periodo,
                                                $_SESSIONORDEN_PAGO_verificador,$ordenPrincipal,$horaAsignacion){
        return sprintf("INSERT INTO tbcoursepayment (idstudent,idcareer,paymentorder,paymentorderdate,idpaymenttype,requesttype,amount,year,idschoolyear,verifier,complementorder,paymentordertime)
						 VALUES ('%s','%s','%s','%s','%s','%s',%d,'%s','%s','%s','%s','%s');
						",$_SESSIONORDEN_PAGO_carne,$_SESSIONORDEN_PAGO_carrera,$_SESSIONORDEN_PAGO_numeroOrden,
            $_SESSIONORDEN_PAGO_fecha,$_SESSIONORDEN_PAGO_tipo,$_SESSIONORDEN_PAGO_rubropago,
            $_SESSIONORDEN_PAGO_Monto,$_SESSIONORDEN_PAGO_anio,$_SESSIONORDEN_PAGO_periodo,
            $_SESSIONORDEN_PAGO_verificador,$ordenPrincipal,$horaAsignacion
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _ProcesoGenracionOrdenPago
     * @ref #15
     * @línea   #328
     */
    function _ProcesoGenracionOrdenPago_insert2($_SESSIONORDEN_PAGO_carne,$_SESSIONORDEN_PAGO_carrera,
                                                $_SESSIONORDEN_PAGO_numeroOrden,
                                                $_SESSIONORDEN_PAGO_fecha,$_SESSIONORDEN_PAGO_Monto,
                                                $_SESSIONORDEN_PAGO_anio,$_SESSIONORDEN_PAGO_periodo,
                                                $_SESSIONORDEN_PAGO_verificador,$horaAsignacion,
                                                $_SESSIONORDEN_PAGO_tipo){
        return sprintf(" INSERT INTO tbcoursepayment_back (idstudent,idcareer,paymentorder,paymentorderdate,amount,year,idschoolyear,verifier,paymentordertime,idpaymenttype)
						      VALUES ('%s','%s','%s','%s',%d,'%s','%s','%s','%s','%s');
						    ",$_SESSIONORDEN_PAGO_carne,$_SESSIONORDEN_PAGO_carrera,
            $_SESSIONORDEN_PAGO_numeroOrden,
            $_SESSIONORDEN_PAGO_fecha,$_SESSIONORDEN_PAGO_Monto,
            $_SESSIONORDEN_PAGO_anio,$_SESSIONORDEN_PAGO_periodo,
            $_SESSIONORDEN_PAGO_verificador,$horaAsignacion,
            $_SESSIONORDEN_PAGO_tipo
        );
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  _ProcesoGenracionOrdenPago
     * @ref #16
     * @línea   #358
     */
    function _ProcesoGenracionOrdenPago_insert3($ordenPrincipal,$fechaOrden,$_SESSIONcursosAsig_poscurso,$laboratorio){
        return sprintf("INSERT INTO tbcoursepaymentdetail (paymentorder,idcourse,lab)
		                 VALUES ('%s','%s',',%d)
		               ",$ordenPrincipal,$_SESSIONcursosAsig_poscurso,$laboratorio
        );
    }



}
//fin consultas respecto a la versión 9.0

?>