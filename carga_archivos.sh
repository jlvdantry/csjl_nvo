cat > $0.cmd << fin
{
if (index(\$11,"pdf") ) { print "insert into contra.ope_archivos(ficheroin,observacion,folioconsecutivo,id_tipoarc) select idarchivo||'.pdf',descripcion ,(select folioconsecutivo from contra.gestion where folio = 'GESDIR-'|| lpad(substring(descripcion from 14 for 5),6,'0') and fecharecibo between (extract(year from ma.fecha_alta) || '-01-01')::date and  (extract(year from ma.fecha_alta) || '-12-31')::date) ,1 from menus_archivos ma where fecha_alta > current_date -1 and ficheroin='"\$11"' and (select count(*) from contra.ope_archivos where ficheroin='"\$11"')=0;"
  }
}
fin
cat > $0.cmd1 << fin
{
  if (\$1!="")
  {
    print "mv " \$3 " /home/gesdir/" \$1".pdf" ;
    print "scp -P 22 /home/gesdir/" \$1".pdf root@10.250.103.116:/var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/.";
##    print "rsh -P 22 root\@10.250.103.116 wc /var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/" \$1".pdf | diff " \$1".pdf";;
  }
}
fin
cat > $0.sql1 << fin
select idarchivo,descripcion from menus_archivos where fecha_alta > current_date -1 and descripcion like '/home%';
fin
cat > $0.sql2 << fin
insert into contra.ope_archivos(ficheroin,observacion,folioconsecutivo,id_tipoarc)
select idarchivo||'.pdf',descripcion
,(select folioconsecutivo from contra.gestion where folio = 'GESDIR-'|| lpad(substring(descripcion from 14 for 5),6,'0') and fecharecibo between (extract(year from ma.fecha_alta) || '-01-01')::date and  (extract(year from ma.fecha_alta) || '-12-31')::date)
,1
from menus_archivos ma where fecha_alta > current_date -1 and descripcion like '/home%';
update menus_archivos set descripcion=substring(descripcion from 14) where fecha_alta > current_date -1 and descripcion like '/home%';
fin
##find /var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/?????*.pdf -amin +2 -ls  
cd upload_ficheros
find ?????*.pdf -amin +0 -ls  | awk  -f ../$0.cmd  > ../$0.sql
cd ..
cat $0.sql
##psql forapi1.1 < $0.sql   ## inserta en menus_archivos
##psql -t  forapi1.1 < $0.sql1 | awk  -f $0.cmd1 > $0.sh
##chmod 777 $0.sh
##$0.sh
##psql -t  forapi1.1 < $0.sql2
##rm $0.cmd
##rm $0.cmd1
