<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 30/01/2015
 * Time: 1:09 PM
 */

include("../../path.inc.php");
//include_once("class.phpmailer.php");      // utilizada para envio de correo
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");

/*
 * Incluyendo archivo con sentencias SQL
 */
include_once("$dir_portal/fw/model/sql/UserManager_SQL.php");


class UserManager
{
    /*
     * Variable para utilizar las consultas
     */
    var $gsql;


    // ***************************************************************
    // Constructor
    // ***************************************************************
    function UserManager($param_grupo)
    {  //   Se realiza una conexion con Servidor de Base de datos

        $_SESSION["sBdd"] = NEW DB_Connection();
        $_SESSION["sBdd"]->connect();

        if ($_SESSION["sBdd"]->connect() == 100) {
            echo "error 100";
            die;
        }
        if ($param_grupo == 1) {
        }

        /*
         * Instanciando la variable en la clase donde se encuentran las consultas
         */
        $this->gsql = new UserManager_SQL();
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
            $Pwd_Gen = mt_rand(1111, 9999);  //Obtiene el valor aleatorio
            //Si no se encuentra en el rango permitido, genera otro valor
            if (($Pwd_Gen >= 1111) && ($Pwd_Gen <= 9999)) $Salir = 1;
        }
        return ($Pwd_Gen);
    }

    // ***********************************************************
    // Funcion que obtiene otros datos personales de un estudiante
    // ademas de los principales existente en el sistema.
    // ***********************************************************
    function DatosEstudiante($param_datos)
    {
        $query_usuario = $this->gsql->DatosEstudiante_select2($param_datos[0], $param_datos[2]);

//print $query_usuario.":::<br>";
        $_SESSION["sBdd"]->query($query_usuario);

        if ($_SESSION["sBdd"]->num_rows() > 0) {
            $_SESSION["sBdd"]->next_record();
            return $_SESSION["sBdd"]->r();
        } else {
            return 0;
        }
    } // Fin de Datos Estudiante


    // ***********************************************************
    // Funcion que obtiene otros datos personales de un Docente
    // ademas de los principales existente en el sistema.
    // ***********************************************************
    function DatosDocente($param_datos)
    {
        $query_usuario = $this->gsql->DatosDocente_select2($param_datos[0]);

//print $query_usuario.":::<br>";
        $_SESSION["sBdd"]->query($query_usuario);

        if ($_SESSION["sBdd"]->num_rows() > 0) {
            $_SESSION["sBdd"]->next_record();
            return $_SESSION["sBdd"]->r();
        } else {
            return 0;
        }
    } // Fin de Datos Estudiante

    function ListarMunicipios()
    {
        $_SESSION["accesoBdd"] = NEW DB_Connection();
        if ($_SESSION["accesoBdd"]->connect() == 100) {
            die;
        }

        $query_municipio = $this->gsql->ListarMunicipios_select1();
        $_SESSION["accesoBdd"]->query($query_municipio);

        $i = 1;
        $rows = $_SESSION["accesoBdd"]->num_rows();
        if ($rows > 0) {
            for ($i = 0; $i < $rows; $i++) {
                $_SESSION["accesoBdd"]->next_record();
                $this->mMunicipios[$i][0] = trim($_SESSION["accesoBdd"]->f('iddepartment'));
                $this->mMunicipios[$i][1] = trim($_SESSION["accesoBdd"]->f('idtown'));
                $this->mMunicipios[$i][2] = trim($_SESSION["accesoBdd"]->f('name'));
            }
            $this->mnomunicipios = $rows;
            return 1;
        } else {
            return 0;
        }
    }

    function ListarDepartamentos()
    {
        $_SESSION["accesoBdd1"] = NEW DB_Connection();
        if ($_SESSION["accesoBdd1"]->connect() == 100) {
            //echo "error 100";
            die;
        }

        $query_departamento = $this->gsql->ListarDepartamentos_select1();
        $_SESSION["accesoBdd1"]->query($query_departamento);

        $i = 1;
        $rows = $_SESSION["accesoBdd1"]->num_rows();
        if ($rows > 0) {
            for ($i = 0; $i < $rows; $i++) {
                $_SESSION["accesoBdd1"]->next_record();
                $this->mDepartamentos[$i][0] = trim($_SESSION["accesoBdd1"]->f('iddepartment'));
                $this->mDepartamentos[$i][1] = trim($_SESSION["accesoBdd1"]->f('name'));
                $this->mDepartamentos[$i][2] = trim($_SESSION["accesoBdd1"]->f('idorden'));
            }

            $this->mnodepartamentos = $rows;
            return 1;
        } else {
            return 0;
        }
    }

    function ListarNacionalidades()
    {
        $_SESSION["accesoBdd"] = NEW DB_Connection();
        if ($_SESSION["accesoBdd"]->connect() == 100) {
            die;
        }

        $query_nacionalidades = $this->gsql->ListarNacionalidades_select1();
        $_SESSION["accesoBdd"]->query($query_nacionalidades);

        $i = 1;
        $rows = $_SESSION["accesoBdd"]->num_rows();
        if ($rows > 0) {
            for ($i = 0; $i < $rows; $i++) {
                $_SESSION["accesoBdd"]->next_record();
                $this->mNacionalidades[$i][0] = trim($_SESSION["accesoBdd"]->f('idnationality'));
                $this->mNacionalidades[$i][1] = trim($_SESSION["accesoBdd"]->f('country'));
                $this->mNacionalidades[$i][2] = trim($_SESSION["accesoBdd"]->f('gentilicio'));
            }
            $this->mnonacionalidades = $rows;
            return 1;
        } else {
            return 0;
        }
    }

    function guardarActualizacionDatos($carnet, $parametros)
{
    $_SESSION["accesoBdd2"] = NEW DB_Connection();
    if ($_SESSION["accesoBdd2"]->connect() == 100) {
        die;
    }

    $query_update = $this->gsql->guardarActualizacionDatos_update1(
        $parametros[0], $parametros[1], $parametros[2], $parametros[3], $parametros[4],
        $parametros[5], $parametros[6], $parametros[7], $parametros[8], $parametros[9],
        $parametros[10], $parametros[11], $parametros[12], $parametros[13], $parametros[14],
        $parametros[15], $parametros[16], $parametros[17], $parametros[18], $parametros[19],
        $parametros[20], $parametros[21], $parametros[22], $parametros[23], $parametros[24],
        $parametros[25], $parametros[26], $parametros[27], $parametros[28], $parametros[29],
        $parametros[30], $parametros[31], $carnet);

    $_SESSION["accesoBdd2"]->query($query_update);
    if ($_SESSION["accesoBdd2"]->affected_rows() == 0) {
        return 0;
    } else {

        return 1;
    }
}

    function guardarActualizacionDatosDocente($personal, $parametros)
    {
        $_SESSION["accesoBdd2"] = NEW DB_Connection();
        if ($_SESSION["accesoBdd2"]->connect() == 100) {
            die;
        }

        $query_update = $this->gsql->guardarActualizacionDatosDocente_update1(
            $parametros[0], $parametros[1], $parametros[2], $parametros[3], $parametros[4],
            $parametros[5], $parametros[6], $parametros[7], $parametros[8], $parametros[9],
            $parametros[10], $parametros[11], $parametros[12], $personal);

        $_SESSION["accesoBdd2"]->query($query_update);
        if ($_SESSION["accesoBdd2"]->affected_rows() == 0) {
            return 0;
        } else {

            return 1;
        }
    }

    function  registrarLogActualizacionDatos($USR_DATOS_USUARIO, $personal,$GRUPO, $parametros0, $parametros11) {
        $_SESSION["accesoBdd2"] = NEW DB_Connection();
        if ($_SESSION["accesoBdd2"]->connect() == 100) {
            die;
        }

        $query_update = $this->gsql->ModificaInfo_update5($USR_DATOS_USUARIO, $personal,$GRUPO, $parametros0, $parametros11);

        $_SESSION["accesoBdd2"]->query($query_update);
        if ($_SESSION["accesoBdd2"]->affected_rows() == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    // ***************************************************************
    // Funcion que obtiene los datos personales de un usuario válido y
    // a la vez existente en el Sistema, ya sea Estudiante o Catedrático
    // ***************************************************************
    function DatosUsuario($param_datos)
    {
        $existente = 0;
        $existe_cate = 0;
        switch ($param_datos[1]) {
            case GRUPO_ESTUDIANTE :
                $query_usuario = $this->gsql->DatosUsuario_select1(GRUPO_ESTUDIANTE, $param_datos[0]);
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
            case GRUPO_DOCENTE    :
                $query_usuario1 = $this->gsql->DatosUsuario_select2(GRUPO_DOCENTE, $param_datos[0]);

                $_SESSION["sBdd"]->query($query_usuario1);
                if ($_SESSION["sBdd"]->num_rows() < 1) {
                    $existente = 0; //No existe el usuario
                } else {
                    $_SESSION["sBdd"]->next_record();
                    $datos_docente = $_SESSION["sBdd"]->r();
                    $query_usuario2 = $this->gsql->DatosDocente_select2($param_datos[0]);

                    $_SESSION["sBdd"]->query($query_usuario2);

                    if ($_SESSION["sBdd"]->num_rows() > 0) {
                        $existe_cate = 1;
                        $existente = 1; //Si existen datos
                    }
                }
                break;
        }
//echo "esta la info ".$existente; die;
        if ($existente == 1) {
            switch ($param_datos[1]) {
                case GRUPO_ESTUDIANTE :
                    $_SESSION["sBdd"]->next_record();
                    $this->mUsuarioid = trim($_SESSION["sBdd"]->f('carnet'));
                    //echo " esto debe ser==>>> ".$this->mUsuarioid; die;
                    $this->mContrasenia = $_SESSION["sBdd"]->f('password');
                    $this->mNombre = trim($_SESSION["sBdd"]->f('nombre'));
                    $this->mApellido = trim($_SESSION["sBdd"]->f('apellido'));
                    $this->mDireccion = trim($_SESSION["sBdd"]->f('direccion'));
                    $this->mTelefono = trim($_SESSION["sBdd"]->f('tel1'));
                    $this->mCorreo = trim($_SESSION["sBdd"]->f('correo1'));
                    $this->mCelular = trim($_SESSION["sBdd"]->f('cel1'));
                    $this->mPalabraClave = trim($_SESSION["sBdd"]->f('keyword'));
                    $this->mCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                    $this->mFechaNac = trim($_SESSION["sBdd"]->f('fechanac'));
                    $this->mSexo = trim($_SESSION["sBdd"]->f('sexo'));
                    $this->mEstadoCivil = trim($_SESSION["sBdd"]->f('estadocivil'));
                    $this->mMunicipio = trim($_SESSION["sBdd"]->f('municipio_dir'));
                    $this->mDepartamento = trim($_SESSION["sBdd"]->f('departamento_dir'));

                    if ($_SESSION["sBdd"]->num_rows() == 2) {
                        $_SESSION["sBdd"]->next_record();
                        $this->mOtraCarrera = trim($_SESSION["sBdd"]->f('carrera'));
                    }
                    break;
                case GRUPO_DOCENTE    :
                    if ($existe_cate == 1) {
                        $_SESSION["sBdd"]->next_record();
                        $this->mUsuarioid = trim($datos_docente['usuarioid']);
                        $this->mContrasenia = $datos_docente['password'];
                        $this->mNombre = trim($_SESSION["sBdd"]->f('nombre'));
                        $this->mApellido = trim($_SESSION["sBdd"]->f('apellido'));
                        $this->mDireccion = trim($_SESSION["sBdd"]->f('direccion'));
                        $this->mTelefono = trim($_SESSION["sBdd"]->f('tel1'));
                        $this->mCorreo = trim($_SESSION["sBdd"]->f('correo1'));
                        $this->mCelular = trim($_SESSION["sBdd"]->f('cel1'));
                        $this->mPalabraClave = trim($_SESSION["sBdd"]->f('keyword'));
                        $this->mFechaNac = trim($_SESSION["sBdd"]->f('fechanac'));
                        $this->mSexo = trim($_SESSION["sBdd"]->f('sexo'));
                        $this->mEstadoCivil = trim($_SESSION["sBdd"]->f('estadocivil'));
                        $this->mGentilicio = trim($_SESSION["sBdd"]->f('nacionalidad'));
                    }
                    break;
            }
            return 1;
        } else {
            return 0;
        }
    } // Fin de Datos Usuario

    //********************************************************************
    //Esta función actualiza los valores de un usuario, como contrasenia,correo
    //y palabraclave tambien inserta BitacoraUsuario, tanto para estudiantes
    //como catedraticos.
    //********************************************************************
    function ModificaUsuario($parametros)
    {
        $query_update = $this->gsql->ModificaUsuario_update1($parametros[6], $parametros[8], $parametros[1], $parametros[0]);

        $resultado1 = $_SESSION["sBdd"]->query($query_update);

        if ($resultado1 == 0) {
            return 0;
        } else {
            $resultado2 = 1;
            if ($resultado2 == 0) {
                return 0;
            }

            return 1;
        }
    }
} //Fin de Clase PIN

?>
