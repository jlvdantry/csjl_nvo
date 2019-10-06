##psql forapi1.1  -U postgres  < cambia_menus.sql
##psql forapi1.1  -U postgres  < alta_menus.sql
##psql forapi1.1  -U postgres  < baja_menus.sql
##psql forapi1.1  -U postgres  < baja_menus_pg_tables.sql
##psql forapi1.1  -U postgres  < autoriza_usuario.sql
##psql forapi1.1  -U postgres  < alta_menus_campos.sql
##psql forapi1.1  -U postgres  < alta_menus_pg_tables.sql
##psql forapi1.1  -U postgres  < alta_menus_pg_group.sql
##psql forapi1.1  -U postgres  < estatus_usuario.sql
##psql forapi1.1  -U postgres  < debe_cambiarpwd.sql
##psql forapi1.1  -U postgres  < autoriza_usuario.sql
##psql forapi1.1  -U postgres  < alta_cat_usuarios_pg_group.sql
##psql forapi1.1  -U postgres  < cambio_cat_usuarios.sql
##psql forapi1.1  -U postgres  < gen_menu_sqlp.sql
psql forapi1.1  -U postgres  < baja_menus_pg_group.sql
cat > $0.sql << fin
/*
CREATE SCHEMA forapi;
GRANT  USAGE   ON SCHEMA forapi  TO jlv;
ALTER TABLE menus SET SCHEMA forapi;
ALTER TABLE menus_campos SET SCHEMA forapi;
ALTER TABLE menus_campos_eventos SET SCHEMA forapi;
ALTER TABLE menus_pg_group SET SCHEMA forapi;
ALTER TABLE menus_pg_tables SET SCHEMA forapi;
ALTER TABLE menus_subvistas SET SCHEMA forapi;
ALTER TABLE menus_eventos SET SCHEMA forapi;
ALTER TABLE menus_movtos SET SCHEMA forapi;
ALTER TABLE menus_htmltable SET SCHEMA forapi;
ALTER TABLE menus_archivos SET SCHEMA forapi;
ALTER TABLE menus_tiempos SET SCHEMA forapi;
ALTER TABLE menus_presentacion SET SCHEMA forapi;
ALTER TABLE menus_log SET SCHEMA forapi;
ALTER TABLE menus_mensajes SET SCHEMA forapi;
ALTER TABLE menus_seguimiento SET SCHEMA forapi;
ALTER sequence menus_pg_group_orden_seq SET SCHEMA forapi;
ALTER TABLE his_cat_usuarios  SET SCHEMA forapi;
ALTER TABLE cat_usuarios  SET SCHEMA forapi;
ALTER TABLE cat_usuarios_pg_group  SET SCHEMA forapi;
ALTER TABLE his_menus_pg_group  SET SCHEMA forapi;
ALTER TABLE his_menus_pg_tables  SET SCHEMA forapi;
ALTER TABLE his_menus  SET SCHEMA forapi;
ALTER function alta_cat_usuarios()  SET SCHEMA forapi;
ALTER TABLE his_cambios_pwd  SET SCHEMA forapi;
ALTER TABLE tablas  SET SCHEMA forapi;
ALTER TABLE campos  SET SCHEMA forapi;
ALTER function alta_menus()  SET SCHEMA forapi;
ALTER function alta_cat_usuarios_pg_group()  SET SCHEMA forapi;
ALTER function alta_menus_pg_group()  SET SCHEMA forapi;
ALTER function alta_menus_pg_tables()  SET SCHEMA forapi;
ALTER function baja_cat_usuarios_pg_group()  SET SCHEMA forapi;
ALTER function baja_menus()  SET SCHEMA forapi;
ALTER function baja_menus_pg_tables()  SET SCHEMA forapi;
ALTER function cambia_password()  SET SCHEMA forapi;
ALTER function cambio_menus_pg_tables()  SET SCHEMA forapi;
ALTER function debe_cambiarpwd()  SET SCHEMA forapi;
ALTER function cambia_menus()  SET SCHEMA forapi;
ALTER function estatus_usuario(text)  SET SCHEMA forapi;
*/
--ALTER function debe_cambiarpwd(text,int4)  SET SCHEMA forapi;
--ALTER function usuario_bloqueado(text)  SET SCHEMA forapi;
--ALTER function tiene_grupo(text)  SET SCHEMA forapi;
--ALTER function valida_res_des(text,text)  SET SCHEMA forapi;
--ALTER TABLE eventos  SET SCHEMA forapi;
--ALTER TABLE tcases  SET SCHEMA forapi;
--ALTER TABLE his_tablas_cambios  SET SCHEMA forapi;
--ALTER function autoriza_usuario(text)  SET SCHEMA forapi;
--ALTER function copiamenu(int4)  SET SCHEMA forapi;

/*
select * from forapi.menus where descripcion='accesosistema' limit 1;
select * from forapi.menus_pg_tables where idmenu=85;
select * from forapi.menus_pg_group where idmenu=85;
select * from forapi.menus_subvistas where idmenu=85;
select idmenu,descripcion,fuente,tabla,fuente_nspname from forapi.menus_campos where idmenu=85;
*/
--select idmenu,descripcion,tabla,fuente,fuente_nspname from forapi.menus_campos where tabla like '%menu%' ;
--select * from forapi.menus_campos where tabla like '%menu%' ;
--select * from forapi.menus_pg_tables where tablename like '%menu%';
--update forapi.menus_pg_tables set nspname='forapi' where tablename like 'menu%';

--update forapi.menus_campos set fuente_nspname='forapi' where fuente = 'cat_usuarios_pg_group' ;
--- mando error en la funcion cambia_menus
--update forapi.menus set nspname='forapi' where tabla='menus' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_campos' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_eventos' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_pg_group' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_tables' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_subvistas' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_movtos' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_htmltable' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='menus_archivos' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='cat_usuarios' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='cat_usuarios_pg_group' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='tablas' and nspname='public' ;
--update forapi.menus set nspname='forapi' where tabla='tablas' ;
--select autoriza_usuario('jlv');
--update forapi.menus set nspname='forapi' where tabla='cat_usuarios_pg_group';
--ALTER function gen_menu_sqlp(int4)  SET SCHEMA forapi;
--ALTER type menus_sql  SET SCHEMA forapi;
--ALTER sequence his_menus_pg_group_seq SET SCHEMA forapi;
--ALTER sequence his_menus_pg_tables_seq SET SCHEMA forapi;
--alter table forapi.his_menus_pg_tables alter idcambio set DEFAULT nextval(('forapi.his_menus_pg_group_seq'::text)::regclass);
--alter table forapi.his_menus_pg_group alter idcambio set  DEFAULT nextval(('forapi.his_menus_pg_group_seq'::text)::regclass);
--ALTER function baja_cat_usuarios()  SET SCHEMA forapi;
--ALTER function baja_menus_pg_group()  SET SCHEMA forapi;
--ALTER function alta_menus_campos()  SET SCHEMA forapi;
--ALTER function public.cambio_cat_usuarios()  SET SCHEMA forapi;
--ALTER function public.up_usuario_fecha()  SET SCHEMA forapi;
fin
psql forapi1.1  -U postgres  < $0.sql
rm $0.sql
