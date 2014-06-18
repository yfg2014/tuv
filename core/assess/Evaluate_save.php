<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'/include/module/Evaluate.php';

GrepUtil::InitGP(array('pdid','ppdid','username','taskId','pdId','hr_id','rating','thendate','rossdate','billingdate','fees','ht_id','zuzhi_id'));

Power::CkPower('D1E');

if(empty($pdid)) {
	echo"<script type=\"text/javascript\">";
	echo"alert('评定人员-评定的体系至少勾选一项',history.go(-1))";
	echo"</script>";
	exit;
}

$AssessmentItem = new AssessmentItem();
$Evaluate = new Evaluate();
foreach ($pdid as $v) {
	$result = $AssessmentItem->query($v);
	foreach ($username as $k => $vl) {
		$value = array(
			'pdid' => $v,
			'xmid' => $result['xmid'],
			'zuzhi_id' => $result['zuzhi_id'],
			'ht_id' => $result['ht_id'],
			'htfrom' => $result['htfrom'],
			'taskId' => $taskId,
			'hr_id' => $hr_id[$k],
			'username' => $vl,
			'iso' => $result['iso'],
			'rating' => $rating[$k],
			'thendate' => $thendate[$k],
			'rossdate' => $rossdate[$k],
			'billingdate' => $billingdate[$k],
			'fees' => $fees[$k],
			'zd_ren' => $_SESSION['username'],
			'zd_date' => date("Y-m-d"),
		);

		$Evaluate->save($pdId[$k], $value);
	}
}

Url::goto_url("index.php?m=assess&do=pd_evaluation_edit&taskId=$taskId&pdid=$ppdid&zuzhi_id=$zuzhi_id&ht_id=$ht_id&", '操作成功');
?>