<?php
    /**
     * Created by PhpStorm.
     * User: emsaban
     * Date: 7/08/14
     * Time: 02:22 PM
     */

    include_once("class.phpmailer.php"); // utilizada para envio de correo
    include_once("class.manejostring.inc.php"); //para strings

    /*
     * Incluyendo archivo con sentencias SQL
     */
    include_once("sql/biblioteca_class.manejopin.sql.php");

//******************************************
//        Biblioteca class.manejopin.inc.php
// Clase para el manejo del Pin de usuario
//******************************************

    class Pin
    {
        var $mUsuarioid;
        var $mGrupo;
        var $mContrasenia;
        var $mCorreo;
        var $mNombre;
        var $mApellido;
        var $mDireccion;
        var $mColonia;
        var $mTelefono;
        var $mCelular;
        var $mEmpresaCel;
        var $mCarrera;
        var $mOtraCarrera;
        var $mPalabraClave;
        var $mFechaNac;
        var $mSexo;
        var $mEstadoCivil;
        var $mEdad;
        var $mGentilicio;
        var $mMunicipio;
        var $mDepartamento;
        var $mSuspension;
        var $mPromedio;
        var $mCreditos;
        var $mId;

        /*
         * Variable para utilizar las consultas
         */
        var $gsql;


        // ***************************************************************
        // Constructor
        // ***************************************************************
        function Pin($param_grupo)
        { //   Se realiza una conexion con Servidor de Base de datos

            $_SESSION["sBdd"] = NEW DB_Sql;
            $_SESSION["sBdd"]->Database = BD_NAME_INGENIERIA2; //INGENIERIA2;
            $_SESSION["sBdd"]->Host = BD_HOST_INGENIERIA2; //HOST_WEB;
            $_SESSION["sBdd"]->Port = BD_PORT_INGENIERIA2;
            $_SESSION["sBdd"]->User = BD_USR_DATOS_USUARIO; // Usuario para hacer
            $_SESSION["sBdd"]->Password = BD_PWD_DATOS_USUARIO; // acciones en la BDD
            if ($_SESSION["sBdd"]->connect() == 100) {
                echo "error 100";
                die;
            }
            if ($param_grupo == 1) {
                //conexion a base datos personal2 en la 15
                $_SESSION["sBdd1"] = NEW DB_Sql;
                $_SESSION["sBdd1"]->Database = BD_NAME_PERSONAL2; //PERSONAL2;
                $_SESSION["sBdd1"]->Host = BD_HOST_PERSONAL2; //HOST_WEB;
                $_SESSION["sBdd1"]->Port = BD_PORT_PERSONAL2;
                $_SESSION["sBdd1"]->User = BD_USR_DATOS_USUARIO; // Usuario para hacer
                $_SESSION["sBdd1"]->Password = BD_PWD_DATOS_USUARIO; // acciones en la BDD
                if ($_SESSION["sBdd1"]->connect() == 100) {
                    echo "error 100";
                    die;
                }
//            $_SESSION["sBdd1"]->connect2();
                //conexion a base datos personal1 en la 10
                $_SESSION["sBdd2"] = NEW DB_Sql;
                $_SESSION["sBdd2"]->Database = BD_NAME_PERSONAL; //PERSONAL;
                $_SESSION["sBdd2"]->Host = BD_HOST_PERSONAL; //HOST_WEB;
                $_SESSION["sBdd2"]->Port = BD_PORT_PERSONAL;
                $_SESSION["sBdd2"]->User = BD_USR_DATOS_USUARIO; // Usuario para hacer
                $_SESSION["sBdd2"]->Password = BD_PWD_DATOS_USUARIO; // acciones en la BDD

                if ($_SESSION["sBdd2"]->connect() == 100) {
                    echo "error 100";
                    die;
                }
            }

            /*
             * Instanciando la variable en la clase donde se encuentran las consultas
             */
            $this->gsql = new biblio_PinSQL();
        }

        // *********************************************************
        // Funcion que verifica si el usuario existe en estudiante o
        // registropersonal
        // *********************************************************
        function ExisteUsuario($param)
        {
            $existe_cate = 0;
            switch ($param[1]) {
                case GRUPO_ESTUDIANTE : //$query_usuario = sprintf("select e.usuarioid,e.nombre,e.apellido,ec.carrera
//                                                         from estudiante e,estudiantecarrera ec
//                                                         where e.usuarioid = ec.usuarioid
//							                             and e.usuarioid = '%s' order by ec.carrera;",
//														 $param[0]);

                    $query_usuario = $this->gsql->ExisteUsuario_select1($param[0]);

                    $_SESSION["sBdd"]->query($query_usuario);
                    break;
                case GRUPO_DOCENTE    : //$query_usuario = sprintf("select distinct personal
//                                                        from horariodetalle
//														where personal = '%s';",$param[0]);

                    $query_usuario = $this->gsql->ExisteUsuario_select2($param[0]);

                    $_SESSION["sBdd"]->query($query_usuario);
                    if ($_SESSION["sBdd"]->num_rows() > 0) { //si existe
//		                       	   $query_usuario2 = sprintf("select p.nombre, p.apellido, p.direccion, p.colonia, p.telefono,
//								   p.celular, p.empresa, p.correo, t.puesto
//								   from personal p, titularidad t
//								   where p.personal = t.personal
//								   and p.personal = '%s';",$param[0]);

                        $query_usuario2 = $this->gsql->ExisteUsuario_select3($param[0]);

                        $_SESSION["sBdd1"]->query($query_usuario2);
                        if ($_SESSION["sBdd1"]->num_rows() > 0) {
                            $existe_cate = 1;
                        }
                    }
                    break;
                case GRUPO_VACACIONES : // $query_vaca = sprintf("select distinct uc.usuarioid,uc.apellido,uc.nombre,uc.puesto
//                                                        from Usuariocontrol uc
//                                                       where uc.usuarioid = '%s';",$param[0]);

                    $query_vaca = $this->gsql->ExisteUsuario_select4($param[0]);

                    print $query_vaca . "<br>";
                    $_SESSION["sBdd"]->query($query_vaca);
                    if ($_SESSION["sBdd"]->num_rows() > 0) {
                        $existente = 1;
                    } //No existe el usuario
                    else {
                        $existente = 0;
                    } //Si existen datos
                    break;
                case GRUPO_AUXILIAR   :
                    break; //Para auxiliares
            }

            if ($_SESSION["sBdd"]->num_rows() < 1) {
                return 0; //No existe el usuario
            } else {
                switch ($param[1]) {
                    case GRUPO_ESTUDIANTE :
                        $_SESSION["sBdd"]->next_record();
                        $this->mUsuarioid = trim($_SESSION["sBdd"]->f('usuarioid'));
                        $this->mCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                        //Para estudiantes con carreras dobles
                        if ($_SESSION["sBdd"]->num_rows() == 2) {
                            $_SESSION["sBdd"]->next_record();
                            $this->mOtraCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                        }
                        $this->mNombre = trim($_SESSION["sBdd"]->f('nombre'));
                        $this->mApellido = trim($_SESSION["sBdd"]->f('apellido'));
                        break;
                    case GRUPO_DOCENTE    :
                        if ($existe_cate == 1) {
                            $_SESSION["sBdd1"]->next_record();
                            $this->mUsuarioid = trim($_SESSION["sBdd1"]->f('personal'));
                            $this->mNombre = trim($_SESSION["sBdd1"]->f('nombre'));
                            $this->mApellido = trim($_SESSION["sBdd1"]->f('apellido'));
                            $this->mCarrera = trim($_SESSION["sBdd1"]->f('puesto'));
                        }
                        break;
                    case GRUPO_VACACIONES :
                        $_SESSION["sBdd"]->next_record();
                        $this->mUsuarioid = trim($_SESSION["sBdd"]->f('usuarioid'));
                        $this->mNombre = trim($_SESSION["sBdd"]->f('nombre'));
                        $this->mApellido = trim($_SESSION["sBdd"]->f('apellido'));
                        $this->mCarrera = trim($_SESSION["sBdd"]->f('puesto'));
                        break;
                    case GRUPO_AUXILIAR   :
                        break; //Para auxiliares
                }
                return 1;
            }
        } //Fin de Existe Usuario

        // ***************************************************************
        // Funcion que obtiene los datos personales de un usuario válido y
        // a la vez existente en el Sistema, ya sea Estudiante o Catedrático
        // ***************************************************************
        function DatosUsuario($param_datos)
        {
            $existente = 0;
            $existe_cate = 0;
            switch ($param_datos[1]) {
                case GRUPO_ESTUDIANTE : // $query_usuario = sprintf("select e.usuarioid, u.contrasenia, e.nombre, e.apellido, e.direccion,
//								e.colonia, e.telefono, e.celular, e.empresa, u.palabraclave, e.correo, ec.carrera,e.sexo,ec.promedio,ec.creditos,
//								ed.fechanacimiento
//								from estudiante e,usuario u, estudiantecarrera ec, estudiantedatos ed
//								where e.usuarioid   = u.usuarioid
//								and e.usuarioid     = ec.usuarioid
//								and e.usuarioid     = ed.usuarioid
//								and u.grupo         = %d
//							  	and e.usuarioid     = '%s' order by ec.carrera;", GRUPO_ESTUDIANTE, $param_datos[0]);

                    $query_usuario = $this->gsql->DatosUsuario_select1(GRUPO_ESTUDIANTE, $param_datos[0]);

                    //comprobar
                    //echo "query --".$query_usuario."--"; die;

                    $_SESSION["sBdd"]->query($query_usuario); //ejecuta query

//                                if ($_SESSION["sBdd"]->num_rows()>1)
                    if ($_SESSION["sBdd"]->num_rows() > 0) {
                        $existente = 1;
                    } //No existe el usuario
                    else {
                        $existente = 0;
                    } //Si existen datos
                    break;
                case GRUPO_DOCENTE    : //Comentarizado por Pancho López el 24/05/2010
                    /*
                    $query_usuario1 = sprintf("select distinct u.usuarioid,u.contrasenia,u.palabraclave
                                               from horariodetalle hd, usuario u
                                               where hd.personal = u.usuarioid
                                               and u.grupo = %d and u.usuarioid = '%s';",GRUPO_DOCENTE,$param_datos[0]);
                    */
                    //Modificado por Pancho López el 24/04/2010 para soportar sistema de Ingreso de Notas de Actividades
//		                        $query_usuario1 = sprintf("select distinct u.usuarioid,u.contrasenia,u.palabraclave
//                                                           from usuario u
//                                                           where u.grupo = %d and u.usuarioid = '%s';",GRUPO_DOCENTE,$param_datos[0]);

                    $query_usuario1 = $this->gsql->DatosUsuario_select2(GRUPO_DOCENTE, $param_datos[0]);

                    $_SESSION["sBdd"]->query($query_usuario1);
                    if ($_SESSION["sBdd"]->num_rows() < 1) {
                        $existente = 0; //No existe el usuario
                    } else {
//								  $query_usuario2 = sprintf("select p.nombre,p.apellido,p.direccion,
//                                                             p.colonia,p.telefono,p.celular,p.empresa,
//													  	     p.correo,t.puesto,pd.nacimientofecha,
//															 p.sexo,p.estadocivil,pd.nacionalidad,
//															 pd.nacimientomunicipio,pd.nacimientodepartamento
//                                                             from personal p, titularidad t, personaldocumento pd
//                                                             where p.personal = t.personal
//															 and p.personal = pd.personal
//                                                             and p.personal = '%s';",$param_datos[0]);

                        $query_usuario2 = $this->gsql->DatosUsuario_select3($param_datos[0]);

                        $_SESSION["sBdd1"]->query($query_usuario2);

                        if ($_SESSION["sBdd1"]->num_rows() > 0) {
                            $existe_cate = 1;
                            $existente = 1; //Si existen datos
                        }
                    }
                    break;
                case GRUPO_VACACIONES : // $query_vaca = sprintf("select distinct uc.usuarioid,u.contrasenia,u.palabraclave,uc.apellido,uc.nombre
//                                                           from usuario u,usuariocontrol uc
//                                                           where uc.usuarioid = u.usuarioid
//                                                           and uc.usuarioid = '%s';",$param_datos[0]);

                    $query_vaca = $this->gsql->DatosUsuario_select4($param_datos[0]);

                    print $query_vaca . "<br>";
                    $_SESSION["sBdd"]->query($query_vaca);
                    if ($_SESSION["sBdd"]->num_rows() > 0) {
                        $existente = 1;
                    } //No existe el usuario
                    else {
                        $existente = 0;
                    } //Si existen datos
                    break;
                case GRUPO_AUXILIAR   :
                    break; //Para auxiliares
            }
//echo "esta la info ".$existente; die;
            if ($existente == 1) {
                switch ($param_datos[1]) {
                    case GRUPO_ESTUDIANTE :
                        $_SESSION["sBdd"]->next_record();
                        $this->mUsuarioid = trim($_SESSION["sBdd"]->f('usuarioid'));
                        //echo " esto debe ser==>>> ".$this->mUsuarioid; die;
                        $this->mContrasenia = $_SESSION["sBdd"]->f('contrasenia');
                        $this->mNombre = trim($_SESSION["sBdd"]->f('nombre'));
                        $this->mApellido = trim($_SESSION["sBdd"]->f('apellido'));
                        $this->mDireccion = trim($_SESSION["sBdd"]->f('direccion'));
                        $this->mColonia = trim($_SESSION["sBdd"]->f('colonia'));
                        $this->mTelefono = trim($_SESSION["sBdd"]->f('telefono'));
                        $this->mCorreo = trim($_SESSION["sBdd"]->f('correo'));
                        $this->mCelular = trim($_SESSION["sBdd"]->f('celular'));
                        $this->mEmpresaCel = trim($_SESSION["sBdd"]->f('empresa'));
                        $this->mPalabraClave = trim($_SESSION["sBdd"]->f('palabraclave'));
                        $this->mCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                        $this->mFechaNac = trim($_SESSION["sBdd"]->f('fechanacimiento'));
                        $this->mSexo = trim($_SESSION["sBdd"]->f('sexo'));
                        $this->mEstadoCivil = trim($_SESSION["sBdd"]->f('estadocivil'));
                        $this->mGentilicio = trim($_SESSION["sBdd"]->f('gentilicio'));
                        $this->mMunicipio = trim($_SESSION["sBdd"]->f('municipio'));
                        $this->mDepartamento = trim($_SESSION["sBdd"]->f('departamento'));

                        // lineas agregadas para peticionJD
                        $this->mPromedio = $_SESSION["sBdd"]->f('promedio');
                        $this->mCreditos = $_SESSION["sBdd"]->f('creditos');
                        //Para estudiantes con carreras dobles

                        if ($_SESSION["sBdd"]->num_rows() == 2) {
                            $_SESSION["sBdd"]->next_record();
                            $this->mOtraCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                        }
                        //Para alguna suspension

//								  $query_usuario3 = sprintf("select distinct eb.usuarioid,tb.descripcion
//								                             from estudiantebloqueo eb, tipobloqueo tb
//															 where eb.tipo = tb.tipo
//															 and eb.usuarioid = '%s';",
//															 $param_datos[0]);

                        $query_usuario3 = $this->gsql->DatosUsuario_select5($param_datos[0]);

                        $_SESSION["sBdd"]->query($query_usuario3);
                        //echo "paso 1 ".$query_usuario3; die;
                        if ($_SESSION["sBdd"]->num_rows() > 0) {
                            $_SESSION["sBdd"]->next_record();
                            $this->mSuspension = trim($_SESSION["sBdd"]->f('descripcion'));
                        }
                        break;
                    case GRUPO_DOCENTE    :
                        if ($existe_cate == 1) {
                            $_SESSION["sBdd"]->next_record();
                            $_SESSION["sBdd1"]->next_record();
                            $this->mUsuarioid = trim($_SESSION["sBdd"]->f('usuarioid'));
                            $this->mContrasenia = $_SESSION["sBdd"]->f('contrasenia');
                            $this->mNombre = trim($_SESSION["sBdd1"]->f('nombre'));
                            $this->mApellido = trim($_SESSION["sBdd1"]->f('apellido'));
                            $this->mDireccion = trim($_SESSION["sBdd1"]->f('direccion'));
                            $this->mColonia = trim($_SESSION["sBdd1"]->f('colonia'));
                            $this->mTelefono = trim($_SESSION["sBdd1"]->f('telefono'));
                            $this->mCorreo = trim($_SESSION["sBdd1"]->f('correo'));
                            $this->mCelular = trim($_SESSION["sBdd1"]->f('celular'));
                            $this->mEmpresaCel = trim($_SESSION["sBdd1"]->f('empresa'));
                            $this->mPalabraClave = trim($_SESSION["sBdd"]->f('palabraclave'));
                            $this->mCarrera = trim($_SESSION["sBdd1"]->f('puesto'));
                            $this->mFechaNac = trim($_SESSION["sBdd1"]->f('nacimientofecha'));
                            $this->mSexo = trim($_SESSION["sBdd1"]->f('sexo'));
                            $this->mEstadoCivil = trim($_SESSION["sBdd1"]->f('estadocivil'));
                            $this->mGentilicio = trim($_SESSION["sBdd1"]->f('nacionalidad'));
                            $this->mMunicipio = trim($_SESSION["sBdd1"]->f('nacimientomunicipio'));
                            $this->mDepartamento = trim($_SESSION["sBdd1"]->f('nacimientodepartamento'));
                        }
                        break;
                    case GRUPO_VACACIONES :
                        break;
                    case GRUPO_AUXILIAR   :
                        break; //Para auxiliares
                }
                return 1;
            } else {
                return 0;
            }
        } // Fin de Datos Usuario

        //**************************************************************************
        //Esta función actualiza los valores personales de un usuario,
        //tambien inserta BitacoraUsuario, tanto para estudiantes como catedraticos.
        //**************************************************************************
        function ModificaInfo($parametros)
        {
            switch ($parametros[1]) {
                case GRUPO_ESTUDIANTE: // $query_update = sprintf("update estudiante set direccion='%s',telefono='%s',
//                               celular='%s',colonia='%s',empresa='%s'
//                               where usuarioid='%s';",$parametros[2],$parametros[3],$parametros[4],
//                                                           $parametros[5],$parametros[9],$parametros[0]);

                    $query_update = $this->gsql->ModificaInfo_update1($parametros[2], $parametros[3], $parametros[4],
                        $parametros[5], $parametros[9], $parametros[0]);

                    $resultado = $_SESSION["sBdd"]->query($query_update);


//                               $query_update = sprintf("update estudiantedatos set fechanacimiento = '%s'
//                                                         where usuarioid='%s';",$parametros[10],$parametros[0]);

                    $query_update = $this->gsql->ModificaInfo_update2($parametros[10], $parametros[0]);

                    $resultado = $_SESSION["sBdd"]->query($query_update);
                    break;
                case GRUPO_DOCENTE   : // $query_update = sprintf("update personal set direccion = '%s',telefono='%s',
//                               celular='%s',colonia='%s',empresa='%s' where personal='%s';",
//                               $parametros[2],$parametros[3],$parametros[4],$parametros[5],
//                               $parametros[9],$parametros[0]);

                    $query_update = $this->gsql->ModificaInfo_update3($parametros[2], $parametros[3], $parametros[4], $parametros[5],
                        $parametros[9], $parametros[0]);

                    $resultado = $_SESSION["sBdd1"]->query($query_update);

//							   $query_update1 = sprintf("update personal set dirper = '%s',telper='%s',
//                                                         celper='%s',colonia='%s',empresa='%s'
//														 where regper='%s';",
//                                                         $parametros[2],$parametros[3],$parametros[4],
//														 $parametros[5],$parametros[9],$parametros[0]);

                    $query_update1 = $this->gsql->ModificaInfo_update4($parametros[2], $parametros[3], $parametros[4],
                        $parametros[5], $parametros[9], $parametros[0]);

                    $resultado1 = $_SESSION["sBdd2"]->query($query_update1);

//                               $query_insert = sprintf("insert into bitacorausuario (usuario,fechahora,
//                                                        grupo,usuarioid,contrasenia,datosmodificados)
//                                                        values('%s','%s',%d,'%s',null,'%s');",
//                                                        USR_DATOS_USUARIO,date('Y-m-d H:i:s'),GRUPO_DOCENTE,$parametros[0],$parametros[11]);
//                                                        $resultado = $_SESSION["sBdd"]->query($query_insert);

                    $query_insert = $this->gsql->ModificaInfo_update5(USR_DATOS_USUARIO, date('Y-m-d H:i:s'), GRUPO_DOCENTE, $parametros[0], $parametros[11]);
                    $resultado = $_SESSION["sBdd"]->query($query_insert);

                    break;
                case GRUPO_AUXILIAR   :
                    break; // Para auxiliares
                case GRUPO_VACACIONES :
                    break; // Para usuarios del curso de Vacaciones
            }

            if ($resultado == 0) {
                return 0;
            }

            return 1;
        }



        //********************************************************************
        //Esta función actualiza los valores de un usuario, como contrasenia,correo
        //y palabraclave tambien inserta BitacoraUsuario, tanto para estudiantes
        //como catedraticos.
        //********************************************************************
        function ModificaUsuario($parametros)
        {
//      $query_update = sprintf("update usuario set contrasenia='%s', palabraclave='%s'
//                              where grupo = %d and usuarioid='%s';",
//                              $parametros[6],$parametros[8],$parametros[1],$parametros[0]);

            $query_update = $this->gsql->ModificaUsuario_update1($parametros[6], $parametros[8], $parametros[1], $parametros[0]);

            $resultado1 = $_SESSION["sBdd"]->query($query_update);

            if ($resultado1 == 0) {
                return 0;
            } else {
                switch ($parametros[1]) {
                    case GRUPO_ESTUDIANTE : // $query_update = sprintf("update estudiante set correo='%s'
//                                                           where usuarioid='%s';",
//														   $parametros[7],$parametros[0]);

                        $query_update = $this->gsql->ModificaUsuario_update2($parametros[7], $parametros[0]);

                        $resultado2 = $_SESSION["sBdd"]->query($query_update);
                        break;
                    case GRUPO_DOCENTE    : // $query_update = sprintf("update personal set correo = '%s'
//                                                           where personal = '%s';",
//														   $parametros[7],$parametros[0]);

                        $query_update = $this->gsql->ModificaUsuario_update3($parametros[7], $parametros[0]);

                        $resultado2 = $_SESSION["sBdd1"]->query($query_update);
//								  $query_update1 = sprintf("update personal set email = '%s'
//                                                            where regper = '%s';",
//															$parametros[7],$parametros[0]);

                        $query_update1 = $this->gsql->ModificaUsuario_update4($parametros[7], $parametros[0]);

                        $resultado1 = $_SESSION["sBdd2"]->query($query_update1);
                        break;
                    case GRUPO_VACACIONES :
                        break; // Para usuarios del curso de Vacaciones
                    case GRUPO_AUXILIAR   :
                        break; //Para auxiliares
                }
                if ($resultado2 == 0) {
                    return 0;
                }

                return 1;
            }
        }

        //**********************************************************************
        //Esta función actualiza el valor de un PIN, ya sea por generación o
        //modificación, tambien inserta en UsuarioGrupo y BitacoraUsuario, tanto
        //para estudiantes como catedraticos por medio de triggers.
        //**********************************************************************
        function AlteraPIN($parametros)
        {
            $insertar = 0;
            $resultado = 0;
            //Quita blancos de palabra clave
            $parametros[4] = ltrim($parametros[4]);
            $parametros[4] = rtrim($parametros[4]);
            //Insercion en tabla Usuario
//      $query_usuario = sprintf("insert into usuario (usuarioid,grupo,suspendido,contrasenia,palabraclave)
//                                values('%s',%d,0,'%s','%s'); ",$parametros[0],$parametros[1],$parametros[4],$parametros[3]);

            $query_usuario = $this->gsql->AlteraPIN_insert1($parametros[0], $parametros[1], $parametros[4], $parametros[3]);

            $resultado1 = $_SESSION["sBdd"]->query($query_usuario);

            if ($resultado1 == 0) {
                return 0;
            } else {
                switch ($parametros[1]) {
                    case GRUPO_ESTUDIANTE : // $query_update = sprintf("update estudiante set correo='%s'
//                                                           where usuarioid = '%s';",
//														   $parametros[7],$parametros[0]);

                        $query_update = $this->gsql->AlteraPIN_update1($parametros[7], $parametros[0]);

                        $resultado2 = $_SESSION["sBdd"]->query($query_update);
                        break;
                    case GRUPO_DOCENTE    : // $query_update = sprintf("update personal set correo = '%s'
//                                                           where personal = '%s';",
//														   $parametros[7],$parametros[0]);

                        $query_update = $this->gsql->AlteraPIN_update2($parametros[7], $parametros[0]);

                        $resultado2 = $_SESSION["sBdd1"]->query($query_update);
//                                  $query_update1 = sprintf("update personal set email = '%s'
//                                                            where regper = '%s';",
//														    $parametros[7],$parametros[0]);

                        $query_update1 = $this->gsql->AlteraPIN_update3($parametros[7], $parametros[0]);

                        $resultado1 = $_SESSION["sBdd2"]->query($query_update1);
                        break;

                    case GRUPO_AUXILIAR   :
                        break; //Para auxiliares
                    case GRUPO_VACACIONES : // $query_update = sprintf("update usuariocontrol set correo = '%s'
//                                                           where usuarioid = '%s';",
//														   $parametros[2],$parametros[0]);

                        $query_update = $this->gsql->AlteraPIN_update4($parametros[2], $parametros[0]);

                        $resultado2 = $_SESSION["sBdd"]->query($query_update);
                        break;
                }

                if ($resultado2 == 0) {
                    return 0;
                }

                return 1;
            }
        }

        //******************************************************************
        //Esta función genera un valor numérico aleatorio entre los rangos
        //1111 y 9999 para el valor del Pin que será asignado a cada usuario
        //que ingrese a Consulta o Asignación por Internet.
        //******************************************************************
        function GeneraPin()
        {
            $seed = 0;
            $Pwd_Gen = 0;
            $Salir = 0;

            //La semilla se toma de la función time del sistema
            $seed = time(NULL);
            $seed = $seed % 9999;

            mt_srand($seed);
            while ($Salir != 1) {
                $Pwd_Gen = mt_rand(1111, 9999); //Obtiene el valor aleatorio
                //Si no se encuentra en el rango permitido, genera otro valor
                if (($Pwd_Gen >= 1111) && ($Pwd_Gen <= 9999)) $Salir = 1;
            }

            return ($Pwd_Gen);
        }

        //*****************************************************
        //Envio de boleta de asignacion por correo electrónico.
        //*****************************************************
        function EnviaPinCorreo($param_envio)
        {
            $mail = new phpmailer();
            $obj_cad = new ManejoString;

            $to = trim($param_envio[2]);
            $clave = trim($param_envio[4]);
            $titulo = trim($param_envio[10]);

            if (isset($to) && $to != "") {
                $mail->Mailer = "mail";
                $mail->Host = "ing.usac.edu.gt";
                $mail->Subject = $titulo; // Titulo del mensaje
                $mail->AddAddress($to);
                $mail->AddReplyTo("desarrollo_cci@ing.usac.edu.gt");
                $mail->IsHTML(TRUE);

                $body_val1 .= "<br><CENTER>Usuario     :  <strong>" . $param_envio[0] . " </strong></CENTER>";

                //En el caso de diferentes Grupos para personalizar mensajes tanto a
                //Docentes, auxiliares y estudiantes
                switch ($param_envio[1]) {
                    case GRUPO_DOCENTE    :
                        $body_val2 .= "<br><br> <font color='#FF0000'><strong>Aviso</strong></font> : Entre los servicios que puede obtener al hacer uso del portal de ingeniería están los siguientes :";
                        $body_val2 .= "    <br>          Listado de alumnos asignados en pantalla.";
                        $body_val2 .= "    <br>          Descarga de archivo para la opción de ingreso de notas.";
                        $body_val2 .= "    <br>          Ingreso de notas en línea.";
                        $body_val2 .= "    <br>          Listado de actividades.";
                        $body_val2 .= "    <br>          Horarios de cursos.";
                        $descripcion = $obj_cad->StringPuesto($this->mCarrera);
                        $body_val3 .= "<br>Puesto  : <strong>$descripcion</strong><br>";
                        break;
                    case GRUPO_AUXILIAR   :
                        break;
                    case GRUPO_ESTUDIANTE :
                        $body_val2 .= "<br><font color='#FF0000'><strong>Aviso</strong></font> : Esta le servirá para Consultar Información y otros servicios como Inscripción y Asignación de Cursos.";
                        $descripcion = $obj_cad->StringCarrera($this->mCarrera);
                        $body_val3 .= "<br>Carrera  : <strong>$descripcion</strong><br>";
                        break;
                    case GRUPO_VACACIONES :
                        break; // Para usuarios del curso de Vacaciones
                }

                $mail->Body = <<<EOT
       <HTML>
       <HEAD>
       <TITLE>$titulo</TITLE>
       </HEAD>

       <BODY>
       <font color='#0000FF' face='Tahoma'>
       <p>
       <br>
       Portal de Ingeniería : <strong>http://www.ingenieria.usac.edu.gt</strong>
       <br><br>
       Nombre  : <strong>$this->mNombre $this->mApellido</strong>
       $body_val3
       $body_val1
       <br>
       <CENTER>CONTRASEÑA : <strong>$clave</strong></CENTER>
       $body_val2
       <br><br>
       <font color='#FF0000'><strong>Nota Importante</strong></font> : La Contraseña es de suma necesidad
       para usted, memorícela o guárdela en un lugar seguro.
       <br><br>
       Dudas y comentarios, escribanos a <strong>desarrollo_cci@ing.usac.edu.gt</strong>
       </p>
       <br>
       </font>
       </BODY>
       </HTML>

EOT;

                $exito = $mail->Send();
                //El mensaje se envia en 5 intentos máximo
                $intentos = 1;
                while ((!$exito) && ($intentos <= 5)) {
                    sleep(5);
                    $exito = $mail->Send();
                    $intentos = $intentos + 1;
                }
                if (!$exito) {
//           $tpl->assign("msgInscripcion", $msg[241]);
                    return FALSE;
                } else {
                    return TRUE;
                }

//       if(!$mail->Send())  {
//         echo "Problemas enviando correo electrónico";
//         return false;
//       }
//       else
//       {  return true;  }

            }
            unset($mail);
            unset($obj_cad);
        } // Fin de Envia PIN Correo

        //*******************************************************************
        //Esta función verifica que el correo ingresado no exista ya en la BDD
        //para otro u otros usuarios.
        //*******************************************************************
        function VerificaCorreo($param)
        {
            switch ($param[1]) {
                case GRUPO_ESTUDIANTE : // $query_usuario = sprintf("select correo from usuario where grupo = 3 and correo='%s';",$param[2]);

                    $query_usuario = $this->gsql->VerificaCorreo_select1($param[2]);

                    break;
                case GRUPO_DOCENTE    : // $query_usuario = sprintf("select correo from usuario where grupo = 1 and correo='%s';",$param[2]);

                    $query_usuario = $this->gsql->VerificaCorreo_select2($param[2]);

                    break;
                case GRUPO_AUXILIAR   :
                    break; //Para auxiliares
                case GRUPO_VACACIONES :
                    break; // Para usuarios del curso de Vacaciones
            }
            $_SESSION["sBdd"]->query($query_usuario);

            if ($_SESSION["sBdd"]->num_rows() < 1) {
                //No está registrado
                return 1;
            } else {
                $_SESSION["sBdd"]->next_record();
                $this->mCorreo = trim($_SESSION["sBdd"]->f('correo'));

                return 0;
            }
        }

        //*******************************************************************
        //Esta función verifica que el correo ingresado sea para el usuario en
        //cuestion.
        //*******************************************************************
        function CoincideCorreo($param_correo)
        {
            $varCorreo = '';
            switch ($param_correo[1]) {
                case GRUPO_ESTUDIANTE : // $query_usuario = sprintf("select correo
//                                                          from estudiante
//                               		 					  where usuarioid = '%s'
//                               					 		  and correo ='%s';",
//														  $param_correo[0],$param_correo[2]);

                    $query_usuario = $this->gsql->CoincideCorreo_select1($param_correo[0], $param_correo[2]);

//echo "<br>query--",$query_usuario,"--";
                    $_SESSION["sBdd"]->query($query_usuario);
                    if ($_SESSION["sBdd"]->num_rows() > 0) {
                        $_SESSION["sBdd"]->next_record();
                        $varCorreo = trim($_SESSION["sBdd"]->f('correo'));
                    }
                    break;
                case GRUPO_DOCENTE    : // $query_usuario = sprintf("select p.correo
//                               						      from personal p
//                               							  where p.personal = '%s'
//                               							  and p.correo     = '%s';",
//														  $param_correo[0],$param_correo[2]);

                    $query_usuario = $this->gsql->CoincideCorreo_select2($param_correo[0], $param_correo[2]);


                    $_SESSION["sBdd1"]->query($query_usuario);
                    if ($_SESSION["sBdd1"]->num_rows() > 0) {
                        $_SESSION["sBdd1"]->next_record();
                        $varCorreo = trim($_SESSION["sBdd1"]->f('correo'));
                    }
                    break;
                case GRUPO_AUXILIAR   :
                    break; // Para auxiliares
                case GRUPO_VACACIONES :
                    break; // Para usuarios del curso de Vacaciones
            }
//echo "bdd --",$varCorreo,"--";
            if (strcmp($varCorreo, '') == 0) {
                return 0; //No está registrado
            } else {
                $this->mCorreo = $varCorreo;

                return 1;
            }
        }

        // *********************************************************
        // Funcion que realiza la busqueda de un usuario en las tres
        // formas posibles, por carne, nombre y apellido.
        // *********************************************************
        function BusquedaUsuario($param)
        {
            $consulta = "";
            switch ($param[1]) {
                case GRUPO_ESTUDIANTE :
                    switch ($param[2]) {
                        case POR_CARNE    : /*echo "por carnet"; die;*/
//														$consulta = "and e.usuarioid = '".$param[0]."'";

                            $consulta = $this->gsql->BusquedaUsuario_select1_1($param[0]);

                            break;
                        case POR_NOMBRE   : // $consulta = "and e.nombre like '%".$param[3]."%'";

                            $consulta = $this->gsql->BusquedaUsuario_select1_2($param[3]);

                            break;
                        case POR_APELLIDO : // $consulta = "and e.apellido like '%".$param[4]."%'";

                            $consulta = $this->gsql->BusquedaUsuario_select1_3($param[4]);

                            break;
                        case POR_NOMBREAPELLIDO : // $consulta = "and e.nombre like '%".$param[3]."%'".
//		 									   							  "and e.apellido like '%".$param[4]."%'";

                            $consulta = $this->gsql->BusquedaUsuario_select1_4($param[3], $param[4]);

                            break;
                    } //Por tipo de busqueda
//								 $query_usuario = sprintf("select e.usuarioid,e.nombre,e.apellido,ec.carrera
//                                                           from estudiante e,estudiantecarrera ec
//                                						   where e.usuarioid = ec.usuarioid
//							    						   %s order by e.usuarioid,ec.carrera;",$consulta);

                    $query_usuario = $this->gsql->BusquedaUsuario_select1($consulta);

                    //echo $query_usuario; die;
                    $_SESSION["sBdd"]->query($query_usuario);
                    if ($_SESSION["sBdd"]->num_rows() < 1) {
                        return 0;
                    } //No existe el usuario
                    else //Si existe el usuario
                    {
                        $_SESSION["sBdd"]->next_record();
                        $this->mUsuarioid = trim($_SESSION["sBdd"]->f('usuarioid'));
                        $this->mCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                        //Para estudiantes con carreras dobles
                        if ($_SESSION["sBdd"]->num_rows() == 2) {
                            $_SESSION["sBdd"]->next_record();
                            $this->mOtraCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                        }
                    }
                    break;
                case GRUPO_DOCENTE    :
                    switch ($param[2]) {
                        case POR_CARNE    : // $consulta = "personal = '".$param[0]."'";

                            $consulta = $this->gsql->BusquedaUsuario_select2_1($param[0]);

                            break;
                        case POR_NOMBRE   : // $consulta = "nombre like '%".$param[3]."%'";

                            $consulta = $this->gsql->BusquedaUsuario_select2_2($param[3]);

                            break;
                        case POR_APELLIDO : // $consulta = "apellido like '%".$param[4]."%'";

                            $consulta = $this->gsql->BusquedaUsuario_select2_3($param[4]);

                            break;
                        case POR_NOMBREAPELLIDO : // $consulta = "nombre like '%".$param[3]."%'".
//		 									   							  "and apellido like '%".$param[4]."%'";

                            $consulta = $this->gsql->BusquedaUsuario_select2_4($param[3], $param[4]);

                    } //Por tipo de busqueda
//								 $query_usuario = sprintf("select personal,nombre,apellido
//                                                           from personal
//														   where %s order by personal;",$consulta);

                    $query_usuario = $this->gsql->BusquedaUsuario_select2($consulta);


                    $_SESSION["sBdd1"]->query($query_usuario);

                    if ($_SESSION["sBdd1"]->num_rows() < 1) {
                        return 0;
                    } //No existe el usuario
                    else //Si existe el usuario
                    {
                        $_SESSION["sBdd1"]->next_record();
                        $this->mUsuarioid = trim($_SESSION["sBdd1"]->f('personal'));
                    }
                    break;
            } //Por grupo estudiante o docente
            return 1;
        } //Fin de Busqueda Usuario


        // ********************************************************
        // Funcion que obtiene otros datos personales de un docente
        // ademas de los principales existente en el sistema.
        // ********************************************************
        function DatosDocente($param_datos)
        {
//      $query_docente = sprintf("select p.personal,p.nombre,p.apellido,p.direccion,
//                                p.colonia,p.telefono,p.celular,p.empresa,
//							    p.correo,t.puesto,pd.nacimientofecha,
//								p.sexo,p.estadocivil,pd.nacionalidad,
//								pd.nacimientomunicipio,pd.nacimientodepartamento
//                                from personal p, titularidad t, personaldocumento pd
//                                where p.personal = t.personal
//								and p.personal = pd.personal
//                                and p.personal = '%s';",$param_datos[0]);

            $query_docente = $this->gsql->DatosDocente_select1($param_datos[0]);


            $_SESSION["sBdd1"]->query($query_docente);

            if ($_SESSION["sBdd1"]->num_rows() > 0) {
                $_SESSION["sBdd1"]->next_record();
                $this->mUsuarioid = trim($_SESSION["sBdd1"]->f('personal'));
                $this->mNombre = trim($_SESSION["sBdd1"]->f('nombre'));
                $this->mApellido = trim($_SESSION["sBdd1"]->f('apellido'));
                $this->mDireccion = trim($_SESSION["sBdd1"]->f('direccion'));
                $this->mColonia = trim($_SESSION["sBdd1"]->f('colonia'));
                $this->mTelefono = trim($_SESSION["sBdd1"]->f('telefono'));
                $this->mCelular = trim($_SESSION["sBdd1"]->f('celular'));
                $this->mEmpresaCel = trim($_SESSION["sBdd1"]->f('empresa'));
                $this->mCorreo = trim($_SESSION["sBdd1"]->f('correo'));
                $this->mCarrera = trim($_SESSION["sBdd1"]->f('puesto'));
                $this->mFechaNac = trim($_SESSION["sBdd1"]->f('nacimientofecha'));
                $this->mSexo = trim($_SESSION["sBdd1"]->f('sexo'));
                $this->mEstadoCivil = trim($_SESSION["sBdd1"]->f('estadocivil'));
                $this->mGentilicio = trim($_SESSION["sBdd1"]->f('nacionalidad'));
                $this->mMunicipio = trim($_SESSION["sBdd1"]->f('nacimientomunicipio'));
                $this->mDepartamento = trim($_SESSION["sBdd1"]->f('nacimientodepartamento'));

                return 1;
            } else {
                return 0;
            }
        } // Fin de Datos Docente


        // ***********************************************************
        // Funcion que obtiene otros datos personales de un estudiante
        // ademas de los principales existente en el sistema.
        // ***********************************************************
        function DatosEstudiante($param_datos)
        {
//      $query_usuario = sprintf("select e.usuarioid,e.nombre,e.apellido,e.direccion,e.colonia,
//	                            e.telefono,e.celular,e.empresa,ed.fechanacimiento,e.correo,
//							    ec.carrera,e.sexo,ed.estadocivil,n.gentilicio,m.nombre as municipio,
//							    d.nombre as departamento
//							    from estudiante e, estudiantedatos ed, estudiantecarrera ec,
//							    municipio m, departamento d, nacionalidad n
//							    where e.usuarioid   = ec.usuarioid
//							    and e.usuarioid     = ed.usuarioid
//							    and ed.municipio    = m.municipio
//							    and e.usuarioid     = ed.usuarioid
//							    and ed.departamento = m.departamento
//							    and ed.departamento = d.departamento
//							    and e.nacionalidad  = n.nacionalidad
//								and e.usuarioid     = '%s'
//								and ec.carrera      = '%s' order by ec.carrera;",
//								$param_datos[0],$param_datos[2]);

            $query_usuario = $this->gsql->DatosEstudiante_select2($param_datos[0], $param_datos[2]);

//print $query_usuario.":::<br>";
            $_SESSION["sBdd"]->query($query_usuario);

            if ($_SESSION["sBdd"]->num_rows() > 0) {
                $_SESSION["sBdd"]->next_record();
                $this->mUsuarioid = trim($_SESSION["sBdd"]->f('usuarioid'));
                $this->mNombre = trim($_SESSION["sBdd"]->f('nombre'));
                $this->mApellido = trim($_SESSION["sBdd"]->f('apellido'));
                $this->mDireccion = trim($_SESSION["sBdd"]->f('direccion'));
                $this->mColonia = trim($_SESSION["sBdd"]->f('colonia'));
                $this->mTelefono = trim($_SESSION["sBdd"]->f('telefono'));
                $this->mCorreo = trim($_SESSION["sBdd"]->f('correo'));
                $this->mCelular = trim($_SESSION["sBdd"]->f('celular'));
                $this->mEmpresaCel = trim($_SESSION["sBdd"]->f('empresa'));
                $this->mCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                $this->mFechaNac = trim($_SESSION["sBdd"]->f('fechanacimiento'));
                $this->mSexo = trim($_SESSION["sBdd"]->f('sexo'));
                $this->mEstadoCivil = trim($_SESSION["sBdd"]->f('estadocivil'));
                $this->mGentilicio = trim($_SESSION["sBdd"]->f('gentilicio'));
                $this->mMunicipio = trim($_SESSION["sBdd"]->f('municipio'));
                $this->mDepartamento = trim($_SESSION["sBdd"]->f('departamento'));

                return 1;
            } else {
                return 0;
            }
        } // Fin de Datos Estudiante

        // ***********************************************************
        // Funcion que retorna el nombre de la escuela a la que
        // pertenece la carrera
        // ***********************************************************
        function NombreEscuela($carrera)
        {
            switch ($carrera) {
                case '01':
                    $escuela = "Escuela de Ingenier&iacute;a Civil";
                    break;

                case '02':
                case '35':
                    $escuela = "Escuela de Ingenier&iacute;a Qu&iacute;mica";
                    break;

                case '03':
                    $escuela = "Escuela de Ingenier&iacute;a Mec&aacute;nica";
                    break;

                case '04':
                case '06':
                    $escuela = "Escuela de Ingenier&iacute;a Mec&aacute;nica El&eacute;ctrica";
                    break;

                case '05':
                case '07':
                case '13':
                    $escuela = "Escuela de Ingenier&iacute;a Mec&aacute;nica Industrial";
                    break;

                case '09':
                    $escuela = "Escuela de Ingenier&iacute;a en Ciencias y Sistemas";
                    break;

                case '10':
                case '12':
                    $escuela = "Escuela de Ciencias";
                    break;

                case '15':
                    $escuela = "Sin escuela";
                    break;
            } // end switch
            return $escuela;
        } // Fin de NombreEscuela


// **********************************************************************
// consulta el numero de creditos y el promedio de una carrera especifica
// **********************************************************************
        function DarPromedioYcreditos($carnet, $carrera)
        {
//	$consulta = "select promedio,creditos from estudiantecarrera where usuarioid='$carnet' and carrera='$carrera';";

            $consulta = $this->gsql->DarPromedioYcreditos_select1($carnet, $carrera);

            $_SESSION["sBdd"]->query($consulta);
            $_SESSION["sBdd"]->next_record();
            $vector[0] = $_SESSION["sBdd"]->f('promedio');
            $vector[1] = $_SESSION["sBdd"]->f('creditos');

            return $vector;
        }


        function ListarMunicipios()
        {

            $_SESSION["accesoBdd"] = NEW DB_Sql;
            $_SESSION["accesoBdd"]->Database = BD_NAME_INGENIERIA2; //INGENIERIA2;
            $_SESSION["accesoBdd"]->Host = BD_HOST_INGENIERIA2; //HOST_WEB;
            $_SESSION["accesoBdd"]->Port = BD_PORT_INGENIERIA2;
            $_SESSION["accesoBdd"]->User = BD_USR_DATOS_USUARIO; //USR_DATOS_USUARIO;  // Usuario para hacer
            $_SESSION["accesoBdd"]->Password = BD_PWD_DATOS_USUARIO; //PWD_DATOS_USUARIO;  // acciones en la BDD
            if ($_SESSION["accesoBdd"]->connect() == 100) {
                //echo "error 100";
                die;
            }
//		$query_municipio = "select departamento, municipio, nombre
//							from municipio
//							where NOT(departamento=0 OR municipio=0)
//							order by departamento;";

            $query_municipio = $this->gsql->ListarMunicipios_select1();


            $_SESSION["accesoBdd"]->query($query_municipio);

            $i = 1;
            $rows = $_SESSION["accesoBdd"]->num_rows();
            if ($rows > 0) {
                for ($i = 0; $i < $rows; $i++) {
                    $_SESSION["accesoBdd"]->next_record();
                    $this->mMunicipios[$i][0] = trim($_SESSION["accesoBdd"]->f('departamento'));
                    $this->mMunicipios[$i][1] = trim($_SESSION["accesoBdd"]->f('municipio'));
                    $this->mMunicipios[$i][2] = trim($_SESSION["accesoBdd"]->f('nombre'));
                }
                $this->mnomunicipios = $rows;

                return 1;
            } else {
                return 0;
            }
        }

        function ListarDepartamentos()
        {

            $_SESSION["accesoBdd1"] = NEW DB_Sql;
            $_SESSION["accesoBdd1"]->Database = BD_NAME_INGENIERIA2; //INGENIERIA2;
            $_SESSION["accesoBdd1"]->Host = BD_HOST_INGENIERIA2; //HOST_WEB;
            $_SESSION["accesoBdd1"]->Port = BD_PORT_INGENIERIA2;
            $_SESSION["accesoBdd1"]->User = BD_USR_DATOS_USUARIO; //USR_DATOS_USUARIO;  // Usuario para hacer
            $_SESSION["accesoBdd1"]->Password = BD_PWD_DATOS_USUARIO; //PWD_DATOS_USUARIO;  // acciones en la BDD
            if ($_SESSION["accesoBdd1"]->connect() == 100) {
                //echo "error 100";
                die;
            }
//		$query_departamento = "select departamento, nombre, orden
//							from departamento
//							where NOT(departamento=0)
//							order by departamento;";

            $query_departamento = $this->gsql->ListarDepartamentos_select1();


            $_SESSION["accesoBdd1"]->query($query_departamento);

            $i = 1;
            $rows = $_SESSION["accesoBdd1"]->num_rows();
            if ($rows > 0) {
                for ($i = 0; $i < $rows; $i++) {
                    $_SESSION["accesoBdd1"]->next_record();
                    $this->mDepartamentos[$i][0] = trim($_SESSION["accesoBdd1"]->f('departamento'));
                    $this->mDepartamentos[$i][1] = trim($_SESSION["accesoBdd1"]->f('nombre'));
                    $this->mDepartamentos[$i][2] = trim($_SESSION["accesoBdd1"]->f('orden'));
                }
                $this->mnodepartamentos = $rows;

                return 1;
            } else {
                return 0;
            }
        }

        function datosGuardados($carne)
        {
            $_SESSION["accesoBdd2"] = NEW DB_Sql;
            $_SESSION["accesoBdd2"]->Database = BD_NAME_INGENIERIA2; //INGENIERIA2;
            $_SESSION["accesoBdd2"]->Host = BD_HOST_INGENIERIA2; //HOST_WEB;
            $_SESSION["accesoBdd2"]->Port = BD_PORT_INGENIERIA2;
            $_SESSION["accesoBdd2"]->User = BD_USR_DATOS_USUARIO; //USR_DATOS_USUARIO;  // Usuario para hacer
            $_SESSION["accesoBdd2"]->Password = BD_PWD_DATOS_USUARIO; //PWD_DATOS_USUARIO;  // acciones en la BDD
            if ($_SESSION["accesoBdd2"]->connect() == 100) {
                //echo "error 100";
                die;
            }
//		$query_graduandos = sprintf("select * from infograduandos where usuarioid='%s'",$carne);

            $query_graduandos = $this->gsql->datosGuardados_select1($carne);


            $_SESSION["accesoBdd2"]->query($query_graduandos);

            $rows = $_SESSION["accesoBdd2"]->num_rows();
            if ($rows > 0) {
                $_SESSION["accesoBdd2"]->next_record();
                $this->mCedula = trim($_SESSION["accesoBdd2"]->f('cedula'));
                $this->mExtdepto = trim($_SESSION["accesoBdd2"]->f('extdepto'));
                $this->mExtmunic = trim($_SESSION["accesoBdd2"]->f('extmunic'));
                $this->mLugarnac = trim($_SESSION["accesoBdd2"]->f('lugar_nac'));
                $this->mTitulo = trim($_SESSION["accesoBdd2"]->f('titulo'));
                $this->mEstablecimiento = trim($_SESSION["accesoBdd2"]->f('establecimiento'));
                $this->mTrabaja = trim($_SESSION["accesoBdd2"]->f('trabaja'));
                $this->mLugartrabajo = trim($_SESSION["accesoBdd2"]->f('lugartrabajo'));
                $this->mCargotrabajo = trim($_SESSION["accesoBdd2"]->f('cargotrabajo'));
                $this->mDirecciontrabajo = trim($_SESSION["accesoBdd2"]->f('direccion_trabajo'));
                $this->mNombrepadre = trim($_SESSION["accesoBdd2"]->f('nombre_padre'));
                $this->mNombremadre = trim($_SESSION["accesoBdd2"]->f('nombre_madre'));
                $this->mCambiocarrera = trim($_SESSION["accesoBdd2"]->f('cambiocarrera'));
                $this->mCarreraorigen = trim($_SESSION["accesoBdd2"]->f('carrera_origen'));
                $this->mTrasladofacultad = trim($_SESSION["accesoBdd2"]->f('traslado_facultad'));
                $this->mFacultadorigen = trim($_SESSION["accesoBdd2"]->f('facultad_origen'));
                $this->mTrasladouniversidad = trim($_SESSION["accesoBdd2"]->f('traslado_universidad'));
                $this->mUniversidadorigen = trim($_SESSION["accesoBdd2"]->f('universidad_origen'));
                $this->mEquivalencias = trim($_SESSION["accesoBdd2"]->f('equivalencias'));
                $this->mAnioingreso = trim($_SESSION["accesoBdd2"]->f('anioingreso'));
                $this->mMesingreso = trim($_SESSION["accesoBdd2"]->f('mesingreso'));

                return 1;
            } else {
                return 0;
            }
        }

        function guardarDatosGraduandos($parametros)
        {


            $_SESSION["accesoBdd2"] = NEW DB_Sql;
            $_SESSION["accesoBdd2"]->Database = BD_NAME_INGENIERIA2; // INGENIERIA2;
            $_SESSION["accesoBdd2"]->Host = BD_HOST_INGENIERIA2; // HOST_WEB;
            $_SESSION["accesoBdd2"]->Port = BD_PORT_INGENIERIA2;
            $_SESSION["accesoBdd2"]->User = BD_USR_DATOS_USUARIO; // USR_DATOS_USUARIO;  // Usuario para hacer
            $_SESSION["accesoBdd2"]->Password = BD_PWD_DATOS_USUARIO; // PWD_DATOS_USUARIO;  // acciones en la BDD
            if ($_SESSION["accesoBdd2"]->connect() == 100) {
                //echo "error 100";
                die;
            }
            //Por defecto, se intenta hacer un update, por si la tupla ya existe en la tabla.
//		$query_update = sprintf("update infograduandos set cedula='%s', extdepto=%d, extmunic=%d,
//								lugar_nac='%s', titulo='%s', establecimiento='%s', trabaja=%d, lugartrabajo='%s',
//								cargotrabajo='%s', direccion_trabajo='%s', nombre_padre='%s', nombre_madre='%s',
//								cambiocarrera=%d, carrera_origen='%s', traslado_facultad=%d, facultad_origen='%s',
//								traslado_universidad=%d, universidad_origen='%s', equivalencias=%d, anioingreso=%d, mesingreso=%d
//								where usuarioid = '%s';",
//								$parametros[1],$parametros[2],$parametros[3],$parametros[4],$parametros[5],$parametros[6],$parametros[7],
//								$parametros[8],$parametros[9],$parametros[10],$parametros[11],$parametros[12],$parametros[13],$parametros[14],
//								$parametros[15],$parametros[16],$parametros[17],$parametros[18],$parametros[19],$parametros[20],$parametros[21],
//								$parametros[0]);

            $query_update = $this->gsql->guardarDatosGraduandos_update1($parametros[1], $parametros[2], $parametros[3], $parametros[4], $parametros[5], $parametros[6], $parametros[7],
                $parametros[8], $parametros[9], $parametros[10], $parametros[11], $parametros[12], $parametros[13], $parametros[14],
                $parametros[15], $parametros[16], $parametros[17], $parametros[18], $parametros[19], $parametros[20], $parametros[21],
                $parametros[0]);

            $_SESSION["accesoBdd2"]->query($query_update);
            //echo $_SESSION["accesoBdd2"]->affected_rows();
            if ($_SESSION["accesoBdd2"]->affected_rows() == 0) { //si da resultado cero, no existe la tupla en la tabla, por lo que se hace un insert
//			$query_insert = sprintf("insert into infograduandos (usuarioid,cedula,extdepto,extmunic,
//								lugar_nac,titulo,establecimiento,trabaja,lugartrabajo,cargotrabajo,direccion_trabajo,
//								nombre_padre,nombre_madre,cambiocarrera,carrera_origen,traslado_facultad,
//								facultad_origen,traslado_universidad,universidad_origen,equivalencias,anioingreso,mesingreso)
//								values('%s','%s',%d,%d,'%s','%s','%s',%d,'%s','%s','%s','%s','%s',%d,'%s',%d,'%s',%d,'%s',%d,%d,%d);",
//	                            $parametros[0],$parametros[1],$parametros[2],$parametros[3],$parametros[4],$parametros[5],$parametros[6],
//								$parametros[7],$parametros[8],$parametros[9],$parametros[10],$parametros[11],$parametros[12],$parametros[13],
//								$parametros[14],$parametros[15],$parametros[16],$parametros[17],$parametros[18],$parametros[19],$parametros[20],
//								$parametros[21]);

                $query_insert = $this->gsql->guardarDatosGraduandos_insert1($parametros[0], $parametros[1], $parametros[2], $parametros[3], $parametros[4], $parametros[5], $parametros[6],
                    $parametros[7], $parametros[8], $parametros[9], $parametros[10], $parametros[11], $parametros[12], $parametros[13],
                    $parametros[14], $parametros[15], $parametros[16], $parametros[17], $parametros[18], $parametros[19], $parametros[20],
                    $parametros[21]);

                $_SESSION["accesoBdd2"]->query($query_insert);
                //echo $_SESSION["accesoBdd2"]->affected_rows();
                if ($_SESSION["accesoBdd2"]->affected_rows() == 0) {
                    return 0; //Error
                }
            } else {
                return 1;
                //Datos Guardados
            }

            return 1;
        }


//**********************************************************************
//Funcion pathfoto agregada por talo
//Consulta y devuelve el path de la fotografia ya sea estudiante o docente
//************************************************************************
        function pathfoto($param_datos)
        {

            $existente = 0;
            $existe_cate = 0;


            switch ($param_datos[1]) {
                case 1    :

//		$query_usuario = sprintf("select * from f_geturl_foto_personal('%s') as id;",$param_datos[0]);

                    $query_usuario = $this->gsql->pathfoto_select1($param_datos[0]);


                    $_SESSION["sBdd1"]->query($query_usuario); //ejecuta query


                    if ($_SESSION["sBdd1"]->num_rows() > 0) {
                        $_SESSION["sBdd1"]->next_record();
                        $this->mId = "/var/www" . trim($_SESSION["sBdd1"]->f('id'));
                    } else {
                        $existente = 0;
                    }

                    break;
                case 3 :
                    // Escribir en el archivo

//		$query_usuario = sprintf("select * from f_geturl_foto_estudiante('%s') as id;",$param_datos[0]);

                    $query_usuario = $this->gsql->pathfoto_select2($param_datos[0]);

                    $_SESSION["sBdd"]->query($query_usuario); //ejecuta query


                    if ($_SESSION["sBdd"]->num_rows() > 0) {
                        $_SESSION["sBdd"]->next_record();
                        $this->mId = "/var/www" . trim($_SESSION["sBdd"]->f('id'));

                    } else {
                        $existente = 0;
                    }

                    break;
            }


        } // Fin de pathfoto
    } //Fin de Clase PIN

?>