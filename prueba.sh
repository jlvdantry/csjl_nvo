cat > $0.sql << fin
--select * from menus
--select trim(sql) from gen_menu_sqlp(2693);
--select trim(sql) from gen_menu_sqlp(2691);
--select trim(sql) from gen_menu_sqlp(2698);
--create database laravel_IMEI
--ALTER USER postgres PASSWORD '888aDantryR';
select 
        case when (strpos(mpgt.tablename,'.')=0 and strpos(mpgt.tablename,'pg_')!=1)
                   then mpgt.nspname||'.'||mpgt.tablename else mpgt.tablename end as tablename
                   , me.descripcion
                   ,mpgt.tablename
                   ,mpgt.nspname
                   ,strpos(mpgt.tablename,'pg_')
                   from cat_usuarios_pg_group as cupg
                   , forapi.menus_pg_tables as mpgt
                            ,forapi.menus as me
                   where trim(cupg.usename)='jlv'
                   and me.idmenu  = mpgt.idmenu
                   and me.idmenu  in ((select idmenu from forapi.menus_pg_group as mpg where
                                                  cupg.grosysid = mpg.grosysid
                                     union
                                     select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)
                                     --group by 1
                                     union
                                     select mc.altaautomatico_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.altaautomatico_idmenu>0
                                     union
                                     select distinct mc.fuente_busqueda_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.fuente_busqueda_idmenu>0
                                     union
                                     select distinct mc.idsubvista from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.idsubvista>0
                                     union
                                     select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select mc.idsubvista from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.idsubvista>0
                                                union
                                             select distinct mc.altaautomatico_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.altaautomatico_idmenu>0
                                                union
                                             select distinct mc.fuente_busqueda_idmenu from forapi.menus_campos as mc join forapi.menus_pg_group as mpg on (mc.idmenu=mpg.idmenu) where cupg.grosysid = mpg.grosysid and mc.fuente_busqueda_idmenu>0)
                                     group by 1
                                     union
                                     select distinct mc2.altaautomatico_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
                                                (select idsubvista from forapi.menus_subvistas as ms where
                                                        ms.idmenu in 
                                                        (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)) and mc2.altaautomatico_idmenu>0
                                     union
                                     select distinct mc2.fuente_busqueda_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
                                                (select idsubvista from forapi.menus_subvistas as ms where
                                                        ms.idmenu in 
                                                        (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))  and mc2.fuente_busqueda_idmenu>0
                                        union
                                        select idsubvista from forapi.menus_subvistas as mss where
                                            mss.idmenu in 
                                            ( select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))
                                     union
                                     select distinct mc2.altaautomatico_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
                                                (select idsubvista from forapi.menus_subvistas as mss where
                                            mss.idmenu in 
                                            ( select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))) and mc2.altaautomatico_idmenu>0
                                     union
                                     select distinct mc2.fuente_busqueda_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
                                                (select idsubvista from forapi.menus_subvistas as mss where
                                            mss.idmenu in 
                                            ( select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)))  and mc2.fuente_busqueda_idmenu>0
                                        union
                                        select idsubvista from forapi.menus_subvistas as msss where
                                            msss.idmenu in 
                                            (select idsubvista from forapi.menus_subvistas as mss where
                                            mss.idmenu in 
                                            ( select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)))
                                     union
                                     select distinct mc2.altaautomatico_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
                                                (select idsubvista from forapi.menus_subvistas as msss where
                                            msss.idmenu in 
                                            (select idsubvista from forapi.menus_subvistas as mss where
                                            mss.idmenu in 
                                            ( select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid)))) and mc2.altaautomatico_idmenu>0
                                     union
                                     select distinct mc2.fuente_busqueda_idmenu from forapi.menus_campos as mc2 where mc2.idmenu in
                                                (select idsubvista from forapi.menus_subvistas as msss where
                                            msss.idmenu in 
                                            (select idsubvista from forapi.menus_subvistas as mss where
                                            mss.idmenu in 
                                            ( select idsubvista from forapi.menus_subvistas as ms where
                                            ms.idmenu in 
                                            (select idmenu from forapi.menus_pg_group as mpg where cupg.grosysid = mpg.grosysid))))  and mc2.fuente_busqueda_idmenu>0
                                     ))

fin
##psql forapi1.1 -U postgres < $0.sql
psql forapi1.1 -U postgres  < $0.sql
rm $0.sql
