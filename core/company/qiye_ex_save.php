<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/CompanyEx.php');

Power::CkPower('A0E');

$value = GrepUtil::InitGP(array('id','zuzhi_id','eiregistername','eiregistername_e','address','address_e','eilinkman','eilinkman_mob','eiphone','eifax','remark'));//过滤数据，获取数据集

include_once S_DIR.'include/module/Company.php';//引用企业模块数据处理文

$company_ex = new CompanyEx();

if ($id > 0) {
	$company_ex->EditCompanyEx($value,$id); //修改企业
} else {
	$company_ex->AddCompanyEx($value); //新增企业
}

Url::goto_url('index.php?m=company&do=qiye_ex_list&zuzhi_id='.$zuzhi_id, '保存成功');
?>