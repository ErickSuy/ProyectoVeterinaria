<?php
/**
 * Created by PhpStorm.
 * User: yajon_000
 * Date: 22/04/2015
 * Time: 8:13 AM
 */

include("../../../path.inc.php");
include_once("$dir_portal/libraries/fpdf/fpdf.php");

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 0, 'UNIVERSIDAD DE SAN CARLOS DE GUATEMALA', 0, 0, 'L');
        $this->Ln(6);
        $this->Cell(0, 0, 'FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA', 0, 0, 'L');
        $this->Ln(6);
        $this->Cell(0, 0, utf8_decode('DEPARTAMENTO DE CONTROL ACADÉMICO'), 0, 0, 'L');
        $this->Ln(15);

        $this->Image('/var/www/libraries/fpdf/logo-fmvz.png',170,15,30,30);

        //Datos
        $this->SetFont('Times', 'B', 16);
        $this->Cell(0, 0, 'CUADRO DE ZONAS FINAL', 0, 0, 'C');
        $this->Ln(15);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 5, 'Del curso:' );
        $this->SetFont('Arial','',9);
        $this->Cell(110,5,utf8_decode($GLOBALS["lblCurso"]),'B');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(25, 5, 'de la carrera:' );
        $this->SetFont('Arial','',9);
        $this->Cell(0,5,utf8_decode($GLOBALS["lblCarrera"]),'B');

        $this->Ln(6);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(36, 5, 'Correspondientes a:' );
        $this->SetFont('Arial','',9);
        $this->Cell(94,5,utf8_decode($GLOBALS["lblPeriodo"]),'B');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(15, 5, utf8_decode('del año:') );
        $this->SetFont('Arial','',9);
        $this->Cell(0,5,utf8_decode($GLOBALS["lblAnio"]),'B');

        $this->Rect(10,75,196,7,"D");

        $this->Ln(8);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 5, 'No.' );
        $this->Cell(30,5,'CARNET');
        $this->Cell(120,5,'NOMBRE');
        $this->Cell(15,5,'ZONA');
        $this->Cell(15,5,'OBS');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetFont('Times','I',9);
        $this->SetY(-15);
        $this->Cell(0, 0, 'Total de asignados:');
        $this->SetX(47);
        $this->Cell(10, 0, $GLOBALS['lblTotal']);
        $this->SetX(60);
        $this->Cell(35, 0, utf8_decode('Fecha de generación:'));
        $this->SetX(90);
        $this->Cell(30,0, utf8_decode(Date('d/m/Y').' a las ' . Date("H:i")),0,2,'R');
        $this->SetY(-15);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
    }
}
session_start();

global $lblCurso, $lblCarrera, $lblPeriodo, $lblAnio, $total;

$lblCurso = $_GET['txtCurso'] . ' - ' . $_GET['txtCursoNombre'];
$lblCarrera = $_GET['txtCarrera'];
$lblPeriodo = $_GET['txtPeriodo'];
$lblAnio = $_GET['txtAnio'];

$pdf = new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(20, 10, 20);

$zonasTotales = $_SESSION['zonasActividades'];
$lblTotal = sizeof($zonasTotales);

for($i=0;$i<sizeof($zonasTotales);$i++){
    $pdf->SetX(10);
    $row = $zonasTotales[$i];
    $pdf->SetFont('Times','I',10);
    $pdf->Cell(0,0,($i+1).'.',0,1); $pdf->SetX(20);
    $pdf->SetFont('Times','',10);
    $pdf->Cell(0,0,trim($row['carnet'])); $pdf->SetX(50);
    $pdf->Cell(0,0, utf8_decode(trim($row['nombre']))); $pdf->SetX(170);
    $pdf->Cell(0,0, trim($row['zona'])); $pdf->SetX(185);
    $pdf->Cell(0,2,"");
    $pdf->Ln(7);

}

$pdf->Output();

?>
