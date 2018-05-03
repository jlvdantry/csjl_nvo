<?PHP
putenv("TZ=America/Mexico_City");
include("conneccion.php");
require_once("menudata.php");
  include('php-barcode.php');
$pix=.35;
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
	$sql="	select vtr.*".
                 "      ,(select descripcion from contra.cat_asuntos where id_cveasunto=vtr.id_cveasunto) as descripcion_asunto ".
		 "	from contra.gestion as vtr	".
		 "	where vtr.folioconsecutivo=$wlfolioconsecutivo order by vtr.fecha_alta asc;	";
	//echo "<textarea>$sql</textarea>";die;
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de gestion"; die;}
	$row=pg_fetch_array($sql_result,0);

      include("idformas.php");
      $me = new menudata();
      $me->connection=$connection;
      $me->idmenu=$repor[$row[id_cveasunto]];
      $me->damemetadata();


$wlpenvia=$row['nombre']." ".trim($row['apepat'])." ".trim($row['apemat']);
$wlrecibe=$row['persona_recibe'];
$wldesdocto=$row['descripcion_docto'];
$wldesasunto=$row['descripcion_asunto'];
$wlestatus=$row['descripcion_estatus'];
      foreach ($me->camposmc as $index => $val)
      {
       if ($index!="fecharecibo" && $index!="id_cveasunto" && $index!="folioconsecutivo"  && $index!="" && $index!="fecha_alta" && $index!="usuario_alta"  && $index!="apepat" && $index!="apemat" && $index!="val_lc"  && $index!="folio" && $row[$index]!="" && $index!="nombre" && $index!="idsexo"  && $index!="edad"  && $index!="condicionv"  && $index!="estatus"  && $index!="diastermino" && $index!="")
       {
          if ($index=="nombre") { $val["size"]="80"; }
          if ($index=="estatus") { $val["size"]="20"; }
          $wlasunto.=$val["descripcion"]." ".$row[$index].";";
       }
      }
//$wlasunto=$row['asunto'];
$wlfechadocumento=$row['fechadocumento'];
$wlfecharecibo=$row['fecharecibo'];
$wlfolio=$row['folio'];
$wloficio=$row['referencia'];
$wlfechaalta=$row['fecha_alta'];
$wlusuarioalta=$row['usuario_alta'];
$wldiastermino=$row['diastermino'];

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
$wlsetx2=150;

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
    function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
    {
        $font_angle+=90+$txt_angle;
        $txt_angle*=M_PI/180;
        $font_angle*=M_PI/180;

        $txt_dx=cos($txt_angle);
        $txt_dy=sin($txt_angle);
        $font_dx=cos($font_angle);
        $font_dy=sin($font_angle);

        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        if ($this->ColorFlag)
            $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
    }
}
$pdf = new PDF();
//$pdf->Fpdf('P','mm',array(214,93));
$pdf->Fpdf('P','pt',array(214/$pix,93/$pix));
$pdf->SetMargins(10/$pix,10/$pix,.1);
$pdf->SetAutoPageBreak(0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2/$pix);
  $fontSize = 8;
  $marge    = 0;   // between barcode and hri in pixel
  $x        = 220;  // barcode center
  $y        = 190;  // barcode center
  $height   = 25;   // barcode height in 1D ; module size in 2D
  $width    = 1;    // barcode height in 1D ; not use in 2D
  $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation

  $code     = str_replace("-","",$wlfolio).substr($wlfecharecibo,0,4)."00"; 
  $type     = 'code128';
  $black    = '000000'; // color in hexa


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
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Image('img/decidiendojuntos.jpg',17/$pix,11/$pix,20/$pix,0);
	$pdf->Image('img/cejur_01.JPG',180/$pix,11/$pix,20/$pix,0);

	$pdf->SetLineWidth(0.4/$pix);
	$pdf->Line(60/$pix,8/$pix,60/$pix,32/$pix);
	//$pdf->SetY(13);
	$pdf->cell(53/$pix,4/$pix,'',0,0,'L',0);
	$pdf->cell(0,4/$pix,$titulo1,0,1,'L',0);
	$pdf->cell(53/$pix,4/$pix,'',0,0,'L',0);	
	$pdf->cell(0,4/$pix,$titulo2,0,1,'L',0);
	$pdf->cell(53/$pix,4/$pix,'',0,0,'L',0);	
	$pdf->cell(0,4/$pix,$titulo3,0,1,'L',0);
	$pdf->cell(53/$pix,4/$pix,'',0,0,'L',0);	
	$pdf->cell(0,4/$pix,$titulo4,0,1,'L',0);
//	$pdf->Ln(1);
 $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);

  $pdf->SetFont('Arial','B',$fontSize);
  $pdf->SetTextColor(0, 0, 0);
  $len = $pdf->GetStringWidth($data['hri']);
  Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
  //$pdf->SetY(13);
  $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);

	
	$pdf->SetLineWidth(0.2/$pix);
	$pdf->SetFillColor(238,242,247);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',$wlsize2);
    $pdf->SetFont('Arial','B',9);
	$pdf->Cell(0,6/$pix,'BOLETA DE INGRESO A TRAMITES','B',1,'C',0);
	if ($relevante=='t') {	$pdf->Cell(0,6,'TRAMITE RELEVANTE',0,1,'C',0);	}
	//$pdf->Image((($wladjuntos>0) ? 'img/attachIcon.jpg' : 'img/fail.jpg'),188,30,5,0);
	
	$pdf->Ln(2);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
        $pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'TIPO DE TRAMITE',0,0,'L',0);
	$pdf->SetX(115/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'SOLICITANTE',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wldesasunto,'BR',0,'C',1);
	$pdf->SetX(115/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wlpenvia,'BR',1,'C',1);
	
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'FECHA DE RECEPCION',0,0,'L',0);
	$pdf->SetX(115/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'FOLIO DE RECEPCION',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wlfecharecibo,'BR',0,'C',1);
	
	$pdf->SetX(115/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wlfolio,'BR',1,'C',1);
	
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'OBSERVACION',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',10);
	$pdf->multiCell($wllargo2/$pix,$wlalto2/$pix,$wlasunto,'BR','C',1);
	global $parametro1;
	global $wldiastermino;
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
	
	
	$pdf->SetY(-25/$pix);
	
	//$pdf->SetX($wlsetx);
	//$pdf->SetFont('Arial','B',$wlsize2);
	//$pdf->Cell(120,$wlalto2,'FIRMA',0,0,'C',0);
	
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'PLAZO DE RESPUESTA',0,1,'L',0);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
        if ($wldiastermino==0) {$wldiastermino="Inmediata";} else {$wldiastermino=$wldiastermino." dias habiles"; }
	$pdf->Cell($wllargo2c/$pix,$wlalto2/$pix,$wldiastermino,'BR',1,'C',1);
	
	$pdf->Ln(4);
    //Go to 1.5 cm from bottom
    //$pdf->SetY(-12);
    //Select Arial italic 8
    $pdf->SetFont('Arial','I',6);
    //Print current and total page numbers

	//$pdf->Image('img/logo3_2007.JPG',8,260,12,0);
	//$pdf->Image('img/logo2_2007_color.JPG',197,260,12,0);
	$pdf->Setx(25/$pix);
	$pdf->Cell(25/$pix,6/$pix,'Fecha de emisiòn: ','LBT',0,'R',1);
	$pdf->Cell(30/$pix,6/$pix,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$pdf->Cell(25/$pix,6/$pix,'Hora de emisiòn: ','BT',0,'R',1);
	$pdf->Cell(15/$pix,6/$pix,date("g:i:s a,"),'BT',0,'L',1);
	$pdf->Cell(35/$pix,6/$pix,'Usuario de emisión: ','BT',0,'R',1);
	$pdf->Cell(25/$pix,6/$pix,$parametro1.',','BT',0,'L',1);
	$pdf->Cell(15/$pix,6/$pix,'Pagina '.$pdf->PageNo().'/{nb}','BTR',1,'C',1);
	$pdf->Ln(1);
	$pdf->Setx(25/$pix);
	$pdf->Cell(170/$pix,6/$pix,'Consulta de tramite al tel. 55-22-51-40 55-22-51-18 Ext. 109 y 113 de 9:00 a 15:00 de lunes a viernes',0,0,'C',1);



$e=0;

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
