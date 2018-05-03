<?php
   session_register("escron");
   $escron='si';
   ini_set('display_errors','On');
   echo "empezo\n";
   $t=getdate();
   $today=date('Y-m-d h:i:s',$t[0]);
   error_log($today." Empezo busca lineas\n",3,"/var/tmp/buscalineafinanzas.log");
   error_reporting(E_ALL);
   include("conneccion.php");
   include("class_validalinea.php");
   $x= new class_validalinea();
        $sql="select folioconsecutivo,lc,intentosval,fecha_alta,folio from contra.gestion where val_lc in(4,1) and lc!='' and intentosval<6 ".
             " and fecha_alta<current_date - integer '0' order by fecha_alta limit 500 ";
##        $sql="select folioconsecutivo,lc,intentosval,fecha_alta,folio from contra.gestion where lc='77273301146377YM5T4M' ";
        $sql_result = pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
        $num= pg_numrows ($sql_result);
        echo "encontro num=".$num."\n";
        for ($i=0; $i<$num; $i++)
        {
             $row=pg_fetch_array($sql_result,$i);
             $x->lc=$row["lc"];
             $pos=$x->validapago();
             echo "encontro reg=".$i." linea=".$row["lc"]." pos=".count($pos)." folio=".$row["folio"]." lcf=".$pos[0]['lineacaptura']."\n";
             $x->marcapago($pos,$row["folioconsecutivo"],$connection);
             $today=date('Y-m-d h:i:s',$t[0]);
             error_log($today." Resultado folio=".$row["folio"]." lc=".$row["lc"]." pos=".count($pos)." intentos=".$row["intentosval"]." registro=".$i." fecha alta=".$row["fecha_alta"]."\n",3,"/var/tmp/buscalineafinanzas.log");
        }

        $sql="select pagos.folioconsecutivo,pagos.lc,pagos.intentosval,pagos.fecha_alta,folio,id from contra.gestion,contra.pagos ".
             " where pagos.val_lc in(4,1) and pagos.lc!='' and pagos.intentosval<6 ".
             " and pagos.fecha_alta<current_date - integer '0' and gestion.folioconsecutivo=pagos.folioconsecutivo order by pagos.fecha_alta limit 500 ";
##        $sql="select folioconsecutivo,lc,intentosval,fecha_alta,folio from contra.gestion where lc='77273301146377YM5T4M' ";
        $sql_result = pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0) { echo "Error al ejecutar qry ".$sql." ".pg_last_error($connection); }
        $num= pg_numrows ($sql_result);
        echo "encontro num=".$num."\n";
        for ($i=0; $i<$num; $i++)
        {
             $row=pg_fetch_array($sql_result,$i);
             $x->lc=strtoupper($row["lc"]);
             $pos=$x->validapago();
             echo "encontro reg=".$i." linea=".$row["lc"]." pos=".count($pos)." folio=".$row["folio"]." lcf=".$pos[0]['lineacaptura']."\n";
             $x->marcapago($pos,$row["folioconsecutivo"],$connection,$row["id"]);
             $today=date('Y-m-d h:i:s',$t[0]);
             error_log($today." Resultado folio=".$row["folio"]." lc=".$row["lc"]." pos=".count($pos)." intentos=".$row["intentosval"]." registro=".$i." fecha alta=".$row["fecha_alta"]."\n",3,"/var/tmp/buscalineafinanzas.log");
        }


        $t=getdate();
        $today=date('Y-m-d h:i:s',$t[0]);
        error_log($today." Termino busca lineas\n",3,"/var/tmp/buscalineafinanzas.log");
?>
