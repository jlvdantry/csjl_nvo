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
-- Name: estatus_usuario(text); Type: FUNCTION; Schema: forapi; Owner: postgres
--

CREATE or replace FUNCTION estatus_usuario(text) RETURNS character varying
    LANGUAGE plpgsql
    AS $_$DECLARE
  wlestatus smallint;  
  begin
          SELECT cu.estatus into wlestatus from pg_shadow pgs, forapi.cat_usuarios cu where pgs.usename=cast($1 as name)
                 and pgs.usename =cast(cu.usename as name);
          if wlestatus=0 then
          		return 'Tu usuario no esta autorizado';
          end if;
          
          if wlestatus=2 then
          		return 'Tu usuario esta bloqueado';
          end if;          		

          if wlestatus=3 then
          		return 'Tu usuario esta inhabilitado definitivamente';
          end if;          		                    
          return '';
end;$_$;


ALTER FUNCTION forapi.estatus_usuario(text) OWNER TO postgres;

--
-- PostgreSQL database dump complete
--

