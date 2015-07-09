<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/setup_changeitem_iso_db.php';
include_once S_DIR.'include/setup/setup_changeitem_pro_db.php';
include_once S_DIR.'include/setup/setup_new_certificate_online.php';
include_once S_DIR.'include/setup/setup_new_zs_stop.php';
include_once S_DIR.'include/setup/setup_new_zs_revocation.php';
include_once S_DIR.'include/setup/setup_new_iso.php';

GrepUtil::InitGP(array('begindate','enddate','kind'));

Power::CkPower('I2S');

$kind == '' && $kind = '1';
switch($kind){
	case '1':
	include_once S_DIR.'core/report/new_monthly_list.php';
	$urlxls = './index.php?m=xls&do=new_monthly_list_xls';
	break;
	case '2':
	include_once S_DIR.'core/report/new_auditor.php';
	$urlxls = './index.php?m=xls&do=new_auditor_xls';
	break;
}

//默认当月开始和结束时间
if ($begindate=='' or $enddate==''){
	if($ydate==""){$ydate=date("Y");}
	if($mdate==""){$mdate=date("m");}
	$tianshu=date("t",mktime(0,0,0,$mdate,01,$ydate));			//获得当月天数

	$begindate	=$ydate."-".$mdate."-01";						//取数开始日期
	$enddate	=$ydate."-".$mdate."-".$tianshu;				//取数截止日期
}

include TEMP.'header.htm';
include TEMP.'report/new_monthly_find.htm';
include TEMP.'footer.htm';
?>
