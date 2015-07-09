<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/Files.php';
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Evaluate.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'/include/setup/setup_pd_online.php';
//include_once S_DIR.'/include/setup/setup_usertype.php';
include_once S_DIR.'include/setup/setup_mark.php';

GrepUtil::InitGP(array('pdid','taskId','zuzhi_id','ht_id','eid'));

Power::CkPower('D1E');
Power::xt_htfrom($zuzhi_id);

$Files = new Files();
$AssessmentItem = new AssessmentItem();
$Evaluate = new Evaluate();

if($taskId!='' && $taskId!='0'){
	$result = $AssessmentItem->toArray("taskId='{$taskId}' GROUP BY iso ASC");
	$pdRenyuan = $Evaluate->toArray("taskId='{$taskId}'");
	$arr = $Files->toArray("taskId='{$taskId}' ORDER BY id ASC");
}else{
	$result = $AssessmentItem->toArray("id='{$pdid}' GROUP BY iso ASC");
	$pdRenyuan = $Evaluate->toArray("pdid='{$pdid}'");
	$arr = $Files->toArray("id='{$pdid}' ORDER BY id ASC");
}

$pdRenyuan == '' ? $pdRenyuan = array() : $pdRenyuan;

//取所有合同id,TAG列表用
foreach($result as $v){
	$htfrom == '' && $htfrom = $v['htfrom'];
	$ht_id_arr []= $v['ht_id'];
	$htxm_id_arr []= $v['htxm_id'];
}

if($eid){
	$pd = $Evaluate->query($eid);
}

$width='700px';
$id_arr = array('taskId'=>$taskId,'zuzhi_id'=>$zuzhi_id,'ht_id'=>$ht_id_arr,'htxm_id'=>$htxm_id_arr);
$params = array('company' => array(),'contract' => array(),'task' => array(),'item'=>array(),'certificate'=>array(),'finance'=>array(),'sampling'=>array());
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'assess/pd_evaluation_edit.htm';
include TEMP.'footer.htm';
?>
