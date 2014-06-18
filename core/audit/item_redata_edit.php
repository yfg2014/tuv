<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/setup/setup_data.php';

GrepUtil::InitGP(array('pxmid','zuzhi_id','taskId'));
Power::CkPower('C7E');//资料回收登记
Power::xt_htfrom($zuzhi_id);
$width='600px';
$params = array('company' => array(),'task' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'taskId'=>$taskId);

$Information = new Information($id_arr,$width,'',$params);

$Item = new Item();
if($taskId!='' && $taskId!='0'){
	$arr = $Item->toArray("taskId='{$taskId}' ORDER BY kind ASC");
	$Task = new Task();
	$result = $Task->query($taskId,array('data','fail1','fail2','fail3'));
}else{
	$arr = $Item->toArray("id='{$pxmid}' ORDER BY kind ASC");
}

//变更信息
if($taskId){
	$xm_q = $db->query("SELECT id,iso FROM `xm_item` WHERE taskId='{$taskId}'");
}else{
	$xm_q = $db->query("SELECT id,iso FROM `xm_item` WHERE id =('{$pxmid}')");
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

$qy = $db->get_one("SELECT kehujibie FROM mk_company WHERE id='$zuzhi_id'"); //客户级别

include T_DIR.'header.htm';
include T_DIR.'audit/item_redata_edit.htm';
include T_DIR.'footer.htm';
?>