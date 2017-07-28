<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 30/01/2015
 * Time: 1:11 PM
 */

include_once("General_SQL.php");

/**
 * Centralización de consultas
 *
 * PostgreSQL @version 9.0
 */
Class UserManager_SQL extends General_SQL {

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ExisteUsuario
     * @ref #1
     * @línea   #109
     */
    function ExisteUsuario_select1($param0) {
        return sprintf("select e.usuarioid,e.nombre,e.apellido,ec.carrera
                        from estudiante e,estudiantecarrera ec
                        where e.usuarioid = ec.usuarioid
                           and e.usuarioid = '%s'
                        order by ec.carrera;
                        ", $param0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ExisteUsuario
     * @ref #2
     * @línea   #117
     */
    function ExisteUsuario_select2($param0) {
        return sprintf("select distinct personal
                        from horariodetalle
                        where personal = '%s';
                        ", $param0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ExisteUsuario
     * @ref #3
     * @línea   #128
     */
    function ExisteUsuario_select3($param0) {
        return sprintf("select p.nombre, p.apellido, p.direccion, p.colonia, p.telefono,
                          p.celular, p.empresa, p.correo, t.puesto
                        from personal p, titularidad t
                        where p.personal = t.personal
                            and p.personal = '%s';
                        ", $param0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ExisteUsuario
     * @ref #4
     * @línea   #147
     */
    function ExisteUsuario_select4($param0) {
        return sprintf("select distinct uc.usuarioid,uc.apellido,uc.nombre,uc.puesto
                        from Usuariocontrol uc
                        where uc.usuarioid = '%s';
                        ", $param0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  DatosUsuario
     * @ref #5
     * @línea   #207
     */
    function DatosUsuario_select1($GRUPO_ESTUDIANTE, $param_datos0) {
        return sprintf("select e.idstudent as carnet,
                          e.name as nombre,
                          e.surname as apellido,
                          case when sex = 'MASCULINO' then 1 when sex='FEMENINO' then 2 else 0 end as sexo,
                          case when maritalstatus = 'SOLTERO' then 1 when maritalstatus='CASADO' then 2 when maritalstatus='CASADA' then 4 when maritalstatus='SOLTERA' then 3 else 0 end as estadocivil,
                          case when (birthday is null or birthday='1900-01-01') then '' else birthday::character varying end as fechanac,
                          idcedula as cedula,
                          cedulatown as municipiocedula,
                          ceduladepartment as departamentocedula,
                          dpi,
                          addres as direccion,
                          avenue_or_similar as avenida,
                          housenumber_or_similar as numerocasa,
                          apartment_or_similar as apartamento,
                          zonenumber as zona,
                          suburb_or_similar as colonia,
                          address_department as departamento_dir,
                          address_town as municipio_dir,
                          e.idnationality as nacionalidad,
                          idpassport as pasaporte,
                          case when (passport_date is null or passport_date='1900-01-01') then '' else passport_date::character varying end as fechapasaporte,
                          passport_country as paispasaporte,
                          titlename as carrera_nombre,
                          institutionname as establecimiento,
                          email as correo1,
                          email2 as correo2,
                          phone as tel1,
                          phone2 as tel2,
                          cellphone as cel1,
                          cellphone2 as cel2,
                          father as padre,
                          fatherphone as telpadre,
                          mother as madre,
                          motherphone as telmadre,
                          emergencyname as responsable,
                          emergencyphone as telresponsable,
        u.password,
        u.keyword,
        ec.idcareer as carrera
                        from tbstudent e, tbstudentcareer ec,
                          tbtown m,
                          tbdepartment d,
                          tbnationality n ,
                          tbcountry p,
                          tbtown m2,
                          tbdepartment d2,
        tbauth_user u
                        where e.idstudent   = ec.idstudent
                            and e.cedulatown    = m.idtown
                            and e.cedulatown    = m.idtown
                            and e.ceduladepartment = m.iddepartment
                            and d.iddepartment = m.iddepartment


                            and e.address_town    = m2.idtown
                            and e.address_department = m2.iddepartment
                            and d2.iddepartment = m2.iddepartment

                            and e.idnationality  = n.idnationality
                            and e.passport_country  = p.idcountry
        and u.iduser=e.idstudent
        and u.idgroup=%d
                            and e.idstudent     = %d
                        order by ec.idcareer;
                        ", $GRUPO_ESTUDIANTE, $param_datos0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  DatosUsuario
     * @ref #6
     * @línea   #232
     */
    function DatosUsuario_select2($GRUPO_ESTUDIANTE, $param_datos0) {
        return sprintf("select distinct u.iduser as usuarioid,u.password,u.keyword
                        from tbauth_user u
                        where u.idgroup = %d
                            and u.iduser = '%s';
                        ", $GRUPO_ESTUDIANTE, $param_datos0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  DatosUsuario
     * @ref #8
     * @línea   #266
     */
    function DatosUsuario_select4($param_datos0) {
        return sprintf("select distinct uc.usuarioid,u.contrasenia,u.palabraclave,uc.apellido,uc.nombre
                        from usuario u,usuariocontrol uc
                        where uc.usuarioid = u.usuarioid
                            and uc.usuarioid = '%s';
                        ", $param_datos0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  DatosUsuario
     * @ref #9
     * @línea   #319
     */
    function DatosUsuario_select5($param_datos0) {
        return sprintf("select distinct eb.usuarioid,tb.descripcion
                        from estudiantebloqueo eb, tipobloqueo tb
                        where eb.tipo = tb.tipo
                            and eb.usuarioid = '%s';
                        ", $param_datos0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     *  -   empresa='%d'
     * @functionOrigen  ModificaInfo
     * @ref #10
     * @línea   #372
     */
    function ModificaInfo_update1($parametros2, $parametros3, $parametros4, $parametros5, $parametros9, $parametros0) {
        return sprintf("update estudiante
                        set direccion='%s',telefono='%s',
                           celular='%s',colonia='%s',empresa='%d'
                        where usuarioid='%s';
                        ", $parametros2, $parametros3, $parametros4, $parametros5, $parametros9, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaInfo
     * @ref #11
     * @línea   #381
     */
    function ModificaInfo_update2($parametros10, $parametros0) {
        return sprintf("update estudiantedatos
                        set fechanacimiento = '%s'
                        where usuarioid='%s';
                        ", $parametros10, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     *  -   empresa='%d'
     * @functionOrigen  ModificaInfo
     * @ref #12
     * @línea   #390
     */
    function ModificaInfo_update3($parametros2, $parametros3, $parametros4, $parametros5, $parametros9, $parametros0) {
        return sprintf("update personal
                        set direccion = '%s',telefono='%s',
                           celular='%s',colonia='%s',empresa='%d'
                        where personal='%s';
                        ", $parametros2, $parametros3, $parametros4, $parametros5, $parametros9, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaInfo
     * @ref #13
     * @línea   #401
     */
    function ModificaInfo_update4($parametros2, $parametros3, $parametros4, $parametros5, $parametros9, $parametros0) {
        return sprintf("update personal
                        set dirper = '%s',telper='%s',
                            celper='%s',colonia='%s',empresa='%s'
                        where regper='%s';
                        ", $parametros2, $parametros3, $parametros4, $parametros5, $parametros9, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaInfo
     * @ref #14
     * @línea   #412
     */
    function ModificaInfo_update5($USR_DATOS_USUARIO, $personal,$GRUPO, $parametros0, $parametros11) {

        return sprintf("insert into tblog_userinformationupdate
                        (username,idpersonal,date,idgroup,iduser,password,infoupdated)
                            values('%s',%d,'%s',%d,%d,'***','%s');
                        ", $USR_DATOS_USUARIO, $personal,date('Y-m-d H:i:s'), $GRUPO, $parametros0, $parametros11);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaUsuario
     * @ref #15
     * @línea   #437
     */
    function ModificaUsuario_update1($parametros6, $parametros8, $parametros1, $parametros0) {
        return sprintf("update tbauth_user
                        set password='%s', keyword='%s'
                        where idgroup = %d
                            and iduser='%s';
                        ", $parametros6, $parametros8, $parametros1, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaUsuario
     * @ref #16
     * @línea   #448
     */
    function ModificaUsuario_update2($parametros7, $parametros0) {
        return sprintf("update estudiante
                        set correo='%s'
                        where usuarioid='%s';
                        ", $parametros7, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaUsuario
     * @ref #17
     * @línea   #456
     */
    function ModificaUsuario_update3($parametros7, $parametros0) {
        return sprintf("update personal
                        set correo = '%s'
                        where personal = '%s';
                        ", $parametros7, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ModificaUsuario
     * @ref #18
     * @línea   #463
     */
    function ModificaUsuario_update4($parametros7, $parametros0) {
        return sprintf("update personal
                        set email = '%s'
                        where regper = '%s';
                        ", $parametros7, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  AlteraPIN
     * @ref #19
     * @línea   #491
     */
    function AlteraPIN_insert1($parametros0, $parametros1, $parametros4, $parametros3) {
        return sprintf("insert into usuario
                        (usuarioid,grupo,suspendido,contrasenia,palabraclave)
                            values('%s',%d,0,'%s','%s');
                        ", $parametros0, $parametros1, $parametros4, $parametros3);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  AlteraPIN
     * @ref #20
     * @línea   #502
     */
    function AlteraPIN_update1($parametros7, $parametros0) {
        return sprintf("update estudiante
                        set correo='%s'
                        where usuarioid = '%s';
                        ", $parametros7, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  AlteraPIN
     * @ref #21
     * @línea   #510
     */
    function AlteraPIN_update2($parametros7, $parametros0) {
        return sprintf("update personal
                        set correo = '%s'
                        where personal = '%s';
                        ", $parametros7, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  AlteraPIN
     * @ref #22
     * @línea   #517
     */
    function AlteraPIN_update3($parametros7, $parametros0) {
        return sprintf("update personal
                        set email = '%s'
                        where regper = '%s';
                        ", $parametros7, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  AlteraPIN
     * @ref #23
     * @línea   #527
     */
    function AlteraPIN_update4($parametros2, $parametros0) {
        return sprintf("update usuariocontrol
                        set correo = '%s'
                        where usuarioid = '%s';
                        ", $parametros2, $parametros0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     *  -    no existe la columna correo en la base de datos de ingenieria2
     * @functionOrigen  VerificaCorreo
     * @ref #24
     * @línea   #669
     */
    function VerificaCorreo_select1($param2) {
        return sprintf("select correo
                        from usuario
                        where grupo = 3
                            and correo='%s';
                        ", $param2);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     *  -    no existe la columna correo en la base de datos de ingenieria2
     * @functionOrigen  VerificaCorreo
     * @ref #25
     * @línea   #674
     */
    function VerificaCorreo_select2($param2) {
        return sprintf("select correo
                        from usuario
                        where grupo = 1
                            and correo='%s';
                        ", $param2);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  CoincideCorreo
     * @ref #26
     * @línea   #707
     */
    function CoincideCorreo_select1($param_correo0, $param_correo2) {
        return sprintf("select correo
                        from estudiante
                        where usuarioid = '%s'
                            and correo ='%s';
                        ", $param_correo0, $param_correo2);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  CoincideCorreo
     * @ref #27
     * @línea   #722
     */
    function CoincideCorreo_select2($param_correo0, $param_correo2) {
        return sprintf("select p.correo
                        from personal p
                        where p.personal = '%s'
                            and p.correo     = '%s';
                        ", $param_correo0, $param_correo2);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #28
     * @línea   #758
     */
    function BusquedaUsuario_select1_1($param0) {
        return "and e.usuarioid = '" . $param0 . "'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #29
     * @línea   #763
     */
    function BusquedaUsuario_select1_2($param3) {
        return "and e.nombre like '%" . $param3 . "%'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #30
     * @línea   #768
     */
    function BusquedaUsuario_select1_3($param4) {
        return "and e.apellido like '%" . $param4 . "%'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #31
     * @línea   #774
     */
    function BusquedaUsuario_select1_4($param3, $param4) {
        return "and e.nombre like '%" . $param3 . "%'" .
        "and e.apellido like '%" . $param4 . "%'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #32
     * @línea   #811
     */
    function BusquedaUsuario_select1($consulta) {
        return sprintf("select e.usuarioid,e.nombre,e.apellido,ec.carrera
                       from estudiante e,estudiantecarrera ec
                       where e.usuarioid = ec.usuarioid
                            %s
                       order by e.usuarioid,ec.carrera;
                       ", $consulta);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #33
     * @línea   #811
     */
    function BusquedaUsuario_select2_1($param0) {
        return "personal = '" . $param0 . "'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #34
     * @línea   #813
     */
    function BusquedaUsuario_select2_2($param3) {
        return "nombre like '%" . $param3 . "%'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #35
     * @línea   #818
     */
    function BusquedaUsuario_select2_3($param4) {
        return "apellido like '%" . $param4 . "%'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #36
     * @línea   #824
     */
    function BusquedaUsuario_select2_4($param3, $param4) {
        return "nombre like '%" . $param3 . "%' " .
        "and apellido like '%" . $param4 . "%'";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  BusquedaUsuario
     * @ref #37
     * @línea   #832
     */
    function BusquedaUsuario_select2($consulta) {
        return sprintf("select personal,nombre,apellido
                        from personal
                        where %s
                        order by personal;
                        ", $consulta);
    }


    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  DatosEstudiante
     * @ref #39
     * @línea   #917
     */
    function DatosEstudiante_select2($param_datos0, $param_datos2) {
        return sprintf("select e.idstudent as carnet,
                          e.name as nombre,
                          e.surname as apellido,
                          case when sex = 'MASCULINO' then 1 when sex='FEMENINO' then 2 else 0 end as sexo,
                          case when maritalstatus = 'SOLTERO' then 1 when maritalstatus='CASADO' then 2 when maritalstatus='CASADA' then 4 when maritalstatus='SOLTERA' then 3 else 0 end as estadocivil,
                          case when (birthday is null or birthday='1900-01-01') then '' else birthday::character varying end as fechanac,
                          idcedula as cedula,
                          cedulatown as municipiocedula,
                          ceduladepartment as departamentocedula,
                          dpi,
                          avenue_or_similar as avenida,
                          housenumber_or_similar as numerocasa,
                          apartment_or_similar as apartamento,
                          zonenumber as zona,
                          suburb_or_similar as colonia,
                          address_department as departamento_dir,
                          address_town as municipio_dir,
                          e.idnationality as nacionalidad,
                          idpassport as pasaporte,
                          case when (passport_date is null or passport_date='1900-01-01') then '' else passport_date::character varying end as fechapasaporte,
                          passport_country as paispasaporte,
                          titlename as carrera_nombre,
                          institutionname as establecimiento,
                          email as correo1,
                          email2 as correo2,
                          phone as tel1,
                          phone2 as tel2,
                          cellphone as cel1,
                          cellphone2 as cel2,
                          father as padre,
                          fatherphone as telpadre,
                          mother as madre,
                          motherphone as telmadre,
                          emergencyname as responsable,
                          emergencyphone as telresponsable
                        from tbstudent e, tbstudentcareer ec,
                          tbtown m,
                          tbdepartment d,
                          tbnationality n ,
                          tbcountry p,
                          tbtown m2,
                          tbdepartment d2
                        where e.idstudent   = ec.idstudent
                            and e.cedulatown    = m.idtown
                            and e.cedulatown    = m.idtown
                            and e.ceduladepartment = m.iddepartment
                            and d.iddepartment = m.iddepartment


                            and e.address_town    = m2.idtown
                            and e.address_department = m2.iddepartment
                            and d2.iddepartment = m2.iddepartment

                            and e.idnationality  = n.idnationality
                            and e.passport_country  = p.idcountry
                            and e.idstudent     = %d
                            and ec.idcareer      = %d
                        order by ec.idcareer;
                        ", $param_datos0, $param_datos2);
    }

    /**
     * @detalleBD
     * @diagramaBD
     * @cambios
     * @functionOrigen  DatosEstudiante
     * @ref #39
     * @línea   #917
     */
    function DatosDocente_select2($param_datos0) {
        return sprintf("select e.idteacher as personal,
                          e.name as nombre,
                          e.surname as apellido,
                          case when sex = 'MASCULINO' then 1 when sex='FEMENINO' then 2 else 0 end as sexo,
                          sex as sexo_nombre,
                          case when maritalstatus = 'SOLTERO' then 1 when maritalstatus='CASADO' then 2 when maritalstatus='CASADA' then 4 when maritalstatus='SOLTERA' then 3 else 0 end as estadocivil,
                          case when (birthday is null or birthday='1900-01-01') then '' else birthday::character varying end as fechanac,
                          idcedula as cedula,
                          cedula_town as municipiocedula,
                          case when cedula_town=0 then '' else m.name end as cedula_munic_nombre,
                          cedula_department as departamentocedula,
                          case when cedula_department=0 then '' else d.name end as cedula_depto_nombre,
                          dpi,
                          address as direccion,
                          address_department as departamento_dir,
                          case when address_department=0 then '' else d2.name end as dir_depto_nombre,
                          address_town as municipio_dir,
                          case when address_town=0 then '' else m2.name end as dir_munic_nombre,
                          e.idnationality as nacionalidad,
                          n.gentilicio as nacionalidad_nombre,
                          email as correo1,
                          phone as tel1,
                          cellphone as cel1
                        from tbteacher e,
                          tbtown m,
                          tbdepartment d,
                          tbnationality n ,
                          tbtown m2,
                          tbdepartment d2
                        where e.cedula_town    = m.idtown
                            and e.cedula_town    = m.idtown
                            and e.cedula_department = m.iddepartment
                            and d.iddepartment = m.iddepartment

                            and e.address_town    = m2.idtown
                            and e.address_department = m2.iddepartment
                            and d2.iddepartment = m2.iddepartment
                            and e.idnationality  = n.idnationality
                            and e.idteacher     = %d
                        order by idteacher;
                        ", $param_datos0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  DarPromedioYcreditos
     * @ref #40
     * @línea   #986
     */
    function DarPromedioYcreditos_select1($carnet, $carrera) {
        return "select promedio,creditos
                from estudiantecarrera
                where usuarioid='$carnet'
                    and carrera='$carrera';";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ListarMunicipios
     * @ref #41
     * @línea   #1014
     */
    function ListarMunicipios_select1() {
        return "select iddepartment, idtown, name
                from tbtown
                where NOT(iddepartment=0 OR idtown=0)
                order by iddepartment;";
    }

    /**
     * @detalleBD
     * @diagramaBD
     * @cambios
     * @functionOrigen  ListarNacionalidades
     * @ref #41
     * @línea   #1014
     */
    function ListarNacionalidades_select1() {
        return "select idnationality, country, gentilicio
                from tbnationality
                where NOT(idnationality=0 OR idnationality=99)
                order by idnationality;";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  ListarDepartamentos
     * @ref #42
     * @línea   #1052
     */
    function ListarDepartamentos_select1() {
        return "select iddepartment, name, idorden
                from tbdepartment
                where NOT(iddepartment=0)
                order by iddepartment;";
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  datosGuardados
     * @ref #43
     * @línea   #1084
     */
    function datosGuardados_select1($carne) {
        return sprintf("select *
                        from infograduandos
                        where usuarioid='%s'
                        ", $carne);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  guardarDatosGraduandos
     * @ref #44
     * @línea   #1143
     */
    function guardarActualizacionDatos_update1($parametros1, $parametros2, $parametros3, $parametros4, $parametros5,
                                            $parametros6, $parametros7, $parametros8, $parametros9, $parametros10,
                                            $parametros11, $parametros12, $parametros13, $parametros14, $parametros15,
                                            $parametros16, $parametros17, $parametros18, $parametros19, $parametros20,
                                            $parametros21, $parametros22,$parametros23,$parametros24,$parametros25,
                                            $parametros26,$parametros27,$parametros28,$parametros29,$parametros30,
                                            $parametros31,$parametros32,$carnet) {
        return sprintf("update tbstudent
                        set
                         sex='%s',
                         maritalstatus='%s',
                         birthday='%s',
                         idcedula='%s',
                         ceduladepartment=%d,
                         cedulatown=%d,
                         dpi='%s',
                         avenue_or_similar='%s',
                         housenumber_or_similar='%s',
                         apartment_or_similar='%s',
                         zonenumber='%s',
                         suburb_or_similar='%s',
                         address_department=%d,
                         address_town=%d,
                         idnationality=%d,
                         idpassport='%s',
                         passport_date='%s',
                         passport_country=%d,
                         titlename='%s',
                         institutionname='%s',
                         email='%s',
                         email2='%s',
                         phone='%s',
                         phone2='%s',
                         fatherphone='%s',
                         motherphone='%s',
                         emergencyphone='%s',
                         mother='%s',
                         father='%s',
                         emergencyname='%s',
                         cellphone='%s',
                         cellphone2='%s'
                        where idstudent = %d;
                        ",
            $parametros1, $parametros2, $parametros3, $parametros4, $parametros5,
            $parametros6, $parametros7, $parametros8, $parametros9, $parametros10,
            $parametros11, $parametros12, $parametros13, $parametros14, $parametros15,
            $parametros16, $parametros17, $parametros18, $parametros19, $parametros20,
            $parametros21, $parametros22,$parametros23, $parametros24,$parametros25,
            $parametros26, $parametros27,$parametros28, $parametros29,$parametros30,
            $parametros31, $parametros32,$carnet);
    }

    /**
     * @detalleBD
     * @diagramaBD
     * @cambios
     * @functionOrigen  guardarDatosGraduandos
     * @ref #44
     * @línea   #1143
     */
    function guardarActualizacionDatosDocente_update1($parametros1, $parametros2, $parametros3, $parametros4, $parametros5,
                                               $parametros6, $parametros7, $parametros8, $parametros9, $parametros10,
                                               $parametros11, $parametros12, $parametros13,$personal) {
        return sprintf("update tbteacher
                        set
                         sex='%s',
                         birthday='%s',
                         idcedula='%s',
                         cedula_department=%d,
                         cedula_town=%d,
                         dpi='%s',
                         address='%s',
                         address_department=%d,
                         address_town=%d,
                         idnationality=%d,
                         email='%s',
                         phone='%s',
                         cellphone='%s'
                        where idteacher = %d;
                        ",
            $parametros1, $parametros2, $parametros3, $parametros4, $parametros5,
            $parametros6, $parametros7, $parametros8, $parametros9, $parametros10,
            $parametros11, $parametros12, $parametros13,$personal);
    }

    /**
     *
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  guardarDatosGraduandos
     * @ref #45
     * @línea   #1161
     */
    function guardarDatosGraduandos_insert1($parametros0, $parametros1, $parametros2, $parametros3, $parametros4, $parametros5, $parametros6, $parametros7, $parametros8, $parametros9, $parametros10, $parametros11, $parametros12, $parametros13, $parametros14, $parametros15, $parametros16, $parametros17, $parametros18, $parametros19, $parametros20, $parametros21) {
        return sprintf("insert into infograduandos
                          (usuarioid,cedula,extdepto,extmunic,
                           lugar_nac,titulo,establecimiento,trabaja,lugartrabajo,cargotrabajo,direccion_trabajo,
                           nombre_padre,nombre_madre,cambiocarrera,carrera_origen,traslado_facultad,
                           facultad_origen,traslado_universidad,universidad_origen,equivalencias,anioingreso,mesingreso)
                        values('%s','%s',%d,%d,'%s','%s','%s',%d,'%s','%s','%s','%s','%s',%d,'%s',%d,'%s',%d,'%s',%d,%d,%d);
                        ", $parametros0, $parametros1, $parametros2, $parametros3, $parametros4, $parametros5, $parametros6, $parametros7, $parametros8, $parametros9, $parametros10, $parametros11, $parametros12, $parametros13, $parametros14, $parametros15, $parametros16, $parametros17, $parametros18, $parametros19, $parametros20, $parametros21);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  pathfoto
     * @ref #46
     * @línea   #1196
     */
    function pathfoto_select1($param_datos0){
        return sprintf("select * from f_geturl_foto_personal('%s') as id;",$param_datos0);
    }

    /**
     * @detalleBD 
     * @diagramaBD 
     * @cambios
     * @functionOrigen  pathfoto
     * @ref #47
     * @línea   #1211
     */
    function pathfoto_select2($param_datos0){
        return sprintf("select * from f_geturl_foto_estudiante('%s') as id;",$param_datos0);
    }



}
//fin consultas respecto a la versión 9.0

?>