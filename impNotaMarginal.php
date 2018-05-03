<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
include('php-barcode.php');
include('num_a_letras.php');
include('NumberConverter.php');
$wlfolioconsecutivo=$_GET['wl_folioconsecutivo'];
##$wlfolioconsecutivo=$argv[1];
##$wlemail=$argv[2];
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
        $sql = " select * ".
		 "	from contra.v_certificacion ".
		 "	where folioconsecutivo=$wlfolioconsecutivo 	";
	//echo "<textarea>$sql</textarea>";die;
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de tramites"; die;}
	$row=pg_fetch_array($sql_result,0);

$wlfolio=str_replace("-","",$row["folio"]);
//str_replace("'","''",$this->argumentos["filtro"]).
$wlfecharecibo=$row["fecharecibo"];

$wlsize1=5;
$wlalto1=5;
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
require_once('WriteTag.php');

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
	$this->SetFont('Courier','',18);
	//$this->Image('img/decidiendojuntos.jpg',95,190,30,0);
        $this->Image('img/cdmx_02.png',10,14,0,0);
	//$this->Image('img/enm.jpg',85,120,50,0);
        //$code=str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."99";
	//$this->TextWithDirection(12,120,$code,'U');
        $this->Ln(25);
        $enca='<p>VOL. <vb>'.$row['vol'].'</vb>                <vb>'.$row['des_tipoprotocolo'].'</vb>                  '.'FOJA <vb>'.$row['escr_hojac'].'</vb></p>';
        $this->WriteTag(0,$wlalto1,$enca,0,'J');
        $this->Ln(5);
        $nn='0';
        if ($row['id_tipo_autorizacion']==2) {
                   $nn=$row['ade_not'];
        }
        if ($row['id_tipo_autorizacion']==1) {
                   $nn=$row['apr_not'];
        }
        if ($row['id_tipo_autorizacion']==3) {
                   $nn=$row['nota'];
        }
        if ($row['id_tipo_autorizacion']==4) {
                   $nn=$row['ade_not'];
        }

        $enca='<p>INSTRUMENTO: <vb>'.$row['escr'].'</vb>   NOTARIO: <vb>'.$nn.'</vb>  '.'FECHA ESCRITURA:<vb>'.$row['escr_fecha'].'</vb></p>';
        $this->WriteTag(0,$wlalto1,$enca,0,'J');
        $this->Ln(5);
        $wlstr='<p>NOTA MARGINAL NUMERO: '.'<vb>'.numtoletras($row['num_nota_marginal']).'</vb></p>';
	$this->WriteTag(0,$wlalto1,$wlstr,0,'J');
        $wlfechacerti='<p>FECHA: <vb>CIUDAD DE M�XICO A '.numtoletras(substr($row['fecha_certificacion'],8,2)).' DE '.strtoupper(meses_espanol(intval(substr($row['fecha_certificacion'],5,2)))).' DE '. numtoletras(substr($row['fecha_certificacion'],0,4)).'</vb><p>';
        $this->Ln(5);
	$this->WriteTag(0,$wlalto1,$wlfechacerti,0,'J');
        $wlparat='COMO SUBDIRECTOR DE ARCHIVO GENERAL DE NOTARIAS DE LA CIUDAD DE MEXICO, TITULAR DEL MISMO ARCHIVO, '; 
        $this->Ln(5);
        $wlplanos=($row['planos']!='' && $row['planos']>='2' ? ' Y <vb>'.numtoletras($row['planos'],1).'</vb> PLANOS ' : '');
        if ($row['planos']=='1') {
            $wlplanos=' Y <vb>'.numtoletras($row['planos'],1).'</vb> PLANO ';
        }

        if ($row['id_tipocopia']==3 ) 
        { 
          $wltexto='<vb>EXPIDO COPIA CERTIFICADA</vb> EN'; 
          $wlfojas='<vb> '.$row['fojas']."</vb> FOJAS �TILES".$wlplanos.".";
        };

        if ($row['id_tipocopia']==4 )
        {
          $wltexto='<vb>EXPIDO COPIA SIMPLE</vb> EN';
          $wlfojas='<vb> '.$row['fojas']."</vb> FOJAS �TILES".$wlplanos.".";
        };

        if ($row['id_tipocopia']==1) 
        {
          $a= new NumberConverter();
          $wltexto='<vb>EXPIDO '.strtoupper($a->convert($row['tes_orden'],"O")).' TESTIMONIO '.strtoupper($a->convert($row['tes_sol'],"O")).' PARA EL SOLICITANTE,</vb> EN ';
          $wlfojas='<vb>'.$row['fojas']."</vb> FOJAS �TILES".$wlplanos.".";
        };
        if ($row['id_tipocopia']==2)
        {
          $a= new NumberConverter();
          $wltexto='EL PRESENTE ES EL '.strtoupper($a->convert($row['drpp_primer'],"O")).' TESTIMONIO PARA EFECTOS DE INSCRIPCI�N EL EN REGISTRO P�BLICO DE LA PROPIEDAD, EN ';
          $wlfojas='<vb>'.$row['fojas']."</vb> FOJAS �TILES".$wlplanos.".";
        };
        //$wlstr='<p>'.$wlparat.$wltexto.$wlfojas.'</p>';
        //$this->WriteTag(0,$wlalto1,$wlstr,0,'J');
        //$wlpara='PARA: <vb>'.$row['para'].'</vb>';
	$this->SetFont('Courier','B',18);
        $wlstr='<p>'.$wlparat.$wltexto.$wlfojas.$wlpara.'</p>';
	$this->WriteTag(0,$wlalto1,$wlstr,0,'J');
        $this->Ln(5);
        $wlpara='<p>PARA: <vb>'.$row['para'].'</vb></p>';
	$this->WriteTag(0,$wlalto1,$wlpara,0,'J');
}

function Footer()
{
}
}

  $date = new DateTime();
  $code     = str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."90";
$pdf = new PDF ();
$pdf->Fpdf('P','mm','Letter');
$pdf->SetStyle("p","courier","N",15);
$pdf->SetStyle("vb","courier","B",15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);
$e=0;

  $x        = 170;  // barcode center
  $y        = 20;  // barcode center
  $inicioln = 20;
  $fontSize = 14;
  $marge    = -8;   // between barcode and hri in pixel
  $height   = 10;   // barcode height in 1D ; module size in 2D
  $width    = .4;    // barcode height in 1D ; not use in 2D
  $angle    = 0;
  $type     = 'code128';
  $black    = '000000'; // color in hexa
  $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
  //$pdf->SetFont('Arial','B',$fontSize);
  //$pdf->SetTextColor(0, 0, 0);
  $len = $pdf->GetStringWidth($data['hri']);
  Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
  $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
//Determinar un nombre temporal de fichero en el directorio actual
//$file=basename(tempnam(getcwd(),'tmp'));
$file="notamar_".$wlfolioconsecutivo;
//Determinar en nombre para el archivo pdf
$file1="temp/".$file;
$file1.='.pdf';
//Guardar el PDF en un fichero
$pdf->Output($file1);
//Borro archivo temporal
if(file_exists($file))
{unlink($file);}
//Redirecci�n con JavaScript
echo "	<html><script>	";
echo "		self.close();	";
echo "		document.location='$file1';	";
echo "	</script></html>";
?>
