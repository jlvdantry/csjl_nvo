<?PHP
putenv("TZ=America/Mexico_City");
include("conneccion.php");
	// Genera titulos
	$titulo1='GOBIERNO DEL DISTRITO FEDERAL0';
	$titulo2='OFICIALIA MAYOR';
	$titulo3='DIRECCION GENERAL DE PATRIMONIO INMOBILIARIO';
	$titulo4='DIRECCION DE INVENTARIO INMOBILIARIO Y SISTEMAS DE INFORMACION';
	$titulo5='SUBDIRECCION DE INSPECCION E INVESTIGACIONINMOBILIARIA';
	// Genera firmas
	$sql = "select * from sicop.v_firmas where idreporte=1 order by nivel	";
	//echo "<textarea>$sql</textarea>";
	$sql_result_f = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result_f);	
	if ($num==0) {echo "No existen firmas definidas para el reporte, consulte con el administrador del sistema"; die;}
	
		
	// Genera detalle del reporte
	$sql =	"	select * from sicop.v_visitas where idvisita=$wlidvisita	";
	$sql_result_v = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result_v);	
	if ($num==0) {echo "No existen registros"; die;} else {$rowv=pg_fetch_array($sql_result_v,0);}
	
// :::: parametros de las celdas de titulos ::::
// tamaño de letra
$wlsize1=4.5;
// alto de celda
$wlalto1=3;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tamaño de letra
$wlsize2=6;
// alto de celda
$wlalto2=5;
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
	global $rowv;
	global $titulo1;
	global $titulo2;
	global $titulo3;
	global $titulo4;
	global $titulo5;
	global $wlsize1;
	global $wlsize2;
	global $wlalto1;
	global $wlalto2;

	
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',7);
	$this->Image('img/logo4_2007.JPG',70,30,160,0);
	$this->Image('img/logo1_2007_color.JPG',15,11,30,0);
	$this->Image('img/logo_omcolor.JPG',240,11,20,0);
	$this->SetLineWidth(0.4);
	$this->Line(50,10,50,28);
	$this->SetY(10);
	$this->cell(43,4,'',0,0,'L',0);
	$this->cell(0,4,$titulo1,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo2,0,1,'L',0);
	//$this->cell(0,4,$rowv['clave'],0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3,0,1,'L',0);
	$this->SetFont('Arial','B',5);
	$this->cell(43,3,'',0,0,'L',0);	
	$this->cell(0,3,$titulo4,0,1,'L',0);
	$this->cell(43,3,'',0,0,'L',0);	
	$this->cell(0,3,$titulo5,0,1,'L',0);
	$this->Ln(3);
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',$wlsize2);
	$this->cell(0,$wlalto2,'IDENTIFICACION DEL INMUEBLE:','B',1,'C',0);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell(40,$wlalto1,'CLAVE:',0,0,'L',0);
	$this->Cell(1,$wlalto1,'',0,0,0,0);
	$this->Cell(40,$wlalto1,'FECHA:',0,0,'L',0);
	$this->Cell(1,$wlalto1,'',0,0,0,0);
	$this->Cell(45,$wlalto1,'CUENTA CATASTRAL:',0,0,'L',0);
	$this->Cell(1,$wlalto1,'',0,0,0,0);
	$this->Cell(45,$wlalto1,'PLANO CATASTRAL:',0,0,'L',0);
	$this->Cell(1,$wlalto1,'',0,0,0,0);
	$this->Cell(50,$wlalto1,'SUP. DE TERRENO:',0,0,'L',0);
	$this->Cell(1,$wlalto1,'',0,0,0,0);
	$this->Cell(0,$wlalto1,'SUP. DE CONSTRUCCION:',0,1,'L',0);
    $this->SetFont('Arial','B',$wlsize2);
    $this->Cell(40,$wlalto2,$rowv['clave'],'RB',0,'L',1);
    $this->Cell(1,$wlalto2,'',0,0,0,0);
	$this->Cell(40,$wlalto2,meses_espanol(substr($rowv['fecha_visita'],5,2)).' '.(int)substr($rowv['fecha_visita'],8,2).' de '.substr($rowv['fecha_visita'],0,4),'RB',0,'l',1);
	$this->Cell(1,$wlalto2,'',0,0,0,0);
	$this->Cell(45,$wlalto2,$rowv['ctapredial'],'RB',0,'L',1);
    $this->Cell(1,$wlalto2,'',0,0,0,0);
    $this->Cell(45,$wlalto2,$rowv['plano_catastral'],'RB',0,'L',1);
    $this->Cell(1,$wlalto2,'',0,0,0,0);
    $this->Cell(50,$wlalto2,$rowv['superficieterreno'],'RB',0,'L',1);
    $this->Cell(1,$wlalto2,'',0,0,0,0);
    $this->Cell(0,$wlalto2,$rowv['superficieconstruccion'],'RB',1,'L',1);
    $this->SetFont('Arial','B',$wlsize1);
    $this->Cell(0,$wlalto1,'DOMICILIO:',0,1,'L',0);
    $this->SetFont('Arial','B',$wlsize2);
    $this->Cell(0,$wlalto2,'CALLE '.$rowv['calle'].' No. '.$rowv['numext'].' '.$rowv['numint'].', COLONIA '.$rowv['colonia'].', DELEGACION '.$rowv['delegacion'].', CP. '.$rowv['cp'],'RB',1,'L',1);
    $this->SetFont('Arial','B',$wlsize1);
    $this->Cell(150,$wlalto1,'PROPIETARIO:',0,0,'L',0);
    $this->Cell(1,$wlalto1,'',0,0,0,0);
    $this->Cell(0,$wlalto1,'USO:',0,1,'L',0);
    $this->SetFont('Arial','B',$wlsize2);
    $this->Cell(150,$wlalto2,$rowv['propietario'],'RB',0,'L',1);
    $this->Cell(1,$wlalto2,'',0,0,0,0);
    $this->Cell(0,$wlalto2,$rowv['usog'].' - '.$rowv['usoe'],'RB',1,'L',1);
	$this->Ln(2);
}

function Footer()
{
	global $parametro1;
	global $sql_result_f;
	global $wlsize1;
	global $wlsize2;
	
	global $wlalto1;
	global $wlalto2;
	
    $this->SetY(-22);
    //Select Arial italic 8
    $this->SetFont('Arial','I',7);
    //Print current and total page numbers

	$this->Image('img/logo3_2007.JPG',8,200,12,0);
	$this->Image('img/logo2_2007_color.JPG',265,200,12,0);
	$this->Setx(23);
	$this->SetFont('Arial','B',$wlsize1);
	
	$num= pg_numrows ($sql_result_f);	
	for ($i=0; $i<$num; $i++)
	{
		$row=pg_fetch_array($sql_result_f,$i);
		$this->Cell(45,2,$row['titulo'],0,0,'L',0);
		$this->Cell(2,4,'',0,0,0,0);
	}
	$this->Cell(0,2,'',0,1,0,0);
	
	$this->SetX(23);
	$this->SetFont('Arial','B',$wlsize2);
	for ($i=0; $i<$num; $i++)
	{
		$row=pg_fetch_array($sql_result_f,$i);
		$this->Cell(45,4,$row['nombre'],0,0,'C',0);
		$this->Cell(2,4,'',0,0,0,0);
	}
	$this->Cell(0,4,'',0,1,0,0);
	
	$this->Setx(23);
	for ($i=0; $i<$num; $i++)
	{
		$this->Cell(45,5,'','B',0,'C',0);
		$this->Cell(2,5,'',0,0,0,0);
	}
	$this->Cell(0,5,'',0,1,0,0);
	
	$this->Ln(2);
	$this->Setx(23);
	$this->Cell(40,5,'Fecha de emisiòn: ','LBT',0,'C',1);
	$this->Cell(40,5,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(40,5,'Hora de emisiòn: ','BT',0,'C',1);
	$this->Cell(30,5,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(40,5,'Usuario de emisión: ','BT',0,'C',1);
	$this->Cell(30,5,$parametro1.',','BT',0,'L',1);
	$this->Cell(20,5,'Pagina '.$this->PageNo().'/{nb}','BTR',0,'R',1);
}
}

$pdf = new PDF ();
$pdf->Fpdf('L','mm','Letter');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);

// :::: inicio del contenido del documento ::::

		// datos generales
		$pdf->SetFont('Arial','B',$wlsize2);
		$pdf->Cell(0,$wlalto2,'CARACTERISTICAS FISICAS Y ANTECEDENTES','B',1,'C',0);
		$pdf->SetFont('Arial','B',$wlsize1);
		$pdf->Cell(0,$wlalto1,'ESTADO FISICO:',0,1,'L',0);
    	$pdf->SetFont('Arial','B',$wlsize2);
    	$pdf->Multicell(0,$wlalto2,$rowv['estadofisico'],'RB','J',1);
    	$pdf->SetFont('Arial','B',$wlsize1);
    	$pdf->Cell(0,$wlalto1,'OBSERVACIONES:',0,1,'L',0);
    	$pdf->SetFont('Arial','B',$wlsize2);
    	$pdf->Multicell(0,$wlalto2,$rowv['observacion'],'RB','J',1);
    	
    	$pdf->SetFont('Arial','B',$wlsize2);
    	$pdf->Cell(0,$wlalto2,'MEDIDAS Y COLINDANCIAS','B',1,'C',0);
    	$pdf->SetFont('Arial','B',$wlsize1);
    	$pdf->Cell(0,$wlalto1,'DATOS:',0,1,'L',0);
    	$numv= pg_numrows ($sql_result_v);	
    	$cadena='';
    	for ($i=0; $i<$numv; $i++)
    	{
	    	$rowv=pg_fetch_array($sql_result_v,$i);
    		$cadena=$cadena.'AL '.$rowv['orientacion'].', EN '.$rowv['medida'].' METROS, CON '.$rowv['colindancia'];
    		if ($i<$num) {$cadena=$cadena.htmlspecialchars("\n");} else {}
		}
		$pdf->SetFont('Arial','B',$wlsize2);
    	$pdf->Multicell(0,$wlalto2,$cadena,'RB','J',1);
    	
    	//consulta los archivos
		$sql = " select * from sicop.v_archivos where idvisita=$wlidvisita and img order by idtipoarchivo,fecha_alta	";
		$sql_result_a = pg_exec($connection,$sql);
		if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
		$num= pg_numrows ($sql_result_a);	
		if ($num>0)
		{
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',$wlsize2);
			$e=0;
			for ($i=0; $i < $num; $i++)
			{
				$row=pg_fetch_array($sql_result_a,$i);
				/*if ($row['idtipoarchivo']=='10') {	$descripcionplano=$row['archivo'];	}
				if ($row['idtipoarchivo']=='11') {	$croquis=$row['archivo'];	}
				if ($row['idtipoarchivo']=='12') {	$fotografia=$row['archivo'];	}*/
				if ($e==0) 
				{
					$pdf->Cell(129,5,$row['tipo'].":",0,1,'L',0);
					$pdf->Cell(129,100,$pdf->Image('upload_ficheros/'.$row['archivo'],10,67,129,100),1,0,'C',0);
					$e++;
				} elseif ($e==1) {
					$pdf->SetY(62);
					$pdf->SetX(140);
					$pdf->Cell(129,5,$row['tipo'].":",0,1,'L',0);
					$pdf->SetY(67);
					$pdf->SetX(140);
					$pdf->Cell(129,100,$pdf->Image('upload_ficheros/'.$row['archivo'],10+129+1,67,129,100),1,0,'C',0);
					$e=0;
					if ($i+1 < $num)	{	$pdf->AddPage();	}
				}
				
			}
		}
		
		
		/*$pdf->Cell(140,5,'PLANO',0,0,'L',0);
		$pdf->Cell(2,5,'',0,0,0,0);
		$pdf->Cell(95,5,'CROQUIS DE LOCALIZACION',0,0,'L',0);
		$pdf->Cell(2,5,'',0,0,0,0);
		$pdf->Cell(95,5,'FOTOGRAFIA',0,1,'L',0);
		
		$pdf->Cell(140,110,((!$plano) ? 'Sin plano' :$pdf->Image('upload_ficheros/'.$plano,10,60,140,110)),1,0,'C',0);
		$pdf->Cell(2,100,'',0,0,0,0);
		$pdf->Cell(95,70,((!$croquis) ? 'Sin croquis' : $pdf->Image('upload_ficheros/'.$croquis,10+140+2,60,95,70)),1,0,'C',0);
		$pdf->Cell(2,100,'',0,0,0,0);
		$pdf->Cell(95,70,((!$fotografia) ? 'Sin fotografia' : $pdf->Image('upload_ficheros/'.$fotografia,10+140+2+95+2,60,95,70)),1,0,'C',0);*/
   
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