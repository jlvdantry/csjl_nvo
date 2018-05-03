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
	$sql =	"	select	".
			"	vp.fecharecibo,	".
			"	cp.descripcion as pst,	".
			"	cpe.nombre_completo,	".
			"	ctt.descripcion as tt,	".
			"	count (*) as reg	".
			"	from contra.v_pendientes".(($iterno=="t") ? "_interno" : "" )." vp	".
			"	left join contra.cat_organizaciones as co on co.id_organizacion=vp.id_organizacion	".
			"	left join contra.cat_puestos as cp on cp.id_puesto=vp.id_puesto	".
			"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=vp.id_tipotra	".
			"	left join contra.v_cat_personas as cpe on cpe.id_persona=vp.id_persona	".
			"	where vp.id_organizacion=(select id_organizacion from contra.cat_personas where usename=current_user) 	".
			"	and liberado='N'	".
			"	$wlfltro ".
			"	group by 1,2,3,4	";
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
	
	//echo $sql."<br>"; 
	
	$sql =	"	select vp.*,case when relevante is true then 'Si' else 'No' end as rv,	".
			"	cp.descripcion as pst,	".
			"	cpe.nombre_completo,	".
			"	ctt.descripcion as tt,	".
			"	ca.descripcion as ast,	".
			"	cpeev.nombre_completo as nombre_completo_ev,	".
			"	cpere.nombre_completo as nombre_completo_re	".
			"	from contra.v_pendientes".(($iterno=="t") ? "_interno" : "" )." as vp	".
			"	left join contra.cat_organizaciones as co on co.id_organizacion=vp.id_organizacion	".
			"	left join contra.cat_puestos as cp on cp.id_puesto=vp.id_puesto	".
			"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=vp.id_tipotra	".
			"	left join contra.v_cat_personas as cpe on cpe.id_persona=vp.id_persona	".
			"	left join contra.v_cat_personas as cpeev on cpeev.id_persona=vp.idpersona_envia	".
			"	left join contra.v_cat_personas as cpere on cpere.id_persona=vp.idpersona_recibe	".
			"	left join contra.cat_asuntos as ca on ca.id_cveasunto=vp.id_cveasunto	".
			"	where vp.id_organizacion=(select id_organizacion from contra.cat_personas where usename=current_user) 	".
			"	and liberado='N'	".
			"	$wlfltro ".
			"	order by fecharecibo,pst,folio::numeric";
	$sql_result2 = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	
	//echo "<textarea>$sql</textarea>"; die;
	
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
$wlalto2=3;
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
	global $relvante;
	global $fecharecibo;
	global $fecharecibofin;
	global $iterno;
	
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
	$this->Cell(0,6,'REPORTE DE PENDIENTES'.(($iterno=="t") ? " INTERNOS" : "" ),0,1,'C',0);
	$this->Ln(3);
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
$pdf->Fpdf('L','mm','Legal');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','I',$wlsize2);
$pdf->SetLineWidth(0.2);

$row=pg_fetch_array($sql_result2,$i);
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,$wlalto1,'Fecha de recepcion:',0,0,'L',0);
$pdf->Cell(40,$wlalto1,'Fecha de turno:',0,0,'L',0);
$pdf->Cell(40,$wlalto1,'Estatus:',0,0,'L',0);
$pdf->Cell(40,$wlalto1,'Filtro de relevantes:',0,1,'L',0);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(40,4,$fecharecibo.' - '.$fecharecibofin,'LB',0,'C',0);
$pdf->Cell(40,4,($fechat=='' ? 'SIN DEFINIR' : $fechat ),'LB',0,'C',0);
$pdf->Cell(40,4,($estatus=='' ? 'SIN DEFINIR' : $row['desestatus']),'LB',0,'C',0);
$pdf->Cell(40,4,($relvante=='t' ? 'SI' : 'NO'),'LB',0,'C',0);
$pdf->Cell(0,4,'Hoja con resumen','LB',1,'R',0);
$pdf->Ln(3);
$pdf->SetFont('Arial','I',$wlsize1);
$pdf->Cell(20,$wlalto1,'FECHA',0,0,'L',0);
$pdf->Cell(120,$wlalto1,'PUESTO',0,0,'L',0);
$pdf->Cell(111,$wlalto1,'PERSONA EN TURNO',0,0,'L',0);
$pdf->Cell(51,$wlalto1,'TIPO DE TRAMITE',0,0,'L',0);
$pdf->Cell(0,$wlalto1,'PENDIENTES',0,1,'L',0);
$pdf->Ln(1);

$pdf->SetFont('Arial','I',$wlsize2);
$num= pg_numrows ($sql_result);	
for ($i=0; $i<$num; $i++)
{
	if ($pdf->GetY()>180)
	{
		$pdf->AddPage();
		$row=pg_fetch_array($sql_result2,$i);
		$pdf->SetFont('Arial','',4);
		$pdf->Cell(40,$wlalto1,'Fecha de recepcion:',0,0,'L',0);
		$pdf->Cell(40,$wlalto1,'Fecha de turno:',0,0,'L',0);
		$pdf->Cell(40,$wlalto1,'Estatus:',0,0,'L',0);
		$pdf->Cell(40,$wlalto1,'Filtro de relevantes:',0,1,'L',0);
		$pdf->SetFont('Arial','B',5);
		$pdf->Cell(40,4,$fecharecibo.' - '.$fecharecibofin,'LB',0,'C',0);
		$pdf->Cell(40,4,($fechat=='' ? 'SIN DEFINIR' : $fechat ),'LB',0,'C',0);
		$pdf->Cell(40,4,($estatus=='' ? 'SIN DEFINIR' : $row['desestatus']),'LB',0,'C',0);
		$pdf->Cell(40,4,($relvante=='t' ? 'SI' : 'NO'),'LB',0,'C',0);
		$pdf->Cell(0,4,'Hoja con resumen','LB',1,'R',0);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','I',$wlsize1);
		$pdf->Cell(20,$wlalto1,'FECHA',0,0,'L',0);
		$pdf->Cell(120,$wlalto1,'PUESTO',0,0,'L',0);
		$pdf->Cell(111,$wlalto1,'PERSONA EN TURNO',0,0,'L',0);
		$pdf->Cell(51,$wlalto1,'TIPO DE TRAMITE',0,0,'L',0);
		$pdf->Cell(0,$wlalto1,'PENDIENTES',0,1,'L',0);
		$pdf->Ln(1);
	}
	$row=pg_fetch_array($sql_result,$i);
	$pdf->Cell(20,$wlalto2,$row['fecharecibo'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(120,$wlalto2,substr ($row['pst'],0,80),'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(110,$wlalto2,$row['nombre_completo'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(50,$wlalto2,$row['tt'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(0,$wlalto2,$row['reg'],'BR',1,'C',1);
	$pdf->Ln(1);
}
$pdf->AddPage();

$row=pg_fetch_array($sql_result2,$i);
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,$wlalto1,'Fecha de recepcion:',0,0,'L',0);
$pdf->Cell(40,$wlalto1,'Fecha de turno:',0,0,'L',0);
$pdf->Cell(40,$wlalto1,'Estatus:',0,0,'L',0);
$pdf->Cell(40,$wlalto1,'Filtro de relevantes:',0,1,'L',0);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(40,4,$fecharecibo.' - '.$fecharecibofin,'LB',0,'C',0);
$pdf->Cell(40,4,($fechat=='' ? 'SIN DEFINIR' : $fechat ),'LB',0,'C',0);
$pdf->Cell(40,4,($estatus=='' ? 'SIN DEFINIR' : $row['desestatus']),'LB',0,'C',0);
$pdf->Cell(40,4,($relvante=='t' ? 'SI' : 'NO'),'LB',0,'C',0);
$pdf->Cell(0,4,'Hoja con detalle','LB',1,'R',0);
$pdf->Ln(3);
$pdf->SetFont('Arial','I',$wlsize1);
$pdf->Cell(10,$wlalto1,'NO.',0,0,'C',0);
$pdf->Cell(13,$wlalto1,'FOLIO',0,0,'C',0);
$pdf->Cell(36,$wlalto1,'OFICIO',0,0,'C',0);
$pdf->Cell(51,$wlalto1,'DE',0,0,'C',0);
$pdf->Cell(51,$wlalto1,'PARA',0,0,'C',0);
$pdf->Cell(21,$wlalto1,'FECHA DE DOCUMENTO',0,0,'C',0);
$pdf->Cell(16,$wlalto1,'DIAS DE TERMINO',0,0,'C',0);
$pdf->Cell(31,$wlalto1,'FECHA DE TURNO',0,0,'C',0);
$pdf->Cell(86,$wlalto1,'PUESTO',0,0,'C',0);
$pdf->Cell(0,$wlalto1,'TIPO DE TRAMITE',0,1,'C',0);
$pdf->Ln(1);

// :::: inicio del contenido del documento ::::

$fechar1='';
$num= pg_numrows ($sql_result2);	
for ($i=0; $i<$num; $i++)
{
	if ($pdf->GetY()>180)
	{
		$pdf->AddPage();
		$row=pg_fetch_array($sql_result2,$i);
		$pdf->SetFont('Arial','',4);
		$pdf->Cell(40,$wlalto1,'Fecha de recepcion:',0,0,'L',0);
		$pdf->Cell(40,$wlalto1,'Fecha de turno:',0,0,'L',0);
		$pdf->Cell(40,$wlalto1,'Estatus:',0,0,'L',0);
		$pdf->Cell(40,$wlalto1,'Filtro de relevantes:',0,1,'L',0);
		$pdf->SetFont('Arial','B',5);
		$pdf->Cell(40,4,$fecharecibo.' - '.$fecharecibofin,'LB',0,'C',0);
		$pdf->Cell(40,4,($fechat=='' ? 'SIN DEFINIR' : $fechat ),'LB',0,'C',0);
		$pdf->Cell(40,4,($estatus=='' ? 'SIN DEFINIR' : $row['desestatus']),'LB',0,'C',0);
		$pdf->Cell(40,4,($relvante=='t' ? 'SI' : 'NO'),'LB',0,'C',0);
		$pdf->Cell(0,4,'Hoja con detalle','LB',1,'R',0);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','I',$wlsize1);
		$pdf->Cell(10,$wlalto1,'NO.',0,0,'C',0);
		$pdf->Cell(13,$wlalto1,'FOLIO',0,0,'C',0);
		$pdf->Cell(36,$wlalto1,'OFICIO',0,0,'C',0);
		$pdf->Cell(51,$wlalto1,'DE',0,0,'C',0);
		$pdf->Cell(51,$wlalto1,'PARA',0,0,'C',0);
		$pdf->Cell(21,$wlalto1,'FECHA DE DOCUMENTO',0,0,'C',0);
		$pdf->Cell(16,$wlalto1,'DIAS DE TERMINO',0,0,'C',0);
		$pdf->Cell(31,$wlalto1,'FECHA DE TURNO',0,0,'C',0);
		$pdf->Cell(86,$wlalto1,'PUESTO',0,0,'C',0);
		$pdf->Cell(0,$wlalto1,'TIPO DE TRAMITE',0,1,'C',0);
		$pdf->Ln(1);
	}
	$row=pg_fetch_array($sql_result2,$i);
	$fechar2=$row['fecharecibo'];
	if ($fechar1!=$fechar2 || $hoja1!=$hoja2)
	{
		
		$fechar1=$fechar2;
		$pdf->SetFont('Arial','BI',$wlsize2);
		$pdf->Cell(0,$wlalto1,'FOLIOS CON FECHA DE RECEPCION: '.$row['fecharecibo'],'B',1,'C',0);		
		$pdf->Ln(1);
	}
	$pdf->SetFont('Arial','I',$wlsize2);
	$pdf->Cell(10,$wlalto2,$i+1,'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(13,$wlalto2,$row['folio'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->SetFont('Arial','I',$wlsize2);
	$pdf->Cell(35,$wlalto2,$row['referencia'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(50,$wlalto2,$row['nombre_completo_ev'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(50,$wlalto2,$row['nombre_completo_re'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(20,$wlalto2,$row['fechadocumento'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	/*$pdf->Cell(20,$wlalto2,$row['fecharecibo'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);*/
	$pdf->Cell(15,$wlalto2,$row['diastermino'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(30,$wlalto2,$row['fecha_altat'],'BR',0,'C',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(85,$wlalto2,substr ($row['pst'],0,55),'BR',0,'L',1);
	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	//$pdf->Cell(65,$wlalto2,$row['nombre_completo'],'BR',0,'C',1);
	//$pdf->Cell(1,$wlalto2,'',0,0,0,0);
	$pdf->Cell(0,$wlalto2,$row['tt'],'BR',0,'C',1);
	$pdf->Cell(0,$wlalto2,'',0,1,'L',0);
	$pdf->Ln(1);
	$pdf->SetFont('Arial','I',$wlsize1);
	$pdf->Cell(20,$wlalto2,'ASUNTO:',0,0,'C',0);
	$pdf->SetFont('Arial','I',$wlsize2);
	$pdf->MultiCell(0,$wlalto2,$row['ast'].": ".$row['asunto'],'BR','L',1);
	$pdf->Ln(1);
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