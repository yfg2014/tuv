<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Task.php';

GrepUtil::InitGP(array('zuzhi_id','taskId'));
Power::CkPower('C1D');

$Item = new Item();
$arr = $Item->toArray("taskId='{$taskId}'",array('iso','audit_type'));
$audit_type = array();
foreach ($arr as $v){
	$audit_type []= $v['iso'].'：'.$v['audit_type'];
}
$iso = implode('/',$audit_type);

$Task = new Task();
$del = $Task->delete($taskId);

if($del){
	$msg = '操作成功';
}else{
	$msg = '操作有误';
}
LogRW::logWriter($zuzhi_id, '审核任务项目删除 '.$iso);

Url::goto_url('index.php?m=audit&do=xm_no_list', $msg);
?>