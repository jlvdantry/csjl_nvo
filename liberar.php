<? session_start();
function termina()
{
  echo "</respuesta>";
  echo "</channel>" ;
  echo "</rss>" ;
  die();
}
   header("Content-type: text/xml");
   header("Pragma: public");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  echo "<?xml version='1.0' encoding='UTF-8'?>\n";
  echo "<rss version=\"2.0\">\n";
  echo "<channel>";
  echo "<respuesta>";
  $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2")
        or die("No se pudo establecer la Comunicacion. ");
  $sql="select count(*)  from contra.ope_turnados where folioconsecutivo=".$wlfolioconsecutivo.
       " and liberado='N'";
  $sql_result = pg_exec($connection,$sql)
        or die("No se pudo hacer el query."+$sql );
  $Row = pg_fetch_array($sql_result, $z);
  if ($Row[0]==0) { echo "No ha turnado aun"; termina();}
  $sql="update contra.ope_turnados set liberado='S', fecha_modifico=current_timestamp(0) where folioconsecutivo=".$wlfolioconsecutivo.
       " and liberado='N'";
  $sql_result = pg_exec($connection,$sql)
        or die("No se pudo hacer el query."+$sql );
  $sql="select count(*)  from contra.ope_turnados where folioconsecutivo=".$wlfolioconsecutivo.
       " and liberado='N'";
  $sql_result = pg_exec($connection,$sql)
        or die("No se pudo hacer el query."+$sql );
  $Row = pg_fetch_array($sql_result, $z);
  if ($Row[0]==0) { echo "tramite liberado"; }
  else      { echo "No se libero el tramite"; };
  termina();
?>
