<?php
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
   var $titulo="";
   var $rango="";
   var $ancho="";
   var $alto="";
   var $desy="";
   var $formatodex="";

   function generadatos()
   {
           $sql="select * from estadisticas.cat_estadisticas as ce where ce.id_estadistica=".$this->argumentos["wl_id_solicitud"];
           $sql_result = @pg_exec($this->connection,$sql);
           if (strlen(pg_last_error($this->connection))>0)
           {
                         echo "<error>Error generadatos".pg_last_error($this->connection)."</error>";
                        return false;
           }
           $Row = pg_fetch_array($sql_result, 0);
           if ($Row["funcion"]=="barchartsort" || $Row["funcion"]=="linechart")
           {
                 $sql="delete from estadisticas.estadisticas_datos where id_solicitud=".$this->argumentos["wl_id_solicitud"].";".
                      " insert into estadisticas.estadisticas_datos (x,y,id_solicitud)".
                      " select array_agg(dia) as dia,array_agg(cuantos) as y,".$this->argumentos["wl_id_solicitud"].
                      " from (".
                      " select ".$Row["groupby"]." as dia ".
                      " ,".$Row["quecalculo"]."  as cuantos ".
                      " from ".$Row["nspname"].".".$Row["tabla"]." as ge".
                      " where ".$Row["filtro"]." between '".$this->argumentos["wl_fecha_inicial"]."' and '".$this->argumentos["wl_fecha_final"]."'".
                      " group by 1  ".
                      " order by 1  ".
                      " ) as a";
           }
           if ($Row["funcion"]=="groupedbar")
           {
                 $sql="delete from estadisticas.estadisticas_datos where id_solicitud=".$this->argumentos["wl_id_solicitud"].";".
                      " insert into estadisticas.estadisticas_datos (agrupacion,x,y,id_solicitud)".
                      " select agrupacion ,array_agg(dia) as dia,array_agg(cuantos) as y,".$this->argumentos["wl_id_solicitud"].
                      " from (".
                      " select ".$Row["groupby"]." as dia ".
                      " ,".$Row["agrupacion"]." as agrupacion ".
                      " ,".$Row["quecalculo"]."  as cuantos ".
                      " from ".$Row["nspname"].".".$Row["tabla"]." as ge".
                      " where ".$Row["filtro"]." between '".$this->argumentos["wl_fecha_inicial"]."' and '".$this->argumentos["wl_fecha_final"]."'".
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

   function preparadatos()
   {
      $soli=$this->argumentos["wl_id_solicitud"];
      $this->nombreArchivo=$this->nombreArchivo.$this->argumentos["wl_id_solicitud"].".csv";

      $sql="select fecha_inicial,fecha_final ".
           ",(select descripcion from estadisticas.cat_estadisticas as ce where ce.id_estadistica=se.id_estadistica) as titulo ".
           ",(select ancho from estadisticas.cat_estadisticas as ce where ce.id_estadistica=se.id_estadistica) as ancho ".
           ",(select alto  from estadisticas.cat_estadisticas as ce where ce.id_estadistica=se.id_estadistica) as alto ".
           ",(select desy  from estadisticas.cat_estadisticas as ce where ce.id_estadistica=se.id_estadistica) as desy ".
           ",(select formatodex  from estadisticas.cat_estadisticas as ce where ce.id_estadistica=se.id_estadistica) as formatodex ".
           " from estadisticas.sol_estadisticas as se where id_solicitud=".$soli;
      $sql_result = @pg_exec($this->connection,$sql);
                 if (strlen(pg_last_error($this->connection))>0)
                 {
                        echo "<error>Error preparadatos sol_estadisticas ".pg_last_error($this->connection)."</error>";
                        return false;
                 }

      $rowe = pg_fetch_array($sql_result, 0);
      $this->titulo=$rowe["titulo"];
      $this->rango="del ".$rowe["fecha_inicial"]." al ".$rowe["fecha_final"];
      $this->ancho=$rowe["ancho"];
      $this->alto=$rowe["alto"];
      $this->desy=$rowe["desy"];
      $this->formatodex=$rowe["formatodex"];

      $sql="select x as xx ,y as yy from estadisticas.estadisticas_datos where id_solicitud=".$soli;
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
                ##if (!fwrite ($ff,"<table border=\"1\">")) { echo "no pudo escribir archivo"; }
                ##if (!fwrite ($ff,"<tr>")) { echo "no pudo escribir archivo"; }
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
                for ($i=0; $i < $num ;$i++)
                { $row = pg_fetch_array($sql_result, $i);
                  ##if (!fwrite ($ff,"\n")) { echo "no pudo escribir archivo"; }
                  ##foreach ($rowe as $value)
                  ##{ if (Key($rowe)<"100")
                  ##  { }
                  ##  else
                  ##  { 
                       $xx=explode(",",str_replace("}","",str_replace("{","",$row["xx"]))); 
                       $yy=explode(",",str_replace("}","",str_replace("{","",$row["yy"]))); 
                       for($i=0;$i<count($xx);$i++) {
                           if (!fwrite ($ff,$xx[$i].",".$yy[$i]."\n")) { echo "no pudo escribir archivo"; } 
                       }
                   ## }
                   ## next($rowe);
                  ##}
                  ##if (!fwrite ($ff,"\n")) { echo "no pudo escribir archivo"; }
                }
                fclose($ff);
   }
 
   function linechart()
   {
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

	echo "</style>\n";
        echo "<form>\n";
        echo "<h1>Estadistica de ".$this->titulo."<h1>";
        echo "<h2>".$this->rango."<h2>";
       ## echo "<label><input type=\"checkbox\"> Ordena valores</label>\n";
        echo "</form>\n";

	echo "<body>\n";
	echo "<script src=\"d3.v3.min.js\"></script>\n";
	echo "<script>\n";

	echo "var margin = {top: 20, right: 20, bottom: 30, left: 50},\n";
	echo "    width = 960 - margin.left - margin.right,\n";
	echo "    height = 500 - margin.top - margin.bottom;\n";

	echo "var parseDate = d3.time.format(\"".$this->formatodex."\").parse;\n";

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
	echo "    .x(function(d) { console.debug('line:'+d.xx); return x(d.xx); })\n";
	echo "    .y(function(d) { console.debug('line:'+d.yy); return y(d.yy); });\n";

	echo "var svg = d3.select(\"body\").append(\"svg\")\n";
	echo "    .attr(\"width\", width + margin.left + margin.right)\n";
	echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
	echo "  .append(\"g\")\n";
	echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

	echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
##echo "d3.tsv(\"data.tsv\", function(error, data) {\n";
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
        echo "      .text(\"".$this->desy."\");\n";
	echo "  svg.append(\"path\")\n";
	echo "      .datum(data)\n";
	echo "      .attr(\"class\", \"line\")\n";
	echo "      .attr(\"d\", line);\n";
	echo "});\n";
	echo "</script>\n";
   }

   function groupedbar()
   {
      $this->generadatos();
      $this->preparadatos();

echo "<!DOCTYPE html>\n";
echo "<meta charset=\utf-8\>\n";
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

echo ".x.axis path {\n";
echo "  display: none;\n";
echo "}\n";

echo "</style>\n";
echo "<body>\n";
echo "<script src=\"d3.v3.min.js\"></script>\n";
echo "<script>\n";

echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
echo "    width = 960 - margin.left - margin.right,\n";
echo "    height = 500 - margin.top - margin.bottom;\n";

echo "var x0 = d3.scale.ordinal()\n";
echo "    .rangeRoundBands([0, width], .1);\n";

echo "var x1 = d3.scale.ordinal();\n";

echo "var y = d3.scale.linear()\n";
echo "    .range([height, 0]);\n";

echo "var color = d3.scale.ordinal()\n";
echo "    .range([\"#98abc5\", \"#8a89a6\", \"#7b6888\", \"#6b486b\", \"#a05d56\", \"#d0743c\", \"#ff8c00\"]);\n";

echo "var xAxis = d3.svg.axis()\n";
echo "    .scale(x0)\n";
echo "    .orient(\"bottom\");\n";

echo "var yAxis = d3.svg.axis()\n";
echo "    .scale(y)\n";
echo "    .orient(\"left\")\n";
echo "    .tickFormat(d3.format(\"".$this->formatodex."\"));\n";

echo "var svg = d3.select(\"body\").append(\"svg\")\n";
echo "    .attr(\"width\", width + margin.left + margin.right)\n";
echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
echo "  .append(\"g\")\n";
echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

echo "d3.csv(\"data.csv\", function(error, data) {\n";
echo "  var ageNames = d3.keys(data[0]).filter(function(key) { return key !== \"State\"; });\n";

echo "  data.forEach(function(d) {\n";
echo "    d.ages = ageNames.map(function(name) { return {name: name, value: +d[name]}; });\n";
echo "  });\n";

echo "  x0.domain(data.map(function(d) { return d.State; }));\n";
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
echo "      .text(\"Population\");\n";

echo "  var state = svg.selectAll(\".state\")\n";
echo "      .data(data)\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"g\")\n";
echo "      .attr(\"transform\", function(d) { return \"translate(\" + x0(d.State) + \",0)\"; });\n";

echo "  state.selectAll(\"rect\")\n";
echo "      .data(function(d) { return d.ages; })\n";
echo "    .enter().append(\"rect\")\n";
echo "      .attr(\"width\", x1.rangeBand())\n";
echo "      .attr(\"x\", function(d) { return x1(d.name); })\n";
echo "      .attr(\"y\", function(d) { return y(d.value); })\n";
echo "      .attr(\"height\", function(d) { return height - y(d.value); })\n";
echo "      .style(\"fill\", function(d) { return color(d.name); });\n";

echo "  var legend = svg.selectAll(\".legend\")\n";
echo "      .data(ageNames.slice().reverse())\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"legend\")\n";
echo "      .attr(\"transform\", function(d, i) { return \"translate(0,\" + i * 20 + \")\"; });\n";

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
echo "      .text(function(d) { return d; });\n";

echo "});\n";

echo "</script>\n";
   }
   function stackedbar()
   {
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

echo ".x.axis path {\n";
echo "  display: none;\n";
echo "}\n";

echo "</style>\n";
echo "<body>\n";
echo "<script src=\"d3.v3.min.js\"></script>\n";
echo "<script>\n";

echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
echo "    width = 960 - margin.left - margin.right,\n";
echo "    height = 500 - margin.top - margin.bottom;\n";

echo "var x = d3.scale.ordinal()\n";
echo "    .rangeRoundBands([0, width], .1);\n";

echo "var y = d3.scale.linear()\n";
echo "    .rangeRound([height, 0]);\n";

echo "var color = d3.scale.ordinal()\n";
echo "    .range([\"#98abc5\", \"#8a89a6\", \"#7b6888\", \"#6b486b\", \"#a05d56\", \"#d0743c\", \"#ff8c00\"]);\n";

echo "var xAxis = d3.svg.axis()\n";
echo "    .scale(x)\n";
echo "    .orient(\"bottom\");\n";

echo "var yAxis = d3.svg.axis()\n";
echo "    .scale(y)\n";
echo "    .orient(\"left\")\n";
echo "    .tickFormat(d3.format(\"".$this->formatodex."\"));\n";

echo "var svg = d3.select(\"body\").append(\"svg\")\n";
echo "    .attr(\"width\", width + margin.left + margin.right)\n";
echo "    .attr(\"height\", height + margin.top + margin.bottom)\n";
echo "  .append(\"g\")\n";
echo "    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");\n";

##echo "d3.csv(\"data.csv\", function(error, data) {\n";
echo "d3.csv(\"".$this->nombreArchivo."\", function(error, data) {\n";
echo "  color.domain(d3.keys(data[0]).filter(function(key) { return key !== \"State\"; }));\n";

echo "  data.forEach(function(d) {\n";
echo "    var y0 = 0;\n";
echo "    d.ages = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });\n";
echo "    d.total = d.ages[d.ages.length - 1].y1;\n";
echo "  });\n";

echo "  data.sort(function(a, b) { return b.total - a.total; });\n";

echo "  x.domain(data.map(function(d) { return d.State; }));\n";
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
echo "      .text(\"Population\");\n";

echo "  var state = svg.selectAll(\".state\")\n";
echo "      .data(data)\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"g\")\n";
echo "      .attr(\"transform\", function(d) { return \"translate(\" + x(d.State) + \",0)\"; });\n";

echo "  state.selectAll(\"rect\")\n";
echo "      .data(function(d) { return d.ages; })\n";
echo "    .enter().append(\"rect\")\n";
echo "      .attr(\"width\", x.rangeBand())\n";
echo "      .attr(\"y\", function(d) { return y(d.y1); })\n";
echo "      .attr(\"height\", function(d) { return y(d.y0) - y(d.y1); })\n";
echo "      .style(\"fill\", function(d) { return color(d.name); });\n";

echo "  var legend = svg.selectAll(\".legend\")\n";
echo "      .data(color.domain().slice().reverse())\n";
echo "    .enter().append(\"g\")\n";
echo "      .attr(\"class\", \"legend\")\n";
echo "      .attr(\"transform\", function(d, i) { return \"translate(0,\" + i * 20 + \")\"; });\n";

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
echo "      .text(function(d) { return d; });\n";

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
	echo "  fill: steelblue;\n";
	echo "  fill-opacity: .9;\n";
	echo "}\n";

	echo ".textvas {\n";
	##echo "  fill: steelblue;\n";
	##echo "  fill-opacity: .9;\n";
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
        echo "<h1>Estadistica de ".$this->titulo."<h1>";
        echo "<h2>".$this->rango."<h2>";
	echo "<label><input type=\"checkbox\"> Ordena valores</label>\n";
	echo "</form>\n";
	echo "<script src=\"d3.v3.min.js\"></script>\n";
	echo "<script>\n";

	echo "var margin = {top: 20, right: 20, bottom: 30, left: 40},\n";
	echo "    width = ".$this->ancho." - margin.left - margin.right,\n";
	echo "    height = ".$this->alto." - margin.top - margin.bottom;\n";

	##echo "var formatPercent = d3.format(\"0f\");\n";
	echo "var formatPercent = d3.format(\"".$this->formatodex."\");\n";

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
	echo "    .tickFormat(formatPercent);\n";

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
        echo "      .text(\"".$this->desy."\");\n";

	echo "  svg.selectAll(\"bar\")\n";
	echo "      .data(data)\n";
	echo "    .enter().append(\"rect\")\n";
	echo "      .attr(\"class\", \"bar\")\n";
	echo "      .attr(\"x\", function(d) { console.debug('xbar'+x(d.xx)); return x(d.xx); })\n";
	echo "      .attr(\"width\", x.rangeBand())\n";
	echo "      .attr(\"y\", function(d) { return y(d.yy); })\n";
	echo "      .attr(\"height\", function(d) { return height - y(d.yy); });\n";

        echo "  svg.selectAll(\"textvas\")\n";
        echo "      .data(data)\n";
        echo "    .enter().append(\"text\")\n";
	echo "      .attr(\"class\", \"textvas\")\n";
        echo "      .text(function(d) { return d.yy ;} ) \n";
	echo "      .style(\"text-anchor\", \"start\")\n"; 
        echo "      .attr(\"x\", function(d) { console.debug('xtex'+x(d.xx)); return x(d.xx); })\n";
        echo "      .attr(\"width\", x.rangeBand())\n";
        echo "      .attr(\"y\", function(d) { return y(d.yy); })\n";
        echo "      .attr(\"height\", function(d) { return height - y(d.yy); })\n";
        echo "      .attr(\"fill\",\"red\");\n";


	echo "  d3.select(\"input\").on(\"change\", change);\n";

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
     call_user_func_array(array($this,$this->funcion),$arguments);
   }
}
?>
