<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/ContractItem.php';
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Complex.php';
include_once S_DIR.'/include/module/Certificate.php';
include_once S_DIR.'/include/module/MaintenanceItem.php';

GrepUtil::InitGP(array('pdid','ppdid','taskId','zuzhi_id','ht_id','zs_if','sp_if','sp_date'));
Power::CkPower('D4E');
Power::xt_htfrom($zuzhi_id);

$ContractItem = new ContractItem();
$Item = new Item();
$AssessmentItem = new AssessmentItem();
$MaintenanceItem = new MaintenanceItem();
foreach ($pdid as $k => $v) {
	$value = array(
		'sp_if' => '1',
		'sp_date' => $sp_date
	);

	$AssessmentItem->update($v, $value);

	if ($zs_if[$k] == '1') {
		//生成下次审核
		$MaintenanceItem->Maintenance($v);
	}else{
		//监督项目撤销
		$MaintenanceItem->re_assess($v);
	}

}

Url::goto_url("index.php?m=assess&do=pd_show&pdid=$ppdid&taskId=$taskId&ht_id=$ht_id&zuzhi_id=$zuzhi_id", '操作成功');
?>