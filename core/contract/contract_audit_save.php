<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';

Power::CkPower('B2E');

GrepUtil::InitGP(array('zuzhi_id','ht_id','online','ps_other','ps_time'));
GrepUtil::InitGP(array('htxm_id','risk','jichurenri','outsourcing','shanjiangyiju','hedingrenri','shanjiangtiaokuan','audit_code','nqa_audit_code','renzhengfanwei','renzhengfanwei_e','cert_num','one_stage_num','two_stage_num','one_jiandu_num','two_jiandu_num','re_audit_num','approach'));

$online == '' && $online = '1';
//合同数据保存
$value = array(
	'outsourcing' => implode(',',$outsourcing),
	'ps_other' => $ps_other,
	'ps_ren' => $_SESSION['username'],
	'ps_time' => $ps_time,
	'online'=>$online
);

$Contract = new  Contract();
$ht_id = $Contract->save($ht_id,$value);

//合同项目数据保存
$ContractItem = new ContractItem();
foreach ($htxm_id as $k => $v){
	$htxm = $db->get_one("SELECT id FROM {$dbtable['xm_item']} WHERE htxm_id='{$v}' AND htxm_id>'0'");
	if($htxm['id'] == ''){
		$value = array(
			'risk' => $risk[$k],
			'jichurenri' => $jichurenri[$k],
			'shanjiangyiju' => $shanjiangyiju[$k],
			'hedingrenri' => $hedingrenri[$k],
			'shanjiangtiaokuan' => $shanjiangtiaokuan[$k],
			'audit_code' => Cache::cache_audit_code($audit_code[$k]),
			'nqa_audit_code' => Cache::cache_audit_code($nqa_audit_code[$k]),
			'renzhengfanwei' => $renzhengfanwei[$k],
			'renzhengfanwei_e' => $renzhengfanwei_e[$k],
			'cert_num' => $cert_num[$k],
			'one_stage_num' => $one_stage_num[$k],
			'two_stage_num' => $two_stage_num[$k],
			'one_jiandu_num' => $one_jiandu_num[$k],
			'two_jiandu_num' => $two_jiandu_num[$k],
			're_audit_num' => $re_audit_num[$k],
			'approach' => $approach[$k],
			'online'=>$online
		);

		$ContractItem->save($v,$value);
	}
}
LogRW::logWriter($zuzhi_id, '合同项目评审');

Url::goto_url("index.php?m=contract&do=contract_list&online=$online", '保存成功');

?>