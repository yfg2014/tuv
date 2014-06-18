<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'include/module/Item.php';
GrepUtil::InitGP(array('taskId','zuzhi_id','sp_date'));
Power::CkPower('C2P');
$Task = new Task();

foreach($taskId as $v){
	$t = $Task->query($v,array('id'));
	if($t['id']==''){
		Url::goto_url("index.php?m=audit&do=approval_list&xmonline=3&", '任务已删除，无法审批通过');
	}
}
$Task->approval($taskId,$sp_date);
if ($zuzhi_id != ''){
	$Item = new Item();
	$arr = $Item->toArray("taskId='{$taskId[0]}'",array('iso','audit_type'));
	$audit_type = array();
	foreach ($arr as $v){
		$audit_type []= $v['iso'].'：'.$v['audit_type'];
	}
	$type = implode('/',$audit_type);
	$msg = '审批审核项目'.$type;
	$url = "approval_edit&taskId=$taskId[0]&zuzhi_id=$zuzhi_id";
}else{
	$msg = '批量审批审核项目';
	$approval = 'approval_list';
	$url = "approval_list&xmonline=3";
}

LogRW::logWriter($zuzhi_id, $msg);
Url::goto_url("index.php?m=audit&do=$url&", '保存成功');
?>