<?php
error_reporting( E_ALL & ~E_NOTICE );
class esta_class
{
   /**
    * Coneccion a la base de datos
    */
   var $connection="";
   /**
     *  Funcion que se va a ejecutar
     */
   var $funcion="";
   /**
     *  Datos que se recibe del html
     */
   var $argumentos=array();
   /**
     *  Nombre del archivo que va tomar de entrada d3
     */
   var $nombreArchivo="ficheros/esta_";
   /*
    *   campos de la tabla cat_estadisticas
    */
   var $campos_ce = array();
   /*
    *   campos de la tabla sol_estadisticas
    */
   var $campos_so = array();
   var $rango="";
   ##include 'cuadrados.php';
   function piechart()
   {
      $this->generadatos();
      $je=$this->preparadatos();

        echo "<!DOCTYPE html>\n";
        echo "<meta charset=\"utf-8\">\n";
        echo " <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
        echo "<meta name='mobile-web-app-capable' content='yes' />";
        echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
        echo "<style>\n";
        echo "body {\n";
        echo "  font: 10px sans-serif;\n";
        echo "}\n";
        echo ".arc path {\n";
        echo "  stroke: #fff;\n";
        echo "}\n";

        echo "</style>\n";
        echo "<body>\n";
        echo "<form>\n";
        echo "<div id='mypie'></div>";
        echo "</form>\n";
        echo "<script src=\"d3.v3.min.js\"></script>\n";
        echo "<script src=\"d3pie.js\"></script>\n";
        echo "<script>";
        echo " var je = eval(".json_encode($je).");\n";
        echo " var sum = 0;\n";
        echo " for (x in je) {\n";
        echo "     sum += je[x].value;\n";
        echo "}\n";
        echo " var pie = new d3pie('mypie', {\n";
        echo "   size: {\n";
        echo "     canvasHeight: ".$this->campos_ce["alto"].",\n";
        echo "     canvasWidth:  ".$this->campos_ce["ancho"]."\n";
        echo "   },\n";
        echo "  header : {\n";
        echo "    title : { text: '"."ESTADISTICA ".$this->campos_so["descripcion"]." ".$this->rango."'\n";
        echo "            },";
        echo "    subtitle : { text: 'Total '+sum\n";
        echo "            }";
        echo "           },\n";
        echo "    labels : {\n";
        echo "       outer : {\n";
        echo "             format : 'label-value1', \n";
        echo "               },\n";
        echo "       inner : {\n";
        echo "             format : 'percentage', \n";
        echo "             hideWhenLessThanPercentage: 3 \n";
        echo "               }\n";
        echo "            },\n";
        echo "   data: {\n";
        echo "     sortOrder: 'value-asc',\n";
        echo "     content: je";
        echo "   }";
        echo " });";
        echo "</script>";
        echo "<body>\n";
        echo "</body>\n";
   }

/*
   function piechart()
   {
      $this->generadatos();
      $this->preparadatos();

	echo "<!DOCTYPE html>\n";
	echo "<meta charset=\"utf-8\">\n";
        echo " <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
        echo "<meta name='mobile-web-app-capable' content='yes' />";
        echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
	echo "<style>\n";
	echo "body {\n";
	echo "  font: 10px sans-serif;\n";
	echo "}\n";
	echo ".arc path {\n";
	echo "  stroke: #fff;\n";
	echo "}\n";

	echo "</style>\n";
	echo "<body>\n";
        echo "<form>\n";
        echo "<tr><td colspan=2><h2>Estadistica ".$this->campos_so["descripcion"]." ".$this->rango."</h2></td></tr>";
        echo "</form>\n";

	echo "<script src=\"d3.v3.min.js\"></script>\n";
	echo "<script>\n";

	echo "var width = ".$this->campos_ce["ancho"].",\n";
	echo "    height = ".$this->campos_ce["alto"].",\n";
	echo "    radius = Math.min(width, height) / 2;\n";

	echo "var color = d3.scale.category20();\n";

	echo "var arc = d3.svg.arc()\n";
	echo "    .outerRadius(radius - 10)\n";
	echo "    .innerRadius(0);\n";

	echo "var pie = d3.layout.pie()\n";
	echo "    .sort(null)\n";
	echo "    .value(function(d) { return d.yy; });\n";

	echo "var svg = d3.select(\"body\").append(\"svg\")\n";
	echo "    .attr(\"width\", width)\n";
	echo "    .attr(\"height\", height)\n";
	echo "  .append(\"g\")\n";
	echo "    .attr(\"transform\", \"translate(\" + width / 2 + \",\" + height / 2 + \")\");\n";

        echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";

	echo "  data.forEach(function(d) {\n";
	echo "    d.yy = +d.yy;\n";
	echo "  });\n";

	echo "  var g = svg.selectAll(\".arc\")\n";
	echo "      .data(pie(data))\n";
	echo "    .enter().append(\"g\")\n";
	echo "      .attr(\"class\", \"arc\");\n";

	echo "  g.append(\"path\")\n";
	echo "      .attr(\"d\", arc)\n";
	echo "      .style(\"fill\", function(d,i) { return color(i); });\n";

	echo "  g.append(\"text\")\n";
	echo "      .attr(\"transform\", function(d) { return \"translate(\" + arc.centroid(d) + \")\"; })\n";
	echo "      .attr(\"dy\", \".35em\")\n";
	echo "      .style(\"text-anchor\", \"middle\")\n";
	echo "      .text(function(d) { return d.data.xx; });\n";

	echo "});\n";

	echo "</script>\n";
     }
*/

   function generadatos()
   {

           if ($this->campos_ce["funcion"]=="barchartsort" || $this->campos_ce["funcion"]=="linechart" || $this->campos_ce["funcion"]=="piechart" || $this->campos_ce["funcion"]=="piechartn")
           {
                 $sql="delete from estadisticas.estadisticas_datos where id_solicitud=".$this->argumentos["wl_id_solicitud"].";".
                      " insert into estadisticas.estadisticas_datos (x,y,id_solicitud)".
                      " select array_agg(dia) as dia,array_agg(cuantos) as y,".$this->argumentos["wl_id_solicitud"].
                      " from (".
                      " select ".str_replace("\"","'",$this->campos_ce["groupby"])." as dia ".
                      " ,".$this->campos_ce["quecalculo"]."  as cuantos ".
                      " from ".$this->campos_ce["nspname"].".".$this->campos_ce["tabla"]." as ge".
                      " where ".$this->campos_ce["filtro"]." between '".$this->campos_so["fecha_inicial"]."' and '".$this->campos_so["fecha_final"]."'".
                      ($this->campos_ce["otro_filtro"]!="" ? " and ".$this->campos_ce["otro_filtro"] : " ").
                      " group by 1  ".
                      " order by 1  ".
                      " ) as a";
           }
           if ($this->campos_ce["funcion"]=="groupedbar" || $this->campos_ce["funcion"]=="stackedbar" || $this->campos_ce["funcion"]=="cuadrados")
           {
                 $sql="delete from estadisticas.estadisticas_datos where id_solicitud=".$this->argumentos["wl_id_solicitud"].";".
                      " insert into estadisticas.estadisticas_datos (agrupacion,x,y,id_solicitud)".
                      " select agrupacion ,array_agg(dia) as dia,array_agg(cuantos) as y,".$this->argumentos["wl_id_solicitud"].
                      " from (".
                      " select ".str_replace("\"","'",$this->campos_ce["groupby"])." as dia ".
                      " ,".str_replace("\"","'",$this->campos_ce["agrupacion"])." as agrupacion ".
                      " ,".$this->campos_ce["quecalculo"]."  as cuantos ".
                      " from ".$this->campos_ce["nspname"].".".$this->campos_ce["tabla"]." as ge";
                 $wlfiltro=($this->campos_ce["filtro"]!="" ? " where ".$this->campos_ce["filtro"]." between '".$this->campos_so["fecha_inicial"]."' and '".$this->campos_so["fecha_final"]."'" : "" );
                 $wlfiltro=$wlfiltro.($this->campos_ce["otro_filtro"]!="" ? ($wlfiltro!="" ? " and ".$this->campos_ce["otro_filtro"] : " where ".$this->campos_ce["otro_filtro"])  : "");
                 $sql=$sql.$wlfiltro.
                      " group by 1,2  ".
                      " ) as a group by 1";
           }
           $sql_result = @pg_exec($this->connection,$sql);
           if (strlen(pg_last_error($this->connection))>0)
           {
                         echo "<error>Error generadatos".pg_last_error($this->connection)."</error>";
                        return false;
           }
   }

   function parametros()
   {
      $soli=$this->argumentos["wl_id_solicitud"];
      $this->nombreArchivo=$this->nombreArchivo.$this->argumentos["wl_id_solicitud"].".csv";
      $sql="select * ".
           " ,(select destipografica from estadisticas.v_rel_estadisticatiposgraficas as v where v.id=se.id) as funcion ".
           " from estadisticas.sol_estadisticas as se where id_solicitud=".$soli;
      $sql_result = @pg_exec($this->connection,$sql);
                 if (strlen(pg_last_error($this->connection))>0)
                 {
                        echo "<error>Error en parametros 1".pg_last_error($this->connection)."</error>";
                        return false;
                 }
      $this->campos_so = pg_fetch_array($sql_result, 0);
      $this->rango="DEL ".$this->campos_so["fecha_inicial"]." AL ".$this->campos_so["fecha_final"];
      $sql="select * from estadisticas.cat_estadisticas where id_estadistica=".$this->campos_so["id_estadistica"];
      $sql_result = @pg_exec($this->connection,$sql);
                 if (strlen(pg_last_error($this->connection))>0)
                 {
                        echo "<error>Error en parametros 1".pg_last_error($this->connection)."</error>";
                        return false;
                 }
      $this->campos_ce = pg_fetch_array($sql_result, 0);
      ##var_dump($this->campos_so); die();
      if ($this->campos_so["funcion"]!="")
      { $this->campos_ce["funcion"]=$this->campos_so["funcion"]; }
   } 

   function preparadatos()
   {
      if ($this->campos_ce["funcion"]=="barchartsort" || $this->campos_ce["funcion"]=="linechart" || $this->campos_ce["funcion"]=="piechart" || $this->campos_ce["funcion"]=="piechartn")
      {
      	$sql="select x as xx ,y as yy from estadisticas.estadisticas_datos where id_solicitud=".$this->campos_so["id_solicitud"];
                $sql_result = @pg_exec($this->connection,$sql);
                 if (strlen(pg_last_error($this->connection))>0)
                 {
                        echo "<error>Error preparadatos".pg_last_error($this->connection)."</error>";
                        return false;
                 }
                $num = pg_numrows($sql_result);
                if(file_exists($this->nombreArchivo)) {unlink($this->nombreArchivo);}
                // genera el encabezado del archivo
                $ff = fopen ($this->nombreArchivo, w);
                $rowe = pg_fetch_array($sql_result, 0);
                foreach ($rowe as $value )
                {       if (Key($rowe)<"100")
                        { }
                        else
                        {
                                if ($me=='')
                                {       if (!fwrite ($ff,Key($rowe).",")) { echo "no pudo escribir archivo"; }   }
                                else
                                {       if (!fwrite ($ff,$me->camposmc[Key($rowe)]["descripcion"].",")) { echo "no pudo escribir archivo"; }     }
                        }
                        next($rowe);
                }
                if (!fwrite ($ff,"\n")) { echo "no pudo escribir archivo"; }
                $etiquetas=array();
                for ($i=0; $i < $num ;$i++)
                { $row = pg_fetch_array($sql_result, $i);
                       $xx=explode(",",str_replace("}","",str_replace("{","",$row["xx"]))); 
                       $yy=explode(",",str_replace("}","",str_replace("{","",$row["yy"]))); 
                       for($ii=0;$ii<count($xx);$ii++) {
                           if (!fwrite ($ff,$xx[$ii].",".$yy[$ii]."\n")) { echo "no pudo escribir archivo"; } 
                           $miarreglo=array("label"=>$xx[$ii],"value"=>intval($yy[$ii]));
                           array_push($etiquetas,$miarreglo);
                       }
                }
                fclose($ff);
                return $etiquetas;
      }
      if ($this->campos_ce["funcion"]=="groupedbar" || $this->campos_ce["funcion"]=="stackedbar" || $this->campos_ce["funcion"]=="cuadrados")
      {
        $sql="select agrupacion as ag,x as xx ,y as yy from estadisticas.estadisticas_datos where id_solicitud=".$this->campos_so["id_solicitud"]." order by agrupacion";
                $sql_result = @pg_exec($this->connection,$sql);
                 if (strlen(pg_last_error($this->connection))>0)
                 {
                        echo "<error>Error preparadatos".pg_last_error($this->connection)."</error>";
                        return false;
                 }
                $num = pg_numrows($sql_result);
                if(file_exists($this->nombreArchivo)) {unlink($this->nombreArchivo);}
                // genera el encabezado del archivo
                $ff = fopen ($this->nombreArchivo, w);
                $rowe = pg_fetch_array($sql_result, 0);
                $to=array();
                for ($i=0; $i < $num ;$i++)
                {      $row = pg_fetch_array($sql_result, $i);
                       $xx=explode(",",str_replace("}","",str_replace("{","",$row["xx"])));
                       $to=array_merge($to,$xx);
                }
                $res=array_unique($to);
                $imp=implode(",",$res);
                if (!fwrite ($ff,"ag,".$imp."\n")) { echo "no pudo escribir archivo datos"; }

                for ($i=0; $i < $num ;$i++)
                {  
                       $row = pg_fetch_array($sql_result, $i);
                       $xx=explode(",",str_replace("}","",str_replace("{","",$row["xx"])));
                       $yy=explode(",",str_replace("}","",str_replace("{","",$row["yy"])));
                       $val="";
                       foreach ($res as $value) {
                         //if (!fwrite ($ff,$row["ag"].",sientro".$val."\n")) { echo "no pudo escribir archivo datos"; }
                         $cl=FALSE;
                         $cl=array_search($value,$xx);
                         if ($cl===false) { $val=$val.",0"; } else { $val=$val.",".$yy[$cl]; }
                       } 
                       if (!fwrite ($ff,$row["ag"].$val."\n")) { echo "no pudo escribir archivo datos"; }
                }
                fclose($ff);
      }
   }
 
   function linechart()
   {
      $this->parametros();
      $this->generadatos();
      $this->preparadatos();
	echo "<!DOCTYPE html>\n";
	echo "<meta charset=\"utf-8\">\n";
	echo "<style>\n";

	echo "body {\n";
	echo " 	 font: 10px sans-serif;\n";
	echo "}\n";

	echo ".axis path,\n";
	echo ".axis line {\n";
	echo "  fill: none;\n";
	echo "  stroke: #000;\n";
	echo "  shape-rendering: crispEdges;\n";
	echo "}\n";

	echo ".x.axis path {\n";
	echo "  display: none;\n";
	echo "}\n";

	echo ".line {\n";
	echo "  fill: none;\n";
	echo "  stroke: steelblue;\n";
	echo "  stroke-width: 1.5px;\n";
	echo "}\n";

        echo ".textvas {\n";
        echo "  font: 10px sans-serif;";
        echo "}\n";


	echo "</style>\n";
        echo "<form>\n";
        echo "<table>";
        echo "<tr><td colspan=2><h2>Estadistica ".$this->campos_so["descripcion"]." ".$this->rango."</h2></td></tr>";
        echo "<tr><td><input type=\"checkbox\"> Ordena valores</td>\n";
        echo "<td id=wltotal >Total</td></tr>\n";
        echo "</table>";
        echo "</form>\n";

	echo "<body>\n";
	echo "<script src=\"d3.v3.min.js\"></script>\n";
	echo "<script>\n";
        echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
        echo "    width = ".$this->campos_ce["ancho"]." - margin.left - margin.right,\n";
        echo "    height = ".$this->campos_ce["alto"]." - margin.top - margin.bottom;\n";

	echo "var parseDate = d3.time.format(\"".$this->campos_ce["formatodex"]."\").parse;\n";
        echo "var wltotal=0;\n";

	echo "var x = d3.time.scale()\n";
	echo "    .range([0, width]);\n";

	echo "var y = d3.scale.linear()\n";
	echo "    .range([height, 0]);\n";

	echo "var xAxis = d3.svg.axis()\n";
	echo "    .scale(x)\n";
	echo "    .orient(\"bottom\");\n";

	echo "var yAxis = d3.svg.axis()\n";
	echo "    .scale(y)\n";
	echo "    .orient(\"left\");\n";

	echo "var line = d3.svg.line()\n";
	echo "    .x(function(d) {  return x(d.xx); })\n";
	echo "    .y(function(d) {  return y(d.yy); });\n";

	echo "var svg = d3.select(\"body\").append(\"svg\")\n";
	echo "    .attr(\"width\", width + margin.left + margin.right)\n";
	echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
	echo "  .append(\"g\")\n";
	echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

	echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
	echo "  data.forEach(function(d) {\n";
	echo "    d.xx = parseDate(d.xx);\n";
	echo "    d.yy = +d.yy;\n";
	echo "  });\n";

	echo "  x.domain(d3.extent(data, function(d) { return d.xx; }));\n";
	echo "  y.domain(d3.extent(data, function(d) { return d.yy; }));\n";

	echo "  svg.append(\"g\")\n";
	echo "      .attr(\"class\", \"x axis\")\n";
	echo "      .attr(\"transform\", \"translate(0,\" + height + \")\")\n";
	echo "      .call(xAxis);\n";

	echo "  svg.append(\"g\")\n";
	echo "      .attr(\"class\", \"y axis\")\n";
	echo "      .call(yAxis)\n";
	echo "    .append(\"text\")\n";
	echo "      .attr(\"transform\", \"rotate(-90)\")\n";
	echo "      .attr(\"y\", 6)\n";
	echo "      .attr(\"dy\", \".71em\")\n";
	echo "      .style(\"text-anchor\", \"end\")\n";
        echo "      .text(\"".$this->campos_ce["desy"]."\");\n";

	echo "  var path = svg.append(\"path\")\n";
	echo "      .datum(data)\n";
	echo "      .attr(\"class\", \"line\")\n";
	echo "      .attr(\"d\", line);\n";
        echo "  var pathLength= path.node().getTotalLength();\n";

        echo " path\n";
        echo "   .attr('stroke-dasharray', pathLength + ' ' + pathLength)\n";
        echo "   .attr('stroke-dashoffset', pathLength)\n";
        echo "   .transition()\n";
        echo "   .duration(2000)\n";
        echo "   .ease('linear')\n";
        echo "   .attr('stroke-dashoffset', 0);\n";

        echo "  svg.selectAll(\"textvas\")\n";
        echo "      .data(data)\n";
        echo "    .enter().append(\"text\")\n";
        echo "      .attr(\"class\", \"textvas\")\n";
        echo "      .text(function(d) { wltotal=wltotal+d.yy; return d.yy ;} ) \n";
        echo "      .style(\"text-anchor\", \"start\")\n";
        echo "      .attr(\"x\", function(d) { return x(d.xx); })\n";
        echo "      .attr(\"y\", function(d) { return y(d.yy); })\n";
        echo "      .attr(\"height\", function(d) { return height - y(d.yy); })\n";
        echo "      .attr(\"fill\",\"red\");\n";

        echo "  document.getElementById(\"wltotal\").outerText=\"Total \"+wltotal;\n";


	echo "});\n";
	echo "</script>\n";
   }

   function groupedbar()
   {
      $this->generadatos();
      $this->preparadatos();

echo "<!DOCTYPE html>\n";
echo "<meta charset=\utf-8\>\n";
    echo " <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "<meta name='mobile-web-app-capable' content='yes' />";
    echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
echo "<style>\n";

echo "body {\n";
echo "  font: 10px sans-serif;\n";
echo "}\n";

echo ".axis path,\n";
echo ".axis line {\n";
echo "  fill: none;\n";
echo "  stroke: #000;\n";
echo "  shape-rendering: crispEdges;\n";
echo "}\n";

echo ".bar {\n";
echo "  fill: steelblue;\n";
echo "}\n";

        echo ".textvas {\n";
        echo "  font: 10px sans-serif;";
        echo "}\n";

echo ".x.axis path {\n";
echo "  display: none;\n";
echo "}\n";

echo "</style>\n";
        echo "<form>\n";
        echo "<table>";
        echo "<tr><td colspan=2><h2>Estadistica ".$this->campos_so["descripcion"]." ".$this->rango."</h2></td></tr>";
        echo "<tr><td><input type=\"checkbox\"> Ordena valores</td>\n";
        echo "<td id=wltotal >Total</td></tr>\n";
        echo "</table>";
        echo "</form>\n";

echo "<body>\n";
echo "<script src=\"d3.v3.min.js\"></script>\n";
echo "<script>\n";

        echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
        echo "    width = ".$this->campos_ce["ancho"]." - margin.left - margin.right,\n";
        echo "    height = ".$this->campos_ce["alto"]." - margin.top - margin.bottom;\n";
        echo "var wltotal=0;\n";


echo "var x0 = d3.scale.ordinal()\n";
echo "    .rangeRoundBands([0, width], .1);\n";

echo "var x1 = d3.scale.ordinal();\n";

echo "var y = d3.scale.linear()\n";
echo "    .range([height, 0]);\n";

        echo "var color = d3.scale.category20();\n";
        echo "var wltotales = [];\n";

echo "var xAxis = d3.svg.axis()\n";
echo "    .scale(x0)\n";
echo "    .orient(\"bottom\");\n";

echo "var yAxis = d3.svg.axis()\n";
echo "    .scale(y)\n";
echo "    .orient(\"left\")\n";
echo "    .tickFormat(d3.format(\"".$this->campos_ce["formatodex"]."\"));\n";

echo "var svg = d3.select(\"body\").append(\"svg\")\n";
echo "    .attr(\"width\", width + margin.left + margin.right)\n";
echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
echo "  .append(\"g\")\n";
echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
echo "  var ageNames = d3.keys(data[0]).filter(function(key) { return key !== \"ag\"; });\n";
echo "  data.forEach(function(d) {\n";
echo "    d.ages = ageNames.map(function(name) { return {name: name, value: +d[name]}; });\n";
echo "  });\n";

echo "  x0.domain(data.map(function(d) { return d.ag; }));\n";
echo "  x1.domain(ageNames).rangeRoundBands([0, x0.rangeBand()]);\n";
echo "  y.domain([0, d3.max(data, function(d) { return d3.max(d.ages, function(d) { return d.value; }); })]);\n";

echo "  svg.append(\"g\")\n";
echo "      .attr(\"class\", \"x axis\")\n";
echo "      .attr(\"transform\", \"translate(0,\" + height + \")\")\n";
echo "      .call(xAxis);\n";

echo "  svg.append(\"g\")\n";
echo "      .attr(\"class\", \"y axis\")\n";
echo "      .call(yAxis)\n";
echo "    .append(\"text\")\n";
echo "      .attr(\"transform\", \"rotate(-90)\")\n";
echo "      .attr(\"y\", 6)\n";
echo "      .attr(\"dy\", \".71em\")\n";
echo "      .style(\"text-anchor\", \"end\")\n";
echo "      .text(\"".$this->campos_ce["desy"]."\");\n";

echo "  var state = svg.selectAll(\".state\")\n";
echo "      .data(data)\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"g\")\n";
echo "      .attr(\"transform\", function(d) { return \"translate(\" + x0(d.ag) + \",0)\"; });\n";

echo "  state.selectAll(\"rect\")\n";
echo "      .data(function(d) { return d.ages; })\n";
echo "      .enter().append(\"rect\")\n";
echo "      .attr(\"width\", x1.rangeBand())\n";
echo "      .attr(\"x\", function(d) { return x1(d.name); })\n";
echo "      .attr(\"y\", function(d) { wltotal=wltotal+d.value; x=ageNames.indexOf(d.name); wltotales[x]=(isNaN(wltotales[x]) ? 0 : wltotales[x])+d.value;  return y(d.value); })\n";
echo "      .attr(\"height\", function(d) { return height - y(d.value); })\n";
echo "      .style(\"fill\", function(d) { return color(d.name); });\n";

        echo "  state.selectAll(\"textvas\")\n";
        echo "      .data(function(d) { return d.ages; })\n";
        echo "    .enter().append(\"text\")\n";
        echo "      .attr(\"class\", \"textvas\")\n";
        echo "      .text(function(d) { return d.value=='0' ? '' : d.value ;} ) \n";
        echo "      .style(\"text-anchor\", \"start\")\n";
        echo "      .attr(\"x\", function(d) { return x1(d.name); })\n";
        echo "      .attr(\"width\", x1.rangeBand())\n";
##        echo "      .attr(\"writing-mode\", \"tb\" )\n";
        echo "      .attr(\"y\", function(d) { return y(d.value); })\n";
        echo "      .attr(\"height\", function(d) { return height - y(d.value); })\n";
        echo "      .attr(\"fill\",\"red\");\n";


echo "  var legend = svg.selectAll(\".legend\")\n";
echo "      .data(ageNames.slice().reverse())\n";
echo "      .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"legend\")\n";
echo "      .attr(\"transform\", function(d, i) { return \"translate(0,\" + i * 20 + \")\"; });\n";
echo "  document.getElementById(\"wltotal\").outerText=\"Total \"+wltotal;\n";

echo "  legend.append(\"rect\")\n";
echo "      .attr(\"x\", width - 18)\n";
echo "      .attr(\"width\", 18)\n";
echo "      .attr(\"height\", 18)\n";
echo "      .style(\"fill\", color);\n";
echo "  legend.append(\"text\")\n";
echo "      .attr(\"x\", width - 24)\n";
echo "      .attr(\"y\", 9)\n";
echo "      .attr(\"dy\", \".35em\")\n";
echo "      .style(\"text-anchor\", \"end\")\n";
echo "      .text(function(d) {  x=ageNames.indexOf(d); return d + ' ' + wltotales[x]; });\n";
echo "});\n";

echo "</script>\n";
   }

   function cuadrados()
   {
      $this->generadatos();
      $this->preparadatos();

echo "<!DOCTYPE html>\n";
echo "<meta charset=\"utf-8\">\n";
echo "<style>\n";

echo "body {\n";
echo "  font: 10px sans-serif;\n";
echo "}\n";

echo ".axis path,\n";
echo ".axis line {\n";
echo "  fill: none;\n";
echo "  stroke: #000;\n";
echo "  shape-rendering: crispEdges;\n";
echo "}\n";

echo ".bar {\n";
echo "  fill: steelblue;\n";
echo "}\n";

echo ".textvas {\n";
        echo "  font: 10px sans-serif;";
echo "}\n";

echo ".x.axis path {\n";
echo "  display: none;\n";
echo "}\n";

echo "</style>\n";
        ##echo "<form>\n";
        ##echo "<table>";
        ##echo "<tr><td colspan=2><h2>Estadistica ".$this->campos_so["descripcion"]." ".$this->rango."</h2></td></tr>";
        ##echo "<tr><td><input type=\"checkbox\"> Ordena valores</td>\n";
        ##echo "<td id=wltotal >Total</td></tr>\n";
        ##echo "</table>";
        ##echo "</form>\n";

echo "<body>\n";
echo "<script src=\"d3.v3.min.js\"></script>\n";
echo "<script>\n";

     echo "var margin = {top: 0, right: 0, bottom: 0, left: 0},\n";
     echo "    width = ".$this->campos_ce["ancho"]." - margin.left - margin.right,\n";
     echo "    height = ".$this->campos_ce["alto"]." - margin.top - margin.bottom;\n";
        echo "var wltotal=0;\n";


echo "var x = d3.scale.ordinal()\n";
echo "    .rangeRoundBands([0, width], .1);\n";

echo "var y = d3.scale.linear()\n";
echo "    .rangeRound([height, 0]);\n";

echo "var color = d3.scale.category20();\n";
echo "var wltotales = [];\n";

echo "var xAxis = d3.svg.axis()\n";
echo "    .scale(x)\n";
echo "    .orient(\"bottom\");\n";

echo "var yAxis = d3.svg.axis()\n";
echo "    .scale(y)\n";
echo "    .orient(\"left\")\n";
echo "    .tickFormat(d3.format(\"".$this->campos_ce["formatodex"]."\"));\n";

echo "var svg = d3.select(\"body\").append(\"svg\")\n";
echo "    .attr(\"width\", width + margin.left + margin.right)\n";
echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
echo "  .append(\"g\")\n";
echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
echo "  color.domain(d3.keys(data[0]).filter(function(key) { return key !== \"ag\"; }));\n";

echo "  data.forEach(function(d) {\n";
echo "    var y0 = 0;\n";
echo "    d.ages = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });\n";
echo "    d.total = d.ages[d.ages.length - 1].y1;\n";
echo "  });\n";

echo "  data.sort(function(a, b) { return b.total - a.total; });\n";

echo "  x.domain(data.map(function(d) { return d.ag; }));\n";
echo "  y.domain([0, d3.max(data, function(d) { return d.total; })]);\n";

echo "  svg.append(\"g\")\n";
echo "      .attr(\"class\", \"x axis\")\n";
echo "      .attr(\"transform\", \"translate(0,\" + height + \")\")\n";
echo "      .call(xAxis);\n";

/*
echo "  svg.append(\"g\")\n";
echo "      .attr(\"class\", \"y axis\")\n";
echo "      .call(yAxis)\n";
echo "    .append(\"text\")\n";
echo "      .attr(\"transform\", \"rotate(-90)\")\n";
echo "      .attr(\"y\", 6)\n";
echo "      .attr(\"dy\", \".71em\")\n";
echo "      .style(\"text-anchor\", \"end\")\n";
echo "      .text(\"".$this->campos_ce["desy"]."\");\n";
*/

echo "  var state = svg.selectAll(\".state\")\n";
echo "      .data(data)\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"g\")\n";
echo "      .attr(\"transform\", function(d) { return \"translate(\" + x(d.ag) + \",0)\"; });\n";

echo "  state.selectAll(\"rect\")\n";
echo "      .data(function(d) { return d.ages; })\n";
echo "    .enter().append(\"rect\")\n";
echo "      .attr(\"width\", x.rangeBand())\n";
echo "      .attr(\"y\", function(d) { wltotal=wltotal+(d.y1-d.y0); x=color.domain().indexOf(d.name); wltotales[x]=(isNaN(wltotales[x]) ? 0 : wltotales[x])+(d.y1-d.y0); return y(d.y1); })\n";
echo "      .attr(\"height\", function(d) { return y(d.y0) - y(d.y1); })\n";
echo "      .style(\"fill\", function(d) { return color(d.name); });\n";

        echo "  state.selectAll(\"textvas\")\n";
        echo "      .data(function(d) { return d.ages; })\n";
        echo "    .enter().append(\"text\")\n";
        echo "      .attr(\"class\", \"textvas\")\n";
        echo "      .text(function(d) { return d.total;} ) \n";
        echo "      .style(\"text-anchor\", \"start\")\n";
        echo "      .attr(\"y\", function(d) {  return y(d.y1); })\n";
        echo "      .attr(\"height\", function(d) { return y(d.y0) - y(d.y1); })\n";
        echo "      .attr(\"fill\",\"red\");\n";



echo "  var legend = svg.selectAll(\".legend\")\n";
echo "      .data(color.domain().slice().reverse())\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"legend\")\n";
echo "      .attr(\"transform\", function(d, i) { return \"translate(0,\" + i * 20 + \")\"; });\n";
##echo "  document.getElementById(\"wltotal\").outerText=\"Total \"+wltotal;\n";


/*
echo "  legend.append(\"rect\")\n";
echo "      .attr(\"x\", width - 18)\n";
echo "      .attr(\"width\", 18)\n";
echo "      .attr(\"height\", 18)\n";
echo "      .style(\"fill\", color);\n";
*/

/*
echo "  legend.append(\"text\")\n";
echo "      .attr(\"x\", width - 24)\n";
echo "      .attr(\"y\", 9)\n";
echo "      .attr(\"dy\", \".35em\")\n";
echo "      .style(\"text-anchor\", \"end\")\n";
echo "      .text(function(d) { x=color.domain().indexOf(d); return d + ' ' + wltotales[x]; });\n";
*/

echo "});\n";

echo "</script>\n";
   }

   function stackedbar()
   {
      $this->generadatos();
      $this->preparadatos();

echo "<!DOCTYPE html>\n";
echo "<meta charset=\"utf-8\">\n";
    echo " <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "<meta name='mobile-web-app-capable' content='yes' />";
    echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
echo "<style>\n";

echo "body {\n";
echo "  font: 10px sans-serif;\n";
echo "}\n";

echo ".axis path,\n";
echo ".axis line {\n";
echo "  fill: none;\n";
echo "  stroke: #000;\n";
echo "  shape-rendering: crispEdges;\n";
echo "}\n";

echo ".bar {\n";
echo "  fill: steelblue;\n";
echo "}\n";

echo ".textvas {\n";
        echo "  font: 10px sans-serif;";
echo "}\n";

echo ".x.axis path {\n";
echo "  display: none;\n";
echo "}\n";

echo "</style>\n";
        echo "<form>\n";
        echo "<table>";
        echo "<tr><td colspan=2><h2>Estadistica ".$this->campos_so["descripcion"]." ".$this->rango."</h2></td></tr>";
        echo "<tr><td><input type=\"checkbox\"> Ordena valores</td>\n";
        echo "<td id=wltotal >Total</td></tr>\n";
        echo "</table>";
        echo "</form>\n";

echo "<body>\n";
echo "<script src=\"d3.v3.min.js\"></script>\n";
echo "<script>\n";

     echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
     echo "    width = ".$this->campos_ce["ancho"]." - margin.left - margin.right,\n";
     echo "    height = ".$this->campos_ce["alto"]." - margin.top - margin.bottom;\n";
        echo "var wltotal=0;\n";


echo "var x = d3.scale.ordinal()\n";
echo "    .rangeRoundBands([0, width], .1);\n";

echo "var y = d3.scale.linear()\n";
echo "    .rangeRound([height, 0]);\n";

echo "var color = d3.scale.category20();\n";
echo "var wltotales = [];\n";

echo "var xAxis = d3.svg.axis()\n";
echo "    .scale(x)\n";
echo "    .orient(\"bottom\");\n";

echo "var yAxis = d3.svg.axis()\n";
echo "    .scale(y)\n";
echo "    .orient(\"left\")\n";
echo "    .tickFormat(d3.format(\"".$this->campos_ce["formatodex"]."\"));\n";

echo "var svg = d3.select(\"body\").append(\"svg\")\n";
echo "    .attr(\"width\", width + margin.left + margin.right)\n";
echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
echo "  .append(\"g\")\n";
echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
echo "  color.domain(d3.keys(data[0]).filter(function(key) { return key !== \"ag\"; }));\n";

echo "  data.forEach(function(d) {\n";
echo "    var y0 = 0;\n";
echo "    d.ages = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });\n";
echo "    d.total = d.ages[d.ages.length - 1].y1;\n";
echo "  });\n";

echo "  data.sort(function(a, b) { return b.total - a.total; });\n";

echo "  x.domain(data.map(function(d) { return d.ag; }));\n";
echo "  y.domain([0, d3.max(data, function(d) { return d.total; })]);\n";

echo "  svg.append(\"g\")\n";
echo "      .attr(\"class\", \"x axis\")\n";
echo "      .attr(\"transform\", \"translate(0,\" + height + \")\")\n";
echo "      .call(xAxis);\n";

echo "  svg.append(\"g\")\n";
echo "      .attr(\"class\", \"y axis\")\n";
echo "      .call(yAxis)\n";
echo "    .append(\"text\")\n";
echo "      .attr(\"transform\", \"rotate(-90)\")\n";
echo "      .attr(\"y\", 6)\n";
echo "      .attr(\"dy\", \".71em\")\n";
echo "      .style(\"text-anchor\", \"end\")\n";
echo "      .text(\"".$this->campos_ce["desy"]."\");\n";

echo "  var state = svg.selectAll(\".state\")\n";
echo "      .data(data)\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"g\")\n";
echo "      .attr(\"transform\", function(d) { return \"translate(\" + x(d.ag) + \",0)\"; });\n";

echo "  state.selectAll(\"rect\")\n";
echo "      .data(function(d) { return d.ages; })\n";
echo "    .enter().append(\"rect\")\n";
echo "      .attr(\"width\", x.rangeBand())\n";
echo "      .attr(\"y\", function(d) { wltotal=wltotal+(d.y1-d.y0); x=color.domain().indexOf(d.name); wltotales[x]=(isNaN(wltotales[x]) ? 0 : wltotales[x])+(d.y1-d.y0); return y(d.y1); })\n";
echo "      .attr(\"height\", function(d) { return y(d.y0) - y(d.y1); })\n";
echo "      .style(\"fill\", function(d) { return color(d.name); });\n";

        echo "  state.selectAll(\"textvas\")\n";
        echo "      .data(function(d) { return d.ages; })\n";
        echo "    .enter().append(\"text\")\n";
        echo "      .attr(\"class\", \"textvas\")\n";
        echo "      .text(function(d) { return d.total;} ) \n";
        echo "      .style(\"text-anchor\", \"start\")\n";
        echo "      .attr(\"y\", function(d) {  return y(d.y1); })\n";
        echo "      .attr(\"height\", function(d) { return y(d.y0) - y(d.y1); })\n";
        echo "      .attr(\"fill\",\"red\");\n";



echo "  var legend = svg.selectAll(\".legend\")\n";
echo "      .data(color.domain().slice().reverse())\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"legend\")\n";
echo "      .attr(\"transform\", function(d, i) { return \"translate(0,\" + i * 20 + \")\"; });\n";
echo "  document.getElementById(\"wltotal\").outerText=\"Total \"+wltotal;\n";


echo "  legend.append(\"rect\")\n";
echo "      .attr(\"x\", width - 18)\n";
echo "      .attr(\"width\", 18)\n";
echo "      .attr(\"height\", 18)\n";
echo "      .style(\"fill\", color);\n";

echo "  legend.append(\"text\")\n";
echo "      .attr(\"x\", width - 24)\n";
echo "      .attr(\"y\", 9)\n";
echo "      .attr(\"dy\", \".35em\")\n";
echo "      .style(\"text-anchor\", \"end\")\n";
echo "      .text(function(d) { x=color.domain().indexOf(d); return d + ' ' + wltotales[x]; });\n";
//echo "      .text(function(d) { console.debug('d='+d); x=ageNames.indexOf(d); return d + ' ' + wltotales[x]; });\n";

echo "});\n";

echo "</script>\n";
   }
   function barchartsort()
   {
      $this->generadatos();
      $this->preparadatos();
      echo "<!DOCTYPE html>\n";
      echo "<meta charset=\"utf-8\">\n";
      echo "<style>\n";

	echo "body {\n";
	echo "  font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;\n";
	echo "  position: relative;\n";
	echo "  width: 960px;\n";
	echo "}";

	echo ".axis text {";
	echo "  font: 10px sans-serif;";
	echo "}"; 
	echo ".axis path,\n";
	echo ".axis line {\n";
	echo "  fill: none;\n";
	echo "  stroke: #000;\n";
	echo "  shape-rendering: crispEdges;\n";
	echo "}\n";

	echo ".bar {\n";
##	echo "  fill: steelblue;\n";
##	echo "  fill-opacity: .9;\n";
	echo "}\n";

	echo ".textvas {\n";
	echo "  font: 10px sans-serif;";
	echo "}\n";

	echo ".x.axis path {\n";
	echo "  display: none;\n";
	echo "}\n";

	echo "label {\n";
	echo "  position: absolute;\n";
	echo "  top: 10px;\n";
	echo "  right: 10px;\n";
	echo "}\n";

	echo "</style>\n";
	echo "<form>\n";
        echo "<table>";
        echo "<tr><td colspan=2><h2>ESTADISTICA ".$this->campos_so["descripcion"]." ".$this->rango."</h2></td></tr>";
	echo "<tr><td><input type=\"checkbox\"> Ordena valores</td>\n";
	echo "<td id=wltotal >Total</td></tr>\n";
        echo "</table>";
	echo "</form>\n";
	echo "<script src=\"d3.v3.min.js\"></script>\n";
	echo "<script>\n";

	echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
	Echo "    width = ".$this->campos_ce["ancho"]." - margin.left - margin.right,\n";
	echo "    height = ".$this->campos_ce["alto"]." - margin.top - margin.bottom;\n";

	echo "var formatPercent = d3.format(\"".$this->campos_ce["formatodex"]."\");\n";

	echo "var x = d3.scale.ordinal()\n";
	echo "    .rangeRoundBands([0, width], .1, 1);\n";

	echo "var y = d3.scale.linear()\n";
	echo "    .range([height, 0]);\n";

	echo "var xAxis = d3.svg.axis()\n";
	echo "    .scale(x)\n";
	echo "    .orient(\"bottom\");\n";

	echo "var yAxis = d3.svg.axis()\n";
	echo "    .scale(y)\n";
	echo "    .orient(\"left\")\n";
##	echo "    .tickFormat(formatPercent);\n";
        echo "var wltotal=0;\n";


	echo "var svg = d3.select(\"body\").append(\"svg\")\n";
	echo "    .attr(\"width\", width + margin.left + margin.right)\n";
	echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
	echo "  .append(\"g\")\n";
	echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

	echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
	echo "  data.forEach(function(d) {\n";
	echo "    d.yy = +d.yy;\n";
	echo "  });\n";

	echo "  x.domain(data.map(function(d) { return d.xx; }));\n";
	echo "  y.domain([0, d3.max(data, function(d) { return d.yy; })]);\n";

	echo "  svg.append(\"g\")\n";
	echo "      .attr(\"class\", \"x axis\")\n";
	echo "      .attr(\"transform\", \"translate(0,\" + height + \")\")\n";
	echo "      .call(xAxis);\n";

	echo "  svg.append(\"g\")\n";
	echo "      .attr(\"class\", \"y axis\")\n";
	echo "      .call(yAxis)\n";
	echo "    .append(\"text\")\n";
	echo "      .attr(\"transform\", \"rotate(-90)\")\n";
	echo "      .attr(\"y\", 6)\n";
	echo "      .attr(\"dy\", \".71em\")\n";
	echo "      .style(\"text-anchor\", \"end\")\n"; 
        echo "      .text(\"".$this->campos_ce["desy"]."\");\n";

	echo "  svg.selectAll(\"bar\")\n";
	echo "      .data(data)\n";
	echo "    .enter().append(\"rect\")\n";
	echo "      .attr(\"class\", \"bar\")\n";
	echo "      .attr(\"x\", function(d) {  return x(d.xx); })\n";
	echo "      .attr(\"width\", x.rangeBand())\n";
	echo "      .attr(\"y\", function(d) { return y(d.yy); })\n";
	echo "      .attr(\"fill\", function(d) { return \"rgb(0,0, \" + (d.yy*100) + \")\"; })\n";
	echo "      .attr(\"height\", function(d) { return height - y(d.yy); });\n";

        echo "  svg.selectAll(\"textvas\")\n";
        echo "      .data(data)\n";
        echo "    .enter().append(\"text\")\n";
	echo "      .attr(\"class\", \"textvas\")\n";
        echo "      .text(function(d) { wltotal=wltotal+d.yy; return d.yy ;} ) \n";
	echo "      .style(\"text-anchor\", \"start\")\n"; 
        echo "      .attr(\"x\", function(d) { return x(d.xx); })\n";
##        echo "      .attr(\"x\", function(d,i) { return i * (w / dataset.length) + (w / dataset.length -barpadding ) / 2; })\n";
        echo "      .attr(\"width\", x.rangeBand())\n";
        echo "      .attr(\"y\", function(d) { return y(d.yy); })\n";
        echo "      .attr(\"height\", function(d) { return height - y(d.yy); })\n";
        echo "      .attr(\"fill\",\"red\");\n";


	echo "  d3.select(\"input\").on(\"change\", change);\n";
        echo "  document.getElementById(\"wltotal\").outerText=\"Total \"+wltotal;\n";

	echo "  var sortTimeout = setTimeout(function() {\n";
	echo "    d3.select(\"input\").property(\"checked\", true).each(change);\n";
	echo "  }, 2000);\n";

	echo "  function change() {\n";
	echo "    clearTimeout(sortTimeout);\n";

	echo "    var x0 = x.domain(data.sort(this.checked\n";
	echo "        ? function(a, b) { return b.yy - a.yy; }\n";
	echo "        : function(a, b) { return d3.ascending(a.xx, b.xx); })\n";
	echo "        .map(function(d) { return d.xx; }))\n";
	echo "        .copy();\n";

	echo "    var transition = svg.transition().duration(750),\n";
	echo "        delay = function(d, i) { return i * 50; };\n";

	echo "    transition.selectAll(\".bar\")\n";
	echo "        .delay(delay)\n";
	echo "        .attr(\"x\", function(d) { return x0(d.xx); });\n";

	echo "    transition.selectAll(\".textvas\")\n";
	echo "        .delay(delay)\n";
	##echo "        .attr(\"x\", function(d) { x0(d.xx); console.debug('vas' +  x0(d.xx));});\n";
	echo "        .attr(\"x\", function(d) { return x0(d.xx); });\n";
##	echo "        .attr(\"y\", function(d) { x0(d.yy); });\n";

	echo "    transition.select(\".x.axis\")\n";
	echo "        .call(xAxis)\n";
	echo "      .selectAll(\"g\")\n";
	echo "        .delay(delay);\n";
	echo "  }\n";
	echo "});\n";

	echo "</script>\n";
   }

   /**
   *   decodifica un dato enviado por una pagina
   *   @param string $data string campos separa
   *   @return array arreglo de acuerdo a los datos enviados
   **/
   function decodeGetData($data)
   {
    foreach ($data as $k => $v)
           $data[$k] = ((is_array($v)) ? decodeGetData($v) : urldecode($v));
    return $data;
   }

   /**
     * ejecuta una funcion de acuerdo al xml recibido
     **/
   function procesa()
   {
     $arguments = array();
     $arguments = $this->decodeGetData($this->argumentos);
     $this->parametros();
     call_user_func_array(array($this,$this->campos_ce["funcion"]),$arguments);
   }
}
?>
