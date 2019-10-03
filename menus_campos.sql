--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menus_campos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE menus_campos (
    idcampo integer NOT NULL,
    idmenu integer,
    reltype oid DEFAULT 0 NOT NULL,
    attnum integer DEFAULT 0 NOT NULL,
    descripcion character varying(100),
    size integer,
    male integer,
    fuente character varying(100) DEFAULT ''::character varying,
    fuente_campodes character varying(30) DEFAULT ''::character varying,
    fuente_campodep character varying(30) DEFAULT ''::character varying,
    fuente_campofil character varying(255) DEFAULT ''::character varying,
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
    fuente_nspname name DEFAULT ''::name,
    altaautomatico_idmenu integer DEFAULT 0,
    fuente_busqueda_idmenu integer DEFAULT 0,
    upload_file boolean DEFAULT false,
    formato_td smallint,
    colspantxt smallint,
    rowspantxt smallint,
    autocomplete smallint DEFAULT 0,
    imprime boolean DEFAULT true,
    totales boolean DEFAULT false,
    cambiarencambios boolean DEFAULT true,
    link_file boolean DEFAULT false,
    fuente_info boolean DEFAULT false,
    fuente_info_idmenu integer DEFAULT 0,
    fuente_actu boolean DEFAULT false,
    fuente_actu_idmenu integer DEFAULT 0,
    eshidden boolean DEFAULT false
);


ALTER TABLE public.menus_campos OWNER TO postgres;

--
-- Name: COLUMN menus_campos.fuente_busqueda; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.fuente_busqueda IS 'Indica si en un campo select se tiene la opcion de busqueda esto se utiliza cuando las opciones son bastantes y el browse no se pasme';


--
-- Name: COLUMN menus_campos.val_particulares; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.val_particulares IS 'Se indica que validacion utilizar en el cliente si es mas de una es separado por ; y hay que corregir el ';


--
-- Name: COLUMN menus_campos.htmltable; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.htmltable IS 'Numero de tabla en el html, por default es 0, si se pone otro numero crea otra tabla en region de captura de datos';


--
-- Name: COLUMN menus_campos.altaautomatico_idmenu; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.altaautomatico_idmenu IS 'Numero de menu con la cual se va a dara alta en automatico';


--
-- Name: COLUMN menus_campos.fuente_busqueda_idmenu; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.fuente_busqueda_idmenu IS 'Numero de menu con la cual se van a buscar datos';


--
-- Name: COLUMN menus_campos.upload_file; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.upload_file IS 'Indica si el campo sirve para subir archivos false=no true=si';


--
-- Name: COLUMN menus_campos.formato_td; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.formato_td IS '0=normal,1=etiqueta y texto juntos,2=etiqueta y texto juntos todo el renglon';


--
-- Name: COLUMN menus_campos.colspantxt; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.colspantxt IS 'col span del texto en td, en caso de textarea es el ancho del renglon';


--
-- Name: COLUMN menus_campos.rowspantxt; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.rowspantxt IS 'row span del texto en td, en caso de textarea es la altura del renglon';


--
-- Name: COLUMN menus_campos.autocomplete; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.autocomplete IS 'Indica si se completa el campos en estos momento funciona para campos select, la idea es que funcione para campos texto 0=no,1=si';


--
-- Name: COLUMN menus_campos.imprime; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.imprime IS 'Indica si el campo se imprime true=si false=no';


--
-- Name: COLUMN menus_campos.totales; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.totales IS 'Indica si en la columna de campos se arega un total true=si, false=no';


--
-- Name: COLUMN menus_campos.cambiarencambios; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN menus_campos.cambiarencambios IS 'Con este campo se control que no se puedan cambiar datos en cambios, especificamente sirve para los campos de busqueda';


--
-- Name: menus_campos_idcampo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE menus_campos_idcampo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.menus_campos_idcampo_seq OWNER TO postgres;

--
-- Name: menus_campos_idcampo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE menus_campos_idcampo_seq OWNED BY menus_campos.idcampo;


--
-- Name: idcampo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY menus_campos ALTER COLUMN idcampo SET DEFAULT nextval('menus_campos_idcampo_seq'::regclass);


--
-- Name: menus_campos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY menus_campos
    ADD CONSTRAINT menus_campos_pkey PRIMARY KEY (idcampo);


--
-- Name: ak1_menus_campos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ak1_menus_campos ON menus_campos USING btree (idmenu, attnum);


--
-- Name: ak2_menus_campos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ak2_menus_campos ON menus_campos USING btree (fuente_busqueda_idmenu);


--
-- Name: ak3_menus_campos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ak3_menus_campos ON menus_campos USING btree (fuente_info_idmenu);


--
-- Name: ak4_menus_campos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ak4_menus_campos ON menus_campos USING btree (fuente_actu_idmenu);


--
-- Name: ak5_menus_campos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ak5_menus_campos ON menus_campos USING btree (idsubvista);


--
-- Name: ak6_menus_campos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ak6_menus_campos ON menus_campos USING btree (altaautomatico_idmenu);


--
-- Name: ti_menus_campos; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER ti_menus_campos BEFORE INSERT ON menus_campos FOR EACH ROW EXECUTE PROCEDURE alta_menus_campos();


--
-- Name: menus_campos; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE menus_campos FROM PUBLIC;
REVOKE ALL ON TABLE menus_campos FROM postgres;
GRANT ALL ON TABLE menus_campos TO postgres;
GRANT SELECT ON TABLE menus_campos TO metmon WITH GRANT OPTION;
GRANT ALL ON TABLE menus_campos TO marcomonroy WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO angeles WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cid WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO leticia WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO carlos WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mgalindo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO gjimen75 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO "lgarduño" WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mpaz WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO gjimenez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mmarquez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO araceli WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO gabriela WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jgonzaga WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO amartinez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO josevm WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO maguilar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cchavez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO unieto WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jhon WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO vgallardo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jesus WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO ogarcia WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO alandi WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO monica WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mruiz WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lgarduno WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO adriana WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO enriqueestrada WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mauriciosm WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mjimenez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO beatrizp WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO quejascon WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aolivares WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rangeles WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO acalixte WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rosa WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lgonzalez1 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO iesus WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO alextorres WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mvazquez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO josel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO miguel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO quejasusr WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO enmosqueda WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO khraramirez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO hjimenez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO abejarano WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jon WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO luz WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mortiz WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mjluna WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jromero WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aris WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lcabrera WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mreyes WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aclarita WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO alejandra WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cnavarro WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO ivan WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO per3 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO maribel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mramirez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO acervousr WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO marxcastro WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO clicona WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mcardenas WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO madejesus WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jaqueline WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO amapeco WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cristina WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO francisco WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mcoca WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO operez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO isaac WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO malcantara WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO ovazquez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO martha1 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO vamador WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO sleal WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO tsequera WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rverduzco WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO salcantara WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO alejandro WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO maggui WITH GRANT OPTION;
GRANT ALL ON TABLE menus_campos TO grecar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mvera WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO eduardo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO miguelolvera WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO agus WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO fmartinez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO eramos WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO tere WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lgutierrez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lenin WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO scuevasreyes WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO marta WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO srangel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lulu WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mangeles WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO dosorio WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO monica1 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO gris_pernic WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO dgjel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO fjcornejo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO pako WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jared WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO leticia1 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO alfonsodelao WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rocha WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO oscar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lcastillo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO eandujar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO edgar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mapaez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jcarrillo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mmorales WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jzaragoza WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lbecerril WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO ccorres WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO federic WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO nibarra WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO salvador WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lgonzalez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rpcvazquez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO dandres WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO itzel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO tromero WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO javo WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO abel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO spadilla WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aescobar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO spalafox WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO agomez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rparra WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO nceron WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO gesgaceta WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mromero WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jladino WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mmartinez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mcastro WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO "amuñiz" WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO edgarosorio WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rmartinez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rey WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO enavarrete WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO arielm WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aahuet WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jlujano WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aagarcia WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mmendiola WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO libelula26 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cluengas WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO vparada WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO evargas WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rflores WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO montserrat WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO nrocha WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO alopez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rortega WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO msanchez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO apadilla WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO eli WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jorge WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO nietzsche WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO folguin WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jregalado WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO eibarra WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cromero WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO fgomez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mcamacho WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO cnogueda WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mresendiz WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO david WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO harlem WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mcorona WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO nixta WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO luis WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO pdelarosa WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jgarcia WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mllerena WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO elizabeth WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO hchaidez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO egutierrez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO tmeza WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jguzman WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rgutierrez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jlv8 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO janeth WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO fernandamiranda WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO gbenavides WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rpcastellanos WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO amnistia WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO ylgabriel WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jazmin1 WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO pilar WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO aescorza WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO universidad WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO veronica WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO rluna WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO vvizcaino WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO lcasillas WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO upineda WITH GRANT OPTION;
GRANT ALL ON TABLE menus_campos TO kevinsolis WITH GRANT OPTION;
GRANT ALL ON TABLE menus_campos TO alfredog WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO jcespindola WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO iglopez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO igonzalez WITH GRANT OPTION;
GRANT ALL ON TABLE menus_campos TO igasa WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO vportoni WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO fabiolaanduaga WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO hiram WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO schavez WITH GRANT OPTION;
GRANT SELECT ON TABLE menus_campos TO mpalacios WITH GRANT OPTION;
SET SESSION AUTHORIZATION igasa;
GRANT SELECT ON TABLE menus_campos TO upineda WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
GRANT SELECT ON TABLE menus_campos TO temporal WITH GRANT OPTION;
GRANT ALL ON TABLE menus_campos TO jlv WITH GRANT OPTION;
SET SESSION AUTHORIZATION alfredog;
GRANT SELECT ON TABLE menus_campos TO operez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO agodinez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO lcolin WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO rarroyo WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO sfajardo WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO blopez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO nmorales WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO mmorales WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO imartinez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO maviles WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO agarcia WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT ALL ON TABLE menus_campos TO alfredog WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO armandopg WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO cmartinez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION alfredog;
GRANT SELECT ON TABLE menus_campos TO cmartinez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
GRANT SELECT ON TABLE menus_campos TO cmartinez WITH GRANT OPTION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO fgomez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT ALL ON TABLE menus_campos TO kevinsolis WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION alfredog;
GRANT SELECT ON TABLE menus_campos TO icruz WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO icruz WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO lhernandez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION kevinsolis;
GRANT SELECT ON TABLE menus_campos TO agarcia WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION kevinsolis;
GRANT SELECT ON TABLE menus_campos TO agodinez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO jcruz WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION alfredog;
GRANT SELECT ON TABLE menus_campos TO aserrano WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO aserrano WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION alfredog;
GRANT SELECT ON TABLE menus_campos TO maviles WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO srangel WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO lbojorquez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION alfredog;
GRANT SELECT ON TABLE menus_campos TO mramirez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO ylgabriel WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO operez WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO temporal WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
GRANT SELECT ON TABLE menus_campos TO jlv11 WITH GRANT OPTION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO jlv11 WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT SELECT ON TABLE menus_campos TO jlv8 WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;


--
-- Name: menus_campos_idcampo_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE menus_campos_idcampo_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE menus_campos_idcampo_seq FROM postgres;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO postgres;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO enriqueestrada WITH GRANT OPTION;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO marcomonroy WITH GRANT OPTION;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO grecar WITH GRANT OPTION;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO PUBLIC;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO kevinsolis WITH GRANT OPTION;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO alfredog WITH GRANT OPTION;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO igasa WITH GRANT OPTION;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO jlv WITH GRANT OPTION;
SET SESSION AUTHORIZATION jlv;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO alfredog WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;
SET SESSION AUTHORIZATION jlv;
GRANT ALL ON SEQUENCE menus_campos_idcampo_seq TO kevinsolis WITH GRANT OPTION;
RESET SESSION AUTHORIZATION;


--
-- PostgreSQL database dump complete
--

