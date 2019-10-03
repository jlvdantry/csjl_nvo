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
                                        $this->Enviaemail("Error al autorizar permisos ".$sql." error ".pg_last_error($this->connection));
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
                                        $this->Enviaemail("error en validausuario".$sql." error ".pg_last_error($this->connection));
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
    	
			if ($this->argumentos["wl_usename"]=="")
    		{
    	  		echo "<error>No esta definido el usuario</error>";
    	  		return; 
    		}

    		$parametro1p=$this->argumentos["wl_usename"];
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
          $sql ="SELECT forapi.estatus_usuario('".$parametro1p."');";
	   	  $sql_result = @pg_exec($this->connection,$sql);
       	  if (strlen(pg_last_error($this->connection))>0)
       	  {
       			echo "<error>Error al validausuario</error>";
       			//echo "<error>Error al validausuario".pg_last_error($this->connection)."</error>";
                        $this->Enviaemail("error en validausuario usuario=".$parametro1p." sql=".$sql." error ".pg_last_error($this->connection));
       			return;  
       	  }                                          
    	  $Row = pg_fetch_array($sql_result, 0); 
		  if ($Row[0]!="")       		
		  {
             	echo "<error>".$Row[0]."</error>";
             	return;			  
		  }

          $sql ="SELECT forapi.debe_cambiarpwd('".$parametro1p."',210);";
	   	  $sql_result = @pg_exec($this->connection,$sql);
       	  if (strlen(pg_last_error($this->connection))>0)
       	  {
       			//echo  "<error>Error al debe_cambiarpwd </error>";
       			echo  "<error>Error al debe_cambiarpwd ".pg_last_error($this->connection)."</error>";
                        $this->Enviaemail("Error al debe_cambiarpwd ".$sql." error ".pg_last_error($this->connection));
       			return ;
       	  }                                          
    	  $Row = pg_fetch_array($sql_result, 0); 
		  if ($Row[0]!="")       		
		  {
	             	switch ($Row[0])
	             	{
		             	case "Usuario debe cambia pwd":
          					$_SESSION["parametro1"]=$parametro1p;
          					$_SESSION["parametro2"]=$parametro2p;
          					$_SESSION["servidor"]=$this->servidort;
          					$_SESSION["bada"]=$this->badat;  //20070822
          					$_SESSION["puerto"]=$this->puerto;  //20070822
						echo "<abresubvista></abresubvista>";
						echo "<wlhoja>man_menus.php</wlhoja>";
						echo "<wlcampos>idmenu=1025".htmlspecialchars("&")."filtro=usename='".$parametro1p."'</wlcampos>";
						echo "<wldialogWidth>800</wldialogWidth>";
						echo "<wldialogHeight>400</wldialogHeight>";		    	  		
		             		break;
		             	default:
		             		echo "<error>1".$Row[0]."</error>";		             	
	             	}
	             	return;
		  }		  
		  		  
          $_SESSION["parametro1"]=$parametro1p;
          $_SESSION["parametro2"]=$parametro2p;
          $_SESSION["servidor"]=$this->servidort; //20070822
          $_SESSION["bada"]=$this->badat; //20070822
          $_SESSION["puerto"]=$this->puerto; //20070822
			echo "<otrahoja>opciones_antn.php</otrahoja>";		
		}
}		

	if (isset($_POST['opcion']))
	{
		include("conneccion.php");
		$va = new seguridad();
		$va->connection = $connection;
		$va->argumentos = $_POST;		##20071105
		$va->funcion = $_POST['opcion'];
		$va->servidort = $wlhost;  //20070822
		$va->badat= $wldbname;  //20070822
		$va->puerto= $wlport;  //20070822
		$va->procesa();		
	}
	
?>
