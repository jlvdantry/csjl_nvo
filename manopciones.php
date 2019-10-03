<? session_start() ?>
<HTML>
<head>
<? 
echo " <LINK REL=StyleSheet HREF=\"estilo_siscor_gre.css\" TYPE=\"text/css\" MEDIA=screen>\n";
   include("mensajes.php"); 
?>
<title>Secretaria de Finanzas </title>
</head>
<?
function  existegrupo($wldescripcion, $wlidmenu, $connection, $wlopcion)
{
   $sql = "select count(*) from forapi.menus where idmenu='".$wlidmenu."'";
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query existegrupo. ".$sql );
   $Row = pg_fetch_array($sql_result, 0);
    if ($Row[0]==0) { menerror("La opcion no existe");die(); }
}

function consulta($wldescripcion,$connection)
{
   if ($wldescripcion == "") {
      $sql = " select idmenu,descripcion,php,idmenupadre from forapi.menus  order by descripcion"; }
   else
      { $sql = " select idmenu,descripcion,php,idmenupadre from forapi.menus  where descripcion like '".$wldescripcion."%' order by descripcion "; };
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query consulta. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay opciones ");die(); };
##   echo "  <table> \n";
       echo "  <table class='sortable' id='tbinicio' > \n";
       echo "  <caption>Mantenimiento a Opciones</caption> \n";

###  titulos de la tabla
   echo "<tr>";
   $i = pg_numfields($sql_result);
        for ($j = 0; $j < $i; $j++)
        { echo "<th>".pg_fieldname($sql_result,$j)."</th>";
		}
            
/*       
   $Row1 = pg_fetch_array($sql_result, 0);
   foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else { echo "<th>".Key($Row1)."</th>"; }
          next($Row1);
                               };
*/                               
   echo "<th></th>";
   echo "<th></th>";
   echo "<th>Grupos</th>";
   echo "<th>Tablas</th>";
   echo "</tr>";

##  campos para capturar en altas o cambios
   echo "<tr>";
/*   
   foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else { echo "<th><input type=text name='".Key($Row1)."'</input></th>"; }
          next($Row1);
                               };
*/
   $i = pg_numfields($sql_result);
        for ($j = 0; $j < $i; $j++)
        { echo "<th><input type=text name='".pg_fieldname($sql_result,$j)."'</input></th>";
		}
                               
   echo "<th> <input type=image src='img/alta.gif' title='alta de opcion' value='Alta de Opcion' name=matriz ".
        "onclick='validausuario(\"alta\");return false'></input></th>\n";
   echo "<th> <input type=image src='img/busca.gif' title='Busca opcion' value='Busca de Opcion' name=busca ".
        "onclick='validausuario(\"consulta\");return false'></input></th>\n";
   echo "</tr>";
   echo " <input type=hidden name=wlopcion ></input>\n";


###    desplegas filas
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        echo "<tr>";
   $mi = pg_numfields($sql_result);
        for ($j = 0; $j < $mi; $j++)
        { echo "<td>".$Row[pg_fieldname($sql_result,$j)]."</td>";
		}        
        
/*        
        foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else {
          echo "<td>".$Row[Key($Row1)]."</td>"; }
          next($Row1);
                               };
*/                               
##        echo "<td><a href=\"javascript:validausuario(  'baja','".$Row[idmenu]."','".$Row[descripcion]."')\">x</a></td>\n";
        echo "<td><input type=image title='Da de baja la opcion' src='img/baja.gif' onclick=\"javascript:validausuario(  'baja','".$Row[idmenu]."','".$Row[descripcion]."')\"></input></td>\n";
##        echo "<td><a href=\"javascript:validausuario(      'cambio','".$Row[idmenu]."','".$Row[descripcion]."')\">x</a></td>\n";
        echo "<td><input title='Cambia la opcion' type=image src='img/cambio.bmp' onclick=\"javascript:validausuario(      'cambio','".$Row[idmenu]."','".$Row[descripcion]."')\"></td>\n";
        echo "<td><a href=\"javascript:validausuario('manttogruopc','".$Row[idmenu]."','".$Row[descripcion]."')\">x</a></td>\n";
        echo "<td><a href=\"javascript:validausuario('manttogruusu','".$Row[idmenu]."','".$Row[descripcion]."')\">x</a></td>\n";
        echo "</tr>";
       };
   echo "</table>";
}


function consultatabla($wltablename,$wlidmenu,$connection)
{
   if ( $wltablename=="" ) { $sql = " select relname as tablename from pg_class where substr(relname,1,3)<>'pg_' ".
                                    " and relkind in ('r', 'S', 'v') ".
                                    " and trim(relname) not in (select trim(tablename) from forapi.menus_pg_tables where ".
                                    " idmenu=".$wlidmenu.") order by 1"; }
   else {  $sql = " select relname as tablename from pg_class where relname like '".$wltablename."%'".
                  " and relkind in ('r', 'S', 'v') ".
                  " and trim(relname) not in (select trim(tablename) from forapi.menus_pg_tables where ".
                  " idmenu=".$wlidmenu.") ".
                  " and substr(relname,1,3)<>'pg_' ".
                  " order by 1 "; }
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query consultatabla. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay opciones ");die(); };
   echo "  <table> \n";
   echo "<caption>TABLAS NO ASIGNADAS</caption>";
   echo "<tr>";
   echo "<th > Descripcion </th>";
   echo "</tr>"; 
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        if ($Row[tablename]<>"") {
           echo "<tr>";
           echo "<td > $Row[tablename] </td>";
           echo "</tr>"; 
                                   }
       };
   echo "</table>";
}

function consultatablaasig($wltablename,$wlidmenu,$connection)
{
   if ( $wltablename=="" ) { $sql = " select * from forapi.menus_pg_tables where idmenu=".$wlidmenu." order by tablename"; }
   else {  $sql = " select * from forapi.menus_pg_tables where tablename like '".$wltablename.
                  "%' and idmenu=".$wlidmenu." order by tablename "; }
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query consultatablaasig. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay tablas asignadas ");die(); };
   echo "<table> \n";
   echo "<caption>TABLAS ASIGNADA</caption>";
   echo "<tr>";
   echo "<th > Descripcion </th>";
   echo "<th > Select </th>";
   echo "<th > Insert </th>";
   echo "<th > Update </th>";
   echo "<th > Delete </th>";
   echo "<th > All </th>";
   echo "<th > Fecha Alta </th>";
   echo "<th > Fecha Modifico </th>";
   echo "<th > Usuario Alta </th>";
   echo "<th > Usuario Modifico </th>";
   echo "<th > Clave Movto </th>";
   echo "</tr>"; 
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        if ($Row[tablename]<>"") {
           echo "<tr>";
           echo "<td > $Row[tablename] </td>";
           echo "<td > $Row[tselect] </td>";
           echo "<td > $Row[tinsert] </td>";
           echo "<td > $Row[tupdate] </td>";
           echo "<td > $Row[tdelete] </td>";
           echo "<td > $Row[tall] </td>";
           echo "<td > $Row[fecha_alta] </td>";
           echo "<td > $Row[fecha_modifico] </td>";
           echo "<td > $Row[usuario_alta] </td>";
           echo "<td > $Row[usuario_modifico] </td>";
           echo "</tr>"; 
                                   }
       };
   echo "</table>";
}

function consultahistab($wltablename,$wlidmenu,$connection)
{
   if ( $wltablename != "") 
    { $sql = " select * from his_menus_pg_tables where idmenu=".$wlidmenu." and tablename='".$wltablename."' order by fecha_alta desc " ; }
   else
    { $sql = " select * from his_menus_pg_tables where idmenu=".$wlidmenu."order by fecha_alta desc " ; }
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query consultahistab. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay movimientos en el historioo ");die(); };
   echo "<table> \n";
   echo "<caption>Historico de Movimientos a tablas</caption>";

###  titulos de la tabla
   echo "<tr>";
   $Row1 = pg_fetch_array($sql_result, 0);
   foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else { echo "<th>".Key($Row1)."</th>"; }
          next($Row1);
                               };
   echo "</tr>";

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
        echo "</tr>";
       };

   echo "</table>";
}


function conhis($wldescripcion,$wlidmenu,$connection)
{
   $sql = " select m.descripcion,g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_menus_pg_group hmg, forapi.menus m, pg_group g where hmg.idmenu = m.idmenu ".
          " and hmg.grosysid = g.grosysid and hmg.idmenu = ".$wlidmenu." order by fecha_alta desc ";
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query conhis. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay historial del grupo ");solgrupo($wldescripcion,$wlphp,$wlidmenu);die(); };
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

function conhisusu($wldescripcion,$connection)
{
   $sql = " select trim(m.nombre) || ' ' || trim(apepat) || ' ' || apemat as descripcion,".
          " g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_cat_usuarios_pg_group hmg, cat_usuarios m, pg_group g where hmg.usename = m.usename ".
          " and hmg.grosysid = g.grosysid and g.groname = '".$wldescripcion."' order by fecha_alta desc ";
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query conhisusu. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay historial del grupo ");solgrupo($wldescripcion,$wlphp,$wlidmenu);die(); };
   echo "  <table width=100% cellspacing=0 cellpadding=8 border=1 align=center> \n";
   echo "<tr>";
   echo "<th width=60%> USUARIO </th>";
   echo "<th width=25%> FECHA DEL MOVIMIENTO </th>";
   echo "<th width=10%> USUARIO </th>";
   echo "<th width=5%>  MOVIMIENTO </th>";
   echo "</tr>"; 
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        if ($Row[descripcion]<>"") {
           echo "<tr>";
           echo "<td width=60%>".$Row[descripcion]."</td>";
           echo "<td width=25%> $Row[fecha_alta] </td>";
           echo "<td width=10%> $Row[usuario_alta] </td>";
           echo "<td width=5%> $Row[cve_movto] </td>";
           echo "</tr>"; 
                                   }
       };
   echo "</table>";
}


function manttogrupopc($wldescripcion,$wlidmenu,$connection)
{
              $sql = " select grosysid as idmenu,groname as descripcion from pg_group  where grosysid not in ".
                     "(select grosysid from forapi.menus_pg_group where idmenu=".$wlidmenu.")";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
              $num = pg_numrows($sql_result);
                  echo "  <table> ";
                  echo "  <caption>Nombre de la opcion: ".$wldescripcion."</caption>";
                  echo " <tr><th> Grupos no Asignados a la opcion </th><th></th><th>Grupos asignados a la opcion</th></tr>";
                  echo " <th width=40%> \n";
                  echo "<select name=\"wldescripcionmenu\"  size=20 width=200 align=center> \n";
                  if ( $num == 0 ) { echo "<option value='0'>*No hay grupos a asignar a la opcion *</option>\n"; };
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[descripcion]<>"") {
                     echo "<option value='$Row[idmenu]'> $Row[descripcion] </option>"; 
                                              }
                  };
                  echo "</select></td>\n";
                  echo "<th> <input border=1 type=image title='Asigna Grupo' src='img/asigna1.gif' \n".
                       "name=matriz onclick='asignamenu(\"am1\");return false'></input>\n";
                  echo "<input type=image title='Asigna Todos Grupos' src='img/asignato2s.gif' name=matriz \n".
                       "onclick='asignamenu(\"amt\");return false'></input>\n";
                  echo "<input type=image title='Quita todos Grupos' src='img/quitato2s.gif' name=matriz \n".
                       "onclick='quitamenu(\"qmt\");return false'></input>\n";
                  echo "<input type=image title='Quita Grupo' src='img/quita1.gif' name=matriz \n".
                       "onclick='quitamenu(\"qm1\");return false'></input>\n";
                  echo "<input type=hidden name=wlopcion </input>\n";
                  echo "<input type=hidden name=idmenu value=".$wlidmenu."></input>";
                  echo "<input type=hidden name=descripcion value=".$wldescripcion."></input>";
                  echo "<input type=submit value='Atras' name=matriz ".
                       "onclick='parent.history.back();return false'></input>\n";
                  echo "<input type=submit value='Historico' name=matriz ".
                       "onclick='document.forms[0].wlopcion.value=\"conhis\"'></input>\n";
                  echo "</th>";
                  $sql = " select grosysid as idmenu,groname as descripcion from pg_group  where grosysid in ".
                     "(select grosysid from forapi.menus_pg_group where idmenu=".$wlidmenu.")";
                  $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
                  $num = pg_numrows($sql_result);
                  echo "<th width=40%> \n";
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

function manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection)
{
                  echo "<table> \n";
                  echo "<caption>Mantenimiento a tablas de la opcion ".$wldescripcion."</caption>";
                  echo "<tr align=center><th>Select<input type=checkbox name=wltselect style=\"background-color:#CDCDCD\" size=1 value="
                       .$wltselect."></input></td>";
                  echo "<th>Insert<input type=checkbox style=\"background-color:#CDCDCD\" name=wltinsert size=1 maxlength=1 value="
                       .$wltinsert."></input></td>";
                  echo "<th>Update<input type=checkbox style=\"background-color:#CDCDCD\" name=wltupdate size=1 maxlength=1 value="
                       .$wltupdate."></input></td>";
                  echo "<th>Delete<input type=checkbox style=\"background-color:#CDCDCD\" name=wltdelete size=1 maxlength=1 value="
                       .$wltdelete."></input></td>";
                  echo "<th>All<input type=checkbox style=\"background-color:#CDCDCD\" name=wltall size=1 maxlength=1 value="
                       .$wltall."></input></td>";
                  echo "</tr>";
                  echo "</table>";
           $sql = " select pgn.nspname || '.' || relname as tablename from pg_class, pg_namespace as pgn  where ".
                  " relkind in ('r', 'S', 'v') ".
                  " and pgn.oid=pg_class.relnamespace ".
                  " and pgn.nspname || '.' || trim(relname) not in (select trim(tablename) from forapi.menus_pg_tables where ".
                  " idmenu=".$wlidmenu.") ".
                  " and substr(relname,1,3)<>'pg_' ".
                  " order by 1 "; 
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. manttogruusu".$sql );
              $num = pg_numrows($sql_result);
                  echo "  <table> ";
                  echo " <tr><th>Tablas no Asignados a la opcion </th><th></th><th>Tablas asignados a la opcion</th></tr>";
                  echo " <th width=45%> \n";
                  echo "<select name=\"wltablename\"  size=20  width=200 align=center > \n";
                  if ( $num == 0 ) { echo "<option value='0'>*No hay tablas a asignar a la opcion *</option>\n"; };
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[tablename]<>"") {
                     echo "<option value='$Row[tablename]'> $Row[tablename] </option>"; 
                                              }
                  };
                  echo "</select></td>";
                  echo "<th width=10%> <input type=submit value='Asigna tabla' ".
                       "name=matriz onclick='validaaltatabla(\"altatabla\");return false'></input>\n";
                  echo "<input type=submit value='Asigna Todas tablas' name=matriz ".
                       "onclick='asignatodastablas(\"asignatodastablas\");return false'></input>\n";
                  echo "<input type=submit value='Quita tabla' name=matriz ".
                       "onclick='validabajatabla(\"bajatabla\");return false'></input>\n";
                  echo "<input type=submit value='Quita todas tablas' name=matriz ".
                       "onclick='bajatodastablas(\"bajatodastablas\");return false'></input>\n";
                  echo "<input type=submit value='Cambio Tabla' name=matriz ".
                       "onclick='validaaltatabla(\"cambiotabla\");return false'></input>\n";
                  echo "<input type=hidden name=wlopcion </input>";
                  echo "<input type=hidden name=idmenu value=".$wlidmenu."></input>";
                  echo "<input type=hidden name=descripcion value=".$wldescripcion."></input>";
                  echo "<input type=submit value='Atras' name=matriz ".
                       "onclick='parent.history.back();return false'></input>\n";
                  echo "<input type=submit value='Consulta Historico' name=matriz ".
                       "onclick='document.forms[0].wlopcion.value=\"consultahistab\"'></input>\n";
                  $sql = " select * from forapi.menus_pg_tables where ". 
                       " idmenu=".$wlidmenu." order by tablename "; 
                  $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
                  $num = pg_numrows($sql_result);
                  echo "<th width=45%> \n";
                  echo "<select name=\"wltablaaquitar\" size=20 width=200 align=center > \n";
                  if ($num==0) 
                     { echo "<option value=\"\">Tablas asignados a la opcion </option>"; } ;
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[tablename]<>"") {
                     echo "<option value='$Row[tablename]'> $Row[tablename]"."-".
                          $Row[tselect].",".
                          $Row[tinsert].",".
                          $Row[tupdate].",".
                          $Row[tdelete].",".
                          $Row[tall].
                          "  </option>";
                                              }
                  };
                  echo "</select></td>";
                  echo "</td>";
                  echo "</table>";
}

function solgrupo($wldescripcion,$wlphp,$wlidmenu)
{
       echo " <table align=center>\n ";
       echo " <tr><td align=center ><b>Teclee </b></td>";
       echo "<td><b>Id Menu</b><td><input type=text name=wlidmenu size=10 maxlength=10 value="
            .$wlidmenu."></input></td>";
       echo "<td><b>Descripcion</b><td><input type=text name=wldescripcion size=30 maxlength=100 value="
            .$wldescripcion."></input></td>";
       echo "<td><b>Php</b><td><input type=text name=wlphp size=30 maxlength=100 value="
            .$wlphp."></input></td>";
       echo "</tr>\n";
       echo " </table><hr>\n ";
       echo " <table align=center>\n ";
       echo " <input type=hidden name=wlopcion ></input>\n";
       echo "<tr><td> <input type=submit value='Alta de Opcion' name=matriz ".
            "onclick='validausuario(\"alta\");return false'></input>\n";
       echo "<td> <input type=submit value='Baja de Opcion' name=matriz ".
            "onclick='validausuario(\"baja\");return false'></input>\n";
       echo "<td> <input type=submit value='Cambio de Opcion' name=matriz ".
            "onclick='validausuario(\"cambio\");return false'></input>\n";
       echo "<td> <input type=submit value='Consulta de Opcion' name=matriz ".
            "onclick='document.forms[0].wlopcion.value=\"consulta\"'></input>\n";
       echo "</tr><tr>";
       echo "</table>\n ";
       echo "<table align=center>\n ";
       echo "<td> <input type=submit value='Mantenimiento de Opciones-Grupo' name=matriz ".
            "onclick='validausuario(\"manttogruopc\");return false'></input>\n";
       echo "<td> <input type=submit value='Consulta Historico Opciones-Grupo' name=matriz ".
            "onclick='validausuario(\"conhis\");return false'></input></tr>\n";
       echo "</tr><tr>";
       echo "<td> <input type=submit value='Mantenimiento de Opciones-Tablas' name=matriz ".
            "onclick='validausuario(\"manttogruusu\");return false'></input></tr>\n";
       echo "<td> <input type=submit value='Consulta Historico Opciones-Tablas' name=matriz ".
            "onclick='validausuario(\"conhisusu\");return false'></input></tr>\n";
       echo " </table>\n ";
}

if ($wlusuario=="")
##   { echo "<BODY onload=\"document.forms[0].wldescripcion.focus()\">\n"; } 
   { echo "<BODY onload=\"inicia()\" onClick=\"numeroClicks++\">\n"; } 
else
   { echo "<BODY onload=\"inicia()\" onClick=\"numeroClicks++\">\n"; } 
?>
<script src="sortable.js"></script>
<script language="JavaScript" type="text/javascript">;
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

echo "function validaaltatabla(queop)\n";
echo "{\n";
echo "if (document.forms[0].wltablename.value == \"\" && queop == \"altatabla\")  {\n";
echo "     window.alert(\"Primero debe de seleccionar una tabla no asignada\" );\n";
echo "     document.forms[0].wltablename.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (document.forms[0].wltablaaquitar.value == \"\" && queop == \"cambiotabla\")  {\n";
echo "     window.alert(\"Primero debe de seleccionar una tabla asignada para cambiar permisos\" );\n";
echo "     document.forms[0].wltablaaquitar.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "if (document.forms[0].wltselect.checked) {document.forms[0].wltselect.value=1;} ";
echo "if (document.forms[0].wltinsert.checked) {document.forms[0].wltinsert.value=1; }";
echo "if (document.forms[0].wltupdate.checked) {document.forms[0].wltupdate.value=1; }";
echo "if (document.forms[0].wltdelete.checked) {document.forms[0].wltdelete.value=1; }";
echo "if (document.forms[0].wltall.checked) {document.forms[0].wltall.value=1; }";
echo "if (document.forms[0].wltselect.value!=1 && document.forms[0].wltinsert.value!=1 && document.forms[0].wltupdate.value!=1  && document.forms[0].wltdelete.value!=1  && document.forms[0].wltall.value!=1 ) {\n";
echo "     window.alert(\"No ha seleccionado ningun permiso\" );\n";
echo "     document.forms[0].wltselect.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function validabajatabla(queop)\n";
echo "{\n";
echo "if (document.forms[0].wltablaaquitar.value == \"\" )  {\n";
echo "     window.alert(\"Primero debe de seleccionar una tabla ya asignada\" );\n";
echo "     document.forms[0].wltablaaquitar.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (window.confirm(\"Esta seguro que quiere dar la tabla de la opcion\"))\n";
echo "   { document.forms[0].wlopcion.value=\"bajatabla\";\n";
echo "     document.forms[0].submit();}\n";
echo "else\n";
echo "    {  document.forms[0].wltablaaquitar.focus();\n";
echo "       return; }\n";
echo "}\n";


echo "function bajatodastablas(queop)\n";
echo "{\n";
echo "if (window.confirm(\"Esta seguro que quiere dar de baja todas las tablas de la opcion\"))\n";
echo "   { document.forms[0].wlopcion.value=queop;\n";
echo "     document.forms[0].submit();}\n";
echo "else\n";
echo "   {    return; }\n";
echo "}\n";

echo "function asignatodastablas(queop)\n";
echo "{\n";
echo "if (document.forms[0].wltselect.checked) {document.forms[0].wltselect.value=1;} ";
echo "if (document.forms[0].wltinsert.checked) {document.forms[0].wltinsert.value=1; }";
echo "if (document.forms[0].wltupdate.checked) {document.forms[0].wltupdate.value=1; }";
echo "if (document.forms[0].wltdelete.checked) {document.forms[0].wltdelete.value=1; }";
echo "if (document.forms[0].wltall.checked) {document.forms[0].wltall.value=1; }";
echo "if (document.forms[0].wltselect.value!=1 && document.forms[0].wltinsert.value!=1 && document.forms[0].wltupdate.value!=1  && document.forms[0].wltdelete.value!=1  && document.forms[0].wltall.value!=1 ) {\n";
echo "     window.alert(\"No ha seleccionado ningun permiso\" );\n";
echo "     document.forms[0].wltselect.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (window.confirm(\"Esta seguro que quiere asignar todas las tablas a la opcion\"))\n";
echo "   { document.forms[0].wlopcion.value=\"asignatodastablas\";\n";
echo "     document.forms[0].submit();}\n";
echo "else\n";
echo "   {    return; }\n";
echo "}\n";

echo "function validausuario(queop,idmenu,descripcion)\n";
echo "{\n";
echo "    document.forms[0].idmenu.value=idmenu \n";
##echo "     alert(\"menu\"+document.forms[0].idmenu.value );\n";
echo "if (document.forms[0].descripcion.value == \"\" && queop == \"alta\")  {\n";
echo "     window.alert(\"Primero debe de teclear la descripcion de la opcion\" );\n";
echo "     document.forms[0].descripcion.focus();\n";
echo "     return;\n";
echo "                      }\n";
//  esto lo comentarice porque con submenus esto no es necesario
//echo "if (queop == \"alta\") {\n";
//echo "     if (document.forms[0].php.value == \"\") {\n";
//echo "        window.alert(\"Primero debe de teclear el php \" + queop);\n";
//echo "        document.forms[0].php.focus();\n";
//echo "        return;}; \n";
//echo "                      }\n";

echo "if (queop == \"manttogruopc\") {\n";
echo "   document.forms[0].descripcion.value=descripcion;\n";
echo "                      }\n";

echo "if (queop == \"manttogruusu\") {\n";
echo "   document.forms[0].descripcion.value=descripcion;\n";
echo "                      }\n";

echo "if (queop != \"alta\"  ) {\n";
echo "         if (document.forms[0].idmenu.value == \"\") {\n";
echo "           window.alert(\"Primero debe de teclear el idmenu \" + queop);\n";
echo "           return;                                      }; \n";
echo "                         }\n";

echo "if (queop == \"baja\") {\n";
echo "     if (window.confirm(\"Esta seguro que quiere dar la opcion\"))\n";
echo "        { if (document.forms[0].idmenu.value == \"\") {\n";
echo "           window.alert(\"Primero debe de teclear el idmenu \" + queop);\n";
echo "           return;}; \n";
echo "        } \n";
echo "     else { \n";
echo "        return;}; \n";
echo "                      }\n";
echo "    document.forms[0].wlopcion.value=queop;\n";
##echo "     alert(\"menu_\"+document.forms[0].idmenu.value+\"opcion\"+document.forms[0].wlopcion.value );\n";
echo "    document.forms[0].submit();\n";
echo "}\n";
echo "</script>\n";
//include("enca.php");
?>
        <form method=POST action=manopciones.php>
  <?
      $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2");
##       echo "  <table class='sortable' id='tbinicio' > \n";
##       echo "  <caption>Mantenimiento a Opciones</caption> \n";
##       echo "descripcion ".$descripcion;
##       echo "opcion ".$wlopcion;
##       echo "idmenu ".$idmenu;
       switch ($wlopcion) 
           {
           case "":
              consulta($wldescripcion,$connection);
              break;

           case "alta":
              $sql = "select count(*) from forapi.menus where descripcion='".$descripcion."'";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. " );
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row[0]>=1) { menerror("La opcion ya existe"); }
              else
                 { $sql = " insert into forapi.menus (descripcion,php,idmenupadre) values ('".$descripcion."','".$php."',".$idmenupadre.")" ;
                                    $sql_result = pg_exec($connection,$sql) or die("Couldn't make query.".$sql );
                   menok("La opcion se dio de alta"); 
                 }
              consulta($wldescripcion,$connection);
              break;

           case "baja":
              existegrupo($wldescripcion, $idmenu, $connection, $wlopcion);
              $sql = " select count(*) from forapi.menus_pg_group where idmenu=".$idmenu ;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row[0]>=1) { menerror("La opcion tiene asignados grupos");die(); }
              $sql = " delete from forapi.menus where idmenu =".$idmenu;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
              menok("La opcion se dio de baja"); 
              consulta($wldescripcion,$connection);
              break;

           case "cambio":
              existegrupo($wldescripcion, $idmenu, $connection, $wlopcion);
              if ($descripcion!="") {
                 $sql = " update  forapi.menus set descripcion='".$descripcion."' where idmenu =".$idmenu;
                 $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
                                     }
              if ($php!="") {
                 $sql = " update  forapi.menus set php='".$php."' where idmenu =".$idmenu;
                 $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
                                     }

              if ($idmenupadre!="") {
                 $sql = " update  forapi.menus set idmenupadre='".$idmenupadre."' where idmenu =".$idmenu;
                 $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
                                     }
              menok("La opcion se actualizo"); 
              consulta($descripcion,$connection);
              break;

           case "manttogruopc":
              existegrupo($descripcion, $idmenu, $connection, $wlopcion);
              manttogrupopc($descripcion,$idmenu,$connection);
              break;
           case "manttogruusu":
##              echo "idmenu"+$idmenu;
              existegrupo($descripcion, $idmenu, $connection, $wlopcion);
              manttogruusu($descripcion,$idmenu,$wltablename,$connection);
              break;

           case "consulta":
              consulta($descripcion,$connection);
              break;

           case "consultatabla":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection);
              consultatabla($wltablename,$wlidmenu,$connection);
              break;

           case "consultahistab":
              manttogruusu($descripcion,$idmenu,$wltablename,$connection);
              consultahistab($wltablaaquitar,$idmenu,$connection);
              break;

           case "consultatablaasig":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection);
              consultatablaasig($wltablename,$wlidmenu,$connection);
              break;


           case "altatabla":
              $sql = " insert into forapi.menus_pg_tables (idmenu,tablename,tselect,tinsert,tupdate,tdelete,tall) ".
                   " values (".$idmenu.",'".$wltablename."','".$wltselect."','".$wltinsert."','".$wltupdate."','".$wltdelete.
                     "','".$wltall."')";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("La tabla se asigno a la opcion"); 
              manttogruusu($descripcion,$idmenu,$wltablename,$connection);
              break;

           case "asignatodastablas":
              $sql = " insert into forapi.menus_pg_tables (idmenu,tablename,tselect,tinsert,tupdate,tdelete,tall) ".
                     " select ".$idmenu.",relname as tablename,'".
                     $wltselect."','".$wltinsert."','".$wltupdate."','".$wltdelete."','".
                     $wltall."' from pg_class where ".
                     " relkind in ('r', 'S', 'v') ".
                     " and trim(relname) not in (select trim(tablename) from forapi.menus_pg_tables where ".
                     " idmenu=".$idmenu.") ".
                     " and substr(relname,1,3)<>'pg_' ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todas las tabla no asignada se asignaron a la opcion"); 
              manttogruusu($descripcion,$idmenu,$wltablename,$connection);
              break;

           case "cambiotabla":
              $sql = " update forapi.menus_pg_tables set ".
                     " tselect='".$wltselect."',tinsert='".$wltinsert."',tupdate='".$wltupdate.
                     "',tdelete='".$wltdelete."',tall='".$wltall."' ".
                     " where tablename='".$wltablaaquitar."' and idmenu=".$idmenu;
                    $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
                    menok("La tabla se cambio "); 
              manttogruusu($descripcion,$idmenu,$wltablaaquitar,$connection);
              break;

           case "bajatabla":
              $sql = " delete from forapi.menus_pg_tables  where idmenu=".$idmenu." and tablename='".$wltablaaquitar."'";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("La tabla se dio de baja de  la opcion"); 
              manttogruusu($descripcion,$idmenu,$wltablaquitar,$connection);
              break;

           case "bajatodastablas":
              $sql = " delete from forapi.menus_pg_tables  where idmenu=".$idmenu;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todas las tablas se dieron de baja de la opcion"); 
              manttogruusu($descripcion,$idmenu,$wltablaquitar,$connection);
              break;


           case "conhis":
              manttogrupopc($descripcion,$idmenu,$connection);
              conhis($descripcion,$idmenu,$connection);
              break;

           case "conhisusu":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              existegrupo($wldescripcion, $wlidmenu, $connection, $wlopcion);
              conhisusu($wldescripcion,$connection);
              break;

           case "am1":
##              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              $sql = " insert into forapi.menus_pg_group (idmenu,grosysid) values (".$idmenu.",".$wldescripcionmenu.")";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El grupo se asigno a la opcion"); manttogrupopc($descripcion,$idmenu,$connection); die();
              break;

           case "au1":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              $sql = " insert into cat_usuarios_pg_group (usename,grosysid) values ('".$wldescripcionusuario.
                     "',(select grosysid from pg_group where trim(groname)='".$wldescripcion."')) ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El usuario se asigno a el grupo"); manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection); die();
              break;

           case "amt":
              $sql = " insert into forapi.menus_pg_group (idmenu,grosysid) ".
                     " select ".$idmenu.", grosysid ".
                     " from pg_group  where grosysid not in (select grosysid from forapi.menus_pg_group where idmenu=".$idmenu.")";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los grupos se asignaron a la opcion ".$wldescripcion); manttogrupopc($descripcion,$idmenu,$connection); die();
              break;

           case "aut":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              $sql = " insert into cat_usuarios_pg_group (usename,grosysid) ".
                     " select usename,(select grosysid from pg_group where trim(groname)='".$wldescripcion."') ".
                     " from cat_usuarios where usename not in (select usename from cat_usuarios_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$wldescripcion."')) ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Toda los usuarios se asignaron a el grupo ".$wldescripcion); manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection); die();
              break;

           case "qm1":
              $sql = " delete from forapi.menus_pg_group where idmenu = ".$idmenu." and grosysid=".$wlmenuaquitar;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El grupo se quito de la opcion"); manttogrupopc($descripcion,$idmenu,$connection); die();
              break;

           case "qu1":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              $sql = " delete from cat_usuarios_pg_group where usename = '".$wlusuarioaquitar."' and grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$wldescripcion."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El usuario se quito del grupo"); manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection); die();
              break;

           case "qmt":
              $sql = " delete from forapi.menus_pg_group where idmenu=".$idmenu;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los grupos se quitaron de la opcion"); manttogrupopc($descripcion,$idmenu,$connection); die();
              break;

           case "qut":
              solgrupo($wldescripcion,$wlphp,$wlidmenu);
              $sql = " delete from cat_usuarios_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$wldescripcion."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los usuarios se quitaron del grupo"); manttogruusu($wldescripcion,$wlidmenu,$wltablename,$connection); die();
              break;

           }
//        }
   ?>
		</font>
                </table>
</BODY>
</HTML>
