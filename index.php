<?php
session_start();
header("Cache-control: private");
/**
 * 初始化变量
 */
$m = '';
$do = '';
$power_ck = '';
header("Content-Type: text/html; charset=utf-8");
include dirname(__FILE__).'/'.'include/globals.php';
include S_DIR."conf/left_menu.php";
GrepUtil::InitGP(array('m','do','menu','core'));
Power::ckLogin('login.php');

if($m == 'setup'){
	Power::CkPower('J0E');
}
foreach($left_menu as $vv){
	foreach($vv as $v){
		if(strpos($v['itemurl'],$do)!==false){
			$main_title = $v['title'];break;
		}
	}
}

if (file_exists("core/$m/$do.php")) 
{
	include_once "core/$m/$do.php";
}
elseif(file_exists("oa/$m/$do.php"))
{
	include_once "oa/$m/$do.php";
} 
elseif (file_exists("$m/$do.php")) 
{
	include_once "$m/$do.php";
} 
elseif (file_exists("$do.php")) 
{
	include_once "$do.php";
} 
else 
{
	Url::goto_url('main.php');
}
?>