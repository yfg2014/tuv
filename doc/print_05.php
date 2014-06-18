<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';

GrepUtil::InitGP(array('zuzhi_id','taskId'));

$w['dateNow'] = date("Y 年 m 月 d 日");

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);

$w['eientercode'] = $com['eientercode'];        //档案号
$w['eiregistername'] = $com['eiregistername'];        //企业名称
$w['eilinkman'] = $com['eilinkman'];                    //联系人
$w['eiphone'] = $com['eiphone'];                    //电话
$w['eifax'] = $com['eifax'];                          //传真

$query = $db->query("SELECT id,empId,empName,taskBeginDate,taskEndDate FROM xm_auditor WHERE taskId='{$taskId}'");
while ($rows = $db->fetch_array($query)){
	
	if($w['taskDate'] == ''){
		$w['taskDate'] = $rows['taskBeginDate'].' 至 '.$rows['taskEndDate'];
	}
	
	$plan = array();
	$plan_sql = "SELECT a.isLeader, b.phone" .
			" FROM `xm_auditor_plan` a LEFT JOIN `hr_information` b" .
			" ON a.empId = b.id WHERE a.empId = '{$rows[empId]}'" .
			" AND a.auditorId = '{$rows[id]}' AND b.id = '{$rows[empId]}'";
	$plan = $db->get_one($plan_sql);
	
	if($plan['isLeader'] == '1'){
		$isLeader []= $rows['empName'].' （'.$plan['phone'].'）'; 
	}else{
		$auditor []=  $rows['empName'].' （'.$plan['phone'].'）'; 
	}
}

$w['isLeader'] = implode('<br />',array_unique($isLeader));	//组长
$w['empName'] = implode('<br />',array_unique($empName));	//所有审核员

$name = preg_replace ("/[\/\\ <> .\*\?\:\|]/","",$w['eiregistername']);
$filename = iconv("utf-8","gbk//IGNORE",$name.'-审核计划表.doc');
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Disposition: attachment; filename=".$filename);

$localimg = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/image003.jpg';
$localh = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_05.htm';
$localx = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_05.xml';

foreach($w as $k=>$v){
	$w[$k] = iconv("utf-8","gbk//IGNORE",$v);
}

require "./doc/doc/print_05.doc";

?>
