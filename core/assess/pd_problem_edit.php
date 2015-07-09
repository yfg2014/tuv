<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'/include/module/Files.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('pdid','xmid','taskId','zuzhi_id','eid'));

Power::CkPower('D2E');
Power::xt_htfrom($zuzhi_id);

$Files = new Files();
$AssessmentItem = new AssessmentItem();
if($taskId!='' && $taskId!='0'){
	$result = $AssessmentItem->toArray("taskId='{$taskId}' ORDER BY kind ASC");
	$arr = $Files->toArray("taskId='{$taskId}' ORDER BY id ASC");
}else{
	$result = $AssessmentItem->toArray("id='{$pdid}' ORDER BY kind ASC");
	$arr = $Files->toArray("id='{$pdid}' ORDER BY id ASC");
}
//取所有合同id,TAG列表用
foreach($result as $v){
	$htfrom == '' && $htfrom = $v['htfrom'];
	$ht_id_arr []= $v['ht_id'];
	$htxm_id_arr []= $v['htxm_id'];
}

if($eid != '')
{	
	$rows = $Files->query($eid);
}

$width='700px';
$id_arr = array('taskId'=>$taskId,'zuzhi_id'=>$zuzhi_id,'ht_id'=>$ht_id_arr,'htxm_id'=>$htxm_id_arr);
$params = array('company' => array(),'contract' => array(),'task' => array(),'item'=>array(),'certificate'=>array(),'finance'=>array(),'sampling'=>array());
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'assess/pd_problem_edit.htm';
include TEMP.'footer.htm';
?>
