SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET search_path = contra, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;
CREATE TABLE cat_tipolibro (
    id integer NOT NULL,
    descripcion character varying(100) NOT NULL,
    descorta character varying(10) NOT NULL,
    color character varying(6),
    imagen bytea,
    idregcambio integer NOT NULL,
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp without time zone,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp without time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername()
);


ALTER TABLE contra.cat_tipolibro OWNER TO postgres;
CREATE SEQUENCE cat_lado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE contra.cat_tipolibro_id_seq OWNER TO postgres;
ALTER SEQUENCE cat_tipolibro_id_seq OWNED BY cat_tipolibro.id;
CREATE SEQUENCE cat_tipolibro_idregcambio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE contra.cat_tipolibro_idregcambio_seq OWNER TO postgres;
ALTER SEQUENCE cat_tipolibro_idregcambio_seq OWNED BY cat_tipolibro.idregcambio;
ALTER TABLE ONLY cat_tipolibro ALTER COLUMN id SET DEFAULT nextval('cat_tipolibro_id_seq'::regclass);
ALTER TABLE ONLY cat_tipolibro ALTER COLUMN idregcambio SET DEFAULT nextval('cat_tipolibro_idregcambio_seq'::regclass);
REVOKE ALL ON TABLE cat_tipolibro FROM PUBLIC;
REVOKE ALL ON TABLE cat_tipolibro FROM postgres;
GRANT ALL ON TABLE cat_tipolibro TO postgres;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE cat_tipolibro TO jlv WITH GRANT OPTION;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE cat_tipolibro TO temporal WITH GRANT OPTION;

REVOKE ALL ON SEQUENCE cat_lado_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE cat_lado_id_seq FROM postgres;
GRANT ALL ON SEQUENCE cat_lado_id_seq TO postgres;
GRANT SELECT,UPDATE ON SEQUENCE cat_lado_id_seq TO jlv WITH GRANT OPTION;
GRANT SELECT,UPDATE ON SEQUENCE cat_lado_id_seq TO temporal WITH GRANT OPTION;

