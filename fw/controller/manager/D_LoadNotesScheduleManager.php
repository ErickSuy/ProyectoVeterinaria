<?php

include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/sql/D_LoadNotesScheduleManager_SQL.php");


class D_LoadNotesScheduleManager
{
    /*
     * Variable para utilizar las consultas
     */
    var $gsql;

    /*
     * Creando Constructor para inicializar la variable $gsql;
     */
    public function D_LoadNotesScheduleManager()
    {
        /*
        * Instanciando la variable en la clase donde se encuentran las consultas
        */

        $this->gsql = new D_LoadNotesScheduleManager_SQL();
    }

    function obtenerHorario($periodo, $anio) {
        $query_horario = $this->gsql->HacerHorario_select1($periodo, $anio);

        $query_status = $this->gsql->HacerHorario_update1($periodo, $anio);

        $_SESSION["sConexion"]->query($query_status);

        if($_SESSION["sConexion"]->query($query_horario) and $_SESSION["sConexion"]->num_rows()>0) {
            $numero_filas = $_SESSION["sConexion"]->num_rows();
            for ($i = 1; $i <= $numero_filas; $i++) {
                $_SESSION["sConexion"]->next_record();
                $idcourse = $_SESSION["sConexion"]->f('idcourse');
                $name = $_SESSION["sConexion"]->f('name');
                $section = $_SESSION["sConexion"]->f('section');
                $building = $_SESSION["sConexion"]->f('building');
                $idclassroom = $_SESSION["sConexion"]->f('idclassroom');
                $starttime = $_SESSION["sConexion"]->f('starttime');
                $endtime = $_SESSION["sConexion"]->f('endtime');
                $mon = $_SESSION["sConexion"]->f('mon');
                $tue = $_SESSION["sConexion"]->f('tue');
                $wed = $_SESSION["sConexion"]->f('wed');
                $thu = $_SESSION["sConexion"]->f('thu');
                $fri = $_SESSION["sConexion"]->f('fri');
                $sat = $_SESSION["sConexion"]->f('sat');
                $sun = $_SESSION["sConexion"]->f('sun');
                $nombrecat = $_SESSION["sConexion"]->f('nombrecat');
                $type = $_SESSION["sConexion"]->f('idscheduletype');
                $career = $_SESSION["sConexion"]->f('career');

                switch ($type) {
                    case 1:
                        $type = "CM";
                        break;
                    case 2:
                        $type = "LB";
                        break;
                    case 3:
                        $type = "PR";
                        break;
                    case 4:
                        $type = "TT";
                        break;
                    case 5:
                        $type = "#FF0000";
                        break;
                }

                $schedule[] = array('idcourse' => $idcourse,
                    'name' => $name,
                    'section' => $section,
                    'building' => $building,
                    'idclassroom' => $idclassroom,
                    'starttime' =>$starttime,
                    'endtime' => $endtime,
                    'mon' => $mon == 1 ? "X" : "-",
                    'tue' => $tue == 1 ? "X" : "-",
                    'wed' => $wed == 1 ? "X" : "-",
                    'thu' => $thu == 1 ? "X" : "-",
                    'fri' => $fri == 1 ? "X" : "-",
                    'sat' => $sat == 1 ? "X" : "-",
                    'sun' => $sun == 1 ? "X" : "-",
                    'nombrecat' => $nombrecat,
                    'type' => $type,
                    'career' => $career);
            }
        }

        return $schedule;
    }

    function obtenerHorarioCurso($periodo, $anio,$curso,$seccion,$index) {
        $query_horario = $this->gsql->HacerHorarioCurso_select1($periodo, $anio,$curso,$seccion,$index);
        //$query_status = $this->gsql->HacerHorario_update1($periodo, $anio);

        //$_SESSION["sConexion"]->query($query_status);

        if($_SESSION["sConexion"]->query($query_horario) and $_SESSION["sConexion"]->num_rows()>0) {
            $numero_filas = $_SESSION["sConexion"]->num_rows();
            for ($i = 1; $i <= $numero_filas; $i++) {
                $_SESSION["sConexion"]->next_record();
                $idcourse = $_SESSION["sConexion"]->f('idcourse');
                $name = $_SESSION["sConexion"]->f('name');
                $section = $_SESSION["sConexion"]->f('section');
                $building = $_SESSION["sConexion"]->f('building');
                $idclassroom = $_SESSION["sConexion"]->f('idclassroom');
                $starttime = $_SESSION["sConexion"]->f('starttime');
                $endtime = $_SESSION["sConexion"]->f('endtime');
                $mon = $_SESSION["sConexion"]->f('mon');
                $tue = $_SESSION["sConexion"]->f('tue');
                $wed = $_SESSION["sConexion"]->f('wed');
                $thu = $_SESSION["sConexion"]->f('thu');
                $fri = $_SESSION["sConexion"]->f('fri');
                $sat = $_SESSION["sConexion"]->f('sat');
                $sun = $_SESSION["sConexion"]->f('sun');
                $nombrecat = $_SESSION["sConexion"]->f('nombrecat');
                $type = $_SESSION["sConexion"]->f('idscheduletype');

                switch ($type) {
                    case 1:
                        $type = "CM";
                        break;
                    case 2:
                        $type = "LB";
                        break;
                    case 3:
                        $type = "PR";
                        break;
                    case 4:
                        $type = "TT";
                        break;
                    case 5:
                        $type = "#FF0000";
                        break;
                }

                $schedule[] = array('idcourse' => $idcourse,
                    'name' => $name,
                    'section' => $section,
                    'building' => $building,
                    'idclassroom' => $idclassroom,
                    'starttime' =>$starttime,
                    'endtime' => $endtime,
                    'mon' => $mon == 1 ? "X" : "-",
                    'tue' => $tue == 1 ? "X" : "-",
                    'wed' => $wed == 1 ? "X" : "-",
                    'thu' => $thu == 1 ? "X" : "-",
                    'fri' => $fri == 1 ? "X" : "-",
                    'sat' => $sat == 1 ? "X" : "-",
                    'sun' => $sun == 1 ? "X" : "-",
                    'nombrecat' => $nombrecat,
                    'type' => $type);
            }
        }

        return $schedule;
    }

    function obtenerHorarioCursoCarrera($index,$curso,$anio,$periodo,$carrera){
        $query_horario = $this->gsql->HacerHorarioCurso_select2($index,$curso,$anio,$periodo,$carrera);
        //echo $query_horario;
        if($_SESSION["sConexion"]->query($query_horario) and $_SESSION["sConexion"]->num_rows()>0) {
            $numero_filas = $_SESSION["sConexion"]->num_rows();
            for ($i = 1; $i <= $numero_filas; $i++) {
                $_SESSION["sConexion"]->next_record();
                $idcourse = $_SESSION["sConexion"]->f('r_course');
                $name = $_SESSION["sConexion"]->f('r_name');
                $section = $_SESSION["sConexion"]->f('r_section');
                $building = $_SESSION["sConexion"]->f('r_building');
                $idclassroom = $_SESSION["sConexion"]->f('r_idclassroom');
                $starttime = $_SESSION["sConexion"]->f('r_starttime');
                $endtime = $_SESSION["sConexion"]->f('r_endtime');
                $mon = $_SESSION["sConexion"]->f('r_mon');
                $tue = $_SESSION["sConexion"]->f('r_tue');
                $wed = $_SESSION["sConexion"]->f('r_wed');
                $thu = $_SESSION["sConexion"]->f('r_thu');
                $fri = $_SESSION["sConexion"]->f('r_fri');
                $sat = $_SESSION["sConexion"]->f('r_sat');
                $sun = $_SESSION["sConexion"]->f('r_sun');
                $nombrecat = $_SESSION["sConexion"]->f('r_teacher');
                $type = $_SESSION["sConexion"]->f('r_type');

                switch ($type) {
                    case 1:
                        $type = "CM";
                        break;
                    case 2:
                        $type = "LB";
                        break;
                    case 3:
                        $type = "PR";
                        break;
                    case 4:
                        $type = "TT";
                        break;
                    case 5:
                        $type = "#FF0000";
                        break;
                }

                $schedule[] = array('idcourse' => $idcourse,
                    'name' => $name,
                    'section' => $section,
                    'building' => $building,
                    'idclassroom' => $idclassroom,
                    'starttime' =>$starttime,
                    'endtime' => $endtime,
                    'mon' => $mon == 1 ? "X" : "-",
                    'tue' => $tue == 1 ? "X" : "-",
                    'wed' => $wed == 1 ? "X" : "-",
                    'thu' => $thu == 1 ? "X" : "-",
                    'fri' => $fri == 1 ? "X" : "-",
                    'sat' => $sat == 1 ? "X" : "-",
                    'sun' => $sun == 1 ? "X" : "-",
                    'nombrecat' => $nombrecat,
                    'type' => $type);
            }
        }

        return $schedule;
    }

    function HacerHorario($periodo, $anio, $nombre_archivo)
    {
        $fila = "odd-style";

        $query_horario = $this->gsql->HacerHorario_select1($periodo, $anio);

        $query_status = $this->gsql->HacerHorario_update1($periodo, $anio);

        $_SESSION["sConexion"]->query($query_status);

// Se obtienen los cursos
        $_SESSION["sConexion"]->query($query_horario);

        $numero_filas = $_SESSION["sConexion"]->num_rows();

        $archivo = fopen($nombre_archivo, "w");

        $simbologia = "<table align='center'>
                         <tr>
                            <td>[<font color=#3d3d3d>Clase Magistral</font>]</td>
                            <td>|</td>
                            <td><font color=#0000FF>[Laboratorio]</font></td>
                            <td>| <font color=#008000>[Práctica]</font></td>
                            <td>| <font color=#FF00CC>[Tutoria]</font></td>
                        </tr>
                    </table>";

        fwrite($archivo, $simbologia);

        fwrite($archivo, "<table align=center width=100% border=0 cellpadding=0 cellspacing=0>");

        $encabezado = "<thead><tr class='tableheader'><td class='encabezado'>Curso</td>
                   <td class='encabezado'>Nombre</td>
                   <td class='encabezado'>Sección</td>
                   <td class='encabezado'>Edificio</td>
                   <td class='encabezado'>Salón</td>
                   <td class='encabezado'>Inicio</td>
                   <td class='encabezado'>Final</td>
                   <td class='encabezado'>L</td>
                   <td class='encabezado'>M</td>
                   <td class='encabezado'>M</td>
                   <td class='encabezado'>J</td>
                   <td class='encabezado'>V</td>
                   <td class='encabezado'>S</td>
                   <td class='encabezado'>D</td>
				   <td class='encabezado'>Catedrático</td></tr></thead><tbody>";

        fwrite($archivo, $encabezado);

        for ($i = 1; $i <= $numero_filas; $i++) {
            $_SESSION["sConexion"]->next_record();
            $tipos = $_SESSION["sConexion"]->f('idscheduletype') + 0;

            switch ($tipos) {
                case 1:
                    $color = "#3d3d3d";
                    break;
                case 2:
                    $color = "#0000FF";
                    break;
                case 3:
                    $color = "#008000";
                    break;
                case 4:
                    $color = "#FF00CC";
                    break;
                case 5:
                    $color = "#FF0000";
                    break;
            }

            $codigo = $_SESSION["sConexion"]->f('idcourse');
            $nombre = $_SESSION["sConexion"]->f('name');
            $seccion = $_SESSION["sConexion"]->f('section');
            $edificio = $_SESSION["sConexion"]->f('building');
            $salon = $_SESSION["sConexion"]->f('idclassroom');
            switch (trim(strtoupper($salon))) {
                case "VIRTUAL" :
                    if (trim(strtoupper($edificio)) == "EXT") {
                        $tinicio = "&nbsp;";
                        $tfinal = "&nbsp;";
                        $lunes = "&nbsp;";
                        $martes = "&nbsp;";
                        $miercoles = "&nbsp;";
                        $jueves = "&nbsp;";
                        $viernes = "&nbsp;";
                        $sabado = "&nbsp;";
                        $domingo = "&nbsp;";
                    } else {
                        $tinicio = $_SESSION["sConexion"]->f('starttime');
                        $tfinal = $_SESSION["sConexion"]->f('endtime');

                        $lunes = ($_SESSION["sConexion"]->f('mon') == 1) ? "X" : "-";
                        $martes = ($_SESSION["sConexion"]->f('tue') == 1) ? "X" : "-";
                        $miercoles = ($_SESSION["sConexion"]->f('wed') == 1) ? "X" : "-";
                        $jueves = ($_SESSION["sConexion"]->f('thu') == 1) ? "X" : "-";
                        $viernes = ($_SESSION["sConexion"]->f('fri') == 1) ? "X" : "-";
                        $sabado = ($_SESSION["sConexion"]->f('sat') == 1) ? "X" : "-";
                        $domingo = ($_SESSION["sConexion"]->f('sun') == 1) ? "X" : "-";
                    }
                    break;
                default :
                    $tinicio = $_SESSION["sConexion"]->f('starttime');
                    $tfinal = $_SESSION["sConexion"]->f('endtime');

                    $lunes = ($_SESSION["sConexion"]->f('mon') == 1) ? "X" : "-";
                    $martes = ($_SESSION["sConexion"]->f('tue') == 1) ? "X" : "-";
                    $miercoles = ($_SESSION["sConexion"]->f('wed') == 1) ? "X" : "-";
                    $jueves = ($_SESSION["sConexion"]->f('thu') == 1) ? "X" : "-";
                    $viernes = ($_SESSION["sConexion"]->f('fri') == 1) ? "X" : "-";
                    $sabado = ($_SESSION["sConexion"]->f('sat') == 1) ? "X" : "-";
                    $domingo = ($_SESSION["sConexion"]->f('sun') == 1) ? "X" : "-";
                    break;
            }

            $nombreCat = $_SESSION["sConexion"]->f('nombrecat');
            $linea_horario = sprintf("<tr><td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
						 <td class='%s'><font color='%s'>%s</font></td></tr>",
                $fila, $color, $codigo, $fila, $color, $nombre,
                $fila, $color, $seccion, $fila, $color, $edificio,
                $fila, $color, $salon, $fila, $color, $tinicio,
                $fila, $color, $tfinal, $fila, $color, $lunes,
                $fila, $color, $martes, $fila, $color, $miercoles,
                $fila, $color, $jueves, $fila, $color, $viernes,
                $fila, $color, $sabado,
                $fila, $color, $domingo, $fila, $color, $nombreCat);


            fwrite($archivo, $linea_horario);

            $fila = ($fila == "odd-style") ? "pair-style" : "odd-style";

        } // end for

        fwrite($archivo, "</tbody></table>");
        fclose($archivo);

    } // fin function HacerHorario()


// *****************************************************************
// metodo que realiza la consulta que retorna el horario de examenes
// asociados al perido y al carne del estudiante
// *****************************************************************
    function HacerHorarioExamen($periodo, $anio)
    {
//     $query_examenes = sprintf(" SELECT h.codcurso,c.nombre,h.jornada,".
//              "h.diasemana,h.dia,h.salon,h.hora ".
//              "FROM horarioexamen h,curso c,asignacion a ".
//              "WHERE h.periodo = '%s' ".
//              "AND h.codcurso = c.codcurso " .
//              "AND h.periodo = a.periodo " .
//              "AND h.codcurso = a.codcurso " .
//              "AND a.userid = '%s' ",$periodo,$_SESSION["sUsuarioDeSesion"]->mUsuario);

        $query_examenes = $this->gsql->HacerHorarioExamen_select1($periodo, $_SESSION["sUsuarioDeSesion"]->mUsuario);


//     if ((strcmp($periodo,'07')==0) || (strcmp($periodo,'08')==0))
//          {
//            $agrega = "AND to_char(a.fecasig,'YYYY') = (h.anio + 1) ";
//            $anio = $anio + 1;
//          }
//     else {
//            $agrega = "AND to_char(a.fecasig,'YYYY') = h.anio ";
//            $anio = $anio;
//          }
//     $agrega = "AND to_char(a.fechasignacion,'YYYY') = h.anio ";

        $agrega = $this->gsql->HacerHorarioExamen_select1_1();

//     $query_examenes = $query_examenes . $agrega . sprintf( "AND to_char(a.fechasignacion,'YYYY') = '%d'",$anio);

        $query_examenes = $this->gsql->HacerHorarioExamen_select1_2($query_examenes, $agrega, $anio);

        echo $query_examenes;
        // Se obtienen los cursos
        $_SESSION["sConexion"]->query($query_examenes);

        return $_SESSION["sConexion"]->num_rows();
    } // final function HacerHorarioExamen()

// ********************************************************************************
// metodo que realiza el horario de examenes finales recibe como parametros
// el periodo, el anio y el nombre del archivo que se generara
// ********************************************************************************
    function DarHorarioFinales($periodo, $anio, $nombre_archivo)
    {
        $fila = "filaPar";


// solo por prueba $anio=2004;
//     $anio = 2004;

//     $query_horario=sprintf("SELECT e.curso,c.nomcurso,e.jornada,e.diasemana,e.dia,e.salones,e.horainicio
//     $query_horario=sprintf("SELECT e.curso,c.nombre,e.jornada,to_char(d.fecha,'DD') as dia,e.salones,e.edificio,e.horainicio,d.fecha
//                        FROM examencurso e,curso c,dia d
//                        WHERE e.periodo = '%s' AND
//                              e.anio = '%s'    AND
//                              d.anio = '%s'    AND d.periodo='%s' AND d.dia=e.dia AND
//                              e.curso = c.curso order by 2;",$periodo,$anio,$anio,$periodo);

        $query_horario = $this->gsql->DarHorarioFinales_select1($periodo, $anio);

//echo $query_horario; die;

// Se actualiza a 0 el campo horariocursostatus porque ya se va ha crear el horario
//  $query_status = sprintf("update parametrosgen set horarioexamenstatus='0' where activo=1;");
//  $query_status = sprintf("update periodovigencia set estadohorarioexamenes='0' "
//                  ."where periodo = '%s' and anio='%s' and activo=1;",$periodo,$anio);

        $query_status = $this->gsql->DarHorarioFinales_update1($periodo, $anio);

        $_SESSION["sConexion"]->query($query_status);

// Se obtienen los cursos
        $_SESSION["sConexion"]->query($query_horario);

        $numero_filas = $_SESSION["sConexion"]->num_rows();
        $archivo = fopen($nombre_archivo, "w");
        fwrite($archivo, "<table align='center' width='90%' cellspacing='0' cellpadding='0' border='1' bordercolor='#000000'>");
        $encabezado = "<tr><td class='encabezado'>Código</td>
                   <td class='encabezado'>Nombre de Curso</td>
                   <td class='encabezado'>Jornada</td>
                   <td class='encabezado'>D&iacute;a</td>
                   <td class='encabezado'>Hora</td>
                   <td class='encabezado'>Edificio</td>
                   <td class='encabezado'>Salones</td>
                   </tr>";

        fwrite($archivo, $encabezado);

        //if($periodo=='08' OR $periodo=='07')
        if ($periodo == '08') {
            $anio++;
        }

        for ($i = 1; $i <= $numero_filas; $i++) {
            $_SESSION["sConexion"]->next_record();
//     $tipos = $_SESSION["sConexion_1"]->f('tiposec') + 0;
//       switch($tipos) {
//        case 1 : $color = "#000000";break;
//        case 2 : $color = "#0000FF";break; // Laboratorio
//        case 3 : $color = "#9400D3";break; // Trabajo Dirigido
//        case 4 : $color = "#008000";break; // Dibujo
//        case 5 : $color = "#FF0000";break; // Practica
//       }

            $color = "#000000";

            $codigo = $_SESSION["sConexion"]->f('curso');
            $nombre = $_SESSION["sConexion"]->f('nombre');
            $jornada = $_SESSION["sConexion"]->f('jornada');
//    $dia_semana =  diasemana; //$_SESSION["sConexion_1"]->f('diasemana');
            $dia = $_SESSION["sConexion"]->f('dia');
            $edificio = $_SESSION["sConexion"]->f('edificio');
            $hora = $_SESSION["sConexion"]->f('horainicio');
            $salones = $_SESSION["sConexion"]->f('salones');
            $fecha = $_SESSION["sConexion"]->f('fecha');

            $mes = substr($fecha, 5, 2);

            $dia_numero = DiaDeLaSemana($dia, $mes, $anio);
            $dia_nombre = NombreDelDia($dia_numero);

            $fecha = $dia_nombre . " " . $dia;

            switch ($jornada) {
                case 1:
                    $jornada = "Matutina";
                    break;
                case 2:
                    $jornada = "Vespertina";
                    break;
                case 3:
                    $jornada = "Mixta";
                    break;
            } // fin del switch($jornada)


            $linea_horario = sprintf("<tr><td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td></tr>",
                $fila, $color, $codigo, $fila, $color, $nombre,
                $fila, $color, $jornada,
                $fila, $color, $fecha, $fila, $color, $hora,
                $fila, $color, $edificio, $fila, $color, $salones);


            fwrite($archivo, $linea_horario);

            $fila = ($fila == "filaImpar") ? "filaPar" : "filaImpar";

        } // end for

        fwrite($archivo, "</table>");
        fclose($archivo);

    } // fin DarExamensFinales

    function COAC_DarListadoDeCursos($anio,$periodo,$carrera=-1){
        $sql = $this->gsql->COAC_DarListadoDeCursos_selec1($anio,$periodo,$carrera);
        $_SESSION["sConexion"]->query($sql);

        $numero_filas = $_SESSION["sConexion"]->num_rows();

        return $numero_filas;
    }

// ********************************************************************************
// funcion que obtiene el listado de los cursos que imparte un catedratico
// en un periodo y anio especifico
// ********************************************************************************
    function DarListadoDeCursos($periodo, $anio, $pIdDocente)
    {

//     $query_curso = sprintf("select distinct(hd.curso),c.nombre,hd.seccion,h.estado
//                             from horariodetalle hd,horario h,curso c
//                             where hd.periodo = '%s'
//                             and hd.anio = '%s'
//                             and hd.tipo = 1
//                             and h.curso = hd.curso
//							 and h.periodo = hd.periodo
//                             and h.anio = hd.anio
//                             and h.seccion = hd.seccion
//                             and h.periodo = '%s'
//                             and h.anio = '%s'
//                             and hd.personal= '%s'
//                             and c.curso = hd.curso
//                             order by 1;",
//                             $periodo,$anio,
//                             $periodo,$anio,
//                             $_SESSION["sUsuarioDeSesion"]->mUsuario);

        $query_curso = $this->gsql->DarListadoDeCursos_select1($periodo, $anio,$pIdDocente);
        
        $_SESSION["sConexion"]->query($query_curso);
        $_SESSION["sConexion2"]=$query_curso;
        
       
        $numero_filas = $_SESSION["sConexion"]->num_rows();

        return $numero_filas;
    } //  fin de la funcion DarListadoDeCursos


// !!!!! BLOQUE NUEVO !!!!!  
// ********************************************************************************
// funcion que obtiene el listado de los cursos que imparte un catedratico
// en un periodo y anio especifico
// funcion que unicamente tiene cursos especificos 
// ********************************************************************************
    function DarListadoDeCursos1($periodo, $anio, $usuarioid)
    {
        $query_curso = $this->gsql->DarListadoDeCursos1_select1($periodo, $anio,$usuarioid);

// echo "<br>",$query_curso,"<br>";

        $_SESSION["sConexion"]->query($query_curso);

        $numero_filas = $_SESSION["sConexion"]->num_rows();

        $resultado = array();
        if($numero_filas>0) {
            for ($i = 0; $i < $numero_filas; $i++) {
                $_SESSION["sConexion"]->next_record();
                $resultado[] = $_SESSION["sConexion"]->r();
            }
        }

        return $resultado;
    } //  fin de la funcion DarListadoDeCursos
// FIN DE BLOQUE NUEVO     


// ********************************************************************************
// funcion que obtiene el listado de los demás docentes de un curso
// ********************************************************************************
    function DarListadoDocentesCurso($curso,$index,$carrera, $anio,$periodo, $_SESSIONsUsuarioDeSesionmUsuario, $tipo)
    {
        $query_curso = $this->gsql->DarListadoDocentesCurso_select1($curso,$index,$carrera, $anio,$periodo, $_SESSIONsUsuarioDeSesionmUsuario, $tipo);
        $_SESSION["sConexion"]->query($query_curso);
        $numero_filas = $_SESSION["sConexion"]->num_rows();

        $resultado = array();
        if($numero_filas>0) {
            for ($i = 0; $i < $numero_filas; $i++) {
                $_SESSION["sConexion"]->next_record();
                $resultado[] = $_SESSION["sConexion"]->r();
            }
        }

        return $resultado;

    } //  fin de la funcion DarListadoDocentesCurso

// ********************************************************************************
// funcion que retorna true si la sesion del usuario en el sistema ya caduco
// ********************************************************************************
    function hNoEsValidaLaSesion()
    {
    } // fin function NoEsValidaLaSesion()

// ********************************************************************************
// funcion que calcula en numero de asignados de un curso en particular 
// ********************************************************************************
    function AsignadosDelCurso($base, $anio, $periodo, $curso, $carrera/*$seccion*/,$index)
    {
//     $query_asignados = "select count(*) as numero from asignaciondetalle ".
//	                    "where anio=".$anio." and periodo='".$periodo."' and curso='".
//						$curso."' and seccion='".$seccion."';";

        $query_asignados = $this->gsql->AsignadosDelCurso_select1($anio, $periodo, $curso, $carrera/*$seccion*/,$index);

        $base->query($query_asignados);
        $base->next_record();

        $asignados = $base->f('numero');

        //echo "seccion = ".$seccion."=".$asignados." ";

        return $asignados;

    } // fin function AsignadosDelCurso()


    /**AGREGAGADO TEMPORALMENTE PARA VACACINES JUNIO 2009   */

    function HorarioVac($periodo, $anio, $nombre_archivo)
    {
//  echo $periodo;
//  echo $anio;
//  echo $nombre_archivo;
        $fila = "filaPar";
        /*
          $query_horario=sprintf("select c.curso,c.nombre,h.seccion,hd.edificio,
                hd.salon,hd.horainicio,hd.horafinal,hd.dias,hd.tipo
                from horario h,horariodetalle hd,curso c where
                h.periodo = '%s'       and
                h.anio    = '%s'       and
                h.curso   = hd.curso   and
                h.seccion = hd.seccion and
                h.periodo = hd.periodo and
                h.anio    = hd.anio    and
                ((substring(c.curso,0,2)= '%s' AND substring(c.curso,2,4) = substring(h.curso,2,4)) OR c.curso = h.curso )
                order by 2,3,9;",$periodo,$anio,DIGITO_DIPLOMADO);*/

//  $query_horario=sprintf("select c.curso,c.nombre,h.seccion,hd.edificio,
//        hd.salon,hd.horainicio,hd.horafinal,hd.dias,hd.tipo, (t.nombre||' '||t.apellido) as nombrecat 
//        from horario h,curso c,horariodetalle hd  left join tmppersonal t on hd.personal= t.personal where 
//        h.periodo = '%s'       and 
//        h.anio    = '%s'       and 
//        h.curso   = hd.curso   and 
//        h.seccion = hd.seccion and 
//        h.periodo = hd.periodo and 
//        h.anio    = hd.anio    and 
//        ((substring(c.curso,0,2)= '%s' AND substring(c.curso,2,4) = substring(h.curso,2,4)) OR c.curso = h.curso )
//        order by 2,3,9;",$periodo,$anio,DIGITO_DIPLOMADO);

        $query_horario = $this->gsql->HorarioVac_select1($periodo, $anio, DIGITO_DIPLOMADO);


//       . " and h.periodo='%s' "
//       . " and h.anio = %d "
//       . " and not exists (select horario from asignaseccion where"
//       . " asignaseccion.horario=h.horario"
//       . " and asignaseccion.codproblema=1"
//       . " and asignaseccion.anioalterno=h.anio)"


//echo "es el horario ".$query_horario; die;

// Se actualiza a 0 el campo horariocursostatus porque ya se va ha crear el horario
//  $query_status = sprintf("update parametrosgen set horariocursostatus='0' where activo=1;");

//  $query_status = sprintf("update periodovigencia set estadohorariocursos='0' where "
//                          ."activo  = 1    and "
//                          ."periodo = '%s' and "
//                          ."anio    = '%s';",$periodo,$anio);

        $query_status = $this->gsql->HorarioVac_update1($periodo, $anio);


        $_SESSION["sConexion"]->query($query_status);

// Se obtienen los cursos
        $_SESSION["sConexion"]->query($query_horario);

        $numero_filas = $_SESSION["sConexion"]->num_rows();

        $archivo = fopen($nombre_archivo, "w");

        $simbologia = "<table align='center'>
                         <tr>
                           <td>[ Clase Magistral ] &nbsp;&nbsp;</td>
                           <td><font color=#0000FF>[ Laboratorio ] &nbsp;&nbsp;</font></td>
                           <td><font color=#9400D3>[ Trabajo Dirigido ] &nbsp;&nbsp;</font></td>
                           <td><font color=#008000>[ Dibujo ] &nbsp;&nbsp;</font></td>
                           <td><font color=#FF0000>[ Práctica ] &nbsp;&nbsp;</font></td>
                         </tr>
                    </table>";

        fwrite($archivo, $simbologia);

        fwrite($archivo, "<table align='center' width='90%' cellspacing='0' cellpadding='0' border='1' bordercolor='#000000'>");

        $encabezado = "<tr><td class='encabezado'>Código</td>
                   <td class='encabezado'>Nombre de Curso</td>
                   <td class='encabezado'>Sección</td>
                   <td class='encabezado'>Edificio</td>
                   <td class='encabezado'>Salón</td>
                   <td class='encabezado'>Inicio</td>
                   <td class='encabezado'>Final</td>
                   <td class='encabezado'>Catedrático</td>
                   </tr>";

        fwrite($archivo, $encabezado);

        for ($i = 1; $i <= $numero_filas; $i++) {
            $_SESSION["sConexion"]->next_record();
            $tipos = $_SESSION["sConexion"]->f('tipo') + 0;

            switch ($tipos) {
                case 1 :
                    $color = "#000000";
                    break;
                case 2 :
                    $color = "#0000FF";
                    break; // Laboratorio
                case 3 :
                    $color = "#9400D3";
                    break; // Trabajo Dirigido
                case 4 :
                    $color = "#008000";
                    break; // Dibujo
                case 5 :
                    $color = "#FF0000";
                    break; // Practica
            }

            $codigo = $_SESSION["sConexion"]->f('curso');
            $nombre = $_SESSION["sConexion"]->f('nombre');
            $seccion = $_SESSION["sConexion"]->f('seccion');
            $edificio = $_SESSION["sConexion"]->f('edificio');
            $salon = $_SESSION["sConexion"]->f('salon');
            /*
                switch(trim(strtoupper($salon))) {
                  case "VIRTUAL" :
                            $tinicio = "&nbsp;";
                            $tfinal = "&nbsp;";
                            break;
                  default :
                            $tinicio = $_SESSION["sConexion"]->f('horainicio');
                            $tfinal = $_SESSION["sConexion"]->f('horafinal');
                            break;
                  }
            */
// Si no se quiere mostrar el horario del curso, se puede utilizar el código comentarizado arriba en lugar de las dos líneas siguientes.
// Para los casos en los que el curso se imparta de forma VIRTUAL.
            $tinicio = $_SESSION["sConexion"]->f('horainicio');
            $tfinal = $_SESSION["sConexion"]->f('horafinal');
            $nombrecat = $_SESSION["sConexion"]->f('nombrecat');

            $linea_horario = sprintf("<tr><td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
                         <td class='%s'><font color='%s'>%s</font></td>
						 <td class='%s'><font color='%s'>%s</font></td>
                       <td class='%s'><font color='%s'>%s</font></td></tr>",
                $fila, $color, $codigo, $fila, $color, $nombre,
                $fila, $color, $seccion, $fila, $color, $edificio,
                $fila, $color, $salon, $fila, $color, $tinicio,
                $fila, $color, $tfinal, $fila, $color, $nombrecat
            );


            fwrite($archivo, $linea_horario);

            $fila = ($fila == "filaImpar") ? "filaPar" : "filaImpar";

        } // end for

        fwrite($archivo, "</table>");
        fclose($archivo);

    } // fin function HacerHorarioVac()


} // final de la definición de la clase Horario

function DiaDeLaSemana($dia, $mes, $anio)
{
    $numerodiasemana = date('w', mktime(0, 0, 0, $mes, $dia, $anio));
    if ($numerodiasemana == 0)
        $numerodiasemana = 6;
    else
        $numerodiasemana--;
    return ($numerodiasemana + 1);
}

function NombreDelDia($dia)
{
    switch ($dia) {
        case 1:
            $nombre_dia = "Lunes";
            break;
        case 2:
            $nombre_dia = "Martes";
            break;
        case 3:
            $nombre_dia = "Miercoles";
            break;
        case 4:
            $nombre_dia = "Jueves";
            break;
        case 5:
            $nombre_dia = "Viernes";
            break;
        case 6:
            $nombre_dia = "Sabado";
            break;
        case 7:
            $nombre_dia = "Domingo";
            break;
    }
    return $nombre_dia;
}

function NombreDelMes($mes)
{
    switch ($mes) {
        case 1:
            $nombre_mes = "enero";
            break;
        case 2:
            $nombre_mes = "febrero";
            break;
        case 3:
            $nombre_mes = "marzo";
            break;
        case 4:
            $nombre_mes = "abril";
            break;
        case 5:
            $nombre_mes = "mayo";
            break;
        case 6:
            $nombre_mes = "junio";
            break;
        case 7:
            $nombre_mes = "julio";
            break;
        case 8:
            $nombre_mes = "agosto";
            break;
        case 9:
            $nombre_mes = "septiembre";
            break;
        case 10:
            $nombre_mes = "octubre";
            break;
        case 11:
            $nombre_mes = "noviembre";
            break;
        case 12:
            $nombre_mes = "diciembre";
            break;
    }
    return $nombre_mes;
}


?>
