create or replace FUNCTION forapi.cambia_menus() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
      wlestado numeric;
    BEGIN
  if new.tabla!=old.tabla then
     update forapi.menus_campos set tabla=new.tabla
            where idmenu=old.idmenu;
     delete from forpai.menus_pg_tables where tablename=old.tabla and idmenu=old.idmenu;
     if new.movtos!=old.movtos then
        insert into forapi.menus_pg_tables (idmenu,tablename,tselect,tinsert,tupdate,tdelete,tall,tgrant,nspname)
               values (old.idmenu,new.tabla
                              ,case when strpos(old.movtos,'s')>0 or strpos(old.movtos,'S')>0 then 1 else 0 end
                              ,case when strpos(old.movtos,'i')>0 or strpos(old.movtos,'cc')>0 then 1 else 0 end
                              ,case when strpos(old.movtos,'u')>0 then 1 else 0 end
                              ,case when strpos(old.movtos,'d')>0 then 1 else 0 end
                              ,0
                              ,0
                              ,new.nspname);
     else
        insert into forapi.menus_pg_tables (idmenu,tablename,tselect,tinsert,tupdate,tdelete,tall,tgrant,nspname)
               values (old.idmenu,new.tabla
                              ,case when strpos(new.movtos,'s')>0 or strpos(new.movtos,'S')>0 then 1 else 0 end
                              ,case when strpos(new.movtos,'i')>0 or strpos(new.movtos,'cc')>0 then 1 else 0 end
                              ,case when strpos(new.movtos,'u')>0 then 1 else 0 end
                              ,case when strpos(new.movtos,'d')>0 then 1 else 0 end
                              ,0
                              ,0
                              ,new.nspname);
     end if;
  else
     if old.movtos!=new.movtos then
        update  forapi.menus_pg_tables set
                tselect=case when strpos(new.movtos,'s')>0 or strpos(new.movtos,'S')>0 then 1 else 0 end
                ,tinsert=case when strpos(new.movtos,'i')>0 or strpos(new.movtos,'cc')>0 then 1 else 0 end
                ,tupdate=case when strpos(new.movtos,'u')>0 then 1 else 0 end
                ,tdelete=case when strpos(new.movtos,'d')>0 then 1 else 0 end
        where idmenu=old.idmenu
              and tablename=old.tabla;
     end if;
  end if;
  if new.nspname!=old.nspname then
     update forapi.menus_campos set nspname=new.nspname
            where idmenu=old.idmenu;
  end if;
  new.usuario_modifico = getpgusername();
  new.fecha_modifico = current_timestamp;

  insert into forapi.his_menus (
  idmenu,
  descripcion,
  objeto,
  fecha_alta,
  usuario_alta,
  fecha_modifico,
  usuario_modifico,
  php,
  modoconsulta,
  idmenupadre,
  idmovtos,
  movtos,
  fuente,
  presentacion,
  columnas,
  tabla,
  reltype,
  filtro,
  limite,
  orden,
  menus_campos,
  dialogWidth,
  dialogHeight,
  s_table,
  s_table_height,
  cvemovto
  ,nspname
  )
  values  (
  old.idmenu,
  old.descripcion,
  old.objeto,
  old.fecha_alta,
  old.usuario_alta,
  old.fecha_modifico,
  old.usuario_modifico,
  old.php,
  old.modoconsulta,
  old.idmenupadre,
  old.idmovtos,
  old.movtos,
  old.fuente,
  old.presentacion,
  old.columnas,
  old.tabla,
  old.reltype,
  old.filtro,
  old.limite,
  old.orden,
  old.menus_campos,
  old.dialogWidth,
  old.dialogHeight,
  old.s_table,
  old.s_table_height,
  'u'
  ,old.nspname
  ); 
 return new;
    END;$$;


