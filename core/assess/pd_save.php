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

GrepUtil::InitGP(array('ppdid','pdid','xmid','htxm_id','taskId','audit_code','renzhengfanwei','describes','assessmentdate','approvaldate','zs_if','ifchangecert','mark','evaluatother','ht_id','zuzhi_id'));
Power::CkPower('D0E');
Power::xt_htfrom($zuzhi_id);

$ContractItem = new ContractItem();
$Item = new Item();
$AssessmentItem = new AssessmentItem();
$MaintenanceItem = new MaintenanceItem();
foreach ($pdid as $k => $v) {
	$value = array(
		'audit_code' => Cache::cache_audit_code($audit_code[$k]),
		'describes' => $describes[$k],
		'renzhengfanwei' => $renzhengfanwei[$k],
		'assessmentdate' => $assessmentdate[$k],
		'approvaldate' => $approvaldate[$k],
		'ifchangecert' => $ifchangecert[$k],
		'zs_if' => $zs_if[$k],
		'evaluatother' => $evaluatother[$k],
		'pd_ren' => $_SESSION['username'],
		'pd_date' => date("Y-m-d"),
	);

	$AssessmentItem->update($v, $value);
	$ContractItem->update($htxm_id[$k], array('mark'=>$mark[$k]));

	if ($zs_if[$k] == '1') {
		//生成下次审核
		$MaintenanceItem->Maintenance($v);
	}else{
		//监督项目撤销
		$MaintenanceItem->re_assess($v);
	}

}

Url::goto_url("index.php?m=assess&do=pd_edit&pdid=$ppdid&taskId=$taskId&ht_id=$ht_id&zuzhi_id=$zuzhi_id", '操作成功');
?>