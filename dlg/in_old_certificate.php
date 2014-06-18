<?php
session_start();
include '../include/globals.php';

GrepUtil::InitGP(array('zsid'));
Power::CkPower('E0E');
$rows = $db->get_one("SELECT * FROM {$dbtable['zs_cert']} WHERE id='{$zsid}'");

$params = array(
	'zuzhi_id' => $rows['zuzhi_id'],
	'ht_id' => $rows['ht_id'],
	'htxm_id' => $rows['htxm_id'],
	'xmid' => $rows['xmid'],
	'zsid' => $zsid,
	'iso' => $rows['iso'],
	'audit_ver' => $rows['audit_ver'],
	'htfrom' => $rows['htfrom'],
	'changeitem' => '99',
	'change_bf' => $rows['online'],
	'change_af' => '99',
	'zd_ren' => $_SESSION['username'],
	'zd_date' => date('Y-m-d'),
	//'online'=>'1'
);
DBUtil::Insert_tb($db, $dbtable['zs_change'], $params);
$cgid = $db->insert_id();

$taskvalue = array(
	'zuzhi_id' => $rows['zuzhi_id'],
	'cgid' => $cgid,
	'zsid' => $zsid,
	'htfrom' => $rows['htfrom'],
	'zs_change_date' => date('Y-m-d'),
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Y-m-d")
);
DBUtil::Insert_tb($db, $dbtable['zs_change_task'], $taskvalue);
$new_task_id = $db->insert_id();

$db->query("UPDATE {$dbtable['zs_change']} SET cg_task_id='$new_task_id' WHERE id='$cgid'");
$db->query("UPDATE {$dbtable['zs_cert']} SET online='99' WHERE id='{$zsid}'");
LogRW::logWriter($rows['zuzhi_id'],'标旧证 '.$rows['certNo']);
exit('1');
?>