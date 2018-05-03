{
if (index($11,"pdf") ) { print "insert into contra.ope_archivos(ficheroin,observacion,folioconsecutivo,id_tipoarc) select idarchivo||'.pdf',descripcion ,(select folioconsecutivo from contra.gestion where folio = 'GESDIR-'|| lpad(substring(descripcion from 14 for 5),6,'0') and fecharecibo between (extract(year from ma.fecha_alta) || '-01-01')::date and  (extract(year from ma.fecha_alta) || '-12-31')::date) ,1 from menus_archivos ma where fecha_alta > current_date -1 and ficheroin='"$11"' and (select count(*) from contra.ope_archivos where ficheroin='"$11"')=0;"
  }
}
