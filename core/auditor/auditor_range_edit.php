<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Evaluate.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'/include/setup/setup_pd_online.php';
include_once S_DIR.'/include/setup/setup_usertype.php';
include_once S_DIR.'include/setup/setup_mark.php';

GrepUtil::InitGP(array('taskId','zuzhi_id'));
Power::CkPower('K0W');

$hrts = $db->get_one("SELECT id FROM xm_auditor WHERE empId='{$_SESSION['userid']}' and taskId='$taskId' GROUP BY empId");
$zz = $db->get_one("SELECT id FROM xm_auditor_plan WHERE taskId='$taskId' and isLeader='1' and auditorId='{$hrts['id']}'");
if($zz['id'] == ''){
	Url::goto_url('', '不是审核组长，不能修改信息');
}

$Item = new Item();
$arr = $Item->toArray("taskId='{$taskId}' ORDER BY kind ASC");
//取所有合同id,TAG列表用
foreach($arr as $v){
	$ht_id_arr []= $v['ht_id'];
	$htxm_id_arr []= $v['htxm_id'];
}

$Task = new Task();
$rows = $Task->query($taskId);

$width='700px';
$id_arr = array('taskId'=>$taskId,'zuzhi_id'=>$zuzhi_id,'ht_id'=>$ht_id_arr,'htxm_id'=>$htxm_id_arr);
$params = array('company' => array(),'contract' => array(),'task' => array(),'item'=>array(),'certificate'=>array());
$Information = new Information($id_arr,$width,'',$params);

$width = '700px';
include TEMP.'header.htm';
include TEMP.'auditor/auditor_range_edit.htm';
include TEMP.'footer.htm';
?>