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

    
function Header()
{
    
    
    $this->SetY(55);
    $this->SetX(176);
    $this->SetFont('Arial','B',9);
     
    $this->Cell(0,0,$this->getiduser(),0,0);
    $Y=37;
    $this->Y = $Y;
    // Logo
    //$this->Image('logo_pb.png',10,8,33);
    // Arial bold 15
    
    // Movernos a la derecha
   // $this->Cell(80);
    // Título
    $this->SetFont('Arial','B',10);
     $this->SetY($this->Y);
    $this->Cell(0,0,utf8_decode($this->getusername()),0,0,'C');
    $Y = $Y + $this->ln;
    $this->SetY($Y);
    $this->SetX(109);
    $this->Cell(0,0,$this->getcuiuser(),0,0,'');
    $Y = $Y + $this->ln;
    $this->SetY($Y);
    
     $Y = $Y + ($this->ln*2);
    $this->SetY($Y);
    $this->Cell(0,0,utf8_decode(strtoupper($this->getusercareer()).' '.'-'.' '.  strtoupper($this->getuserpensum()) ),0,0,'C');
    
    // Salto de línea
    $this->Ln(20);
    $this->Y = $Y;
    $this->SetY($Y+5);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
}

// Creación del objeto de la clase heredada
session_start();


$user  = $_POST['ff_cui'];
$career = $_POST['career'];

// obtenemos el password del usuario

$objcontroller = new ControlUser('testfmvz@usac.edu.gt', NULL, $user, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $career, NULL, NULL, 3, 1, session_id());
//echo 'aldjfa;lkdf';die;
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

if($result) {
    $result1 = $objControlCourse->getCourseListInfo($objuser);
    if($result1) {
        $avgStudent = $result1[0]['avg'];
        $TotalCourses = $result1[0]['numc'];
    }
}


/****************** si tiene eps **************/   
    
    $epsdata = $objControlCourse->getEps($objuser);
    
    if($epsdata!=null){
        $epsdata = $epsdata[0]; 
        $eps = true;
        $epscode = 743;
        $epsnote = $epsdata['nota'];
        $epsnote = intval($epsnote);
        $epsnoteLetras = $epsdata['letras'];
        $time2 = strtotime($epsdata['aprobacion']);
        $epsdate = date('m-Y',$time2);
        
        if($epsdata['fechain'] != NULL && $epsdata['fechafn'] != NULL 
        && $epsdata['lugarnom'] != NULL && $epsdata['lugardir'] !=NULL){
            $epsplace   = $epsdata['lugarnom'].', '.$epsdata['lugardir'];
            $eps_datein = date('d-m-Y',strtotime($epsdata['fechain']));   
            $eps_dateout = date('d-m-Y',strtotime($epsdata['fechafn']));       
        }ELSE{
            
        }
        
    }
 /***********************************************************/   

$catalogoAprobados = $result[0];

$ciclo = '';
$ciclotmp = '';
$Y = $pdf->GetY();
$pdf->Y= $pdf->GetY()+5;
$pdf->SetY($pdf->Y);
$X = 138;
$Xin = $pdf->GetX()+3;

$numcurso = 0;

$breakp1cursos = 0;
$breakp2cursos = 0;
$breakp1fina   = 0;
$breakp1finb   = 0;
$breakp2fina   = 0;
$breakp2finb   = 0;

switch ($objuser->getCareer()) {
    case 2:
        $breakp1cursos = 33;
        $breakp2cursos = 70;
        
        $breakp1fina   = 26;
        $breakp1finb   = 33;
        
        $breakp2fina   = 58;
        $breakp2finb   = 70;
        
        
        $cursosGanados = 74;

        break;
    case 3:
        $breakp1cursos = 36;
        $breakp2cursos = 65;
        
        $breakp1fina   = 22;
        $breakp1finb   = 36;
        
        $breakp2fina   = 61;
        $breakp2finb   = 64;
        
        $cursosGanados = 75;

        break;
    default:
        break;
} 


$TotalCourses = 0;
foreach($catalogoAprobados as $curso) {
    /*
        $tpl->newBlock('detalleaprobados');
        $tpl->assign('aNum',$curso['num']);
        $tpl->assign('aCurso',$curso['cod']);
        $tpl->assign('aNombreCurso',$curso['nom']);
        $tpl->assign('aCreditos',$curso['cred']);
        $tpl->assign('aFechaAprobacion',$curso['fechaa']);
        $tpl->assign('aNota',strcmp($curso['nota'],'EQV')==0?$curso['nota']:(int)$curso['nota']);
      */  
    $numcurso = $curso['num'];
 //   echo "num>>> ".$numcurso;//;die;
  //  if ($curso['num']<50){
  //  if ($curso['num'] > 38){
   //     $curso['num'] = $curso['num'];
  //  }
    
    
    IF ($curso['nomciclo']!=$ciclo){
        $ciclo =  $curso['nomciclo'];
        $pdf->SetY($pdf->Y);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,utf8_decode($curso['nomciclo']),0,1);
        $pdf->Y= $pdf->Y+($pdf->ln+1);
        $pdf->SetFont('Arial','',9);
    }
    
    
    if ( ($curso['cod']!==620 && $curso['cod']!==621 && $curso['cod']!==622 && $curso['cod']!==623 && $curso['cod']!==600 && $curso['cod']!==605 && $curso['cod']!==615 && $curso['cod']!==610) && ($curso['fechaa'] != '12-1969' && $curso['fechaa'] != '01-1900') ){
    //   print_r($curso);die; 
    if ($curso['cod']!=743){
        $pdf->SetX($pdf->X);
        $pdf->SetXY($Xin,$pdf->Y);
        $pdf->Cell(5,0,$curso['cod'],0,1);
        $pdf->SetX(21);
        $lennom = strlen($curso['nom']);
        if($lennom>55){
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(5,0,utf8_decode($curso['nom']),0,1);
            $pdf->SetFont('Arial','',9);
        }else{
            $pdf->Cell(5,0,utf8_decode($curso['nom']),0,1);
        }
        $pdf->SetX($X);
        
        if($curso['nota']>0){
            if ($curso['desc']=='EQUIVALENCIA' ){
                $pdf->Cell(0,0,$curso['nota'].'   '.utf8_decode('EQUIVALENCIA'),0,1);
            }elseif ($curso['desc']=='SUFICIENCIA' ){
                $pdf->Cell(0,0,$curso['nota'].'   '.utf8_decode('SUFICIENCIA'),0,1);
            }elseif ($curso['desc']=='AUT. POR J.D.' ){
                $pdf->Cell(0,0,$curso['nota'].'   '.utf8_decode('AUT. POR J.D.'),0,1);
            }
            else{
                $pdf->Cell(0,0,$curso['nota'].'   '.utf8_decode($curso['letras']),0,1);
            }
            
            
        }elseif($curso['nota']==0 && $curso['desc']=='EQUIVALENCIA'  ) {
            $pdf->Cell(0,0,'EQUIVALENCIA',0,1);
        }elseif($curso['nota']==0 && $curso['desc']=='AUT. POR J.D.'  ) {
            $pdf->Cell(0,0,'AUT. POR J.D.',0,1);
        }
        
        $X = $X + 45;
        $pdf->SetX($X);
        $pdf->Cell(0,0,$curso['fechaa'],0,1);
        $pdf->Y= $pdf->Y+($pdf->ln+1);
       // $X=0;
        $X = $X - 45;
        $pdf->SetX($X);
        $TotalCourses ++;
        
        if($curso['cod']==178 || $curso['cod']==176 || $curso['cod']==169 || $curso['cod']==144 || $curso['cod']==143 || $curso['cod']==748 || $curso['cod']==750 ){
            $cursosGanados++;
        }
        
    }elseif($curso['cod']==743){
        $eps = true;
        $epscode = $curso['cod'];
        $epsdate = $curso['fechaa'];
        $epsnote = $curso['nota'];
        $epsnoteLetras = $curso['letras'];
        $eps_desc = $curso['descripcion'];
    }
    
    }elseif( ($curso['cod']==625 || $curso['cod']==606 || $curso['cod']==634 || $curso['cod']==600 || $curso['cod']==605 || $curso['cod']==615 || $curso['cod']==610) &&( $curso['fechaa'] == '12-1969'  || $curso['fechaa'] == '01-1900') ){
        $pdf->SetY($pdf->Y);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,0,$curso['cod'].'  '.utf8_decode($curso['nom']),0,1);
        $pdf->Y= $pdf->Y+($pdf->ln+1);
        $pdf->SetFont('Arial','',9);
    }
    
    
    if($numcurso == $breakp1cursos || $numcurso == $breakp2cursos ){
        $pdf->AddPage($pdf->CurOrientation, $pdf->CurPageSize);
     //   echo "break <br>";
       $pdf->Y= $pdf->Y+($pdf->ln+5);
       $pdf->SetY($pdf->Y);
    }
    
 //   }
    
    }
    
    if($numcurso > $breakp1fina && $numcurso < $breakp1finb ){
        $pdf->AddPage($pdf->CurOrientation, $pdf->CurPageSize);
       $pdf->Y= $pdf->Y+($pdf->ln+5);
   //    echo "break2 <br>";
       $pdf->SetY($pdf->Y);
    }elseif($numcurso > $breakp2fina && $numcurso < $breakp2finb ){
        $pdf->AddPage($pdf->CurOrientation, $pdf->CurPageSize);
   //    echo "break3 <br>";
       $pdf->Y= $pdf->Y+($pdf->ln+5);
       $pdf->SetY($pdf->Y);
    }
   // die;
    //if > 26 and < 33 cambio
    //if > 55 AND < 62+6;
    $pdf->Y= $pdf->Y+($pdf->ln+5);
    $pdf->SetXY($Xin+25,$pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('El promedio General de punteos obtenidos es:   '.$avgStudent),0,1);
    $pdf->SetX($X);
    
    if($TotalCourses>$cursosGanados){
        $cursosGanados = $TotalCourses;
    }
    
    
    $pdf->Cell(0,0,  utf8_decode('Cursos Ganados:   '.$TotalCourses.'/'.$cursosGanados),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln+1);
    
    
 /****************** si tiene cierre de pensum **************/
    $cierre = $objControlCourse->getCierrePensum($objuser);
    if ($cierre!=null){
       $pdf->SetX($pdf->X);
        $pdf->Y = $pdf->Y+5;
        $pdf->SetY($pdf->Y);
        $pdf->SetFont('Arial','B',9);
        $time = strtotime($cierre[0][0]['aprobacion']);
        
        $pdf->Cell(5,0,'  '.utf8_decode('FECHA DE CIERRE DE PENSUM: '.date('m-Y',$time)),0,1);
        $pdf->SetFont('Arial','',9);
        $pdf->Y = $pdf->Y+10;
    }
    
 /****************** si tiene eps **************/   
    
    $epsdata = $objControlCourse->getEps($objuser);
    
    $epsplace1='';
    $epsplace2='';
    if($epsdata!=null){
        $epsdata = $epsdata[0]; 
        $eps = true;
        $epscode = 743;
        $epsnote = $epsdata['nota'];
        $epsnote = intval($epsnote);
        $epsnoteLetras = $epsdata['letras'];
        $time2 = strtotime($epsdata['aprobacion']);
        $epsdate = date('m-Y',$time2);
        
        if($epsdata['fechain'] != NULL && $epsdata['fechafn'] != NULL 
        && $epsdata['lugarnom'] != NULL && $epsdata['lugardir'] !=NULL){
            $epsplace     = $epsdata['lugarnom'];
            $epsplace_dir = $epsdata['lugardir'];
            $eps_datein   = date('d-m-Y',strtotime($epsdata['fechain']));   
            $eps_dateout  = date('d-m-Y',strtotime($epsdata['fechafn']));       
        }ELSE{
            $epsinfo = $epsdata['descripcion'];
            //echo $epsinfo;die;
            $epsinfo2 = explode('[', $epsinfo);
  //          print_r($epsinfo2);die;
            $epsplace = $epsinfo2[0];
            $epsdate  = $epsinfo2[1];
            $epsdate = substr($epsdate, 0,  strlen($epsdate)-1);
            
            $epsplace = explode(',',$epsplace);
            $divsplace = count($epsplace);
            $epsplace1 = $epsplace[0];
            if($divsplace>1){
                for($i = 1;$i<=$divsplace;$i++){
                    $epsplace2 .= ' '.$epsplace[$i];
                }
            }
            
         //   echo $epsplace.'<br> '.$epsdate;die;
        }
        
    }
    
    if($eps){
        $pdf->SetX($pdf->X);
        $pdf->SetY($pdf->Y);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,0,$epscode.'  '.utf8_decode('EJERCICIO PROFESIONAL SUPERVISADO'),0,1);
        
        $pdf->SetFont('Arial','',9);
        $pdf->SetX($X);
        $pdf->Cell(0,0,$epsnote.'   '.utf8_decode($epsnoteLetras),0,1);
        $X = $X + 45;
        $pdf->SetX($X);
      //  $pdf->Cell(0,0,$epsdate,0,1);
        $pdf->Y= $pdf->Y+($pdf->ln+1);
       // $X=0;
        $X = $X - 45;
        $pdf->SetX($X);
        $pdf->SetFont('Arial','',9);
         if($epsdata['fechain'] != NULL && $epsdata['fechafn'] != NULL 
        && $epsdata['lugarnom'] != NULL && $epsdata['lugardir'] !=NULL){
        $pdf->SetX($pdf->X);
        $pdf->SetXY($Xin,$pdf->Y);
        
        $countl = strlen($epsplace);
        if($countl>70){
            $epsplace_aux = explode(' ', $epsplace);
            $long=0;
            $times = 0;
            $epsplace = '';
         //   print_r($epsplace_aux);die;
            foreach($epsplace_aux as $ea){
                if($long<60){
                    $epsplace .= ' '.$ea;
                    $long += strlen($ea);
                }else{
                    
                    $pdf->Y = $pdf->Y+5;
                    $pdf->SetX($pdf->X);
                    $pdf->SetXY($Xin,$pdf->Y);
                    if($times==0){
                    $pdf->Cell(5,0,'    '.'Realizado en: '.utf8_decode($epsplace),0,1);
                    }else{
                        $pdf->Cell(5,0,'    '.utf8_decode($epsplace),0,1);
                    }
                    $times ++;
                    $epsplace = $ea;
                    $long = 0;
                    $long = strlen($ea);
                }
            }
            $pdf->Y = $pdf->Y+5;
            $pdf->X = $pdf->X+33; 
            $pdf->SetX($pdf->X);
            $pdf->SetXY($pdf->X,$pdf->Y); 
            $pdf->Cell(5,0,'    '.utf8_decode($epsplace),0,1);
            $pdf->X = $pdf->X-33;
            
        }else{
        
        $pdf->Cell(5,0,'    '.utf8_decode('Realizado en: '.$epsplace),0,1);
        }
         ; 
        $countl = strlen($epsplace_dir);
        if($countl>70){
           // $pdf->SetFont('Arial','',7);
            $epsplace_aux = explode(' ', $epsplace_dir);
            $long=0;
            $epsplace_dir = '';
         //   print_r($epsplace_aux);die;
            foreach($epsplace_aux as $ea){
                if($long<60){
                    $epsplace_dir .= ' '.$ea;
                    $long += strlen($ea);
                }else{
                
                    $pdf->Y = $pdf->Y+5;
                    $pdf->SetX($pdf->X);
                    $pdf->SetXY($Xin,$pdf->Y); 
                    $pdf->Cell(5,0,'    '.utf8_decode($epsplace_dir),0,1);
                    $epsplace_dir = $ea;
                    $long = 0;
                    $long = strlen($ea);
                }
            }
            $pdf->Y = $pdf->Y+5;
            $pdf->SetX($pdf->X);
            $pdf->SetXY($Xin,$pdf->Y); 
            $pdf->Cell(5,0,'    '.utf8_decode($epsplace_dir),0,1);
         //    echo $epsplace_dir." $long >><br>";
           // die;
          //  echo $eps
            
        }else{
            $pdf->Y = $pdf->Y+5;
         $pdf->X = $pdf->X+33; 
        $pdf->SetY($pdf->Y);
        $pdf->SetX($pdf->X);
            $pdf->Cell(5,0,'    '.utf8_decode($epsplace_dir),0,1);
        }
        $pdf->SetFont('Arial','',9);
        
         $pdf->Y = $pdf->Y+5;
        $pdf->SetX($pdf->X);
        $pdf->SetXY($Xin,$pdf->Y); 
       
        $pdf->Cell(5,0,'    '.utf8_decode('del: '.$eps_datein.' al '.$eps_dateout),0,1);
        $pdf->Y = $pdf->Y+5;
        }else{
        $pdf->SetX($pdf->X);
        $pdf->SetXY($Xin,$pdf->Y); 
        $pdf->Cell(5,0,'    '.utf8_decode('Realizado en: '.$epsplace1),0,1);
          $pdf->Y = $pdf->Y+5;
         $pdf->X = $pdf->X+33; 
        $pdf->SetY($pdf->Y);
        $pdf->SetX($pdf->X);
        $pdf->Cell(5,0,'    '.utf8_decode($epsplace2),0,1);
         $pdf->Y = $pdf->Y+5;
        $pdf->SetX($pdf->X);
        $pdf->SetXY($Xin,$pdf->Y); 
       
        $pdf->Cell(5,0,'    '.utf8_decode($epsdate),0,1);
        $pdf->Y = $pdf->Y+5;
        }
    }
   
 /****************** anotacion final ********************************/
    $pdf->SetY($pdf->Y+5);
    $pdf->Cell(0,0,  utf8_decode('Conforme el Capítulo I,  Articulo 5 del Reglamento de Evaluación  y Promoción estudiantil de esta Facultad, aprobado por  el Consejo'),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln+5);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('Superior Universitario, según Punto Décimo,  Acta No. 30-95, fecha 25/10/1995, la escala de calificaciones es de cero (0) a cien (100) '),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('puntos.   La nota mínima para aprobar un curso es de sesenta  (60)  punto.  Antes del  24  de febrero de  1996  la nota mínima era de '),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('cincuenta y un   (51)   puntos.   Conforme el Capítulo II,   Artículo   20  del Reglamento de Evaluación y Promoción estudiantil de esta'),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('Facultad, aprobado por el Consejo Superior Universitario, según Punto Séptimo Acta No.  15-2005, fecha 08/06/2005, la nota mínima '),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('para aprobar un curso es de sesenta y un  (61) puntos en una escala de cero (0) a cien  (100), aplicado a partir  del 01/11/2005. Para '),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('cursos de vacaciones Junta Directiva aprobó como nota mínima de promoción setenta (70) puntos. Y para los usos legales que al (la)'),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('estudiante; '.$username.', convengan. '),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->Cell(0,0,  utf8_decode('Se extiende la presente Certificación en '.$pdf->PageNo().' hoja(s) de papel membretado, en la ciudad de Guatemala, en la siguiente fecha '.date("d/m/Y").'.' ),0,1);
 //   $pdf->Y= $pdf->Y+($pdf->ln);
 //   $pdf->SetY($pdf->Y);
 //   $pdf->Y= $pdf->Y+($pdf->ln);
//    $pdf->SetY($pdf->Y);
//    $pdf->SetFont('Arial','',8);
//    $pdf->Cell(0,0,  utf8_decode('* Equivalencia entre Carreras'),0,1);
  //  $pdf->Y= $pdf->Y+($pdf->ln);
  //  $pdf->SetY($pdf->Y);
  //  $pdf->Cell(0,0,  utf8_decode('** Suficiencia'),0,1);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->Y= $pdf->Y+($pdf->ln);
    $pdf->SetY($pdf->Y);
    $pdf->SetFont('Arial','B',10);
    // Movernos a la derecha
   // $this->Cell(80);
    // Título
    $pdf->Cell(0,0,utf8_decode('"ID Y ENSEÑAD A TODOS"'),0,0,'C');
    
$pdf->Output();


$_SESSION['usuario']= $_SESSION['usuarioCOAC'] ;