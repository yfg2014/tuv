<?php
/**
 * 人员职称
 * @2011-05-5
 */


!defined('IN_SUPU') && exit('Forbidden');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','hr_id','profession','zc_msg','danwei','zc_date','other');

$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/Hr_profession.php');			//人员专业能力类 审核代码

Power::CkPower('H0E');

$s = new hr_profession();

	$value['zd_ren']	=	$_SESSION["username"];
	$value['zd_date']	=	date("Y-m-d");

if ($id > 0) {
    Power::xt_htfrom($id,'hr');
	$re = $s->update($id,$value);
	LogRW::logWriter('', '修改人员'.Cache::cache_username($hr_id).'职称信息');
} else {
	$re = $s->add($value);
	LogRW::logWriter('', '添加人员'.Cache::cache_username($hr_id).'职称信息');
}

Url::goto_url('index.php?m=hr&do=hr_profession&hr_id='.$hr_id, '保存成功');
?>