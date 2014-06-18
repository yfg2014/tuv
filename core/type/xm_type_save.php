<?php
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/TypeChange.php';

$value = GrepUtil::InitGP(array('cgid','htxm_id','ht_id','xmid','zuzhi_id','ifhuanzheng','renzhengleixing','xm_change_date','other','cw_other','audit_ver_bf','audit_ver_af','product_ver_bf','product_ver_af','changerange','audit_code_bf','audit_code_cg','audit_code_af','renzhengfanwei_bf','renzhengfanwei_cg','renzhengfanwei_af','creatitem','ifaudit','ifchouyan','ifchangecert'));
Power::CkPower('F1E');
Power::xt_htfrom($zuzhi_id);
$TypeChange = new TypeChange($xmid,$value,$cgid);
$TypeChange->TypeSave($value);
Url::goto_url("index.php?m=type&do=xm_type_list", '保存成功');
?>