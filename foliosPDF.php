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
	$area=$row['area'];

	//echo "filtro:".$wlfltro;
	
	// Genera detalle del reporte
	$sql =	"	select	".($foliosunicos=="t" ? "distinct" : "" )." date(fecha_altat) as fecha_altat,folio,".
			"	".($foliosunicos=="t" ? "'-' as hora,'-' as vom," : "hora,vom," )."oficio,persona_envia,organizacion_envia,anexos,desestatus,	".
			"	".($foliosunicos=="t" ? ($puesto=="" ? "'-'" : "area" ): ($puesto=="" ? "'-'" : "area" ) )." as area	".
			"	from contra.v_folios".(($iterno=="t") ? "_interno" : "" )." vp	".
			"	where folio is not null	".
/*			($ventanilla=="t" ? "	and vp.usuario_altat in ".
								"	(	select usename from cat_usuarios	".
								"	where usename in (	".
								"	select usename 	".
								"	from cat_usuarios_pg_group as capg	".
								"	left join pg_group as pg on  pg.grosysid=capg.grosysid	".
								"	where groname  in ('contra_ventanilla','contra_ventanilla_admon')))	" : " and vp.usuario_altat=current_user " ).*/
			"	$wlfltro order by 1,2 ";
//	echo "<textarea>$sql</textarea>";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql."<br>".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0 )
	{	echo "	<script language=\"JavaScript\">";
		echo "	alert ('No se encontraron registros $num');	";
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
$wlsize2=5;
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
	global $fecharecibofin;
	global $fecharecibo;
	global $puesto;
	global $area;
	global $foliosunicos;
	global $iterno;
	global $estatus;
	global $ventanilla;
	
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',7);
	$this->Image('img/logo4_2007.JPG',90,30,160,0);
	$this->Image('img/logo1_2007_color.JPG',17,11,30,0);
	$this->Image('img/logo_omcolor.JPG',240,11,20,0);
	$this->SetLineWidth(0.4);
	$this->Line(50,8,50,28);
	$this->SetY(10);
	$this->cell(43,4,'',0,0,'L',0);
	$this->cell(0,4,$titulo1,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo2,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo4,0,1,'L',0);
	$this->Ln(3);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','BI',7);
	$this->Cell(0,5,"CONTROL DE FOLIOS".(($iterno=="t") ? " INTERNOS" : "" ).($foliosunicos=="t" ? " (Sin detalle de turnos)" : " (Con detalle de turnos)" ),0,1,'C',0);
		
	$row=pg_fetch_array($sql_result,$i);
	$num= pg_numrows ($sql_result);	
	$this->SetFont('Arial','',4);
	$this->Cell(90,$wlalto1,'Área:',0,0,'L',0);
	$this->Cell(90,$wlalto1,'Puesto:',0,0,'L',0);
	$this->Cell(25,$wlalto1,'Estatus:',0,0,'L',0);
	$this->Cell(25,$wlalto1,'Fecha de recepcion:',0,0,'L',0);
	$this->Cell(25,$wlalto1,'Fecha de turno:',0,0,'L',0);
	$this->Cell(0,$wlalto1,'Folios:',0,1,'L',0);
	$this->SetFont('Arial','B',5);
	$this->Cell(90,$wlalto2,($ventanilla=='t' ? 'J.U.D. DE VENTANILLA ÚNICA DE RECEPCIÓN Y SEGUIMIENTO DOCUMENTAL' : $area ),'LB',0,'C',0);
	$this->Cell(90,$wlalto2,($puesto=='' ? 'SIN DEFINIR' : $row['area']),'LB',0,'C',0);
	$this->Cell(25,$wlalto2,($estatus=='' ? 'SIN DEFINIR' : $row['desestatus']),'LB',0,'C',0);
	$this->Cell(25,$wlalto2,$fecharecibo.' - '.$fecharecibofin,'LB',0,'C',0);
	$this->Cell(25,$wlalto2,($fechat=='' ? 'SIN DEFINIR' : $fechat ),'LB',0,'C',0);
	$this->Cell(0,$wlalto2,$num,'LB',1,'C',0);
	$this->Ln(3);
	//$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell(10,$wlalto1,'No.',0,0,'L',0);
	$this->Cell(11,$wlalto1,'Folio',0,0,'L',0);
	$this->Cell(11,$wlalto1,'Hora',0,0,'L',0);
	$this->Cell(21,$wlalto1,'VOM',0,0,'L',0);
	$this->Cell(31,$wlalto1,'Oficio',0,0,'L',0);
	$this->Cell(71,$wlalto1,'Persona que envia',0,0,'L',0);
	$this->Cell(91,$wlalto1,'Organizacion',0,0,'L',0);
	$this->Cell(0,$wlalto1,'Anexos',0,1,'L',0);
	$this->Ln(1);
}

function Footer()
{
	global $parametro1;
	
    $this->SetY(-15);
    //Select Arial italic 8
    $this->SetFont('Arial','B',7);
    //Print current and total page numbers

    //$this->Cell(180,8,'',0,0,0,0);
    //$this->Cell(50,8,'FIRMA Y SELLO DEL AREA','T',1,'C',0);
    
	$this->Image('img/logo3_2007.JPG',8,198,12,0);
	$this->Image('img/logo2_2007_color.JPG',259,198,12,0);
	$this->Setx(23);
	$this->Cell(40,6,'Fecha de emisiòn: ','LBT',0,'C',1);
	$this->Cell(40,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(30,6,'Hora de emisiòn: ','BT',0,'C',1);
	$this->Cell(30,6,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(30,6,'Usuario de emisión: ','BT',0,'C',1);
	$this->Cell(40,6,$parametro1.',','BT',0,'L',1);
	$this->Cell(22,6,'Pagina '.$this->PageNo().'/{nb}','BTR',0,'R',1);
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

$num= pg_numrows ($sql_result);	
$e=0;
for ($i=0; $i<$num; $i++)
{
	$row=pg_fetch_array($sql_result,$i);
	$fechat2=$row['fecha_altat'];
	if ($fechat1!=$fechat2)
	{
		
		$fechat1=$fechat2;
		$pdf->SetFont('Arial','BI',$wlsize2);
		$pdf->Cell(40,$wlalto1,'Folios turnados: '.$row['fecha_altat'],0,1,'L',0);
		$pdf->Ln(1);
		$e=0;
	}
	$e++;
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell(5,$wlalto2,$e,0,0,'C',0);
	$pdf->Cell(10,$wlalto2,$i+1,'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(10,$wlalto2,$row['folio'],'BR',0,'C',1);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(10,$wlalto2,$row['hora'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(20,$wlalto2,$row['vom'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(30,$wlalto2,$row['oficio'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(70,$wlalto2,$row['persona_envia'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(90,$wlalto2,$row['organizacion_envia'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(0,$wlalto2,$row['anexos'],'BR',0,'C',1);
	$pdf->Cell(0,$wlalto2,'',0,1,'L',0);
	$pdf->Ln(1);
	//$e++;
	//if ($e==30 && ($num>30 || $num>60 || $num>90 || $num>120)) {$e=0; $pdf->AddPage();}
	if ($pdf->GetY()>190 && $i+1<$num)
	{
		$pdf->AddPage();
		$pdf->SetFont('Arial','BI',$wlsize2);
		$pdf->Cell(40,$wlalto1,'Folios turnados: '.$row['fecha_altat'],0,1,'L',0);
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