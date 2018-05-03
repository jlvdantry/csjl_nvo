<?
session_start();
 ?>
<HTML>
<head>
<title>Secretaria de Finanzas </title>
</head>
<BODY>


<?
$connection = @pg_connect("host=$servidor
                          dbname=$bada
                          user=$parametro1p
                          password=$parametro2p");
if ( $connection == "" ) { 
     unset($parametro2);
     unset($parametro1);
     $connection = pg_connect("host=$servidor dbname=$bada user='jlv' password='jlv026'");
     if ($connection != "") {
        $sql="select grababitacora(0, 23, 0,0,current_timestamp,current_timestamp,'usuario="
             .$parametro1p." pwd=".$parametro2p."')"; 
        $sql_result = pg_exec($connection,$sql);
        }
                   die ("<br><div align=center> <blink> <font color=red size=+2>"
                        ."No se pudo conectar a el servidor con el usuario ".$parametro1p."</blink> </div></font>"); }

$sql ="SELECT usesuper from pg_shadow where usename='"
      .$parametro1p."'"." and passwd = '".$parametro2p."'";
$sql_result = pg_exec($connection,$sql)
              or die("El usuario no existe ".$parametro1p);
$num = pg_numrows($sql_result);
if ( $num == 0 ) {
   die ("<br><div align=center> <blink> <font color=red size=+2>USUARIO INVALIDO</blink> </div></font>");
                } else {
                session_register("servidor");
                session_register("bada");
                session_register("parametro1");
                session_register("parametro2"); 
                $parametro1=$parametro1p;
                $parametro2=$parametro2p;
                $sql="select grababitacora(0, 3, 0,0,current_timestamp,current_timestamp,'internet ".pg_tty()."')";
                $sql_result = pg_exec($connection,$sql)
                               or die("No se pudo grabar en la bitacora ");
                $num = pg_numrows($sql_result);
                if ( $num == 0 ) {
                   die ("<br><div align=center> <blink> <font color=red size=+2>"
                        ."No Genero el serial de la bitacora</blink> </div></font>");
                } else {
                   $Row = pg_fetch_array($sql_result, 0);
                   session_register("wlserial");
                   $wlserial=$Row[0]; 
                }
                       }
?>
                 <input type=submit value=Entrar name=matriz onclick='neww2=window.open("opciones.php", "siscor", "dependent,resizable=yes")'></input>
                        <input type=reset value="Limpiar"> </input> 


</BODY>
</HTML>
