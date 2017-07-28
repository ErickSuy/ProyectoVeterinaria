<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Autor: Angel Hernandez
 * Fecha: 8/11/2016
 * 
 */
 

include("../../path.inc.php");

include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/libraries/fpdf/fpdf.php");


include_once("$dir_portal/fw/controller/ControlUser.php");
include_once("$dir_portal/fw/controller/ControlCourse.php");

class PDF extends FPDF
{
// Cabecera de página
private $iduser;
private $cuiuser;
private $username;
private $usercareer;
private $userpensum;
public  $ln=4;
public  $X=0;
public  $Y=0;


public function setiduser($data){
    $this->iduser = $data;   
}

public function &getiduser(){
    return $this->iduser;
}

public function setcuiuser($data){
    $this->cuiuser = $data;
} 

public function &getcuiuser(){
    return $this->cuiuser;
}

public function setusername($data){
    $this->username = $data;
} 

public function &getusername(){
    return $this->username;
}

public function setusercareer($data){
    $this->usercareer = $data;
} 

public function &getusercareer(){
    return $this->usercareer;
}

public function setuserpensum($data){
    $this->userpensum = $data;
} 

public function &getuserpensum(){
    return $this->userpensum;
}

}

// Creación del objeto de la clase heredada
session_start();


$user  = $_POST['ff_cui'];
$career = $_POST['career'];

// obtenemos el password del usuario

$objcontroller = new ControlUser('testfmvz@usac.edu.gt', NULL, $user, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $career, NULL, NULL, 3, 1, session_id());
$result = $objcontroller->getUserData();
$_SESSION['usuarioCOAC'] = $_SESSION['usuario'];
$_SESSION['usuario'] = serialize($objcontroller->getUser());
$objuser = unserialize($_SESSION['usuario']);


$fname =$objuser->getName();

$username = $objuser->getSurName().' '.$fname;
$pdf = new PDF();
$pdf->setcuiuser($objuser->getDpi());
$pdf->setiduser($objuser->getIdUser());
$pdf->setusername($username);
$pdf->setusercareer($objuser->getCareerName());
$pdf->setuserpensum($objuser->getCurriculumName());
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
$pdf->SetAutoPageBreak(false);

$objControlCourse = new ControlCourse(NULL, NULL, NULL, NULL, NULL);
$result = $objControlCourse->getCertifcateList($objuser);

$avgStudent = 0;
$TotalCourses = 0;

$eps = false;
$epscode = 0;
$epsnote = 0;
$epsnoteLetras = '';
$epsdate = '';
$epsplace= '';
$eps_datein = '';
$eps_dateout = '';
$eps_desc   = '';
/***************** dibujamos el contorno *************************/

$pdf->Line(20,10,20,115);

$pdf->Line(20,10,185,10);

$pdf->Line(185,10,185,115);

$pdf->Line(20,115,185,115);

  $pdf->SetFont('Courier','B',8);
$Y = 15;  
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+65);
    $pdf->Cell(0,0,  utf8_decode('UNIVERSIDAD DE SAN CARLOS DE GUATEMALA'),0,1);
 
    $Y= $Y+3;
 $pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+65);
    $pdf->Cell(0,0,  utf8_decode('DEPARTAMENTO DE REGISTRO Y ESTADISTICA'),0,1);
    
$pdf->SetFont('Courier','',7);
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+35);
$pdf->Cell(0,0,  utf8_decode('Edificio de Recursos Educativos - Ciudad Universitaria zona 12 - Guatemala, Centroamérica'),0,1);
    
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+50);
$pdf->Cell(0,0,  utf8_decode('Horas de Oficina: de lunes a viernes 7:30 a 15:30 Tel. 24187902'),0,1);
    
$pdf->SetFont('Courier','I',6);
$Y= $Y+4;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+145);
$pdf->Cell(0,0,  utf8_decode('Const. Insc. P. ext. Grales'),0,1);

$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+152);
$pdf->Cell(0,0,  utf8_decode('Reg. 2f-12000-99'),0,1);

$pdf->SetFont('Courier','',8);
$Y= $Y+6;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+78);
$pdf->Cell(0,0,  utf8_decode('CICLO ACADÉMICO'),0,1);

$pdf->SetFont('Courier','B',9);
$pdf->Setx($pdf->X+105); 
$thisyear = date("Y");
$pdf->Cell(0,0,  utf8_decode($thisyear),0,1); 

$pdf->SetFont('Courier','',8);
$Y= $Y+6;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('Constancia para inscripción de estudiantes que han cerrado curricula y están pendientes de  '),0,1);
   
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('Exámenes Generales (Privado o Público, Ejercicio Profesional Supervisado, o Examen Especial).'),0,1);

$pdf->SetFont('Courier','',8);
$Y= $Y+5;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('Estudiante:'),0,1);


$pdf->SetFont('Courier','IB',8);
$pdf->Setx($pdf->X+50);
$txtdpi='';
if (strlen($objuser->getDpi())>0){
    $txtdpi = ' '.$objuser->getDpi().' - ';
}else{
    $txtdpi = ' ';
}
$pdf->Cell(0,0,  utf8_decode($txtdpi.$username.' ( '.$objuser->getIdUser().' )'),0,1);


$pdf->Line(53,$pdf->Y+$Y+2,180,$pdf->Y+$Y+2);

$pdf->SetFont('Courier','',8);
$Y= $Y+7;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('Unidad Académica:'),0,1);


$pdf->SetFont('Courier','IB',8);
$pdf->Setx($pdf->X+52);
$pdf->Cell(0,0,  utf8_decode('FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA'),0,1);


$pdf->Line(53,$pdf->Y+$Y+2,180,$pdf->Y+$Y+2);

$pdf->SetFont('Courier','',8);
$Y= $Y+7;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('Carrera:'),0,1);

$txtcareer = '';
if($career==2){
    $txtcareer = 'MEDICINA VETERINARIA';
}else if($career==3){
    $txtcareer = 'ZOOTECNIA';
}

$pdf->SetFont('Courier','IB',8);
$pdf->Setx($pdf->X+52);
$pdf->Cell(0,0,  utf8_decode($txtcareer),0,1);


$pdf->Line(53,$pdf->Y+$Y+2,180,$pdf->Y+$Y+2);

$pdf->SetFont('Courier','',8);
$Y= $Y+6;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('La Secretaría de esta Unidad Académica autoriza la inscripción del estudiante nombrado quien'),0,1);

$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+22);
$pdf->Cell(0,0,  utf8_decode('cerró curricula con fecha:'),0,1);

/****************** si tiene cierre de pensum **************/

    $cierre = $objControlCourse->getCierrePensum($objuser);
    if ($cierre!=null){
       
        $time = strtotime($cierre[0][0]['aprobacion']);
        $dia = date('d',$time);
        $mes = date('m',$time);
        $anio = date('Y',$time);
        
        switch ($mes) {
            case 1:$mes='Enero';break;
            case 2:$mes='Febrero';break;
            case 3:$mes='Marzo';break;
            case 4:$mes='Abril';break;
            case 5:$mes='Mayo';break;
            case 6:$mes='Junio';break;
            case 7:$mes='Julio';break;
            case 8:$mes='Agosto';break;
            case 9:$mes='Septiembre';break;
            case 10:$mes='Octubre';break;
            case 11:$mes='Noviembre';break;
            case 12:$mes='Diciembre';break;

            default:
                break;
        }
        $pdf->SetFont('Courier','IUB',8);
        $pdf->Setx($pdf->X+68);
        $pdf->Cell(0,0,utf8_decode($dia.' de '.$mes.' de '.$anio.'.'),0,1);
        
    }else{
        $pdf->Setx($pdf->X+68);
        $pdf->Cell(0,0,utf8_decode('*******'),0,1);
    }
    
 
    
 /****************** si tiene eps **************/   
    
    $epsdata = $objControlCourse->getEps($objuser);
    
    if($epsdata!=null){
        $epsdata = $epsdata[0]; 
        $eps = true;
        $time2 = strtotime($epsdata['aprobacion']);
        
        
        $pdf->SetFont('Courier','',8);
        $Y= $Y+3;
        $pdf->SetY($pdf->Y+$Y);
        $pdf->Setx($pdf->X+22);
        $pdf->Cell(0,0,  utf8_decode('Realizó su Examen General Privado O - Ejercicio Profesional Supervisado con fecha:'),0,1);
        
        $dia = date('d',$time2);
        $mes = date('m',$time2);
        $anio = date('Y',$time2);
        
        switch ($mes) {
            case 1:$mes='Enero';break;
            case 2:$mes='Febrero';break;
            case 3:$mes='Marzo';break;
            case 4:$mes='Abril';break;
            case 5:$mes='Mayo';break;
            case 6:$mes='Junio';break;
            case 7:$mes='Julio';break;
            case 8:$mes='Agosto';break;
            case 9:$mes='Septiembre';break;
            case 10:$mes='Octubre';break;
            case 11:$mes='Noviembre';break;
            case 12:$mes='Diciembre';break;

            default:
                break;
        }
        $pdf->SetFont('Courier','IUB',8);
        $pdf->SetY($pdf->Y+$Y);
        $pdf->Setx($pdf->X+166);
        
        $pdf->Cell(0,0,utf8_decode($dia.' de '),0,1);
        $Y= $Y+3;
        
        $pdf->SetY($pdf->Y+$Y);
        $pdf->Setx($pdf->X+22);
        $pdf->Cell(0,0,utf8_decode($mes.' de '.$anio.'.'),0,1);
        
        
    }
    
    $dia = date('d');
        $mes = date('m');
        $anio = date('Y');
        
        switch ($mes) {
            case 1:$mes='Enero';break;
            case 2:$mes='Febrero';break;
            case 3:$mes='Marzo';break;
            case 4:$mes='Abril';break;
            case 5:$mes='Mayo';break;
            case 6:$mes='Junio';break;
            case 7:$mes='Julio';break;
            case 8:$mes='Agosto';break;
            case 9:$mes='Septiembre';break;
            case 10:$mes='Octubre';break;
            case 11:$mes='Noviembre';break;
            case 12:$mes='Diciembre';break;

            default:
                break;
        }
        
     $pdf->SetFont('Courier','',8);
    $Y= $Y+6;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+120);
$pdf->Cell(0,0,  utf8_decode('Guatemala, '.$dia.' de '.$mes.' de '.$anio),0,1);
 
  $pdf->SetFont('Courier','I',8);
$Y= $Y+9;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+40);
$pdf->Cell(0,0,  utf8_decode('f.'),0,1);
    
$pdf->Line(45,$pdf->Y+$Y+1,100,$pdf->Y+$Y+1);

$pdf->SetFont('Courier','',8);
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+39);
$pdf->Cell(0,0,  utf8_decode('Vo. Bo. Dr. Hugo Réne Pérez Noriega'),0,1);

$pdf->SetFont('Courier','B',8);
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+53);
$pdf->Cell(0,0,  utf8_decode('Secretario Académico'),0,1);

 $pdf->SetFont('Courier','I',8);
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+120);
$pdf->Cell(0,0,  utf8_decode('f.'),0,1);
    
$pdf->Line(125,$pdf->Y+$Y+1,180,$pdf->Y+$Y+1);

$pdf->SetFont('Courier','',8);
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+131);
$pdf->Cell(0,0,  utf8_decode('Norma Zúñiga de Chajón'),0,1);

$pdf->SetFont('Courier','B',8);
$Y= $Y+3;
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+135);
$pdf->Cell(0,0,  utf8_decode('CONTROL ACADÉMICO'),0,1);

$Y= $Y+3;
$pdf->SetFont('Courier','',7   );
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+21);
$pdf->Cell(0,0,  utf8_decode('Inscripcion Fecha:'),0,1);

$Y= $Y+3;
$pdf->SetFont('Courier','',7   );
$pdf->SetY($pdf->Y+$Y);
$pdf->Setx($pdf->X+21);
$pdf->Cell(0,0,  utf8_decode('Encargado de Inscripción:'),0,1);


$pdf->Output();


$_SESSION['usuario']= $_SESSION['usuarioCOAC'] ;