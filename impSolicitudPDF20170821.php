<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
require_once("menudata.php");
  include('php-barcode.php');
##print_r($_GET); die();
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
        $folioconsecutivo=$_GET['wl_folioconsecutivo'];	
	// Genera detalle del reporte
	if (empty($_GET['id_cveasunto'])) {echo "El tramite no esta definido"; die;}
	if (empty($_GET['fecharecibo'])) {echo "La fecha de recepcion no esta definido"; die;}
        $sql="  select vtr.*".
                 "      ,(select descripcion from contra.cat_asuntos where id_cveasunto=vtr.id_cveasunto) as descripcion_asunto ".
                 "      ,(select descripcion from contra.cat_condicionv where condicionv=vtr.condicionv) as descripcion_condicionv ".
                 "      from contra.gestion as vtr      ".
                 "      where vtr.id_cveasunto=".$_GET['id_cveasunto']." and vtr.fecharecibo='".$_GET['fecharecibo']."'".
                 ($_GET['wl_folioconsecutivo']!='' ? " and folioconsecutivo=".$_GET['wl_folioconsecutivo'] : "").
                 " order by vtr.folio asc; ";
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
$pdf->Fpdf('P','pt',array(270/$pix,140/$pix));
//$pdf->Fpdf('P','pt',array(270/$pix,280/$pix));
$pdf->SetMargins(10/$pix,10/$pix,.1);
$pdf->SetAutoPageBreak(0);
$pdf->AddPage();

      $copia=0;
  $x        = 620;  // barcode center
  $y        = 40;  // barcode center
  $inicioln =20;

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
// tama�o de letra
$wlsize1=5;
// alto de celda
$wlalto1=5;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tama�o de letra
$wlsize2=7;
// alto de celda
$wlalto2=7;
// largo de celdas
$wllargo2=215;
$wllargo2b=85;
$wllargo2c=50;

// :::: valor para establecer las abscisas de la celdas ::::
//$wlsetx=20;
//$wlsetx2=150;
$wlsetx=40;
$wlsetx2=170;

//$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2/$pix);
  $fontSize = 8;
  $marge    = 0;   // between barcode and hri in pixel
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
	//$pdf->Image('img/decidiendojuntos.jpg',17/$pix,11/$pix,20/$pix,0);
	//$pdf->Image('img/cejur_01.JPG'       ,180/$pix,11/$pix,20/$pix,0);
	 //$pdf->Image('img/decidiendojuntos.jpg',$wlsetx/$pix,11/$pix,20/$pix,0);
	 $pdf->Image('img/cdmx.png',$wlsetx/$pix,11/$pix,20/$pix,0);
	//$pdf->Image('img/cejur_01.JPG'       ,$wlsetx+220/$pix,11/$pix,20/$pix,0);

	$pdf->SetLineWidth(0.4/$pix);
	$pdf->Line(60/$pix,8/$pix,60/$pix,32/$pix);
	//$pdf->SetY(13);
        
        $pdf->ln($inicioln);
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
	$pdf->SetX($wlsetx/$pix);
	$pdf->Cell(215/$pix,6/$pix,'SOLICITUD DE INSCRIPCION','B',1,'C',0);
	if ($relevante=='t') {	$pdf->Cell(0,6,'TRAMITE RELEVANTE',0,1,'C',0);	}
	//$pdf->Image((($wladjuntos>0) ? 'img/attachIcon.jpg' : 'img/fail.jpg'),188,30,5,0);
	
	$pdf->Ln(2);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
        $pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'TIPO DE TRAMITE',0,0,'L',0);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'SOLICITANTE',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wldesasunto,'BR',0,'C',1);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wlpenvia,'BR',1,'C',1);
	
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'FECHA DE RECEPCION',0,0,'L',0);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'FOLIO DE RECEPCION',0,1,'L',0);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wlfecharecibo,'BR',0,'C',1);
	
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',20);
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
	
	
	//$pdf->SetY(-25/$pix);
        $pdf->Ln(60);
	
	//$pdf->SetX($wlsetx);
	//$pdf->SetFont('Arial','B',$wlsize2);
	//$pdf->Cell(120,$wlalto2,'FIRMA',0,0,'C',0);
	
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
	$pdf->Cell($wllargo1/$pix,$wlalto1/$pix,'PLAZO DE RESPUESTA',0,1,'L',0);
	$pdf->SetX($wlsetx2/$pix);
	$pdf->SetFont('Arial','B',$wlsize2);
        if ($wldiastermino==0) {$wldiastermino="Inmediata";} else {$wldiastermino=$wldiastermino." dias habiles"; }
	$pdf->Cell($wllargo2b/$pix,$wlalto2/$pix,$wldiastermino,'BR',1,'C',1);
	
	$pdf->Ln(4);
    //Go to 1.5 cm from bottom
    //$pdf->SetY(-12);
    //Select Arial italic 8
    $pdf->SetFont('Arial','I',6);
    //Print current and total page numbers

	$pdf->Setx($wlsetx/$pix);
	$pdf->Cell(25/$pix,6/$pix,'Fecha de emisi�n: ','LBT',0,'R',1);
	$pdf->Cell(30/$pix,6/$pix,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$pdf->Cell(25/$pix,6/$pix,'Hora de emisi�n: ','BT',0,'R',1);
	$pdf->Cell(15/$pix,6/$pix,date("g:i:s a,"),'BT',0,'L',1);
	$pdf->Cell(35/$pix,6/$pix,'Usuario de emisi�n: ','BT',0,'R',1);
	$pdf->Cell(25/$pix,6/$pix,$parametro1.',','BT',0,'L',1);
	$pdf->Cell(60/$pix,6/$pix,'Pagina '.$pdf->PageNo(),'BTR',1,'C',1);
	$pdf->Ln(1);
	$pdf->Setx($wlsetx/$pix);
	//$pdf->Cell(215/$pix,6/$pix,'Consulta de tramite al tel. 55-22-51-40 55-22-51-18 Ext. 109 y 113 de 9:00 a 15:00 de lunes a viernes',0,1,'C',1);
                         $x        = 620;  // barcode center
                         $y        = 40;  // barcode center
                         $inicioln =20;
                         //$pdf->AddPage(); 
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
if (empty($_GET['wl_email']))
{
   echo "	<html><script>	";
   echo "		self.close();	";
   echo "		parent.close();	";
   echo "		document.location='$file1';	";
   echo "	</script></html>";
} else
{
  EnviaCitaemail($file1,$_GET['wl_email'],$connection);
}
        function EnviaCitaemail($file1,$wlemail,$connection)
        {
             global $code;
             global $folioconsecutivo;
             global $connection;
             global $wlfolio;
             if ($wlemail=="")
             { echo "<error>No esta definido el el email </error>"; return false;}
//             if ($wlfolio=="")
//             { echo "<error>No esta definido el folio de la cita para enviar el email </error>"; return false; }
             $cita ="cita_".$wlfolio.".pdf";
             $mail = new PHPMailer;
             $mail->IsSMTP();                                      // Set mailer to use SMTP
             $mail->SMTPDebug=false;                                      // Set mailer to use SMTP
             $mail->Host = 'smtp.live.com';  // Specify main and backup server
             $mail->SMTPAuth = true;                               // Enable SMTP authentication
             $mail->Username = 'jlvdantry@hotmail.com';                            // SMTP username
             $mail->Password = '888aDantryR';                           // SMTP password
             $mail->Port = '25';                           // SMTP password
             $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
             $mail->From = 'jlvdantry@hotmail.com';
             $mail->FromName = ('Direccion Jur�dica y Estudios Legislativos');
             //$mail->setFrom = ('csjl_2016@outlook.es','Direccion Jur�dica y Estudios Legislativos');
             $mail->AddAddress($wlemail);               // Name is optional
             $mail->AddReplyTo('jlvdantry@hotmail.com', 'Information');
             $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
             $mail->AddAttachment($file1, $file1);    // Optional name
             $mail->IsHTML(true);                                  // Set email format to HTML
             $mail->Subject = 'Confirmaci�n de su Solicitud de Inscripci�n '.$wlfolio;
             $mail->Body    = 'Gracias por utilizar este servicio<br>Se anexa comprobante de su Solicitud de Inscripci�n<BR>Recuerde presentarse 10 minutos antes del evento';
             if(!$mail->Send()) {
               echo "<error>No se pudo enviar el email a ".$wlemail." error ".$mail->ErrorInfo."</error>"; return false;
             }
             echo "<error>EL numero de solicitud que se genero es $code y se envio al email ".$wlemail."</error>";;
               $sql="insert into contra.ope_turnados (folioconsecutivo,id_tipotra,observacion,id_persona) values (".
                    $folioconsecutivo.",30,'".$wlemail."',0)";
               $sql_result = pg_exec($connection,$sql);
               if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
          }
?>
