<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';

GrepUtil::InitGP(array('zuzhi_id','taskId'));

$w['dateNow'] = date("Y 年 m 月 d 日");

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);

$w['eientercode'] = $com['eientercode'];        //档案号
$w['eiregistername'] = $com['eiregistername'];        //企业名称
$w['eiregistername_e'] = $com['eiregistername_e'];        //企业名称
$w['eisc_address'] = $com['eisc_address'];          //通讯地址
$w['eisc_address_e'] = $com['eisc_address_e'];          //通讯地址(英)

$w['eilinkman'] = $com['eilinkman'];                    //联系人
$w['eiphone'] = $com['eiphone'];                    //电话
$w['eifax'] = $com['eifax'];                          //传真


$task = $db->get_one("SELECT audit_ver,taskBeginDate,taskEndDate FROM `xm_task` WHERE id='{$taskId}'");

$audit_ver_tmp = explode(',', $task['audit_ver']);
foreach($audit_ver_tmp as $v){
	$audit_ver []= Cache::cache_audit_ver($v);
}
$w['audit_ver'] = implode(' & ', $audit_ver); //标准版本

//审核时间
$w['taskDate'] = date("Y年m月d日", strtotime($task['taskBeginDate'])).' 至 '.date("Y年m月d日", strtotime($task['taskEndDate']));
$w['taskDate_e'] = date("d.m.Y", strtotime($task['taskBeginDate'])).' - '.date("d.m.Y", strtotime($task['taskEndDate']));

$name = preg_replace ("/[\/\\ <> .\*\?\:\|]/","",$w['eiregistername']);
$filename = iconv("utf-8","gbk//IGNORE",$name.'-审核通知函.doc');
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Disposition: attachment; filename=".$filename);

$localimg = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/images/';
$localh = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_06.htm';
$localx = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_06.xml';

foreach($w as $k=>$v){
	$w[$k] = iconv("utf-8","gbk//IGNORE",$v);
}

require "./doc/doc/print_06.doc";

?>
