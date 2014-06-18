<?php
/**
 * 修改密码
 * @2011-5-9
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','m_server','email','m_password');
GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集

	$value['m_server'] = $m_server;
	$value['email'] = $email;
	$value['m_password'] = $m_password;
	$s	= new hr_information();
	$re = $s->update($id,$value);
	LogRW::logWriter('','修改人员'.Cache::cache_username($id).'邮箱');
	Url::goto_url('index.php?m=hr&do=sys_mail_edit', '保存成功');

?>