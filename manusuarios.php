<? session_start(); 
echo "<HTML>";
echo "<head>";
echo " <LINK REL=StyleSheet HREF=\"estilo_siscor_gre.css\" TYPE=\"text/css\" MEDIA=screen>\n";
   include("mensajes.php"); 
?>
<title>Secretaria de Finanzas </title>
</head>
<?
function consulta($wldescripcion,$connection)
{
   $sql = " select trim(usename) as usename,nombre,apepat,apemat,correoe,estatus from cat_usuarios  order by nombre"; 
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay opciones ");die(); };
##   echo "<tr align=center><td> <input type=submit value='Alta de Opcion' name=matriz ".
##        "onclick='validausuario(\"alta\");return false'></input>\n";

##   echo "  <table> \n";
   echo "  <table class=\"sortable\" id=\"tabconsulta\"> \n";
   echo "  <caption> Mantenimiento a Usuarios</caption> \n";

###  titulos de la tabla
   echo "<tr>";
   $Row1 = pg_fetch_array($sql_result, 0);
   foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else { echo "<th>".Key($Row1)."</th>"; }
          next($Row1);
                               };
   echo "<th>Baja</th>";
   echo "<th>Grupos</th>";
   echo "<th>Permisos</th>";
   echo "</tr>";

##  campos para capturar en altas o cambios
##   echo "<tr>";
##   foreach ($Row1 as $value)    {
##          if (Key($Row1)<"100") { }
##          else { echo "<td><input type=text name='".Key($Row1)."'</input></td>"; }
##          next($Row1);
##                               };
##   echo "</tr>";
   echo " <input type=hidden name=wlopcion ></input>\n";
   echo " <input type=hidden name=usename ></input>\n";
   echo " <input type=hidden name=nombre ></input>\n";


###    desplegas filas
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        echo "<tr>";
        foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else {
          echo "<td>".$Row[Key($Row1)]."</th>"; }
          next($Row1);
                               };
##        echo "<td><a href=\"javascript:validausuario(  'baja','".$Row[usename]."','".$Row[nombre]."')\">baja</a></td>\n";
        echo "<td><input type=image src='img/baja.gif' title='baja de usuario ".$Row[usename]."' onclick=\"javascript:validausuario(  'baja','".$Row[usename]."','".$Row[nombre]."')\"></td>\n";
        echo "<td><a href=\"javascript:validausuario('manttogruopc','".$Row[usename]."','".$Row[nombre]."')\">grupos</a></td>\n";
        echo "<td><a href=\"javascript:validausuario('permisos','".$Row[usename]."','".$Row[nombre]."')\">permisos</a></td>\n";
        echo "</tr>";
       };
   echo "</table>";
}


function conhis($wldescripcion,$usename,$connection)
{
   $sql = " select hmg.usename as descripcion,g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_cat_usuarios_pg_group hmg, pg_group g where  ".
          " hmg.grosysid = g.grosysid and hmg.usename = '".$usename."' order by fecha_alta desc ";
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay historial del usuario ");die(); };
   echo "  <table width=100% cellspacing=0 cellpadding=8 border=1 align=center> \n";
   echo "<tr>";
   echo "<th width=60%> GRUPO </th>";
   echo "<th width=25%> FECHA DEL MOVIMIENTO </th>";
   echo "<th width=10%> USUARIO </th>";
   echo "<th width=5%>  MOVIMIENTO </th>";
   echo "</tr>"; 
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        if ($Row[descripcion]<>"") {
           echo "<tr>";
           echo "<td width=60%> $Row[groname] </td>";
           echo "<td width=25%> $Row[fecha_alta] </td>";
           echo "<td width=10%> $Row[usuario_alta] </td>";
           echo "<td width=5%> $Row[cve_movto] </td>";
           echo "</tr>"; 
                                   }
       };
   echo "</table>";
}


function manttogrupopc($wldescripcion,$usename,$connection)
{
              $sql = " select grosysid as idmenu,groname as descripcion from pg_group  where grosysid not in ".
                     "(select grosysid from cat_usuarios_pg_group where trim(usename)='".$usename."')";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
              $num = pg_numrows($sql_result);
                  echo "  <table> ";
                  echo "  <caption>Usuario: ".$usename."</caption>";
                  echo " <tr><th> Grupos no Asignados al usuario </th><th></th><th>Grupos asignados al usuario</th></tr>";
                  echo " <th width=45%> \n";
                  echo "<select name=\"wldescripcionmenu\"  size=20 width=200 align=center> \n";
                  if ( $num == 0 ) { echo "<option value='0'>*No hay grupos a asignar al usuario *</option>\n"; };
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[descripcion]<>"") {
                     echo "<option value='$Row[idmenu]'> $Row[descripcion] </option>"; 
                                              }
                  };
                  echo "</select></td>";
                  echo "<th width=10%> <input type=submit value='Asigna Grupo' ".
                       "name=matriz onclick='asignamenu(\"am1\");return false'></input>\n";
                  echo "<input type=submit value='Asigna Todos Grupos' name=matriz ".
                       "onclick='asignamenu(\"amt\");return false'></input>\n";
                  echo "<input type=submit value='Quita Grupo' name=matriz ".
                       "onclick='quitamenu(\"qm1\");return false'></input>\n";
                  echo "<input type=submit value='Quita todos Grupos' name=matriz ".
                       "onclick='quitamenu(\"qmt\");return false'></input>\n";
                  echo "<input type=hidden name=wlopcion </input>";
                  echo "<input type=hidden name=usename value=".$usename."></input>";
                  echo "<input type=hidden name=nombre value=".$nombre."></input>";
                  echo "<input type=submit value='Atras' name=matriz ".
                       "onclick='parent.history.back();return false'></input>\n";
                  echo "<input type=submit value='Historico' name=matriz ".
                       "onclick='document.forms[0].wlopcion.value=\"conhis\"'></input>\n";
                  $sql = " select grosysid as idmenu,groname as descripcion from pg_group  where grosysid in ".
                     "(select grosysid from cat_usuarios_pg_group where usename='".$usename."')";
                  $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
                  $num = pg_numrows($sql_result);
                  echo "<th width=45%> \n";
                  echo "<select name=\"wlmenuaquitar\" size=20 width=200 align=center > \n";
                  if ($num==0) 
                     { echo "<option value=\"\">- Grupos asignados a la opcion -</option>"; } ;
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[descripcion]<>"") {
                     echo "<option value='$Row[idmenu]'> $Row[descripcion] </option>";
                                              }
                  };
                  echo "</select></td>";
                  echo "</td>";
                  echo "</table>";
}

if ($wlusuario=="")
   { echo "<BODY onload=\"inicia()\" onClick=\"numeroClicks++\">\n"; } 
##   { echo "<BODY onload=\"sortables_init()\" onClick=\"numeroClicks++\">\n"; } 
else
   { echo "<BODY onload=\"inicia()\" onClick=\"numeroClicks++\">\n"; } 
##   { echo "<BODY onload=\"sortables_init()\" onClick=\"numeroClicks++\">\n"; } 
?>
<script src="sortable.js"></script>
<script language="JavaScript" type="text/javascript">
<?
include("val_inactividad_js.php");
echo "function asignamenu(queop)\n";
echo "{\n";
echo "    var s = document.forms[0].wldescripcionmenu;\n";
echo "    if (s.selectedIndex < 0  && queop == \"am1\" ) {\n";
echo "        alert('Primero debe seleccionar el grupo que va a asignar.');\n";
echo "        return false; } \n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function asignausuario(queop)\n";
echo "{\n";
echo "    var s = document.forms[0].wldescripcionusuario;\n";
echo "    if (s.selectedIndex < 0  && queop == \"au1\" ) {\n";
echo "        alert('Primero debe seleccionar el usuario que va a asignar.');\n";
echo "        return false; } \n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function quitamenu(queop)\n";
echo "{\n";
echo "    var s = document.forms[0].wlmenuaquitar;\n";
echo "    if (s.selectedIndex < 0 && queop == \"qm1\" ) {\n";
echo "        alert('Primero debe seleccionar el menu que va a quitar del grupo.');\n";
echo "        return false; } \n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function quitausuario(queop)\n";
echo "{\n";
echo "    var s = document.forms[0].wlusuarioaquitar;\n";
echo "    if (s.selectedIndex < 0 && queop == \"qu1\" ) {\n";
echo "        alert('Primero debe seleccionar el usuario que va a quitar del grupo.');\n";
echo "        return false; } \n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function validausuario(queop,usename,nombre)\n";
echo "{\n";
echo "if (queop == \"baja\") {\n";
echo "     if (window.confirm(\"Esta seguro que quiere dar de baja al usuario \"+usename))\n";
echo "        { \n";
echo "        } \n";
echo "     else { \n";
echo "        return;}; \n";
echo "                      }\n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].usename.value=usename;\n";
echo "    document.forms[0].nombre.value=nombre;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";
echo "</script>\n";
//include("enca.php");
?>
        <form method=POST action=manusuarios.php>
  <?
      $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2");
##       echo "  <table class=\"sortable\" id=\"tabusuarios\"> \n";
##       echo "  <caption> Mantenimiento a Usuarios</caption> \n";
##       echo "  <tr><th><hr></th></tr>	";
##       echo "descripcion ".$descripcion;
##       echo "opcion ".$wlopcion;
##       echo "usename ".$usename;
       switch ($wlopcion) 
           {
           case "":
              consulta($wldescripcion,$connection);
              break;

           case "baja":
              $sql = " select count(*) from cat_usuarios_pg_group where usename='".$usename."'" ;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row[0]>=1) { menerror("El usuario pertenece a un grupos");consulta($wldescripcion,$connection);die(); }
              $sql = " delete from cat_usuarios where usename ='".$usename."'";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
              $sql = " delete from pg_shadow where usename ='".$usename."'";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
              menok("El usuario se dio de baja"); 
              consulta($wldescripcion,$connection);
              break;

           case "manttogruopc":
              manttogrupopc($nombre,$usename,$connection);
              break;

           case "permisos":
              $sql="select autoriza_usuario('".$usename."')";
              $sql_result = pg_exec($connection,$sql)
                      or die(menerror("El usuario no se pudo autorizar"));
              menok("el usuarios se le reasignaron permisos");
              consulta($wldescripcion,$connection);
              break;

           case "consulta":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              consulta($wldescripcion,$connection);
              break;

           case "conhis":
              manttogrupopc($nombre,$usename,$connection);
              conhis($nombre,$usename,$connection);
              break;

           case "am1":
              $sql = " insert into cat_usuarios_pg_group (usename,grosysid) values ('".$usename."',".$wldescripcionmenu.")";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El grupo se asigno al usuario"); manttogrupopc($nombre,$usename,$connection); 
              break;

           case "amt":
              $sql = " insert into cat_usuarios_pg_group (usename,grosysid) ".
                     " select '".$usename."', grosysid ".
                     " from pg_group  where grosysid not in (select grosysid from cat_usuarios_pg_group where usename='".$usename."')";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los grupos se asignaron al usuario ".$nombre); manttogrupopc($nombre,$usename,$connection);
              break;

           case "qm1":
              $sql = " delete from cat_usuarios_pg_group where usename = '".$usename."' and grosysid=".$wlmenuaquitar;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El grupo se quito del usuario"); manttogrupopc($nombre,$usename,$connection);
              break;

           case "qmt":
              $sql = " delete from cat_usuarios_pg_group where usename='".$usename."'";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los grupos se quitaron del usuario"); manttogrupopc($nombre,$usename,$connection); 
              break;


           }
//        }
   ?>
		</font>
                </table>
</BODY>
</HTML>
