<?php
    /**
     * Created by PhpStorm.
     * User: emsaban
     * Date: 21/08/14
     * Time: 02:34 PM
     */

    require_once("../../path.inc.php");
    require_once("$dir_portal/fw/model/Connection.php");
    include_once("$dir_portal/fw/model/DB_Connection.php");

    class ControlAssignationRequeriments1_SQL
    {
        private $objConnection;

        public function ControlAssignationRequeriments1_SQL()
        {
            $this->objConnection = new Connection();
        }

        public function  queryProcessInformation($pAnio,$pPeriodo1,$pPeriodo2,$pProceso,$pFecha)
        {
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_PROCESSACTIVE", "SELECT * FROM f_activeprocess($1::smallint,$2::smallint,array[$3::smallint,$4::smallint],$5);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_PROCESSACTIVE", array($pProceso,$pAnio,$pPeriodo1,$pPeriodo2,$pFecha));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'=> $row['r_result'],
                                      'msg' => $row['r_resultmsg'],
                            'schoolyear' => $row['r_schoolyear']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }

        public function  queryCheckEnrollment($pUsuario,$pCarrera,$pAnio)
        {
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_CHECKENROLLMENT", "SELECT * FROM f_activeenrollment($1::numeric,$2::smallint,$3::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_CHECKENROLLMENT", array($pUsuario,$pCarrera,$pAnio));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'=> $row['r_result'],
                                      'msg' => $row['r_resultmsg'],
                            'curriculum' => $row['r_curriculum'],
                            'enrollmentdate' => $row['r_enrollmentdate']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }
        
        public function queryAddEnrollment($data,$carrera,$anio){
            $vecResult = NULL;
            
            $carrera       = $carrera;
            $carnet        = $data[0]['CARNET'];
            $anio          = $anio;
            $inscripcion   = $data[0]['DETALLE_ACADEMICO']['FECHA_INSCRITO'];
            $inscripcion    = trim($inscripcion);
            IF(strlen($inscripcion)==0){
                $inscripcion=$anio."-01-01";
            }
            $nombre        = $data[0]['NOMBRE']; 
            
            $nombre = strtoupper($nombre);
            $nombre_array = split(' ',$nombre);
            $apellidos = '';
            $nombres   = '';
            $contador_apellidos=0;
            foreach($nombre_array as $na){
                if($contador_apellidos < 2){
                    $apellidos .= $na.' ';
                    if($na != 'DE' || $na != 'LA'){
                        $contador_apellidos ++;
                    }
                }else{
                    $nombres .= $na.' ';
                }
            }

            $apellidos = utf8_encode($apellidos);
            $nombres  = utf8_encode($nombres);
            
            $nombre1    = $apellidos;
            $nombre2    = '';
            $nombre3    = $nombres;
            $nombre4    = '';
            
            $sexo       ='';
            $nacimiento ='';
            $correo     ='';
            $nacionalidad ='';
            $nov        ='';
            $pin        ='';
            $dpi        ='';
           
            $connect = NEW DB_Connection();
            $connect->connect();
            $query = sprintf("delete from reingreso2015");
            if (($connect->query($query)) ) {
                 
              //   echo "borrado <br>";
             }
            
            $query =  sprintf("insert into reingreso2015 values
                (%d,%d,%d,'%s','%s','%s','%s','%s',%d,'%s','%s',%d,'%s','%s','%s');
						",  $carrera,$carnet,$anio,$inscripcion,$nombre1,$nombre2,$nombre3,$nombre4,$sexo,$nacimiento,$correo,$nacionalidad,$nov,$pin,$dpi); 
             if (($connect->query($query)) ) {
                 
                // echo "Ingresada <br>";
             }
            
             $query = sprintf("insert into tbenrollment
-- SE OBTIENE LOS DATOS PARA LA TABLA INSCRIPCION DE TODOS LOS ESTUDIANTES INSCRITOS QUE CASAN EN LA TABLA ESTUDIANTECARRERA
select a.* from 
(select 
 i.inscripcion::date,i.inscripcion::date,
 i.anio,0,0,0,0,1,0,i.carnet,i.carrera,20111203,case when i.carrera=2 then 2 else 4 end 
from reingreso2015 i left outer join tbstudentcareer ec on (i.carnet=ec.idstudent and i.carrera=ec.idcareer) where  ec.idstudent is NOT null
)a
left outer join tbenrollment e 
on a.carnet = e.idstudent and a.carrera = e.idcareer and a.anio = e.\"year\"  where e.idstudent is null;");
            if (($connect->query($query)) ) {
                 
              //   echo "borrado <br>";
             
                
            
                    
            }
                
            
            
        }

        public function  queryCheckAccessSite($pUsuario,$pGrupo,$pSitio,$pCarrera,$pAnio,$pPeriodo)
        {
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_CHECKACCESSSITE","SELECT * FROM f_accesssite($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint,$6::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_CHECKACCESSSITE", array($pUsuario,$pGrupo,$pSitio,$pCarrera,$pAnio,$pPeriodo));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'=> $row['r_result'],
                            'msg' => $row['r_resultmsg']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }

        public function queryScheduleInformation($pAnio,$pPeriodo,$pPensum,$pCarrera,$pTipo){
            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_SCHEDULEINFORMATION","SELECT * FROM f_getschedule($1::smallint,$2::smallint,$3::smallint,$4::smallint,$5::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_SCHEDULEINFORMATION", array($pPensum,$pCarrera,$pTipo,$pAnio,$pPeriodo));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('index'=> $row['r_index'],
                            'course' => $row['r_course'],
                            'name' => $row['r_name'],
                            'lab' => $row['r_lab'],
                            'required' => $row['r_required'],
                            'requirement' => $row['r_requirement'],
                            'credits' => $row['r_credits'],
                            'curriculum' => $row['r_curriculum'],
                            'section' => $row['r_section']);
                        $vecResult[] = $rRow;
                    }
                }
            }
            return $vecResult;
        }

        public function queryCheckAssignation($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo) {

            $vecResult = NULL;
            if ($this->objConnection->prepared("SELECT_CHECKASSIGNATION","SELECT * FROM f_getassignation($1::numeric,$2::smallint,$3::smallint,$4::smallint,$5::smallint);")){
                $result = $this->objConnection->ejecuteStatement("SELECT_CHECKASSIGNATION", array($pEstudiante,$pCarrera,$pPensum,$pAnio,$pPeriodo));
                $pos=0;
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('cindex'=> $row['r_index'],
                            'course' => $row['r_course'],
                            'section' => $row['r_section'],
                            'name' => $row['r_name'],
                            'credits' => $row['r_credits'],
                            'lab' => $row['r_lab'],
                            'labgroup' => $row['r_labgroup'],
                            'requirement' => $row['r_requirement'],
                            'assigned' => $row['r_assigned'],
                            'check' => $row['r_check']);
                        $vecResult[++$pos] = $rRow;
                    }
                }
            }
            return $vecResult;
        }
    }

?>