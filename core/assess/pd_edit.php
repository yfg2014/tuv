<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'/include/module/Evaluate.php';
include_once S_DIR.'/include/module/Files.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'/include/setup/setup_pd_online.php';
//include_once S_DIR.'/include/setup/setup_usertype.php';
include_once S_DIR.'include/setup/setup_mark.php';

GrepUtil::InitGP(array('pdid','taskId','zuzhi_id','ht_id'));
Power::CkPower('D0E');
Power::xt_htfrom($zuzhi_id);
$AssessmentItem = new AssessmentItem();
$Evaluate = new Evaluate();
$Files = new Files();
if($taskId!='' && $taskId!='0'){
	$arr = $AssessmentItem->toArray("taskId='{$taskId}' ORDER BY kind ASC");
	$pdRenyuan = $Evaluate->toArray("taskId='{$taskId}'");
	$add = $Files->toArray("taskId='{$taskId}' ORDER BY id ASC");
}else{
	$arr = $AssessmentItem->toArray("id='{$pdid}' ORDER BY kind ASC");
	$pdRenyuan = $Evaluate->toArray("pdid='{$pdid}'");
	$add = $Files->toArray("id='{$pdid}' ORDER BY id ASC");
}
//取所有合同id,TAG列表用
foreach($arr as $v){
	$ht_id_arr []= $v['ht_id'];
	$htxm_id_arr []= $v['htxm_id'];
}

$zs = $db->get_one("SELECT zsprintdate FROM {$dbtable['zs_cert']} WHERE pdid='{$pdid}'");
if($zs['zsprintdate'] != '0000-00-00' && $zs['zsprintdate'] != '')
{
	$disabled = 'disabled="disabled"';
	$value = '<font color="red">(证书已登记不能修改)</font>';
}

//变更信息
if($taskId){
	$xm_q = $db->query("SELECT id,iso FROM `xm_item` WHERE taskId='{$taskId}'");
}else{
	$xm_q = $db->query("SELECT id,iso FROM `xm_item` WHERE id =('{$arr[0][xmid]}')");
}

while($xm_fetch = $db->fetch_array($xm_q)){
	$xmid_arr []= $xm_fetch['id'];
}
$xmid_arr = implode("','",$xmid_arr);

$query = $db->query("SELECT iso,xm_change_date,changerange,renzhengleixing FROM xm_rzlx WHERE (xmid IN('$xmid_arr') AND xmid!='0') OR (pdid='$pdid' AND pdid!='0') ORDER BY renzhengleixing DESC");
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
$query = $db->query("SELECT iso,changeitem,changereason,zs_change_date FROM zs_change WHERE (xmid IN('$xmid_arr') AND xmid!='0') OR (pdid='$pdid' AND pdid!='0') and changeitem!='99'");
while($row = $db->fetch_array($query)){
	$change[] = array(
	'iso'=>$row['iso'],
	'msg'=> Cache::cache_changeitem($row['changeitem']),
	'changedate'=> $row['zs_change_date'],
	'changereason'=> Cache::cache_setup_stop($row['changereason'])
	);
}

$width='700px';
$id_arr = array('taskId'=>$taskId,'zuzhi_id'=>$zuzhi_id,'ht_id'=>$ht_id_arr,'htxm_id'=>$htxm_id_arr);
$params = array('company' => array(),'contract' => array(),'task' => array(),'item'=>array(),'certificate'=>array(),'finance'=>array(),'sampling'=>array());
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'assess/pd_edit.htm';
include T_DIR.'footer.htm';
?>