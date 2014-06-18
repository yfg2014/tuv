<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Auditor.php';

GrepUtil::InitGP(array('op','auditorId','paydate'));

$Auditor = new Auditor();
$params = array('op'=>$op,'paydate'=>$paydate);
$Auditor->approval($auditorId, $params);

Url::goto_url('index.php?m=audit&do=labor_costs_list&online=0', '保存成功');
?>