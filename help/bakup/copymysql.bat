@ECHO OFF
D:\PHPnow\MySQL-5.0.83\bin\mysqlcheck -o cmd -uq -pw
md g:\bf\%date:~0,10%
copy g:\PHPnow-1.5.4\MySQL-5.0.83\data\new E:\bf\%date:~0,10% /y
exit

