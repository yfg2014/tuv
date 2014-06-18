<?php
/**
 * 人员专业能力
 * @2011-5-5
 */


!defined('IN_SUPU') && exit('Forbidden');



//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','zg_id','hr_id','htfrom','iso','qualification','dalei','xiaolei','ability_source','sbyj','shenbaodate','last_chat_date','online','other');
Power::CkPower('H2E');
Power::xt_htfrom($hr_id,'hr');

$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码


$s = new Hr_audit_code();

	$value['zd_ren']	=	$_SESSION["username"];
	$value['zd_date']	=	date("Y-m-d");

if ($id > 0) {
	$dalei = array_unique(explode('.',$value['xiaolei']));
	$value['dalei'] = $dalei[0];
	if($value['dalei'] == ''){
		$value['dalei'] = $value['xiaolei'];
	}
	$id = $s->update($id,$value);

	LogRW::logWriter('', '修改人员'.Cache::cache_username($hr_id).'专业代码');
} else {
	$id = $s->add($value);
	LogRW::logWriter('', '添加人员'.Cache::cache_username($hr_id).'专业代码');
}

Url::goto_url('index.php?m=hr&do=hr_audit_code_edit&hr_id='.$hr_id.'&zg_id='.$zg_id.'&id='.$id, '保存成功');
?>