<?php
    /**
     * Created by PhpStorm.
     * User: emsaban
     * Date: 20/08/14
     * Time: 09:45 AM
     */


    require_once("/var/www/path.inc.php");
    require_once("$dir_portal/fw/model/Connection.php");

    class ControlCourse_SQL
    {
        private $objConnection;

        public function ControlCourse_SQL()
        {
            $this->objConnection = new Connection();
        }

        public function queryCoursetList($pStudent, $pCareer)
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_COURSELIST", "SELECT t1.idstudent,t1.idcurriculum,t1.idcourse,t3.name,t2.cycle,t2.credits,t1.approvaldate,case when t1.idapprovalform=300 then 'EQV'::text else t1.score::text end as score,t1.description,t2.index,t2.idmodule,t4.name as namep " .
                "FROM tbapprovedcourse t1, tbcurriculum t2,tbcourse t3, tbcurriculumdata t4 " .
                "WHERE t1.idstudent=$1 AND t1.idcareer=$2 AND t1.idcourse=t2.idcourse AND t1.idcurriculum=t2.idcurriculum AND t1.validitystart=t2.validitystart AND t2.idcourse=t3.idcourse AND t2.index=t3.index AND t2.idcurriculum=t4.idcurriculum " .
                "ORDER by t2.cycle,t4,idmodule,t1.approvaldate,t1.idcourse")
            ) {
                $result = $this->objConnection->ejecuteStatement("SELECT_COURSELIST", array($pStudent, $pCareer));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idstudent'    => $row['idstudent'],
                                      'idcurriculum' => $row['idcurriculum'],
                                      'idcourse'     => $row['idcourse'],
                                      'name'         => $row['name'],
                                      'cycle'        => $row['cycle'],
                                      'credits'      => $row['credits'],
                                      'approvaldate' => $row['approvaldate'],
                                      'score'        => $row['score'],
                                      'description'  => $row['description'],
                                      'idmodule'     => $row['idmodule'],
                                      'namep'        => $row['namep']);
                        $vecCourseList[] = $rRow;
                    }
                }
            }

            return $vecCourseList;
        }
            
        public function queryCertificateList($pStudent, $pCareer,$pCurriculum)
        {
			//print_r($pCurriculum);die;
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_CERTILIST", "select SURNAME ||', '||name as student,cycle as ciclo, cyclename as nombreciclo, idcurriculum as pensum,curriculumname as pensumnombre,
                        idcourse as curso, coursename as cursonombre,score as nota, 
                        (select * from f_get_numero_letras
                         (CASE WHEN SCORE='' THEN 0 ELSE score::numeric END)) as letras,
                         TO_CHAR(case when approvaldate='' then '1900-01-01' else approvaldate::timestamp end, 'MM-YYYY') as aprobacion
                         ,approvaldate as fechaaprobacion,description as descripcion from 
                         f_get_student_certication($1,$2,$3) ce, tbstudent es
                         where ce.idstudent=es.idstudent;")
            ) {
                $result = $this->objConnection->ejecuteStatement("SELECT_CERTILIST", array($pStudent, $pCareer,$pCurriculum));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('student'    => $row['studentname'],
                                      'ciclo'    => $row['ciclo'],
                                      'nombreciclo'    => $row['nombreciclo'],
                                      'pensum'    => $row['pensum'],
                                      'pensumnombre'    => $row['pensumnombre'],
                                      'curso'    => $row['curso'],
                                      'cursonombre'    => $row['cursonombre'],
                                      'nota'    => $row['nota'],
                                      'letras'    => $row['letras'],
                                      'aprobacion'    => $row['aprobacion'],
                                      'fechaaprobacion'    => $row['fechaaprobacion'],
                                      'descripcion'    => $row['descripcion'],
                                );
                        $vecCourseList[] = $rRow;
                    }
                }
            }

            return $vecCourseList;
        }
        
        public function queryCierrePensum($pStudent, $pCareer,$pCurriculum){
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_CIERRE", "select idstudent, idcareer, idcourse,idcurriculum,approvaldate from tbapprovedcourse
                                                where idcourse = 900 and idstudent = $1
                                                and idcareer = $2
                                                and idcurriculum = $3;")
            ) {
                $result = $this->objConnection->ejecuteStatement("SELECT_CIERRE", array($pStudent, $pCareer,$pCurriculum));
            }
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                       $rRow = array('student'    => $row['idstudent'],
                                      'carrera'    => $row['idcareer'],
                                      'curso'    => $row['idcourse'],
                                      'aprobacion'    => $row['approvaldate'],
                                );
                        $vecCourseList[] = $rRow;
                    }
                }
                return $vecCourseList;
        }
        
        public function queryEPS($pStudent, $pCareer,$pCurriculum){
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_EPS", "select ac.idstudent,ac.approvaldate,ac.score, (select * from f_get_numero_letras(CASE WHEN ac.SCORE=0 THEN 0 ELSE ac.score::numeric END)) as letras,ac.description, 
                                                                    ei.startdate,ei.enddate,ei.institutionname,ei.institutionaddres  
                                                                 from tbapprovedcourse ac
                                                         left join 
                                                         tbepsinformation ei
                                                         on ac.idstudent = ei.idstudent
                                                         and ac.idcareer		= ei.idcareer
                                                         and ac.idcurriculum	= ei.idcurriculum
                                                         and ac.idcourse	 = ei.idcourse
                                                         where
                                                         ac.idstudent = $1
                                                         and ac.idcareer = $2
                                                         and ac.idcurriculum = $3
                                                         and ac.idcourse = 743
;")
            ) {
                $result = $this->objConnection->ejecuteStatement("SELECT_EPS", array($pStudent, $pCareer,$pCurriculum));
            }
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                       $rRow = array('estudiante'    => $row['idstudent'],
                                      'aprobacion'    => $row['approvaldate'],
                                      'nota'         => $row['score'],
                                      'letras'         => $row['letras'],
                                      'descripcion'    => $row['description'],
                                      'fechain'     => $row['startdate'],
                                      'fechafn'    => $row['enddate'],
                                      'lugarnom'    => $row['institutionname'],
                                      'lugardir'    => $row['institutionaddres'],
                                );
                        $vecCourseList[] = $rRow;
                    }
                }
                return $vecCourseList;
        }
        
        public function queryAvgCourseList($pStudent, $pCareer)
        {
            $vecRegs = NULL;
            if ($this->objConnection->prepared("SELECT_COURSEAVG", "SELECT t1.idstudent,t1.idcareer,t1.idcurriculum,t4.name,round(avg(score),2) as avg,count(*) as numc " .
                "FROM tbapprovedcourse t1, tbcurriculum t2,tbcourse t3, tbcurriculumdata t4 " .
                "WHERE t1.idstudent=$1 AND t1.idcareer=$2 AND t1.idcourse=t2.idcourse AND t1.idcurriculum=t2.idcurriculum " .
                "AND t1.validitystart=t2.validitystart AND t2.idcourse=t3.idcourse AND t2.index=t3.index " .
                "AND t2.idcurriculum=t4.idcurriculum AND cycle between 1 AND 10 and t1.idcourse!=743 " .
                "  and score > 0".    
                "GROUP BY t1.idstudent,t1.idcareer,t1.idcurriculum,t4.name;")
            ) {
                $result = $this->objConnection->ejecuteStatement("SELECT_COURSEAVG", array($pStudent, $pCareer));

                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idstudent'    => $row['idstudent'],
                                      'idcareer'     => $row['idcareer'],
                                      'idcurriculum' => $row['idcurriculum'],
                                      'name'         => $row['name'],
                                      'avg'          => $row['avg'],
                                      'numc'         => $row['numc']);
                        $vecRegs[] = $rRow;
                    }
                }
            }

            return $vecRegs;
        }
        
        public function querySection($carnet, $idcourse)
        {
            $sec = "-";
            //*****************************************************************************
            if ($this->objConnection->prepared("SectionGet", "SELECT t.section as sec
                                                            FROM (
                                                                    SELECT section, idcourse, max(year)
                                                                    FROM tbassignation asig JOIN tbassignationdetail det ON (asig.idassignation = det.idassignation) 
                                                                    WHERE asig.idstudent = $1
																	AND det.idschoolyear in (100,200)
                                                                    group by section, idcourse
                                                            ) as t
                                                            WHERE t.idcourse = $2
                                                ")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SectionGet", array($carnet, $idcourse)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $sec = $row['sec'];
                    }
                }
            }
            //*****************************************************************************
            return $sec;
        }
        
        public function queryHorarioCurso($carnet, $idcourse)
        {
            $sec = "-";
            //*****************************************************************************
            if ($this->objConnection->prepared("SectionGet", "SELECT t.section as sec
                                                            FROM (
                                                                    SELECT section, idcourse, max(year)
                                                                    FROM tbassignation asig JOIN tbassignationdetail det ON (asig.idassignation = det.idassignation) 
                                                                    WHERE asig.idstudent = $1
                                                                    group by section, idcourse
                                                            ) as t
                                                            WHERE t.idcourse = $2
                                                ")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SectionGet", array($carnet, $idcourse)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $sec = $row['sec'];
                    }
                }
            }
            //*****************************************************************************
            return $sec;
        }
        
        
        
                
        
        
        public function querySchoolyearOrder($ORDENPAGO){
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("GET_SCHOOLYEAR_ORDER", "SELECT cp.idschoolyear as periodo
                                                                            FROM tbcoursepayment cp
                                                                            WHERE cp.paymentorder = $1"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("GET_SCHOOLYEAR_ORDER", array($ORDENPAGO)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('periodo'     => $row['periodo']);
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
		
		public function queryRestoreMaster($ORDENPAGO){
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("INSERT_RESTORE_MASTER", "INSERT INTO tbcoursepayment 
                                                                    SELECT *
                                                                    FROM tborder cp
                                                                    WHERE cp.paymentorder = $1"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("INSERT_RESTORE_MASTER", array($ORDENPAGO)); //Ejecucion y paso de parametros a la consulta
                if ($result) 
                {
                    $vecCourseList=true;
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        
        public function queryRestoreSlave($ORDENPAGO){
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("INSERT_RESTORE_SLAVE", "INSERT INTO tbcoursepaymentdetail
                                                                            SELECT *
                                                                            FROM tborderdetail cp
                                                                            WHERE cp.paymentorder = $1"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("INSERT_RESTORE_SLAVE", array($ORDENPAGO)); //Ejecucion y paso de parametros a la consulta
                if ($result) 
                {
                    $vecCourseList=true;
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        
        public function querySearchOrderWS($ORDENPAGO)
        {
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("OBTENER_ORDEN", "SELECT 
                                                                                CASE
                                                                                        WHEN (
                                                                                                SELECT cp.paymentorder
                                                                                                FROM tbcoursepayment cp
                                                                                                WHERE cp.paymentorder = $1
                                                                                        ) IS NOT NULL THEN 1
                                                                                        WHEN (
                                                                                                SELECT tor.paymentorder
                                                                                                FROM tborder tor
                                                                                                WHERE tor.paymentorder = $1
                                                                                        ) IS NOT NULL THEN 2
                                                                                        ELSE 0
                                                                                END AS opcion")
            ) {                
                $result = $this->objConnection->ejecuteStatement("OBTENER_ORDEN", array($ORDENPAGO)); //Ejecucion y paso de parametros a la consulta
                //echo"<script type=\"text/javascript\">alert('Antes de ejecutar consulta');</script>";
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('estatus'     => $row['opcion']);
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
		
		
		public function queryProcesoAsignacion($ORDENPAGO){
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("INSERT_ASIG_AUTOMATICO", "select f_set_asignacion($1);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("INSERT_ASIG_AUTOMATICO", array($ORDENPAGO)); //Ejecucion y paso de parametros a la consulta
                //echo"<script type=\"text/javascript\">alert('Antes de ejecutar consulta');</script>";
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('estatus'     => $row['f_set_asignacion']);
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
		
		public function queryProcesoAsignacionVacas($ORDENPAGO){
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("INSERT_ASIG_AUTOMATICO_VACAS", "select f_set_asignacionvacaciones($1);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("INSERT_ASIG_AUTOMATICO_VACAS", array($ORDENPAGO)); //Ejecucion y paso de parametros a la consulta
                //echo"<script type=\"text/javascript\">alert('Antes de ejecutar consulta');</script>";
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('estatus'     => $row['f_set_asignacion']);
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        
        public function queryMoveOrdenR1($ppaymentorder)
        {
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("SELECT_MOV_R1", "SELECT f_move_orden($1);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_MOV_R1", array($ppaymentorder)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'     => $row['f_move_orden']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        
        //********************** Funciones para simulacion de pago retrasada 2
        
        
        
		
	public function queryVerifyInscripcion($idstudent, $year, $idcareer)
        {
             $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("SELECT_INSC", "SELECT f_get_inscripcion resultado "
                                    .                          "FROM f_get_inscripcion($1,"
                                    .                                                 "$2,"
                                    .                                                 "$3"
                                    .                                                  ");"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_INSC", array($idstudent, $year, $idcareer)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('resultado'     => $row['resultado']
                                      );
                        //echo '<b>Curso encontrado: '.$row['idcourse'].'</b>';
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        //************************************ FUNCIONES MODULO DE RETRASADA1 ***********************************************
        public function queryActivationRetrasada1()
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECTACTIVE1", "
                                                                SELECT DISTINCT schoolyear as P1, year as P2, state as P3
                                                                FROM tbproc_processactivation
                                                                WHERE state = 1
                                                                AND schoolyear in (102,202)
                                                                AND NOW() BETWEEN startdate and enddate
                                                            ")
            ) {                
                $result = $this->objConnection->ejecuteQuery("
                                                                SELECT DISTINCT schoolyear as P1, year as P2, state as P3
                                                                FROM tbproc_processactivation
                                                                WHERE state = 1
                                                                AND schoolyear in (102,202)
                                                                AND NOW() BETWEEN startdate and enddate
                                                            "); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('schoolyear'     => $row['p1'],
                                      'anio'   => $row['p2'],
                                      'estado'   => $row['p3']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            return $vecCourseList;
        }
        public function queryCursosRetrasada1($pStudent, $pAnio, $pPeriodo,$periodoActual,$anioSis,$carrera,$diasretra)
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECTR1", "select r_idcourse, r_name FROM f_get_AssignationRetrasada1($1, $2, $3, $4, $5, $6, $7);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECTR1", array($pStudent, $pAnio, $pPeriodo,$periodoActual,$anioSis,$carrera,$diasretra)); //Ejecucion y paso de parametros a la consulta
                //echo"<script type=\"text/javascript\">alert('Antes de ejecutar consulta');</script>";
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idcourse'     => $row['r_idcourse'],
                                      'name'   => $row['r_name']
                                      );
                        
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryBuscarOrdenR1($pStudent, $pAnio, $pPeriodo){
            $vecCourseList = NULL;

            if ($this->objConnection->prepared("SELECT_ORD_R1", "SELECT * FROM tbcoursepayment
                                                                WHERE idstudent=$1
                                                                AND year=$2
                                                                AND idschoolyear=$3")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_ORD_R1", array($pStudent, $pAnio, $pPeriodo)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('paymentorder'     => $row['paymentorder'],
                                     'paymentidnumber'     => $row['paymentidnumber'],
                                     'bankname'     => $row['bankname'],
                                     'paymentdate'     => $row['paymentdate'],
                                     'paymenttime'     => $row['paymenttime']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryBuscarOrdenPagadaR1($pStudent, $pAnio, $pPeriodo,$pCarrera){
            $vecCourseList = NULL;

            if ($this->objConnection->prepared("SELECT_ORD_PAG_R1", "SELECT DISTINCT CPD.idcourse, cour.name, CP.paymentorder, CP.paymentidnumber,CP.bankname, CP.paymentdate, CP.paymenttime
                                                FROM tbcoursepayment CP JOIN tbcoursepaymentdetail CPD 
                                                ON (CP.paymentorder = CPD.paymentorder) JOIN tbcourse cour
                                                ON (CPD.idcourse = cour.idcourse) JOIN tbcurriculum cur
                                                ON (cur.idcourse = cour.idcourse) 
                                                WHERE cur.index = cour.index
                                                AND cur.validityend is null
                                                AND cur.index = 1
                                                AND CP.idstudent = $1
                                                AND CP.year = $2
                                                AND CP.idschoolyear = $3
                                                AND cur.idcareer = $4;
                                                ")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_ORD_PAG_R1", array($pStudent, $pAnio, $pPeriodo,$pCarrera)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idcourse'     => $row['idcourse'],
                                     'name'     => $row['name'],
                                     'paymentorder'     => $row['paymentorder'],
                                     'paymentidnumber'     => $row['paymentidnumber'],
                                     'bankname'     => $row['bankname'],
                                     'paymentdate'     => $row['paymentdate'],
                                     'paymenttime'     => $row['paymenttime']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryOrdenRetrasada1($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $checksum, $rubro)
        {
            $vecCourseList = false;
            
            if ($this->objConnection->prepared("INSERT_ORDEN_R1", "INSERT INTO tbcoursepayment(
                                                                                paymentorder,
                                                                                paymentorderdate,
                                                                                paymentordertime,
                                                                                amount,
                                                                                verca,
                                                                                verws,
                                                                                idpaymenttype,
                                                                                idcareer,
                                                                                idstudent,
                                                                                verifier,
                                                                                requesttype,
                                                                                year,
                                                                                idschoolyear,
                                                                                checksum,
                                                                                rubro
                                                                                )
                                                                        VALUES ($1,
                                                                                now(),
                                                                                now(),
                                                                                $2,
                                                                                $3,
                                                                                $4,
                                                                                $5,
                                                                                $6,
                                                                                $7,
                                                                                $8,
                                                                                $9,
                                                                                $10,
                                                                                $11,
                                                                                $12,
                                                                                $13
                                                                                );
                    ")
                ) 
            {
                $result = $this->objConnection->ejecuteStatement("INSERT_ORDEN_R1", array($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $checksum, $rubro));
                if ($result) 
                {
                    $vecCourseList=true;
                    
                }
            }
            return $vecCourseList;
        }
                
        public function queryDetalleRetrasada1($paymentorder, $price, $inscriptionprice, $idcourse, $lab)
        {
            $vecCourseList = false;
            
            if ($this->objConnection->prepared("INSERT_DETALLE_R1", "INSERT INTO tbcoursepaymentdetail(
                                                                            paymentorder,
                                                                            price,
                                                                            inscriptionprice,
                                                                            idcourse,
                                                                            lab
                                                                            )
                                                                    VALUES ($1,
                                                                            $2,
                                                                            $3,
                                                                            $4,
                                                                            $5
                                                                            );
                    ")
                )
            {
                $result = $this->objConnection->ejecuteStatement("INSERT_DETALLE_R1", array($paymentorder, $price, $inscriptionprice, $idcourse, $lab));
                if ($result) 
                {
                    $vecCourseList=true;
                }
            }
            return $vecCourseList;
        }
        public function queryBuscarDetOrdenR1($paymentorder){
            $vecCourseList = NULL;
            
            if ($this->objConnection->prepared("SELECT_DET_R1", "SELECT paymentorder, price, idcourse 
                                                                FROM tbcoursepaymentdetail
                                                                WHERE paymentorder = $1;"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_DET_R1", array($paymentorder)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('paymentorder'     => $row['paymentorder'],
                                    'price'     => $row['price'],
                                    'idcourse'     => $row['idcourse']
                                      );
                        //echo '<b>Curso encontrado: '.$row['idcourse'].'</b>';
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        
        //************************************ FUNCIONES MODULO DE RETRASADA2 ***********************************************
        public function queryActivationRetrasada2()
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECTACTIVE2", "
                                                                SELECT DISTINCT schoolyear as P1, year as P2, state as P3
                                                                FROM tbproc_processactivation
                                                                WHERE state = 1
                                                                AND schoolyear in (103,203)
                                                                AND NOW() BETWEEN startdate and enddate
                                                            ")
            ) {                
                $result = $this->objConnection->ejecuteQuery("
                                                                SELECT DISTINCT schoolyear as P1, year as P2, state as P3
                                                                FROM tbproc_processactivation
                                                                WHERE state = 1
                                                                AND schoolyear in (103,203)
                                                                AND NOW() BETWEEN startdate and enddate
                                                            "); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('schoolyear'     => $row['p1'],
                                      'anio'   => $row['p2'],
                                      'estado'   => $row['p3']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            return $vecCourseList;
        }
        public function queryBuscarOrdenPagadaR2($pStudent, $pAnio, $pPeriodo,$pCarrera){
            $vecCourseList = NULL;

            if ($this->objConnection->prepared("SELECT_ORD_PAG_R2", "SELECT DISTINCT CPD.idcourse, cour.name, CP.paymentorder, CP.paymentidnumber,CP.bankname, CP.paymentdate, CP.paymenttime
                                                FROM tbcoursepayment CP JOIN tbcoursepaymentdetail CPD 
                                                ON (CP.paymentorder = CPD.paymentorder) JOIN tbcourse cour
                                                ON (CPD.idcourse = cour.idcourse) JOIN tbcurriculum cur
                                                ON (cur.idcourse = cour.idcourse) 
                                                WHERE cur.index = cour.index
                                                AND cur.validityend is null
                                                AND cur.index = 1
                                                AND CP.idstudent = $1
                                                AND CP.year = $2
                                                AND CP.idschoolyear = $3
                                                AND cur.idcareer = $4;
                                                ")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_ORD_PAG_R2", array($pStudent, $pAnio, $pPeriodo,$pCarrera)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idcourse'     => $row['idcourse'],
                                     'name'     => $row['name'],
                                     'paymentorder'     => $row['paymentorder'],
                                     'paymentidnumber'     => $row['paymentidnumber'],
                                     'bankname'     => $row['bankname'],
                                     'paymentdate'     => $row['paymentdate'],
                                     'paymenttime'     => $row['paymenttime']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryCursosRetrasada2($pStudent, $pAnio, $pPeriodo,$periodoActual,$anioSis,$carrera,$diasretra)
        {
            $vecCourseList = NULL;
            
            if ($this->objConnection->prepared("SELECTR2", "select r_idcourse, r_name FROM f_get_AssignationRetrasada2($1, $2, $3, $4, $5, $6, $7);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECTR2", array($pStudent, $pAnio, $pPeriodo,$periodoActual,$anioSis,$carrera,$diasretra)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idcourse'     => $row['r_idcourse'],
                                      'name'   => $row['r_name']
                                      );
                        
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryBuscarOrdenR2($pStudent, $pAnio, $pPeriodo){
            $vecCourseList = NULL;
            
            if ($this->objConnection->prepared("SELECT_ORD_R2", "SELECT * FROM tbcoursepayment
                                                                WHERE idstudent=$1
                                                                AND year=$2
                                                                AND idschoolyear=$3")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_ORD_R2", array($pStudent, $pAnio, $pPeriodo)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('paymentorder'     => $row['paymentorder'],
                                     'paymentidnumber'     => $row['paymentidnumber'],
                                     'bankname'     => $row['bankname'],
                                     'paymentdate'     => $row['paymentdate'],
                                     'paymenttime'     => $row['paymenttime']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryBuscarDetOrdenR2($paymentorder){
            $vecCourseList = NULL;
            
            if ($this->objConnection->prepared("SELECT_DET_R2", "SELECT paymentorder, price, idcourse 
                                                                FROM tbcoursepaymentdetail
                                                                WHERE paymentorder = $1;"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_DET_R2", array($paymentorder)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('paymentorder'     => $row['paymentorder'],
                                    'price'     => $row['price'],
                                    'idcourse'     => $row['idcourse']
                                      );
                        //echo '<b>Curso encontrado: '.$row['idcourse'].'</b>';
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            return $vecCourseList;
        }
        public function queryOrdenRetrasada2($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $checksum, $rubro)
        {
            $vecCourseList = false;
            
            if ($this->objConnection->prepared("INSERT_ORDEN_R2", "INSERT INTO tbcoursepayment(
                                                                                paymentorder,
                                                                                paymentorderdate,
                                                                                paymentordertime,
                                                                                amount,
                                                                                verca,
                                                                                verws,
                                                                                idpaymenttype,
                                                                                idcareer,
                                                                                idstudent,
                                                                                verifier,
                                                                                requesttype,
                                                                                year,
                                                                                idschoolyear,
                                                                                checksum,
                                                                                rubro
                                                                                )
                                                                        VALUES ($1,
                                                                                now(),
                                                                                now(),
                                                                                $2,
                                                                                $3,
                                                                                $4,
                                                                                $5,
                                                                                $6,
                                                                                $7,
                                                                                $8,
                                                                                $9,
                                                                                $10,
                                                                                $11,
                                                                                $12,
                                                                                $13
                                                                                );
                    ")
                ) 
            {
                $result = $this->objConnection->ejecuteStatement("INSERT_ORDEN_R2", array($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $checksum, $rubro));
                if ($result) 
                {
                    $vecCourseList=true;
                    
                }
            }
            return $vecCourseList;
        }
                
        public function queryDetalleRetrasada2($paymentorder, $price, $inscriptionprice, $idcourse, $lab)
        {
            $vecCourseList = false;
            
            if ($this->objConnection->prepared("INSERT_DETALLE_R2", "INSERT INTO tbcoursepaymentdetail(
                                                                            paymentorder,
                                                                            price,
                                                                            inscriptionprice,
                                                                            idcourse,
                                                                            lab
                                                                            )
                                                                    VALUES ($1,
                                                                            $2,
                                                                            $3,
                                                                            $4,
                                                                            $5
                                                                            );
                    ")
                )
            {
                $result = $this->objConnection->ejecuteStatement("INSERT_DETALLE_R2", array($paymentorder, $price, $inscriptionprice, $idcourse, $lab));
                if ($result) 
                {
                    $vecCourseList=true;
                }
            }
            return $vecCourseList;
        }
        public function queryMoveOrdenR2($ppaymentorder)
        {
            $vecCourseList = NULL;
            
            //*****************************************************************************
            if ($this->objConnection->prepared("SELECT_MOV_R2", "SELECT f_move_orden($1);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_MOV_R2", array($ppaymentorder)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'     => $row['f_move_orden']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        
        //************************************ FUNCIONES MODULO DE VACACIONES ***********************************************
        public function queryActivationVacaciones()
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECTACTIVEV", "
                                                                SELECT DISTINCT schoolyear as P1, year as P2, state as P3
                                                                FROM tbproc_processactivation
                                                                WHERE state = 1
                                                                AND schoolyear in (101,201)
                                                                AND NOW() BETWEEN startdate and enddate
                                                            ")
            ) {                
                $result = $this->objConnection->ejecuteQuery("
                                                                SELECT DISTINCT schoolyear as P1, year as P2, state as P3
                                                                FROM tbproc_processactivation
                                                                WHERE state = 1
                                                                AND schoolyear in (101,201)
                                                                AND NOW() BETWEEN startdate and enddate
                                                            "); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('schoolyear'     => $row['p1'],
                                      'anio'   => $row['p2'],
                                      'estado'   => $row['p3']
                                      );
                      //  print_r($rRow);die;
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            return $vecCourseList;
        }
        
        public function queryCursosVacaciones($pstudent, $pcarrera, $pperiodosis,$aniosis, $periodoEnviar, $anioEnviar)
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECTV", "select r_idcourse, r_name, r_price, r_inicio, r_fin, r_lun, r_mar, r_mie, r_jue, r_vie, r_sab, r_dom from f_get_assignationvacaciones($1, $2, $3, $4, $5, $6);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECTV", array($pstudent, $pcarrera, $pperiodosis,$aniosis, $periodoEnviar, $anioEnviar)); //Ejecucion y paso de parametros a la consulta
                //echo 'PARAMETROS VACACIONES:'." ".$pstudent." ".$pcarrera." ".$pperiodosis." ".$aniosis;
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('idcourse'     => $row['r_idcourse'],
                                        'name'   => $row['r_name'],
                                        'inicio'   => $row['r_inicio'],
                                        'fin'   => $row['r_fin'],
                                        'lun'   => $row['r_lun'],
                                        'mar'   => $row['r_mar'],
                                        'mie'   => $row['r_mie'],
                                        'jue'   => $row['r_jue'],
                                        'vie'   => $row['r_vie'],
                                        'sab'   => $row['r_sab'],
                                        'dom'   => $row['r_dom'],
                                        'price'   => $row['r_price']
                                      );
                        
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            
            //*****************************************************************************
            
            return $vecCourseList;
        }
        
        public function queryOrdenVacaciones($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $checksum, $rubro)
        {
            $vecCourseList = false;
            
            if ($this->objConnection->prepared("INSERT_ORDEN_V", "INSERT INTO tbcoursepayment(
                                                                                paymentorder,
                                                                                paymentorderdate,
                                                                                paymentordertime,
                                                                                amount,
                                                                                verca,
                                                                                verws,
                                                                                idpaymenttype,
                                                                                idcareer,
                                                                                idstudent,
                                                                                verifier,
                                                                                requesttype,
                                                                                year,
                                                                                idschoolyear,
                                                                                checksum,
                                                                                rubro
                                                                                )
                                                                        VALUES ($1,
                                                                                now(),
                                                                                now(),
                                                                                $2,
                                                                                $3,
                                                                                $4,
                                                                                $5,
                                                                                $6,
                                                                                $7,
                                                                                $8,
                                                                                $9,
                                                                                $10,
                                                                                $11,
                                                                                $12,
                                                                                $13
                                                                                );
                    ")
                ) 
            {
                $result = $this->objConnection->ejecuteStatement("INSERT_ORDEN_V", array($paymentorder, $amount, $verca,$verws, $idpayment, $idcareer, $idstudent, $verifier, $requesttype, $year, $idschoolyear, $checksum, $rubro));
                if ($result) 
                {
                    $vecCourseList=true;
                    
                }
            }
            return $vecCourseList;
        }
        
        public function queryDetalleVacaciones($paymentorder, $price, $inscriptionprice, $idcourse, $lab)
        {
            $vecCourseList = false;
            
            if ($this->objConnection->prepared("INSERT_DETALLE_V", "INSERT INTO tbcoursepaymentdetail(
                                                                            paymentorder,
                                                                            price,
                                                                            inscriptionprice,
                                                                            idcourse,
                                                                            lab
                                                                            )
                                                                    VALUES ($1,
                                                                            $2,
                                                                            $3,
                                                                            $4,
                                                                            $5
                                                                            );
                    ")
                )
            {
                $result = $this->objConnection->ejecuteStatement("INSERT_DETALLE_V", array($paymentorder, $price, $inscriptionprice, $idcourse, $lab));
                if ($result) 
                {
                    $vecCourseList=true;
                }
            }
            return $vecCourseList;
        }
        public function queryBuscarOrdenV($pStudent, $pAnio, $pPeriodo)
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_ORD_V", "SELECT * FROM tbcoursepayment
                                                                WHERE idstudent=$1
                                                                AND year=$2
                                                                AND idschoolyear=$3")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_ORD_V", array($pStudent, $pAnio, $pPeriodo)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('paymentorder'     => $row['paymentorder'],
                                     'paymentidnumber'     => $row['paymentidnumber'],
                                     'bankname'     => $row['bankname'],
                                     'paymentdate'     => $row['paymentdate'],
                                     'paymenttime'     => $row['paymenttime']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            return $vecCourseList;
        }
        public function queryBuscarDetOrdenV($paymentorder){
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_DET_V", "SELECT paymentorder, price, idcourse 
                                                                FROM tbcoursepaymentdetail
                                                                WHERE paymentorder = $1;"
                                              )
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_DET_V", array($paymentorder)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('paymentorder'     => $row['paymentorder'],
                                    'price'     => $row['price'],
                                    'idcourse'     => $row['idcourse']
                                      );
                        //echo '<b>Curso encontrado: '.$row['idcourse'].'</b>';
                        $vecCourseList[] = $rRow;
                    }
                }
            }
            return $vecCourseList;
        }
        
        public function queryMoveOrdenV($ppaymentorder)
        {
            $vecCourseList = NULL;
            if ($this->objConnection->prepared("SELECT_MOV_V", "SELECT f_move_orden($1);")
            ) {                
                $result = $this->objConnection->ejecuteStatement("SELECT_MOV_V", array($ppaymentorder)); //Ejecucion y paso de parametros a la consulta
                if ($result) {
                    while ($row = $this->objConnection->getResult($result)) {
                        $rRow = array('result'     => $row['f_move_orden']
                                      );
                        $vecCourseList[] = $rRow;
                    }
                }
            }            
            return $vecCourseList;
        }
        

        //********************************************************************
        
    }

?>