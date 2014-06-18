<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('pid','upplan','upplan_online'));
Power::CkPower('I3E');
foreach($pid as $v){
	$db->query("UPDATE $dbtable[xm_item] SET upplan='$upplan[$v]' WHERE id='$v' AND online='3'");
}
$upplan_online == '1' ? $upplan_online = '' : $upplan_online = '1';
$pid = implode("','",$pid);
$upplan_online == '1' ? $msg = '审核计划上报' : $msg = '取消审核计划上报';
$sql = $db->query("SELECT zuzhi_id,iso FROM xm_item WHERE id IN('$pid')");
while($v = $db->fetch_array($sql)){
	LogRW::logWriter($v['zuzhi_id'], $v['iso'].$msg);
}
Url::goto_url("index.php?m=report&do=authentication_activity_list&upplan_online=$upplan_online", '保存成功');
?>