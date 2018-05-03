<?PHP
putenv("TZ=America/Mexico_City");
include("conneccion.php");
//include "conneccion.php";		
//$connection = pg_connect("host=localhost	dbname=forapi user='grecar' password='.grecar.'") or die("Error con clave temporal");
echo "<head>   ";
echo "<title>Gráficas</title>   ";
echo "<script src='graphs.js' type='text/javascript'></script> ";
//echo " <LINK id=estilo REL=StyleSheet HREF=pupan.css TYPE=\"text/css\" MEDIA=screen>\n";
echo "</head> ";

	// Genera estatus 
	if ($estatus!="")
	{
		$sql = "select * from contra.cat_estatus where estatus=$estatus ";
		//echo "<textarea>$sql</textarea>";
		$sql_result = pg_exec($connection,$sql);
		if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
		$row=pg_fetch_array($sql_result,0);
		$estatus=$row['descripcion'];
	}
	// Genera persona
	if ($persona!="")
	{
		$sql = "select * from contra.v_cat_personas where id_persona=$persona ";
		//echo "<textarea>$sql</textarea>";
		$sql_result = pg_exec($connection,$sql);
		if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
		$row=pg_fetch_array($sql_result,0);
		$persona=$row['nombre_completo'];
	}
	// Genera tramite
	if ($tramite!="")
	{
		$sql = "select * from contra.cat_tipo_tramite where id_tipotra=$tramite ";
		//echo "<textarea>$sql</textarea>";
		$sql_result = pg_exec($connection,$sql);
		if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
		$row=pg_fetch_array($sql_result,0);
		$tramite=$row['descripcion'];
	}
	// Genera titulos
	$sql = "select * from contra.v_titulos ";
	//echo "<textarea>$sql</textarea>";
	$sql_result = pg_exec($connection,$sql);
	if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
	$num= pg_numrows ($sql_result);	
	//echo "num: ".$num;
	if ($num==0) {echo "No existen titulos definidos para el reporte, consulte con el administrador del sistema"; die;}
	$row=pg_fetch_array($sql_result,0);
	$titulo1=$row['titulo1'];
	$titulo2=$row['titulo2'];
	$titulo3=$row['titulo3'];
	$titulo4=$row['titulo4'];
	$firma_n=$row['nombre_completo'];
	$firma_p=$row['puesto'];
	$area=$row['area'];

/*$sql = "select	area, ".
	"	extract (year from fechadocumento) as anio,	".
	"	extract (month from fechadocumento) as mes,	".
	"sum (case when liberado='N' then 1 else 0 end) as pendientes, ".
	"sum (case when liberado='S' then 1 else 0 end) as liberados, ".
	"sum (case when liberado='C' then 1 else 0 end) cerrados, ".
	"count (*) as turnos, ".
	"count (distinct folio) as folios ".
"	from contra.v_folios".(($iterno=="t") ? "_interno" : "" )." vp	".
"	where folio is not null	".
"	$wlfltro  ".
"group by 1,2,3 ".
"order by 1,2 asc,5 asc  ";*/

		//PENDIENTES
		if ($idtiporeporte==1 || $idtiporeporte==3)
		{
			$sql = "select	area, ".
				"	extract (year from fecharecibo) as anio,	".
				"	extract (month from fecharecibo) as mes,	".
				"sum (case when liberado='N' then 1 else 0 end) as pendientes, ".
				"sum (case when liberado='S' then 1 else 0 end) as liberados, ".
				"sum (case when liberado='C' then 1 else 0 end) cerrados, ".
				"count (*) as turnos, ".
				"count (distinct folio) as folios ".
				"	from contra.v_pendientes".(($iterno=="t") ? "_interno" : "" )." as v	".
				"	where v.id_organizacion=(select id_organizacion from contra.cat_personas where usename=current_user) 	".
				"	and liberado='N'	".
				"	$wlfltro ".
				"	group by 1,2,3 ".
				"	order by 1,2 asc,3 asc  ";
		//FOLIOS
		} else if ($idtiporeporte==2  || $idtiporeporte==4)
		{
			$sql = "select	area, ".
				"	extract (year from fecharecibo) as anio,	".
				"	extract (month from fecharecibo) as mes,	".
				"sum (case when liberado='N' then 1 else 0 end) as pendientes, ".
				"sum (case when liberado='S' then 1 else 0 end) as liberados, ".
				"sum (case when liberado='C' then 1 else 0 end) cerrados, ".
				"count (*) as turnos, ".
				"count (distinct folio) as folios ".
				"	from contra.v_folios".($iterno=="t" ? "_interno" : "" )." v ".
				"	where folio is not null	".
				($ventanilla=="t" ? "	and v.usuario_altat in ".
									"	(	select usename from cat_usuarios	".
									"	where usename in (	".
									"	select usename 	".
									"	from cat_usuarios_pg_group as capg	".
									"	left join pg_group as pg on  pg.grosysid=capg.grosysid	".
									"	where groname  in ('contra_ventanilla','contra_ventanilla_admon')))	" : "" ).
				"	".$wlfltro.
				"	group by 1,2,3 ".
				"	order by 1,2 asc,3 asc  ";
		//LIBERADOS
		} else 	if ($idtiporeporte==5 || $idtiporeporte==7)
		{
			$sql = "select	area, ".
				"	extract (year from fecharecibo) as anio,	".
				"	extract (month from fecharecibo) as mes,	".
				"sum (case when liberado='N' then 1 else 0 end) as pendientes, ".
				"sum (case when liberado='S' then 1 else 0 end) as liberados, ".
				"sum (case when liberado='C' then 1 else 0 end) cerrados, ".
				"count (*) as turnos, ".
				"count (distinct folio) as folios ".
				"	from contra.v_pendientes".(($iterno=="t") ? "_interno" : "" )." as v	".
				"	where v.id_organizacion=(select id_organizacion from contra.cat_personas where usename=current_user) ".
				"	and liberado='S'	".
				"	$wlfltro ".
				"	group by 1,2,3 ".
				"	order by 1,2 asc,3 asc  ";
		//TURNADOS
		} else if ($idtiporeporte==6  || $idtiporeporte==8)
		{
			$sql = "select	area, ".
				"	extract (year from fecharecibo) as anio,	".
				"	extract (month from fecharecibo) as mes,	".
				"sum (case when liberado='N' then 1 else 0 end) as pendientes, ".
				"sum (case when liberado='S' then 1 else 0 end) as liberados, ".
				"sum (case when liberado='C' then 1 else 0 end) cerrados, ".
				"count (*) as turnos, ".
				"count (distinct folio) as folios ".
				"	from contra.v_folios".($iterno=="t" ? "_interno" : "" )." v ".
				"	where folio is not null ".
				($ventanilla=="t" ? "	and v.usuario_altat in ".
									"	(	select usename from cat_usuarios	".
									"	where usename in (	".
									"	select usename 	".
									"	from cat_usuarios_pg_group as capg	".
									"	left join pg_group as pg on  pg.grosysid=capg.grosysid	".
									"	where groname  in ('contra_ventanilla','contra_ventanilla_admon')))	" : " and v.usuario_altat=current_user " ).
				"	".$wlfltro.
				"	group by 1,2,3 ".
				"	order by 1,2 asc,3 asc  ";
		}
//echo "<textarea>$sql</textarea>";
$sql_result = pg_exec($connection,$sql);
if (strlen(pg_last_error($connection))>0) { echo pg_last_error($connection);die; }
$num = pg_numrows($sql_result);
if ($num==0 )
{	echo "	<script language=\"JavaScript\">";
	echo "	alert ('No se encontraron registros');	";
	echo "	close ();	";
	echo "	</script>";
}

//ejemplo 1
/*
echo "	<script language=\"JavaScript\">";
for ($i=0; $i<$num; $i++)
{
	$row = pg_fetch_array($sql_result, $i);	
	echo "	document.write('<table><tr><td>".$row['area']."</td></tr>'); ";
	//instanciamos la gráfica 
	echo "	graph = new BAR_GRAPH('vBar'); ";
	//insertamos valores 
	echo "	graph.labelBGColor = '#A0B0C2';";
	echo "	graph.legend = '".$row['turnos']." turnos'; ";
	//echo "	graph.values = '".$row['liberados'].";".$row['turnos']."'; ";
	//echo "	graph.labels = 'a,b'; 	";
	echo "	graph.values = '".$row['pendientes'].",".$row['liberados'].",".$row['cerrados']."'; ";
	echo "	graph.labels = '".$row['pendientes']." PENDIENTES,".$row['liberados']." LIBERADOS,".$row['cerrados']." CERRADOS'; 	";
	//echo "alert ('$values');";
	//mostramos la gráfica en la página 
	echo "	document.write(graph.create()); ";
	echo "	document.write('<br><hr><br>'); ";
	echo "	document.write('</table>'); ";
}
echo "</script>";
*/

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
//echo meses_espanol(date('n'));
}

//ejemplo 2
$tipoesta='v';
echo "	<body background=\"img\bg.gif\">";

echo "	<style type=\"text/css\">	";
echo "	.gth	";
echo "	{	";
echo "		font-family: tahoma,verdana,arial;	";
echo "		font-size:8PT;	";
echo "		color:#0A1B34;	";
echo "		background-color:#A0B0C2;	";
echo "		text-align: center;	";
echo "		font-style: arial;	";
echo "		border-bottom: 1px solid #0A1B34;	";
echo "		border-right: 1px solid #0A1B3;	";
echo "	}	";
echo "	.gtd	";
echo "	{	";
echo "		font-family: tahoma,verdana,arial;	";
echo "		font-size:8PT;	";
echo "		color:#0A1B34;	";
echo "		background-color:#EAF2F7;	";
echo "		padding: 1px;	";
echo "		border-bottom: 1px solid #0A1B34  ;	";
echo "		border-right: 1px solid #0A1B34  ;	";
echo "		text-align: center;	";
echo "		overflow: auto; ";
echo "	}	";

echo "	</style>	";

echo "	<script language=\"JavaScript\">";
$areat="";
for ($i=0; $i<$num; $i++)
{
	
	$row = pg_fetch_array($sql_result, $i);	
	if ($areat!=$row['area'])
	{
		echo "	document.write('<table width=100% align=center><tr><th class=\"gth\">".$row['area']."</th></tr>'); ";
//			 "	#0A1B34; background-color:#A0B0C2; text-align: center;	font-style: arial; border-bottom: 1px solid #0A1B34; border-right: ".
//			 "	1px solid #0A1B3\">".$row['area']."</th></tr>'); ";
		echo "	document.write('<tr>'); ";
		echo "	document.write('<td class=\"gtd\"><b>Fecha de recepcion:</b> ".$fecharecibo." - ".$fecharecibofin.
			 " <br><b>Area:</b> ".($ventanilla=='t' ? 'J.U.D. DE VENTANILLA ÚNICA DE RECEPCIÓN Y SEGUIMIENTO DOCUMENTAL' : $area ).
			 ($puesto=='' ? "" : "<br><b>Puesto:</b> ".$row['area']).
			 ($persona=='' ? "" : "<br><b>Persona:</b> ".$persona).
			 ($estatus=='' ? "" : "<br><b>Estatus:</b> ".$estatus).
			 ($tramite=='' ? "" : "<br><b>Tipo de tramite:</b> ".$tramite).
			 "	</td>'); ";
		echo "	document.write('</tr>'); ";
		echo "	document.write('</table><hr>'); ";
		$areat=$row['area'];
	}
	//instanciamos la gráfica 
	echo "	graph = new BAR_GRAPH('".$tipoesta."Bar'); ";
	//insertamos valores 
	echo "	graph.labelBGColor = '#A0B0C2';";
	echo "	graph.titles='Periodo, &nbsp;Progreso&nbsp; ';";
	echo "	graph.titleBGColor='#0A1B34';";
	echo "	graph.titleColor='#A0B0C2';";
	echo "	graph.barColors = 'img/bar".$tipoesta."Green.jpg,img/bar".$tipoesta."Red.jpg,img/bar".$tipoesta."Purple.jpg';";
	echo "	graph.legend = 'LIBERADOS: ".$row['liberados'].",PENDIENTES: ".$row['pendientes'].",CERRADOS: ".$row['cerrados']."'; ";
	echo "	graph.values = '".$row['liberados'].";".$row['pendientes'].";".$row['cerrados']."'; ";
	echo "	graph.percValuesDecimals = 1;";
	echo "	graph.labels = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".meses_espanol($row['mes'])." ".$row['anio']." - Turnos: ".$row['turnos']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 	";
	echo "	graph.labelSpace = 50; 	";
	//echo "	graph.titles = 'Turnos,".$row['turnos']."'; 	";
	//echo "alert ('$values');";
	
	//mostramos la gráfica en la página 
	echo "	document.write(graph.create());";
	echo "	document.write('<hr>'); ";
}
echo "</script>";

/*
echo "	<script language=\"JavaScript\">";
//instanciamos la gráfica 
echo "	graph = new BAR_GRAPH('pBar'); ";
//insertamos valores 
echo "	graph.labelBGColor = '#A0B0C2';";
echo "	graph.legend = 'x,y,z,w'; ";
echo "	graph.values = '10;100'; ";
echo "	graph.labels = 'a'; 	";
//mostramos la gráfica en la página 
echo "	document.write(graph.create()); ";
echo "</script>";*/

echo $values;

?>