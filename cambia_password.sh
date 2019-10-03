cat > $0.sql << fin
alter role $1 WITH PASSWORD '$2';
--update cat_usuarios set estatus=1 where usename='jlv';
fin
psql forapi1.1 -U postgres < $0.sql
