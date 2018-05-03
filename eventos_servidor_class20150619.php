<?php
session_start();
require_once("class.phpmailer.php");
require_once("menudata.php");
require_once("xmlhttp_class.php");
require_once("soldatos.php");
require_once("cal_fechas.php");
require_once("cal_fechas.php");
/**
 *   Eventos que son ejecutados en el servido que son disparados en los html
 *   @package forapi
 */
class eventos_servidor_class extends xmlhttp_class
{	
    function Enviaemail()
    {
   $wlemail='jlvdantry@hotmail.com';
   $error=$this->argumentos["campos"];
   $mail = new PHPMailer;
   $mail->IsSMTP();                                      // Set mailer to use SMTP
   $mail->Host = 'smtp.live.com';  // Specify main and backup server
   ##$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup server
   $mail->SMTPAuth = true;                               // Enable SMTP authentication
   $mail->Username = 'jlvdantry@hotmail.com';                            // SMTP username
   $mail->Password = '888aDantryR';                           // SMTP password
   $mail->Port = '25';                           // SMTP password
   $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
   $mail->From = $wlemail;
   $mail->FromName = 'Jose Luis Vasquez Barbosa';
   $mail->AddAddress($wlemail);               // Name is optional
   $mail->AddReplyTo($wlemail, 'Information');
   $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
   $mail->IsHTML(true);                                  // Set email format to HTML
   $mail->Subject = 'Error en el aplicativo de ventanilla por eventos';
   $mail->Body    = $error;
   if(!$mail->Send()) {
               echo "<_nada_> </_nada_>";
                      }
               ##echo "<_nada_> </_nada_>";
               echo "<error>Envio correo</error>";
    }

        function altabarra()
        {
          $barra=$this->argumentos["wl_barras"];
          if ($barra=="")
          { $barra=$this->argumentos["wl_folio"]; }
          if ($barra=="")
          { echo "<error>No recibio el codigo de barras</error>"; }
          $sql=" select * from contra.dame_tramitedesdebarra('".$barra."');";
          $sql_result = @pg_exec($this->connection,$sql);
          if (strlen(pg_last_error($this->connection))>0)
          {
                        echo "<error>Error altabarra ".pg_last_error($this->connection).$sql."</error>";
                        return false;
          }
          $num = pg_numrows($sql_result);
          if ($num==0)
          {
              echo "<error>No encontro el codigo de barras ".$barra."</error>";
              return;
          }
          if ($num>1)
          {
              echo "<error>hay mas de un folio para el codigo de barras ".$barra."</error>";
              return;
          }
          $Row = pg_fetch_array($sql_result, 0);
          echo "<_nada_>".$Row['folioconsecutivo']."</_nada_>";
          echo "<fecharecibo>".$Row['fecharecibo']."</fecharecibo>";
          echo "<nota>".$Row['nota']."</nota>";
          echo "<nota_libro>".$Row['nota_libro']."</nota_libro>";
          echo "<vol>".$Row['vol']."</vol>";
          echo "<escr>".$Row['escr']."</escr>";

        }
        function altagestion()
        {
          $folio=$this->argumentos["wl_folioconsecutivo"];
          $movto=$this->argumentos["wl_id_tipotra"];
          $obse=$this->argumentos["wl_observacion"];
          $sql=" select * from contra.ope_turnados where folioconsecutivo=".$folio." and id_tipotra in(2,5) order by fecha_alta desc limit 1";
          $sql_result = @pg_exec($this->connection,$sql);
          if (strlen(pg_last_error($this->connection))>0)
          {
                        echo "<error>Error altagestion ".pg_last_error($this->connection).$sql."</error>";
                        return false;
          }
         $Row = pg_fetch_array($sql_result, 0);
         if ($obse=="LPE" || $obse=="RA" || $obse=="ENT" )
         {
               echo "<_nada_></_nada_>";
                return;
         }
         if ($movto==5)
         {
            if ($Row["id_tipotra"]==2 || $Row["id_tipotra"]==3)    // recibir tramite
            {   echo "<_nada_></_nada_>";
                return;
            } else {
                  echo "<error>Usted no puede recibir el folio ".$Row["folio"]." ya que el ultimo movimiento debe tener estatus de TURNAR or RECHAZAR </error>";
                  return;
           }
         }
         if ($movto==2)
         {
            if (($Row["id_tipotra"]==1 || $Row["id_tipotra"]==5) && $movto==2)   // turnar tramite
            {  echo "<_nada_></_nada_>";
               return;
            } else {
                  echo "<error>Usted no puede turnar el folio ".$Row["folio"]." ya que el ultimo movimiento debe tener estatus de RECIBIR o INICIO DE GESTION</error>";
                  return;
            }
         }
         if ($movto==6 || $movto==11)
         {
               echo "<_nada_></_nada_>";
                return;
         }
        }

        function entregainfjus()
        {
          $folio=$this->argumentos["wl_folioconsecutivo"];
          $movto=$this->argumentos["wl_id_tipotra"];
          $obse=$this->argumentos["wl_observacion"];
          $sql=" select ope.*,ge.id_cveasunto,ge.estatus as esges from contra.ope_turnados as ope, contra.gestion as ge where ope.folioconsecutivo=".
                 $folio." ".
                 " and ope.folioconsecutivo=ge.folioconsecutivo ".
                 " order by fecha_alta desc limit 1";
          $sql_result = @pg_exec($this->connection,$sql);
          if (strlen(pg_last_error($this->connection))>0)
          {
                        echo "<error>Error altagestion ".pg_last_error($this->connection).$sql."</error>";
                        return false;
          }
            $num = pg_numrows($sql_result);
                   if ($num==0)
                   {
                     echo "<error>No encontro tramite </error>"; return; 
                   }

         $Row = pg_fetch_array($sql_result, 0);
         if ($Row["id_cveasunto"]!="17")
         {  echo "<error>El tramite no es un informe de juzgados</error>"; return ;  }
         if ($Row["esges"]=="3")
         {  
                  echo "<error>El informe de juzgado ya fue entregado</error>"; return ;  
         }
         if ($obse=="LPE" || $obse=="RA" || $obse=="ENT" )
         {
               echo "<_nada_></_nada_>";
                return;
         }
         }

        function traedatos()
        {
           $wsdl = 'https://www.testamentosconsejeria.df.gob.mx/avisostesta/ws_avisos.php?wsdl';
              $sql="select referencia,folio from contra.gestion where referencia='".$this->argumentos["wl_referencia"]."' and id_cveasunto=24 ";
              $sql_result = @pg_exec($this->connection,$sql);
              if (strlen(pg_last_error($this->connection))>0)
              {
                echo "Error en traedatos ".$sql." error ".pg_last_error($this->connection);
                return;
              }

              $Row = pg_fetch_array($sql_result, 0);
                if ($Row[0]>0)
                {
                   echo "<error>El aviso de testamento ".$this->argumentos["wl_referencia"]." ya fue recido en ventanilla con el folio ".$Row["folio"]."</error>"; return;
                }

/*
           try {
                 $sClient = new SoapClient($wsdl,array('trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
                 $out = $sClient->consulta_aviso(array("id_escritura" => $this->argumentos["wl_referencia"]));
                 if ($out->estatus=="ok")
                 { echo "<_nada_>Se encontro el aviso de testamento</_nada_>";
                   echo "<nombre>$out->nombre</nombre>";
                   echo "<ap_paterno>$out->ap_paterno</ap_paterno>";
                   echo "<ap_materno>$out->ap_materno</ap_materno>";
                   echo "<num_notaria>$out->num_notaria</num_notaria>";
                   echo "<num_escritura>$out->num_escritura</num_escritura>";
                   echo "<fecha_recibido>$out->fecha_recibido</fecha_recibido>";
                 }
                 else
                 { echo "<error>".$out->mensaje."</error>";
               }
           } catch (SoapFault $fault) {
                  echo "<error>Error al conectarse al servidor de avisos de testamentos ".Fault."</error>";
           }
*/
        }

        /**
        *       Genera reporte de foio recibidos y que son entregados a otras areas
        */
        function ventanillapdf()
        {
           parent::subvista("ventanillaPDF.php","filtro=".$this->argumentos["filtro"].htmlspecialchars("&")."id_cveasunto=".$this->argumentos["wl_id_cveasunto"],600);
        }

        function impBoletaMasPDF()
        {
           parent::subvista("impBoletaMasPDF.php","filtro=".$this->argumentos["filtro"].htmlspecialchars("&")."id_cveasunto=".$this->argumentos["wl_id_cveasunto"].htmlspecialchars("&")."fecharecibo=".$this->argumentos["wl_fecharecibo"],600);
        }

        function impBoletaMas1PDF()
        {
           parent::subvista("impBoletaMas1PDF.php","filtro=".$this->argumentos["filtro"].htmlspecialchars("&")."id_cveasunto=".$this->argumentos["wl_id_cveasunto"].htmlspecialchars("&")."fecharecibo=".$this->argumentos["wl_fecharecibo"],600);
        }

        /**
        *      abre el php que ejecuta las estadisticas
        */
        function estadisticas()
        {
           $sql="select * from estadisticas.cat_estadisticas as ce where ce.id_estadistica=".$this->argumentos["wl_id_solicitud"];
           $sql_result = @pg_exec($this->connection,$sql);
           if (strlen(pg_last_error($this->connection))>0)
           {
                         echo "<error>Error turnarmasiva".pg_last_error($this->connection)."</error>";
                        return false;
           }
           $Row = pg_fetch_array($sql_result, 0);
           //$this->gendatos();
           parent::subvista("estadisticas.php","opcion=".$Row["funcion"]
                                 .htmlspecialchars("&")."wl_id_solicitud=".$this->argumentos["wl_id_solicitud"]
                                 .htmlspecialchars("&")."wl_fecha_inicial=".$this->argumentos["wl_fecha_inicial"]
                                 .htmlspecialchars("&")."wl_fecha_final=".$this->argumentos["wl_fecha_final"]
                                 ,627);
        }


	/**
	  *  Valida el password tecleado
	  */
	function validapwdtecleado()
	{
		
		if ($this->argumentos["wl_password"]=="")
    	{
    	  echo "<error>No esta definido el password</error>";
    	  return; 
    	}				
    	
		if ($this->argumentos["wl_tecleedenuevopassword"]=="")
    	{
    	  echo "<error>El pwd retecleado no esta definido</error>";
    	  return; 
    	}			
    	
		if ($this->argumentos["wl_usename"]=="")
    	{
    	  echo "<error>No esta definido el usuario</error>";
    	  return; 
    	}
    	$wlrespuesta=$this->checa_nuevopwd($this->argumentos["wl_usename"],$this->argumentos["wl_tecleedenuevopassword"],$this->argumentos["wl_password"]);
    	if ($wlrespuesta!="")
    	{
    		echo $wlrespuesta;
    		return;
		}
	 	$this->continuamovto();    	
	}
	
	function turnarmasiva()
	{
          if ($this->argumentos["filtro"]=="")
          { echo "<error>No esta definido el filtro para turnar</error>"; }
	  $sql=" select count(*) as cuantos from contra.v_gestionturnar where ".$this->argumentos["filtro"];
          $sql_result = @pg_exec($this->connection,$sql);
          if (strlen(pg_last_error($this->connection))>0)
          {
        		echo "<error>Error turnarmasiva ".pg_last_error($this->connection).$sql."</error>";
        		return false;
          } 
         $Row = pg_fetch_array($sql_result, 0);
         
		  echo "<_nada_>Usted va a turnar ".$Row["cuantos"]." tramites a Esta seguro</_nada_>";		    	      	  
		  return;
	}
	function validapago()
	{
              if ($this->argumentos["wl_lc"]=='')
              { echo "<error>no esta definida la linea de captura</error>"; return; }
              $sql="select * from contra.gestion where lc='".$this->argumentos["wl_lc"]."'";
              $sql_result = @pg_exec($this->connection,$sql);
              if (strlen(pg_last_error($this->connection))>0)
              {
                echo "Error al validarespuestadesbloqueo".$sql." error ".pg_last_error($this->connection);
                return;
              }
              $Row = pg_fetch_array($sql_result, 0);
                if ($Row[0]>0)
                {
                   echo "<error>La linea ya ocupada con el tramite ".$Row["folio"]."</error>"; return;
                }
              include("class_validalinea.php");
              $x= new class_validalinea();
              $x->lc=$this->argumentos["wl_lc"];
              $pos=$x->validapago();
              if ($pos==1)
              { echo "<_nada_>true</_nada_>"; return; }
              else 
              { echo "<error>No existe la Linea de captura</error>"; }
	}

	function turnarmasivaok()
	{
		  $sql=" select contra.turnamasiva(".$this->argumentos["wl_idpersona_recibe"].",'".str_replace("'","''",$this->argumentos["filtro"])."')";
          $sql_result = @pg_exec($this->connection,$sql);
          if (strlen(pg_last_error($this->connection))>0)
          {
        		echo "<error>Error turnarmasiva".pg_last_error($this->connection)."</error>";
        		return false;
          } 
        
		  echo "<error>Se turnaron los tramitos correctamente</error>";		    	      	  
		  return;
	}
	
		
	/**
	 *   efectua el cambio de password
	 */	
	function cambiodepassword()
	{
		
		if ($_SESSION["parametro1"]=="")
    	{
    	  echo "<error>No esta definido el usuario en cambio de password</error>";
    	  return; 
    	}						

		if ($_SESSION["parametro2"]=="")
    	{
    	  echo "<error>No esta definido el password en cambio de password</error>";
    	  return; 
    	}						    	
    			
		if ($this->argumentos["wl_passwordnuevo"]=="")
    	{
    	  echo "<error>No esta definido el password nuevo</error>";
    	  return; 
    	}				

		if ($this->argumentos["wl_passwordanterior"]=="")
    	{
    	  echo "<error>No esta definido el password anterior</error>";
    	  return; 
    	}				
    	    	    	
		if ($this->argumentos["wl_tecleedenuevopassword"]=="")
    	{
    	  echo "<error>El pwd retecleado no esta definido</error>";
    	  return; 
    	}			
    	
		if ($this->argumentos["wl_usename"]=="")
    	{
    	  echo "<error>No esta definido el usuario</error>";
    	  return; 
    	}
    	
    	$wlmensaje=$this->pwd_tecleado($this->argumentos["wl_passwordanterior"],$_SESSION["parametro2"],$_SESSION["parametro1"]);
    	if ($wlmensaje!="Se bloqueo el usuario" && $wlmensaje!="")
    	{
    		echo "<error>".$wlmensaje."</error>";
    		return;
		}
    	if ($wlmensaje=="Se bloqueo el usuario")
    	{
	    	$this->salida($wlmensaje);
    		return;
		}
		
    	$wlrespuesta=$this->checa_nuevopwd($this->argumentos["wl_usename"],$this->argumentos["wl_tecleedenuevopassword"],$this->argumentos["wl_passwordnuevo"]);
    	if ($wlrespuesta!="")
    	{
    		echo $wlrespuesta;
    		return;
		}
		
    	$wlrespuesta=$this->cambia_password($this->argumentos["wl_usename"],$this->argumentos["wl_passwordnuevo"],$this->argumentos["wl_passwordanterior"]);
    	
    	if ($wlrespuesta=="SE CAMBIO PASSWORD")
    	{
//    		echo "<salida>".$wlrespuesta."</salida>";
//			session_destroy();
			$this->salida($wlrespuesta);
    		return;	    	
    	}
    	else
    	{
    	echo "<error>".$wlrespuesta."</error>";		
		}

	}	

   /**
   	*  Funcion que cambia el tamaño de una subvista si este fue cambiado en el cliente
   	*/
   function cambiotamano()
   {
	   	if ($this->argumentos["idmenu"]=="")
    	{
    		echo "<error>El menu esta en espacios</error>";				   
    		return;
		}
	   	if ($this->argumentos["wldialogWidth"]=="" || $this->argumentos["wldialogWidth"]=="0")
    	{
    		echo "<error>El width no esta definido o esta en ceros</error>";				   
    		return;
		}
	   	if ($this->argumentos["wldialogHeight"]=="" || $this->argumentos["wldialogHeight"]=="0")
    	{
    		echo "<error>El height no esta definido o esta en ceros</error>";				   
    		return;
		}		
	    $sql=" update menus_subvistas set dialogwidth=".$this->argumentos["wldialogWidth"].", dialogheight=".$this->argumentos["wldialogHeight"].
 	         " where idsubvista=".$this->argumentos["idmenu"];
	    $sql_result = @pg_exec($this->connection,$sql);
        if (strlen(pg_last_error($this->connection))>0)
        {
        		echo "<error>Error cambiotamano".pg_last_error($this->connection)."</error>";
        		return;
        }                              
//        echo "<error>Cambio size</error>";
        echo "<__eventocontinua>true</__eventocontinua>";        
   }   
   	
	/**
	 *  Salida del sistema
	 */	
	function salida($wlmensaje)
	{
    		echo "<salida>".$wlmensaje."</salida>";
			session_destroy();		
	}

	
    /**
     *  cambia de password en la base de datos		
     *  @param string $usename  clave del usuario
     *  @param string $passwordnvo nuevo password
     *  @param string $passwordant password anterior
     */
	function cambia_password($usename,$passwordnvo,$passwordant)
	{
        $sql="select cambia_password('".$usename."','".$passwordnvo."','".$passwordant."');";
        $sql_result = @pg_exec($this->connection,$sql);
       	if (strlen(pg_last_error($this->connection))>0)
       	{
       		return "Error en cambia_password";
       	}                                                   
         
         $Row = pg_fetch_array($sql_result, 0);
         return $Row[0]; 
    }
    
    /**
     *  checa el nuevo passord
     *  @param string $usename  clave del usuario
     *  @param string $tecleedenuevopassword nuevo password
     *  @param string $password password anterior
     */            	
	function checa_nuevopwd($usename,$tecleedenuevopassword,$password)
    {
		if ($password!=$tecleedenuevopassword)       		
		{    	
    	  return "<error>La confirmacion del password es incorrecta</error>";
	 	}			
	 	
		if ($password==$usename)
    	{
    	  return "<error>El password no puede ser igual al usuario</error>";
    	}				    	    	
    	
		if (strlen($password)<6)
    	{
    	  return "<error>La cantidad de caracteres debe ser minimo de 6 posiciones</error>";
    	}				    	    	    	
 
/*   		if (count(count_chars($password, 1)) < 6)
    	{
    	  return "<error>Los caracteres de password deben ser diferentes </error>";
    	}	
*/
	}
	

    /**
     *  Valida la respuesta para desbloquear el usuario
     */            		
	function validarespuestadesbloqueo()
	{
		if ($this->argumentos["wl_usename"]=="")
    	{
    	  echo "<error>No esta definido el usuario</error>";
    	  return; 
    	}		
		if ($this->argumentos["wl_respuesta_"]=="")
    	{
    	  echo "<error>No esta definido la respuesta</error>";
    	  return; 
    	}
       	$sql=" select valida_res_des('".$this->argumentos["wl_usename"]."','".$this->argumentos["wl_respuesta_"]."')";
	   	$sql_result = @pg_exec($this->connection,$sql);
       	if (strlen(pg_last_error($this->connection))>0)
       	{
       		echo "Error al validarespuestadesbloqueo".$sql." error ".pg_last_error($this->connection);
       		return;
       	}                                          
    	$Row = pg_fetch_array($sql_result, 0); 
		if ($Row[0]>0)       		
		{
	   		$sql=" select desbloquea_usuario('".$this->argumentos["wl_usename"]."')";
	   		$sql_result = @pg_exec($this->connection,$sql);
    		$Row = pg_fetch_array($sql_result, 0); 	   		
       		if (strlen(pg_last_error($this->connection))>0)
       		{
       				return "Error al desbloquear usuario".$sql." ".pg_last_error($this->connection);
       		}                               
//       		echo "<salida>".$Row[0]."</salida>";
       		$this->salida($Row[0]);
       		return;
		}
		else
		{
    	  echo "<error>No es la respuesta correcta".$Row[0]."</error>";			
    	  return;			
		}
		
	}    
	
	/**
	*    abre en automatico la hoja de turnados, contra
	*/					   			
	function abreturnados()
	{
		echo "<abresubvista></abresubvista>";
		echo "<wlhoja>man_menus.php</wlhoja>";
		echo "<wlcampos>idmenu=1211".htmlspecialchars("&")."filtro=folioconsecutivo=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wldialogWidth>50</wldialogWidth>";
		echo "<wldialogHeight>30</wldialogHeight>";
	}	
	/**
	*    abre en automatico la hoja de archivos, contra
	*/					   			
	function abrearchivos()
	{
		//echo "<error>1:".$this->argumentos["iden"].",2:".$this->argumentos["wl_folioconsecutivo"]."</error>";
		echo "<abresubvista></abresubvista>";
		echo "<wlhoja>man_menus.php</wlhoja>";
		echo "<wlcampos>idmenu=1213".htmlspecialchars("&")."filtro=folioconsecutivo=".$this->argumentos["iden"]."</wlcampos>";
		//echo "<wlcampos>idmenu=1213".htmlspecialchars("&")."wl_folioconsecutivo=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wldialogWidth>50</wldialogWidth>";
		echo "<wldialogHeight>30</wldialogHeight>";
	}
	/**
	*    abre en automatico la hoja de archivos internos, contra
	*/					   			
	function abrearchivosInterno()
	{
		//echo "<error>1:".$this->argumentos["iden"].",2:".$this->argumentos["wl_folioconsecutivo"]."</error>";
		echo "<abresubvista></abresubvista>";
		echo "<wlhoja>man_menus.php</wlhoja>";
		echo "<wlcampos>idmenu=2147".htmlspecialchars("&")."filtro=folioconsecutivo=".$this->argumentos["iden"]."</wlcampos>";
		//echo "<wlcampos>idmenu=1213".htmlspecialchars("&")."wl_folioconsecutivo=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wldialogWidth>50</wldialogWidth>";
		echo "<wldialogHeight>30</wldialogHeight>";
	}
	/**
	*    Libera una gestion a los turnado, contra
	*/					   			
	function liberatramiteAlta($meda)
	{
		$sql = 	" select respuesta,fecha_alta from contra.v_turnados where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"].
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user) order by id_turnado desc ";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0)	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;}
		$row = pg_fetch_array($sql_result, 0);
		$respuesta=$row[0];
		$falta=$row[1];
		//echo "<error>".$falta."</error>";
		
		$sql = 	" select count(*) as registros from contra.v_turnados where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"].
				"	and id_persona not in (select id_persona from contra.cat_personas where usename=current_user) ".
				"	and fecha_alta>'$falta' and usuario_alta=current_user;	";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
       	if (strlen(pg_last_error($this->connection))>0)	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;}
		$row = pg_fetch_array($sql_result, 0);
		
		//echo "<error>".$row[registros]." - ".$respuesta." ".$falta."</error>";
		
		if ($row[registros]==0 && $respuesta=='t') { echo "<error>Imposible liberar, primero turne el tramite!</error>"; return; }

		$sql = 	"	select count (*) as registros from contra.v_turnados	".
				"	where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]." and liberado='N'".
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user) ";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return; }
       	$row = pg_fetch_array($sql_result, 0);
		if ($row[registros]==0) { echo "<error>El tramite ya fue liberado</error>"; return;}
		
		$sql = 	" update contra.ope_turnados".
				"	set liberado='S'	".
				"	where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]." and liberado='N'".
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user)" ;			
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
       	if (strlen(pg_last_error($this->connection))>0)
       	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;
       	}else{
			echo "<error>Tramite liberado</error>";
		}		
	}
	/**
	*    Libera una gestion a los turnado, contra
	*/					   			
	function liberatramite($meda)
	{
		$sql = 	" select respuesta,fecha_alta from contra.v_turnados where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"].
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user) order by id_turnado desc ";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0)	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;}
		$row = pg_fetch_array($sql_result, 0);
		$respuesta=$row[0];
		$falta=$row[1];
		//echo "<error>".$falta."</error>";
		
		$sql = 	" select count(*) as registros from contra.v_turnados where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"].
				"	and id_persona not in (select id_persona from contra.cat_personas where usename=current_user) ".
				"	and fecha_alta>'$falta' and usuario_alta=current_user;	";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
       	if (strlen(pg_last_error($this->connection))>0)	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;}
		$row = pg_fetch_array($sql_result, 0);
		
		//echo "<error>".$row[registros]." - ".$respuesta." ".$falta."</error>";
		
		if ($row[registros]==0 && $respuesta=='t') { echo "<error>Imposible liberar, primero turne el tramite!</error>"; return; }

		$sql = 	"	select count (*) as registros from contra.v_turnados	".
				"	where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]." and liberado='N'".
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user) ";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return; }
       	$row = pg_fetch_array($sql_result, 0);
		if ($row[registros]==0) { echo "<error>El tramite ya fue liberado</error>"; return;}
		
		$sql = 	" update contra.ope_turnados".
				"	set liberado='S'	".
				"	where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]." and liberado='N'".
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user)" ;			
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
       	if (strlen(pg_last_error($this->connection))>0)
       	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;
       	}else{
			//echo "<error>Tramite liberado</error>";
			echo "<actualiza></actualiza>";
			echo "<menu>1347</menu>";
			echo "<mensaje>Tramite liberado</mensaje>";
		}		
	}
	/**
	*    Libera una gestion a los turnado, contra
	*/					   			
	function liberatramiteInterno($meda)
	{	
		$sql = 	" select respuesta,fecha_alta from contra.v_turnados_interno where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"].
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user) order by id_turnado desc ";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0)	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;}
		$row = pg_fetch_array($sql_result, 0);
		$respuesta=$row[0];
		$falta=$row[1];
		//echo "<error>".$falta."</error>";
		
		$sql = 	" select count(*) as registros from contra.v_turnados_interno where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"].
				"	and id_persona not in (select id_persona from contra.cat_personas where usename=current_user) ".
				"	and fecha_alta>'$falta' and usuario_alta=current_user;	";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
       	if (strlen(pg_last_error($this->connection))>0)	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;}
		$row = pg_fetch_array($sql_result, 0);
		
		//echo "<error>".$row[registros]." - ".$respuesta." ".$falta."</error>";
		
		if ($row[registros]==0 && $respuesta=='t') { echo "<error>Imposible liberar, primero turne el tramite!</error>"; return; }

		$sql = 	"	select count (*) as registros from contra.v_turnados_interno	".
				"	where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]." and liberado='N'".
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user) ";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return; }
       	$row = pg_fetch_array($sql_result, 0);
		if ($row[registros]==0) { echo "<error>El tramite ya fue liberado</error>"; return;}
		
		$sql = 	" update contra.ope_turnados_interno".
				"	set liberado='S'	".
				"	where folioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]." and liberado='N'".
				"	and id_persona=(select id_persona from contra.cat_personas where usename=current_user)" ;			
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
       	if (strlen(pg_last_error($this->connection))>0)
       	{	echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; return;
       	}else{
			//echo "<error>Tramite liberado</error>";
			echo "<actualiza></actualiza>";
			echo "<menu>2153</menu>";
			echo "<mensaje>Tramite liberado</mensaje>";
		}		
	}	
	/**
	*	Cierra una gestion, contra
	*/					   			
	function cierraGestion()
	{
		//echo "<error>1</error>";
		$this->validapwdtecleadoope();
		$wlidpersonarecibe=$this->argumentos["wl_idpersona_recibe"];
		$wlusuarioalta=$this->argumentos["wl_usuario_alta"];
		$wlusuarioactual=$_SESSION["parametro1"];
		$wlfolio=$this->argumentos["wl_folioconsecutivo"];

		/*$sql = 	" select id_persona from contra.cat_personas where usename='".$wlusuarioactual."';";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"cierraGestion",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$wlidusuarioactual=$row["id_persona"];
		if ($wlidpersonarecibe==$wlidusuarioactual || $wlusuarioalta==$wlusuarioactual)
		{	echo "<__eventocontinua>true</__eventocontinua>";	}
		else
		{	echo "<error>El usuario actual no puede cerrar el tramite</error>";	}*/

		$sql = 	"	select ctt.respuesta	".
				"	from contra.ope_turnados as t	".
				"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=t.id_tipotra	".
				"	where folioconsecutivo=".$wlfolio."	".
				"	and id_persona =(select id_persona from contra.cat_personas where usename=current_user)	".
				"	order by t.fecha_alta desc	".
				"	limit 1	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['respuesta'];
		if ($reg=='t') { echo "<error>El ultimo turno requiere seguimiento</error>";	}
				
		$sql = 	" select estatus from contra.gestion where folioconsecutivo=$wlfolio;";
		$sql_result = pg_exec($this->connection,$sql);		
		$row = pg_fetch_array($sql_result, 0);
		$wlestatus=$row["estatus"];
		if ($wlestatus==3) { echo "<error>El tramite ya fue cerrado</error>";	}
		
		$sql = 	" select count (*) from contra.ope_archivos where folioconsecutivo=$wlfolio;";
		$sql_result = pg_exec($this->connection,$sql);		
		$row = pg_fetch_array($sql_result, 0);
		$count=$row["count"];
		if ($count==0) { echo "<error>Imposible cerrar, el tramite no tiene archivos adjuntos!</error>";	}
		
	}
	/**
	*	Cierra una gestion interna, contra
	*/					   			
	function cierraGestionInterno()
	{
		//echo "<error>1</error>";
		$this->validapwdtecleadoope();
		$wlidpersonarecibe=$this->argumentos["wl_idpersona_recibe"];
		$wlusuarioalta=$this->argumentos["wl_usuario_alta"];
		$wlusuarioactual=$_SESSION["parametro1"];
		$wlfolio=$this->argumentos["wl_folioconsecutivo"];

		/*$sql = 	" select id_persona from contra.cat_personas where usename='".$wlusuarioactual."';";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"cierraGestion",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$wlidusuarioactual=$row["id_persona"];
		if ($wlidpersonarecibe==$wlidusuarioactual || $wlusuarioalta==$wlusuarioactual)
		{	echo "<__eventocontinua>true</__eventocontinua>";	}
		else
		{	echo "<error>El usuario actual no puede cerrar el tramite</error>";	}*/
		
		$sql = 	"	select ctt.respuesta	".
				"	from contra.ope_turnados_interno as t	".
				"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=t.id_tipotra	".
				"	where folioconsecutivo=".$wlfolio."	".
				"	and id_persona =(select id_persona from contra.cat_personas where usename=current_user)	".
				"	order by t.fecha_alta desc	".
				"	limit 1	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['respuesta'];
		if ($reg=='t') { echo "<error>El ultimo turno requiere seguimiento</error>";	}
		
		$sql = 	" select estatus from contra.gestion_interno where folioconsecutivo=$wlfolio;";
		$sql_result = pg_exec($this->connection,$sql);		
		$row = pg_fetch_array($sql_result, 0);
		$wlestatus=$row["estatus"];
		if ($wlestatus==3) { echo "<error>El tramite ya fue cerrado</error>";	}
		
		$sql = 	" select count (*) from contra.ope_archivos_interno where folioconsecutivo=$wlfolio;";
		$sql_result = pg_exec($this->connection,$sql);		
		$row = pg_fetch_array($sql_result, 0);
		$count=$row["count"];
		if ($count==0) { echo "<error>Imposible cerrar, el tramite no tiene archivos adjuntos!</error>";	}
		
	}
	/**
	*	Cierra una gestion, contra
	*/					   			
	function cierraGestionOficio()
	{
		$this->validapwdtecleadoope();
		$wlidpersonarecibe=$this->argumentos["wl_idpersona_recibe"];
		$wlusuarioalta=$this->argumentos["wl_usuario_alta"];
		$wlusuarioactual=$_SESSION["parametro1"];
		$wlfolio=$this->argumentos["wl_folioconsecutivo"];

		/*$sql = 	" select id_persona from contra.cat_personas where usename='".$wlusuarioactual."';";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"cierraGestion",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en libera ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$wlidusuarioactual=$row["id_persona"];
		if ($wlidpersonarecibe==$wlidusuarioactual || $wlusuarioalta==$wlusuarioactual)
		{	echo "<__eventocontinua>true</__eventocontinua>";	}
		else
		{	echo "<error>El usuario actual no puede cerrar el tramite</error>";	}*/
		
		$sql = 	" select estatus from contra.gestion where folioconsecutivo=$wlfolio;";
		$sql_result = pg_exec($this->connection,$sql);		
		$row = pg_fetch_array($sql_result, 0);
		$wlestatus=$row["estatus"];
		if ($wlestatus==3) { echo "<error>El tramite ya fue cerrado</error>";	}
		
		$sql = 	" select count (*) from contra.ope_archivos where folioconsecutivo=$wlfolio and id_tipoarc=2;";
		$sql_result = pg_exec($this->connection,$sql);		
		$row = pg_fetch_array($sql_result, 0);
		$count=$row["count"];
		if ($count==0) { echo "<error>Imposible cerrar, el tramite no tiene adjunto el oficio de contestacion!</error>";	}
		
	}
	/**
	*	Cierra una gestion, contra
	*/					   			
	function validaPuestoPadre()
	{
		$wlorganizacion=$this->argumentos["wl_id_organizacion"];
		$wlpuestopadre=$this->argumentos["wl_id_puesto_padre"];
		$wlpuesto=$this->argumentos["wl_id_puesto"];
		
		$sql =	"	select count (*) as registros from contra.cat_puestos where id_puesto=$wlpuestopadre and id_puesto in ".
				"	(	".
				"	select id_puesto from contra.cat_puestos where id_organizacion=$wlorganizacion  and id_puesto_padre=$wlpuesto	".
				"	union	".
				"	select id_puesto from contra.cat_puestos where id_organizacion=$wlorganizacion  and id_puesto_padre in	".
				"	(select id_puesto from contra.cat_puestos where id_organizacion=$wlorganizacion  and id_puesto_padre=$wlpuesto) )	";

		/*$sql = 	" select count(*) as registros from contra.cat_puestos where id_puesto in ".
				"	(select id_puesto from contra.cat_puestos where id_organizacion=$wlorganizacion and id_puesto_padre=$wlpuestopadre	".
				"	union	".
				"	select id_puesto from contra.cat_puestos where id_organizacion=$wlorganizacion and id_puesto_padre in ".
				"	(select id_puesto from contra.cat_puestos where id_organizacion=$wlorganizacion and id_puesto_padre=$wlpuestopadre))";*/
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en validaPuestoPadre ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		//echo "<error>".$row[registros]."</error>";
		echo "<enviavalor>".$row[registros]."</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	Cierra una gestion, contra
	*/
	function actuEstatusGestion()
	{
		$folioc=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	" update contra.gestion set estatus=2 where folioconsecutivo=$folioc and estatus=1	";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en actuEstatusGestion ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	Cierra una gestion interno, contra
	*/
	function actuEstatusGestionInterno()
	{
		$folioc=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	" update contra.gestion_interno set estatus=2 where folioconsecutivo=$folioc and estatus=1	";
		$sql_result = pg_exec($this->connection,$sql);
		$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en actuEstatusGestionInterno ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		echo "<__eventocontinua>true</__eventocontinua>";
	}
    /**
     *  Valida usuario a desbloquear
     */            			
	function validausuarioadesbloquear()
	{
		if ($this->argumentos["wl_usuario"]=="")
    	{
    	  echo "<error>No esta definido el usuario a autorizar</error>";
    	  return; 
    	}    				   		
       		$sql=" select usuario_bloqueado('".$this->argumentos["wl_usuario"]."')";
	   		$sql_result = @pg_exec($this->connection,$sql);
       		if (strlen(pg_last_error($this->connection))>0)
       		{
       			return "Error al validarusuarioadesbloquear".$sql." error ".pg_last_error($this->connection);
       		}                                          
    		$Row = pg_fetch_array($sql_result, 0); 
			if ($Row[0]>0)       		
			{
##    	  		echo "<abresubvista>1009</abresubvista>";				
##    	  		echo "<filtro>usename=".$this->argumentos["wl_usuario"]."</filtro>";				    	  		
				echo "<abresubvista></abresubvista>";
				echo "<wlhoja>man_menus.php</wlhoja>";
				echo "<wlcampos>idmenu=1022".htmlspecialchars("&")."filtro=usename='".$this->argumentos["wl_usuario"]."'</wlcampos>";
##				echo "<wlcampos>idmenu=1009</wlcampos>";
				echo "<wldialogWidth>50</wldialogWidth>";
				echo "<wldialogHeight>30</wldialogHeight>";		    	  		
			}
			else
			{
    	  		echo "<error>El usuario no esta bloqueado</error>";				
			}
		    return ""; 	   
	}	
    /**
     *  Valida el pwd tecleado para autorizar una operacion
     *  Return true si ok false error
     */            				
   function validapwdtecleadoope()
   {
		if ($_SESSION["parametro1"]=="")
    	{
    	  echo "<error>No esta definido el usuario en suspende actividades</error>";
    	  return false; 
    	}						
		if ($_SESSION["parametro2"]=="")
    	{
    	  echo "<error>No esta definido el password en suspende actividades</error>";
    	  return false; 
    	}						    
		if ($this->argumentos["wl_password"]=="")
    	{
    	  echo "<error>Falta teclear el password</error>";
    	  return false; 
    	}    				   		    		
    	$wlmensaje=$this->pwd_tecleado($this->argumentos["wl_password"],$_SESSION["parametro2"],$_SESSION["parametro1"]);
    	if ($wlmensaje!="Se bloqueo el usuario" && $wlmensaje!="")
    	{
    		echo "<error>".$wlmensaje."</error>";
    		return false;
		}
    	if ($wlmensaje=="Se bloqueo el usuario")
    	{
	    	$this->salida($wlmensaje);
    		return false;
		}	   	   
		echo "<__eventocontinua>true</__eventocontinua>";
   }
    /**
     *  Valida el pwd tecleado para autorizar una operacion
     *  envia valor al browser 1=ok, 2=er
     */  
   function validapwdtecleadoenviavalor()
   {
	   $valor=1;
		if ($_SESSION["parametro1"]=="")
    	{
    	  echo "<error>No esta definido el usuario en suspende actividades</error>";
    	  $valor=2;
    	}						
		if ($_SESSION["parametro2"]=="")
    	{
    	  echo "<error>No esta definido el password en suspende actividades</error>";
    	  $valor=2;
    	}						    
		if ($this->argumentos["wl_password"]=="")
    	{
    	  echo "<error>Falta teclear el password</error>";
    	  $valor=2;
    	}    				   		    		
    	$wlmensaje=$this->pwd_tecleado($this->argumentos["wl_password"],$_SESSION["parametro2"],$_SESSION["parametro1"]);
    	if ($wlmensaje!="Se bloqueo el usuario" && $wlmensaje!="")
    	{
    		echo "<error>".$wlmensaje."</error>";
    		$valor=2;
		}
    	if ($wlmensaje=="Se bloqueo el usuario")
    	{
	    	$this->salida($wlmensaje);
    		$valor=2;
		}	   	   
		echo "<enviavalor>".$valor."</enviavalor>";
   }
   /**
   	*  Autoriza un usuario para utilizar el sistema
   	*/
   function autoriza_usuario()
   {
	if ($_SESSION["parametro1"]=="")
    	{
    	  echo "<error>No esta definido el usuario en cambio de password</error>";
    	  return; 
    	}						
	if ($_SESSION["parametro2"]=="")
    	{
    	  echo "<error>No esta definido el password en cambio de password</error>";
    	  return; 
    	}						    	
	if ($this->argumentos["wl_usename"]=="")
    	{
    	  echo "<error>No esta definido el usuario a autorizar</error>";
    	  return; 
    	}    				   
    	$wlmensaje=$this->pwd_tecleado($this->argumentos["wl_password"],$_SESSION["parametro2"],$_SESSION["parametro1"]);
    	if ($wlmensaje!="Se bloqueo el usuario" && $wlmensaje!="")
    	{
    		echo "<error>".$wlmensaje."</error>";
    		return;
	}
    	if ($wlmensaje=="Se bloqueo el usuario")
    	{
	    	$this->salida($wlmensaje);
    		return;
	}
		
	    $sql=" select autoriza_usuario('".$this->argumentos["wl_usename"]."')";
	    //echo "<error>$sql</error>	";
	    $sql_result = @pg_exec($this->connection,$sql);
        if (strlen(pg_last_error($this->connection))>0)
        {
        		echo "<error>Error al autorizar usuario".pg_last_error($this->connection)." ".$sql."</error>";
        		return;
        }                               
    	$Row = pg_fetch_array($sql_result, 0); 
##		if ($Row[0]=="No existe grupo asignado al usuario")       		
##		{
    		echo "<error>".$Row[0]."</error>";			
##		}
        return "";		
/*		
    	$wlmensaje=$this->tiene_grupo($this->argumentos["wl_usename"]);		
    	if ($wlmensaje!="")
    	{
    		echo "<error>".$wlmensaje."</error>";
    		return;
		}    					
    	$wlmensaje=$this->activa_usuario($this->argumentos["wl_usename"]);		
    	if ($wlmensaje!="")
    	{
    		echo "<error>".$wlmensaje."</error>";
    		return;
		}    	
*/		
		echo "<error>Se autorizo usuario ".$this->argumentos["wl_usename"]."</error>";
   }

   /**
    *   valida que el password tecleado sea igual al registrado en el sistema
    *   @param string  $pwdtecleado  password tecleado
    *   @param string  $password	 password del usuario registrado en el sistema
    *   @param string  $usuario		 clave del usuario
    */
   function pwd_tecleado($pwdtecleado,$password,$usuario)
   {
	   if ($password=="")
	   {
		   return "No esta definido el password del usuario en la session";
	   }
	   if ($usuario=="")
	   {
		   return "No esta definido el usuario en la session";
	   }	   
	   
	   if ($pwdtecleado!=$password)
	   { 
			$wlmensaje=$this->cuenta_errores($usuario);
    		if ($wlmensaje!="")
    		{
    			return $wlmensaje;
			}
/*			
            $sql="select grababitacora(0, 999, 0,0,current_date,current_date,cast('".session_id()."' as text))";		   
	   		$sql_result = @pg_exec($this->connection,$sql);
       		if (strlen(pg_last_error($this->connection))>0)
       		{
       			return "Error al grabar en bitacora pwd_tecleado sql".$sql." error ".pg_last_error($this->connection);
       		}                                          
*/       		
		    return "No checa el password"; 
//		    return "No checa el password tecleado=".$pwdtecleado." session=".$password; 		    
	   }
	   return "";
   }
   
   /**
   *   cuenta los errores al no checar el password del usuario
   *   @param string $usuario clave del usuario
   */   
   function cuenta_errores($usuario)
   { 
            $sql="select grababitacora(0, 999, 0,0,current_date,current_date,cast('".session_id()."' as text))";		   
	   		$sql_result = @pg_exec($this->connection,$sql);
       		if (strlen(pg_last_error($this->connection))>0)
       		{
       			return "Error al grabar en bitacora cuenta_errores sql".$sql." error ".pg_last_error($this->connection);
       		}                                          
       			     
       		$sql=" select count(*) from cat_bitacora where ".
//       		     " usuario_alta='".$usuario."'".  // lo quite porque al ingresar el usuario a registrar en bitacora
													  // es el usuario temporal
       		     " idproceso=999 and ".
       		     " descripcion='".session_id()."'".
       		     " and fecha_alta > current_timestamp - interval '3 min'";
	   		$sql_result = @pg_exec($this->connection,$sql);
       		if (strlen(pg_last_error($this->connection))>0)
       		{
       			return "Error al contar bitacora bloquea_usuario sql".$sql." error ".pg_last_error($this->connection);
       		}                                          
    		$Row = pg_fetch_array($sql_result, 0); 
			if ($Row[0]>2)       		
			{
				$wlmensaje=$this->bloquea_usuario($usuario);
    			if ($wlmensaje!="")
    			{
    				return $wlmensaje;
				}    					
			}
		    return ""; 	   
   }

   /**
   *   Cambia el estatus del usuario a activo
   *   @param string $usuario clave del usuario
   */         
   function activa_usuario($usuario)
   {
	   $sql=" update cat_usuarios set estatus=1 where usename='".$usuario."'";
	   $sql_result = @pg_exec($this->connection,$sql);
       if (strlen(pg_last_error($this->connection))>0)
       {
       		return "Error al autorizar usuario".$sql." ".pg_last_error($this->connection);
       }                               
       return "";
   }
   
   /**
   *   bloquea el usuario a activo
   *   @param string $usuario clave del usuario
   */            
   function bloquea_usuario($usuario)
   {
	   $sql=" select bloquea_usuario('".$usuario."')";
	   $sql_result = @pg_exec($this->connection,$sql);
       if (strlen(pg_last_error($this->connection))>0)
       {
       		return "Error al bloquear usuario".$sql." ".pg_last_error($this->connection);
       } 
       $Row = pg_fetch_array($sql_result, 0);                               
       return $Row[0];
   }   
     
   /**
   *   Continua el flujo del movimiento
   */            	
	function continuamovto()
	{
		echo "<continuamovto>true</continuamovto>";
		echo "<wlmenu>".$this->argumentos[wlmenu]."</wlmenu>";
		echo "<wlmovto>".htmlspecialchars($this->argumentos[wlmovto])."</wlmovto>";		
		echo "<wlllave>".htmlspecialchars($this->argumentos[wlllave])."</wlllave>";				
		echo "<wlrenglon>".htmlspecialchars($this->argumentos[wlrenglon])."</wlrenglon>";
		echo "<wleventodespues>".htmlspecialchars($this->argumentos[wleventodespues])."</wleventodespues>";								
	}	
	/**
	* funcion para validar que la fecha no sea mayor a la actual
	*/
	function valida_fechamayor ($x,$y)
	{
		$hoy = date("Y-m-d");
		$fecha = $x;
		$fechades = $y;
		if ($fecha>$hoy) { echo "<error>La ".$fechades." no puede ser mayor a la actual</error>	"; }
		echo "<__eventocontinua>true</__eventocontinua>";
	}
   /**
   *   Regresa la fecha del servidor
   */ 
	function fechaServidor ()
	{
		$wlfechactual=date(Y)."-".date(m)."-".date(d);
		echo "<fechaactual>".$wlfechactual."</fechaactual>";
		echo "<_nada_></_nada_>";
	}
	/**
	*	Realiza el registro del usuario en contra.cat_personas
	*/	
	function salidacontra($wlmensaje)
	{
		$usename=$this->argumentos["wl_usename"];
		$nombre=$this->argumentos["wl_nombre"];
		$apepat=$this->argumentos["wl_apepat"];
		$apemat=$this->argumentos["wl_apemat"];
		$correoe=$this->argumentos["wl_correoe"];
		//echo "<error> $usename $nombre $apepat $apemat $correoe </error>	";
		$sql = 	"	insert into contra.cat_personas (nombre,apepat,apemat,usename,correoe)	".
				"	values ('$nombre','$apepat','$apemat','$usename','$correoe')	";
		//echo "<error>$sql</error>";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error al ejecutar qry ".$sql." ".pg_last_error($this->connection)."</error>"; }
		$this->salida("El registro se realizo exitosamente");
	}

        /**
        *       Genera la impresion del volante de un tramite con firma
        */
        function quefolioAsigno()
        {
               $sql =  "       select * from contra.gestion         ".
                       "       where folioconsecutivo=".$this->argumentos["iden"];
                $sql_result = pg_exec($this->connection,$sql);
                if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en quefolioAsigno ".$sql." error ".pg_last_error($this->connection)."</error>"; }
                else
                {
                  $num = pg_numrows($sql_result);
                  if ($num!=0) 
                  {
                    $row = pg_fetch_array($sql_result, 0);
                    $folio=$row['folio'];
                    echo "<error>Folios Asignado: ".$folio."</error>";
                  }
                  { echo "<error>error no encontro folio asignado</error>"; }
                }
        }
	/**
	*	Genera la impresion del volante de un tramite con firma
	*/	
	function impresionAltaTramite()
	{
	   $relevante=$this->argumentos["wl_relevante"];
	   echo "<abresubvista></abresubvista>";
       echo "<wlhoja>impTramitePDF.php</wlhoja>";
       echo "<wlcampos>wlfolioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]."".htmlspecialchars("&")."relevante=".$relevante."</wlcampos>";
       echo "<wldialogWidth>50</wldialogWidth>";
       echo "<wldialogHeight>30</wldialogHeight>";		
       }   

	/**
	*	Genera la impresion del volante de un tramite con firma
	*/	
	function ImprimeBoleta()
	{
           if ($this->argumentos["iden"]!="" || $this->argumentos["wl_folioconsecutivo"]!="")
           {
           $wlfolio=($this->argumentos["iden"]!="" ? $this->argumentos["iden"] : $this->argumentos["wl_folioconsecutivo"]);
	   $relevante=$this->argumentos["wl_relevante"];
//           parent::subvista("impBoletaPDF.php","wlfolioconsecutivo=".$wlfolio."".htmlspecialchars("&")."relevante=".$relevante,600);
           $this->quefolioAsigno();
           } else {                 echo "<error>no encontro folio</error>"; }
       }   

        function ImprimeBoletacb()
        {
           if ($this->argumentos["iden"]!="" || $this->argumentos["wl_folioconsecutivo"]!="")
           {
           $wlfolio=($this->argumentos["iden"]!="" ? $this->argumentos["iden"] : $this->argumentos["wl_folioconsecutivo"]);
           $relevante=$this->argumentos["wl_relevante"];
           parent::subvista("impBoletaPDF.php","wlfolioconsecutivo=".$wlfolio."".htmlspecialchars("&")."relevante=".$relevante,600);
           } else {                 echo "<error>no encontro folio</error>"; }
       }


	/**
	*	Genera la impresion del volante de un tramite sin firma
	*/	
	function impresionAltaTramiteConsulta()
	{
	   $relevante=$this->argumentos["wl_relevante"];
	   echo "<abresubvista></abresubvista>";
       echo "<wlhoja>impTramitePDFconsulta.php</wlhoja>";
       echo "<wlcampos>wlfolioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]."".htmlspecialchars("&")."relevante=".$relevante."</wlcampos>";
       echo "<wldialogWidth>50</wldialogWidth>";
       echo "<wldialogHeight>30</wldialogHeight>";		
   }
	/**
	*	Genera la impresion del volante de un tramite con firma
	*/	
	function impresionAltaTramiteInterno()
	{
	   $relevante=$this->argumentos["wl_relevante"];
	   echo "<abresubvista></abresubvista>";
       echo "<wlhoja>impTramiteInternoPDF.php</wlhoja>";
       echo "<wlcampos>wlfolioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]."".htmlspecialchars("&")."relevante=".$relevante."</wlcampos>";
       echo "<wldialogWidth>50</wldialogWidth>";
       echo "<wldialogHeight>30</wldialogHeight>";		
   }   
	/**
	*	Genera la impresion del volante de un tramite sin firma
	*/	
	function impresionAltaTramiteConsultaInterno()
	{
	   $relevante=$this->argumentos["wl_relevante"];
	   echo "<abresubvista></abresubvista>";
       echo "<wlhoja>impTramiteInternoPDFconsulta.php</wlhoja>";
       echo "<wlcampos>wlfolioconsecutivo=".$this->argumentos["wl_folioconsecutivo"]."".htmlspecialchars("&")."relevante=".$relevante."</wlcampos>";
       echo "<wldialogWidth>50</wldialogWidth>";
       echo "<wldialogHeight>30</wldialogHeight>";		
   }
	/**
	*	funcion que revisa el alta de un turno, CONTRA
	*/	
	function validaTramiteTurnado ()
	{
		$folioc=$this->argumentos["wl_folioconsecutivo"];
		$personat=$this->argumentos["wl_id_persona"];
		$tramite=$this->argumentos["wl_id_tipotra"];
		//echo "<error>$folioc - $personat - $tramite</error>	";
		$sql = 	"	select * from contra.v_turnados 	".
				"	where folioconsecutivo=$folioc and id_persona=$personat and liberado='N' 	";
				//"	where folioconsecutivo=$folioc and id_persona=$personat and id_tipotra=$tramite and liberado='N' 	";
		$sql_result = pg_exec($this->connection,$sql);
		//$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en validaTramiteTurnado ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		else
		{
		$num = pg_numrows($sql_result);
		echo "<enviavalor>$num</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
		}
	}
	/**
	*	funcion que revisa el alta de un turno, CONTRA
	*/	
	function validaTramiteTurnadoInterno ()
	{
		$folioc=$this->argumentos["wl_folioconsecutivo"];
		$personat=$this->argumentos["wl_id_persona"];
		$tramite=$this->argumentos["wl_id_tipotra"];
		//echo "<error>$folioc - $personat - $tramite</error>	";
		$sql = 	"	select * from contra.v_turnados_interno 	".
				"	where folioconsecutivo=$folioc and id_persona=$personat and liberado='N' 	";
				//"	where folioconsecutivo=$folioc and id_persona=$personat and id_tipotra=$tramite and liberado='N' 	";
		$sql_result = pg_exec($this->connection,$sql);
		//$this->hayerrorsql($this->connection,"libera",$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error en validaTramiteTurnadoInterno ".$sql." error ".pg_last_error($this->connection)."</error>"; }
		else
		{
		$num = pg_numrows($sql_result);
		echo "<enviavalor>$num</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
		}
	}
	/**
	*	funcion para regresar el usuario actual
	*/	
	function usuarioBase ()
	{
		$usename=$_SESSION["parametro1"];
		echo "<enviavalor>$usename</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para regresar el tipo de tramite del turno seleccionado
	*/	
	function tramiteTurno ()
	{
		$idturno=$this->argumentos["wl_id_turnado"];
		$sql = 	"	select id_tipotra from contra.ope_turnados where id_turnado=$idturno;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$idtipotra=$row['id_tipotra'];
		echo "<enviavalor>$idtipotra</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para regresar el tipo de tramite interno del turno seleccionado
	*/	
	function tramiteTurnoInterno ()
	{
		$idturno=$this->argumentos["wl_id_turnado"];
		$sql = 	"	select id_tipotra from contra.ope_turnados_interno where id_turnado=$idturno;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$idtipotra=$row['id_tipotra'];
		echo "<enviavalor>$idtipotra</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para regresar el estatus del tramite
	*/	
	function estatusTramite ()
	{
		$folioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	"	select estatus from contra.gestion where folioconsecutivo=$folioconsecutivo;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$estatus=$row['estatus'];
		echo "<enviavalor>$estatus</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para regresar el numero de tramites turnado del turno
	*/	
	function turnosTurno ()
	{
		$fechaalta=$this->argumentos["wl_fecha_alta"];
		$idturno=$this->argumentos["wl_id_turnado"];
		$folioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	"	select count (*) as reg from contra.ope_turnados	".
				"	where usuario_alta=(select usename from contra.cat_personas	".
				"		where id_persona=(select id_persona from contra.ope_turnados	".
				"			where id_turnado=$idturno)) and fecha_alta>'$fechaalta' and folioconsecutivo=".$folioconsecutivo;
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['reg'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para regresar el numero de tramites turnado del turno interno
	*/	
	function turnosTurnoInterno ()
	{
		$fechaalta=$this->argumentos["wl_fecha_alta"];
		$idturno=$this->argumentos["wl_id_turnado"];
		$folioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	"	select count (*) as reg from contra.ope_turnados_interno	".
				"	where usuario_alta=(select usename from contra.cat_personas	".
				"		where id_persona=(select id_persona from contra.ope_turnados_interno	".
				"			where id_turnado=$idturno)) and fecha_alta>'$fechaalta' and folioconsecutivo=".$folioconsecutivo;
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['reg'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si la referencia ya existe
	*/	
	function validaReferencia ()
	{
		$anio = date("Y");
		$referecnia=$this->argumentos["wl_referencia"];
		$penvia	=$this->argumentos["wl_idpersona_envia"];
		$sql = 	"	select id_organizacion, count (*) as reg from contra.gestion as g ".
				"	left join contra.v_cat_personas as vcp on vcp.id_persona=g.idpersona_envia	".
				"	where referencia='".$referecnia."' and anio=".$anio." and referencia<>'S/N' and idpersona_envia= ".$penvia."	".
				"	group by 1	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		if ($row['reg']=='') { $reg=0; } else {$reg=$row['reg'];}
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si el tramite ya existe
	*/	
	function validaDuplicado ()
	{
		$anio = date("Y");
		$referecnia=$this->argumentos["wl_referencia"];
		$penvia=$this->argumentos["wl_idpersona_envia"];
		$precibe=$this->argumentos["wl_idpersona_recibe"];
		$fecha=$this->argumentos["wl_fechadocumento"];
		$docto=$this->argumentos["wl_id_tipodocto"];
		$asunto=$this->argumentos["wl_id_cveasunto"];
		
		$sql = 	"	select count (*) as reg from contra.gestion as g ".
				"	where referencia='".$referecnia."' and anio=".$anio." and referencia='".$referecnia."' and idpersona_envia= ".$penvia."	".
				"	and idpersona_recibe=".$precibe." and fechadocumento='".$fecha."' and id_tipodocto=".$docto." and id_cveasunto=".$asunto.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		if ($row['reg']=='') { $reg=0; } else {$reg=$row['reg'];}
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si la referencia ya existe en tramites internos
	*/	
	function validaReferenciaInterno ()
	{
		$referecnia=$this->argumentos["wl_referencia"];
		$docto=$this->argumentos["wl_id_tipodocto"];
		$sql = 	"	select count (*) as reg from contra.gestion_interno where referencia='".$referecnia."' and referencia<>'S/N' and id_tipodocto=".$docto.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['reg'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si el tramite ya existe
	*/	
	function validaDuplicadoInterno ()
	{
		$anio = date("Y");
		$referecnia=$this->argumentos["wl_referencia"];
		$penvia=$this->argumentos["wl_idpersona_envia"];
		$precibe=$this->argumentos["wl_idpersona_recibe"];
		$fecha=$this->argumentos["wl_fechadocumento"];
		$docto=$this->argumentos["wl_id_tipodocto"];
		$asunto=$this->argumentos["wl_id_cveasunto"];
		
		$sql = 	"	select count (*) as reg from contra.gestion_interno as g ".
				"	where referencia='".$referecnia."' and anio=".$anio." and idpersona_envia= ".$penvia."	".
				"	and idpersona_recibe=".$precibe." and fechadocumento='".$fecha."' and id_tipodocto=".$docto." and id_cveasunto=".$asunto.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		if ($row['reg']=='') { $reg=0; } else {$reg=$row['reg'];}
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si el folio ya existe
	*/	
	function validaFolio ()
	{
		$folio=$this->argumentos["wl_folio"];
		$anio=$this->argumentos["wl_anio"];
		$sql = 	"	select count (*) as reg from contra.gestion where folio='".$folio."' and anio=".$anio.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['reg'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para generar reporte de control de tramites
	*/	
	function reportesPDF()
	{
		$idtiporeporte=$this->argumentos["wl_idtiporeporte"];
		//echo "<error>".$idtiporeporte."</error>";
		$sql = 	"	select php,interno from contra.cat_tiporeportes where idtiporeporte=".$idtiporeporte.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$php=$row['php'];
		$iterno=$row['interno'];
		//echo "<error>".$php."</error>";
		$fecharecibo=$this->argumentos["wl_fecharecibo"];
		$fecharecibofin=$this->argumentos["wl_fecharecibo_fin"];
		$relvante=$this->argumentos["wl_relevante"];
		$organizacion=$this->argumentos["wl_id_organizacion"];
		$puesto=$this->argumentos["wl_id_puesto"];
		$persona=$this->argumentos["wl_id_persona"];
		$tramite=$this->argumentos["wl_id_tipotra"];
		$fechat=$this->argumentos["wl_fecha_turno"];
		$area=$this->argumentos["wl_id_puesto_direccion"];
		$foliosunicos=$this->argumentos["wl_foliosunicos"];
		$estatus=$this->argumentos["wl_estatus"];
		$ventanilla=$this->argumentos["wl_ventanilla"];
		
		$wlfltro =	" and vp.id_organizacion=$organizacion and vp.fecharecibo between '$fecharecibo' and '$fecharecibofin'";
					
		if ($area!='') {$wlfltro=$wlfltro." and vp.id_puesto in (select id_puesto from contra.cat_puestos where id_puesto_direccion=".$area.")";}
		if ($relvante=='t') {$wlfltro=$wlfltro." and vp.relevante is true ";}
		if ($puesto!='') {$wlfltro=$wlfltro." and vp.id_puesto=$puesto ";}
		if ($persona!='') {$wlfltro=$wlfltro." and vp.id_persona=$persona ";}
		if ($tramite!='') {$wlfltro=$wlfltro." and vp.id_tipotra=$tramite ";}
		if ($fechat!='') {$wlfltro=$wlfltro." and date(vp.fecha_altat)='$fechat' ";}
		if ($estatus!='') {$wlfltro=$wlfltro." and vp.estatus=$estatus ";}
			
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>".$php."</wlhoja>";
       	echo "<wlcampos>wlfltro=".$wlfltro."".htmlspecialchars("&")."relvante=".$relvante."".htmlspecialchars("&")."fecharecibo=".$fecharecibo.htmlspecialchars("&")."fecharecibofin=".$fecharecibofin.htmlspecialchars("&")."foliosunicos=".$foliosunicos.htmlspecialchars("&")."area=".$area.htmlspecialchars("&")."puesto=".$puesto.htmlspecialchars("&")."iterno=".$iterno.htmlspecialchars("&")."estatus=".$estatus.htmlspecialchars("&")."ventanilla=".$ventanilla.htmlspecialchars("&")."fechat=".$fechat."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";       	
   }
	/**
	*	funcion para obtener el codigo postal
	*/	
	function dameCP()
	{
		$wlidcolonia=$this->argumentos["wl_idcolonia"];	
		$sql = 	"	select cp from sicop.cat_colonias where idcolonia=".$wlidcolonia.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$cp=$row['cp'];
		echo "<enviavalor>$cp</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para validar si la cuenta predial existe
	*/
	function validaDatosUnicos ()
	{
		$wlctrapredial=$this->argumentos["wl_ctapredial"];
		if (strlen($wlctrapredial)>0)
		{
		$sql = 	"	select count (*) as reg from sicop.propiedades where ctapredial='".$wlctrapredial."';";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error al ejecutar qry ".$sql." ".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		if ($row['reg']>0)
		{
		echo "<error>La cuenta predial ya existe en la base de datos</error>";
		}
		}
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para determinar la vista que tiene que abrir desde archivos
	*/
	function abreSubvistaArchivos ()
	{
		$wlorigen=$this->argumentos["wl_idorigenarchivo"];
		$idarchivo=$this->argumentos["wl_idarchivo"];
		$wlpropiedad=$this->argumentos["wl_idpropiedad"];
		$sql = 	"	select * from sicop.cat_origenarchivo where idorigenarchivo=".$wlorigen.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error al ejecutar qry ".$sql." ".pg_last_error($this->connection)."</error>"; }
		$num = pg_numrows($sql_result);
		if 	($num>0)
		{
			$row = pg_fetch_array($sql_result, 0);
			$idmenu=$row['idmenu'];
			$sql2 = 	"	select ".$row['campo']." as campo from sicop.archivos where idarchivo=".$idarchivo.";";
			$sql2_result = pg_exec($this->connection,$sql2);
			if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error al ejecutar qry ".$sql2." ".pg_last_error($this->connection)."</error>"; }
			$row2 = pg_fetch_array($sql2_result, 0);
			$filtro="idpropiedad=".$wlpropiedad." and ".$row['campo']."=".$row2['campo'];
			//echo "<error>".$filtro."</error>";
			echo "<abresubvista></abresubvista>";
			echo "<wlhoja>man_menus.php</wlhoja>";
			echo "<wlcampos>idmenu=".$idmenu."".htmlspecialchars("&")."filtro=".$filtro."</wlcampos>";
			echo "<wldialogWidth>65</wldialogWidth>";
			echo "<wldialogHeight>35</wldialogHeight>";
		}
	}
	/**
	*	funcion para abrir la subvista de valores en avaluos
	*/
	function abreValores ()
	{
		//echo "<error>".$this->argumentos["iden"]."</error>	";
		echo "<abresubvista></abresubvista>";
		echo "<wlhoja>man_menus.php</wlhoja>";
		//echo "<wlcampos>idmenu=2047".htmlspecialchars("&")."wl_idavaluo_actu=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wlcampos>idmenu=2047".htmlspecialchars("&")."filtro=idavaluo=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wldialogWidth>65</wldialogWidth>";
		echo "<wldialogHeight>35</wldialogHeight>";
	}
	/**
	*	funcion para abrir la subvista de valores en avaluos en actualizaciones
	*/
	function abreValoresActu ()
	{
		//echo "<error>".$this->argumentos["iden"]."</error>	";
		echo "<abresubvista></abresubvista>";
		echo "<wlhoja>man_menus.php</wlhoja>";
		//echo "<wlcampos>idmenu=2048".htmlspecialchars("&")."wl_idavaluo_actu=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wlcampos>idmenu=2048".htmlspecialchars("&")."filtro=idavaluo_actu=".$this->argumentos["iden"]."</wlcampos>";
		echo "<wldialogWidth>65</wldialogWidth>";
		echo "<wldialogHeight>35</wldialogHeight>";
	}
	/**
	*	funcion para generar reporte de una visita en PDF
	*/	
	function visitasPDF()
	{
		$wlidvisita=$this->argumentos["wl_idvisita"];
		//echo "<error>$wlidvisita</error>	";
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>visitasPDF.php</wlhoja>";
       	echo "<wlcampos>wlidvisita=".$wlidvisita."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";       	
   }
	/**
	*	funcion para generar reporte topografico en PDF
	*/	
	function topografiaPDF()
	{
		$wlidtopografia=$this->argumentos["wl_idtopografia"];
		//echo "<error>$wlidvisita</error>	";
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>topografiaPDF.php</wlhoja>";
       	echo "<wlcampos>wlidtopografia=".$wlidtopografia."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";       	
   }
	/**
	*	funcion validar si el archivo debe ser una imagen
	*/	
	function validaExtArchivo()
	{      	
       	$wlidtipoarchivo=$this->argumentos["wl_idtipoarchivo"];	
		$sql = 	"	select img from sicop.cat_tiposarchivo where idtipoarchivo=".$wlidtipoarchivo.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$img=$row['img'];
		echo "<enviavalor>$img</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
   }
	/**
	*	obtiene el numero de inventario
	*/	
	function dameInventario() 
	{   
		//echo "<error>entro server</error>	";   	
       	$wlpropiedad=$this->argumentos["wl_idpropiedad"];
		$sql = 	"	select inventario from sicop.propiedades where idpropiedad=".$wlpropiedad.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$inventario=$row['inventario'];
		echo "<enviavalor>$inventario</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
   }
	/**
	*	genera un pdf cuendo el archivo es una imagen
	*/	
	function armaImgPdf() 
	{   
		$wlarchivo=$this->argumentos["wl_archivo"];
		if ($wlarchivo=='') {	$wlarchivo=$this->argumentos["wl_ficheroin"];	}
		//echo "<error>".$wlarchivo."</error>";
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>imgPDF.php</wlhoja>";
       	echo "<wlcampos>wlarchivo=".$wlarchivo."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";       	
	}
	/**
	*	Modifica la fecha de alta de un tramite
	*/	
	function ajustaFecha() 
	{   
		
		$connectionmaster = pg_connect("host='localhost' dbname='forapi' user='postgres' password='.,postok2.'") or die("Error con clave postgres");
		
		$wlfecha=$this->argumentos["wl_fecharecibo"];
		$wlfolioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql =
				"	DROP TRIGGER tu_gestion ON contra.gestion;	".
				"	DROP TRIGGER up_usename_fecha ON contra.gestion;	".
				"	DROP TRIGGER tu_ope_turnados ON contra.ope_turnados;	".
				"	DROP TRIGGER up_usename_fecha ON contra.ope_turnados;	".
				"	DROP TRIGGER tu_ope_archivos ON contra.ope_archivos;	".
				"	DROP TRIGGER up_usename_fecha ON contra.ope_archivos;	".
				"	update contra.gestion set fecha_alta=('".$wlfecha."'::date||substr (fecha_alta::text,11))::timestamp,fecha_modifico=('".$wlfecha."'::date||substr (fecha_alta::text,11))::timestamp where folioconsecutivo=".$wlfolioconsecutivo.";\n".
				"	update contra.ope_turnados set fecha_alta=('".$wlfecha."'::date||substr (fecha_alta::text,11))::timestamp,fecha_modifico=('".$wlfecha."'::date||substr (fecha_alta::text,11))::timestamp where folioconsecutivo=".$wlfolioconsecutivo.";".
				"	update contra.ope_archivos set fecha_alta=('".$wlfecha."'::date||substr (fecha_alta::text,11))::timestamp,fecha_modifico=('".$wlfecha."'::date||substr (fecha_alta::text,11))::timestamp where folioconsecutivo=".$wlfolioconsecutivo.";".
				"	CREATE TRIGGER tu_gestion BEFORE UPDATE ON contra.gestion FOR EACH ROW EXECUTE PROCEDURE tablas_cambios(); ".
				"	CREATE TRIGGER up_usename_fecha BEFORE UPDATE ON contra.gestion FOR EACH ROW EXECUTE PROCEDURE upa_usuario_fecha();	".
				"	CREATE TRIGGER tu_ope_turnados BEFORE UPDATE ON contra.ope_turnados FOR EACH ROW EXECUTE PROCEDURE tablas_cambios();	".
				"	CREATE TRIGGER up_usename_fecha BEFORE UPDATE ON contra.ope_turnados FOR EACH ROW EXECUTE PROCEDURE up_usuario_fecha();	".
				"	CREATE TRIGGER tu_ope_archivos BEFORE UPDATE ON contra.ope_archivos FOR EACH ROW EXECUTE PROCEDURE tablas_cambios();	".
				"	CREATE TRIGGER up_usename_fecha  BEFORE UPDATE ON contra.ope_archivos FOR EACH ROW EXECUTE PROCEDURE upa_usuario_fecha();	";
		$sql_result = pg_exec($connectionmaster,$sql);
		if (strlen(pg_last_error($connectionmaster))>0) { echo "<error>".pg_last_error($connectionmaster)."</error>"; }
		echo "<error>Cambio efectuado</error>";
		pg_close ($connectionmaster);
	}
	/**
	*	funcion para generar reporte en excel de control de tramites
	*/	
	function reportesExcel()
	{
		$idregcambio=$this->argumentos["wl_idregcambio"];
		$idtiporeporte=$this->argumentos["wl_idtiporeporte"];
		$fecharecibo=$this->argumentos["wl_fecharecibo"];
		$fecharecibofin=$this->argumentos["wl_fecharecibo_fin"];
		$relvante=$this->argumentos["wl_relevante"];
		$organizacion=$this->argumentos["wl_id_organizacion"];
		$puesto=$this->argumentos["wl_id_puesto"];
		$persona=$this->argumentos["wl_id_persona"];
		$tramite=$this->argumentos["wl_id_tipotra"];
		$fechat=$this->argumentos["wl_fecha_turno"];
		$area=$this->argumentos["wl_id_puesto_direccion"];
		$foliosunicos=$this->argumentos["wl_foliosunicos"];
		$estatus=$this->argumentos["wl_estatus"];
		$estatus=$this->argumentos["wl_estatus"];
		$ventanilla=$this->argumentos["wl_ventanilla"];
		//echo "<error>".$idtiporeporte."</error>";
		$sql = 	"	select php,interno from contra.cat_tiporeportes where idtiporeporte=".$idtiporeporte.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$iterno=$row['interno'];
		//echo "<error>".$iterno."</error>";
		
		
		$wlfltro=	" and v.id_organizacion=$organizacion and v.fecharecibo between '$fecharecibo' and '$fecharecibofin' ";
					
		if ($area!='') {$wlfltro=$wlfltro." and v.id_puesto in (select id_puesto from contra.cat_puestos where id_puesto_direccion=".$area.")";}
		if ($relvante=='t' ) {$wlfltro=$wlfltro." and v.relevante is true ";}
		if ($puesto!='') {$wlfltro=$wlfltro." and v.id_puesto=$puesto ";}
		if ($persona!='') {$wlfltro=$wlfltro." and v.id_persona=$persona ";}
		if ($tramite!='') {$wlfltro=$wlfltro." and v.id_tipotra=$tramite ";}
		if ($fechat!='') {$wlfltro=$wlfltro." and date(v.fecha_altat)='$fechat' ";}
		if ($estatus!='') {$wlfltro=$wlfltro." and v.estatus=$estatus ";}
		//PENDIENTES
		if ($idtiporeporte==1 || $idtiporeporte==3)
		{
			$sql =	"	select v.folio as \"FOLIO\",	".
					"	v.referencia as \"OFICIO\",	".
					"	cpeev.nombre_completo as \"PERSONA QUE ENVIA\",	".
					"	cpere.nombre_completo as \"DESTINATARIO\",	".
					"	v.fechadocumento as \"FECHA DEL DOCUMENTO\",	".
					"	v.fecharecibo as \"FECHA DE RECEPCION\",	".
					"	v.diastermino as \"DIAS DE TERMINO\",	".
					"	case when relevante is true then 'Si' else 'No' end as \"RELEVANTE\",	".
					"	ca.descripcion as \"CLAVE DEL ASUNTO\",	".
					"	v.asunto as \"ASUNTO\",	".
					"	cpe.nombre_completo \"PERSONA EN TURNO\",	".
					"	cp.descripcion as \"PUESTO\",	".
					"	ctt.descripcion as \"TIPO DE TRAMITE\",	".
					"	v.liberado as \"LIBERADO\",	".
					"	v.fecha_altat as \"FECHA DEL TURNO\",	".
					"	v.usuario_altat as \"USUARIO QUIEN TURNO\",	".
					"	v.estatus as \"ESTATUS\"	".
					"	from contra.v_pendientes".(($iterno=="t") ? "_interno" : "" )." as v	".
					"	left join contra.cat_organizaciones as co on co.id_organizacion=v.id_organizacion	".
					"	left join contra.cat_puestos as cp on cp.id_puesto=v.id_puesto	".
					"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=v.id_tipotra	".
					"	left join contra.v_cat_personas as cpe on cpe.id_persona=v.id_persona	".
					"	left join contra.v_cat_personas as cpeev on cpeev.id_persona=v.idpersona_envia	".
					"	left join contra.v_cat_personas as cpere on cpere.id_persona=v.idpersona_recibe	".
					"	left join contra.cat_asuntos as ca on ca.id_cveasunto=v.id_cveasunto	".
					"	where v.id_organizacion=(select id_organizacion from contra.cat_personas where usename=current_user) ".
					"	and liberado='N'	".
					"	$wlfltro ".
					"	order by 6,1";
		//FOLIOS
		} else if ($idtiporeporte==2  || $idtiporeporte==4)
		{
			$sql = 	"	select ".(($foliosunicos=="t") ? "distinct" : "" )." folio as \"FOLIO\",	".
					(($foliosunicos=="t") ? "" : " hora as \"HORA\",	vom as \"VOM\",	" ).
					"		oficio as \"OFICIO\",	".
					"		fechadocumento as \"FECHA DE DOCUMENTO\",	".
					"		fecharecibo as \"FECHA DE RECEPCION\",	".
					"		persona_envia as \"PERSONA QUE ENVIA\",".
					"		organizacion_envia as \"ORGANIZACION\",	".
					"		destinatario as \"DESTINATARIO\",	".
					"		anexos as \"ANEXOS\",	".
					"		asunto as \"ASUNTO\"	".
					(($foliosunicos=="t") ? "" : " , persona_turno as \"PERSONA EN TURNO\", tramite as \"TRAMITE\", liberado as \"LIBERADO\", fecha_alta as \"FECHA DE TURNO\", usuario_altat as \"USUARIO QUIEN TURNO\"	").
					"	from contra.v_folios".($iterno=="t" ? "_interno" : "" )." v ".
					"	where folio is not null	".
					($ventanilla=="t" ? "	and v.usuario_altat in ".
										"	(	select usename from cat_usuarios	".
										"	where usename in (	".
										"	select usename 	".
										"	from cat_usuarios_pg_group as capg	".
										"	left join pg_group as pg on  pg.grosysid=capg.grosysid	".
										"	where groname  in ('contra_ventanilla','contra_ventanilla_admon')))	" : "" ).
					"	".$wlfltro." order by 1 ";
		//LIBERADOS
		} else 	if ($idtiporeporte==5 || $idtiporeporte==7)
		{
			$sql =	"	select v.folio as \"FOLIO\",	".
					"	v.referencia as \"OFICIO\",	".
					"	cpeev.nombre_completo as \"PERSONA QUE ENVIA\",	".
					"	cpere.nombre_completo as \"DESTINATARIO\",	".
					"	v.fechadocumento as \"FECHA DEL DOCUMENTO\",	".
					"	v.fecharecibo as \"FECHA DE RECEPCION\",	".
					"	v.diastermino as \"DIAS DE TERMINO\",	".
					"	case when relevante is true then 'Si' else 'No' end as \"RELEVANTE\",	".
					"	ca.descripcion as \"CLAVE DEL ASUNTO\",	".
					"	v.asunto as \"ASUNTO\",	".
					"	v.fecha_altat as \"FECHA DEL TURNO\",	".
					"	cpe.nombre_completo \"PERSONA EN TURNO\",	".
					"	cp.descripcion as \"PUESTO\",	".
					"	ctt.descripcion as \"TIPO DE TRAMITE\",	".
					"	v.liberado as \"LIBERADO\",	".
					"	v.fecha_altat as \"FECHA DEL TURNO\",	".
					"	v.usuario_altat as \"USUARIO QUIEN TURNO\"	".
					"	from contra.v_pendientes".(($iterno=="t") ? "_interno" : "" )." as v	".
					"	left join contra.cat_organizaciones as co on co.id_organizacion=v.id_organizacion	".
					"	left join contra.cat_puestos as cp on cp.id_puesto=v.id_puesto	".
					"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=v.id_tipotra	".
					"	left join contra.v_cat_personas as cpe on cpe.id_persona=v.id_persona	".
					"	left join contra.v_cat_personas as cpeev on cpeev.id_persona=v.idpersona_envia	".
					"	left join contra.v_cat_personas as cpere on cpere.id_persona=v.idpersona_recibe	".
					"	left join contra.cat_asuntos as ca on ca.id_cveasunto=v.id_cveasunto	".
					"	where v.id_organizacion=(select id_organizacion from contra.cat_personas where usename=current_user) and v.respuesta is true	".
					"	and liberado='S'	".
					"	$wlfltro ".
					"	order by 6,1";
		//TURNADOS
		} else if ($idtiporeporte==6  || $idtiporeporte==8)
		{
			$sql = 	"	select ".(($foliosunicos=="t") ? "distinct" : "" )." folio as \"FOLIO\",	".
					(($foliosunicos=="t") ? "" : " hora as \"HORA\",	vom as \"VOM\",	" ).
					"		vom as \"VOM\",	".
					"		oficio as \"OFICIO\",	".
					"		fechadocumento as \"FECHA DE DOCUMENTO\",	".
					"		fecharecibo as \"FECHA DE RECEPCION\",	".
					"		persona_envia as \"PERSONA QUE ENVIA\",".
					"		organizacion_envia as \"ORGANIZACION\",	".
					"		destinatario as \"DESTINATARIO\",	".
					"		anexos as \"ANEXOS\",	".
					"		asunto as \"ASUNTO\"	".
					(($foliosunicos=="t") ? "" : " , persona_turno as \"PERSONA EN TURNO\", tramite as \"TRAMITE\", liberado as \"LIBERADO\", fecha_alta as \"FECHA DE TURNO\", usuario_altat as \"USUARIO QUIEN TURNO\"	").
					"	from contra.v_folios".($iterno=="t" ? "_interno" : "" )." v ".
					"	where folio is not null ".
					($ventanilla=="t" ? "	and v.usuario_altat in ".
										"	(	select usename from cat_usuarios	".
										"	where usename in (	".
										"	select usename 	".
										"	from cat_usuarios_pg_group as capg	".
										"	left join pg_group as pg on  pg.grosysid=capg.grosysid	".
										"	where groname  in ('contra_ventanilla','contra_ventanilla_admon')))	" : " and v.usuario_altat=current_user " ).
					"	".$wlfltro." order by 1 ";
		}
		$handle = fopen ("ficheros/sql.sql", "w");
		if (!fwrite ($handle,$sql)) { echo "no pudo escribir archivo"; }
		fclose($handle);
		//echo "<error>".$sql."</error>";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$num = pg_numrows($sql_result);
		if ($num==0)
		{	echo "<error>No se encontraron registros</error>";	}
		else
		{	$this->generaExcel($sql_result,$num,$_SESSION["parametro1"],'');
			echo '<generaexcel>Consulta efectuada '.$num.' registros</generaexcel>';
       		echo '<archivo>ficheros/consulta_'.$_SESSION['parametro1'].'.xls.gz</archivo>';	}
   }
	/**
	*	funcion para revisar si el folio relacionado quie se quiere registerar existe
	*/	
	function validaAltaReferencia()   
	{
		//echo "<error>entro server</error>	";   	
       	$wlvalor=$this->argumentos["wl_valor"];
       	$wlanio=$this->argumentos["wl_anio"];
		$sql = 	"	select count (*) from contra.gestion where folio::numeric='".$wlvalor."'::numeric and anio=".$wlanio.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['count'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si el folio relacionado quie se quiere registerar existe
	*/	
	function validaAltaReferenciaInterno()   
	{
		//echo "<error>entro server</error>	";   	
       	$wlvalor=$this->argumentos["wl_valor"];
       	$wlanio=$this->argumentos["wl_anio"];
		$sql = 	"	select count (*) from contra.gestion_interno where folio::numeric='".$wlvalor."'::numeric and anio=".$wlanio.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['count'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si el folio relacionado quie se quiere registerar esta cerrado
	*/	
	function validaAltaReferenciaCerrado()   
	{
		//echo "<error>entro server</error>	";   	
       	$wlvalor=$this->argumentos["wl_valor"];
       	$wlanio=$this->argumentos["wl_anio"];
		$sql = 	"	select count (*) from contra.gestion where folio::numeric='".$wlvalor."'::numeric and anio=".$wlanio." and estatus=3;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['count'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para revisar si el folio relacionado quie se quiere registerar esta cerrado
	*/	
	function validaAltaReferenciaCerradoInterno()   
	{
		//echo "<error>entro server</error>	";   	
       	$wlvalor=$this->argumentos["wl_valor"];
       	$wlanio=$this->argumentos["wl_anio"];
		$sql = 	"	select count (*) from contra.gestion_interno where folio::numeric='".$wlvalor."'::numeric and anio=".$wlanio." and estatus=3;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['count'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para obtener los numeros de folios relacionados con un tramite
	*/	
	function dameReferencias()
	{
		$wlfolioconsecutivo=$this->argumentos["wl_folioconsecutivo"];	
		$sql = 	"	select lpad(valor,5,'0') as valor from contra.ope_referencias where folioconsecutivo=".$wlfolioconsecutivo." and id_tiporef=4;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$num = pg_numrows($sql_result);
		$folios='';
		if ($num>0)
		{
			$row = pg_fetch_array($sql_result, 0);	
			$folios=$row['valor'];
			for ($i=1; $i<$num; $i++)
			{
				$row = pg_fetch_array($sql_result, $i);	
				$folios=$folios.", ".$row['valor'];
			}
			
		}
		echo "<enviavalor>$folios</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	*	funcion para obtener los numeros de folios relacionados con un tramite interno
	*/	
	function dameReferenciasInterno()
	{
		$wlfolioconsecutivo=$this->argumentos["wl_folioconsecutivo"];	
		$sql = 	"	select lpad(valor,5,'0') as valor from contra.ope_referencias_interno where folioconsecutivo=".$wlfolioconsecutivo." and id_tiporef=4;";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$num = pg_numrows($sql_result);
		$folios='';
		if ($num>0)
		{
			$row = pg_fetch_array($sql_result, 0);	
			$folios=$row['valor'];
			for ($i=1; $i<$num; $i++)
			{
				$row = pg_fetch_array($sql_result, $i);	
				$folios=$folios.", ".$row['valor'];
			}
			
		}
		echo "<enviavalor>$folios</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	function generaGrafica()
	{
		$idregcambio=$this->argumentos["wl_idregcambio"];
		$idtiporeporte=$this->argumentos["wl_idtiporeporte"];
		$fecharecibo=$this->argumentos["wl_fecharecibo"];
		$fecharecibofin=$this->argumentos["wl_fecharecibo_fin"];
		$relvante=$this->argumentos["wl_relevante"];
		$organizacion=$this->argumentos["wl_id_organizacion"];
		$puesto=$this->argumentos["wl_id_puesto"];
		$persona=$this->argumentos["wl_id_persona"];
		$tramite=$this->argumentos["wl_id_tipotra"];
		$fechat=$this->argumentos["wl_fecha_turno"];
		$area=$this->argumentos["wl_id_puesto_direccion"];
		$foliosunicos=$this->argumentos["wl_foliosunicos"];
		$estatus=$this->argumentos["wl_estatus"];
		$estatus=$this->argumentos["wl_estatus"];
		$ventanilla=$this->argumentos["wl_ventanilla"];
		//echo "<error>".$idtiporeporte."</error>";
		$sql = 	"	select php,interno from contra.cat_tiporeportes where idtiporeporte=".$idtiporeporte.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$iterno=$row['interno'];
		//echo "<error>".$iterno."</error>";
				
		$wlfltro=	" and v.id_organizacion=$organizacion and v.fecharecibo between '$fecharecibo' and '$fecharecibofin' ";
					
		if ($area!='') {$wlfltro=$wlfltro." and v.id_puesto in (select id_puesto from contra.cat_puestos where id_puesto_direccion=".$area.")";}
		if ($relvante=='t' ) {$wlfltro=$wlfltro." and v.relevante is true ";}
		if ($puesto!='') {$wlfltro=$wlfltro." and v.id_puesto=$puesto ";}
		if ($persona!='') {$wlfltro=$wlfltro." and v.id_persona=$persona ";}
		if ($tramite!='') {$wlfltro=$wlfltro." and v.id_tipotra=$tramite ";}
		if ($fechat!='') {$wlfltro=$wlfltro." and date(v.fecha_altat)='$fechat' ";}
		if ($estatus!='') {$wlfltro=$wlfltro." and v.estatus=$estatus ";}
		
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>grafica.php</wlhoja>";
       	echo "<wlcampos>wlfltro=".$wlfltro."".htmlspecialchars("&")."relvante=".$relvante."".htmlspecialchars("&")."fecharecibo=".$fecharecibo.htmlspecialchars("&")."fecharecibofin=".$fecharecibofin.htmlspecialchars("&")."foliosunicos=".$foliosunicos.htmlspecialchars("&")."area=".$area.htmlspecialchars("&")."puesto=".$puesto.htmlspecialchars("&")."iterno=".$iterno.htmlspecialchars("&")."estatus=".$estatus.htmlspecialchars("&")."ventanilla=".$ventanilla.htmlspecialchars("&")."persona=".$persona.$ventanilla.htmlspecialchars("&")."idtiporeporte=".$idtiporeporte."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";
	}
	/**
	* funcion para actualizar el oficio de contestacion
	*/	
	function actualizaOficio()   
	{
       	$wlidcontestacion=$this->argumentos["wl_idcontestacion"];
		$sql = 	"	select referencia_c from contra.gestion_contestacion idcontestacion".$wlidcontestacion.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['referencia_c'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	* funcion para validar el ultimo turno de una gestion
	*/	
	function validaTurno()   
	{
       	$wlfolioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	"	select ctt.respuesta	".
				"	from contra.ope_turnados as t	".
				"	left join contra.cat_tipo_tramite as ctt on ctt.id_tipotra=t.id_tipotra	".
				"	where folioconsecutivo=".$wlfolioconsecutivo."	".
				"	order by t.usuario_alta desc	".
				"	limit 1	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['respuesta'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	* funcion para validar el ultimo turno de una gestion
	*/	
	function validaTurnoSeguimiento()   
	{
       	$wlfolioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	"	select count (*) as reg from contra.ope_turnados as t	".
				"	where folioconsecutivo=".$wlfolioconsecutivo."	".
				"	and id_persona =(select id_persona from contra.cat_personas where usename=current_user)	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['reg'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	* funcion para validar el ultimo turno de una gestion
	*/	
	function validaTurnoSeguimientoInterno()   
	{
       	$wlfolioconsecutivo=$this->argumentos["wl_folioconsecutivo"];
		$sql = 	"	select count (*) as reg from contra.ope_turnados_interno as t	".
				"	where folioconsecutivo=".$wlfolioconsecutivo."	".
				"	and id_persona =(select id_persona from contra.cat_personas where usename=current_user)	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$reg=$row['reg'];
		echo "<enviavalor>$reg</enviavalor>";
		echo "<__eventocontinua>true</__eventocontinua>";
	}
	/**
	* funcion para la impresion de los documentos de contestacion
	*/	
	function generaContestacion()   
	{
		$wlidcontestacion=$this->argumentos["wl_idcontestacion"];
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>contestacionPDF.php</wlhoja>";
       	echo "<wlcampos>wlfltro=".$wlfltro."".htmlspecialchars("&")."wlidcontestacion=".$wlidcontestacion."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";
	}
	/**
	* funcion para generar los valores para el modulo de avaluos
	*/	
	function generaValores()   
	{
       	$sql = 	"	select avaluos.genera_valores();	";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) 
		{ echo "<error>".pg_last_error($this->connection)."</error>"; }
		else
		{ echo "<error>Los valores se actualizaron</error>"; }
	}
	/**
	*	funcion para generar reporte de la agenda en pdf
	*/	
	function reportesAgendaPDF()
	{
		$idtiporeporte=$this->argumentos["wl_idtiporeporte"];
		//echo "<error>".$idtiporeporte."</error>";
		$sql = 	"	select php from agenda.cat_tiporeportes where idtiporeporte=".$idtiporeporte.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$php=$row['php'];
		//echo "<error>".$php."</error>";
		$fecha_inicio=$this->argumentos["wl_fecha_inicio"];
		$fecha_fin=$this->argumentos["wl_fecha_fin"];
		$estatus=$this->argumentos["wl_idestatus"];
		
		$wlfltro =	" where fecha between '".$fecha_inicio."' and '".$fecha_fin."'	";
		if ($estatus!='' && $idtiporeporte<>3) {$wlfltro=$wlfltro." and idestatus=$estatus ";}
			
       	echo "<abresubvista></abresubvista>";
       	echo "<wlhoja>".$php."</wlhoja>";
       	echo "<wlcampos>wlfltro=".$wlfltro."".htmlspecialchars("&")."fecha_inicio=".$fecha_inicio.htmlspecialchars("&")."fecha_fin=".$fecha_fin.htmlspecialchars("&")."estatus=".$estatus."</wlcampos>";
       	echo "<wldialogWidth>50</wldialogWidth>";
       	echo "<wldialogHeight>30</wldialogHeight>";       	
   }
	/**
	*	funcion para generar reporte de la agenda en excel
	*/	
	function reportesAgendaExcel()
	{
		$idtiporeporte=$this->argumentos["wl_idtiporeporte"];
		//echo "<error>".$idtiporeporte."</error>";
		$sql = 	"	select php from agenda.cat_tiporeportes where idtiporeporte=".$idtiporeporte.";";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$row = pg_fetch_array($sql_result, 0);
		$php=$row['php'];
		//echo "<error>".$php."</error>";
		$fecha_inicio=$this->argumentos["wl_fecha_inicio"];
		$fecha_fin=$this->argumentos["wl_fecha_fin"];
		$estatus=$this->argumentos["wl_idestatus"];
		
		$wlfltro =	" where fecha between '".$fecha_inicio."' and '".$fecha_fin."'	";
		if ($estatus!='' && $idtiporeporte<>3) {$wlfltro=$wlfltro." and idestatus=$estatus ";}
		
		//CITAS	
		if ($idtiporeporte==1)
		{
			$sql =	"	select * from agenda.v_citas	".
					"	$wlfltro order by fecha, hora";
		//JUNTAS
		} else if ($idtiporeporte==2)
		{
			$sql =	"	select * from agenda.v_juntas	".
					"	$wlfltro order by fecha, hora";
		//LLAMADAS
		} else if ($idtiporeporte==2)
		{
			$sql =	"	select * from agenda.v_llamadas	".
					"	$wlfltro order by fecha, hora";
		}
       	
		$handle = fopen ("ficheros/sql.sql", "w");
		if (!fwrite ($handle,$sql)) { echo "no pudo escribir archivo"; }
		fclose($handle);
		//echo "<error>".$sql."</error>";
		$sql_result = pg_exec($this->connection,$sql);
		if (strlen(pg_last_error($this->connection))>0) { echo "<error>".pg_last_error($this->connection)."</error>"; }
		$num = pg_numrows($sql_result);
		if ($num==0)
		{	echo "<error>No se encontraron registros</error>";	}
		else
		{	$this->generaExcel($sql_result,$num,$_SESSION["parametro1"],'');
			echo '<generaexcel>Consulta efectuada '.$num.' registros</generaexcel>';
       		echo '<archivo>ficheros/consulta_'.$_SESSION['parametro1'].'.xls.gz</archivo>';	}
   }
}

?>
