@ECHO OFF
D:\phpStudy\MySQL\bin\mysqldump -uroot -padmin demo > D:\AutoBak\mysql%date:~0,4%%date:~5,2%%date:~8,2%.sql
exit

