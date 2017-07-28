<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 07/05/2015
 * Time: 08:50 AM
 */
include("../../path.inc.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/OG_PaymentOrderGenerationWS.php");
require_once("$dir_biblio/biblio/librerias_externas/class.PrintAnything.inc.php");
require_once("$dir_biblio/fpdf/fpdf.php");

session_start();
header("Cache-control: private");

$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

$dato = new OG_PaymentOrderGenerationWS();
$_SESSION["pa"] = new PrintAnything();

$dato->obtieneInfoOrdenPago($_SESSION['OrdenPago']); //Obteniendo datos para la consulta

if ( $dato->datosOrden != FALSE) //si se obtiene resultado de la consulta realizada
{
    $periodo="";
    switch ($_SESSION["datosGenerales"]->periodo)
    { case PRIMERA_RETRASADA_DEL_PRIMER_SEMESTRE: $periodo = ' PRIMERA RETRASADA, PRIMER SEMESTRE ';break;
        case SEGUNDA_RETRASADA_DEL_PRIMER_SEMESTRE: $periodo = ' SEGUNDA RETRASADA, PRIMER SEMESTRE ';break;
        case VACACIONES_DEL_PRIMER_SEMESTRE: $periodo = ' ESCUELA DE VACACIONES, PRIMER SEMESTRE ';break;
        case VACACIONES_DEL_SEGUNDO_SEMESTRE: $periodo = ' ESCUELA DE VACACIONES, SEGUNDA SEMESTRE ';break;
        case PRIMERA_RETRASADA_DEL_SEGUNDO_SEMESTRE: $periodo = ' PRIMERA RETRASADA, SEGUNDO SEMESTRE ';break;
        case SEGUNDA_RETRASADA_DEL_SEGUNDO_SEMESTRE: $periodo = ' SEGUNDA RETRASADA, SEGUNDO SEMESTRE ';break;
    }
    $periodo .= $_SESSION["datosGenerales"]->anio;   
    
    ob_start();
    $pdf = new FPDF('P','mm','Letter');
    $pdf->AddPage();
    $pdf->SetMargins(10, 40,10);
    $pdf->SetAutoPageBreak(true,25); 

    // -------------------------- BLOQUE 1 -----------------------------
    $pdf->SetFont('Arial','B',10);
    $pdf->SetXY(42,20);
    $pdf->Cell(30,10,utf8_decode('ORDEN DE PAGO'),0,0,'C');
    $pdf->SetXY(10,($pdf->GetY()+7));
    $pdf->SetFont('Arial','',8);

    $pdf->Cell(30,10,utf8_decode('No.'),0,0,'R');
    $pdf->SetX(42);
    $pdf->Cell(40,10,utf8_decode($dato->datosOrden['ordenpago']),0,0,'L');
    $pdf->SetXY(10,($pdf->GetY()+4));

    $pdf->Cell(30,10,utf8_decode('Carné'),0,0,'R');
    $pdf->SetX(42);
    $pdf->Cell(40,10,utf8_decode($dato->datosOrden['carne']),0,0,'L');
    $pdf->SetXY(10,($pdf->GetY()+4));

    $pdf->Cell(30,10,utf8_decode('Nombre'),0,0,'R');
    $pdf->SetXY(42,($pdf->GetY()+3));
    //$pdf->MultiCell(60,4,utf8_decode('ADELFA ALEJANDRA LOPEZ ARGUETA ARGUETA ADELFA ALEJANDRA LOPEZ ARGUETA ARGUETA'),0,'L',FALSE);
    $pdf->MultiCell(60,4,utf8_decode($dato->datosOrden['nombreest']),0,'L',FALSE);
    $pdf->SetXY(10,($pdf->GetY()-3));

    $pdf->Cell(30,10,utf8_decode('Facultad'),0,0,'R');
    $pdf->SetX(42);
    $pdf->Cell(40,10,utf8_decode('Medicina Veterinaria y Zootecnia'),0,0,'L');
    $pdf->SetXY(10,($pdf->GetY()+4));

    $pdf->Cell(30,10,utf8_decode('Extensión'),0,0,'R');
    $pdf->SetX(42);
    $pdf->Cell(40,10,utf8_decode('Plan Diario'),0,0,'L');
    $pdf->SetXY(10,($pdf->GetY()+4));

    $pdf->Cell(30,10,utf8_decode('Carrera'),0,0,'R');
    $pdf->SetXY(42, ($pdf->GetY()+3));
    $pdf->MultiCell(60,4,utf8_decode($dato->datosOrden['nombrecar']),0,'L',FALSE);
    $pdf->SetXY(10,($pdf->GetY()));

    $pdf->SetX(30);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50,10,utf8_decode('DETALLE DE PAGO DE'),0,0,'R');
    $pdf->SetXY(10,($pdf->GetY()+4));

    $pdf->SetFont('Arial','',11);
    $pdf->SetXY(15,($pdf->GetY()+4));
    $pdf->MultiCell(90,4,utf8_decode($periodo),0,'L',FALSE);
    $pdf->SetFont('Arial','',8);
    $pdf->SetXY(10,($pdf->GetY()-2));
    
    $vecCursos=$_SESSION["NombreCursos2"];
    if($vecCursos){
        foreach ($vecCursos as $curso){            
            $pdf->Cell(30,10,utf8_decode($curso['idcourse']),0,0,'R');
            $pdf->SetXY(42,($pdf->GetY()+3));
            $pdf->MultiCell(60,4,utf8_decode($curso['name'].' - Q.'.$curso['price']),0,'L',FALSE);
            $pdf->SetXY(10,($pdf->GetY()+1));
        }
    }

    $pdf->SetX(42);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50,10,utf8_decode('Total a pagar'),0,0,'L');
    $pdf->SetX(80);
    $pdf->Cell(30,10,utf8_decode($dato->datosOrden['monto']),0,0,'L');

    $pdf->Line(10, 20, 200, 20);
    $pdf->Line(10, ($pdf->GetY()+10), 200, ($pdf->GetY()+10));

    $pdf->Line(10, 20, 10, ($pdf->GetY()+10));
    $pdf->Line(200, 20, 200, ($pdf->GetY()+10));

    $pdf->Line(110, 20, 110, ($pdf->GetY()+10));

    // -------------------------- BLOQUE 2 -----------------------------
    $pdf->SetXY(130,20);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,utf8_decode('PARA USO EXCLUSIVO DEL BANCO'),0,0,'C');

    $pdf->SetXY(120,($pdf->GetY()+7));
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(30,10,utf8_decode('Orden de Pago'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode($dato->datosOrden['ordenpago']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Carné'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode($dato->datosOrden['carne']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Total a pagar'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode($dato->datosOrden['monto']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Código Unidad'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode($dato->datosOrden['unidad']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Código Extensión'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode('0'.$dato->datosOrden['extension']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Código Carrera'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode('0'.$dato->datosOrden['carrera']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Rubro Pago'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode($dato->datosOrden['rubro']),0,0,'L');

    $pdf->SetXY(120,($pdf->GetY()+4));
    $pdf->Cell(30,10,utf8_decode('Llave'),0,0,'R');
    $pdf->SetX($pdf->GetX()+2);
    $pdf->Cell(20,10,utf8_decode($dato->datosOrden['checksum']),0,0,'L');

    $pdf->SetXY(115,($pdf->GetY()+8));
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(75,4,utf8_decode('Puede digirse a efectuar su pago a cualquier agencia o por medio de banca virtual de BANRURAL (ATX-253) o gytContinental'),0,'C',FALSE);

    header('Content-type: application/pdf');
    $pdf->Output('Boleta'.date("Ymd").'.pdf',"I");
    ob_end_flush();
}

//$_SESSION['contenidoImpresion'] = $_SESSION["pa"]->addPrintContext($tpl->getOutputContent());

unset($dato);

?>