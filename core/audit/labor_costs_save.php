<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Auditor.php';

GrepUtil::InitGP(array('auditorId','laborcosts','printdate','paydate'));
Power::CkPower('C5E');
$params = array(
	'laborcosts' => $laborcosts,
	'printdate' => $printdate,
	'paydate' => $paydate
);
if($paydate != '0000-00-00' && $paydate != ''){
	$params['online'] = '1';
}
$Auditor = new Auditor();
$Auditor->update($auditorId, $params);

Url::goto_url('index.php?m=audit&do=labor_costs_list&online=0', '保存成功');
?>