<?php
include("mensajes.php");
require_once("metadata.php");
class soldatos
{
  var $connection="";
  var $xlfechaini="";
  var $xlfechafin="";
  var $titulos="";
  var $datos=array();
  var $movto_mantto=array(); /*motimientos que se pueden hacer en el mantenimiento de una tabla 
                               i=insert,d=delete,u=update,s=select */
  var $tabla=""; /*tabla a la que le va a dar mantenimiento */
  var $boton="";
  var $accion="";
  var $destino="";

  function despledatos()
  {
      $this->inicio_html();
      $this->arma_js();
      $this->inicio_form();
      $this->inicio_tab();
      $this->quedatos_t();
      $this->quedatos();
      $this->botones();
      $this->fin_tab();
      $this->fin_form();
      $this->fin_html();
  }

  function arma_js()
  {
    include("cookies1.js");
    include("val_comunes.js");
               include("broseaing.js");
    echo "<script>\n";
    include("sortable.js");
    echo "function inicioforma() {\n";  // funcion para obtener el valor del dato de la cookiee
    echo "   var a='';\n";
    reset($this->datos);
    $z=0;
    while (current($this->datos) !== false)
    {
          switch (current($this->datos))
          {
             case 'FE':
                $this->obtendato('xlfechaini',$z);
                break;
             case 'RF':
                $this->obtendato('xlfechaini',$z);
                $this->obtendato('xlfechafin',$z+1);
                break;
             case 'PI':
                $this->obtendato('xlprini',$z);
                break;
             case 'PF':
                $this->obtendato('xlprfin',$z);
                break;
             case 'ES':
                $this->obtendato('xlestado',$z);
                break;
          }
          next($this->datos);
          $z=$z+1;
    }
    echo "}\n";
    echo "</script>";
    echo "<script>";
    echo "function valdatos() {\n";  // funcion para validar los datos en el cliente 
    echo "    var vd = new valcomunes();\n";
    reset($this->datos);
    while (current($this->datos) !== false)
    {
          switch (current($this->datos))
          {
             case 'FE':
                echo "    vd.ponfecha(document.forms[0].xlfechaini);\n";
                break;
             case 'RF':
                echo "    vd.ponfecha(document.forms[0].xlfechaini);\n";
                echo "    vd.ponfechaf(document.forms[0].xlfechafin);\n";
                break;
             case 'PI':
                echo "    vd.ponpi(document.forms[0].xlprini);\n";
                break;
             case 'PF':
                echo "    vd.ponpf(document.forms[0].xlprfin);\n";
                break;
             case 'ES':
                echo "    vd.pones(document.forms[0].xlestado);\n";
                break;
          }
          next($this->datos);
    }
    echo "    vd.valida();\n";
    echo "}\n";
    echo "</script>";
/*
    reset($this->datos);
    while (current($this->datos) !== false)
    {
          switch (current($this->datos))
          {
             case 'BI':
                include("broseaing.js");
                break;
             case 'MRP':
                include("broseaing.js");
                break;
          }
          next($this->datos);
    }
*/

  }


// FE=fecha  RF=fecha final; PI=Punto de recaudacion inicial PF=Punto de recaudacion fina ES=estatus del dia de cobro
// OCC=opciones del control de caja
  function quedatos()
  {
    reset($this->datos);
    while (current($this->datos) !== false)
    {
          switch (current($this->datos))
          {
	
             case 'FE':
                $this->pidefechaini();
                break;
             case 'RF':
                $this->pidefechaini();
                $this->pidefechafin();
                break;
             case 'PI':
                $this->pideprini();
                break;
             case 'PF':
                $this->pideprfin();
                break;
             case 'ES':
                $this->pideestado();
                break;
             case 'BI':
                $this->broseaing();
                break;
             case 'MRP':  // mantenimiento a rangos de puntos de recaudacion
                $this->mantorpr();
                break;
             case 'OCC':
                $this->pideocc();
                break;
          }
          next($this->datos);
    }
  }

  function quedatos_t()
  {
    reset($this->datos);
    while (current($this->datos) !== false)
    {
          switch (current($this->datos))
          {
	
             case 'FE':
                $this->pidefechaini_t();
                break;
             case 'RF':
                $this->pidefechaini_t();
                $this->pidefechafin_t();
                break;
             case 'PI':
                $this->pideprini_t();
                break;
             case 'PF':
                $this->pideprfin_t();
                break;
             case 'ES':
                $this->pideestado_t();		
                break;
          }
          next($this->datos);
    }
	echo "</tr>	";
  }

  function inicio_tab()
  {
    echo "<table class='sortable' id='tabdinamica'>\n";
    echo "<caption>".$this->titulos."</caption>\n";
  }

  function inicio_form()
  {
     if ($this->accion!="")
     { echo "  <form method=POST name=formpr action=".$this->accion." target='".$this->destino."' >\n"; }
     else
     { echo "  <form method=POST name=formpr action=".$_SERVER['PHP_SELF'].">\n"; }
  }
 
  function fin_form()
  {
     echo "</form>";
  }

  function inicio_html()
  {
    echo "<html>\n";
    echo "<BODY onLoad=\"inicioforma();pone_focus_forma('formpr')\">\n";
    echo " <LINK REL=StyleSheet HREF=\"pupan.css\" TYPE=\"text/css\" MEDIA=screen>\n";
  }

  function botones()
  {
    if ($this->boton!="")
    {
       echo "<table>\n";
       echo "<tr><th><input type=submit value=\"".$this->boton."\" name=Cajas onclick='valdatos();return false;'></input></th><tr>\n";
       echo "<table>\n";
    }
  }

  function pideocc()
  {
    echo "<table align=center style=\"width:50%\">";
    echo "<th><input checked name=xltiporep type=radio style=\"background-color:#EEEEEE;\"value=estado> Trae Estados</input></th>";
    echo "<th><input name=xltiporep type=radio style=\"background-color:#EEEEEE;\" value=total> Trae Importes</input></th>";
    echo "<th><input checked name=xldetalle style=\"background-color:#EEEEEE;\" type=checkbox value=si> Incluye Detalle</input></th>";
    echo "<table>";
  }

  function fin_tab()
  {
    echo "</table>";
  }

  function fin_html()
  {
    echo "</body>";
    echo "</html>";
  } 

  function __construct()
  {
  }

  function pidefechaini_t()
  {
    echo "   <th>Fecha Inicial AAAA-MM-DD </th>    ";
  }

  function pidefechaini()
  {
    echo "   <th><input onBlur='ponCookie(this.name,this.value);' type=text name=xlfechaini maxlength=10 size=20 value=".$this->xlfechaini."></input> </th>";
  }

  function obtendato($xldato,$z)
  {
         echo "obtenCookie('".$xldato."');\n";
         if ($z==0) { echo "document.forms[0].".$xldato.".focus();"; };
  }


  function pidefechafin_t()
  {
    echo "   <th>Fecha Final AAAA-MM-DD </th>    ";
  }

  function pidefechafin()
  {
    echo "   <th><input onBlur='ponCookie(this.name,this.value);' type=text name=xlfechafin maxlength=10 size=20 value=".$this->xlfechafin."></input> </th>";
  }


  function pideprini_t()
  {
    echo "<th>PR inicial</th>    ";
  }

  function pideprini()
  {
    echo "<th> <select onBlur='ponCookie(this.name,this.value);' name=xlprini>      ";
    $sql ="SELECT atl || ' ' || substr((trim(nombre_atl)),1,14) as nombre_atl,atl FROM atls "
          ."order by 2";
    $this->arma_select($sql);
    echo "<input type=hidden name=xlprininame value=$b></input> ";
    echo "</select></th>  ";
  }


  function pideprfin_t()
  {
    echo "<th>PR Final</th>    ";
  }

  function pideprfin()
  {
    echo "<th> <select onBlur='ponCookie(this.name,this.value);' name=xlprfin>      ";
    $sql ="SELECT atl || ' ' || substr((trim(nombre_atl)),1,14) as nombre_atl,atl FROM atls "
          ."order by 2";
    $this->arma_select($sql);
    echo "<input type=hidden name=xlprfinname value=$b></input> ";
    echo "</select></th>  ";
  }


  function pideestado_t()
  {
     echo "<th>Estado</th>    ";

  }

  function pideestado()
  {
     echo "<th><select onBlur='ponCookie(this.name,this.value);' name=xlestado> ";
     $sql ="SELECT descripcion, estado FROM estados order by estado";
     $this->arma_select($sql);
   }

   function arma_select($sql)
   {
    $sql_result = pg_exec($this->connection,$sql)
                   or die("Couldn't make query. " );
     $num = pg_numrows($sql_result);
     for ($i=0; $i < $num ;$i++)
     {
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[1];
         $b = $Row[0];
         if ($a==$xlestado)
         {
            echo "<option selected value=$a> $b </option>";
         }
         else
         {
             echo "<option value=$a> $b </option>";
         }
     }
     echo "  </select></th>  ";
   }

  function titulos_tab($sql_result)  // funcion que desplega los titulos de la tabla
  {
     echo "<tr>";
     $i = pg_numfields($sql_result);
        for ($j = 0; $j < $i; $j++)
        {
          echo "<th>".pg_fieldname($sql_result, $j)."</th>\n";
        };
     echo "</tr>";

  }
 
  // desplega los campos para capturar en una tabla
  // recibe el recorset
  // recibe el nombre de la tabla a la cual va a insertar
  function campo_cap($sql_result)
  {
     echo "<tr>";
     $i = pg_numfields($sql_result);
        for ($j = 0; $j < $i; $j++)
        {
          if ($j == 0)
##          { echo "<th></th>"; }
          { echo "<th> <input type=hidden name=wl_".pg_fieldname($sql_result, $j)."></input></th>\n"; }
          else
          { echo "<th> <input type=text name=wl_".pg_fieldname($sql_result, $j)."></input></th>\n"; }
        };
     echo "<th> <input type=image src='img/alta.gif' title='alta' value='Alta' name=matriz ".
          "onclick='mantto_tabla(\"".$this->tabla."\",\"i\");return false'></input></th>\n";
     echo "<th> <input type=image src='img/busca.gif' title='Busca' value='Busca' name=busca ".
          "onclick='validausuario(\"consulta\");return false'></input></th>\n";
     echo "</tr>";
  }

  function filas_ing($sql_result,$num) //desplega las filas para la captura de ingresos
  {
     $md = new metadata();
     $md->connection=$this->connection;
     $md->tabla=$this->tabla;
     $md->damemetadata();
     $wlllave="";

     if ($num!=0)
     {
     $Row1 = pg_fetch_array($sql_result, 0);
     for ($i=0; $i < $num ;$i++)
     {
        $Row = pg_fetch_array($sql_result, $i);
        echo "<tr>";
        $wlcol=0;
        foreach ($Row1 as $value)
        {
          if (Key($Row1)<"100")
          { echo ""; }
          else
          switch (Key($Row1))
          {
            case "importe":
##  { return " onfocus='this.className=\"foco\";' onblur='this.className=\"\";'  "; }
               if (trim($Row["estado"])!="Correcto Usuario")
               { echo "<td><input onfocus='this.className=\"foco\";' onBlur='caping(".($i+1).",".$wlcol.",this,this.value,".$Row["atl"].",\"".$this->xlfechaini."\");this.className=\"\";' name=celda".($i+1)."_".$wlcol.
                      " value=".$Row[Key($Row1)]."></input></td>"; }
               else
               { echo "<td>".$Row[Key($Row1)]."</td>"; }
               break;
            default:
               { echo "<td>".$Row[Key($Row1)]."</td>"; }
          }
          if ($md->camposk[Key($Row1)]=='t')
          {  $wlllave=Key($Row1)."=".$Row[Key($Row1)]; }
          $wlcol=$wlcol+1;
          next($Row1);
        }

        if (in_array("d",$this->movto_mantto)) 
        {
             echo "<td><input type=image title='Da de baja registro' src='img/baja.gif' onclick='mantto_tabla(\"".$this->tabla."\",\"d\",\"".$wlllave."\",".($i+2).");return false'></input></td>\n";
        }

        if (in_array("u",$this->movto_mantto)) 
        {
             echo "<td><input type=image title='Cambia registro' src='img/cambio.bmp' onclick='mantto_tabla(\"".$this->tabla."\",\"u\",\"".$wlllave."\",".($i+2).");return false'></input></td>\n";
        }
        echo "</tr>";
      }
      }
  } 

  function mantorpr()
  {
    $sql = "select idranpr,descripcion,rango from cat_ranpr ";
     $sql_result = pg_exec($this->connection,$sql)
                   or die("Couldn't make query. ".$sql );
     $num = pg_numrows($sql_result);
     if ( $num == 0 ) {menerror("No hay rangos "); };
     $this->titulos_tab($sql_result);
     if (in_array("i",$this->movto_mantto)) { $this->campo_cap($sql_result); }
     $this->filas_ing($sql_result, $num);
  } 

  function broseaing()
  {
     $sql ="SELECT atl,nombre_atl as Nombre  ".
           " ,(select total from cobros_enca_cap as cec where   cec.atl=atls.atl and cec.fcobro='".$this->xlfechaini."') as importe".
           " ,(select (select descripcion from estados es where es.estado=ce.estado) from cobros_enca as ce ".
           "  where   ce.atl=atls.atl and ce.fcobro='".$this->xlfechaini."') as estado".
           " ,(select fecha_modifico from cobros_enca_cap as cec where   cec.atl=atls.atl and cec.fcobro='".$this->xlfechaini."') as fecha".
           " ,(select usuario_modifico from cobros_enca_cap as cec where   cec.atl=atls.atl and cec.fcobro='".$this->xlfechaini."') as usuario".
           " FROM atls order by atl";
     $sql_result = pg_exec($this->connection,$sql)
                   or die("Couldn't make query. ".$sql );
     $num = pg_numrows($sql_result);
     if ( $num == 0 ) {menerror("No hay opciones ");die(); };
     $this->titulos_tab($sql_result);
     $this->filas_ing($sql_result, $num);
    }

}
