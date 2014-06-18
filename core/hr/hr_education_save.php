<?php
/**
 * 人员学历
 * @2011-05-5
 */


!defined('IN_SUPU') && exit('Forbidden');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','hr_id','school','study_date','graduate_date','zhuanye','xueli','xuewei','other');

Power::CkPower('H0E');
Power::xt_htfrom($hr_id,'hr');

$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/hr_education.php');			//人员专业能力类 审核代码


$s = new hr_education();

$value['zd_ren']	=	$_SESSION["username"];
$value['zd_date']	=	date("Y-m-d");

if ($id > 0) {
	$id = $s->update($id,$value);
	LogRW::logWriter('', '修改人员'.Cache::cache_username($hr_id).'学历信息');
} else {
	$id = $s->add($value);
	LogRW::logWriter('', '添加人员'.Cache::cache_username($hr_id).'学历信息');
}

Url::goto_url('index.php?m=hr&do=hr_education&hr_id='.$hr_id, '保存成功');
?>