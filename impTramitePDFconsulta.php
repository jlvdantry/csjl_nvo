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
	
	// Genera detalle del reporte
	if (empty($wlfolioconsecutivo)) {echo "El folio consecutivo no esta definido"; die;}
	$sql="	select vtr.*, vt.nombre_completo,vt.descripcion, vt.observacion, vt.liberado, vt.fecha_alta as falta, vt.usuario_alta as ualta, vt.liberado,	".
		 "	(select length(ficheroin) from contra.ope_archivos as a where a.folioconsecutivo=vtr.folioconsecutivo and id_tipoarc=1 order by fecha_alta asc limit 1) as adjuntos	".
		 "	from contra.v_tramites as vtr	".
		 "	LEFT JOIN contra.v_turnados vt ON vt.folioconsecutivo = vtr.folioconsecutivo	".
		 "	where vtr.folioconsecutivo=$wlfolioconsecutivo order by vt.fecha_alta asc;	";
	//echo "<textarea>$sql</textarea>";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de gestion"; die;}
	$row=pg_fetch_array($sql_result,0);

$wlpenvia=$row['persona_envia'];
$wlrecibe=$row['persona_recibe'];
$wldesdocto=$row['descripcion_docto'];
$wldesasunto=$row['descripcion_asunto'];
$wlestatus=$row['descripcion_estatus'];
$wlasunto=$row['asunto'];
$wlfechadocumento=$row['fechadocumento'];
$wlfecharecibo=$row['fecharecibo'];
$wlfolio=$row['folio'];
$wloficio=$row['referencia'];
$wlfechaalta=$row['fecha_alta'];
$wlusuarioalta=$row['usuario_alta'];
$wldiastermino=$row['diastermino'];
$wladjuntos=$row['adjuntos'];

// :::: parametros de las celdas de titulos ::::
// tamaño de letra
$wlsize1=5;
// alto de celda
$wlalto1=3;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tamaño de letra
$wlsize2=7;
// alto de celda
$wlalto2=5;
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
	global $wladjuntos;
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',9);
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
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3,0,1,'L',0);
	$this->cell(53,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo4,0,1,'L',0);
	$this->Ln(3);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',$wlsize2);
    $this->SetFont('Arial','B',9);
	$this->Cell(0,6,'CONTROL DE TRAMITES','B',1,'C',0);
	if ($relevante=='t') {	$this->Cell(0,6,'TRAMITE RELEVANTE',0,1,'C',0);	}
	$this->Image((($wladjuntos>0) ? 'img/attachIcon.jpg' : 'img/fail.jpg'),188,30,5,0);
	
	$this->Ln(3);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'TIPO DE DOCUMENTO',0,0,'L',0);
	$this->SetX(115);
	$this->Cell($wllargo1,$wlalto1,'CLAVE DEL ASUNTO',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$wldesdocto,'BR',0,'C',1);
	$this->SetX(115);
	$this->Cell($wllargo2b,$wlalto2,$wldesasunto,'BR',1,'C',1);
	
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'PERSONA QUE ENVIA',0,0,'L',0);
	$this->SetX(115);
	$this->Cell($wllargo1,$wlalto1,'DESTINATARIO',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$wlpenvia,'BR',0,'C',1);
	$this->SetX(115);
	$this->Cell($wllargo2b,$wlalto2,$wlrecibe,'BR',1,'C',1);
	
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'FECHA DEL DOCUMENTO',0,0,'L',0);
	$this->SetX(115);
	$this->Cell($wllargo1,$wlalto1,'FECHA DE RECEPCION',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$wlfechadocumento,'BR',0,'C',1);
	$this->SetX(115);
	$this->Cell($wllargo2b,$wlalto2,$wlfecharecibo,'BR',1,'C',1);
	
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'FOLIO DE RECEPCION',0,0,'L',0);
	$this->SetX(115);
	$this->Cell($wllargo1,$wlalto1,'NUMERO DE OFICIO',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$wlfolio,'BR',0,'C',1);
	$this->SetX(115);
	$this->Cell($wllargo2b,$wlalto2,$wloficio,'BR',1,'C',1);
	
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'ASUNTO',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,$wlasunto,'BR','C',1);
	$this->ln(5);
	$this->Cell(0,6,'TURNOS','B',1,'C',0);
	$this->ln(2);
}

function Footer()
{
	global $parametro1;
	global $wldiastermino;
	global $wlfechaalta;
	global $wlusuarioalta;
	global $wlsetx;
	global $wlsetx2;
	global $wlsize1;
	global $wlsize2;
	global $wllargo1;
	global $wllargo2c;
	global $wllargo2;
	global $wlalto1;
	global $wlalto2;
	
	$this->SetY(-45);
	
	$this->SetX($wlsetx2);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'DIAS DE TERMINO:',0,1,'L',0);
	$this->SetX($wlsetx2);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2c,$wlalto2,$wldiastermino,'BR',1,'C',1);
	
	$this->SetX($wlsetx2);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'FECHA DE REGISTRO:',0,1,'L',0);
	$this->SetX($wlsetx2);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2c,$wlalto2,$wlfechaalta,'BR',1,'C',1);
	
	$this->SetX($wlsetx2);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'USUARIO DE ALTA:',0,1,'L',0);

	$this->SetX($wlsetx2);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2c,$wlalto2,$wlusuarioalta,'BR',1,'C',1);
	
	$this->Ln(5);
    //Go to 1.5 cm from bottom
    //$this->SetY(-12);
    //Select Arial italic 8
    $this->SetFont('Arial','I',6);
    //Print current and total page numbers

	$this->Image('img/logo3_2007.JPG',8,260,12,0);
	$this->Image('img/logo2_2007_color.JPG',197,260,12,0);
	$this->Setx(23);
	$this->Cell(25,6,'Fecha de emisiòn: ','LBT',0,'R',1);
	$this->Cell(30,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(25,6,'Hora de emisiòn: ','BT',0,'R',1);
	$this->Cell(15,6,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(35,6,'Usuario de emisión: ','BT',0,'R',1);
	$this->Cell(25,6,$parametro1.',','BT',0,'L',1);
	$this->Cell(15,6,'Pagina '.$this->PageNo().'/{nb}','BTR',1,'C',1);
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

$e=0;
for ($i=0; $i<$num; $i++)
{
	$e++;
	if ($e==8){$e=1; $pdf->AddPage();}
	$row=pg_fetch_array($sql_result,$i);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell(70,$wlalto1,'PERSONA EN TURNO',0,0,'L',0);
	$pdf->Cell(56,$wlalto1,'TRAMITE',0,0,'L',0);
	$pdf->Cell(35,$wlalto1,'FECHA ALTA',0,0,'L',0);
	$pdf->Cell(25,$wlalto1,'USUARIO ALTA',0,0,'L',0);
	$pdf->Cell(0,$wlalto1,'LIBERADO',0,1,'L',0);
	
	$pdf->SetX(4);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell(5,$wlalto2,$i+1,0,0,'C',0);
	$pdf->Cell(1,$wlalto2,'',0,0,'L',0);
	$pdf->Cell(70,$wlalto2,$row['nombre_completo'],'BR',0,'L',1);
	$pdf->Cell(1,$wlalto2,'',0,0,'L',0);
	$pdf->Cell(55,$wlalto2,$row['descripcion'],'BR',0,'L',1);
	$pdf->Cell(1,$wlalto2,'',0,0,'L',0);
	$pdf->Cell(33,$wlalto2,$row['falta'],'BR',0,'L',1);
	$pdf->Cell(1,$wlalto2,'',0,0,'L',0);
	$pdf->Cell(25,$wlalto2,$row['ualta'],'BR',0,'L',1);
	$pdf->Cell(1,$wlalto2,'',0,0,'L',0);
	$pdf->Cell(0,$wlalto2,$row['liberado'],'BR',1,'L',1);
	
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell(30,$wlalto1,'OBSERVACION:',0,1,'L',0);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->multiCell(0,$wlalto2,$row['observacion'],'BR','L',1);
	//$pdf->Cell($wllargo2c,$wlalto2,$row['observacion'],'BR',1,'C',1);
	$pdf->Cell(0,1,'','B',1,'L',0);
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