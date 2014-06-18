<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Files.php';

GrepUtil::InitGP(array('pdid','taskId','zuzhi_id','problem','eid','theauthor','submittedate','htfrom','online'));

Power::CkPower('D2E');

$Files = new Files();
foreach($problem as $k=>$v)
{
	$value = array(
		'taskId' => $taskId,
		'zuzhi_id' => $zuzhi_id,
		'pdid' => $pdid,
		'problem' => $v,
		'htfrom' => $htfrom,
		'theauthor' => $theauthor[$k],
		'submittedate' => $submittedate[$k],
		'online' => $online[$k],
		'zd_ren' => $_SESSION['username'],
		'zd_date' => date("Y-m-d"),
	);
	
	$Files->save($eid[$k],$value);	
}
Url::goto_url("index.php?m=assess&do=pd_problem_edit&pdid=$pdid&taskId=$taskId&zuzhi_id=$zuzhi_id", '操作成功');
?>
