<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';

GrepUtil::InitGP(array('zuzhi_id','ht_id','taskId','pdid'));

$Company = new Company();

$com = $Company->GetCompany($zuzhi_id);
$w['eientercode'] = str_replace('FE', '', $com['eientercode']);        //档案号
$w['eiregistername'] = $com['eiregistername'];        //企业名称
$w['eiregistername_e'] = $com['eiregistername_e'];        //企业名称(英)

$pd = $db->get_one("SELECT taskId,audit_type,audit_code,audit_ver FROM pd_xm WHERE id='$pdid'");
$w['audit_type'] = Cache::cache_audit_type($pd['audit_type']);
$w['audit_code'] = $pd['audit_code'];
$w['audit_ver'] = Cache::cache_audit_ver($pd['audit_ver']); 

$task = $db->get_one("SELECT taskBeginDate,taskEndDate FROM `xm_task` WHERE id='{$pd['taskId']}'");
$w['taskDate'] = date("d",strtotime($task['taskBeginDate'])).'~'.date("d.m.Y",strtotime($task['taskEndDate'])); //审核时间
	
$name = preg_replace ("/[\/\\ <> .\*\?\:\|]/","",$w['eiregistername']);
$filename = iconv("utf-8","gbk//IGNORE",$name.'-评定结果表.doc');
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Disposition: attachment; filename=".$filename);

$localh = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_04.htm';
$localx = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_04.xml';

foreach($w as $k=>$v){
	$w[$k] = iconv("utf-8","gbk//IGNORE",$v);
}

require "./doc/doc/print_04.doc";

?>
