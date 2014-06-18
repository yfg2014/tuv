<?php
/**
 * 人员简历
 * @2011-05-5
 */


!defined('IN_SUPU') && exit('Forbidden');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','hr_id','hr_kdate','hr_jdate','danwei','job','other');

Power::CkPower('H0E');
Power::xt_htfrom($hr_id,'hr');
$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/Hr_resume.php');			//人员专业能力类 审核代码


$s = new Hr_resume();

	$value['zd_ren']	=	$_SESSION["username"];
	$value['zd_date']	=	date("Y-m-d");

if ($id > 0) {
	$re = $s->update($id,$value);
	LogRW::logWriter('', '修改人员'.Cache::cache_username($hr_id).'简历信息');
} else {
	$re = $s->add($value);
	LogRW::logWriter('', '添加人员'.Cache::cache_username($hr_id).'简历信息');
}

Url::goto_url('index.php?m=hr&do=hr_resume&hr_id='.$hr_id, '保存成功');
?>