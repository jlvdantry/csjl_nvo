<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
include('php-barcode.php');
include('num_a_letras.php');
include('NumberConverter.php');
$wlfolioconsecutivo=$_GET['wl_folioconsecutivo'];
##$wlfolioconsecutivo=$argv[1];
	$sql = "select * from contra.v_titulos ";
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
	
	if (empty($wlfolioconsecutivo)) {echo "El folio consecutivo no esta definido"; die;}
        $sql = " select * ".
		 "	from contra.v_certificacion ".
		 "	where folioconsecutivo=$wlfolioconsecutivo 	";
	$sql_result = pg_exec($connection,$sql);
        echo "ejecuto sql";
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); die(); }
        echo "paso erro";
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de tramites"; die;}
	$row=pg_fetch_array($sql_result,0);
   echo "Paso lectura";

$wlfolio=str_replace("-","",$row["folio"]);
$wlfecharecibo=$row["fecharecibo"];

$wlsize1=5;
$wlalto1=7;
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
require_once('WriteTag_x.php');
   echo "Paso requiere";

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
	global $row;
	$this->SetTextColor(0);
	$this->Image('img/cdmx_02.png',10,14,0,0);
        $this->Ln(25);
        $enca='<p>VOL. <vb>'.$row['vol'].'</vb>                <vb>'.$row['des_tipoprotocolo'].'</vb>                  '.'FOJA <vb>'.$row['escr_hojac'].'</vb></p>';
        $this->WriteTag(0,$wlalto1,$enca,0,'J');
        $wlplanos=($row['planos']!='' && $row['planos']>='2' ? ' Y <vb>'.numtoletras($row['planos'],1).'</vb> PLANOS ' : '');
        if ($row['planos']=='1') {
            $wlplanos=' Y <vb>'.numtoletras($row['planos'],1).'</vb> PLANO ';
        } 
        if ($row['id_tipocopia']==3 || $row['id_tipocopia']==4) 
        { 
          $wltexto='LA PRESENTE ES COPIA CERTIFICADA DEL INSTRUMENTO NOTARIAL NUMERO '; 
          $wlfojast='LA PRESENTE COPIA VA EN ';
          $wlfojasut=" FOJAS ÚTILES ".$wlplanos." CON LOS REQUISITOS LEGALES";
          $wlfojas=numtoletras($row['fojas'],1);
        };
        if ($row['id_tipocopia']==1) 
        {
          $a= new NumberConverter();
          $wltexto='EL PRESENTE ES EL '.strtoupper($a->convert($row['tes_orden'],"O")).' TESTIMONIO '.strtoupper($a->convert($row['tes_sol'],"O")).' PARA EL SOLICITANTE DEL INSTRUMENTO NOTARIAL NUMERO ';
          $wlfojast='EL PRESENTE TESTIMONIO VA EN ';
          $wlfojasut=" FOJAS ÚTILES ".$wlplanos." CON LOS REQUISITOS LEGALES";
          $wlfojas=numtoletras($row['fojas'],1);
        };
        if ($row['id_tipocopia']==2)
        {
          $a= new NumberConverter();
          $wltexto='EL PRESENTE ES EL '.strtoupper($a->convert($row['drpp_primer'],"O")).' TESTIMONIO PARA EFECTOS DE INSCRIPCIÓN EL EN REGISTRO PÚBLICO DE LA PROPIEDAD '.strtoupper(utf8_decode($a->convert($row['drpp_orden'],"O"))).' EN SU ORDEN DEL INSTRUMENTO NOTARIAL NUMERO ';
          $wlfojast='EL PRESENTE TESTIMONIO VA EN ';
          $wlfojasut=" FOJAS ÚTILES ".$wlplanos." CON LOS REQUISITOS LEGALES";
          $wlfojas=numtoletras($row['fojas'],8,2);
        };
        $this->Ln(5);
        $wlescri=numtoletras($row['escr'],1);
        $wltexto='<p>'.$wltexto.'<vb>'.$wlescri.' -- </vb></p>';
	$this->WriteTag(0,$wlalto1,$wltexto,0,'J',0,0,3);
        $wlfechal=numtoletras(substr($row['escr_fecha'],8,2)).' DE '.strtoupper(meses_espanol(intval(substr($row['escr_fecha'],5,2)))).' DE '. numtoletras(substr($row['escr_fecha'],0,4),1);
        $wlfecha=trim($wlfechal);
        $wlpad="";
        $wlfecha='<p>DE FECHA <vb>'.$wlfecha.' -- </vb></p>';
        //$wlfecha='<p>DE FECHA <vb>'.$wlfecha.'</vb></p>';
        $this->Ln(1);
	$this->WriteTag(0,$wlalto1,$wlfecha,0,'J',0,0,3);
        if ($row['observaciones']=='')
        { $wlcoteja='COTEJADA CON SU ORIGINAL MISMO QUE SE ENCUENTRA '; }
        else
        { $wlcoteja='COTEJADA CON SU ORIGINAL MISMO QUE SE ENCUENTRA ';
          $wlencuentra='<vb>'.$row['observaciones'].'</vb>'; }
	//$this->WriteTag(0,$wlalto1,$wlcoteja,0,'J');
        $y="";

        if ($row['id_tipo_autorizacion']=='1' ) 
        { 
          $wlencuentra='<vb>AUTORIZADO PREVENTIVAMENTE</vb> POR EL NOTARIO NUMERO ';
          if (($row['apr_asociado']=='0' || $row['apr_asociado']=='') && ($row['apr_suplente']=='0' || $row['apr_suplente']==''))
          {
             $wlnota='<vb>'.numtoletras($row['apr_not']).'</vb> DE LA CIUDAD DE MÉXICO, LICENCIADO <vb>'.$row['apr_nombre'].' --</vb>';
          }
          if ($row['apr_asociado']!='0' && $row['apr_asociado']!='')
          {
             $wlnota='<vb>'.numtoletras($row['apr_asociado']).'</vb>, LICENCIADO <vb>'.$row['apr_asociadonombre'].'</vb> ACTUANDO COMO ASOCIADO EN EL PROTOCOLO DEL NOTARIO <vb>'.numtoletras($row['apr_not']).' --';
          }
          if ($row['apr_suplente']!='0' && $row['apr_suplente']!='')
          {
             $wlnota='<vb>'.str_pad(numtoletras($row['apr_suplente']).'</vb>, LICENCIADO <vb>'.$row['apr_suplentenombre'].'</vb> ACTUANDO COMO SUPLENTE EN EL PROTOCOLO DEL NOTARIO <vb>'.numtoletras($row['apr_not']),263,' -');
          }
        }

        if ($row['id_tipo_autorizacion']=='2') 
        { 
          $wlencuentra='<vb>AUTORIZADO DEFINITIVAMENTE</vb> POR EL NOTARIO NUMERO '; 
          if (($row['ade_asociado']=='0' || $row['ade_asociado']=='') && ($row['ade_suplente']=='0' || $row['ade_suplente']==''))
          {
             $wlnota='<vb>'.numtoletras($row['ade_not']).'</vb> DE LA CIUDAD DE MÉXICO, LICENCIADO <vb>'.$row['ade_nombre'].' --';
          }
          if ($row['ade_asociado']!='0' && $row['ade_asociado']!='')
          {
             $wlnota='<vb>'.numtoletras($row['ade_asociado']).'</vb>, LICENCIADO <vb>'.$row['ade_asociadonombre'].'</vb> ACTUANDO COMO ASOCIADO EN EL PROTOCOLO DEL NOTARIO <vb>'.numtoletras($row['ade_not']).' --';
          }
          if ($row['ade_suplente']!='0' && $row['ade_suplente']!='')
          {
             $wlnota='<vb>'.numtoletras($row['ade_suplente']).'</vb>, LICENCIADO <vb>'.$row['ade_suplentenombre'].'</vb> ACTUANDO COMO SUPLENTE EN EL PROTOCOLO DEL NOTARIO <vb>'.numtoletras($row['ade_not']).' --';
          }
        }
        $this->Ln(1);
        $wlnota='<p>'.$wlcoteja.$wlencuentra.$wlnota.'</p>';
        $this->WriteTag(0,$wlalto1,$wlnota,0,'J',0,0,5); 
	$this->SetFont('Courier','',14);
        $wlparat='MISMO QUE OBRA DEPOSITADO EN EL ARCHIVO GENERAL DE NOTARÍAS DE LA CIUDAD DE MÉXICO, Y SE EXPIDE EN USO DE LA FACULTAD QUE ME CONFIEREN LOS ARTÍCULOS DOSCIENTOS TREINTA Y OCHO FRACCIONES V Y VI, DOSCIENTOS TREINTA Y NUEVE Y DOSCIENTOS CUARENTA Y CUATRO DE LA LEY DEL NOTARIADO VIGENTE PARA EL DISTRITO FEDERAL, COMO SUBDIRECTOR DE ARCHIVO GENERAL DE NOTARIAS DE LA CIUDAD DE MÉXICO, TITULAR DEL MISMO ARCHIVO PARA: ';
	//$this->Justify($wlpara,200,10);
	//$this->WriteTag(0,$wlalto1,$wlparat,0,'J');
        $wlpara=$row['para'];
        $this->Ln(1);
        $wlpara='<p>'.$wlparat."<vb>".$wlpara." --</vb></p>";
	$this->WriteTag(0,$wlalto1,$wlpara,0,'J',0,0,18);
	//$this->MultiCell(0,$wlalto1,$wlfojast,0,'J');
        $this->Ln(1);
        $wlpad="";
        $wlfojas='<p>'.$wlfojast."<vb>".$wlfojas."</vb>".$wlfojasut.$wlpad.'<vb> --</vb></p>';
	$this->WriteTag(0,$wlalto1,$wlfojas.$wlpad,0,'J',0,0,2);
}

function Footer()
{
        global $wlalto1;
        global $row;
        $this->SetY(-75);
	$this->SetFont('Courier','',14);
        $wlfechacerti='<p>'.'CIUDAD DE MÉXICO A <vb>'.numtoletras(substr($row['fecha_certificacion'],8,2)).' DE '.strtoupper(meses_espanol(intval(substr($row['fecha_certificacion'],5,2)))).' DE '. numtoletras(substr($row['fecha_certificacion'],0,4)).'. --</vb></p>';
        $this->Ln(1);
        $this->WriteTag(0,$wlalto1,$wlfechacerti,0,'J',0,0,2);
        $this->Ln(1);
        $firman='SUBDIRECTOR DE ARCHIVO GENERAL DE'."\n"."NOTARIAS DE LA CIUDAD DE MÉXICO, TITULAR DEL MISMO"."\n\n\n"."LIC. HIRAM GONZALEZ MAYA";
        $firmaj='J.U.D. DE CALIFICACIONES'."\n".'CERTIFICACIONES Y TESTAMENTOS'."\n\n\n\n"."LIC. FERNANDO SOTO FERNÁNDEZ";
        $suple='EN SUPLENCIA POR AUSENCIA DEL SUBDIRECTOR DE ARCHIVO GENERAL DE NOTARIAS DE LA CIUDAD DE MÉXICO TITULAR DEL MISMO ARCHIVO, CON FUNDAMENTO EN EL ARTICULO 24 FRAC. V EN RELACIÓN CON EL ART. 3 FRAC. II, Y ARTICULO 119 D DEL REGLAMENTO INTERIOR DE LA ADMINISTRACIÓN PÚBLICA DEL DISTRITO FEDERAL';
        $control="CAPTURO:".$row["usuario_alta_cer"].($row["usuario_alta_cer"]!=$row["usuario_modifico_cer"] ? " MODIFICO:".$row["usuario_modifico_cer"] : "")." DERECHOS:".$row["monto_certi"]." LC:".$row["lc"].($row["usuario_reviso"]!="" ? " REVISO:".$row["usuario_reviso"] : "").($row["usuario_certifico"]!="" ? " CERTIFICO:".$row["usuario_certifico"] : "").($row["usuario_firmo"]!="" ? " FIRMO:".$row["usuario_firmo"] : "");
        $this->SetY(-60);
        $this->SetFont('Courier','B',13);
        $this->Multicell(100,$wlalto1,$firman,1,'C');
        $this->SetFont('Courier','',6);
        $this->MultiCell(90,3,$control,0,'J');
        $this->SetY(-60);
        $this->SetFont('Courier','B',14);
        $this->SetX(110);
        $this->MultiCell(100,$wlalto1,$firmaj,1,'C');
        $this->SetFont('Courier','',6);
        $this->SetX(110);
        $this->MultiCell(100,3,$suple,0,'J');
}
}
echo "paso fuciones";

  $date = new DateTime();
  $code     = str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."90";
$pdf = new PDF ();
$pdf->Fpdf('P','mm','Legal');
$pdf->SetStyle("p","courier","N",15);
$pdf->SetStyle("vb","courier","B",15);

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
##$pdf->SetMargins(30,10,10);
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
echo "TextWithRotation";
//Determinar un nombre temporal de fichero en el directorio actual
//$file=basename(tempnam(getcwd(),'tmp'));
$file="certi_".$wlfolioconsecutivo;
//Determinar en nombre para el archivo pdf
$file1="temp/".$file;
$file1.='.pdf';
$pdf->Output($file1);
echo "Paso outpue";
echo "	<html><script>	";
echo "		self.close();	";
echo "		document.location='$file1';	";
echo "	</script></html>";
##if(file_exists($file1))
##{unlink($file1);}

?>
