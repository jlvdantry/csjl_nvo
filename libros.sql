SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET search_path = contra, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;
CREATE TABLE libros (
    id integer NOT NULL,
    notaria integer DEFAULT 0,
    nombre_not character varying(100),
    id_tipolibro integer NOT NULL,
    volumen integer DEFAULT 0,
    escriturainicial integer DEFAULT 0,
    fechainicial date,
    escriturafinal integer DEFAULT 0,
    fechafinal date,
    anaquel integer DEFAULT 0,
    fila integer DEFAULT 0,
    columna integer DEFAULT 0,
    id_lado integer DEFAULT 0,
    usuario_alta character varying(20) DEFAULT getpgusername(),
    fecha_alta timestamp with time zone DEFAULT ('now'::text)::timestamp without time zone,
    fecha_modifico timestamp with time zone DEFAULT ('now'::text)::timestamp without time zone,
    usuario_modifico character varying(20) DEFAULT getpgusername(),
    idregcambio integer NOT NULL
);


ALTER TABLE contra.libros OWNER TO postgres;
COMMENT ON COLUMN libros.notaria IS 'Numero de notaria';
COMMENT ON COLUMN libros.id_tipolibro IS 'Id del tipo de libro valor de acuerdo al catalogo de tipo de libros';
COMMENT ON COLUMN libros.volumen IS 'Numero de volumen';
COMMENT ON COLUMN libros.escriturainicial IS 'Escritura inicial de la bateria nivel';
COMMENT ON COLUMN libros.fechainicial IS 'fecha inicial de la bateria nivel';
COMMENT ON COLUMN libros.escriturafinal IS 'Escritura final de la bateria nivel';
COMMENT ON COLUMN libros.fechafinal IS 'fecha final del bateria nivel';
COMMENT ON COLUMN libros.anaquel IS 'Numero de anaquel';
COMMENT ON COLUMN libros.fila IS 'Numero de fila dentro del anaquel';
COMMENT ON COLUMN libros.columna IS 'Numero de columna dentro del anaquel';
COMMENT ON COLUMN libros.id_lado IS 'Id del lado del anaquel valor de acuerdo al catalogo de lados';
CREATE SEQUENCE libros_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE contra.libros_id_seq OWNER TO postgres;
ALTER SEQUENCE libros_id_seq OWNED BY libros.id;
CREATE SEQUENCE libros_idregcambio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE contra.libros_idregcambio_seq OWNER TO postgres;
ALTER SEQUENCE libros_idregcambio_seq OWNED BY libros.idregcambio;
ALTER TABLE ONLY libros ALTER COLUMN id SET DEFAULT nextval('libros_id_seq'::regclass);
ALTER TABLE ONLY libros ALTER COLUMN idregcambio SET DEFAULT nextval('libros_idregcambio_seq'::regclass);
CREATE TRIGGER up_usename_fecha BEFORE UPDATE ON libros FOR EACH ROW EXECUTE PROCEDURE public.upa_usuario_fecha();
REVOKE ALL ON TABLE libros FROM PUBLIC;
REVOKE ALL ON TABLE libros FROM postgres;
