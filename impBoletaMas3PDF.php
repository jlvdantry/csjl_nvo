<?PHP
putenv("TZ=America/Mexico_City");
include("conneccion.php");
require_once("menudata.php");
include('php-barcode.php');
require_once('WriteTag.php');
##print_r($_GET); die();
$pix=1;
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
	$titulo4='J.U.D DE VENTANILLA ÚNICA';;
	$firma_n=$row['nombre_completo'];
	$firma_p=$row['puesto'];
	
	// Genera detalle del reporte
	if (empty($_GET['id_cveasunto'])) {echo "El tramite no esta definido"; die;}
	if (empty($_GET['fecharecibo'])) {echo "La fecha de recepcion no esta definido"; die;}
	$sql="	select vtr.*".
                 "      ,(select descripcion from contra.cat_asuntos where id_cveasunto=vtr.id_cveasunto) as descripcion_asunto ".
                 "      ,(select descripcion from contra.cat_condicionv where condicionv=vtr.condicionv) as descripcion_condicionv ".
		 "	from contra.gestion as vtr	".
		 "	where vtr.id_cveasunto=".$_GET['id_cveasunto']." and vtr.fecharecibo='".$_GET['fecharecibo']."'".
                 ($_GET['wl_folioconsecutivo']!="" ? " and folioconsecutivo=".$_GET['wl_folioconsecutivo'] : " ").
                 " order by vtr.folio asc;	";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "No hay movimiento a imprimir las boletas"; die;}

      include("idformas.php");
      $me = new menudata();
      $me->connection=$connection;
      $me->idmenu=$repor[$_GET['id_cveasunto']];
      $me->damemetadata();
define('FPDF_FONTPATH','font/');
##require('fpdf.php');

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

class PDF extends PDF_WriteTag
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
$wlsize2=7;
$wlsize3=13;
$pdf = new PDF();
$pdf->Fpdf('P','mm','Letter');
$pdf->SetStyle("p",'courier',"N",9,'0,0,0');
$pdf->SetStyle("vb","courier",'n',12,'0,100,100');
$pdf->SetMargins(10,10,10);
$pdf->SetAutoPageBreak(0);
$pdf->AddPage();

  $copia=0;
  $inicioln =5;

      for ($z=0; $z < $num ;$z++)
      {
	$row=pg_fetch_array($sql_result,$z);

$wlpenvia=$row['nombre']." ".trim($row['apepat'])." ".trim($row['apemat']);
$wlrecibe=$row['persona_recibe'];
$wldesdocto=$row['descripcion_docto'];
$wldesasunto=$row['descripcion_asunto'];
$wlestatus=$row['descripcion_estatus'];
$row['condicionv']=$row['descripcion_condicionv'];
$wlasunto="";
      foreach ($me->camposmc as $index => $val)
      {
       if ($index!="fecharecibo" && $index!="id_cveasunto" && $index!="folioconsecutivo"  && $index!="" && $index!="fecha_alta" && $index!="usuario_alta"  && $index!="apepat" && $index!="apemat" && $index!="val_lc"  && $index!="folio" && $row[$index]!="" && $index!="nombre" && $index!="idsexo"  && $index!="edad"  && $index!="estatus"  && $index!="diastermino" && $index!="")
       {
          if ($index=="nombre") { $val["size"]="80"; }
          if ($index=="estatus") { $val["size"]="20"; }
          if (substr(trim($row[$index]),0,9)!=="sincorreo") {
          $wlasunto.=$val["descripcion"]." ".$row[$index].";";
          ##$wlasunto.=$val["descripcion"]." ".substr(trim($row[$index]),1,9).";";
          }
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
$wlalto1=5;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tamaño de letra
// alto de celda
$wlalto2=5;
// largo de celdas
$wllargo2=195;
$wllargo2b=85;
$wllargo2c=50;

// :::: valor para establecer las abscisas de la celdas ::::
//$wlsetx=20;
//$wlsetx2=150;
$wlsetx=25;
$wlsetx2=120;

//$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);
  $fontSize = 7;

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
	$pdf->SetFont('Arial','B',8);
        $x        = 165;  // barcode center
        $y        = 8;  // barcode cente
        $marge    = 0;   // between barcode and hri in pixel
        $height   = 10;   // barcode height in 1D ; module size in 2D
        $width    = .4;    // barcode height in 1D ; not use in 2D
        $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
        
        $data = Barcode::fpdf($pdf, $black, $pdf->GetX()+$x, $pdf->GetY()+$y, $angle, $type, array('code'=>$code), $width, $height);

        $pdf->SetFont('Arial','B',$fontSize);
        $pdf->SetTextColor(0, 0, 0);
        $len = $pdf->GetStringWidth($data['hri']);
        Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
        $pdf->TextWithRotation($pdf->GetX() + $xt + $x, $pdf->GetY() + $yt + $y -5 , $data['hri'], $angle);

	$pdf->Image('img/cdmx_03.png',$wlsetx/$pix,$pdf->GetY()+7,15/$pix,0);
	$pdf->SetLineWidth(0.4/$pix);
	##$pdf->Line(47/$pix,8/$pix,47/$pix,27/$pix);
        
        $pdf->ln(2);
	$pdf->cell(30/$pix,4/$pix,'',0,0,'L',0);
	$pdf->cell(0,4/$pix,$titulo1,0,1,'L',0);
	$pdf->cell(30/$pix,4/$pix,'',0,0,'L',0);	
	$pdf->cell(0,4/$pix,$titulo2,0,1,'L',0);
	$pdf->cell(30/$pix,4/$pix,'',0,0,'L',0);	
	$pdf->cell(0,4/$pix,$titulo3,0,1,'L',0);
	$pdf->cell(30/$pix,4/$pix,'',0,0,'L',0);	
	$pdf->cell(0,4/$pix,$titulo4,0,1,'L',0);

        $data = Barcode::fpdf($pdf, $black, 10, $pdf->GetY()+$y+15 , 90, $type, array('code'=>$code), .4, $height);
        Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
        $pdf->TextWithRotation(19  , $pdf->GetY()+y+40 , $data['hri'], 90);

	
	$pdf->SetLineWidth(0.2/$pix);
	$pdf->SetFillColor(238,242,247);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',$wlsize2);
    $pdf->SetFont('Arial','B',9);
	$pdf->SetX($wlsetx/$pix);
	$pdf->Cell(0,6/$pix,'BOLETA DE INGRESO A TRAMITES DEL ARCHIVO GENERAL DE NOTARÍAS',0,1,'C',0);
	if ($relevante=='t') {	$pdf->Cell(0,6,'TRAMITE RELEVANTE',0,1,'C',0);	}
	
	$pdf->Ln(2);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
        ##$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'TIPO DE TRAMITE',0,0,'L',0);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	##$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'SOLICITANTE',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,'TIPO DE TRAMITE: '.$wldesasunto,'BR',0,'L',1);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell(0,$wlalto2/$pix,'SOLICITANTE: '.$wlpenvia,'BR',1,'L',1);
	$pdf->SetX($wlsetx/$pix);
        $pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,'FECHA DE INGRESO: '.$wlfecharecibo,'BR',0,'L',1);
	
	$pdf->SetX($wlsetx2/$pix);
	##$pdf->SetFont('Arial','B',20);
        ##$wlfolio='<p>FOLIO DE RECEPCIÓN: <vb>'.$wlfolio.'</vb></p>';
	##$pdf->WriteTag(0,$wlalto2/$pix,$wlfolio,'BR',1,'L',1);
        $pdf->Cell(30,$wlalto2/$pix,'FOLIO DE RECEPCIÓN: ','B',0,'L',1);
	$pdf->SetFont('Arial','B',$wlsize3);
        $pdf->Cell(0,$wlalto2/$pix,$wlfolio,'BR',1,'L',1);
	$pdf->SetFont('Arial','B',$wlsize2);
	
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'OBSERVACION',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',10);
	$pdf->multiCell(0,$wlalto2/$pix,$wlasunto,'BR','C',1);
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
	
	
        $pdf->Ln(8);
	
	//$pdf->SetX($wlsetx);
	//$pdf->SetFont('Arial','B',$wlsize2);
	//$pdf->Cell(120,$wlalto2,'FIRMA',0,0,'C',0);
	
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	##$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'PLAZO DE RESPUESTA',0,1,'L',0);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
        ##if ($wldiastermino==0) {$wldiastermino="Inmediata";} else {$wldiastermino=$wldiastermino." dias habiles"; }
        if ($row["id_cveasunto"]==16 || $row["id_cveasunto"]==17) {$wldiastermino="15";} else {$wldiastermino="25"; }
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,'PLAZO DE RESPUESTA: '.$wldiastermino.' días habiles','BR',1,'C',1);
	
	$pdf->Ln(1);
        $pdf->SetFont('Arial','I',6);

	$pdf->SetX($wlsetx/$pix);
	$pdf->Cell(25/$pix,6/$pix,'Fecha de emisiòn: ','LBT',0,'R',1);
	$pdf->Cell(30/$pix,6/$pix,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$pdf->Cell(25/$pix,6/$pix,'Hora de emisiòn: ','BT',0,'R',1);
	$pdf->Cell(15/$pix,6/$pix,date("g:i:s a,"),'BT',0,'L',1);
	$pdf->Cell(35/$pix,6/$pix,'Usuario de emisión: ','BT',0,'R',1);
	$pdf->Cell(25/$pix,6/$pix,$parametro1.',','BT',0,'L',1);
	$pdf->Cell(0,6/$pix,'Pagina '.$pdf->PageNo(),'BTR',1,'C',1);
	$pdf->Ln(1);
	$pdf->Setx($wlsetx/$pix);
	$pdf->Cell(0,6/$pix,'Consulta tu tramite en http://data.consejeria.cdmx.gob.mx/index.php/dgjel/tramite o al tel. 55-22-51-40 Ext. 113 de 9:00 a 15:00 dias habiles',0,1,'C',0);
        $copia=$copia+1;
        if ($copia<3) {
           $pdf->SetLineWidth(1);
           $pdf->Line(0,$pdf->GetY(),$pdf->GetX()+300,$pdf->GetY()); 
              $z=$z-1;
              $inicioln =100; }
        else {
        $pdf->SetFont('Arial','I',10);
	$pdf->Cell(0,6/$pix,'SOLICITANTE',0,1,'R',0);
              //$pdf->AddPage();
              $copia=0;
        }
/*
        if ($copia<3) { $z=$z-1;  
                         $inicioln =100;

                       }
*/
     }

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
