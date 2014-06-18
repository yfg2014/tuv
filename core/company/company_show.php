<?php
!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('zuzhi_id'));

$main_title = '企业信息';

$com = $db->get_one("SELECT * FROM {$dbtable['mk_company']} WHERE id='{$zuzhi_id}'");
$com['htfrom'] = Cache::cache_htfrom($com['htfrom']);

//证书
$sql = "SELECT * FROM {$dbtable[zs_cert]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' and zsprintdate!='0000-00-00' ORDER BY id DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
	$rows['online'] = Cache::cache_Certification_online($rows['online']);
	$zs []= $rows;
}

//审核项目
$sql = "SELECT * FROM {$dbtable[xm_item]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' ORDER BY id DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
	if($rows['audit_type'] != '一阶段'){
		$cw = $db->get_one("SELECT * FROM {$dbtable['cw_finance_list_ex']} WHERE xmid='{$rows['id']}'");
		$rows['feespaid'] = $cw['feespaid'];
		$rows['costtime'] = $cw['costtime'];
		$rows['invoice'] = $cw['invoice'];
		$rows['invoicemoney'] = $cw['invoicemoney'];
		$rows['invoicemoneytime'] = $cw['invoicemoneytime'];
		$cwarr []= $rows;
	}
	$rows['audit_code'] = str_replace('；', '<br/>', $rows['audit_code']);
	$rows['online'] = Cache::cache_item_online($rows['online']);
	$xm []= $rows;
}

//任务
$sql = "SELECT * FROM {$dbtable[xm_task]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' ORDER BY taskEndDate DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$rows['online'] = Cache::cache_task_online($rows['online']);
	$arr = explode(',',$rows['audit_type']);
	$type = $empName = '';
	foreach($arr as $v){
		$type []= Cache::cache_audit_type($v);
	}
	$rows['audit_type'] = implode('<br/>',$type);
	
	$query2 = $db->query("SELECT empName FROM {$dbtable[xm_auditor]} WHERE taskId='{$rows['id']}' ORDER BY id ASC");
	while ($v = $db->fetch_array($query2)){
		$empName []= $v['empName'];
	}
	$rows['empName'] = implode(';',$empName);
	$task []= $rows;
}

//评定
$sql = "SELECT * FROM {$dbtable[pd_xm]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' ORDER BY id DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
	$xmt = $db->get_one("SELECT taskBeginDate,taskEndDate FROM {$dbtable['xm_item']} WHERE id='{$rows['xmid']}'");
	$rows['taskBeginDate'] = $xmt['taskBeginDate'];
	$rows['taskEndDate'] = $xmt['taskEndDate'];
	$username = '';
	$query2 = $db->query("SELECT username FROM {$dbtable[pd_evaluation_hr]} WHERE pdid='{$rows['id']}' ORDER BY id ASC");
	while ($v = $db->fetch_array($query2)){
		$username []= Cache::cache_audit_type($v['username']);
	}
	if($rows['sp_if'] == '1'){
		$rows['sp_if'] = '已审批';
	}else{
		$rows['sp_if'] = '未审批';
	}
	$rows['empName'] = implode(';',$username);
	$pd []= $rows;
}

//合同
$sql = "SELECT * FROM {$dbtable[ht_contract]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' ORDER BY id DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$type = '';
	$query2 = $db->query("SELECT audit_type FROM {$dbtable[ht_contract_item]} WHERE ht_id='{$rows['id']}' ORDER BY id ASC");
	while ($v = $db->fetch_array($query2)){
		$type []= Cache::cache_audit_type($v['audit_type']);
	}
	$rows['audit_type'] = implode('<br/>',$type);
	$rows['online'] = Cache::cache_ht_online($rows['online']);
	$ht []= $rows;
}

//上传资料
$sql = "SELECT * FROM {$dbtable[mk_company_file]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' ORDER BY id DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$rows['filekind'] = Cache::cache_company_uploadfile($rows['filekind']);
	$zl []= $rows;
}

//多现场
$sql = "SELECT * FROM {$dbtable[mk_company_ex]} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' ORDER BY id DESC";
$query = $db->query($sql);
while ($rows = $db->fetch_array($query)){
	$dxc []= $rows;
}

$width= '700px';
include T_DIR.'header.htm';
include T_DIR.'company/company_show.htm';
include T_DIR.'footer.htm';
?>
