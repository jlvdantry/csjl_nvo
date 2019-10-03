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
-- Name: alta_menus_pg_group(); Type: FUNCTION; Schema: forapi; Owner: postgres
--

CREATE or replace FUNCTION alta_menus_pg_group() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
      wlestado numeric;
    BEGIN
        insert into forapi.his_menus_pg_group (idmenu,grosysid,cve_movto)
               values (new.idmenu,new.grosysid,'a');
     return new;
    END;$$;


ALTER FUNCTION forapi.alta_menus_pg_group() OWNER TO postgres;

--
-- PostgreSQL database dump complete
--

