<?php
require_once("menudata.php");
require_once("class.phpmailer.php");
require_once("soldatos.php");
require_once("class_logmenus.php");
##require_once("mail_smtp_ejemplo.php");
/**
  *  Arma los sql para darle mantenimiento a las tablas
  *   @package   forapi  
  **/
class xmlhttp_class
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
     *  arma el footer del xml
     **/
   function termina()
   {
     echo "</respuesta>";
     echo "</channel>" ;
     echo "</rss>" ;
   }
/**
  *  Arma el header del xml
  **/
   function inicio()
   {
      header("Content-type: text/xml");
      header("Pragma: public");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
      echo "<rss version=\"2.0\">\n";
      echo "<channel>";
      echo "<respuesta>";
   }

   /**
    *   Captura de ingresos  tiende a desaparacer
    **/
   function caping()
   {
    $sql=" select ins_cec(".func_get_arg(1).",'".func_get_arg(2)."',".func_get_arg(3).")";
    $sql_result = pg_exec($this->connection,$sql)
                   or die("Couldn't make query caping ".$sql );
    $Row = pg_fetch_array($sql_result, 0); 
    echo $Row[0];
   }

   /**
    *   obtiene el width y el height de las subvista
    **/
   function subvista($wlhoja,$otrosdatos,$idmenu,$ventana,$titulo)
   {
     $ventana=($ventana=="" ? "0" : $ventana);
     $titulo=($titulo=="" ? "Subvista" : $titulo);
     $sql=" select dialogwidth,dialogheight,ventana from menus_subvistas where idmenus_subvistas=".$idmenu;
     $sql_result = pg_exec($this->connection,$sql)
                   or die("Couldn't make query subvista ".$sql );
     $num = pg_numrows($sql_result);
     if ( $num <> 0 ) 
     {
     $Row = pg_fetch_array($sql_result, 0); 
     echo "<abresubvista>true</abresubvista>";
     echo "<wlhoja>".$wlhoja."</wlhoja>";
     echo "<wlcampos>".$otrosdatos."</wlcampos>";
     echo "<wldialogWidth>".$Row["dialogwidth"]."</wldialogWidth>";
     echo "<wldialogHeight>".$Row["dialogheight"]."</wldialogHeight>";
     echo "<ventana>".$Row["ventana"]."</ventana>";
     echo "<titulo>".$titulo."</titulo>";
     } else
     {
     echo "<abresubvista>true</abresubvista>";
     echo "<wlhoja>".$wlhoja."</wlhoja>";
     echo "<wlcampos>".$otrosdatos."</wlcampos>";
     echo "<wldialogWidth>100</wldialogWidth>";
     echo "<wldialogHeight>80</wldialogHeight>";
     echo "<ventana>".$ventana."</ventana>";
     echo "<titulo>".$titulo."</titulo>";
     }
   }
   
   function altaautomatico()
   {
      if($this->argumentos['fuente']=='')
      {  echo '<error>No esta definido fuente o tabla'.$this->argumentos['fuente'].'</error>'; return; }	         
      if($this->argumentos['fuente_campodes']=='')
      {  echo '<error>No esta definido fuente_campodes descripcion del campo'.$this->argumentos['fuente_campodes'].'</error>'; return; }	                     
      if($this->argumentos['dato']=='')
      {  echo '<error>No esta definido dato id del campo en el html'.$this->argumentos['dato'].'</error>'; return; }	                           
      if($this->argumentos['des']=='')
      {  echo '<error>No esta definido des Descripcion a dar de alta'.$this->argumentos['des'].'</error>'; return; }	                                 
      if($this->argumentos['fuente_campofil']=='')      
      {  $sql = " select * from ".($this->argumentos["fuente_nspname"]!="" ? $this->argumentos["fuente_nspname"]."." : "").$this->argumentos['fuente']." where ".$this->argumentos['fuente_campodes']." = '".$this->argumentos['des']."'"; }      
      else
      {  $sql = " select * from ".($this->argumentos["fuente_nspname"]!="" ? $this->argumentos["fuente_nspname"]."." : "").
                $this->argumentos['fuente']." where ".$this->argumentos['fuente_campodes']." = '".$this->argumentos['des'].
                "' and ".$this->argumentos['fuente_campofil']." = ".$this->argumentos['valorfuente_campofil']; }
      $sql_result = @pg_exec($this->connection,$sql);        	          	
                                $this->hayerrorsql($this->connection,'buscasecuencia ',$sql);      
      $num = pg_numrows($sql_result);																	//20070215
##    20070731  aunque mandaba mensaje de que ya estaba dado de alta de todos modos lo daba de alta      
##      if ( $num >= 1 ) { echo "<error>La opcion ya esta dada de alta</error>"; }								//20070215      
      if ( $num >= 1 ) { echo "<error>La opcion ya esta dada de alta</error>"; return; }//20070215   // 20070731
      $me = new menudata();
      $me->connection=$this->connection;
      $wlsecuencia=$me->damesecuencia($this->argumentos['fuente'],$this->argumentos["fuente_nspname"]);
      if($this->argumentos['fuente_campofil']=='')            
	  { $sql = "insert into ".($this->argumentos["fuente_nspname"]!="" ? $this->argumentos["fuente_nspname"]."." : "").$this->argumentos['fuente']." (".$this->argumentos['fuente_campodes'].
			           	") values ('".
        	          	$this->argumentos['des']."')";
	  }         	          	
	  else
	  { $sql = "insert into ".($this->argumentos["fuente_nspname"]!="" ? $this->argumentos["fuente_nspname"]."." : "").
	                   $this->argumentos['fuente']." (".$this->argumentos['fuente_campodes'].",".$this->argumentos['fuente_campofil'].
			           	") values ('".
        	          	$this->argumentos['des']."',".$this->argumentos['valorfuente_campofil'].")";
	  }         	          	
      $sql_result = @pg_exec($this->connection,$sql);        	          	
                                $this->hayerrorsql($this->connection,'Altaautomatico ',$sql);
##      $Reg = pg_cmdtuples($sql_result);         			      
##      $sql = " select ".$wlsecuencia." as secuencia";
      $sql = " select lastval() as secuencia";
      $sql_result = @pg_exec($this->connection,$sql);        	          	
                                $this->hayerrorsql($this->connection,'buscasecuencia ',$sql);      
      $num = pg_numrows($sql_result);																	//20070215
      if ( $num == 0 ) { echo "<error>error secuencia</error>"; }								//20070215
      $Row=pg_fetch_array($sql_result, 0);
      echo '<altaautomatico></altaautomatico>';            
      echo '<dato>'.$this->argumentos['dato'].'</dato>';                  
      echo '<des>'.$this->argumentos['des'].'</des>';                        
      echo '<secuencia>'.($Row["secuencia"]).'</secuencia>';
   }
   // 20080116   busca la descripcion de un registro se dio de alta esto para los campos select
   function buscaaltaautomatico()
   {
      if($this->argumentos['fuente']=='')
      {  echo '<error>No esta definido fuente o tabla'.$this->argumentos['fuente'].'</error>'; return; }	         
      if($this->argumentos['fuente_campodes']=='')
      {  echo '<error>No esta definido fuente_campodes descripcion del campo'.$this->argumentos['fuente_campodes'].'</error>'; return; }	                     
      if($this->argumentos['dato']=='')
      {  echo '<error>No esta definido dato id del campo en el html'.$this->argumentos['dato'].'</error>'; return; }	                                 
      if($this->argumentos['fuente_campodep']=='')
      {  echo '<error>No esta definido fuente_campodep descripcion del campo'.$this->argumentos['fuente_campodep'].'</error>'; return; }	                           
      if($this->argumentos['iden']=='')
      {  echo '<error>No esta definido el valor de identificacion de alta'.$this->argumentos['iden'].'</error>'; return; }	                                 
      $sql = " select * from ".($this->argumentos["fuente_nspname"]!="" ? $this->argumentos["fuente_nspname"]."." : "").$this->argumentos['fuente']." where ".$this->argumentos['fuente_campodep']." = '".$this->argumentos['iden']."'";
      $sql_result = @pg_exec($this->connection,$sql);        	          	
                                $this->hayerrorsql($this->connection,'buscasecuencia ',$sql);      
      $num = pg_numrows($sql_result);																	//20070215
##    20070731  aunque mandaba mensaje de que ya estaba dado de alta de todos modos lo daba de alta      
##      if ( $num >= 1 ) { echo "<error>La opcion ya esta dada de alta</error>"; }								//20070215      
##      if ( $num >= 1 ) { echo "<error>La opcion ya esta dada de alta</error>"; return; }//20070215   // 20070731
##      $me = new menudata();
##      $me->connection=$this->connection;
##      $wlsecuencia=$me->damesecuencia($this->argumentos['fuente']);
##	  $sql = "insert into ".($this->argumentos["fuente_nspname"]!="" ? $this->argumentos["fuente_nspname"]."." : "").$this->argumentos['fuente']." (".$this->argumentos['fuente_campodes'].
##			           	") values ('".
##        	          	$this->argumentos['des']."')";
##      $sql_result = @pg_exec($this->connection,$sql);        	          	
##                                $this->hayerrorsql($this->connection,'Altaautomatico ',$sql);
##      $Reg = pg_cmdtuples($sql_result);         			      
##      $sql = " select ".$wlsecuencia." as secuencia";
##      $sql_result = @pg_exec($this->connection,$sql);        	          	
##                                $this->hayerrorsql($this->connection,'buscasecuencia ',$sql);      
##      $num = pg_numrows($sql_result);																	//20070215
##      if ( $num == 0 ) { echo "<error>error secuencia</error>"; }								//20070215
      $Row=pg_fetch_array($sql_result, 0);
      echo '<altaautomatico></altaautomatico>';            
      echo '<dato>'.$this->argumentos['dato'].'</dato>';                  
      echo '<des>'.$Row[$this->argumentos['fuente_campodes']].'</des>';                        
      echo '<secuencia>'.$this->argumentos['iden'].'</secuencia>';
   }   
   

   
   /**
     *  pone las opciones de un campo select
     **/
   function pon_select()
   {
	$wlfiltrohijo=$this->argumentos['wlfiltrohijo'];
    $sql=$this->argumentos['sql'];
	$t=getdate();
	$today=date('Y-m-d h:i:s',$t[0]);
//    error_log($today." sql ".$sql." filtro ".$this->argumentos['wlfiltrohijo']."\n",3,"/var/tmp/errores.log");    
//    error_log($today." sql ".$sql." filtro ".$this->argumentos['wlfiltrohijo']."\n",1,"jvazquez@finanzas.df.gob.mx");
    $sql_result = @pg_exec($this->connection,$sql." order by 1");
    $this->hayerrorsql($this->connection,'pon_select',$sql); // para atrapar errore en triggers    
    $num = pg_numrows($sql_result);
    if ($num>300)
    {
    	$sql=$this->argumentos['sql'];
//    	$sql_result = pg_exec($this->connection,$sql." order by 1 limit 300")
    	$sql_result = pg_exec($this->connection,$sql." order by 1 ")  // 20070818  checar el limite en el select
                   or die("<error>Couldn't make query pon_select. ".$sql."</error> ");
    	$num1 = pg_numrows($sql_result);	    
	   echo "<wlfiltropadre>".$this->argumentos['wlfiltropadre']."</wlfiltropadre>"; 
	   echo "<wlfiltrohijo>".$this->argumentos['wlfiltrohijo']."</wlfiltrohijo>"; 	   
	   echo "<fuenteevento>".$this->argumentos['fuenteevento']."</fuenteevento>"; 	   	   
	   echo "<fuentewhere>".$this->argumentos['fuentewhere']."</fuentewhere>"; 	   	   	   
	   echo "<wlselect>".(strpos($this->argumentos['sql'],"where") ? substr($this->argumentos['sql'],0,strpos($this->argumentos['sql'],"where")-1) : $this->argumentos['sql']).
	                     "</wlselect>";
	   echo "<opciones>".$num."</opciones>";
	   echo "<mostradas>".$num1."</mostradas>";	   
	   echo "<pon_selectpasolimite></pon_selectpasolimite>" ;   
	   $num=$num1;	    	
    }
    $i = pg_numfields($sql_result);       
        for ($j = 0; $j < $i; $j++)
        {
	        if ($j==0)   // el campo cero contiene la descripcion de la opcion
	        {
		      echo "<s_descripcion>".pg_fieldname($sql_result, $j)."</s_descripcion>\n";
	        }
	        
	        if ($j==1)   // el campo 1 contiene el valor de la opcion
	        {
		      echo "<s_value>".pg_fieldname($sql_result, $j)."</s_value>\n";
	        }
        };    
    if($wlfiltrohijo=='')    //
    {  echo "<error>el filtro hijo no esta definido</error>"; }
    echo "<wlfiltrohijo>".$wlfiltrohijo."</wlfiltrohijo>";
    
    if($this->argumentos['fuenteevento']=='')    //
    {  echo "<error>No esta definido el fuente evento</error>"; return ;}
    
    echo "<fuenteevento>".$this->argumentos['fuenteevento']."</fuenteevento>";    
    
    
    echo "<ponselect>";
	for ($z=0; $z < $num ;$z++)
   	{
	   	echo "<registro>";
        $Row = pg_fetch_array($sql_result, $z);
        for ($j = 0; $j < $i; $j++)
        {
          echo "<".pg_fieldname($sql_result, $j).">".htmlspecialchars(trim($Row[pg_fieldname($sql_result, $j)]))."</".pg_fieldname($sql_result, $j).">\n";  //20070615
        };
	   	echo "</registro>";        
   	};


    echo "</ponselect>";    

   }   
   
   
   
   /**
     *  Arma el insert,delete o update para darle mantenimiento a una tabla
     **/
   function mantto_tabla()
   {
      if($this->argumentos['idmenu']=='')
      {  echo '<error>menu o vista no definida'.$this->argumentos['idmenu'].'</error>'; return; }
      if($this->argumentos['movto']!='I' && $this->argumentos['movto']!='i'
      && $this->argumentos['movto']!='d' && $this->argumentos['movto']!='u'
      && $this->argumentos['movto']!='cc' && $this->argumentos['movto']!='s'
      && $this->argumentos['movto']!='S' && $this->argumentos['movto']!='B'
      && $this->argumentos['movto']!='f'       // 20070223 genera txt-grecar
      && $this->argumentos['movto']!='ex'       // 20110126 genera excel-grecar
      && $this->argumentos['movto']!='m' )      // 20110126 muestra manual-grecar
      {  echo '<error>movimiento no definido'.$this->argumentos['movto'].'</error>'; return; }


      $me = new menudata();
      $me->connection=$this->connection;
      $me->idmenu=$this->argumentos['idmenu'];
      $me->filtro=$this->argumentos['filtro'];
      $me->damemetadata();      
	  $soldatos = new soldatos();
	  $soldatos->idmenu=$idmenu;
##	  echo '<error>argumentos';print_r($this->argumentos);'</error>';
      
//20070223      se modifico para incluir el movimiento de copia      
//20070223      if($this->argumentos['movto']!='i' && $this->argumentos['movto']!='d' && $this->argumentos['movto']!='u' )
      
      // Consulta
      if($this->argumentos['movto']=='s' || $this->argumentos['movto']=='S' || $this->argumentos['movto']=='B')
      { 
	      // Escribe en archivo txt el sql por ejecutar
	      $handle = fopen ("ficheros/sql.sql", "w");
	      if (!fwrite ($handle,$me->camposm["fuente"])) { echo "no pudo escribir archivo1"; }
	      fclose($handle);

//	      echo "<error>entro en consulta".$this->argumentos['filtro']."</error>";
//				return;
					$sql=$me->filtro;
	        		$sql_result = @pg_exec($this->connection,$me->camposm["fuente"]);
	        		
        			if (strlen(pg_last_error($this->connection))>0)
        			{
        				echo "<error>En select  ".pg_last_error($this->connection)." sql=".$me->camposm["fuente"]."</error>";
        				return;
        			}
					$Row = pg_numrows($sql_result);

					if ($Row>=1)
					{	      
						echo '<consulta>Consulta efectuada '.$Row.' registros</consulta>';
                                                if (strpos($me->camposm['noconfirmamovtos'],'s')===false && strpos($me->camposm['noconfirmamovtos'],'S')===false && strpos($me->camposm['noconfirmamovtos'],'B')===false)
                                                {   } else { echo '<noconfirma>true</noconfirma>'; }
						$wlrenglones=$soldatos->inicio_tab($sql_result).$soldatos->filas_ing($sql_result,$Row,$me);
						echo '<renglones>'.htmlspecialchars($wlrenglones).'</renglones>';												
						echo '<wleventodespues>'.$this->argumentos['wleventodespues'].'</wleventodespues>';
						echo '<iden>'.$Reg.'</iden>';  // en caso de una secuencia esta es el no de registro de identificacion
					}
					else { echo "<error>No encontro registros </error>"; }
//			return;		
      }
      
		// Consulta en archivo txt
		if($this->argumentos['movto']=='f')
		{
	        		$sql_result = @pg_exec($this->connection,$me->camposmf["fuente"]);	      
        			if (strlen(pg_last_error($this->connection))>0)
        			{
        				echo "<error>En select  ".pg_last_error($this->connection)." sql=".$me->camposmf["fuente"]."</error>";
        				return;
        			}
        			$Row = pg_numrows($sql_result);
					if ($Row>=1)
					{
						$this->generaTexto($sql_result,$Row,$_SESSION['parametro1'],$me);
						echo '<generatexto>Consulta efectuada '.$Row.' registros</generatexto>';
       					echo '<archivo>ficheros/consulta'.$this->argumentos['idmenu'].'_'.$_SESSION['parametro1'].'.txt.gz</archivo>';
					}
					else { echo "<error>No se encontraron registros</error>"; }
		}

		// Consulta en archivo xls
		if($this->argumentos['movto']=='ex')
		{
	        		$sql_result = @pg_exec($this->connection,$me->camposmf["fuente"]);	      
        			if (strlen(pg_last_error($this->connection))>0)
        			{
        				echo "<error>En select  ".pg_last_error($this->connection)." sql=".$me->camposmf["fuente"]."</error>";
        				return;
        			}
        			$Row = pg_numrows($sql_result);
					if ($Row>=1)
					{
						$this->generaExcel($sql_result,$Row,$_SESSION['parametro1'],$me);
						echo '<generaexcel>Consulta efectuada '.$Row.' registros</generaexcel>';
       					echo '<archivo>ficheros/consulta_'.$_SESSION['parametro1'].'.xls</archivo>';
					}
					else { echo "<error>No se encontraron registros</error>"; }
		}
		// Muestra el manual del menu
		if($this->argumentos['movto']=='m')
		{
				echo '<abremanual>'.$me->camposm["manual"].'</abremanual>';
		}

      // alta de un registro
//20070223      if($this->argumentos['movto']=='i' )       
      if($this->argumentos['movto']=='I' || $this->argumentos['movto']=='i' || $this->argumentos['movto']=='cc')  //2007023
      { 
		if ($this->checaduplicados($this->argumentos, $me->camposm, $me->camposmc)==false)	
		   {
			    if ($me->camposm["tiene_pk_serial"]!="")  // si la tabla tiene una pk y es serial investiga que secuencia le asigno
			    {
//20080114    Se modifico para manejar esquemas				    
//20080114	    	$sql = "insert into ".$me->camposm['tabla']."(".$this->array_to_stringk($this->argumentos,$me->camposmc).
			    	$sql = "insert into ".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "").  //20080114
			    			$me->camposm['tabla']."(".$this->array_to_stringk($this->argumentos,$me->camposmc).  //20080114	    	
			        	   ") values (".
//20080114 	          		$this->array_to_stringv($this->argumentos,$me->camposmc)."); select currval('".$me->camposm['tabla']."_".$me->camposm["tiene_pk_serial"]."_seq');";				    
        	          		$this->array_to_stringv($this->argumentos,$me->camposmc).//20080114
        	          		"); select currval('".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "").$me->camposm['tabla']."_".$me->camposm["tiene_pk_serial"]."_seq');";//20080114
//        	          		echo "<error>".$sql."</error>";
//        	          		return;
        			        $sql_result = @pg_exec($this->connection,$sql);
        			        $this->hayerrorsql($this->connection,'Insert-serial',$sql); // para atrapar errore en triggers
        	          		$Row = pg_fetch_array($sql_result, 0); 
        	          		$Reg=$Row[0];
	        				$wlllave=$me->camposm["tiene_pk_serial"]."=".$Reg;
			    }
			    else
			    {
			    	$sql = "insert into ".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "")."".$me->camposm['tabla']."(".$this->array_to_stringk($this->argumentos,$me->camposmc).
			           	") values (".
        	          	$this->array_to_stringv($this->argumentos,$me->camposmc).")";
        			$sql_result = @pg_exec($this->connection,$sql);        	          	
                                $this->hayerrorsql($this->connection,'Alta x',$sql);
        			$Reg = pg_cmdtuples($sql_result);         			
	        		$wlllave=$this->damellave($this->argumentos,$me->camposmc);        			
//        	          		echo "<error>".$sql."</error>";
//        	          		return;	        		
	        		if ($wlllave=="")
	        		{
        				echo "<error>En alta, la vista no tiene definido una llave</error>";	        				        		
        				return;
	        		}
	        		
			        	          }

        		if($Reg!=0)
        		{  
//  20070521  cuando meti que incluyera esquema esto trono porque a la tabla se le antepone el esquema que puede ser
//  20070521  public o el esquema en referencia	        		
        		$sqls=substr($me->camposm['fuente'],0,$this->stringrpos($me->camposm['fuente']," from ".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "").$me->camposm['tabla'])+strlen(" from ".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "").$me->camposm['tabla'])).
//	        		$sql=substr($me->camposm['fuente'],0,$this->stringrpos($me->camposm['fuente'],$me->camposm['tabla']." where")+strlen($me->camposm['tabla'])).
	        			" where ".$wlllave;
	        		$sql_result = @pg_exec($this->connection,$sqls);
        			if (strlen(pg_last_error($this->connection))>0)
        			{
        				echo "<error>En alta ".pg_last_error($this->connection)." sql=".$sqls."</error>";	        		
        				return;
        			}
					$num = pg_numrows($sql_result);                    				
					if ($num>=1)
					{
						$Row = pg_fetch_array($sql_result, 0); 
						//   ejecuta armarenglon para traer el renglon armado de la tabla y poder mostrarlo en la tabla                   					 						
						$wlrenglon=$soldatos->armarenglon($sql_result,$Row,($this->argumentos['wlrenglon']+1),$me);
						if ($this->argumentos['movto']=='i')
						{ echo '<altaok>Alta efectuada</altaok>';}
						if ($this->argumentos['movto']=='cc')
						{ echo '<copiaok>Copia efectuada</copiaok>';}
						if ($this->argumentos['movto']=='I')
						{ echo '<altaokautomatica>Alta efectuada</altaokautomatica>';}						
                                                if (strpos($me->camposm['noconfirmamovtos'],'i')===false && strpos($me->camposm['noconfirmamovtos'],'I')===false && strpos($me->camposm['noconfirmamovtos'],'cc')===false)
                                                {   } else { echo '<noconfirma>true</noconfirma>'; }
						echo '<renglon>'.htmlspecialchars($wlrenglon).'</renglon>';
						echo '<wlrenglon>'.($this->argumentos['wlrenglon']+1).'</wlrenglon>';
						if ($this->argumentos['wleventodespues']!='')
			       		{	echo '<wleventodespues>'.$this->argumentos['wleventodespues'].'</wleventodespues>'; }	// 20070918	
			       		if ($this->argumentos['wleventodespuescl']!='')
			       		{	echo '<wleventodespuescl>'.$this->argumentos['wleventodespuescl'].'</wleventodespuescl>'; }	//20101223 - grecar
						echo '<iden>'.$Reg.'</iden>';  // en caso de una secuencia esta es el no de registro de identificacion
						echo '<limpiaralta>'.htmlspecialchars($me->camposm["limpiaralta"]).'</limpiaralta>';
					}
					else
					{
	        			echo '<error>No encontro registro en alta para mostralo en tabla</error>'; 						
					}
	        	}
	        	else
	        	{
	        			echo '<error>No encontro registro en alta</error>'; 								        	
	        	}
    		}
      }
 
      //   baja de un registros
      if($this->argumentos['movto']=='d') 
      { 
//	      echo '<error>entro en d</error>';
        if ($this->argumentos['wlllave']=="")
        {  echo '<error>no esta definida la llave para borrar</error>'; } 
        else
        {
           $sql = "delete from  ".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "").$me->camposm['tabla'].
               " where ".$this->argumentos['wlllave'];
           $sql_result = pg_exec($this->connection,$sql);
           $errorc=pg_last_error($this->connection);
//  20070419  se modifico para que desplegara el sql, ya que solo enviaba error y no sabiamos porque           
//  20070502  la instruccion lo regrese a como estaba originalente ya que es muy peligroso que despliegue los sql
           if ($errorc!="") { echo '<error>Delete'.$errorc.'</error>'; return; }
//                    or die('<error>'.$sql.'</error>');
//					  or echo '<error>'.$sql.'</error>'; return;
           $Row = pg_cmdtuples($sql_result); 
           if($Row!=0)
              {  echo '<bajaok>Baja efectuada</bajaok>'; }
           else
              { echo '<error>No hizo la baja</error>'; }
        } 
      }

      //   cambio de un registros
      if($this->argumentos['movto']=='u') 
      { 
        if ($this->argumentos['wlllave']=="")
        {  echo '<error>no esta definida la llave para actualizar el dato</error>'; } 
        else
        {
			if ($this->checaduplicados($this->argumentos, $me->camposm, $me->camposmc)!=true)	
		   	{
			   	$wldatos=$this->damedatos($this->argumentos, $me->camposm, $me->camposmc);		
           		$sql = "update ".($me->camposm["nspname"]!="" ? $me->camposm["nspname"]."." : "").$me->camposm['tabla']. //20080114
               		" set ".$this->arma_upd($this->argumentos,$me->camposmc,$wldatos).
               		" where ".$this->argumentos['wlllave'];

           		$sql_result = @pg_exec($this->connection,$sql);
//                	    or die("Couldn't make query. ".$sql );
//                        $wlerror=pg_last_error($this->connection) ;  // esto lo inclui para controlar errores por trigger
			$this->hayerrorsql($this->connection,'Cambio x',$sql);
//			if ($wlerror!="") 
//			{ echo '<error>'.$wlerror.'</error>'; }
//			else
//                        {
           		   $Row = pg_cmdtuples($sql_result); 
           		   if($Row!=0)
              	           {  
	               echo '<cambiook>Cambio efectuado</cambiook>'; 
			       echo "<wlmenu>".$this->argumentos['wlmenu']."</wlmenu>";
			       echo "<wlmovto>".htmlspecialchars($this->argumentos['wlmovto'])."</wlmovto>";		
			       echo "<wlllave>".htmlspecialchars($this->argumentos['wlllave'])."</wlllave>";
			       echo "<wlrenglon>".htmlspecialchars($this->argumentos['wlrenglon'])."</wlrenglon>";
			       if ($this->argumentos['wleventodespues']!='')
			       {	echo '<wleventodespues>'.$this->argumentos['wleventodespues'].'</wleventodespues>'; }	// 20070918	
			       if ($this->argumentos['wleventodespuescl']!='')
			       {	echo '<wleventodespuescl>'.$this->argumentos['wleventodespuescl'].'</wleventodespuescl>'; }	//20101223 - grecar
              	           }
           		   else
              	           { echo '<error>No hizo el cambio</error>'; }
//                         }

         	}
        } 
      }
      $reg= new logmenus($this->connection); //20070623
//      $reg->registra($menu->idmenu ,trim($menu->camposm["tabla"]." ".$this->filtro." regs=".$num));//20070623
      $reg->registra($this->argumentos['idmenu'] ,trim($me->camposm['tabla']." ".$sql." regs=".$Row));//20070623            
      $reg=null;        //20070623      
   }
   
   
   /**
     * busca la posicion de un caracter empezando desde el ultimo caracter
     * @param string $sHaystack campo donde se va a buscar
     * @param int $sNeedle valor que se va a busca 
     * @return mixed  false si no lo encontro caso contrario el numero de posicion
     **/
   function stringrpos( $sHaystack, $sNeedle )
	{
  		$i = strlen( $sHaystack );
  		while ( substr( $sHaystack, $i, strlen( $sNeedle ) ) != $sNeedle )
  		{
   			$i--;
   			if ( $i < 0 )
   			{
     			return false;
   			}
  		}
  		return $i;
	}

	/**
      * checa si en el ultimo sql ejecutado de la coneccion hubo error
      * @param coneccion $connection connecion
      * @param string $mensaje mensaje a enviar
      **/
   	function hayerrorsql($connection,$mensaje,$sql)
	{
        	if (strlen(pg_last_error($this->connection))>0)
        	{
        		echo "<error>01 ".$mensaje." ".pg_last_error($this->connection)."</error>";
        		$wlmensaje=$mensaje." ".
        					pg_last_error($this->connection)."\nArgumentos:".
        					print_r($this->argumentos)."\n".
        		           "sql=".$sql.
        		           "usuario=".$_SESSION['parametro1'];
        		error_log($wlmensaje,1,"jlvdantry@hotmail.com","Subject: Error forapi\nFrom: jlvdantry@hotnail.com\n");
				$t=getdate();
			    $today=date('Y-m-d h:i:s',$t[0]);
        		error_log($today." ".$parametro1." ".$wlmensaje."\n",3,"/var/tmp/errores.log");
                        $this->Enviaemail($wlmensaje);
//        		error_log($today." ".$parametro1." ".$mensaje." ".pg_last_error($this->connection)." ".$sql."\n",3,"errores.log");        		
        		return;
        	}		
	}   

	/**
     *  arma la llave de acuerdo al indice y al dato tecleado   
     *  @param arreglo $array arreglo de los datos tecleados
     *  @param arreglo $camposmc arreglo de la tabla menus campos
     *  @return string llave armada
     **/
   	function damellave($array,$camposmc)
	{
	  $wlllave="";
////   checar porque no puedes tomar en cuenta un campos que es una secuencia
      foreach ($camposmc as $index => $val)
      {
              $vas.="index=".$index." esindex=".$val["esindex"]." attnum=".$val["attnum"]." valor=".$array["wl_".$index]."\n";
	      if (($val["esindex"]==t || $val["indice"]==t) &&  $val["attnum"]!=0)
	      {
                      $vas.=" entro ";
		      if ($array["wl_".$index]!="")
		      {
		     		($wlllave=="") ? $wlllave="" : $wlllave=$wlllave." and ";
##	   				echo "<error>entro en damellave index=".$index." typname=".$val["typname"]."</error>";
##	   				die();		     		
             		// si el tipo de campo es un texto le pone comillas al campo
			 		($this->lleva_comilla($val["typname"])==false ? $wlllave.=$index."=".$array["wl_".$index]
			                                       : $wlllave.=$index."='".$array["wl_".$index]."'");
		   	  }
	      }
	  }
      ##echo "<error>".$vas."</error>";
      ##die();
	  
      return $wlllave;
	}   
   /**
     * Funcion que indica si el dato lleva comillas o no
     *  @param string $tipo tipo de dato
     *  @return bool true si lleva comilla caso contrario false
     **/
   function lleva_comilla($tipo)
   { 
	   if (strpos($tipo,"char")===false 
	   && strpos($tipo,"bool")===false 
	   && strpos($tipo,"date")===false 
	   && strpos($tipo,"name")===false
	   && strpos($tipo,"timesta")===false	   
	   && strpos($tipo,"text")===false	   
	   && strpos($tipo,"varc")===false	   	   
	   ) { return false; } else { return true; }
    }

   /**
     *   funcion que regresa un arreglo del registro con la llave que se va actualizar
     *   @param arreglo $array datos tecleados en la pantalla
     *   @param arreglo $camposm  arrelgo con los campos de la tabla menus
     *   @param arreglo $camposmc arreglo con los cmpoas de la tabla menus_campos
     *   @return mixed row si encontro informacion caso contrarion error
     **/
   function damedatos($array,$camposm,$camposmc)
   {
	  $wlllave=$this->damellave($array,$camposmc);	

      if ($wlllave!="")
      {

	  	$sql = "select * from ".($camposm["nspname"]!="" ? $camposm["nspname"]."." : "").$camposm["tabla"]." where ".$wlllave;
		$sql_result = @pg_exec($this->connection,$sql)
                    or die("Error xhmlhttp_class.damedatos. ".$sql );
		$num = pg_numrows($sql_result);                    				                                        
		if ($num>=1)
		{ 
			$Row = pg_fetch_array($sql_result, 0); 		
				
		    return $Row;
		}      
  	  }
  	  else
  	  {
      return "error no hay llave".$wlllave;	  
      }
   }
		
   /**
     *  funcion que checa que no este duplicado un registro en base a su llave
     *  @param arreglo $array arreglo de los campos capturado o enviados por la forma
     *  @param arreglo $camposm recibe arreglo de las caracteristica de la tabla menus
     *  @param arreglo $camposmc recibe arreglo de las caracterisitca de todos los campos de la tabla menus_campos
     *  entra en esta rutina tambien los cambio ya que tambien pueden cambiar una llave 
     *  @return bool true si esta duplicado caso contrario false
     **/
   function checaduplicados($array,$camposm,$camposmc)
   {
##	   print_r($array);
##	   die();
	  $wlllave=$this->damellave($array,$camposmc);
	  $wlvalores="";

      if ($wlllave!="")
      {
	  	$sql = "select count(*) from ".($camposm["nspname"]!="" ? $camposm["nspname"]."." : "").$camposm["tabla"]." where ".$wlllave.
	  	// pregunta si tiene llave si es asi es un cambio y checa duplicados sin considerar el registros
	  	// que se esta cambiando
	  	// este punto no se como resolverlo cuando hay mas de un campos que es llave por lo mientras los dejo
	 	(($array['wlllave']!='' && $array['wlllave']!='undefined') ? " and not (".$array['wlllave'].")" : "");
		$sql_result = pg_exec($this->connection,$sql)
                    or die("Error checaduplicados. ".$sql );
		$Row = pg_fetch_array($sql_result, 0);                    					 
		if ($Row[0]>=1)
		{ 
			echo "<error>El registro ya existe en la base de datos</error>";
		    return true;
		 }      
  	  }
      return false;
}

   /**
   *  arma el update de los campos que va actualizar en el  update
   *  @param arreglo $array recibe arreglo de la hoja recibida,
   *  @param arreglo $camposs recibe arreglo que contiene si el campos de la tabla memnus_campos, para saber si un campo es una secuencia
   *  @param arreglo $wldatos arreglo del registro que va a actualizar 
   *  @return string campos que se deben de actualizar
                                                **/
   function arma_upd($array,$camposs,$wldatos) 
   {
##		echo "<error>";	   
      foreach ($array as $index => $val)
      {
        $wlcampo=substr($index,3);
        $pos=strpos($index,"wl_");
        $wltmp="";
##  	 	echo "wlcamposs= ".$index." attnum=".$camposs[$wlcampo]["attnum"]." val=".$val." wldatos=".$wldatos[$wlcampo]."\n";            	         
		if ($camposs[$wlcampo]["attnum"]!=0)        	
		{                
##200071219  Hay campos que tenian espacios del lado izquierdo o derecho y detectaba cambio          			
##	     	if ($pos===0 && strpos($camposs[$wlcampo]["default"],"nextval")===false && $val!=$wldatos[$wlcampo])
	     	if ($pos===0 && strpos($camposs[$wlcampo]["default"],"nextval")===false && trim($val)!==trim($wldatos[$wlcampo]))
    	    	{   
##  	 	echo "wlcamposs= ".$index." attnum=".$camposs[$wlcampo]["attnum"]." val=".$val." wldatos=".$wldatos[$wlcampo]."\n";            	         	    	    	
##	    	    	echo "entro en pos=".$val;
            		 $this->lleva_comilla($camposs[$wlcampo]["typname"])===true ? 
##20070602    Cuando el valor contenia una comilla este tronaba haci que hice la siguiente modificacion            		 
##20070602           	 	$wltmp=substr($index,3)."='".$val."'" : 
            	 	$wltmp=substr($index,3)."=".$this->esfechaNula($camposs[$wlcampo]["typname"],$val) :             	 	
##20061108            	 	(strlen($val)==0 ? $wltmp="" : $wltmp=substr($index,3)."=".$val) ;  // si el campos es numerico y viene en espacio no lo actualiza
##   cambie esta linea para que si un numerico su longitud es cero le ponda nulo
##20070713   comentarice para que cuando la longitud venga en ceros no actualice nada hay que ver como se comportar 
##20070713    esta modificacion
##20070713     	 	(strlen($val)==0 ? $wltmp=substr($index,3)."= null" : $wltmp=substr($index,3)."=".$val) ;  // si el campos es numerico y viene en espacio no lo actualiza
            	 	(strlen($val)==0 ? $wltmp="" : $wltmp=substr($index,3)."=".$val) ;  ##20070713
##            	 	echo "wltmp=".$wltmp." val2=".$val2." val=".$val." typname=".$camposs[$wlcampo]["typname"];            	 
            	 	strlen($val2)==0  ? $val2.=$wltmp : ($wltmp!="" ? $val2.=",".$wltmp : $val2.="");
##            	 	echo "wltmp=".$wltmp." val2=".$val2." val=".$val." typname=".$camposs[$wlcampo]["typname"];
        		}
		}
      }
##    echo $val2;
##	echo "</error>";      
      return $val2;
   }
   /**
    *  checa si el valor a actualiza es una fecha si esta viene con longitud cero le pone null
    */
   function esfechaNula($tipo,$valor)
   {
	   return  (strpos($tipo,"date")!==false & strlen($valor)==0 ? "null" : "'".str_replace("'","\'",$valor)."'");
   }
   
   /**
   	*  funcion que regresa los valores de un arreglo
   	*  @param arreglo $array arreglo de la pagina recibida,
   	*  @param arreglo $camposs arreglo que contiene la descripciones del campo, para sabe si es una secuencia o es un campo de trabajo
   	*  @return string valores del arreglo
   	**/
   function array_to_stringv($array,$camposs) 
   {
      foreach ($array as $index => $val)
      {
        $wlcampo=substr($index,3);
		if ($camposs[$wlcampo]["attnum"]!=0)        	
		{        
        	$pos=strpos($index,"wl_");
//     	echo "campo".$wlcampo." default ".$camposs[$wlcampo]["default"]."posicion".strpos($camposs[$wlcampo]["default"],"nextval");        
        	if ($pos===0 && strpos($camposs[$wlcampo]["default"],"nextval")===false && $val!="") /* si el valor por default tiene nextval es una secuencia */
        	{   
            	 $val2 = strlen($val2)==0 ? "'".$val."'" : $val2.",'".$val."'" ;
        	}
		}        	
      }
      return $val2;
   }

   /**
   	* funcion que regresa los nombres de los campos de un arreglo
   	*  @param arreglo $array arreglo de la pagina recibida,
   	*  @param arreglo $camposs arreglo que contiene la descripciones del campo, para sabe si es una secuencia o es un campo de trabajo
   	*  @return string Nombre de los campos
   	**/
   function array_to_stringk($array,$camposs) 
   {
		$val="";
      foreach ($array as $index => $val)
      {
       	$wlcampo=substr($index,3);
		if ($camposs[$wlcampo]["attnum"]!=0)        	
		{
        	$pos=strpos($index,"wl_");
        	if ($pos===0 && strpos($camposs[$wlcampo]["default"],"nextval")===false && $val!="")
        	{   
            	$val2 = strlen($val2)==0 ? substr($index,3) : $val2.",".substr($index,3) ;
        	}
		}        	
      }
      return $val2;
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
      $this->inicio();
//      if  ($_SESSION['parametro1']!="" || $this->funcion=="validausuario")
//      {
         $arguments = array();
         $arguments = $this->decodeGetData($this->argumentos);
         call_user_func_array(array($this,$this->funcion),$arguments);
//      } else
//      { 
//         echo "<finsession>".$this->funcion."</finsession>";
//      }
     $this->termina();
   }
	/**
	*	Genera archivo txt y lo comprime
	**/
    function generaTexto($sql_result,$num,$parametro1,$me)
    {
		$hoy=date(Y)."-".date(m)."-".date(d).", ".date(H).":".date(i).":".date(s);
		$nombreArchivo="ficheros/consulta".$me->camposm["idmenu"]."_".$parametro1.".txt";
		$nombreArchivogz="ficheros/consulta".$me->camposm["idmenu"]."_".$parametro1.".txt.gz";
		// Borra  archivos txt y gz
		if(file_exists($nombreArchivo)) {unlink($nombreArchivo);}
		if(file_exists($nombreArchivogz)) {unlink($nombreArchivogz);}
		// genera el encabezado del archivo
		$ff = fopen ($nombreArchivo, w);
		$rowe = pg_fetch_array($sql_result, 0);
		foreach ($rowe as $value )
		{	if (Key($rowe)<"100")
			{ }
			else
//			{  if (!fwrite ($ff,Key($rowe)."|")) { echo "no pudo escribir archivo2"; } }
			{  if (!fwrite ($ff,$me->camposmc[Key($rowe)]["descripcion"]."|")) { echo "no pudo escribir archivo3"; } }
			next($rowe);
		}
		if (!fwrite ($ff,"\n")) { echo "no pudo escribir archivo4"; }
		// genera los registros
		for ($i=0; $i < $num ;$i++)
		{	$row = pg_fetch_array($sql_result, $i);
			foreach ($rowe as $value)
        	{	if (Key($rowe)<"100")
          		{ }
          		else
          		{ if (!fwrite ($ff,trim($row[Key($rowe)])."|")) { echo "no pudo escribir archivo5"; } }
          		next($rowe);
          	}
			if (!fwrite ($ff,"\n")) { echo "no pudo escribir archivo6"; }
		}
		if (!fwrite ($ff,"\narchivo generado: ".$hoy.", usuario: ".$parametro1)) { echo "no pudo escribir archivo7"; }
		fclose($ff);
		// Abrir en modo binario(lectura), acceder a los datos y cerrar
		$fp = fopen ($nombreArchivo, rb);
		$data = fread ($fp, filesize($nombreArchivo));
		fclose ($fp);
		// Abrir en modo binario (escritura), comprimir , escribir y cerrar
		$fd = fopen ($nombreArchivogz, wb);
		$gzdata = gzencode($data,9);
		fwrite($fd, $gzdata);
		fclose($fd);
		// Borra txt
		if(file_exists($nombreArchivo)) {unlink($nombreArchivo);}
	}
	/**
	*	Genera archivo excel y lo comprime
	**/
    function generaExcel($sql_result,$num,$parametro1,$me)
    {
		$hoy=date(Y)."-".date(m)."-".date(d).", ".date(H).":".date(i).":".date(s);
		$nombreArchivo="ficheros/consulta_".$parametro1.".xls";
		$nombreArchivogz="ficheros/consulta_".$parametro1.".xls.gz";
		// Borra  archivos xls y gz
		if(file_exists($nombreArchivo)) {unlink($nombreArchivo);}
		if(file_exists($nombreArchivogz)) {unlink($nombreArchivogz);}
		// genera el encabezado del archivo
		$ff = fopen ($nombreArchivo, w);
		if (!fwrite ($ff,"<table border=\"1\">")) { echo "no pudo escribir archivo8"; }
		if (!fwrite ($ff,"<tr>")) { echo "no pudo escribir archivo9"; }
		$rowe = pg_fetch_array($sql_result, 0);
		foreach ($rowe as $value )
		{	if (Key($rowe)<"100")
			{ }
			else
			{  
				if ($me=='')
				{	if (!fwrite ($ff,"<th style=\"color:#0A1B34; font-size:8PT; font-family:tahoma,verdana,arial; background-color:#A0B0C2;\">".Key($rowe)."</th>")) { echo "no pudo escribir archivo10"; }	}
				else
				{	if (!fwrite ($ff,"<th style=\"color:#0A1B34; font-size:8PT; font-family:tahoma,verdana,arial; background-color:#A0B0C2;\">".$me->camposmc[Key($rowe)]["descripcion"]."</th>")) { echo "no pudo escribir archivo11"; }	}
			}
			next($rowe);
		}
		if (!fwrite ($ff,"</tr>")) { echo "no pudo escribir archivo12"; }
		// genera los registros
		for ($i=0; $i < $num ;$i++)
		{	$row = pg_fetch_array($sql_result, $i);
			if (!fwrite ($ff,"<tr>")) { echo "no pudo escribir archivo13"; }
			foreach ($rowe as $value)
        	{	if (Key($rowe)<"100")
          		{ }
          		else
          		{ if (!fwrite ($ff,"<td style=\"color:#0A1B34; font-size:8PT; font-family:tahoma,verdana,arial; background-color:#EAF2F7;\">".trim($row[Key($rowe)])."</td>")) { echo "no pudo escribir archivo14"; } }
          		next($rowe);
          	}
			if (!fwrite ($ff,"</tr>")) { echo "no pudo escribir archivo15"; }
		}
		if (!fwrite ($ff,"<caption>archivo generado: ".$hoy.", usuario: ".$parametro1."</caption>")) { echo "no pudo escribir archivo16"; }
		if (!fwrite ($ff,"</table>")) { echo "no pudo escribir archivo"; }
		fclose($ff);
		// Abrir en modo binario(lectura), acceder a los datos y cerrar
		$fp = fopen ($nombreArchivo, rb);
		$data = fread ($fp, filesize($nombreArchivo));
		fclose ($fp);
		// Abrir en modo binario (escritura), comprimir , escribir y cerrar
		$fd = fopen ($nombreArchivogz, wb);
		$gzdata = gzencode($data,9);
		fwrite($fd, $gzdata);
		fclose($fd);
		// Borra txt
		//if(file_exists($nombreArchivo)) {unlink($nombreArchivo);}
	}
    function Enviaemail($error)
    {
   $wlemail='jlvdantry@hotmail.com';
   $wlemailk='kevin.solis@outlook.com';
   $mail = new PHPMailer;
   ##echo "paso new";
   $mail->IsSMTP();                                      // Set mailer to use SMTP
   $mail->Host = 'cj.df.gob.mx';  // Specify main and backup server
   ##$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup server
   $mail->SMTPAuth = true;                               // Enable SMTP authentication
   ##$mail->SMTPDebug = true;
   $mail->Username = 'no.reply';                            // SMTP username
   $mail->Password = 'NoResponder';                           // SMTP password
   $mail->Port = '25';                           // SMTP password
   $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
   $mail->From = 'no.reply@cj.df.gob.mx';
   $mail->FromName = 'Jose Luis Vasquez Barbosa';
   $mail->AddAddress($wlemail);               // Name is optional
   $mail->AddAddress($wlemailk);               // Name is optional
   $mail->AddReplyTo($wlemail, 'Information');
   $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
   $mail->IsHTML(true);                                  // Set email format to HTML
   $mail->Subject = 'Error en el aplicativo de ventanilla xml';
   $mail->Body    = $error."<br>IP: ".$_SERVER['REMOTE_ADDR'];
   if(!$mail->Send()) { exit; }
    }

}
?>
