<?php
!defined('IN_SUPU') && exit('Forbidden');

//要处理的字段数据 字段名和POST名必须一样
$HrFields = array(
'id','idcode','username','username_e','sex','groupdate','birthday','national','politics','htfrom','shenfenkind','cardid','cardaddress','working',
'city','city_msg','phone','email','tel','address','dwtel','dwdizhi','fax','postcode','bankmsg','bankcode',
'xingzhi','contract_type','agency','agency_date',
'worktype','suozaidanwei','hetonghao','ruzhidate','online','lizhidate','pinyongdate','pinyongover','baomi','baomidate',
'yjm','shebaohao','use_lev','bumen','other','technical','workingtime','language','language_level','position','healthystart','healthyend'
);

$value = GrepUtil::InitGP($HrFields);	//过滤数据，获取数据集
include(S_DIR.'include/module/Hr_information.php');

Power::CkPower('H0E');
$value['xingzhi'] = implode(',',(array)$xingzhi);
$value['contract_type'] = implode(',',(array)$contract_type);

//注销不存的字段
$value['province']	=	substr($value['city'], 0,2).'0000';

$s = new hr_information();

if ($id > 0) {
   	Power::xt_htfrom($id,'hr');
	$value['online']	=	'1';
	$id = $s->update($id,$value);
	LogRW::logWriter('', '修改人员'.Cache::cache_username($id).'基本信息');
} else {

	$value['user']		=	time();	//默认添加 user字段，user不能为空，不能重复
	$value['online']	=	'1';
	$id = $s->add($value);
	LogRW::logWriter('', '添加人员'.Cache::cache_username($id).'基本信息');
}


Url::goto_url('index.php?m=hr&do=hr_reg_qualification&hr_id='.$id, '保存成功');
?>