<? session_start();
  $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2")
        or die("No se pudo establecer la Comunicacion. ");
  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
  $sql="select * from contra.".$tabla;
  $sql_result = pg_exec($connection,$sql)
        or die("No se pudo hacer el query. " );
  $num = pg_numrows($sql_result);
  $i = pg_numfields($sql_result);
  echo "<".$tabla."s>";
   for ($z=0; $z < $num ;$z++)
   {
        $Row = pg_fetch_array($sql_result, $z);
        echo "<".$tabla.">";
        for ($j = 0; $j < $i; $j++)
        {
          echo "<".pg_fieldname($sql_result, $j).">".$Row[pg_fieldname($sql_result, $j)]."</".pg_fieldname($sql_result, $j).">\n";
        };
        echo "</".$tabla.">";
   };

  echo "</".$tabla."s>" ;
?>
