<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
require_once("menudata.php");
include('php-barcode.php');
require_once('WriteTag_x.php');
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
        echo $_GET['filtro'];
	// Genera detalle del reporte
	//if (empty($_GET['id_cveasunto'])) {echo "El tramite no esta definido"; die;}
	//if (empty($_GET['fecharecibo'])) {echo "La fecha de recepcion no esta definido"; die;}
        //include("idsolicitudes.php");
        $me = new menudata();
        $me->connection=$connection;
        $me->idmenu=2519;
        $me->filtro=$_GET['filtro'];
        $me->damemetadata();
        echo "paso metadata";
        $sql_result = pg_exec($connection,$me->camposm["fuente"]);
        if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
        $num= pg_numrows ($sql_result);
        if ($num==0) {echo "No hay movimiento a imprimir la solicitud".$me->camposm["fuente"]; die;}


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

    function Header()
    {
        global $wllargo2;
        global $wlalto2;
        global $pix;
        ##$this->WriteTag($wllargo2/$pix,$wlalto2/$pix,'<p>Header</p>',0,'R',0,0);

    }

    function Footer()
    {
        global $wllargo2;
        global $wlalto2;
        global $pix;
        ##$this->WriteTag($wllargo2/$pix,$wlalto2/$pix,'<p>Pagina <vb>'.$this->PageNo().'</vb> de <vb>{nb}</vb></p>',0,'R',0,0);

    }

}
$pdf = new PDF();
$pdf->Fpdf('P','pt',array(270/$pix,280/$pix));
$pdf->SetStyle("p",'courier',"N",9);
$pdf->SetStyle("p1",'courier',"N",6);
$pdf->SetStyle("vb","courier",'B',12);
##$pdf->SetMargins(10/$pix,10/$pix,.1);
##$pdf->SetAutoPageBreak(1,4/$pix);
$pdf->AddPage();

      $copia=0;
  $x        = 620;  // barcode center
  $y        = 40;  // barcode center
  $inicioln =20;

      for ($z=0; $z < $num ;$z++)
      {
	$row=pg_fetch_array($sql_result,$z);
        $row['id_tipopersona']=substr($row['id_tipopersona'],0,strpos($row['id_tipopersona'],"="));
        $row['id_tipotramite']=substr($row['id_tipotramite'],0,strpos($row['id_tipotramite'],"="));
        $row['idcolonia']=substr($row['idcolonia'],0,strpos($row['idcolonia'],"="));
        $row['iddelegacion']=substr($row['iddelegacion'],0,strpos($row['iddelegacion'],"="));
        $row['id_tipodocumentoiden']=substr($row['id_tipodocumentoiden'],0,strpos($row['id_tipodocumentoiden'],"="));
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
$wlfolio=$row['folioconsecutivo'];
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
$wlsize2=7;
// alto de celda
$wlalto2=6;
$wlalto3=2;
// largo de celdas
$wllargo2=240;
$wllargo2b=110;
$wllargo2c=50;

// :::: valor para establecer las abscisas de la celdas ::::
//$wlsetx=20;
//$wlsetx2=150;
$wlsetx=20;
$wlsetx2=150;
//$fill={199,0,128};
$pdf->AliasNbPages();
$pdf->SetFillColor(234,163,196);
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
        $pdf->Image('img/cdmx_03.png',$wlsetx/$pix,$pdf->GetY()+7,30/$pix,0);

	$pdf->SetLineWidth(0.4/$pix);
        
        $pdf->ln($inicioln/$pix);
 $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);

  $pdf->SetFont('Arial','B',$fontSize);
  $pdf->SetTextColor(0, 0, 0);
  $len = $pdf->GetStringWidth($data['hri']);
  Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
  //$pdf->SetY(13);
  $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);

	
	$pdf->SetLineWidth(0.2/$pix);
	$pdf->SetFillColor(234,163,196);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',$wlsize2);
        $pdf->SetFont('Arial','B',9);
	$pdf->SetX($wlsetx/$pix);
	$pdf->Cell($wllargo2/$pix,6/$pix,'SOLICITUD DE TRÁMITE','B',1,'C',0);
	//$pdf->Image((($wladjuntos>0) ? 'img/attachIcon.jpg' : 'img/fail.jpg'),188,30,5,0);
	
	$pdf->Ln(2);
	$pdf->SetX($wlsetx/$pix);
	$pdf->SetFont('Arial','B',$wlsize1);
        $wl='<p>NOMBRE DEL TRÁMITE: <vb>'.$row['id_tipotramite'].'<vb><p>';
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'J',1,0);
        //$pdf->WriteTag(600,$wlalto2/$pix,$wl,2,'J',1,0);
        //$pdf->Cell(30/$pix,6/$pix,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1)
        $wl='<p>Ciudad de México, a <vb>'.date(' j ').'</vb>de <vb>'.meses_espanol(date('n')).'</vb> de <vb>'.date(" Y.").'</vb><p>';
	$pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'J',0,0);

        $wl='<p><vb>Titular del Archivo General de Notarías</vb><p>';
	$pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'J',0,0);

        $wl='<p>Presente<p>';
	$pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'J',0,0);
        $wl='<p>Declaro bajo protesta de decir verdad que la información y documentación proporcionada es verídica, por lo que en caso de existir falsedad en ella, tengo pleno conocimiento que se aplicarán las sanciones administrativas y penas establecidas en los ordenamientos respectivos para quienes se conducen con falsedad ante la autoridad competente, en términos del artículo 165 fracción I de la Ley del Notariado, con relación al 311 del Código Penal, ambos del Distrito Federal.<p>';
	$pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'J',0,0);

	$pdf->Ln(8);
        $wl='<p><vb>Información al interesado sobre el tratamiento de sus datos personales</vb><p>';
        $pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,2,'C',1,0);

        $wl='<p>Los datos personales recabados serán protegidos, incorporados y tratados en el Sistema de Datos Personales <vb>PROTOCOLOS DE LOS NOTARIOS PUBLICOS</vb>, el cual tiene su fundamento en la <vb>Ley del Notariado para el Distrito Federal artículos 236, 237 y 238 Fracciones V, VI, VII, IX, XI, XIV, XIX y 239; Ley de Transparencia y Acceso a la Información Pública del Distrito Federal. Artículo 4 Fracciones II, VII, VIII, XV, XVIII, XIX; 10, 36, 37 Fracción II; 38 Fracción IV, 39 y 44; Ley de Protección de Datos Personal es para el Distrito Federal. Artículos 7, 8, 9, 13, 14 y 15; y Reglamento Interior de la Administración Pública del Distrito Federal. Artículos 7, Fracción XV numeral 3; y 114 Fracción XV,</vb> cuya finalidad es <vb>La secrecía de los datos personales que contienen los protocolos en cada uno de los actos jurídicos que se celebran ante los diversos Notarios del Distrito Federal, los cuales se custodian de manera definitiva en los acervos del Archivo General de Notarías del Distrito Federal, así como de los apéndices que soportan lamotricidad de cada uno de éstos y podrán ser transmitidos en los supuestos previstos en la Ley de Protección de Datos Personal es para el Distrito Federal.</vb> Los datos marcados con un asterisco(*) son obligatorios y sin ellos no podrá acceder al servicio o completar el trámite de <vb>'.$row['id_tipotramite'].'</vb>. '.
         'Asimismo, se le informa que sus datos no podrán ser difundidos sin su consentimiento expreso, salvo las excepciones previstas en la Ley. El responsable del Sistema de Datos Personales es la <vb>MTRA. CLAUDIA ANGELICA NOGALES GAONA </vb>, y la dirección donde podrá ejercer los derechos de acceso, rectificación, cancelación y oposición, así como la revocación del consentimiento es <vb>Candelaria de los Patos s/n, Planta Baja, Colonia Diez de Mayo, Delegación Venustiano Carranza México Distrito Federal Código Postal 15290, Oficina de Información Pública de la Consejería Jurídica y de Servicios Legales.</vb>El titular de los datos podrá dirigirse al Instituto de Acceso a la Información Pública y Protecciónde Datos Personales del Distrito Federal, donde recibirá asesoría sobre los derechos que tutela la Ley de Protección de Datos Personales para el Distrito Federal al teléfono 56364636; correo electrónico: datospersonales@infodf.org.mx o en la página www.infodf.org.mx.<p>';
	$pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'J',0,'10,10,10,10');
        if ($row['id_tipopersona']=='PERSONA FÍSICA') {
	    $pdf->Ln(5/$pix);
            $wl='<p><vb>DATOS DEL INTERESADO (PERSONA FÍSICA)</vb><p>'.
	    $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $wl='<p>Nombre(s): <vb> '.$row['nombre'].'</vb></p>';
            ##$pdf->SetX($wlsetx/$pix);
            ##$pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $wl=$wl.'<p> Apellido Paterno: <vb> '.$row['apepat'].'</vb> Apellido Materno: <vb> '.$row['apemat'].'</vb></p>';
            ##$pdf->SetX($wlsetx/$pix);
            ##$pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $wl=$wl.'<p> Identificación Oficinal: <vb> '.$row['id_tipodocumentoiden'].'</vb> Número / Folio: <vb> '.$row['folioiden'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,0);

        }
            $pdf->Ln(2/$pix);
            $wl='<p><vb>DOMICILIO PARA OIR Y RECIBIR NOTIFICACIONES Y DOCUMENTOS EN EL DISTRITO FEDERAL </vb><p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $wl='<p>Calle: <vb> '.$row['calle'].'</vb> No. Exterior: <vb> '.$row['numext'].'</vb> No. Exterior: <vb> '.$row['numint'].'</p>';
            ##$pdf->SetX($wlsetx/$pix);
            ##$pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $wl=$wl.'<p> Colonia: <vb> '.$row['idcolonia'].'</vb> Delegacion: <vb> '.$row['iddelegacion'].'</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,0);

            $pdf->Ln(2/$pix);
            $wl='<p><vb>INSTRUMENTO SOLICITADO</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $wl='<p>Escritura: <vb> '.$row['escr'].'</vb> Fecha de <vb>'.substr($row['fechaescr'],8,2).'</vb> de <vb>'.meses_espanol(intval(substr($row['fechaescr'],5,2))).'</vb> de <vb>'.substr($row['fechaescr'],0,4).'</vb></p>';
            #$pdf->SetX($wlsetx/$pix);
            #$pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $wl=$wl.'<p> Nombre del Notario: <vb> '.$row['nombrenotaescr'].'</vb> Notaría número: <vb> '.$row['notaescr'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,0);

            $pdf->Ln(2/$pix);
            $wl='<p><vb>REQUISITOS</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);
            $wly=$pdf->GetY(); 
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Documentos con los que se acredite interés jurídico, en original o copia certificada, y dos copias simples. (Ejemplo:Sentencia Judicial, copia de la Escritura Solicitada)</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',1,'10,1,1,1',5);
            $wl='<p>Documentos con los que se acredite la personalidad, cuando se actúe a nombre de persona física o moral, original o copia certificada, y dos copias simples. (Ejemplo: PoderNotarial, Carta Poder, Poder Especial otorgado en Escritura Pública)</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1',5);

            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);
            $wly=$pdf->GetY();
            $wl='<p>Formato debidamente requisitado en original y dos copias simples</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',1,'10,1,1,1');
            $wl='<p>Original del comprobante del pago de derechos correspondientes.</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');

            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);
            $wly=$pdf->GetY();
            $wl='<p>Original y copia simple identificación oficial vigente (Credencial del Instituto Nacional Electoral, Pasaporte, Cartilla del Servicio Militar Nacional, Cédula Profesional, en su caso documento migratorio).</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',1,'10,1,1,1');
            $wl='<p>Original del comprobante del pago de derechos correspondientes.</p>';
            $pdf->SetFillColor(234,163,196);

            $pdf->Ln(2/$pix);
            $wl='<p><vb>FUNDAMENTO JURÍDICO</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);
            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Ley de Notariado para el Distrito Federal, Artículos 238, Fracciones V y VI, 239, 240, 244 y 247</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',1,'10,1,1,1');
            $wl='<p>Ley Orgánica de la Administración Pública del Distrito Federal artículo 35 Fracción XX</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');

            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);
            $wly=$pdf->GetY();
            $wl='<p>Reglamento Interior de la Administración Pública del Distrito Federal, artículo 114, fracción XIV y XV.</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',1,'10,1,1,1');

             $pdf->Ln(2/$pix); 
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",2,'C',0,0);
            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Costo</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,'<p></p>',5,'L',0,'10,1,1,1');
            $wl='<p>Artículos 214, Fracción I, Inciso a, b, c y d; y 248, Fracción I, Inciso d, y fracción II, Inciso a.</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);

            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Documento a obtener</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,'<p></p>',5,'L',0,'10,1,1,1');
            $wl='<p>Testimonio de Instrumento Notarial, copia simple, copia certificada</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);

            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Tiempo de respuesta</p><p></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,1,1,1');
            $wl='<p>13 días hábiles</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,'<p></p>',4,'L',0,'1,1,1,1');

            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Vigencia del documento a obtener</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,1,1,1');
            $wl='<p>Indeterminada</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,'<p></p>',4,'L',0,'1,1,1,1');

            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>Procedencia de la Afirmativa o Negativa Ficta</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,1,1,1');
            $wl='<p>No aplica</p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',1,'1,1,1,1');
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,'<p></p>',3,'L',0,'1,1,1,1');

            $pdf->SetFillColor(234,163,196);
            $pdf->Ln(2/$pix);
            $wl='<p><vb>OBSERVACIONES</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,"<p></p>",4,'C',0,0);
            $wly=$pdf->GetY();
            $pdf->SetFillColor(245,231,237);
            $wl='<p>* Señalar número y fecha del instrumento notarial, así como número y nombre del Notario ante quien se otorgó.</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,'10,1,1,1');
            $wl='<p>* No se aceptan cotejos notariales en términos del artículo 160 de la Ley del Notariado para el Distrito Federal.</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,'10,1,1,1');
            $wl='<p>* En caso de representar a una sucesión, exhibir original o copia certificada y copia simple del documento con el que se acredite que se está en ejercicio del cargo de albacea.</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,'10,1,1,1');
            $wl='<p>* En caso de ser representante o apoderado legal, deberá exhibir original o copia certificada y copia simple del poder notarial con facultades para actos de administración y/o de dominio o facultades especiales para el trámite de reproducción de instrumento notarial.</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,1,1,1');

            $pdf->SetFillColor(234,163,196);
            $pdf->Ln(2/$pix);
            $wl='<p><vb>INTERESADO O REPRESENTANTE LEGAL (en su caso)</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'C',0,0);
            $pdf->Ln(8/$pix);
            $wl='<p>_______________________________________________<p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'C',0,0);
            $wl='<p>Nombre y firma<p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,0,'C',0,0);

            $pdf->SetFillColor(234,163,196);
            $pdf->Ln(2/$pix);
            $wl='<p>LA PRESENTE HOJA Y LA FIRMA QUE APARECE AL CALCE, FORMAN PARTE INTEGRANTE DE LA SOLICITUD DEL TRÁMITE EXPEDICIÓN DE TESTIMONIO, COPIA CERTIFICADA O COPIA SIMPLE DE INSTRUMENTO NOTARIAL, DE FECHA <vb>'.substr($row['fechaescr'],8,2).'</vb> DE <vb>'.meses_espanol(intval(substr($row['fechaescr'],5,2))).'</vb> DE <vb>'.substr($row['fechaescr'],0,4).'</vb><p>'.

            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
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
                         $x        = 620;  // barcode center
                         $y        = 40;  // barcode center
                         $inicioln =80;
     }

$e=0;
$file=basename(tempnam(getcwd(),'tmp'));
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
             $mail->Host = 'smtp.gmail.com';  // Specify main and backup server
             $mail->SMTPAuth = true;                               // Enable SMTP authentication
             $mail->Username = 'seminarioderechonotarial@gmail.com';                            // SMTP username
             $mail->Password = 'seminario2017';                           // SMTP password
             $mail->Port = '587';                           // SMTP password
             $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
             $mail->From = 'seminarioderechonotarial@gmail.com';
             $mail->FromName = ('Direccion Jurídica y Estudios Legislativos');
             //$mail->setFrom = ('csjl_2016@outlook.es','Direccion Jurídica y Estudios Legislativos');
             $mail->AddAddress($wlemail);               // Name is optional
             $mail->AddReplyTo('seminarioderechonotarial@gmail.com', 'Informacion');
             $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
             $mail->AddAttachment($file1, $file1);    // Optional name
             $mail->IsHTML(true);                                  // Set email format to HTML
             $mail->Subject = 'Confirmación de su Solicitud de Inscripción '.$wlfolio;
             $mail->Body    = 'Gracias por utilizar este servicio<br>Se anexa comprobante de su Solicitud de Inscripción<BR>Recuerde presentarse 10 minutos antes del evento';
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
