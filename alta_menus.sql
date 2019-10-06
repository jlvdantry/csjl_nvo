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
-- Name: alta_menus(); Type: FUNCTION; Schema: forapi; Owner: postgres
--

CREATE or replace FUNCTION alta_menus() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
      wlestado numeric;
      wlnum numeric;
    BEGIN
        if new.tabla='' or new.tabla is null then
                select relname,nspname into new.tabla,new.nspname from forapi.tablas where reltype=new.reltype;
        else
                if new.reltype!=(select reltype from forapi.tablas where relname=new.tabla and nspname=new.nspname) then
                   select reltype into new.reltype from forapi.tablas where relname=new.tabla and nspname=new.nspname;
                end if;
        end if;
	select count (*) into wlnum from forapi.menus_pg_tables where tablename=new.tabla and nspname=new.nspname;
        --raise notice 'registros % tabla % nspname % ', wlnum, new.tabla, new.nspname;
	if wlnum=0 then
        --raise notice 'entro a insertar % tabla % nspname % ', wlnum, new.tabla, new.nspname;
        insert into forapi.menus_pg_tables (idmenu,tablename,tselect,tinsert,tupdate,tdelete,tall,tgrant,nspname)
               values (new.idmenu,new.tabla
                              ,case when strpos(new.movtos,'s')>0 or strpos(new.movtos,'S')>0 then 1 else 0 end
                              ,case when strpos(new.movtos,'i')>0 or strpos(new.movtos,'cc')>0 then 1 else 0 end
                              ,case when strpos(new.movtos,'u')>0 then 1 else 0 end
                              ,case when strpos(new.movtos,'d')>0 then 1 else 0 end
                              ,0
                              ,0
                              ,new.nspname);
	end if;
     return new;
    END;$$;


ALTER FUNCTION forapi.alta_menus() OWNER TO postgres;

--
-- PostgreSQL database dump complete
--

