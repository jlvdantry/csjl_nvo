<?PHP
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
include("conneccion.php");
require_once("menudata.php");
include('php-barcode.php');
require_once('WriteTag_x.php');
##print_r($_GET); die();

    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'temp/';
    include "phpqrcode/qrlib.php";

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
	global $pix;
	global $inicioln;
	global $x;
	global $y;
        $fontSize = 8;
        $marge    = 0;   // between barcode and hri in pixel
        $height   = 25;   // barcode height in 1D ; module size in 2D
        $width    = 1;    // barcode height in 1D ; not use in 2D
        $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
        $code     = str_replace("-","",$wlfolio).substr($wlfecharecibo,0,4)."00";
        $type     = 'code128';
        $black    = '000000'; // color in hexa
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',9);
        $this->Image('img/cdmx_03.png',$wlsetx/$pix,$this->GetY()+7,30/$pix,0);
        $this->Image('img/Consejeria_02.jpg',$wlsetx/$pix+(35/$pix),$this->GetY(),60/$pix,0);
        $this->Image('img/AtencionCiudadana_02.jpg',$wlsetx/$pix+(100/$pix),$this->GetY(),60/$pix,0);
        $this->SetLineWidth(0.4/$pix);
        $this->ln($inicioln/$pix);
        $data = Barcode::fpdf($this, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
        $this->SetFont('Arial','B',$fontSize);
        $this->SetTextColor(0, 0, 0);
        $len = $this->GetStringWidth($data['hri']);
        Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
        $this->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
    }


    function Footer()
    {
        global $wllargo2;
        global $wlalto2;
        global $pix;
        $this->WriteTag($wllargo2/$pix,$wlalto2/$pix,'<p>Pagina <vb>'.$this->PageNo().'</vb> de <vb>{nb}</vb></p>',0,'R',0,0);
    }

}
      $copia=0;
  $x        = 620;  // barcode center
  $y        = 40;  // barcode center
  $inicioln =25;
$wlsetx=20;
$wlsetx2=150;

      for ($z=0; $z < $num ;$z++)
      {
	$row=pg_fetch_array($sql_result,$z);
        $row['id_tipopersona']=substr($row['id_tipopersona'],0,strpos($row['id_tipopersona'],"="));
        $row['id_tipotramite']=substr($row['id_tipotramite'],0,strpos($row['id_tipotramite'],"="));
        $row['idcolonia']=substr($row['idcolonia'],0,strpos($row['idcolonia'],"="));
        $row['iddelegacion']=substr($row['iddelegacion'],0,strpos($row['iddelegacion'],"="));
        $row['id_tipodocumentoiden']=substr($row['id_tipodocumentoiden'],0,strpos($row['id_tipodocumentoiden'],"="));
        $row['id_tipodocumentomoral']=substr($row['id_tipodocumentomoral'],0,strpos($row['id_tipodocumentomoral'],"="));
        $row['idestado_notasol']=substr($row['idestado_notasol'],0,strpos($row['idestado_notasol'],"="));

$wlpenvia=$row['nombre']." ".trim($row['apepat'])." ".trim($row['apemat']);
$wlrecibe=$row['persona_recibe'];
$wldesdocto=$row['descripcion_docto'];
$wldesasunto=$row['descripcion_asunto'];
$wlestatus=$row['descripcion_estatus'];
$row['condicionv']=$row['descripcion_condicionv'];
$wlasunto="";
      foreach ($me->camposmc as $index => $val)
      {
       if ($index!="fecharecibo" && $index!="id_cveasunto" && $index!="folioconsecutivo"  && $index!="" && $index!="fecha_alta" && $index!="usuario_alta"  
       && $index!="hora_cita" && $index!="apemat" 
       && $index!="val_lc"  && $index!="folio" && $row[$index]!="" 
       && $index!="idgrupo" 
       && $index!="idsexo"  && $index!="edad"  && $index!="condicionv"  && $index!="estatus"  && $index!="diastermino" && $index!="")
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
$pdf = new PDF();
$pdf->Fpdf('P','pt',array(270/$pix,280/$pix));
$pdf->SetStyle("p",'courier',"N",9);
$pdf->SetStyle("p1",'courier',"N",6);
$pdf->SetStyle("vb","courier",'B',12);
$pdf->SetAutoPageBreak(1,5/$pix);
$pdf->AddPage();

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

$pdf->AliasNbPages();
$pdf->SetFillColor(234,163,196);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2/$pix);

	$pdf->SetLineWidth(0.2/$pix);
	$pdf->SetFillColor(234,163,196);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',$wlsize2);
        $pdf->SetFont('Arial','B',9);
	$pdf->SetX($wlsetx/$pix);
	$pdf->Cell($wllargo2/$pix,6/$pix,'SOLICITUD DE TRÁMITE','B',1,'C',0);
	
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

        $wl='<p> Los datos personales recabados serán protegidos, incorporados y tratados en el Sistema de Datos Personales PROTOCOLOS DE LOS NOTARIOS PUBLICOS, el cual tiene su fundamento en la Ley del Notariado para el Distrito Federal, artículos 236, 237 y 238 Fracciones V, VI, VII, IX, XI, XIV, XIX y  239, cuya finalidad es la secrecía de los datos personales que contienen los protocolos en cada uno de los actos jurídicos que se celebran ante los diversos Notarios de la Ciudad de México, los cuales se custodian de manera definitiva en los acervos del Archivo General de Notarías de la Ciudad de México, así como de los apéndices que soportan la matricidad de cada uno de éstos y podrán ser transmitidos en los supuestos previstos en la Ley de Protección de Datos Personales para el Distrito Federal. 
Todos los datos son obligatorios y sin ellos no podrá acceder al servicio o completar el trámite de <vb>'.$row['id_tipotramite'].'.</vb></p>'.
         '<p> Asimismo, se le informa que sus datos no podrán ser difundidos sin su consentimiento expreso, salvo las excepciones previstas en la Ley. 
El responsable del Sistema de Datos Personales es: <vb>Mtra. Claudia Angélica Nogales Gaona</vb>, y la dirección donde podrá ejercer los derechos de acceso, rectificación, cancelación y oposición, así como la revocación del consentimiento es Candelaria de los Patos s/n, Planta Baja, Colonia Diez de Mayo, Delegación Venustiano Carranza, Ciudad de México, Código Postal 15290, Oficina de Información Pública de la Consejería Jurídica y de Servicios Legales. <p>'.
        '<p> El titular de los datos podrá dirigirse al Instituto de Transparencia, Acceso a la Información Pública, Protección de Datos Personales y Rendición de Cuentas de la Ciudad de México, donde recibirá asesoría sobre los derechos que tutela la Ley de Protección de Datos Personales para el Distrito Federal al teléfono: 56 36 46 36; correo electrónico: datospersonales@infodf.org.mx o en la página www.infodf.org.mx.</p>';
	$pdf->SetX($wlsetx/$pix);
        $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'J',0,'10,10,10,10');
        if ($row['id_tipopersona']=='PERSONA FÍSICA' || $row['id_tipopersona']=='AUTORIDAD') {
	    $pdf->Ln(5/$pix);
            $wl='<p><vb>DATOS DEL INTERESADO (PERSONA FÍSICA)</vb><p>'.
	    $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,'10,0,0,10');
            $wl='<p>Nombre(s): <vb> '.$row['nombre'].'</vb></p>';
            $wl=$wl.'<p> Apellido Paterno: <vb> '.$row['apepat'].'</vb> Apellido Materno: <vb> '.$row['apemat'].'</vb></p>';
            $wl=$wl.'<p> Identificación Oficinal: <vb> '.$row['id_tipodocumentoiden'].'</vb> Número / Folio: <vb> '.$row['folioiden'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,0,0,10');
            $pdf->AddPage();

        }
        if ($row['id_tipopersona']=='PERSONA MORAL') {
            $pdf->Ln(5/$pix);
            $wl='<p><vb>DATOS DEL INTERESADO (PERSONA MORAL)</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,2,'C',1,0);

            $pdf->SetX($wlsetx/$pix);
            $wl='<p>Denominación: <vb> '.$row['denominacion'].'</vb></p>';
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $pdf->AddPage();

            $wl='<p><vb>Acta Constitutiva o Póliza</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'C',1,0);

            $wl='<p>Tipo de Documento: <vb> '.$row['id_tipodocumentomoral'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);

            $wly=$pdf->GetY();
            $pdf->SetX($wlsetx/$pix);
            $wl='<p>Número o Folio del acta o Póliza: <vb> '.$row['folioidenmoral'].'</vb></p>';
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,0);
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $wl='<p> Fecha de otorgamiento: <vb> '.$row['fecha_otorgamiento_moral'].'</vb></p>';
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',0,0);

            $wl='<p>Nombre del Notario o Corredor Público: <vb> '.$row['nombre_notario_moral'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);

            $wly=$pdf->GetY();
            $pdf->SetX($wlsetx/$pix);
            $wl='<p>Número de notaria o Correduría: <vb> '.$row['nombre_notario_moral'].'</vb></p>';
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,5,'L',0,0);
            $wl='<p> Entidad Federativa: <vb> '.$row['entidad_federativa_moral'].'</vb></p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',0,0);
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto3/$pix,'<p></p>',3,'L',0,0);
 
            $pdf->Ln(5/$pix);
            $wl='<p><vb>DATOS DEL REPRESENTANTE LEGAL</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $wl='<p>Nombre(s): <vb> '.$row['nombrelegal'].'</vb></p>';
            $wl=$wl.'<p> Apellido Paterno: <vb> '.$row['apepatlegal'].'</vb> Apellido Materno: <vb> '.$row['apematlegal'].'</vb></p>';
            $wl=$wl.'<p> Identificación Oficinal: <vb> '.$row['id_tipodocumentolegal'].'</vb> Número / Folio: <vb> '.$row['folioidenlegal'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,0,0,10');

        }
        if ($row['id_tipopersona']=='NOTARIO') {
            $pdf->Ln(5/$pix);
            $wl='<p><vb>NOTARIO SOLICITANTE</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,2,'C',1,0);

            $pdf->SetX($wlsetx/$pix);
            $wl='<p>Nombre del notario: <vb> '.$row['nombrenotasol'].'</vb></p>';
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,'10,0,0,10');
            $pdf->AddPage();
            $wly=$pdf->GetY();
            $wl='<p>Número: <vb> '.$row['notasol'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,0,0,10');
            $wl='<p>Entidad: <vb> '.$row['idestado_notasol'].'</vb></p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',0,0);
            $wl='<p>Autorizado para la gestión del citado instrumento a: <vb> '.$row['autorizadopara'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,0,0,10');
        }

        if ($row['id_tipopersona']=='AUTORIDAD') {
            $pdf->Ln(5/$pix);
            $wl='<p><vb>DATOS DE LA AUTORIDAD</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,'10,0,0,10');
            $wl='<p>Nombre(s): <vb> '.$row['nombreaut'].'</vb></p>';
            $wl=$wl.'<p> Apellido Paterno: <vb> '.$row['apepataut'].'</vb> Apellido Materno: <vb> '.$row['apemataut'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,'10,0,0,10');

            $wly=$pdf->GetY();
            $wl='<p>Número de expediente: <vb> '.$row['expediente'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,5,'L',0,'10,0,0,10');
            $wl='<p>Número de oficio: <vb> '.$row['oficio'].'</vb></p>';
            $pdf->SetY($wly);
            $pdf->SetX($wlsetx2/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,$wl,6,'L',0,0);
            $wl='<p>Partes uno: <vb> '.$row['partes1'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,'10,0,0,10');
            $wl='<p>Partes dos: <vb> '.$row['partes2'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,0,0,10');
        }

        if ($row['id_tipopersona']!='AUTORIDAD') {
            $pdf->Ln(2/$pix);
            $wl='<p><vb>DOMICILIO PARA OIR Y RECIBIR NOTIFICACIONES Y DOCUMENTOS EN LA CIUDAD DE MÉXICO </vb><p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $wl='<p>Calle: <vb> '.$row['calle'].'</vb> No. Exterior: <vb> '.$row['numext'].'</vb> No. Exterior: <vb> '.$row['numint'].'</p>';
            ##$pdf->SetX($wlsetx/$pix);
            ##$pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $wl=$wl.'<p> Colonia: <vb> '.$row['idcolonia'].'</vb> Delegacion: <vb> '.$row['iddelegacion'].'</p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,0,0,10');
        }

            $pdf->Ln(2/$pix);
            $wl='<p><vb>INSTRUMENTO SOLICITADO</vb><p>'.
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,1,'C',1,0);
            $wl='<p>Escritura: <vb> '.$row['escr'].'</vb> Fecha de <vb>'.substr($row['fechaescr'],8,2).'</vb> de <vb>'.meses_espanol(intval(substr($row['fechaescr'],5,2))).'</vb> de <vb>'.substr($row['fechaescr'],0,4).'</vb></p>';
            #$pdf->SetX($wlsetx/$pix);
            #$pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,4,'L',0,0);
            $wl=$wl.'<p> Nombre del Notario: <vb> '.$row['nombrenotaescr'].'</vb> Notaría número: <vb> '.$row['notaescr'].'</vb></p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$wl,3,'L',0,'10,0,0,10');

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
            $wl='<p>Documentos con los que se acredite la personalidad, cuando se actúe a nombre de persona física o moral, original o copia certificada, y dos copias simples. (Ejemplo: Poder Notarial, Carta Poder, Poder Especial otorgado en Escritura Pública)</p>';
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
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2b/$pix,$wlalto2/$pix,'<p></p>',5,'L',0,'10,1,1,1');
            $wl='<p>Artículos 214, Fracción I, Inciso a, b, c y d; y 248, Fracción I, Inciso d, y fracción II, Inciso a del código fiscal de la Ciudad de México.</p>';
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
            if ($pdf->PageNo()==2) 
            { $pdf->AddPage(); }
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
            $pdf->Ln(10/$pix);
            $pdf->SetX($wlsetx/$pix);
            ##$pdf->Image($filename,);
            ##echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  ^M
            $tempx=gen_linea();
            //$doc = new DOMDocument(); 
            //$doc->loadHTML(gen_linea());
            $lc=substr($tempx,strpos($tempx,'lineacaptura')+12);
            $lc=substr($lc,strpos($lc,'value =')+8,20);
            $lcb=substr($tempx,strpos($tempx,'lineacapturaCB')+14);
            $lcb=substr($lcb,strpos($lcb,'value=')+7,32);
            $tot=substr($tempx,strpos($tempx,'total')+6);
            $tot=substr($tot,strpos($tot,'value =')+8);
            $tot=substr($tot,0,strpos($tot,'\''));
            $pdf->Ln(10/$pix);
            $lct='<p>Linea de captura <vb>'.$lc.'</vb> Monto <vb>'.$tot.'</vb><p>';
            $pdf->SetX($wlsetx/$pix);
            $pdf->WriteTag($wllargo2/$pix,$wlalto2/$pix,$lct,1,'C',1,0);

        $fontSize = 8;
        $marge    = 0;   // between barcode and hri in pixel
        $height   = 25;   // barcode height in 1D ; module size in 2D
        $width    = 1;    // barcode height in 1D ; not use in 2D
        $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
        $code     = $lc;
        $type     = 'code128';
        $black    = '000000'; // color in hexa
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','B',9);
        $pdf->SetLineWidth(0.4/$pix);
        $pdf->Ln(10/$pix);
        $pdf->SetX($wlsetx2/$pix);
        ##$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
        $data = Barcode::fpdf($pdf, $black, $pdf->GetX(), $pdf->GetY(), $angle, $type, array('code'=>$code), $width, $height);
        $pdf->SetFont('Arial','B',$fontSize);
        $pdf->SetTextColor(0, 0, 0);
        $len = $pdf->GetStringWidth($data['hri']);
        Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
        $pdf->TextWithRotation($pdf->GetX() + $xt, $pdf->GetY() + $yt, $data['hri'], $angle);

        $code=$lcb;
        $pdf->Ln(20/$pix);
        $pdf->SetX($wlsetx2/$pix);
        ##$data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
        $data = Barcode::fpdf($pdf, $black, $pdf->GetX(), $pdf->GetY(), $angle, $type, array('code'=>$code), $width, $height);
        $pdf->SetFont('Arial','B',$fontSize);
        $pdf->SetTextColor(0, 0, 0);
        $len = $pdf->GetStringWidth($data['hri']);
        Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
        $pdf->TextWithRotation($pdf->GetX() + $xt, $pdf->GetY() + $yt, $data['hri'], $angle);


            $pdf->Ln(10/$pix);
            $pdf->SetX($wlsetx/$pix);
            $filename = 'temp/test.png';
            $errorCorrectionLevel = 'L';
            $matrixPointSize = 4;
            QRcode::png($wlasunto, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            $pdf->Image($filename,$wlsetx/$pix+(100/$pix),$pdf->GetY(),60/$pix,0);




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
                         $inicioln =90;
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

   function gen_linea() {
        $dir_abs=".";
        $post_data = array();
##    $post_data['importe'] = '2937';
    $post_data['importe'] = '2.34';
    $post_data['escritura'] = '0';
    $post_data['cantidad'] = '1';
    $post_data['reduccion'] = '0';
    $post_data['art'] = '0';
    $post_data['frac'] = '0';
    $post_data['concepto'] = 'ag2079';
    $post_data['laDependencia'] = 'AG';
    $ch = curl_init("https://data.finanzas.cdmx.gob.mx/formato_lc/rpp/rpp_fij_resultado.php");
    curl_setopt($ch, CURLOPT_COOKIEJAR, $dir_abs."cookieFileName");//ubicacion de la cookie que se genera del inicio de sesion
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
   }

?>
