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
-- Name: debe_cambiarpwd(text, integer); Type: FUNCTION; Schema: forapi; Owner: postgres
--

CREATE or replace FUNCTION debe_cambiarpwd(text, integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $_$DECLARE
  wldias smallint;  
  begin
          SELECT coalesce((current_date-max(fecha_alta)),'0') into wldias from forapi.his_cambios_pwd cu where cu.usuario_alta=cast($1 as name);
          if wldias>$2 then
          		return 'Usuario debe cambia pwd';
          end if;
          
          return '';
end;$_$;


ALTER FUNCTION forapi.debe_cambiarpwd(text, integer) OWNER TO postgres;

--
-- PostgreSQL database dump complete
--

