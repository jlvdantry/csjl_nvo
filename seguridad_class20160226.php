<?php
require_once("class_men.php");
require_once("xmlhttp_class.php");
require_once("eventos_servidor_class.php");
/**
  *  valida la seguridad
  *  @package forapi
  */
class seguridad extends xmlhttp_class
{
	    /**
	      *   Autoriza a un usuario utilizar el sistema
	      **/
		var $servidort;
		var $badat;
		var $puerto;
		function permisos()
		{
			if($this->argumentos['wl_usename']=="")
			{
				echo "<error>No esta definido el usuario a reasignar permisos</error>";
				return;
			}
            $sql="select autoriza_usuario('".$this->argumentos['wl_usename']."')";
            $sql_result = @pg_exec($this->connection,$sql);
        	if (strlen(pg_last_error($this->connection))>0)
        	{
        				echo "<error>Error al autorizar permisos</error>";	        		
        				return;
        	}            
	  		$row=pg_fetch_array($sql_result, 0);                                          
			echo "<error>".$row[0]."</error>";
		}
	    /**
	      *   Actualiza los permisos de un grupo  20070608
		**/
		function permisosgrupo()
		{
			if($this->argumentos['wl_grosysid']=="")
			{
				echo "<error>No esta definido el grupo a reasignar permisos</error>";
				return;
			}
            $sql="select autoriza_usuario(cu.usename) from cat_usuarios_pg_group as pg	".
            	"	join cat_usuarios as cu on (cu.usename=pg.usename)	".
            	"	where grosysid='".$this->argumentos['wl_grosysid']."' and estatus=1;";
            $sql_result = @pg_exec($this->connection,$sql);
        	if (strlen(pg_last_error($this->connection))>0)
        	{
        				echo "<error>Error al autorizar permisos del grupo".$sql."</error>";	        		
        				return;
        	}            
	  		$row=pg_fetch_array($sql_result, 0);                                          
			echo "<error>".$row[0]."</error>";
		}		
		
		/**
		  *  Valida si el usuario esta autorizado de utilizar el sistema
		  **/
		function validausuario()
		{
			if ($this->argumentos["wl_password"]=="")
    		{
    		  	echo "<error>No esta definido el password</error>";
    	  		return; 
    		}				
    	
			if ($this->argumentos["wl_usuario"]=="")
    		{
    	  		echo "<error>No esta definido el usuario</error>";
    	  		return; 
    		}

    		$parametro1p=$this->argumentos["wl_usuario"];
    		$parametro2p=$this->argumentos["wl_password"];
          	$cd = @pg_connect("host=$this->servidort dbname=$this->badat  user=$parametro1p  password=$parametro2p port=$this->puerto"); //20070822

          	if ( $cd == "" )
          	{
    	    	unset($parametro2); 	unset($parametro1);  	unset($parametro2f);
             	unset($parametro1f);   	unset($paragrupo);    	unset($wlserial);
             	require("conneccion.php");
             	$es = new eventos_servidor_class();
             	$es->connection=$connection;
             	$wlmensaje=$es->cuenta_errores($parametro1p);
             	if ($wlmensaje!="")
             	{
	             	
	             	switch ($wlmensaje)
	             	{
		             	case "No se bloqueo el usuario":
		             		$es->salida($wlmensaje);
		             		break;
		             	default:
		             		echo "<error>".$wlmensaje."</error>";		             	
	             	}
	             	return;
            	}
             	echo "<error>No se pudo conectar el usuario ".$parametro1p."</error>";
             	return;
       	  	}
       	  	
       	  $this->connection=$cd;	
          $sql ="SELECT estatus_usuario('".$parametro1p."');";
	   	  $sql_result = @pg_exec($this->connection,$sql);
       	  if (strlen(pg_last_error($this->connection))>0)
       	  {
       			echo "<error> al validausuario".$sql." error ".pg_last_error($this->connection)."</error>";
       			return;   // 20070327
       	  }                                          
    	  $Row = pg_fetch_array($sql_result, 0); 
		  if ($Row[0]!="")       		
		  {
             	echo "<error>".$Row[0]."</error>";
             	return;			  
		  }

/*   aqui es donde se pone los dias de antiguedad que debe de tener el cambio de pwd */		  
          $sql ="SELECT debe_cambiarpwd('".$parametro1p."',190);";
	   	  $sql_result = @pg_exec($this->connection,$sql);
       	  if (strlen(pg_last_error($this->connection))>0)
       	  {
       			echo  "<error>Error al debe_cambiarpwd ".pg_last_error($this->connection)."-".print_r($_SESSION)."</error>";
       			return ;
       	  }                                          
    	  $Row = pg_fetch_array($sql_result, 0); 
		  if ($Row[0]!="")       		
		  {
	             	switch ($Row[0])
	             	{
		             	case "Usuario debe cambia pwd":
//							echo "<abresubvista>man_menus.php?idmenu=1025</abresubvista>";		
          					session_register("parametro1");
          					session_register("parametro2");
          					session_register("servidor");
          					session_register("bada");          					
          					session_register("puerto");          					
          					$_SESSION["parametro1"]=$parametro1p;
          					$_SESSION["parametro2"]=$parametro2p;
          					$_SESSION["servidor"]=$this->servidort;
          					$_SESSION["bada"]=$this->badat;  //20070822
          					$_SESSION["puerto"]=$this->puerto;  //20070822
						echo "<abresubvista></abresubvista>";
						echo "<wlhoja>man_menus.php</wlhoja>";
						echo "<wlcampos>idmenu=1025".htmlspecialchars("&")."filtro=usename='".$parametro1p."'</wlcampos>";
						echo "<wldialogWidth>50</wldialogWidth>";
						echo "<wldialogHeight>30</wldialogHeight>";		    	  		
		             		break;
		             	default:
		             		echo "<error>1".$Row[0]."</error>";		             	
	             	}
	             	return;
		  }		  
		  		  
          session_register("servidor");
          session_register("bada");
          session_register("parametro1");
          session_register("parametro2");
          session_register("puerto");
          session_register("servidorf");
          session_register("badaf");
          session_register("parametro1f");
          session_register("parametro2f");
          session_register("servidori");
          session_register("badai");
          session_register("parametro1i");
          session_register("parametro2i");
          session_register("paragrupo");
          $_SESSION["parametro1"]=$parametro1p;
          $_SESSION["parametro2"]=$parametro2p;
          $_SESSION["servidor"]=$this->servidort; //20070822
          $_SESSION["bada"]=$this->badat; //20070822
          $_SESSION["puerto"]=$this->puerto; //20070822
			echo "<otrahoja>opciones_antn.php</otrahoja>";		
		}
}		

	if (isset($opcion))
	{
		session_start();
		include("conneccion.php");
		$va = new seguridad();
		$va->connection = $connection;
##20071105		$va->argumentos = $_GET;
		$va->argumentos = $_POST;		##20071105
		$va->funcion = $opcion;
		$va->servidort = $wlhost;  //20070822
		$va->badat= $wldbname;  //20070822
		$va->puerto= $wlport;  //20070822
		
##		print_r($va->argumentos);
		$va->procesa();		
	}
	
?>
