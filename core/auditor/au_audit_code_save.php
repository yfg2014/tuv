<?php
/**
 * 人员专业能力
 * @2011-5-5
 */


!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','hr_id','iso','qualification','dalei','xiaolei','ability_source','sbyj','shenbaodate','online','other');
Power::CkPower('K0E');
Power::xt_htfrom($hr_id,'hr');
$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集

if($online!='2'){
	exit('专业代码已经为有效，不能再修改');
}

$s = new Hr_audit_code();
if ($id > 0) {
	$re = $s->update($id,$value);
} else {
	$value['zd_ren']	=	$_SESSION["username"];
	$value['zd_date']	=	date("Y-m-d");
	$re = $s->add($value);
}

Url::goto_url('index.php?m=auditor&do=au_audit_code&hr_id='.$hr_id, '保存成功');
?>