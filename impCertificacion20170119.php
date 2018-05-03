<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
include('php-barcode.php');
include('num_a_letras.php');
include('NumberConverter.php');
$wlfolioconsecutivo=$_GET['wl_folioconsecutivo'];
$wlemail=$argv[2];
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
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de tramites"; die;}
	$row=pg_fetch_array($sql_result,0);

$wlfolio=str_replace("-","",$row["folio"]);
$wldesasunto=$row["desasunto"];
$wldiacaratula=$row["diacaratula"];
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
    var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';

    function WriteHTML($html)
    {
        //HTML parser
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                else
                    $this->Write(5,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }

    function OpenTag($tag,$prop)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
    }

    function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
function Justify($text, $w, $h)
{
    $tab_paragraphe = explode("\n", $text);
    $nb_paragraphe = count($tab_paragraphe);
    $j = 0;

    while ($j<$nb_paragraphe) {

        $paragraphe = $tab_paragraphe[$j];
        $tab_mot = explode(' ', $paragraphe);
        $nb_mot = count($tab_mot);

        // Handle strings longer than paragraph width
        $k=0;
        $l=0;
        while ($k<$nb_mot) {

            $len_mot = strlen ($tab_mot[$k]);
            if ($len_mot<($w-5) )
            {
                $tab_mot2[$l] = $tab_mot[$k];
                $l++;    
            } else {
                $m=0;
                $chaine_lettre='';
                while ($m<$len_mot) {

                    $lettre = substr($tab_mot[$k], $m, 1);
                    $len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);

                    if ($len_chaine_lettre>($w-7)) {
                        $tab_mot2[$l] = $chaine_lettre . '-';
                        $chaine_lettre = $lettre;
                        $l++;
                    } else {
                        $chaine_lettre .= $lettre;
                    }
                    $m++;
                }
                if ($chaine_lettre) {
                    $tab_mot2[$l] = $chaine_lettre;
                    $l++;
                }

            }
            $k++;
        }

        // Justified lines
        $nb_mot = count($tab_mot2);
        $i=0;
        $ligne = '';
        while ($i<$nb_mot) {

            $mot = $tab_mot2[$i];
            $len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

            if ($len_ligne>($w-5)) {

                $len_ligne = $this->GetStringWidth($ligne);
                $nb_carac = strlen ($ligne);
                $ecart = (($w-2) - $len_ligne) / $nb_carac;
                $this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
                $this->MultiCell($w,$h,$ligne);
                $ligne = $mot;

            } else {

                if ($ligne)
                {
                    $ligne .= ' ' . $mot;
                } else {
                    $ligne = $mot;
                }

            }
            $i++;
        }

        // Last line
        $this->_out('BT 0 Tc ET');
        $this->MultiCell($w,$h,$ligne);
        $tab_mot = '';
        $tab_mot2 = '';
        $j++;
    }
}
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
	$this->SetFont('Courier','B',18);
	//$this->Image('img/decidiendojuntos.jpg',95,190,30,0);
	$this->Image('img/cdmx_02.png',10,14,0,0);
	//$this->Image('img/enm.jpg',85,120,50,0);
        //$code=str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."99";
	//$this->TextWithDirection(12,120,$code,'U');
        $this->Ln(25);
	$this->Cell($wllargo1,$wlalto1,'VOL. '.$row['vol'],0,0,'L',0);
	$this->Cell(100,$wlalto1,$row['des_tipoprotocolo'],0,0,'C',0);
	$this->Cell($wllargo1,$wlalto1,'FOJA '.$row['escr_hojac'],0,1,'R',0);
        if ($row['id_tipocopia']==3 || $row['id_tipocopia']==4) 
        { 
          $wltexto='LA PRESENTE ES COPIA CERTIFICADA DEL INSTRUMENTO NOTARIAL NUMERO:'; 
          $wlfojast='LA PRESENTE COPIA VA EN: ';
          $wlfojas=str_pad(numtoletras($row['fojas'],8,2)." FOJAS ÚTILES CON LOS REQUISITOS LEGALES",256," -");
        };
        if ($row['id_tipocopia']==1) 
        {
          $a= new NumberConverter();
          $wltexto='EL PRESENTE ES EL '.strtoupper($a->convert($row['tes_orden'],"O")).' TESTIMONIO '.strtoupper($a->convert($row['tes_sol'],"O")).' PARA EL SOLICITANTE DEL INSTRUMENTO NOTARIAL NUMERO:';
          $wlfojast='EL PRESENTE TESTIMONIO VA EN: ';
          $wlfojas=str_pad(numtoletras($row['fojas'],8,2)." FOJAS ÚTILES CON LOS REQUISITOS LEGALES",256," -");
        };
        if ($row['id_tipocopia']==2)
        {
          $a= new NumberConverter();
          $wltexto='EL PRESENTE ES EL '.strtoupper($a->convert($row['drpp_primer'],"O")).' TESTIMONIO PARA EFECTOS DE INSCRIPCIÓN EL EN REGISTRO PÚBLICO DE LA PROPIEDAD '.strtoupper(utf8_decode($a->convert($row['drpp_orden'],"O"))).' EN SU ORDEN DEL INSTRUMENTO NOTARIAL NUMERO:';
          $wlfojast='EL PRESENTE TESTIMONIO VA EN: ';
          $wlfojas=str_pad(numtoletras($row['fojas'],8,2)." FOJAS ÚTILES CON LOS REQUISITOS LEGALES",256," -");
        };
	$this->SetFont('Courier','',15);
        $this->Ln(4);
	$this->MultiCell(0,$wlalto1,$wltexto,0,'J');
        $wlescril=numtoletras($row['escr']);
        $wlescri=str_pad($wlescril.' ',394,' -');
        $this->Ln(2);
	$this->SetFont('Courier','B',14);
	//$this->Justify($wlescri,200,6);
	$this->MultiCell(0,$wlalto1,$wlescri,0,'J');
//$this->WriteHtml($wlescri);
        $wlfechal=numtoletras(substr($row['escr_fecha'],8,2)).' DE '.strtoupper(meses_espanol(intval(substr($row['escr_fecha'],5,2)))).' DE '. numtoletras(substr($row['escr_fecha'],0,4));
        $wlfecha=str_pad($wlfechal,328,' -');
	$this->SetFont('Courier','',14);
        //$this->Ln(2);
	$this->cell(10,$wlalto1,'DE FECHA:',0,1,'L',0);
	$this->SetFont('Courier','B',14);
        $this->Ln(2);
	//$this->Justify($wlfecha,200,6);
	$this->MultiCell(0,$wlalto1,$wlfecha,0,'J');
        if ($row['observaciones']=='')
        { $wlcoteja='COTEJADA CON SU ORIGINAL MISMO QUE SE ENCUENTRA:'; }
        else
        { $wlcoteja='COTEJADA CON SU ORIGINAL MISMO QUE:';
          $wlencuentra=$row['observaciones']; }
	$this->SetFont('Courier','',14);
        $this->Ln(2);
	$this->MultiCell(0,$wlalto1,$wlcoteja,0,'J');
        $y="";
        if ($row['apr_not']!='' && $row['observaciones']=='') 
        { $wlencuentra='AUTORIZADO PREVENTIVA'; 
          $wlnota=str_pad(numtoletras($row['apr_not']).' DE LA CIUDAD DE MÉXICO, LICENCIADO ',264,' -');
          $Y=" y ";
        }
        if ($row['ade_not']!='' && $row['observaciones']=='') 
        { $wlencuentra='AUTORIZADO DEFINITIVAMENTE'; 
          $wlnota=str_pad(numtoletras($row['ade_not']).' DE LA CIUDAD DE MÉXICO, LICENCIADO '.$row['ade_nombre'],263,' -');
        }
        $this->Ln(2);
	$this->SetFont('Courier','B',14);
	$this->MultiCell(0,$wlalto1,$wlencuentra,0,'J');
	$this->SetFont('Courier','',14);
        $this->Ln(2);
        if ( $row['observaciones']=='') { $this->MultiCell(0,$wlalto1,'POR EL NOTARIO NUMERO:',0,'J'); }
        $this->Ln(2);
	$this->SetFont('Courier','B',14);
        if ( $row['observaciones']=='') { $this->MultiCell(0,$wlalto1,$wlnota,0,'J'); }
	//$this->cell(10,$wlalto1,$wlnota,0,1,'L',0);
	$this->SetFont('Courier','',15);
        $wlparat='MISMO QUE OBRA DEPOSITADO EN EL ARCHIVO GENERAL DE NOTARÍAS DE LA CIUDAD DE MÉXICO, Y SE EXPIDE EN USO DE LA FACULTAD QUE ME CONFIEREN LOS ARTÍCULOS DOSCIENTOS TREINTA Y OCHO V, VI DOSCIENTOS TREINTA Y NUEVE, DOSCIENTOS CUARENTA Y CUATRO Y DEMÁS RELATIVOS APLICABLES DE LA LEY DEL NOTARIADO VIGENTE PARA LA CIUDAD DE MÉXICO, COMO TITULAR DEL ARCHIVO GENERAL DE NOTARIAS LA CIUDAD DE MÉXICO PARA:';
	//$this->Justify($wlpara,200,10);
	$this->MultiCell(0,$wlalto1,$wlparat,0,'J');
        $wlpara=str_pad($row['para'],659,' -');
	$this->SetFont('Courier','B',14);
	$this->MultiCell(0,$wlalto1,$wlpara,0,'J');
	$this->SetFont('Courier','',14);
	$this->MultiCell(0,$wlalto1,$wlfojast,0,'J');
	$this->SetFont('Courier','B',14);
	$this->MultiCell(0,$wlalto1,$wlfojas,0,'J');
	//$this->Justify($wlfojas,200,8);
        $wlfechacerti=str_pad('CIUDAD DE MÉXICO A '.numtoletras(substr($row['fecha_certificacion'],8,2)).' DE '.strtoupper(meses_espanol(intval(substr($row['fecha_certificacion'],5,2)))).' DE '. numtoletras(substr($row['fecha_certificacion'],0,4)),130,' -');
	//$this->Justify($wlfechacerti,200,6);
	$this->MultiCell(0,$wlalto1,$wlfechacerti,0,'J');
        $firman='TITULAR DEL ARCHIVO GENERAL DE '."\n".'NOTARIAS DE LA CIUDAD DE MÉXICO'."\n\n\n\n"."LIC. HIRAM GONZALEZ MAYA";
        $firmaj='J.U.D. DE CALIFICACIONES'."\n".'CERTIFICACIONES Y TESTAMENTOS'."\n\n\n\n"."LIC. FERNANDO SOTO FERNÁNDEZ";
        $suple='EN SUPLENCIA POR AUSENCIA DEL TITULAR DEL ARCHIVO GENERAL DE NOTARIAS DE LA CIUDAD DE MÉXICO, CON FUNDAMENTO EN EL ARTICULO 24 FRACC. V EN RELACION CON EL ARTICULO 3 FRACC. II DEL REGLAMENTO INTERIOR DE LA ADMINISTRACIÓN PUBLICA DE LA CIUDAD DE MÉXICO';
        $control="CAPTURO:".$row["usuario_alta_cer"].($row["usuario_alta_cer"]!=$row["usuario_modifico_cer"] ? " MODIFICO:".$row["usuario_modifico_cer"] : "")." DERECHOS:".$row["monto"]." LC:".$row["lc"].($row["usuario_reviso"]!="" ? " REVISO:".$row["usuario_reviso"] : "").($row["usuario_certifico"]!="" ? " CERTIFICO:".$row["usuario_certifico"] : "").($row["usuario_firmo"]!="" ? " FIRMO:".$row["usuario_firmo"] : "");
        $this->SetY(-65);
	$this->MultiCell(100,$wlalto1,$firman,0,'C');
	$this->SetFont('Courier','',5);
	$this->MultiCell(90,3,$control,0,'J');
        $this->SetY(-65);
	$this->SetFont('Courier','B',14);
        $this->SetX(110);
	$this->MultiCell(100,$wlalto1,$firmaj,0,'C');
	$this->SetFont('Courier','',5);
        $this->SetX(115);
	$this->MultiCell(90,3,$suple,0,'J');
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
        $this->SetFont('Arial','I',$wlsize2);
        $this->SetY(308);
        $this->Setx($wlsetx);
}

function Footer()
{
}
}

  $date = new DateTime();
  $code     = str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."90";
$pdf = new PDF ();
$pdf->Fpdf('P','mm','Legal');
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
$file="cita_".$wlfolioconsecutivo;
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
echo "		document.location='$file1';	";
echo "	</script></html>";

?>
