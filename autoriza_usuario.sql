--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = forapi, pg_catalog;

--
-- Name: autoriza_usuario(text); Type: FUNCTION; Schema: forapi; Owner: postgres
--

CREATE or replace FUNCTION autoriza_usuario(text) RETURNS character varying
    LANGUAGE plpgsql
    AS $_$DECLARE
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
--     wlsentencia=' grant usage on schema contra to ' ||  $1 ;    -- 2007-03-23
--     execute wlsentencia;                                          -- 2007-03-23 
--     2007-07-20  jlv lo modifique para que si la tabla viene en espacion no haga el grant
            raise notice ' entro  % ', $1  ;
     select count(*) into wlcuantos from forapi.cat_usuarios_pg_group
            where trim(usename)=trim($1);
     if wlcuantos=0 then
        return 'No existe grupo asignado al usuario';
     end if;
     update forapi.cat_usuarios set estatus=1 where trim(usename)=trim($1);
--     for mireg  in select   mpgt.*,me.descripcion 
     for mireg  in select 
        case when (strpos(mpgt.tablename,'.')=0 and strpos(mpgt.tablename,'pg_')!=1)
	           then mpgt.nspname||'.'||mpgt.tablename else mpgt.tablename end as tablename
                   , me.descripcion
                   , sum(case when mpgt.tall='1' then 1 else 0 end) as tall
                   , sum(case when mpgt.tselect='1' then 1 else 0 end) as tselect
                   , sum(case when mpgt.tinsert='1' then 1 else 0 end) as tinsert
                   , sum(case when mpgt.tupdate='1' then 1 else 0 end) as tupdate
                   , sum(case when mpgt.tdelete='1' then 1 else 0 end) as tdelete
                   from forapi.cat_usuarios_pg_group as cupg
		   , forapi.menus_pg_tables as mpgt
                            ,forapi.menus as me
                   where trim(cupg.usename)=trim($1) 
--                   and cupg.grosysid = mpg.grosysid
                   and me.idmenu  = mpgt.idmenu
                   and me.idmenu  in ((select idmenu from forapi.menus_pg_group as mpg where
		                                  cupg.grosysid = mpg.grosysid
                                     union
				     select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)
                                     --group by 1
                                     union
				     select mc.altaautomatico_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.altaautomatico_idmenu>0
				     union
				     select distinct mc.fuente_busqueda_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.fuente_busqueda_idmenu>0
				     union
				     select distinct mc.idsubvista from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.idsubvista>0
				     union
				     select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select mc.idsubvista from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.idsubvista>0
						union
					     select distinct mc.altaautomatico_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.altaautomatico_idmenu>0
						union
					     select distinct mc.fuente_busqueda_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.fuente_busqueda_idmenu>0)
                                     group by 1
                                     union
                                     select distinct mc2.altaautomatico_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
						(select idsubvista from forapi.menus_subvistas as ms where
							ms.idmenu in 
							(select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)) and mc2.altaautomatico_idmenu>0
                                     union
                                     select distinct mc2.fuente_busqueda_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
						(select idsubvista from forapi.menus_subvistas as ms where
							ms.idmenu in 
							(select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))  and mc2.fuente_busqueda_idmenu>0
					union
					select idsubvista from forapi.menus_subvistas as mss where
				            mss.idmenu in 
					    ( select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))
                                     union
                                     select distinct mc2.altaautomatico_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
						(select idsubvista from forapi.menus_subvistas as mss where
				            mss.idmenu in 
					    ( select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))) and mc2.altaautomatico_idmenu>0
                                     union
                                     select distinct mc2.fuente_busqueda_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
						(select idsubvista from forapi.menus_subvistas as mss where
				            mss.idmenu in 
					    ( select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)))  and mc2.fuente_busqueda_idmenu>0
					union
					select idsubvista from forapi.menus_subvistas as msss where
				            msss.idmenu in 
					    (select idsubvista from forapi.menus_subvistas as mss where
				            mss.idmenu in 
					    ( select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)))
                                     union
                                     select distinct mc2.altaautomatico_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
						(select idsubvista from forapi.menus_subvistas as msss where
				            msss.idmenu in 
					    (select idsubvista from forapi.menus_subvistas as mss where
				            mss.idmenu in 
					    ( select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)))) and mc2.altaautomatico_idmenu>0
                                     union
                                     select distinct mc2.fuente_busqueda_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
						(select idsubvista from forapi.menus_subvistas as msss where
				            msss.idmenu in 
					    (select idsubvista from forapi.menus_subvistas as mss where
				            mss.idmenu in 
					    ( select idsubvista from forapi.menus_subvistas as ms where
				            ms.idmenu in 
					    (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))))  and mc2.fuente_busqueda_idmenu>0
				     ))
                   group by 1,2
                   order by 1,2
                   loop
--         mireg.sentencia=substr(mireg.sentencia,1,strpos(mireg.sentencia,'<')-1) || $1;
--         mireg.sentencia=substr(mireg.sentencia,1,strpos(mireg.sentencia,'<')-1) || $1;
         wlper='';
         wlsentencia='';
--         raise notice ' tablename % tselect % tall % ',  mireg.tablename, mireg.tselect ,
--               mireg.tall ;
           if mireg.tall>0 then
              wlper=' all ';
           else
           if mireg.tselect>0 then
              if length(trim(wlper))=0 then
                 wlper=' select ';
              else
                 wlper= wlper || ', select ';
              end if;
           end if;

           if mireg.tinsert>0 then
              if length(trim(wlper))=0 then
                 wlper=' insert ';
              else
                 wlper= wlper || ', insert ';
              end if;
           end if;

           if mireg.tupdate>0 then
              if length(trim(wlper))=0 then
                 wlper=' update ';
              else
                 wlper= wlper || ', update ';
              end if;
           end if;

           if mireg.tdelete>0 then
              if length(trim(wlper))=0 then
                 wlper=' delete ';
              else
                 wlper= wlper || ', delete ';
              end if;
           end if;
           end if;
         if trim(mireg.tablename)!='' then   -- 20070720
            wlsentencia='grant ' || trim(wlper) || '  on ' || trim(mireg.tablename) || ' to ' ||  $1 || ' with grant option ';
            raise notice ' sentencia % %', wlsentencia,mireg.descripcion  ;
            if wlper!='' then
               execute wlsentencia;     -- 20070720
            end if;
         end if;
     end loop ;
--  20070602  incluit este for para dar permisos de uso al usuario a los esquemas
--            esquemas que no son publicos
     for mireg  in select
                   mpgt.nspname 
                   from forapi.cat_usuarios_pg_group as cupg
                   , forapi.menus_pg_tables as mpgt
                            ,forapi.menus as me
                   where trim(cupg.usename)=trim($1)
                   and me.idmenu  = mpgt.idmenu
                   and me.idmenu  in ((select idmenu from forapi.menus_pg_group as mpg where
                                                  cupg.grosysid = mpg.grosysid
                                     union
                                     select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)
                                     group by 1
                                     ))
                   group by 1
                   loop
         if mireg.nspname!='public' then
            --wlsentencia=' revoke all on schema ' || trim(mireg.nspname) || ' from ' ||  $1  || ' cascade' ;
            --execute wlsentencia;
            wlsentencia=' grant usage,create on schema ' || trim(mireg.nspname) || ' to ' ||  $1 || ' with grant option ';
            execute wlsentencia;
            raise notice ' sentencia % ', wlsentencia  ;
            wlsentencia=' grant all PRIVILEGES on all sequences in schema ' || trim(mireg.nspname) || ' to ' ||  $1 || ' with grant option ';
            execute wlsentencia;
            raise notice ' sentencia % ', wlsentencia  ;
         end if;
--   termina 20070602
     end loop ;

  return 'El usuario ' || $1 || ' se autorizo';
end;$_$;


ALTER FUNCTION forapi.autoriza_usuario(text) OWNER TO postgres;

--
-- PostgreSQL database dump complete
--

