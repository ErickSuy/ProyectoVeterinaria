<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 8/08/14
 * Time: 06:52 AM
 */

// Constantes para diferenciar los grupos que pertenecen al portal
define("GRUPO_DOCENTE", 2);
define("GRUPO_AUXILIAR", 2);
define("GRUPO_ESTUDIANTE", 3);
define("GRUPO_VACACIONES", 5);
define("GRUPO_CONTROL_ACADEMICO", 4);
define("GRUPO_AUDITORIA", 7);
define("GRUPO_OPERADOR", 8);

// Constantes para diferenciar los sitios que conforman al portal
define("SITIO_DOCENTE", 2);
define("SITIO_AUXILIAR", 2);
define("SITIO_ESTUDIANTE", 1);
define("SITIO_JEFATURA", 36);
define("SITIO_AUDITORIA", 39);
define("SITIO_ASIGNACIONREGULAR",15);

// Constantes para las carreras
define("VETERINARIA", 2);
define("ZOOTECNIA", 3);

// Constantes para tipos de peticiones
define("SRV_LOGUEO_PERFIL", 3);
define("SRV_OBTENER_DEPARTAMENTOS", 300);
define("SRV_OBTENER_LISTADO_CURSOS", 301);
define("SRV_OBTENER_INFO_PENSUM", 302);
define("SRV_VALIDACIONES_ASIG1SEM", 303);

//  Constantes para manejar la codificacion de los periodos en base de datos
define("PRIMER_SEMESTRE", 100);
define("VACACIONES_DEL_PRIMER_SEMESTRE", 101);
define("PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE", 102);
define("SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE", 103);
define("SUFICIENCIAS_DEL_PRIMER_SEMESTRE", 104);
define("FINALES_DEL_PRIMER_SEMESTRE", 105);
define("SEGUNDO_SEMESTRE", 200);
define("VACACIONES_DEL_SEGUNDO_SEMESTRE", 201);
define("PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE", 202);
define("SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE", 203);
define("SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE", 204);
define("FINALES_DEL_SEGUNDO_SEMESTRE", 205);
define("EQUIVALENCIA", 300);
define("EQUIVALENCIA_CAMBIO_CARRERA", 301);
define("EQUIVALENCIA_SIMULTANEA", 302);
define("SIN_PERIODO", 400);
define("SIN_PERIODO_CIERRE PENSUM", 401);
define("SIN_PERIODO_EPS", 402);
define("SIN_PERIODO_SEMINARIO", 403);
define("SIN_PERIODO_EXAMENES_GENERALES", 500);

define("ASIGNACION_DE_SEMESTRE", 100);
define("ASIGNACION_RETRASADAS",200);
define("PROCESOS_GENERALES_PRIMER_SEMESTRE", 1);
define("PROCESOS_GENERALES_SEGUNDO_SEMESTRE", 2);
define("PROCESOS_GENERALES_VACACIONES_JUNIO", 3);
define("PROCESOS_GENERALES_VACACIONES_DICIEMBRE", 4);

// Llave para la encriptación, no modificar
define("LLAVE","c9c3i4I2n5g0e4n2i0e0r5ia");

define("OK", 1);
define("FAIL", 0);

define("CLASE_MAGISTRAL", 1);
define("LABORATORIO", 2);
define("REPITENCIA_TOPADA", 300);
define("CUPO_COMPLETO",310);
define("CURSO_ASIGNADO_CARRERA_SIMULTANEA", 325);
define("CERTIFICADO_CALUSAC", 312);

define("DIAS_ANTES_DE_PAGO_RETRASADAS", 1); //Numero de dias antes del
define ("TIEMPO_PERIODO", 50); /* Tiempo que comprende un perido de clases */

define ("TRASLAPE_TIPO1", 1); /* Traslape de un periodo entre dos cursos*/
define ("TRASLAPE_TIPO2", 2); /* Traslape de 'n' periodos entre dos cursos consideranzo que uno ya se llevo llegando a zona minima*/
define ("TRASLAPE_TIPO3", 3); /* Traslape de 'n' periodos entre dos cursos considerando que es estudiante de cierre*/
define ("TRASLAPE_TIPO4", 4); /* Traslape de 'n' periodos entre dos cursos considerando que son cursos asignados como pre y post */
define ("TRASLAPE_INVALIDO", 0); /* Traslape de 'n' periodos entre dos cursos considerando que son cursos asignados como pre y post */

define ("TRASLAPE_SIMPLE", 1); /* Un periodo a la semana */
define ("TRASLAPE_TOTAL", 2); /* Mas de un periodo a la semana */

define ("ASIGNACION_PREyPOST", 99);
define ("TRASLAPE_PORCIERRE", 98);
define ("TRASLAPE_PREyPOST", 100);
define ("CURSO_CONGELADO", 3);
define ("CURSO_RETRASADA_UNICA", 2);
define ("CURSO_RETRASADA_UNICA_FP", 16);
define ("CURSO_CONGELADO_FP", 17);
define ("CURSO_FALTA_PRERREQUISITO", 1);
define ("CURSO_TRASLAPE", 98);
define("MAX_FILE_SIZE","99200");

define("CURSOS_MODULARES","106,110,112,116,120,122,123,190,251,508,516,526,236,238,239,242,511,520,260,262,407,408,418,430,620,621,622,623,151,197,198,330,508,511,407,407");

$file_dir="/var/www/uploads";

define("MONGO_CLIENT","mongodb://10.50.23.11/:27017");
define("MONGO_COLLECTION","fotos");
define("MONGO_DB","fmvz");
define("ANCHO_IMG",145);
define("ALTO_IMG",170);


define("VALOR_PRIMERA_RETRASADA",10);
define("VALOR_SEGUNDA_RETRASADA",15);


define("TIPO_PAGO_INSCRIPCION","1");
define("TIPO_PAGO_VACACIONES","2");
define("TIPO_PAGO_RETRASADA","3");

define("TIPO_PAGO_INTENSIVO","4");
define("TIPO_PAGO_SUFICIENCIA","5");

define("_TIPO_PAGO_VACACIONES_JUNIO",2);
define("_TIPO_PAGO_VACACIONES_DICIEMBRE",3);
define("_TIPO_PAGO_PRIMERA_RETRASADA",4);
define("_TIPO_PAGO_SEGUNDA_RETRASADA",5);
define("_TIPO_PAGO_PRIMERA_RETRASADA2",6);
define("_TIPO_PAGO_SEGUNDA_RETRASADA2",7);



define("VERCA",0); //Siempre 0
define("VERWS",1); // 1 si es con webservice, 0 a mano
define("VERIFIER",1); //
define("REQUESTYPE",102); //
define("COMPLEMENTORDER",1); // 


// definicion de la longitud de los carne
define("LONGITUD_CARNET",9);
define("LONGITUD_CARRERA",2);

define("DIGITO_RELLENO","0");

define("UNIDAD_ACADEMICA",'10');
define("EXTENSION_ACADEMICA",'0');


define("VALORCURSO",50);
// datos para el pago de escuela de vacaciones (Junio y Diciembre)
define("MAXIMO_HORAS_VACACIONES",4);
define("TIEMPO_VACACIONES",240);

define("VALOR_INSCRIPCION",0);
define("VALOR_CURSO_DOS_HRS",95);
define("VALOR_CURSO_CUATRO_HRS",165);

define("VALOR_LABORATORIO",80);
define("VALOR_CURSO_ESPECIAL",40);

//define("URL_CONEXION_WS",'http://testsiif.usac.edu.gt/WSGeneracionOrdenPago/WSGeneracionOrdenPagoSoapHttpPort?wsdl');//LINK DE PRUEBAS version 1
//define("URL_CONEXION_WS",'http://testsiif.usac.edu.gt/WSGeneracionOrdenPagoV2/WSGeneracionOrdenPagoV2SoapHttpPort?wsdl');//version 2
//define("URL_CONEXION_WS",'http://localhost/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php');
//define("URL_CONEXION_WS",'http://10.50.23.229/libraries/biblio/librerias_externas/nusoap/WS/servidor_nusoap.php');
define("URL_CONEXION_WS",'http://siif.usac.edu.gt/WSGeneracionOrdenPagoV2/WSGeneracionOrdenPagoV2SoapHttpPort?wsdl');//LINK DE PRODUCCION
define("LOCATION_URL",'http://siif.usac.edu.gt:80/WSGeneracionOrdenPagoV2/WSGeneracionOrdenPagoV2SoapHttpPort');//LINK PARA PUERTO


?>