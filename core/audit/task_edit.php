<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/module/Change.php';
include_once S_DIR.'include/setup/setup_changeitem.php';

GrepUtil::InitGP(array('pid','taskId'));
Power::CkPower('C0E');
 $xmid =array();
if($taskId){
	$Task = new Task();
	$result = $Task->query($taskId);
	$Item = new Item();
	$ContractItem = new ContractItem();
	$rows = $Item->toArray("taskId='{$taskId}' ORDER BY id ASC",array('id','htxm_id','iso','audit_ver','audit_type','xm_other','product','renzhengfanwei','xcd','upplan','online'));
	foreach ($rows as $v){
		$pidArr []= $v['id'];
		$ht_id []= $v['ht_id'];
		$ht_other =  $db->get_one("SELECT other  FROM ht_contract WHERE id = '$v[ht_id]'"); //合同备注
		$xm_other []= $v['xm_other'];
		$upplan []= $v['upplan'];
		$ht = $ContractItem->query($v['htxm_id'],array('hedingrenri')); //查合同评审的核定人日
		if($v['iso'] == 'P'){
			$hedingrenri [$v['iso']] < '0.5' && $hedingrenri [$v['iso']]= $ht['hedingrenri'];
			$iso [$v['iso']]= $v['iso'];
			$audit_ver [$v['iso']]= $v['audit_ver'];
			$xcd [$v['iso']]= $v['xcd'];
			$v['audit_type'] = Cache::cache_audit_type($v['audit_type'],0);
			$audit_type [$v['iso']] == '' ? $audit_type [$v['iso']] = $v['audit_type'] : $audit_type [$v['iso']]= $audit_type [$v['iso']].','.$v['audit_type'];
		}else{
			$hedingrenri [$v['iso']]= $ht['hedingrenri'];
			$iso []= $v['iso'];
			$audit_ver []= $v['audit_ver'];
			$v['audit_type'] = Cache::cache_audit_type($v['audit_type'],0);
			$audit_type []= $v['audit_type'];
			$xcd []= $v['xcd'];
		}

	}
	$xmid	=$pidArr;
	$zuzhi_id = $result['zuzhi_id'];
	if($result['jinxianchang'] == '0'){
		$checked = 'checked="checked"';
	}else{
		$checked1 = 'checked="checked"';
	}

	$time_s_int = (int)$result['taskBeginHalfDate'];
	$time_e_int = (int)$result['taskEndHalfDate'];
	${'time_s_select'.$time_s_int} = 'selected';
	${'time_e_select'.$time_e_int} = 'selected';
}else{
	//初始时间选择
	$time_s_select8 = 'selected';
	$time_e_select17 = 'selected';

	$zuzhi_id = '';
	$iso = array();
	$pidArr = $pid;
	$aid = implode('\',\'',$pid);

	$Item = new Item();
	$ContractItem = new ContractItem();
	$rows = $Item->toArray("id IN('{$aid}') ORDER BY id ASC",array('id','zuzhi_id','ht_id','htxm_id','iso','audit_ver','audit_type','xm_other','product','renzhengfanwei','xcd','online'));
	foreach($rows as $v){
		 $xmid []=$v['id'];
		 $ht_id []= $v['ht_id'];
	$ht_other =  $db->get_one("SELECT other  FROM `ht_contract` WHERE `id` = '$v[ht_id]'"); //合同备注
		 $v['audit_type'] = Cache::cache_audit_type($v['audit_type'],0);
		if($v['online'] != '0'){
			Url::goto_url('./index.php?m=audit&do=xm_no_list', $v['iso'].' 产品已安排，操作失败');
		}
		if($v['iso'] == 'P'){
			$iso [$v['iso']]= $v['iso'];
			$audit_ver [$v['iso']]= $v['audit_ver'];
			$xcd [$v['iso']]= $v['xcd'];
			$audit_type [$v['iso']] == '' ? $audit_type [$v['iso']] = $v['audit_type'] : $audit_type [$v['iso']]= $audit_type [$v['iso']].','.$v['audit_type'];
		}else{
			$iso []= $v['iso'];
			$audit_ver []= $v['audit_ver'];
			$audit_type []= $v['audit_type'];
			$xcd []= $v['xcd'];
		}
		$xm_other []= $v['xm_other'];

		if($zuzhi_id == ''){
			$zuzhi_id = $v['zuzhi_id'];
		}

		$ht = $ContractItem->query($v['htxm_id'],array('hedingrenri')); //查合同评审的核定人日
		$hedingrenri [$v['iso']]= $ht['hedingrenri'];

	}
}

//变更信息
if($taskId){
	$xm_q = $db->query("SELECT id FROM `xm_item` WHERE taskId='{$taskId}'");
}else{
	$xm_q = $db->query("SELECT id FROM `xm_item` WHERE id IN('{$aid}')");
}

while($xm_fetch = $db->fetch_array($xm_q)){
	$xmid_arr []= $xm_fetch['id'];
}
$xmid_arr = implode("','",$xmid_arr);

$query = $db->query("SELECT iso,xm_change_date,changerange,renzhengleixing FROM xm_rzlx WHERE xmid IN('$xmid_arr') AND pdid='0' ORDER BY renzhengleixing DESC");
while($rows = $db->fetch_array($query)){
	if($rows['renzhengleixing'] == '06'){
		if($rows['changerange'] == '1'){
			$rows['changerange']= '扩大';
		}elseif($rows['changerange'] == '2'){
			$rows['changerange']= '缩小';
		}else{
			$rows['changerange']= '其它';
		}
	}elseif($rows['renzhengleixing'] == '03'){
		$rows['changerange']= '(标准转换)';
	}
	$change[] = array(
	'iso'=>$rows['iso'],
	'msg'=> $rows['changerange'],
	'changedate'=> $rows['xm_change_date']
	);
}
$query = $db->query("SELECT iso,changeitem,changereason,zs_change_date FROM zs_change WHERE xmid IN('$xmid_arr') AND pdid='0' AND changeitem!='99'");
while($row = $db->fetch_array($query)){
	$change[] = array(
	'iso'=>$row['iso'],
	'msg'=> Cache::cache_changeitem($row['changeitem']),
	'changedate'=> $row['zs_change_date'],
	'changereason'=> Cache::cache_setup_stop($row['changereason'])
	);
}


$xm_other = array_unique($xm_other); //TO审核部
Power::xt_htfrom($zuzhi_id);
$width='600px';
$params = array('company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id);
$Information = new Information($id_arr,$width,'',$params);

for($i=0;$i<=23;$i++){
	if($i == 8 or $i == 12){
		$time_s_str .= "<option value=\"$i:00:00\" ".${'time_s_select'.$i}." >$i:00</option>";
	}
}
for($i=0;$i<=23;$i++){
	if($i == 12 or $i == 17){
		$time_e_str .= "<option value=\"$i:00:00\" ".${'time_e_select'.$i}." >$i:00</option>";
	}
}

include T_DIR.'header.htm';
include T_DIR.'audit/task_edit.htm';
include T_DIR.'footer.htm';
?>