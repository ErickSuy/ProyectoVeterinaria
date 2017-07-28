<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 19/05/2015
 * Time: 07:23 AM
 */

include("../../../path.inc.php");
require_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/libraries/fpdf/fpdf.php");

class PDF extends FPDF
{
	public $Y = 0;
    public $nameLength = 0;
	
    // Cabecera de página
    function Header()
    {
        $this->Ln(10);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 0, '', 0, 0, 'L');

        $this->SetX(50);
        $this->Cell(0, 0, utf8_decode($GLOBALS['lblNombreCurso']), 0, 0, 'L');
        $this->Ln(6);
        $this->SetX(30);
        $this->Cell(0, 0, utf8_decode('        '.strtoupper($GLOBALS['lblCurso'])), 0, 0, 'L');
        $this->SetX(100);
        $this->Cell(0, 0, utf8_decode('ÚNICA'), 0, 0, 'L');
        $this->SetX(140);
        $this->Cell(0, 0, utf8_decode($GLOBALS['lblCiclo']), 0, 0, 'L');
        $this->Ln(6);
        $this->SetX(30);
        //$this->Cell(0, 0, utf8_decode(strtoupper('OTRAS UNIDADES ACADÉMICAS'). '  -  ' .utf8_decode($GLOBALS['lblPeriodo'])), 0, 0, 'L');
        $this->Cell(0, 0, utf8_decode('        '.strtoupper($GLOBALS['lblCarrera']). '  -  ' .utf8_decode($GLOBALS['lblPeriodo'])), 0, 0, 'L');
        //$this->Cell(0, 0, utf8_decode(strtoupper($GLOBALS['lblCarrera'])), 0, 0, 'L');
        $this->Ln(6);
        $this->SetX(80);
        $this->Cell(0, 0, utf8_decode($GLOBALS['lblDia']), 0, 0, 'L');
        $this->SetX(125);
        $this->Cell(0, 0, utf8_decode(strtoupper($GLOBALS['lblMes'])), 0, 0, 'L');
        $this->SetX(170);
        $this->Cell(0, 0, utf8_decode(strtoupper($GLOBALS['lblAnio'])), 0, 0, 'L');

        $this->Ln(25);

    }

    // Pie de página
    function Footer()
    {
        $this->SetFont('Times','I',9);
        switch(sizeof($GLOBALS['vCatedraticos'])) {
            case 6:
                $this->Ln(25);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('HUGO RENÉ PÉREZ NORIEGA'));

                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][0]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][1]));

                $this->Ln(3);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('Secretario Académico'));
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));

                //Segunda línea de docentes
                $this->Ln(25);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][2]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][3]));

                $this->Ln(3);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));

                //Tercera línea de docentes
                $this->Ln(25);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][4]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][5]));

                $this->Ln(3);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));
                break;
            case 5:

                $this->Ln(17);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('HUGO RENÉ PÉREZ NORIEGA'));

                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][0]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][1]));

                $this->Ln(3);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('Secretario Académico'));
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));

                //Segunda línea de docentes
                $this->Ln(20);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][2]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][3]));

                $this->Ln(3);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));

                //Tercera línea de docentes
                $this->Ln(20);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][4]));

                $this->Ln(3);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));

                break;
            case 4:
                $this->Ln(17);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('HUGO RENÉ PÉREZ NORIEGA'));

                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][0]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][1]));

                $this->Ln(3);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('Secretario Académico'));
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));

                //Segunda línea de docentes
                $this->Ln(20);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][2]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][3]));

                $this->Ln(3);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));
                break;

            case 3:
                $this->Ln(25);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('HUGO RENÉ PÉREZ NORIEGA'));

                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][0]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][1]));

                $this->Ln(3);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('Secretario Académico'));
                $this->SetX(70);
                if($GLOBALS['periodo']==104 OR $GLOBALS['periodo']==204) {
                    $this->Cell(0, 0, utf8_decode('Director'));
                } else {
                    $this->Cell(0, 0, utf8_decode('Docente'));
                }

                $this->SetX(145);

                if($GLOBALS['periodo']==104 OR $GLOBALS['periodo']==204) {
                    $this->Cell(0, 0, utf8_decode('Coordinador'));
                } else {
                    $this->Cell(0, 0, utf8_decode('Docente'));
                }

                $this->Ln(25);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][2]));
                $this->Ln(3);
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));

                break;
            case 2:
                $this->Ln(25);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('HUGO RENÉ PÉREZ NORIEGA'));

                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][0]));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][1]));

                $this->Ln(3);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('Secretario Académico'));
                $this->SetX(70);
                $this->Cell(0, 0, utf8_decode('Docente'));
                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));
                break;

            case 1:
                $this->Ln(25);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('HUGO RENÉ PÉREZ NORIEGA'));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode($GLOBALS['vCatedraticos'][0]));

                $this->Ln(3);
                $this->SetX(10);
                $this->Cell(0, 0, utf8_decode('Secretario Académico'));

                $this->SetX(145);
                $this->Cell(0, 0, utf8_decode('Docente'));
                break;
        }

    }
}
session_start();

$notasFinales = unserialize($_SESSION['notasFinales']);

global $lblCurso, $lblCarrera, $lblPeriodo, $lblAnio, $total, $totalCat, $vCatedraticos,$lblMes,$lblDia;

$lblCurso = $_GET['txtCurso'];
$lblNombreCurso = $_GET['txtCursoNombre'];
$lblCarrera = $_GET['txtCarrera'];
$lblPeriodo = $_GET['txtPeriodo'];
$lblAnio = $_GET['txtAnio'];
$lblCiclo = $_GET['txtCiclo'];
$periodo = $_GET['periodo'];

$vCatedraticos = explode(",",$_GET['txtDocente']);

$time = strtotime($_GET['txtFecha']);
//$time = strtotime('2016-07-06');
$lblMes = date("m", $time);
$lblAnio = date("Y", $time);
$lblDia = date("d", $time);

switch ($lblMes) {
    case '01' :
        $lblMes = "Enero";
        break;
    case '02' :
        $lblMes = "Febrero";
        break;
    case '03' :
        $lblMes = "Marzo";
        break;
    case '04' :
        $lblMes = "Abril";
        break;
    case '05' :
        $lblMes = "Mayo";
        break;
    case '06' :
        $lblMes = "Junio";
        break;
    case '07' :
        $lblMes = "Julio";
        break;
    case '08' :
        $lblMes = "Agosto";
        break;
    case '09' :
        $lblMes = "Septiembre";
        break;
    case '10' :
        $lblMes = "Octubre";
        break;
    case '11' :
        $lblMes = "Noviembre";
        break;
    case '12' :
        $lblMes = "Diciembre";
        break;
    default   :
        $lblMes = "Mes";
}

$lblTotal = count($notasFinales);

$pdf = new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(20, 10, 20);
$margin = 0;
switch(sizeof($vCatedraticos)) {
    case 6:
        $pdf->SetAutoPageBreak(1,95); $pdf->Y=183;
    case 5:
        $pdf->SetAutoPageBreak(1,95); $pdf->Y=183;
        break;
    case 4:
        $pdf->SetAutoPageBreak(1,80); $pdf->Y=198;
    case 3:
        $pdf->SetAutoPageBreak(1,80); $pdf->Y=198;
        break;
    case 2:
        $pdf->SetAutoPageBreak(1,60); $pdf->Y=218;
    case 1:
        $pdf->SetAutoPageBreak(1,60); $pdf->Y=218;
        break;
}

$Y = 0;
$Ynextline = 0;
$breakName = false;

switch((int)$periodo) {
    case PRIMER_SEMESTRE:
    case SEGUNDO_SEMESTRE:
    
	for ($i = 1; $i<=$lblTotal; $i++) {
        $pdf->SetX(10);
        $pdf->SetFont('Times','I',10);
        $pdf->Cell(0,0,($i).'.',0,1);
        $pdf->SetX(20);
        $pdf->SetFont('Times','',9);
        $pdf->Cell(0,0,trim($notasFinales[$i][carnet]));
        $pdf->SetX(42);
        $pdf->Cell(0,0, utf8_decode($notasFinales[$i][nombre]));
        $pdf->SetFont('Times', '', 10);
        $pdf->SetX(148);
        $pdf->Cell(0,0, trim($notasFinales[$i][zona]));
        $pdf->SetX(167);
        $pdf->Cell(0,0, trim($notasFinales[$i][examen]));
        $pdf->SetX(187);
        $pdf->Cell(0,0, trim($notasFinales[$i][nota]));
        $pdf->Ln(5);
    }
        break;
	case VACACIONES_DEL_PRIMER_SEMESTRE:
    case VACACIONES_DEL_SEGUNDO_SEMESTRE:	
    case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE:
    case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE:
    
        $fila = 0;
        $pdf->nameLength = 47;
    for ($i = 1; ($i <= $lblTotal); $i++) {
        if((int)$notasFinales[$i][examen]>0) {
			// AGREGADO 2017-04-04
            $breakName = false;
            $longitud = 0;
            $longitud = strlen($notasFinales[$i][nombre]);
            // Verifica si debe hacer salto de pagina antes de imprimir una
            // linea, si es que esta dividira el nombre en dos lineas
            if($longitud > $pdf->nameLength){
                if($pdf->GetY() >= $pdf->Y)
                    $pdf->AddPage();
            }
            $fila++;
            $pdf->SetX(10);
            $pdf->SetFont('Times','I',10);
            $pdf->Cell(0,0,($fila).'.',0,1);
            $pdf->SetX(20);
            $pdf->SetFont('Times','',10);
            $pdf->Cell(0,0,trim($notasFinales[$i][derecho]));
            $pdf->SetX(42);
            $pdf->SetFont('Times','I',9);
            $pdf->Cell(0,0, trim($notasFinales[$i][carnet]));
			$pdf->SetX(64);
            
            // INICIO MODIFICACION 2017-04-04
            // ajusta el nombre en 2 lineas cuando es demasiado grande
            if($longitud > $pdf->nameLength){
                $longTemp = 0;
                $arrayName = explode(' ', $notasFinales[$i][nombre]);
                $tempName = '';
                
                foreach($arrayName as $strName){
                    if(($longTemp + strlen($strName)) < 44) {
                        $tempName .= $strName.' ';
                        $longTemp += strlen($strName);
                    } else{
                        $Y = $pdf->GetY();
                        $pdf->Cell(0,0, utf8_decode($tempName));
                        $pdf->Ln(5);
                        $Ynextline = $pdf->GetY();
                        $pdf->SetX(64);
                        
                        $tempName = $strName;
                        $longTemp = 0;
                        $longTemp = strlen($strName);
                        $breakName = true;
                    }
                }
                
                $pdf->Cell(0,0, utf8_decode($tempName));
                $pdf->SetY($Y + ($Ynextline - $Y)/2);
            } else {
                $pdf->Cell(0,0, utf8_decode($notasFinales[$i][nombre]));
            }
            // FIN MODIFICACION
            //$pdf->SetX(155);
            $pdf->SetFont('Times','I',10);
            $pdf->SetX(148);
            $pdf->Cell(0,0, trim($notasFinales[$i][zona]));
            //$pdf->SetX(175);
            $pdf->SetX(167);
            $pdf->Cell(0,0, trim($notasFinales[$i][examen]));
            //$pdf->SetX(195);
            $pdf->SetX(187);
            $pdf->Cell(0,0, trim($notasFinales[$i][nota]));
			
			if($breakName){
                $pdf->SetY($Y);
                $pdf->Ln(5);
                $pdf->Ln(5);
            } else {
                $pdf->Ln(5);
            }
        }

    }
        break;
    case SUFICIENCIAS_DEL_PRIMER_SEMESTRE:
    case SUFICIENCIAS_DEL_SEGUNDO_SEMESTRE:
    $fila = 0;
    for ($i = 1; ($i <= $lblTotal); $i++) {
        $fila++;
        $pdf->SetX(10);
        $pdf->SetFont('Times','I',10);
        $pdf->Cell(0,0,($fila).'.',0,1);
        $pdf->SetX(20);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(0,0,trim($notasFinales[$i][derecho]));
        $pdf->SetX(40);
        $pdf->SetFont('Times','I',9);
        $pdf->Cell(0,0, utf8_decode(trim($notasFinales[$i][carnet]).'   '.$notasFinales[$i][nombre] ));
        //$pdf->SetX(155);
        $pdf->SetFont('Times','I',10);
        $pdf->SetX(145);
        $pdf->Cell(0,0, trim(''));
        //$pdf->SetX(175);
        $pdf->SetX(165);
        $pdf->Cell(0,0, trim(''));
        //$pdf->SetX(195);
        $pdf->SetX(185);
        $pdf->Cell(0,0, trim($notasFinales[$i][nota]));
        $pdf->Ln(5);
    }
}



$pdf->Output();

?>
