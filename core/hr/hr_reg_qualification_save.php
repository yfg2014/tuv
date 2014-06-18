<?php
!defined('IN_SUPU') && exit('Forbidden');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','hr_id','htfrom','qualification_type','audit_ver','qualification','qualification_no','s_date','e_date','online','other','yeardate1','yeardate2');
$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/Hr_reg_qualification.php');
include_once SET_DIR.'setup_audit_ver.php';
Power::CkPower('H1E');
Power::xt_htfrom($hr_id,'hr');

function ver_iso($ver){
	global $setup_audit_ver;
	foreach($setup_audit_ver as $k=>$v){
		if($k == $ver){
			$rst = $v['iso'];
			break;
		}
	}
	return $rst;
}
$value['iso'] = ver_iso($value['audit_ver']); //根据标准版本自动获取体系CODE

$s = new Hr_reg_qualification();

	$value['zd_ren']	=	$_SESSION["username"];
	$value['zd_date']	=	date("Y-m-d");

if ($id > 0) {
	$re = $s->update($id,$value);
	LogRW::logWriter('', '修改人员'.Cache::cache_username($hr_id).'注册资格');
} else {
	$re = $s->add($value);
	LogRW::logWriter('', '添加人员'.Cache::cache_username($hr_id).'注册资格');
}

Url::goto_url('index.php?m=hr&do=hr_reg_qualification&hr_id='.$hr_id, '保存成功');
?>