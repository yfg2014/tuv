<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Review.php';

GrepUtil::InitGP(array('eid','hr_id','license_type','code','iso','remark'));

Power::CkPower('H8E');

$Review = new Review();

$code = str_replace('\r\n','',$code);
$code = str_replace('\n','',$code);
$params = array(
	'hr_id' => $hr_id,
	'code' => $code,
	'iso' => $iso,
	'license_type' => $license_type,
	'remark' => $remark,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Y-m-d")
);

$Review->save($eid,$params);

Url::goto_url('index.php?m=hr&do=hr_review_list', '保存成功');
?>
