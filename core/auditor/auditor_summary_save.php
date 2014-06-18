<?php
!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('zuzhi_id','taskId','prompt','summary','communicate','provisions'));

$params = array(
	'prompt' => $prompt,
	'summary' => $summary,
	'communicate' => $communicate,
	'provisions' => $provisions,
);
DBUtil::update_tb($db, $dbtable['xm_task'], $params, "id='{$taskId}'");

LogRW::logWriter($zuzhi_id, '登记审核方案');
Url::goto_url("index.php?m=auditor&do=au_audit_item_list&", '保存成功');
?>
