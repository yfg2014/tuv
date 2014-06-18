<?php
/**
 * 修改密码
 * @2011-5-9
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array('id','pasd_old','pasd_new');
GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集

	$s			= new hr_information();
	$ren_psd	= $s->query($id);

if ($ren_psd[password]==MD5($pasd_old)){

	$value['password'] = MD5($pasd_new);
	$re = $s->update($id,$value);
	LogRW::logWriter('','修改人员'.Cache::cache_username($id).'密码');
	Url::goto_url('index.php?m=hr&do=sys_pasd_edit', '保存成功');

}else{
	Url::goto_url('index.php?m=hr&do=sys_pasd_edit', '原密码错误！');
}
?>