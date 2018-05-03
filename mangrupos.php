<? session_start() ?>
<HTML>
<head>
<? 
echo " <LINK REL=StyleSheet HREF=\"estilo_siscor_gre.css\" TYPE=\"text/css\" MEDIA=screen>\n";
?>
<title>Secretaria de Finanzas </title>
</head>
<?
function  existegrupo($wlgrupo, $connection, $wlopcion)
{
   $sql = "select count(*) from pg_group where groname='".$wlgrupo."'";
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. " );
   $Row = pg_fetch_array($sql_result, 0);
    if ($Row[0]==0) { menerror("El grupo no existe");die(); }
}

function consulta($wlgrupo,$connection)
{
   $sql = " select grosysid, groname as grupo from pg_group order by groname";
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay grupos ");solgrupo($wlgrupo);die(); };
   echo "<tr align=center><th><input type=submit value='Alta de Grupo' name=matriz ".
        "onclick='validausuario(\"alta\");return false'></input>\n";

   echo "  <table> \n";
###  titulos de la tabla
   echo "<tr>";
   $Row1 = pg_fetch_array($sql_result, 0);
   foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else { echo "<th>".Key($Row1)."</th>"; }
          next($Row1);
                               };
   echo "<th>Baja</th>";
   echo "<th>Opciones</th>";
   echo "<th>Usuarios</th>";
   echo "</tr>";

##  campos para capturar en altas o cambios
   echo "<tr>";
   foreach ($Row1 as $value)    {
          if (Key($Row1)<"100") { }
          else { echo "<th><input size=30 maxlength=30 type=text name='".Key($Row1)."'</input></th>"; }
          next($Row1);
                               };
   echo "</tr>";
   echo " <input type=hidden name=wlopcion ></input>\n";

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
        echo "<td><a href=\"javascript:validausuario(  'baja','".$Row[grosysid]."','".$Row[grupo]."')\">x</a></td>\n";
        echo "<td><a href=\"javascript:validausuario('manttogruopc','".$Row[grosysid]."','".$Row[grupo]."')\">x</a></td>\n";
        echo "<td><a href=\"javascript:validausuario('manttogruusu','".$Row[grosysid]."','".$Row[grupo]."')\">x</a></td>\n";
        echo "</tr>";
       };

##   echo "<tr>";
##   echo "<th width=60%> GRUPO </th>";
##   echo "</tr>"; 
##   for ($i=0; $i < $num ;$i++)
##       {
##        $Row = pg_fetch_array($sql_result, $i);
##        if ($Row[groname]<>"") {
##           echo "<tr>";
##           echo "<td width=60%> $Row[groname] </td>";
##           echo "</tr>"; 
##                                   }
##       };
   echo "</table>";
}


function conhis($wlgrupo,$connection,$wlgrupomenu)
{
   if ( $wlgrupomenu == "") 
      { $sql = " select m.descripcion,g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_menus_pg_group hmg, menus m, pg_group g where hmg.idmenu = m.idmenu ".
          " and hmg.grosysid = g.grosysid and g.groname = '".$wlgrupo."' order by fecha_alta desc "; }
   else
      { $sql = " select m.descripcion,g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_menus_pg_group hmg, menus m, pg_group g where hmg.idmenu = m.idmenu ".
          " and hmg.grosysid = g.grosysid and g.groname = '".$wlgrupo."'".
          " and hmg.idmenu=".$wlgrupomenu." order by fecha_alta desc "; }
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay historial de opciones grupo ");die(); };
   echo "  <table width=100% cellspacing=0 cellpadding=8 border=1 align=center> \n";
   echo "<tr>";
   echo "<th width=60%> OPCION </th>";
   echo "<th width=25%> FECHA DEL MOVIMIENTO </th>";
   echo "<th width=10%> USUARIO </th>";
   echo "<th width=5%>  MOVIMIENTO </th>";
   echo "</tr>"; 
   for ($i=0; $i < $num ;$i++)
       {
        $Row = pg_fetch_array($sql_result, $i);
        if ($Row[descripcion]<>"") {
           echo "<tr>";
           echo "<td width=60%> $Row[descripcion] </td>";
           echo "<td width=25%> $Row[fecha_alta] </td>";
           echo "<td width=10%> $Row[usuario_alta] </td>";
           echo "<td width=5%> $Row[cve_movto] </td>";
           echo "</tr>"; 
                                   }
       };
   echo "</table>";
//   solgrupo($wlgrupo);
}

function conhisusu($wlgrupo,$connection,$wlgrupousuario)
{
   if ( $wlgrupousuario == "") 
      { $sql = " select trim(m.nombre) || ' ' || trim(apepat) || ' ' || apemat as descripcion,".
          " g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_cat_usuarios_pg_group hmg, cat_usuarios m, pg_group g where hmg.usename = m.usename ".
          " and hmg.grosysid = g.grosysid and g.groname = '".$wlgrupo."' order by fecha_alta desc "; }
   else
      { $sql = " select trim(m.nombre) || ' ' || trim(apepat) || ' ' || apemat as descripcion,".
          " g.groname,hmg.fecha_alta,hmg.usuario_alta,hmg.cve_movto from ".
          " his_cat_usuarios_pg_group hmg, cat_usuarios m, pg_group g where hmg.usename = m.usename ".
          " and hmg.grosysid = g.grosysid and g.groname = '".$wlgrupo."'".
          " and hmg.usename='".$wlgrupousuario."'  order by fecha_alta desc "; }
   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
   $num = pg_numrows($sql_result);
   if ( $num == 0 ) {menerror("No hay historial de usuarios grupo ");die(); };
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
//   solgrupo($wlgrupo);
}


function manttogrupopc($wlgrupo,$connection)
{
              $sql = " select idmenu,descripcion from menus where idmenu not in ".
                     "(select idmenu from menus_pg_group where grosysid=".
                     "(select grosysid from pg_group where trim(groname)='".$wlgrupo."')) order by 2 ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
              $num = pg_numrows($sql_result);
                  echo "  <table> ";
                  echo "  <caption>Nombre del Grupo: ".$wlgrupo."</caption>";
                  echo " <tr><th>Opciones no Asignados al grupo </th><th></th><th>Opciones asignados al grupo</th></tr>";
                  echo " <th width=45%> \n";
                  echo "<select name=\"wlgrupomenu\"   size=20 width=200 align=center maxlength=10 > \n";
                  if ( $num == 0 ) { echo "<option value='0'>*No hay menus a asignar al grupo *</option>\n"; };
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[descripcion]<>"") {
                     echo "<option value='$Row[idmenu]'> $Row[descripcion] </option>"; 
                                              }
                  };
                  echo "</select></td>";
                  echo "<th width=10%> <input type=submit value='Asigna Opcion' ".
                       "name=matriz onclick='asignamenu(\"am1\");return false'></input>\n";
                  echo "<input type=submit value='Asigan Todos Opcion' name=matriz ".
                       "onclick='asignamenu(\"amt\");return false'></input>\n";
                  echo "<input type=submit value='Quita Opcion' name=matriz ".
                       "onclick='quitamenu(\"qm1\");return false'></input>\n";
                  echo "<input type=submit value='Quita todos Opcion' name=matriz ".
                       "onclick='quitamenu(\"qmt\");return false'></input>\n";
                  echo "<input type=hidden name=wlopcion </input>";
                  echo "<input type=hidden name=grupo value=".$wlgrupo."></input>";
                  echo "<input type=submit value='Atras' name=matriz ".
                       "onclick='parent.history.back();return false'></input>\n";
                  echo "<input type=submit value='Historico' name=matriz ".
                       "onclick='document.forms[0].wlopcion.value=\"conhis\"'></input>\n";
                  $sql = " select idmenu, descripcion from menus where idmenu in ".
                         "(select idmenu from menus_pg_group where grosysid=".
                         "(select grosysid from pg_group where trim(groname)='".$wlgrupo."')) order by 2";
                  $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
                  $num = pg_numrows($sql_result);
                  echo "<th width=45%> \n";
                  echo "<select name=\"wlmenuaquitar\" size=20 width=200 align=center  > \n";
                  if ($num==0) 
                     { echo "<option value=\"\">- Menus asignados a el grupo -</option>"; } ;
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

function manttogruusu($wlgrupo,$connection)
{
              $sql = " select usename,trim(nombre) || ' ' || trim(apepat) || ' ' || trim(apemat) as descripcion ".
                     "from cat_usuarios where usename not in ".
                     "(select usename from cat_usuarios_pg_group where grosysid=".
                     "(select grosysid from pg_group where trim(groname)='".$wlgrupo."')) order by 2 ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
              $num = pg_numrows($sql_result);
                  echo "  <table> ";
                 echo "  <caption>Nombre del Grupo: ".$wlgrupo."</caption>";
                  echo " <tr><th> Usuarios no Asignados al grupo </th><th></th><th>Usuarios asignados al grupo</th></tr>";
                  echo " <th width=45%> \n";
                  echo "<select name=\"wlgrupousuario\"  size=20 width=200 align=center   > \n";
                  if ( $num == 0 ) { echo "<option value='0'>*No hay usuarios a asignar al grupo *</option>\n"; };
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[descripcion]<>"") {
                     echo "<option value='$Row[usename]'> $Row[descripcion] </option>"; 
                                              }
                  };
                  echo "</select></td>";
                  echo "<th width=10%> <input type=submit value='Asigna Usuario' ".
                       "name=matriz onclick='asignausuario(\"au1\");return false'></input>\n";
                  echo "<input type=submit value='Asigan Todos Usuario' name=matriz ".
                       "onclick='asignausuario(\"aut\");return false'></input>\n";
                  echo "<input type=submit value='Quita Usuario' name=matriz ".
                       "onclick='quitausuario(\"qu1\");return false'></input>\n";
                  echo "<input type=submit value='Quita todos Usuario' name=matriz ".
                       "onclick='quitausuario(\"qut\");return false'></input>\n";
                 echo "<input type=hidden name=wlopcion </input>";
                  echo "<input type=hidden name=grupo value=".$wlgrupo."></input>";
                  echo "<input type=submit value='Atras' name=matriz ".
                       "onclick='parent.history.back();return false'></input>\n";
                  echo "<input type=submit value='Historico' name=matriz ".
                       "onclick='document.forms[0].wlopcion.value=\"conhisusu\"'></input>\n";

                  $sql = " select usename,trim(nombre) || ' ' || trim(apepat) || ' ' || trim(apemat) as descripcion ".
                         "from cat_usuarios where usename in ".
                         "(select usename from cat_usuarios_pg_group where grosysid=".
                         "(select grosysid from pg_group where trim(groname)='".$wlgrupo."')) order by 2";
                  $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. ".$sql );
                  $num = pg_numrows($sql_result);
                  echo "<th width=45%> \n";
                  echo "<select name=\"wlusuarioaquitar\" size=20 width=200 align=center   > \n";
                  if ($num==0) 
                     { echo "<option value=\"\">- Usuarios asignados a el grupo -</option>"; } ;
                  for ($i=0; $i < $num ;$i++)
                  {
                   $Row = pg_fetch_array($sql_result, $i);
                   if ($Row[descripcion]<>"") {
                     echo "<option value='$Row[usename]'> $Row[descripcion] </option>";
                                              }
                  };
                  echo "</select></td>";
                  echo "</td>";
                  echo "</table>";
//                  solgrupo($wlgrupo);
}

function menerror($wlmensaje)
{ echo "<center><font color=red size=+2>".$wlmensaje."</font></center>"; }

function menok($wlmensaje)
{ echo "<center><font color=green size=+2>".$wlmensaje."</font></center>"; }

function solgrupo($wlgrupo)
{
       echo " <table>\n ";
       echo " <tr><td align=center ><b>Teclee el grupo </b></td><td><input type=text ".
            "name=wlgrupo size=10 maxlength=30 value=".$wlgrupo."></input></td></tr>\n";
       echo " <input type=hidden name=wlopcion ></input>\n";
       echo "<tr><td> <input type=submit value='Alta de Grupo' name=matriz ".
            "onclick='validausuario(\"alta\");return false'></input>\n";
       echo "<td> <input type=submit value='Baja de Grupo' name=matriz ".
            "onclick='validausuario(\"baja\");return false'></input>\n";
       echo "<td> <input type=submit value='Consulta de Grupo' name=matriz ".
            "onclick='validausuario(\"consulta\");return false'></input>\n";
       echo "</tr><tr>";
       echo "<td> <input type=submit value='Mantenimiento de Opciones del Grupo' name=matriz ".
            "onclick='validausuario(\"manttogruopc\");return false'></input>\n";
       echo "<td> <input type=submit value='Consulta Historico Opciones' name=matriz ".
            "onclick='validausuario(\"conhis\");return false'></input></tr>\n";
       echo "</tr><tr>";
       echo "<td> <input type=submit value='Mantenimiento de Usuarios del Grupo' name=matriz ".
            "onclick='validausuario(\"manttogruusu\");return false'></input></tr>\n";
       echo "<td> <input type=submit value='Consulta Historico Usuarios Grupo' name=matriz ".
            "onclick='validausuario(\"conhisusu\");return false'></input></tr>\n";
       echo " </table>\n ";
}

if ($wlusuario=="")
   { echo "<BODY onload=\"inicia()\" onClick=\"numeroClicks++\">\n"; } 
else
   { echo "<BODY onload=\"inicia()\" onClick=\"numeroClicks++\">\n"; } 
?>
<script language="JavaScript" type="text/javascript">;
<?
include("val_inactividad_js.php");
echo "function asignamenu(queop)\n";
echo "{\n";
##echo "    var s = document.forms[0].wlgrupomenu;\n";
##echo "    if (s.selectedIndex < 0  && queop == \"am1\" ) {\n";
##echo "        alert('Primero debe seleccionar el menu que va a asignar.');\n";
##echo "        return false; } \n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function asignausuario(queop)\n";
echo "{\n";
echo "    var s = document.forms[0].wlgrupousuario;\n";
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

echo "function validausuario(queop,grosysid,groname)\n";
echo "{\n";
echo "if (document.forms[0].grupo.value == \"\" && (queop == \"alta\") ) {\n";
echo "     window.alert(\"Primero debe de teclear el grupo \" + queop);\n";
echo "     document.forms[0].grupo.focus();\n";
echo "     return;}\n";
echo "if (queop != \"alta\") { \n";
echo "    document.forms[0].grupo.value=groname; }; \n";
echo "if (queop == \"baja\") {\n";
echo "     if (window.confirm(\"Esta seguro que quiere dar de baja el grupo\"))\n";
echo "        { }; \n";
echo "     else { \n";
echo "        document.forms[0].grupo.focus();\n";
echo "        return;}; \n";
echo "                      }\n";
//echo "    window.alert(\"Entro en submit\" + queop);\n";
echo "    document.forms[0].wlopcion.value=queop;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";
echo "</script>\n";
//include("enca.php");
?>
        <form method=POST action=mangrupos.php>
  <?
      $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2");
       echo "  <table> \n";
       echo "  <caption>Mantenimiento a Grupos</caption> \n";
       echo "  <tr><th><hr></th></tr>	";
##   if ($wlgrupo=="") {
##       solgrupo($wlgrupo);
##       switch ($wlopcion) 
##           {
##           case "consulta":
##              consulta($wlgrupo,$connection);
##              break;
##           }
##                       }
##   else {
       switch ($wlopcion) 
           {
           case "":
              consulta($grupo,$connection);
              break;
           case "alta":
##              solgrupo($wlgrupo);
              $sql = "select count(*) from pg_group where groname='".$grupo."'";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. " );
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row[0]>=1) { menerror("El grupo ya existe".$grupo);die(); }
              else
                 { $sql = " create group ".$grupo;
                   $sql_result = pg_exec($connection,$sql) or die("Couldn't make query. " );
                   menok("El Grupo se dio de alta"); 
                 }
              consulta($grupo,$connection);
              break;

           case "baja":
              existegrupo($grupo, $connection, $wlopcion);
              $sql = " select count(*) from cat_usuarios_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row[0]>=1) { menerror("El grupo tiene asignados usuarios");consulta($grupo,$connection);die(); }

              $sql = " select count(*) from menus_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row[0]>=1) { menerror("El grupo tiene asignadas opciones");consulta($grupo,$connection);die(); }

              $sql = " drop group ".$grupo;
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql );
              menok("El Grupo se dio de baja");
              consulta($grupo,$connection);
              break;

           case "manttogruopc":
              manttogrupopc($grupo,$connection);
              break;
           case "manttogruusu":
              manttogruusu($grupo,$connection);
              break;

           case "consulta":
              solgrupo($wlgrupo);
              consulta($wlgrupo,$connection);
              break;

           case "conhis":
              manttogrupopc($grupo,$connection);
              conhis($grupo,$connection,$wlgrupomenu);
              break;

           case "conhisusu":
              manttogruusu($grupo,$connection);
              conhisusu($grupo,$connection,$wlgrupousuario);
              break;

           case "am1":
##              solgrupo($wlgrupo);
              $sql = " insert into menus_pg_group (idmenu,grosysid) values (".$wlgrupomenu.
                     ",(select grosysid from pg_group where trim(groname)='".$grupo."')) ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El menu se asigno a el grupo"); 
              manttogrupopc($grupo,$connection);
              break;
           case "au1":
              $sql = " insert into cat_usuarios_pg_group (usename,grosysid) values ('".$wlgrupousuario.
                     "',(select grosysid from pg_group where trim(groname)='".$grupo."')) ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El usuario se asigno a el grupo"); manttogruusu($grupo,$connection); 
              break;

           case "amt":
              $sql = " insert into menus_pg_group (idmenu,grosysid) ".
                     " select idmenu,(select grosysid from pg_group where trim(groname)='".$grupo."') ".
                     " from menus where idmenu not in (select idmenu from menus_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."')) ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Toda los menus se asignaron a el grupo ".$grupo); manttogrupopc($grupo,$connection);
              break;

           case "aut":
              $sql = " insert into cat_usuarios_pg_group (usename,grosysid) ".
                     " select usename,(select grosysid from pg_group where trim(groname)='".$grupo."') ".
                     " from cat_usuarios where usename not in (select usename from cat_usuarios_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."')) ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Toda los usuarios se asignaron a el grupo ".$grupo); manttogruusu($grupo,$connection); 
              break;

           case "qm1":
              $sql = " delete from menus_pg_group where idmenu = ".$wlmenuaquitar." and grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El menu se quito del grupo"); manttogrupopc($grupo,$connection); die();
              break;

           case "qu1":
              $sql = " delete from cat_usuarios_pg_group where usename = '".$wlusuarioaquitar."' and grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("El usuario se quito del grupo"); manttogruusu($grupo,$connection); 
              break;

           case "qmt":
              $sql = " delete from menus_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los menus se quitaron del grupo"); manttogrupopc($grupo,$connection); 
              break;

           case "qut":
              $sql = " delete from cat_usuarios_pg_group where grosysid=".
                     " (select grosysid from pg_group where trim(groname)='".$grupo."') ";
              $sql_result = pg_exec($connection,$sql) or die("Couldn't make query".$sql."opcion".$wlopcion );
              menok("Todos los usuarios se quitaron del grupo"); manttogruusu($grupo,$connection); 
              break;

           }
   ?>
		</font>
                </table>
</BODY>
</HTML>
