--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = contra, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cat_lado; Type: TABLE; Schema: contra; Owner: postgres; Tablespace: 
--

CREATE TABLE cat_lado (
    id integer DEFAULT nextval('cat_lado_id_seq'::regclass) NOT NULL,
    descripcion character varying(1) NOT NULL,
    idregcambio integer DEFAULT nextval('cat_lado_idregcambio_seq'::regclass) NOT NULL,
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp without time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp without time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE contra.cat_lado OWNER TO postgres;

--
-- Name: cat_lado; Type: ACL; Schema: contra; Owner: postgres
--

REVOKE ALL ON TABLE cat_lado FROM PUBLIC;
REVOKE ALL ON TABLE cat_lado FROM postgres;
GRANT ALL ON TABLE cat_lado TO postgres;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE cat_lado TO jlv WITH GRANT OPTION;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE cat_lado TO temporal WITH GRANT OPTION;


--
-- PostgreSQL database dump complete
--

