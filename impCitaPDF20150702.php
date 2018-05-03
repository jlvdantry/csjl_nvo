<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
include('php-barcode.php');
	// Genera titulos
$wlfolioconsecutivo=$_GET['wlfolioconsecutivo'];
	$sql = "select * from contra.v_titulos ";
	//echo "<textarea>$sql</textarea>";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0) {echo "<error>No existen titulos definidos para el reporte, consulte con el administrador del sistema</error>"; die;}
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
$wlfolio=$row['folioconsecutivo'];
$wloficio=$row['referencia'];
$wlfechaalta=$row['fecha_alta'];
$wlusuarioalta=$row['usuario_alta'];
$wldiastermino=$row['diastermino'];
$wladjuntos=$row['adjuntos'];
$wlnombre=$row['entrega']=='t'? "Entrega de tramite ".$row["folio"]." de ".$wldesasunto." del ".$wlfecharecibo : $row['nombre']." ".$row['apepat']." ".$row['apemat'];

/* propiedades del codigo de barras */
  $marge    = 0;   // between barcode and hri in pixel
  $height   = 25;   // barcode height in 1D ; module size in 2D
  $width    = 1;    // barcode height in 1D ; not use in 2D
  $code     = str_replace("-","",$wlfolio).substr($wlfecharecibo,0,4)."00";
  $type     = 'code128';
  $black    = '000000'; // color in hexa


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
function Header()
{
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
	$this->Image('img/decidiendojuntos.jpg',17,11,20,0);
	//$this->Image('img/attachIcon.jpg',17,11,40,0);
	//$this->Image('img/cejur_01.JPG',180,11,20,0);
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
	$this->Ln(1);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',$wlsize2);
    $this->SetFont('Arial','B',9);
	$this->Cell(0,6,'COMPROBANTE DE CITA AGENDADA VIA INTERNET','B',1,'C',0);
	if ($relevante=='t') {	$this->Cell(0,6,'TRAMITE RELEVANTE',0,1,'C',0);	}
	//$this->Image((($wladjuntos>0) ? 'img/attachIcon.jpg' : 'img/fail.jpg'),188,30,5,0);
	
	$this->Ln(2);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
        $this->Cell($wllargo1,$wlalto1,'TIPO DE TRAMITE',0,0,'L',0);
	$this->SetX(115);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'ESTATUS DEL TRAMITE',0,1,'L',0);
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
	$this->Cell($wllargo2b,$wlalto2,$wlfolio,'BR',1,'C',1);
	
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize1);
	$this->Cell($wllargo1,$wlalto1,'OBSERVACION',0,1,'L',0);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,'PRESENTARSE 10 MINUTOS ANTES DE SU CITA PROGRAMADA ','BR','C',1);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,'LA OMISION EN LA PRESENTACION DE ALGUN REQUISITO INVALIDA LA CITA PROGRAMADA Y DEBERA DE SOLICITAR UNA NUEVA','BR','C',1);
	$this->SetX($wlsetx);
	$this->SetFont('Arial','B',$wlsize2);
	$this->multiCell($wllargo2,$wlalto2,'ACUDIR CON SU DOCUMENTACION EN ORIGINAL y 2 COPIAS SIMPLES','BR','C',1);
}

function Footer()
{
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
	$this->SetY(-25);
	
	$this->Ln(5);
    //Go to 1.5 cm from bottom
    $this->SetY(-12);
    //Select Arial italic 8
    $this->SetFont('Arial','I',6);
    //Print current and total page numbers

	$this->Image('img/logo3_2007.JPG',8,260,12,0);
	$this->Image('img/logo2_2007_color.JPG',197,260,12,0);
	$this->Setx(25);
	$this->Cell(25,6,'Fecha de emisiòn: ','LBT',0,'R',1);
	$this->Cell(30,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(25,6,'Hora de emisiòn: ','BT',0,'R',1);
	$this->Cell(15,6,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(35,6,'','BT',0,'R',1);
	$this->Cell(25,6,'','BT',0,'L',1);
	$this->Cell(15,6,'Pagina '.$this->PageNo().'/{nb}','BTR',1,'C',1);
}
}

$pdf = new PDF ();
$pdf->Fpdf('P','mm',array(215,93));
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);
// :::: inicio del contenido del documento ::::
$e=0;

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
//echo "		parent.close();	";
echo "		document.location='$file1';	";
echo "	</script></html>";
        function EnviaCitaemail($wlfolio,$wlemail)
        {
             ##$wlemail=$this->argumentos["wl_email"];
             ##$wlfolio=$this->argumentos["wl_folioconsecutivo"];
             if ($wlemail=="")
             { echo "<error>No esta definido el el email </error>"; return false;}
             if ($wlfolio=="")
             { echo "<error>No esta definido el folio de la cita para enviar el email </error>"; return false; }
             $cita ="cita_".$wlfolio.".pdf";
             $mail = new PHPMailer;
             $mail->IsSMTP();                                      // Set mailer to use SMTP
             ##$mail->Host = 'plus.smtp.mail.yahoo.com';  // Specify main and backup server
             $mail->Host = 'smtp.live.com';  // Specify main and backup server
             $mail->SMTPAuth = true;                               // Enable SMTP authentication
             ##$mail->Username = 'jlvdantry@yahoo.com';                            // SMTP username
             ##$mail->Password = '888aDantryR';                           // SMTP password
             $mail->Username = 'jlvdantry@hotmail.com';                            // SMTP username
             $mail->Password = '888aDantryR';                           // SMTP password
             $mail->Port = '587';                           // SMTP password
             $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
             $mail->From = 'jlvdantry@yahoo.com';
             $mail->FromName = 'Jose Luis Vasquez Barbosa';
             ##$mail->AddAddress('josh@example.net', 'Josh Adams');  // Add a recipient
             $mail->AddAddress($wlemail);               // Name is optional
             $mail->AddReplyTo('jlvdantry@hotmail.com', 'Information');
             ##$mail->AddCC('cc@example.com');
             ##$mail->AddBCC('bcc@example.com');
             $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
             ##$mail->AddAttachment('/var/tmp/file.tar.gz');         // Add attachments
             $mail->AddAttachment('temp/'.$cita, $cita);    // Optional name
             $mail->IsHTML(true);                                  // Set email format to HTML
             $mail->Subject = 'Confirmacion de su cita reservada por internet';
             $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
             if(!$mail->Send()) {
               echo "<error>No se pudo enviar el email a ".$wlemail." error ".$mail->ErrorInfo."</error>"; return false;
             }
             echo "<error>Se envio la cita al email ".$wlemail."</error>";;
          }

EnviaCitaemail($wlfolioconsecutivo,$wlemail);
?>
