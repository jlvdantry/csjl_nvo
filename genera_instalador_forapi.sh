pg_dump forapi1.1 -U postgres -s -x -n forapi > forapi_esquema.sql
cat > $0.sql << fin
select 'select sql from forapi.gen_menu_sqlp('||idmenu||');'
from forapi.menus where nspname in('forapi','pg_catalog') ;
--and descripcion='Cambio de Contraseña';
fin
psql -t  forapi1.1 -U postgres < $0.sql > $0.sql1
psql -t  forapi1.1 -U postgres < $0.sql1 > forapi_insert.sql
tar -zcvf forapi_php.tar.gz index.php titulos.php entrada.php conneccion.php soldatos.php mensajes.php class.phpmailer.php menudata.php class_logmenus.php man_menus.php xmlhttp_class.php Classes/PHPExcel.php dhtmlwindow.js modal.js common.js subModal.js val_comunes.js val_particulares.js eve_particulares.js altaautomatica.js altaadjuntara.js leearchivo.js jsrsasign-latest-all-min.js broseaing.js  img/
rm $0.sql
rm $0.sql1
