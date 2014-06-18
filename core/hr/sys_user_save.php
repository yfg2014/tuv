<?php
/**
 * 权限编辑
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
Power::CkPower('J1E');
//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','user','password','sys_quanxian','online');

$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/Hr_information.php');

unset($value['sys_quanxian']);
$value['power'] = implode(',',(array)$sys_quanxian);


$s = new Hr_information();

/**
 * 还缺少相同账号判断
 */

if (empty($password)) {
	unset($value['password']);
}else{
	$value['password'] = md5($password);
}

if ($id) {
	$re = $s->update($id,$value);
	LogRW::logWriter('','编辑人员'.Cache::cache_username($id).'权限信息');
}

Url::goto_url('index.php?m=hr&do=sys_user_edit&hr_id='.$id, '保存成功');
?>