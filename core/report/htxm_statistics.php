<?php
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array('htfrom','iso','audit_type'));

$search = '';
if($htfrom != ''){
	$search .= " AND a.htfrom='{$htfrom}'";
}
if($iso != ''){
	$search .= " AND a.iso='{$iso}'";
}
if($audit_type != ''){
	$search .= " AND a.audit_type='{$audit_type}'";
}

$this_year = date('Y');
$last_year = $this_year - 1;

$arr = array();
$arr[0]['name'] = "{$last_year}".'年';
$arr[1]['name'] = "{$this_year}".'年';
for($m = 1;$m <= 12;$m++){
	$s2 = date('Y-m-d',mktime(0,0,0,$m,1,$last_year));
	$e2 = date('Y-m-d',mktime(0,0,0,$m+1,1,$last_year));
	$s1 = date('Y-m-d',mktime(0,0,0,$m,1,$this_year));
	$e1 = date('Y-m-d',mktime(0,0,0,$m+1,1,$this_year));

	$sum1 = $db->get_one("SELECT count(*) sum FROM `{$dbtable['ht_contract_item']}` a LEFT JOIN `{$dbtable['ht_contract']}` b ON a.ht_id=b.id WHERE b.htdate>'$s2' AND b.htdate<'$e2' $search");
	$sum2 = $db->get_one("SELECT count(*) sum FROM `{$dbtable['ht_contract_item']}` a LEFT JOIN `{$dbtable['ht_contract']}` b ON a.ht_id=b.id WHERE b.htdate>'$s1' AND b.htdate<'$e1' $search");
	$arr[0]['data'][$m - 1] = floor($sum1['sum']);
	$arr[1]['data'][$m - 1] = floor($sum2['sum']);
}
foreach($setup_htfrom as $v){
	if($v['code'] == $htfrom){
		$arr[2] = $v['msg'];
	}
}
$arr[3] = $iso == '' ? '所有体系' : $iso;
foreach($setup_audit_type as $v){
	if($v['code'] == $audit_type){
		$arr[4] = $v['msg'];
	}
}
$audit_type == '' && $arr[4] = '全部认证类型';
$htfrom == '' && $arr[2] = '全部办事处（联络处）';
echo json_encode($arr);
?>