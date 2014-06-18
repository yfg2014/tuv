#!/bin/sh 
d=`date +%Y%m%d`
/usr/local/mysql/bin/mysqldump -uroot -padmin demo > /mnt/b/mysql$d.sql
