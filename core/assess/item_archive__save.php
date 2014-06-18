<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';

GrepUtil::InitGP(array('zuzhi_id','xmid','archivedate','archivecode','archiveother'));
Power::CkPower('D1E');
Power::xt_htfrom($rows['zuzhi_id']);
$params = array(
	'archivedate' => $archivedate,
	'archivecode' => $archivecode,
	'archiveother' => $archiveother,
	'gd_ren' => $_SESSION['username'],
	'gd_time' => date("Y-m-d"),
);

$Item = new Item();
$Item->update($xmid, $params);
LogRW::logWriter($zuzhi_id, '材料项目归档');

Url::goto_url('index.php?m=assess&do=item_archive_list', '保存成功');
?>