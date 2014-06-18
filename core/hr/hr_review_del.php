<?php

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Review.php');
Power::CkPower('H8D');

GrepUtil::InitGP(array('id'));

$Review = new Review();
$Review->delete($id);
LogRW::logWriter('','体系管理人员删除');

Url::goto_url('index.php?m=hr&do=hr_review_list', '删除成功');

?>