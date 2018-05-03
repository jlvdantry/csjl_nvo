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

	// Obtiene copias del reporte
	$sql="	select * from contra.v_cc where idcontestacion=".$wlidcontestacion.";	";
	//echo "<textarea>$sql</textarea>";
	$sql_result_cc = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	
	// Obtiene rubricas del reporte
	$sql="	select * from contra.v_rubricas where idcontestacion=".$wlidcontestacion.";	";
	//echo "<textarea>$sql</textarea>";
	$sql_result_rub = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	
	// Genera detalle del reporte
	$sql="	select * from contra.v_contestaciones where idcontestacion=".$wlidcontestacion.";	";
	//echo "<textarea>$sql</textarea>";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio $wlidcontestacion no existe en la tabla de contestaciones de gestion"; die;}
	$row=pg_fetch_array($sql_result,0);

$wlpenvia=($row['titulo_envia']!='' ? $row['titulo_envia'].'. ' : '').$row['persona_envia'];
$wlrecibe=($row['titulo_recibe']!='' ? $row['titulo_recibe'].'. ' : 'C. ').$row['persona_recibe'];
$wlrecibepuesto=$row['puesto_recibe'];
$wlrecibedireccion=$row['ubicacion_recibe'];
$wlrecibeorganizacion=$row['organizacion_recibe'];
$wlrecibecatego=$row['catego_recibe'];
$wlenviapuesto=$row['puesto_envia'];
$wlenviadireccion=$row['ubicacion_envia'];
$wlenviacatego=$row['catego_envia'];
$wldesdocto=$row['descripcion_docto'];
$wldesasunto=$row['descripcion_asunto'];
$wlasunto=$row['asunto_c'];
$wlfechadocumento=$row['fechadocumento'];
$wlfolio=$row['folio'];
$wloficio=$row['referencia_c'];
$wlfechaalta=$row['fecha_alta'];
$wlusuarioalta=$row['usuario_alta'];
$folio=$row['folio'];

// :::: parametros de las celdas de titulos ::::
// tamaño de letra
$wlsize1=5;
// alto de celda
$wlalto1=4;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tamaño de letra
$wlsize2=10;
// alto de celda
$wlalto2=6;
// largo de celdas
$wllargo2=180;
$wllargo2b=85;
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
	global $titulo1;
	global $titulo2;
	global $titulo3;
	global $titulo4;
	global $wldesdocto;
	global $wldesasunto;
	global $wlpenvia;
	global $wlrecibe;
	global $wlfechadocumento;
	global $wlfecharecibo;
	global $wlfolio;
	global $wloficio;
	global $wlasunto;
	global $wlsetx;
	global $wlsize1;
	global $wlsize2;
	global $wllargo1;
	global $wllargo2b;
	global $wllargo2;
	global $wlalto1;
	global $wlalto2;
	global $relevante;
	global $wlenviapuesto;
	
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',10);
	$this->Image('img/logo4_2007.JPG',5,30,0,0);
	$this->Image('img/logo1_2007_color.JPG',17,11,40,0);
	$this->Image('img/logo_omcolor.JPG',180,11,20,0);
	$this->SetLineWidth(0.4);
	$this->Line(60,8,60,32);
	$this->SetY(13);
	$this->cell(53,4,'',0,0,'L',0);
	$this->cell(0,4,$titulo1,0,1,'L',0);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo2,0,1,'L',0);
	$this->SetFont('Arial','',8);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3.' '.$titulo4,0,1,'L',0);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->Multicell(0,4,$wlenviapuesto,0,'L',0);
	$this->Ln(10);
}

function Footer()
{
	global $parametro1;
	global $folio;
	global $wlfechaalta;
	global $wlusuarioalta;
	global $firma_n;
	global $firma_p;
	global $wlsetx;
	global $wlsetx2;
	global $wlsize1;
	global $wlsize2;
	global $wllargo1;
	global $wllargo2c;
	global $wllargo2;
	global $wlalto1;
	global $wlalto2;
	global $wlenviadireccion;
	global $sql_result_rub;
	
	$this->SetY(-24);
	$this->SetFont('Arial','B',7);	
	$num = pg_numrows($sql_result_rub);
	if ($num>0)
	{
		$rubricas='';
		for ($i=0; $i<$num; $i++)
		{
			$row = pg_fetch_array($sql_result_rub, $i);	
			$rubricas=$rubricas.$row['siglas'];
			if ($num>$i+1) {$rubricas=$rubricas.' / ';}
		}
	}
	$this->Cell(80,3,'FOLIO: '.$folio,0,0,'L',0);
	$this->Cell(0,3,$rubricas,0,1,'R',0);	
	$this->Ln(4);
    $this->SetFont('Arial','I',6);
    //Print current and total page numbers
	$this->Image('img/logo3_2007.JPG',8,260,12,0);
	$this->Image('img/logo2_2007_color.JPG',197,260,12,0);
	$this->Setx(23);
	$this->Cell(25,4,'Fecha de emisiòn: ','LBT',0,'R',1);
	$this->Cell(30,4,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(25,4,'Hora de emisiòn: ','BT',0,'R',1);
	$this->Cell(15,4,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(35,4,'Usuario de emisión: ','BT',0,'R',1);
	$this->Cell(25,4,$parametro1.',','BT',0,'L',1);
	$this->Cell(15,4,'Pagina '.$this->PageNo().'/{nb}','BTR',1,'C',1);
	$this->Ln(1);
	$this->Setx(23);
	$this->MultiCell(170,3,$wlenviadireccion,0,'C',0);
}
}

$pdf = new PDF ();
$pdf->Fpdf('P','mm','Letter');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);


// :::: inicio del contenido del documento ::::
$pdf->Ln(5);
$pdf->SetLineWidth(0.2);
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','i',$wlsize2);
$pdf->Cell(110,$wlalto2,'Fecha:',0,0,'R',0);
$pdf->Cell(0,$wlalto2,'Mèxico, D.F. a '.date(" j ").' de '.meses_espanol(date('n')).' de'.date(" Y"),'B',1,'C',0);
$pdf->Cell(110,$wlalto2,'Tipo de documento:',0,0,'R',0);
$pdf->Cell(0,$wlalto2,$wldesdocto,'B',1,'C',0);
$pdf->Cell(110,$wlalto2,'Numero:',0,0,'R',0);
$pdf->Cell(0,$wlalto2,($wloficio=='' ? 'Propuesta '.$wloficio : $wloficio),'B',1,'C',0);
$pdf->Cell(110,$wlalto2,'Asunto:',0,0,'R',0);
$pdf->MultiCell(0,$wlalto2,$wldesasunto,'B','C',0);
$pdf->Ln(3);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->Setx(20);
$pdf->Cell(100,$wlalto1,$wlrecibe,0,1,'L',0);
$pdf->Setx(20);
$pdf->MultiCell(100,$wlalto1,$wlrecibepuesto,0,'L',0);
$pdf->Setx(20);
$pdf->Cell(100,$wlalto1,$wlrecibeorganizacion,0,1,'L',0);
$pdf->SetFont('Arial','',7);
$pdf->Setx(20);
$pdf->MultiCell(100,$wlalto1,$wlrecibedireccion,0,'L',0);

$pdf->SetFont('Arial','B',$wlsize2);
$pdf->Setx(20);
$pdf->Cell(100,$wlalto2,'P R E S E N T E.',0,1,'L',0);
$pdf->Ln(5);
$pdf->SetFont('Arial','',$wlsize2);
$pdf->Setx(20);
$pdf->MultiCell(0,4,'               '.$wlasunto,0,'J',0);

if ($pdf->GetY()>210 ) { $pdf->AddPage(); }

$pdf->SetY(210);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->Setx(20);
$pdf->Cell(100,$wlalto1,'ATENTAMENTE',0,1,'L',0);
$pdf->Setx(20);
$pdf->MultiCell(100,$wlalto1,$wlenviacatego,0,'L',0);
$pdf->Ln(15);
$pdf->Setx(20);
$pdf->Cell(100,$wlalto1,$wlpenvia,0,1,'L',0);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',7);
$num = pg_numrows($sql_result_cc);
if ($num>0)
{
	for ($i=0; $i<$num; $i++)
	{
		$row = pg_fetch_array($sql_result_cc, $i);	
		$pdf->Setx(20);
		$pdf->Cell($wllargo1,3,'C.C.P. '.$row['nombre_completo'],0,1,'L',0);
	}
}

/*$pdf->SetY(255);
$num = pg_numrows($sql_result_rub);
if ($num>0)
{
	$rubricas='';
	for ($i=0; $i<$num; $i++)
	{
		$row = pg_fetch_array($sql_result_rub, $i);	
		$rubricas=$rubricas.$row['siglas'];
		if ($num>$i+1) {$rubricas=$rubricas.' / ';}
	}
}
$pdf->Setx(20);
$pdf->Cell(80,3,'FOLIO: '.$folio,0,0,'L',0);
$pdf->Cell(0,3,$rubricas,0,1,'R',0);
$pdf->Ln(10);
*/


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