<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 2/09/14
 * Time: 03:39 PM
 */

require_once("../../path.inc.php");
require_once("$dir_portal/fw/model/sql/ValidateCourseSchedule_SQL.php");
require_once("$dir_biblio/biblio/SysConstant.php");
require_once("$dir_portal/fw/model/mapping/TbUser.php");


class ValidateCourseSchedule
{
    private $objUser;
    private $vecCursos;
    private $horario;
    private $year;
    private $scheduleYear;
    private $objServiceQuery;

    public function ValidateCourseSchedule($pUser, $pAssignationParamHandler)
    {
        $this->objUser = $pUser;
        $this->year = $pAssignationParamHandler->getYear();
        $this->scheduleYear = $pAssignationParamHandler->getSchoolYear();
        $this->objServiceQuery = new ValidateCourseSchedule_SQL();
    }

    public function  &getAssignation()
    {
        return $this->vecCursos;
    }

    public function setAssignation($pAssignation)
    {
        $this->vecCursos = $pAssignation;
    }

    public function &getYear()
    {
        return $this->year;
    }

    public function setYear($pYear)
    {
        $this->year = $pYear;
    }

    public function &getScheduleYear()
    {
        return $this->scheduleYear;
    }

    public function setScheduleYear($pScheduleYear)
    {
        $this->scheduleYear = $pScheduleYear;
    }

    private function time_diff_mins_($start_time, $end_time)
    {
        $start_time = explode(':', $start_time);
        $end_time = explode(':', $end_time);
        $end_ts = $end_time[0] * 60 + $end_time[1];
        $start_ts = $start_time[0] * 60 + $start_time[1];

        if ($start_time[0] > $end_time[0]) {
            $diff_ts = (24 * 60) - $start_ts + $end_ts;
        } else {
            $diff_ts = $end_ts - $start_ts;
        }

        return $diff_ts;
    }

    private function _sumar_minutos_($start_time, $cantidad)
    {
        $hora = explode(':', $start_time);
        $minutos = $hora[1] + $cantidad;
        if ($minutos >= 60) {
            $hora[0]++;
            if ($hora[0] < 10) {
                $hora[0] = '0' . $hora[0];
            }
            $minutos = $minutos - 60;
        }
        if ($minutos < 10) {
            $minutos = '0' . $minutos;
        }
        $tiempo = $hora[0] . ":" . $minutos;
        return $tiempo;
    }

    private function _restar_minutos_($start_time, $cantidad)
    {
        $hora = explode(':', $start_time);
        $minutos = $hora[1] - $cantidad;
        if ($minutos < 0) {
            $hora[0]--;
            $minutos = $minutos + 60;
        }
        if ($minutos < 10) {
            $minutos = '0' . $minutos;
        }
        if ($hora[0] < 10) {
            $hora[0] = '0' . $hora[0];
        }
        $tiempo = $hora[0] . ":" . $minutos;
        return $tiempo;
    }

    /**
     *** Modificó:    Edwin Saban
     *** Fecha:       28-02-2013
     *** Descripción: Carga el horario del curso
     ***/
    private function getCourseSchedule($ipos)
    {
        $result = $this->objServiceQuery->queryCourseSchedule($this->vecCursos[$ipos]['cindex'],
            $this->vecCursos[$ipos]['course'],
            CLASE_MAGISTRAL,
            $this->vecCursos[$ipos]['section'],
            $this->year,
            $this->scheduleYear,
            $this->objUser->getCareer());

        if(strcmp($this->vecCursos[$ipos]['labgroup'],'')!=0) {// Si el curso tiene laboratorio
            $result1 = $this->objServiceQuery->queryCourseSchedule($this->vecCursos[$ipos]['cindex'],
                $this->vecCursos[$ipos]['course'],
                LABORATORIO,
                $this->vecCursos[$ipos]['labgroup'],
                $this->year,
                $this->scheduleYear,
                $this->objUser->getCareer());

            foreach($result1 as $labSchedule) {
                $result[] = $labSchedule;
            }
        }

        if (strcmp($this->vecCursos[$ipos]['course'], "") != 0) {
            $horario_curso = array();
            foreach ($result as $schedule) {
                $schedule['indice'] = $ipos;
                $schedule['mon'] = array('active' => $schedule['mon'], 'overlapping' => 0);
                $schedule['tue'] = array('active' => $schedule['tue'], 'overlapping' => 0);
                $schedule['wed'] = array('active' => $schedule['wed'], 'overlapping' => 0);
                $schedule['thu'] = array('active' => $schedule['thu'], 'overlapping' => 0);
                $schedule['fri'] = array('active' => $schedule['fri'], 'overlapping' => 0);
                $schedule['sat'] = array('active' => $schedule['sat'], 'overlapping' => 0);
                $schedule['sun'] = array('active' => $schedule['sun'], 'overlapping' => 0);
                array_push($horario_curso, $schedule);
            }
            array_push($this->horario, $horario_curso);
        }

        unset($horario_curso);
    }

    public function validateScheduleProcess()
    {
        $this->horario = array();

        for ($pos = 1; $pos <= count($this->vecCursos); $pos++) {
            $this->getCourseSchedule($pos);
        }

        return $this->validateSchedule();
    }

    private function validateSchedule()
    {
        $traslape = OK;
        $numCursos = count($this->horario);

        for ($k = 0; $k < $numCursos; $k++) {
            if (strcmp($this->horario[$k][0]['course'], "") != 0) {
                for ($j = $k + 1; $j < $numCursos; $j++) {

                    $horario_curso1 = $this->horario[$k];
                    $horario_curso2 = $this->horario[$j];

                    $numHorariosCurso1 = count($horario_curso1);
                    $numHorariosCurso2 = count($horario_curso2);

                    for ($n = 0; $n < $numHorariosCurso1; $n++) {
                        for ($m = 0; $m < $numHorariosCurso2; $m++) {
                            // Datos del horario del curso 1
                            $horainicio1 = $horario_curso1[$n]['starttime'];
                            $horafinal1 = $horario_curso1[$n]['endtime'];

                            // Datos del horario del curso 2
                            $horainicio2 = $horario_curso2[$m]['starttime'];
                            $horafinal2 = $horario_curso2[$m]['endtime'];

                            // Se verifica si existe intersección entre los horarios
                            if (((($horainicio1 >= $horainicio2) AND ($horainicio1 < $horafinal2)) OR (($horafinal1 > $horainicio2) AND ($horafinal1 <= $horafinal2))) OR
                                ((($horainicio2 >= $horainicio1) AND ($horainicio2 < $horafinal1)) OR (($horafinal2 > $horainicio1) AND ($horafinal2 <= $horafinal1)))
                            ) {
                                if(!isset($this->vecCursos[$horario_curso1[$n]['indice']]['remark'])) {
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'] = array();
                                }

                                if(!isset($this->vecCursos[$horario_curso2[$m]['indice']]['remark'])) {
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'] = array();
                                }

                                // Aqui se valida si la inteseccion de horarios se da en un mismo dia, lo que indica que si hay traslape
                                if (($horario_curso1[$n]['mon']['active'] == 1) and ($horario_curso2[$m]['mon']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['mon'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['mon'] = 1;

                                    $horario_curso1[$n]['mon']['overlapping'] = 1;
                                    $horario_curso2[$m]['mon']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }

                                if (($horario_curso1[$n]['tue']['active'] == 1) and  ($horario_curso2[$m]['tue']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['tue'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['tue'] = 1;

                                    $horario_curso1[$n]['tue']['overlapping'] = 1;
                                    $horario_curso2[$m]['tue']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }

                                if (($horario_curso1[$n]['wed']['active'] == 1) and ($horario_curso2[$m]['wed']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['wed'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['wed'] = 1;

                                    $horario_curso1[$n]['wed']['overlapping'] = 1;
                                    $horario_curso2[$m]['wed']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }

                                if (($horario_curso1[$n]['thu']['active'] == 1) and ($horario_curso2[$m]['thu']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['thu'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['thu'] = 1;

                                    $horario_curso1[$n]['thu']['overlapping'] = 1;
                                    $horario_curso2[$m]['thu']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }

                                if (($horario_curso1[$n]['fri']['active'] == 1) and ($horario_curso2[$m]['fri']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['fri'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['fri'] = 1;

                                    $horario_curso1[$n]['fri']['overlapping'] = 1;
                                    $horario_curso2[$m]['fri']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }

                                if (($horario_curso1[$n]['sat']['active'] == 1) and ($horario_curso2[$m]['sat']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['sat'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['sat'] = 1;

                                    $horario_curso1[$n]['sat']['overlapping'] = 1;
                                    $horario_curso2[$m]['sat']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }

                                if (($horario_curso1[$n]['sun']['active'] == 1) and ($horario_curso2[$m]['sun']['active'] == 1)) {
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso1[$n]['indice']]['remark']);
                                    $this->checkCourseRemark(CURSO_TRASLAPE, $this->vecCursos[$horario_curso2[$m]['indice']]['remark']);

                                    $claveCurso = $horario_curso1[$n]['indice'] . ':' . $horario_curso1[$n]['index'] . ':' . $horario_curso1[$n]['course'] . ':' . $horario_curso1[$n]['type'];
                                    $this->vecCursos[$horario_curso2[$m]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['sun'] = 1;

                                    $claveCurso = $horario_curso2[$m]['indice'] . ':' . $horario_curso2[$m]['index'] . ':' . $horario_curso2[$m]['course'] . ':' . $horario_curso2[$m]['type'];
                                    $this->vecCursos[$horario_curso1[$n]['indice']]['remark'][CURSO_TRASLAPE][$claveCurso]['sun'] = 1;

                                    $horario_curso1[$n]['sun']['overlapping'] = 1;
                                    $horario_curso2[$m]['sun']['overlapping'] = 1;
                                    $traslape = FAIL;
                                }
                            }
                        }
                    }
                    // Al finalizar la verificacion se establece nuevamente el valor del horario debido a que
                    // pudieron haber dias traslapados
                    $this->horario[$k] = $horario_curso1;
                    $this->horario[$j] = $horario_curso2;

                } // del for $j
            } // if de verifiacacion de  que curso es vacio
        } // del for $k
        return $traslape;
    }

    private function checkCourseRemark($pRemark, &$pArray)
    {
        if (!array_key_exists($pRemark, $pArray)) {
            $pArray[$pRemark] = array();
        }
    }
}

?>