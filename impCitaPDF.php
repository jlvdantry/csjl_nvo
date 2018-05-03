<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
include('php-barcode.php');
	// Genera titulos
##$wlfolioconsecutivo=$_GET['wlfolioconsecutivo'];
$wlfolioconsecutivo=$argv[1];
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
               " ,(select descripcion from contra.cat_estatus as ce where ce.estatus=co.estatus) as desestatus ".
               " from ( ".
	       "	select tr.*	".
                 " ,tt.descripcion as desasunto ".
                 " ,tt.entrega  as entrega  ".
                 " ,tt.id_tipotramite_equiv ".
                 " ,tt.prefijo ".
		 "	from agenda.tramites tr  ".
                 "       , agenda.tramites_tipo  tt  ".
		 "	where folioconsecutivo=$wlfolioconsecutivo and tr.id_tipotramite=tt.id_tipotramite) a	".
                 "      left join contra.gestion  as co on (co.folio = prefijo || '-' || lpad(a.folio::varchar(10),6,'0')  and co.fecharecibo = a.fecharecibo ) ";
	//echo "<textarea>$sql</textarea>";die;
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "El folio consecutivo $wlfolioconsecutivo no existe en la tabla de tramites"; die;}
	$row=pg_fetch_array($sql_result,0);

$wlpenvia=$row['persona_envia'];
$wlrecibe=$row['persona_recibe'];
$wldesdocto=$row['descripcion_docto'];
$wldesasunto=$row['desasunto'];
$wlestatus=$row['desestatus'];
$wlasunto=$row['asunto'];
$wlfechadocumento=$row['fechadocumento'];
$wlfecharecibo=$row['fecharecibo'];
$wlfolio=$row['idcita'];
$wloficio=$row['referencia'];
$wlfechaalta=$row['fecha_alta'];
$wlusuarioalta=$row['usuario_alta'];
$wldiastermino=$row['diastermino'];
$wladjuntos=$row['adjuntos'];
$wlnombre=$row['entrega']=='t'? "Entrega de tramite ".$row["folio"]." de ".$wldesasunto." del ".$wlfecharecibo : $row['nombre']." ".$row['apepat']." ".$row['apemat'];

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
	global $row;
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',9);
	##$this->Image('img/decidiendojuntos.jpg',17,11,20,0);
        $this->Image('img/cdmx_03.png',20,16,28,0);
	$this->SetLineWidth(0.4);
	$this->Line(50,8,50,32);
	$this->SetY(11);
	$this->cell(42,4,'',0,0,'L',0);
	$this->cell(0,4,$titulo1,0,1,'L',0);
	$this->cell(42,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo2,0,1,'L',0);
	$this->cell(42,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3,0,1,'L',0);
	$this->cell(42,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo4,0,1,'L',0);
	//$this->Ln(1);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',$wlsize2);
        $this->SetFont('Arial','B',9);
	$this->cell(42,4,'',0,0,'L',0);
	$this->Cell(0,4,'COMPROBANTE DE CITA AGENDADA POR INTERNET',0,1,'L',0);
	//$this->Image((($wladjuntos>0) ? 'img/attachIcon.jpg' : 'img/fail.jpg'),188,30,5,0);
	
	$this->Ln(4);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
        $this->Cell($wllargo1,$wlalto1,'TIPO DE TRÁMITE',0,0,'L',0);
	$this->SetX(115);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'ESTATUS DEL TRÁMITE',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$wlnombre,'BR',0,'C',1);
	$this->SetX(115);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$wlestatus,'BR',1,'C',1);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'FECHA Y HORA DE LA CITA',0,0,'L',0);
	$this->SetX(115);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'FOLIO DE LA CITA',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$row['fecha_cita']." ".$row['hora_cita'],'BR',0,'C',1);
	
	$this->SetX(115);
	$this->SetFont('Arial','B',$wlsize2);
	$this->Cell($wllargo2b,$wlalto2,$code,'BR',1,'C',1);
	
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'OBSERVACIÓN',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,'PRESENTARSE 10 MINUTOS ANTES DE SU CITA PROGRAMADA ','BR','C',1);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,'LA OMISIÓN EN LA PRESENTACIÓN DE ALGUN REQUISITO INVALIDA LA CITA PROGRAMADA Y DEBERÁ DE SOLICITAR UNA NUEVA','BR','C',1);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	//$this->multiCell($wllargo2,$wlalto2,'ACUDIR CON SU DOCUMENTACIÓN EN ORIGINAL y 2 COPIAS SIMPLES','BR','C',1);

        //$this->Ln(1);
        $this->SetY(80);
        $this->SetFont('Arial','I',6);
        $this->Setx($wlsetx);
        $this->Cell(25,6,'Fecha de emisiòn: ','LBT',0,'R',1);
        $this->Cell(30,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
        $this->Cell(25,6,'Hora de emisiòn: ','BT',0,'R',1);
        $this->Cell(15,6,date("g:i:s a,"),'BT',0,'L',1);
        $this->Cell(35,6,'','BT',0,'R',1);
        $this->Cell(25,6,'','BT',0,'L',1);
        $this->Cell(25,6,'Pagina '.$this->PageNo().'/{nb}','BTR',1,'C',1);
}

function Footer()
{
}
}

  $date = new DateTime();
  $code     = 'C'.$date->format('s').str_pad($wlfolio,6,'0',STR_PAD_LEFT).substr($wlfecharecibo,0,4)."99";
$pdf = new PDF ();
$pdf->Fpdf('P','mm',array(215,93));
$pdf->SetAutoPageBreak(0,1);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);
$e=0;

  $x        = 180;  // barcode center
  $y        = 12;  // barcode center
  $inicioln =20;
  $fontSize = 8;
  $marge    = -3;   // between barcode and hri in pixel
  $height   = 9;   // barcode height in 1D ; module size in 2D
  $width    = .3;    // barcode height in 1D ; not use in 2D
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
$id=rand(10,100000000);
$file="cita_".$id;
//Determinar en nombre para el archivo pdf
$file1="temp/".$file;
$file1.='.pdf';
//Guardar el PDF en un fichero
$pdf->Output($file1);
//Borro archivo temporal
if(file_exists($file))
{unlink($file);}
//Redirección con JavaScript
/*
echo "	<html><script>	";
echo "		self.close();	";
echo "		document.location='$file1';	";
echo "	</script></html>";
*/
        function EnviaCitaemail($wlfolio,$wlemail,$idcita)
        {
             global $code;
             global $connection;
             if ($wlemail=="")
             { echo "<error>No esta definido el el email </error>"; return false;}
             if ($wlfolio=="")
             { echo "<error>No esta definido el folio de la cita para enviar el email </error>"; return false; }
             $cita ="cita_".$wlfolio.".pdf";
             $mail = new PHPMailer;
             $mail->IsSMTP();                                      // Set mailer to use SMTP
             ##$mail->SMTPDebug=true;                                      // Set mailer to use SMTP
             ##$mail->Host = 'plus.smtp.mail.yahoo.com';  // Specify main and backup server
             $mail->Host = 'correo.consejeria.cdmx.gob.mx';  // Specify main and backup server
             ##$mail->Host = 'cj.df.gob.mx';  // Specify main and backup server
             ##$mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup server
             $mail->SMTPAuth = true;                               // Enable SMTP authentication
             ##$mail->SMTPDebug = true;
             $mail->Username = 'atencionciudadana_jyel';                            // SMTP username
             $mail->Password = 'correodf';                           // SMTP password
             ##$mail->Username = 'csjl_2016@outlook.es';                            // SMTP username
             ##$mail->Password = '888aDantryRR';                           // SMTP password
             $mail->Port = '25';                           // SMTP password
             $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
             $mail->From = 'atencionciudadana_jyel';
             $mail->FromName = 'Direccion Jurídica y Estudios Legislativos';
             $mail->AddAddress($wlemail);               // Name is optional
             $mail->AddReplyTo('no.reply@cj.df.gob.mx', 'Information');
             $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
             $mail->AddAttachment('temp/'.$cita, $cita);    // Optional name
             $mail->IsHTML(true);                                  // Set email format to HTML
             $mail->Subject = 'Confirmación de su cita reservada por internet';
             $mail->Body    = 'Gracias por utilizar este servicio<br>Se anexa comprobante de cita reservada por internet<BR>Recuerde presentarse 10 minutos antes de su cita<BR>Favor de imprimir el comprobante de la cita reservada y presentarla en informes, caso contrario no podrá ser atendido.';
             if(!$mail->Send()) {
               echo "<error>No se pudo enviar el email a ".$wlemail." error ".$mail->ErrorInfo."</error>"; return false;
             }
             echo "<error>EL numero de cita que se genero es $code y se envio al email ".$wlemail." , Es importante que imprima la confirmación de la cita ya que solamente con esta va a poder ser atendido. Favor de checar la bandeja de entrada de su correo o en la bandeja de SPAM o correo no deseado.</error>";
             $sql = "update agenda.tramites set envio_correo=true where idcita=".$idcita;
             $sql_result = pg_exec($connection,$sql);
             $num= pg_numrows ($sql_result);

          }
          EnviaCitaemail($id,$wlemail,$wlfolio);
?>
