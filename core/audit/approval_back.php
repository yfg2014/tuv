<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'include/module/Item.php';
GrepUtil::InitGP(array('taskId','zuzhi_id'));
Power::CkPower('C2P');
$Task = new Task();

$t = $Task->query($taskId,array('id'));
if($t['id']==''){
	Url::goto_url("index.php?m=audit&do=approval_list&xmonline=3&", '任务已删除，无法撤销审批');
}

$Task->approval_back($taskId);

$Item = new Item();

$arr = $Item->toArray("taskId='{$taskId}'",array('iso','audit_type'));
$audit_type = array();
foreach ($arr as $v){
	$audit_type []= $v['iso'].'：'.$v['audit_type'];
}
$type = implode('/',$audit_type);

LogRW::logWriter($zuzhi_id, '审核项目撤销'.$type);

Url::goto_url('index.php?m=audit&do=approval_list&xmonline=2', '保存成功');
?>