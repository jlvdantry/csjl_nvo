<?PHP
putenv("TZ=America/Mexico_City");
include("conneccion.php");
	// Genera titulos
	$sql = "select * from contra.v_titulos ";
	//echo "<textarea>$sql</textarea>";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "No existen titulos definidos para el reporte, consulte con el administrador del sistema"; die;}
	$row=pg_fetch_array($sql_result,0);
	$titulo1=$row['titulo1'];
	$titulo2=$row['titulo2'];
	$titulo3=$row['titulo3'];
	$titulo4=$row['titulo4'];
	$firma_n=$row['nombre_completo'];
	$firma_p=$row['puesto'];

	//echo "filtro:".$wlfltro;
	// Genera detalle del reporte
	$sql =	"	select * from agenda.v_llamadas	".
			"	$wlfltro order by fecha, hora";
	//echo "<textarea>$sql</textarea>"; die;
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0 )
	{	echo "	<script language=\"JavaScript\">";
		echo "	alert ('No se encontraron registros');	";
		echo "	close ();	";
		echo "	</script>";
		die;
	}
	
// :::: parametros de las celdas de titulos ::::
// tamaño de letra
$wlsize1=4.5;
// alto de celda
$wlalto1=2;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tamaño de letra
$wlsize2=6;
// alto de celda
$wlalto2=4;
// largo de celdas
$wllargo2=180;
$wllargo2b=45;
$wllargo2c=50;

// :::: valor para establecer las abscisas de la celdas ::::
$wlsetx=20;
$wlsetx2=140;

define('FPDF_FONTPATH','font/');
require('fpdf.php');

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
	global $sql_result;
	global $titulo1;
	global $titulo2;
	global $titulo3;
	global $titulo4;
	global $wlsetx;
	global $wlsize1;
	global $wlsize2;
	global $wllargo1;
	global $wllargo2b;
	global $wllargo2;
	global $wlalto1;
	global $wlalto2;
	global $fecha_inicio;
	global $fecha_fin;
	global $estatus;
	
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',9);
	$this->Image('img/logo4_2007.JPG',150,30,160,0);
	$this->Image('img/logo1_2007_color.JPG',17,11,40,0);
	$this->Image('img/logo_omcolor.JPG',310,11,20,0);
	$this->SetLineWidth(0.4);
	$this->Line(60,8,60,32);
	$this->SetY(13);
	$this->cell(53,4,'',0,0,'L',0);
	$this->cell(0,4,$titulo1,0,1,'L',0);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo2,0,1,'L',0);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3,0,1,'L',0);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo4,0,1,'L',0);
	$this->Ln(3);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
    $this->SetFont('Arial','BI',9);
	$this->Cell(0,6,'REPORTE DE LLAMADAS','B',1,'C',0);
	$this->Ln(3);
	$row=pg_fetch_array($sql_result,0);
	$this->SetFont('Arial','',5);
	$this->Cell(60,$wlalto1,'Fecha de cita:',0,1,'L',0);
	$this->SetFont('Arial','B',6);
	$this->Cell(60,4,$fecha_inicio.' - '.$fecha_fin,'LB',0,'C',0);
	$this->Cell(0,4,'Hoja con resumen','LB',1,'R',0);
	$this->Ln(2);
	$this->SetFont('Arial','I',$wlsize1);
	$this->Cell(20,$wlalto1,'FECHA',0,0,'L',0);
	$this->Cell(20,$wlalto1,'HORA',0,0,'L',0);
	$this->Cell(80,$wlalto1,'QUIEN',0,0,'L',0);
	$this->Cell(30,$wlalto1,'NUMERO',0,0,'L',0);
	$this->Cell(80,$wlalto1,'ASUNTO',0,1,'L',0);
}

function Footer()
{
	global $parametro1;
	
    $this->SetY(-17);
    //Select Arial italic 8
    $this->SetFont('Arial','I',7);
    //Print current and total page numbers

	$this->Image('img/logo3_2007.JPG',8,195,12,0);
	$this->Image('img/logo2_2007_color.JPG',335,195,12,0);
	$this->Setx(23);
	$this->Cell(40,6,'Fecha de emisiòn: ','LBT',0,'C',1);
	$this->Cell(50,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(40,6,'Hora de emisiòn: ','BT',0,'C',1);
	$this->Cell(50,6,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(40,6,'Usuario de emisión: ','BT',0,'C',1);
	$this->Cell(50,6,$parametro1.',','BT',0,'L',1);
	$this->Cell(40,6,'Pagina '.$this->PageNo().'/{nb}','BTR',0,'R',1);
}
}

$pdf = new PDF ();
$pdf->Fpdf('L','mm','Letter');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','I',$wlsize2);
$pdf->SetLineWidth(0.2);

$pdf->SetFont('Arial','I',$wlsize2);
$num= pg_numrows ($sql_result);	
for ($i=0; $i<$num; $i++)
{
	$row=pg_fetch_array($sql_result,$i);
	$pdf->SetX(0);
	$pdf->SetFont('Arial','I',$wlsize2);
	$pdf->Cell(9,$wlalto2,$i+1,0,0,'C',0);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(20,$wlalto2,$row['fecha'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(19,$wlalto2,$row['hora'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(80,$wlalto2,$row['nombre_completo'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(29,$wlalto2,$row['numero'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->MultiCell(0,$wlalto2,$row['asunto'],'BR','C',1);
	$pdf->Ln(1);
	if ($row['comentarios']!='')
	{
		$pdf->Cell(20,$wlalto2,'COMENTARIOS:',0,0,'C',0);
		$pdf->MultiCell(0,$wlalto2,$row['comentarios'],'BR','L',1);
		$pdf->Ln(1);
	}
}

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