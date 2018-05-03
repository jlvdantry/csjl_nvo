<?PHP
//echo "entro ".$wlarchivo;
putenv("TZ=America/Mexico_City");
define('FPDF_FONTPATH','font/');
require('fpdf.php');
include("conneccion.php");
function meses_espanol($n)
{
   $months = array(
     1 => 'Enero',
     2 => 'Febrero',
     3 => 'Marzo',
     4 => 'Abril',
     5 => 'Mayo',
     6 => 'Junio',
     7 => 'Julio',
     8 => 'Agosto',
     9 => 'Septiembre',
     10 => 'Octubre',
     11 => 'Noviembre',
     12 => 'Diciembre'
   );
   return $months[$n];
echo meses_espanol(date('n'));
}

class PDF extends FPDF
{
function Header()
{
}

function Footer()
{
	global $parametro1;

	$this->SetY(-15);
	$this->SetFont('Arial','I',6);
	$this->Image('img/logo3_2007.JPG',8,260,12,0);
	$this->Image('img/logo2_2007_color.JPG',197,260,12,0);
	$this->Setx(23);
	$this->Cell(20,6,'Fecha de emisiòn: ','LBT',0,'C',1);
	$this->Cell(30,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(30,6,'Hora de emisiòn: ','BT',0,'C',1);
	$this->Cell(25,6,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(25,6,'Usuario de emisión: ','BT',0,'C',1);
	$this->Cell(30,6,$parametro1.',','BT',0,'L',1);
	$this->Cell(10,6,'Pagina '.$this->PageNo().'/{nb}','BTR',0,'R',1);
}
}

$pdf = new PDF ();
$pdf->Fpdf('P','mm','Letter');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',8);
$pdf->SetLineWidth(0.2);

$pdf->Cell(195,245,$pdf->Image('upload_ficheros/'.$wlarchivo,10,10,195,245),0,1,'C',0);
//$pdf->Cell(200,300,$pdf->Image('upload_ficheros/'.$wlarchivo,10,67,129,100),1,1,'C',0);
//$pdf->Image('upload_ficheros/'.$wlarchivo,10,10,200,200);


//Determinar un nombre temporal de fichero en el directorio actual
$file=basename(tempnam(getcwd(),'tmp'));
//Determinar en nombre para el archivo pdf
$file1="temp/".$file;
$file1.='.pdf';
//Guardar el PDF en un fichero
$pdf->Output($file1);
//Borro archivo temporal
if(file_exists($file))
{unlink($file);}
//Redirección con JavaScript
echo "	<html><script>	";
echo "		self.close();	";
echo "		parent.close();	";
echo "		document.location='$file1';	";
echo "	</script></html>";
?>