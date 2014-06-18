<?php
/**
 * 添加、修改培训计划
 */
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Review.php');
include(SET_DIR.'setup_hr_review.php');
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array('id'));
Power::CkPower('H8E');

$width='500px';

$Review = new Review();
$result = $Review->query($id);
$result['username'] = Cache::cache_username($result['hr_id']);

include T_DIR.'header.htm';
include T_DIR.'hr/hr_review_edit.htm';
include T_DIR.'footer.htm';
?>