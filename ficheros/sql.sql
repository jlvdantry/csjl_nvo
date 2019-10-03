 select idmenu
,descripcion
,objeto
,php
,(coalesce ((select  descripcion || '=' || sm.idpresentacion from forapi.menus_presentacion as sm  where sm.idpresentacion=menus.presentacion group by 1 order by 1),  '=' || menus.presentacion)) as presentacion
,modoconsulta
,(coalesce ((select  nspname || '=' || sm.nspname from pg_catalog.pg_namespace as sm  where sm.nspname=menus.nspname group by 1 order by 1),  '=' || menus.nspname)) as nspname
,(coalesce ((select  relname || '=' || sm.relname from public.tablas as sm  where sm.relname=menus.tabla and sm.nspname = menus.nspname group by 1 order by 1),  '=' || menus.tabla)) as tabla
,columnas
,movtos
,noconfirmamovtos
,(coalesce ((select  descripcion || '=' || sm.idmenu from forapi.menus as sm  where sm.idmenu=menus.idmenupadre group by 1 order by 1),  '=' || menus.idmenupadre)) as idmenupadre
,filtro
,limite
,(coalesce ((select  descripcion || '=' || sm.idmenu from forapi.menus as sm  where sm.idmenu=menus.menus_campos group by 1 order by 1),  '=' || menus.menus_campos)) as menus_campos
,dialogwidth
,dialogheight
,s_table
,s_table_height
,table_width
,table_height
,table_align
,orden
,inicioregistros
,css
,imprime
,limpiaralta
,manual
,icono
,ayuda
 from forapi.menus where  nspname='forapi' order by fecha_alta desc limit 100