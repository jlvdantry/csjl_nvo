<?php
        session_start() ;	
	if (isset($_POST['id'])) {
		if (!copy($_FILES[$_POST['id']]['tmp_name'], 'upload_ficheros/'.$_FILES[$_POST['id']]['name'])) {
			echo '<script> alert("Failed to upload file");</script>';
		}
		else
		{ session_register($upload_archivo);$upload_archivo=$_FILES[$_POST['id']]['name'];}
	}
	else {
	       if (isset($upload_archivo))
	       {
		    include("conneccion.php");
                    $sql =" insert into menus_archivos (descripcion) values ('".$upload_archivo."');";
                    $sql_result = pg_exec($connection,$sql)
                                  or die("Couldn't make query. ".$sql );
		    $sql =" select currval(pg_get_serial_sequence('menus_archivos', 'idarchivo'));";
		    $sql_result = pg_exec($connection,$sql)
		                  or die("Couldn't make query. " );
				   $Row = pg_fetch_array($sql_result, 0);
			    $wlopcion="cerrar";
	            echo "subio archivo el registo que inserto fue".$Row[0]."archivo=".$upload_archivo;
		    unset($upload_archivo);
		}
//		echo '<script>alert("'.print_r($_SESSION)."');";
//		echo "File uploaded".$_POST['id'];
//		echo '<script>alert("subio el archivo");</script>';
	}
?>
