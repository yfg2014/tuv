<?php
// 数据库运行时错误，连接错误等
Class DB_ERROR {
	function DB_ERROR($msg) {
		global $db_bbsname,$db_obstart,$REQUEST_URI,$dbhost,$pwServer,$db_charset;
		$sqlerror = mysql_error();
		$sqlerrno = mysql_errno();
		$sqlerror = str_replace($dbhost,'dbhost',$sqlerror);
		ob_end_clean();
		$db_obstart && function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
		echo"<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><title>$db_bbsname</title><style type='text/css'>P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:11px;}A { TEXT-DECORATION: none;}a:hover{ text-decoration: underline;}TABLE{TABLE-LAYOUT:fixed;WORD-WRAP: break-word}TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}</style><body>\n\n";
		echo"<table><tr><td>$msg";

		echo"<br><br><b>MYSQL执行错误</b>:<br>$sqlerror  ( $sqlerrno )  ";
		echo"<br><br><b>请与贵公司系统管理员联系</b>";
		echo"</td></tr></table></body></html>";
		$this->dblog($msg);
		exit;
	}
	function dblog($msg){
		$msg = str_replace(array("\n","\r","<"),array('','','&lt;'),$msg);
		if (file_exists(S_DIR.'log/dblog.php')){
			File::writeFile('log/dblog.php',"$msg\n",'ab');
		} else{
			File::writeFile('log/dblog.php',"<?php die;?>\n$msg\n");
		}
	}
}
?>