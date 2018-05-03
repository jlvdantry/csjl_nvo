<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
include('php-barcode.php');
	// Genera titulos
$wlfolioconsecutivo=$_GET['wl_folioconsecutivo'];
##$wlfolioconsecutivo=$argv[1];
$wlemail=$argv[2];
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
        $sql = " select a.* ".
               " ,(select descripcion from contra.cat_estatus as ce where ce.estatus=a.estatus) as desestatus ".
               ",(select fecha_modifico from contra.ope_turnados ot where ot.folioconsecutivo=a.folioconsecutivo and id_tipotra=16 order by fecha_alta desc limit 1) as diacaratula ".
               ",(select observacion from contra.ope_turnados ot where ot.folioconsecutivo=a.folioconsecutivo and id_tipotra=16 order by fecha_alta desc limit 1) as desasunto ".
               " from ( ".
	       "	select tr.*	".
		 "	from contra.gestion tr  ".
		 "	where folioconsecutivo=$wlfolioconsecutivo ) a	";
	//echo "<textarea>$sql</textarea>";die;
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de tramites"; die;}
	$row=pg_fetch_array($sql_result,0);

$wlfolio=str_replace("-","",$row["folio"]);
//str_replace("'","''",$this->argumentos["filtro"]).
$wldesasunto=$row["desasunto"];
$wldiacaratula=$row["diacaratula"];
$wlfecharecibo=$row["fecharecibo"];

$wlsize1=5;
$wlalto1=3;
$wllargo1=50;

$wlsize2=17;
$wlalto2=5;
$wllargo2=180;
$wllargo2b=85;
$wllargo2c=50;

// :::: valor para establecer las abscisas de la celdas ::::
$wlsetx=20;
$wlsetx2=150;

define('FPDF_FONTPATH','font/');
require('circulartext.php');

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

class PDF extends PDF_CircularText
{
function TextWithDirection($x, $y, $txt, $direction='R')
{
    if ($direction=='R')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    elseif ($direction=='L')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    elseif ($direction=='U')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    elseif ($direction=='D')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    else
        $s=sprintf('BT %.2F %.2F Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    if ($this->ColorFlag)
        $s='q '.$this->TextColor.' '.$s.' Q';
    $this->_out($s);
}
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

function Header()
{
        global $code;
	global $titulo1;
	global $titulo2;
	global $titulo3;
	global $titulo4;
	global $wldesdocto;
	global $wlestatus;
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
	global $wlnombre;
	global $wldiacaratula;
	global $wldesasunto;
	global $row;
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',25);
	//$this->Image('img/decidiendojuntos.jpg',95,190,30,0);
	$this->Image('img/cdmx_01.png',40,190,0,0);
	$this->Image('img/enm.jpg',85,120,50,0);
        $code=str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."99";
	$this->TextWithDirection(12,120,$code,'U');
	$this->SetFont('Arial','B',20);
	$this->SetLineWidth(0.4);
	$this->SetY(10);
	$this->cell(42,4,'',0,0,'L',0);
	$this->CircularText(110,130,90,'CIUDAD DE MÉXICO','top',100,260);
	$this->cell(42,4,'',0,0,'L',0);	
	$this->CircularText(110,140,80,$titulo3,'top');
	$this->cell(42,4,'',0,0,'L',0);	
	$this->CircularText(110,140,40,$titulo4,'top');

	//$this->Ln(1);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',$wlsize2);
        $this->SetFont('Arial','B',9);
	
	
        $this->SetY(270);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo1,$wlalto1,'ASUNTO:',0,1,'L',0);
	$this->Ln(1);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,$wldesasunto,'BR','C',1);

        $this->SetFont('Arial','I',$wlsize2);
        $this->SetY(308);
        $this->Setx($wlsetx);
        $this->Cell($wllargo2,10,"México,".substr($wldiacaratula,8,2)." de ".meses_espanol(intval(substr($wldiacaratula,5,2)))." de ".substr($wldiacaratula,0,4) ,'LBTR',0,'C',1);
}

function Footer()
{
}
}

  $date = new DateTime();
  $code     = str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."99";
$pdf = new PDF ();
$pdf->Fpdf('P','mm',array(215,340));
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);
$e=0;

  $x        = 170;  // barcode center
  $y        = 20;  // barcode center
  $inicioln =20;
  $fontSize = 14;
  $marge    = -8;   // between barcode and hri in pixel
  $height   = 10;   // barcode height in 1D ; module size in 2D
  $width    = .4;    // barcode height in 1D ; not use in 2D
  $angle    = 0;
  $type     = 'code128';
  $black    = '000000'; // color in hexa
  $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
  $pdf->SetFont('Arial','B',$fontSize);
  $pdf->SetTextColor(0, 0, 0);
  $len = $pdf->GetStringWidth($data['hri']);
  Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
  $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);

//Determinar un nombre temporal de fichero en el directorio actual
//$file=basename(tempnam(getcwd(),'tmp'));
$file="caratula_".$wlfolioconsecutivo;
//Determinar en nombre para el archivo pdf
$file1="temp/".$file;
$file1.='.pdf';
//Guardar el PDF en un fichero
$pdf->Output($file1);
//Borro archivo temporal
//Redirección con JavaScript
echo "	<html><script>	";
echo "		self.close();	";
echo "		document.location='$file1';	";
echo "	</script></html>";
##if(file_exists($file1))
##{unlink($file1);}

?>
