<?php
session_start();
$do = '';
$uname = '';
$upwd = '';

include dirname(__FILE__).'/'.'include/globals.php';
GrepUtil::InitGP(array('do','uname','upwd','cert'));
switch ($do) {
	case 'login' :
		$hr = new UserLogin();
		if($hr->login($uname,$upwd,$cert,$conf['cert_open']))
		{
			exit('1');
		}
		else
		{
			exit('帐号、密码或数字证书错误');
		}
		break;
	case 'logout' :
		$hr = new UserLogin();
		$hr->logout();
		Url::goto_url("login.php");
		break;
	default:
		include T_DIR.'login.htm';
		break;
}
?>