<?php
session_start();
include '../include/globals.php';

GrepUtil::InitGP(array('id'));

$db->query("UPDATE {$dbtable['xm_item']} SET online='9' WHERE id='{$id}'");

$rows = $db->get_one("SELECT zuzhi_id,audit_type FROM {$dbtable['xm_item']} WHERE id='{$id}'");
$rows["audit_type"] = Cache::cache_audit_type($rows["audit_type"]);
LogRW::logWriter($rows['zuzhi_id'],'监督维护：标作废&nbsp;'.$rows["audit_type"]);
?>