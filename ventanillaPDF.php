<?PHP
error_reporting(E_ALL & ~E_NOTICE);
putenv("TZ=America/Mexico_City");
require_once("menudata.php");
include("conneccion.php");
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
	$area=$row['area'];
      //echo "asunto=".$id_cveasunto;
      //die;
      include("idformas.php");
      $me = new menudata();
      $me->connection=$connection;
      $me->idmenu=$repor[$id_cveasunto];
      $me->filtro=$filtro;
      if ($id_cveasunto=="51") {
        $me->filtro.=" and (select count(*) from contra.ope_turnados ot2 where ot2.usuario_alta in (select usename from contra.dame_usuariosdelarea()) and contra.v_gestioncontermino.folioconsecutivo=ot2.folioconsecutivo and ot2.id_tipotra=2)>0";
      }
      $me->damemetadata();
      $me->camposmf["fuente"]=str_replace("fecha_alta desc","fecha_alta asc",$me->camposmf["fuente"]);
      $me->camposmf["fuente"]=str_replace("folio desc","folio asc",$me->camposmf["fuente"]);
      ##echo "fuente:".$me->camposmf["fuente"]; die();
      $sql_result = @pg_exec($connection,$me->camposmf["fuente"]);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$me->camposmf["fuente"]."<br>".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	if ($num==0 )
	{
        	echo "	<script language=\"JavaScript\">";
		echo "	alert ('No se encontraron registros $num');	";
		echo "	close ();	";
		echo "	</script>";
		die;
	}
	
// :::: parametros de las celdas de titulos ::::
// tamaño de letra
$wlsize1=8;
// alto de celda
$wlalto1=6.5;
// largo de celda
$wllargo1=50;

// :::: parametros de las celdas de datos ::::
// tamaño de letra
$wlsize2=8;
// alto de celda
$wlalto2=6;
// largo de celdas
$wllargo2=180;
$wllargo2b=45;
$wllargo2c=50;

// :::: valor para establecer las abscisas de la celdas ::::
$wlsetx=20;
$wlsetx2=140;

define('FPDF_FONTPATH','font/');
require('cellpdf.php');

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

class PDF extends CellPDF
{
function Header()
{
	global $sql_result;
	global $titulo1;
	global $titulo2;
	global $titulo3;
	global $titulo4;
	global $wlsetx;
	global $wlsize1;
	global $wlsize2;
	global $wllargo1;
	global $wllargo2b;
	global $wllargo2;
	global $wlalto1;
	global $wlalto2;
	global $fecharecibofin;
	global $fecharecibo;
	global $puesto;
	global $area;
	global $foliosunicos;
	global $iterno;
	global $estatus;
	global $ventanilla;
	global $me;
	
	$this->SetTextColor(0);
	$this->SetFont('Arial','B',7);
        $this->Image('img/decidiendojuntos.jpg',17,8,20,0);
        $this->Image('img/cejur_01.JPG',300,8,20,0);
	$this->SetLineWidth(0.4);
	$this->Line(50,8,50,28);
	$this->SetY(10);
	$this->cell(43,4,'',0,0,'L',0);
	$this->cell(0,4,$titulo1,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo2,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
	$this->cell(0,4,$titulo3,0,1,'L',0);
	$this->cell(43,4,'',0,0,'L',0);	
//	$this->cell(0,4,$titulo4,0,1,'L',0);
	$this->Ln(6);
	
	$this->SetLineWidth(0.2);
	$this->SetFillColor(238,242,247);
	$this->SetTextColor(0);
	$this->SetFont('Arial','BI',7);
		
	$num= pg_numrows ($sql_result);	
        $row=pg_fetch_array($sql_result,0);
	$this->SetFont('Arial','',9);
        $this->SetX(10);
	$this->Cell(10,$wlalto1,'Tramite:',0,0,'L',0);
	$this->Cell(50,$wlalto1,substr($row["id_cveasunto"],0,strpos($row["id_cveasunto"],"=")),'LB',0,'L',0);
	$this->Cell(25,$wlalto1,'Fecha de recepcion:',0,0,'L',0);
	$this->Cell(25,$wlalto1,$row["fecharecibo"],'LB',0,'C',0);
	$this->Cell(8,$wlalto1,'Folios:',0,0,'L',0);
	$this->Cell(8,$wlalto1,$num,'LB',1,'C',0);
	$this->Ln(3);
	$this->SetX(10);
	$this->SetFont('Arial','B',$wlsize1);
      $this->Cell(10,$wlalto1,'No.',0,0,'L',0);
      foreach ($me->camposmc as $index => $val)
      {
       if ($index!="fecharecibo" && $index!="id_cveasunto" && $index!="folioconsecutivo"  && $index!="" && $index!="fecha_alta" && $index!="usuario_alta"  && $index!="apepat" && $index!="apemat" && $index!="val_lc")
       {
          if ($index=="nombre") { $val["size"]="80"; }
          if ($index=="id_organizacion") { $val["size"]="50"; $val["male"]="50";}
          if ($index=="estatus") { $val["size"]="20"; }
//          $this->Cell((($val["size"]=="") ? 10 : $val["size"]),$wlalto1,$val["descripcion"],0,0,'L',0);
          if ($val["fuente_campodep"]=="") {
          $this->Cell((($val["size"]=="") ? 10 : $val["size"]),$wlalto1,$val["descripcion"],0,0,'L',0);
          } else {
          $this->Cell((($val["male"]=="") ? 20 : $val["male"]),$wlalto1,$val["descripcion"],0,0,'L',0);
          }

       }
      }
	$this->Cell(30,$wlalto1,'Entrega',0,0,'L',0);
	$this->Cell(30,$wlalto1,'Recibo',0,1,'L',0);
	$this->Ln(1);
}

function Footer()
{
	global $parametro1;
	
    $this->SetY(-15);
    //Select Arial italic 8
    $this->SetFont('Arial','B',7);
    //Print current and total page numbers

    //$this->Cell(180,8,'',0,0,0,0);
    //$this->Cell(50,8,'FIRMA Y SELLO DEL AREA','T',1,'C',0);
    
	//$this->Image('img/logo3_2007.JPG',8,198,12,0);
	//$this->Image('img/logo2_2007_color.JPG',259,198,12,0);
	$this->SetX(10);
	$this->Cell(40,6,'Fecha de emisiòn: ','LBT',0,'C',1);
	$this->Cell(40,6,meses_espanol(date('n')).date(" j ").'de'.date(" Y,"),'BT',0,'L',1);
	$this->Cell(30,6,'Hora de emisiòn: ','BT',0,'C',1);
	$this->Cell(30,6,date("g:i:s a,"),'BT',0,'L',1);
	$this->Cell(30,6,'Usuario de emisión: ','BT',0,'C',1);
	$this->Cell(40,6,$parametro1.',','BT',0,'L',1);
	$this->Cell(100,6,'Pagina '.$this->PageNo().'/{nb}','BTR',0,'R',1);
}
}

$pdf = new PDF();
$pdf->Fpdf('L','mm','Legal');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFillColor(238,242,247);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',$wlsize2);
$pdf->SetLineWidth(0.2);

// :::: inicio del contenido del documento ::::

$num= pg_numrows ($sql_result);	
$e=0;
for ($i=0; $i<$num; $i++)
{
	$row=pg_fetch_array($sql_result,$i);
/*
	$fechat2=$row['fecha_altat'];
	if ($fechat1!=$fechat2)
	{
		
		$fechat1=$fechat2;
		$pdf->SetFont('Arial','BI',$wlsize2);
		$pdf->Cell(40,$wlalto1,'Folios turnados: '.$row['fecha_altat'],0,1,'L',0);
		$pdf->Ln(1);
		$e=0;
	}
*/
	$e++;
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',$wlsize2);
//	$pdf->Cell(5,$wlalto2,$e,0,0,'C',0);
	$pdf->Cell(10,$wlalto2,$i+1,'BR',0,'C',1);
//	$pdf->Cell(1,$wlalto2,'',0,0,0,0);
      $wlnombre="";
      foreach ($me->camposmc as $index => $val)
      {
       if ($index!="fecharecibo" && $index!="id_cveasunto" && $index!="folioconsecutivo"  && $index!="" && $index!="fecha_alta" && $index!="usuario_alta"  && $index!="val_lc")
       {
          $pon=$row[$index];
          if ($index=="monto" && $row["estatus"]!="CANCELADO=4")
          { $wlmonto=$wlmonto+$row[$index]; $wlgetx=$pdf->GetX();}
          if ($index=="folio")
          {  $pon=substr($row[$index],strpos($row[$index],"-")+1); }
          if ($index=="nombre" || $index=="apepat" || $index=="apemat")
          {  $wlnombre=$wlnombre." ".trim($row[$index]); }
          if ($index=="apemat") {$pon=$wlnombre; $val["size"]="80"; }
          if ($index=="estatus") { $val["size"]="20"; }
          if ($index=="id_organizacion") { $val["size"]="50"; $val["male"]="50";}
          if ($index=="nombre" || $index=="apepat" )
          {   continue ;}
          if ($val["fuente_campodep"]=="") { 
/*
             if ($index=="asunto") {
             $pdf->MultiCell((($val["size"]=="") ? 10 : $val["size"]),$wlalto2,$pon,'BR','L',0);
             } else { 
             $long=strlen($pon);
             if ($long<=100) {
                 $long=$wlalto2;
             }  elseif ($long<=200) {
                 $long=$wlalto2*2;
             }  elseif ($long<=300) {
                 $long=$wlalto2*3;
             }
*/
             $pdf->Cell((($val["size"]=="") ? 10 : $val["size"]),$wlalto2,$pon,'BR',0,'L',0);
//             }
          } else {
          $pdf->Cell((($val["male"]=="") ? 20 : $val["male"]),$wlalto2,$pon,'BR',0,'L',0);
          //$pdf->MultiCell(0,$wlalto2,$row['ast'].": ".$row['asunto'],'BR','L',1);
          }

       }
      }

	$pdf->Cell(30,$wlalto2,'','BR',0,'L',0);
	$pdf->Cell(30,$wlalto2,'','BR',1,'L',0);
	$pdf->Ln(1);
	//$e++;
	//if ($e==30 && ($num>30 || $num>60 || $num>90 || $num>120)) {$e=0; $pdf->AddPage();}
}
$pdf->SetX($wlgetx);
$pdf->Cell(10,$wlalto2,$wlmonto,0,0,'L',0);
//die;

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
