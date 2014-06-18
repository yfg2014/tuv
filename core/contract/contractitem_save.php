<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';

Power::CkPower('B2E');

GrepUtil::InitGP(array('zuzhi_id','ht_id','htxm_id','re_views','iso_people_num','shanjiangtiaokuan','one_stage_num','two_stage_num','two_stage_num','one_jiandu_num','two_jiandu_num','re_audit_num','audit_code','nqa_audit_code','renzhengfanwei'));


//合同项目修改的数据保存
$ContractItem = new ContractItem();

$zs = $db->get_one("SELECT id FROM {$dbtable['zs_cert']} WHERE htxm_id='{$htxm_id}'");
if($zs['id'] == '' or $zs['id'] == '0'){
	$value = array(
		're_views' => $re_views,
		'iso_people_num' => $iso_people_num,
		'one_stage_num' => $one_stage_num,
		'two_stage_num' => $two_stage_num,
		'one_jiandu_num' => $one_jiandu_num,
		'two_jiandu_num' => $two_jiandu_num,
		're_audit_num' => $re_audit_num,
		'shanjiangtiaokuan' => $shanjiangtiaokuan,
		'audit_code' => Cache::cache_audit_code($audit_code),
		'nqa_audit_code' => Cache::cache_audit_code($nqa_audit_code),
		'renzhengfanwei' => $renzhengfanwei
	);
	$ContractItem->ContractItemSave($htxm_id,$value);
}


LogRW::logWriter($zuzhi_id, '合同项目修改');
Url::goto_url("index.php?m=contract&do=contractitem_edit&zuzhi_id=$zuzhi_id&ht_id=$ht_id&htxm_id=$htxm_id", '保存成功');
?>