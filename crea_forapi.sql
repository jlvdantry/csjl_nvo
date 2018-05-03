drop database forapi;
create database forapi encoding='latin1';
\c forapi
CREATE FUNCTION pltcl_call_handler() RETURNS language_handler
    AS '$libdir/pltcl', 'pltcl_call_handler'
    LANGUAGE c;
CREATE FUNCTION plpgsql_call_handler() RETURNS language_handler
    AS '$libdir/plpgsql', 'plpgsql_call_handler'
    LANGUAGE c;
CREATE TRUSTED PROCEDURAL LANGUAGE pltcl HANDLER pltcl_call_handler;
CREATE TRUSTED PROCEDURAL LANGUAGE plpgsql HANDLER plpgsql_call_handler;
SET SESSION AUTHORIZATION 'postgres';
REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;
 CREATE OR REPLACE FUNCTION grababitacora(int8,int2,int2,date,date,text )  RETURNS "numeric" AS '
DECLARE
  wlestado numeric;
  begin
   if $1 = 0 then
      raise notice '' antes de insert '';
      insert into cat_bitacora (idproceso,fecha_inicio,fecha_fin,at_inicio,at_fin,descripcion)
         values ($1,$4,$5,$2,$3,$6);
      raise notice '' paso insert '';
      select currval(''cat_bitacora_seq'') into wlestado;
   else
      update cat_bitacora set estado=1, descripcion=$6
             where idbitacora=$1;
   end if;
  return wlestado;
  end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION up_usuario_fecha( )  RETURNS "trigger" AS 'BEGIN
      new.usuario_modifico = getpgusername();
      new.fecha_modifico = current_timestamp(0);
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION baja_menus_pg_group( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_menus_pg_group (idmenu,grosysid,cve_movto)
               values (old.idmenu,old.grosysid,''b'');
     return old;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION alta_menus_pg_group( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_menus_pg_group (idmenu,grosysid,cve_movto)
               values (new.idmenu,new.grosysid,''a'');
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION alta_cat_usuarios_pg_group( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_cat_usuarios_pg_group (usename,grosysid,cve_movto)
               values (new.usename,new.grosysid,''a'');
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION baja_cat_usuarios_pg_group( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_cat_usuarios_pg_group (usename,grosysid,cve_movto)
               values (old.usename,old.grosysid,''b'');
     return old;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION baja_menus_pg_tables( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_menus_pg_tables (idmenu,tablename,cve_movto,tselect,tinsert,tupdate,tdelete,tall)
               values (old.idmenu,old.tablename,''b'',old.tselect,old.tinsert,old.tupdate,old.tdelete,old.tall);
     return old;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION alta_menus_pg_tables( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_menus_pg_tables (idmenu,tablename,cve_movto,tselect,tinsert,tupdate,tdelete,tall)
               values (new.idmenu,new.tablename,''a'',new.tselect,new.tinsert,new.tupdate,new.tdelete,new.tall);
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION cambio_menus_pg_tables( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_menus_pg_tables (idmenu,tablename,cve_movto,tselect,tinsert,tupdate,tdelete,tall)
               values (new.idmenu,new.tablename,''c'',new.tselect,new.tinsert,new.tupdate,new.tdelete,new.tall);
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION desbloquea_usuario(text )  RETURNS "varchar" AS 'DECLARE
  wlcuantos numeric;  
  begin
     update cat_usuarios set estatus=1
            where trim(usename)=trim($1);
     get diagnostics wlcuantos = ROW_COUNT;
     if wlcuantos>0 then
       return ''El usuario '' || $1 || '' se desbloqueo'';
     else
       return '''';
     end if;
	end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION baja_cat_usuarios( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_cat_usuarios (usename,nombre,apepat,apemat,puesto,depto,correoe,direccion_ip,
               idpregunta,respuesta,estatus,telefono,cve_movto)
               values (old.usename,old.nombre,old.apepat,old.apemat,old.puesto,old.depto,old.correoe,old.direccion_ip,
               old.idpregunta,old.respuesta,old.estatus,old.telefono,''b'');
        delete from cat_tiposcobrosusuarios where usename=cast(old.usename as name); 
     return old;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION grababitacora(int4,int4,int4,int4,date,date,text )  RETURNS "text" AS '
DECLARE
  wlestado numeric;
  aoutput  text;
  begin
   if $1 = 0 then
--      raise notice '' antes de insert %, fecha inicial % '', $7,$5;
--      aoutput := '' insert into cat_bitacora (idproceso,fecha_inicio,fecha_fin,at_inicio,at_fin,descripcion) '' ||
--         '' values ('' || $2 || '','''''' || $5 || '''''','''''' || $6 || '''''','' ||
--            $3 || '','' || $4 || '','''''' || $7 || '''''');'' ;
--      raise notice '' va a ejecutar insert % '', aoutput;
--      execute aoutput;
        insert into cat_bitacora (idproceso,fecha_inicio,fecha_fin,at_inicio,at_fin,descripcion) 
          values ( $2 , $5 , $6, $3 , $4 , $7 );
--      raise notice '' va a ejecutar insert % '', aoutput;
      select currval(''cat_bitacora_seq'') into wlestado;
   else
      if $7 <> '''' then
          update cat_bitacora set estado=1, descripcion=$7
             where idbitacora=$1;
      else
          update cat_bitacora set estado=1
             where idbitacora=$1;
      end if;
   end if;
--  commit transaction;
  return wlestado;
  end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION grababitacora(numeric,int4,int4,int4,date,date,text )  RETURNS "numeric" AS '
DECLARE
  wlestado numeric;
  aoutput  text;
  begin
   if $1 = 0 then
        insert into cat_bitacora (idproceso,fecha_inicio,fecha_fin,at_inicio,at_fin,descripcion) 
          values ( $2 , $5 , $6, $3 , $4 , $7 );
      select currval(''cat_bitacora_seq'') into wlestado;
   else
      if $7 <> '''' then
          update cat_bitacora set estado=1, descripcion=$7
             where idbitacora=cast($1 as integer);
      else
          update cat_bitacora set estado=1
             where idbitacora=cast($1 as integer);
      end if;
   end if;
  return wlestado;
  end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION grababitacora(int8,int2,int2,int2,date,date,text )  RETURNS "text" AS '
DECLARE
  wlestado numeric;
  aoutput  text;
  begin
   if $1 = 0 then
--      raise notice '' antes de insert %, fecha inicial % '', $7,$5;
--      aoutput := '' insert into cat_bitacora (idproceso,fecha_inicio,fecha_fin,at_inicio,at_fin,descripcion) '' ||
--         '' values ('' || $2 || '','''''' || $5 || '''''','''''' || $6 || '''''','' ||
--            $3 || '','' || $4 || '','''''' || $7 || '''''');'' ;
--      raise notice '' va a ejecutar insert % '', aoutput;
--      execute aoutput;
        insert into cat_bitacora (idproceso,fecha_inicio,fecha_fin,at_inicio,at_fin,descripcion) 
          values ( $2 , $5 , $6, $3 , $4 , $7 );
--      raise notice '' va a ejecutar insert % '', aoutput;
      select currval(''cat_bitacora_seq'') into wlestado;
   else
      if $7 <> '''' then
          update cat_bitacora set estado=1, descripcion=$7
             where idbitacora=$1;
      else
          update cat_bitacora set estado=1
             where idbitacora=$1;
      end if;
   end if;
--  commit transaction;
  return wlestado;
  end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION cambia_password(bpchar,bpchar,bpchar )  RETURNS "text" AS 'DECLARE
-- parametro1 usuario
-- parametro2 nuevo password
-- parametro3 password anterior
  wlcuantos int8;
  wlfecha date;
  wlserial numeric;
  a_output VARCHAR(4000);
begin
   RAISE NOTICE '' entro en val_idpago '';
   select count(*) into wlcuantos from his_cambios_pwd htc where usename=cast($1 as name)
         and valor_anterior=cast($2 as name);
     if wlcuantos>0 then
        return '' ERROR EL PASSWORD NUEVO YA FUE UTILIZADO EN ALGUNA OCACION '';
     end if;
   a_output=''alter user '' || $1 || '' with password '' || quote_literal("$2");
   RAISE NOTICE '' sql % '', a_output;
   execute a_output;
   insert into his_cambios_pwd (usename,valor_anterior,valor_nuevo) values
                               ($1,$3,$2);
   return ''SE CAMBIO PASSWORD'';
  end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION baja_menus( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        insert into his_menus (
  idmenu,
  descripcion,
  objeto,
  fecha_alta,
  usuario_alta,
  fecha_modifico,
  usuario_modifico,
  php,
  modoconsulta,
  idmenupadre,
  idmovtos,
  movtos,
  fuente,
  presentacion,
  columnas,
  tabla,
  reltype,
  filtro,
  limite,
  orden,
  menus_campos,
  dialogWidth,
  dialogHeight,
  s_table,
  s_table_height,
  cvemovto
  )
  values  (
  old.idmenu,
  old.descripcion,
  old.objeto,
  old.fecha_alta,
  old.usuario_alta,
  old.fecha_modifico,
  old.usuario_modifico,
  old.php,
  old.modoconsulta,
  old.idmenupadre,
  old.idmovtos,
  old.movtos,
  old.fuente,
  old.presentacion,
  old.columnas,
  old.tabla,
  old.reltype,
  old.filtro,
  old.limite,
  old.orden,
  old.menus_campos,
  old.dialogWidth,
  old.dialogHeight,
  old.s_table,
  old.s_table_height,
  ''d''
  );  
  delete from menus_campos where idmenu=old.idmenu;
  delete from menus_subvistas where idmenu=old.idmenu;
  delete from menus_pg_group where idmenu=old.idmenu;
  delete from menus_pg_tables where idmenu=old.idmenu;  
  delete from menus_eventos where idmenu=old.idmenu;    
 return old;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION alta_menus_campos( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
        select reltype into new.reltype from menus where idmenu=new.idmenu;
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION bloquea_usuario(text )  RETURNS "varchar" AS 'DECLARE
  wlcuantos numeric;  
  begin
     update cat_usuarios set estatus=2
            where trim(usename)=trim($1)
            and   estatus=1;
     get diagnostics wlcuantos = ROW_COUNT;
     if wlcuantos>0 then
       return ''Se bloqueo el usuario'';
     else
       return ''No se bloqueo el usuario'';
     end if;
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION estatus_usuario(text )  RETURNS "varchar" AS 'DECLARE
  wlestatus smallint;  
  begin
          SELECT cu.estatus into wlestatus from pg_shadow pgs, cat_usuarios cu where pgs.usename=cast($1 as name)
                 and pgs.usename =cast(cu.usename as name);
          if wlestatus=0 then
          		return ''Tu usuario no esta autorizado'';
          end if;
          
          if wlestatus=2 then
          		return ''Tu usuario esta bloqueado'';
          end if;          		

          if wlestatus=3 then
          		return ''Tu usuario esta inhabilitado definitivamente'';
          end if;          		                    
          return '''';
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION debe_cambiarpwd(text,int4 )  RETURNS "varchar" AS 'DECLARE
  wldias smallint;  
  begin
          SELECT coalesce((current_date-max(fecha_alta)),''0'') into wldias from his_cambios_pwd cu where cu.usuario_alta=cast($1 as name);
          if wldias>$2 then
          		return ''Usuario debe cambia pwd'';
          end if;
          
          return '''';
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION usuario_bloqueado(text )  RETURNS "varchar" AS 'DECLARE
  wlcuantos numeric;
  begin
     select count(*) into wlcuantos from cat_usuarios where estatus=2
            and trim(usename)=trim($1);
  return wlcuantos;
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION tiene_grupo(text )  RETURNS "varchar" AS 'DECLARE
  wlcuantos numeric;
  begin
     select count(*) into wlcuantos from cat_usuarios_pg_group where 
             trim(usename)=trim($1);
  return wlcuantos;
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION valida_res_des(text,text )  RETURNS "varchar" AS 'DECLARE
  wlcuantos numeric;
  begin
     select count(*) into wlcuantos from cat_usuarios where respuesta=$2
            and trim(usename)=trim($1);
  return wlcuantos;
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION cambio_cat_usuarios( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
      wlyapuesto numeric;
      wlatl smallint;
      wlid_puesto smallint;
    BEGIN
        wlatl=old.atl;
        wlid_puesto=old.id_puesto;
        if old.atl!=new.atl then
	   wlatl=new.atl;
	end if;
        if old.id_puesto!=new.id_puesto then
	   wlid_puesto=new.id_puesto;
	end if;
        if old.id_puesto!=new.id_puesto or old.atl!=new.atl then
	   select count(*) into wlyapuesto from cat_usuarios where atl=wlatl and id_puesto=wlid_puesto; 
	   if wlyapuesto>0 then
              raise exception '' LA AT YA TIENE ASIGNADO EL PUESTO A OTRA PERSONA '';
	   end if;
	end if;
        new.usuario_modifico = getpgusername();
        new.fecha_modifico = current_timestamp;
        insert into his_cat_usuarios (usename,nombre,apepat,apemat,puesto,depto,correoe,
                    direccion_ip,idpregunta,respuesta,telefono,cve_movto,estatus,atl,id_puesto)
               values (old.usename,old.nombre,old.apepat,old.apemat,old.puesto,old.depto,old.correoe,
                    old.direccion_ip,old.idpregunta,old.respuesta,old.telefono,''c'',
		    old.estatus,old.atl,old.id_puesto);
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION cambia_menus( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
--          raise notice '' entro '';
  if new.tabla!=old.tabla then
     update menus_campos set tabla=new.tabla
            where idmenu=old.idmenu;
  end if;
  if new.nspname!=old.nspname then
     update menus_campos set nspname=new.nspname
            where idmenu=old.idmenu;
  end if;
--          raise notice '' va a insertar '';
  new.usuario_modifico = getpgusername();
  new.fecha_modifico = current_timestamp;

  insert into his_menus (
  idmenu,
  descripcion,
  objeto,
  fecha_alta,
  usuario_alta,
  fecha_modifico,
  usuario_modifico,
  php,
  modoconsulta,
  idmenupadre,
  idmovtos,
  movtos,
  fuente,
  presentacion,
  columnas,
  tabla,
  reltype,
  filtro,
  limite,
  orden,
  menus_campos,
  dialogWidth,
  dialogHeight,
  s_table,
  s_table_height,
  cvemovto
  ,nspname
  )
  values  (
  old.idmenu,
  old.descripcion,
  old.objeto,
  old.fecha_alta,
  old.usuario_alta,
  old.fecha_modifico,
  old.usuario_modifico,
  old.php,
  old.modoconsulta,
  old.idmenupadre,
  old.idmovtos,
  old.movtos,
  old.fuente,
  old.presentacion,
  old.columnas,
  old.tabla,
  old.reltype,
  old.filtro,
  old.limite,
  old.orden,
  old.menus_campos,
  old.dialogWidth,
  old.dialogHeight,
  old.s_table,
  old.s_table_height,
  ''u''
  ,old.nspname
  ); 
 return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION alta_cat_usuarios( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
      wlsi numeric;
      wlsentencia varchar(255);
      wlusuario varchar(20);
      wlpasswd varchar(20);
    BEGIN
--        select count(*) into wlsi from contra.cat_personas where id_persona=new.id_persona;
--        if (wlsi>0) then
--           select nombre,apepat,apemat into new.nombre,new.apepat,new.apemat from contra.cat_personas where id_persona=new.id_persona;
--        end if;
        insert into his_cat_usuarios (usename,nombre,apepat,apemat,puesto,depto,correoe,
                    direccion_ip,idpregunta,respuesta,telefono,cve_movto)
               values (new.usename,new.nombre,new.apepat,new.apemat,new.puesto,new.depto,new.correoe,
                    new.direccion_ip,new.idpregunta,new.respuesta,new.telefono,''a'');
--        insert into pg_shadow (usename,passwd,usesysid,usecreatedb,usesuper,usecatupd)
--               values (new.usename,md5(new.password),(select max(usesysid)+1 from pg_shadow), false,false,false);
		select count(*) into wlsi from pg_shadow where cast(usename as text)=new.usename;
		if wlsi=0 then
		RAISE NOTICE '' $wlsi % '', wlsi;

		wlusuario=new.usename;
		wlpasswd=new.password;
		RAISE NOTICE '' $wlusuario % '', wlusuario;
		RAISE NOTICE '' $wlpasswd % '', wlpasswd;
		
        	wlsentencia = '' create user '' || wlusuario || '' password '' || quote_literal(trim(wlpasswd)) || '' nocreatedb nocreateuser; '';
--        	wlsentencia = ''create user '' ||  new.usename || '' password '' || quote_literal(new.password) || '' nocreatedb nocreateuser;'';
--        insert into pg_shadow (usename,passwd,usesysid,usecreatedb,usesuper,usecatupd)
--               values (new.usename,md5(new.password),(select max(usesysid)+1 from pg_shadow), false,false,false);
		RAISE NOTICE '' $wlsentencia % '', wlsentencia;
        	execute wlsentencia;
        end if;
--        new.password=md5(new.password);
--        update contra.cat_personas set usename=new.usename where id_persona=new.id_persona;
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION tablas_cambios( )  RETURNS "trigger" AS '
    set ss [ array names OLD ]; 
    set campo "idregcambio";
    set idmenu 0;

    foreach key $args { 
       set idmenu $key
    }

    if { [info exists NEW($campo)] } {
         foreach key $ss { 
           if { [info exists NEW($key)] & [info exists NEW($key)] } {
              set anterior [ string trim $OLD($key) ]
              set actual [ string trim $NEW($key) ]
              if { $actual != $anterior } { 
                 spi_exec " insert into his_tablas_cambios ( nspname,tabla, attnum ,  idregcambio, valor_anterior,
                              valor_nuevo,idmenu ) values ( 
                          (select nspname from tablas where oid= $TG_relid)
                         ,(select relname from tablas where oid= $TG_relid)
                         ,(select attnum  from campos where attrelid= $TG_relid and attname=''$key'')
                         , ''$OLD($campo)''
                         , ''$OLD($key)'', ''$NEW($key)'', $idmenu ) ";
               }
           }
         }
    }
    return [array get NEW]
' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION autoriza_usuario(text )  RETURNS "varchar" AS 'DECLARE
  mireg record;
  wlcuantos numeric;
  wlper     varchar(255);
  wlsentencia varchar(255);
  begin
--     2005-12-13  jlv modificacion ya que existian tablas sin public esto provocaba que en una tabla
--     2005-12-13      que tenia all y a su vez select al final le poonia select provocando problema de permisos
--     2007-01-08  jlv hay menus que tienen subvistas estas no se les da permisos a los usuarios, se modifico para
--                     que las subvistas las contemple para los permisos
--     2007-03-12  grecar modifique linea 55 porque faltaba espesificar un parametro
--     2007-03-23  jlv cuando no existe el esquema contra esto truena no tiene caso lo quite
--     2007-05-26  jlv lo modifique para que incluyera un esque que viene en la tabla menus_pg_tables,
--                 a esta la tabla se le agrego un campo que es el esquema
--     SET search_path to public,pg_catalog,contra;
--     grant usage on schema contra to $1;
--     wlsentencia='' grant usage on schema contra to '' ||  $1 ;    -- 2007-03-23
--     execute wlsentencia;                                          -- 2007-03-23 
--     2007-07-20  jlv lo modifique para que si la tabla viene en espacion no haga el grant

     select count(*) into wlcuantos from cat_usuarios_pg_group
            where trim(usename)=trim($1);
     if wlcuantos=0 then
        return ''No existe grupo asignado al usuario'';
     end if;
     update cat_usuarios set estatus=1 where trim(usename)=trim($1);
--     for mireg  in select   mpgt.*,me.descripcion 
     for mireg  in select 
        case when (strpos(mpgt.tablename,''.'')=0 and strpos(mpgt.tablename,''pg_'')=0)
--2007-05-26 	           then ''public.''||mpgt.tablename else mpgt.tablename end as tablename
	           then mpgt.nspname||''.''||mpgt.tablename else mpgt.tablename end as tablename
                   , sum(case when mpgt.tall=''1'' then 1 else 0 end) as tall
                   , sum(case when mpgt.tselect=''1'' then 1 else 0 end) as tselect
                   , sum(case when mpgt.tinsert=''1'' then 1 else 0 end) as tinsert
                   , sum(case when mpgt.tupdate=''1'' then 1 else 0 end) as tupdate
                   , sum(case when mpgt.tdelete=''1'' then 1 else 0 end) as tdelete
                   from cat_usuarios_pg_group as cupg
--		   , menus_pg_group as mpg 
		   , menus_pg_tables as mpgt
                            ,menus as me
                   where trim(cupg.usename)=trim($1) 
--                   and cupg.grosysid = mpg.grosysid
                   and me.idmenu  = mpgt.idmenu
                   and me.idmenu  in ((select idmenu from menus_pg_group as mpg where
		                                  cupg.grosysid = mpg.grosysid
                                     union
				     select idsubvista from menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)
                                     group by 1
			             ))
                   group by 1
                   order by 1
                   loop
--         mireg.sentencia=substr(mireg.sentencia,1,strpos(mireg.sentencia,''<'')-1) || $1;
--         mireg.sentencia=substr(mireg.sentencia,1,strpos(mireg.sentencia,''<'')-1) || $1;
         wlper='''';
         wlsentencia='''';
--         raise notice '' tablename % tselect % tall % '',  mireg.tablename, mireg.tselect ,
--               mireg.tall ;
           if mireg.tall>0 then
              wlper='' all '';
           else
           if mireg.tselect>0 then
              if length(trim(wlper))=0 then
                 wlper='' select '';
              else
                 wlper= wlper || '', select '';
              end if;
           end if;

           if mireg.tinsert>0 then
              if length(trim(wlper))=0 then
                 wlper='' insert '';
              else
                 wlper= wlper || '', insert '';
              end if;
           end if;

           if mireg.tupdate>0 then
              if length(trim(wlper))=0 then
                 wlper='' update '';
              else
                 wlper= wlper || '', update '';
              end if;
           end if;

           if mireg.tdelete>0 then
              if length(trim(wlper))=0 then
                 wlper='' delete '';
              else
                 wlper= wlper || '', delete '';
              end if;
           end if;
           end if;
         if trim(mireg.tablename)!='''' then   -- 20070720
            wlsentencia='' revoke all on '' || trim(mireg.tablename) || '' from '' ||  $1 ;
            execute wlsentencia;
            wlsentencia=''grant '' || trim(wlper) || ''  on '' || trim(mireg.tablename) || '' to '' ||  $1 ;
            raise notice '' sentencia % '', wlsentencia  ;
            execute wlsentencia;     -- 20070720
         end if;
     end loop ;
--  20070602  incluit este for para dar permisos de uso al usuario a los esquemas
--            esquemas que no son publicos
     for mireg  in select
                   mpgt.nspname 
                   from cat_usuarios_pg_group as cupg
                   , menus_pg_tables as mpgt
                            ,menus as me
                   where trim(cupg.usename)=trim($1)
                   and me.idmenu  = mpgt.idmenu
                   and me.idmenu  in ((select idmenu from menus_pg_group as mpg where
                                                  cupg.grosysid = mpg.grosysid
                                     union
                                     select idsubvista from menus_subvistas as ms where
                                            ms.idmenu in
                                            (select idmenu from menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)
                                     group by 1
                                     ))
                   group by 1
                   loop
         if mireg.nspname!=''public'' then
            wlsentencia='' revoke all on schema '' || trim(mireg.nspname) || '' from '' ||  $1 ;
            execute wlsentencia;
            wlsentencia='' grant usage on schema '' || trim(mireg.nspname) || '' to '' ||  $1 ;
            execute wlsentencia;
            raise notice '' sentencia % '', wlsentencia  ;
         end if;
--   termina 20070602
     end loop ;

  return ''El usuario '' || $1 || '' se autorizo'';
end;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION alta_menus( )  RETURNS "trigger" AS '
    DECLARE
      wlestado numeric;
    BEGIN
    	if new.tabla='''' or new.tabla is null then
        	select relname,nspname into new.tabla,new.nspname from tablas where reltype=new.reltype;
        else
        	if new.reltype!=(select reltype from tablas where relname=new.tabla and nspname=new.nspname) then
        	   select reltype into new.reltype from tablas where relname=new.tabla and nspname=new.nspname;
        	end if;
        end if;
     return new;
    END;' LANGUAGE 'plpgsql' VOLATILE ;
 CREATE OR REPLACE FUNCTION copiamenu(int4 )  RETURNS "text" AS 'DECLARE
        wlatl smallint;
        wlidmenu numeric;
BEGIN
--  menus
    select * into temp menu_tmp from menus
    where idmenu=$1;
    select nextval(''menus_idmenu_seq'') into wlidmenu;
    update menu_tmp set idmenu=wlidmenu,descripcion=descripcion||'' Copia''
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus select * from menu_tmp;

--  menus_campos
    select * into temp menus_campos_tmp from menus_campos
    where idmenu=$1;
    update menus_campos_tmp set idmenu=wlidmenu
           ,idcampo=(select nextval(''menus_campos_idcampo_seq'') from menus_campos_tmp as mct where mct.idcampo=menus_campos_tmp.idcampo)
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_campos select * from menus_campos_tmp;

--  menus_tablas
    select * into temp menus_pg_tables_tmp from menus_pg_tables
    where idmenu=$1;
    update menus_pg_tables_tmp set idmenu=wlidmenu
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_pg_tables select * from menus_pg_tables_tmp;

--  menus_subvistas
    select * into temp menus_subvistas_tmp from menus_subvistas
    where idmenu=$1;
    update menus_subvistas_tmp set idmenu=wlidmenu
           ,idmenus_subvistas=(select nextval(''menus_subvistas_idmenus_subvistas_seq'') from menus_subvistas as mct where mct.idmenus_subvistas=menus_subvistas_tmp.idmenus_subvistas)
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_subvistas select * from menus_subvistas_tmp;


--  menus_grupos
    select * into temp menus_pg_group_tmp from menus_pg_group
    where idmenu=$1;
    update menus_pg_group_tmp set idmenu=wlidmenu
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_pg_group select * from menus_pg_group_tmp;

--  menus_movtos
    select * into temp menus_movtos_tmp from menus_movtos
    where idmenu=$1;
    update menus_movtos_tmp set idmenu=wlidmenu
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_movtos select * from menus_movtos_tmp;


--  menus_eventos
    select * into temp menus_eventos_tmp from menus_eventos
    where idmenu=$1;
    update menus_eventos_tmp set idmenu=wlidmenu
           ,idmenus_eventos=(select nextval(''menus_eventos_idmenus_eventos_seq'') from menus_eventos as mct where mct.idmenus_eventos=menus_eventos_tmp.idmenus_eventos)
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_eventos select * from menus_eventos_tmp;

--  menus_campos_eventos
    select * into temp menus_campos_eventos_tmp from menus_campos_eventos
    where idmenu=$1;
    update menus_campos_eventos_tmp set idmenu=wlidmenu
           ,icv=(select nextval(''menus_campos_eventos_icv_seq'') from menus_campos_eventos as mct where mct.icv=menus_campos_eventos_tmp.icv)
           ,fecha_alta=current_timestamp ,usuario_alta=getpgusername()
           ,fecha_modifico=current_timestamp ,usuario_modifico=getpgusername();
    insert into menus_campos_eventos select * from menus_campos_eventos_tmp;

RETURN ''ok'';
END;' LANGUAGE 'plpgsql' VOLATILE ;

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cat_usuarios; Type: TABLE; Schema: public; Owner: siscor; Tablespace: 
--

CREATE TABLE cat_usuarios (
    usename character(15) NOT NULL,
    nombre character varying(30),
    apepat character varying(30),
    apemat character varying(30),
    puesto character varying(50),
    depto character varying(50),
    correoe character varying(50),
    direccion_ip numeric(20,0),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    idpregunta smallint DEFAULT 0,
    respuesta character varying(100),
    estatus smallint DEFAULT 0,
    telefono character varying(30),
    direccion character varying(50),
    atl smallint,
    id_direccion smallint,
    id_persona integer,
    "password" text,
    id_puesto integer,
    id_tipomenu smallint
);


ALTER TABLE public.cat_usuarios OWNER TO siscor;

--
-- Name: pkcat_usuarios; Type: INDEX; Schema: public; Owner: siscor; Tablespace: 
--

CREATE UNIQUE INDEX pkcat_usuarios ON cat_usuarios USING btree (usename);


--
-- Name: catu_up_usuario_fecha; Type: TRIGGER; Schema: public; Owner: siscor
--

CREATE TRIGGER catu_up_usuario_fecha
    BEFORE UPDATE ON cat_usuarios
    FOR EACH ROW
    EXECUTE PROCEDURE up_usuario_fecha();


--
-- Name: td_cat_usuarios; Type: TRIGGER; Schema: public; Owner: siscor
--

CREATE TRIGGER td_cat_usuarios
    BEFORE DELETE ON cat_usuarios
    FOR EACH ROW
    EXECUTE PROCEDURE baja_cat_usuarios();


--
-- Name: ti_cat_usuarios; Type: TRIGGER; Schema: public; Owner: siscor
--

CREATE TRIGGER ti_cat_usuarios
    BEFORE INSERT ON cat_usuarios
    FOR EACH ROW
    EXECUTE PROCEDURE alta_cat_usuarios();


--
-- Name: tu_cat_usuarios; Type: TRIGGER; Schema: public; Owner: siscor
--

CREATE TRIGGER tu_cat_usuarios
    BEFORE UPDATE ON cat_usuarios
    FOR EACH ROW
    EXECUTE PROCEDURE cambio_cat_usuarios();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cat_usuarios_pg_group; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE cat_usuarios_pg_group (
    usename character(15),
    grosysid integer,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.cat_usuarios_pg_group OWNER TO jlv;

--
-- Name: ak1_cat_usuarios_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_cat_usuarios_pg_group ON cat_usuarios_pg_group USING btree (usename);


--
-- Name: xpkcat_usuarios_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkcat_usuarios_pg_group ON cat_usuarios_pg_group USING btree (usename, grosysid);


--
-- Name: td_cat_usuarios_pg_group; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER td_cat_usuarios_pg_group
    BEFORE DELETE ON cat_usuarios_pg_group
    FOR EACH ROW
    EXECUTE PROCEDURE baja_cat_usuarios_pg_group();


--
-- Name: ti_cat_usuarios_pg_group; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER ti_cat_usuarios_pg_group
    BEFORE INSERT ON cat_usuarios_pg_group
    FOR EACH ROW
    EXECUTE PROCEDURE alta_cat_usuarios_pg_group();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus (
    idmenu serial NOT NULL,
    descripcion character varying(100),
    objeto character varying(100),
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    php character varying(100),
    modoconsulta smallint,
    idmenupadre integer,
    idmovtos integer,
    movtos character varying(20) DEFAULT 'i,d,u,s,l,a'::character varying,
    fuente character varying(255),
    presentacion smallint DEFAULT 2,
    columnas smallint DEFAULT 2,
    tabla character varying(50),
    reltype oid DEFAULT 0,
    filtro character varying(255),
    limite integer DEFAULT 100,
    orden character varying(255),
    menus_campos integer DEFAULT 0,
    dialogwidth integer DEFAULT 0,
    dialogheight integer DEFAULT 0,
    s_table integer DEFAULT 0,
    s_table_height integer DEFAULT 300,
    inicioregistros boolean DEFAULT false,
    nspname name,
    css character varying(50)
);


ALTER TABLE public.menus OWNER TO jlv;

--
-- Name: COLUMN menus.movtos; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus.movtos IS 'i=insert,d=delete,u=update,s=select,l=limpiar,a=autodiseño,cc=copia un renglon';


--
-- Name: menus_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus
    ADD CONSTRAINT menus_pkey PRIMARY KEY (idmenu);


--
-- Name: td_menus; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER td_menus
    BEFORE DELETE ON menus
    FOR EACH ROW
    EXECUTE PROCEDURE baja_menus();


--
-- Name: ti_menus; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER ti_menus
    BEFORE INSERT ON menus
    FOR EACH ROW
    EXECUTE PROCEDURE alta_menus();


--
-- Name: tu_menus; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER tu_menus
    BEFORE UPDATE ON menus
    FOR EACH ROW
    EXECUTE PROCEDURE cambia_menus();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_pg_group; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_pg_group (
    idmenu integer,
    grosysid integer,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_pg_group OWNER TO jlv;

--
-- Name: ak1_menus_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_menus_pg_group ON menus_pg_group USING btree (grosysid);


--
-- Name: ak2_menus_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak2_menus_pg_group ON menus_pg_group USING btree (idmenu);


--
-- Name: xpkmenus_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkmenus_pg_group ON menus_pg_group USING btree (idmenu, grosysid);


--
-- Name: td_menus_pg_group; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER td_menus_pg_group
    BEFORE DELETE ON menus_pg_group
    FOR EACH ROW
    EXECUTE PROCEDURE baja_menus_pg_group();


--
-- Name: ti_menus_pg_group; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER ti_menus_pg_group
    BEFORE INSERT ON menus_pg_group
    FOR EACH ROW
    EXECUTE PROCEDURE alta_menus_pg_group();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_menus; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE his_menus (
    idmenu integer,
    descripcion character varying(100),
    objeto character varying(100),
    fecha_alta timestamp with time zone,
    usuario_alta character varying(20),
    fecha_modifico timestamp with time zone,
    usuario_modifico character varying(20),
    php character varying(100) DEFAULT ''::character varying,
    modoconsulta smallint,
    idmenupadre integer DEFAULT 0,
    idmovtos integer,
    movtos character varying(20) DEFAULT 'i,d,u,s'::character varying,
    fuente character varying(255),
    presentacion smallint DEFAULT 2,
    columnas smallint DEFAULT 2,
    tabla character varying(50),
    reltype oid DEFAULT 0,
    filtro character varying(255),
    limite integer DEFAULT 100,
    orden character varying(255),
    menus_campos integer DEFAULT 0,
    dialogwidth integer DEFAULT 0,
    dialogheight integer DEFAULT 0,
    s_table integer DEFAULT 0,
    s_table_height integer DEFAULT 300,
    cvemovto character varying(1),
    fecha_movto timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_movto character varying(20) DEFAULT getpgusername(),
    nspname name
);


ALTER TABLE public.his_menus OWNER TO jlv;

--
-- Name: ak1_menus; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_menus ON his_menus USING btree (idmenu);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_eventos; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_eventos (
    idmenus_eventos serial NOT NULL,
    idmenu integer,
    idevento integer,
    donde smallint,
    descripcion character varying(255),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_eventos OWNER TO jlv;

--
-- Name: menus_eventos_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_eventos
    ADD CONSTRAINT menus_eventos_pkey PRIMARY KEY (idmenus_eventos);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_subvistas; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_subvistas (
    idmenus_subvistas serial NOT NULL,
    idmenu integer,
    texto character varying(50),
    imagen character varying(255),
    idsubvista integer DEFAULT 0,
    funcion character varying(255),
    dialogwidth integer DEFAULT 40,
    dialogheight integer DEFAULT 30,
    esboton integer DEFAULT 1,
    donde smallint,
    eventos_antes character varying(255),
    eventos_despues character varying(255),
    campo_filtro character varying(255),
    valor_padre character varying(255),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    clase character varying(255),
    posicion smallint DEFAULT 0,
    orden smallint DEFAULT 0
);


ALTER TABLE public.menus_subvistas OWNER TO jlv;

--
-- Name: menus_subvistas_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_subvistas
    ADD CONSTRAINT menus_subvistas_pkey PRIMARY KEY (idmenus_subvistas);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_movtos; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_movtos (
    idmenu integer NOT NULL,
    idmovto character(1),
    descripcion character varying(255),
    imagen character varying(255),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_movtos OWNER TO jlv;

--
-- Name: COLUMN menus_movtos.idmovto; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_movtos.idmovto IS 'i=insert,d=delete,s=select,u=upate,l=limpiar';


--
-- Name: ak1_menus_movtos; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_menus_movtos ON menus_movtos USING btree (idmenu);


--
-- Name: pk_menus_movtos; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX pk_menus_movtos ON menus_movtos USING btree (idmenu, idmovto);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_presentacion; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_presentacion (
    idpresentacion serial NOT NULL,
    descripcion character varying(100),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_presentacion OWNER TO jlv;

--
-- Name: menus_presentacion_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_presentacion
    ADD CONSTRAINT menus_presentacion_pkey PRIMARY KEY (idpresentacion);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tcases; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE tcases (
    tcase smallint NOT NULL,
    descripcion character varying(10)
);


ALTER TABLE public.tcases OWNER TO jlv;

--
-- Name: tcases_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY tcases
    ADD CONSTRAINT tcases_pkey PRIMARY KEY (tcase);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_campos; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_campos (
    idcampo serial NOT NULL,
    idmenu integer,
    reltype oid DEFAULT 0 NOT NULL,
    attnum integer DEFAULT 0 NOT NULL,
    descripcion character varying(100),
    size integer,
    male integer,
    fuente character varying(100),
    fuente_campodes character varying(30),
    fuente_campodep character varying(30),
    fuente_campofil character varying(255),
    fuente_where character varying(4000),
    fuente_evento smallint DEFAULT 0,
    orden integer,
    idsubvista integer,
    dialogwidth integer DEFAULT 40,
    dialogheight integer DEFAULT 30,
    obligatorio boolean,
    busqueda boolean DEFAULT false,
    altaautomatico boolean DEFAULT false,
    tcase smallint DEFAULT 0,
    checaduplicidad boolean,
    readonly boolean DEFAULT false,
    valordefault character varying(299),
    esindex boolean DEFAULT false,
    tipayuda character varying(255),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    espassword smallint DEFAULT 0,
    tabla character varying(50),
    nspname name,
    fuente_busqueda boolean DEFAULT false,
    val_particulares character varying(30),
    htmltable smallint DEFAULT 0,
    fuente_nspname name
);


ALTER TABLE public.menus_campos OWNER TO jlv;

--
-- Name: COLUMN menus_campos.fuente_busqueda; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_campos.fuente_busqueda IS 'Indica si en un campo select se tiene la opcion de busqueda esto se utiliza cuando las opciones son bastantes y el browse no se pasme';


--
-- Name: COLUMN menus_campos.val_particulares; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_campos.val_particulares IS 'Se indica que validacion utilizar en el cliente si es mas de una es separado por ; y hay que corregir el ';


--
-- Name: COLUMN menus_campos.htmltable; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_campos.htmltable IS 'Numero de tabla en el html, por default es 0, si se pone otro numero crea otra tabla en region de captura de datos';


--
-- Name: menus_campos_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_campos
    ADD CONSTRAINT menus_campos_pkey PRIMARY KEY (idcampo);


--
-- Name: ak1_menus_campos; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_menus_campos ON menus_campos USING btree (idmenu, attnum);


--
-- Name: ti_menus_campos; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER ti_menus_campos
    BEFORE INSERT ON menus_campos
    FOR EACH ROW
    EXECUTE PROCEDURE alta_menus_campos();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: eventos; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE eventos (
    idevento serial NOT NULL,
    descripcion character varying(60),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.eventos OWNER TO jlv;

--
-- Name: eventos_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY eventos
    ADD CONSTRAINT eventos_pkey PRIMARY KEY (idevento);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_campos_eventos; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_campos_eventos (
    icv serial NOT NULL,
    attnum integer,
    idmenu integer,
    idevento integer,
    donde smallint,
    descripcion character varying(255),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_campos_eventos OWNER TO jlv;

--
-- Name: menus_campos_eventos_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_campos_eventos
    ADD CONSTRAINT menus_campos_eventos_pkey PRIMARY KEY (icv);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_menus_pg_tables; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE his_menus_pg_tables (
    idcambio integer DEFAULT nextval(('his_menus_pg_group_seq'::text)),
    idmenu integer,
    tablename name,
    tselect character(1) DEFAULT ''::bpchar,
    tinsert character(1) DEFAULT ''::bpchar,
    tupdate character(1) DEFAULT ''::bpchar,
    tdelete character(1) DEFAULT ''::bpchar,
    tall character(1) DEFAULT ''::bpchar,
    cve_movto character(1),
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    tgrant character(1) DEFAULT ''::bpchar
);


ALTER TABLE public.his_menus_pg_tables OWNER TO jlv;

--
-- Name: xpkhis_menus_pg_tables; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX xpkhis_menus_pg_tables ON his_menus_pg_tables USING btree (idmenu, tablename);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_pg_tables; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_pg_tables (
    idmenu integer,
    tablename name,
    tselect character(1) DEFAULT ''::bpchar,
    tinsert character(1) DEFAULT ''::bpchar,
    tupdate character(1) DEFAULT ''::bpchar,
    tdelete character(1) DEFAULT ''::bpchar,
    tall character(1) DEFAULT ''::bpchar,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    tgrant character(1) DEFAULT ''::bpchar,
    nspname name
);


ALTER TABLE public.menus_pg_tables OWNER TO jlv;

--
-- Name: xpkmenus_pg_tables; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkmenus_pg_tables ON menus_pg_tables USING btree (idmenu, tablename);


--
-- Name: td_menus_pg_tables; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER td_menus_pg_tables
    BEFORE DELETE ON menus_pg_tables
    FOR EACH ROW
    EXECUTE PROCEDURE baja_menus_pg_tables();


--
-- Name: ti_menus_pg_tables; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER ti_menus_pg_tables
    BEFORE INSERT ON menus_pg_tables
    FOR EACH ROW
    EXECUTE PROCEDURE alta_menus_pg_tables();


--
-- Name: tu_menus_pg_tables; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER tu_menus_pg_tables
    BEFORE UPDATE ON menus_pg_tables
    FOR EACH ROW
    EXECUTE PROCEDURE cambio_menus_pg_tables();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_shadow; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_shadow (
    idmenu integer,
    usename name,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_shadow OWNER TO jlv;

--
-- Name: xpkmenus_shadow; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkmenus_shadow ON menus_shadow USING btree (idmenu, usename);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_cambios_pwd; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE his_cambios_pwd (
    usename name,
    valor_anterior name,
    valor_nuevo name,
    usuario_alta name DEFAULT getpgusername(),
    fecha_alta date DEFAULT date('now'::text),
    hora_alta time without time zone DEFAULT ('now'::text)::time(6) with time zone
);


ALTER TABLE public.his_cambios_pwd OWNER TO jlv;

--
-- Name: xakhis_cambios_pwdfcpnva; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX xakhis_cambios_pwdfcpnva ON his_cambios_pwd USING btree (usuario_alta, fecha_alta);


--
-- Name: xpkhis_cambios_pwdfcpnva; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX xpkhis_cambios_pwdfcpnva ON his_cambios_pwd USING btree (usename, valor_anterior);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_cat_usuarios; Type: TABLE; Schema: public; Owner: siscor; Tablespace: 
--

CREATE TABLE his_cat_usuarios (
    idcambio integer DEFAULT nextval(('his_cat_usuarios_seq'::text)),
    usename character(15) NOT NULL,
    nombre character varying(30),
    apepat character varying(30),
    apemat character varying(30),
    puesto character varying(50),
    depto character varying(50),
    correoe character varying(50),
    direccion_ip numeric(20,0),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    idpregunta smallint DEFAULT 0,
    respuesta character varying(100),
    estatus smallint DEFAULT 0,
    telefono character varying(30),
    cve_movto character(1) DEFAULT ' '::bpchar,
    id_personas integer,
    atl smallint,
    id_puesto integer
);


ALTER TABLE public.his_cat_usuarios OWNER TO siscor;

--
-- Name: pkhis_cat_usuarios; Type: INDEX; Schema: public; Owner: siscor; Tablespace: 
--

CREATE INDEX pkhis_cat_usuarios ON his_cat_usuarios USING btree (usename);


--
-- Name: pkhis_usuariosidcambio; Type: INDEX; Schema: public; Owner: siscor; Tablespace: 
--

CREATE UNIQUE INDEX pkhis_usuariosidcambio ON his_cat_usuarios USING btree (idcambio);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_cat_usuarios_pg_group; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE his_cat_usuarios_pg_group (
    idcambio integer DEFAULT nextval(('his_cat_usuarios_pg_group_seq'::text)),
    usename character(15),
    grosysid integer,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    cve_movto character(1) DEFAULT ' '::bpchar
);


ALTER TABLE public.his_cat_usuarios_pg_group OWNER TO jlv;

--
-- Name: xpkhis_cat_usuarios_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX xpkhis_cat_usuarios_pg_group ON his_cat_usuarios_pg_group USING btree (usename, grosysid);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cat_bitacora; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE cat_bitacora (
    idbitacora integer DEFAULT nextval(('cat_bitacora_seq'::text)),
    idproceso integer,
    fecha_inicio date,
    fecha_fin date,
    at_inicio smallint,
    at_fin smallint,
    estado smallint DEFAULT 0,
    descripcion text,
    fecha_alta timestamp with time zone DEFAULT "timestamp"('now'::text),
    usuario_alta text DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT "timestamp"('now'::text),
    usuario_modifico text DEFAULT getpgusername()
);


ALTER TABLE public.cat_bitacora OWNER TO jlv;

--
-- Name: xalcat_bitacora; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX xalcat_bitacora ON cat_bitacora USING btree (idproceso, fecha_inicio, fecha_fin);


--
-- Name: xpkcat_bitacora; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkcat_bitacora ON cat_bitacora USING btree (idbitacora);


--
-- Name: tu_cat_bitacora; Type: TRIGGER; Schema: public; Owner: jlv
--

CREATE TRIGGER tu_cat_bitacora
    BEFORE UPDATE ON cat_bitacora
    FOR EACH ROW
    EXECUTE PROCEDURE up_usuario_fecha();


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_menus_pg_group; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE his_menus_pg_group (
    idcambio integer DEFAULT nextval(('his_menus_pg_group_seq'::text)),
    idmenu integer,
    grosysid integer,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    cve_movto character(1) DEFAULT ' '::bpchar
);


ALTER TABLE public.his_menus_pg_group OWNER TO jlv;

--
-- Name: xpkhis_menus_pg_group; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX xpkhis_menus_pg_group ON his_menus_pg_group USING btree (idmenu, grosysid);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: his_tablas_cambios; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE his_tablas_cambios (
    nspname name,
    tabla character varying(50),
    attnum integer DEFAULT 0 NOT NULL,
    idregcambio integer,
    valor_anterior text,
    valor_nuevo text,
    usuario_alta name DEFAULT getpgusername(),
    fecha_alta timestamp with time zone DEFAULT "timestamp"('now'::text),
    idmenu integer
);


ALTER TABLE public.his_tablas_cambios OWNER TO jlv;

--
-- Name: akhis_tablas_cambios; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX akhis_tablas_cambios ON his_tablas_cambios USING btree (nspname, tabla, idregcambio);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cat_preguntas; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE cat_preguntas (
    idpregunta integer DEFAULT nextval(('cat_preguntas_seq'::text)),
    descripcion character varying(100),
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.cat_preguntas OWNER TO jlv;

--
-- Name: xpkcat_preguntas; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkcat_preguntas ON cat_preguntas USING btree (idpregunta);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: campos; Type: VIEW; Schema: public; Owner: siscor
--

CREATE VIEW campos AS
    SELECT pgc.relname, pga.attrelid, pga.attname, pga.atttypid, pga.attstattarget, pga.attlen, pga.attnum, pga.attndims, pga.attcacheoff, pga.atttypmod, pga.attbyval, pga.attstorage, pga.attalign, pga.attnotnull, pga.atthasdef, pga.attisdropped, pga.attislocal, pga.attinhcount, pgt.typname, pgc.relname AS fuente, pgc.oid AS reltype, pgc.relname AS tabla, (SELECT pgad.adsrc FROM pg_attrdef pgad WHERE ((pga.attnum = pgad.adnum) AND (pgc.oid = pgad.adrelid))) AS valor_default, (SELECT count(*) AS count FROM pg_index pgi WHERE (((pgc.oid = pgi.indrelid) AND (pgi.indisunique = true)) AND ((((pgi.indkey[0] = pga.attnum) OR (pgi.indkey[1] = pga.attnum)) OR (pgi.indkey[2] = pga.attnum)) OR (pgi.indkey[3] = pga.attnum)))) AS indice, (SELECT pg_description.description FROM pg_description WHERE ((pg_description.objoid = pgc.oid) AND (pg_description.objsubid = pga.attnum))) AS descripcion, pgn.nspname, pgn.nspname AS fuente_nspname FROM pg_class pgc, pg_attribute pga, pg_type pgt, pg_namespace pgn WHERE (((pgc.oid = pga.attrelid) AND (pgt.oid = pga.atttypid)) AND (pgc.relnamespace = pgn.oid)) ORDER BY pga.attnum;


ALTER TABLE public.campos OWNER TO siscor;

--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: tablas; Type: VIEW; Schema: public; Owner: siscor
--

CREATE VIEW tablas AS
    SELECT c.relname, c.reltype, c.oid, n.nspname, n.nspname AS fuente_nspname FROM ((pg_class c LEFT JOIN pg_namespace n ON ((n.oid = c.relnamespace))) LEFT JOIN pg_tablespace t ON ((t.oid = c.reltablespace))) WHERE ((((c.relkind = 'r'::"char") OR (c.relkind = 'S'::"char")) OR (c.relkind = 'v'::"char")) AND (substr((c.relname)::text, 1, 4) <> 'sql_'::text));


ALTER TABLE public.tablas OWNER TO siscor;

--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_tiempos; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_tiempos (
    idtiempo serial NOT NULL,
    descripcion character varying(30),
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_tiempos OWNER TO jlv;

--
-- Name: xpkmenus_tiempos; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE UNIQUE INDEX xpkmenus_tiempos ON menus_tiempos USING btree (idtiempo);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_archivos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE menus_archivos (
    idarchivo serial NOT NULL,
    descripcion character varying,
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying DEFAULT getpgusername(),
    version integer,
    idtipoarchivo smallint DEFAULT 0,
    "tamaño" integer DEFAULT 0,
    ubicacion character varying
);


ALTER TABLE public.menus_archivos OWNER TO postgres;

--
-- Name: menus_archivos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY menus_archivos
    ADD CONSTRAINT menus_archivos_pkey PRIMARY KEY (idarchivo);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_tipoarchivos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE menus_tipoarchivos (
    idtipoarchivo serial NOT NULL,
    descripcion character varying(20) NOT NULL,
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_tipoarchivos OWNER TO postgres;

--
-- Name: menus_tipoarchivos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY menus_tipoarchivos
    ADD CONSTRAINT menus_tipoarchivos_pkey PRIMARY KEY (idtipoarchivo);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_seguimiento; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_seguimiento (
    idseguimietno serial NOT NULL,
    idmenu integer NOT NULL,
    usename character varying(20),
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_seguimiento OWNER TO jlv;

--
-- Name: TABLE menus_seguimiento; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON TABLE menus_seguimiento IS 'tabla para indicar a que se le va a dar seguimiento';


--
-- Name: COLUMN menus_seguimiento.idmenu; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_seguimiento.idmenu IS 'Numero de menu a dar seguimietno si 9999999=todos';


--
-- Name: COLUMN menus_seguimiento.usename; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_seguimiento.usename IS 'Usuario a dar seguimiento *=todos';


--
-- Name: COLUMN menus_seguimiento.fecha_alta; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_seguimiento.fecha_alta IS 'Fecha en que hizo el movimiento';


--
-- Name: COLUMN menus_seguimiento.usuario_alta; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_seguimiento.usuario_alta IS 'Usuario hizo el alta ';


--
-- Name: menus_seguimiento_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_seguimiento
    ADD CONSTRAINT menus_seguimiento_pkey PRIMARY KEY (idseguimietno);


--
-- Name: ak1_menus_seguimiento; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_menus_seguimiento ON menus_seguimiento USING btree (idmenu);


--
-- Name: ak2_menus_seguimiento; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak2_menus_seguimiento ON menus_seguimiento USING btree (usename);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_htmltable; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE menus_htmltable (
    idhtmltable serial NOT NULL,
    descripcion character varying(255),
    esdesistema boolean DEFAULT false,
    columnas smallint DEFAULT 0,
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_htmltable OWNER TO postgres;

--
-- Name: TABLE menus_htmltable; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE menus_htmltable IS 'Tablas de html';


--
-- Name: COLUMN menus_htmltable.idhtmltable; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_htmltable.idhtmltable IS 'Numero de identificacion de la tabla';


--
-- Name: COLUMN menus_htmltable.descripcion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_htmltable.descripcion IS 'Caption que va a tener la tabla';


--
-- Name: COLUMN menus_htmltable.columnas; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_htmltable.columnas IS 'Numero de columnas en la tabla si es cero pone las columnas de la tabla maestra';


--
-- Name: menus_htmltable_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY menus_htmltable
    ADD CONSTRAINT menus_htmltable_pkey PRIMARY KEY (idhtmltable);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_log; Type: TABLE; Schema: public; Owner: jlv; Tablespace: 
--

CREATE TABLE menus_log (
    idlog serial NOT NULL,
    idmenu integer NOT NULL,
    movto character(1),
    sql text,
    fecha_alta timestamp(0) with time zone DEFAULT ('now'::text)::timestamp(0) with time zone,
    usuario_alta character varying(20) DEFAULT getpgusername()
);


ALTER TABLE public.menus_log OWNER TO jlv;

--
-- Name: TABLE menus_log; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON TABLE menus_log IS 'tabla para dar seguimiento a lo que los usuarios hacen en el sistema';


--
-- Name: COLUMN menus_log.idlog; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_log.idlog IS 'registro en la tabla';


--
-- Name: COLUMN menus_log.idmenu; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_log.idmenu IS 'Numero de menu que utilizo el usuario';


--
-- Name: COLUMN menus_log.movto; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_log.movto IS 'Movimiento que hizo el usuario';


--
-- Name: COLUMN menus_log.sql; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_log.sql IS 'Movimiento que hizo el usuario';


--
-- Name: COLUMN menus_log.fecha_alta; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_log.fecha_alta IS 'Fecha en que hizo el movimiento el usuario';


--
-- Name: COLUMN menus_log.usuario_alta; Type: COMMENT; Schema: public; Owner: jlv
--

COMMENT ON COLUMN menus_log.usuario_alta IS 'Usuario hizo el alta ';


--
-- Name: menus_log_pkey; Type: CONSTRAINT; Schema: public; Owner: jlv; Tablespace: 
--

ALTER TABLE ONLY menus_log
    ADD CONSTRAINT menus_log_pkey PRIMARY KEY (idlog);


--
-- Name: ak1_menus_log; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak1_menus_log ON menus_log USING btree (idmenu);


--
-- Name: ak2_menus_log; Type: INDEX; Schema: public; Owner: jlv; Tablespace: 
--

CREATE INDEX ak2_menus_log ON menus_log USING btree (usuario_alta);


--
-- PostgreSQL database dump complete
--

CREATE SEQUENCE his_menus_pg_group_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE his_cat_usuarios_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE his_cat_usuarios_pg_group_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE cat_bitacora_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE cat_preguntas_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 100 CACHE 1;
GRANT USAGE ON SCHEMA contra TO temporal;
GRANT SELECT ON TABLE campos TO temporal;
GRANT ALL ON TABLE cat_bitacora TO temporal;
GRANT ALL ON TABLE cat_bitacora_seq TO temporal;
GRANT ALL ON TABLE cat_preguntas TO temporal;
GRANT SELECT ON TABLE cat_preguntas_seq TO temporal;
GRANT ALL ON TABLE cat_usuarios TO temporal;
GRANT INSERT ON TABLE his_cat_usuarios TO temporal;
GRANT ALL ON TABLE his_cat_usuarios_seq TO temporal;
GRANT SELECT ON TABLE menus TO temporal;
GRANT SELECT ON TABLE menus_campos TO temporal;
GRANT SELECT ON TABLE menus_campos_eventos TO temporal;
GRANT SELECT ON TABLE menus_eventos TO temporal;
GRANT SELECT ON TABLE menus_htmltable TO temporal;
GRANT SELECT ON TABLE menus_log TO temporal;
GRANT SELECT ON TABLE menus_movtos TO temporal;
GRANT SELECT ON TABLE menus_pg_group TO temporal;
GRANT SELECT ON TABLE menus_seguimiento TO temporal;
GRANT SELECT ON TABLE menus_shadow TO temporal;
GRANT SELECT ON TABLE menus_subvistas TO temporal;
GRANT SELECT ON TABLE tablas TO temporal;
GRANT INSERT,SELECT ON TABLE pg_shadow TO temporal;
ALTER FUNCTION public.alta_menus_campos() OWNER TO jlv;
ALTER FUNCTION public.baja_menus() OWNER TO jlv;
ALTER FUNCTION public.bajacuentas_contables() OWNER TO jlv;
ALTER FUNCTION public.bajafcobros() OWNER TO jlv;
ALTER FUNCTION public.bajafunciones_acceso() OWNER TO jlv;
ALTER FUNCTION public.bloquea_usuario(text) OWNER TO jlv;
ALTER FUNCTION public.checacobros(smallint, smallint) OWNER TO jlv;
ALTER FUNCTION public.checacuenta(character) OWNER TO jlv;
ALTER FUNCTION public.cor_centavos_upd(smallint, date, integer, character, character, numeric) OWNER TO jlv;
ALTER FUNCTION public.cor_centavos_upd(smallint, date, integer, character, character, real) OWNER TO jlv;
ALTER FUNCTION public.cor_centavos_upd(smallint, date, integer, character, character, bigint) OWNER TO jlv;
ALTER FUNCTION public.cor_predial(date, date) OWNER TO jlv;
ALTER FUNCTION public.debe_cambiarpwd(text, integer) OWNER TO jlv;
ALTER FUNCTION public.des_predial(date, date) OWNER TO jlv;
ALTER FUNCTION public.estatus_usuario(text) OWNER TO jlv;
ALTER FUNCTION public.grababitacora(bigint, smallint, smallint, date, date, text) OWNER TO jlv;
ALTER FUNCTION public.grababitacora(bigint, smallint, smallint, smallint, date, date, text) OWNER TO jlv;
ALTER FUNCTION public.modicuentas_contables() OWNER TO jlv;
ALTER FUNCTION public.modifcobros() OWNER TO jlv;
ALTER FUNCTION public.modifunciones_acceso() OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta(smallint, smallint, date, date, character, integer) OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta(smallint, smallint, date, date, character, integer, integer, integer) OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta_at(smallint, smallint, date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta_todo(smallint, smallint, date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta_todo1(date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta_todo_c(smallint, smallint, date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idcuenta_todo_c1(date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idpago(smallint, smallint, date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idpago1(date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_idpagoxtipo(date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_prueba(smallint, smallint, date, date) OWNER TO jlv;
ALTER FUNCTION public.sum_prueba(smallint, smallint, date, date, character) OWNER TO jlv;
ALTER FUNCTION public.tiene_grupo(text) OWNER TO jlv;
ALTER FUNCTION public.upa_usuario_fecha() OWNER TO jlv;
ALTER FUNCTION public.usuario_bloqueado(text) OWNER TO jlv;
ALTER FUNCTION public.val_cobro(smallint, smallint, date, date, character, integer) OWNER TO jlv;
ALTER FUNCTION public.val_cobrotodo(date, date) OWNER TO jlv;
ALTER FUNCTION public.val_idpago(smallint, smallint, date, date, character, integer) OWNER TO jlv;
ALTER FUNCTION public.val_idpagocobro(smallint, smallint, date, date, character, integer) OWNER TO jlv;
ALTER FUNCTION public.val_idpagopl(smallint, smallint, date, date, character, integer) OWNER TO jlv;
ALTER FUNCTION public.val_idpagotodo(date, date) OWNER TO jlv;
ALTER FUNCTION public.val_idpagotodo_o(date, date) OWNER TO jlv;
ALTER FUNCTION public.val_soldatos() OWNER TO jlv;
ALTER FUNCTION public.valida_res_des(text, text) OWNER TO jlv;
ALTER TABLE contra.cat_asuntos OWNER TO jlv;
ALTER TABLE contra.cat_organizaciones OWNER TO jlv;
ALTER TABLE contra.cat_personas OWNER TO jlv;
ALTER TABLE contra.cat_tipo_archivo OWNER TO jlv;
ALTER TABLE contra.cat_tipo_referencia OWNER TO jlv;
ALTER TABLE contra.cat_tipo_tramite OWNER TO jlv;
ALTER TABLE contra.cat_tipodoctos OWNER TO jlv;
ALTER TABLE public.accesorios OWNER TO jlv;
ALTER TABLE public.accesorios_seq OWNER TO jlv;
ALTER TABLE public.atls OWNER TO jlv;
ALTER TABLE public.bajdomicilios OWNER TO jlv;
ALTER TABLE public.bajpadrones OWNER TO jlv;
ALTER TABLE public.cat_bitacora OWNER TO jlv;
ALTER TABLE public.cat_clasehos OWNER TO jlv;
ALTER TABLE public.cat_control_procesos OWNER TO jlv;
ALTER TABLE public.cat_control_procesos_seq OWNER TO jlv;
ALTER TABLE public.cat_dependencias OWNER TO jlv;
ALTER TABLE public.cat_dependencias_seq OWNER TO jlv;
ALTER TABLE public.cat_estados_cp OWNER TO jlv;
ALTER TABLE public.cat_formaspago OWNER TO jlv;
ALTER TABLE public.cat_fucliqdef OWNER TO jlv;
ALTER TABLE public.cat_movtos OWNER TO jlv;
ALTER TABLE public.cat_movtos_seq OWNER TO jlv;
ALTER TABLE public.cat_participaciones OWNER TO jlv;
ALTER TABLE public.cat_personas OWNER TO jlv;
ALTER TABLE public.cat_personas_seq OWNER TO jlv;
ALTER TABLE public.cat_preguntas OWNER TO jlv;
ALTER TABLE public.cat_preguntas_seq OWNER TO jlv;
ALTER TABLE public.cat_procesos OWNER TO jlv;
ALTER TABLE public.cat_procesos_seq OWNER TO jlv;
ALTER TABLE public.cat_puestos OWNER TO jlv;
ALTER TABLE public.cat_rangos_seq OWNER TO jlv;
ALTER TABLE public.cat_rangospol_seq OWNER TO jlv;
ALTER TABLE public.cat_ranpr OWNER TO jlv;
ALTER TABLE public.cat_ranpr_at OWNER TO jlv;
ALTER TABLE public.cat_sumarizacion OWNER TO jlv;
ALTER TABLE public.cat_sumarizacion_seq OWNER TO jlv;
ALTER TABLE public.cat_tipodoctos OWNER TO jlv;
ALTER TABLE public.cat_tipohos OWNER TO jlv;
ALTER TABLE public.cat_tiposcampos OWNER TO jlv;
ALTER TABLE public.cat_tiposcampos_seq OWNER TO jlv;
ALTER TABLE public.cat_tiposcobros OWNER TO jlv;
ALTER TABLE public.cat_tiposcobros_seq OWNER TO jlv;
ALTER TABLE public.cat_tiposcobroscampos OWNER TO jlv;
ALTER TABLE public.cat_tiposcobroscamposdomicilio OWNER TO jlv;
ALTER TABLE public.cat_tiposcobroscampospadron OWNER TO jlv;
ALTER TABLE public.cat_tiposcobrosusuarios OWNER TO jlv;
ALTER TABLE public.cat_tiposrfc OWNER TO jlv;
ALTER TABLE public.cat_usuarios_pg_group OWNER TO jlv;
ALTER TABLE public.catcolonias OWNER TO jlv;
ALTER TABLE public.catdelegaciones OWNER TO jlv;
ALTER TABLE public.catdomicilios OWNER TO jlv;
ALTER TABLE public.catpadrones OWNER TO jlv;
ALTER TABLE public.certi_atl_puestos OWNER TO jlv;
ALTER TABLE public.certi_cuenta OWNER TO jlv;
ALTER TABLE public.certi_fcp OWNER TO jlv;
ALTER TABLE public.certi_lc OWNER TO jlv;
ALTER TABLE public.cobros OWNER TO jlv;
ALTER TABLE public.cobros_campos OWNER TO jlv;
ALTER TABLE public.cobros_campos_seq OWNER TO jlv;
ALTER TABLE public.cobros_enca OWNER TO jlv;
ALTER TABLE public.cobros_enca_cajas OWNER TO jlv;
ALTER TABLE public.cobros_enca_cap OWNER TO jlv;
ALTER TABLE public.cobros_enca_che OWNER TO jlv;
ALTER TABLE public.cobros_enca_ibm OWNER TO jlv;
ALTER TABLE public.cobros_enca_idcuenta OWNER TO jlv;
ALTER TABLE public.cobros_enca_idcuenta_c OWNER TO jlv;
ALTER TABLE public.cobros_enca_idpago OWNER TO jlv;
ALTER TABLE public.cobros_enca_idpagoxtipo OWNER TO jlv;
ALTER TABLE public.cobros_enca_paragua OWNER TO jlv;
ALTER TABLE public.cobros_enca_ssp OWNER TO jlv;
ALTER TABLE public.cobros_enca_tra OWNER TO jlv;
ALTER TABLE public.cobros_enca_xtiposcobro OWNER TO jlv;
ALTER TABLE public.cobros_paragua OWNER TO jlv;
ALTER TABLE public.cobrospru OWNER TO jlv;
ALTER TABLE public.cobrosxcuentapru OWNER TO jlv;
ALTER TABLE public.cuentas_contables_seq OWNER TO jlv;
ALTER TABLE public.delegaciones OWNER TO jlv;
ALTER TABLE public.depe_fcobros OWNER TO jlv;
ALTER TABLE public.depe_fcobros_seq OWNER TO jlv;
ALTER TABLE public.descuadres OWNER TO jlv;
ALTER TABLE public.diassininf OWNER TO jlv;
ALTER TABLE public.dif_idcuenta_vs_enca OWNER TO jlv;
ALTER TABLE public.estados OWNER TO jlv;
ALTER TABLE public.estados_procesos OWNER TO jlv;
ALTER TABLE public.estados_usuarios OWNER TO jlv;
ALTER TABLE public.eventos OWNER TO jlv;
ALTER TABLE public.eventos_campos OWNER TO jlv;
ALTER TABLE public.fcobros_cajas OWNER TO jlv;
ALTER TABLE public.fcobrosam OWNER TO jlv;
ALTER TABLE public.funciones_acceso_seq OWNER TO jlv;
ALTER TABLE public.funciones_cuentas OWNER TO jlv;
ALTER TABLE public.funciones_cuentas_seq OWNER TO jlv;
ALTER TABLE public.gestion OWNER TO jlv;
ALTER TABLE public.his_cambios_pwd OWNER TO jlv;
ALTER TABLE public.his_cat_control_procesos OWNER TO jlv;
ALTER TABLE public.his_cat_usuarios_pg_group OWNER TO jlv;
ALTER TABLE public.his_cat_usuarios_pg_group_seq OWNER TO jlv;
ALTER TABLE public.his_cobros OWNER TO jlv;
ALTER TABLE public.his_cobros_nuevo OWNER TO jlv;
ALTER TABLE public.his_cobros_nuevo_sp OWNER TO jlv;
ALTER TABLE public.his_menus OWNER TO jlv;
ALTER TABLE public.his_menus_pg_group OWNER TO jlv;
ALTER TABLE public.his_menus_pg_group_seq OWNER TO jlv;
ALTER TABLE public.his_menus_pg_tables OWNER TO jlv;
ALTER TABLE public.his_tablas_cambios OWNER TO jlv;
ALTER TABLE public.idcuenta_at OWNER TO jlv;
ALTER TABLE public.idcuenta_at_tot OWNER TO jlv;
ALTER TABLE public.idcuenta_teso OWNER TO jlv;
ALTER TABLE public.idcuenta_teso_tot OWNER TO jlv;
ALTER TABLE public.matriz1 OWNER TO jlv;
ALTER TABLE public.menus OWNER TO jlv;
ALTER TABLE public.menus_campos OWNER TO jlv;
ALTER TABLE public.menus_campos_eventos OWNER TO jlv;
ALTER TABLE public.menus_eventos OWNER TO jlv;
ALTER TABLE public.menus_log OWNER TO jlv;
ALTER TABLE public.menus_movtos OWNER TO jlv;
ALTER TABLE public.menus_pg_group OWNER TO jlv;
ALTER TABLE public.menus_pg_tables OWNER TO jlv;
ALTER TABLE public.menus_presentacion OWNER TO jlv;
ALTER TABLE public.menus_seguimiento OWNER TO jlv;
ALTER TABLE public.menus_seq OWNER TO jlv;
ALTER TABLE public.menus_shadow OWNER TO jlv;
ALTER TABLE public.menus_subvistas OWNER TO jlv;
ALTER TABLE public.menus_tiempos OWNER TO jlv;
ALTER TABLE public.otrosacceso OWNER TO jlv;
ALTER TABLE public.otrosacceso_seq OWNER TO jlv;
ALTER TABLE public.padron_nom OWNER TO jlv;
ALTER TABLE public.pagnomtem OWNER TO jlv;
ALTER TABLE public.parametros_gen OWNER TO jlv;
ALTER TABLE public.pings_servidor OWNER TO jlv;
ALTER TABLE public.predial_adeudos OWNER TO jlv;
ALTER TABLE public.predpagostemporal OWNER TO jlv;
ALTER TABLE public.seq_fcobros OWNER TO jlv;
ALTER TABLE public.sol_auxiliar_seq OWNER TO jlv;
ALTER TABLE public.soldatos OWNER TO jlv;
ALTER TABLE public.subsisdios_ant OWNER TO jlv;
ALTER TABLE public.tcases OWNER TO jlv;
ALTER TABLE public.wlcuentas OWNER TO jlv;
ALTER TABLE public.wlnada OWNER TO jlv;
ALTER TABLE public.wlregresa OWNER TO jlv;
ALTER TABLE public.wltotal OWNER TO jlv;
ALTER TABLE tenencia.cobros_enca_ten OWNER TO jlv;
GRANT USAGE ON SCHEMA contra TO jlv;
GRANT USAGE ON SCHEMA vehiculos TO jlv;
GRANT ALL ON TABLE cat_asuntos TO jlv;
GRANT ALL ON TABLE cat_asuntos_id_cveasunto_seq TO jlv;
GRANT ALL ON TABLE cat_organizaciones TO jlv;
GRANT ALL ON TABLE cat_organizaciones_id_organizacion_seq TO jlv;
GRANT ALL ON TABLE cat_personas TO jlv;
GRANT ALL ON TABLE cat_personas_id_persona_seq TO jlv;
GRANT ALL ON TABLE cat_personas_seq TO jlv;
GRANT ALL ON TABLE cat_tipo_archivo TO jlv;
GRANT ALL ON TABLE cat_tipo_archivo_id_tipoarc_seq TO jlv;
GRANT ALL ON TABLE cat_tipo_referencia TO jlv;
GRANT ALL ON TABLE cat_tipo_referencia_id_tiporef_seq TO jlv;
GRANT ALL ON TABLE cat_tipo_tramite TO jlv;
GRANT ALL ON TABLE cat_tipo_tramite_id_tipotra_seq TO jlv;
GRANT ALL ON TABLE cat_tipodoctos TO jlv;
GRANT ALL ON TABLE cat_tipodoctos_seq TO jlv;
GRANT ALL ON TABLE gestion TO jlv;
GRANT ALL ON TABLE gestion_seq TO jlv;
GRANT ALL ON TABLE ope_archivos TO jlv;
GRANT ALL ON TABLE ope_archivos_id_archivo_seq TO jlv;
GRANT ALL ON TABLE ope_referencias TO jlv;
GRANT ALL ON TABLE ope_referencias_id_referencia_seq TO jlv;
GRANT ALL ON TABLE ope_referencias_seq TO jlv;
GRANT ALL ON TABLE ope_turnados TO jlv;
GRANT ALL ON TABLE ope_turnados_id_turnado_seq TO jlv;
GRANT ALL ON TABLE ope_turnados_seq TO jlv;
GRANT ALL ON TABLE referencias TO jlv;
GRANT ALL ON TABLE referencias_seq TO jlv;
GRANT SELECT ON TABLE accesorios TO jlv;
GRANT ALL ON TABLE accesorios_seq TO jlv;
GRANT SELECT,UPDATE ON TABLE adeudos TO jlv;
GRANT SELECT ON TABLE atls TO jlv;
GRANT ALL ON TABLE bajdomicilios TO jlv;
GRANT ALL ON TABLE bajpadrones TO jlv;
GRANT SELECT ON TABLE campos TO jlv;
GRANT INSERT,SELECT,UPDATE ON TABLE cat_bitacora TO jlv;
GRANT ALL ON TABLE cat_bitacora_seq TO jlv;
GRANT SELECT ON TABLE cat_clasehos TO jlv;
GRANT SELECT ON TABLE cat_clasehos_idclasehos_seq TO jlv;
GRANT ALL ON TABLE cat_control_procesos TO jlv;
GRANT ALL ON TABLE cat_control_procesos_seq TO jlv;
GRANT ALL ON TABLE cat_dependencias TO jlv;
GRANT ALL ON TABLE cat_dependencias_seq TO jlv;
GRANT SELECT ON TABLE cat_estados_cp TO jlv;
GRANT SELECT ON TABLE cat_estatus TO jlv;
GRANT ALL ON TABLE cat_formaspago TO jlv;
GRANT SELECT ON TABLE cat_fucliqdef TO jlv;
GRANT SELECT ON TABLE cat_fucliqdef_idfucliqdef_seq TO jlv;
GRANT ALL ON TABLE cat_motivoscam TO jlv;
GRANT ALL ON TABLE cat_movtos TO jlv;
GRANT SELECT ON TABLE cat_movtosibm TO jlv;
GRANT SELECT ON TABLE cat_participaciones TO jlv;
GRANT SELECT ON TABLE cat_personas TO jlv;
GRANT SELECT ON TABLE cat_personas_seq TO jlv;
GRANT SELECT ON TABLE cat_preguntas TO jlv;
GRANT ALL ON TABLE cat_preguntas_seq TO jlv;
GRANT SELECT ON TABLE cat_procesos TO jlv;
GRANT SELECT ON TABLE cat_procesos_seq TO jlv;
GRANT ALL ON TABLE cat_puestos TO jlv;
GRANT ALL ON TABLE cat_puestos_id_puesto_seq TO jlv;
GRANT ALL ON TABLE cat_rangos TO jlv;
GRANT ALL ON TABLE cat_rangos_seq TO jlv;
GRANT ALL ON TABLE cat_ranpr TO jlv;
GRANT ALL ON TABLE cat_ranpr_at TO jlv;
GRANT ALL ON TABLE cat_ranpr_at_idranprat_seq TO jlv;
GRANT ALL ON TABLE cat_ranpr_idranpr_seq TO jlv;
GRANT ALL ON TABLE cat_sumarizacion TO jlv;
GRANT ALL ON TABLE cat_tipodoctos TO jlv;
GRANT SELECT ON TABLE cat_tipohos TO jlv;
GRANT ALL ON TABLE cat_tiposcampos TO jlv;
GRANT SELECT ON TABLE cat_tiposcobros TO jlv;
GRANT SELECT ON TABLE cat_tiposcobros_idregcambio_seq TO jlv;
GRANT SELECT ON TABLE cat_tiposcobros_seq TO jlv;
GRANT ALL ON TABLE cat_tiposcobroscampos TO jlv;
GRANT SELECT ON TABLE cat_tiposcobroscamposdomicilio TO jlv;
GRANT ALL ON TABLE cat_tiposcobroscampospadron TO jlv;
GRANT ALL ON TABLE cat_tiposcobrosusuarios TO jlv;
GRANT SELECT ON TABLE cat_tiposrfc TO jlv;
GRANT SELECT ON TABLE cat_tiposrfc_idtiporfc_seq TO jlv;
GRANT ALL ON TABLE cat_usuarios TO jlv;
GRANT ALL ON TABLE cat_usuarios_pg_group TO jlv;
GRANT ALL ON TABLE catcolonias TO jlv;
GRANT ALL ON TABLE catcolonias_idcolonia_seq TO jlv;
GRANT ALL ON TABLE catdelegaciones TO jlv;
GRANT ALL ON TABLE catdomicilios TO jlv;
GRANT ALL ON TABLE catdomicilios_idregcambio_seq TO jlv;
GRANT ALL ON TABLE catlineas TO jlv;
GRANT ALL ON TABLE catlineas_idlinea_seq TO jlv;
GRANT ALL ON TABLE catmarcas TO jlv;
GRANT ALL ON TABLE catmarcas_idmarca_seq TO jlv;
GRANT ALL ON TABLE catpadrones TO jlv;
GRANT ALL ON TABLE catpadrones_idregcambio_seq TO jlv;
GRANT ALL ON TABLE catvehiculos TO jlv;
GRANT ALL ON TABLE catversion TO jlv;
GRANT ALL ON TABLE catversion_idversion_seq TO jlv;
GRANT ALL ON TABLE certi_atl_puestos TO jlv;
GRANT ALL ON TABLE certi_cuenta TO jlv;
GRANT ALL ON TABLE certi_cuenta_idcerticuenta_seq TO jlv;
GRANT ALL ON TABLE certi_enca TO jlv;
GRANT ALL ON TABLE certi_enca_idcerti_seq TO jlv;
GRANT ALL ON TABLE certi_enca_idregcambio_seq TO jlv;
GRANT ALL ON TABLE certi_estados TO jlv;
GRANT ALL ON TABLE certi_estados_estado_seq TO jlv;
GRANT ALL ON TABLE certi_fcp TO jlv;
GRANT ALL ON TABLE certi_fcp_idcertifcp_seq TO jlv;
GRANT ALL ON TABLE certi_impresiones TO jlv;
GRANT ALL ON TABLE certi_lc TO jlv;
GRANT ALL ON TABLE certi_lc_idcertilc_seq TO jlv;
GRANT SELECT ON TABLE chequeo_cxc TO jlv;
GRANT ALL ON TABLE cheques TO jlv;
GRANT SELECT ON TABLE cheques_partidas TO jlv;
GRANT ALL ON TABLE cheques_seq TO jlv;
GRANT SELECT,UPDATE ON TABLE cobros TO jlv;
GRANT SELECT ON TABLE cobros_campos TO jlv;
GRANT SELECT ON TABLE cobros_campos_seq TO jlv;
GRANT SELECT,UPDATE ON TABLE cobros_cance2 TO jlv;
GRANT ALL ON TABLE cobros_captura TO jlv;
GRANT ALL ON TABLE cobros_captura_enca TO jlv;
GRANT SELECT ON TABLE cobros_captura_estados TO jlv;
GRANT ALL ON TABLE cobros_captura_id_registro_seq TO jlv;
GRANT ALL ON TABLE cobros_captura_importes TO jlv;
GRANT ALL ON TABLE cobros_enca TO jlv;
GRANT SELECT ON TABLE cobros_enca_agua TO jlv;
GRANT ALL ON TABLE cobros_enca_agua_cuenta TO jlv;
GRANT ALL ON TABLE cobros_enca_cajas TO jlv;
GRANT ALL ON TABLE cobros_enca_cap TO jlv;
GRANT ALL ON TABLE cobros_enca_che TO jlv;
GRANT ALL ON TABLE cobros_enca_ibm TO jlv;
GRANT ALL ON TABLE cobros_enca_idcuenta TO jlv;
GRANT SELECT ON TABLE cobros_enca_idcuenta_c TO jlv;
GRANT ALL ON TABLE cobros_enca_idpago TO jlv;
GRANT ALL ON TABLE cobros_enca_idpagoxtipo TO jlv;
GRANT ALL ON TABLE cobros_enca_paragua TO jlv;
GRANT ALL ON TABLE cobros_enca_ssp TO jlv;
GRANT SELECT ON TABLE cobros_enca_tra TO jlv;
GRANT ALL ON TABLE cobros_enca_xtiposcobro TO jlv;
GRANT ALL ON TABLE cobros_movtos TO jlv;
GRANT ALL ON TABLE cobros_movtos_seq TO jlv;
GRANT ALL ON TABLE cobros_paragua TO jlv;
GRANT SELECT,UPDATE ON TABLE cobrospru TO jlv;
GRANT SELECT,UPDATE ON TABLE cobrosxcuenta TO jlv;
GRANT SELECT,UPDATE ON TABLE cobrosxcuentapru TO jlv;
GRANT SELECT ON TABLE cuentas_contables_seq TO jlv;
GRANT ALL ON TABLE cuentas_contablesam TO jlv;
GRANT ALL ON TABLE delegaciones TO jlv;
GRANT SELECT ON TABLE depe_fcobros TO jlv;
GRANT ALL ON TABLE descuadres TO jlv;
GRANT ALL ON TABLE diassininf TO jlv;
GRANT ALL ON TABLE dif_idcuenta_vs_enca TO jlv;
GRANT ALL ON TABLE esta_certi TO jlv;
GRANT SELECT ON TABLE estados TO jlv;
GRANT SELECT ON TABLE estados_usuarios TO jlv;
GRANT ALL ON TABLE eventos TO jlv;
GRANT ALL ON TABLE eventos_campos TO jlv;
GRANT ALL ON TABLE fcobrosam TO jlv;
GRANT ALL ON TABLE fechas_venci TO jlv;
GRANT SELECT ON TABLE funciones_acceso_seq TO jlv;
GRANT SELECT ON TABLE funciones_accesoam TO jlv;
GRANT ALL ON TABLE funciones_cuentas TO jlv;
GRANT ALL ON TABLE gestion TO jlv;
GRANT ALL ON TABLE grupos_permisos TO jlv;
GRANT INSERT,SELECT ON TABLE his_cambios_pwd TO jlv;
GRANT ALL ON TABLE his_cat_control_procesos TO jlv;
GRANT ALL ON TABLE his_cat_usuarios TO jlv;
GRANT ALL ON TABLE his_cat_usuarios_pg_group TO jlv;
GRANT ALL ON TABLE his_cat_usuarios_pg_group_seq TO jlv;
GRANT ALL ON TABLE his_cat_usuarios_seq TO jlv;
GRANT INSERT ON TABLE his_certi_cobros TO jlv;
GRANT INSERT ON TABLE his_certi_enca TO jlv;
GRANT ALL ON TABLE his_cheques TO jlv;
GRANT ALL ON TABLE his_cobros TO jlv;
GRANT INSERT,SELECT ON TABLE his_cobros_nuevo TO jlv;
GRANT INSERT,SELECT ON TABLE his_cobros_nuevo_sp TO jlv;
GRANT ALL ON TABLE his_consulta_cobros TO jlv;
GRANT ALL ON TABLE his_cuentas_contablesam TO jlv;
GRANT SELECT ON TABLE his_fcobrosam TO jlv;
GRANT ALL ON TABLE his_funciones_accesoam TO jlv;
GRANT ALL ON TABLE his_menus TO jlv;
GRANT ALL ON TABLE his_menus_pg_group TO jlv;
GRANT ALL ON TABLE his_menus_pg_group_seq TO jlv;
GRANT ALL ON TABLE his_menus_pg_tables TO jlv;
GRANT INSERT,SELECT ON TABLE his_tablas_cambios TO jlv;
GRANT ALL ON TABLE idcuenta_at TO jlv;
GRANT ALL ON TABLE idcuenta_at_tot TO jlv;
GRANT ALL ON TABLE idcuenta_teso TO jlv;
GRANT ALL ON TABLE idcuenta_teso_tot TO jlv;
GRANT ALL ON TABLE menus TO jlv;
GRANT ALL ON TABLE menus_campos TO jlv;
GRANT ALL ON TABLE menus_campos_eventos TO jlv;
GRANT ALL ON TABLE menus_campos_idcampo_seq TO jlv;
GRANT ALL ON TABLE menus_eventos TO jlv;
GRANT ALL ON TABLE menus_eventos_idmenus_eventos_seq TO jlv;
GRANT SELECT ON TABLE menus_htmltable TO jlv;
GRANT SELECT ON TABLE menus_htmltable_idhtmltable_seq TO jlv;
GRANT INSERT ON TABLE menus_log TO jlv;
GRANT ALL ON TABLE menus_log_idlog_seq TO jlv;
GRANT ALL ON TABLE menus_movtos TO jlv;
GRANT ALL ON TABLE menus_pg_group TO jlv;
GRANT ALL ON TABLE menus_pg_tables TO jlv;
GRANT SELECT ON TABLE menus_presentacion TO jlv;
GRANT SELECT ON TABLE menus_seguimiento TO jlv;
GRANT ALL ON TABLE menus_seguimiento_idseguimietno_seq TO jlv;
GRANT ALL ON TABLE menus_seq TO jlv;
GRANT ALL ON TABLE menus_shadow TO jlv;
GRANT ALL ON TABLE menus_subvistas TO jlv;
GRANT SELECT ON TABLE menus_tiempos TO jlv;
GRANT SELECT ON TABLE menus_tiempos_idtiempo_seq TO jlv;
GRANT ALL ON TABLE otrosacceso TO jlv;
GRANT SELECT ON TABLE padron_nom TO jlv;
GRANT ALL ON TABLE parametros_gen TO jlv;
GRANT ALL ON TABLE pings_servidor TO jlv;
GRANT ALL ON TABLE predial_adeudos TO jlv;
GRANT ALL ON TABLE sol_auxiliar TO jlv;
GRANT ALL ON TABLE sol_auxiliar_intranet TO jlv;
GRANT ALL ON TABLE sol_auxiliar_intranet_seq TO jlv;
GRANT ALL ON TABLE sol_auxiliar_seq TO jlv;
GRANT ALL ON TABLE sol_poliza TO jlv;
GRANT ALL ON TABLE subsidios TO jlv;
GRANT ALL ON TABLE subsisdios_ant TO jlv;
GRANT SELECT ON TABLE tablas TO jlv;
GRANT SELECT ON TABLE tcases TO jlv;
GRANT SELECT ON TABLE v_atls TO jlv;
GRANT SELECT ON TABLE v_atls_1 TO jlv;
GRANT SELECT ON TABLE v_atls_puestos TO jlv;
GRANT SELECT ON TABLE v_cambios TO jlv;
GRANT SELECT ON TABLE v_certi TO jlv;
GRANT SELECT ON TABLE v_cobros_lc TO jlv;
GRANT SELECT ON TABLE v_cobrosxcuenta TO jlv;
GRANT SELECT ON TABLE v_cobrosxnomina TO jlv;
GRANT SELECT ON TABLE v_cobrosxpredial TO jlv;
GRANT SELECT ON TABLE v_delegaciones TO jlv;
GRANT SELECT ON TABLE v_fcobrosam TO jlv;
GRANT ALL ON TABLE v_funciones_accesoam TO jlv;
GRANT ALL ON TABLE v_hospedaje TO jlv;
GRANT SELECT ON TABLE v_lcsindes TO jlv;
GRANT ALL ON TABLE verificentros TO jlv;
GRANT ALL ON TABLE cobros_enca_ten TO jlv;
GRANT ALL ON TABLE pad_tenencia_07 TO jlv;
GRANT USAGE ON SCHEMA pg_catalog TO jlv;
GRANT SELECT ON TABLE pg_shadow TO jlv;
